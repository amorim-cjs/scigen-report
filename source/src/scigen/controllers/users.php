<?php
class Users extends Base_Controller{
	public function __construct(){
		parent::__construct();
		$this->loadModel("users");
	}

	public function add(){
		// keep track of origin page before login
		$this->view->from = $_POST['from'] ? $_POST['from'] : $_GET['from'];

		if (empty($_POST)){
			$this->view->render("users/add");
			return;
		}

		$captchaValid = isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']) ?
					$this->captcher->validateV2($_POST['g-recaptcha-response']) :
					false;

		if (!$captchaValid){
			echo "Couldn't validate captcha.";
			$this->view->render("users/add");
			return;
		}

		unset($_POST['g-recaptcha-response']);

		$user = $this->sanitize($_POST);

		unset($user['from']);

		$validation = $this->validate($user);

		$valid = true;
		foreach($validation as $entry_check){
			$valid = $valid && $entry_check;
		}
		
		$password = $user['password'];

		if ($valid && isset($_POST['submit'])){
			unset($user['submit']);
			unset($user['agree']);
				
			$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT); // real algorithm omitted
			unset($user['password2']);

		}

        // For Sprint: dropped hash column
		//$user['hash'] = $this->generateHash();

		$this->view->id = $valid ? $this->model->addUser($user) : NULL;

		// Case success:
		if ($this->view->id > 0){
            $sent = TRUE; /* $this->mailValidation($user['email'], 
				$user['given_name'].' '.$user['family_name'],
				$this->view->id, 
				$user['hash']);*/


			$parsed = parse_url($_POST['from']);
			$new_url = 'https://' . $parsed['host'] . $parsed['path']. "?"
			 . ($parsed['query'] ? $parsed['query'] . "&" : "" ) . 'message=' . urlencode("User signup successful");

			$this->view->return_url = $new_url;

			if ($sent) $this->view->render("users/thankyou");
			else 	   $this->view->render("users/fail");

			return;
		} // Case fail
		else {
			echo "An error occured: ";
			$this->reportInvalid($validation);
			$_POST['password'] = $password;
			$this->view->render("users/add");
		}
	}

	public function get($user_id=null){
		session_start();
		$public_profile = true;
		if (is_null($user_id)){
			if ($_SESSION['loggedin']){
				$user_id = $_SESSION['user_id'];
				$public_profile = false;
				if ($this->rejectBots()) return FALSE;
			}
			else{
				$user_id = $_POST['user_id'];
			}
		}
		
		$this->view->user = $this->model->getUser($user_id);
		$this->view->reviews = $this->model->getContributions($user_id);

		$this->view->passMsg = $_GET['msg'];

		if ($public_profile && !empty($user_id)){
			$this->view->render('users/publicProfile');
		}
		elseif ($public_profile){
			header('Location: '.BASE_URL);
		}
		else{
			$this->view->render('users/profile');
		}

	}

	public function delete(){
		session_start();

		if ($this->rejectBots()) return FALSE;

		$passCheck = $this->model->verifyPass($_SESSION['username'],$_POST['password']);

		$this->view->delMsg = "";
		
		if($passCheck){
			switch ($_POST['delOption']){
				case "minimum":
				$this->model->deleteUser($_SESSION['user_id']);
				break;
				case "all":
				$this->model->forgetUser($_SESSION['user_id']);
				break;
				default:
				break;
			}
			session_destroy();
			header("Location: " . BASE_URL . "?message=" . urlencode("User account deleted"));

		}
		else {
			$this->view->delMsg = "Wrong password";
			header('Location: '.BASE_URL.'users/profile?msg='.urlencode($this->view->delMsg));
		}
		
	}

	public function forgetme(){
		if ($this->rejectBots()) return FALSE;

		$this->view->render('users/forget');
		
	}

	public function update(){
		session_start();
		if ($this->rejectBots()) return FALSE;
		if (isset($_POST['submit'])){
			unset($_POST['submit']);
			$passCheck = $this->model->verifyPass($_SESSION['username'],$_POST['password']);

			if ($passCheck){
				unset($_POST['password']);
				$user = $this->sanitize($_POST);
				$user['user_id'] = $_SESSION['user_id'];
				$error = $this->model->updateUser($user);
				echo $error;
				header('Location: ' . BASE_URL . 'users/get');
				return;
			} else {
				echo "Wrong password.";
			}
		}
		elseif (isset($_POST['cancel'])){
			header('Location: ' . BASE_URL . 'users/profile');
			return;
		}
		else {
			$_POST = $this->model->getUser($_SESSION['user_id']);
		}

		$this->view->render('users/update');
	}

	public function changePassword(){
		session_start();
		if ($this->rejectBots()) return FALSE;
		$newPassValid = $_POST['newpass1'] == $_POST['newpass2'];
		
		$passCheck = $this->model->verifyPass($_SESSION['username'],$_POST['password']);

		$this->view->passMsg = "";

		if ($newPassValid && $passCheck){
			$newPassword = password_hash($_POST['newpass1'], PASSWORD_DEFAULT); // real algorithm omitted
			$this->view->passMsg = $this->model->updatePassword($_SESSION['user_id'], $newPassword);
		}
		elseif(!$newPassValid){
			$this->view->passMsg = "New passwords do not match";
		}
		else {
			$this->view->passMsg = "Current password is wrong";
		}

		header('Location: ' . BASE_URL . "users/get" . "?msg=" . urlencode($this->view->passMsg));
	}

       
	public function recover(){
		if ($this->rejectBots()) return FALSE;
		if (!isset($_POST) ){
			$this->view->render('users/recover');
			return TRUE;
		}

		// verify information

		if (!isset($_POST['email']) || !isset($_POST['given_name']) || !isset($_POST['birthday'])){
			$this->view->render('users/recover'); 
			return FALSE;
		}


		$_POST['given_name'] = $this->sanitizeGivenName($_POST['given_name']);

		$identityCheck =
			$this->model->verifyIdentity($_POST['email'], $_POST['given_name'], $_POST['birthday']);

		if (!$identityCheck){
			$this->view->msg = 'User ' . $_POST['given_name'] .' not found.';
			$this->view->render('users/recover'); 
			return;
		} 
		
		$hash = $this->generateHash();
		$user = array(
			'email'      => $_POST['email'],
			'given_name' => $_POST['given_name'],
			'birthday'   => $_POST['birthday']
		);
		$matchUsername = $this->model->matchResetRequest($user, $hash, $_POST['recover_target']);
		echo $matchUsername;
		
		//send email
		$emailSent = $this->sendResetMail($_POST['email'], $_POST['given_name'], $hash, $_POST['recover_target']);
			
		if ($emailSent){
			$this->view->render('users/success');
		} else {
			$this->view->render('users/fail');	
		}
	}


	public function resetData(){
		$hash = $_GET['verifier'];
		$target = $_GET['target'];

		if (empty($hash) || empty($target)){
			$this->view->render('users/reject');
			http_response_code(202);
			return FALSE;
		}		
		$stat = $this->model->getResetStatus($hash);

		// validate request

		if (empty($stat)){
			$this->view->render('users/reject');
			http_response_code(202);
			return FALSE;
		}

		if ($stat['hash'] != $hash || $stat['target'] != $target){
			$this->view->render('users/reject');
			http_response_code(202);
			return FALSE;
		}

		$this->view->targetValue = $target;
		$this->view->hash = $hash;
		$this->view->render('users/reset');

	}

	public function resetTarget(){
		$target = $_POST['target'];

		if ($target != 'username' && $target != 'password'){
			$this->view->render('users/reject');
			http_response_code(202);
			return FALSE;
		}

		if ($target == 'password'){
			if ($_POST['password'] != $_POST['password2']){
				$msg = urlencode("Passwords do not match");

				$url = BASE_URL . 'users/resetData?target='. $target . '&verifier=' . $_POST['hash']
					. '&msg=' . $msg;

				$this->redirect($url);

				return FALSE;
			} else {
				$newValue = password_hash($_POST[$target], PASSWORD_DEFAULT); // real algorithm omitted
			}
		} else {
			$validUsername = preg_match('/^[a-zA-Z0-9_\.\-]{6,}$/', $_POST['username']);
			if (!$validUsername){
				$msg = urlencode("Username must contain only alphanumeric characters, '_', or '.', and contain at least 6 characters.");
				
				$url = BASE_URL . 'users/resetData?target='. $target . '&verifier=' . $_POST['hash']
					. '&msg=' . $msg;

				$this->redirect($url);
				return FALSE;
			} else {
				$newValue = $_POST['username'];
			}
		}
		 
		$this->model->resetEntry($target, $newValue, $_POST['hash']);
		$this->view->render('users/success'); 
	}



	private function sanitize($user){
		$usr = $user;

		$usr['given_name']  = $this->sanitizeGivenName($user['given_name']);  
		$usr['family_name'] = strtoupper(filter_var($user['family_name'], FILTER_SANITIZE_STRING));
		$usr['affiliation'] = filter_var($user['affiliation'], FILTER_SANITIZE_STRING);
		$usr['email'] = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
		
		return $usr;
	}

       	private function sanitizeGivenName($inputName){
		$given_name = explode(" ", strtolower(filter_var($inputName, FILTER_SANITIZE_STRING)));
		foreach($given_name as $key=>$name){
			$hifened = explode("-", $given_name[$key]);
			foreach($hifened as $subkey => $namePiece){
				$hifened = explode("-", $given_name[$key]);
				$hifened[$subkey] = ucfirst($namePiece);
			}
			$given_name[$key] = implode('-', $hifened);
		}
		$sanitizedName = implode(" ",$given_name);

		return $sanitizedName;
	}

	private function validate($user){
		$over13 = $this->above13($user['birthday']);
		$given_name = !empty($user['given_name']) && preg_match('/[a-z\-\s\']/i', $user['given_name']);
		$family_name = empty($user['family_name'])? true : preg_match('/[a-z\-\s\']/i', $user['family_name']);
		$email = filter_var($user['email'], FILTER_VALIDATE_EMAIL);
		$username = preg_match('/^[a-zA-Z0-9_\.\-]{6,}$/', $user['username']);
		$password = $user['password'] == $user['password2'];
		$agree = isset($user['agree']);

		$validation = array(
			"age"         => $over13,
			"given_name"  => $given_name,
			"family_name" => $family_name,
			"email"       => $email,
			"username"    => $username,
			"password"    => $password,
			"agree"       => $agree
		);

		return $validation;

	}

	private function captchaValidation($token){
		$key = '***';
		
		$gtoken = $_POST['g-recaptcha-response'];
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$token);
		$response_data = json_decode($response);

		return $response_data->success;
		
	}

	private function above13($birthday){
		$birthdate = new DateTime($birthday);

		$age = (new DateTime())->diff($birthdate)->y;

		if ($age < 13) return false;
		else return true;
	}

	private function reportInvalid($validation){
		if (!$validation['age']) 
			echo "Currently, we cannot register users below 13 years old.<br>";

		if (!$validation['given_name']) 
			echo "Given name(s) must be written with alphabet characters.<br>";

		if (!$validation['family_name']) 
			echo "Family name must be written with alphabet characters.<br>";

		if (!$validation['email']) 
			echo "Email doesn't seem to be valid.<br>";

		if (!$validation['username']) 
			echo "Username must contain only alphanumeric characters, '_', or '.', and contain at least 6 characters.<br>";

		if (!$validation['password']) 
			echo "Passwords do not match.<br>";

		if (!$validation['agree']) 
			echo "You must accept our terms of service.<br>";
	}

	private function redirect($url){
		if (!headers_sent()) {
			header('Location: '.$url);
		} else {
			echo
			"<script type='text/javascript'>
			document.location.href='" . $url . "';
			</script>
			<a href='" . $url . "'>Redirecting...</a>";
			
		}
	}

	private function mailValidation($email, $name, $uid, $hash){
		$url = BASE_URL . 'users/confirmation?uid='.$uid."&verifier=".$hash;
		$mailer = new Mailer(true);

		// Set addresses
		$mailer->setFrom('c**@*.scigen.report', 'Mailer');
		$mailer->addAddress($email, $name);

		// Set message
		$mailer->Subject = "Email confirmation";
		$mailer->Body = '<h2>Email registration confirmation</h2>
			<p>Thank you for singing up for <a href="https://scigen.report">SciGen.Report</a>,
			the world\'s platform most committed to the reproducibility of scientific papers.
			Please, visit the following link to confirm your email address.
			Note that you need to verify your email to submit reports and to reset your password in case of loss.</p>
			<a href="'.$url.'">'.$url.'</a>
			<p> If you did not sign up for our site, please ignore this message.</p>';
		$mailer->AltBody = '
Thank you for singing up for SciGen.Report,
the world\'s platform most committed to reproducibility of scientific papers.
Please, visit the following URL to confirm your email address.
Note that you need to verify your email to submit reports and to reset your password in case of loss.'
.PHP_EOL.$url.PHP_EOL.
'If you did not sign up for our site, please ignore this message.';

		if (!$mailer->send()){
			$this->model->deleteUser($uid);
			return false;
		} else {
			return true;
		}

	}

	public function confirmation(){
		$uid = $_GET['uid'];
		$hash = $_GET['verifier'];


		if (!empty($uid) && !empty($hash)){
			$this->view->confirmed = $this->model->verifyEmail($uid, $hash);
			$this->view->render("users/confirmation");
			
		} else {
			echo "Invalid Access.";
			return false;
		}

	}

	public function resendEmail(){
		session_start();
		$uid = $_SESSION['user_id'];
		$user = $this->model->getUser($uid);
		$hash = $this->model->getHash($uid);

		$sent = $this->mailValidation($user['email'], 
			$user['given_name'].' '.$user['family_name'],
			$uid, $hash);

		$message = $sent ? "Validation mail resent" : "Couldn't resend validation email";

		header('Location: '.BASE_URL."users/get?msg=".urlencode($message));
	}

	private function generateHash(){
		$rndKey  = strval(random_int(1,1000000000));
		$rndKey .= strval(random_int(1,1000000000));

		return hash('sha256', $rndKey);
	}


	private function sendResetMail($email, $name, $hash, $target){ 
		$url = BASE_URL . 'users/resetData?target='.$target.'&verifier='.$hash;

		
		$mailer = new Mailer(true);
		$mailer->setFrom('r**@*.scigen.report', 'Mailer - '. $target .' Reset');
		$mailer->addAddress($email, $name);


		$mailer->Subject = $target . " recovery";
		$mailer->Body = '<p>We have received your request to reset your account\'s' . $target .' for SciGen.Report,
			the world\'s platform most committed to reproducibility of scientific papers. 
			Please, visit the following URL to reset your '. $target.'.
			<a href='.$url .'>'. $url . '</a>
			<p>If you did not request this, please ignore this message.</p>';
 
		$mailer->AltBody = 'We have received your request to reset your account\'s' . $target .' for SciGen.Report, the world\'s platform most committed to reproducibility of scientific papers. Please, visit the following URL to reset your '. $target.'.'
.PHP_EOL.$url.PHP_EOL. 
'If you did not request this, please ignore this message.';

		return $mailer->send();

	}

	private function resolve_uid($uid){
		$hasher = new Hasher();
		$id = $hasher->breakTag($uid);
		return $id[0];
	}

	public function hash_id($id){
		$hasher = new Hasher();
		$tag = $hasher->makeTag($id);
		return $tag;
	}

	public function profile($uid=NULL){
		if (is_null($uid)) {
			$this->get();
		}
		else {
			$id = $this->resolve_uid($uid);
			$this->get($id);
		}
	}

}

?>
