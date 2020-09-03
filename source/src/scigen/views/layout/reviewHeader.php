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
	background-color: rgb(245,245,245);
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
	grecaptcha.execute('key', {action: 'get_review'}).then(function(token) {
		const xhr = new XMLHttpRequest();
		xhr.open("POST", "search/validateAccess/" + token, true);
		xhr.send();
	});
});
</script>
<style>
html, body{
width: 100%;
height: 100%;
margin: 0;
}
#reviews{
  position: relative;
  min-height:150px;
  min-width:320px;
  left: 15px;
  margin-top: 50px;
}

#review-board{
  position: absolute;
  top: 5px;
  padding-bottom: 50px;
}

#add-review{
  position: relative;
  margin-top: -500px;
}


@media screen and (min-width:601px){
	#review-board{margin-left:7%;max-width:95%;}	
}
@media screen and (max-width:600px) {
	#review-board{width:95%;}
h2{font-size:24px}h3{font-size:21px}h4{font-size:18px}h5{font-size:16px}
	}
@media screen and (max-height:600px) {
h2{font-size:24px}h3{font-size:21px}h4{font-size:18px}h5{font-size:16px}
	}

@media screen and (min-width:1501px){
	.w3-image{max-width:550px;}
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="<?=BASE_URL . 'assets/scripts/clamp.js'?>"></script>
<script>
function formatAuthors(){
	var header = document.getElementById('paper-title');
	const lines = screen.height < 450 ? 2 : 3;
	$clamp(header, {clamp: lines});
	
	var h = header.clientHeight;
	var authors =  document.getElementById('paper-authors');
	authors.style.top = h - 70 + "px";
	document.getElementById('paper').style.right = "100%";
    const chartHeight = document.getElementById('repCanvas').style.height;
	document.getElementById('paper-info').style.height = h + authors.clientHeight - 20 + chart + "px";


}

function copyLink(ref){
<?php
	$reviewAddress = BASE_URL . 'reviews/get?doi=' . $_GET['doi'];
	echo 'const lnk = "' . $reviewAddress . '#" + ref;' . PHP_EOL;
?>
	if (typeof navigator.clipboard !== "undefined"){
		navigator.clipboard.writeText(lnk);
	} else {
	const el = document.createElement('input');
	el.value = lnk;
	el.setAttribute('readonly', '');
	el.style.position = 'absolute';
	el.style.left = '-9999 px';
	document.body.appendChild(el);
	el.select();
	document.execCommand("copy");
	document.body.removeChild(el);
	} 
}
<?php $paper = $this->paper_data;?>
function generateChart(){
    const ctx = document.getElementById('repCanvas');
    const fsize = 30;
    var width =  window.innerWidth
    || document.documentElement.clientWidth
    || document.body.clientWidth;
    const show = width > 600;
    //ctx.style.width  = 400 + "px";
    //ctx.style.height = 400 + "px";
   const chart = new Chart(ctx, {
        type: 'polarArea',

        data: {
            labels: ['Yes', 'Almost', 'Partially', 'Hardly', 'No'],
            datasets: [{
                //label: 'Reproducibility reports',
                backgroundColor: [
                          /*'rgb(0, 118, 106)',
                         'rgb(0, 133, 120)',
                          'rgb(0, 220, 198)',
                         'rgb(38, 255, 233)',
                         'rgb(110, 255, 240)'*/
                            'rgb(5, 199, 32)',
                           'rgb(55, 199, 132)',
                            'rgb(255, 245, 69)',
                           'rgb(255, 150, 132)',
                           'rgb(255, 99, 132)'
                          ],
                borderColor: [
                 'rgb(5, 199, 32)',
                'rgb(55, 199, 132)',
                 'rgb(155, 255, 132)',
                'rgb(255, 150, 132)',
                'rgb(255, 99, 132)'
                 ],
                data: [ <?=$paper['rep_success'] - $paper['tricky'] - $paper['partial']?>,
                         <?=$paper['tricky']?>,
                         <?=$paper['partial']?>,
                         <?=$paper['possible']?>,
                         <?=$paper['rep_fail'] - $paper['possible']?>]
                //data: [0, 0, 0, 1, 3],
            }]
        },

        // Configuration options go here
        options: {
                legend: {
                           display: show,
                           position : 'bottom',
                           labels: {
                           boxWidth : 12
                           //fontSize : fsize
                           }
                },
                tooltips: {
                           //bodyFontSize: fsize
                }
        }
    });
    
    //ctx.style.width  = 300 + "px";
    //ctx.style.height = 300 + "px";
    
    //var sum = document.getElementById('paper-info');
    //sum.style.height += ctx.style.height + 100;
    
}
</script>


</head>
<body onload="formatAuthors(); " onresize="formatAuthors();generateChart();">

<div class="w3-bar w3-theme-light" style="position:fixed; top: 0px; z-index: 2;min-height: 70px;">
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

