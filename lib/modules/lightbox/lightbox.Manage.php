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


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(__FILE__)))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/admin/includes/security.inc.php'); // when session expires or is overridden, the login page won't show if we don't include this one, but a cryptic error will be printed.


// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2']))
{
	die("No external access to file");
}


$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');




// Read through selected album, get first and count all
function fileList($d)
{
	$l = array();
	foreach(array_diff(scandir($d),array('.','..','index.html','info.txt','_thumbs')) as $f)
	{
		if(is_file($d.'/'.$f))
		{
			$ext = strtolower(substr($f, strrpos($f, '.') + 1));
			if ($ext=='jpg'||$ext=='jpeg'||$ext=='png'||$ext=='gif')
			{
				$l[] = $f;
			}
		}
	}
	sort($l, SORT_STRING);
	return $l;
}


function calc_thumb_padding($img_path, $thumb_path = null, $max_height = 80, $max_width = 80)
{
	$show_thumb = 0;
	$height = null;
	$width = null;
	$aspect_ratio = null;
	if(!empty($thumb_path) && file_exists($thumb_path))
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
		if(file_exists($thumb_path))
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

	$rv['style'] = 'style="padding:' . $rv['ph1'] . 'px ' . $rv['pw2'] . 'px ' . $rv['ph2'] . 'px ' . $rv['pw1'] . 'px; width:' . $rv['w'] . 'px; height:' . $rv['h'] . 'px;"';

	return $rv;
}





// Fill array with albums
$albums = array();
$count = array();

if ($handle = opendir(BASE_PATH.'/media/albums/'))
{
	while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != ".." && is_dir(BASE_PATH.'/media/albums/'.$file))
		{
			// Fill albums array
			$albums[] = $file;
		}
	}
	closedir($handle);
	sort($albums, SORT_STRING);

	// to make sure $count[] array is in sync with the $albums[] array, we need to perform this extra round AFTER the sort() operation.
	foreach($albums as $key => $file)
	{
		// Count files in album
		$images = fileList(BASE_PATH.'/media/albums/'.$file);
		$count[$key] = count($images);
	}
}

$album = getGETparam4Filename('album');
$album_path = (in_array($album, $albums) ? BASE_PATH.'/media/albums/'.$album : null);

$page_id = getGETparam4IdOrNumber('page_id');
$preview_checkcode = GenerateNewPreviewCode($page_id);



$tinyMCE_required = false;


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Lightbox module</title>
	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<link rel="stylesheet" type="text/css" href="modLightbox.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/ie.css" />
	<![endif]-->
</head>
<body>
	<div class="module" id="lightbox-management">
		<div class="center-text <?php echo $status; ?>">
			<?php
			if(!empty($status_message))
			{
				echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>';
			}
			?>
		</div>

		<div class="span-16 colborder">
		<?php
		// more secure: only allow showing specific albums if they are in the known list; if we change that set any time later, this code will not let undesirable items slip through
		if(empty($album))
		{
		?>
			<form action="lightbox.Process.php?page_id=<?php echo $page_id; ?>&action=del-album" method="post" accept-charset="utf-8">
			<h2><?php echo $ccms['lang']['album']['currentalbums']; ?></h2>
			<div class="table_inside">
				<table cellspacing="0" cellpadding="0">
					<?php
					if(count($albums) > 0)
					{
					?>
					<tr>
						<?php
						if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
						{
						?>
							<th class="span-1 nowrap">&#160;</th>
						<?php
						}
						?>
						<th class="span-8 nowrap"><?php echo $ccms['lang']['album']['album']; ?></th>
						<th class="span-3 nowrap"><?php echo $ccms['lang']['album']['files']; ?></th>
						<th class="span-7 nowrap"><?php echo $ccms['lang']['album']['lastmod']; ?></th>
						<th class="span-5 nowrap"><?php echo $ccms['lang']['album']['assigned_page']; ?></th>
						</tr>
						<?php
						foreach ($albums as $key => $value)
						{
							$pageName = 'light';  // TODO
							
							// Alternate rows
							if($key % 2 != 1)
							{
								echo '<tr class="altrgb">';
							}
							else
							{
								echo '<tr>';
							}

							if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
							{
							?>
								<td class="nowrap">
									<input type="checkbox" name="albumID[<?php echo $key + 1; ?>]" value="<?php echo $value; ?>" id="newsID">
									<?php 
									echo '<a href="' . $cfg['rootdir'] . $pageName . '/' . $value . '.html?preview=' . $preview_checkcode . '" ' .
											'title="' . $ccms['lang']['backend']['previewpage'] . '"><span class="ss_sprite_16 ss_eye">&#160;</span></a>';
									?>
								</td>
							<?php
							}
							?>
							<td class="nowrap">
								<a href="lightbox.Manage.php?page_id=<?php echo $page_id; ?>&album=<?php echo $value;?>"><span class="ss_sprite_16 ss_folder_picture">&#160;</span><?php echo $value;?></a>
							</td>
							<td class="nowrap">
								<span class="ss_sprite_16 ss_pictures">&#160;</span><?php echo $count[$key]; ?>
							</td>
							<td class="nowrap">
								<span class="ss_sprite_16 ss_calendar">&#160;</span><?php echo date("Y-m-d G:i:s", filemtime(BASE_PATH.'/media/albums/'.$value)); ?>
							</td>
							<td class="nowrap">
								<span class="ss_sprite_16 ss_page">&#160;</span><?php echo $pageName; ?>
							</td>
						</tr>
						<?php
						}
					}
					else
					{
						echo $ccms['lang']['system']['noresults'];
					}
					?>
				</table>
			</div>
			<hr />
			<?php
			if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']) && count($albums) > 0)
			{
			?>
				<button type="submit" onclick="return confirmation_delete();" name="deleteAlbum"><span class="ss_sprite_16 ss_bin_empty">&#160;</span><?php echo $ccms['lang']['backend']['delete']; ?></button>
			<?php
			}
			?>
			</form>
		<?php
		}
		else
		{
			// Load all images
			$images = fileList($album_path);
			$imagethumbs = array();
			$imginfo = array();
			if (count($images) > 0)
			{
				foreach($images as $index => $file)
				{
					$imagethumbs[$index] = '../../../media/albums/'.$album.'/_thumbs/'.$file;
					$thumb_path = $album_path.'/_thumbs/'.$file;
					$img_path = $album_path.'/'.$file;
					$imginfo[$index] = calc_thumb_padding($img_path, $thumb_path);
				}
			}
			?>
			<h2><?php echo $ccms['lang']['album']['manage']; ?></h2>
			<form action="lightbox.Process.php?page_id=<?php echo $page_id; ?>&album=<?php echo $album; ?>&amp;action=del-images" accept-charset="utf-8" method="post" id="album-pics">
			<div class="right">
				<?php
				if (count($images) > 0 && $perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
				{
				?>
					<a class="button" onclick="move_up_in_order(); return false;" title="Move to front in display order." >
						<span class="ss_sprite_16 ss_bullet_arrow_top">&#160;</span>
					</a>
					<a class="button" onclick="move_up_in_order(); return false;" title="Move up in display order." >
						<span class="ss_sprite_16 ss_bullet_arrow_up">&#160;</span>
					</a>
					<a class="button" onclick="move_up_in_order(); return false;" title="Move down in display order." >
						<span class="ss_sprite_16 ss_bullet_arrow_down">&#160;</span>
					</a>
					<a class="button" onclick="move_up_in_order(); return false;" title="Move to bottom in display order." >
						<span class="ss_sprite_16 ss_bullet_arrow_bottom">&#160;</span>
					</a>
					<a class="button" onclick="move_up_in_order(); return false;" title="Group these images." >
						<span class="ss_sprite_16 ss_pictures">&#160;</span>
					</a>
					<a class="button" onclick="toggle_image_edit_mode(); return false;" title="Edit the title and description of each of these images." >
						<span class="ss_sprite_16 ss_pencil">&#160;</span>
					</a>
					<a class="button" onclick="delete_these_files(); return false;">
						<span class="ss_sprite_16 ss_bin_empty">&#160;</span><?php echo $ccms['lang']['backend']['delete']; ?>
					</a>
					<a class="button" onclick="return confirm_regen();" href="lightbox.Process.php?page_id=<?php echo $page_id; ?>&album=<?php echo $album; ?>&amp;action=confirm_regen">
						<span class="ss_sprite_16 ss_arrow_in">&#160;</span><?php echo $ccms['lang']['album']['regenalbumthumbs']; ?>
					</a>
				<?php
				}
				?>
				<a class="button" href="lightbox.Manage.php?page_id=<?php echo $page_id; ?>">
					<span class="ss_sprite_16 ss_arrow_undo">&#160;</span><?php echo $ccms['lang']['album']['albumlist']; ?>
				</a>
			</div>
			<div class="clear">
			<?php
			foreach ($images as $key => $value)
			{
				echo '<label class="thumbimgwdel"><span style="background-image: url(' . path2urlencode($imagethumbs[$key]) . ');" class="thumbview" title="Thumbnail of ' . $value . '" ' . /* $imginfo[$key]['style'] . */ ' >&#160;</span>';

				if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
				{
					echo '<input type="checkbox" name="imageName['. ($key+1) .']" value="' . $value . '">';
				}
				echo "</label>\n";
			}
			?>
			</div>
			</form>
			<hr class="clear space" />
			<?php
		}
		?>
		</div>

		<div class="span-8 last">
		<?php
		if(empty($album))
		{
		?>
			<h2><?php echo $ccms['lang']['album']['newalbum']; ?></h2>
			<?php
			if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
			{
			?>
				<form action="lightbox.Process.php?page_id=<?php echo $page_id; ?>&action=create-album" id="createAlbum" method="post" accept-charset="utf-8">
					<label for="album"><?php echo $ccms['lang']['album']['album']; ?></label>
					<input type="text" class="required minLength:1 text" name="album" value="" id="album-create" />
					<button type="submit"><span class="ss_sprite_16 ss_wand">&#160;</span><?php echo $ccms['lang']['forms']['createbutton']; ?></button>
				</form>
			<?php
			}
			else
			{
				echo $ccms['lang']['auth']['featnotallowed'];
			}
			?>

			<hr class="clear space" />
		<?php
		}
		else
		{
			$lines = @file($album_path.'/info.txt');
			?>
			<h2><?php echo $ccms['lang']['album']['settings']; ?></h2>
			<?php
			if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
			{
			?>
				<form action="lightbox.Process.php?page_id=<?php echo $page_id; ?>&action=apply-album" method="post" accept-charset="utf-8">
					<label for="albumtopage"><?php echo $ccms['lang']['album']['apply_to']; ?></label>
					<select class="text" name="albumtopage" id="albumtopage" size="1">
						<option value=""><?php echo $ccms['lang']['backend']['none']; ?></option>
						<?php
						$lightboxes = $db->SelectArray($cfg['db_prefix'].'pages', array('module' => "'lightbox'"));
						if ($db->ErrorNumber()) $db->Kill();
						for ($i = 0; $i < count($lightboxes); $i++)
						{
						?>
							<option <?php echo (!empty($lines[0])&&trim($lines[0])==$lightboxes[$i]['urlpage']?'selected="selected"':null); ?> value="<?php echo $lightboxes[$i]['urlpage'];?>"><?php echo $lightboxes[$i]['urlpage'];?>.html</option>
						<?php
						}
						?>
					</select>
					<?php
					$desc = '';
					for ($x = 1; $x < count($lines); $x++)
					{
						$desc = trim($desc.' '.$lines[$x]); // [i_a] double invocation of htmlspecialchars, together with the form input (lightbox.Process.php)
					}
					
					$tinyMCE_required = true;
					?>
					<label for="description"><?php echo $ccms['lang']['album']['description']; ?></label>
					<textarea name="description" rows="3" cols="40" id="description"><?php echo $desc; ?></textarea>
					<input type="hidden" name="album" value="<?php echo $album; ?>" id="album-cfg" />
					<div class="right">
						<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['savebutton']; ?></button>
					</div>
				</form>
			<?php
			}
			else
			{
				echo $ccms['lang']['auth']['featnotallowed'];
			}
			?>

			<hr class="clear space" />
		<?php
		}

		if(count($albums) > 0)
		{
		?>
			<h2><?php echo $ccms['lang']['album']['uploadcontent']; ?></h2>
			<?php
			if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
			{
			?>
			<form action="./lightbox.Process.php?<?php
				/*
				 * FancyUpload 3.0 uses a Flash object, which doesn't pass the session ID cookie, hence it BREAKS the session.
				 * Given that we now finally DO check the session variables, FancyUpload suddenly b0rks with timeout errors as
				 * lightbox.Process.php didn't produce ANY output in such circumstances.
				 *
				 * We need to make sure the Flash component forwards the session ID anyway. Use SID for that. See also:
				 *
				 *  http://www.php.net/manual/en/function.session-id.php
				 *  http://devzone.zend.com/article/1312
				 *  http://www.php.net/manual/en/session.idpassing.php
				 */
				$sesid = null;
				if (defined('SID'))
				{
					$sesid = SID;
				}

				if (!empty($sesid))
				{
					echo $sesid;
				}
				else
				{
					echo 'SID' . md5($cfg['authcode'].'x') . '=' . session_id();
				}

				/*
				 * Because sessions are long-lived, we need to add an extra check as well, which will ensure that the current
				 * form display will only produce a single permitted upload request; we can do this using a few random values
				 * which may be stored in the session, but we MUST DESTROY those values once we've handled the corresponding
				 * 'save-files' action resulting from a form submit.
				 */
				$_SESSION['fup1'] = md5(mt_rand().time().mt_rand());
				echo '&SIDCHK=' . $_SESSION['fup1'];
				
				/* whitespace is important here... */ ?>&page_id=<?php echo $page_id; ?>&action=save-files" method="post" enctype="multipart/form-data" id="lightboxForm">

				<label><?php echo $ccms['lang']['album']['toexisting']; ?>
					<select name="album" id="album-upl-target" class="text" size="1">
						<?php
						foreach ($albums as $value)
						{
						?>
							<option <?php echo ($album === $value ? "selected" : null); ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
						<?php
						}
						?>
					</select>
				</label>
				
				<div class="clearfix">
					<label><?php echo $ccms['lang']['forms']['overwrite_imgs']; ?>?</label>
					<label for="f_ovr1" class="yesno"><?php echo $ccms['lang']['backend']['yes']; ?>: </label>
						<input type="radio" id="f_ovr1" name="overwrite_existing" value="Y" />
					<label for="f_ovr2" class="yesno"><?php echo $ccms['lang']['backend']['no']; ?>: </label>
						<input type="radio" id="f_ovr2" checked="checked" name="overwrite_existing" value="N" />
				</div>
				
				<div id="lightbox-status">
					<p>
						<a id="lightbox-browse"><span class="ss_sprite_16 ss_folder_image">&#160;</span><?php echo $ccms['lang']['album']['browse']; ?></a> |
						<a id="lightbox-clear"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['album']['clear']; ?></a> |
						<a id="lightbox-upload"><span class="ss_sprite_16 ss_picture_save">&#160;</span><?php echo $ccms['lang']['album']['upload']; ?></a>
					</p>
					<div>
						<strong class="overall-title"></strong>
						<br />
						<img src="../../../lib/includes/js/fancyupload/Assets/bar.gif" class="progress overall-progress" />
					</div>
					<div>
						<strong class="current-title"></strong>
						<br />
						<img src="../../../lib/includes/js/fancyupload/Assets/bar.gif" class="progress current-progress" />
					</div>
					<div class="current-text"></div>
				</div>

				<ul id="lightbox-list"></ul>
			</form>

			<div id="lightbox-fallback" class="clear" >
				<form action="lightbox.Process.php?page_id=<?php echo $page_id; ?>&action=save-files1" method="post" accept-charset="utf-8" enctype="multipart/form-data">
					<?php echo $ccms['lang']['album']['singlefile']; ?>

					<label><?php echo $ccms['lang']['album']['toexisting']; ?>
						<select name="album" id="album_upload1" class="text" size="1">
							<?php
							foreach ($albums as $value)
							{
							?>
								<option <?php echo ($album === $value ? "selected" : null); ?> value="<?php echo $value; ?>"><?php echo $value; ?></option>
							<?php
							}
							?>
						</select>
					</label>
<?php
/* 
the file input element cannot be styled without a lot of hassle. The extra effort required is deemed not worth it, since the 'usual' process
wouldn't even get here as it uses the Flash-based multifile upload feature available further above.

See also: http://www.quirksmode.org/dom/inputfile.html
*/
?>
					<input id="lightbox-photoupload" type="file" name="Filedata" class="span-24" />
					<hr class="space" />
					<div class="right clear">
						<button type="submit"><span class="ss_sprite_16 ss_add"><span class="ss_sprite_16 ss_folder_picture">&#160;</span><?php echo $ccms['lang']['album']['upload']; ?></button>
					</div>
				</form>
			</div>
			<?php
			}
			else
			{
				echo $ccms['lang']['auth']['featnotallowed'];
			}
		}
		?>
		</div>

		<div id="lightbox-pending" class="lightbox-spinner-bg">
			<p class="loading-img" ><?php echo $ccms['lang']['album']['please_wait']; ?></p>
		</div>

<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	<textarea id="jslog" class="log span-25" readonly="readonly">
	</textarea>
<?php
}
?>

	</div>
<?php
// prevent JS errors when permissions don't allow uploading (and all the rest)
if($perm->is_level_okay('manageModLightbox', $_SESSION['ccms_userLevel']))
{
?>
<script type="text/javascript" charset="utf-8">

function confirmation_delete()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'D') !== false ? 'confirm("'.$ccms['lang']['backend']['confirmdelete'].'")' : 'true'); ?>;
	return !!answer;
}

function confirm_regen()
{
	var answer=confirm('<?php echo $ccms['lang']['backend']['confirmthumbregen']; ?>');
	if(answer)
	{
		try
		{
			$('lightbox-pending').setStyle('visibility', 'visible');
			return true;
		}
		catch(e)
		{
			$('lightbox-pending').setStyle('visibility', 'hidden');
			return false;
		}
	}
	else
	{
		$('lightbox-pending').setStyle('visibility', 'hidden');
		return false;
	}
}

function delete_these_files()
{
	var go = confirmation_delete();

	if (go)
	{
		var form = $('album-pics');
		form.submit();
	}
}


<?php
$js_files = array();
$js_files[] = $cfg['rootdir'] . 'lib/includes/js/the_goto_guy.js';
$js_files[] = $cfg['rootdir'] . 'lib/includes/js/mootools-core.js,mootools-more.js';

$driver_code = '';
$starter_code = null;
if (!$tinyMCE_required)
{
	$js_files[] = $cfg['rootdir'] . 'lib/includes/js/fancyupload/dummy.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader/Fx.ProgressBar.js,FancyUpload2.js';
	$js_files[] = $cfg['rootdir'] . 'lib/modules/lightbox/modLightbox.js';
}
else
{	
	$js_files = array_merge($js_files, generateJS4TinyMCEinit(0, 'description', true));
	// these must FOLLOW the tinyMCE JS list as that part will include the basics for these ones as well:
	$js_files[] = $cfg['rootdir'] . 'lib/includes/js/fancyupload/dummy.js,FancyUpload2.js';
	$js_files[] = $cfg['rootdir'] . 'lib/modules/lightbox/modLightbox.js';

	$driver_code = <<<EOT42
		tinyMCE.init({
			mode : "exact",
			elements : "description",
			//theme : "advanced",
			theme : "simple",
			skin: 'o2k7',
			skin_variant: 'silver',
			//theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect',
			//theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,forecolorpicker,backcolor,backcolorpicker',
			//theme_advanced_buttons3 : 'removeformat,visualaid,|,sub,sup,|,charmap,emotions,spellchecker,advhr',
			//theme_advanced_buttons4 : 'cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak',
			//theme_advanced_toolbar_location: 'top'
			theme_simple_toolbar_location: 'top'
		});
EOT42;

	$starter_code = generateJS4TinyMCEinit(1, 'description', true);
}


$driver_code .= "\n" . "lazyload_done_now_init('" . $cfg['rootdir'] . "');   // defined in modLightbox.js\n";

echo generateJS4lazyloadDriver($js_files, $driver_code, $starter_code);
?>
</script>
<script type="text/javascript" src="../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
<?php
}
?>
</body>
</html>
