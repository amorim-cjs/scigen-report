<head>
<meta name="viewport" content="width-device-width, initial-scale=1">
<style type="text/css">
 .fullscreen {
	position: relative;
	top: 25%;
	text-align: center;

}
</style>
</head>

<body>

<?php 
if (!$this->found) :
?>
<form action="search" method="get">
<label>DOI:</label>
<input class="w3-input" style="max-width:300px;" name="doi" value="<?=$_GET['doi']?>">
<input class="w3-btn w3-theme" type="submit" value="search">
</form>
<?php endif; ?>

</body>
