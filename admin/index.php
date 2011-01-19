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
// /*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');   [i_a] loaded by process.inc.php anyway
/*MARKER*/require_once(BASE_PATH . '/admin/includes/process.inc.php');


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !CheckAuth()) 
{
	// this situation should've caught inside process.inc.php above! This is just a safety measure here.
	die($ccms['lang']['auth']['featnotallowed']); 
}



// Get the number of users; this is used to dimension some user management window(s); also count INactive users!
$user_count = $db->SelectSingleValue($cfg['db_prefix'].'users', null, 'COUNT(userID)');
if ($db->ErrorNumber()) 
	$db->Kill();
$total_page_count = $db->SelectSingleValue($cfg['db_prefix'].'pages', null, 'COUNT(page_id)');
if ($db->ErrorNumber()) 
	$db->Kill();



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<title>CompactCMS Administration</title>
	<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
	<link rel="icon" type="image/ico" href="../media/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="img/styles/base.css,layout.css,editor.css,sprite.css" />
<!--
	<link rel="stylesheet" type="text/css" href="../lib/includes/js/mochaui/Source/Themes/default/css/Window.css,Taskbar.css"/>
-->
	<link rel="stylesheet" type="text/css" href="img/styles/last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="img/styles/ie.css" />
	<![endif]-->
	<style type="text/css">
		#democlock_collective .clock 
		{
			float:left; 
			display:block; 
			margin:10px; 
			padding:10px; 
			background-color:#eee;
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
				if(!empty($version_recent) && !empty($v) && $cfg['version']) 
				{ 
				?>
					<p><?php echo $ccms['lang']['backend']['currentversion']." ".$v; ?>. <?php echo $ccms['lang']['backend']['mostrecent']." ".$version_recent; ?>.</p>
					<p class="versionstatus"><?php echo $ccms['lang']['backend']['versionstatus']." ".$version; ?></p>
				<?php 
				} 
				else 
					echo '<p>'.$ccms['lang']['system']['error_versioninfo'].'</p>'; 
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
					if($_SESSION['ccms_userLevel']>=4) 
					{ 
					?>
						<li><a id="sys-perm" href="./includes/modules/permissions/permissions.Manage.php" rel="<?php echo $ccms['lang']['backend']['permissions']; ?>" class="tabs"><span class="ss_sprite_16 ss_group_key">&#160;</span><?php echo $ccms['lang']['backend']['permissions']; ?></a></li>
					<?php 
					} 
					if($perm['manageOwners']>0 && $_SESSION['ccms_userLevel']>=$perm['manageOwners']) 
					{ 
					?>
						<li><a id="sys-pow" href="./includes/modules/content-owners/content-owners.Manage.php" rel="<?php echo $ccms['lang']['backend']['contentowners']; ?>" class="tabs"><span class="ss_sprite_16 ss_folder_user">&#160;</span><?php echo $ccms['lang']['backend']['contentowners']; ?></a></li>
					<?php 
					} 
					if($perm['manageTemplate']>0 && $_SESSION['ccms_userLevel']>=$perm['manageTemplate'])  // [i_a] template dialog would still appear when turned off in permissions --> error message in that window anyway.
					{ 
					?>
						<li><a id="sys-tmp" href="./includes/modules/template-editor/backend.php" rel="<?php echo $ccms['lang']['backend']['templateeditor']; ?>" class="tabs"><span class="ss_sprite_16 ss_color_swatch">&#160;</span><?php echo $ccms['lang']['backend']['templateeditor']; ?></a></li>
					<?php 
					} 
					// if($perm['manageUsers']>0)    -- [i_a] we'll always be able to 'manage' ourselves; at least the users.manage page can cope with that scenario - plus it's in line with the rest of the admin behaviour IMHO
					{ 
					?>
						<li><a id="sys-usr" href="./includes/modules/user-management/backend.php" rel="<?php echo $ccms['lang']['backend']['usermanagement']; ?>" class="tabs"><span class="ss_sprite_16 ss_group">&#160;</span><?php echo $ccms['lang']['backend']['usermanagement']; ?></a></li>
					<?php 
					} 
					if($perm['manageModBackup']>0 /* && $_SESSION['ccms_userLevel']>=$perm['manageModBackup'] */ ) 
					{ 
					?>
						<li><a id="sys-bck" href="./includes/modules/backup-restore/backend.php" rel="<?php echo $ccms['lang']['backup']['createhd'];?>" class="tabs"><span class="ss_sprite_16 ss_drive_disk">&#160;</span><?php echo $ccms['lang']['backup']['createhd'];?></a></li>
					<?php 
					} 
					if($_SESSION['ccms_userLevel']>=4 && $cfg['IN_DEVELOPMENT_ENVIRONMENT']) 
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

	<div id="createnew" class="span-9 clear">
	<?php 
	
	// Start main management section 
	
	// Create new page 
	if($_SESSION['ccms_userLevel']>=$perm['managePages']) 
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
				// Modules
				if(count($modules)>0) 
				{ 
				?>
				<label for="f_mod"><?php echo $ccms['lang']['forms']['module']; ?></label>
				<select class="text" name="module" id="f_mod" size="1">
					<option value="editor"><?php echo $ccms['lang']['forms']['contentitem']; ?></option>
					<optgroup label="<?php echo $ccms['lang']['forms']['additions']; ?>">
						<?php //
						for ($i=0; $i<count($modules); $i++) 
						{ 
						?>
							<option value="<?php echo $modules[$i]['modName'];?>"><?php echo $modules[$i]['modTitle']; ?></option>
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
					<div class="clearfix span-8">
						<label><?php echo $ccms['lang']['forms']['printable']; ?>?</label> 
						<label for="f_pr1" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?>: </label>
							<input type="radio" id="f_pr1" checked="checked" name="printable" value="Y" />  
						<label for="f_pr2" class="yesno"><?php echo $ccms['lang']['backend']['no']; ?>: </label>
							<input type="radio" id="f_pr2" name="printable" value="N" />
						<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['printable']; ?>">&#160;</span>
					</div>
					<div class="clearfix span-8">
						<label><?php echo $ccms['lang']['forms']['published']; ?>?</label> 
						<label for="f_pu1" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?>: </label>
							<input type="radio" id="f_pu1" checked="checked" name="published" value="Y" />  
						<label for="f_pu2" class="yesno"><?php echo $ccms['lang']['backend']['no']; ?>: </label>
							<input type="radio" id="f_pu2" name="published" value="N" />
						<span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['published']; ?>">&#160;</span>
					</div>
					<div class="clearfix span-8">
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
	?>
		<p class="ss_has_sprite"><span class="ss_sprite_16 ss_warning">&#160;</span><?php echo $ccms['lang']['auth']['featnotallowed']; ?></p>
	<?php 
	} 
	?>
	</div>

	<div id="menudepth" class="span-16 last clear-right">
	<?php 

	// Manage menu depths & languages 
	
	if($_SESSION['ccms_userLevel']>=$perm['manageMenu']) 
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
	?>
		<p class="ss_has_sprite"><span class="ss_sprite_16 ss_warning">&#160;</span><?php echo $ccms['lang']['auth']['featnotallowed']; ?></p>
	<?php 
	} 
	?>
	</div>
	<?php 
	

		
	?>
	<div id="manage" class="span-25 last">
	<fieldset>
		<legend><a class="toggle" rel="filelist_wrapper"><span class="ss_sprite_16 ss_folder_database">&#160;</span><?php echo $ccms['lang']['backend']['managefiles']; ?></a></legend>
		<div id="filelist_wrapper">
		<p><?php echo $ccms['lang']['backend']['currentfiles']; ?></p>
		<form action="index.php" id="delete">
		<?php
		/*
		 * With lining the header texts with the data and everything, it's simply way too much hassle to keep them in separate tables:
		 * you never get the laignment right.
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
				if($_SESSION['ccms_userLevel']>=$perm['managePages']) 
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

	<div id='debugMsg' class="span-25">

<?php

if (0)
{
	global $_SERVER;
	global $_ENV;
	global $ccms;
	global $cfg;

	echo '<h1>$_SERVER</h1>';
	echo "<pre>";
	var_dump($_SERVER);
	echo "</pre>";
	echo '<h1>$_ENV</h1>';
	echo "<pre>";
	var_dump($_ENV);
	echo "</pre>";
	echo '<h1>$_SESSION</h1>';
	echo "<pre>";
	var_dump($_SESSION);
	echo "</pre>";
	echo '<h1>$ccms</h1>';
	echo "<pre>";
	var_dump($ccms);
	echo "</pre>";
	echo '<h1>$cfg</h1>';
	echo "<pre>";
	var_dump($cfg);
	echo "</pre>";
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
	<div id="democlock_collective" class="span-25 clear"> </div>
	
	<textarea id="jslog" class="log span-25" readonly="readonly">
	</textarea>
<?php
}
?>
	
	<?php // Footer block ?>
	<div id="footer" class="span-25 clear">
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
<div id="dockWrapper">
	<div id="dock">
		<div id="dockPlacement"></div>
		<div id="dockAutoHide"></div>
		<div id="dockSort"><div id="dockClear" class="clear"></div></div>
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



var jsLogEl = document.getElementById('jslog');
var js = [
	'../lib/includes/js/excanvas.js?only-when=%3d%3d+IE',
	'../lib/includes/js/mootools-core.js,mootools-more.js',
	/*--MOCHAUI--*/
<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	'../lib/includes/js/mochaui/Source/dummy.js,Core/Core.js,Core/Canvas.js,Core/Content.js,Core/Desktop.js,Controls/column/Column.js,Controls/panel/Panel.js,Controls/taskbar/Taskbar.js,Controls/window/Window.js,Controls/window/Modal.js,Core/Themes.js',
<?php
}
else
{
?>
	'../lib/includes/js/mochaUI.js',
<?php
}
?>
	/* '../lib/includes/js/mochaui/Source/Utility/window-from-html.js', */
	'../lib/includes/js/common.js'
	];

function jsComplete(user_obj, lazy_obj)
{
	var stop_loading = (lazy_obj.pending_count == 0 && lazy_obj.type !== 'css');
	
	//alert('done count: ' + lazy_obj.done_count + ', todo count: ' + lazy_obj.todo_count);
    if (lazy_obj.todo_count)
	{
		/* nested invocation of LazyLoad added one or more sets to the load queue */
		jslog('Another set of JS files is going to be loaded next! Todo count: ' + lazy_obj.todo_count + ', Next up: '+ lazy_obj.load_queue['js'][0].urls);
		return false;
	}
	else
	{
		jslog('All JS has been loaded!');
	}

	// window.addEvent('domready',function()
	//{
		lazyloading_commonJS_done("<?php echo $cfg['rootdir']; ?>");
	//});
	
	return stop_loading;
}


function jslog(message) 
{
	if (jsLogEl)
	{
		jsLogEl.value += "[" + (new Date()).toTimeString() + "] " + message + "\r\n";
	}
}

/* the magic function which will start it all, thanks to the augmented lazyload.js: */
function ccms_lazyload_setup_GHO()
{
	jslog('loading JS (sequential calls)');

	LazyLoad.js(js, jsComplete);
}


</script>
<script type="text/javascript" src="../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>