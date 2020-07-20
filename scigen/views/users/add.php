<script type="text/javascript">
function agreed(){
	var agreeBox = document.getElementById('agree');
	if (!agreeBox.checked) {
		alert("You must agree with our Pivacy Policy and Terms of Use");
		agreeBox.style.border = "thick solid red";
	}
	return agreeBox.checked;
}

var onloadCallback =  grecaptcha.ready(() => {
	grecaptcha.render('captcha-box', {
		'sitekey' : 'key'
	});
});
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

<div>
<?php
if(isset($this->id)){
	if ($this->id > 0){
		echo "New user has been successfully added.";
	} elseif ($this->id == -1){
		echo "Email already registered.";
	} elseif ($this->id == -2){
		echo "Username already registered.";
      }
}
?>

<body>
<form class="w3-container" style="max-width:1000px;margin:auto;padding-bottom:50px;" action="add" method="post" onsubmit="return agreed();" >
  <ul>
  <label><b>Given name(s)</b> (required, max. 30 characters. It will be displaied to other users with your family name)</label>
      <input required class="w3-input" name="given_name" placeholder="Given name" maxlength="30"
	value="<?=isset($_POST['given_name'])? $_POST['given_name'] : ""?>">
<label><b>Family name</b> (Please, leave blank only if you DO NOT HAVE a family name. Max. 60 characters. It will be displayed to other users with your given name)</label>
      <input class="w3-input" name="family_name" placeholder="Family name" maxlength="60"
	value="<?=isset($_POST['family_name'])? $_POST['family_name'] : ""?>">
    
      <label><b>Birthdate</b> (for age verification, required)</label>
      <input required class="w3-input" type="date" name="birthday" min="1901-01-01" max=<?= date("Y-m-d")?> 
	value="<?=isset($_POST['birthday'])? $_POST['birthday'] : "1970-01-01"?>">
    
      <label><b>Affiliation</b> (max. 250 characters)</label>
      <input class="w3-input" name="affiliation" placeholder="Current institution(s)" maxlength="250"
	value="<?=isset($_POST['affiliation'])? $_POST['affiliation'] : ""?>">
    
      <label><b>Email</b> (required. Visible only to you.)</label>
    <input required class="w3-input" type="email" name="email" placeholder="researcher@institution.org"  maxlength="120"
	value="<?=isset($_POST['email'])? $_POST['email'] : ""?>">

      <label><b>Research Interests</b> (max. 250 characters)</label>
      <textarea class="w3-input" name="expertise" placeholder="Enter your fields of study" maxlength="250">
<?php if (isset($_POST['expertise'])) echo $_POST['expertise']; ?></textarea>
    
      <label><b>Username</b> (required, 6-30 characters, for login only, visible only to you)</label>
      <input required class="w3-input" name="username" placeholder="username" minlength="6" maxlength="32"
	value="<?=isset($_POST['username'])? $_POST['username'] : ""?>">
    
      <label><b>Password</b> (required)</label>
	<span id='message'>Minimum 8 characters.</span>
      <input required class="w3-input" name="password" type="password" id="password" minlength="8"
	 value="<?= isset($_POST['password']) ? $_POST['password'] : ''?>">

  <label><b>Confirm Password</b> (required)</label>
      <input required class="w3-input" name="password2" type="password" id="password2" minlength="8">
    
    <br> I agree with 
    <a href="<?=BASE_URL.'info/terms'?>" target="_blank">terms of service</a>
     and <a href="<?=BASE_URL.'info/terms'?>" target="_blank">privacy policy</a>:
    <input class="w3-check" type="checkbox" name="agree" id="agree">
    <br>
    
      <input type="hidden" name="from" value="<?=$this->from?>">
      <div id="captcha-box"></div>

      <input class="w3-btn w3-theme-d1" type="submit" name="submit" value="Add User" >

  </form>
</body>
</div>
