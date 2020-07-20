<?php
class Users_Model extends Base_Model{
	public function __construct(){
		parent::__construct();
	}

	public function addUser($user){
		$sqlString = "SELECT fn_addUser(:username, :password, 
			:given_name, :family_name, :affiliation, :email,
			:birthday, :expertise, :hash);";

		$stmt = $this->db->prepare($sqlString);


		foreach ($user as $key=>$value){
			$stmt->bindValue(":$key", "$value");
		}
		$stmt->execute();

		$error = $stmt->fetchAll(PDO::FETCH_COLUMN);

		error_log("User addition returned: " . $error[0]);

		if ($error[0] <= 0){
			return $error[0]; // Return error codes
		}
		else{
			http_response_code(201);
			return $error[0]; // Return id
		}
	}

	public function getUser($user_id){
		if (!is_null($user_id)){
			return $this->db->query("SELECT user_id, given_name, family_name, expertise, 
						username, email, affiliation, email_status FROM users
						WHERE user_id = $user_id;")
				   ->fetchAll(PDO::FETCH_ASSOC)[0];
		}
		else{
			http_response_code(204);
			return FALSE;
		}
	}

	public function updateUser($user){
		$sqlString = "SELECT fn_updateUser(:user_id, :username, :given_name, :family_name,
			:affiliation, :email, :expertise);";

		$stmt = $this->db->prepare($sqlString);

		foreach ($user as $key=>$value){
			$stmt->bindValue(":$key", "$value");
		}
		$stmt->execute();

		$error = $stmt->fetchAll(PDO::FETCH_COLUMN);
 
		error_log("User update returned: " . $error[0]);

		return $error[0];
	}

	public function updatePassword($user_id, $newpass){
		
		try{
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$sqlString = "UPDATE users SET password=:password WHERE user_id=:user_id;";

			$stmt = $this->db->prepare($sqlString);

			$stmt->bindValue(":password", $newpass);
			$stmt->bindValue(":user_id", $user_id);

			$stmt->execute();

			return "Password changed";
		}
		catch(Exception $e){
			error_log("Exception while updating password: ". $e->getMessage());
			return "An error occurred while updating your password";
		}

	}

	public function deleteUser($user_id){
		$stmt = $this->db->prepare('SELECT fn_deleteUser(:user_id);');
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();

		echo $user_id;
		$error = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];

		error_log("User deletion returned: " . $error);
		echo $error;

		return $error;
	}

	public function forgetUser($user_id){
		$stmt = $this->db->prepare('SELECT fn_forgetUser(:user_id);');
		$stmt->bindValue(":user_id", $user_id);
		$stmt->execute();

		$error = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];

		error_log("User deletion returned: " . $error);

		return $error;
	}

	public function getNumContributions($user_id){
		if (!is_null($user_id)){
			return $this->db->query("SELECT COUNT(review_id) FROM reviews WHERE user_id=$user_id")->fetchAll(PDO::FETCH_COLUMN)[0];
		}
	}

	public function getContributions($user_id){
		if(!is_null($user_id)){
			return $this->db->query("SELECT title, DOI as doi, rep_status FROM rep_view where user_id=$user_id")->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	public function verifyPass($username, $password){
		$stmt = $this->db->prepare('SELECT password FROM users WHERE username=:username');
		$stmt->bindValue(":username", "$username");
		$stmt->execute();

		$passHash = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];

		return password_verify($password, $passHash);
	}

	// 
	public function verifyEmail($uid, $hash){
		session_start();
		$stmt = $this->db->prepare('SELECT hash FROM users WHERE user_id=:user_id');
		$stmt->bindValue(":user_id", $uid);
		$stmt->execute();

		$DBHash = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];

		if ($DBHash == $hash) {
			$stmt = $this->db->prepare('UPDATE users SET email_status="verified" WHERE user_id=:user_id');
			$stmt->bindValue(":user_id", $uid);
			$stmt->execute();
			if ($_SESSION['loggedin']) $_SESSION['email_status'] = 'verified';
			return true;
		}
		else return false;
	}

	public function getHash($uid){
		$stmt = $this->db->prepare('SELECT hash FROM users WHERE user_id=:user_id');
		$stmt->bindValue(":user_id", $uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0];
	}

	public function matchResetRequest($user, $hash, $target){
		$stmt = $this->db->prepare('SELECT user_id FROM users WHERE email=:email AND birthday=:birthday AND given_name=:given_name;');
		foreach ($user as $key=>$value){
			$stmt->bindValue(":$key", "$value");
		}
		$stmt->execute();
		$uid = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];

		if ($uid > 0){
			$stmt = $this->db->prepare('UPDATE users SET reset_hash=:hash, reset_request_date=NOW(), reset_request_type=:target WHERE user_id=:user_id;'); 
		$stmt->bindValue(":user_id", $uid);
		$stmt->bindValue(":hash", "$hash");
		$stmt->bindValue(":target", "$target");
		$stmt->execute();
		return $this->db->query("SELECT username FROM users WHERE user_id=$uid")->fetchAll(PDO::FETCH_COLUMN)[0];
		}
		else {
			return false;
		}

	}

	public function getResetStatus($hash){
		$stmt = $this->db->prepare('SELECT reset_request_date, reset_request_type FROM users WHERE reset_hash=:hash;');
		$stmt->bindValue(":hash", "$hash");
		$stmt->execute();

		$query = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
		$stat = array(
			'hash' => $hash,
			'date'   => $query['reset_request_date'],
			'target' => $query['reset_request_type']
		);

		return $stat;
	}

	public function resetEntry($target, $value, $hash){
		$sqlString = 'UPDATE users SET '.$target.'=:value, reset_hash=NULL, reset_request_date=NULL, reset_request_type=NULL WHERE reset_hash=:hash;'; 
		$stmt = $this->db->prepare($sqlString);
		$stmt->bindValue(":hash", "$hash");
		$stmt->bindValue(":value", "$value");
		$stmt->execute();
	}

	public function verifyIdentity($email, $givenName, $birthday){
		$user = array(
			"email" => $email,
			"given_name" => $givenName,
			"birthday" => $birthday
		);

		$stmt = $this->db->prepare('SELECT user_id FROM users WHERE email=:email AND birthday=:birthday AND given_name=:given_name;');
		foreach ($user as $key=>$value){
			$stmt->bindValue(":$key", "$value");
		}

		$stmt->execute();

		$uid = $stmt->fetchAll(PDO::FETCH_COLUMN)[0];

		return $uid > 0;
	}

}

?>
