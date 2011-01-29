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


// Security functions




// Set default variables
$page_id    = getPOSTparam4Filename('page_id');
$cfgID		= getPOSTparam4Number('cfgID');
$do_action 	= getGETparam4IdOrNumber('action');


/**
 *
 * Show comments
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'GET' && $do_action=='show-comments' && !empty($_SESSION['ccms_captcha']) /* && checkAuth() */ ) // there's not necessarily an *authenticated* SESSION going on here... 
{
	// Pagination variables
	$page_id = getGETparam4IdOrNumber('page_id');
	$rs = $db->SelectSingleRow($cfg['db_prefix'].'cfgcomment', array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER)), array('showMessage', 'showLocale'));
	if (!$rs)
		$db->Kill();
	$rsCfg	= $rs->showMessage;
	$rsLoc	= $rs->showLocale;
	$max 	= ($rsCfg > 0 ? $rsCfg : 10);
	/*
	 * The next query is a nice example about the MySQL class 'enhanced' use: by 
	 * passing a prefab string instead of an array of fields, it is copied 
	 * literally into the query, without quoting it.
	 * 
	 * This produces the following query as a result:
	 * 
	 *   SELECT COUNT(commentID) FROM ccms_modcomment WHERE pageID = 'xyz'
	 *   
	 * NOTE that this type of usage assumes the 'raw string' has been correctly
	 * processed by the caller, i.e. all SQL injection attack prevention precautions
	 * have been taken. (Well, /hardcoding/ it like this is the safest possible
	 * thing right there, so no worries, mate! ;-) )
	 */
	$total = $db->SelectSingleValue($cfg['db_prefix'].'modcomment', array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER)), 'COUNT(commentID)');
	if ($db->ErrorNumber()) 
		$db->Kill();
	$limit = getGETparam4Number('offset') * $max;
	// feature: if a comment 'bookmark' was specified, jump to the matching 'page'...
	$commentID = getGETparam4Number('commentID');
	if ($commentID > 0)
	{
		$limit = $commentID - 1;
		$limit -= $limit % $max;
	}
	if ($limit >= $total)
		$limit = $total - 1;
	if ($limit < 0)
		$limit = 0;
	$offset = intval($limit / $max);
	$limit4sql = ($offset * $max) . ',' . $max;
	
	// Set front-end language
	SetUpLanguageAndLocale($rsLoc);

	// Load recordset
	$commentlist = $db->SelectObjects($cfg['db_prefix'].'modcomment', array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER)), null, array('-commentTimestamp', '-commentID'), $limit4sql);
	if ($commentlist === false)
		$db->Kill();
	//echo "<pre>" . $db->GetLastSQL() . " -- $limit, $page_id, ".getGETparam4Number('offset')."\n";
	//var_dump($_GET);
	//echo "</pre>";
	
	// Start switch for comments, select all the right details
	if(count($commentlist) > 0) 
	{
		$index = $limit;
		foreach($commentlist as $rsComment)
		{
			$index++; // start numbering at 1 (+ N*pages)
			
			?>
			<div id="s-display"><a name="<?php echo "cmt" . $index; ?>"></a>
				<?php 
				if ($cfg['enable_gravatar']) 
				{ 
				?>
					<div id="s-avatar">
						<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($rsComment->commentEmail);?>&amp;size=80&amp;rating=G" alt="<?php echo $ccms['lang']['guestbook']['avatar'];?>" /><br/>
					</div>
				<?php
				} 
				?>
				<div id="s-name">
					<?php echo (!empty($rsComment->commentUrl)?'<a href="'.$rsComment->commentUrl.'" rel="nofollow" target="_blank">'.$rsComment->commentName.'</a>':$rsComment->commentName).' '.$ccms['lang']['guestbook']['wrote']; ?>:
				</div>
				<div id="s-comment"><p><?php echo nl2br(strip_tags($rsComment->commentContent)); ?></p></div>
				<div id="s-rating">
					<img src="<?php echo $cfg['rootdir']; ?>lib/modules/comment/resources/<?php echo $rsComment->commentRate;?>-star.gif" alt="<?php echo $ccms['lang']['guestbook']['rating']." ".$rsComment->commentRate; ?>" />
					<p>
						<?php echo htmlentities(strftime('%A %d %B %Y, %H:%M',strtotime($rsComment->commentTimestamp)));?>
					</p>
				</div>
			</div>
		<?php 
		} 
		?>
		
		<div class="pagination">
			<?php 
			$maxblocks = intval(($total + $max - 1) / $max);
			$current = $offset;
			for ($i = 0; $i < $maxblocks; $i++) 
			{ 
				$linktext = $i+1;
				if ($current == $i) 
				{
					echo '<span class="current">'.$linktext.'</span>';
				} 
				else 
				{
					echo '<a href="?offset='.$i.'&action='.$do_action.'&page_id='.$page_id.'&">'.$linktext.'</a>';
				}
			} 
			?>
		</div>
		<!--<p>&#160;</p>-->
	<?php 
	} 
	else
	{	
		echo $ccms['lang']['guestbook']['noposts'];
	}
	
	exit();
}

/**
 *
 * Delete comments (one or more)
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && $do_action == 'del-comments' && checkAuth()) 
{
	$page_id = getPOSTparam4IdOrNumber('page_id');
	
	FbX::SetFeedbackLocation('comment.Manage.php');
	try
	{
		if (!empty($page_id))
		{
			FbX::SetFeedbackLocation('comment.Manage.php', 'page_id=' . $page_id);
			
			// Only if current user has the rights
			if($perm->is_level_okay('manageModComment', $_SESSION['ccms_userLevel'])) 
			{
				// Number of selected items
				$total = (!empty($_POST['commentID']) && is_array($_POST['commentID']) ? count($_POST['commentID']) : 0);
				
				// If nothing selected, throw error
				if($total==0) 
				{
					throw new FbX($ccms['lang']['system']['error_selection']);
				}
				
				// Delete details from the database
				$i=0;
				foreach ($_POST['commentID'] as $idnum) 
				{
					$idnum = filterParam4Number($idnum);
					
					$values = array(); // [i_a] make sure $values is an empty array to start with here
					$values['commentID'] = MySQL::SQLValue($idnum, MySQL::SQLVALUE_NUMBER);
					/* only do this when a good pageID value was specified! */
					$values["page_id"] = MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER);
					
					$result = $db->DeleteRows($cfg['db_prefix'].'modcomment', $values);
					if (!$result) break;
					$i++;
				}
			
				// Check for errors
				if($result && $i==$total) 
				{
					header('Location: ' . makeAbsoluteURI('comment.Manage.php?status=notice&page_id='.$page_id.'&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'])));
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
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
	
	exit();
}

/**
 *
 * Add comment
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && $do_action == 'add-comment' && $_POST['verification'] == $_SESSION['ccms_captcha'] && !empty($_SESSION['ccms_captcha']) /* && checkAuth() */ ) // there's not necessarily an *authenticated* SESSION going on here...  
{
	$error = '';
	
	$commentName = getPOSTparam4DisplayHTML('name');
	$commentEmail = getPOSTparam4Email('email');
	$commentUrl = getPOSTparam4URL('website');
	$commentRating = getPOSTparam4Number('rating', 3);
	$commentContent = getPOSTparam4DisplayHTML('comment'); // no need for strip_tags here: 4DisplayHTML already encodes anything that might be dangerous in HTML entities so they show but don't hurt
	$commentHost = $_SERVER['REMOTE_ADDR'];

	if (!empty($commentName) && !empty($commentEmail) && !empty($commentRating) && !empty($commentContent) && !empty($commentHost))
	{
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values['page_id']      = MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER);
		$values['commentName']	= MySQL::SQLValue($commentName, MySQL::SQLVALUE_TEXT);
		$values['commentEmail']	= MySQL::SQLValue($commentEmail, MySQL::SQLVALUE_TEXT);
		$values['commentUrl']	= MySQL::SQLValue($commentUrl, MySQL::SQLVALUE_TEXT);
		$values['commentRate']	= MySQL::SQLValue($commentRating, MySQL::SQLVALUE_ENUMERATE); // 'note the 'tricky' comment in the MySQL::SQLValue() member: we MUST have quotes around this number as mySQL enums are quoted :-(
		$values['commentContent'] = MySQL::SQLValue($commentContent, MySQL::SQLVALUE_TEXT);
		$values['commentHost']	= MySQL::SQLValue($commentHost, MySQL::SQLVALUE_TEXT);
		
		// Insert new page into database
		if (!$db->InsertRow($cfg['db_prefix'].'modcomment', $values))
		{
			$error = $db->Error();
		}
		else
		{
			echo '<h2>' . $ccms['lang']['guestbook']['success'] . '</h2>';
			echo '<div id="sent-comment-ok">' . $ccms['lang']['guestbook']['posted'] . '</div>';
			exit();
		}
	}
	else
	{
		$error = $ccms['lang']['guestbook']['rejected'];
	}
	
	// assert(!empty($error));
	echo '<h2>' . $ccms['lang']['guestbook']['error'] . '</h2>';
	echo '<div id="sent-comment-fail">' . $error . '</div>';

	exit();
}

/**
 *
 * Save configuration
 *
 */
if($_SERVER['REQUEST_METHOD'] == 'POST' && $do_action == 'save-cfg' && checkAuth()) 
{
	$page_id = getPOSTparam4IdOrNumber('page_id');
	
	FbX::SetFeedbackLocation('comment.Manage.php');
	try
	{
		if (!empty($page_id))
		{
			FbX::SetFeedbackLocation('comment.Manage.php', 'page_id=' . $page_id);
			
			// Only if current user has the rights
			if($perm->is_level_okay('manageModComment', $_SESSION['ccms_userLevel'])) 
			{
				$showMessage = getPOSTparam4Number('messages');
				$showLocale = getPOSTparam4IdOrNumber('locale');

				if (!empty($showMessage) && !empty($showLocale))
				{
					$values = array(); // [i_a] make sure $values is an empty array to start with here
					$values['page_id'] = MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER);
					$values['showMessage'] = MySQL::SQLValue($showMessage, MySQL::SQLVALUE_NUMBER);
					$values['showLocale'] = MySQL::SQLValue($showLocale, MySQL::SQLVALUE_TEXT);

					// Insert or update configuration
					if($db->AutoInsertUpdate($cfg['db_prefix'].'cfgcomment', $values, array('cfgID' => MySQL::BuildSQLValue($cfgID)))) 
					{
						header('Location: ' . makeAbsoluteURI('comment.Manage.php?page_id='.$page_id.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
						exit();
					} 
					else 
					{
						throw new FbX($db->MyDyingMessage());
					}
				}
				else
				{
					throw new FbX($ccms['lang']['system']['error_forged']);
				}
			} 
			else 
			{
				throw new FbX($ccms['lang']['auth']['featnotallowed']);
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
die($ccms['lang']['system']['error_forged']);

?>