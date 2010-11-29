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

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Comments module</title>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/ie.css" />
		<![endif]-->
	</head>
<body>
	<div class="module" id="comment-manager">
		<div class="center-text <?php echo $status; ?>">
			<?php 
			if(!empty($status_message)) 
			{ 
				echo '<p><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>'; 
			} 
			?>
		</div>
			
		<div class="span-18 colborder">
		<h2><?php echo $ccms['lang']['guestbook']['manage']; ?></h2>
		<?php 
		// Load recordset; most recent comments on top; the extra order by commentID bit is there to ensure the sort/order is repeatable.
		$i = 0;
		if (!$db->SelectRows($cfg['db_prefix'].'modcomment', array('pageID' => MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT)), null, array('-commentTimestamp', '-commentID')))
			$db->Kill();
	
		// Start switch for news, select all the right details
		if($db->HasRecords()) 
		{
		?>
			<form action="comment.Process.php?action=del-comment" method="post" accept-charset="utf-8">
			<div class="table_inside">
				<table cellspacing="0" cellpadding="0" cols="7">
					<tr class="head0">
						<th class="span-1 rowspan2" rowspan="2">&#160;</th>
						
						<th class="span-2"><?php echo $ccms['lang']['guestbook']['rating']; ?></th>
						<th class="span-6"><?php echo $ccms['lang']['guestbook']['author']; ?></th>
						<th class="span-6"><?php echo $ccms['lang']['guestbook']['website']; ?></th>
						<th class="span-2"><?php echo $ccms['lang']['guestbook']['date']; ?></th>
						<th class="span-2"><?php echo $ccms['lang']['guestbook']['host']; ?></th>
						<th class="span-6 last"><?php echo $ccms['lang']['guestbook']['sendmail'];?></th>
					</tr>
					<tr class="head1">
						<th colspan="6"><?php echo $ccms['lang']['guestbook']['reaction'];?></th>
					</tr>
					<?php
					while (!$db->EndOfSeek()) 
					{
						$rsComment = $db->Row(); 
						
						// Alternate rows
						if($i%2 != 1) 
						{
							echo '<tr class="altrgb row0"><td rowspan="2" class="rowspan2 hover">';
						} 
						else 
						{ 
							echo '<tr class="row0"><td rowspan="2" class="rowspan2 hover">';
						} 
						
							if($perm['manageModNews']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModNews']) 
							{ 
							?>
								<label>
									<input type="checkbox" name="commentID[]" value="<?php echo rm0lead($rsComment->commentID); ?>">
								</label>
							<?php 
							} 
							?>
							</td>
							<td class="rating-col">
								<img src="./resources/<?php echo $rsComment->commentRate;?>-star.gif" alt="<?php echo $ccms['lang']['guestbook']['rating']." ".$rsComment->commentRate;?>"/>
							</td>
							<td class="name-col">
							<?php 
							if ($cfg['enable_gravatar']) 
							{ 
							?>
								<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo md5($rsComment->commentEmail); ?>&amp;size=80&amp;rating=G" style="margin:4px;border:2px solid #000;" alt="<?php echo $ccms['lang']['guestbook']['avatar'];?>"/>
								<br/>
							<?php
							}
							?>
							<strong><?php echo (!empty($rsComment->commentUrl) ? '<a href="'.$rsComment->commentUrl.'" target="_blank">'.$rsComment->commentName.'</a>' : $rsComment->commentName); ?></strong>
							</td>
							<td class="url-col nowrap">
								<?php 
								if (!empty($rsComment->commentUrl))
								{
									echo '<a href="'.$rsComment->commentUrl.'" target="_blank">'.$rsComment->commentUrl.'</a>';
								}
								?>
							</td>
							<td class="date-col nowrap">
								<span class="ss_sprite ss_time quiet small"><?php echo date('Y-m-d G:i:s',strtotime($rsComment->commentTimestamp)); ?></span>
							</td>
							<td class="host-col nowrap">
								<span class="ss_sprite ss_world quiet small"><?php echo $rsComment->commentHost; ?></span>
							</td>
							<td class="email-col nowrap">
								<span class="ss_sprite ss_email small"><a href="mailto:<?php echo $rsComment->commentEmail; ?>"><?php echo $rsComment->commentEmail; ?></a></span>
							</td>
						</tr>
						<?php
						
						if($i%2 != 1) 
						{
							echo '<tr class="altrgb row1">';
						} 
						else 
						{ 
							echo '<tr class="row1">';
						} 
						?>
							<td colspan="6" class="comment-col">
								<p><?php echo nl2br(strip_tags($rsComment->commentContent));?></p>
							</td>
						</tr>
						<?php 
						$i++; 
					}
					?>
				</table>
				<?php 
				if($perm['manageModComment']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModComment']) 
				{ 
				?>
					<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID">
					<div class="right">
						<button type="submit" onclick="return confirmation_delete();" name="deleteComments"><span class="ss_sprite_16 ss_newspaper_delete">&#160;</span><?php echo $ccms['lang']['backend']['delete']; ?></button>
					</div>
				<?php 
				} 
				?>
			</div>
			</form>
		<?php
		} 
		else 
		{
			echo $ccms['lang']['guestbook']['noposts']; 
		}
		?>
		</div>
	
		<div class="span-6 last">
			<h2>Configuration</h2>
			<?php 
			if($perm['manageModComment']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModComment']) 
			{ 
				$rsCfg = $db->SelectSingleRow($cfg['db_prefix'].'cfgcomment', array('pageID' => MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT)));
				if ($db->HasRecords())
				{
					$showmsg = max(1,intval($rsCfg->showMessage)); // always show at least 1 news item on a comment page!
					$locale = $rsCfg->showLocale;
					//$newscfgid = $rsCfg->cfgID;
				}
				else // set defaults 
				{
					// [i_a] when no cfg record, fill in the defaults as were also set in the database
					$showmsg = 3;
					$locale = $cfg['locale'];
					//$newscfgid = null;
				}
				?>
				<form action="comment.Process.php?action=save-cfg" method="post" accept-charset="utf-8">
					
					<label for="messages"><?php echo $ccms['lang']['news']['numbermess']; ?></label>
					<input type="input" class="text span-25 last" name="messages" value="<?php echo $showmsg; ?>" id="messages" />
					
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
					
					<?php echo ($db->HasRecords()?'<input type="hidden" name="cfgID" value="'.rm0lead($rsCfg->cfgID).'" id="cfgID" />':null); ?>
					<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID" />
					<div class="right">
						<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['savebutton']; ?></button>
						<a class="button" href="../../../admin/index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
					</div>
				</form>
			<?php 
			} 
			else 
				echo $ccms['lang']['auth']['featnotallowed']; 
			?>
		</div>
	</div>
<script type="text/javascript" src="../../includes/js/the_goto_guy.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
function confirmation_delete()
{
	var answer=confirm('<?php echo $ccms['lang']['backend']['confirmdelete']; ?>');
	return !!answer;
}

function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-perm_ccms');
	}
	return false;
}
</script>
</body>
</html>