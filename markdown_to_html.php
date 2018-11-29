#!/usr/bin/php -q
<?php
include("includes/args_parser.php");
include("includes/markdown_parser.php");

$argsParser = new ArgsParser($_SERVER["argv"]);

$inputFilename = $argsParser->getAfter("-i", true);
$outputFilename = $argsParser->getAfter("-o");

// Open input (.md) file
$inputFileDescriptor = fopen($inputFilename, "r");
if ($inputFileDescriptor) {
	// instantiate MarkdownParser
	$mdParser = new MarkdownParser();
	// if output to file
	if ($outputFilename) {
		// Open output (.html) file
		$outputFileDescriptor = fopen($outputFilename, "w");
		if ($outputFileDescriptor) {
			// HTML boilerplate start
			fwrite($outputFileDescriptor, "<!DOCTYPE html>\n\t<head>\n\t\t<title>Markdown File</title>\n\t</head>\n\t<body>\n");
			// loop through input file line by line
			while (!feof($inputFileDescriptor)) {
				$line = fgets($inputFileDescriptor, 4096);
				// write parsed markdown to html output file
				fwrite($outputFileDescriptor, $mdParser->parseString($line)."\n");
			}
			// HTML boilerplate end
			fwrite($outputFileDescriptor, "</body>\n</html>");
			// close output file
			fclose($outputFileDescriptor);
		} else {
			echo "Could not open output file.";
		}
	} else {
		while (!feof($inputFileDescriptor)) {
			$line = fgets($inputFileDescriptor, 4096);
			echo $mdParser->parseString($line)."\n";
		}
	}
	// close input file
	fclose($inputFileDescriptor);
} else {
	echo "Could not open input file.";
}

echo "Complete";

?>
