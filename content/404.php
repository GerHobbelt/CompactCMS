<?php 

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

send_response_status_header(404); 

?>
<p>The requested file <strong><?php echo strlen($ccms['pagereq']) > 0 ? $ccms['pagereq'].'.html' : '&lt;unidentified&gt;'; ?></strong> could not be found.</p>

<?php
if (0)
{
	echo "<pre>invocation_mode = " . get_interpreter_invocation_mode() . "\n";
	echo "response(404) = '" . get_response_code_string(404) . "'\n";
	echo "response(403) = '" . get_response_code_string(403) . "'\n";
	echo "response(302) = '" . get_response_code_string(302) . "'\n";

	echo '<h1>$_SERVER</h1>';
	echo "<pre>";
	var_dump($_SERVER);
	echo "</pre>";
	echo '<h1>$_ENV</h1>';
	echo "<pre>";
	var_dump($_ENV);
	echo "</pre>";
	echo '<h1>$ccms</h1>';
	echo "<pre>";
	var_dump($ccms);
	echo "</pre>";
	echo '<h1>$cfg</h1>';
	echo "<pre>";
	var_dump($cfg);
	echo "</pre>";
}

?>
