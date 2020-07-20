<?php
abstract class Base_Model{
	public function __construct(){
		$this->db = new Database(DB_VENDOR, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PWD);
	}
}
?>
