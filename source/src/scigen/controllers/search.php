<?php
class Search extends Base_Controller{
	public function __construct(){
		parent::__construct();
		$this->loadModel('search');
	}

	public function index(){
		$this->view->render('search/index');
	}

	public function validateAccess($token = NULL){
		session_start();
		$_SESSION['likelyHuman'] = FALSE;
		if (empty($token) || is_null($token))
			return FALSE;

		$this->captcher->validateV3($token);
	}

	// Search function
	public function search(){
		session_start();

		// Block bots
		if ($this->rejectBots()) return FALSE;
		
		// Start search
		$doi = trim($_GET['doi']);
		if (empty($doi)){
			$this->index();
			return;
		}
		$_SESSION['doi'] = $doi;
		$paper = $this->model->lookupDB($doi);

		// If not in DB, curl doi.org
		if (is_null($paper)){
			$metadata = $this->fetch_metadata($doi);

			if ($metadata){
			require_once('papers.php');
			$ct_papers = new Papers;
			$paper = $this->parse_json($metadata, $ct_papers);
			$ct_papers->model->addPaper($paper);
			}
			else {
				$this->view->found = false;
				$this->view->render('search/wait');

				return;
			}
			
		}

		header("Location: " . BASE_URL . "reviews/get?doi=" . $doi);

	}

	private function fetch_metadata($doi){

		echo "<label>Paper not in the database. Searching the Web...</label> <br>";

		$url = "https://data.datacite.org/application/vnd.citationstyles.csl+json/" . $doi;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$metadata = curl_exec($ch);
		
		// split response
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		// check response
		if ($http_code == 204){
			$status = "Metadata unavailable for this object.";
			echo $status;
			return false;
		}
		elseif ($http_code != 200){
			echo "Couldn't retrieve object. Check the DOI inserted is correct.";
			return false;
		} 
		
		return $metadata;
	}

	private function parse_json($json, $controller){
		$data = json_decode($json);

		$controller->view->doi = $data->DOI;
		$controller->view->title = $data->title;
		$controller->view->journal = 
			is_array($data->{'container-title'}) ? $data->{'container-title'}[0] : $data->{'container-title'};
		$controller->view->volume = $data->volume;
		$controller->view->issue = $data->issue;
		$controller->view->page = $data->page;

		$controller->view->year = $data->issued->{'date-parts'}[0][0];

		$authors = "";
		foreach ($data->author as $author) {
			$authors .= $author->given . " " . strtoupper($author->family) . " & ";
		}

// reduce if too large 
		if (strlen($authors) > 258){
			$authors = $data->author[0]->given." ".strtoupper($data->author[0]->family)." et al.";
		}
		else{
			$authors = substr($authors, 0, strlen($authors) - 3);
		}
		$controller->view->author = $authors;

		$controller->view->autofetch = true;

		$paper = array(
			"doi" => $controller->view->doi,
			"title" => $controller->view->title,
			"journal" => $controller->view->journal == "(:unav)" ? "" : $controller->view->journal,
			"volume" => is_null($controller->view->volume) ? 0 : $controller->view->volume,                                         "issue" => is_null($controller->view->issue) ? 0 :  $controller->view->issue,
			"page" => is_null($controller->view->page) ? 0 :  $controller->view->page,
			"year" => is_null($controller->view->year) ? 0 :  $controller->view->year,
			"author" => $controller->view->author
		);				
		return $paper;

	}

	// For tests only.
	private function showmeta($doi1 = "", $doi2 = ""){
		$doi = "";
		if (!empty($doi1) && !empty($doi2)){
			$doi = $doi1 . '/' . $doi2;
		} 
		elseif (isset($_GET['doi'])){
			$doi = $_GET['doi'];
		}
		else return;

		$data = $this->fetch_metadata($doi);

		var_dump($data);

		require_once('papers.php');
		$ct_papers = new Papers;
		$paper = $this->parse_json($data, $ct_papers);

		echo "parsed:".PHP_EOL;
		var_dump($paper);
	}

}
?>
