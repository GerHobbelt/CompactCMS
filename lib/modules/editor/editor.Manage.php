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
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !CheckAuth())
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die($ccms['lang']['auth']['featnotallowed']);
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
	$page_id = getGETparam4Filename('page_id');
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
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS - <?php echo $ccms['lang']['editor']['editorfor'].' '.$name; ?></title>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<?php
	// Load TinyMCE (compressed for faster loading)
	if($cfg['wysiwyg'] && $iscoding != 'Y')
	{
	?>
		<!-- File uploader styles -->
		<link rel="stylesheet" media="all" type="text/css" href="../../../lib/includes/js/fancyupload/Css/FileManager.css,Additions.css" />
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
		<h2><?php echo $ccms['lang']['backend']['editpage']." $name<em>.html</em>"; ?></h2>
		<p><?php echo $ccms['lang']['editor']['instruction']; ?></p>

		<form action="editor.Process.php?page_id=<?php echo rm0lead($row->page_id); ?>&action=save-changes" method="post" name="save">
			<textarea id="content" name="content"><?php echo htmlspecialchars(trim($contents), ENT_COMPAT, 'UTF-8'); ?></textarea>
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
$js_files = array();
$js_files[] = $cfg['rootdir'] . 'lib/includes/js/the_goto_guy.js';
$js_files[] = $cfg['rootdir'] . 'lib/includes/js/mootools-core.js,mootools-more.js';
if($cfg['wysiwyg'] && $iscoding != 'Y')
{
	// -------------------------------------------------
	// Load TinyMCE (compressed for faster loading)
	$js_files = array_merge($js_files, generateJS4TinyMCEinit(0, 'content'));

	$driver_code = generateJS4TinyMCEinit(2, 'content');

	$starter_code = generateJS4TinyMCEinit(1, 'content');
}
else
{
	// -------------------------------------------------
	// Alternative to tinyMCE: load Editarea for code editing
	if ($cfg['USE_JS_DEVELOPMENT_SOURCES'])
	{
		$js_files[] = $cfg['rootdir'] . 'lib/includes/js/edit_area/edit_area_full.js';
	}
	else
	{
		$js_files[] = $cfg['rootdir'] . 'lib/includes/js/edit_area/edit_area_full.js';
	}

	$eaLanguage = $cfg['editarea_language'];
	$driver_code = <<<EOT
		editAreaLoader.init(
			{
				id: "content",
				is_multi_files: false,
				allow_toggle: false,
				word_wrap: true,
				start_highlight: true,
				language: "$eaLanguage",
				syntax: "html"
			});
EOT;
	$starter_code = null;
}

echo generateJS4lazyloadDriver($js_files, $driver_code, $starter_code);
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
header('Location: ' . makeAbsoluteURI($cfg['rootdir'] . 'lib/includes/auth.inc.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' )));
//die('status=error&action-was=' . $do_action . '&check=' . (1 * checkAuth()) . '&msg='.rawurlencode($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' ));
die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );

?>