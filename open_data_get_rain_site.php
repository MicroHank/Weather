<?php
	// 氣候 -> 氣象測站基本資料, 有人與無人
	include "vendor/autoload.php" ;

	$curlobj = curl_init() ;
	curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true) ;
	curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false) ;
	curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, false) ;

	$field = array(
		"Authorization" => AUTHORIZATION,
		"status" => "現存測站"
	) ;
	curl_setopt($curlobj, CURLOPT_URL, API_RAIN_SITE_2."?".http_build_query($field)) ;
	$json = curl_exec($curlobj) ;

	if ($json) {
		$site = json_decode($json, true) ;

		foreach ($site["records"]["data"]["stationStatus"]["station"] as $obj) {
			$tmp = array(
				"station_id" => isset($obj["stationID"]) ? $obj["stationID"] : "",
				"station_name" => isset($obj["stationName"]) ? $obj["stationName"] : "",
				"lat" => isset($obj["latitude"]) ? $obj["latitude"] : "",
				"lon" => isset($obj["longitude"]) ? $obj["longitude"] : "",
				"county_name" => isset($obj["countyName"]) ? $obj["countyName"] : "",
				"address" => isset($obj["stationAddress"]) ? $obj["stationAddress"] : "",
				"type" => 2
			) ;
			\DB::insertIgnore("station", $tmp) ;
		}
	}