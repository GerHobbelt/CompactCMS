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
	$base = str_replace('\\','/',dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');


// security check done ASAP
if(!checkAuth() || empty($_SESSION['rc1']) || empty($_SESSION['rc2']))
{
	die("No external access to file");
}

if(!$cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
	die($ccms['lang']['auth']['featnotallowed']);
}



$do = getGETparam4IdOrNumber('do');
$to_lang = getGETparam4IdOrNumber('to_lang');
if (!empty($_COOKIE['googtrans']))
{
	$to_lang = filterParam4IdOrNumber(substr($_COOKIE['googtrans'], strrpos($_COOKIE['googtrans'], '/') + 1));
}
if (empty($to_lang)) $to_lang = 'xx';
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');





function load_lang_file($language)
{
	$ccms = array();
	$lang_file = BASE_PATH . '/lib/languages/' . $language . '.inc.php';
	if (is_file($lang_file))
	{
		// include($lang_file);
		$str = file_get_contents($lang_file);
		$str = preg_replace('/\$ccms[^\n]+\/\* BABELFISH \*\/[^\n]+/u', '', $str);
		eval('?>' . $str . '<?php');
	}

	return $ccms;
}


function collect_translations($language)
{
	/*
	We collect all translation strings by first loading the default (English) and then the language file itself.

	This way, we'll be sure to have all language strings (as the English one is assumed to be complete at all
	times) while any gaps in the language X file are filled by those English ones.

	Since we are within function scope, the global $ccms[] is not accessible from here, which is actually GOOD.

	Because we trick the loading by include()ing both the english and the selected language file and then
	return the LOCAL $ccms[] array as a result, thus staying completely independent of the global $ccms[] and
	its language strings!
	*/

		// English indexes are our 'master base':
	$ccms = load_lang_file('en');

	$i18n = load_lang_file($language);
	// now ONLY override entries which EXIST in 'en'; anything else is superfluous/outdated in the language file anyway and will corrupt our index-counting later on
	foreach($i18n as $key => $value)
	{
		if (!empty($value) && array_key_exists($key, $ccms))
		{
			// override value now!
			$ccms[$key] = $value;
		}
	}

	return $ccms;
}


function show_all_translations($lang_arr, $item_prefix = '$ccms[\'lang\']', $index = 0)
{
	foreach ($lang_arr as $key => $value)
	{
		$index++;
		if (is_array($value))
		{
			$index = show_all_translations($value, $item_prefix."['".$key."']", $index - 1);
		}
		else
		{
			$item = $item_prefix . "['" . $key . "']";

			echo '<tr' . ($index % 2 == 0 ? ' style="background-color:#f0f0f0;"' : '') . '><td class="nowrap" style="font-size:.7em; vertical-align: baseline;">' . $item . "</td>\n"
				. '<td style="vertical-align: baseline;"><span class="sprite-hover liveedit" id="'.md5($item).'" rel="i18n_string">'
				. $value
				. '</span></td>' . "</tr>\n";
		}
	}
	return $index;
}

function get_i18n_ccms_key_as_code(&$entry, $lang_arr, $wanted_index, $item_prefix = '$ccms[\'lang\']', $index = 0)
{
	foreach ($lang_arr as $key => $value)
	{
		$index++;
		if (is_array($value))
		{
			$index = get_i18n_ccms_key_as_code($entry, $value, $wanted_index, $item_prefix."['".$key."']", $index - 1);
			if ($index < 0)
				break;
		}
		else
		{
			$item = $item_prefix . "['" . $key . "']";

			if ($wanted_index == md5($item))
			{
				$entry = $item;
				return -1;
			}
		}
	}
	return $index;
}


function get_i18n_ccms_value(&$entry, $lang_arr, $wanted_index, $item_prefix = '$ccms[\'lang\']', $index = 0)
{
	foreach ($lang_arr as $key => $value)
	{
		$index++;
		if (is_array($value))
		{
			$index = get_i18n_ccms_value($entry, $value, $wanted_index, $item_prefix."['".$key."']", $index - 1);
			if ($index < 0)
				break;
		}
		else
		{
			$item = $item_prefix . "['" . $key . "']";

			if ($wanted_index == md5($item))
			{
				$entry = $value;
				return -1;
			}
		}
	}
	return $index;
}


/*
WARNING: contrary to regular PHP array_diff_assoc, this one EDITS $a!
*/
function array_diff_assoc_recursive(&$a, &$b)
{
	// only keep those entries in $a which actually differ from the ones in $b: those are ready & done translations!
	foreach($b as $key => $value)
	{
		if (is_array($value))
		{
			if (array_key_exists($key, $a))
			{
				array_diff_assoc_recursive($a[$key], $value);
			}
		}
		else
		{
			if (array_key_exists($key, $a) && $a[$key] == $value)
			{
				$a[$key] = null;
			}
		}
	}
}





if ($do == 'update')
{
	$error = true;

	$content = getPOSTparam4RAWHTML('content'); // must be RAW CONTENT
	if (!empty($content))
	{
		$to_lang_arr = collect_translations($to_lang);
		$i18n_arr = collect_translations('en');
		array_diff_assoc_recursive($to_lang_arr, $i18n_arr);

		// process content as c&p'd by the translator/user
		//$content = file_get_contents(BASE_PATH . '/media/files/trial.html');

		// clean the content:
		$content = preg_replace('/ style=".*?"/u', '', $content);

		// strip anything outside the translation table.
		if (preg_match('/ id="i18n-list".*?>(.*)<\/table>/su', $content, $matches))
		{
			$content = $matches[1];

			// extract the translated content:
			if ($mcount = preg_match_all('/<tr.*?<td>(<span id="[0-9a-fA-F]+" .*?)<\/td>/su', $content, $matches))
			{
				$i18n_units = array();

				$content = 'count = '.$mcount . "\n\n\n";
				for ($i = 0; $i < $mcount; $i++)
				{
					$idx = 0;
					$entry = $matches[1][$i];
					$orig_str = $entry;
					if (preg_match('/<span id="([0-9a-fA-F]+)".*?>(.*)<\/span>/su', $entry, $ematch))
					{
						$idx = $ematch[1];
						$entry = $ematch[2];

						// strip <span>s
						$entry = preg_replace('/<[\/]?span>/u', '', $entry);
						// replace newlines, etc. by space:
						$entry = preg_replace('/\s+/u', ' ', $entry);

						$content .= "\n".$idx.' = '.$entry;

						// make it UTF-8 data; no more HTML entities in there.
						$entry = html_entity_decode($entry, ENT_QUOTES, 'UTF-8');

						// and last bits of cleanup
						$entry = preg_replace('/<br\s*\/>/u', '<br>', $entry);
						$entry = preg_replace('/\s*:\s*:\s*/u', ' :: ', $entry);
						$entry = preg_replace('/\s*：\s*：\s*/u', ' :: ', $entry);
						$entry = trim($entry);

						$entry_phpcode = '---';
						get_i18n_ccms_key_as_code($entry_phpcode, $i18n_arr['lang'], $idx);

						$original_value = '';
						get_i18n_ccms_value($original_value, $to_lang_arr['lang'], $idx);

						$english_value = '';
						get_i18n_ccms_value($english_value, $i18n_arr['lang'], $idx);


						if (!empty($original_value))
						{
							$i18n_units[$entry_phpcode] = '"'.str_replace('"', "'", $original_value).'";';
						}
						else if ($english_value != $entry)
						{
							// actual translation has happened!
							$i18n_units[$entry_phpcode] = '/* BABELFISH */ "'.str_replace('"', "'", $entry).'";';
						}
						else
						{
							// no translation whatsoever
							$i18n_units[$entry_phpcode] = '"'.str_replace('"', "'", $english_value).'";';
						}

						//$content .= "\n".$entry_phpcode.' = /* BABELFISH */ "'.$entry.'";';
						//$content .= "\n:::".htmlspecialchars($orig_str, ENT_COMPAT, 'UTF-8');
					}
				}
				//file_put_contents(BASE_PATH . '/media/files/trial.html', $content);

				// see which items already exist in the file
				$lang_file = BASE_PATH . '/lib/languages/' . $to_lang . '.inc.php';
				$orig_content = '';
				if (is_file($lang_file))
				{
					$orig_content = file_get_contents($lang_file);
					$orig_content = preg_replace('/<\?php/', '', $orig_content);
					$orig_content = preg_replace('/\?>/', '', $orig_content);
				}

				$remaining = $i18n_units;
				foreach($i18n_units as $key => $value)
				{
					$pos = strpos($orig_content, $key);
					if ($pos > 0)
					{
						$nlpos = strpos($orig_content, "\n", $pos);
						$orig_content = substr_replace($orig_content, $key . ' = ' . $value, $pos, $nlpos - $pos);
						$remaining[$key] = null;
					}
				}
				$orig_content .= "\n\n/* ADDITION */\n\n";
				foreach($remaining as $key => $value)
				{
					if (!empty($value))
					{
						$orig_content .= "\n" . $key . ' = ' . $value;
					}
				}

				file_put_contents(BASE_PATH . '/media/files/'.$to_lang.'.inc.php', "<?php\n" . $orig_content . "\n?>");
				if (0)
				{
					echo "<html><body><pre>" . htmlspecialchars($orig_content, ENT_COMPAT, 'UTF-8');
				}





				// for comparison:
				//$to_lang = 'en';
				$lang_file = BASE_PATH . '/lib/languages/' . 'en' . '.inc.php';
				$orig_content = '';
				if (is_file($lang_file))
				{
					$orig_content = file_get_contents($lang_file);
					$orig_content = preg_replace('/<\?php/', '', $orig_content);
					$orig_content = preg_replace('/\?>/', '', $orig_content);
				}

				$remaining = $i18n_units;
				foreach($i18n_units as $key => $value)
				{
					$pos = strpos($orig_content, $key);
					if ($pos > 0)
					{
						$nlpos = strpos($orig_content, "\n", $pos);
						$orig_content = substr_replace($orig_content, $key . ' = ' . $value, $pos, $nlpos - $pos);
						$remaining[$key] = null;
					}
				}
				$orig_content .= "\n\n/* ADDITION */\n\n";
				foreach($remaining as $key => $value)
				{
					if (!empty($value))
					{
						$orig_content .= "\n" . $key . ' = ' . $value;
					}
				}

				@mkdir(BASE_PATH . '/media/files/lang-babel');
				file_put_contents(BASE_PATH . '/media/files/lang-babel/'.$to_lang.'.inc.php', "<?php\n" . $orig_content . "\n?>");
				$status_message = "The augmented translation data has been written to the file " . BASE_PATH . '/lib/languages/' . 'en' . '.inc.php';
				$status = 'notice';
				$error = false;
			}
		}
	}

	if ($error)
	{
		echo "boom!";
		if (0)
		{
			dump_request_to_logfile();
		}
		die();
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Translation module</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<!-- File uploader styles -->
	<link rel="stylesheet" media="all" type="text/css" href="../../../../lib/includes/js/fancyupload/Css/FileManager.css,Additions.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/ie.css" />
	<![endif]-->
</head>
<body>
<div class="module" id="translation-assist">
	<?php
	// (!) Only administrators can change these values
	if($_SESSION['ccms_userLevel']>=4)
	{
	?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?do=update" method="post" accept-charset="utf-8">

		<div id="google_translate_element"></div>
	<?php
	}
	?>
		<div class="center-text <?php echo $status; ?>">
			<?php
			if(!empty($status_message))
			{
				echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message;
				if ($status != 'error')
				{
					echo '<br/><span class="ss_sprite_16 ss_exclamation">&#160;</span>'.$ccms['lang']['backend']['must_refresh'];
				}
				echo '</p>';
			}
			?>
		</div>

		<h2><?php echo $ccms['lang']['translation']['header']; ?></h2>
	<?php

	// (!) Only administrators can change these values
	if($_SESSION['ccms_userLevel']>=4)
	{
	?>
		<p><?php echo $ccms['lang']['translation']['explain']; ?></p>
		<table border="0" cellspacing="0" cellpadding="0" name="i18n-list" id="i18n-list">
			<tr>
				<th class="span-4 nowrap"><em>Item</em></th>
				<th class="span-21">Text</th>
			</tr>
			<?php
			$i18n_arr = collect_translations('en'); /* $to_lang */
			show_all_translations($i18n_arr['lang']);
			?>
		</table>
		<hr />
		<p>Copy and paste the entire page into the edit box below; we will sort it out...</p>

		<textarea id="content" name="content" style="height:400px;width:100%;color:#000;">---copy&amp;paste your stuff in here!---</textarea>

		<div class="right">
			<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['forms']['savebutton'];?></button>
			<a class="button" href=="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
		</div>
	</form>
	<?php
	}
	else
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
	?>

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
<script type="text/javascript">

function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-tran_ccms');
	}
	return false;
}

function googleTranslateElementInit()
{
	new google.translate.TranslateElement({
			pageLanguage: 'en', /* <?php echo $to_lang; ?> */
			layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
		}, 'google_translate_element');
}


<?php
$js_files = array();
$js_files[] = '../../../../lib/includes/js/the_goto_guy.js';
$js_files[] = 'http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
$js_files[] = '../../../../lib/includes/js/mootools-core.js,mootools-more.js';
$js_files = array_merge($js_files, generateJS4TinyMCEinit(0, 'content'));

$driver_code = generateJS4TinyMCEinit(2, 'content');

$starter_code = generateJS4TinyMCEinit(1, 'content');

echo generateJS4lazyloadDriver($js_files, $driver_code, $starter_code);
?>
</script>
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
