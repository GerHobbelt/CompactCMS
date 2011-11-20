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


// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2']))
{
	die("No external access to file");
}



$do = getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Permissions module</title>
	<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/ie.css" />
	<![endif]-->
</head>
<body>
<div class="module" id="permission-manager">
	<div class="center-text <?php echo $status; ?>">
		<?php
		if(!empty($status_message))
		{
			echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>';
			if ($status != 'error')
			{
			?>
				<p class="ss_has_sprite"><span class="ss_sprite_16 ss_exclamation">&#160;</span><?php echo $ccms['lang']['backend']['must_refresh']; ?></p>
				<form id="refresh_everytin_form" onsubmit="return refresh_adminmain();">
					<button type="submit"><span class="ss_sprite_16  ss_arrow_refresh">&#160;</span><?php echo $ccms['lang']['backend']['reload_admin_screen']; ?></button>
				</form>
			<?php
			}
		}
		?>
	</div>

	<h2><?php echo $ccms['lang']['permission']['header']; ?></h2>
	<?php

	// (!) Only administrators can change these values
	if($_SESSION['ccms_userLevel'] >= 4)
	{
	?>
		<p class="left-text"><?php echo $ccms['lang']['permission']['explain']; ?></p>
		<form action="permissions.Process.php" method="post" accept-charset="utf-8">
			<div class="table_inside">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<th class="span-5"><em><?php echo $ccms['lang']['permission']['target']; ?></em></th>
					<th class="span-4 center-text"><?php echo $ccms['lang']['backend']['disabled']; ?></th>
					<th class="span-4 center-text"><?php echo $ccms['lang']['permission']['level1']; ?></th>
					<th class="span-4 center-text"><?php echo $ccms['lang']['permission']['level2']; ?></th>
					<th class="span-4 center-text"><?php echo $ccms['lang']['permission']['level3']; ?></th>
					<th class="span-4 center-text"><?php echo $ccms['lang']['permission']['level4']; ?></th>
				</tr>
				<?php
				$rsCfg = $db->SelectArray($cfg['db_prefix'].'cfgpermissions', null, null, array('display_order', 'name'));
				if (empty($rsCfg)) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

				$i = 0;
				foreach ($rsCfg as $rec)
				{
					$columnName = $rec['name'];
					$comments = (array_key_exists($rec['name'], $ccms['lang']['permitem']) ? $ccms['lang']['permitem'][$rec['name']] : null);
					$state = $rec['value'];
					$dlevel = explode('.', $rec['display_order']);

					?>
					<tr class="<?php echo ($i % 2 != 1 ? 'altrgb' : 'regrgb'); ?>">
						<th class="permission-name">
						<?php
							if ($dlevel[1] > 0)
							{
								echo '<span class="ss_sprite_16 ss_bullet_white">&#160;</span>';
							}
							echo (!empty($comments) ? '<abbr title="' . $comments . '">' . $columnName . '</abbr>' : $columnName);
						?>
						</th>
						<?php
						for ($j = 0; $j < 5; $j++)
						{
							?>
							<td class="hover center-text">
								<label>
								<input type="radio" name="<?php echo $columnName; ?>" <?php echo ($state == $j ? 'checked="checked"' : null); ?> value="<?php echo $j; ?>">
								</label>
							</td>
							<?php
						}
						?>
					</tr>
					<?php
					$i++;
				}
				?>
			</table>
			</div>
			<div class="right">
				<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['savebutton'];?></button>
				<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
			</div>
		</form>
	<?php
	}
	else
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
	?>

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
function refresh_adminmain()
{
	return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", null);
}

function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-perm_ccms');
	}
	return false;
}
</script>
<script type="text/javascript" src="../../../../lib/includes/js/the_goto_guy.js" charset="utf-8"></script>
</body>
</html>
