<body>

<div class="w3-container w3-padding">
<form action="/contact/submit" method="post" style="padding-bottom:50px;">

  <label>Name</label>
  <input class="w3-input" name="name" readonly value="<?=$_POST['name']?>">

  <label>Email</label>
  <input class="w3-input" name="email" readonly value="<?=$_POST['email']?>">

  <label>Type of contact: </label>
<?php
if ($_POST['sortAs'] == 'contact') echo "General contact";
elseif ($_POST['sortAs'] == 'feedback') echo "Feedback or bug report";
?>
  <input type="hidden" name="sortAs" value="<?=$_POST['sortAs']?>">
  <br>
  <label>Subject</label>
  <input class="w3-input" name="subject" readonly value="<?=$_POST['subject']?>">

  <label>Message (max. 500 characters)</label><br>
  <textarea name="message" readonly maxlength="500"><?php echo $_POST['message'];?></textarea><br>

  
  <input class="w3-btn w3-theme" type="submit" name="submit" value="Submit">
  <input class="w3-btn w3-theme" type="submit" name="correct" value="Correct">
</form>
</div>
</body>
