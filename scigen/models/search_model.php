<?php
class Search_Model extends Base_Model{
	public function __construct(){
		parent::__construct();
	}

	public function lookupDB($doi){
		$sql = "SELECT * FROM papers WHERE DOI=:doi";

		$stmt = $this->db->prepare($sql);

		$stmt->bindValue(":doi", "$doi");

		$stmt->execute();

		$paper = $stmt->fetch(PDO::FETCH_ASSOC);

		return $paper ? $paper : NULL;
	}

	public function getRandomWord(){
		$max_id = $this->db->query("select AUTO_INCREMENT from INFORMATION_SCHEMA.TABLES where TABLE_NAME='words';")
			->fetchAll(PDO::FETCH_COLUMN)[0];

		$rand_id = rand(1, $max_id -1);

		$word = $this->db->query("SELECT word FROM words WHERE word_id=".$rand_id.";")
			->fetchAll(PDO::FETCH_COLUMN)[0];

		return $word;

	}

	public function addWords($title){
		$words = explode(" ", $title);

		// Check if is in DB, add if not
		foreach ($words as $word){
			$count = $this->db->query("SELECT COUNT(word_id) FROM words WHERE word=' \"".$word.'";')
				->fetchAll(PDO::FETCH_COLUMN)[0];

			if ($count == 0){
				// Add to DB
				$this->db->query("INSERT INTO words (word) VALUES (' \"" . $word . '");');
			}
		}
	}
}
?>
