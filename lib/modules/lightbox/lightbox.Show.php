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


// Default albums location
$album_path = BASE_PATH.'/media/albums';
$album_url  = $cfg['rootdir'] . 'media/albums';

$pageID = getGETparam4Filename('page');
$imgID  = getGETparam4Filename('id');
$is_printing = ($ccms['printing'] == 'Y');

// Read through selected album, get first and count all
function fileList($d)
{
	$l = array();
	if (is_dir($d))
	{
		foreach(array_diff(scandir($d),array('.','..','index.html','info.txt','_thumbs')) as $f)
		{
			if(is_file($d.'/'.$f))
			{
				$ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
				if ($ext=="jpg"||$ext=="jpeg"||$ext=="png"||$ext=="gif")
				{
					$l[] = $f;
				}
			}
		}
		sort($l, SORT_STRING);
	}
	return $l;
}

// Get all the albums in the default media/albums location
$albums = array();
if($handle = opendir($album_path))
{
	while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != ".." && $file != "index.html" && $file != "info.txt" && is_dir($album_path . '/' . $file))
		{
			$albums[] = $file;
			}
	}
	closedir($handle);
	sort($albums, SORT_STRING);
}

// Get specified album for current page
$singleShow = false;
$spec_album = array();
if(count($albums) > 0)
{
	foreach ($albums as $file)
	{
		$lines = @file($album_path.'/'.$file.'/info.txt');
		if($lines > 0 && @preg_match('/'.$pageID.'/',$lines[0]))
		{
			$spec_album[] = $file;
		}
	}
	// Define single show
	$singleShow = (count($spec_album) == 1 || count($albums) == 1 || !empty($imgID));
}


// code requires mootools: make sure we load it!
tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/includes/js/mootools-core.js');
tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/includes/js/mootools-more.js');
// now register our own JS and make sure it ends up AFTER the mootools stuff has been loaded:
tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/modules/lightbox/resources/script.js');


tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/modules/lightbox/resources/style.css > media="screen" title="lightbox"');

// window.addEvent("domready", function() {
$ccms['JS.done'][] = 'initImageZoom({loadImage: "' . $cfg['rootdir'] . 'lib/modules/lightbox/resources/loading.gif"});';




function calc_thumb_padding($img_path, $thumb_path = null, $max_height = 80, $max_width = 80)
{
	$show_thumb = 0;
	$height = null;
	$width = null;
	$aspect_ratio = null;
	if(!empty($thumb_path) && file_exists($thumb_path) && is_readable($thumb_path))
	{
		$imginfo = @getimagesize($thumb_path);
		if (!empty($imginfo[0]))
		{
			$height = floatval($imginfo[1]);
			$width = floatval($imginfo[0]);
			$aspect_ratio = (floatval($height)/floatval($width));

			$show_thumb = 1;
		}
	}
	if ($show_thumb != 1)
	{
		$thumb_path = $img_path;
		if(file_exists($thumb_path) && is_readable($thumb_path))
		{
			$imginfo = @getimagesize($thumb_path);
			if (!empty($imginfo[0]))
			{
				$height = floatval($imginfo[1]);
				$width = floatval($imginfo[0]);
				$aspect_ratio = (floatval($height)/floatval($width));

				$show_thumb = 2;
			}
		}
	}

	if ($show_thumb == 0)
	{
		return null;
	}

	// Resize thumbnail to approx 80 x 80
	$newheight = $height;
	$newwidth = $width;
	if ($newwidth > $max_width)
	{
		$newwidth = $max_width;
		$newheight = intval($aspect_ratio * $newwidth);
	}
	if ($newheight > $max_height)
	{
		$newheight = $max_height;
		$newwidth = intval($newheight / $aspect_ratio);
	}

	// calc padding to fill box up to max_h x max_w
	$pad_height = $max_height - $newheight;
	$pad_width = $max_width - $newwidth;

	$rv = array();
	$rv['h'] = $newheight;
	$rv['w'] = $newwidth;
	$rv['show'] = $show_thumb;
	$rv['ph1'] = intval($pad_height / 2);
	$pad_height -= $rv['ph1'];
	$rv['ph2'] = $pad_height;
	$rv['pw1'] = intval($pad_width / 2);
	$pad_width -= $rv['pw1'];
	$rv['pw2'] = $pad_width;

	$rv['style'] = 'style="padding:' . $rv['ph1'] . 'px ' . $rv['pw2'] . 'px ' . $rv['ph2'] . 'px ' . $rv['pw1'] . 'px;"';

	return $rv;
}



//echo '<pre>count = ' . count($albums) . ', single = ' . (1 * $singleShow) . '</pre> ';

if(count($albums) > 1 && $singleShow == false)
{
	if(!empty($albums))
	{
		foreach ($albums as $i => $album)
		{
			if (count($spec_album) > 0)
			{
				$show_this_one = false;
				foreach ($spec_album as $spec)
				{
					if ($spec == $album)
					{
						$show_this_one = true;
						break;
					}
				}
				if (!$show_this_one)
					continue; // skip this entry
			}

			// Get the images in an album
			$images = fileList($album_path.'/'.$album);

			// If album is not empty and thumbnail is found
			$show_thumb = 0;
			if (count($images) > 0)
			{
				$thumb_path = $album_path.'/'.$album.'/_thumbs/'.$images[0];
				$img_path = $album_path.'/'.$album.'/'.$images[0];
				$imginfo = calc_thumb_padding($img_path, $thumb_path);
				if(is_array($imginfo))
				{
					$show_thumb = $imginfo['show'];
				}
			}
			switch ($show_thumb)
			{
			case 1:
				echo "\n<div class=\"album-item\">";
				echo "<a href=\"".$cfg['rootdir'].$pageID."/".$album.".html\">";
				echo "<img src=\"".$album_url."/".$album."/_thumbs/".$images[0]."\" " . $imginfo['style'] . " /><br/>";
				echo ucfirst($album)." (".count($images).")</a></div>\n";
				break;

			case 0:
			default:
				// If album does exist, but no contents (empty album)
				echo "\n<div class=\"album-item\">";
				$thumb_path = BASE_PATH . "lib/modules/lightbox/resources/empty.png";
				$imginfo = calc_thumb_padding($thumb_path);
				echo "<img src=\"".$cfg['rootdir']."lib/modules/lightbox/resources/empty.png\" " . $imginfo['style'] . " /><br/>";
				echo ucfirst($album)." (0)</div>\n";
				break;

			case 2:
				// Otherwise show the first image of non-empty album and scale it to 80x80
				echo "\n<div class=\"album-item\">";
				echo "<a href=\"".$cfg['rootdir'].$pageID."/".$album.".html\">";
				echo "<img src=\"".$album_url."/".$album."/".$images[0]."\" " . $imginfo['style'] . " /><br/>";
				echo ucfirst($album)." (".count($images).")</a></div>\n";
				break;
			}
		}
	}
	else
	{
		echo $ccms['lang']['album']['noalbums'];
	}
}
elseif($singleShow)
{
	$album = (!empty($imgID) ? $imgID : (count($spec_album) > 0 ? $spec_album[0] : $albums[0])); // [i_a] PHP evaluates nested ?: from RIGHT-TO-LEFT! Without the braces, you'ld get the wrong result.

	$desc = null;
	$lines = @file($album_path.'/'.$album.'/info.txt');
	for ($x = 1; $x < count($lines); $x++)
	{
		$desc .= ' ' . $lines[$x];
	}

	echo '<h3>'.$ccms['lang']['album']['album'].' '.ucfirst($album)."</h3>\n";

	$back_to_overview_html = '';
	if(!empty($imgID))
	{
		if (!$is_printing)
		{
			$back_to_overview_html = '<div class="album-back-to-ov-top"><a href="'.$cfg['rootdir'].$pageID.'.html">'.$ccms['lang']['backend']['tooverview']."</a></div>\n";
			echo $back_to_overview_html;
		}

		// and augment the breadcrumb trail and other template variables:
		$preview_checkcode = ($ccms['preview'] == 'Y' ? GenerateNewPreviewCode(null, $pageID) : false);
		$ccms['previewcode'] = $preview_checkcode;

		$preview_qry = ($preview_checkcode ? '?preview=' . $preview_checkcode : '');
		$ccms['breadcrumb'][] = '<a href="'.$cfg['rootdir'].$ccms['page_name'].'/'.$album.'.html'.$preview_qry.'" title="'.$ccms['lang']['album']['album'].' '.ucfirst($album).'">'.$ccms['lang']['album']['album'].' '.ucfirst($album).'</a>';

		$ccms['urlpage']   = $ccms['page_name'] . '/' . $album;
		$ccms['complete_page_url'] = $ccms['urlpage'] . '.html';
		$ccms['pagetitle'] .= ' : ' . $ccms['lang']['album']['album'].' '.ucfirst($album);
		//$ccms['subheader']  = $row->subheader;
		$ccms['desc']       = $desc;
		//$ccms['keywords']   = $row->keywords;
		$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
	}

	echo '<div class="lightbox-description">' . $desc . "</div>\n";

	// Get the images in an album
	$images = fileList($album_path.'/'.$album);

	// If album is not empty and thumbnail is found
	if (count($images) > 0)
	{
		foreach($images as $content)
		{
			$caption = substr($content, 0, strrpos($content, '.'));
			$caption = ucfirst(str_replace('_', ' ', $caption));

			// If album is not empty and thumbnail is found
			$show_thumb = 0;
			$thumb_path = $album_path.'/'.$album.'/_thumbs/'.$content;
			$img_path = $album_path.'/'.$album.'/'.$content;
			$imginfo = calc_thumb_padding($img_path, $thumb_path);
			if(is_array($imginfo))
			{
				$show_thumb = $imginfo['show'];
			}
			switch ($show_thumb)
			{
			case 1:
				echo "\n<div class=\"album-item\">";
				echo "<a rel=\"imagezoom[$album]\" href=\"$album_url/$album/$content\" title=\"$caption\">";
				echo "<img src=\"".$album_url."/".$album."/_thumbs/".$content."\" " . $imginfo['style'] . " />";
				echo "</a></div>\n";
				break;

			case 0:
			default:
				// If album does exist, but no contents (empty album)
				echo "\n<div class=\"album-item\">";
				$thumb_path = BASE_PATH . "lib/modules/lightbox/resources/empty.png";
				$imginfo = calc_thumb_padding($thumb_path);
				echo "<img src=\"".$cfg['rootdir']."lib/modules/lightbox/resources/empty.png\" " . $imginfo['style'] . " />";
				echo "</div>\n";
				break;

			case 2:
				// Otherwise show the first image of non-empty album and scale it to 80x80
				echo "\n<div class=\"album-item\">";
				echo "<a rel=\"imagezoom[$album]\" href=\"$album_url/$album/$content\" title=\"$caption\">";
				echo "<img src=\"".$album_url."/".$album."/".$content."\" " . $imginfo['style'] . " />";
				echo "</div>\n";
				break;
			}
		}
	}
	else
	{
		echo '<p>&#160;</p><p>' . $ccms['lang']['system']['error_value'] . '</p>';
	}

	echo str_replace("album-back-to-ov-top", "album-back-to-ov-bottom", $back_to_overview_html);
}
else
{
	echo $ccms['lang']['system']['noresults'];
}
?>