<?php
/**
 * Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
 *
 * Last changed: $LastChangedDate$
 * @author $Author$
 * @version $Revision$
 * @package CompactCMS.nl
 * @license GNU General Public License v3
 *
 * This file is part of CompactCMS.
 *
 * CompactCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CompactCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * A reference to the original author of CompactCMS and its copyright
 * should be clearly visible AT ALL TIMES for the user of the back-
 * end. You are NOT allowed to remove any references to the original
 * author, communicating the product to be your own, without written
 * permission of the original copyright owner.
 *
 * You should have received a copy of the GNU General Public License
 * along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * > Contact me for any inquiries.
 * > E: Xander@CompactCMS.nl
 * > W: http://community.CompactCMS.nl/forum
**/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc.
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

class FbX extends CcmsAjaxFbException {}; // nasty way to do 'shorthand in PHP -- I do miss my #define macros! :'-|

// Some security functions


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !checkAuth())
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die_with_forged_failure_msg(__FILE__, __LINE__); // $ccms['lang']['auth']['featnotallowed']
}


// Prevent PHP warning by setting default (null) values
$do_action = getGETparam4IdOrNumber('action');









/**
 *
 * Generate the WYSIWYG or code editor for editing purposes (prev. editor.php)
 *
 */
if($do_action == 'edit' && $_SERVER['REQUEST_METHOD'] == 'GET' && checkAuth())   // action=edit
{
	// Set the necessary variables
	$page_id = getGETparam4Number('page_id');
	$row = $db->SelectSingleRow($cfg['db_prefix'].'pages', array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER)));
	if (!$row) $db->Kill();

	$owner = explode('||', strval($row->user_ids));

	if ($perm->is_level_okay('managePageEditing', $_SESSION['ccms_userLevel']) &&
		($row->iscoding != 'Y' || $perm->is_level_okay('managePageCoding', $_SESSION['ccms_userLevel'])) && // deny editing of code pages unless the user has PageCoding permissions
		 (!in_array($row->urlpage, $cfg['restrict']) || in_array($_SESSION['ccms_userID'], $owner))) // only the OWNER is ALWAYS allowed editing access for a restricted page! (unless the owner doesn't have code edit perms and it's a code page)
	{
		$iscoding = $row->iscoding;
		$active = $row->published;
		$name = $row->urlpage;
		$filename = BASE_PATH . '/content/'.$name.'.php';

if (0) // TODO?
{
		// Check for editor.css in template directory
		$template = $row->variant;
}

		// Check for filename
		if(!empty($filename))
		{
			$handle = @fopen($filename, 'r');
			if ($handle)
			{
				// PHP5+ Feature
				$contents = stream_get_contents($handle);
				if (0)
				{
					// PHP4 Compatibility
					$flen = filesize($filename);
					if ($flen > 0)
					{
						$contents = fread($handle, $flen);
					}
				}
				fclose($handle);
				$contents = str_replace('<br />', '<br>', $contents);
			}
			else
			{
				die($ccms['lang']['system']['error_deleted']);
			}
		}

		// Get keywords for current file
		$keywords = $row->keywords;

		
		$textarea_id = str2variablename('page_' . $page_id);
		
		// blow away the resize cookie: expire it 10 hours ago
		setcookie ('TinyMCE_' . $textarea_id . '_size', '', time() - 36000);
		
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS - <?php echo $ccms['lang']['editor']['editorfor'] . ' ' . $name; ?></title>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
		<?php
		// Load TinyMCE (compressed for faster loading)
		if($cfg['wysiwyg'] && $iscoding != 'Y')
		{
		?>
			<!-- File uploader styles -->
			<link rel="stylesheet" media="all" type="text/css" href="../../../lib/includes/js/mootools-filemanager/Assets/js/milkbox/css/milkbox.css" />
			<link rel="stylesheet" media="all" type="text/css" href="../../../lib/includes/js/mootools-filemanager/Assets/Css/FileManager.css,Additions.css" />
		<?php
		}
		// else : load Editarea for code editing
		?>
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/ie.css" />
	<![endif]-->
	</head>

	<body>
	<div class="module" id="edit-page">
		<h2><?php echo $ccms['lang']['backend']['editpage']. ' ' . $name . '<em>.html</em>'; ?></h2>
		<p><?php echo $ccms['lang']['editor']['instruction']; ?></p>

		<form action="editor.Process.php?page_id=<?php echo rm0lead($row->page_id); ?>&action=save-changes" method="post" name="save">
			<textarea id="<?php echo $textarea_id; ?>" name="content" style="width: 100%"><?php echo htmlspecialchars(trim($contents), ENT_COMPAT, 'UTF-8'); ?></textarea>
			<!--<br/>-->
			<label for="keywords"><?php echo $ccms['lang']['editor']['keywords']; ?></label>
			<input type="input" class="text span-25" maxlength="250" name="keywords" value="<?php echo $keywords; ?>" id="keywords">
			<div class="right">
				<button type="submit" name="do" id="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['editor']['savebtn']; ?></button>
				<a class="button" href="../../../admin/index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
			</div>
		</form>

<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	<textarea id="jslog" class="log span-25" readonly="readonly">
	</textarea>
<?php
}
?>

	</div>
	<script type="text/javascript">
function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", '<?php echo $name; ?>_ccms');
	}
	return false;
}




<?php
		$js_files = array(
			$cfg['rootdir'] . 'lib/includes/js/the_goto_guy.js',
			$cfg['rootdir'] . 'lib/includes/js/mootools-core.js,mootools-more.js'
		);

		if($cfg['wysiwyg'] && $iscoding != 'Y')
		{
			// -------------------------------------------------
			// Load TinyMCE (compressed for faster loading)
			
			$MCEcodegen = new tinyMCEcodeGen($textarea_id, array(array('FileManager' => array())));

			$js_files = array_merge($js_files, $MCEcodegen->get_JSheaderfiles());
			$js_files[] = $cfg['rootdir'] . 'lib/includes/js/dummy.js?cb=exec_GHO';

			$starter_code = $MCEcodegen->genStarterCode();

			$driver_code = $MCEcodegen->genDriverCode();

			$extra_functions_code = $MCEcodegen->genExtraFunctionsCode();

			$extra_functions_code .= <<<EOT42

function exec_GHO()
{
	//alert('exec_GHO');

$driver_code
}

EOT42;

			$driver_code = <<<EOT42

	//alert('DOMready equivalent!');

EOT42;
		}
		else
		{
			// -------------------------------------------------
			// Alternative to tinyMCE: load Editarea for code editing
			if ($cfg['USE_JS_DEVELOPMENT_SOURCES'])
			{
				$js_files[] = $cfg['rootdir'] . 'lib/includes/js/edit_area/edit_area_ccms.js';
			}
			else
			{
				$js_files[] = $cfg['rootdir'] . 'lib/includes/js/edit_area/edit_area_ccms.js';
			}

			/*
			be aware that the edit_area code assumes a 'onLoad' event will be triggered AFTER it is
			loaded, which is ONLY PROBABLY TRUE when the edit_area code is loaded by <script> tags
			in the page header or HTML itself and NOT TRUE when edit_Area itself is loaded through
			a lazyloader, like we do.

			Hence, we need to execute the edit_area onLoad event code manually at a time when we
			can be certain the edit_area code is really loaded.
			That's what the 'loaded' check and call in the code below is for.
			*/
			$eaLanguage = $cfg['editarea_language'];
			$EAsyntax = cvt_extension2EAsyntax($filename);
			$driver_code = <<<EOT42

	if (editAreaLoader.win != "loaded")
	{
		editAreaLoader.window_loaded();
	}

	/*
	resize event has the problem that it is triggered continually when in IE (and tests reveal it's similar in FF3)
	and we do NOT want to spend CPU cycles on repeated updates of the MUI window sizes all the time, so we follow the
	advice found here:

	http://mbccs.blogspot.com/2007/11/fixing-window-resize-event-in-ie.html
	http://mootools-users.660466.n2.nabble.com/Moo-Detecting-window-resize-td3713058.html
	*/
	var resizeTimeout;

	var realResize = function(){

		//alert('editor: resize event');
	};

	window.addEvent('resize', function(e){
		clearTimeout(resizeTimeout);
		resizeTimeout = realResize.delay(200, this);
	});

	// initialisation

	// make sure we only specify a /supported/ syntax; if we spec something else, edit_area will NOT show up!
	editAreaLoader.init(
		{
			id: "{$textarea_id}",
			is_multi_files: false,
			allow_toggle: false,
			word_wrap: true,
			start_highlight: true,
			language: "{$eaLanguage}",
			syntax: "{$EAsyntax}",
			ignore_unsupported_syntax: true
		});

EOT42;

		$starter_code = null;
		$extra_functions_code = null;
	}

	echo generateJS4lazyloadDriver($js_files, $driver_code, $starter_code, $extra_functions_code);
?>
	</script>
	<script type="text/javascript" src="../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
	</body>
	</html>
<?php
	}
	else
	{
		die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
	}
	exit();
}







// when we get here, an illegal command was fed to us!
die_with_forged_failure_msg(__FILE__, __LINE__);

?>