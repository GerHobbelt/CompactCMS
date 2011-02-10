<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) die('Illegal entry point!');

// global $cfg, $ccms; // also available: $template_instance;



// make sure these are generated before anything else:
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/html5/style.css');
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/html5/layout.css');
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/html5/sprite.css');



// Mark the JS sourcefiles needed by this template itself:
//if (IE)
//{
//  $ccms['JS.required_files']['http://html5shiv.googlecode.com/svn/trunk/html5.js'] = true;
//}
//$ccms['JS.required_files']['html5.js?only-when=%3d%3d+IE'] = 0; // the first to load!


?>