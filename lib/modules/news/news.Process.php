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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


class FbX extends CcmsAjaxFbException {}; // nasty way to do 'shorthand in PHP -- I do miss my #define macros! :'-|



// Set default variables
$newsID     = getPOSTparam4Number('newsID');
$page_id    = getPOSTparam4IdOrNumber('page_id');
$cfgID      = getPOSTparam4Number('cfgID');
$do_action  = getGETparam4IdOrNumber('action');



/**
 *
 * Either INSERT or UPDATE news article
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && $do_action == 'add-edit-news' && checkAuth())
{
	FbX::SetFeedbackLocation('news.Manage.php');
	try
	{
		if (!empty($page_id))
		{
			FbX::SetFeedbackLocation('news.Manage.php', 'page_id=' . $page_id);

			// Only if current user has the rights
			if($perm->is_level_okay('manageModNews', $_SESSION['ccms_userLevel']))
			{
				// Published
				$newsAuthor = getPOSTparam4Number('newsAuthor');
				$newsTitle = getPOSTparam4DisplayHTML('newsTitle');
				$newsTeaser = getPOSTparam4DisplayHTML('newsTeaser');
				$newsContent = getPOSTparam4DisplayHTML('newsContent');
				$newsModified = getPOSTparam4DateTime('newsModified', time());
				$newsPublished = getPOSTparam4boolean('newsPublished');

				/* make sure empty news posts don't get through! front-end checking alone is NOT enough! */
				if (!empty($page_id) && !empty($newsAuthor) && !empty($newsAuthor) && !empty($newsTitle) && strlen($newsTitle) >= 3 && !empty($newsTeaser) && !empty($newsContent))
				{
					// Set all the submitted variables
					$values = array(); // [i_a] make sure $values is an empty array to start with here
					$values["userID"] = MySQL::SQLValue($newsAuthor, MySQL::SQLVALUE_NUMBER);
					$values["page_id"] = MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER);
					$values["newsTitle"] = MySQL::SQLValue($newsTitle, MySQL::SQLVALUE_TEXT);
					$values["newsTeaser"] = MySQL::SQLValue($newsTeaser, MySQL::SQLVALUE_TEXT);
					$values["newsContent"] = MySQL::SQLValue($newsContent, MySQL::SQLVALUE_TEXT);
					$values["newsModified"] = MySQL::SQLValue($newsModified, MySQL::SQLVALUE_DATETIME);
					$values["newsPublished"] = MySQL::SQLValue($newsPublished, MySQL::SQLVALUE_BOOLEAN);

					// Execute either INSERT or UPDATE based on $newsID
					if($db->AutoInsertUpdate($cfg['db_prefix'].'modnews', $values, array('newsID' => MySQL::BuildSQLValue($newsID))))
					{
						header('Location: ' . makeAbsoluteURI('news.Manage.php?page_id='.$page_id.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['itemcreated'])));
						exit();
					}
					else
					{
						throw new FbX($db->MyDyingMessage());
					}
				}
				else
				{
					throw new FbX($ccms['lang']['system']['error_pagetitle']);
				}
			}
			else
			{
				throw new FbX($ccms['lang']['auth']['featnotallowed']);
			}
		}
		else
		{
			throw new FbX($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Delete current news item
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && $do_action == 'del-news' && checkAuth())
{
	FbX::SetFeedbackLocation($cfg['rootdir'] . 'lib/modules/news/news.Manage.php');

	try
	{
		if ($page_id)
		{
			FbX::SetFeedbackLocation('news.Manage.php', 'page_id=' . $page_id);

			// Only if current user has the rights
			if($perm->is_level_okay('manageModNews', $_SESSION['ccms_userLevel']))
			{
				// Number of selected items
				$total = (!empty($_POST['newsID']) && is_array($_POST['newsID']) ? count($_POST['newsID']) : 0);

				// If nothing selected, throw error
				if($total==0)
				{
					throw new FbX($ccms['lang']['system']['error_selection']);
				}

				// Delete details from the database
				$i=0;
				foreach ($_POST['newsID'] as $idnum)
				{
					$idnum = filterParam4Number($idnum);

					$values = array(); // [i_a] make sure $values is an empty array to start with here
					$values['newsID'] = MySQL::SQLValue($idnum, MySQL::SQLVALUE_NUMBER);
					$result = $db->DeleteRows($cfg['db_prefix'].'modnews', $values);
					if (!$result) break;
					$i++;
				}

				// Check for errors
				if($result && $i==$total)
				{
					header('Location: ' . makeAbsoluteURI('news.Manage.php?page_id='.$page_id.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'])));
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
		else
		{
			throw new FbX($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Save configuration preferences
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && $do_action == 'cfg-news' && checkAuth())
{
	FbX::SetFeedbackLocation('news.Manage.php');
	try
	{
		if ($page_id)
		{
			FbX::SetFeedbackLocation('news.Manage.php', 'page_id=' . $page_id);

			// Only if current user has the rights
			if($perm->is_level_okay('manageModNews', $_SESSION['ccms_userLevel']))
			{
				$showLocale = getPOSTparam4IdOrNumber('locale');
				$showMessage = getPOSTparam4Number('messages');
				$showAuthor = getPOSTparam4boolean('author');
				$showDate = getPOSTparam4boolean('show_modified');
				$showTeaser = getPOSTparam4boolean('show_teaser');

				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values["page_id"]      = MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER);
				$values["showLocale"]   = MySQL::SQLValue($showLocale, MySQL::SQLVALUE_TEXT);
				$values["showMessage"]  = MySQL::SQLValue($showMessage, MySQL::SQLVALUE_NUMBER);
				$values["showAuthor"]   = MySQL::SQLValue($showAuthor, MySQL::SQLVALUE_BOOLEAN);
				$values["showDate"]     = MySQL::SQLValue($showDate, MySQL::SQLVALUE_BOOLEAN);
				$values["showTeaser"]   = MySQL::SQLValue($showTeaser, MySQL::SQLVALUE_BOOLEAN);

				// Execute the insert or update for current page
				if($db->AutoInsertUpdate($cfg['db_prefix'].'cfgnews', $values, array('cfgID' => MySQL::BuildSQLValue($cfgID))))
				{
					header('Location: ' . makeAbsoluteURI('news.Manage.php?page_id='.$page_id.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
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
		else
		{
			throw new FbX($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

// when we get here, an illegal command was fed to us!
die_with_forged_failure_msg(__FILE__, __LINE__);

?>
