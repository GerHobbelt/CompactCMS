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

/***************************************************************
 * Optimizer invocation added oct/2010, Ger Hobbelt
 *
 * The idea is this: before this feature was added in here, CompactCMS had
 * quite a few 'optimized' CSS and JS files floating around.
 * Which is a big bother when you're trying to develop stuff as debuggers and
 * other diagnostic tools can't cope well with such materials. So from a
 * development point of view it is best to have the development sources
 * (which are nicely formatted for HUMAN perusal) available on the site under
 * construction.
 *
 * From there, there are two ways towards a 'release':
 *
 * 1) pack/optimize everything so its filesize is reduced and put that on
 *    the 'release' server.
 *
 * 2) pack/optimize 'on the fly'.
 *
 * #1 has the significant drawback that any 'live' checks/diagnostics are hampered
 * to the point of becoming infeasible tasks, while #2 has the drawback of
 * implied significantly raised server load.
 *
 * The way out of this conundrum is already in here:
 *   the CSS/JS cache!
 * By enhancing its use to include EVERY JS and CSS file on the site, we have
 * enabled a cache for all these. This seems pretty useless for single file fetches,
 * but wait until you add 'on the fly compression/optimization'... Then it
 * turns out to be pretty handy to feed every JS and CSS load through this baby:
 * we can optimize/compress each of those JS/CSS files ONCE, cache them in
 * compressed format (which would cut on further CPU load due to recompression on
 * each fetch, as well) and thus arrive at a very nicely workable option #2:
 * have your development code on the server as-is and still benefit from high-speed,
 * cached, transfers.
 *
 * All it takes is three bits of work:
 *
 * a) Augment the Rewrite rules to point all JS and CSS URLs to me.
 *
 * b) Adapt this code so it doesn't REQUIRE the JS and CSS files to sit in a specific
 *    directory.
 *
 * c) Install and invoke the appropriate compressor/optimizer for each file type
 *    on the server: this means adding CSS and JS optimizers (written in PHP) to the
 *    source tree and calling them when the need arrises.
 *****************************************************************/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc.
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');


define('COMBINER_DEV_DUMP_OUTPUT', true); // dump generated content to cache dir with processed 'files' names - only happens when in DEVELOPMENT mode!


$optimize = array();
$optimize['css'] = ($cfg['IN_DEVELOPMENT_ENVIRONMENT'] ? false : 'css-compressor');    // possible values: false, 'csstidy', 'css-compressor'
$optimize['javascript'] = ($cfg['IN_DEVELOPMENT_ENVIRONMENT'] ? false : 'JSmin');       // possible values: false, 'JSmin'
$optimize['css3'] = ($cfg['IN_DEVELOPMENT_ENVIRONMENT'] ? 'remove' : 'browser-fix');            // possible values: false, 'remove', 'browser-fix'


$cache      = !$cfg['IN_DEVELOPMENT_ENVIRONMENT']; // only disable cache when in development environment
$cachedir   = BASE_PATH . '/lib/includes/cache';

$jsdir      = getGETparam4FullFilePath('jsdir');
if (empty($jsdir))
	$jsdir = $cfg['rootdir'] . '/lib/includes/js';
else if ($jsdir[0] != '/')
	$jsdir = $cfg['rootdir'] . $jsdir;

$cssdir     = getGETparam4FullFilePath('cssdir');
if (empty($cssdir))
	$cssdir = $cfg['rootdir'] . 'admin/img/styles';
else if ($cssdir[0] != '/')
	$cssdir = $cfg['rootdir'] . $cssdir;

$http_root = $cfg['rootdir'];
$root = str_replace('\\', '/', cvt_abs_http_path2realpath($http_root, $cfg['rootdir'], BASE_PATH));


// Determine the directory and type we should use
$type = getGETparam4IdOrNumber('type');
switch ($type)
{
case 'css':
	$http_base = path_remove_dot_segments($cssdir);
	$base = str_replace('\\', '/', cvt_abs_http_path2realpath($http_base, $cfg['rootdir'], BASE_PATH));
	break;
case 'javascript':
	$http_base = path_remove_dot_segments($jsdir);
	$base = str_replace('\\', '/', cvt_abs_http_path2realpath($http_base, $cfg['rootdir'], BASE_PATH));
	break;
default:
	send_response_status_header(503); // Not Implemented
	exit;
};


$extra_JS_callback = getGETparam4IdOrNumber('cb');
$only_when_expression = trim(getGETparam4MathExpression('only-when', ''));



require_once(BASE_PATH . '/lib/includes/browscap/Browscap.php');

$client_browser = new Browscap(BASE_PATH . '/lib/includes/cache');
$client_browser->localFile = BASE_PATH . '/lib/includes/browscap/browscap/php_browscap.ini';
$client_browser = $client_browser->getBrowser();

if (0)
{
	echo '<h1>$client_browser</h1>';
	echo "<pre>";
	var_dump($client_browser);
	echo "</pre>";
	echo "<p>CFG:</p>\n";
	echo "<pre>";
	var_dump($cfg);
	echo "</pre>";
	echo "<p>OPTIMIZE:</p>\n";
	echo "<pre>";
	var_dump($optimize);
	echo "</pre>";
}

/*
 * we abuse the browscap conditional filter to check whether we should okay or discard this load request.
 *
 * This is our server-side alternative, suitable for use with lazyloading, to the MSIE conditional
 * comment:
 *
 * <--[equ IEx] .... -->
 *
 * For this we 'fake'; a wee bit of content (a single line) and see whether the filter leaves it be, or not:
 */
if (!empty($only_when_expression))
{
	$faked_content = '1 /*:: ' . $only_when_expression . ' */';
	$faked_content = filter4browser($faked_content, $client_browser);
	$do_not_load = (empty($faked_content) || $faked_content[0] != '1');
}
else
{
	$do_not_load = false;
}
/*
 * when $do_not_load==true then we produce a seemingly EMPTY file.
 *
 * However, we DO adhere to the ?cb=callback JS request, when it's there.
 *
 * In short: we go through the entire process, we just do NOT load any real content!
 */




/*
 * This bit allows paths such as '../../../../../../../etc/passwd' to enter the system.
 *
 * We protect against such attacks by making sure the effective path ends up within the
 * BASE_PATH subtree (or better).
 */
$elements = explode(',', getGETparam4CommaSeppedFullFilePaths('files'));


if (substr($base, 0, strlen(BASE_PATH)) != BASE_PATH)
{
	send_response_status_header(403); // Illegal Access
	die();
}


// let's speed things up (min = 4 days)
$offset = 3600 * 24 * 5;
$expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($expire);

// Determine last modification date of the files
$lastmodified = 0;
foreach($elements as $element)
{
	$path = str_replace('\\', '/', realpath($base . '/' . $element));

	if (($type == 'javascript' && substr($path, -3) != '.js') ||
		($type == 'css' && substr($path, -4) != '.css'))
	{
		send_response_status_header(403); // Forbidden
		die();
	}

	/*
	 * The next part makes sure the code is still XSS safe as each of the
	 * generated file paths are checked against the 'base' directory and
	 * will only be allowed when they are part of that subtree.
	 */
	if (substr($path, 0, strlen($root)) != $root)
	{
		send_response_status_header(403); // Illegal Access
		die();
	}
	if (!file_exists($path))
	{
		send_response_status_header(404); // Not Found
		die();
	}

	$lastmodified = max($lastmodified, filemtime($path));
}


/*
 * Send Etag hash
 *
 * make sure all settings, which influence what should be fetched from cache, are
 * include in the MD5 hash!
 *
 * This includes the current minification settings in $optimize[]!
 *
 * And since we now have server-side Browser-dependent filtering enabled in here
 * (to filter CSS depending on who's visiting), plus we have lazyload/loadorder-safe
 * JS invocation through the request-definable JS callback, all those should be taken into
 * account for the indentifying hash code as well.
 *
 * This basically means we'll need to include the browscap record and the $_SERVER['QUERY_STRING']
 * both to cover all bases for CSS.
 *
 * However, since our current filter is limited to checking against major brands and versions
 * only, we can significantly cut down on the number of variations which should co-exist
 * by only including just those particular browscap elements in the hash!
 *
 * That way we can be sure that each browser brand gets served the appropriate pre-filtered CSS
 * from cache!
 *
 * NOTE: JavaScript doesn't get processed in a browser-dependent way, so we don't need
 *       multiple copies of those JS files! This significantly improves our ability to
 *       'prime the cache' at startup as the largest files are the JS ones, and minifying
 *       those can take quite a long time...
*/
$hash = $lastmodified . '-' .
		md5($base . ':' . implode(':', $optimize) . ':' .
			$_SERVER['QUERY_STRING'] /* this includes all 'files'! */ .
			($type == 'css'
			? '::' . $client_browser->Browser . '::' . $client_browser->Version
			: '')
		);
header('Etag: "' . $hash . '"');

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) &&
	stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"' &&
	!$cfg['IN_DEVELOPMENT_ENVIRONMENT']) // disable this 'shortcut' when in development environment
{
	// Return visit and no modifications, so do not send anything
	send_response_status_header(304); // Not Modified
	header('Content-Length: 0');
	exit();
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

	if ($cfg['HTTPD_SERVER_TAKES_CARE_OF_CONTENT_COMPRESSION'])
	{
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

				header('Content-Type: text/' . $type . '; charset=UTF-8');
				header('Content-Length: ' . filesize($cachedir . '/' . $cachefile));

				fpassthru($fp);
				fclose($fp);
				exit();
			}
		}
	}

	// Get contents of the files
	$contents = '';
	foreach($elements as $element)
	{
		$my_content = load_one($type, $http_base, $base, $root, $element);
		$contents .= "\n" . $my_content;
	}

	// invoke the apropriate optimizer for the given type:
	switch ($type)
	{
	case 'css':
		switch ($optimize['css'])
		{
		case 'csstidy':
			/*
			 * CSStidy would be great, but watch it with the 'optimise_shorthands'
			 * setting: that bugger implicitly ASSUMES CSS3 ABILITY: the generated
			 *     font:
			 * and
			 *     background:
			 * 'shorthands' are NOT accepted by FF3.6.x at least.
			 *
			 * Hence never dial that setting higher than '1' unless you fix csstidy.
			 */
			/*MARKER*/require_once(BASE_PATH . '/lib/includes/csstidy/class.csstidy.php');

			$css = new csstidy();

			$css->set_cfg('remove_last_;',true);
			$css->set_cfg('preserve_css',false);
			$css->set_cfg('lowercase_s', false); // MUST be 'false' or scrollbar in admin screen==MochaUI desktop is GONE :-(
			/*
			 * 1 common shorthands optimization
			 * 2 + font property optimization
			 * 3 + background property optimization
			 */
			$css->set_cfg('optimise_shorthands', 1);
			/* rewrite all properties with low case, better for later gzip */
			$css->set_cfg('case_properties', false);
			/* sort properties in alpabetic order, better for later gzip */
			$css->set_cfg('sort_properties', true);
			/*
			 * 1, 3, 5, etc -- enable sorting selectors inside @media: a{}b{}c{}
			 * 2, 5, 8, etc -- enable sorting selectors inside one CSS declaration: a,b,c{}
			 */
			$css->set_cfg('sort_selectors', false); // MUST be FALSE or admin screen is TOAST

			/* is dangerous to be used: CSS is broken sometimes. Modes: 0,1,2 */
			$css->set_cfg('merge_selectors', 0);

			/* preserve or not browser hacks */
			$css->set_cfg('discard_invalid_selectors', false);
			$css->set_cfg('discard_invalid_properties', false);
			$css->set_cfg('timestamp', false);

			$css->set_cfg('compress_colors', true);
			$css->set_cfg('compress_font-weight', true);
			$css->set_cfg('css_level', 'CSS2.1');
			$css->set_cfg('remove_bslash', true);

			$css->set_cfg('template','highest'); // default, highest, high, low

			$css->parse($contents);

			$contents = $css->print->plain();
			break;

		case 'css-compressor':
			// http://www.phphulp.nl/php/script/php-algemeen/css-compressor/1145/   + [i_a]

			/**
			 * Remove superfluous characters from CSS.
			 *
			 * @param string $contents The CSS data to be compressed
			 * @return string / boolean false
			 */
			function compress_css_file($sContent)
			{
				# Verwijder CSS kommentaar
				$sContent = preg_replace('/\/\*.*?\*\//s', '', $sContent);

				# Verwijder alle enters en tabs uit de inhoud van het CSS bestand
				$sContent = str_replace(array("\r", "\n", "\t"), '', $sContent);

				# Whitespaces rond bepaalde tekens  verwijderen
				$sContent = preg_replace('/\s*({|}|;|:|,)\s*/', '$1', $sContent);

				# Verwijder alle dubbele spaties
				$sContent = preg_replace('/ {2,}/', '', $sContent);

				# Vervang alle '0px' door '0'
				$sContent = preg_replace('/\b0 *px/', '0', $sContent);

				# Vervang alle '0.x' floating point values door '.x' values
				$sContent = preg_replace('/\b0\./', '.', $sContent);

				# Grijp alle {....} blokken
				if( preg_match_all('/{.*?}/s', $sContent, $aMatch) )
				{
					$aMatch[0] = array_unique($aMatch[0]);

					foreach( $aMatch[0] as $k => $v )
					{
						# Verwijder laatste ";" van laatste statement in een blok
						$l = strlen($v);
						if( $v[$l-2] == ';' )
						{
							$v = substr($v, 0, $l-2) . '}';
						}

						# Vervang het nieuw blok met het oude.
						$sContent = str_replace($aMatch[0][$k], $v, $sContent);
					}
				}

				return $sContent;
			}

			$contents = compress_css_file($contents);
			break;

		default:
			// no optimization
			break;
		}
		break;

	case 'js':
	case 'javascript':
		// make sure to paste the callback invocation at the end:
		if (!empty($extra_JS_callback))
		{
			$contents .= "\n\nif (typeof window.$extra_JS_callback == 'function')\n{\n //alert('invoking $extra_JS_callback');\n window.$extra_JS_callback();\n}\n";
		}

		switch ($optimize['javascript'])
		{
		case 'JSmin':
			/*MARKER*/require_once(BASE_PATH. '/lib/includes/rgrove-jsmin/jsmin.php');
			$contents = JSMin::minify($contents);
			break;

		default:
			// no optimization
			break;
		}
		break;

	default:
		// unidentified type: no optimization support.
		break;
	}


	// Store cache
	if ($cache)
	{
		if ($fp = @fopen($cachedir . '/' . $cachefile, 'wb'))
		{
			fwrite($fp, $contents);
			fclose($fp);
		}
		else
		{
			send_response_status_header(500);
			die("Failed to write data to cache file: " . $cachedir . '/' . $cachefile);
		}
	}

	if (COMBINER_DEV_DUMP_OUTPUT && $cfg['IN_DEVELOPMENT_ENVIRONMENT'])
	{
		$dump_filename = str2VarOrFileName($_GET['files']);

		if ($fp = @fopen($cachedir . '/' . $dump_filename, 'wb'))
		{
			fwrite($fp, $contents);
			fclose($fp);
		}
		else
		{
			send_response_status_header(500);
			die("Failed to write data to development diagnostics dump file: " . $cachedir . '/' . $dump_filename);
		}
	}

	// Send Content-Type
	header("Content-Type: text/" . $type . '; charset=UTF-8');

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
		$abspath = merg_path_elems($basedir, $relpath);
		$abspath = path_remove_dot_segments($abspath);
	}
	else
	{
		// don't modify, but bash out any lingering '../' parts in there to prevent illegal access outside DocumentRoot
		$abspath = $relpath;
		$abspath = path_remove_dot_segments($abspath);
	}

	return $abspath;
}



/**
 * Filter the CSS/JS based on sniffed browser capacilities: this way we can deliver nicely compliant CSS/JS files to
 * the browsers that can handle them and feed 'browser hacked' content to MSIE et al.
 *
 * After all, not everything can be easily remedied in a separate MSIE-only 'conditional comment'ed individual
 * CSS/JS file...
 *
 * The relevant 'markers' are comments which start with '::' without any leading whitespace. The expression
 * which follows it is of the format:
 *
 *     operator brand [version] ['|' operator brand [version] ]+ ['::' further comment]
 *
 * where 'operand' can be any of the comparison operators '==', '<', '>', '>=', '<=', '!=', the 'brand' is
 * name of the browser as per browscap and the (optional!) version is compared as a floating point number
 * of the format: major.minor
 *
 * The whitespace between 'brand' and 'version' is optional: 'brand' is assumed to stop at the first occurrence
 * of a decimal digit.
 *
 * When you wish to add a comment here for human perusal, you may do so after the double colon '::'.
 *
 * You may combine multiple conditions by separating them with '|' pipe symbols; these conditions are, of course,
 * treated as a boolean-OR combined set.
 *
 * The 'marker' comment filter is applied per line by default. The only time such markers apply to the ENTIRE CLASS
 * DEFINITION is when the marker is FOLLOWED by a '{' open curly brace: this implies that the entire scope block
 * will be kept or removed, depending on the outcome of the browser check.
 *
 * Returns the filtered CSS/JS.
 *
 *
 * WARNING:
 *
 * it is assumed such a marker comment is the last thing on the current line. We do NOT wish to cope with 'minified'
 * CSS/JS here, so you'd better make sure the CSS/JS is spread across multiple lines, i.e. CSS/JS code formatting for
 * /human perusal/ is what we accept/assume as input.
 *
 * you may feed this function 'minified' CSS/JS, but then it will only play nice IFF you also have ensured there's not
 * even a single occurrence of the 'filter marker' (comment start plus double colon) in there! In that case it's
 *   output <- input
 * and we're done.
 *
 *
 * IMPORTANT NOTE:
 *
 * This filter system is *NOT* meant to remove the 'CSS/JS hacks' from the CSS/JS source files (heck, the fact alone
 * that the installer will be using those CSS/JS files as well, while we (the Combiner) have NOT been turned on yet
 * through the RewriteRules is reason enough to keep them in there), but the reason for this filter is rather to
 * rid CSS/JS output of such CSS/JS hacks for compliant browsers, which would report quite a few of them as the errors
 * that they may be.
 * In short, reason #1 for this filter code is to shut up Firefox and Opera about errors in the CSS/JS being fed
 * to them.
 *
 * A nice side effect that is not to be sneezed at is the fact that we can now also 'condition' CSS/JS for those
 * brosers as we now have something similar to a 'server side conditional comment' approach.
 *
 * Ooooooh... shiny!
 */
function filter4browser($contents, $client_browser)
{
	$prev_content = '';
	for($idx = strpos($contents, "/*::"); $idx !== false; $idx = strpos($contents, "/*::"))
	{
		// shift non-conditional lead off to other buffer, so we're never bothered with it again in this loop: speed!
		$chunk = substr($contents, 0, $idx);
		$nlidx = strrpos($chunk, "\n");
		if ($nlidx === false)
		{
			// start of contents: assume we're on the first line!
			$nlidx = -1;
		}
		// clip just AFTER the previous NL: anything before that does not concern us any more...
		$nlidx = $idx - strlen($chunk) + $nlidx + 1;
		$prev_content .= substr($contents, 0, $nlidx);

		$contents = substr($contents, $nlidx);
		$eol = strcspn($contents, "\n");
		$this_line = substr($contents, 0, $eol);

		$contents = substr($contents, $eol);
		if (!preg_match('/^(.*)\/\*\:\:\s*([=<>!]=)\s*([^0-9:|*\/]+)([0-9]+(\.[0-9]+)?)?\s*(|\s*([=<>!]=)\s*([^0-9:|*\/]+)([0-9]+(\.[0-9]+)?)?\s*)*(::.*)?\*\/\s*$/', $this_line, $matches))
		{
			// illegal format: report this and fail dramatically
			send_response_status_header(500);
			die("Illegal filter condition string: " . $this_line);
		}

		$pass = true;

		$src = $matches[1];
		$match_count = count($matches);
		$comment = trim($matches[$match_count - 1]);
		/* op/brand/version = indexes 2/3/4 and a combined set has a index step of initial 5, and 4 after that */
		$extra_first_step = 1;
		for ($i = 2; $i < $match_count - 1 /* discount the last one: the trailing comment */; $i++)
		{
			$op = $matches[$i++];
			$brand = trim($matches[$i++]);
			$b_v_set = !empty($matches[$i]);
			$b_version = floatval($matches[$i++]);
			$i++; // skip the minor version match
			$i += $extra_first_step; // move to the next of a combined set (if there are any more conditions in there)
			$extra_first_step = 0;
			if ($i > 6 && empty($op) && empty($brand) && !$b_v_set)
			{
				// an empty match of the '| op brand version' set: happens when there's a comment at the end -- skip this entry
				continue;
			}

			// perform check, set $pass=false when there's a match
			//echo "<pre>check: ($op) ($brand) ($b_version) ($this_line) ($comment)\n";
			//echo "<pre>matches: \n"; var_dump($matches);
			$gotcha = (0 == strcasecmp($brand, $client_browser->Browser));
			/*
			 * we would have liked to calculate the version 'float' value from the ["MajorVer"] and ["MinorVer"] entries,
			 * but then we'd be screwed when you got versions like '3.01' which would be encoded as 3 and 1.
			 *
			 * On the other hand we cannot assume the ["Version"] entry has just a single point. After all, there's nothing
			 * stopping the format from speccing for example '3.01.2750' and again we'ld be screwed if we casted such an
			 * entry to float without watching out. So we do it the hard way and pick ["Version"] and strip off anything
			 * past the second '.' dot in there.
			 */
			if (!preg_match('/^([0-9]+(\.[0-9]+)?)/', $client_browser->Version, $vmp))
			{
				// illegal format: report this and fail dramatically
				send_response_status_header(500);
				die("Unexpected version format in browser capabilities DB: " . $client_browser->Version);
			}
			$sniffed_version = floatval($vmp[1]);
			$vchk = $sniffed_version - $b_version;
			//echo "\n<pre>browser says: " . $client_browser->Browser . " " . $client_browser->Version;
			//echo "\n<pre>we want: " . $op . " " . $brand . " " . $b_version;
			//echo "\n<pre>we got: gotcha = $gotcha, sniffed = $sniffed_version, vchk = $vchk, b_v_set = $b_v_set";

			switch ($op)
			{
			default:
				// illegal format: report this and fail dramatically
				send_response_status_header(500);
				echo "\n<pre>contents = $contents";
				die("Illegal filter comparison operator: " . $this_line);

			case '!=':
				$pass = !($gotcha && (!$b_v_set || $vchk == 0));
				break;

			case '==':
				$pass = ($gotcha && (!$b_v_set || $vchk == 0));
				break;

			case '>=':
				$pass = ($gotcha && (!$b_v_set || $vchk >= 0));
				break;

			case '>':
				$pass = ($gotcha && (!$b_v_set || $vchk > 0));
				break;

			case '<':
				$pass = ($gotcha && (!$b_v_set || $vchk < 0));
				break;

			case '<=':
				$pass = ($gotcha && (!$b_v_set || $vchk <= 0));
				break;
			}
			//echo "\n<pre>#### Verdict: " . ($pass ? "FILTER" : "PASS") . "\n";
		}

		if (!$pass)
		{
			// filter line... or CSS/JS block? When the next non-whitespace item is a '{' we'll need to ditch the entire block
			if (preg_match('/^\s*(\{[^}]*\})(.*)$/s', $contents, $matches))
			{
				// bingo! CSS/JS block!
				$contents = $matches[2];
				// removed!  <snip> Just like that! :-)
			}
			/*
			 * else: single line: just go on with the rest and see what needs to be done there in the next round.
			 *       The current line has already been stripped off the contents, so we're good to go.
			 */
		}
		else
		{
			// we must keep the current line/block:
			$prev_content .= $this_line;
		}
	}

	// re-merge contents from both buffers:
	$contents = $prev_content . $contents;

	return $contents;
}




/**
 * patch/correct CCS3 and other particulars, such as
 * '@import flattening', which reduces the number of CSS files to load.
 */
function fixup_css($contents, $http_base, $type, $base, $root, $element)
{
	global $cfg, $optimize, $client_browser;

	$contents = filter4browser($contents, $client_browser);

	// make sure the @import statements are on their own lines: easier for us to process them:
	$prev_content = '';
	for($idx = strpos($contents, "@import"); $idx !== false; $idx = strpos($contents, "@import"))
	{
		// shift non-@import-ing lead off to other buffer, so we're never bothered with it again in this loop: speed!
		$prev_content .= substr($contents, 0, $idx);

		$contents = substr($contents, $idx);
		if (!preg_match('/@import\s+url\(([^)]+)\);?(.*)/s', $contents, $matches))
		{
			send_response_status_header(500); // Internal Failure
			die('import url regex croaked');
		}
		$url = trim($matches[1]);
		$contents = $matches[2];

		$delimiter = '';
		if ($url[0] == "'" || $url[0] == '"')
		{
			$url = trim($url, $url[0]);
		}
		/*
		 * we know the path to the current CSS file; this one is relative to that one, or an absolute path.
		 *
		 * Only 'flatten' imported CSS which has a RELATIVE path: those are /ours/ :-)
		 */
		if ($url[0] != '/' && strpos($url, '://') === false)
		{
			/*
			 * a relative path was specified; either as
			 *   url(./relpath)
			 * or
			 *   url(../relpath)
			 * or
			 *   url(relpath)
			 *
			 * Anyway, prepend the current path to the given file and then discard the ./ and ../ entries in the path.
			 */
			//echo "<pre>@import\n";
			if (0)
			{
				echo "<pre>@import: $type, $http_base, \n$base, \n$root, $url, $element";
				$rfn = merg_path_elems($base, $element, '../', $url);
				echo "<pre>makes: $type, $http_base, \n$base, \n$root, $url, $rfn, $element";
				$rfn = path_remove_dot_segments($rfn);
				echo "<pre>makes 2: $type, $http_base, \n$base, \n$root, $url, $rfn, $element";
			}

			$imported_content = load_one($type, $http_base, $base, $root, $url);

			/*
			 * inject imported content into the current buffer to have it (re)processed in the loop:
			 * this way we detect and handle nested @import's.
			 */
			$contents = "\n" . $imported_content . "\n" . $contents;
		}
		else
		{
			/* abs @import URL: reconstruct the @import statement and shift it off to the 'done' buffer... */
			$stmt = "\n@import url(\"" . $url . "\");\n";
			$prev_content .= $stmt;
		}
	}

	// re-merge contents from both buffers:
	$contents = $prev_content . $contents;


	switch ($optimize['css3'])
	{
	case 'browser-fix':
		$is_IE = (0 == strcasecmp('IE', $client_browser->Browser));
		$is_FF = (0 == strcasecmp('Firefox', $client_browser->Browser));
		/*
		 * we would have liked to calculate the version 'float' value from the ["MajorVer"] and ["MinorVer"] entries,
		 * but then we'd be screwed when you got versions like '3.01' which would be encoded as 3 and 1.
		 *
		 * On the other hand we cannot assume the ["Version"] entry has just a single point. After all, there's nothing
		 * stopping the format from speccing for example '3.01.2750' and again we'ld be screwed if we casted such an
		 * entry to float without watching out. So we do it the hard way and pick ["Version"] and strip off anything
		 * past the second '.' dot in there.
		 */
		if (!preg_match('/^([0-9]+(\.[0-9]+)?)/', $client_browser->Version, $vmp))
		{
			// illegal format: report this and fail dramatically
			send_response_status_header(500);
			die("Unexpected version format in browser capabilities DB: " . $client_browser->Version);
		}
		$sniffed_version = floatval($vmp[1]);

		// fix CSS3 border-radius for IE:
		if ($is_IE)
		{
			$fixup = "    behavior: url('" . $cfg['rootdir'] . "admin/img/styles/PIE.php');\n";

			$contents = preg_replace('/\sborder-radius/', "\t". $fixup . "\tborder-radius", $contents);
		}
		else if ($is_FF)
		{
			$contents = preg_replace('/\sborder-radius/', "-moz-border-radius", $contents);
		}

		/* fix opacity for IE: */
		// -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)"; /* IE8 */
		// filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80); /* IE6 and 7*/
		// filter:alpha(opacity=80);
		// -ms-filter:'alpha(opacity=80)';
		/*
		 * see also why we removed the MSIE filter lines in the CSS file 'originals':
		 *
		 * http://developer.yahoo.com/performance/rules.html
		 *   (section: Avoid Filters)
		 *
		 * we can add the opacity filters back in through fixup_css() in
		 * the Combiner, as all CSS travels through there anyway.
		 */

		// FireFox/Chrome fixes
		//-webkit-border-radius: 5px [5px 5px 5px];
		//-moz-border-radius: 5px [5px 5px 5px];
		//border-radius: 5px [5px 5px 5px];

		$contents = '/* Browser: ' . $client_browser->Browser . ' ' . $client_browser->Version . " */\n" . $contents;
		break;

	case 'remove':
		// remove any border-radius alike entry, including the mozilla+webkit specific ones:
		$contents = preg_replace('/\s(-[a-z-]+)?border-radius[^:]*:\s*[^;}]+;?/', ' ', $contents);

		// remove -moz/khtml-opacity lines and MSIE filter lines:
		//
		// note that these damage the looks of mochaUI: AJAX windows turn up with a green border and red corners.
		// It's because a few mochaUI styles specify a 'opacity: 0;' to make them invisible.
		$contents = preg_replace('/\s(-ms-)?filter:\s*[\'"]?[^(};]*[Aa]lpha\([^)]+\)[\'"]?\s*;?/', ' ', $contents);
		$contents = preg_replace('/\s-[a-z]+-opacity:\s*[0-9.]+;?/', ' ', $contents);
		$contents = preg_replace('/\sopacity:\s*[0-9.]+;?/', ' ', $contents);
		break;

	default:
		// keep as-is
		break;
	}

	/*
	 * make all url() paths in there ABSOLUTE.
	 *
	 * As $element MAY contain path bits itself and url()s are relative to the CSS file, i.e. the $element,
	 * we must mix in those possible path bits. The fastest way to accomplish this is to simply
	 * specify a path like
	 *   <path+filename of CSS>/../
	 * and let the '..' directory remover do its regular job.
	*/
	$abspath = merg_path_elems($http_base, $element, '../');
	$abspath = path_remove_dot_segments($abspath);

	$contents = preg_replace('/\surl\(([^)]+)\)/e', "' url(\"'.css_mk_abs_path('\\1', '".$abspath."').'\")'", $contents);

	return $contents;
}



/**
 * patch/correct JS relative paths and other particulars
 */
function fixup_js($contents, $http_base, $type, $base, $root, $element)
{
	if (strmatch_tail($element, "tiny_mce_ccms.js"))
	{
		$flattened_content = load_tinyMCE_js($type, $http_base, $base, $root, $element);
		$contents .= "\n" . $flattened_content;
	}

	return $contents;
}



function load_tinyMCE_js($type, $http_base, $base, $root, $element)
{
	global $cfg;
	global $do_not_load;

	if ($do_not_load) return ''; // return zip, nada, nothing

	/*
	 * Make sure the tinyMCE language is set up correctly!
	 *
	 * If we don't this here, then $cfg['tinymce_language'] will not exist and we will croak further down below.
	 */
	SetUpLanguageAndLocale($cfg['language'], true);

	$mce_basepath = merg_path_elems($base, substr($element, 0, strlen($element) - strlen("tiny_mce_ccms.js")));

	$mce_files = array();
	$suffix = ''; /* can be '_src' or '_dev' for development work; '' for production / tests */

	// Add core
	$mce_files[] = merg_path_elems($mce_basepath, "tiny_mce" . $suffix . ".js");
	// Add core language(s)
	$languages = array($cfg['tinymce_language']);
	if ($cfg['tinymce_language'] != 'en')
	{
		$languages[] = 'en';
	}
	foreach ($languages as $lang)
	{
		$mce_files[] = merg_path_elems($mce_basepath, "langs/" . $lang . ".js");
	}
	// Add themes
	$themes = array('advanced');
	foreach ($themes as $theme)
	{
		$mce_files[] = merg_path_elems($mce_basepath, "themes", $theme, "editor_template" . $suffix . ".js");

		foreach ($languages as $lang)
		{
			$mce_files[] = merg_path_elems($mce_basepath, "themes", $theme, "langs", $lang . ".js");
			$mce_files[] = merg_path_elems($mce_basepath, "themes", $theme, "langs", $lang . "_dlg.js");
		}
	}
	// Add plugins
	
	$pluginarr = get_tinyMCE_plugin_list();

	foreach ($pluginarr as $plugin)
	{
		$mce_files[] = merg_path_elems($mce_basepath, "plugins", $plugin, "editor_plugin" . $suffix . ".js");

		foreach ($languages as $lang)
		{
			$mce_files[] = merg_path_elems($mce_basepath, "plugins", $plugin, "langs", $lang . ".js");
			$mce_files[] = merg_path_elems($mce_basepath, "plugins", $plugin, "langs", $lang . "_dlg.js");
		}
	}

//echo "<pre>";
//var_dump($mce_files);
//echo "</pre>\n";

	// now load all content:
	$my_content = '';
	foreach($mce_files as $jsf)
	{
		$path = realpath($jsf);
		if (is_file($path))
		{
			$c = file_get_contents($path) . "\n";
			if ($c !== false)
			{
				$my_content .= $c;
			}
			else
			{
				send_response_status_header(404); // Not Found
				die();
			}
		}
	}

	return $my_content;
}


/**
 * Load the content of the given item. ($element MUST be an absolute path!)
 *
 * Note that this function does 'fixup' the loaded content, which MAY result in recursive
 * invocation of this function to load each of the dectected sub-items. This way we can easily handle
 * 'flattening' CSS which uses the @import statement, etc.
 */
function load_one($type, $http_base, $base, $root, $element)
{
	global $do_not_load;

	$uri = path_remove_dot_segments($base . '/' . $element);
	$path = str_replace("\\", '/', realpath($uri)); /* Windows can handle '/' so we're OK with the replace here; makes strpos() below work on all platforms */

	/*
	 * only allow a load when the CSS/JS is indeed within document-root:
	 *
	 * as path_remove_dot_segments() will remove ALL '../' directory bits, any attempt to grab, say,
	 *   ../../../../../../../../../etc/passwd
	 * will fail as path_remove_dot_segments() will have DAMAGED the path and $element
	 * does not point within the $root path any more!
	 */
	$my_content = null;
	if (is_file($path) && strpos($path, $root) === 0)
	{
		//echo "<pre>$type, $http_base, \n$base, \n$root, $element, \n$uri --> $path, " . strpos($path, $root);
		$my_content = '';
		if (!$do_not_load)
		{
			$my_content = file_get_contents($path);
		}
	}
	else
	{
		die("<pre>$type, $http_base, \n$base, \n$root, $element, \n$uri --> $path, " . strpos($path, $root));
	}
	if ($my_content === false)
	{
		send_response_status_header(404); // Not Found
		die();
	}

	switch ($type)
	{
	case 'css':
		/*
		 * Before we go and optimize the CSS (or not), we fix up the CSS for IE7/8/... by adding the
		 *
		 *          behavior: url(PIE.php);
		 *
		 * line in every definition which has a 'border-radius'.
		 *
		 * We do it this way to ensure all styles are patched correctly; previously this was done by hand in the
		 * various CSS files, resulting in quite a few ommisions in the base css files and also the templates' ones.
		 *
		 * As we now force all CSS+JS requests through here, we can easily fix this kind of oddity very consistently
		 * by performing the fix in code, right here.
		 *
		 * As the result is cached, this effort is only required once. Which would happen at install time when
		 * you run the 'cache priming' action, resulting in a fully set up cache when you go 'live'.
		 */
		$my_content = fixup_css($my_content, $http_base, $type, $base, $root, $element);
		break;

	default:
		$my_content = fixup_js($my_content, $http_base, $type, $base, $root, $element);
		break;
	}
	return $my_content;
}
?>