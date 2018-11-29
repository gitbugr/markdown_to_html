<?php

class FileDriver {

	private $fileDescriptor;

	function __construct($fileDesciptor) {
		$this->fileDescriptor = fopen($fileDesciptor, "w");
		if(!$this->fileDescriptor) {
			die("Cannot open file.");
		}
	}

	public function write($string) {
		fwrite($this->fileDescriptor, $string);
	}

	public function close() {
		fclose($this->fileDescriptor);
	}

}

?>

