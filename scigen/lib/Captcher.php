<?php

class Captcher{
	public function __construct(){
		$this->keyv2 = '6L2***';
		$this->keyv3 = '6L3***';
	}

	public function validateV2($token){
		$response = $this->getResponse($token, $this->keyv2);

		return $response->success;
	}

	public function validateV3($token, $cut = 0.5){
		session_start();
		$response = $this->getResponse($token, $this->keyv3);

		$_SESSION['likelyHuman']  = $response->success && $response->score > $cut;
		$_SESSION['captchaScore'] = $response->score; 

	}

	private function getResponse($token, $key){
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$key."&response=".$token);
		return json_decode($response);
	
	}

	// Insert captcha v2
	public static function captcha2(){
		echo '<div class="g-recaptcha" data-sitekey="***"></div>';

	}
}
?>
