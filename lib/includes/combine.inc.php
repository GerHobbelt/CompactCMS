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

/***************************************************************
Optimizer invocation added oct/2010, Ger Hobbelt 

The idea is this: before this feature was added in here, CompactCMS had 
quite a few 'optimized' CSS and JS files floating around.
Which is a big bother when you're trying to develop stuff as debuggers and
other diagnostic tools can't cope well with such materials. So from a
development point of view it is best to have the development sources 
(which are nicely formatted for HUMAN perusal) available on the site under
construction.

From there, there are two ways towards a 'release':

1) pack/optimize everything so its filesize is reduced and put that on 
   the 'release' server.
   
2) pack/optimize 'on the fly'.

#1 has the significant drawback that any 'live' checks/diagnostics are hampered
to the point of becoming infeasible tasks, while #2 has the drawback of
implied significantly raised server load.

The way out of this conundrum is already in here: 
  the CSS/JS cache!
By enhancing its use to include EVERY JS and CSS file on the site, we have
enabled a cache for all these. This seems pretty useless for single file fetches,
but wait until you add 'on the fly compression/optimization'... Then it
turns out to be pretty handy to feed every JS and CSS load through this baby:
we can optimize/compress each of those JS/CSS files ONCE, cache them in
compressed format (which would cut on further CPU load due to recompression on
each fetch, as well) and thus arrive at a very nicely workable option #2:
have your development code on the server as-is and still benefit from high-speed,
cached, transfers.

All it takes is three bits of work:

a) Augment the Rewrite rules to point all JS and CSS URLs to me.

b) Adapt this code so it doesn't REQUIRE the JS and CSS files to sit in a specific
   directory.
   
c) Install and invoke the appropriate compressor/optimizer for each file type
   on the server: this means adding CSS and JS optimizers (written in PHP) to the
   source tree and calling them when the need arrises.
****************************************************************/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');



$cache		= false;
$cachedir	= $cfg['rootdir'] . 'lib/includes/cache';

$jsdir		= getGETparam4FullFilePath('jsdir');
if (empty($jsdir)) 
	$jsdir = $cfg['rootdir'] . '/lib/includes/js';
else if ($jsdir[0] != '/') 
	$jsdir = $cfg['rootdir'] . $jsdir;
	
$cssdir		= getGETparam4FullFilePath('cssdir');
if (empty($cssdir)) 
	$cssdir = $cfg['rootdir'] . 'admin/img/styles';
else if ($cssdir[0] != '/') 
	$cssdir = $cfg['rootdir'] . $cssdir;


	
// Determine the directory and type we should use
$type = getGETparam4IdOrNumber('type');
switch ($type) 
{
case 'css':
	$http_base = path_remove_dot_segments($cssdir);
	$base = cvt_abs_http_path2realpath($http_base, $cfg['rootdir'], BASE_PATH);
	break;
case 'javascript':
	$http_base = path_remove_dot_segments($jsdir);
	$base = cvt_abs_http_path2realpath($http_base, $cfg['rootdir'], BASE_PATH);
	break;
default:
	send_response_status_header(503); // Not Implemented
	exit;
};


$elements = explode(',', getGETparam4CommaSeppedFilenames('files'));

// let's speed things up (min = 4 days)
$offset = 3600 * 120;	
$expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire);

// Determine last modification date of the files
$lastmodified = 0;
while (list(,$element) = each($elements)) 
{
	$path = realpath($base . '/' . $element);

	if (($type == 'javascript' && substr($path, -3) != '.js') || 
		($type == 'css' && substr($path, -4) != '.css')) 
	{
		send_response_status_header(403); // Forbidden
		exit;	
	}

	if (substr($path, 0, strlen($base)) != $base || !file_exists($path)) 
	{
		send_response_status_header(404); // Not Found
		exit;
	}
	
	$lastmodified = max($lastmodified, filemtime($path));
}

// Send Etag hash
$hash = $lastmodified . '-' . md5($base . '::' . $_GET['files']);
header("Etag: \"" . $hash . "\"");

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && 
	stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') 
{
	// Return visit and no modifications, so do not send anything
	send_response_status_header(304); // Not Modified
	header('Content-Length: 0');
} 
else 
{
	// Determine supported compression method
	if (!empty($_SERVER['HTTP_ACCEPT_ENCODING']))
	{
		$gzip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');
		$deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

		// Determine used compression method
		$encoding = $gzip ? 'gzip' : ($deflate ? 'deflate' : 'none');
	}
	else
	{
		$encoding = 'none';
	}

	// Check for buggy versions of Internet Explorer
	if (!empty($_SERVER['HTTP_USER_AGENT']))
	{
		if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') && 
			preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) 
		{
			$version = floatval($matches[1]);
			
			if ($version < 6)
				$encoding = 'none';
				
			if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1')) 
				$encoding = 'none';
		}
	}
	else
	{
		/*
		 * Play it safe: if the browser is behind a filtering proxy (e.g. privoxy) 
		 * which is configured strips this info, then we have to assume the worst,
		 * if only to ensure the data makes it through unharmed.
		 */
		$encoding = 'none';
	}


	
	// First time visit or files were modified
	if ($cache) 
	{
		// Try the cache first to see if the combined files were already generated
		$cachefile = 'cache-' . $hash . '.' . $type . ($encoding != 'none' ? '.' . $encoding : '');
		
		if (file_exists($cachedir . '/' . $cachefile)) 
		{
			if ($fp = fopen($cachedir . '/' . $cachefile, 'rb')) 
			{
				if ($encoding != 'none') 
				{
					header("Content-Encoding: " . $encoding);
				}
			
				header("Content-Type: text/" . $type);
				header("Content-Length: " . filesize($cachedir . '/' . $cachefile));
	
				fpassthru($fp);
				fclose($fp);
				exit();
			}
		}
	}

	// Get contents of the files
	$contents = '';
	reset($elements);
	while (list(,$element) = each($elements)) 
	{
		$path = realpath($base . '/' . $element);
		$my_content = file_get_contents($path);
		switch ($type)
		{
		case 'css':
			/*
			Before we go and optimize the CSS (or not), we fix up the CSS for IE7/8/... by adding the 
			
					 behavior: url(PIE.php);
			
			line in every definition which has a 'border-radius'.
			
			We do it this way to ensure all styles are patched correctly; previously this was done by hand in the
			various CSS files, resulting in quite a few ommisions in the base css files and also the templates' ones.
			
			As we now force all CSS+JS requests through here, we can easily fix this kind of oddity very consistently
			by performing the fix in code, right here.
			
			As the result is cached, this effort is only required once. Which would happen at install time when
			you run the 'cache priming' action, resulting in a fully set up cache when you go 'live'.
			*/
			$my_content = fixup_css($my_content, $http_base);
			break;
			
		default:
			$my_content = fixup_js($my_content, $http_base);
			break;
		}
		$contents .= "\n\n" . $my_content;
	}

	// invoke the apropriate optimizer for the given type:
	
	
	// Send Content-Type
	header("Content-Type: text/" . $type);
	
	if (isset($encoding) && $encoding != 'none') 
	{
		// Send compressed contents
		$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
		header("Content-Encoding: " . $encoding);
		header('Content-Length: ' . strlen($contents));
		echo $contents;
	} 
	else 
	{
		// Send regular contents
		header('Content-Length: ' . strlen($contents));
		echo $contents;
	}

	// Store cache
	if ($cache) 
	{
		if ($fp = fopen($cachedir . '/' . $cachefile, 'wb')) 
		{
			fwrite($fp, $contents);
			fclose($fp);
		}
	}
}



function css_mk_abs_path($relpath, $basedir)
{
	global $cfg;
	
	$delimiter = false;
	if ($relpath[0] == "'")
	{
		$delimiter = "'";
		$relpath = trim($relpath, $delimiter);
	}
	else if ($relpath[0] == '\\' && $relpath[1] == '"')
	{
		$delimiter = '"';
		// a bit of a hack; anyway, paths cannot contain a double quote so we're fine doing it like this (alternative to 'trim()')
		$relpath = str_replace('\\"', '', $relpath);
	}
	
	if ($relpath[0] != '/' && strpos($relpath, '://') === false)
	{
		/* 
		a relative path was specified; either as		
		  url(./relpath)
		or
		  url(../relpath)
		or
		  url(relpath)
		  
		Anyway, prepend the path with the absolute path to the given file and then discard the ./ and ../ entries in the path.
		*/
		$abspath = $basedir . (substr($basedir, -1, 1) != '/' ? '/' : '') . $relpath;
		$abspath = path_remove_dot_segments($abspath);
	}
	else
	{
		// don't modify
		$abspath = $relpath;
	}
		
	return $delimiter . $abspath . $delimiter;
}



/**
patch/correct CCS3 and other particulars
*/
function fixup_css($contents, $basedir)
{
	global $cfg;
	
	// fix CSS3 border-radius for IE:
	$fixup = "    behavior: url('" . $cfg['rootdir'] . "admin/img/styles/PIE.php');\n";

	$contents = preg_replace('/\sborder-radius/', "\t". $fixup . "\tborder-radius", $contents);

	// make all url() paths in there ABSOLUTE:
	$contents = preg_replace('/\surl\(([^)]+)\)/e', "' url('.css_mk_abs_path('\\1', '$basedir').')'", $contents);
	
	return $contents;
}



/**
patch/correct JS relative paths and other particulars
*/
function fixup_js($contents, $basedir)
{
	global $cfg;
	
	return $contents;
}


?>