<?php 

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

send_response_status_header(403); 

echo '<p>' . $ccms['lang']['system']['error_403content'] . '</p>';


if (0)
{
	dump_request_to_logfile(array('invocation_mode' => get_interpreter_invocation_mode(),
							       'response(404)' => get_response_code_string(404),
								   'response(403)' => get_response_code_string(403),
								   'response(302)' => get_response_code_string(302)),
							true);
}


?>
