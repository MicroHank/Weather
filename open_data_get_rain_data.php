<?php
	include "vendor/autoload.php" ;

	use Henwen\Date\DateLib ;

	// 台中: 467770(梧棲), 467490(北區)
	$station_id = "467770" ;

	$today = new DateLib() ;
	$time_to = $today->getDateString() ; // Today
	
	$today->shiftDate("sub", 6, "month") ;
	$time_from = $today->getDateString() ; // 1 month ago
	
	$curlobj = curl_init() ;
	curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true) ;
	curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false) ;
	curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, false) ;

	$field = array(
		"Authorization" => AUTHORIZATION,
		"stationId" => $station_id,
		"sort" => "",
		"timeFrom" => $time_from,
		"timeTo" => $time_to
	) ;
	curl_setopt($curlobj, CURLOPT_URL, API_RAIN_SITE_DAY_VALUE."?".http_build_query($field)) ;
	$json = curl_exec($curlobj) ;

	if ($json) {
		$weather = json_decode($json, true) ;
		$precipitation = $weather["records"]["location"][0]["stationObsTimes"]["stationObsTime"] ;
		
		$date_rain = array() ;
		foreach ($precipitation as $obj) {
			$date_rain = array(
				"station_id" => $station_id,
				"date" => $obj["dataDate"],
				"value" => $obj["weatherElements"]["precipitation"]
			) ;
			\DB::insertIgnore("station_rain", $date_rain) ;
		}
	}

