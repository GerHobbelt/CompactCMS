<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) die('Illegal entry point!');

// global $cfg, $ccms; // also available: $template_instance;



// make sure these are generated before anything else:
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/fireworks/style.css');
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/fireworks/layout.css');
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/fireworks/sprite.css');




?>