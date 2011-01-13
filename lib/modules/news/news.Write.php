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



if (empty($cfg['fancyupload_language']) || empty($cfg['tinymce_language']))
{
	die("INTERNAL LANGUAGE INIT ERROR!");
}



$do	= getGETparam4IdOrNumber('do');

// Open recordset for specified user
$newsID = getGETparam4Number('newsID');
$pageID = getGETparam4IdOrNumber('pageID');

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");


if (!(checkAuth() && $perm['manageModNews']>0 && $_SESSION['ccms_userLevel'] >= $perm['manageModNews']))
{
	die("No external access to file");
}
if (empty($pageID))
{
	die($ccms['lang']['system']['error_forged']);
}

if($newsID != null)
{
	$news = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."modnews` m LEFT JOIN `".$cfg['db_prefix']."users` u ON m.userID=u.userID WHERE newsID = " . MySQL::SQLValue($newsID, MySQL::SQLVALUE_NUMBER) . " AND pageID=" . MySQL::SQLValue($pageID, MySQL::SQLVALUE_TEXT));
	if (!$news) $db->Kill();
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>News module</title>

	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />

	<!-- File uploader styles -->
	<link rel="stylesheet" media="all" type="text/css" href="../../../lib/includes/js/fancyupload/Css/FileManager.css,Additions.css" />

	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/ie.css" />
	<![endif]-->

</head>
<body>
	<div class="module" id="news-writer">
		<div id="status">
			<!-- spinner -->
		</div>

		<h2><?php echo $ccms['lang']['news']['writenews']; ?></h2>
		<div class="span-25 last">
			<form action="./news.Process.php?action=add-edit-news" id="newsForm" method="post" accept-charset="utf-8">
			<div class="table_inside">
				<table cellspacing="0" cellpadding="0">
					<tr>
						<th class="span-10">
							<label for="newsTitle"><?php echo $ccms['lang']['news']['title']; ?></label>
						</th>
						<th class="span-7">
							<label for="newsAuthor"><?php echo $ccms['lang']['news']['author']; ?></label>
						</th>
						<th class="span-5">
							<label for="newsModified"><?php echo $ccms['lang']['news']['date']; ?></label>
						</th>
						<th class="span-2 last">
							<label for="newsPublished"><?php echo $ccms['lang']['news']['published']; ?></label>
						</th>
					</tr>
					<tr>
						<td>
							<input type="text" class="minLength:3 text span-25" name="newsTitle" value="<?php echo (isset($news)?$news->newsTitle:null);?>" id="newsTitle"/>
						</td>
						<td>
							<select name="newsAuthor" class="required text span-25" id="newsAuthor">
								<?php
								$userlist = $db->SelectObjects($cfg['db_prefix'].'users');
								if ($userlist === false) $db->Kill();
								foreach($userlist as $user)
								{
								?>
									<option value="<?php echo rm0lead($user->userID); ?>" <?php echo (isset($news) && $user->userID==$news->userID ? 'selected="selected"' : null); ?>><?php echo $user->userFirst.' '.$user->userLast; ?></option>
								<?php
								}
								?>
							</select>
						</td>
						<td class="nowrap">
							<input type="text" class="required text" name="newsModified" value="<?php echo (isset($news) ? date('Y-m-d G:i',strtotime($news->newsModified)) : date('Y-m-d G:i')); ?>" id="newsModified">
						</td>
						<td>
							<input type="checkbox" name="newsPublished" <?php echo (isset($news) && $news->newsPublished ? 'checked="checked"' : null); ?>  value="1" id="newsPublished" />
						</td>
					</tr>
				</table>
			</div>
				<label class="clear" for="newsTeaser"><?php echo $ccms['lang']['news']['teaser']; ?></label>
				<textarea name="newsTeaser" id="newsTeaser" class="minLength:3 text span-25" rows="4" cols="40"><?php
					echo (isset($news) ? $news->newsTeaser : null);
				?></textarea>

				<label for="newsContent"><?php echo $ccms['lang']['news']['contents']; ?></label>
				<textarea name="newsContent" id="newsContent" class="text span-25" rows="8" cols="40"><?php
					echo (isset($news) ? $news->newsContent : null);
				?></textarea>
				<hr class="space"/>
				<input type="hidden" name="newsID" value="<?php echo $newsID; ?>" id="newsID" />
				<input type="hidden" name="pageID" value="<?php echo $pageID; ?>" id="pageID" />
				<div class="right">
					<button type="submit" name="submitNews" value="<?php echo $newsID; ?>">
						<?php
						if(empty($newsID))
						{
						?>
							<span class="ss_sprite_16 ss_newspaper_add">&#160;</span><?php echo $ccms['lang']['forms']['createbutton']; ?></button>
						<?php
						}
						else
						{
						?>
							<span class="ss_sprite_16 ss_newspaper_go">&#160;</span><?php echo $ccms['lang']['forms']['modifybutton']; ?></button>
						<?php
						}
						?>
					<a class="button" href="./news.Manage.php?pageID=<?php echo $pageID; ?>" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
				</div>
			</form>
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
	<script type="text/javascript">

function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url(null, null);
	}
	return false;
}



var jsLogEl = document.getElementById('jslog');
var js = [
	'../../includes/js/the_goto_guy.js',
	'../../../lib/includes/js/tiny_mce/tiny_mce_dev.js',
	'../../includes/js/mootools-core.js,mootools-more.js',
	'../../../lib/includes/js/fancyupload/dummy.js,Source/FileManager.js,<?php
	if ($cfg['fancyupload_language'] != 'en')
	{
		echo 'Language/Language.en.js,';
	}
	?>Language/Language.<?php echo $cfg['fancyupload_language']; ?>.js,Source/Additions.js,Source/Uploader/Fx.ProgressBar.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader.js,Source/FileManager.TinyMCE.js'
	];

function jsComplete(user_obj, lazy_obj)
{
    if (lazy_obj.todo_count)
	{
		/* nested invocation of LazyLoad added one or more sets to the load queue */
		jslog('Another set of JS files is going to be loaded next! Todo count: ' + lazy_obj.todo_count + ', Next up: '+ lazy_obj.load_queue['js'][0].urls);
		return;
	}
	else
	{
		jslog('All JS has been loaded!');
	}

	// window.addEvent('domready',function()
	//{
		tinyMCE.init(
			{
				mode: 'exact',
				elements: 'newsContent',
				theme: 'advanced',
				<?php echo 'language: "'.$cfg['tinymce_language'].'",'; ?>
				skin: 'o2k7',
				skin_variant: 'silver',
				plugins: 'table,advlink,advimage,media,inlinepopups,print,fullscreen,paste,searchreplace,visualchars,spellchecker,tinyautosave',
				theme_advanced_toolbar_location: 'top',
				theme_advanced_buttons1: 'fullscreen,tinyautosave,print,formatselect,fontselect,fontsizeselect,|,justifyleft,justifycenter,justifyright,justifyfull,|,sub,sup,|,spellchecker,link,unlink,anchor,hr,image,media,|,charmap,code',
				theme_advanced_buttons2: 'undo,redo,cleanup,|,bold,italic,underline,strikethrough,|,forecolor,backcolor,removeformat,|,cut,copy,paste,replace,|,bullist,numlist,outdent,indent,|,tablecontrols',
				theme_advanced_buttons3: '',
				theme_advanced_toolbar_align: 'left',
				theme_advanced_statusbar_location: 'bottom',
				dialog_type: 'modal',
				paste_auto_cleanup_on_paste:true,
				theme_advanced_resizing:true,
				relative_urls:true,
				convert_urls:false,
				remove_script_host:true,
				document_base_url: '<?php echo $cfg['rootdir']; ?>',
				<?php
				if($cfg['iframe'])
				{
				?>
				extended_valid_elements: 'iframe[align<bottom?left?middle?right?top|class|frameborder|height|id|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style|title|width]',
				<?php
				}
				?>
 				spellchecker_languages: '+English=en,Dutch=nl,German=de,Spanish=es,French=fr,Italian=it,Russian=ru',
				file_browser_callback:FileManager.TinyMCE(
					function(type)
					{
						return { /* ! '{' MUST be on same line as 'return' otherwise JS will see the newline as end-of-statement! */
							url: '<?php echo $cfg['rootdir']; ?>lib/includes/js/fancyupload/' + (type=='image' ? 'selectImage.php' : 'manager.php'),
							baseURL: '<?php echo $cfg['rootdir']; ?>',
							assetBasePath: '<?php echo $cfg['rootdir']; ?>lib/includes/js/fancyupload/Assets',
							<?php echo 'language: "' . $cfg['fancyupload_language'] . '",'; ?>
							selectable: true,
							uploadAuthData:
							{
								session:'ccms_userLevel',
								sid:'<?php echo session_id(); ?>'
							}
						};
					})
			});
			



		/* Check form and post */
		new FormValidator($('newsForm'),
			{
				onFormValidate:function(passed,form,event)
				{
					event.stop();
					if(passed)
						form.submit();
				}
			});
	//});
}


function jslog(message)
{
	if (jsLogEl)
	{
		jsLogEl.value += "[" + (new Date()).toTimeString() + "] " + message + "\r\n";
	}
}


/* the magic function which will start it all, thanks to the augmented lazyload.js: */
function ccms_lazyload_setup_GHO()
{
	jslog('loading JS (sequential calls)');


	/*
	 * when loading the flattened tinyMCE JS, this is (almost) identical to invoking the lazyload-done hook 'jsComplete()';
	 * however, tinyMCE 'dev' sources (tiny_mce_dev.js) employs its own lazyload-similar system, so having loaded /that/
	 * file does /NOT/ mean that the tinyMCE editor has been loaded completely, on the contrary!
	 */
	tinyMCEPreInit = {
		  suffix: '_src' /* '_src' when you load the _src or _dev version, '' when you want to load the stripped+minified version of tinyMCE plugins */
		, base: <?php echo '"' . $cfg['rootdir'] . 'lib/includes/js/tiny_mce"'; ?>
		, query: 'load_callback=jsComplete' /* specify a URL query string, properly urlescaped, to pass special arguments to tinyMCE, e.g. 'api=jquery'; must have an 'adapter' for that one, 'debug=' to add tinyMCE firebug-lite debugging code */
	};
	
	LazyLoad.js(js, jsComplete);
}
</script>
<script type="text/javascript" src="../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
