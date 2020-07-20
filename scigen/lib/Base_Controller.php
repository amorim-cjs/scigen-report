<?php
abstract class Base_Controller{

	public function __construct(){
		$this->view = new Base_View();
		$this->captcher = new Captcher();
	}

	public function loadModel($name){
		$path = 'scigen/models/' . $name . '_model.php';

		if (file_exists($path)){
			require_once($path);

			$modelName = ucfirst($name) . "_Model";
			$this->model = new $modelName();
		}
	}

	protected function rejectBots($msg = "Bot behavior detected"){
		session_start();

		if (!$_SESSION['likelyHuman']){
			echo $msg;
			return TRUE;
		}
	}
}

?>
