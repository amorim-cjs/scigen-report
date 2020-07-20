<?php
class Asset{
	public function __construct(){
	}

	public function loadCSS($css){
		$css_path = "scigen/assets/css/";
		$css_file = $css_path . $css;

		if(!file_exists($css_file)){
			error_log($css . "not found in CSS folder");
			return false;
		}

		return $css_file;

	}
		
}
?>
