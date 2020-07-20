<div>
<?php session_start();?>
<form class="w3-container" style="max-width:1000px;margin:auto;" action="update" method="post" onsubmit="return checked();">
  <ul>
  <label>Given name(s)</label>
      <input class="w3-input" name="given_name" placeholder="Given name"
	value="<?= $_POST['given_name'] ?>">
  <label>Family name</label>
      <input class="w3-input" name="family_name" placeholder="Family name"
	value="<?=$_POST['family_name']?>">
    
      <label>Affiliation</label>
      <input class="w3-input" name="affiliation" placeholder="Current institution(s)"
	value="<?=isset($_POST['affiliation'])? $_POST['affiliation'] : ""?>">
    
      <label>Email</label>
    <input class="w3-input" type="email" name="email" placeholder="researcher@institution.org" 
	value="<?=isset($_POST['email'])? $_POST['email'] : ""?>">

      <label>Expertise</label>
      <textarea class="w3-input" name="expertise" placeholder="Enter your fields of study">
<?php if (isset($_POST['expertise'])) echo $_POST['expertise']; ?></textarea>
    
      <label>Username</label>
      <input class="w3-input" name="username" placeholder="username"
	value="<?=isset($_POST['username'])? $_POST['username'] : ""?>">
    
      <label>Password</label>
      <input class="w3-input" name="password" type="password" id="password" minlength="8"
	value="<?= isset($_POST['password']) ? $_POST['password'] : ''?>">

      <input type="hidden" name="user_id" value-"<?=$_SESSION['user_id']?>"> 
      <input class="w3-btn w3-theme-d1" type="submit" name="submit" value="Update">
      <input class="w3-btn w3-theme-d1" type="submit" name="cancel" value="Cancel">
    
  </form>
</div>
