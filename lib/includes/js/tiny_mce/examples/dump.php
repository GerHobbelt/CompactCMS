<?php

error_reporting(E_ALL | E_STRICT);



define('DEVELOPMENT', 01);   // set to 01 / 1 to enable logging of each incoming event request.


// dumper useful in development
function MCE_vardumper($mgr = null, $action = null, $info = null, $filenamebase = null)
{
	if (DEVELOPMENT)
	{
		if (!is_string($filenamebase))
		{
			$filenamebase = basename(__FILE__);
		}

		// log request data:
		ob_start();
			if (0) // set to 'if (01)' if you want this bit dumped as well; fastest back-n-forth edit that way :-)
			{
				echo "\n\n_SERVER:\n";
				var_dump($_SERVER);
			}
			if (0)
			{
				echo "\n\n_ENV:\n";
				if (isset($_ENV)) var_dump($_ENV); else echo "(null)\n";
			}
			if (01)
			{
				echo "\n\n_GET:\n";
				if (isset($_GET)) var_dump($_GET); else echo "(null)\n";
			}
			if (01)
			{
				echo "\n\n_POST:\n";
				if (isset($_POST)) var_dump($_POST); else echo "(null)\n";
			}
			if (01)
			{
				echo "\n\n_REQUEST:\n";
				if (isset($_REQUEST)) var_dump($_REQUEST); else echo "(null)\n";
			}
			if (01)
			{
				echo "\n\n_FILES:\n";
				if (isset($_FILES)) var_dump($_FILES); else echo "(null)\n";
			}
			if (01)
			{
				echo "\n\n_COOKIE:\n";
				if (isset($_COOKIE)) var_dump($_COOKIE); else echo "(null)\n";
			}
			if (0)
			{
				echo "\n\n_SESSION:\n";
				if (isset($_SESSION)) var_dump($_SESSION); else echo "(null)\n";
			}
		$dump = ob_get_clean();
		static $count;
		if (!$count) $count = 1; else $count++;
		$dst = ((!empty($filenamebase) ? $filenamebase . '.' : '') . date('Ymd-His') . '.' . fmod(microtime(true), 1) . '-' . $action . '-' . $count . '.log');
		$dst = preg_replace('/[^A-Za-z0-9-_.]+/', '_', $dst);    // make suitable for filesystem
		return html_entity_decode(strip_tags($dump), ENT_NOQUOTES, 'UTF-8');
	}
	return '';
}



// log request data:
$dump = MCE_vardumper();

?>
<html>
<body>
	<h1>DUMP:</h1>
	<pre style="white-space: pre-wrap;" >
<?php echo htmlentities($dump, ENT_NOQUOTES, 'UTF-8'); ?>
	</pre>
</body>
</html>
