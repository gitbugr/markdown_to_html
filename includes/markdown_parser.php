<?php

class MarkdownParser {
	// used for checking if currently parsing a block of code
	public static $inBlock = null;
	// we'll use regex to check for markdown patterns and replace using either function or preg_replace
	public static $regex = [
		'/`{3}/' => 'self::codeBlock',
		'/`(.*?)`/' => '<code>\1</code>',
		'/(#+)(.*)/' => 'self::header',
		'/\*\*\*/' => '<hr />',
		'/(\*\*|__)(.*?)(\*\*|__)/' => '<b>\2</b>',
		'/(\*|_)(.*?)(\*|_)/' => '<i>\2</i>',
		'/!\[[^\]]*\]\((.*?)\s*("(?:.*[^"])")?\s*\)/' => '<img alt=\'\1\' src=\'\2\' title=\'\3\' />',
		'/\[([^\[]+)\]\(([^\)]+)\)/' => '<a href=\'\2\'>\1</a>',
		'/^- (.+?)/' => '<li>',
	];

	// Counts #'s to return corrent header tag
	public static function header($args) {
		$hashes = $args[1];
		$string = $args[2];
		$headerNumber = strlen($hashes);
		return '<h'.$headerNumber.'>'.trim($string).'</h'.$headerNumber.'>';
	}

	// Checks if currently parsing a <code> block and returns relevant tag
	// This approach could also be used for <blockquote>'s
	public static function codeBlock() {
		$tag = '<code>';
		if (self::$inBlock === 'code') {
			self::$inBlock = null;
			$tag = '</code>';
		} else {
			self::$inBlock = 'code';
		}
		return $tag;
	}

	// Check if regex is found within code block (so we can skip parsing it)
	public function isInCodeBlock($regex, $string) {
		if ($regex === '/`{3}/') {
			return false;
		} else if (self::$inBlock === 'code') {
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
	public static function parseString($string) {
		foreach (self::$regex as $regex => $replace) {
			if(!self::isInCodeBlock($regex, $string)) {
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
