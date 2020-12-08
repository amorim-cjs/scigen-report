<?php
class Papers_Model extends Base_Model{
	public function __construct(){
		parent::__construct();
	}

	public function addPaper($paper){
		ksort($paper);

		$sqlString = "SELECT fn_addPaper(:doi, :title, :author,
		:journal, :year, :volume, :issue, :page);";

		$stmt = $this->db->prepare($sqlString);

		foreach($paper as $key=>$value){
			$stmt->bindValue(":$key", "$value");
		}

		$stmt->execute();

		$error = $stmt->fetchAll(PDO::FETCH_COLUMN);

		error_log("Paper addition return error code: ".$error[0]);

		return $this->db->lastInsertId();
	}

	public function updatePaper($paper){
		$sqlString = "SELECT fn_updatePaper(:doi, :title, :author, :journal, :year, :volume, :issue, :page);";

		$stmt = $this->db->prepare($sqlString);

		foreach($paper as $key=>$value){
			$val = trim($value);
			$stmt->bindValue(":$key", "$val");
		}

		$stmt->execute();
		$error = $stmt->fetchAll(PDO::FETCH_COLUMN);
		error_log("Paper update return error code: ".$error[0]);
		error_log(print_r($error,true));
		error_log(print_r($paper, true));


	}

	public function getPapers(){
		return $this->db->query("SELECT paper_id, DOI, authors, title FROM papers;")
			->fetchAll(PDO::FETCH_ASSOC);
	}

	public function registerInterest($doi, $userId){
		// Check current interest
		$interestedFlag = $this->isInterested($doi, $userId);

		if ($interestedFlag) return true;

		//Register interest in DB
		$sql = "UPDATE papers SET interested = concat(interested, :user_id, ',') WHERE DOI=:doi;";
		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":doi", "$doi");
		$stmt->bindValue(":user_id", "$userId");

		$stmt->execute();
	}

	public function unregisterInterest($doi, $userId){
		// Get interested users' list
		$interestedUsers = $this->fetchInterest($doi);
		$interestArray = explode(',', $interestedUsers);

		$newInterestedArray = array_diff($interestArray, array($userId));
		$newInterested = implode(',', $newInterestedArray);

		// Send to DB
		$sql = "UPDATE papers SET interested = :interested WHERE DOI=:doi;";
		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":doi", "$doi");
		$stmt->bindValue(":interested", "$newInterested");

		$stmt->execute();

	}

	public function fetchInterest($doi){
		$sql = "SELECT interested FROM papers WHERE DOI=:doi;";
		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":doi", "$doi");

		$stmt->execute();

		$interested = $stmt->fetchAll(PDO::FETCH_COLUMN);

		return $interested[0];
	}

	public function isInterested($doi, $userId){
		// Check current interest
		$interestedUsers = $this->fetchInterest($doi);
		$interestArray = explode(',', $interestedUsers);

		$interestedFlag = in_array($userId, $interestArray);

		return $interestedFlag;
	}

	private function countInterested($doi){
		$interestedUsers = $this->fetchInterest($doi);
		$interestArray = explode(',', $interestedUsers);

		return count($interestArray) - 1;
	}
}
?>
