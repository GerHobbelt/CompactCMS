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


// Include general configuration
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}

/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


$do = getGETparam4IdOrNumber('do');


// Disable common hacking functions
ini_set('base64_decode', 'Off');
ini_set('exec', 'Off');
ini_set('allow_url_fopen', 'Off');
ini_set('allow_url_include', 'Off');

// Set appropriate auth.inc.php file location
$loc = $cfg['rootdir'] . 'lib/includes/auth.inc.php';

// Check whether current user has running session
if(empty($_SESSION['ccms_userID']) && $cfg['protect'])
{
	header('Location: ' . makeAbsoluteURI($loc));
	exit();
}

// Do log-out (kill sessions) and redirect
if($do == 'logout')
{
	// check if the current user is a 'switched to' user; check whether the switching info is valid.
	//
	// Note that the latter bit there is NOT about doing a full security check; any user which can
	// manipulate the session data (e.g. by having the PHP coding page edit right) has everything you'll
	// ever need to become an admin anyhow; we are not worried such users are 'bad guys' trying to
	// hack the site; all security checks rather focus on whether you are a valid user or not-a-user.
	// Keep that in mind when secuirty auditing. This bit has to be safe from attackers who do not
	// have write access to the $_SESSION[] array.
	if (checkAuth() 
		&& !empty($_SESSION['ccms_userID']) 
		/* && !empty($_SESSION['ccms_userName']) && !empty($_SESSION['ccms_userFirst']) && !empty($_SESSION['ccms_userLast']) */
		&& !empty($_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= 1  
		&& !empty($_SESSION['ccms_isSwitchedUser']))
	{
		// set a default error message:
		$logmsg = '<strong>Session data corruption or hack attempt. Access denied.</strong>';
		
		$su_arr = explode(':', $_SESSION['ccms_isSwitchedUser'], 3);
		if (count($su_arr) != 3 || intval($su_arr[1]) != 4 /* admin level */)
		{
			// something was corrupted; treat this as a regular logout!
		}
		else
		{
			$values = array();
			$values['userID'] = MySQL::SQLValue($su_arr[0], MySQL::SQLVALUE_NUMBER);
			$values['userName'] = MySQL::SQLValue($su_arr[2], MySQL::SQLVALUE_TEXT);
			$values['userLevel'] = MySQL::SQLValue($su_arr[1], MySQL::SQLVALUE_NUMBER);
			$values['userActive'] = MySQL::SQLValue(true, MySQL::SQLVALUE_BOOLEAN);
			$row = $db->SelectSingleRowArray($cfg['db_prefix'].'users', $values);

			if ($db->ErrorNumber()) 
			{
				//$db->Kill();
				$logmsg = $db->MyDyingMessage();
			}
			elseif ($db->RowCount() > 1)
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
			}
			elseif($su_arr[2] != $row['userName'] || $row['userActive'] <= 0)
			{
				// If no match: count attempt and show error
				//
				// NOTE: code should never enter here!
				//
				$logmsg = 'INTERNAL ERROR!';
			}
			else
			{
				// Set system wide session variables
				$_SESSION['ccms_isSwitchedUser'] = false;
				
				$_SESSION['ccms_userID']    = $row['userID'];
				$_SESSION['ccms_userName']  = $row['userName'];
				$_SESSION['ccms_userFirst'] = $row['userFirst'];
				$_SESSION['ccms_userLast']  = $row['userLast'];
				$_SESSION['ccms_userLevel'] = $row['userLevel'];

				// [i_a] fix for session faking/hijack security issue:
				// Setting safety variables as well: used for checkAuth() during the session.
				SetAuthSafety();

				unset($logmsg);
				// Return functions result
				header('Location: ' . makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'));
				exit();
			}
		}
		die_and_goto_url(null, $logmsg);
	}
	
	// Unset all of the session variables.
	$_SESSION = array();

	// Destroy session
	if (ini_get('session.use_cookies'))
	{
		$params = session_get_cookie_params();
		if (!empty($params['ccms_userID']))
		{
			setcookie(session_name(), '', time() - 42000,
				$params['path'], $params['domain'],
				$params['secure'], $params['httponly']
			);
		}
	}

	// Generate a new session_id
	session_regenerate_id();

	// Finally, destroy the session.
	if(session_destroy())
	{
		header('Location: ' . makeAbsoluteURI($loc));
		exit();
	}

	if(empty($_SESSION['ccms_userID']))
	{
		header('Location: ' . makeAbsoluteURI($loc));
		exit();
	}
}



/*
-----------------------------------------------------------------

Further setup/init work for the entire admin section of the site:

-----------------------------------------------------------------
*/


?>