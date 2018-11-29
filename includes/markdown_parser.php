<?php

class MarkdownParser {
	// used for checking if currently parsing a block of code
	public $inBlock = null;
	// we'll use regex to check for markdown patterns and replace using either function or preg_replace
	public $regex = [
		'/`{3}/' => '$this->codeBlock',
		'/`(.*?)`/' => '<code>\1</code>',
		'/(#+)(.*)/' => '$this->header',
		'/\*\*\*/' => '<hr />',
		'/(\*\*|__)(.*?)(\*\*|__)/' => '<b>\2</b>',
		'/(\*|_)(.*?)(\*|_)/' => '<i>\2</i>',
		'/!\[[^\]]*\]\((.*?)\s*("(?:.*[^"])")?\s*\)/' => '<img alt=\'\1\' src=\'\2\' title=\'\3\' />',
		'/\[([^\[]+)\]\(([^\)]+)\)/' => '<a href=\'\2\'>\1</a>',
		'/^- (.+?)/' => '<li>',
	];

	// Counts #'s to return corrent header tag
	public function header($args) {
		$hashes = $args[1];
		$string = $args[2];
		$headerNumber = strlen($hashes);
		return '<h'.$headerNumber.'>'.trim($string).'</h'.$headerNumber.'>';
	}

	// Checks if currently parsing a <code> block and returns relevant tag
	// This approach could also be used for <blockquote>'s
	public function codeBlock() {
		$tag = '<code>';
		if ($this->$inBlock === 'code') {
			$this->$inBlock = null;
			$tag = '</code>';
		} else {
			$this->$inBlock = 'code';
		}
		return $tag;
	}

	// Check if regex is found within code block (so we can skip parsing it)
	public function isInCodeBlock($regex, $string) {
		if ($regex === '/`{3}/') {
			return false;
		} else if ($this->$inBlock === 'code') {
			return true;
		} else {
			preg_match('/<code>(.*?)<\/code>/', $string, $codeBlocks, PREG_OFFSET_CAPTURE);
			preg_match($regex, $string, $regexMatches, PREG_OFFSET_CAPTURE);
			foreach (array_slice($codeBlocks, 1) as $codeBlock) {
				$codeBlockLength = strlen($codeBlock[0]);
				$codeBlockOffset = $codeBlock[1];
				foreach (array_slice($regexMatches, 1) as $regexMatch) {
					if ($regexMatch[1] >= $codeBlockOffset && $regexMatch[1] <= $codeBlockOffset + $codeBlockLength) {
						return true;
					}
				}
			}
			return false;
		}
	}

	// Takes a string and parses it to html syntax
	public function parseString($string) {
		foreach ($this->regex as $regex => $replace) {
			if(!$this->isInCodeBlock($regex, $string)) {
				if (is_callable ($replace)) {
					$string = preg_replace_callback ($regex, $replace, $string);
				} else {
					$string = preg_replace ($regex, $replace, $string);
				}
			} else {
				return $string;
			}
		}
		return trim($string);
	}

}

?>
