<?php

class ArgsParser {
	// args array
	private $args;
	private $argsLength;

	// constructor
	function __construct($args) {
		$this->args = $args;
		$this->argsLength = sizeof($args);
	}

	public function getAfter($string) {
		$pos = array_search($string, $this->args);
		if ($pos != false && $this->argsLength > $pos) {
			return $this->args[$pos + 1];
		} else {
			die("invalid arguments.");
		}
	}
}

?>
