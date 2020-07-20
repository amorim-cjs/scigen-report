
<script>
function agreed(){
	var agreeBox = document.getElementById('agree');
	if (!agreeBox.checked) {
		alert("You must confirm you really want to delete your account.");
		agreeBox.style.border = "thick solid red";
	}
	return agreeBox.checked;
}

</script>

<div class="w3-container" style="margin:auto; padding-bottom:50px;">

<h1><b><?= $this->user['given_name'] . " " . $this->user['family_name']?></b> (<?=$this->user['username']?>)</h1>
<h2><?= $this->user['affiliation']?> </h2>
<h2>Expertise: <?= $this->user['expertise']?> </h2>
<h2><?= $this->user['email']?> 

<?php
switch ($this->user['email_status']){
case "verified":
	echo '<span class="w3-text-green" ><b>✓ </b>';
	break;
case "pending":
	echo '<span class="w3-text-red">(pending verification)',
		PHP_EOL, '<form action="resendEmail" method="post"><input class="w3-btn w3-theme w3-medium" type="submit" value="Send validation email again"></form>';
	break;
default:
	echo '<br><span><b> (Cannot reach this address.</b> Please update for a valid email and check your anti-spam configurations)';
	break;
}

	echo '</span></h2>', PHP_EOL;

	$hasher = new Hasher();
	$uid = $hasher->makeTag($_SESSION['user_id'], 0);
	
	echo '<a class="w3-btn w3-theme-l1" href="', BASE_URL . 'users/profile/' . $uid,
		'">Public profile</a>';

?>

<div class="w3-container">
  <span class="red"><?php echo $this->passMsg;?></span><br>
  <form action="update" method="get">
    <input class="w3-btn w3-theme" type="submit" name="update" value="Update information">
  </form>

  <button class="w3-btn w3-theme" type="text" onclick="document.getElementById('passChange').style.display='block'">
  Change password</button>
  <div id="passChange" class="w3-modal" style="z-index:5;">
    <div class="w3-modal-content w3-animate-top">
      <div class="w3-container ">
      <h2>Change password</h2>
      <form action="changePassword" method="post">
        <span onclick="document.getElementById('passChange').style.display='none'"
          class="w3-button w3-display-topright">&times;</span>
        <p><input class="w3-input" type="password" name="password" placeholder="Current password"></p>
        <p><input class="w3-input" type="password" name="newpass1" placeholder="New password" required minlength="8"></p>
        <p><input class="w3-input" type="password" name="newpass2" placeholder="Confirm new password"></p>
        <input class="w3-button w3-theme" type="submit" name="submit" value="Confirm">
        <span  onclick="document.getElementById('passChange').style.display='none'" class="w3-btn w3-theme">Cancel</span>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="w3-container" >
  <b><button class="w3-btn" type="text" style="color:white; background-color:red;" onclick="document.getElementById('delChoice').style.display='block'">
  Delete Account</button></b>
  <span class="red"><?php echo $this->delMsg;?></span>
  <div id="delChoice" class="w3-modal" style="z-index:5;">
    <div class="w3-modal-content w3-animate-top">
      <div class="w3-container ">
      <form action="delete" method="post" onsubmit="return agreed();">
        <span onclick="document.getElementById('delChoice').style.display='none'"
          class="w3-button w3-display-topright">&times;</span>
	<p><br> <b>WARNING</b>: By deleting your account, we will  <span class="red">irreversibly delete</span> your information from our database. 
	  However, we want to keep the discussion alive in the scientific community, and in order to keep transparency, we will keep your submitted reviews in our server attributed to your name.
	  If you wish to delete all your reviews together with every register of your name in our server, click 
	  <a href="<?=BASE_URL . 'users/forgetme'?>">here</a> 
and send us a request.
          We value every contribution, and suggest you the option of deleting your personal account,
          together with all your personal information but your name and reviews.
          If you agree with this option, your reviews will still be public and signed by you,
          but no other information will be kept.<br>
	<p><input class="w3-check" type="checkbox" name="agree" id="agree">  
	   <input type="hidden" name="delOption" value="minimum">
          I understand. Proceed with deleting my account.</p>
          <input class="w3-input" type="password" name="password" placeholder="Password">
        <input class="w3-button" style="color:white;background-color:red;" type="submit" name="delete" value="Confirm">
        <span  onclick="document.getElementById('delChoice').style.display='none'" class="w3-btn w3-theme">Cancel</span>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- # of contributions go here -->
<?php 
$reproduced = 0;
foreach ($this->reviews as $review){
  if ($review['rep_status'] == 'Y' || $review['rep_status'] == 'T' || $review['rep_status'] == 'K') $reproduced++;
}
?>
<h3><b><?= sizeof($this->reviews)?></b> review(s) submitted.<br>
 <b><?= $reproduced?></b> papers successfully reproduced, <b><?=sizeof($this->reviews) - $reproduced?></b> negative result(s).</h3>

<!-- list of contributions goes here -->
<div class="report-list">
<?php foreach ($this->reviews as $review): ?>
<a href="<?=BASE_URL.'reviews/get?doi='.$review['doi']?>">
  <h3><b><?=$review['title']?></b>, DOI:<?=$review['doi']?></h3></a>
  <p><?php
    switch ($review['rep_status']){
      case 'Y':
        echo "<span class='w3-tag w3-green w3-xlarge' style='transform:rotate(-3deg);'>Successfully reproduced";
        break;
      case 'T':
        echo "<span class='w3-tag w3-green w3-xlarge' style='transform:rotate(-3deg);'>Reproduced, but tricky";
	break;
      case 'K':
	      echo "<span class='w3-tag w3-green w3-xlarge' style='transform:rotate(-3deg);'>Reproduced partially";
	      break;

      case 'P':
        echo "<span class='w3-tag w3-red w3-xlarge' style='transform:rotate(3deg);'>Not reproduced, but seems possible";
        break;
      case 'N':
        echo "<span class='w3-tag w3-red w3-xlarge' style='transform:rotate(3deg);'>Failed to reproduce";
        break;
      default:
      break;
    }
  ?></span></p>

<?php endforeach; ?>
</div>

</div>
