<?php
class papers extends Base_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->loadModel("papers");
	}

	public function add(){
		if(isset($_POST['submit'])){
			unset ($_POST['submit']);
			$this->view->id = $this->model->addPaper($_POST);

			require_once('reviews.php');

			$ct_review = new Reviews();
			$_GET['doi'] = $_POST['doi'];
			$ct_review->get();
		}
		elseif(isset($_POST['correct'])){
			$this->view->autofetch = false;
			$this->set($_POST);
			$this->view->render('papers/add');
		}
		else {
			$this->view->render('papers/add');
		}
		
		
	}

	public function update(){
		session_start();
		if ($this->rejectBots()) return FALSE;
		if (!$_SESSION['loggedin']){ 
			echo "<script type='text/javascript'>alert('Unauthorized access: you are not logged in');</script>";
			header('Location: '.BASE_URL);
		}

		if (isset($_POST['submit'])){
			unset($_POST['submit']);
			$this->model->updatePaper($_POST);
			header('Location: '.BASE_URL.'reviews/get?doi='.$_POST['doi']);
		}
		else{
			$this->set($_POST);
			$this->view->render("papers/add");
			return;
		}
	}


	private function set($data){
		$this->view->doi = $data['doi'];
		$this->view->author = $data['author'];
		$this->view->title = $data['title'];
		$this->view->journal = $data['journal'];
		$this->view->year = $data['year'];
		$this->view->volume = $data['volume'];
		$this->view->issue = $data['issue'];
		$this->view->page = $data['page'];
	}
}

?>
