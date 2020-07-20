<?php
class Login extends Base_Controller{
	function __construct(){
		parent::__construct();
		Session::init();
		$this->loadModel('login');
	}

	function index(){
		$username = Session::get('username');
		$this->view->username = $username ? $username : '';
		$this->view->message = isset($_GET['message']) ? $_GET['message'] : '';
		$this->view->render('login/index');
	}

	function runLogout(){
		Session::destroy();
		header('Location: ' . $_SERVER['HTTP_REFERER']);
	}

	function runLogin(){
		session_start();
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		$doi = $_POST['doi'] !== "" ? $_POST['doi'] : $_SESSION['doi'];
		
		$this->model->login($username, $password, $doi !== "" ? $doi : NULL);

	}
}

?>
