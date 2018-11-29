#!/usr/bin/php -q
<?php
include("includes/args_parser.php");
include("includes/markdown_parser.php");

$argsParser = new ArgsParser($_SERVER["argv"]);

$inputFilename = $argsParser->getAfter("-i");
$outputFilename = $argsParser->getAfter("-o");

echo $inputFilename;
// Open input (.md) file
$inputFileDescriptor = fopen($inputFilename, "r");
if ($inputFileDescriptor) {
	// Open output (.html) file
	$outputFileDescriptor = fopen($outputFilename, "w");
	if ($outputFileDescriptor) {
		// HTML boilerplate start
		fwrite($outputFileDescriptor, "<!DOCTYPE html>\n\t<head>\n\t\t<title>Markdown File</title>\n\t</head>\n\t<body>\n");
		// instantiate MarkdownParser
		$mdParser = new MarkdownParser();
		// loop through input file line by line
		while (!feof($inputFileDescriptor)) {
			$line = fgets($inputFileDescriptor, 4096);
			// write parsed markdown to html output file
			fwrite($outputFileDescriptor, $mdParser::parseString($line)."\n");
		}
		// HTML boilerplate end
		fwrite($outputFileDescriptor, "</body>\n</html>");
		// close output file
		fclose($outputFileDescriptor);
	} else {
		echo "Could not open output file.";
	}
	// close input file
	fclose($inputFileDescriptor);
} else {
	echo "Could not open input file.";
}

?>
