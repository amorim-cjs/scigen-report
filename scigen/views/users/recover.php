<div>

<form class="w3-container" style="max-width:1000px;margin:auto;" action="recover" method="post" onsubmit="return checked();">
  <ul>
<?php echo $this->msg . PHP_EOL; ?>
  <label>Given name(s)</label>
    <input required class="w3-input" name="given_name" placeholder="Given name"
	value="<?= $_POST['given_name'] ?>">
    
    <label>Email</label>
    <input required class="w3-input" type="email" name="email" placeholder="researcher@institution.org"
	value="<?= $_POST['email']?>" >

    <label>Birthdate</label>
    <input required class="w3-input" type="date" name="birthday" min="1901-01-01"
    value="<?= $_POST['birthday'] ? $_POST['birthday'] :'1970-01-01'?>">


    <p><label> Choose the data you want to reset </label></p>
    <input required class="w3-radio" type="radio" name="recover_target", value="password">
      I want to recover my <b>password</b><br>
    <input required class="w3-radio" type="radio" name="recover_target", value="username">
      I want to recover my <b>username</b><br>
   <br>
      <input class="w3-btn w3-theme-d3" type="submit" name="reset" value="Reset">
      <input class="w3-btn w3-theme-d1" type="submit" name="cancel" value="Cancel">
    
  </form>
</div>
