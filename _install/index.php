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

if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}

if(empty($_GET['do'])) 
{ 
	// destroy the session if it existed before: start a new session
	session_start();
	session_unset();
	if (ini_get("session.use_cookies")) 
	{
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			(!empty($params["ccms_userID"]) ? $params["ccms_userID"] : ''), 
			(!empty($params["domain"]) ? $params["domain"] : ''),
			(!empty($params["secure"]) ? $params["secure"] : ''),
			(!empty($params["httponly"]) ? $params["httponly"] : '')
		);
	}
	session_destroy();
	session_regenerate_id();
}
// Start the current session
session_start();

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

if (!isset($cfg))
{
	?>
	<h1>Fatal Error</h1>
	<p>The installer has apparently rewritten the config.inc.php file but failed to rewrite its content. 
	This is a severe failure and you must restore a backup or re-install the CompactCMS source code, in
	particular the <file>lib/config.inc.php</file> and <file>.htaccess</file> files!</p>
	<?php
	die();
}

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');


$do	= getGETparam4IdOrNumber('do');

// If no step, set session hash
if(empty($do) && empty($_SESSION['id']) && empty($_SESSION['authcheck'])) 
{
	// Setting safety variables
	SetAuthSafety();
} 

$do_ftp_chmod = ($do == md5('ftp') && CheckAuth());



// Set root directory
$rootdir = str_replace('\\','/',dirname(dirname($_SERVER['PHP_SELF'])));
if($rootdir != '/')
{
	$rootdir = $rootdir.'/';
}




/*
 * To detect the possiblity of an upgrade action, there's two ways we support:
 * 
 * 1) someone extracted a backup archive and (when this is from a pre-1.4.2 backup) moved all the files
 *    in the appropriate spots, which we ASSUME HAS HAPPENED when we find the 
 *      compactcms-sqldump.sql
 *    file in the site's media/files/ccms-restore/ directory. 
 *    This implies the config.inc.php file has also been copied to that directory already.
 *    
 * 2) someone actually ran the 'backup' command in the admin backup within the last
 *    hour or so (timeout configurable through the UPGRADE_FROM_BACKUP_ACTION_TIMEOUT
 *    constant) as the backup will have not only generated the expected .zip archive
 *    but (since 1.4.2) also dumped the SQL dump and config file to the /media/files/ccms-restore/
 *    directory.
 */

if (empty($_SESSION['variables']))
{
	$_SESSION['variables'] = array();
}


/*
preload the session variables
*/
if (empty($_SESSION['variables']['sitename']) && !empty($cfg['sitename']))
{
	$_SESSION['variables']['sitename'] = $cfg['sitename'];
}
if (empty($_SESSION['variables']['rootdir']) && !empty($cfg['rootdir']))
{
	$_SESSION['variables']['rootdir'] = $cfg['rootdir'];
}
if (empty($_SESSION['variables']['language']) && !empty($cfg['language']))
{
	$_SESSION['variables']['language'] = $cfg['language'];
}
if (empty($_SESSION['variables']['version']) && !empty($cfg['version']))
{
	$_SESSION['variables']['version'] = !!$cfg['version'];
}
if (empty($_SESSION['variables']['iframe']) && !empty($cfg['iframe']))
{
	$_SESSION['variables']['iframe'] = !!$cfg['iframe'];
}
if (empty($_SESSION['variables']['wysiwyg']) && !empty($cfg['wysiwyg']))
{
	$_SESSION['variables']['wysiwyg'] = !!$cfg['wysiwyg'];
}
if (empty($_SESSION['variables']['protect']) && !empty($cfg['protect']))
{
	$_SESSION['variables']['protect'] = !!$cfg['protect'];
}
if (empty($_SESSION['variables']['authcode']) && !empty($cfg['authcode']))
{
	$_SESSION['variables']['authcode'] = $cfg['authcode'];
}
if (empty($_SESSION['variables']['db_host']) && !empty($cfg['db_host']))
{
	$_SESSION['variables']['db_host'] = $cfg['db_host'];
}
if (empty($_SESSION['variables']['db_user']) && !empty($cfg['db_user']))
{
	$_SESSION['variables']['db_user'] = $cfg['db_user'];
}
if (empty($_SESSION['variables']['db_pass']) && isset($cfg['db_pass']))
{
	$_SESSION['variables']['db_pass'] = $cfg['db_pass'];
}
if (empty($_SESSION['variables']['db_name']) && !empty($cfg['db_name']))
{
	$_SESSION['variables']['db_name'] = $cfg['db_name'];
}
if (empty($_SESSION['variables']['db_prefix']) && isset($cfg['db_prefix']))
{
	$_SESSION['variables']['db_prefix'] = $cfg['db_prefix'];
}

if (empty($_SESSION['variables']['restrict']) && !empty($cfg['restrict']))
{
	$_SESSION['variables']['restrict'] = $cfg['restrict'];
}
if (empty($_SESSION['variables']['default_template']) && !empty($cfg['default_template']))
{
	$_SESSION['variables']['default_template'] = $cfg['default_template'];
}
if (empty($_SESSION['variables']['enable_gravatar']) && !empty($cfg['enable_gravatar']))
{
	$_SESSION['variables']['enable_gravatar'] = $cfg['enable_gravatar'];
}
if (empty($_SESSION['variables']['admin_page_dynlist_order']) && !empty($cfg['admin_page_dynlist_order']))
{
	$_SESSION['variables']['admin_page_dynlist_order'] = $cfg['admin_page_dynlist_order'];
}
if (empty($_SESSION['variables']['IN_DEVELOPMENT_ENVIRONMENT']) && !empty($cfg['IN_DEVELOPMENT_ENVIRONMENT']))
{
	$_SESSION['variables']['IN_DEVELOPMENT_ENVIRONMENT'] = !!$cfg['IN_DEVELOPMENT_ENVIRONMENT'];
}
if (empty($_SESSION['variables']['HTTPD_SERVER_TAKES_CARE_OF_CONTENT_COMPRESSION']) && !empty($cfg['HTTPD_SERVER_TAKES_CARE_OF_CONTENT_COMPRESSION']))
{
	$_SESSION['variables']['HTTPD_SERVER_TAKES_CARE_OF_CONTENT_COMPRESSION'] = !!$cfg['HTTPD_SERVER_TAKES_CARE_OF_CONTENT_COMPRESSION'];
}

/*
 * now see whether the prerequisite files exist in ../media/files/ccms-restore/ 
 * *AND* 
 * whether at the SQL dump file has a 'last nodified' timestamp equal or beyond ANY
 * of the content files stored in ../content/ or ../media/
 * 
 * ... because only if it has, can we be sure the backup producing those prerequisite
 * files is of the 'most recent activity' kind.
 * 
 * Naturally, there is a hitch: when we are performing a 'site restore' operation right
 * now, then there MAY be some lingering content which has not been replaced by the 
 * 'restore' operation so far, i.e. the archive extract and /content/... + /media/... 
 * directory overwrite. That is, assuming such an overwrite-on-restore was done in an
 * unclean way. Which should be frowned upon most severely as it can cause all sorts
 * of site editing ulcers later on, when the lingering content disrupts the creation
 * of, say, fresh pages with the same name as the lingering (not cleaned up) files.
 * 
 * Hence, we should put up a FATAL warning informing the user she's got some lingering
 * files in there, which are not part of the backup. Plus a little hint for the truly
 * stubborn and savvy ones: after all it only takes a UNIX' touch to fix the problem
 * in a way of your liking. ;-)
 * Nevertheless, when using the magick of UNIX' touch, you're on your own from that point
 * on as using magick like that is only for grownups who should've learned their lesson
 * already.
 * (And maybe, just maybe, I shouldn't have read Neil Gaiman so very early in the 
 * morning when the dawn is yet only a hint from yesterday's tale.)
 */

$has_prepped_restore = false;
$has_uptodate_backup = false;
$filepath = BASE_PATH . '/media/files/ccms-restore/';
if (is_file($filepath . 'config.inc.php') && is_file($filepath . 'compactcms-sqldump.sql'))
{
	$has_prepped_restore = true;
	/*
	 * NOTE that the sqldump file will be the LAST file WRITTEN by the backup procedure and will have, upon extraction from the archive,
	 *      a create/modify timestamp equaling the time the backup was being performed. As such, it MUST be the latest file in the
	 * 	    entire content region!
	 */
	$backup_time = filemtime($filepath . 'compactcms-sqldump.sql');

	/*
	now scan the /content/ and /media/ directories to see if the backup is representative of the latest state of affairs
	*/
	$filelist = array_merge(safe_glob(BASE_PATH . '/media/*', GLOB_RECURSE | GLOB_NODOTS | GLOB_PATH | GLOB_NODIR),
							safe_glob(BASE_PATH . '/content/*', GLOB_RECURSE | GLOB_NODOTS | GLOB_PATH | GLOB_NODIR));
	$lastmtime = 0;
	foreach($filelist as $item)
	{
		/* 
		don't bother filtering the ccms-restore/ subdir: it will only bump the $lastmtime
		timestamp to equal $backup_time, which is fine with us. We're only concerned about
		items LATER THAN that date, and such must come from other parts of the 
		media/content zone.
		*/
		$lastmtime = max($lastmtime, filemtime($item));
	}
	
	/*
	 * If we have ANY more recent content than the files in .../ccms-restore/ , this signals two things:
	 * 
	 * a) we have performed backups before OR extracted an older backup archive and prepared that restore directory,
	 *    either way signalling that we MAY desire a restore/upgrade operation now, while
	 *    
	 * b) the fact that there's more recent content than our latest backup signal files means we haven't 
	 *    created a backup very recently. THIS implies that going through on such a 'automated' restore
	 *    operation would REWIND the site content to some prior UNIDENTIFIED state: UNIDENTIFIED because
	 *    we have failed to either clean the content&media directory trees of recent content which MUST be
	 *    removed as we apparently wish to rewind to the state as of an older date (restore/rewind), or we
	 *    simply failed to run a recent backup (meaning 'we', as in ANY user with sufficient priveleges)
	 *    changed or augmented the site content AFTER the last backup was made.
	 *    
	 * That, my friends, is a clear cut case of Nuking Your Site With Extreme Prejudice and we don't want
	 * to be a party in such Murphian Madness. So you either do a proper backup, a proper restore OR you
	 * tweak the SQL dump file last-modified timestamp to agree with our rule here, in which case, of course,
	 * you just handed yourself a paddle to travel upcreek. Who am I, my dearies, to stand in the way of 
	 * a Viking so visionary as to crave a UNIX' touch? Have it your way then, and may the gods look 
	 * favorably upon your soul in the here-on-after. Ta ta.
	 */
	$has_uptodate_backup = ($lastmtime < $backup_time);
}
	
	


if (empty($_SESSION['variables']['may_upgrade']))
{
	$_SESSION['variables']['may_upgrade'] = ($has_prepped_restore && $has_uptodate_backup);
}
if (empty($_SESSION['variables']['do_upgrade']))
{
	$_SESSION['variables']['do_upgrade'] = ($has_prepped_restore && $has_uptodate_backup);
}





// B0RK when the server still has that old hack still active:
if (get_magic_quotes_gpc())
{
	die("Your server still has the old PHP 'magic quotes' hack setting turned ON; it is obsoleted and poses an indirect security risk: any software on your machine still depending on that setting should be upgraded/overhauled! CompactCMS will NOT install as long as this setting is active.");
}




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CompactCMS Installation</title>
		<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
		<link rel="stylesheet" type="text/css" href="./install.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="../admin/img/styles/ie.css" />
		<![endif]-->
	</head>
<body>

<noscript class="noscript" id="noscript">
	<h1>Your browser does not support JavaScript</h1>
	<h2>... or has JavaScript disabled.</h2>
	<p>Both the CompactCMS installer (i.e. this page) and the CompactCMS administration pages require JavaScript 
	to be enabled in your browser.</p>
	<p>If, for any reason, you cannot or may not enable JavaScript in your browser (or alternatively, change or 
	upgrade to another browser that <em>does</em> support JavaScript), then regretably you cannot install nor
	manage your CompactCMS web site from this location.</p>
	
	<hr class="space" />
</noscript>

<div id="install-wrapper" class="container-18" style="display:none;" >
	<div id="help" class="span-8 colborder">
		<div id="logo" class="sprite logo">
			<h1>CompactCMS installation</h1>
		</div>
		<h2><span class="ss_sprite_16 ss_package_green">&#160;</span>Install CompactCMS</h2>
		<p>Welcome to the installation wizard of CompactCMS. This wizard will guide you through the five steps required to get CCMS to work on your server.
		Installing CompactCMS will not take more than five minutes of your time.</p>
		<h2><span class="ss_sprite_16 ss_tick">&#160;</span>What steps to expect</h2>
		<ol>
			<li><strong>Environment</strong><br/><em>Tell CCMS where your installation is located and what language to speak</em></li>
			<li><strong>Preferences</strong><br/><em>Indicate how you prefer your CCMS</em></li>
			<li><strong>Database</strong><br/><em>Fill-out your credentials to help CCMS save its data</em></li>
			<li><strong>Confirm</strong><br/><em>Go through all of your settings one last time</em></li>
			<li><strong>Done!</strong><br/><em>CCMS saves all of your settings and preferences</em></li>
		</ol>
		<p>If you have any questions, suggestions or perhaps even praise; be sure to <a href="http://www.compactcms.nl/contact.html?subject=My installation feedback" target="_blank" title="Send me an e-mail">let me know</a>!</p>
		<p>Cheers!<br/><em>Xander</em></p>
	</div>
	<div class="span-9 last">
		<?php
		if ($has_prepped_restore && !$has_uptodate_backup)
		{
		?>
		<div class="error">
			<h1><span class="ss_sprite_16 ss_error">&#160;</span>Outdated backup or inproper restore preparation</h1>
			<p>It turns out you have:</p>
			<ol>
				<li><p>either not performed a backup prior to running this installer.</p>
					<p>More specifically, we have found that certain existing 
					content (located in the <file><?php echo $rootdir; ?>content/</file>
					and/or <file><?php echo $rootdir; ?>media/</file> directories) is of a later
					date than the files mandatory for performing an upgrade/restore operation:
					<file><?php echo $rootdir; ?>media/files/ccms-restore/config.inc.php</file> 
					and <file><?php echo $rootdir; ?>media/files/ccms-restore/compactcms-sqldump.sql</file>.</p>
				</li>
				<li><p>or not correctly prepared the content and media directories from an existing, possibly older, backup.</p>
				    <p>Note that you MUST at least provide suitable files for both 
					<file><?php echo $rootdir; ?>media/files/ccms-restore/config.inc.php</file> 
					and <file><?php echo $rootdir; ?>media/files/ccms-restore/compactcms-sqldump.sql</file>
					as these will drive the upgrade/restore operation.</p>

					<p>You may also note that</p>
					<ul>
						<li><p>backup archives created by earlier versions of CompactCMS (version 1.4.1 and older)
							only include the aforementioned SQLdump file, and that in a different location, and are
							lacking a usable <file>config.inc.php</file> entirely. We are sorry for this inconvenience,
							but you must recreate a usable <file>config.inc.php</file>; please consult the CompactCMS forum 
							for further directions.</p>
						</li>
						<li><p>any backup archives as created by any version of CompactCMS to date does <strong>not</strong>
							include any <file><?php echo $rootdir; ?>media/</file> files, which means those backups are 
							<strong>incomplete</strong> when you use the <dfn>lightbox</dfn> module to showcase image albums
							or employ third-party CompactCMS augmentations (i.e. other modules besides 'news', 'comments' (formerly
							known as 'guestbook') and 'lightbox').</p>
							<p>Only since version 1.4.2 does the CompactCMS' own backup archives contain a backup of your 
							<dfn>lightbox</dfn> album descriptions, yet you still must provide the image files themselves
							from another source (e.g. an externally provided backup).</p>
						</li>
					</ul>
				</li>
			</ol>
			<p>There are a few ways to remedy this:</p>
			<ol>
			<li><p>The <strong>advised</strong> route would be executing a complete backup before performing an upgrade like this.
			    or ensuring both the <file><?php echo $rootdir; ?>media/</file> and <file><?php echo $rootdir; ?>content/</file>
				directories contain all required content as per the time the backup was made, and nothing more.</p>
				<p>Also, one must make sure the files 					
				<file><?php echo $rootdir; ?>media/files/ccms-restore/config.inc.php</file> 
				and <file><?php echo $rootdir; ?>media/files/ccms-restore/compactcms-sqldump.sql</file>
				exist <em>and</em> have timestamps marking them as being more recent than any content (which would
				be an implicit fact if the <file><?php echo $rootdir; ?>compactcms-sqldump.sql</file> file originated from
				the same time as when the other items were backed up).</p>
			</li>
			<li><p><strong>When you are absolutely sure about what you're doing</strong> you may shortcircuit
			   our validation checks by 'touching' the 
			   <file><?php echo $rootdir; ?>media/files/ccms-restore/compactcms-sqldump.sql</file> file. If you
			   don't know what this last bit means, you are in peril beyond the scope of this text.</p>
			   <p>You may consult the CompactCMS forum for assistance, but meanwhile you should ...</p>
			   <blockquote>
				   <h2 class="error">Heed My Warning</h2>
				   <p>When the 
					<file><?php echo $rootdir; ?>media/files/ccms-restore/compactcms-sqldump.sql</file> file
					is known to be of an earlier date (&amp; time) then some other part of the created 
					content of this site at this time, then this fact clearly indicates that the backup
					from which such 
					<file>compactcms-sqldump.sql</file> file
					originated is <strong>outdated</strong>, i.e. older then the existing content which is
					assumed to be part of that same backup.<p>
					<p>We refer to ignoring this fact as <dfn>Nuking Your Site With Extreme Prejudice</dfn>
					and it ain't a pretty sight when you proceed and make this happen.</p>
				</blockquote>
				<p>Whether you choose this alternative path is your decision. Cave canem. (You have been warned!)</p>
			</li>
			</ol>
			<p>Note that the installer will <em>refuse</em> to work as long as this condition persists. You
			must resolve it before you can continue.</p>
		</div>
		<?php
		}
		else
		{
		?>
		<form method="post" id="installFrm">
			<fieldset id="install" style="border:none;" class="none">
				<legend class="installMsg"><?php echo (!$do_ftp_chmod ? 'Step 1 - Knowing the environment' : 'FTP - Setting permissions right');?></legend>
				<?php 
				if(!$do_ftp_chmod) 
				{ 
				?>
					<p>The details below have been filled-out based on information readily available. Please confirm these settings, select your language and click proceed.</p>
					
					<label for="sitename"><span class="ss_sprite_16 ss_pencil">&#160;</span>Site name</label>
					<input type="text" class="alt title" name="sitename" value="<?php echo (!isset($_SESSION['variables']['sitename'])?ucfirst(preg_replace("/^www\./", "", $_SERVER['HTTP_HOST'])):$_SESSION['variables']['sitename']);?>" id="sitename" />
					<br/>
					
					<label for="rootdir"><span class="ss_sprite_16 ss_application_osx_terminal">&#160;</span>Web root directory</label>
					<input type="text" class="alt title" name="rootdir" autofocus value="<?php echo $rootdir;?>" id="rootdir" />
					<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>When www.domain.ext/ccms/, <strong>/ccms/</strong> is your web root</p>
					<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Must include trailing slash!</p>
					
					<label for="language"><span class="ss_sprite_16 ss_comments">&#160;</span>CCMS backend language</label>
					<select name="language" class="title" id="language" size="1">
						<?php 
						// Get current languages
						$s = (isset($_SESSION['variables']['language'])?$_SESSION['variables']['language']:'en');
						$lcoll = GetAvailableLanguages();
						foreach($lcoll as $lcode => $ldesc)
						{
							$c = ($lcode == $s ? 'selected="selected"' : null);
							echo '<option value="'.$lcode.'" '.$c.'>'.$ldesc['name'].'</option>';
						}
						?>   	
					</select>
					<input type="hidden" name="do" value="<?php echo md5('2'); ?>" id="do" />
				<?php 
				} 
				// Populate optional FTP form
				else
				{ 
				?>
					<p>Whenever a chmod() command failes through standard procedures, the installer can try to execute the chmod() command over FTP. This requires you to submit your FTP details and full path of your CCMS installation. Any of the data entered below will <strong>never</strong> be saved by the installer.</p>
					
					<label for="ftp_host">FTP host</label>
					<input type="text" class="alt title" name="ftp_host" value="" id="ftp_host"/>
					<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Often www.domain.ext or ftp.domain.ext</p>
					<label for="ftp_user">FTP username</label>
					<input type="text" class="alt title" name="ftp_user" value="" id="ftp_user"/>
					<br/>
					<label for="ftp_pass">FTP password</label>
					<input type="password" class="title" name="ftp_pass" value="" id="ftp_pass"/>
					<br/>
					<label for="ftp_path">Installation path</label>
					<input type="text" class="alt title" name="ftp_path" value="<?php echo dirname(getcwd()); ?>" id="ftp_path"/>
					<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>CCMS will try to auto-find this using the default value above</p>
					
					<input type="hidden" name="do" value="<?php echo md5('final'); ?>" id="do" />
				<?php 
				} 
				?>
				
				<div class="right">
					<button name="submit" type="submit"><span class="ss_sprite_16 ss_lock_go">&#160;</span>Proceed</button>
					<a class="button" href="<?php echo (empty($do) ? 'http://www.compactcms.nl/contact.html?subject=My installation feedback' : 'index.php');?>"><span class="ss_sprite_16 ss_cancel">&#160;</span>Cancel</a>
				</div>
			</fieldset>
		</form>
		<?php
		}
		?>
	</div>
</div>

<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
<div>
  <textarea id="jslog" class="log span-25 clear" readonly="readonly">
  </textarea>
</div>
<?php
}
?>


<p class="quiet small clear" style="text-align:center;">&copy; 2008 - <?php echo date('Y'); ?> <a href="http://www.compactcms.nl" title="Maintained with CompactCMS.nl">CompactCMS.nl</a>. All rights reserved.</p>

<script type="text/javascript" charset="utf-8">
<?php
$js_files = array();
$js_files[] = '../lib/includes/js/mootools-core.js';
$js_files[] = '../lib/includes/js/mootools-more.js';
$js_files[] = '../admin/includes/modules/user-management/passwordcheck.js?cb=ccms_combiner_running';
$js_files[] = 'install.js';

$driver_code = <<<EOT
EOT;

echo generateJS4lazyloadDriver($js_files, $driver_code);
?>

function ccms_combiner_running()
{
	jslog("the Combiner is already running; you are installing over an existing installation! :-)");
}

/* now show the correct DIV, as we do have JavaScript up & running */
document.getElementById("noscript").style.display = "none";
document.getElementById("install-wrapper").style.display = "block";

</script>
<script type="text/javascript" src="../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>


</body>
</html>