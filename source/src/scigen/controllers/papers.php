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

	public function upvote($doi){
		session_start();

		if (!Session::get('loggedin')){
			// Save current DOI
			$_SESSION['doi'] = $_GET['doi'];

			header("Location: ". BASE_URL . "login/index?message=" . urlencode("You must be loggedin to submit a report"));

			return 'Error: ' . USER_NOT_LOGGED_IN;
		}
		elseif (isset($_GET['doi'])){
			//$doi = $_GET['doi'];
			$userId = Session::get('user_id');
			$this->model->registerInterest($doi, $userId);

			//header("Location: ". BASE_URL . "reviews/get?doi=" . urlencode($doi)); 
			return true;
		}
	}

	public function downvote($doi){
		session_start();

		if (!Session::get('loggedin')){
			// Save current DOI
			$_SESSION['doi'] = $_GET['doi'];

			header("Location: ". BASE_URL . "login/index?message=" . urlencode("You must be loggedin to submit a report"));

			return 'Error: ' . USER_NOT_LOGGED_IN;
		}
		elseif (isset($_GET['doi'])){
			//$doi = $_GET['doi'];
			$userId = Session::get('user_id');
			$this->model->unregisterInterest($doi, $userId);

			//header("Location: ". BASE_URL . "reviews/get?doi=" . urlencode($doi)); 
			return false;
		}
	}

	public function switchVote(){
		session_start();

		if (Session::get('loggedin') && isset($_GET['doi'])){
			$userId = Session::get('user_id');
			$doi = $_GET['doi'];

			//var_dump($userId);
			//$interests = $this->countvote($doi, $userId);
			
			$interested = $this->model->isInterested($doi, $userId);//$interests[1];

			if ($interested) $interested = $this->downvote($doi);
			else $interested =  $this->upvote($doi);

			$interests = $this->countvote($doi, $userId);
			
			$response = [ 'count' => $interests[0], 'interest' => $interests[1] ];
			http_response_code(200);
			header('Content-type: application/json');
			//var_dump($interests);
			echo json_encode($response);
		}
		else{
			http_response_code(401);
			echo "invalid request";
		} 

	}

	public function countvote($doi, $userId = NULL){
		$interested = $this->model->fetchInterest($doi);
		if (empty($interested)) return array(0, false);
		
		$interestArray = explode(',', $interested);

		$votes = count($interestArray) - 1;

		if (!is_null($userId)){
			$interestFlag = in_array($userId, $interestArray);
			return array($votes, $interestFlag);
		}

		return array($votes, false);
	}

}

?>
