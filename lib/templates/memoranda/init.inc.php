<?php

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) die('Illegal entry point!');

// global $cfg, $ccms; // also available: $template_instance;



// make sure these are generated before anything else:
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/memoranda/style.css');

// hacky fix for certain bits of formatted content, i.e. where we use the famfamfam sprites in the content proper...
tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/templates/ccms/sprite.css');



?>