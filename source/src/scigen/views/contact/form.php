<body>

<div class="w3-container w3-padding">
<form action="/contact/confirm" method="post" style="padding-bottom:50px;">

  <label>Name</label>
  <input class="w3-input" name="name" placeholder="Your name" value="<?=$_POST['name']?>">

  <label>Email</label>
  <input class="w3-input" name="email" placeholder="email address" value="<?=$_POST['email']?>">

  <label>Select your type of contact</label>
  <select name="sortAs">
    <option value="contact">General contact</option>
    <option value="feedback">Feedback or bug report</option>
  </select>
  <br>
  <label>Subject</label>
  <input class="w3-input" name="subject" value="<?=$_POST['subject']?>">

  <label>Message (max. 500 characters)</label><br>
  <textarea name="message" maxlength="500"><?php echo $_POST['message'];?></textarea><br>

  
  <input class="w3-btn w3-theme" type="submit" name="confirm" value="Confirm">
</form>
</div>
</body>
