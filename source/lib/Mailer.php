<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends PHPMailer{
	public function __construct($param){
		parent::__construct($param);
		// Basic configuration
		$this->isSMTP();
		$this->Username = "A";
		$this->Password = "BBB";
		$this->Host = "smtp.host.com";

		// Security
		$this->SMTPAuth = true;
		$this->SMTPSecure = 'tls';
		$this->Port = 587;

		// Format
		$this->isHTML(true);

	}
}
?>
