<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Documentation - CompactCMS | Getting started and advanced tips</title>
<link rel="stylesheet" href="fmt/screen.css,layout.css,sprite.css,syntax.css" type="text/css" charset="utf-8">
<style type="text/css">
.light_up_editing {
	color: #FF0000;
}

.ss_tick
{
	background-image: url('../_install/sprite-src/famfamfam_silk_icons_v013/icons/tick.png');
	background-repeat: no-repeat;
}


.ss_stop
{
	background-image: url('../_install/sprite-src/famfamfam_silk_icons_v013/icons/stop.png');
	background-repeat: no-repeat;
}


.ss_exclamation
{
	background-image: url('../_install/sprite-src/famfamfam_silk_icons_v013/icons/exclamation.png');
	background-repeat: no-repeat;
}


td {
	vertical-align: top;
}


</style>
</head><body class="container">



<h1 class="center-text">Issues overview</h1>
<p>(I tend to write these things on paper (haven't got the same issue tracker around everywhere I go); moved the paper notes here for easier transportation.)</p>
<p>These issues have been identified and need to be fixed. Issues may be bugs, 'oddities' or change requests. When done, an issue is marked as such: &#10004;</p>
<table cellspacing="0" cellpadding="0">
<tbody>
<tr><th class="span-2">
<p>Done</p>
</th><th>
<p>Description</p>
</th></tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>installer: add &lt;IfDef&gt; around Rewrite rules to DISable these until we arrive at a point in time where we can be sure the rules are valid.</p>
<p>Symptom: when installing/upgrading over an existing site or a straight copy of one, the rewrite rules may be completely wrong to start with and thus obstruct the installer operations themselves in all sorts of unpredictable ways.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>installer sets $cfg[] entries to 1 instead of boolean true/false.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10006;
<p>2010/??/??</p>
</td>
<td><p>In production setting the /admin/index.php does <em>not</em> warn about the _install directory still existing.</p>
<p>Rejected: must've been some cache issue in my FF3.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>MochaUI windows are too high for lorez laptop screen.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>Image upload doesn't work in tinyMCE 'insert image': empty content (no text, just formatting) visible for the FileManager.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>News items are stored twice (duplicated!) when adding these using IE8.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>Inconsistent width for dyn_list in admin screen: header differs from content columns.</p>
<p>Fixed: pulled the header into the generated page list table, so the entire list is now exactly 1(one) table instead of two: this reduces the alignment hassles <em>significantly</em>. Same has been done for the menu management table (which was two tables as well).</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>backup-restore (a.k.a. 'upgrade') cycle damages UTF-8 characters. Must be some encoding issue b0rking somewhere.</p>
<p>Update: database was not configured to use the UTF8 character encoding; hence several fields had the wrong encoding assumption. Now everything is done in UTF8 -- IFF you upgrade to the latest SQL/database layout, of course!</p>
</td>
</tr>
<tr>
<td><p>✔
<p>2010/12/29</p>
</td>
<td><p>Xander says: CCMS fails when using a different table prefix than ccms_ and/or a different database name ('compactcms').</p>
<p>Fixed. Tested this with bleeding edge today and it's not reproducible, at least not now any more.</p>
<p>Fixed (2011/02/27). Tested both <strong>database name</strong> change and
<strong>table prefix</strong> change in installer: okay.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;
<p>2010/12/28</p>
</td>
<td><p>preview codes don't work.</p>
<p>Update: Well... they do work now (didn't before), but there's still the issues with news items and such: those are really SUBpages and the previewcode acts on a PAGE scope; should we include SUBpage treatment to make a preview for a single news item possible (before, news didn't have any 'preview' ability available <em>at all</em>!) or should we keep the preview scope as it is now? I'd like to have it as tight as possible, so that would mean restricted to a single news item, however that would mean a few more spots in the CCMS code needs to be intimately aware of the what and how of the (news) module(s) internals. ==&gt; stick with the current state of affairs.</p>
<p>Fixed. change suggested in update: Rejected.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/12/29</p>
</td>
<td><p>Editor (WYSIWYG) doesn't work.</p>
<p>Fixed. Don't know how I 'did it' exactly as the problem got reduced to being an issue with the tinyMCE plugins (specify none (plugins: '') and the editor is shown as expected) but after a simultaneous tinyMCE upgrade to latest GIT it worked with plugins without me having to do anything else.</p>
<p>Note that during the update I switched from moxie:master to ephox:accessibility branch and there's sufficient change there to include any tweaks to make the plugins work for me again...</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>lazyloading doesn't happen consistently for <em>all</em> JavaScript -- we still get timing issues for some pages and JS addons.</p>
<p>Update: largely fixed, but needs a review to make sure.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/12/28</p>
</td>
<td><p>Add preview to news items as well as regular pages. Treat news items as first class page citizens.</p>
<p>Fixed.</p>
<p>Note that it's a wee bit hackish in my opinion: you must save the news item (overwriting a previous <em>revision</em> if there was one) before you can preview.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/12/??</p>
</td>
<td><p>On-line Help tips are not shown properly formatted in the admin screen.</p>
<p>Fixed. Cause was that the Tipz assignment JS code was not re-run on every dyn-list / menu management table load.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>Delete a page and below the dyn-list there's a PHP report about 'Warning: using side-effect which existed until 4.2.3' or thereabouts. What's wrong in process.inc.php?</p>
<p>&nbsp;</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/??/??</p>
</td>
<td><p>lightbox management: click on image and checkbox is <em>not</em> marked in latest FF3.</p>
<p>Fixed. Adjusted HTML and CSS.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>Todo: sprite the lightbox thumbnails in a 84x84 grid. Problem: background color is dependent on admin and regular template colors!</p>
<p>In fact, this request is triggered by the fact that for large image collections, IE8 doesn't seem to load all the thumbnails every time.</p>
<p>Update: do not 'sprite' those images but lazyload them instead.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>Test install from github using a (hackish)&nbsp; Alias /c&nbsp; /www/c&nbsp; apache line instead of a name- or IP-based virtual host. http://&lt;ip-number&gt;/c/ shows as 'cannot connect to database'.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>@paul: http://c/_install/ shows up in FF4beta (with lots of GreaseMonkey addons, Skype and more addon cruft :-( ) as 1(one) column instead of 2 -- must be the layout is damaged somehow, but where and by whom?!</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>@paul: when user rights (on Windows and UNIX) are not as you might want for apache, then the installer will try to rewrite the config.inc.php file, but will produce an EMPTY file. Which produces an EMPTY installer screen when you retry/rerun the CCMS installer as the $ccms[] array is now <em>gone</em>.</p>
</td>
</tr>
<tr>
<td><p>&#8263; 
<p>2010/??/??</p>
</td>
<td><p>.sh scripts in _install will need to have their a+x attributes set when we generate a distro package from this.</p>
</td>
</tr>
<tr>
<td><p>&#8263; 
<p>2010/??/??</p>
</td>
<td><p>the usual issues with getting the RewriteEngine running: do not forget to run</p>
<pre>a2enmod rewrite</pre>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>@paul: FF4beta admin screen create page and menu mgt. divs jump around when you create a page and hit the 'create' button there: next thing you know the create page div is moved to the middle of the screen, ruining the admin screen layout.</p>
</td>
</tr>
<tr>
<td><p><span class="ss_sprite_16 ss_tick" title="okay"><br /></span>&#8263; 
<p>2010/??/??</p>
</td>
<td><p>tinyMCE: add an image: select an image, don't add an 'alt' text and hit 'insert': the message box shown next will be to small to fit the text. Adjust the CSS to make it large enough.</p>
<p>Update: fixed preliminary by adjusting the relevant CSS. Must be tested.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>feature add: add image view option to lightbox management page: right now you can only look at the thumbnails, but you may want to have a look at the 'full size' image before you decide to delete it (or do something else).</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>Create an empty album directory, go to the lightbox management where this directory will turn up as an empty album, try to delete it and you get a crash and your session is ditched alongside. ==&gt; The next round, you get the auth/login screen as the session has been destroyed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/12/28</p>
</td>
<td><p>mochaUI: path/plugin directories are not configured correctly it seems as a sniffer shows that invalid URLs are requested; one of the consequences of that seems to be that the admin session is destroyed, so you get the auth/login page shown in various spots in the admin screen.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/12/30</p>
</td>
<td><p>admin page crashes and renders incorrectly (dyn_list and such is not loaded!) on IE6. (v6.0.900.5512)</p>
<p>Fixed. Turns out IE6 doesn't like trailing commas in object definitions at all: those superfluous ones have been removed. Another crash occurred in mochaUI while testing, due to an undefined object. Fixed as well. (see Window.js in the mochaUI sources)</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>news items: calendar icon is shown with a non-transparent background in IE6 -- probably due to IE6 not supporting PNG transparency.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2010/??/??</p>
</td>
<td><p>compactCMS default template ('ccms') is not okay; bottom menu and breadcrumb lines mix with the content; others issues as well.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2010/12/30</p>
</td>
<td><p>request the default page start: /c/index.html and you get a 404 instead of the home page. In effect, '/index.html' produces a 404, where '/' does not. Rewrite rules forward to index.html, but apparently the backend doesn't cope correctly with a page called 'index'.</p>
<p>Fixed: now home.html, index.html are both recognized as 'home page requests'. The VHOST rewrite rules have been adjusted as well to make sure the home page always is the '/' URL (though, of course, /home.html and /index.html are both accepted as well -- it's better to have unique front for SEO reasons)</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/10</p>
</td>
<td><p>Bug: Open the lightbox admin page for an album (which you created previously), e.g. <a href="http://example.com/c/lib/modules/lightbox/lightbox.Manage.php?album=0rn3"> http://example.com/c/lib/modules/lightbox/lightbox.Manage.php?album=0rn3</a> and then click on the 'Start Upload' button without browsing/picking a couple of images first. The result will be a little JSON dump instead of a properly HTML formatted page avec du error report:</p>
<pre>{"status":"0","error":"Invalid Upload: ","code":" : : "}</pre>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/11</p>
</td>
<td><p>Feature request: event calendar. Something along the lines of the <a href="http://wordpress.org/extend/plugins/events-calendar/">Wordpress one</a>, I'd say. There's of course a few mootools-based ones out there: <a href="http://www.electricprism.com/aeron/calendar/">here</a> (and the <a href="https://github.com/aglemann/calendar">GIT repo</a> of that one, <a href="http://dansnetwork.com/mootools/events-calendar/demo/">demo here</a>), <a href="http://www.webresourcesdepot.com/mootools-ajax-calendar-vista-like/"> here</a> and <a href="http://dev.base86.com/scripts/vista-like_ajax_calendar_version_2.html"> here</a> (where the latter is really a date picker, if you ask me: <a href="http://dev.base86.com/app/pages/scripts/vista-like_ajax_calendar_version_2/"> demo</a>)</p>
<p>However, what I'm really looking for is an extra module which can manage events with both time and duration (imagine festivals lasting several days and how to show those in a printable event calendar!)</p>
<p>Examples to look at:</p>
<p><a href="http://dansnetwork.com/mootools/events-calendar/demo/"> http://dansnetwork.com/mootools/events-calendar/demo/</a></p>
<p><a href="http://www.netbanker.com/2007/04/updated_online_banking_payment.html"> http://www.netbanker.com/2007/04/updated_online_banking_payment.html</a></p>
<p><a href="http://www.expo70.or.jp/e/park/park_events.html"> http://www.expo70.or.jp/e/park/park_events.html</a> (note the particularly beautiful event rendering here, which is not suitable for everything, but is very nice nevertheless)</p>
<p><a href="http://www.avonvale.co.uk/index.php/events_calendar_2009/"> http://www.avonvale.co.uk/index.php/events_calendar_2009/</a></p>
<p><a href="http://www.trumba.com/connect/onlinecalendars/new_york_times.aspx"> http://www.trumba.com/connect/onlinecalendars/new_york_times.aspx</a> (now these are serious buggers! :-) If possible, let's do something like this, eh?)</p>
<p><a href="https://events.rit.edu/help.cfm"> https://events.rit.edu/help.cfm</a> (and if you'ld get on-line help of a quality like this, then we're absolutely super-fine!)</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/11</p>
</td>
<td><p>Bug: CoolClock is broken (probably thanks to that untested v2.something update a ways back).</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/11</p>
</td>
<td><p>Restored the rounded-corner look for the admin pages. (The non-development mode CSS processing in the Combiner now inserts the correct incantation for FF3 at least.)</p>
<p>TODO: do the same magick for Chrome and others.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/11</p>
</td>
<td><p>Bug: Running csstidy on the CSS through the Combiner nukes the admin pages.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/14</p>
</td>
<td><p>Feature Request: new module called Mixer: generates pages by 'mixing' other pages into a single entity. The idea originated from pondering how one would be able to (ab)use CompactCMS as a blog system, which would require each article (~ page) to come with a comment section. In other words: a 'blog page' would be a regular page and a comments page combined into one! The Mixer can do that for us by having a 'mixed page' row, which has this module assigned. Then the module can check another table (ccms_modmixer) to see which pages must be loaded to render the requested 'mixed' page. That would make the ccms_modmixer table a N:M relational table (with attributes).</p>
<p>Of course the Mixer could also be the answer to my question how we might be able to put page-based content in other spots in the template then just the 'content' section. E.g. 'mixing' a regular page with a 'news' page and a 'event calendar' page, thus presenting a complex events announcement page.</p>
<p>The approach has one drawback: for the 'blog solution', you'ld need to have two pages: the article itself and the mix page. Next to that, you need one comments page (not included in <em>any</em> menu) in order to be able to manage the comments (though the Mixer module would have its own management page which can forward management to the comments page for ease of use).</p>
<p>This means that the number of 'pages' in the admin section will grow quite quickly for a busy site and that would make them hard to manage. The filtering feature in there can help, but it's not ideal. Besides, there's no ability to filter on 'is published', 'is in menu X', etc. selections (yet). Maybe it would work better if we had one mixer page and managed all 'mixes' from there. How we'd go about keeping the page list small while sticking with the CCMS page approach is still a conundrum; I can't see a way out of that without some sort of filtering being applied to the page list (dyn_list) in the admin screen.</p>

		<pre>CREATE TABLE IF NOT EXISTS `ccms_modmixer` (
`MixerID` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT &#39;record index&#39;,
`UserID` int(5) DEFAULT NULL COMMENT &#39;user for which this mix applies&#39;,
`PageID` int(5) NOT NULL COMMENT &#39;reference to the MixIn page itself&#39;,
`MixIn_PageID` int(5) NOT NULL COMMENT &#39;reference to the page which will be placed at this location/position&#39;,
`location` smallint(5) NOT NULL DEFAULT &#39;0&#39; COMMENT &#39;0: main section; 1..N: menu structure; 100: head; 200: header; 1000: footer&#39;,
`position` smallint(5) NOT NULL DEFAULT &#39;0&#39; COMMENT &#39;order = 0: main/center stage; -1000..-1: before; 1..1000: after&#39;,
`published` enum(&#39;Y&#39;,&#39;N&#39;) NOT NULL DEFAULT &#39;Y&#39; COMMENT &#39;is this part included in the generated page?&#39;,
`printable` enum(&#39;Y&#39;,&#39;N&#39;) NOT NULL DEFAULT &#39;Y&#39; COMMENT &#39;is this part included in the generated page when rendering a page for print?&#39;,
`last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`MixerID`),
KEY `PageID` (`PageID`),
KEY `UserID` (`UserID`),
KEY `MixIn_PageID` (`MixIn_PageID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT=&#39;N:M relationships which define the page layout mix per page/&#39; AUTO_INCREMENT=1;</pre>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/14</p>
</td>
<td><p>Feature Request: integrate <a href="https://github.com/paulirish/html5-boilerplate">HTML5 Boilerplate</a> in admin and templates?</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/14</p>
</td>
<td><p>Feature Request: seen on other sites: use lazy image loading for when we don't want a lightbox-ed image gallery (or for pages which show lots of (large) images).</p>
<p><a href="http://www.webresourcesdepot.com/lazy-loading-of-images-resources-you-need/"> http://www.webresourcesdepot.com/lazy-loading-of-images-resources-you-need/</a></p>
<p><a href="http://davidwalsh.name/mootools-lazyload"> http://davidwalsh.name/mootools-lazyload</a></p>
<p><a href="http://www.appelsiini.net/projects/lazyload"> http://www.appelsiini.net/projects/lazyload</a></p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/14</p>
</td>
<td><p>Template generator? Or a dynamic template which uses PHP/MySQL + asset collections to produce different 'templates' suitable for the CompactCMS render engine?</p>
<p>Something that starts like this: <a href="http://www.mycelly.com/"> http://www.mycelly.com/</a></p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2011/01/14</p>
</td>
<td><p>Bug: tinyMCE calculates an internal editable content iframe width based on screen width or something like that; it certainly does NOT look at the mochaUI window width.</p>
<p>Question is: how do we tell tinyMCE what the 'screen' width is, then?</p>
<p>Answer: specify an explicit width (and height?) in the init() invocation.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/15</p>
</td>
<td><p>tinyMCE does not resize when the browser window is resized. With a liquid layout (like the module management windows) the rest of the pages resizes, but tinyMCE editors do not.</p>
</td>
</tr>
<tr>
<td><p>✔<p>2011/01/15</p>
</td>
<td><p>news module: the &lt;meta description&gt; tag is filled directly from the content, screwing up the page render severely when there's any HTML in the content of the news article.</p>
<pre id="line1">&lt;<span class="start-tag">meta</span><span class="attribute-name"> name</span>=<span class="attribute-value">"description" </span><span class="attribute-name">content</span>=<span class="attribute-value">"{site} | {content}" /&gt;</span></pre>
<p>What <em>should</em> happen is that the news module produces a (reduced) strip of text for the description, preferably the preview/teaser text instead of the content itself, as the content can be quite large!</p>
<p>Fixed: added &#39;!xxx&#39; filtering capability to the template engine; here we need 
to apply the &#39;protect4attrib&#39; filter, e.g.:</p>
<pre>&lt;meta name=&quot;description&quot; content=&quot;{%sitename!protect4attr%} | {%desc!protect4attr%}&quot; /&gt;</pre>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/15</p>
</td>
<td><p>Feature Request: use a different lightbox (e.g. a la the one used at bakabt.org) or no lightbox at all: a configurable option how we want our images displayed. Regular, thumbnails with one image per page in full view, or a kanochan-like N-thumbs per page display, or a 'all full sized images in one page, but using image lazy loading').</p>
<p>(The thought is that once we got that down, we can either copy or extend the module to provide generic downloads.)</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/15</p>
</td>
<td><p>Feature Request: news, comments, lightbox management: add selectable so we can edit either only those items targeting the given page (click on edit in page row in admin then gives only the entries for that particular page!) or 'all of them' (as is the current behaviour).</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/15</p>
</td>
<td><p>Feature Request: move lightbox data to the database.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/15</p>
</td>
<td><p>Bug: SQL backup scripts do not have CREATE TABLE statements with UTF8 / utf8_unicode collation: this is probably why the upgrade/update installer process fails to handle non-US text properly!</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/15</p>
</td>
<td><p>Bug: IE8? 'modals don't show' (Xander)</p>
</td>
</tr>
<tr>
<td><p>&#10006;&#8263;
<p>2011/01/15</p>
</td>
<td><p>Bug: IE8? installer shows the two &lt;div&gt; sections BELOW one another instead of NEXT TO one another. (Xander)</p>
<p>Update: Finally happened to me as well (FF3); turned out to be a weird FF3 problem however, so I don't know whether this happening is identical to the older observation by Xander.</p>
<p>&nbsp;Fixed???</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/16</p>
</td>
<td><p>Feed edit_area JS load requests through its own Compressor; include that one in the combiner and mix in the code to make it adapt to the dev/dbg settings -- reason: right now the dev/dbg version also delivers a minified edit_area script and that's dang right bothersome to debug when you got a b0rk inside edit_area.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/01/16</p>
</td>
<td><p>Bug: open multiple mochaUI windows, while the first is a window with the edit_area control in it (e.g. template editor window); open other mochaUI window after that one and edit_area b0rks in the template editor window about some undefined element.</p>
</td>
</tr>
<tr>
<td><p>&#10004;
<p>2011/01/16</p>
</td>
<td><p>Bug: mochaUI comes with those dreaded green edges and red corners again.</p>
<p>Fixed: turns out to be part of the mochaUI CSS files: .mocha .corner and .mocha .handler styles do that to you. The skins and our own 'last_minute_fixes.css' takes care of those two, but then it MUST load as the last CSS in the set. Which isn't so when you're messing around with mochaUI errors elsewhere and decide the @import's inside editor.css can be commented out and shifted to extra &lt;link&gt; statements -- which, of course, tickly Murphy as they FOLLOW that last_minute_fixes.css file!</p>
</td>
</tr>
<tr>
<td><p>2011/02/02</p>
</td>
<td><p>Feature request: lightbox: do not overwrite existing pictures when uploading (currently the JS code only reports 'duplicates' when merging multiple 'Browse Files' actions. Seems like the upload itself is bluntly overwriting any exiting content.</p>
<p>Fixed. (2011/02/03)</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/02</p>
</td>
<td><p>Feature request: lightbox: keep log of UPLOAD errors visible once done (separate 'clear' button maybe?) so that upload/image conversion errorscan be reviewe easily. Now, it's a mystery what went wrong because the 'log' disappears as soon as the upload is completed, errors or not.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/02</p>
</td>
<td><p>Fix/tweak: lightbox: remove the SIDxxx URL query hack on Flash image upload: it turns out that the current code (which had a fix in fancyupload quite a while back to pass the sessionid cookie to Flash) actually sends the PHPSESSID=xxx (Apache dumpio log says: "Cookie: PHPSESSID=tcbv3viq3kgfmbplh2tt81ejs3\r\n") so we should be perfectly fine without that SIDxxx query hack</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/06</p>
</td>
<td><p>Feature: lightbox: save all PNG thumbnails as JPG to reduce size: now JPG thumbnails are around 2K while quite a few PNG thumbnails are up to 12K each! Should we smooth, then sharpen edges, to get better yet lighter thumbnails?</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/06</p>
</td>
<td><p>Installer: upgrade process is completely b0rked.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/06</p>
</td>
<td><p>Installer: see if we can assist upgrading from versions BEFORE 1.4.1: we have SQL for 1.40, 1.3.3 and before. What we need are backup archives of those.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/06</p>
</td>
<td><p>Update documentation regarding the template engine; particularly the JS and CSS section handling needs some explanation to be useful for others.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/06</p>
</td>
<td><p>lightbox: provide upgrading from existing directory/flatfile system to a database-based system.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>2011/02/06</p>
</td>
<td><p>tinyMCE: check out the toolbars and see which plugins fail; then make sure the toolbars are distributed evenly across the three rows in the tinyMCE layout in mochaUI windows.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
<tr>
<td><p>&#10004;&#10006;&#8263; 
<p>date</p>
</td>
<td><p>ErrorDocument lines in .htaccess are not patched correctly.</p>
<p>Fixed.</p>
</td>
</tr>
</tbody>
</table>

