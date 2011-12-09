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
We're rendering the admin page here, no need to load the user/viewer page content in sitemap.php, etc.
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');




/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !checkAuth())
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die_with_forged_failure_msg(__FILE__, __LINE__); // $ccms['lang']['auth']['featnotallowed']
}


$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');



// Get the number of users; this is used to dimension some user management window(s); also count INactive users!
$user_count = $db->SelectSingleValue($cfg['db_prefix'].'users', null, 'COUNT(userID)');
if ($db->ErrorNumber())
	$db->Kill();
$total_page_count = $db->SelectSingleValue($cfg['db_prefix'].'pages', null, 'COUNT(page_id)');
if ($db->ErrorNumber())
	$db->Kill();



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html id="admin_index_page" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>CompactCMS Administration</title>
	<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
	<link rel="icon" type="image/ico" href="../media/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../admin/img/styles/base.css,layout.css,editor.css,sprite.css" />
<!--
	<link rel="stylesheet" type="text/css" href="../lib/includes/js/mochaui/Source/Themes/default/css/Window.css,Taskbar.css"/>
-->
	<link rel="stylesheet" type="text/css" href="../admin/img/styles/last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../admin/img/styles/ie.css" />
	<![endif]-->
<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	<style type="text/css">
		#democlock_collective .clock
		{
			float:left;
			display:block;
			margin:10px;
			padding:10px;
			background-color:#eee;
			box-shadow: 3px 3px 3px #999; border-radius:5px;
			-moz-box-shadow: 3px 3px 3px #999; -moz-border-radius:5px;
			-webkit-box-shadow: 3px 3px 3px #999; -webkit-border-radius:5px;
		}
		#democlock_collective .skintitle
		{
			float:left;
			display:block;
			margin:10px;
			padding:10px;
			border: 3px solid black;
			background-color: #577a9e;
		}
	</style>
<?php
}
?>
</head>

<body id="desktop">
<div id='pageWrapper'><?php /* <-- required to ensure there are proper scrollbars in the MochaUI 'desktop' */ ?>
<div class="container-25">
	<?php // Top bar including status block ?>
	<div id="logo" class="span-5 colborder">
		<div class="sprite logo">
			<h1>CompactCMS <?php echo $ccms['lang']['backend']['administration']; ?></h1>
		</div>
		<p class="ss_has_sprite"><span class="ss_sprite_16 ss_world">&#160;</span><?php echo $cfg['sitename']; ?></p>
	</div>
	<div id="notify" class="span-12">
		<div class="rounded-border">
			<div class="header">
				<?php
				if($cfg['protect'])
				{
				?>
					<a class="right span-6" href="./includes/security.inc.php?do=logout"><span class="ss_sprite_16 ss_door_open">&#160;</span><?php echo $ccms['lang']['backend']['logout']; ?></a>
				<?php
				}
				?>
				<a id="clockLink" class="clock"><span class="ss_sprite_16 ss_clock">&#160;</span></a>
			</div>
			<div id="notify_icon">&#160;</div>
			<div id="notify_res">
				<?php
				/*
				 * Check latest version
				 */
				$version_recent = @file_get_contents('http://www.compactcms.nl/version/'.$v.'.txt');
				if(version_compare($version_recent, $v) != 1)
				{
					$version = $ccms['lang']['backend']['uptodate'];
				}
				else
				{
					$version = $ccms['lang']['backend']['outofdate'] . ' <a href="http://www.compactcms.nl/changes.html" class="external" rel="external">' . $ccms['lang']['backend']['considerupdate'] . '</a>.';
				}

				if(!empty($version_recent) && !empty($v) && $cfg['version'])
				{
				?>
					<p><?php echo $ccms['lang']['backend']['currentversion']." ".$v; ?>. <?php echo $ccms['lang']['backend']['mostrecent']." ".$version_recent; ?>.</p>
					<p class="versionstatus"><?php echo $ccms['lang']['backend']['versionstatus']." ".$version; ?></p>
				<?php
				}
				else
				{
					echo '<p class="error">'.$ccms['lang']['system']['error_versioninfo'].'</p>';
				}

				/*
				Show possibly incoming status messages:
				*/
				if (!empty($status_message))
				{
					echo '<p class="' . $status . '">'.$status_message.'</p>';
				}
				?>
			</div>
		</div>
	</div>
	<div id="advanced" class="prepend-1 span-6 last clear-right">
		<div class="rounded-border">
			<div class="header"><span class="ss_sprite_16 ss_user_red">&#160;</span><?php echo $ccms['lang']['backend']['hello']; ?> <?php echo $_SESSION['ccms_userFirst']; ?></div>
			<div id="advanced_res">
				<ul>
					<?php
					if($_SESSION['ccms_userLevel'] >= 4)
					{
					?>
						<li><a id="sys-perm" href="./includes/modules/permissions/permissions.Manage.php" rel="<?php echo $ccms['lang']['backend']['permissions']; ?>" class="tabs"><span class="ss_sprite_16 ss_group_key">&#160;</span><?php echo $ccms['lang']['backend']['permissions']; ?></a></li>
					<?php
					}
					if($perm->is_level_okay('manageOwners', $_SESSION['ccms_userLevel']))
					{
					?>
						<li><a id="sys-pow" href="./includes/modules/content-owners/content-owners.Manage.php" rel="<?php echo $ccms['lang']['backend']['contentowners']; ?>" class="tabs"><span class="ss_sprite_16 ss_folder_user">&#160;</span><?php echo $ccms['lang']['backend']['contentowners']; ?></a></li>
					<?php
					}
					if($perm->is_level_okay('manageTemplate', $_SESSION['ccms_userLevel']))  // [i_a] template dialog would still appear when turned off in permissions --> error message in that window anyway.
					{
					?>
						<li><a id="sys-tmp" href="./includes/modules/template-editor/template-editor.Manage.php" rel="<?php echo $ccms['lang']['backend']['templateeditor']; ?>" class="tabs"><span class="ss_sprite_16 ss_color_swatch">&#160;</span><?php echo $ccms['lang']['backend']['templateeditor']; ?></a></li>
					<?php
					}
					// if($perm->get('manageUsers') > 0)    -- [i_a] we'll always be able to 'manage' ourselves; at least the users.manage page can cope with that scenario - plus it's in line with the rest of the admin behaviour IMHO
					{
					?>
						<li><a id="sys-usr" href="./includes/modules/user-management/user-management.Manage.php" rel="<?php echo $ccms['lang']['backend']['usermanagement']; ?>" class="tabs"><span class="ss_sprite_16 ss_group">&#160;</span><?php echo $ccms['lang']['backend']['usermanagement']; ?></a></li>
					<?php
					}
					if($perm->is_level_okay('manageModBackup', $_SESSION['ccms_userLevel']))
					{
					?>
						<li><a id="sys-bck" href="./includes/modules/backup-restore/backup-restore.Manage.php" rel="<?php echo $ccms['lang']['backup']['createhd'];?>" class="tabs"><span class="ss_sprite_16 ss_drive_disk">&#160;</span><?php echo $ccms['lang']['backup']['createhd'];?></a></li>
					<?php
					}
					if($perm->is_level_okay('manageModTranslate', $_SESSION['ccms_userLevel']) && $cfg['IN_DEVELOPMENT_ENVIRONMENT'])
					{
					?>
						<li><a id="sys-tran" href="./includes/modules/translation/translation.Manage.php" rel="<?php echo $ccms['lang']['backend']['translation']; ?>" class="tabs"><span class="ss_sprite_16 ss_group_key">&#160;</span><?php echo $ccms['lang']['backend']['translation']; ?></a></li>
					<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>

<!--[if lt IE 7]>
<hr class="clear space" />
<![endif]-->

	<div id="load_notice" class="span-25 last clear">
	<fieldset>
		<legend><a rel="notice_wrapper"><span class="ss_sprite_16 ss_exclamation">&#160;</span><?php echo $ccms['lang']['backend']['warning']; ?></a></legend>
		<div id="notice_wrapper">
			<p class="center-text"><?php echo $ccms['lang']['backend']['js_loading']; ?></p>
		</div>
	</fieldset>
	</div>

	<?php
	// yak when the install directory is still there, due to us being a 'smart Alec' by saving an empty override file in there (/_install/install_check_override.txt):
	if ($cfg['install_dir_exists'])
	{
	?>
	<div id="install_dir_notice" class="span-25 last clear">
	<fieldset>
		<legend><a rel="notice_wrapper"><span class="ss_sprite_16 ss_exclamation">&#160;</span><?php echo $ccms['lang']['backend']['warning']; ?></a></legend>
		<div id="notice_wrapper">
			<p class="center-text"><?php echo $ccms['lang']['backend']['install_dir_exists']; ?></p>
		</div>
	</fieldset>
	</div>
	<?php
	}
	?>
	
	<div id="createnew" class="span-9 clear">
	<?php

	// Start main management section

	// Create new page
	if($perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
	{
	?>
	<fieldset>
		<legend><a class="toggle" rel="form_wrapper"><span class="ss_sprite_16 ss_add">&#160;</span><?php echo $ccms['lang']['backend']['createpage']; ?></a></legend>
		<div id="form_wrapper">
		<form method="post" id="addForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<p><?php echo $ccms['lang']['backend']['createtip']; ?></p>
			<div id="fields">
				<label for="urlpage"><?php echo $ccms['lang']['forms']['filename']; ?></label>
				<input class="required minLength:3 text" type="text" id="urlpage" name="urlpage" />
				<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['filename']; ?>">&#160;</span><br/>

				<label for="f_pt"><?php echo $ccms['lang']['forms']['pagetitle']; ?></label>
				<input class="required minLength:3 text" type="text" id="f_pt" name="pagetitle" />
				<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['pagetitle']; ?>">&#160;</span><br/>

				<label for="f_sh"><?php echo $ccms['lang']['forms']['subheader']; ?></label>
				<input class="required minLength:3 text" type="text" id="f_sh" name="subheader" />
				<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['subheader']; ?>">&#160;</span><br/>

				<label for="f_de"><?php echo $ccms['lang']['forms']['description']; ?></label>
				<textarea class="required minLength:3" id="f_de" name="description" rows="4" cols="30"></textarea>
				<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['description']; ?>">&#160;</span><br/>

				<?php
				// Modules which can create a page:
				$page_creators = array();
				for ($i = 0; $i < count($modules); $i++)
				{
					if (!$modules[$i]['modActive'] || !$modules[$i]['hasPageMaker'])
						continue;

					$page_creators[] = $modules[$i];
				}

				if(count($page_creators) > 0)
				{
				?>
				<label for="f_mod"><?php echo $ccms['lang']['forms']['module']; ?></label>
				<select class="text" name="module" id="f_mod" size="1">
					<option value="editor"><?php echo $ccms['lang']['forms']['contentitem']; ?></option>
					<optgroup label="<?php echo $ccms['lang']['forms']['additions']; ?>">
						<?php
						for ($i = 0; $i < count($page_creators); $i++)
						{
						?>
							<option value="<?php echo $page_creators[$i]['modName']; ?>"><?php echo $page_creators[$i]['modTitle']; ?></option>
						<?php
						}
						?>
					</optgroup>
				</select>
				<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['module']; ?>">&#160;</span><br/>
				<?php
				}
				?>

				<div id="editor-options">
					<div class="clearfix span-8 last">
						<label><?php echo $ccms['lang']['forms']['printable']; ?>?</label>
						<label for="f_pr1" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?>: </label>
							<input type="radio" id="f_pr1" checked="checked" name="printable" value="Y" />
						<label for="f_pr2" class="yesno"><?php echo $ccms['lang']['backend']['no']; ?>: </label>
							<input type="radio" id="f_pr2" name="printable" value="N" />
						<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['printable']; ?>">&#160;</span>
					</div>
					<div class="clearfix span-8 last">
						<label><?php echo $ccms['lang']['forms']['published']; ?>?</label>
						<label for="f_pu1" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?>: </label>
							<input type="radio" id="f_pu1" checked="checked" name="published" value="Y" />
						<label for="f_pu2" class="yesno"><?php echo $ccms['lang']['backend']['no']; ?>: </label>
							<input type="radio" id="f_pu2" name="published" value="N" />
						<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['published']; ?>">&#160;</span>
					</div>
					<div class="clearfix span-8 last">
						<label><?php echo $ccms['lang']['forms']['iscoding']; ?>?</label>
						<label for="f_cod" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?>: </label>
							<input type="radio" id="f_cod" name="iscoding" value="Y" />
						<label for="f_co2" class="yesno"><?php echo $ccms['lang']['backend']['no']; ?>: </label>
							<input type="radio" id="f_co2" checked="checked" name="iscoding" value="N" />
						<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['iscoding']; ?>">&#160;</span>
					</div>
				</div>

				<div class="right">
					<button type="submit" id="addbtn" name="submit"><span class="ss_sprite_16 ss_wand">&#160;</span><?php echo $ccms['lang']['forms']['createbutton']; ?></button>
				</div>
				<input type="hidden" name="form" value="create" />
			</div>
		</form>
		</div>
	</fieldset>
	<?php
	}
	else
	{
		// echo '<p class="ss_has_sprite"><span class="ss_sprite_16 ss_warning">&#160;</span>' . $ccms['lang']['backend']['createpage'] . ': ' . $ccms['lang']['auth']['featnotallowed'] . "</p>\n";
	}
	?>
	</div>

	<div id="menudepth" class="span-16 last">
	<?php

	// Manage menu depths & languages

	if($perm->is_level_okay('manageMenu', $_SESSION['ccms_userLevel']))
	{
	?>
	<fieldset>
		<legend><a class="toggle" rel="menu_wrapper"><span class="ss_sprite_16 ss_text_list_bullets">&#160;</span><?php echo $ccms['lang']['backend']['managemenu']; ?></a></legend>
		<div id="menu_wrapper">
		<p><?php echo $ccms['lang']['backend']['ordertip']; ?></p>
		<form method="post" id="menuForm" action="<?php echo $_SERVER['PHP_SELF'] ?>">
			<div id="menuFields">
				<!--spinner-->
			</div>
			<hr class="space"/>

			<div class="right">
				<a class="button" id="reorder_menu" title="<?php echo $ccms['lang']['hints']['reordercmdhelp']; ?>">
					<span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['reorderbutton']; ?>
				</a>
				<button type="submit" name="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['editor']['savebtn']; ?></button>
			</div>
			<input type="hidden" name="form" value="menuorder" />
		</form>
		</div>
	</fieldset>
	<?php
	}
	else
	{
		// echo '<p class="ss_has_sprite"><span class="ss_sprite_16 ss_warning">&#160;</span>' . $ccms['lang']['backend']['managemenu'] . ': ' . $ccms['lang']['auth']['featnotallowed'] . "</p>\n";
	}
	?>
	</div>
	<?php



	?>
	<div id="manage" class="span-25 last clear">
	<fieldset>
		<legend><a class="toggle" rel="filelist_wrapper"><span class="ss_sprite_16 ss_folder_database">&#160;</span><?php echo $ccms['lang']['backend']['managefiles']; ?></a></legend>
		<div id="filelist_wrapper">
		<p><?php echo $ccms['lang']['backend']['currentfiles']; ?></p>
		<form action="index.php" id="delete">
		<?php
		/*
		 * With lining the header texts with the data and everything, it's simply way too much hassle to keep them in separate tables:
		 * you never get the alignment right.
		 *
		 * So starting with this edition, the header is regenerated with each reload. This has an impact on the filters, but we can fix them
		 * easily there by re-registering those nodes in the JS code.
		 */
		?>
		<div id="dyn_list">
			<?php echo $ccms['lang']['system']['error_misconfig']; ?> <a href="http://community.compactcms.nl/forum/"><strong><?php echo $ccms['lang']['backend']['see_forum']; ?></strong></a>.
			<!--spinner-->
		</div>
		<table width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<?php
				if($perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
				{
				?>
					<th class="span-17 text-left">
						<input type="hidden" name="form" value="delete" />
						<input type="hidden" id="ad_msg01" value="<?php echo $ccms['lang']['backend']['confirmdelete']; ?>" />
						<button type="submit" name="btn_del"><span class="ss_sprite_16 ss_bin_empty">&#160;</span><?php echo $ccms['lang']['backend']['delete']; ?></button>
					</th>
				<?php
				}
				else
				{
				?>
					<th class="span-17">&#160;</th>
				<?php
				}
				?>
				<th class="span-4"><div style="background-color: #CDE6B3; text-align: center; padding-left: 20px; padding-right: 20px;"><span class="ss_sprite_16 ss_accept">&#160;</span><?php echo $ccms['lang']['backend']['active']; ?></div></th>
				<th class="span-4 last"><div style="background-color: #F2D9DE; text-align: center; padding-left: 20px; padding-right: 20px;"><span class="ss_sprite_16 ss_stop">&#160;</span><?php echo $ccms['lang']['backend']['disabled']; ?></div></th>
			</tr>
		</table>
		</form>
		</div>
	</fieldset>
	</div>

	<div id='debugMsg' class="span-25 last clear">

<?php

if (0)
{
	dump_request_to_logfile(null, true);
}

if (0)
{
	require_once(BASE_PATH . '/lib/includes/browscap/Browscap.php');

	$bc = new Browscap(BASE_PATH . '/lib/includes/cache');
	$bc->localFile = BASE_PATH . '/lib/includes/browscap/browscap/php_browscap.ini';
	$bc = $bc->getBrowser();

	echo '<h1>$bc</h1>';
	echo "<pre>";
	var_dump($bc);
	echo "</pre>";
}
?>
	</div>


<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	<div id="democlock_collective" class="span-25 last clear"> </div>

	<textarea id="jslog" class="log span-25 last" readonly="readonly">
	</textarea>
<?php
}
?>

	<?php // Footer block ?>
	<div id="footer" class="span-25 last clear">
		<div class="prepend-11 span-11 colborder">
			&copy; 2008 - <?php echo date('Y'); ?> <a href="http://www.compactcms.nl">CompactCMS.nl</a>. <?php echo $ccms['lang']['system']['message_rights']; ?>.<br/>
			<em><?php echo $ccms['lang']['backend']['gethelp']; ?></em>
		</div>
		<div class="span-1 last"><a href="http://twitter.com/compactcms" class="sprite twittlogo" title="Follow at Twitter"></a></div>
		<div style="margin-top: 10px;" class="prepend-13 span-12">
			<span class="sprite ff" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Firefox"></span>
			<span class="sprite ie" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Internet Explorer 7+"></span>
			<span class="sprite opera" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Opera"></span>
			<span class="sprite chrome" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Chrome"></span>
			<span class="sprite safari" title="<?php echo $ccms['lang']['system']['message_compatible']; ?> Safari"></span>
		</div>
	</div>
</div>
</div>

<?php // Dock block ?>
<div id="taskbarWrapper">
	<div id="taskbar">
		<div id="taskbarPlacement"></div>
		<div id="taskbarAutoHide"></div>
		<div id="taskbarSort"><div id="taskbarClear" class="clear"></div></div>
	</div>
</div>


<script type="text/javascript" charset="utf-8">

function get_admin_user_count()
{
	return <?php printf("%d", $user_count); ?>;
}
function get_total_page_count()
{
	return <?php printf("%d", $total_page_count); ?>;
}


<?php
$js_files = array();
$js_files[] = '../lib/includes/js/excanvas.js?only-when=%3d%3d+IE';
$js_files[] = '../lib/includes/js/mootools-core.js,mootools-more.js';
/*--MOCHAUI--*/
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
	$js_files[] = '../lib/includes/js/mochaui/Source/dummy.js,' .

   'Core/Core.js,' .
   'Core/Canvas.js,' .
   'Core/Content.js,' .
   'Core/persist.js,' .
   'Core/themes.js,' .
/*

													'Core/core.js,' .
													'Core/create.js,' .
													'Core/require.js,' .
													'Core/canvas.js,' .
													'Core/content.js,' .
													'Core/persist.js,' .
													'Core/themes.js,' .
*/
													'Controls/desktop/desktop.js,' .
													'Controls/panel/panel.js,' .
													'Controls/column/column.js,' .
													'Controls/taskbar/taskbar.js,' .
													'Controls/window/window.js,' .
													'Controls/window/modal.js';
}
else
{
	$js_files[] = '../lib/includes/js/mochaUI.js';
}
/* $js_files[] = '../lib/includes/js/mochaui/Source/Utility/window-from-html.js'; */
$js_files[] = '../lib/includes/js/Fx.CmsAdminSlide.js';
$js_files[] = '../lib/includes/js/common.js';

$cfg_root_dir = $cfg['rootdir'];
$driver_code = <<<EOT42
	//if (typeof lazyloading_commonJS_done !== 'undefined')
	try
	{
		lazyloading_commonJS_done("$cfg_root_dir");
	}
	catch(e)
	{
		alert('lazyload sequence b0rked, probably due to some broken JS being loaded in this sequence. Check the log.');
	}
EOT42;

echo generateJS4lazyloadDriver($js_files, $driver_code);
?>
</script>
<script type="text/javascript" src="../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>