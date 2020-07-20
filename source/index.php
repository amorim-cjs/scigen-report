<?php
require_once("config.php");

function __autoload($class){
	require LIBRARY . $class  . ".php";
}

$app = new Bootstrap();

?>
