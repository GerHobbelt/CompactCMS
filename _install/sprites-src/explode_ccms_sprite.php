#! /usr/bin/php
<?php 

/*
 generate a rename script for the icons based on the sprite.css fed to ARGV
*/

if ($argc < 3)
{
	var_dump($argv);
	echo "\narguments: {ccms-sprite.png} {dest-dir}\n";
	exit;
}

function mk_path($name)
{
	global $argv;
	
	$path = str_replace("\\", '/', $argv[2]);
	if (substr($path, -1, 1) != '/')
		$path .= '/';
	$path .= $name;
	return $path;
}


@mkdir($argv[2]);

$pos = 0;
$name = null;

$items = array(
	"external" => "10x18+0+159",
	/* "info" => "18x18+0+178",  // does not exist! */ 
	"ff" => "80x15+0+0",
	"ie" => "80x15+0+20",
	"opera" => "80x15+0+40",
	"safari" => "80x15+0+60",
	"chrome" => "80x15+0+80",
	/* "editinplace" => "18x18+0+100", // does not exist! */
	"edit" => "18x18+0+121",
	"logo" => "181x65+0+228",
	"twittlogo" => "26x24+0+200",
	"notify" => "64x62+0+510",
	"liveedit" => "18x18+0+121",
	"livefilter_add" => "18x18+0+780",
	"livefilter_add.hover" => "18x18+0+700",
	"livefilter_active" => "18x18+0+720",
	"livefilter_delete" => "18x18+0+740",
	"livefilter_remove" => "18x18+0+760",
);

foreach($items as $name => $item)
{
	$prefix = sprintf("ccms.%04d.", $pos++);
	echo "convert " . $argv[1] . " -crop " . $item . " " . mk_path($prefix.$name.".png") . "\n";
	exec("convert " . $argv[1] . " -crop " . $item . " " . mk_path($prefix.$name.".png"));
}
