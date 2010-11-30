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

// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

// Get all pages
$pages = $db->QueryArray("SELECT page_id,urlpage,user_ids FROM ".$cfg['db_prefix'].'pages');
if (!is_array($pages)) $db->Kill();

// Get all users
$users = $db->QueryArray("SELECT userID,userName,userFirst,userLast,userEmail,userLevel FROM ".$cfg['db_prefix'].'users');
if (!is_array($users)) $db->Kill();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Page-owners</title>
	<link rel="stylesheet" type="text/css" href="../../../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../../../img/styles/ie.css" />
	<![endif]-->
</head>
<body>
	<div class="module" id="content-owners">

	<div class="center-text <?php echo $status; ?>">
		<?php
		if(!empty($status_message))
		{
			echo '<p class="ss_has_sprite"><span class="ss_sprite_16 '.($status == 'notice' ? 'ss_accept' : 'ss_error').'">&#160;</span>'.$status_message.'</p>';
		}
		?>
	</div>

	<h2><span class="ss_sprite_16 ss_group_gear">&#160;</span><?php echo $ccms['lang']['owners']['header']; ?></h2>
	<p class="left-text"><?php echo $ccms['lang']['owners']['explain']; ?></p>
	<form action="content-owners.Process.php" method="post" accept-charset="utf-8">
	<div class="table_inside">
		<table cellspacing="0" cellpadding="0">
		<tr>
			<th class="span-4"><span class="ss_sprite_16 ss_arrow_down">&#160;</span><?php echo $ccms['lang']['owners']['pages']; ?> \ <span class="ss_sprite_16 ss_arrow_right">&#160;</span><?php echo $ccms['lang']['owners']['users']; ?></th>
			<?php
			for ($ar1=0; $ar1<count($users); $ar1++)
			{
			?>
				<th class="center-text span-2">
					<span class="ss_sprite_16 ss_user_<?php echo ($users[$ar1]['userLevel']>=4?'suit':'green'); ?>">&#160;</span><?php echo $users[$ar1]['userFirst'].' '.substr($users[$ar1]['userLast'],0,1); ?>.</span>
				</th>
			<?php
			}
			?>
		</tr>
		<?php
		for ($i = 0; $i < count($pages); $i++)
		{
			$users_owning_page = explode('||', $pages[$i]['user_ids']);

		?>
			<tr class="<?php echo ($i % 2 != 1 ? 'altrgb' : 'regrgb'); ?>">
			<th class="span-4 pagename">
				<span class="ss_sprite_16 ss_page_white_world">&#160;</span><?php echo $pages[$i]['urlpage']; ?>.html
			</td>
			<?php
			for ($ar2=0; $ar2<count($users); $ar2++)
			{
			?>
				<td class="hover center-text">
					<label>
					<input type="checkbox" name="owner[]"
					<?php
					/*
					* This code is a security issue of another kind: user
					* ownership settings will OVERLAP for certain users
					* when their IDs are substrings, e.g. user #1 will
					* have everything user #11 has as well.
					*
					*   if(strstr($pages[$i]['user_ids'], $users[$ar2]['userID'])!==false)
					*
					* Hence the code is replaced with an explode plus
					* array scan. Another way to solve would be padding
					* the rights string with leading and trailing '||'
					* and then regex matching against "/||$userid||/".
					*/
					if (in_array(rm0lead($users[$ar2]['userID']), $users_owning_page))
					{
						echo 'checked="checked"';
					}
					?> value="<?php echo rm0lead($users[$ar2]['userID']).'||'.rm0lead($pages[$i]['page_id']);?>" id="<?php echo $i.'_'.$ar2;?>" />
					</label>
				</td>
			<?php
			}
			?>
			</tr>
		<?php
		}
		?>
		</tr>
		</table>
	</div>
	<div class="right">
		<button type="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['editor']['savebtn']; ?></button>
		<a class="button" href="../../../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
	</div>
	</form>

	<textarea id="jslog" class="log" readonly="readonly">
	</textarea>

	<!-- Gets replaced with TinyMCE, remember HTML in a textarea should be encoded -->
	<textarea id="elm1" name="elm1" rows="15" cols="80" style="width: 80%">
		&lt;p&gt;
			This is some example text that you can edit inside the &lt;strong&gt;TinyMCE editor&lt;/strong&gt;.
		&lt;/p&gt;
		&lt;p&gt;
		Nam nisi elit, cursus in rhoncus sit amet, pulvinar laoreet leo. Nam sed lectus quam, ut sagittis tellus. Quisque dignissim mauris a augue rutrum tempor. Donec vitae purus nec massa vestibulum ornare sit amet id tellus. Nunc quam mauris, fermentum nec lacinia eget, sollicitudin nec ante. Aliquam molestie volutpat dapibus. Nunc interdum viverra sodales. Morbi laoreet pulvinar gravida. Quisque ut turpis sagittis nunc accumsan vehicula. Duis elementum congue ultrices. Cras faucibus feugiat arcu quis lacinia. In hac habitasse platea dictumst. Pellentesque fermentum magna sit amet tellus varius ullamcorper. Vestibulum at urna augue, eget varius neque. Fusce facilisis venenatis dapibus. Integer non sem at arcu euismod tempor nec sed nisl. Morbi ultricies, mauris ut ultricies adipiscing, felis odio condimentum massa, et luctus est nunc nec eros.
		&lt;/p&gt;
	</textarea>


	</div>
	<script type="text/javascript">
function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", 'sys-pow_ccms');
	}
	return false;
}





var jsLogEl = document.getElementById('jslog');
var js = [
	'../../tiny_mce/tiny_mce_full.js',
	'../../../../lib/includes/js/the_goto_guy.js'
	];

function jsComplete() 
{
	jslog('All JS has been loaded!');
	
	// window.addEvent('domready',function()
	//{
	tinyMCE.init({
		mode : "exact",
		elements : "elm1",
		theme : "advanced",
		theme : "simple"
	});
	//});
}


function jslog(message) 
{
	jsLogEl.value += "[" + (new Date()).toTimeString() + "] " + message + "\r\n";
}


/* the magic function which will start it all, thanks to the augmented lazyload.js: */
function ccms_lazyload_setup_GHO()
{
	jslog('loading JS (sequential calls)');

	LazyLoad.js(js, jsComplete);
}
</script>
<script type="text/javascript" src="../../../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
</body>
</html>
