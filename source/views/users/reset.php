<body>
<div>

<?php echo $_GET['msg'];?>
<form class="w3-container" style="max-width:1000px;margin:auto;" action="resetTarget" method="post" onsubmit="return checked();">
<?php if ($this->targetValue == 'password'):?>
  <label> Choose new password: </label>
  <input required class="w3-input" type="password" name="password" placeholder="New password">
  <label> Confirm new password: </label>
  <input required class="w3-input" type="password" name="password2" placeholder="Confirm new password">
  <input hidden name="target" value="password"> 
<?php elseif ($this->targetValue == 'username'):?>
  <label> Choose new username: </label>
  <input required class="w3-input" name="username" placeholder="New username">

  <input hidden name="target" value="username"> 

<?php endif ?>

  <input hidden name="hash" value="<?=$this->hash?>">
  <input class="w3-btn w3-theme-d3" type="submit" name="reset" value="Reset">

</div>
</body>
