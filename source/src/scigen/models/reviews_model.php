<?php

class reviews_model extends Base_Model{
	public function __construct(){
		parent::__construct();
	}

	public function registerReview($rev){
		$sqlString = "SELECT fn_addReview(:username, :doi, :reproducible, :review,
		:pvalue, :corr, :acc, :missing_param);";

		$stmt = $this->db->prepare($sqlString);

		foreach($rev as $key=>$value){
			if ($value === "") {
				$stmt->bindValue(":$key", NULL, PDO::PARAM_NULL);
			}
			else {
				$stmt->bindValue(":$key", "$value");
			}
		}
		
		$stmt->execute();

		$error = $stmt->fetchAll(PDO::FETCH_COLUMN);
		error_log("Review registration returned code ". $error[0]);

		return $this->db->lastInsertId();
	}

	public function updateReview($rev){
		$sqlString = "SELECT fn_editReview(:review_id, :username, :doi, :reproducible, :review,
		:pvalue, :corr, :acc, :missing_param);";

		$stmt = $this->db->prepare($sqlString);
		
		foreach($rev as $key=>$value){
			if ($value === "") {
				$stmt->bindValue(":$key", NULL, PDO::PARAM_NULL);
			}
			else {
				$stmt->bindValue(":$key", "$value");
			}
		}

		$stmt->execute();

		$error = $stmt->fetchAll(PDO::FETCH_COLUMN);
		error_log("Review edit returned code ". $error[0]);

		return $error[0];
	}

	public function getReviews($doi){
		$sqlQuery = "SELECT * FROM rep_view where DOI=:doi;";

		$stmt = $this->db->prepare($sqlQuery);

		$stmt->bindValue(":doi", "$doi");
		
		$stmt->execute();

		$reviews = [];
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$reviews[] = $row;
		}

		return $reviews;
	}

	public function getPaperInfo($doi){
		$sql = "SELECT title, authors, 
			journal, year, volume, issue, page,
			rep_success, rep_fail, tricky, possible, partial
			FROM papers WHERE doi=:doi";

		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":doi", "$doi");

		$stmt->execute();

		$paper = $stmt->fetch(PDO::FETCH_ASSOC);

		return $paper;
	}

	public function deleteReview($revId){
		$sql ="DELETE FROM reviews WHERE review_id=:review_id";

		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":review_id", $revId);

		$stmt->execute();
	}

	public function registerInterest($doi, $userId){
		$sql = "UPDATE reviews SET interested = concat(interested, :user_id, ' ') WHERE DOI=:doi;";
		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":doi", "$doi");
		$stmt->bindValue(":user_id", "$userId");

		$stmt->execute();
	}

}
?>
