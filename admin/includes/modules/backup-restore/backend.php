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

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

if ($perm['manageModBackup'] <= 0 || !checkAuth()) 
{
	die("No external access to file");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Back-up &amp; Restore module</title>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="../../../img/styles/ie.css" />
		<![endif]-->
	</head>
<body>
	<div id="backup-module" class="module">

<?php


/**
 *
 * Create requested backup archive
 *
 */
$btn_backup = getPOSTparam4IdOrNumber('btn_backup');
if($do == "backup" && !empty($btn_backup))
{
	// Include back-up functions
	/*MARKER*/require_once('./functions.php');
	
	$error = null;
	
	$current_user = '-' . preg_replace('/[^a-zA-Z0-9\-]/', '_', $_SESSION['ccms_userFirst'] . '_' . $_SESSION['ccms_userLast']);

	/*
	Also backup the config php file: that one contains critical data, such as 
	the auth code, without which, the backup is not complete: the authcode is
	required to ensure the MD5 hashes stored in the DB per user are still valid
	when the backup is restored at an unfortunate moment later in time.
	*/
	$configBackup       = array('../../../../content/','../../../../lib/templates/','../../../../lib/config.inc.php','../../../../media/albums/:/\.txt$/i','../../../../media/albums/:/\/_thumbs\//i');
	$configBackupDir    = '../../../../media/files/';
	$backupName         = date('Ymd_His').'-data'.$current_user.'.zip';
	
	$createZip = new createZip;
	if (isset($configBackup) && is_array($configBackup) && count($configBackup)>0) 
	{
		foreach ($configBackup as $spec) 
		{
			$pathspec = explode(':', $spec);
			$dir = $pathspec[0];
			$regex_to_match = (count($pathspec) > 1 ? $pathspec[1] : null);
			
			/*
			 strip off the relative-path-to-root so we're left with the full, yet relative, path. 
			 
			 Handy when restoring data: extract zip equals (almost) done.
			*/
			$basename = substr($dir, strlen('../../../../')); 
			if (is_file($dir)) 
			{
				$fileContents = file_get_contents($dir);
				$createZip->addFile($fileContents,$basename);
			} 
			else 
			{
				$basename .= (substr($basename, -1, 1) != '/' ? '/' : '');
				$createZip->addDirectory($basename);
				$files = directoryToArray($dir, true, $regex_to_match);
				/*
				opendir+readdir deliver the file set in arbitrary order.
				
				In order for this code to work, we'll need a known order of the files.
				*/
				sort($files, SORT_STRING);
				//$files = array_reverse($files);

				foreach ($files as $file) 
				{
					$zipPath = explode($dir,$file);
					$zipPath = $zipPath[1];
					if (is_dir($file)) 
					{
						$createZip->addDirectory($basename . $zipPath);
					} 
					else 
					{
						$fileContents = file_get_contents($file);
						$createZip->addFile($fileContents, $basename . $zipPath);
					}
				}
			}
		}
	}
	
	$sqldump = $db->Dump();
	if ($sqldump === false)
	{
		$error[] = $db->Error();
	}
	else
	{
		/*
		And make sure we 'position' the .sql file just right for a subsequent 
		restore through our installer/wizard: to make that happen it has to live
		in the /_docs/ directory.
		*/
		$createZip->addDirectory('media');
		$createZip->addDirectory('media/files');
		$createZip->addDirectory('media/files/ccms-restore');
		$createZip->addFile($sqldump, 'media/files/ccms-restore/compactcms-sqldump.sql');
	}
	
	$fileName = $configBackupDir.$backupName;
	$fd = @fopen($fileName, "wb");
	if (!$fd)
	{
		$error[] = $ccms['lang']['system']['error_openfile'] . ": " . $fileName;
	}
	else
	{
		$out = fwrite($fd, $createZip->getZippedfile());
		if (!$out)
		{
			$error[] = $ccms['lang']['system']['error_write'] . ": " . $fileName;
		}
		fclose($fd);
	}

	/*
	To facilitate the auto-upgrade path we copy the current config.inc.php 
	and write the SQL dump to another location: 
	  /media/files/ccms-restore/config.inc.php
	  /media/files/ccms-restore/compactcms-sqldump.sql
	These files will be picked up by the installer/wizard to perform an
	automated upgrade when the admin so desires.
	
	Note the comment in /_install/inde.php: the SQL DUMP must be the
	VERY LAST FILE WRITTEN during the backup action (as we depend on 
	its 'last modified' timestamp to be the latest thing in the 
	content/media zone!
	*/
	if (!file_exists(BASE_PATH . '/media/files/ccms-restore'))
	{
		@mkdir(BASE_PATH . '/media/files/ccms-restore', fileperms(BASE_PATH . '/media/files'));
	}

	$cfgfile = BASE_PATH . '/lib/config.inc.php';
	$fileContents = file_get_contents($cfgfile);
	if (!$fileContents)
	{
		$error[] = $ccms['lang']['system']['error_openfile'] . ": " . $cfgfile;
	}
	$cfgfile = BASE_PATH . '/media/files/ccms-restore/config.inc.php';
	$fd = @fopen($cfgfile, "wb");
	if (!$fd)
	{
		$error[] = $ccms['lang']['system']['error_openfile'] . ": " . $cfgfile;
	}
	else
	{
		$out = fwrite($fd, $fileContents);
		if (!$out)
		{
			$error[] = $ccms['lang']['system']['error_write'] . ": " . $cfgfile;
		}
		fclose($fd);
	}

	if ($sqldump !== false)
	{
		$sqldumpfile = BASE_PATH . '/media/files/ccms-restore/compactcms-sqldump.sql';
		$fd = @fopen($sqldumpfile, "wb");
		if (!$fd)
		{
			$error[] = $ccms['lang']['system']['error_openfile'] . ": " . $sqldumpfile;
		}
		else
		{
			$out = fwrite($fd, $sqldump);
			if (!$out)
			{
				$error[] = $ccms['lang']['system']['error_write'] . ": " . $sqldumpfile;
			}
			fclose($fd);
		}
	}
	// else: error has already been registered before, no sweat, mate!
	
	if (empty($error))
	{
		echo '<div class="success center-text"><p>'.$ccms['lang']['backend']['newfilecreated'].', <a href="../../../../media/files/'.$backupName.'">'.strtolower($ccms['lang']['backup']['download']).'</a>.</p></div>'; 
	}
	else
	{
		echo '<div class="error center-text">';
		foreach($error as $msg)
		{
			echo '<p>'.msg.'</p>';
		}
		echo '</div>'; 
	}
}

/**
 *
 * Delete current backup archives
 *
 */
$btn_delete = getPOSTparam4IdOrNumber('btn_delete');
if($do=="delete" && !empty($btn_delete)) 
{
	if (!empty($_POST['file']))
	{
		// Only if current user has the rights
		if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
		{
			echo '<div class="notice center-text">';
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
			echo '</div>';
		} 
		else 
		{
			echo '<div class="error center-text">'.$ccms['lang']['auth']['featnotallowed'].'</div>';
		}
	} 
	else 
	{
		echo '<div class="error center-text">'.$ccms['lang']['system']['error_selection'].'</div>';
	}
}




/*
See if the site uses the lightbox or any other 'predefined' plugin/module: if so, warn
about those bits not being backed up entirely (or not at all).
*/
$modules_in_use = $db->SelectArray($cfg['db_prefix'].'pages', null, 'DISTINCT module');
if (!$modules_in_use)
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
	}
}

$mediawarning = explode(' :: ', $ccms['lang']['backup']['warn4media']);
$mediawarning[1] = explode("\n", $mediawarning[1]);


?>
		<div class="span-8 colborder">
			<h2><?php echo $ccms['lang']['backup']['createhd']; ?></h2>
			<p><?php echo $ccms['lang']['backup']['explain'];?></p>
			<form id="create-arch" action="<?php echo $_SERVER['PHP_SELF'];?>?do=backup" method="post" accept-charset="utf-8" class="clearfix" >
				<button type="submit" name="btn_backup" value="dobackup"><span class="ss_sprite_16 ss_package_add">&#160;</span><?php echo $ccms['lang']['forms']['createbutton'];?></button>
			</form>
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
						if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
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
								if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
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
			if($_SESSION['ccms_userLevel']>=$perm['manageModBackup']) 
			{
				if($i>0) 
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
					echo $ccms['lang']['system']['noresults'];
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
	global $ccms;
	global $cfg;

	echo '<h1>My Code</h1>';
	echo "<pre>\nbtn_backup = $btn_backup\ndo = $do\nbtn_delete = $btn_delete\n";
	echo "</pre>";
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
	echo '<h1>$_POST</h1>';
	echo "<pre>";
	var_dump($_POST);
	echo "</pre>";
	echo '<h1>$_GET</h1>';
	echo "<pre>";
	var_dump($_GET);
	echo "</pre>";
	echo '<h1>$cfg</h1>';
	echo "<pre>";
	var_dump($cfg);
	echo "</pre>";
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




var jsLogEl = document.getElementById('jslog');
var js = [
	'../../../../lib/includes/js/mootools-core.js,mootools-more.js',
	'../../../../lib/includes/js/the_goto_guy.js'
	];

function jsComplete(user_obj, lazy_obj)
{
    if (lazy_obj.todo_count)
	{
		/* nested invocation of LazyLoad added one or more sets to the load queue */
		jslog('Another set of JS files is going to be loaded next! Todo count: ' + lazy_obj.todo_count + ', Next up: '+ lazy_obj.load_queue['js'][0].urls);
		return;
	}
	else
	{
		jslog('All JS has been loaded!');
	}

	// window.addEvent('domready',function()
	//{
		$('create-arch').addEvent('click', function()
			{
				var el = $('backup-module');
				el.set('spinner', { 
						message: "<?php echo $ccms['lang']['backup']['wait4backup']; ?>", 
						img: {
							'class': 'loading'
						}
					});
				el.spin(); //obscure the element with the spinner

				//alert('go! ' + el);
				return true;
			});
	//});
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
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
