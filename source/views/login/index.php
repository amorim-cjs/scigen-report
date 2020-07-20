
<div class="w3-card w3-center " style="max-width:600px; margin:auto;">

<h1>Login</h1>
<?php

if (isset($this->message)) echo '<h3 class="w3-text-red">' . $this->message . '</h3>';

?>

<?php if (!$_SESSION['loggedin']) :
?>

<form class="w3-container  w3-padding-16" action="runLogin" method="post" style="max-width: 95%;">
  <ul>
    <li style="display:none;"><input type="hidden" name="doi" value="<?=$_GET['doi']?>"></li>
    <li style="display:none;"><input type="hidden" name="return_page" value="<?=$_SERVER['HTTP_REFERER']?>"></li>
      
    <li class="nobullet"><input class="w3-input" name="username" placeholder="Username"><br></li>
      
     <li class="nobullet"><input class="w3-input w3-border" name="password" type="password" placeholder="Password"></li>
    
  </ul>
    <input class="w3-btn w3-theme-d3" type="submit" name="submit" value="Login">
    <a class="w3-btn w3-theme-l1" href="<?=BASE_URL . 'users/add?from=' . $_SERVER['HTTP_REFERER']?>">signup</a>
<br>
<?php
echo '<a href=' . BASE_URL . 'users/recover>Forgot username or password?</a>'
?>

</form>

</div>

<?php endif; ?>
