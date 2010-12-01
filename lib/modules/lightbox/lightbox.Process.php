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



// Some security functions
if (!checkAuth())
{
	die_and_goto_url(null, $ccms['lang']['auth']['featnotallowed']);
}

// set jpeg quality for the thumbnails; turns out they are quite reasonable @ 70% quality (and still way smaller than @ 100%)
define('THUMBNAIL_JPEG_QUALITY', 70);

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");


// Set default variables
$album_name	= getPOSTparam4Filename('album');
$do_action	= getGETparam4IdOrNumber('action');



/**
 *
 * Create a new album
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "create-album") 
{
	// Only if current user has the rights
	if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
	{
		if($album_name!=null) 
		{
			$dest = BASE_PATH.'/media/albums/'.$album_name;
			if(!is_dir($dest)) 
			{
				if(@mkdir($dest) && @mkdir($dest.'/_thumbs') && @fopen($dest.'/info.txt', "w")) 
				{
					header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['itemcreated']).'&album='.$album_name));
					exit();
				} 
				else 
				{
					header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_dirwrite'])));
					exit();
				}
			} 
			else 
			{
				header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_exists'])));
				exit();
			}
		} 
		else 
		{
			header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_tooshort'])));
			exit();
		}
	} 
	else 
	{
		header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['auth']['featnotallowed'])));
		exit();
	}
}

/**
 *
 * Delete a current album (including all of its files)
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "del-album") 
{
	// Only if current user has the rights
	if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
	{
		if(empty($_POST['albumID'])) 
		{
			header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_selection'])));
			exit();
		} 
		else 
		{
			function rrmdir($dir) 
			{
				if (is_dir($dir)) 
				{
					$objects = scandir($dir);
					
					foreach ($objects as $object) 
					{
						if ($object != "." && $object != "..") 
						{
							if (filetype($dir."/".$object) == "dir") 
							{
								rrmdir($dir."/".$object); 
							}
							else 
							{
								unlink($dir."/".$object);
							}
						}
					}
					reset($objects);
					rmdir($dir);
				} 
				return true;
		 	}
		
			$total 	= count($_POST['albumID']);
			$i		= 0;
			foreach ($_POST['albumID'] as $key => $value) 
			{
				$key = filterParam4Number($key);
				$value = filterParam4Filename($value);
				
				if(!empty($key)&&!empty($value)) 
				{
					$dest = BASE_PATH.'/media/albums/'.$value;
					if(is_dir($dest)) 
					{
						if(rrmdir($dest)) 
						{
							$i++;
						}
					}
				}
			}
			if($total==$i) 
			{
				header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'])));
				exit();
			}
		}
	} 
	else 
	{
		header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['auth']['featnotallowed'])));
		exit();
	}
}

/**
 *
 * Delete a one or more images
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "del-images") 
{
	FbX::SetFeedbackLocation($cfg['rootdir'] . 'lib/modules/lightbox/lightbox.Manage.php');
	
	try
	{
		// Only if current user has the rights
		if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
		{
			$album = getGETparam4Filename('album');
			
			if(!empty($album)) 
			{
				// Number of selected items
				$total = (!empty($_POST['imageName']) && is_array($_POST['imageName']) ? count($_POST['imageName']) : 0);
				
				// If nothing selected, throw error
				if($total==0) 
				{
					throw new FbX($ccms['lang']['system']['error_selection'], 'album=' . $album);
				}
				
				$i=0;
				foreach ($_POST['imageName'] as $key => $picname) 
				{
					$picname = filterParam4FileName($picname);
					
					if(!empty($picname)) 
					{
						$file	= BASE_PATH.'/media/albums/'.$album.'/'.$picname;
						$thumb	= BASE_PATH.'/media/albums/'.$album.'/_thumbs/'.$picname;
						if(is_file($file)) 
						{
							// first kill the thumbnail: if anything goes wrong then, we always regenerate later.
							if(@unlink($thumb) && @unlink($file)) 
							{
								// good!
							} 
							else 
							{
								throw new FbX($ccms['lang']['system']['error_delete'] . ': ' . htmlentities($picname), 'album='.$album);
							}
						}
						else 
						{
							throw new FbX($ccms['lang']['system']['error_delete'] . '= ' . htmlentities($picname), 'album='.$album);
						}
					}
					else 
					{
						throw new FbX($ccms['lang']['system']['error_tooshort'], 'album='.$album);
					}
					
					$i++;
				}
				
				header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'].' ('.$i.' '.$ccms['lang']['album']['files'].')').'&album='.$album));
				exit();
			} 
			else 
			{
				throw new FbX($ccms['lang']['auth']['featnotallowed'], 'album='.$album);
			}
		} 
		else 
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed'], 'album='.$album);
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
 * Apply album to page
 *
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "apply-album") 
{
	// Only if current user has the rights
	if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
	{
		if($album_name!=null) 
		{
			// Posted variables
			$topage = getPOSTparam4Filename('albumtopage');
			$description = getPOSTparam4DisplayHTML('description');
			$infofile = BASE_PATH.'/media/albums/'.$album_name.'/info.txt';
			
			if ($handle = fopen($infofile, 'w+')) 
			{
			    if (fwrite($handle, $topage."\r\n".$description)) 
				{
					header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?album='.$album_name.'&status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
					exit();
			    }
			} 
			else 
			{
				header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_write'])));
				exit();
			}
		} 
		else 
		{
			header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_tooshort'])));
			exit();
		}
	} 
	else 
	{
		header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['auth']['featnotallowed'])));
		exit();
	}
}

/**
 *
 * Process and save image plus thumbnail
 *
 * See also the comment in lightbox.Manage.php: FancyUpload 3.0 doesn't pass the 
 * cookies, so we had to hack it using a URL query string adaptation.
 * As we like to play it safe when uploading files, we'll add another check right 
 * here to ensure this action is only allowed once per form rendering.
 */
if($_SERVER['REQUEST_METHOD'] == "POST" && $do_action == "save-files") 
{
	if (!checkAuth() || empty($_GET['SIDCHK']) || $_SESSION['fup1'] != $_GET['SIDCHK'])
	{
		echo "<p>" . (empty($_GET['SIDCHK']) ? '----' : $_GET['SIDCHK']) . ', ' . $_SESSION['fup1'] . "</p>\n";
		var_dump($_GET);
		var_dump($_COOKIES);
	
		// $_SESSION['fup1'] = md5(mt_rand().time().mt_rand());
		
		die($ccms['lang']['auth']['featnotallowed']);
	}
	
	/*
	WARNING: we must NOT reset/alter the extra check session value in here as 
	         FancyUpload will invoke this code multiple times from the same 
		     web form when bulk uploads are performed (more then one(1) image file).
	
	         So we are a little less safe as the extra session var will only 
		     be regenerated every time the upload form is rerendered.
		 
	         Alas.
	*/
	//$_SESSION['fup1'] = md5(mt_rand().time().mt_rand());
	
	$dest = BASE_PATH.'/media/albums/'.$album_name;
	if(!is_dir($dest)) 
	{
		header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_write'])));
		exit();
	} 
	// else ...    [i_a] dangling else removed
	
	// Validation
	$error 		= false;
	$error_code = 0;
	$size       = false; // init to prevent PHP errors about unknown vars further down

	// get the local (temporary) filename:
	$uploadedfile = (isset($_FILES['Filedata']) && !empty($_FILES['Filedata']['tmp_name']) ? $_FILES['Filedata']['tmp_name'] : null); // RAW is okay: it's generated locally (server-side) and contains a temp filename
	// and what it's supposed to be named (as specced by the uploader):
	$target_filename = (isset($_FILES['Filedata']) && !empty($_FILES['Filedata']['name']) ? filterParam4Filename($_FILES['Filedata']['name']) : null);
	// Set file and get file extension
	$extension = pathinfo($target_filename, PATHINFO_EXTENSION);

	if (empty($extension) || empty($target_filename) || empty($uploadedfile) || !is_uploaded_file($uploadedfile)) 
	{
		$error = 'Invalid Upload: ';
		$error_code = $uploadedfile . ' : ' . $extension . ' : ' . $target_filename;
	}
	
	if (empty($error) && !($size = @getimagesize($uploadedfile))) 
	{
		$error = 'Please upload only images, no other files are supported.';
		$error_code = $uploadedfile . ' : ' . $extension . ' : ' . $target_filename;
	}
	
	if (empty($error) && !in_array($size[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM) ) ) 
	{
		$error = 'Please upload only images of type JPEG, GIF or PNG.';
		$error_code = $size[2];
	}
	
	if (empty($error) && (($size[0] < 50) || ($size[1] < 50)))  // braces for proper evaluation precedence!!
	{
		$error = 'Please upload an image bigger than 50px.';
		$error_code = $size[0] . ':' . $size[1];
	}
	
	$src = false;
	if (empty($error)) 
	{
		// Do resize    
		switch ($extension)
		{
		case 'jpg':
		case 'jpeg':
			$src = @imagecreatefromjpeg($uploadedfile);
			break;
			
		case 'png':
			$src = @imagecreatefrompng($uploadedfile);
			break;
			
		case 'gif':
			$src = @imagecreatefromgif($uploadedfile);
			break;
			
		default:
			$error = 'Unsupported file format.';
			$error_code = $uploadedfile . ' : ' . $extension . ' : ' . $target_filename;
			break;
		}
	}
	
	if (empty($error) && $src == false)
	{
		$error = 'Internal error.';
		$error_code = $uploadedfile . ' : ' . $extension . ' : ' . $target_filename;
	}
	
	if (empty($error))
	{
		$imginf = getimagesize($uploadedfile);
		if (!is_array($imginf))
		{
			$error = 'Invalid or severely corrupt image file.';
			$error_code = $uploadedfile . ' : ' . $extension . ' : ' . $target_filename;
		}
		else
		{
			// make sure image type matches extension:
			$ok = false;
			switch ($extension)
			{
			case 'jpg':
			case 'jpeg':
				$ok = ($imginf[2] == IMAGETYPE_JPEG);
				break;
				
			case 'png':
				$ok = ($imginf[2] == IMAGETYPE_PNG);
				break;
				
			case 'gif':
				$ok = ($imginf[2] == IMAGETYPE_GIF);
				break;
				
			default:
				break;
			}
			if (!$ok)
			{
				$error = 'File type does not match extension or corrupt file.';
				$error_code = $uploadedfile . ' : ' . $extension . ' : ' . $target_filename . ' : ' . $imginf[2];
			}
		}
	}
	if (empty($error))
	{
		$width = $imginf[0];
		$height = $imginf[1];
		
		$aspect_ratio = (floatval($height)/floatval($width));
		
		// Resize original file to max 640 x 480
		$newheight = $height;
		$newwidth = $width;
		if ($newwidth > 640)
		{
			$newwidth	= 640;
			$newheight	= intval($aspect_ratio * $newwidth);
		}
		if ($newheight > 480)
		{
			$newheight = 480;
			$newwidth = intval($newheight / $aspect_ratio);
		}
		$tmp = imagecreatetruecolor($newwidth,$newheight);
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		
		// Resize thumbnail to approx 80 x 80
		$newheight_t = $height;
		$newwidth_t = $width;
		if ($newwidth_t > 80)
		{
			$newwidth_t = 80;
			$newheight_t = intval($aspect_ratio * $newwidth_t);
		}
		if ($newheight_t > 80)
		{
			$newheight_t = 80;
			$newwidth_t = intval($newheight_t / $aspect_ratio);
		}
		$tmp_t = imagecreatetruecolor($newwidth_t,$newheight_t);
		imagecopyresampled($tmp_t,$tmp,0,0,0,0,$newwidth_t,$newheight_t,$width,$height);
		
		// Save newly generated versions
		$thumbnail	= $dest.'/_thumbs/'. $target_filename;
		$original	= $dest.'/'.$target_filename;
		
		switch ($extension)
		{
		case 'jpg':
		case 'jpeg':
			imagejpeg($tmp, $original, 100);
			imagejpeg($tmp_t, $thumbnail, THUMBNAIL_JPEG_QUALITY);
			break;
			
		case 'png':
			imagepng($tmp, $original, 9);
			imagepng($tmp_t, $thumbnail, 9);
			break;
			
		case 'gif':
			imagegif($tmp, $original);
			imagegif($tmp_t, $thumbnail);
			break;
			
		default:
			break;
		}
		
		imagedestroy($tmp);
		imagedestroy($tmp_t);
	}
	
	// Check for errors
	if (!empty($error)) 
	{
		$return = array(
			'status' => '0',
			'error' => $error,
			'code' => $error_code
		);
	} 
	else 
	{
		$return = array(
			'status' => '1',
			'name' => $target_filename,
			'src' => $dest.'/'.$target_filename
		);
		// Our processing, we get a hash value from the file
		$return['hash'] = md5_file($return['src']);
		$info = @getimagesize($return['src']);
		
		if ($info) 
		{
			$return['width'] = $info[0];
			$return['height'] = $info[1];
			$return['mime'] = $info['mime'];
		}
	}

	$response = getREQUESTparam4IdOrNumber('response');
	if ($response == 'xml') 
	{
		/* do nothing */
	} 
	else 
	{
		// header('Content-type: application/json');
		echo json_encode($return);
	}
}

/**
 * Regenerate all thumbnails. This will delete any existing thumbnails!
 */
if($_SERVER['REQUEST_METHOD'] == "GET" && $do_action == "confirm_regen") 
{
	// Only if current user has the rights
	if($perm['manageModLightbox']>0 && $_SESSION['ccms_userLevel']>=$perm['manageModLightbox']) 
	{
		$album = getGETparam4Filename('album');
		
		if(!empty($album)) 
		{
			$dest = BASE_PATH.'/media/albums/'.$album;
			if(!is_dir($dest)) 
			{
				header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_dirwrite'])));
				exit();
			} 
			if(!is_dir($dest.'/_thumbs')) 
			{
				if(@mkdir($dest.'/_thumbs')) 
				{
					header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['itemcreated']).'&album='.$album_name));
					exit();
				} 
				else 
				{
					header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_dirwrite'])));
					exit();
				}
			} 
			
			foreach(array_diff(scandir($dest),array('.','..','index.html','info.txt')) as $f) 
			{
				if(is_file($dest.'/'.$f)) 
				{
					$extension = pathinfo($f, PATHINFO_EXTENSION);

					$uploadedfile = $dest . '/' . $f;
					
					// Do resize
					switch ($extension)
					{
					case 'jpg':
					case 'jpeg':
						$src = imagecreatefromjpeg($uploadedfile);
						break;
						
					case 'png':
						$src = imagecreatefrompng($uploadedfile);
						break;
						
					case 'gif':
						$src = imagecreatefromgif($uploadedfile);
						break;
						
					default:
						// skip all other file formats
						continue;
					}
					if (empty($src))
					{
						header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode('invalid image format for file ' . $uploadedfile . ', type: ' . $extension)));
						exit();
					}
						 
					list($width,$height)=getimagesize($uploadedfile);
					
					$aspect_ratio = (floatval($height)/floatval($width));
					
					// Resize thumbnail to approx 80 x 80
					$newheight_t = $height;
					$newwidth_t = $width;
					if ($newwidth_t > 80)
					{
						$newwidth_t = 80;
						$newheight_t = intval($aspect_ratio * $newwidth_t);
					}
					if ($newheight_t > 80)
					{
						$newheight_t = 80;
						$newwidth_t = intval($newheight_t / $aspect_ratio);
					}
					
					// sharpen intermediate image when shrinking size a lot.
					//
					// see also:
					//   http://adamhopkinson.co.uk/blog/2010/08/26/sharpen-an-image-using-php-and-gd/
					//   http://nl2.php.net/manual/nl/ref.image.php#56144
					//   http://loriweb.pair.com/8udf-sharpen.html
					if ($height >= 160 || $width >= 160)
					{
						$newheight = $newheight_t * 2;
						$newwidth = intval($newheight / $aspect_ratio);
						
						$tmp = imagecreatetruecolor($newwidth,$newheight);
						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

						// define the sharpen matrix
						$sharpen = array(
							array(0.0, -1.0, 0.0),
							array(-1.0, 5.0, -1.0),
							array(0.0, -1.0, 0.0)
						);

						// calculate the sharpen divisor
						$divisor = array_sum(array_map('array_sum', $sharpen));

						// apply the matrix
						imageconvolution($tmp, $sharpen, $divisor, 0);

						imagedestroy($src);
						$src = $tmp;
						$width = $newwidth;
						$height = $newheight;
					}

					$tmp_t = imagecreatetruecolor($newwidth_t,$newheight_t);
					imagecopyresampled($tmp_t,$src,0,0,0,0,$newwidth_t,$newheight_t,$width,$height);
					
					// Save newly generated versions
					$thumbnail	= $dest.'/_thumbs/'.$f;
					
					@unlink($thumbnail);
					
					switch ($extension)
					{
					case 'jpg':
					case 'jpeg':
						imagejpeg($tmp_t, $thumbnail, THUMBNAIL_JPEG_QUALITY);
						break;
						
					case 'png':
						imagepng($tmp_t, $thumbnail, 9);
						break;
						
					case 'gif':
						imagegif($tmp_t, $thumbnail);
						break;
						
					default:
						break;
					}
					
					imagedestroy($tmp_t);
				}
			}

			header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullregenerated']).'&album='.$album));
			exit();
		}
		else
		{
			header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_tooshort'])));
			exit();
		}
	} 
	else 
	{
		header('Location: ' . makeAbsoluteURI('lightbox.Manage.php?status=error&msg='.rawurlencode($ccms['lang']['auth']['featnotallowed'])));
		exit();
	}
}

?>