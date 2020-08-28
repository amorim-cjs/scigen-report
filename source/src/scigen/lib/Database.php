<?php
class Database extends PDO{
	public function __construct($DB_VENDOR, $DB_HOST, $DB_PORT, $DB_NAME, $DB_USER, $DB_PWD){
		parent::__construct($DB_VENDOR . ':host=' . $DB_HOST . 
				';port='. $DB_PORT.';dbname=' . $DB_NAME, $DB_USER, $DB_PWD);
	}
}
?>
