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
 * We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc.
 */
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Include general configuration
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

/*
 * NOTICE:
 *
 * as this file can be loaded as part of /anything/, you CANNOT SPECIFY RELATIVE PATHS FOR URLs IN HERE AND EXPECT TO LIVE!
 *
 * URLs and local paths in here MUST be absolute: use $cfg['rootdir'] and BASE_PATH respectively to make it so.
 */

$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');

// If session already exists
if(!empty($_SESSION['ccms_userID']) && !empty($_SESSION['ccms_userName']) && checkAuth()) // [i_a] session vars must exist AND NOT BE EMPTY to be deemed valid.
{
	$qry = '';
	if (!empty($status) || !empty($status_message))
	{
		$qry = '?status=' . rawurlencode($status) . '&msg=' . rawurlencode(!empty($status_message) ? $status_message : $ccms['lang']['system']['error_general']);
	}
	header('Location: ' . makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php') . $qry);
	exit();
}

// Check for ./install directory
if ($cfg['install_dir_exists'] && !$cfg['install_dir_override'])
{
	die('<strong>Security risk: the installation directory is still present.</strong><br/>Either first <a href="../../_install/">run the installer</a>, or remove the <em>./_install</em> directory, before accessing <a href="../../admin/">the back-end</a>.');
}

$userName = strtolower(getPOSTparam4IdOrNumber('userName'));
// also allow logon actions where the user is already 'pre-configured'; easy logon!
if (empty($userName))
{
	$userName = strtolower(getGETparam4IdOrNumber('logon_user'));
}

// Do authentication
if(isset($_POST['submit']) && $_SERVER['REQUEST_METHOD'] == 'POST')
{
	/*
	 * This code does NOT require that the submitted data (user+pass) originates from the
	 * web form below and was entered in the same session (as we don't have the checkAuth()
	 * condition checked in the if(...) above).
	 *
	 * This is intentional: users may store the login credentials in any form and still log
	 * in. However, it also means that we must be aware that the current POST data can be
	 * entirely malicious, hence we MUST perform rigorous checks -- which one would require
	 * anyhow when logging in.
	 *
	 * To prevent SQL injection attacks against this form, we make sure the POST-ed data
	 * does not contain any wildcards or trickery which makes our validation query below
	 * produce multiple records. If for some other reason we get multiple user records
	 * from the database then this is clearly a security/safety violation!
	 *
	 * Only when everything check out do we set the session validation items 'id' and 'host'
	 * which will be used to validate basic website interaction security for the remainder
	 * of this session.
	 */
	$userPass = md5($_POST['userPass'].$cfg['authcode']);
	$logmsg = null;

	if(empty($userName) && empty($userPass))
	{
		$logmsg = rawurlencode($ccms['lang']['login']['nodetails']);
	}
	elseif(empty($userName))
	{
		$logmsg = rawurlencode($ccms['lang']['login']['nouser']);
	}
	elseif(empty($userPass))
	{
		$logmsg = rawurlencode($ccms['lang']['login']['nopass']);
	}
	else
	{
		$values = array();
		$values['userName'] = MySQL::SQLValue($userName, MySQL::SQLVALUE_TEXT);
		$values['userPass'] = MySQL::SQLValue($userPass, MySQL::SQLVALUE_TEXT);
		$values['userActive'] = MySQL::SQLValue(false, MySQL::SQLVALUE_BOOLEAN);
		$matchNumRows = $db->SelectSingleValue($cfg['db_prefix'].'users', $values, 'COUNT(userID)');
		if ($matchNumRows > 0)
		{
			$logmsg = rawurlencode($ccms['lang']['login']['notactive']);
		}
		else
		{
			// Select statement: alter the previous condition set:
			$values['userActive'] = MySQL::SQLValue(true, MySQL::SQLVALUE_BOOLEAN);
			$row = $db->SelectSingleRowArray($cfg['db_prefix'].'users', $values);
			if ($db->ErrorNumber()) $db->Kill();

			if ($db->RowCount() > 1)
			{
				// probably corrupt db table (corrupt import?) or hack attempt
				$logmsg = '<strong>Database corruption or hack attempt. Access denied.</strong>';

				// TODO: alert website owner about this failure/abuse. email to owner?
			}
			elseif(!$row)
			{
				// no match found in DB: user/pass combo doesn't exist!
				//
				// If no match: count attempt and show error
				$logmsg = rawurlencode($ccms['lang']['login']['nomatch']);
			}
			elseif($userName != $row['userName'] || $userPass != $row['userPass'] || $row['userActive'] <= 0)
			{
				// If no match: count attempt and show error
				//
				// NOTE: code should never enter here!
				//
				$logmsg = 'INTERNAL ERROR!';
			}
			else
			{
				// If all checks are okay
				//
				// Update latest login date
				if($db->UpdateRow($cfg['db_prefix'].'users', array('userLastlog' => MySQL::SQLValue(date('Y-m-d G:i:s'), MySQL::SQLVALUE_DATETIME)), array('userID' => MySQL::BuildSQLValue($row['userID']))))
				{
					// Set system wide session variables
					$_SESSION['ccms_userID']    = $row['userID'];
					$_SESSION['ccms_userName']  = $row['userName'];
					$_SESSION['ccms_userFirst'] = $row['userFirst'];
					$_SESSION['ccms_userLast']  = $row['userLast'];
					$_SESSION['ccms_userLevel'] = $row['userLevel'];
					$_SESSION['ccms_isSwitchedUser'] = false;

					// [i_a] fix for session faking/hijack security issue:
					// Setting safety variables as well: used for checkAuth() during the session.
					SetAuthSafety();

					unset($logmsg);
					// Return functions result
					header('Location: ' . makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'));
					exit();
				}
				else
				{
					$logmsg = $db->MyDyingMessage();
				}
			}
		}
	}
	die_and_goto_url(null, $logmsg);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>CompactCMS Administration</title>
		<meta name="description" content="CompactCMS administration. CompactCMS is a light-weight and SEO friendly Content Management System for developers and novice programmers alike." />
		<link rel="stylesheet" type="text/css" href="<?php echo $cfg['rootdir']; ?>admin/img/styles/base.css,layout.css,sprite.css,last_minute_fixes.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="<?php echo $cfg['rootdir']; ?>admin/img/styles/ie.css" />
		<![endif]-->
	</head>
<body>

<?php
if(!empty($status_message))
{
?>
<div id="logon-error-report-wrapper" class="container-18">
	<div class="center-text <?php echo $status; ?>">
		<?php
		echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>';
		?>
	</div>
</div>
<?php
}
?>

<?php
// yak when the install directory is still there, due to us being a 'smart Alec' by saving an empty override file in there (/_install/install_check_override.txt):
printf("<p>install_dir = %s, base path = %s, isdir = %d, isfile = %d", 1 * $cfg['install_dir_exists'], BASE_PATH, is_dir(BASE_PATH . '/_install/'), is_file(BASE_PATH . '/_install/index.php'));
if ($cfg['install_dir_exists'])
{
?>
<div id="install-dir-warning-wrapper" class="container-18">
	<div class="center-text warning">
		<h2><span class="ss_sprite_16 ss_exclamation">&#160;</span><?php echo $ccms['lang']['backend']['warning']; ?></h2>
		<p class="center-text"><?php echo $ccms['lang']['backend']['install_dir_exists']; ?></p>
	</div>
</div>
<?php
}
?>

<div id="login-wrapper" class="container-18">
	<div id="help" class="span-8 colborder">
		<div id="logo" class="sprite logo">
			<h1>CompactCMS administration</h1>
		</div>
		<h2><span class="ss_sprite_16 ss_door_open">&#160;</span><?php echo $ccms['lang']['login']['login']; ?></h2>
		<p><?php echo $ccms['lang']['login']['welcome']; ?></p>
	</div>

	<div id="login" class="span-9 last">
		<form id="loginFrm" name="loginFrm" class="clear" action="<?php echo $cfg['rootdir']; ?>lib/includes/auth.inc.php" method="post">
			<label for="userName"><?php echo $ccms['lang']['login']['username']; ?></label><input type="text" class="alt title span-8" autofocus placeholder="username" name="userName" value="<?php echo $userName;?>" id="userName" />
			<br class="clear"/>
			<label for="userPass"><?php echo $ccms['lang']['login']['password']; ?></label><input type="password" class="title span-8" name="userPass" value="" id="userPass" />

			<p class="span-8 right">
				<button name="submit" type="submit"><span class="ss_sprite_16 ss_lock_go">&#160;</span><?php echo $ccms['lang']['login']['login']; ?></button>
			</p>
		</form>
	</div>
</div>
<p class="quiet small center-text" >&copy; 2010 <a href="http://www.compactcms.nl" title="Maintained with CompactCMS.nl">CompactCMS.nl</a></p>

<script type="text/javascript" charset="utf-8">
<?php
/*
In-line JavaScript is parsed before the external file will be; make it call back to us to execute the check and optional redirect.

However...

The /external/ script won't be loaded when this page is fetched due to a session timeout or authentication error, when the request
is performed through a mootools Request.HTML action. (stripScripts() drops items like these!)

Hence we have to check whether the functions loaded there actually exist and only act when they do. (We can assume the functions
have been loaded by the page issuing the Request.HTML action, so there's no need for panic when we are in such a situation where
our external script load is discarded through Request.HTML + stripScripts() inside mootools.

This is further complicated due to the lazy-loading process: when this page is loaded as-is, the external file will be
parsed AFTER the code above has been executed, while, when loaded through Request.HTML, we cannot be sure the functions we need
have been lazyloaded yet, so we must make sure we don't crash the browser by invoking functions which
are not (yet) available when executing the code above.


The real redirect-when-we're-not-the-top-page-itself magicking happens in the jump_if_not_top() function. jump_if_not_top2() is
here to make sure we invoke it only when it is actually available.

This means that EVERY admin page MUST load 'the_goto_guy.js', whether they use it themselves or not: the unlying code may decide
the session is invalid and go through here, where availability is required for suitable action.
*/
?>
function jump_if_not_top2()
{
	if (typeof window.jump_if_not_top == 'function')
	{
		//alert('invoking jump_if_not_top');
		window.jump_if_not_top("<?php echo $_SERVER['PHP_SELF']; ?>", "<?php echo makeAbsoluteURI($_SERVER['PHP_SELF']); ?>");
	}
}

jump_if_not_top2();

</script>
<script type="text/javascript" src="<?php echo $cfg['rootdir']; ?>lib/includes/js/the_goto_guy.js?cb=jump_if_not_top2" charset="utf-8"></script>
</body>
</html>