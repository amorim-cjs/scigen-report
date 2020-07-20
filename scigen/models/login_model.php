<?php

class Login_Model extends Base_Model{
	public function __construct(){
		parent::__construct();
	}

	public function login($username, $password, $doi=NULL){
		$usr = $this->db->prepare("SELECT user_id, username, password, email_status FROM users WHERE 
			username = :username ");
		$usr->execute(array(
			':username' => $username,
		));

		$data = $usr->fetch(PDO::FETCH_ASSOC);
		$hasData = $usr->rowCount();


		$pass_verified = password_verify($password, $data['password']);

		if ($pass_verified){
			//error_log("Info: Setting session");
			Session::set('loggedin', true);
			Session::set('username', $data['username']);
			Session::set('role', 'peer');
			Session::set('user_id', $data['user_id']);
			Session::set('email_status', $data['email_status']);
			$uri = BASE_URL;

			if (!is_null($doi)){
				header('Location:'.BASE_URL.'search/search?doi='.
				urlencode($doi));
			} else {
			header('Location:'.BASE_URL. $_GET['from'] . '?message='.
				urlencode('login successful'));
			}
		}
		else {
			error_log("User not found: " . $username . ":" . SHA1($password));
			header('Location:'. BASE_URL.
				'login/index?message='.urlencode('login failed') . "&from=" . $_GET['from']);
		}
	}
}
?>
