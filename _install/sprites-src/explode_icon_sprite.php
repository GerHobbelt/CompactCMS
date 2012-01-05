#! /usr/bin/php
<?php
/*
 generate a rename script for the icons based on the sprite.css fed to ARGV
*/

if ($argc < 4)
{
	var_dump($argv);
	echo "\narguments: {sprite.css} {icon-sprites.png} {dest-dir}\n";
	exit;
}

function mk_path($name)
{
	global $argv;
	
	$path = str_replace("\\", '/', $argv[3]);
	if (substr($path, -1, 1) != '/')
		$path .= '/';
	$path .= $name;
	return $path;
}

$content = @file_get_contents($argv[1]);
if (!$content) die("cannot read CSS file\n");

$content = str_replace("{", "\n", $content);
$content = str_replace("}", "\n", $content);
$content = str_replace(";", ";\n", $content);
$content = str_replace("//", "\n//", $content);
$content = str_replace("/*", "\n/*", $content);
$content = str_replace("*/", "*/\n", $content);

@mkdir($argv[3]);

$idx = 0;
$name = null;
$lines = explode("\n", $content);
foreach($lines as $line)
{
	if (preg_match('/(.ss_.+)/', $line, $matches))
	{
		$name = trim(trim($matches[1]), '.');
		continue;
	}
	if (preg_match('/background-position:\s*0px\s+([-0-9]+)px;/', $line, $matches))
	{
		$idx = -intval($matches[1]);
		$pos = intval($idx / 18);
		if ($idx) $idx--;
		
		$prefix = sprintf("%04d.", $pos);
		echo "convert " . $argv[2] . " -crop 18x18+0+" . $idx . " " . mk_path($prefix.$name.".png") . "\n";
		exec("convert " . $argv[2] . " -crop 18x18+0+" . $idx . " " . mk_path($prefix.$name.".png"));
	}
}
