<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:   CompactCMS - v 1.4.2

This file is part of CompactCMS.

CompactCMS is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

CompactCMS is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

A reference to the original author of CompactCMS and its copyright
should be clearly visible AT ALL TIMES for the user of the back-
end. You are NOT allowed to remove any references to the original
author, communicating the product to be your own, without written
permission of the original copyright owner.

You should have received a copy of the GNU General Public License
along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.

> Contact me for any inquiries.
> E: Xander@CompactCMS.nl
> W: http://community.CompactCMS.nl/forum
************************************************************ */

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc.
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

// Set default variables


$do = getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');

// Open recordset for specified user
$userID = getGETparam4Number('userID');

if($userID > 0)
{
	$row = $db->SelectSingleRow($cfg['db_prefix'].'users', array('userID' => MySQL::SQLValue($userID, MySQL::SQLVALUE_NUMBER)));
	if (!$row) $db->Kill($ccms['lang']['system']['error_general']);
}
else
{
	die($ccms['lang']['system']['error_general']);
}




if(isset($_SESSION['rc1']) && !empty($_SESSION['rc2']) && checkAuth())
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Edit users</title>
	<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/ie.css" />
	<![endif]-->
</head>
<body>
	<div class="module" id="edit-user-details">

		<?php
		// Check authority
		if(!$perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) || $row->userLevel > $_SESSION['ccms_userLevel'])
		{
			if($_SESSION['ccms_userID'] != $userID)
			{
				die("[ERR802] ".$ccms['lang']['auth']['featnotallowed']);
			}
		}
		?>

		<div class="center-text <?php echo $status; ?> clear">
			<?php
			if(!empty($status_message))
			{
				echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#150;</span>'.$status_message.'</p>';
			}
			?>
		</div>

		<div class="span-15 colborder clear">
			<h2><?php echo $ccms['lang']['users']['editdetails']; ?></h2>
			<form action="user-management.Process.php?action=edit-user-details" id="userDetailForm" method="post" accept-charset="utf-8">
				<label><?php echo $ccms['lang']['users']['username']; ?></label>
					<span style="display:block;height:30px;"><?php echo $row->userName; ?></span>
				<label for="first"><?php echo $ccms['lang']['users']['firstname']; ?></label>
					<input type="text" class="required text" name="first" value="<?php echo $row->userFirst; ?>" id="first" />
				<label for="last"><?php echo $ccms['lang']['users']['lastname']; ?></label>
					<input type="text" class="required text" name="last" value="<?php echo $row->userLast; ?>" id="last" />
				<label for="email"><?php echo $ccms['lang']['users']['email']; ?></label>
					<input type="text" class="required validate-email text" name="email" value="<?php echo $row->userEmail; ?>" id="email" />

				<input type="hidden" name="userID" value="<?php echo $row->userID; ?>" id="userID" />
				<div class="right">
					<button type="submit"><span class="ss_sprite_16 ss_user_edit">&#160;</span><?php echo $ccms['lang']['forms']['savebutton'];?></button>
				</div>
			</form>
		</div>

		<div class="span-9 last">
			<?php
			if($_SESSION['ccms_userID'] == $row->userID || ($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= $row->userLevel))
			{
			?>
			<h2><?php echo $ccms['lang']['users']['editpassword']; ?></h2>
			<div class="prepend-1">
				<form action="user-management.Process.php?action=edit-user-password" id="userPassForm" method="post" accept-charset="utf-8">
					<label for="userPass"><?php echo $ccms['lang']['users']['password']; ?>
						<br/>
						<a class="small" onclick="randomPassword(8); return false;"><span class="ss_sprite_16 ss_bullet_key">&#160;</span><?php echo $ccms['lang']['auth']['generatepass']; ?></a>
					</label>
					<input type="text" onkeyup="passwordStrength(this.value);" class="required minLength:6 text" name="userPass" value="" id="userPass" />
					<div class="clear strength0" id="passwordStrength">
						<div id="pws1">&#160;</div>
					</div>
					<br class="clear"/>
					<label for="cpass"><?php echo $ccms['lang']['users']['cpassword']; ?></label>
						<input type="password" class="validate-match matchInput:'userPass' matchName:'Password' required minLength:6 text" name="cpass" value="" id="cpass" />

					<input type="hidden" name="userID" value="<?php echo $row->userID; ?>" id="userID" />
					<div class="right">
						<button type="submit"><span class="ss_sprite_16 ss_key">&#160;</span><?php echo $ccms['lang']['forms']['savebutton'];?></button>
					</div>
				</form>
			</div>

			<hr/>

			<h2><?php echo $ccms['lang']['users']['accountcfg']; ?></h2>
			<?php
			}
			if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= $row->userLevel)
			{
			?>
			<div class="prepend-1">
				<form action="user-management.Process.php?action=edit-user-level" id="userLevelForm" method="post" accept-charset="utf-8">
					<label for="userLevel"><?php echo $ccms['lang']['users']['userlevel']; ?></label>
					<select name="userLevel" class="required" id="userLevel" size="1">
						<option value="1" <?php echo ($row->userLevel==1 ? "selected='selected'" : null); ?>><?php echo $ccms['lang']['permission']['level1']; ?></option>
						<?php
						if($_SESSION['ccms_userLevel'] > 1)
						{
						?>
							<option value="2" <?php echo ($row->userLevel==2 ? "selected='selected'" : null); ?>><?php echo $ccms['lang']['permission']['level2']; ?></option>
						<?php
						}
						if($_SESSION['ccms_userLevel'] > 2)
						{
						?>
							<option value="3" <?php echo ($row->userLevel==3 ? "selected='selected'" : null); ?>><?php echo $ccms['lang']['permission']['level3']; ?></option>
						<?php
						}
						if($_SESSION['ccms_userLevel'] > 3)
						{
						?>
							<option value="4" <?php echo ($row->userLevel==4 ? "selected='selected'" : null); ?>><?php echo $ccms['lang']['permission']['level4']; ?></option>
						<?php
						}
						?>
					</select>
					<hr class="space"/>
					<div class="yesno">
						<label><?php echo $ccms['lang']['users']['active']; ?></label>
							<label for="userActive1" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?></label>
							<input type="radio" name="userActive" <?php echo ($row->userActive ? 'checked="checked"' : null); ?> value="1" id="userActive1" />
							<label for="userActive0" class="prepend-1 yesno"><?php echo $ccms['lang']['backend']['no']; ?></label>
							<input type="radio" name="userActive" class="validate-one-required" <?php echo (!$row->userActive ? 'checked="checked"' : null); ?> value="0" id="userActive0" />
					</div>
					<hr class="space"/>

					<input type="hidden" name="userID" value="<?php echo $row->userID; ?>" id="userID" />
					<div class="right">
						<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['savebutton'];?></button>
					</div>
				</form>
			</div>
			<?php
			}
			else
			{
				echo $ccms['lang']['auth']['featnotallowed'];
			}
			?>
		</div>

		<hr class="space clear" />

		<div class="right">
			<a href="user-management.Manage.php"><span class="ss_sprite_16 ss_arrow_undo">&#160;</span><?php echo $ccms['lang']['backend']['tooverview']; ?></a>
		</div>

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
<?php
$js_files = array();
$js_files[] = '../../../../lib/includes/js/mootools-core.js,mootools-more.js';
$js_files[] = 'passwordcheck.js';

$driver_code = <<<EOT
	if ($('userDetailForm'))
	{
		new FormValidator($('userDetailForm'),
			{
				onFormValidate: function(passed, form, event)
				{
					event.stop();
					if (passed)
						form.submit();
				}
			});
	}
	if ($('userPassForm'))
	{
		new FormValidator($('userPassForm'),
			{
				onFormValidate: function(passed, form, event)
				{
					event.stop();
					if (passed)
						form.submit();
				}
			});
	}
	if ($('userLevelForm')) /* form may not be available when global permissions restrict access */
	{
		new FormValidator($('userLevelForm'),
			{
				onFormValidate: function(passed, form, event)
				{
					event.stop();
					if (passed)
						form.submit();
				}
			});
	}
EOT;

echo generateJS4lazyloadDriver($js_files, $driver_code);
?>
</script>
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
<?php
}
else
{
	die("No external access to file");
}
?>