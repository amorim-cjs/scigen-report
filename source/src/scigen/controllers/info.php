<?php
class Info extends Base_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function about(){
		$this->view->render("info/about");
	}
	
	public function reproducibility(){
		$this->view->render("info/repro");
	}
	
	public function privacy(){
		$this->view->render("info/privacy");
	}

	public function terms(){
		$this->view->render("info/terms");
	}

	public function contact(){
		$this->view->render("info/contact");
	}
		
}
?>
