<?php
class Logger{
	public function __construct($logPath = NULL){
		if (is_null($logPath){
			$this->path = LOG_PATH;
		} else {
			$this->path = $logPath;
		}
	}

	private function log($type, $message){
		$handle = fopen($this->path."SciGen.log", "a+");
		fwrite($handle, $type." : ".$message.PHP_EOL);
		fclose($handle);
	}

	public function info($message){
		$this->log("info", $message);
	}

	public function warn($message){
		$this->log("warn", $message);
	}

	public function error($message){
		$this->log("error", $message);
	}
}
?>
