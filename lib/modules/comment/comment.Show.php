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


// Set Captcha value
$_SESSION['ccms_captcha'] = mt_rand('123456','987654');

// Load comment preferences
$pagename = getGETparam4Filename('page');
$is_printing = ($ccms['printing'] == 'Y');

$pagerow = $db->SelectSingleRow($cfg['db_prefix'].'pages', array('urlpage' => MySQL::SQLValue($pagename,MySQL::SQLVALUE_TEXT)));
if ($db->ErrorNumber() != 0) $db->Kill();

$page_id = $pagerow->page_id;

if (empty($page_id) || empty($pagename))
{
	die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
}




// code requires mootools: make sure we load it!
tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/includes/js/mootools-core.js');
tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/includes/js/mootools-more.js');
// now register our own JS and make sure it ends up AFTER the mootools stuff has been loaded:
tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/modules/comment/resources/script.js');


tmpl_set_autoprio($ccms['CSS.required_files'], $cfg['rootdir'] . 'lib/modules/comment/resources/style.css > media="screen" title="comments"');


// window.addEvent("domready", function() {
$cmtdir = $cfg['rootdir'] . 'lib/modules/comment';

$ccms['JS.done'][] = <<<EOT42
window.addEvent(
	'domready',
	function()
	{
		var req=new Request.HTML(
			{
				useSpinner:true,
				method:'get',
				url:'$cmtdir/comment.Process.php?action=show-comments&page_id=$page_id',
				update:$('comments'),
				onRequest:function(){},
				onFailure:function(){},
				onSuccess:function(){ajaxLinks();}
			}).send();
		function ajaxLinks()
		{
			$$('.pagination a').each(
				function(ele)
				{
					ele.addEvent(
						'click',
						function(e)
						{
							e=new Event(e).stop();
							var alink=ele.getProperty('href');
							var qrypos = alink.indexOf('?');
							var url='$cmtdir/comment.Process.php'+alink.substr(qrypos);
							var ajaxLink=new Request.HTML(
								{
									useSpinner:true,
									method:'get',
									url:url,
									onRequest:function(){},
									onSuccess:function()
									{
										new Fx.Scroll(document.body, {'duration':'long'}).toElement('comments');
										ajaxLinks();
									},
									onFailure:function(){},
									update:$('comments')
								}).send(); /* .get(url) didn't work all of a sudden in last edit; send() with method get() /does/ work */
						});
				});
		}
	});
EOT42;






?>

<div id="comments">
	<!--spinner-->
</div>

<?php
if (!$is_printing)
{
?>
<div id="preview-display" style="display:none;">
	<h2><?php echo $ccms['lang']['guestbook']['preview']; ?></h2>
	<?php
	if ($cfg['enable_gravatar'])
	{
	?>
	<div id="preview-avatar"></div>
	<?php
	}
	?>
	<div id="preview-name"></div>
	<div id="preview-comment"></div>
	<div id="preview-rating"></div>
</div>

<fieldset id="comment-field" style="clear:both;">
	<legend><a href="" class="toggle"><?php echo $ccms['lang']['guestbook']['add']; ?></a></legend>
	<form action="" id="commentForm" method="post" accept-charset="utf-8">
		<div id="toggle-field">
			<label for="name"><?php echo $ccms['lang']['guestbook']['name']; ?>*</label><input type="text" name="name" id="name" value="" class="text required" /><br/>
			<label for="email"><?php echo $ccms['lang']['guestbook']['email']; ?>*</label><input type="text" name="email" id="email" value="" class="text required validate-email" /><br/>
			<label for="website"><?php echo $ccms['lang']['guestbook']['website']; ?></label><input type="text" name="website" id="website" value="" class="text validate-url" /><br/>
			<label for="comment"><?php echo $ccms['lang']['guestbook']['comments']; ?>*</label>
			<textarea name="comment" id="comment" class="text minLength:10" style="height:80px; width: 100%;"></textarea><br/>
			<label for="rating"><?php echo $ccms['lang']['guestbook']['rating']; ?></label>
			<select name="rating" id="rating" size="1">
				<option value="1">1 *</option>
				<option value="2">2 **</option>
				<option value="3" selected="selected">3 ***</option>
				<option value="4">4 ****</option>
				<option value="5">5 *****</option>
			</select><br/>
			<p><?php echo $ccms['lang']['guestbook']['verinstr']; ?> <span style="font-weight:bold;color: #f00;"><?php echo $_SESSION['ccms_captcha']; ?></span>.</p>
			<label for="verification"><?php echo $ccms['lang']['guestbook']['verify']; ?></label><input type="input" name="verification" style="width:50px;" maxlength="6" value="" id="verification" class="required validate-match matchInput:'captcha_check' matchName:'captcha' text"/>

			<input type="hidden" name="captcha_check" value="<?php echo $_SESSION['ccms_captcha']; ?>" id="captcha_check" />
			<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" id="page_id" />
			<p style="margin-bottom:20px;text-align:center;">
				<button name="submit_gb" type="submit"><?php echo $ccms['lang']['guestbook']['add']; ?></button>
			</p>
		</div>
	</form>
</fieldset>
<?php
}
?>
