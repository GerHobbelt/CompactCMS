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


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

$do = getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');




if ($perm->get('manageModBackup') <= 0 || !checkAuth())
{
	die("No external access to file");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Back-up &amp; Restore module</title>
		<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/ie.css" />
		<![endif]-->
	</head>
<body>
	<div id="backup-module" class="module">
		<div id="status-report" 

<?php

/**
 *
 * Delete current backup archives
 *
 */
$btn_delete = getPOSTparam4IdOrNumber('btn_delete');
if ($do == 'delete' && !empty($btn_delete))
{
	echo 'style="display: block;" ';
	if (!empty($_POST['file']))
	{
		// Only if current user has the rights
		if($perm->is_level_okay('manageModBackup', $_SESSION['ccms_userLevel']))
		{
			echo 'class="notice center-text">';
			foreach ($_POST['file'] as $value)
			{
				$value = filterParam4Filename($value); // strips any slashes as well, so attacks like '../../../../../../../../../etc/passwords' won't pass
				if (!empty($value))
				{
					unlink('../../../../media/files/'.$value);
					echo ucfirst($value).' '.$ccms['lang']['backend']['statusremoved'].'.<br/>';
				}
				else
				{
					echo $ccms['lang']['auth']['featnotallowed'];
				}
			}
		}
		else
		{
			echo 'class="error center-text">' . $ccms['lang']['auth']['featnotallowed'];
		}
	}
	else
	{
		echo 'class="error center-text">' . $ccms['lang']['system']['error_selection'];
	}
}
else
{
	if (!empty($status_message))
	{
		echo 'style="display: block;" ';
	}
	echo 'class="' . $status . ' center-text">';
	if (!empty($status_message))
	{
		echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>';
	}
}
echo '</div>';




/*
 * See if the site uses the lightbox or any other 'predefined' plugin/module: if so, warn
 * about those bits not being backed up entirely (or not at all).
 */
$modules_in_use = $db->SelectArray($cfg['db_prefix'].'pages', null, 'DISTINCT module');
if ($db->ErrorNumber())
	$db->Kill();
$show_warn_about_partial_backup = false;
foreach($modules_in_use as $row)
{
	switch ($row['module'])
	{
	case 'editor':
	case 'news':
	case 'comment':
		break;

	case 'lightbox':
	default:
		// when you use the lightbox or some plugin we don't know all about, the backup will be incomplete.
		$show_warn_about_partial_backup = true;
		break;
	}
}

$mediawarning = explode(' :: ', $ccms['lang']['backup']['warn4media']);
$mediawarning[1] = explode("\n", $mediawarning[1]);


?>
		<div class="span-8 colborder">
			<h2><?php echo $ccms['lang']['backup']['createhd']; ?></h2>
			<p><?php echo $ccms['lang']['backup']['explain']; ?></p>
			<a id="create-arch" class="button" href="#"><span class="ss_sprite_16 ss_package_add">&#160;</span><?php echo $ccms['lang']['forms']['createbutton']; ?></a>
			<?php
			if ($show_warn_about_partial_backup)
			{
			?>
				<div class="warning error left-text" id="media-warning">
					<h2><?php echo $mediawarning[0]; ?></h2>
					<?php
					foreach ($mediawarning[1] as $line)
					{
						echo '<p class="left">' . $line . "</p>\n";
					}
					?>
				</div>
			<?php
			}
			?>
		</div>

		<div class="span-16 last">
		<h2><?php echo $ccms['lang']['backup']['currenthd'];?></h2>
			<form id="delete-arch" action="<?php echo $_SERVER['PHP_SELF'];?>?do=delete" method="post" accept-charset="utf-8">
				<div class="table_inside">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<?php
						if($perm->is_level_okay('manageModBackup', $_SESSION['ccms_userLevel']))
						{
						?>
							<th class="span-1">&#160;</th>
						<?php
						}
						?>
						<th class="span-14"><?php echo $ccms['lang']['backup']['timestamp'];?></th>
						<th>&#160;</th>
					</tr>
					<?php
					if ($handle = opendir('../../../../media/files/'))
					{
						$i=0;
						while (false !== ($file = readdir($handle)))
						{
							if ($file != "." && $file != ".." && strmatch_tail($file, ".zip"))
							{
								// Alternate rows
								if($i%2 != 1)
								{
									echo '<tr class="altrgb">';
								}
								else
								{
									echo '<tr>';
								}
								echo "\n";
								if($perm->is_level_okay('manageModBackup', $_SESSION['ccms_userLevel']))
								{
									echo '<td><input type="checkbox" name="file[]" value="'.$file.'" id="'.$i.'"></td>';
								}
								echo "\n";
								echo '<td>'.$file.'</td>';
								echo "\n";
								echo '<td><a href="../../../../media/files/'.$file.'" title="'.$file.'"><span class="ss_sprite_16 ss_package_green">&#160;</span>'.$ccms['lang']['backup']['download'].'</a></td>';
								echo "\n</tr>\n";
								$i++;
							}
						}
						closedir($handle);
					}
					?>
				</table>
				</div>
			<?php
			if($perm->is_level_okay('manageModBackup', $_SESSION['ccms_userLevel']))
			{
				if($i > 0)
				{
				?>
					<div class="right">
						<button type="submit" onclick="return confirmation_delete();" name="btn_delete" value="dodelete"><span class="ss_sprite_16 ss_package_delete">&#160;</span><?php echo $ccms['lang']['backend']['delete'];?></button>
						<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
					</div>
				<?php
				}
				else
				{
				?>
					<p><?php echo $ccms['lang']['system']['noresults']; ?></p>
					<div class="right">
						<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
					</div>
				<?php
				}
			}
			else
			{
			?>
				<div id="no-delete-action">
					<h2><span class="ss_sprite_16 ss_package_delete">&#160;</span><?php echo $ccms['lang']['backend']['delete'];?></h2>
					<p><?php echo $ccms['lang']['auth']['featnotallowed']; ?></p>
				</div>
				<div class="right">
					<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
				</div>
			<?php
			}
			?>
			</form>
		</div>
	</div>
<?php

if (0)
{
	dump_request_to_logfile(array('btn_backup' => $btn_backup,
								   'do' => $do,
								   'btn_delete' => $btn_delete),
							true);
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
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-bck_ccms');
	}
	return false;
}


<?php
$js_files = array(
	$cfg['rootdir'] . 'lib/includes/js/the_goto_guy.js',
	$cfg['rootdir'] . 'lib/includes/js/mootools-core.js,mootools-more.js'
);

$wait4backup = $ccms['lang']['backup']['wait4backup'];
$progress_update_time = 1000;
$result_url = $_SERVER['PHP_SELF'];
$driver_code = <<<EOT42

	if ($('create-arch'))
	{
		var obj = {
			module_el: $('backup-module'),
			report_el: $('status-report'),
			progress_timer: null,
		
			init_backup_progress: function()
			{
				this.progress_timer = setTimeout(this.report_backup_progress.bind(this), $progress_update_time);
				this.report_el.setStyles({
					opacity: 0,
					display: 'block'
				}).fade(1);
			},
			terminate_backup_progress: function(json_data)
			{
				clearTimeout(this.progress_timer);
				this.progress_timer = null;

				this.report_el.fade(0).get('tween').chain(function()
				{
					this.report_el.setStyle('display', 'none');
					
					// now jump to the indicated URL
					goto_url(json_data.url ? json_data.url : '$result_url?status=error&msg=' + encodeURIComponent(json_data.error.message));
				}.bind(this));
			},
			report_backup_progress: function() 
			{
				clearTimeout(this.progress_timer);

				console.log('backup progress timer!');
				
				new Request.JSON({
					method: 'post',
					url: './backup-restore.Process.php?action=report_backup_progress',
					onComplete: function(data) 
					{
						console.log('backup report received!', data);
				
						var el = this.module_el;
						if (el && data.state && data.count)
						{
							var spinner = el.get('spinner');
							if (spinner.msg)
							{
								spinner.msg.set('html', "$wait4backup <br><br> " + data.state + ' ' + (data.position ? '' + data.position + ' / ' + data.count : ' (' + data.count + ')') + (data.progress ? ' ~ ' + (data.progress * 100).toFixed(1) + '%' : ' ...'));
							}
						}
						
						this.progress_timer = setTimeout(this.report_backup_progress.bind(this), $progress_update_time);
					}.bind(this)
				}).send();
			},

			click_evt_handler: function(e)
			{
				var el = this.module_el;
				e.stop();
				
				el.set('spinner', {
						message: "$wait4backup",
						img: {
							'class': 'loading'
						}
					});
				el.spin(); //obscure the element with the spinner

				this.init_backup_progress();
				
				new Request.JSON({
					method: 'post',
					url: './backup-restore.Process.php?action=backup',
					onComplete: function(data) 
					{
						console.log('backup completed!', data);
				
						this.terminate_backup_progress(data);
					}.bind(this)
				}).send();
				
				return false;
			}
		};

		$('create-arch').addEvent('click', obj.click_evt_handler.bind(obj));
	}

EOT42;

echo generateJS4lazyloadDriver($js_files, $driver_code);
?>
</script>
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
