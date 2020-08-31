<?php
session_start();
echo "<!DOCTYPE html>", PHP_EOL, '<html lang="en">', PHP_EOL;
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?=BASE_URL . 'assets/loadCSS/styles.css'?>">
<title>SciGen.Report - Research Reproducibility For All</title>
<style>

html,body{
  position: relative;
  top:50px;
  bottom:25px;
  height: 90%;
	background-color: rgb(230,230,230);
}

.nobullet{
  list-style-type: none;
}

.box{
  max-width:250px;
  height: 32px;
  border:1px solid #6efff1;
  background-color: #6efff1;
}

.hidden-item{
  display: none;
}

@media screen and (max-width: 619px){
	.box{
	display:none;}

	div.w3-left{
	display:none;}

	.footer {
	display: none;}

	.grecaptcha-badge { 
	visibility: visible; }
	
	.hidden-item{
	display: none; }
}

@media screen and (min-width: 620px){
	div.w3-left{
	display:inline;
	}

	.grecaptcha-badge { 
	visibility: hidden; }

	.hidden-item{
	display: block; }
}

.fullscreen {
	position: relative;
	top: 20%;
	text-align: center;
}

.footer{
    position:fixed;
    left:0;
    bottom:0;
    width:100%;
    text-align:center;
}
</style>

<script>
function checkFill(input){
	var searchBox = document.getElementById("searchBox");
	var doi = document.getElementById("searchBox").value;
	var width = document.documentElement.clientWidth;
	doi = doi.trim();
	var displaying = getComputedStyle(searchBox,null).display;
	if (displaying == 'none' ){	
			searchBox.style.display='inline';
			return false;
	}
	if (doi == "") {
	  		alert("Cannot search for empty DOI");
			return false;
  	}
	
  return true;
}

function leftCorner(){
	var logo = document.getElementById('logo');
	var width = document.documentElement.clientWidth;

}

</script>

<script src="https://www.google.com/recaptcha/api.js?render=key"></script>
<script>
grecaptcha.ready(function() {
	grecaptcha.execute('key3', {action: 'search'}).then(function(token) {
		const xhr = new XMLHttpRequest();
		xhr.open("POST", "search/validateAccess/" + token, true);
		xhr.send();
	});
});
</script>
<style>
.red{
  color:red;
}
.report-list{
  padding-top:5px;
  padding-bottom:16px;
}
</style>


</head>
<body>

<div class="w3-bar w3-theme-l4" style="position:fixed; top: 0px; z-index: 2;">
  <div id="logo" class="w3-left" style="padding-left:8px;" >
  <p><a class=" w3-center" href="<?=BASE_URL?>"><img src="<?php echo BASE_URL. 'assets/images/logo_title.png';?>" class="w3-image" width="125" alt="SciGen.Report"></a></p>
  </div>
<?php
  $url = explode('/', $_GET['url']);
  if (!empty($url[0])):
?>
  <form class="w3-container" action="/search/search" method="get" style="float:left;margin-top:16px;min-width:200px;" onsubmit="return checkFill()">
  <input id="searchBox" class="box" name="doi" value="<?=$this->doi?>" placeholder="DOI">
  <input class="w3-btn w3-theme"  type=submit value="search" onclick="return checkFill()" >
  </form>
<?php else: ?>
  <script> document.getElementById('logo').style.display='inline';</script>
<?php endif;?>

</div>
  <div class="w3-right" style="position:fixed;top:0;right:3px;z-index:4;" >
<?php if($_SESSION["loggedin"]): ?>
    <div class="w3-dropdown-hover w3-theme-l1 w3-center"  ><p> <button class="w3-btn"><?= $_SESSION["username"]?></button></p>
      <div class="w3-dropdown-content w3-theme w3-bar-block w3-border" style="position:fixed;z-index:1;" >
        <a class="w3-bar-item w3-btn" href="<?php echo BASE_URL; ?>" >
          Home</a>
        <a class="w3-bar-item w3-btn" href="<?php echo BASE_URL."users/get"; ?>" >
	  Profile</a>
<a class="w3-bar-item w3-btn hidden-item" href="<?=BASE_URL . 'info/about'?>">About us</a>

        <a class="w3-bar-item w3-btn" href="<?php echo BASE_URL."login/runLogout?from=" . $_GET['url']; ?>">
          Logout</a>
      </div>
    </div>

<?php else: ?>
<div class="w3-dropdown-hover w3-theme-l1 w3-center"  ><p> <button class="w3-btn">Menu</button></p>
      <div class="w3-dropdown-content w3-theme w3-bar-block w3-border" style="position:fixed;z-index:1;transform:translate(-50px,0px)" >
    <a class="w3-btn w3-bar-item"  href="<?php echo BASE_URL."login/index?from=" . $_GET['url'] . "&doi=" . urlencode($_GET['doi']);?>">Login</a>
<a class="w3-bar-item w3-btn" href="<?php echo BASE_URL; ?>" >
          Home</a>
<a class="w3-bar-item w3-btn" href="<?=BASE_URL . 'info/about'?>">About us</a>

             </div>
    </div>

<?php endif; ?>
  </div>

