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
 * Processing save page (prev. handler.inc.php)
 *
 */
if($do_action == 'save-changes' && checkAuth())
{
	$page_id = getGETparam4Number('page_id');
	$row = $db->SelectSingleRow($cfg['db_prefix'].'pages', array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER)));
	if (!$row) $db->Kill();

	$owner = explode('||', strval($row->user_ids));

	if ($perm->is_level_okay('managePageEditing', $_SESSION['ccms_userLevel']) &&
		($row->iscoding != 'Y' || $perm->is_level_okay('managePageCoding', $_SESSION['ccms_userLevel'])) && // deny editing of code pages unless the user has PageCoding permissions
		 (!in_array($row->urlpage, $cfg['restrict']) || in_array($_SESSION['ccms_userID'], $owner))) // only the OWNER is ALWAYS allowed editing access for a restricted page! (unless the owner doesn't have code edit perms and it's a code page)
	{
		$active = $row->published;
		$name = $row->urlpage;
		if ($row->iscoding == 'Y')
		{
			// code pages: only for users with elevated rights, so we're okay with less filtering (none at all, in this case!)
			$type = 'code';
			$content = getPOSTparam4RAWCONTENT('content'); // accept ANYTHING: it's code, so can carry anything, including javascript and PHP code chunks!
		}
		else
		{
			$type = 'text';
			$content = getPOSTparam4RAWHTML('content'); // [i_a] must be RAW HTML, no htmlspecialchars(). Filtering required if malicious input risk expected.
		}
		$filename = BASE_PATH . '/content/'.$name.'.php';
		$keywords = getPOSTparam4DisplayHTML('keywords');


		if (is_writable_ex($filename))
		{
			if (!$handle = fopen($filename, 'w'))
			{
				die('[ERR105] '.$ccms['lang']['system']['error_openfile'].' ('.$filename.').');
			}
			if (fwrite($handle, $content) === FALSE)
			{
				die('[ERR106] '.$ccms['lang']['system']['error_write'].' ('.$filename.').');
			}
			fclose($handle);
		}
		else
		{
			die($ccms['lang']['system']['error_chmod']);
		}

		// Save keywords to database
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values['keywords'] = MySQL::SQLValue($keywords,MySQL::SQLVALUE_TEXT);

		if ($db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER))))
		{
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS <?php echo $ccms['lang']['backend']['administration']; ?></title>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	</head>

	<body>
		<div id="handler-wrapper" class="module">

			<?php
			if($active != 'Y')
			{
			?>
				<p class="notice"><?php $msg = explode('::', $ccms['lang']['hints']['published']); echo $msg[0].': <strong>'.strtolower($ccms['lang']['backend']['disabled']).'</strong>'; ?></p>
			<?php
			}
			?>
			<p class="success"><?php echo $ccms['lang']['editor']['savesuccess']; ?><em><?php echo $name; ?>.html</em>.</p>
			<hr/>
			<?php
			if($type == 'code')
			{
			?>
				<p><pre><?php echo htmlentities(file_get_contents($filename)); ?></pre></p>
			<?php
			}
			else /* if($type == 'text') */
			{
			?>
				<p><?php echo file_get_contents($filename); ?></p>
			<?php
			}

			$preview_checkcode = GenerateNewPreviewCode($page_id, null);
			?>
			<hr/>
			<p>
				<a href="../../../<?php echo $name; ?>.html?preview=<?php echo $preview_checkcode; ?>" class="external" target="_blank"><?php echo $ccms['lang']['editor']['preview']; ?></a>
			</p>
			<div class="right">
				<a class="button" href="editor.Manage.php?page_id=<?php echo $page_id; ?>&amp;action=edit"><span class="ss_sprite_16 ss_arrow_undo">&#160;</span><?php echo $ccms['lang']['editor']['backeditor']; ?></a>
				<a class="button" href="../../../admin/index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['closewindow']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['closewindow']; ?></a>
			</div>
		</div>
	<script type="text/javascript" src="../../../lib/includes/js/the_goto_guy.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
function confirmation()
{
	//var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	var answer = true;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", '<?php echo $name; ?>_ccms');
	}
	return false;
}
	</script>
	</body>
	</html>
	<?php
		}
		else
		{
			$db->Kill();
		}
	}
	else
	{
		die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
	}

	exit();
}



// when we get here, an illegal command was fed to us!
die_with_forged_failure_msg(__FILE__, __LINE__, "do_action=$do_action, checkAuth=".(1 * checkAuth()));

?>