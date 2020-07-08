<?php
	namespace Henwen\model ;

	/**
	 * Table: user
	 *
	 */
	class User
	{
		const HASHCOST = 12 ;

		public $user_id ;
		public $account ;
		public $password ;
		public $user_name ;
		public $supervisor_id ;

		private $last_insert_id ;

		public function __construct()
		{
			$this->last_insert_id = 0 ;			
		}

		public function login()
		{
			// 取得使用者 by account
			$user = \DB::queryFirstRow("SELECT * FROM user WHERE account = %s", $this->account) ;

			if (! empty($user)) {
				// 檢驗密碼, 通過則儲存 user 資訊
				if (password_verify($this->password, $user["password"])) {
					$this->setUser($user) ;
					return true ;
				}
			}
			else {
				return false ;
			}
		}

		public function save()
		{
			try {
				\DB::insert("user", 
					array(
						"account" => $this->account,
						"password" => $this->password,
						"user_name" => $this->user_name
					)
				) ;

				$this->last_insert_id = \DB::insertId() ;
			} catch (\Exception $e) {
				echo $e->getMessage() ;
			}
		}

		public function remove()
		{
			\DB::delete("user",
				array(
					"user_id" => $this->user_id
				)
			) ;
		}

		public function all()
		{
			return \DB::query("SELECT * FROM user") ;
		}

		public function getUserByAccount($account = "")
		{
			$user = \DB::query("SELECT * FROM user WHERE account = %s", $account) ;
			return ! empty($user) ? $user : "" ;
		}

		public function setUser($user = array())
		{
			$this->user_id = (int) $user["user_id"] ;
			$this->user_name = $user["user_name"] ;
			$this->supervisor_id = $user["supervisor_id"] ;
		}

		public function getLastInsertID()
		{
			return $this->last_insert_id ? $this->last_insert_id : 0 ;
		}

		public function setUserByID($user_id = 0)
		{
			$user = \DB::query("SELECT * FROM user WHERE user_id = %d", $user_id) ;
			if (! empty($user)) {
				$this->setUser($user) ;
			}
		}
	}