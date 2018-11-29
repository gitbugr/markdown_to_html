#!/usr/bin/php -q
<?php
include("includes/args_parser.php");
include("includes/markdown_parser.php");
include("drivers/buffer.php");
include("drivers/file.php");

$argsParser = new ArgsParser($_SERVER["argv"]);

$inputFilename = $argsParser->getAfter("-i", true);
$outputFilename = $argsParser->getAfter("-o");

$driver = new BufferDriver();
if ($outputFilename) {
	$driver = new FileDriver($outputFilename);
}

// Open input (.md) file
$inputFileDescriptor = fopen($inputFilename, "r");
if ($inputFileDescriptor) {
	// instantiate MarkdownParser
	$mdParser = new MarkdownParser();
	// HTML boilerplate start
	$driver->write($outputFileDescriptor, "<!DOCTYPE html>\n\t<head>\n\t\t<title>Markdown File</title>\n\t</head>\n\t<body>\n");
	// loop through input file line by line
	while (!feof($inputFileDescriptor)) {
		$line = fgets($inputFileDescriptor, 4096);
		$driver->write($mdParser->parseString($line)."\n");
	}
	// HTML boilerplate end
	$driver->write($outputFileDescriptor, "</body>\n</html>");
	// close output buffer
	$driver->close();
	// close input file
	fclose($inputFileDescriptor);
} else {
	echo "Could not open input file.";
}

echo "Complete";

?>
