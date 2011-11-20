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
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !checkAuth())
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die_with_forged_failure_msg(__FILE__, __LINE__); // $ccms['lang']['auth']['featnotallowed']
}



// Prevent PHP warning by setting default (null) values
$do_action = getGETparam4IdOrNumber('action');





/**
 *
 * Save the edited template and check for authority
 *
 */
if($do_action == 'save-template' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	FbX::SetFeedbackLocation('template-editor.Manage.php');
	try
	{
		// Only if current user has the rights
		if($perm->is_level_okay('manageTemplate', $_SESSION['ccms_userLevel']))
		{
			$filenoext  = getGETparam4FullFilePath('template');
			$filename   = BASE_PATH . '/lib/templates/' . $filenoext;

			$content    = getPOSTparam4RAWCONTENT('content'); // RAW CONTENT: the template may contain ANYTHING.

			if (is_writable_ex($filename))
			{
				if (!$handle = fopen($filename, 'w'))  throw new FbX($ccms['lang']['system']['error_openfile'].' ('.$filename.').');
				if (fwrite($handle, $content) === FALSE)
				{
					fclose($handle);
					throw new FbX($ccms['lang']['system']['error_write'].' ('.$filename.').');
				}
				// Do on success
				fclose($handle);
				header('Location: ' . makeAbsoluteURI('template-editor.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved']).'&template='.$filenoext));
				exit();
			}
			else
			{
				throw new FbX($ccms['lang']['system']['error_chmod']);
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