<?php
	namespace Henwen\model ;

	/**
	 * Table: user_information
	 *
	 */
	class UserInformation
	{
		public $user_id ;
		public $email ;
		public $url ;

		private $last_insert_id ;

		public function __construct()
		{
			$this->last_insert_id = 0 ;			
		}

		public function save()
		{
			try {
				\DB::insert("user_information", 
					array(
						"user_id" => $this->user_id,
						"email" => $this->email,
						"url" => $this->url
					)
				) ;

				$this->last_insert_id = \DB::insertId() ;
			} catch (\Exception $e) {
				echo $e->getMessage() ;
			}
		}

		public function remove()
		{
			\DB::delete("user_information",
				array(
					"user_id" => $this->user_id
				)
			) ;
		}

		public function all()
		{
			return \DB::query("SELECT * FROM user_information") ;
		}

		public function getLastInsertID()
		{
			return $this->last_insert_id ? $this->last_insert_id : 0 ;
		}

		public function getUserByID($user_id = 0)
		{
			return \DB::query("SELECT * FROM user_information WHERE user_id = %d", $user_id) ;
		}
	}