<?php
	// https://opendata.cwb.gov.tw/api/v1/rest/datastore/C-B0025-001
	include "vendor/autoload.php" ;

	$curlobj = curl_init() ;
	curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true) ;
	curl_setopt($curlobj, CURLOPT_SSL_VERIFYPEER, false) ;
	curl_setopt($curlobj, CURLOPT_SSL_VERIFYHOST, false) ;

	/*
	$field = array(
		"Authorization" => AUTHORIZATION,
		"stationId" => "467490",
		"sort" => "",
		"timeFrom" => "2020-07-01",
		"timeTo" => "2020-07-08"
	) ;
	curl_setopt($curlobj, CURLOPT_URL, API_DAY_RAIN."?".http_build_query($field)) ;
	$json = curl_exec($curlobj) ;
	*/

	$field = array(
		"Authorization" => AUTHORIZATION,
		"locationName" => "臺中市",
	) ;
	curl_setopt($curlobj, CURLOPT_URL, API_WEATHER_36_HOURS."?".http_build_query($field)) ;
	$json = curl_exec($curlobj) ;

	if ($json) {
		$weather = json_decode($json, true) ;

		$city = $weather["records"]["location"][0]["locationName"] ;

		foreach ($weather["records"]["location"][0]["weatherElement"] as $obj) {
			// 天氣狀況
			if ($obj["elementName"] === "Wx") {
				$wx = $obj["time"][0]["parameter"]["parameterName"] ; // 多雲
				$start_time = $obj["time"][0]["startTime"] ; // 2020-07-08 12:00:00
				$end_time = $obj["time"][0]["endTime"] ; // 2020-07-08 18:00:00
			}

			// 最低溫度
			if ($obj["elementName"] === "MinT") {
				$min_t = $obj["time"][0]["parameter"]["parameterName"].$obj["time"][0]["parameter"]["parameterUnit"] ; // 32C
			}

			// 最高溫度
			if ($obj["elementName"] === "MaxT") {
				$max_t = $obj["time"][0]["parameter"]["parameterName"].$obj["time"][0]["parameter"]["parameterUnit"] ; // 34C
			}

			// 體感
			if ($obj["elementName"] === "CI") {
				$ci = $obj["time"][0]["parameter"]["parameterName"] ; // 悶熱、舒適
			}
		}

		echo "城市: $city <br />" ;
		echo "開始時間: $start_time <br />" ;
		echo "結束時間: $end_time <br />" ;
		echo "氣候狀態: $wx <br />" ;
		echo "溫度: $min_t ~ $max_t <br />" ;
		echo "體感: $ci <br />" ;
	}

	