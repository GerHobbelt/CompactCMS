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

if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}


// Start the current session
session_start();

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');


// Set current && additional step
$nextstep = getPOSTparam4IdOrNumber('do');

$may_upgrade = (!empty($_SESSION['variables']['may_upgrade']) && $_SESSION['variables']['may_upgrade'] != false); 
$do_upgrade = (!empty($_SESSION['variables']['do_upgrade']) && $_SESSION['variables']['do_upgrade'] != false); 

$dump_queries_n_stuff_in_devmode = false;


/**
*
* Per step processing of input
*
**/

// Step two
if($nextstep == md5('2') && CheckAuth())
{
	//
	// Installation actions
	//  - Environmental variables
	//
	$dir = getPOSTparam4FullFilePath('rootdir');
	$rootdir    = array('rootdir' => (substr($dir,-1)!=='/'?$dir.'/':$dir));
	$sitename   = array('sitename' => getPOSTparam4HumanName('sitename'));
	$language   = array('language' => getPOSTparam4IdOrNumber('language'));

	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$rootdir,$sitename,$language);
	
?>
	<legend class="installMsg">Step 2 - Setting your preferences</legend>
		<label for="userPass"><span class="ss_sprite_16 ss_lock">&#160;</span>Administrator password
			<br/>
			<a class="ss_has_sprite small" onclick="randomPassword(8); return false;"><span class="ss_sprite_16 ss_arrow_refresh">&#160;</span>Auto generate a safe password</a>
		</label>
		<input type="text" class="alt title" name="userPass" onkeyup="passwordStrength(this.value)" value="" id="userPass" />
		<div id="passwordStrength" class="strength0"></div>
		<p class="ss_has_sprite small quiet clear"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Remember your admin password as it cannot be retrieved</p>
		<label for="authcode"><span class="ss_sprite_16 ss_textfield_key">&#160;</span>Authentication PIN
			<br/>
			<a class="small" onclick="mkNewAuthCode(); return false;"><span class="ss_sprite_16 ss_arrow_refresh">&#160;</span>Generate a safe authCode</a></label>
		</label>
		<input type="text" class="alt title" name="authcode" value="<?php
			echo (empty($_SESSION['variables']['authcode']) ? GenerateNewAuthCode() : $_SESSION['variables']['authcode']); ?>" id="authcode" />
		<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>This PIN is used as a <a href="http://en.wikipedia.org/wiki/Salt_%28cryptography%29">salt</a> to help secure user credentials (specifically: user passphrases) and previews of inactive pages.</p>
		<?php 
		if ($may_upgrade) 
		{ 
		?>
			<p class="ss_has_sprite small alert"><span class="ss_sprite_16 ss_exclamation">&#160;</span>Be reminded that altering the existing authCode during a 
			restore or upgrade operation will <strong>invalidate</strong> all user passwords and preview codes. You <em>may</em> not want to do this, 
			unless you want to reset <em>all</em> user passwords during this upgrade/restore operation.</p>
		<?php
		}
		?>
		<label for="protect"><input type="checkbox" name="protect" value="true" <?php
			echo (!empty($_SESSION['variables']['protect']) && $_SESSION['variables']['protect'] ? 'checked' : ''); ?> id="protect" /> Password protect the administration</label>
		<label for="version"><input type="checkbox" name="version" value="true"  <?php
			echo (!empty($_SESSION['variables']['version']) && $_SESSION['variables']['version'] ? 'checked' : ''); ?>  id="version" /> Show version information</label>
		<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Want to see the latest CCMS version at the dashboard?</p>
		<label for="iframe"><input type="checkbox" name="iframe" value="true"  <?php
			echo (!empty($_SESSION['variables']['iframe']) && $_SESSION['variables']['iframe'] ? 'checked' : ''); ?> id="iframe" /> Support &amp; allow iframes</label>
		<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Can iframes be managed from within the WYSIWYG editor?</p>
		<label for="wysiwyg"><input type="checkbox" name="wysiwyg" value="true"  <?php
			echo (!empty($_SESSION['variables']['wysiwyg']) && $_SESSION['variables']['wysiwyg'] ? 'checked' : ''); ?>  id="wysiwyg" /> Enable the visual content editor</label>
		<p class="ss_has_sprite small quiet"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Uncheck if you want to disable the visual editor all together</p>
		<label for="upgrade" <?php if (!$may_upgrade) { echo 'class="quiet"'; } ?> >
			<input type="checkbox" <?php if ($do_upgrade) { echo 'value="true"'; } ?> name="upgrade" 
			<?php if ($may_upgrade) { echo 'checked'; } else { echo 'disabled="disabled"'; } ?> id="upgrade" /> Perform an 
				<span class="ss_sprite_16 ss_help" title="When and how can we upgrade? :: 
						<ul>
						<li>
							<p>You can perform an upgrade when you have <strong>made a backup of your site</strong>: this installer will automatically detect this and consequently enable the 'upgrade' tickbox.</p>
							<p class='small'>(assuming you have created the backup with a running version of CompactCMS 1.4.2 or later)</p>
						</li>
						<li>
							<p>Note that you can also <strong>restore an existing site</strong> by taking its backup and extracting the contents of the backup (zip archive) 
							in the root directory of the website:</p>
							<p>This will result in the backed-up content to be placed in the <file><?php echo $dir; ?>content/</file> directory 
							and the <file>config.inc.php</file> and <file>compactcms-sqldump.sql</file> files are available in the web site's restore directory
							<file><?php echo $dir; ?>media/files/ccms-restore/</file> .</p>
							<p class='small'>(This is assuming you have created the backup with a running version of CompactCMS 1.4.2 or later; if your backup
							originates from an earlier version of CompactCMS, you will have to move the extracted content to the appropriate spots and possibly
							recover your original config.inc.php some other way as those versions did not include that file in the backups. Consult the 
							CompactCMS forum for assistance.)</p>
							<p>Subsequently running this install wizard will result in auto-detection of such a restore operation and enable the 'upgrade' tickbox for you.</p>
						</li>
						</ul> ">
				&#160;</span>
			<strong>upgrade</strong>
		</label>
		<p class="ss_has_sprite small <?php if (!$may_upgrade) { echo 'quiet'; } ?>"><span class="ss_sprite_16 ss_bullet_star">&#160;</span>Uncheck if you want to execute a fresh install <strong>(your site data will be lost!)</strong></p>

		<div class="right">
			<button name="submit" type="submit"><span class="ss_sprite_16 ss_lock_go">&#160;</span>Proceed</button>
			<a class="button" href="index.php" title="Back to step first step"><span class="ss_sprite_16 ss_cancel">&#160;</span>Cancel</a>
		</div>
		<input type="hidden" name="do" value="<?php echo md5('3'); ?>" id="do" />
		<script>
function mkNewAuthCode()		
{
	var node = $('authcode');
	var val = node.get('value').trim();

	var request = new Request.JSON({
		url:'installer.inc.php',
		data: 'do=mkNewAuthCode',
		method:'post',
		onComplete: function(properties, text) {
			//alert(text);
			//alert(properties);
			//alert(properties.code);
			node.set('value', properties.code);
		}
	}).send();
}
		</script>
<?php

	exit();
} // Close step two

// assistant for step 2:
if ($nextstep == 'mkNewAuthCode')
{
	// no need to check the session?
	$code = GenerateNewAuthCode();

	$return = array(
		'code' => $code
	);

	header('Content-type: application/json');
	echo json_encode($return);

	exit();
}

// Step three
if($nextstep == md5('3') && CheckAuth()) 
{
	//
	// Installation actions
	//  - Saving preferences
	//

	$version    = array('version' => getPOSTparam4boolean('version'));
	$iframe     = array('iframe' => getPOSTparam4boolean('iframe'));
	$wysiwyg    = array('wysiwyg' => getPOSTparam4boolean('wysiwyg'));
	$protect    = array('protect' => getPOSTparam4boolean('protect'));
	$userPass   = array('userPass' => $_POST['userPass']); // must store this in RAW form - will not be displayed anywhere, is only fed to MD5()
	$authcode   = array('authcode' => getPOSTparam4IdOrNumber('authcode'));
	$do_upgrade = array('do_upgrade' => getPOSTparam4boolean('upgrade'));

	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$version,$iframe,$wysiwyg,$protect,$userPass,$authcode,$do_upgrade);
?>
	<legend class="installMsg">Step 3 - Collecting your database details</legend>
		<label for="db_host"><span class="ss_sprite_16 ss_server_database">&#160;</span>Database host</label>
		<input type="text" class="alt title" name="db_host" value="<?php
			echo (empty($_SESSION['variables']['db_host']) ? 'localhost' : $_SESSION['variables']['db_host']); ?>" id="db_host" />
		<br/>
		<label for="db_user"><span class="ss_sprite_16 ss_drive_user">&#160;</span>Database username</label>
		<input type="text" class="alt title" name="db_user" value="<?php
			echo (empty($_SESSION['variables']['db_user']) ? '' : $_SESSION['variables']['db_user']); ?>" id="db_user" />
		<br/>
		<label for="db_pass"><span class="ss_sprite_16 ss_drive_key">&#160;</span>Database password</label>
		<input type="password" class="title" name="db_pass" value="<?php
			echo (empty($_SESSION['variables']['db_pass']) ? '' : $_SESSION['variables']['db_pass']); ?>" id="db_pass" />
		<br>
		<label for="db_name"><span class="ss_sprite_16 ss_database">&#160;</span>Database name</label>
		<input type="text" class="alt title" name="db_name" value="<?php
			echo (empty($_SESSION['variables']['db_name']) ? 'compactcms' : $_SESSION['variables']['db_name']); ?>" id="db_name" />
		<br/>
		<label for="db_prefix"><span class="ss_sprite_16 ss_database_table">&#160;</span>Database table prefix</label>
		<input type="text" class="alt title" name="db_prefix" value="<?php
			echo (empty($_SESSION['variables']['db_prefix']) ? 'ccms_' : $_SESSION['variables']['db_prefix']); ?>" id="db_prefix" />

		<div class="right">
			<button name="submit" type="submit"><span class="ss_sprite_16 ss_information">&#160;</span>To confirmation</button>
			<a class="button" href="index.php" title="Back to step first step"><span class="ss_sprite_16 ss_cancel">&#160;</span>Cancel</a>
		</div>
		<input type="hidden" name="do" value="<?php echo md5('4'); ?>" id="do" />
<?php

	exit();
} // Close step three

// Step four
if($nextstep == md5('4') && CheckAuth())
{
	//
	// Installation actions
	//  - Process database
	//
	$db_host    = array("db_host" => getPOSTparam4IdOrNumber('db_host'));
	$db_user    = array("db_user" => getPOSTparam4IdOrNumber('db_user'));
	$db_pass    = array("db_pass" => $_POST['db_pass']); // must be RAW
	$db_name    = array("db_name" => getPOSTparam4IdOrNumber('db_name'));
	$db_prefix  = array("db_prefix" => getPOSTparam4IdOrNumber('db_prefix'));

	// Add new data to variable session
	$_SESSION['variables'] = array_merge($_SESSION['variables'],$db_host,$db_user,$db_pass,$db_name,$db_prefix);

	//
	// Check for current chmod(); we only are interesting in whether these files and directories are readable and writeable:
	// this also works out for Windows-based servers.
	//
	$chfile = array();
	/*
	Note that the 'required' 0666/0777 access rights are, in reality, overdoing it. To be more precise:
	these files and directories should have [W]rite access enabled for the user the php binary is running
	under. Generally that user would be the user under which the webserver, e.g. apache, is running
	(CGI may be a different story!)

	Next to that, the directories tested here need e[X]ecutable access for that same user as well.

	This is /less/ than the 0666/0777 splattergun, but the latter is easier to grok and do for novices.
	So the message can remain 0666/0777 but in here we're performing the stricter check, as 'is_writable_ex()'
	is the one which really counts after all: that's the very same check performed by the PHP engine on
	open-for-writing any file/directory.
	*/
	if(!is_writable_ex(BASE_PATH.'/.htaccess')) { $chfile[] = '.htaccess (0666)'; }
	if(!is_writable_ex(BASE_PATH.'/lib/config.inc.php')) { $chfile[] = '/lib/config.inc.php (0666)'; }
	if(!is_writable_ex(BASE_PATH.'/content/home.php')) { $chfile[] = '/content/home.php (0666)'; }
	if(!is_writable_ex(BASE_PATH.'/content/contact.php')) { $chfile[] = '/content/contact.php (0666)'; }
	if(!is_writable_ex(BASE_PATH.'/lib/templates/ccms.tpl.html')) { $chfile[] = '/lib/templates/ccms.tpl.html (0666)'; }
	// Directories under risk due to chmod(0777)
	if(!is_writable_ex(BASE_PATH.'/content/')) { $chfile[] = '/content/ (0777)'; }
	if(!is_writable_ex(BASE_PATH.'/media/')) { $chfile[] = '/media/ (0777)'; }
	if(!is_writable_ex(BASE_PATH.'/media/albums/')) { $chfile[] = '/media/albums/ (0777)'; }
	if(!is_writable_ex(BASE_PATH.'/media/files/')) { $chfile[] = '/media/files/ (0777)'; }
	if(!is_writable_ex(BASE_PATH.'/lib/includes/cache/')) { $chfile[] = '/lib/includes/cache/ (0777)'; }
?>
	<legend class="installMsg">Step 4 - Review your input</legend>
		<?php 
		if(count($chfile) == 0) 
		{ 
		?>
			<p class="ss_has_sprite center-text"><span class="ss_sprite_16 ss_tick">&#160;</span><em>All files are already correctly chmod()'ed</em></p>
		<?php 
		} 
		
		if(ini_get('safe_mode') || count($chfile) > 0)
		{ 
		?>
			<h2><span class="ss_sprite_16 ss_exclamation">&#160;</span>Warning</h2>
			<p>It appears that it <abbr title="Based on current chmod() rights and/or safe mode restrictions">may not be possible</abbr> 
			for the installer to chmod() various files. Please consider doing so manually <em>or</em> by using the 
			<a href="index.php?do=<?php echo md5('ftp'); ?>">built-in FTP chmod function</a>.</p>
			<span>&rarr; <em>Files that still require chmod():</em></span>
			<ul>
				<?php 
				foreach ($chfile as $value) 
				{
					echo "<li>$value</li>";
				}
				?>
			</ul>
		<?php 
		} 
		?>
		<h2><span class="ss_sprite_16 ss_computer">&#160;</span>Environment</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="altcolor">
				<th width="55%" scope="row">Sitename</th>
				<td><?php echo $_SESSION['variables']['sitename'];?></td>
			</tr>
			<tr>
				<th scope="row">Root directory</th>
				<td><?php echo $_SESSION['variables']['rootdir'];?></td>
			</tr>
			<tr class="altcolor">
				<th scope="row">Language</th>
				<td><?php echo $_SESSION['variables']['language'];?></td>
			</tr>
		</table>

		<h2><span class="ss_sprite_16 ss_cog">&#160;</span>Preferences</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="altcolor">
				<th width="55%" scope="row">Version Check</th>
				<td><?php echo ($_SESSION['variables']['version'] ? 'yes' : '---');?></td>
			</tr>
			<tr>
				<th scope="row">Iframes Allowed</th>
				<td><?php echo ($_SESSION['variables']['iframe'] ? 'yes' : '---');?></td>
			</tr>
			<tr class="altcolor">
				<th scope="row">Visual editor</th>
				<td><?php echo ($_SESSION['variables']['wysiwyg'] ? 'yes' : '---');?></td>
			</tr>
			<tr>
				<th scope="row">User authentication</th>
				<td><?php echo ($_SESSION['variables']['protect'] ? 'yes' : '---');?></td>
			</tr>
			<tr class="altcolor">
				<th scope="row">Administrator password</th>
				<td> *** </td>
			</tr>
			<tr>
				<th scope="row">Authentication PIN</th>
				<td><?php echo $_SESSION['variables']['authcode'];?></td>
			</tr>
			<tr class="altcolor">
				<th width="55%" scope="row">Install Type</th>
				<td><?php 
				if ($_SESSION['variables']['do_upgrade'])
				{
					echo '<span class="signal_upgrade_mode">Upgrade/Restore</span>';
				}
				else if ($_SESSION['variables']['may_upgrade'])
				{
					// we MAY but we DO NOT upgrade... hmmm...
					echo '<span class="signal_upgrade_mode">New Installation</span>';
				}
				else
				{
					echo 'New Installation';
				}
				?></td>
			</tr>
		</table>
		
		<h2><span class="ss_sprite_16 ss_database">&#160;</span>Database details</h2>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr class="altcolor">
				<th width="55%" scope="row">Database host</th>
				<td><?php echo $_SESSION['variables']['db_host'];?></td>
			</tr>
			<tr>
				<th scope="row">Database username</th>
				<td><?php echo $_SESSION['variables']['db_user'];?></td>
			</tr>
			<tr class="altcolor">
				<th scope="row">Database password</th>
				<td> *** </td>
			</tr>
			<tr>
				<th scope="row">Database name</th>
				<td><?php echo $_SESSION['variables']['db_name'];?></td>
			</tr>
			<tr class="altcolor">
				<th scope="row">Database table prefix</th>
				<td><?php echo $_SESSION['variables']['db_prefix'];?></td>
			</tr>
		</table>

		<hr noshade="noshade" />
		<p class="ss_has_sprite quiet">
			<span class="ss_sprite_16 ss_exclamation">&#160;</span><strong>Please note</strong><br/>
			Any data that is currently in <strong><?php echo $_SESSION['variables']['db_prefix']; ?>pages</strong> and <strong><?php echo $_SESSION['variables']['db_prefix']; ?>users</strong> might be overwritten, depending your server configuration.
		</p>

		<div class="right">
			<button name="submit" id="installbtn" type="submit"><span class="ss_sprite_16 ss_accept">&#160;</span>Install <strong>CompactCMS</strong></button>
			<a class="button" href="index.php" title="Back to step first step"><span class="ss_sprite_16 ss_cancel">&#160;</span>Cancel</a>
		</div>
		<input type="hidden" name="do" value="<?php echo md5('final'); ?>" id="do" />
<?php

	exit();
} // Close step four

/**
*
* Do the actual configuration
*
**/

// Final step
if($nextstep == md5('final') && CheckAuth())
{
	//
	// Installation actions
	//  - Set collected data
	//

	// Let's start with a clean sheet
	$err = 0;

	// Include MySQL class && initiate
	/*MARKER*/require_once(BASE_PATH.'/lib/class/mysql.class.php');
	$db = new MySQL();

	//
	// Try database connection
	//
	if (!$db->Open($_SESSION['variables']['db_name'], $_SESSION['variables']['db_host'], $_SESSION['variables']['db_user'], $_SESSION['variables']['db_pass']))
	{
		$errors[] = 'Error: could not connect to the database';
		$errors[] = $db->Error();
		$err++;
	}
	else
	{
		$log[] = "Database connection successful";
	}

	//
	// Insert database structure and sample data
	//
	if($err==0)
	{
		$currently_in_sqltextdata = false;

		function is_a_sql_query_piece($line)
		{
			global $currently_in_sqltextdata;

			$line = trim($line);

			if (!$currently_in_sqltextdata)
			{
				// not in a quoted text section? --> ignore empty lines and commented lines
				if (empty($line))
					return false;
				if (substr($line, 0, 2) == '--')
					return false;
				if ($line[0] == '#')
					return false;
			}

			/*
			* Check whether we're right smack in the middle of a multiline text being inserted, e.g.:
			*
			*     -- This should be recognized as a comment line!
			*     INSERT INTO ccms_modnews VALUES ('5', '1', '2nd-news', 'newz #2', 'wut?', 'And you call this news?
			*
			*     Good gracious me!
			*
			*     -- This would definitely break our array_filter if we\'re not careful...
			*     # and so would this line
			*     --and this
			*     --#and this!', '2010-10-31 06:20:00', '1');
			*/
			$line = str_replace("\\'", '', $line);
			$line = str_replace("''", '', $line);
			$quotedchunks = explode("'", $line);
			$idx = ($currently_in_sqltextdata ? 1 : 0) + count($quotedchunks);
			$currently_in_sqltextdata = ($idx % 2 == 0);

			// anything in a textblock is valid!

			return true;
		}

		$sqldump = array();

		$sql = file_get_contents(BASE_PATH.'/_docs/structure.sql');
		$sql = preg_replace('/ccms_/', $_SESSION['variables']['db_prefix'], $sql);
		$sql = preg_replace("/'admin', '[0-9a-f]{32}'/", "'admin', '".md5($_SESSION['variables']['userPass'].$_SESSION['variables']['authcode'])."'", $sql);
		$sql = str_replace("\r\n", "\n", $sql);

		// Execute per sql piece: 
		$currently_in_sqltextdata = false;
		$query_so_far = '';
		$queries = explode(";\n", $sql);
		foreach($queries as $tok)
		{
			// filter query: remove comment lines, then see if there's anything left to BE a query...
			$lines = array_filter(explode("\n", $tok), "is_a_sql_query_piece");
			if ($currently_in_sqltextdata)
			{
				/*
				MySQL supports multiline texts in queries; apparently we have a text here which has a line ending with a semicolon :-(
				
				We can only be certain it's a b0rked query by the time we've reached the very end of the SQL file!
				*/
				$query_so_far .= implode("\n", $lines) . ";\n";
				continue;
			}
				
			$tok = trim($query_so_far . implode("\n", $lines));
			$query_so_far = '';

			if (empty($tok))
				continue;

			if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
			{
				$results = $db->Query($tok);
				if ($results == false)
				{
					$errors[] = 'Error: executing query: ' . $tok;
					$errors[] = $db->Error();
					$err++;
				}
			}
			else
			{
				$sqldump[] = "Execute query:\n---------------------------------------\n" . $tok . "\n---------------------------------------\n";
			}
		}
		
		if ($currently_in_sqltextdata)
		{
			echo "<pre>B0rked on query:\n".$query_so_far."\n---------------------------------\n";
			die();
		}

		if ($err == 0 && $_SESSION['variables']['do_upgrade'])
		{
			$sql = file_get_contents(BASE_PATH.'/media/files/ccms-restore/compactcms-sqldump.sql');
			$sql = preg_replace('/\\bccms_\\B/', $_SESSION['variables']['db_prefix'], $sql); // all tables here-in will get the correct prefix: we're doing a restore, so we have this info from the config.inc.php file, but we may have changed our setup in the install run just before!
			$sql = preg_replace("/'admin', '[0-9a-f]{32}'/", "'admin', '".md5($_SESSION['variables']['userPass'].$_SESSION['variables']['authcode'])."'", $sql);
			// note that the passwords for the other users in the backup may be invalid IFF you changed the authcode!
			$sql = str_replace("\r\n", "\n", $sql);
			
			// Execute per sql piece: 
			$currently_in_sqltextdata = false;
			$query_so_far = '';
			$queries = explode(";\n", $sql);
			foreach($queries as $tok)
			{
				// filter query: remove comment lines, then see if there's anything left to BE a query...
				$lines = array_filter(explode("\n", $tok), "is_a_sql_query_piece");
				if ($currently_in_sqltextdata)
				{
					/*
					MySQL supports multiline texts in queries; apparently we have a text here which has a line ending with a semicolon :-(
					
					We can only be certain it's a b0rked query by the time we've reached the very end of the SQL file!
					*/
					$query_so_far .= implode("\n", $lines) . ";\n";
					continue;
				}
					
				$tok = trim($query_so_far . implode("\n", $lines));
				$query_so_far = '';

				if (empty($tok))
					continue;

				/*
				- ignore 'DROP TABLE' queries
				
				- process 'CREATE TABLE' queries by REPLACING them with 'TRUNCATE TABLE' queries; 
				  after all, they will soon be followed up with INSERT INTO queries and we don't 
				  want the 'fresh install' records to linger in there when performing 
				  an upgrade/restore.
				  
				  NOTE that SQL dumps since 1.4.2 (rev. 2011/01/11) do contain their own TRUNCATE TABLE
				  statements, and we do know that is so, but here we wish to be as backwards compatible
				  as humanly possible. Besides a dual TRUNCATE TABLE doesn't hurt, so we don't filter
				  those TRUNCATE statements when they exist in the original SQL script.
				*/
				if (preg_match('/DROP\s+TABLE/i', $tok))
					continue;
					
				if (preg_match('/CREATE\s+TABLE\s+(IF\s+NOT\s+EXISTS\s+)?`?([a-zA-Z0-9_\-]+)`?\s+\(/is', $tok, $matches))
				{
					if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
					{
						$results = $db->TruncateTable($matches[2]);
						if ($results == false)
						{
							$errors[] = 'Error: executing query: ' . $db->GetLastSQL();
							$errors[] = $db->Error();
							$err++;
						}
					}
					else
					{
						$sqldump[] = "Execute query:\n---------------------------------------\nTRUNCATE TABLE `" . $matches[2] . "`\n---------------------------------------\n";
					}
				}
				else
				{
					if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
					{
						$results = $db->Query($tok);
						if ($results == false)
						{
							$errors[] = 'Error: executing query: ' . $tok;
							$errors[] = $db->Error();
							$err++;
						}
					}
					else
					{
						$sqldump[] = "Execute query:\n---------------------------------------\n" . $tok . "\n---------------------------------------\n";
					}
				}
			}
			
			if ($currently_in_sqltextdata)
			{
				echo "<pre>B0rked on query:\n".$query_so_far."\n---------------------------------\n";
				die();
			}
		}
		
		if ($err == 0)
		{
			$log[] = "Database structure and data successfully imported";
		}
		if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'] && $dump_queries_n_stuff_in_devmode)
		{
?>
			<h2>Database Initialization</h2>
			<pre class="small"><?php
				foreach($sqldump as $line)
				{
					echo htmlspecialchars($line, ENT_COMPAT, 'UTF-8');
				}
			?></pre>
<?php
		}

		
	}

	//
	// Set chmod on config.inc.php, .htaccess, content, cache and albums
	//
	if($err==0 && empty($_POST['ftp_host']) /* && !strpos($_SERVER['SERVER_SOFTWARE'], "Win") */ )
	{
		// Set warning when safe mode is enabled
		if(ini_get('safe_mode')) 
		{
			$errors[] = 'Warning: safe mode is enabled, skipping chmod()';
		}

		// Set default values
		$chmod = 0;
		$errfile = array();

		// Chmod check and set function
		function setChmod($path, $value) 
		{
			// Check current chmod() status
			if(substr(sprintf('%o', fileperms(BASE_PATH.$path)), -4) != $value) 
			{
				// If not set, set
				if(@chmod(BASE_PATH.$path, $value)) 
				{
					return true;
				}
			} 
			else 
			{
				return true;
			}
		}

		// Do chmod() per necessary folder and set status
		//
		// The permissions MUST be octal numbers (or at least integers); see also http://nl.php.net/manual/en/function.chmod.php and the comment by Geoff W. @ 2010/feb/08 !
		if(setChmod('/.htaccess', 0666)) { $chmod++; } else $errfile[] = 'Could not chmod() /.htaccess/';
		if(setChmod('/lib/config.inc.php', 0666)) { $chmod++; } else $errfile[] = 'Could not chmod() /lib/config.inc.php';
		if(setChmod('/content/home.php', 0666)) { $chmod++; } else $errfile[] = 'Could not chmod() /content/home.php';
		if(setChmod('/content/contact.php', 0666)) { $chmod++; } else $errfile[] = 'Could not chmod() /content/contact.php';
		if(setChmod('/lib/templates/ccms.tpl.html', 0666)) { $chmod++; } else $errfile[] = 'Could not chmod() /lib/templates/ccms.tpl.html';

		// Directories under risk due to chmod(0777)
		if(setChmod('/content/', 0777)) { $chmod++; } else $errfile[] = 'Could not chmod() /content/';
		if(setChmod('/media/', 0777)) { $chmod++; } else $errfile[] = 'Could not chmod() /media/';
		if(setChmod('/media/albums/', 0777)) { $chmod++; } else $errfile[] = 'Could not chmod() /media/albums/';
		if(setChmod('/media/files/', 0777)) { $chmod++; } else $errfile[] = 'Could not chmod() /media/files/';
		if(setChmod('/lib/includes/cache/', 0777)) { $chmod++; } else $errfile[] = 'Could not chmod() /lib/includes/cache/';

		if($chmod>0) 
		{
			$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Confirmed correct chmod() on '.$chmod.' files/directories.</abbr>';
		}
		if($chmod==0 || count($errfile) > 0) 
		{
			$errors[] = 'Warning: could not chmod() all files.';
			foreach ($errfile as $key => $value) 
			{
				$errors[] = $value;
			}
			$errors[] = 'Either use the <a href="index.php?do=' . md5('ftp') . '">built-in FTP chmod function</a>, or manually perform chmod().';
		}
	}

	//
	// Perform optional FTP chmod command
	//
	if($err==0 && !empty($_POST['ftp_host']) && !empty($_POST['ftp_user'])) 
	{
		// Set up a connection or die
		$conn_id = ftp_connect($_POST['ftp_host']) or die("Couldn't connect to ".$_POST['ftp_host']);
		if ($conn_id !== false)
		{
			// Try to login using provided details
			if(@ftp_login($conn_id, $_POST['ftp_user'], $_POST['ftp_pass'])) 
			{
				// trimPath function
				function trimPath($path,$depth) 
				{
					$path = explode('/',$path);
					$np = '/';
					for ($i=$depth; $i<count($path); $i++) 
					{
						$np .= $path[$i].'/';
					}
					return $np;
				}

				// Find FTP path
				$i      = 1;
				$path   = $_POST['ftp_path'];

				// Set max tries to 15
				for ($i=1; $i<15; $i++) 
				{ 
					if(@ftp_chdir($conn_id, trimPath($path,$i))) 
					{
						$log[] = "Successfully connected to FTP server";
						$i = 15;
					}
				}
			} 
			else 
			{
				$errors[] = "Fatal: couldn't connect to the FTP server. Perform chmod() manually.";
				$err++;
			}
			// Count the ftp_chmod() successes
			$ftp_chmod = 0;
			$errfile = array();

			// Perform the ftp_chmod command
			if(@ftp_chmod($conn_id, 0666, "./.htaccess")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /.htaccess/';
			if(@ftp_chmod($conn_id, 0666, "./lib/config.inc.php")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /lib/config.inc.php';
			if(@ftp_chmod($conn_id, 0666, "./content/home.php")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /content/home.php';
			if(@ftp_chmod($conn_id, 0666, "./content/contact.php")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /content/contact.php';
			if(@ftp_chmod($conn_id, 0666, "./lib/templates/ccms.tpl.html")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /lib/templates/ccms.tpl.html';
			// Directories under risk due to chmod(0777)
			if(@ftp_chmod($conn_id, 0777, "./content/")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /content/';
			if(@ftp_chmod($conn_id, 0777, "./media/")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /media/';
			if(@ftp_chmod($conn_id, 0777, "./media/albums")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /media/albums/';
			if(@ftp_chmod($conn_id, 0777, "./media/files/")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /media/files/';
			if(@ftp_chmod($conn_id, 0777, "./lib/includes/cache/")) { $ftp_chmod++; } else $errfile[] = 'Could not chmod() /lib/includes/cache/';

			if($ftp_chmod>0) 
			{
				$log[] = '<abbr title=".htaccess, config.inc.php, ./content/, ./lib/includes/cache/, back-up folder &amp; 2 media folders">Successful chmod() on '.$chmod.' files/directories using FTP.</abbr>';
			} 
			if($ftp_chmod==0 || count($errfile) > 0) 
			{
				$errors[] = 'Fatal: could not FTP chmod() various files.';
				foreach ($errfile as $key => $value) 
				{
					$errors[] = $value;
				}
				$errors[] = 'Perform chmod() manually.';
				$err++;
			}

			// Close the connection
			ftp_close($conn_id);
		}
	}

	//
	// Write config.inc.php file
	//
	if($err==0)
	{
		/*
		Keep comments, etc. intact in the file; they help when manually modifying
		or upgrading the site.
		*/
		$configinc  = file_get_contents(BASE_PATH . '/lib/config.inc.php');

		// Write new variables to configuration file

		// Edit start line
		$newline = "Copyright (C) 2008 - ".date('Y')." ";
		$configinc = preg_replace('/Copyright \(C\) 2008 - [0-9]+ /', $newline, $configinc);

		// Compare old and new variables - the old set is already loaded at the top!
		foreach($cfg as $key=>$val)
		{
			if (isset($_SESSION['variables'][$key]))
			{
				$new_val = $_SESSION['variables'][$key];
			}
			else
			{
				$new_val = $cfg[$key];
			}
			// Rewrite the previous loaded string
			if($new_val===true||$new_val===false)
			{
				$new_val = ($new_val ? 'true' : 'false');
				$config_str = "\$cfg['{$key}'] = {$new_val};";
				$re_str = '/\$cfg\[\''.$key.'\'\]\s+=\s+[^;]+;/';
			}
			else if(is_integer($new_val))
			{
				$config_str = "\$cfg['{$key}'] = {$new_val};";
				$re_str = '/\$cfg\[\''.$key.'\'\]\s+=\s+[^;]+;/';
			}
			else
			{
				$config_str = "\$cfg['{$key}'] = '{$new_val}';";
				$re_str = '/\$cfg\[\''.$key.'\'\]\s+=\s+[\'"].*[\'"]\s*;/';
			}
			$configinc = preg_replace($re_str, $config_str, $configinc);
		}

		// Write the new setup to the config file
		if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
		{
			// make sure the fopen(..., 'w') is only called when the destination is writable; otherwise an empty file may be produced.
			if (is_writable_ex(BASE_PATH . '/lib/config.inc.php') && ($fp = fopen(BASE_PATH . '/lib/config.inc.php', 'w')))
			{
				if(fwrite($fp, $configinc, strlen($configinc)))
				{
					$log[] = "Successfully wrote the new configuration values in the config.inc.php file";
				}
				else
				{
					$err++;
					$errors[] = "Fatal: Problem saving new configuration values";
				}
				fclose($fp);
			}
			else
			{
				$errors[] = 'Fatal: the configuration file is not writable.';
				$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
				$err++;
			}
		}
		else
		{
			if ($dump_queries_n_stuff_in_devmode)
			{
?>
				<h2>config.inc.php Configuration Values - after modification</h2>
				<pre class="small"><?php echo htmlspecialchars($configinc, ENT_COMPAT, 'UTF-8'); ?></pre>
<?php
			}
			
			$log[] = "Successfully wrote the new configuration values in the config.inc.php file";
		}
	}
	//
	// Modify .htaccess file
	//
	if($err==0)
	{
		$htaccess   = @file_get_contents(BASE_PATH.'/.htaccess');
		$newpath    = $_SESSION['variables']['rootdir'];

		if(strpos($htaccess, $newline)===false)
		{
			// remove the <IfDefine> and </IfDefine> to turn on the rewrite rules, now that we have the site configured!
			$htaccess = str_replace('<IfDefine CCMS_installed>', '# <IfDefine CCMS_installed>', $htaccess);
			$htaccess = str_replace('</IfDefine> # CCMS_installed', '# </IfDefine> # CCMS_installed', $htaccess);

			// make sure the regexes tolerate ErrorDocument/RewriteBase lines which point at a subdirectory instead of the / root:
			$htaccess = preg_replace('/(ErrorDocument\s+[0-9]+\s+)\/(.*)(index\.php\?page)/', '\\1' . $newpath . '\\3', $htaccess);
			$htaccess = preg_replace('/(RewriteBase\s+)\/.*/', '\\1' . $newpath, $htaccess);
			if (!$htaccess)
			{
				$errors[] = 'Fatal: could not set the RewriteBase in the .htaccess file.';
				$err++;
			}
			else
			{
				if (!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
				{
					if (is_writable_ex(BASE_PATH . '/.htaccess') && ($fp = fopen(BASE_PATH . '/.htaccess', 'w')))
					{
						if(fwrite($fp, $htaccess, strlen($htaccess)))
						{
							$log[] = "Successfully rewrote the .htaccess file";
						}
						else
						{
							$errors[] = "Fatal: Problem saving new .htaccess file.";
							$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
							$err++;
						}
						fclose($fp);
					}
					else
					{
						$errors[] = 'Fatal: the .htaccess file is not writable.';
						$errors[] = 'Make sure the file is writable, or <a href="index.php?do=ff104b2dfab9fe8c0676587292a636d3">do so now</a>.';
						$err++;
					}
				}
				else
				{
					if ($dump_queries_n_stuff_in_devmode)
					{
?>
						<h2>.htaccess Rewrite Rules - after modification</h2>
						<pre class="small"><?php echo htmlspecialchars($htaccess, ENT_COMPAT, 'UTF-8'); ?></pre>
<?php
					}
					
					$log[] = "Successfully rewrote the .htaccess file";
				}
			}
		}
	}

?>
	<legend class="installMsg">Final - Finishing the installation</legend>
	<?php 
	if(isset($log)) 
	{
		unset($_SESSION['variables']); 
		?>
		<h2>Process results</h2>
		<?php
		while (list($key,$value) = each($log)) 
		{
			echo '<p class="ss_has_sprite"><span class="ss_sprite_16 ss_accept">&#160;</span>'.$value.'</p>';
		} 
	} 
	if(isset($errors)) 
	{ 
	?>
		<h2>Errors &amp; warnings</h2>
		<?php
		while (list($key,$value) = each($errors)) 
		{
			echo '<p class="ss_has_sprite"><span class="ss_sprite_16 ss_exclamation">&#160;</span>'.$value.'</p>';
		} 
	} 
	
	if($err==0) 
	{ 
	?>
		<h2>What's next?</h2>
		<p>The installation has been successful! You should now follow the steps below, to get you started.</p>
		<ol>
			<li>Delete the <em>./_install</em> directory</li>
			<li><a href="../admin/">Login</a> using username <span class="ss_sprite_16 ss_user_red">&#160;</span><strong>admin</strong></li>
			<li>Change your password through the back-end</li>
			<li><a href="http://www.compactcms.nl/contact.html" target="_blank">Let me know</a> how you like CompactCMS!</li>
		</ol>
	<?php 
	} 
	else 
	{
	?>
		<div class="right">
			<p class="ss_has_sprite"><a class="button" href="index.php"><span class="ss_sprite_16 ss_arrow_undo">&#160;</span>Retry setting the variables</a></p>
		</div>
	<?php
	}

	exit();
} // Close final processing


// when we get here, something went horribly wrong. Have you been messing about?
?>
<p class="error">The flow has been broken. Some unidentified internal error occurred.</p>
