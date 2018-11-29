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

	public function getAfter($string, $required = false) {
		$pos = array_search($string, $this->args);
		if ($pos != false && $this->argsLength > $pos) {
			return $this->args[$pos + 1];
		} else if ($required) {
			die("invalid arguments.");
		} else {
			return false;
		}
	}

	public function exists($string) {
		return array_search($string, $this->args) ? true : false;
	}
}

?>
