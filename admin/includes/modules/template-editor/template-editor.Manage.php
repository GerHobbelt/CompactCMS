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



$do = getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');


// Set the default template
$dir_temp = BASE_PATH . "/lib/templates/";
$get_temp = getGETparam4FullFilePath('template', $template[0].'.tpl.html');
$chstatus = is_writable_ex($dir_temp.$get_temp); // @dev: to test the error feedback on read-only on Win+UNIX: add '|| 1' here.
$temp_extension = strtolower(substr($get_temp, strrpos($get_temp, '.') + 1));

// Check for filename
if(!empty($get_temp))
{
	if(@fopen($dir_temp.$get_temp, "r"))
	{
		$handle = fopen($dir_temp.$get_temp, "r");
		// PHP5+ Feature
		$contents = stream_get_contents($handle);
		if (0)
		{
			// PHP4 Compatibility
			$flen = filesize($dir_temp.$get_temp);
			if ($flen > 0)
			{
				$contents = @fread($handle, $flen);
			}
		}
		fclose($handle);
		$contents = str_replace("<br />", "<br>", $contents);
	}
}


if(!$perm->is_level_okay('manageTemplate', $_SESSION['ccms_userLevel']))
{
	$chstatus = false; // templates are viewable but NOT WRITABLE when user doesn't have permission to manage these.
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Template Editing module</title>
		<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="../../../../admin/img/styles/ie.css" />
		<![endif]-->
	</head>
<body>
	<div class="module" id="template-editor">
		<?php
		if(!$chstatus)
		{
		?>
		<div class="span-25 last center-text error">
			<p class="ss_has_sprite"><span class="ss_sprite_16 ss_error">&#160;</span><?php echo $ccms['lang']['template']['nowrite']; ?></p>
		</div>
		<?php
		}
		?>
		<div class="span-15 clear">
			<h1 class="editor"><?php echo $ccms['lang']['template']['manage']; ?></h1>
		</div>

		<div class="span-10 right last">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" id="changeTmp" method="get" class="right" accept-charset="utf-8">
				<label for="template"><?php echo $ccms['lang']['backend']['template'];?></label>
				<select class="text" onChange="document.getElementById('changeTmp').submit();" id="template" name="template">
					<?php
					for ($x = 0; $x < count($template); $x++)
					{
					?>
						<optgroup label="<?php echo ucfirst($template[$x]); ?>">
							<option <?php echo ($get_temp == $template[$x].'.tpl.html') ? 'selected="selected"' : ''; ?> value="<?php echo $template[$x]; ?>.tpl.html"><?php echo ucfirst($template[$x]).': '.strtolower($ccms['lang']['backend']['template']); ?></option>
							<?php

							// Get CSS and other text-editable files which are part of the engine
							$cssfiles = array();
							if ($handle = opendir($dir_temp.$template[$x].'/'))
							{
								while (false !== ($file = readdir($handle)))
								{
									if ($file != "." && $file != "..")
									{
										switch (strtolower(substr($file, strrpos($file, '.') + 1)))
										{
										case 'css':
										case 'js':
										case 'php':
										case 'html':
										case 'txt':
											$cssfiles[$x][] = $file;
											break;

										default:
											// don't list image files and such
											break;
										}
									}
								}
								closedir($handle);
							}

							foreach ($cssfiles[$x] as $css)
							{
							?>
								<option <?php echo ($get_temp == $template[$x].'/'.$css) ? 'selected="selected"' : ''; ?> value="<?php echo $template[$x].'/'.$css; ?>"><?php echo ucfirst($template[$x]).': '.$css; ?></option>
							<?php
							}
							?>
						</optgroup>
					<?php
					}
					?>
				</select>
			</form>
		</div>
		<hr class="space span-25 clear"/>

		<?php
		/*
		??? ALWAYS saying 'settings saved' instead of the attached message in the old code? Must've been a bug...

		Changed to mimic the layout in the other files...
		*/
		?>
		<div class="center-text <?php echo $status; ?> span-25 clear">
			<?php
			if(!empty($status_message))
			{
				echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>';
			}
			?>
		</div>

		<form action="template-editor.Process.php?template=<?php echo $get_temp; ?>&action=save-template" method="post" accept-charset="utf-8">
			<textarea id="content" name="content"><?php echo htmlspecialchars(trim($contents), ENT_COMPAT, 'UTF-8'); ?></textarea>

			<input type="hidden" name="template" value="<?php echo $get_temp; ?>" id="template" />
			<div class="right">
				<?php
				if($chstatus)
				{
				?>
					<button type="submit" name="do" id="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['editor']['savebtn']; ?></button>
				<?php
				}
				?>
				<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
			</div>
		</form>

<?php
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'])
{
?>
	<textarea id="jslog" class="log span-25 last clear" readonly="readonly">
	</textarea>
<?php
}
?>

	</div>
<?php

// TODO: call edit_area_compressor.php only from the combiner: combine.inc.php when constructing the edit_area.js file for the first time.

?>
		<script type="text/javascript">


function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-tmp_ccms');
	}
	return false;
}


<?php
$js_files = array();
$js_files[] = '../../../../lib/includes/js/the_goto_guy.js';
if ($cfg['USE_JS_DEVELOPMENT_SOURCES'])
{
	$js_files[] = '../../../../lib/includes/js/edit_area/edit_area_full.js';
}
else
{
	$js_files[] = '../../../../lib/includes/js/edit_area/edit_area_full.js';
}

$eaLanguage = $cfg['editarea_language'];
$driver_code = <<<EOT
		// initialisation

		// make sure we only specify a /supported/ syntax; if we spec something else, edit_area will NOT show up!
		var supported_syntaxes = ',' + editAreaLoader.default_settings.syntax_selection_allow + ',';
		var desired_syntax = '$temp_extension';
		desired_syntax = (supported_syntaxes.indexOf(',' + desired_syntax + ',') >= 0 ? desired_syntax : "");

		editAreaLoader.init(
			{
				id: "content",
				start_highlight: true,
				allow_resize: 'both',
				allow_toggle: true,
				word_wrap: true,
				language: "$eaLanguage",
				syntax: desired_syntax
			});
		/*
		for (syn in editAreaLoader.load_syntax)
		{
			alert("syntax: " + syn);
		}
		*/
EOT;

echo generateJS4lazyloadDriver($js_files, $driver_code);
?>
</script>
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>