<?php
class Assets{
	public function __construct(){
	}

	public function loadCSS($css){
		$css_path = "scigen/assets/css/";
		$css_file = $css_path . $css;

		if(!file_exists($css_file)){
			error_log($css . "not found in CSS folder");
			return false;
		}

		header('Content-Type: text/css');
		return readfile($css_file);

	}

	public function scripts($js){
		$js_path = "scigen/assets/js/";
		$js_file = $js_path . $js;

		if(!file_exists($js_file)){
			error_log($js . "not found in scripts folder");
			return false;
		}

		header('Content-Type: text/javascript');
		return readfile($js_file);

	}
	public function images($img){
		$img_path = "scigen/assets/images/";
		$img_file = $img_path . $img;

		if(!file_exists($img_file)){
			error_log("Couldn't load image: ".$img);
			return false;
		}

		header('Content-type: image/png');

		readfile($img_file);

	}
		
}
?>
