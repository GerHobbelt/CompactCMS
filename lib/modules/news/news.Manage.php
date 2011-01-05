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

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/admin/includes/security.inc.php'); // when session expires or is overridden, the login page won't show if we don't include this one, but a cryptic error will be printed.


if (!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2'])) 
{
	die("No external access to file");
}



$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');
$pageID	= getGETparam4Filename('file');

if (empty($pageID))
{
	die($ccms['lang']['system']['error_forged']);
}


// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");





?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>News module</title>
	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/ie.css" />
	<![endif]-->
</head>
<body>
	<div class="module" id="news-manager">
		<div class="center-text <?php echo $status; ?>">
			<?php 
			if(!empty($status_message)) 
			{ 
				echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>'; 
			} 
			?>
		</div>
		
		<div class="span-18 colborder">
			<h2><?php echo $ccms['lang']['news']['manage']; ?></h2>
			<?php
			// Load recordset
			$i=0;
			$newsitems = $db->QueryObjects("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE pageID=".MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT));
			if ($newsitems === false)
				$db->Kill();

			// Start switch for news, select all the right details
			if(count($newsitems) > 0) 
			{ 
				$preview_checkcode = GenerateNewPreviewCode(null, $pageID);
				
			?>
				<form action="news.Process.php?action=del-news" method="post" accept-charset="utf-8">
				<div class="table_inside">
					<table cellspacing="0" cellpadding="0">
						<tr>
							<th class="span-1">&#160;</th>
							<th class="span-1">&#160;</th>
							<th class="span-14"><?php echo $ccms['lang']['news']['title']; ?></th>
							<th class="span-5"><?php echo $ccms['lang']['news']['author']; ?></th>
							<th class="span-4 last"><?php echo $ccms['lang']['news']['date']; ?></th>
						</tr>
						<?php
						
						foreach($newsitems as $rsNews)
						{
							// Alternate rows
							if($i%2 != 1) 
							{
								echo '<tr class="altrgb"><td>';
							} 
							else 
							{ 
								echo '<tr><td>';
							} 
					
								if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
								{ 
								?>
									<label>
										<input type="checkbox" name="newsID[]" value="<?php echo rm0lead($rsNews->newsID); ?>">
									</label>
								<?php 
								} 
								?>
								</td>
								<td>
								<?php
								
								echo "<span class='ss_sprite_16 ".($rsNews->newsPublished != 0 ? "ss_bullet_green'>" : "ss_bullet_red'>") . "&#160;</span>"; 
								
								// Filter spaces, non-file characters and account for UTF-8
								$newsTitle = cvt_text2legibleURL($rsNews->newsTitle);
								
								echo '<a href="' . $cfg['rootdir'].$rsNews->pageID.'/'.rm0lead($rsNews->newsID).'-'.$newsTitle . '.html?preview=' . $preview_checkcode . '" ' .
											'title="' . $ccms['lang']['backend']['previewpage'] . '"><span class="ss_sprite_16 ss_eye">&#160;</span></a>'; 
								?>
								</td>
								<td>
								<?php 
								if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
								{ 
								?>
									<a href="news.Write.php?pageID=<?php echo $pageID; ?>&amp;newsID=<?php echo rm0lead($rsNews->newsID); ?>"><span class="ss_sprite_16 ss_pencil">&#160;</span><?php echo substr($rsNews->newsTitle,0,20); echo (strlen($rsNews->newsTitle)>20 ? '...' : null); ?></a>
								<?php 
								} 
								else 
								{ 
								?>
									<?php echo $rsNews->newsTitle; ?>
								<?php 
								} 
								?>                                       
								</td>
								<td class="nowrap"><a href="mailto:<?php echo $rsNews->userEmail; ?>"><span class="ss_sprite_16 ss_email">&#160;</span><?php echo substr(ucfirst($rsNews->userFirst),0,1).'. '.ucfirst($rsNews->userLast); ?></a></td>
								<td class="nowrap"><span class="ss_sprite_16 ss_calendar">&#160;</span><?php echo date('Y-m-d G:i', strtotime($rsNews->newsModified)); ?></td>
							</tr>
							<?php 
							$i++; 
						}
						?>
					</table>
				</div>
					<?php 
					if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
					{ 
					?>
					<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID">
					<div class="right">
						<button type="submit" onclick="return confirmation_delete();" name="deleteNews"><span class="ss_sprite_16 ss_newspaper_delete">&#160;</span><?php echo $ccms['lang']['backend']['delete']; ?></button>
					</div>
					<?php 
					} 
					?>
				</form>
				<?php
			} 
			else 
			{
				echo $ccms['lang']['system']['noresults'];  // [i_a] moved OUTSIDE the <form><table> : correct HTML
			}
			?>
		</div>
		<div class="span-6 last">
			<h2><?php echo $ccms['lang']['news']['addnews']; ?></h2>
			<?php 
			if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
			{ 
			?>
				<p class="ss_has_sprite"><a href="news.Write.php?pageID=<?php echo $pageID; ?>"><span class="ss_sprite_16 ss_newspaper_add">&#160;</span><?php echo $ccms['lang']['news']['addnewslink']; ?></a></p>
			
				<h2><?php echo $ccms['lang']['news']['settings']; ?></h2>
				<?php 
				$rsCfg = $db->SelectSingleRow($cfg['db_prefix'].'cfgnews', array('pageID' => MySQL::SQLValue($pageID,MySQL::SQLVALUE_TEXT)));
				if ($db->ErrorNumber() != 0) $db->Kill();
					
				if ($rsCfg !== false)
				{
					$showmsg = max(1,intval($rsCfg->showMessage)); // always show at least 1 news item on a news page!
					$locale = $rsCfg->showLocale;
					$showauth = intval($rsCfg->showAuthor);
					$showdate = intval($rsCfg->showDate);
					$showteaser = intval($rsCfg->showTeaser);
					//$newscfgid = $rsCfg->cfgID;
				}
				else // set defaults 
				{
					// [i_a] when no cfg record, fill in the defaults as were also set in the database
					$showmsg = 3;
					$locale = $cfg['locale'];
					$showauth = 1;
					$showdate = 1;
					$showteaser = 0;
					//$newscfgid = null;
				}
				?>
				<form action="news.Process.php?action=cfg-news" method="post" accept-charset="utf-8">
					<label for="messages"><?php echo $ccms['lang']['news']['numbermess']; ?></label>
					<input type="text" class="text span-25 last" name="messages" value="<?php echo $showmsg; ?>" id="messages" />
					
					<label for="locale"><?php echo $ccms['lang']['forms']['setlocale']; ?></label>
					<select name="locale" class="title span-25 last" id="locale" size="1">
						<?php 
						// Get current languages
						$s = (isset($_SESSION['variables']['language']) ? $_SESSION['variables']['language'] : 'en');
						$lcoll = GetAvailableLanguages();
						foreach($lcoll as $lcode => $ldesc)
						{
							$c = ($lcode == $s ? 'selected="selected"' : null);
							echo '<option value="'.$ldesc['locale'].'" '.$c.'>'.$ldesc['name'].'</option>';
						}
						?>   	
					</select>
					
					<label><?php echo $ccms['lang']['news']['showauthor']; ?></label>
					<div id="show-author" class="span-25">
						<label><?php echo $ccms['lang']['backend']['yes']; ?>
							<input type="radio" name="author" <?php echo ($showauth!=0?'checked="checked"':null); ?> value="1" id="author1" />
						</label>
						<label><?php echo $ccms['lang']['backend']['no']; ?>
							<input type="radio" name="author" <?php echo ($showauth==0?'checked="checked"':null); ?> value="0" id="author0" />
						</label>
					</div>
					<label><?php echo $ccms['lang']['news']['showdate']; ?></label>
					<div id="show-date" class="span-25">
						<label><?php echo $ccms['lang']['backend']['yes']; ?>
							<input type="radio" name="show_modified" <?php echo ($showdate!=0?'checked="checked"':null); ?> value="1" id="show_modified1" />
						</label>
						<label><?php echo $ccms['lang']['backend']['no']; ?>
							<input type="radio" name="show_modified" <?php echo ($showdate==0?'checked="checked"':null); ?> value="0" id="show_modified0" />
						</label>
					</div>
					<label><?php echo $ccms['lang']['news']['showteaser']; ?></label>
					<div id="show-teaser" class="span-25">
						<label><?php echo $ccms['lang']['backend']['yes']; ?>
							<input type="radio" name="show_teaser" <?php echo ($showteaser!=0?'checked="checked"':null); ?> value="1" id="show_teaser1" />
						</label>
						<label><?php echo $ccms['lang']['backend']['no']; ?>
							<input type="radio" name="show_teaser" <?php echo ($showteaser==0?'checked="checked"':null); ?> value="0" id="show_teaser0" />
						</label>
					</div>
					<?php 
					if ($rsCfg !== false)
					{
						echo '<input type="hidden" name="cfgID" value="' . rm0lead($rsCfg->cfgID) . '" id="cfgID" />';
					}
					?>
					<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID" />
					<div class="right">
						<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['savebutton']; ?></button>
						<a class="button" href="../../../admin/index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
					</div>
				</form>
			<?php 
			} 
			else 
			{
				echo $ccms['lang']['auth']['featnotallowed']; 
			}
			?>
		</div>
	</div>
	<script type="text/javascript" src="../../includes/js/the_goto_guy.js" charset="utf-8"></script>
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
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", '<?php echo $pageID; ?>_ccms');
	}
	return false;
}
	</script>
</body>
</html>
