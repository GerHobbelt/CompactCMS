<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Documentation - CompactCMS | Getting started and advanced tips</title>
<link rel="stylesheet" href="fmt/screen.css,layout.css,sprite.css,syntax.css" type="text/css" charset="utf-8">
</head><body class="container">

<h1>Getting started and advanced tips</h1>
<p>On this page you'll find documentation on the installation and configuration of CompactCMS.</p>
<ul>
<li><a href="http://compactcms.nl/print/docs.html#req">Requirements</a></li>
<li><a href="http://compactcms.nl/print/docs.html#quick">Quick installation</a></li>
<li><a href="http://compactcms.nl/print/docs.html#full">Full installation instructions</a></li>
<li><a href="http://compactcms.nl/print/docs.html#vars"> Configuration variables</a>
<ul>
<li><a href="http://compactcms.nl/print/docs.html#stv">Standard variables</a></li>
<li><a href="http://compactcms.nl/print/docs.html#exv">Extended configuration</a></li>
<li><a href="http://compactcms.nl/print/docs.html#sev">Security variables</a></li>
<li><a href="http://compactcms.nl/print/docs.html#msv">MySQL variables</a></li>
<li><a href="http://compactcms.nl/print/docs.html#otv">Other variables</a></li>
</ul>
</li>
<li><a href="http://compactcms.nl/print/docs.html#mysql">MySQL database structure</a></li>
<li><a href="http://compactcms.nl/print/docs.html#upgrade">Upgrading</a></li>
<li><a href="http://compactcms.nl/print/docs.html#tplvar">Template variables</a></li>
</ul>
<p>&nbsp;</p>
<h2>Requirements</h2>
<p>Before you consider to install CompactCMS on your server to manage your next or current website, it is important that you understand that CompactCMS has a few, although standard, system requirements you need to meet for the <acronym title="Content Management System">CMS</acronym> to work. If you're not sure whether you meet the requirements stated below, please refer to your hosting company.</p>
<ul>
<li>An Apache server on any operating system (Linux and Windows tested)<br /> <em>PHP should not run as a CGI extension on IIS</em></li>
<li>At least PHP5</li>
<li>A MySQL database</li>
<li>The Apache mod_rewrite() module activated</li>
</ul>
<p>&nbsp;</p>
<h2>Quick installation</h2>
<p>After downloading the package, you should follow the numbered steps below to get you started with the CompactCMS installation.</p>
<ol>
<li>Extract all files by using any compression software. Be sure to keep the folder structure intact while extracting.</li>
<li>Upload all of the extracted files to a remote location of your choosing.</li>
<li>Create a new database, or note down details from a current one to be used for your CCMS install</li>
<li>Point your web browser to the directory where you uploaded the files to. This will show you the installer.</li>
<li>After installing, be sure to remove the /_install/ directory before trying to access the backend under /admin/.</li>
</ol>
<p>That is all there is to it. The installer should guide you through the necessary steps. If anything goes wrong, refer to the next chapter for manual installation instructions. To start designing your own template, take a look at the included examples. <a href="http://compactcms.nl/print/docs.html#tplvar">Chapter "Template variables"</a> lists all of the template variables that you can use.</p>
<h2>Manual installation instructions</h2>
<ul>
<li>
<p><strong>Step 1: extract files</strong></p>
<p>Extract all files by using Winzip, Winrar or any other compression software kit. Be sure to keep the folder structure intact while extracting.</p>
<p>&nbsp;</p>
</li>
<li>
<p><strong>Step 2: edit configuration file</strong></p>
<p>Open the file <strong>/lib/config.inc.php</strong> and edit the commented variables. Do <strong>NOT</strong> change the <code>$cfg['authcode']</code> variable (default is 12345), as this value is required to log you in using the default credentials. An elaboration on the variables used, can be found in <a href="http://compactcms.nl/print/docs.html#vars">"Configuration variables"</a>.</p>
<p><em>Tip: if you later on run intro trouble, please make sure these settings are correct. Database name, username and password are case-sensitive.</em></p>
<p>&nbsp;</p>
</li>
<li>
<p><strong>Step 3: edit .htaccess file</strong></p>
<p><em>If you're installing CompactCMS in the root of your public folder the .htaccess file will not need changing. You can then skip this step.</em></p>
<p>If you have installed CompactCMS in <strong>another directory</strong> than the root, you should open up the &rdquo;.htaccess&rdquo; file included within the archive. By default the root configuration is enabled. If you want to use the sub directory configuration you should set the <strong> RewriteBase</strong> variable appropriately.</p>
<pre class="brush: plain"># For an installation under root (default)<br /># Example: www.mysite.com/index.php<br />RewriteBase /<br /><br /># For an installation under /cms<br /># Example: www.mysite.com/cms/index.php<br /># RewriteBase /cms/<br /><br /># For an installation under /test/compactcms<br /># Example: www.mysite.com/test/compactcms/index.php<br /># RewriteBase /test/compactcms/</pre>
<p><em>Also see the file itself for further details. In case you already have such a file, you should merge the two together into one file.</em></p>
<p>&nbsp;</p>
</li>
<li>
<p><strong>Step 4: create database structure</strong></p>
<p>Create a new database (use the name you specified in the configuration) and import the <a href="http://compactcms.nl/print/docs.html#mysql"> structure as shown below</a> or use the structure.sql file in the included /_docs/ directory.</p>
<p>The instructions below indicate on how to import the structure.sql file.</p>
<ol>
<li>
<p>First select the <strong>import tab</strong></p>
</li>
<li>
<p>Then click <strong>&ldquo;Browse..&rdquo;</strong> and select the structure.sql file in the /_docs/ directory</p>
</li>
<li>
<p>Hit the <strong>&ldquo;Go&rdquo;</strong> button</p>
</li>
</ol>
<p>&nbsp;</p>
</li>
<li>
<p><strong>Step 5: upload to server</strong></p>
<p>Upload all files to your webserver. You'll now be able to access the backend under www.yoursite.com/admin/. Your default user credentials are username: <strong>admin</strong>, password: <strong>pass</strong>. Don't forget to change these default values through the backend. If you cannot log in, make sure that $cfg['authcode'] is set to 12345 in the /lib/config.inc.php file.</p>
<p>&nbsp;</p>
</li>
<li>
<p><strong>Step 6: set permissions</strong></p>
<p>The following files require specific <code>chmod()</code> actions. Use your FTP software to change these values to the ones listed below</p>
<ul>
<li>./content/ to at least 0666 (0755 might work, 0777 is necessary on some servers)</li>
<li>./content/home.php to 0666</li>
<li>./content/contact.php to 0666</li>
<li>./media/ to 0777</li>
<li>./media/albums/ to 0777</li>
<li>./media/files/ to 0777</li>
<li>./lib/includes/cache/ to 0777</li>
<li>./lib/templates/ccms.tpl.html to 0666 (or any other template file)</li>
</ul>
<p><strong>A note on file permissions:</strong></p>
<p>It is true that 0777 forms a security risk for your installation under a Linux installation. All web scripts have this risk if running without a FTP layer (having sufficient risks on its own). If your hosting provider is doing a good job though, <code>chmod</code> values should not pose a risk. You could consider creating all of the mentioned directories through the Apache server itself (making Apache the file owner) and only manage the contents through the CCMS backend. This will lower the requirements from 0777 to 0666. Unfortunately you will then not be able to upload modifications through FTP, as the file owner is not your FTP account, but the Apace server.</p>
</li>
</ul>
<h2>Configuration variables</h2>
<p>Below you'll find a list of all the variables currently used by CompactCMS. The <a href="http://compactcms.nl/print/docs.html#stv"> standard variables</a> and <a href="http://compactcms.nl/print/docs.html#msv">MySQL variables</a> need changing for CompactCMS to work. The other variables should work out of the box and should be changed when in need of changed behavior of CompactCMS.</p>
<p>&nbsp;</p>
<h3>Standard variables</h3>
<p><strong>Variable: <em>$cfg['sitename']</em></strong><br /> This should include the name of your website. It can be used for showing a copyright line in the footer of each page and by default will show in the header tag of each webpage.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['sitename'] = "CompactCMS"<br />$cfg['sitename'] = "Soccer Fansite";</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['language']</em></strong><br /> This will affect the language of the administration. Please therefore make sure that the language your selecting is available in the &rdquo;/admin/includes/languages/&rdquo; directory. This variable is furthermore used for indicating to browsers and search engines which language your website is in.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['language'] = "en"; //-&gt; english<br />$cfg['language'] = "nl"; //-&gt; dutch <br />$cfg['language'] = "de"; //-&gt; german</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['rootdir']</em></strong><br /> The CCMS script needs to know where to look for its files relative to the document root. Set this variable to reflect where your installation resides. Always add a trailing slash.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['rootdir'] = "/"; //-&gt; For an install under root (www.yoursite.com/)<br />$cfg['rootdir'] = "ccms/"; //-&gt; If under a sub directory (www.yoursite.com/ccms/)<br />$cfg['rootdir'] = "tests/cms/"; //-&gt; For www.yoursite.com/tests/cms/</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['authcode']</em></strong><br /> This unique code is used to encrypt (salt) your CCMS passwords and allows you to preview pages that are unpublished. <strong>Be careful to change your authcode after install, it will make your current user credentials unusable. Choose carefully!</strong></p>
<p>Examples:</p>
<pre class="brush: php">$cfg['authcode'] = "12345";<br />$cfg['authcode'] = "A1b2C";</pre>
<p>&nbsp;</p>
<h3>Extended configuration</h3>
<p><strong>Variable: <em>$cfg['version']</em></strong> <em>[true|false]</em><br /> Description</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['version'] = "true"; //-&gt; Check for new version on initial load (default)<br />$cfg['version'] = "false"; //-&gt; Disable checking for a new version</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['iframe']</em></strong> <em>[true|false]</em><br /> By default the management of iframes is not supported. Giving your end-users the possibility to include external pages within the local website brings along risks. Most importantly it could open doors to include malicious scripts which can cause the webserver to go down. As long iframes are not needed, keep the value set to false. If iframes are needed set this value to true, but make sure you limit access to the administration. Enabling support for iframes is on your own risk.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['iframe'] = "false"; //-&gt; Do not support iframe management (default)<br />$cfg['iframe'] = "true"; //-&gt; Enable support for iframe management</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['wysiwyg']</em></strong> <em>[true|false]</em><br /> To prevent coding from being broken due to the use the <acronym title="What You See Is What You Get">WYSIWYG</acronym> editor, this editor can be disabled all together. By setting this variable to false, all editable content will be loaded in a regular textarea. Preventing the loss of coding.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['wysiwyg'] = true; //-&gt; Enable the WYSIWYG editor [true/false]</pre>
<h3>Security variables</h3>
<p><strong>Variable: <em>$cfg['protect']</em></strong></p>
<p>Examples:</p>
<pre class="brush: php">$cfg['protect'] = true; //-&gt; This will require you to login.</pre>
<h3>MySQL database variables</h3>
<p><strong>Variable: <em>$cfg['mysql_host']</em></strong><br /> Enter the host of your MySQL server here. This should be &ldquo;localhost&rdquo; in 96% of the cases.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['mysql_host'] = "localhost"; <br />$cfg['mysql_host'] = "127.0.0.1";</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['mysql_user']</em></strong><br /> The username for accessing your MySQL database. This is not likely to be &ldquo;root&rdquo; unless you're on dedicated or local hosting.</p>
<p>Example:</p>
<pre class="brush: php">$cfg['mysql_user'] = "root";</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['mysql_pass']</em></strong><br /> Set this variable to your MySQL password which belong to the username specified above. Make sure you always have a password set to prevent others from accessing your database (unless on local).</p>
<p>Example:</p>
<pre class="brush: php">$cfg['mysql_pass'] = "compactcms";</pre>
<hr class="space" />
<p><strong>Variable: <em>$cfg['mysql_db']</em></strong><br /> The name of the database you are planning to use for CompactCMS. This could be anything. Note that you don't need a separate database for CompactCMS to work. The *.sql file included with this package only creates one table.</p>
<p>Example:</p>
<pre class="brush: php">$cfg['mysql_db'] = "compactcms";</pre>
<h3>Other variables</h3>
<p><strong>Variable: <em>$cfg['restrict']</em></strong><br /> If you have one or multiple files with specific <acronym title="Hypertext Preprocessor">PHP</acronym> coding, you should restrict editor access to these files. Opening either Javascript or <acronym title="Hypertext Preprocessor">PHP</acronym> coding within the online editor will cause loss of code. Specify the pages that contain special code using this variable. Access to these files will then be restriced for the administration. These files can only be modified with your favourite editor, directly using a <acronym title="File Transfer Protocol">FTP</acronym> program (download - edit - upload).</p>
<p>! Use the filenames without extension.</p>
<p>Examples:</p>
<pre class="brush: php">$cfg['restrict'] = array("sitemap", "phpform");<br />$cfg['restrict'] = array("sitemap");<br />$cfg['restrict'] = array("faq", "contact", "newsletter");</pre>
<p><em>Tip: to see if you configured this variable right, open up the admin and check whether the edit and preview links are replaced by &ldquo;Restriced content&rdquo; for the specified pages.</em> <a id="mysql" name="mysql"></a></p>
<h2>MySQL database structure</h2>
<p>Below you'll find the contents of the "structure.sql" file. If you know how to use this syntax, then go ahead and copy &amp; paste the code. Otherwise use the structure.sql file included with the archive and import it into your database using PhpMyAdmin. See step 4 above on how to import the structure.sql file.</p>
<pre class="brush: sql">--<br />-- MySQL database dump<br />-- Created for CompactCMS (www.compactcms.nl)<br />--<br />-- Host: localhost<br />-- Generated: Feb 25, 2011 at 19:22<br />-- MySQL version: 5.1.41<br />-- PHP version: 5.3.1<br />--<br />-- Database: `compactcms`<br />--<br /><br /><br /><br /><br />-- ========================================================<br /><br />--<br />-- Create the database if it doesn't exist yet for database `compactcms`<br />--<br /><br />CREATE DATABASE IF NOT EXISTS `compactcms` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ ; <br /><br />USE `compactcms`;<br /><br />ALTER DATABASE `compactcms` DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci`;<br /><br /><br />SET CHARACTER SET `utf8`;<br /><br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_cfgcomment`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_cfgcomment`;<br />CREATE TABLE IF NOT EXISTS `ccms_cfgcomment` (<br />`cfgID` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this set of comments will be displayed',<br />`showLocale` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eng',<br />`showMessage` int(5) NOT NULL COMMENT 'the number of comments to show per page (0 = no pagination of comments)',<br />PRIMARY KEY (`cfgID`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='common datums for comments on a per-page basis' AUTO_INCREMENT=3 ;<br /><br />--<br />-- Dumping data for table `ccms_cfgcomment`<br />--<br /><br />TRUNCATE TABLE `ccms_cfgcomment`;<br /><br />-- table `ccms_cfgcomment` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_cfglightbox`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_cfglightbox`;<br />CREATE TABLE IF NOT EXISTS `ccms_cfglightbox` (<br />`album_id` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`page_id` int(5) NOT NULL COMMENT 'the page where this album will be displayed',<br />`user_id` int(5) NOT NULL COMMENT 'the owner',<br />`title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,<br />`description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'HTML text shown on the album page',<br />`keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,<br />`dirname` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the directory where the album images are stored',<br />`display_type` smallint(5) NOT NULL DEFAULT '0' COMMENT 'the way the album and the album images are shown (0: lightbox, 1: alt.lightbox, 2: kanochan, 3: full sized overview)',<br />`order_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'GFD' COMMENT 'the sort order for the images in this album (by [F]=filename, by [T]=title, by [G]=sequence/group number, by [D]=timestamp, ...)',<br />`pagination` smallint(5) NOT NULL DEFAULT '0' COMMENT 'number of images per page (0 = no pagination)',<br />`album_template` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'template override when showing individual albums',<br />`img_template` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the template used to render a single image',<br />`printable` tinyint(1) NOT NULL DEFAULT '1',<br />`published` tinyint(1) NOT NULL DEFAULT '1',<br />`last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br />PRIMARY KEY (`album_id`),<br />KEY `page_id` (`page_id`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;<br /><br />--<br />-- Dumping data for table `ccms_cfglightbox`<br />--<br /><br />TRUNCATE TABLE `ccms_cfglightbox`;<br /><br />-- table `ccms_cfglightbox` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_cfgnews`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_cfgnews`;<br />CRE
ATE TABLE IF NOT EXISTS `ccms_cfgnews` (<br />`cfgID` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this news item will be displayed',<br />`showLocale` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eng',<br />`showMessage` int(5) NOT NULL DEFAULT '3',<br />`showAuthor` tinyint(1) NOT NULL DEFAULT '1',<br />`showDate` tinyint(1) NOT NULL DEFAULT '1',<br />`showTeaser` tinyint(1) NOT NULL DEFAULT '0',<br />PRIMARY KEY (`cfgID`),<br />KEY `page_id` (`page_id`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuration variables for modNews' AUTO_INCREMENT=5 ;<br /><br />--<br />-- Dumping data for table `ccms_cfgnews`<br />--<br /><br />TRUNCATE TABLE `ccms_cfgnews`;<br /><br />-- table `ccms_cfgnews` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_cfgpermissions`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_cfgpermissions`;<br />CREATE TABLE IF NOT EXISTS `ccms_cfgpermissions` (<br />`name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The identifying name of the permission',<br />`value` smallint(1) NOT NULL DEFAULT '0' COMMENT 'The value of the permission: 0 (disabled), 1 (any authenticated user) .. 4 (admin only)',<br />`display_order` decimal(5,2) NOT NULL DEFAULT '999.99' COMMENT 'the order in which these items will be listed in the management edit window: useful when you want to group settings',<br />PRIMARY KEY (`name`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;<br /><br />--<br />-- Dumping data for table `ccms_cfgpermissions`<br />--<br /><br />TRUNCATE TABLE `ccms_cfgpermissions`;<br /><br />INSERT INTO `ccms_cfgpermissions` (`name`, `value`, `display_order`) VALUES<br />('manageModTranslate', '0', '100.10'),<br />('manageOwners', '4', '10.10'),<br />('managePages', '2', '10.00'),<br />('manageMenu', '2', '3.00'),<br />('manageTemplate', '3', '2.00'),<br />('manageModules', '4', '100.00'),<br />('managePageActivation', '2', '10.60'),<br />('managePageCoding', '2', '10.70'),<br />('manageModBackup', '3', '100.10'),<br />('manageModNews', '2', '100.40'),<br />('manageModLightbox', '2', '100.30'),<br />('manageModComment', '2', '100.20'),<br />('manageUsers', '4', '1.00'),<br />('managePageEditing', '2', '10.50');<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_modacl`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_modacl`;<br />CREATE TABLE IF NOT EXISTS `ccms_modacl` (<br />`acl_id` int(7) unsigned NOT NULL AUTO_INCREMENT COMMENT 'record index',<br />`user_id` int(5) NOT NULL COMMENT 'user for which this ACL entry applies; -1=anonymous guest; 0=owner',<br />`page_id` int(5) NOT NULL COMMENT 'reference to the page for which this ACL applies; 0 = all ''pages''',<br />`page_subid` int(10) NOT NULL COMMENT 'can be used by other plugins to check ACLs for parts of a page, e.g. a single album (lightbox) or news item (news); 0 = all ''sub items''',<br />`may_view` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'READ: may this page/item be shown to the given user?',<br />`may_edit` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'UPDATE/WRITE: may this page/item be edited by the given user?',<br />`may_create` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'CREATE: may this page/item be created by the given user?',<br />`may_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'DELETE: may this page/item be deleted by the given user?',<br />`last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br />PRIMARY KEY (`acl_id`),<br />KEY `PageID` (`page_id`),<br />KEY `UserID` (`user_id`),<br />KEY `SubID` (`page_subid`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='N:M relationships which define the access control per page [' AUTO_INCREMENT=1 ;<br /><br />--<br />-- Dumping data for table `ccms_modacl`<br />--<br /><br />TRUNCATE TABLE `ccms_modacl`;<br /><br />-- 
table `ccms_modacl` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_modcomment`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_modcomment`;<br />CREATE TABLE IF NOT EXISTS `ccms_modcomment` (<br />`commentID` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this comment will be displayed',<br />`commentName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`commentEmail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`commentUrl` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,<br />`commentContent` text COLLATE utf8_unicode_ci NOT NULL,<br />`commentRate` smallint(1) NOT NULL DEFAULT '3' COMMENT 'rating: 1..5',<br />`commentTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,<br />`commentHost` varchar(20) COLLATE utf8_unicode_ci NOT NULL,<br />PRIMARY KEY (`commentID`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table containing comment posts for CompactCMS guestbook mo' AUTO_INCREMENT=40 ;<br /><br />--<br />-- Dumping data for table `ccms_modcomment`<br />--<br /><br />TRUNCATE TABLE `ccms_modcomment`;<br /><br />-- table `ccms_modcomment` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_modlightbox`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_modlightbox`;<br />CREATE TABLE IF NOT EXISTS `ccms_modlightbox` (<br />`img_id` int(8) unsigned NOT NULL AUTO_INCREMENT,<br />`album_id` int(5) NOT NULL,<br />`filename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,<br />`description` text COLLATE utf8_unicode_ci NOT NULL,<br />`width` int(5) NOT NULL COMMENT 'derived info (cached in this record)',<br />`height` int(5) NOT NULL COMMENT 'derived info (cached in this record)',<br />`display_sequence` int(8) NOT NULL DEFAULT '0' COMMENT 'sort order for the display - allows for flexible ordering of the images per album, so one can set a particular ''show order'' when they like',<br />`published` tinyint(1) NOT NULL DEFAULT '1',<br />`last_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '''last modified'' timestamp of the image itself (cached from filesystem; this is one of the image show sort order options and it''s way easier (and faster) to have it stored in the database like this!)',<br />PRIMARY KEY (`img_id`),<br />KEY `album_id` (`album_id`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;<br /><br />--<br />-- Dumping data for table `ccms_modlightbox`<br />--<br /><br />TRUNCATE TABLE `ccms_modlightbox`;<br /><br />-- table `ccms_modlightbox` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_modmixer`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_modmixer`;<br />CREATE TABLE IF NOT EXISTS `ccms_modmixer` (<br />`mixer_id` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'record index',<br />`user_id` int(5) DEFAULT NULL COMMENT 'user for which this mix applies',<br />`page_id` int(5) NOT NULL COMMENT 'reference to the MixIn page itself',<br />`mixin_page_id` int(5) NOT NULL COMMENT 'reference to the page which will be placed at this location/position',<br />`args_passing` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'which arguments to be passed to the subpage, and as which parameters?',<br />`location` smallint(5) NOT NULL DEFAULT '0' COMMENT '0: main section; 1..N: menu structure; 100: head; 200: header; 1000: footer',<br />`position` smallint(5) NOT NULL DEFAULT '0' COMMENT 'order = 0: main/center stage; -1000..-1: before; 1..1000: after',<br />`published` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is this part included in the generated page?',<br />`printable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is this 
part included in the generated page when rendering a page for print?',<br />`last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br />PRIMARY KEY (`mixer_id`),<br />KEY `PageID` (`page_id`),<br />KEY `UserID` (`user_id`),<br />KEY `MixIn_PageID` (`mixin_page_id`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='N:M relationships which define the page layout mix per page/' AUTO_INCREMENT=1 ;<br /><br />--<br />-- Dumping data for table `ccms_modmixer`<br />--<br /><br />TRUNCATE TABLE `ccms_modmixer`;<br /><br />-- table `ccms_modmixer` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_modnews`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_modnews`;<br />CREATE TABLE IF NOT EXISTS `ccms_modnews` (<br />`newsID` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`userID` int(5) unsigned NOT NULL,<br />`page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this news item will be displayed',<br />`newsTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL,<br />`newsTeaser` text COLLATE utf8_unicode_ci NOT NULL,<br />`newsContent` text COLLATE utf8_unicode_ci NOT NULL,<br />`newsModified` datetime NOT NULL,<br />`newsPublished` tinyint(1) NOT NULL DEFAULT '0',<br />PRIMARY KEY (`newsID`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;<br /><br />--<br />-- Dumping data for table `ccms_modnews`<br />--<br /><br />TRUNCATE TABLE `ccms_modnews`;<br /><br />-- table `ccms_modnews` has 0 records.<br />--<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_modules`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_modules`;<br />CREATE TABLE IF NOT EXISTS `ccms_modules` (<br />`modID` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`modName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'File name',<br />`modTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Friendly name',<br />`modLocation` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'where you''ld find the plugin sources for management &amp; display (use % as a marker where the mode (''Manage'', ''Show'') should be inserted into the specified path)',<br />`modVersion` decimal(5,2) NOT NULL,<br />`modPermissionName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the permission name used in the admin permissions management screen to configure which users have what permissions',<br />`hasPageMaker` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If module may act as a page content generator',<br />`hasAdminSection` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if module requires a link to access it from the admin screen',<br />`modActive` tinyint(1) NOT NULL DEFAULT '1',<br />PRIMARY KEY (`modID`)<br />) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='the installed modules, their version and activation state';<br /><br />--<br />-- Dumping data for table `ccms_modules`<br />--<br /><br />TRUNCATE TABLE `ccms_modules`;<br /><br />INSERT INTO `ccms_modules` (`modID`, `modName`, `modTitle`, `modLocation`, `modVersion`, `modPermissionName`, `hasPageMaker`, `hasAdminSection`, `modActive`) VALUES<br />('1', 'news', 'News', './lib/modules/news/news.%.php', '1.10', 'manageModNews', '1', '0', '1'),<br />('2', 'lightbox', 'Lightbox', './lib/modules/lightbox/lightbox.%.php', '1.10', 'manageModLightbox', '1', '0', '1'),<br />('4', 'mixer', 'Layout Mixer', './lib/modules/mixer/mixer.%.php', '1.00', 'manageModMixer', '1', '0', '0'),<br />('3', 'comment', 'Comments', './lib/modules/comment/comment.%.php', '1.20', 'manageModComment', '1', '0', '1'),<br />('5', 'acl', 'Access Control', './lib/modules/acl/acl.%.php', '1.00', 'manageModACL', '0', '1', '0'),<br />('6', 'template-editor', 'Template Editor', './admin/modules/template-editor/tpedt.%.php', '1.00', 'manageTemplateEditor', '0', '1', '1'),<br />('7', 'user-management', 'User Management', './admin/mod
ules/user-management/usrmgr.%.php', '1.00', 'manageUsers', '0', '1', '1'),<br />('8', 'backup-restore', 'Backup / Restore', './admin/modules/backup-restore/backup.%.php', '1.00', 'manageBackups', '0', '1', '1'),<br />('9', 'content-owners', 'Content Ownership', './admin/modules/content-owners/owners.%.php', '1.00', 'manageContentOwners', '0', '1', '1'),<br />('10', 'translation', 'Translation Assistant', './admin/modules/translation/translation.%.php', '0.10', 'manageTranslations', '0', '1', '1'),<br />('11', 'editor', 'HTML/PHP Editor', './lib/modules/editor/editor.%.php', '1.10', NULL, '1', '0', '1'),<br />('12', 'permissions', 'CCMS Permissions', './admin/modules/permissions/permissions.%.php', '1.00', NULL, '0', '1', '1');<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br />-- Table structure for table `ccms_pages`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_pages`;<br />CREATE TABLE IF NOT EXISTS `ccms_pages` (<br />`page_id` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`user_ids` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Separated by ||',<br />`urlpage` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The in-site part of the URL, without the .html at the end',<br />`module` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'editor',<br />`toplevel` smallint(5) DEFAULT NULL,<br />`sublevel` smallint(5) DEFAULT NULL,<br />`menu_id` smallint(5) DEFAULT '1' COMMENT 'The menu this will appear in; one of define(MENU_TARGET_COUNT)',<br />`variant` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ccms' COMMENT 'The template ID which will be used in conjunction with this page when rendering',<br />`pagetitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,<br />`description` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description showing as tooltip in page OR as direct link to other place when starting with FQDN/URL',<br />`keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,<br />`srcfile` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`printable` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',<br />`islink` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y' COMMENT 'Y when the item should show up in the menu as a link (N: item shows up in the menu, but as text only)',<br />`iscoding` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y when the WYSIWYG HTML editor should not be used, e.g. when page contains PHP code',<br />`published` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y' COMMENT 'N will not show the page to visitors and give them a 403 page instead',<br />`last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Keeps track of the changes to the page content (through ''touching'' the record) and page attributes - used for web cache hinting and previewCode validation.',<br />PRIMARY KEY (`page_id`),<br />UNIQUE KEY `urlpage` (`urlpage`)<br />) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='Table with details for included pages' AUTO_INCREMENT=3 ;<br /><br />--<br />-- Dumping data for table `ccms_pages`<br />--<br /><br />TRUNCATE TABLE `ccms_pages`;<br /><br />INSERT INTO `ccms_pages` (`page_id`, `user_ids`, `urlpage`, `module`, `toplevel`, `sublevel`, `menu_id`, `variant`, `pagetitle`, `subheader`, `description`, `keywords`, `srcfile`, `printable`, `islink`, `iscoding`, `published`) VALUES<br />(00001, '0', 'home', 'editor', 1, 0, 1, 'ccms', 'Home', 'The CompactCMS demo homepage', 'The CompactCMS demo homepage', 'compactcms, light-weight cms', 'home.php', 'Y', 'Y', 'N', 'Y'),<br />(00002, '0', 'contact', 'editor', 2, 0, 1, 'sweatbee', 'Contact form', 'A basic contact form using Ajax', 'This is an example of a basic contact form based using Ajax', 'compactcms, light-weight cms', 'contact.php', 'Y', 'Y', 'Y', 'Y');<br /><br /><br /><br />-- --------------------------------------------------------<br /><br />--<br /
>-- Table structure for table `ccms_users`<br />--<br /><br />DROP TABLE IF EXISTS `ccms_users`;<br />CREATE TABLE IF NOT EXISTS `ccms_users` (<br />`userID` int(5) unsigned NOT NULL AUTO_INCREMENT,<br />`userName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,<br />`userPass` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`userFirst` varchar(50) COLLATE utf8_unicode_ci NOT NULL,<br />`userLast` varchar(25) COLLATE utf8_unicode_ci NOT NULL,<br />`userEmail` varchar(75) COLLATE utf8_unicode_ci NOT NULL,<br />`userActive` smallint(1) NOT NULL,<br />`userLevel` smallint(1) NOT NULL,<br />`userToken` varchar(100) COLLATE utf8_unicode_ci NOT NULL,<br />`userLastlog` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',<br />`userCreationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',<br />`userTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,<br />PRIMARY KEY (`userID`),<br />UNIQUE KEY `userName` (`userName`)<br />) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table with users for CompactCMS administration';<br /><br />--<br />-- Dumping data for table `ccms_users`<br />--<br /><br />TRUNCATE TABLE `ccms_users`;<br /><br />INSERT INTO `ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userCreationDate`, `userTimestamp`) VALUES<br />(00001, 'admin', '52dcb810931e20f7aa2f49b3510d3805', 'Xander', 'G.', 'xander@compactcms.nl', 1, 4, '5168774687486', '2010-08-30 06:44:57', '2010-08-30 06:44:57', '2010-08-30 08:44:57');</pre>
<p>&nbsp;</p>
<h2>Upgrading instructions</h2>
<p>You are encouraged to do a clean install if upgrading to 1.4.1. The file structure has significantly changed.</p>
<p>Make sure you back-up your /content/ and media directories, your templates and database tables. You can - after doing a clean install - restore these manually. In doing so, please make sure that:</p>
<ul>
<li>You restore your database <em>data</em> only. Keep the latest <em>structure</em> in tact.</li>
<li>The media files have a default fixed location. Restore to the /media/ folder in your installation root.</li>
<li>You should use the new <var>{%rootdir%}</var> directive in your templates to point to images, JS and CSS.</li>
<li>CCMS assumes your homepage contents to be stored in /content/home.php.</li>
</ul>
<p>I apologize for making upgrading to 1.4.1 relatively hard. If unsure, consider not upgrading at all. Otherwise, be sure to keep a back-up of your current pages, media, templates and database data while trying to manually upgrade to 1.4.1.</p>
<p>&nbsp;</p>
<h2>Template variables</h2>
<p>The default templates give you a working example of the various variables that can be used within your template to show content from your CompactCMS back-end. For example when you request the file "contact.html" all of the variables below will be filled with the appropriate content. The variable <var>{%urlpage%}</var> becomes "contact" and the <var>{%pagetitle%}</var> could become something like "Contact us now!" depending what value you have entered in the back-end.</p>
<p>Please refer to the example templates for a demonstration of how these variables are used.</p>
<pre class="brush: plain">&gt;&gt; General variables
- {%urlpage%}| The current filename that is being shown
- {%rootdir%}| The directory your CCMS is installed under, relative to the root (for external files)
- {%title%}| Use this tag within you &lt;title&gt;&lt;/title&gt; tag for optimal SEO
- {%pagetitle%}| Displays the current pagetitle (good for a &lt;h1&gt; tag)
- {%subheader%}| A short slogan, descriptive text for the current page (&lt;h2&gt;)
- {%sitename%}| Use this anywhere you want to show your sitename (e.g.: copyright)
- {%desc%}| An "extensive" description of the current page (use as meta description)
- {%keywords%}| Keywords (tags) for the current page as specified in the back-end (use as meta keywords)
- {%breadcrumb%} | Show the breadcrumb/pathway to the current file
- {%printable%}| Use as: {%IF printable (eq Y)%}&lt;a href="print/{%urlpage%}.html"&gt;Print&lt;/a&gt;{%/IF printable%}

&gt;&gt; Menu items- {%mainmenu%}| Prints an ordered list (&lt;ul&gt;) with all current published files in specified menu
- {%leftmenu%}| ''
- {%rightmenu%}| '' 
- {%footermenu%}| ''
- {%extramenu%}| ''

&gt;&gt; Content tag
- {%content%}| The content from the current file that is being requested
</pre>
<p>&nbsp;</p>
<p>&nbsp;</p>
&lt;!-- smallcaps: see http://joeclark.org/standards/small-caps.html  +  http://en.wikipedia.org/wiki/Small_caps --&gt;
<pre>CSS.inline =&gt; array(0) CSS.required_files =&gt; array(5)  /c/lib/templates/ccms/base.css =&gt; integer <strong>0</strong>
  /c/lib/templates/ccms/layout.css =&gt; integer <strong>1</strong>
  /c/lib/templates/ccms/sprite.css =&gt; integer <strong>2</strong>
  /c/lib/templates/ccms/last_minute_fixes.css =&gt; integer <strong>3</strong>
  /c/lib/templates/ccms/ie.css?only-when=%3d%3d+IE =&gt; integer <strong>4</strong> 
JS.done =&gt; array(0) 
JS.required_files =&gt; array(0) 
breadcrumb =&gt; array(2)
  0 =&gt; string(50) <strong>&lt;a href="/c/" title="Example.com Home"&gt;Home&lt;/a&gt;</strong>
  1 =&gt; string(53) <strong>&lt;a href="/c/404.html" title="ka-boom!"&gt;404 bang!!&lt;/a&gt;</strong> 
ccms_version =&gt; string(5) <strong>1.4.2</strong>
cfg =&gt; string(9) <strong>(skipped)</strong>
content =&gt; string(79) <strong>&lt;p&gt;The requested file &lt;strong&gt;{%pagereq%}.html&lt;/strong&gt; could not be found.&lt;/p&gt;</strong>
desc =&gt; string(51) <strong>Yep yep yep... noooo, Rico, not yet! hoooold it....</strong>
desc_extra =&gt; string(0) 
editarea_language =&gt; string(2) <strong>en</strong>
extramenu =&gt; string(82) <strong>&lt;ul&gt;&lt;li class="menu_item1"&gt;&lt;a href="/c/docs.html" title="Docs"&gt;Docs&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;</strong>
footermenu =&gt; NULL 
iscoding =&gt; string(1) <strong>Y</strong>
islink =&gt; string(1) <strong>Y</strong>
keywords =&gt; string(0) 
lang =&gt; string(9) <strong>(skipped)</strong>
language =&gt; string(2) <strong>en</strong>
leftmenu =&gt; NULL 
mainmenu =&gt; string(1877) <br /><strong>&lt;ul&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/xy12.html" title="Xy12"&gt;Xy12&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/t51.html" title="T51"&gt;T51&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/t522.html" title="T52"&gt;T52&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/features.html" title="Features"&gt;Features&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/contact.html" title="Contact 2 two"&gt;Contact&lt;/a&gt;&lt;/li&gt;<br />&lt;li class="menu_item0 menu_item_dummy"&gt;&lt;span class="menu_item_dummy"&gt;-&lt;/span&gt; <br /> &lt;ul class="sublevel"&gt; <br /> &lt;li class="menu_item1"&gt;&lt;a href="/c/issues_todo_and_fixed.html" title="Issues todo and fixed"&gt;Issues todo and fixed&lt;/a&gt;&lt;/li&gt; <br /> &lt;/ul&gt; <br />&lt;/li&gt; <br />&lt;li class="menu_item1 menu_item_dummy"&gt;&lt;span class="menu_item_dummy"&gt;-&lt;/span&gt; <br /> &lt;ul class="sublevel"&gt; <br /> &lt;li class="menu_item1"&gt;&lt;a href="/c/publications.html" title="Publications"&gt;Publications&lt;/a&gt;&lt;/li&gt; <br /> &lt;/ul&gt; <br />&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/security_howto_for_devs.html" title="Security &lt;i&gt;howto&lt;/i&gt; for &lt;b&gt;devs&lt;/b&gt;"&gt;Security &lt;i&gt;howto&lt;/i&gt; for &lt;b&gt;devs&lt;/b&gt;&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/t54.html" title="Security howto for devs"&gt;T54&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/the_dev_oddities_log.html" title="The dev oddities log"&gt;The dev oddities log&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/todo.html" title="Todo"&gt;Todo&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/news_1.html" title="News 1"&gt;News 1&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/light.html" title="Light"&gt;Light&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/light2.html" title="Light2"&gt;Light2&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1"&gt;&lt;a href="/c/light3.html" title="Light3"&gt;Light3&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item0"&gt;&lt;a href="/c/review.html" title="Review"&gt;Review&lt;/a&gt;&lt;/li&gt; <br />&lt;li class="menu_item1 menu_item_dummy"&gt;&lt;span class="menu_item_dummy"&gt;-&lt;/span&gt; <br /> &lt;ul class="sublevel"&gt; <br /> &lt;li class="menu_item1"&gt;&lt;a href="/c/mixer.html" title="Mixer"&gt;Mixer&lt;/a&gt;&lt;/li&gt; <br /> &lt;/ul&gt; <br />&lt;/li&gt; <br />&lt;/ul&gt;</strong>
menu_id =&gt; integer <strong>0</strong>
module =&gt; string(6) <strong>editor</strong>
module_info =&gt; array(9)
modID =&gt; string(2) <strong>11</strong>
modName =&gt; string(6) <strong>editor</strong>
modTitle =&gt; string(15) <strong>HTML/PHP Editor</strong>
modLocation =&gt; string(33) <strong>./lib/modules/editor/editor.%.php</strong>
modVersion =&gt; string(4) <strong>1.10</strong>
modPermissionName =&gt; NULL 
hasPageMaker =&gt; string(1) <strong>1</strong>
hasAdminSection =&gt; string(1) <strong>0</strong>
modActive =&gt; string(1) <strong>1</strong> 
page_id =&gt; string(2) <strong>21</strong>
page_name =&gt; string(3) <strong>404</strong>
pagereq =&gt; string(3) <strong>404</strong>
pagetitle =&gt; string(10) <strong>404 bang!!</strong>
preview =&gt; string(1) <strong>N</strong>
previewcode =&gt; string(35) <strong>21-e5d1ca0404774d6c316290e962f6f29c</strong>
printable =&gt; string(1) <strong>Y</strong>
printing =&gt; string(1) <strong>N</strong>
published =&gt; string(1) <strong>Y</strong>
responsecode =&gt; boolean <strong>false</strong>
rightmenu =&gt; NULL 
rootdir =&gt; string(3) <strong>/c/</strong>
sitename =&gt; string(14) <strong>Example.com</strong>
structure1 =&gt; string(1877) <strong>&lt;ul&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/xy12.html" title="Xy12"&gt;Xy12&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/t51.html" title="T51"&gt;T51&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/t522.html" title="T52"&gt;T52&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/features.html" title="Features"&gt;Features&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/contact.html" title="Contact 2 two"&gt;Contact&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0 menu_item_dummy"&gt;&lt;span class="menu_item_dummy"&gt;-&lt;/span&gt; &lt;ul class="sublevel"&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/issues_todo_and_fixed.html" title="Issues todo and fixed"&gt;Issues todo and fixed&lt;/a&gt;&lt;/li&gt; &lt;/ul&gt; &lt;/li&gt; &lt;li class="menu_item1 menu_item_dummy"&gt;&lt;span class="menu_item_dummy"&gt;-&lt;/span&gt; &lt;ul class="sublevel"&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/publications.html" title="Publications"&gt;Publications&lt;/a&gt;&lt;/li&gt; &lt;/ul&gt; &lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/security_howto_for_devs.html" title="Security &lt;i&gt;howto&lt;/i&gt; for &lt;b&gt;devs&lt;/b&gt;"&gt;Security &lt;i&gt;howto&lt;/i&gt; for &lt;b&gt;devs&lt;/b&gt;&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/t54.html" title="Security howto for devs"&gt;T54&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/the_dev_oddities_log.html" title="The dev oddities log"&gt;The dev oddities log&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/todo.html" title="Todo"&gt;Todo&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/news_1.html" title="News 1"&gt;News 1&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/light.html" title="Light"&gt;Light&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/light2.html" title="Light2"&gt;Light2&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/light3.html" title="Light3"&gt;Light3&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item0"&gt;&lt;a href="/c/review.html" title="Review"&gt;Review&lt;/a&gt;&lt;/li&gt; &lt;li class="menu_item1 menu_item_dummy"&gt;&lt;span class="menu_item_dummy"&gt;-&lt;/span&gt; &lt;ul class="sublevel"&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/mixer.html" title="Mixer"&gt;Mixer&lt;/a&gt;&lt;/li&gt; &lt;/ul&gt; &lt;/li&gt; &lt;/ul&gt;</strong>
structure5 =&gt; string(82) <strong>&lt;ul&gt; &lt;li class="menu_item1"&gt;&lt;a href="/c/docs.html" title="Docs"&gt;Docs&lt;/a&gt;&lt;/li&gt; &lt;/ul&gt;</strong>
subheader =&gt; string(8) <strong>ka-boom!</strong>
sublevel =&gt; integer <strong>0</strong>
template =&gt; string(4) <strong>ccms</strong>
template_collection =&gt; array(6)
  0 =&gt; string(4) <strong>ccms</strong>
  1 =&gt; string(9) <strong>fireworks</strong>
  2 =&gt; string(5) <strong>html5</strong>
  3 =&gt; string(11) <strong>nightlights</strong>
  4 =&gt; string(9) <strong>reckoning</strong>
  5 =&gt; string(8) <strong>sweatbee</strong> 
templatedir =&gt; string(4) <strong>ccms</strong>
tinymce_language =&gt; string(2) <strong>en</strong>
title =&gt; string(38) <strong>404 bang!! - Example.com | ka-boom!</strong>
toplevel =&gt; integer <strong>0</strong>
urlpage =&gt; string(3) <strong>404</strong> </pre>
<h1>$cfg</h1>
<pre>array(25)
sitename =&gt; string(14) <strong>Example.com</strong>
rootdir =&gt; string(3) <strong>/c/</strong>
authcode =&gt; string(5) <strong>15683</strong>
version =&gt; boolean <strong>true</strong>
iframe =&gt; boolean <strong>false</strong>
wysiwyg =&gt; boolean <strong>true</strong>
protect =&gt; boolean <strong>true</strong>
db_host =&gt; string(9) <strong>localhost</strong>
db_user =&gt; string(4) <strong>root</strong>
db_pass =&gt; string(6) <strong>Noppes</strong>
db_name =&gt; string(10) <strong>compactcms</strong>
db_prefix =&gt; string(5) <strong>ccms_</strong>
restrict =&gt; array(0) 
default_template =&gt; string(4) <strong>ccms</strong>
enable_gravatar =&gt; boolean <strong>false</strong>
admin_page_dynlist_order =&gt; string(4) <strong>FTS0</strong>
verify_alert =&gt; string(0) 
IN_DEVELOPMENT_ENVIRONMENT =&gt; boolean <strong>true</strong>
USE_JS_DEVELOPMENT_SOURCES =&gt; boolean <strong>true</strong>
HTTPD_SERVER_TAKES_CARE_OF_CONTENT_COMPRESSION =&gt; boolean <strong>true</strong>
tinymce_language =&gt; string(2) <strong>en</strong>
editarea_language =&gt; string(2) <strong>en</strong>
fancyupload_language =&gt; string(2) <strong>en</strong>
language =&gt; string(2) <strong>en</strong>
locale =&gt; string(9) <strong>eng.UTF-8</strong> </pre>
<p>... and then there's the language subarray...</p>
<p>&nbsp;</p>
<h2>What's in your $SESSION array when a user is logged in?</h2>
<p>At least this:</p>
<pre>ccms_userID =&gt; string(1) <strong>1</strong>
ccms_userName =&gt; string(5) <strong>admin</strong>
ccms_userFirst =&gt; string(3) <strong>Ger</strong>
ccms_userLast =&gt; string(7) <strong>Hobbelt</strong>
ccms_userLevel =&gt; string(1) <strong>4</strong>
authcheck =&gt; string(32) <strong>3c725855998245d292c1a0037fccf60f</strong>
id =&gt; string(26) <strong>kcecuuj564lk4chmv2ndroeoo7</strong>
rc1 =&gt; integer <strong>92234</strong>
rc2 =&gt; integer <strong>2239</strong>
ccms_captcha =&gt; integer <strong>913087</strong></pre>
<p>where ccms_userID is the record index and user level is a number ranging from 1..4 (user, editor, manager, administrator). 'authCheck' is a security-related bit of info and <strong style="font-variant: small-caps;">must not</strong> be edited.</p>

