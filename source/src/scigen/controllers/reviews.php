<?php
class Reviews extends Base_Controller{
	public function __construct(){
		$this->view = new Reviews_view();
		$this->loadModel("reviews");
	}

	public function register(){
		session_start();

		if ($this->rejectBots()) return FALSE;

		if (!Session::get('loggedin')){
			// Save current DOI
			$_SESSION['doi'] = $_POST['doi'];

			header("Location: ". BASE_URL . "login/index?message=" . urlencode("You must be loggedin to submit a report"));

			return;
		}
		elseif (isset($_SESSION['doi'])){
			$_POST['doi'] = $_SESSION['doi'];
			unset($_SESSION['doi']);
		}


		if (isset($_POST['submit']) && $_SESSION['email_status'] == 'verified'){
			unset($_POST['submit']);
			$_POST['data_code_link'] = $this->cleanInput($_POST['data_code_link']);
			$this->view->id = $this->model->registerReview($_POST);
		}
		elseif (isset($_POST['edit'])){
			unset($_POST['edit']);
			unset($_POST['exist']);

			$error = $this->model->updateReview($_POST);

			if ($error == 0) header('Location: ' . BASE_URL . 'reviews/get?doi=' . $_POST['doi']);
			http_code_response(200);
			return;
		}
		elseif (isset($_POST['correct'])){
			unset($_POST['correct']);
		}
		elseif (isset($_POST['delete'])){
			$this->model->deleteReview($_POST['review_id']);
			header('Location: ' . BASE_URL . 'reviews/get?doi=' . $_POST['doi']);
			http_response_code(204);
		}
		elseif (isset($_POST['cancel'])){
			header('Location: ' . BASE_URL . 'reviews/get?doi=' . $_POST['doi']);
			return;
		}

		$role = Session::get('role');
		if ($role == 'peer'){
			$this->view->user_id = Session::get('user_id');
		}

		$this->view->role = $role ? $role :'';
		$this->view->exist = isset($_POST['exist']) ? $_POST['exist'] : NULL;
		$this->view->render('reviews/register');
	}

	public function confirm(){
		session_start();

		if ($this->rejectBots()) return FALSE;
		
		if (isset($_POST['confirm'])){
			unset($_POST['confirm']);
		}

		if (!isset($_POST['reproducible'])){
			$this->view->message = "Please, inform whether you could reproduce this paper's result or not.<br>";
			$this->view->render('reviews/register');
		}
		else {
			$this->view->render('reviews/confirm');
		}
	}

	public function get($doi0=NULL, $doi1=NULL){
		session_start();

		if (!is_null($doi0) && !is_null($doi1)) $_GET['doi'] = $doi0."/".$doi1;
		if (isset($_GET['doi']) && !empty($_GET['doi'])){
			$this->view->doi = $_GET['doi'];
			$this->view->reviews_data = 
				$this->model->getReviews($_GET['doi']);

			$this->view->paper_data =
				$this->model->getPaperInfo($_GET['doi']);

			if (Session::get('loggedin')){
				$this->view->upvoted = $this->isInterested($this->view->paper_data['interested'],$_SESSION['user_id']);
			}

			$this->view->render('reviews/get');
		}
		else{
			header('Location: ' . BASE_URL);
		}
		
	}

	private function isInterested($interestString, $userId){
		$interestArray = explode(',', $interestString);
		return in_array($userId, $interestArray);
	}
	
	private function cleanInput($input){
		$inputArray = explode(" ", $input);
		return implode(',', $inputArray);
	}
}

?>
