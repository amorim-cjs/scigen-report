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

}
?>
