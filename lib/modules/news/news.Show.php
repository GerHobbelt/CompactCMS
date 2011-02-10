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
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/



// Load news preferences
//$pageID   = getGETparam4Filename('page');
$page_id = $ccms['page_id'];
$page_name = $ccms['page_name'];
$do = getGETparam4IdOrNumber('do');
$id = getGETparam4IdOrNumber('id');
$is_printing = ($ccms['printing'] == 'Y');

if(!empty($page_id))
{
	$rsCfg = $db->SelectSingleRow($cfg['db_prefix'].'cfgnews', array('page_id' => MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER)));
	if ($db->ErrorNumber() != 0) $db->Kill();
}
$locale     = ($rsCfg ? $rsCfg->showLocale : $cfg['locale']);

// no need to check whether the given page is a news page; if it isn't we wouldn't have arrived here...

// Set front-end language
SetUpLanguageAndLocale($locale);


// Do actions for overview
$newsrows = false;
if(empty($id))
{
	$newsID = false;

	// Load recordset for all news on specific news page
	$newsrows = $db->QueryObjects("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsPublished<>'0' AND page_id=" . MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER) . " ORDER BY newsModified DESC");
}
else
{
	// Do actions for specific news

	// Define requested news item
	$newsID = explode("-", $id, 2);

	// Load recordset for newsID
	$newsrows = $db->QueryObjects("SELECT * FROM `".$cfg['db_prefix']."modnews` n LEFT JOIN `".$cfg['db_prefix']."users` u ON n.userID=u.userID WHERE newsID=".MySQL::SQLValue($newsID[0], MySQL::SQLVALUE_NUMBER)." AND newsPublished<>'0' AND page_id=".MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER));
}
if ($newsrows === false)
{
	$db->Kill();
}


// generate the preview code when applicable:
$preview_checkcode = ($ccms['preview'] == 'Y' ? GenerateNewPreviewCode($page_id, null) : false);
$ccms['previewcode'] = $preview_checkcode;


?>
<!-- additional style and code -->
<link rel="stylesheet" href="<?php echo $cfg['rootdir'];?>lib/modules/news/resources/style.css" type="text/css" media="screen" title="lightbox" charset="utf-8" />

<!-- lay-out -->

<?php
// Start switch for news, select all the right details
if(count($newsrows) > 0)
{
	if(empty($do))
	{
		$newsCount = count($newsrows);
		if($rsCfg)
		{
			$listMax    = ($rsCfg->showMessage > $newsCount ? $newsCount : $rsCfg->showMessage);
			$showTeaser = intval($rsCfg->showTeaser);
			$showAuthor = intval($rsCfg->showAuthor);
			$showDate   = intval($rsCfg->showDate);
		}
		else
		{
			$listMax = $newsCount;
			$showTeaser = 1;
			$showAuthor = 1;
			$showDate   = 1;
		}
		$show_single_item = !empty($id); // ($listMax == 1);

		for ($i=0; $i < $listMax; $i++)
		{
			$rsNews = $newsrows[$i];

			// Filter spaces, non-file characters and account for UTF-8
			$newsTitle = cvt_text2legibleURL($rsNews->newsTitle);

			if ($show_single_item)
			{
				if ($i == 0)
				{
					// and augment the breadcrumb trail and other template variables:
					$preview_qry = ($ccms['preview'] == 'Y' ? '?preview=' . $preview_checkcode : '');
					$ccms['breadcrumb'][] = '<a href="'.$cfg['rootdir'].$page_name.'/'.rm0lead($rsNews->newsID).'-'.$newsTitle.'.html'.$preview_qry.'" title="'.$rsNews->newsTitle.'">'.$rsNews->newsTitle.'</a>';

					$ccms['urlpage']   = $page_name . '/' . rm0lead($rsNews->newsID).'-'.$newsTitle;
					$ccms['pagetitle'] = $rsNews->newsTitle;
					//$ccms['subheader']  = $row->subheader;
					$ccms['desc']       = $rsNews->newsContent;
					//$ccms['keywords']   = $row->keywords;
					$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
				}

				echo '<div class="news-item-single">';
			}
			else
			{
				echo '<div class="news-item">';
			}

			if ($showDate)
			{
			?>
				<p class="news-date" title="<?php echo strftime('%Y-%m-%d',strtotime($rsNews->newsModified));?>"><?php echo htmlentities(strftime('%B',strtotime($rsNews->newsModified))); ?><span><?php echo date('j',strtotime($rsNews->newsModified)); ?></span></p>
			<?php
			}

			if(!$show_single_item)
			{
				?>
				<h2><a href="<?php echo $cfg['rootdir'].$page_name.'/'.rm0lead($rsNews->newsID).'-'.$newsTitle; ?>.html"><?php echo $rsNews->newsTitle; ?></a></h2>
				<?php
				if ($showTeaser)
				{
				?>
					<p class="news-teaser"><?php echo $rsNews->newsTeaser; ?></p>
				<?php
				}
				else
				{
				?>
					<div class="news-content"><?php echo $rsNews->newsContent; ?></div>
				<?php
				}
			}
			else
			{
			?>
				<h1><?php echo $rsNews->newsTitle; ?></h1>
				<p class="news-teaser"><?php echo $rsNews->newsTeaser; ?></p>
				<div class="news-content"><?php echo $rsNews->newsContent; ?></div>
			<?php
			}

			if($showAuthor)
			{
			?>
				<p class="news-author">
					<?php
					echo '&ndash; '.$rsNews->userFirst.' '.$rsNews->userLast;
					?>
				</p>
			<?php
			}

			if(!$show_single_item)
			{
			?>
				<p class="news-view-item">&laquo; <a href="<?php echo $cfg['rootdir'].$page_name; ?>.html?do=all"><?php echo $ccms['lang']['news']['viewarchive']; ?></a>
					| <a href="<?php echo $cfg['rootdir'].$page_name; ?>.html"><?php
					// echo $db->SelectSingleValue($cfg['db_prefix'].'pages', array('urlpage' => MySQL::SQLValue($page_name, MySQL::SQLVALUE_TEXT)), array('pagetitle'));
					echo $ccms['pagetitle'];
				?></a></p>
			<?php
			}
			?>
			</div>
			<hr class="clear" />
		<?php
		}

		if($show_single_item || $newsCount > $listMax)
		{
		?>
			<p class="news-view-archive<?php
				if ($show_single_item)
				{
					echo '4single';
				}
				?>"><a href="<?php echo $cfg['rootdir'].$page_name; ?>.html?do=all"><?php echo $ccms['lang']['news']['viewarchive']; ?></a></p>
		<?php
		}
	}
	else if($do == "all")
	{
		// and augment the breadcrumb trail and other template variables:
		$preview_qry = ($preview_checkcode ? '&preview=' . $preview_checkcode : '');
		$ccms['breadcrumb'][] = '<a href="'.$cfg['rootdir'].$page_name.'.html?do=all'.$preview_qry.'" title="'.$ccms['lang']['news']['viewarchive'].'">'.$ccms['lang']['news']['viewarchive'].'</a>';

		$ccms['urlpage']   = $page_name . '?do=all';
		$ccms['pagetitle'] .= ' : ' . $ccms['lang']['news']['viewarchive'];
		//$ccms['subheader']  = $row->subheader;
		//$ccms['desc']       = $rsNews->newsContent;
		//$ccms['keywords']   = $row->keywords;
		$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];

		$preview_qry = ($preview_checkcode ? '?preview=' . $preview_checkcode : '');

		$i = 0;
		foreach($newsrows as $rsNews)
		{
			// Filter spaces, non-file characters and account for UTF-8
			$newsTitle = cvt_text2legibleURL($rsNews->newsTitle);

			?>

			<div class="news-item-short">
				<h3>&#8594; <a href="<?php echo $cfg['rootdir'].$page_name.'/'.rm0lead($rsNews->newsID).'-'.$newsTitle.'.html'.$preview_qry; ?>"><?php echo $rsNews->newsTitle; ?></a></h3>
				<div class="news-timestamp"><?php echo strftime('%Y-%m-%d',strtotime($rsNews->newsModified));?> &ndash; <?php echo $rsNews->userFirst.' '.$rsNews->userLast; ?></div>
				<p class="news-teaser"><?php echo $rsNews->newsTeaser; ?></p>
			</div>
			<?php
			$i++;
		}
	}
}
else
{
	echo $ccms['lang']['system']['noresults'];
}
?>
