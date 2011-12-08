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



if (empty($cfg['MT_FileManager_language']) || empty($cfg['tinymce_language']))
{
	die("INTERNAL LANGUAGE INIT ERROR!");
}



$do = getGETparam4IdOrNumber('do');

// Open recordset for specified user
$newsID = getGETparam4Number('newsID');
$page_id = getGETparam4IdOrNumber('page_id');



if (!(checkAuth() && $perm->is_level_okay('manageModNews', $_SESSION['ccms_userLevel'])))
{
	die("No external access to file");
}
if (!$page_id)
{
	die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
}

if($newsID && $page_id)
{
	$news = $db->QuerySingleRow("SELECT * FROM `".$cfg['db_prefix']."modnews` m LEFT JOIN `".$cfg['db_prefix']."users` u ON m.userID = u.userID WHERE newsID = " . MySQL::SQLValue($newsID, MySQL::SQLVALUE_NUMBER) . " AND page_id = " . MySQL::SQLValue($page_id, MySQL::SQLVALUE_NUMBER));
	if (!$news) $db->Kill();
}

$textarea4teaser_id = str2variablename('newstease_' . $page_id . (!empty($newsID) ? '_' . $newsID : ''));
$textarea4article_id = str2variablename('newsarticle_' . $page_id . (!empty($newsID) ? '_' . $newsID : ''));


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>News module</title>

	<link rel="stylesheet" type="text/css" href="../../../admin/img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />

	<!-- File uploader styles -->
	<link rel="stylesheet" media="all" type="text/css" href="../../../lib/includes/js/mootools-filemanager/Assets/js/milkbox/css/milkbox.css" />
	<link rel="stylesheet" media="all" type="text/css" href="../../../lib/includes/js/mootools-filemanager/Assets/Css/FileManager.css,Additions.css" />

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
							<input type="text" class="minLength:3 text span-25" name="newsTitle" value="<?php echo (isset($news) ? $news->newsTitle : null); ?>" id="newsTitle"/>
						</td>
						<td>
							<select name="newsAuthor" class="required text span-25" id="newsAuthor">
								<?php
								$userlist = $db->SelectObjects($cfg['db_prefix'].'users');
								if ($userlist === false) $db->Kill();
								foreach($userlist as $user)
								{
								?>
									<option value="<?php echo rm0lead($user->userID); ?>" <?php echo (isset($news) && $user->userID == $news->userID ? 'selected="selected"' : null); ?>><?php echo $user->userFirst.' '.$user->userLast; ?></option>
								<?php
								}
								?>
							</select>
						</td>
						<td class="nowrap">
							<input type="text" class="required text" name="newsModified" value="<?php echo (isset($news) ? date('Y-m-d G:i', strtotime($news->newsModified)) : date('Y-m-d G:i')); ?>" id="newsModified">
						</td>
						<td>
							<input type="checkbox" name="newsPublished" <?php echo (isset($news) && $news->newsPublished ? 'checked="checked"' : null); ?>  value="1" id="newsPublished" />
						</td>
					</tr>
				</table>
			</div>
				<label class="clear" for="<?php echo $textarea4teaser_id; ?>"><?php echo $ccms['lang']['news']['teaser']; ?></label>
				<textarea name="newsTeaser" id="<?php echo $textarea4teaser_id; ?>" class="minLength:3 text" rows="4" cols="40" style="width: 100%"><?php
					echo (isset($news) ? $news->newsTeaser : null);
				?></textarea>

				<label for="<?php echo $textarea4article_id; ?>"><?php echo $ccms['lang']['news']['contents']; ?></label>
				<textarea name="newsContent" id="<?php echo $textarea4article_id; ?>" class="text" rows="8" cols="40" style="width: 100%"><?php
					echo (isset($news) ? $news->newsContent : null);
				?></textarea>
				<hr class="space"/>
				<input type="hidden" name="newsID" value="<?php echo $newsID; ?>" id="newsID" />
				<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" id="page_id" />
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
					<a class="button" href="./news.Manage.php?page_id=<?php echo $page_id; ?>" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
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


<?php
$ccms['JS.required_files']['{%rootdir%}lib/includes/js/the_goto_guy.js'] = count($ccms['JS.required_files']); // make sure the value is a nicely unique sequence number: count() is good for that.
$ccms['JS.required_files']['{%rootdir%}lib/includes/js/mootools-core.js'] = count($ccms['JS.required_files']);
$ccms['JS.required_files']['{%rootdir%}lib/includes/js/mootools-more.js'] = count($ccms['JS.required_files']);

$js_files = array();
$js_files[] = $cfg['rootdir'] . 'lib/includes/js/the_goto_guy.js';
$js_files[] = $cfg['rootdir'] . 'lib/includes/js/mootools-core.js,mootools-more.js';

$mce_options = array(
	// [0] carries the generic settings:
	array(
		$textarea4teaser_id => array(
			'theme' => 'simple'
			)
		)
	);
		
$MCEcodegen = new tinyMCEcodeGen($textarea4teaser_id . ',' . $textarea4article_id, $mce_options);

$js_files = array_merge($js_files, $MCEcodegen->get_JSheaderfiles());

$starter_code = $MCEcodegen->genStarterCode();

$driver_code = $MCEcodegen->genDriverCode();

$extra_functions_code = $MCEcodegen->genExtraFunctionsCode();

$driver_code .= <<<EOT42

		/* Check form and post */
		new FormValidator($('newsForm'),
			{
				onFormValidate: function(passed, form, event)
				{
					event.stop();
					if(passed)
						form.submit();
				}
			});
EOT42;

echo generateJS4lazyloadDriver($js_files, $driver_code, $starter_code, $extra_functions_code);
?>
</script>
<script type="text/javascript" src="../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
