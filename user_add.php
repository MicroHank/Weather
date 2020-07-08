<?php
	// http://127.0.0.1/practice/user_add.php?account=12347&user_name=henwen&password1=1234&password2=123&email=henwen@gmail.com&url=https://www.google.com
	include "vendor/autoload.php" ;

	use Henwen\Model\User ;
	use Henwen\Model\UserInformation ;
	use Henwen\Validate\FilterVariable ;

	$user = new User() ;
	$user_info = new UserInformation() ;
	$filter = new FilterVariable() ;

	// filter input
	$account = isset($_GET["account"]) ? $filter->getString($_GET["account"]) : "" ;
	$password1 = isset($_GET["password1"]) ? $filter->getString($_GET["password1"]) : "" ;
	$password2 = isset($_GET["password2"]) ? $filter->getString($_GET["password2"]) : "" ;
	$user_name = isset($_GET["user_name"]) ? $filter->getString($_GET["user_name"]) : "" ;
	$email = isset($_GET["email"]) ? $filter->getEmail($_GET["email"]) : "" ;
	$url = isset($_GET["url"]) ? $filter->getUrl($_GET["url"]) : "" ;

	// 檢驗使用者, 密碼
	try {
		if ($user->getUserByAccount($account)) {
			throw new \Exception("User exists.\r\n", 1) ;
		}

		if ($password1 !== $password2) {
			throw new \Exception("Password are not equal.\r\n", 1) ;
		}
		$password_hash = password_hash($password2, PASSWORD_BCRYPT, ["cost" => User::HASHCOST]) ;

		// save user & user_information
		$user->account = $account ;
		$user->password = $password_hash ;
		$user->user_name = $user_name ;
		$user->save() ;

		$user_info->user_id = $user->getLastInsertID() ;
		$user_info->email = $email ;
		$user_info->url = $url ;
		$user_info->save() ;

	} catch (\Exception $e) {
		echo $e->getMessage()."\r\n" ;
		echo "Add User: Fail \r\n" ;
	}
