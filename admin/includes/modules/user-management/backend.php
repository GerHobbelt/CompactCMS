<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:	CompactCMS - v 1.4.2
	
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

// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{ 
	die("No external access to file");
}


// Set default variables


$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Manage users</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/ie.css" />
	<![endif]-->
</head>
<body>
	<div class="module" id="user-management">
		<div class="center-text <?php echo $status; ?> clear">
			<?php 
			if(!empty($status_message)) 
			{ 
				echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>'; 
			} 
			?>
		</div>
		
		<div class="span-18 colborder clear-left">
			<h2><?php echo $ccms['lang']['users']['overviewusers']; ?></h2>
			<form action="../../process.inc.php?action=delete-user" method="post" accept-charset="utf-8">
				<div class="table_inside">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<th>&#160;</th>
						<th><?php echo $ccms['lang']['users']['user']; ?></th>
						<th><?php echo $ccms['lang']['users']['name']; ?></th>
						<th><?php echo $ccms['lang']['users']['email']; ?></th>
						<th><?php echo $ccms['lang']['users']['active']; ?></th>
						<th><?php echo $ccms['lang']['users']['level']; ?></th>
						<th><?php echo $ccms['lang']['users']['lastlog']; ?></th>
					</tr>
				
					<?php 
					// Open recordset for all users with levels <= to own
					$usercoll = $db->SelectArray($cfg['db_prefix'].'users', null, null, array('userID'));
					if ($db->ErrorNumber()) $db->Kill();
					
					// Loop through results
					$i = 0;
					foreach($usercoll as $row) 
					{
						// Define $isEven for alternate table coloring
						if($i % 2 != 1) 
						{
							echo '<tr class="altrgb">';
						} 
						else 
						{ 
							echo '<tr>';
						} 
						?>
							<td>
							<?php 
							if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= $row['userLevel'] && $_SESSION['ccms_userID'] != rm0lead($row['userID'])) 
							{ 
							?>	
								<input type="checkbox" name="userID[]" value="<?php echo rm0lead($row['userID']); ?>" id="userID" />
							<?php 
							} 
							else 
							{
								echo "&#160;"; 
							}
							?>
							</td>
							<td>
							<?php 
							if($_SESSION['ccms_userID'] == rm0lead($row['userID']) || ($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= $row['userLevel'])) 
							{ 
							?>
								<a href="user.Edit.php?userID=<?php echo rm0lead($row['userID']); ?>"><span class="ss_sprite_16 ss_user_edit">&#160;</span><?php echo $row['userName']; ?></a>
							<?php 
							} 
							else 
							{
								echo $row['userName']; 
							}
							?>
							</td>
							<td><?php echo substr($row['userFirst'],0,1); ?>. <?php echo $row['userLast']; ?></td>
							<td><a href="mailto:<?php echo $row['userEmail']; ?>"><span class="ss_sprite_16 ss_email">&#160;</span><?php echo $row['userEmail']; ?></a></td>
							<td><?php echo ($row['userActive'] ? $ccms['lang']['backend']['yes'] : $ccms['lang']['backend']['no']); ?></td>
							<td><?php echo $row['userLevel']; ?></td>
							<td class="nowrap"><?php echo date('d-m-\'y',strtotime($row['userLastlog'])); ?></td>
						</tr>
						<?php 
						$i++; 
					} 
					?>
				</table>
				</div>
				<hr class="space"/>
				<?php 
				if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel'])) 
				{ 
				?>
					<button type="submit" onclick="return confirmation_delete();" name="deleteUser"><span class="ss_sprite_16 ss_user_delete">&#160;</span><?php echo $ccms['lang']['backend']['delete']; ?></button>
				<?php 
				} 
				?>
			</form>
		</div>
		
		<div class="span-6 last" id="create-user">
			<h2><?php echo $ccms['lang']['users']['createuser']; ?></h2>
			<?php 
			if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel'])) 
			{ 
			?>
				<form action="../../process.inc.php?action=add-user" method="post" id="addUser" accept-charset="utf-8">
					<label for="userName"><?php echo $ccms['lang']['users']['username']; ?></label>
					<input type="text" class="minLength:3 text" name="user" value="" id="userName" />
					<label for="userPass"><?php echo $ccms['lang']['users']['password']; ?><br/>
						<a class="small" onclick="randomPassword(8); return false;"><span class="ss_sprite_16 ss_bullet_key">&#160;</span><?php echo $ccms['lang']['auth']['generatepass']; ?></a>
					</label>
					<input type="text" onkeyup="passwordStrength(this.value);" class="minLength:6 text" name="userPass" value="" id="userPass" />
					<div class="clear strength0" id="passwordStrength">
						<div id="pws1">&#160;</div><div id="pws2">&#160;</div>
					</div>
					</br class="clear"/>
					<label for="userFirstname"><?php echo $ccms['lang']['users']['firstname']; ?></label>
						<input type="text" class="required text" name="userFirstname" value="" id="userFirstname" />
					<label for="userLastname"><?php echo $ccms['lang']['users']['lastname']; ?></label>
						<input type="text" class="required text" name="userLastname" value="" id="userLastname" />
					<label for="userEmail"><?php echo $ccms['lang']['users']['email']; ?></label>
						<input type="text" class="required validate-email text" name="userEmail" value="" id="userEmail" />
					
					<hr class="space"/>
					<label for="userLevel"><?php echo $ccms['lang']['users']['userlevel']; ?></label>
					<select name="userLevel" class="required text" id="userLevel" size="1">
						<option value="1"><?php echo $ccms['lang']['permission']['level1']; ?></option>
						<?php 
						if($_SESSION['ccms_userLevel']>1) 
						{ 
						?>
							<option value="2"><?php echo $ccms['lang']['permission']['level2']; ?></option>
						<?php 
						} 
						if($_SESSION['ccms_userLevel']>2) 
						{ 
						?>
							<option value="3"><?php echo $ccms['lang']['permission']['level3']; ?></option>
						<?php 
						} 
						if($_SESSION['ccms_userLevel']>3) 
						{ 
						?>
							<option value="4"><?php echo $ccms['lang']['permission']['level4']; ?></option>
						<?php 
						} 
						?>
					</select>
					<div>
						<label><?php echo $ccms['lang']['users']['active']; /* [i_a] and make sure either yes or no are selected to begin with; pick 'no' as the default here */ ?></label>
							<label for="userActive1" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['yes']; ?></label>
								<input type="radio" class="validate-one-required" name="userActive" value="1" id="userActive1" />
							<label for="userActive0" class="prepend-1" style="display:inline;font-weight:normal;"><?php echo $ccms['lang']['backend']['no']; ?></label>
								<input type="radio" name="userActive" value="0" id="userActive0" checked="checked" />
					</div>
					<hr class="space"/>
					<div class="right">
						<button type="submit"><span class="ss_sprite_16 ss_user_add">&#160;</span><?php echo $ccms['lang']['forms']['createbutton']; ?></button>
						<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
					</div>
				</form>
			<?php 
			} 
			else 
			{
				echo $ccms['lang']['auth']['featnotallowed']; 
			}
			?>
		</div>

<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	<textarea id="jslog" class="log span-25 last clear" readonly="readonly">
	</textarea>
<?php
}
?>

	</div>	
	<script type="text/javascript" charset="utf-8">
function confirmation_delete()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'D') !== false ? 'confirm("'.$ccms['lang']['backend']['confirmdelete'].'")' : 'true'); ?>;
	return !!answer;
}

function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-usr_ccms');
	}
	return false;
}


<?php
$js_files = array();
$js_files[] = '../../../../lib/includes/js/the_goto_guy.js';
$js_files[] = '../../../../lib/includes/js/mootools-core.js,mootools-more.js';
$js_files[] = 'passwordcheck.js';

$driver_code = <<<EOT
		new FormValidator($('addUser'),
		{
			onFormValidate: function(passed, form, event)
			{
				event.stop();
				if (passed)
					form.submit();
			}
		});
EOT;

echo generateJS4lazyloadDriver($js_files, $driver_code);
?>
</script>
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
