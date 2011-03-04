<?php
error_reporting(E_STRICT);

fwrite(STDERR, memory_get_peak_usage(true)."\n");

require '../jsmin.php';
//echo JSMin::minify(file_get_contents('ext-all-debug.js'));
echo JSMin::minify(file_get_contents('issuecomment-829740.js'));

fwrite(STDERR, memory_get_peak_usage(true)."\n");
?>