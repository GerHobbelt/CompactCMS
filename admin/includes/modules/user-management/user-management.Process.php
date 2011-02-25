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

class FbX extends CcmsAjaxFbException {}; // nasty way to do 'shorthand in PHP -- I do miss my #define macros! :'-|

// Some security functions


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !CheckAuth())
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die($ccms['lang']['auth']['featnotallowed']);
}


// Prevent PHP warning by setting default (null) values
$do_action = getGETparam4IdOrNumber('action');







/**
 *
 * Create a new user as posted by an authorized user
 *
 */
if($do_action == 'add-user' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	FbX::SetFeedbackLocation('user-management.Manage.php');
	try
	{
		// Only if current user has the rights
		if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']))
		{
			//$i=count(array_filter($_POST));
			//if($i <= 6) error

			if (empty($_POST['userPass']))
			{
				throw new FbX($ccms['lang']['system']['error_tooshort']);
			}
			$userName = strtolower(getPOSTparam4IdOrNumber('user'));
			$userPass = md5($_POST['userPass'].$cfg['authcode']);
			$userFirst = getPOSTparam4HumanName('userFirstname');
			$userLast = getPOSTparam4HumanName('userLastname');
			$userEmail = getPOSTparam4Email('userEmail');
			$userActive = getPOSTparam4boolean('userActive');
			$userLevel = getPOSTparam4Number('userLevel');
			if (empty($userName) || empty($userFirst) || empty($userLast) || empty($userEmail) || !$userLevel)
			{
				throw new FbX($ccms['lang']['system']['error_tooshort']);
			}

			// Set variables
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values['userName']     = MySQL::SQLValue($userName,MySQL::SQLVALUE_TEXT);
			$values['userPass']     = MySQL::SQLValue($userPass,MySQL::SQLVALUE_TEXT);
			$values['userFirst']    = MySQL::SQLValue($userFirst,MySQL::SQLVALUE_TEXT);
			$values['userLast']     = MySQL::SQLValue($userLast,MySQL::SQLVALUE_TEXT);
			$values['userEmail']    = MySQL::SQLValue($userEmail,MySQL::SQLVALUE_TEXT);
			$values['userActive']   = MySQL::SQLValue($userActive,MySQL::SQLVALUE_BOOLEAN);
			$values['userLevel']    = MySQL::SQLValue($userLevel,MySQL::SQLVALUE_NUMBER);
			// TODO: userToken is currently UNUSED. -- should be used to augment the $cfg['authcode'] where applicable
			$values['userToken']    = MySQL::SQLValue(mt_rand('123456789','987654321'),MySQL::SQLVALUE_NUMBER);

			// Execute the insert
			$result = $db->InsertRow($cfg['db_prefix'].'users', $values);

			// Check for errors
			if($result)
			{
				header('Location: ' . makeAbsoluteURI('user-management.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
				exit();
			}
			else
			{
				throw new FbX($db->MyDyingMessage());
			}
		}
		else
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Edit user details as posted by an authorized user
 *
 */
if($do_action == 'edit-user-details' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	FbX::SetFeedbackLocation('user-management.Manage.php');
	try
	{
		$userID = getPOSTparam4Number('userID');
		$userFirst = getPOSTparam4HumanName('first');
		$userLast = getPOSTparam4HumanName('last');
		$userEmail = getPOSTparam4Email('email');

		// Only if current user has the rights
		if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) || $_SESSION['ccms_userID'] == $userID)
		{
			// Check length of values
			if(strlen($userFirst) > 2 && strlen($userLast) > 2 && strlen($userEmail) > 6)
			{
				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values['userFirst'] = MySQL::SQLValue($userFirst,MySQL::SQLVALUE_TEXT);
				$values['userLast']  = MySQL::SQLValue($userLast,MySQL::SQLVALUE_TEXT);
				$values['userEmail'] = MySQL::SQLValue($userEmail,MySQL::SQLVALUE_TEXT);

				if ($db->UpdateRow($cfg['db_prefix'].'users', $values, array("userID" => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER))))
				{
					if($userID == $_SESSION['ccms_userID'])
					{
						$_SESSION['ccms_userFirst'] = $userFirst; // getPOSTparam4HumanName already does the htmlentities() encoding, so we're safe to use & display these values as they are now.
						$_SESSION['ccms_userLast']  = $userLast;
					}

					header('Location: ' . makeAbsoluteURI('user-management.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
					exit();
				}
				else
				{
					throw new FbX($db->MyDyingMessage());
				}
			}
			else
			{
				throw new FbX($ccms['lang']['system']['error_tooshort']);
			}
		}
		else
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Edit users' password as posted by an authorized user
 *
 */

if($do_action == 'edit-user-password' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	$userID = getPOSTparam4Number('userID');

	FbX::SetFeedbackLocation('user-management.Edit.php', 'userID='.$userID);
	try
	{
		// Only if current user has the rights
		if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']) || $_SESSION['ccms_userID'] == $userID)
		{
			if (empty($_POST['userPass']) || empty($_POST['cpass']))
			{
				throw new FbX($ccms['lang']['system']['error_passshort']);
			}

			$passphrase_len = strlen($_POST['userPass']);

			if($passphrase_len > 6 && md5($_POST['userPass']) === md5($_POST['cpass']))
			{
				$userPassHash = md5($_POST['userPass'].$cfg['authcode']);

				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values['userPass'] = MySQL::SQLValue($userPassHash,MySQL::SQLVALUE_TEXT);

				if ($db->UpdateRow($cfg['db_prefix'].'users', $values, array('userID' => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER))))
				{
					header('Location: ' . makeAbsoluteURI('user-management.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
					exit();
				}
				else
				{
					throw new FbX($db->MyDyingMessage());
				}
			}
			elseif($passphrase_len <= 6)
			{
				throw new FbX($ccms['lang']['system']['error_passshort']);
			}
			else
			{
				throw new FbX($ccms['lang']['system']['error_passnequal']);
			}
		}
		else
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Edit user level as posted by an authorized user
 *
 */

if($do_action == 'edit-user-level' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	FbX::SetFeedbackLocation('user-management.Manage.php');
	try
	{
		// Only if current user has the rights
		if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']))
		{
			$userID = getPOSTparam4Number('userID');
			$userActive = getPOSTparam4boolean('userActive');
			$userLevel = getPOSTparam4Number('userLevel');
			if ($userLevel > 0)
			{
				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values['userLevel'] = MySQL::SQLValue($userLevel,MySQL::SQLVALUE_NUMBER);
				$values['userActive'] = MySQL::SQLValue($userActive,MySQL::SQLVALUE_BOOLEAN);

				if ($db->UpdateRow($cfg['db_prefix'].'users', $values, array('userID' => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER))))
				{
					if($userID==$_SESSION['ccms_userID'])
					{
						$_SESSION['ccms_userLevel'] = $userLevel;
					}

					header('Location: ' . makeAbsoluteURI('user-management.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
					exit();
				}
				else
				{
					throw new FbX($db->MyDyingMessage());
				}
			}
			else
			{
				throw new FbX($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
			}
		}
		else
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Delete a user as posted by an authorized user
 *
 */
if($do_action == 'delete-user' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	FbX::SetFeedbackLocation('user-management.Manage.php');
	try
	{
		// Only if current user has the rights
		if($perm->is_level_okay('manageUsers', $_SESSION['ccms_userLevel']))
		{
			$total = (isset($_POST['userID']) ? count($_POST['userID']) : 0);

			if($total==0)
			{
				throw new FbX($ccms['lang']['system']['error_selection']);
			}

			// Delete details from the database
			$i=0;
			foreach ($_POST['userID'] as $user_num)
			{
				$user_num = filterParam4Number($user_num);

				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values['userID'] = MySQL::SQLValue($user_num, MySQL::SQLVALUE_NUMBER);
				$result = $db->DeleteRows($cfg['db_prefix'].'users', $values);
				$i++;
			}
			// Check for errors
			if($result && $i == $total)
			{
				header('Location: ' . makeAbsoluteURI('user-management.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'])));
				exit();
			}
			else
			{
				throw new FbX($db->MyDyingMessage());
			}
		}
		else
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}







// when we get here, an illegal command was fed to us!
die_with_forged_failure_msg(__FILE__, __LINE__, "do_action=$do_action, checkAuth=".(1 * checkAuth()));

?>