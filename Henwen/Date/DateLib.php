<?php
	namespace Henwen\Date ;

	class DateLib extends \DateTime {

		// 時區設定
		private $timezone = "Asia/Taipei" ;

		// 時間增減方法
		private $shift_method = array("add", "sub") ;

		// 時間增減單位對照
		private $shift_unit = array(
			"year"  => "years",
			"month" => "months",
			"day" 	=> "days",
			"hour"  => "hours",
			"minute"=> "minutes",
			"second"=> "seconds",
		) ;

		public function __construct($string = "today", $timezone = "Asia/Taipei") 
		{
			$this->timezone = $timezone ;
			return parent::__construct($string, new \DateTimeZone($timezone)) ;
		}

		/**
		 * getTimeString():
		 *
		 * 利用 \DateTime 取得時間字串
		 * @param: $string String, 時間字串 e.g. Ymd, Y-m-d H:i:s
		 * @return String: e.g. 20190523, 2019-05-23, 2019-05-23 10:20:30
		 */
		public function getTimeString($string = "Y-m-d H:i:s")
		{
			return $this->format($string) ;
		}

		/**
		 * getDateString():
		 *
		 * 取得時間物件的日期 Y-m-d
		 * @return String: e.g. 2019-05-23
		 */
		public function getDateString() 
		{
			return $this->getTimestring("Y-m-d") ;
		}

		/**
		 * getDateTimestamp():
		 *
		 * 利用 \DateTime 取得指定日期的 unix timestamp
		 * @param: $date String, e.g. Y-m-d
		 * @return Int: e.g. 1558540800
		 */
		public function getDateTimestamp($date = "")
		{
			if (empty(trim($date))) {
				$date = $this->getDateString() ;
			}
			if (! preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/", $date)) {
				throw new \Exception("Invalid Date Format: input 'Y-m-d'.") ;
			}
			return parent::createFromFormat("Y-m-d H:i:s", "$date 00:00:00", new \DateTimeZone($this->timezone))->getTimestamp() ;
		}

		/**
		 * shiftDate():
		 *
		 * 利用 \DateTime 的 add, sub 方法處理日期的增減, 可增減 年月日時分秒
		 * @param: $shift_method String, add | sub
		 * @param: $num INT, 數值, e.g. 3 (年|月|日|時|分|秒)
		 * @param: $shift_unit String, 增減單位, [year|month|day|hour|minute|second]
		 * @return void: 時間物件的時間移動
		 */
		public function shiftDate($shift_method = "add", $num = 1, $shift_unit = "day")
		{
			// 消毒: 去除數字以外的字元
			$num = filter_var($num, FILTER_SANITIZE_NUMBER_INT) ;
			// 檢驗為數值則返回數值 INT
			$num = filter_var($num, FILTER_VALIDATE_INT) ;

			if (! is_int($num)) {
				throw new \Exception("Interval Days Invalid Format: Integer") ;
			}
			if (! in_array($shift_method, $this->shift_method)) {
				throw new \Exception("Invalid Shift Method: add, sub") ;
			}
			if (! isset($this->shift_unit[$shift_unit])) {
				throw new \Exception("Invalid Shift Unit: day, month, year, hour, minute, second") ;
			}
			$this->{$shift_method}(\DateInterval::createFromDateString("$num ". $this->shift_unit[$shift_unit])) ;
		}

		/**
		 * diffFromDate():
		 *
		 * 計算與另一個時間物件的時間距離
		 * @param: \DateLib $date, 指定的日期時間物件
		 * @return String: 距離指定日期時間剩餘多久
		 */
		public function diffFromDate($date)
		{
			if (! $date instanceof \DateLib) {
				throw new \Exception("Invalid Object: need an instance of class DateLib.") ;
			}
			return $this->diff($date)->format(" %y years, %m months, %d days, %h hours, %i minutes, %s seconds.") ;
		}

		/**
		 * remainFromToday():
		 *
		 * 計算剩餘日期/時間
		 * @param: MIX $date, 指定的日期時間字串或 DateLib 物件
		 * @return String: 距離指定日期時間剩餘多久
		 */
		public function remainFromToday($date = "")
		{
			// 僅接受日期時間字串 或 DateLib 物件
			if (is_string($date)) {
				$date = new DateLib($date, $this->timezone) ;
			} else if (! $date instanceof \DateLib) {
				throw new \Exception("Invalid Type: DateLib Object or Date String") ;
			} 
			// 日期必須在 $this 物件的時間之後
			if ($this >= $date) {
				throw new \Exception("Invalid Date Range: Date must be greater than today.") ;
			}

			return $this->diffFromDate($date) ;
		}

		/**
		 * getDiffDays():
		 *
		 * 計算與另一個時間物件的時間距離多少天, 以 日 至 日 計算
		 * @param: MIX $date, 指定的日期時間字串或 DateLib 物件
		 * @return String: 距離指定日期時間剩餘多久
		 */
		public function getDiffDays($date = "")
		{
			if (is_string($date)) {
				$date = new DateLib($date, $this->timezone) ;
			} else if (! $date instanceof \DateLib) {
				throw new \Exception("Invalid Type: DateLib Object or Date String") ;
			}

			// 比較時間先後
			if ($this >= $date) {
				return intval($date->diff($this)->format("%a")) ;
			} else if ($this < $date) {
				return intval($this->diff($date)->format("%a")) ;
			}
		}
	}