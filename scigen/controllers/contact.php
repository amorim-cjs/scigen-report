<?php
class Contact extends Base_Controller{
	public function __construct(){
		parent::__construct();
	}

	public function get(){
		$this->view->render("contact/form");
	}

	public function confirm(){
		session_start();

		if ($this->rejectBots("Spam-like activity detected")) return FALSE;

		$_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		$_POST['email'] = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		if ($_POST['email'] === false){
			echo "invalid email address";
			$this->view->render("contact/form");
			return false;
		}
		$this->view->render("contact/confirm");
	}

	public function submit(){
		session_start();

		if ($this->rejectBots("Spam-like activity detected")) return FALSE;
		
		// Allow correction
		if (isset($_POST['correct'])){
			unset($_POST['correct']);
			$this->view->render("contact/form");
			return;
		}
		
		$mailer = new Mailer(true);

		try {
		$email = $_POST['sortAs'] . '@scigen.report';

		$mailer->setFrom('form@scigen.report', 'SciGen.Report - Contact');
		$mailer->addAddress($email, $_POST['sortAs']);	
		$mailer->Subject = '['.$_POST['sortAs'].']'.$_POST['subject'];

		$mailer->Body = "FROM: " . $_POST['email'] . '(' . $_POST['name'] . ')' . PHP_EOL
			.htmlspecialchars($_POST['message']);

		$mailer->send();

		} catch (phpmailerException $e){
			echo "An error occurred while sending your email.", PHP_EOL;
			echo $e->errorMessage();
			$this->view->render("contact/confirm");
			return false;
		} catch (Exception $e) {
			echo "An error occurred while sending your email. Please, try again later.";
			$this->view->render("contact/confirm");
			return false;
		}

		if ($_POST['sortAs'] == 'feedback') $this->view->render("contact/feedback");
		else				    $this->view->render("contact/thankyou");
		
		return;
		
	}
		
}
?>
