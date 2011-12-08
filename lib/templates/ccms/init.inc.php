<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) die('Illegal entry point!');

// global $cfg, $ccms; // also available: $template_instance;



// make sure these are generated before anything else:
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/base.css');
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/layout.css');

if ($ccms['printing'] == 'Y')
{
	tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/printing.css');
}

tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/sprite.css');
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/last_minute_fixes.css');
// IE only:
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/ie.css?only-when=%3d%3d+IE');



/*
When we arrive here, the page content has not yet been produced, but the menu structure does exist.

Hence we may opt to manipulate the menu(s) for custom output formats / rendering...

The code below is an example.
*/
for ($i = 1; $i <= MENU_TARGET_COUNT; $i++)
{
	if (!isset($ccms['structure' . $i]))
		continue;
		
	// parse menu structure into XML struct:
	$menu_xml = simplexml_load_string($ccms['structure' . $i]);

	echo '<h1>raw</h1><pre>';
	print_r(htmlentities($ccms['structure' . $i]));
	echo '</pre><hr>';
	echo '<h1>XML</h1><pre>';
	print_r($menu_xml);
	echo '</pre><hr>';
	echo '<h1>XML</h1><pre>';
	print_r($menu_xml->asXML());
	echo '</pre><hr>';
}

?>