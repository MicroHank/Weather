<?php
	// API URL: https://opendata.cwb.gov.tw/dist/opendata-swagger.html#/
	// 氣候資訊平台授權碼
	define("AUTHORIZATION", "CWB-D27AD5BF-86AF-4C5E-96ED-4F371649BAAB") ;

	// 36小時預報
	define("API_WEATHER_36_HOURS", "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001") ;
	/*
	$field = array(
		"Authorization" => AUTHORIZATION,
		"locationName" => "臺中市",
	) ;
	*/

	// 監測每日下雨量: 有人氣象測站, 無人氣象測站
	define("API_RAIN_SITE_1", "https://opendata.cwb.gov.tw/api/v1/rest/datastore/C-B0074-001") ; // 有人
	define("API_RAIN_SITE_2", "https://opendata.cwb.gov.tw/api/v1/rest/datastore/C-B0074-002") ; // 無人
	/*
	$field = array(
		"Authorization" => AUTHORIZATION,
		"status" => "現存測站"
	) ;
	*/

	// 每日下雨量
	define("API_RAIN_SITE_DAY_VALUE", "https://opendata.cwb.gov.tw/api/v1/rest/datastore/C-B0025-001") ;
	/*
	$field = array(
		"Authorization" => AUTHORIZATION,
		"stationId" => "467490",
		"sort" => "",
		"timeFrom" => "2020-07-01",
		"timeTo" => "2020-07-08"
	) ;
	*/

	// 觀測站: 氣候與雨量, 兩個資料結構相同
	//define("API_WEATHER_SITE", "https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0001-001") ;
	//define("API_RAIN_SITE", "https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001") ;
	/*
	$field = array(
		"Authorization" => AUTHORIZATION,
	) ;
	*/

	ini_set("max_execution_time", 600) ;
	ini_set("memory_limit","1024M") ;

	date_default_timezone_set("Asia/Taipei") ;

    // 註冊錯誤處理器
    set_error_handler(
	    function ($severity, $message, $file, $line) {
	        throw new \ErrorException($message, $severity, $severity, $file, $line) ;
	    }
	) ;

	function my_error_handler($params) {
		throw new \ErrorException($params["error"]." (".$params["query"].")", 1) ;
	}
?>