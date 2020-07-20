<?php
class Bootstrap{
	public function __construct(){

		$url = $_GET['url'];
		$url = explode("/", $url);

		// Controlller
		if (empty($url[0])){
			require_once("scigen/controllers/search.php");
			(new Search())->index();
			return false;
		}
		$file_name = "scigen/controllers/" . $url[0] . ".php";

		if (!file_exists($file_name)){
			// redirect
			error_log($file_name . " needed for Bootstrap doesn't exist or is inaccessible.");
			echo "File not found.";
			return false;
		}

		require_once($file_name);
		$ct_name = ucfirst($url[0]);
		$controller = new $ct_name;

		// Action
		if (empty($url[1])){
			$controller->get();
			return false;
		}

		$action_name = isset($url[1]) ? $url[1] : NULL;

		if ($action_name && method_exists($controller, $action_name)){
			if (empty($url[2])){
				$controller->{$url[1]}();
			}
			elseif (empty($url[3])) {
				$controller->{$url[1]}($url[2]);
			}
			else{
				$controller->{$url[1]}($url[2],$url[3]);
			}
		}
		else {
			echo "Action does not exist.";
		}
	
	}
}
?>
