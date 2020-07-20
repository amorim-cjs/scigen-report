<?php
class Base_View{
	public function __construct(){
	}
	public function render($name){
		require("scigen/views/layout/header.php");
		require_once("scigen/views/$name.php");
		require_once("scigen/views/layout/footer.php");
	}

}
?>
