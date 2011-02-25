--
-- MySQL database dump
-- Created for CompactCMS (www.compactcms.nl)
--
-- Host: localhost
-- Generated: Feb 25, 2011 at 19:22
-- MySQL version: 5.1.41
-- PHP version: 5.3.1
--
-- Database: `compactcms`
--




-- ========================================================

--
-- Create the database if it doesn't exist yet for database `compactcms`
--

CREATE DATABASE IF NOT EXISTS `compactcms` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ ; 

USE `compactcms`;

ALTER DATABASE `compactcms` DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci`;


SET CHARACTER SET `utf8`;




-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgcomment`
--

DROP TABLE IF EXISTS `ccms_cfgcomment`;
CREATE TABLE IF NOT EXISTS `ccms_cfgcomment` (
  `cfgID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this set of comments will be displayed',
  `showLocale` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eng',
  `showMessage` int(5) NOT NULL COMMENT 'the number of comments to show per page (0 = no pagination of comments)',
  PRIMARY KEY (`cfgID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='common datums for comments on a per-page basis' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ccms_cfgcomment`
--

TRUNCATE TABLE `ccms_cfgcomment`;

-- table `ccms_cfgcomment` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfglightbox`
--

DROP TABLE IF EXISTS `ccms_cfglightbox`;
CREATE TABLE IF NOT EXISTS `ccms_cfglightbox` (
  `album_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(5) NOT NULL COMMENT 'the page where this album will be displayed',
  `user_id` int(5) NOT NULL COMMENT 'the owner',
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'HTML text shown on the album page',
  `keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dirname` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'name of the directory where the album images are stored',
  `display_type` smallint(5) NOT NULL DEFAULT '0' COMMENT 'the way the album and the album images are shown (0: lightbox, 1: alt.lightbox, 2: kanochan, 3: full sized overview)',
  `order_by` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'GFD' COMMENT 'the sort order for the images in this album (by [F]=filename, by [T]=title, by [G]=sequence/group number, by [D]=timestamp, ...)',
  `pagination` smallint(5) NOT NULL DEFAULT '0' COMMENT 'number of images per page (0 = no pagination)',
  `album_template` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'template override when showing individual albums',
  `img_template` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the template used to render a single image',
  `printable` tinyint(1) NOT NULL DEFAULT '1',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`album_id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_cfglightbox`
--

TRUNCATE TABLE `ccms_cfglightbox`;

-- table `ccms_cfglightbox` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgnews`
--

DROP TABLE IF EXISTS `ccms_cfgnews`;
CREATE TABLE IF NOT EXISTS `ccms_cfgnews` (
  `cfgID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this news item will be displayed',
  `showLocale` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'eng',
  `showMessage` int(5) NOT NULL DEFAULT '3',
  `showAuthor` tinyint(1) NOT NULL DEFAULT '1',
  `showDate` tinyint(1) NOT NULL DEFAULT '1',
  `showTeaser` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cfgID`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Configuration variables for modNews' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ccms_cfgnews`
--

TRUNCATE TABLE `ccms_cfgnews`;

-- table `ccms_cfgnews` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_cfgpermissions`
--

DROP TABLE IF EXISTS `ccms_cfgpermissions`;
CREATE TABLE IF NOT EXISTS `ccms_cfgpermissions` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The identifying name of the permission',
  `value` smallint(1) NOT NULL DEFAULT '0' COMMENT 'The value of the permission: 0 (disabled), 1 (any authenticated user) .. 4 (admin only)',
  `display_order` decimal(5,2) NOT NULL DEFAULT '999.99' COMMENT 'the order in which these items will be listed in the management edit window: useful when you want to group settings',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ccms_cfgpermissions`
--

TRUNCATE TABLE `ccms_cfgpermissions`;

INSERT INTO `ccms_cfgpermissions` (`name`, `value`, `display_order`) VALUES
('manageModTranslate', '0', '100.10'),
('manageOwners', '4', '10.10'),
('managePages', '2', '10.00'),
('manageMenu', '2', '3.00'),
('manageTemplate', '3', '2.00'),
('manageModules', '4', '100.00'),
('managePageActivation', '2', '10.60'),
('managePageCoding', '2', '10.70'),
('manageModBackup', '3', '100.10'),
('manageModNews', '2', '100.40'),
('manageModLightbox', '2', '100.30'),
('manageModComment', '2', '100.20'),
('manageUsers', '4', '1.00'),
('managePageEditing', '2', '10.50');



-- --------------------------------------------------------

--
-- Table structure for table `ccms_modacl`
--

DROP TABLE IF EXISTS `ccms_modacl`;
CREATE TABLE IF NOT EXISTS `ccms_modacl` (
  `acl_id` int(7) unsigned NOT NULL AUTO_INCREMENT COMMENT 'record index',
  `user_id` int(5) NOT NULL COMMENT 'user for which this ACL entry applies; -1=anonymous guest; 0=owner',
  `page_id` int(5) NOT NULL COMMENT 'reference to the page for which this ACL applies; 0 = all ''pages''',
  `page_subid` int(10) NOT NULL COMMENT 'can be used by other plugins to check ACLs for parts of a page, e.g. a single album (lightbox) or news item (news); 0 = all ''sub items''',
  `may_view` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'READ: may this page/item be shown to the given user?',
  `may_edit` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'UPDATE/WRITE: may this page/item be edited by the given user?',
  `may_create` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'CREATE: may this page/item be created by the given user?',
  `may_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'DELETE: may this page/item be deleted by the given user?',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`acl_id`),
  KEY `PageID` (`page_id`),
  KEY `UserID` (`user_id`),
  KEY `SubID` (`page_subid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='N:M relationships which define the access control per page [' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_modacl`
--

TRUNCATE TABLE `ccms_modacl`;

-- table `ccms_modacl` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_modcomment`
--

DROP TABLE IF EXISTS `ccms_modcomment`;
CREATE TABLE IF NOT EXISTS `ccms_modcomment` (
  `commentID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this comment will be displayed',
  `commentName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `commentEmail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `commentUrl` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commentContent` text COLLATE utf8_unicode_ci NOT NULL,
  `commentRate` smallint(1) NOT NULL DEFAULT '3' COMMENT 'rating: 1..5',
  `commentTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentHost` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`commentID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table containing comment posts for CompactCMS guestbook mo' AUTO_INCREMENT=40 ;

--
-- Dumping data for table `ccms_modcomment`
--

TRUNCATE TABLE `ccms_modcomment`;

-- table `ccms_modcomment` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_modlightbox`
--

DROP TABLE IF EXISTS `ccms_modlightbox`;
CREATE TABLE IF NOT EXISTS `ccms_modlightbox` (
  `img_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(5) NOT NULL,
  `filename` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `width` int(5) NOT NULL COMMENT 'derived info (cached in this record)',
  `height` int(5) NOT NULL COMMENT 'derived info (cached in this record)',
  `display_sequence` int(8) NOT NULL DEFAULT '0' COMMENT 'sort order for the display - allows for flexible ordering of the images per album, so one can set a particular ''show order'' when they like',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `last_modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '''last modified'' timestamp of the image itself (cached from filesystem; this is one of the image show sort  order options and it''s way easier (and faster) to have it stored in the database like this!)',
  PRIMARY KEY (`img_id`),
  KEY `album_id` (`album_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_modlightbox`
--

TRUNCATE TABLE `ccms_modlightbox`;

-- table `ccms_modlightbox` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_modmixer`
--

DROP TABLE IF EXISTS `ccms_modmixer`;
CREATE TABLE IF NOT EXISTS `ccms_modmixer` (
  `mixer_id` int(5) unsigned NOT NULL AUTO_INCREMENT COMMENT 'record index',
  `user_id` int(5) DEFAULT NULL COMMENT 'user for which this mix applies',
  `page_id` int(5) NOT NULL COMMENT 'reference to the MixIn page itself',
  `mixin_page_id` int(5) NOT NULL COMMENT 'reference to the page which will be placed at this location/position',
  `args_passing` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'which arguments to be passed to the subpage, and as which parameters?',
  `location` smallint(5) NOT NULL DEFAULT '0' COMMENT '0: main section; 1..N: menu structure; 100: head; 200: header; 1000: footer',
  `position` smallint(5) NOT NULL DEFAULT '0' COMMENT 'order = 0: main/center stage; -1000..-1: before; 1..1000: after',
  `published` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is this part included in the generated page?',
  `printable` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is this part included in the generated page when rendering a page for print?',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mixer_id`),
  KEY `PageID` (`page_id`),
  KEY `UserID` (`user_id`),
  KEY `MixIn_PageID` (`mixin_page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='N:M relationships which define the page layout mix per page/' AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ccms_modmixer`
--

TRUNCATE TABLE `ccms_modmixer`;

-- table `ccms_modmixer` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_modnews`
--

DROP TABLE IF EXISTS `ccms_modnews`;
CREATE TABLE IF NOT EXISTS `ccms_modnews` (
  `newsID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(5) unsigned NOT NULL,
  `page_id` int(5) NOT NULL DEFAULT '0' COMMENT 'the page where this news item will be displayed',
  `newsTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `newsTeaser` text COLLATE utf8_unicode_ci NOT NULL,
  `newsContent` text COLLATE utf8_unicode_ci NOT NULL,
  `newsModified` datetime NOT NULL,
  `newsPublished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`newsID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ccms_modnews`
--

TRUNCATE TABLE `ccms_modnews`;

-- table `ccms_modnews` has 0 records.
--



-- --------------------------------------------------------

--
-- Table structure for table `ccms_modules`
--

DROP TABLE IF EXISTS `ccms_modules`;
CREATE TABLE IF NOT EXISTS `ccms_modules` (
  `modID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `modName` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'File name',
  `modTitle` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Friendly name',
  `modLocation` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'where you''ld find the plugin sources for management & display (use % as a marker where the mode (''Manage'', ''Show'') should be inserted into the specified path)',
  `modVersion` decimal(5,2) NOT NULL,
  `modPermissionName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'the permission name used in the admin permissions management screen to configure which users have what permissions',
  `hasPageMaker` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'If module may act as a page content generator',
  `hasAdminSection` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if module requires a link to access it from the admin screen',
  `modActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`modID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='the installed modules, their version and activation state';

--
-- Dumping data for table `ccms_modules`
--

TRUNCATE TABLE `ccms_modules`;

INSERT INTO `ccms_modules` (`modID`, `modName`, `modTitle`, `modLocation`, `modVersion`, `modPermissionName`, `hasPageMaker`, `hasAdminSection`, `modActive`) VALUES
('1', 'news', 'News', './lib/modules/news/news.%.php', '1.10', 'manageModNews', '1', '0', '1'),
('2', 'lightbox', 'Lightbox', './lib/modules/lightbox/lightbox.%.php', '1.10', 'manageModLightbox', '1', '0', '1'),
('4', 'mixer', 'Layout Mixer', './lib/modules/mixer/mixer.%.php', '1.00', 'manageModMixer', '1', '0', '0'),
('3', 'comment', 'Comments', './lib/modules/comment/comment.%.php', '1.20', 'manageModComment', '1', '0', '1'),
('5', 'acl', 'Access Control', './lib/modules/acl/acl.%.php', '1.00', 'manageModACL', '0', '1', '0'),
('6', 'template-editor', 'Template Editor', './admin/modules/template-editor/tpedt.%.php', '1.00', 'manageTemplateEditor', '0', '1', '1'),
('7', 'user-management', 'User Management', './admin/modules/user-management/usrmgr.%.php', '1.00', 'manageUsers', '0', '1', '1'),
('8', 'backup-restore', 'Backup / Restore', './admin/modules/backup-restore/backup.%.php', '1.00', 'manageBackups', '0', '1', '1'),
('9', 'content-owners', 'Content Ownership', './admin/modules/content-owners/owners.%.php', '1.00', 'manageContentOwners', '0', '1', '1'),
('10', 'translation', 'Translation Assistant', './admin/modules/translation/translation.%.php', '0.10', 'manageTranslations', '0', '1', '1'),
('11', 'editor', 'HTML/PHP Editor', './lib/modules/editor/editor.%.php', '1.10', NULL, '1', '0', '1'),
('12', 'permissions', 'CCMS Permissions', './admin/modules/permissions/permissions.%.php', '1.00', NULL, '0', '1', '1');



-- --------------------------------------------------------

--
-- Table structure for table `ccms_pages`
--

DROP TABLE IF EXISTS `ccms_pages`;
CREATE TABLE IF NOT EXISTS `ccms_pages` (
  `page_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `user_ids` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'Separated by ||',
  `urlpage` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The in-site part of the URL, without the .html at the end',
  `module` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'editor',
  `toplevel` smallint(5) DEFAULT NULL,
  `sublevel` smallint(5) DEFAULT NULL,
  `menu_id` smallint(5) DEFAULT '1' COMMENT 'The menu this will appear in; one of define(MENU_TARGET_COUNT)',
  `variant` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ccms' COMMENT 'The template ID which will be used in conjunction with this page when rendering',
  `pagetitle` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subheader` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Description showing as tooltip in page OR as direct link to other place when starting with FQDN/URL',
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `srcfile` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `printable` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `islink` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y' COMMENT 'Y when the item should show up in the menu as a link (N: item shows up in the menu, but as text only)',
  `iscoding` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'Y when the WYSIWYG HTML editor should not be used, e.g. when page contains PHP code',
  `published` enum('Y','N') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y' COMMENT 'N will not show the page to visitors and give them a 403 page instead',
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Keeps track of the changes to the page content (through ''touching'' the record) and page attributes - used for web cache hinting and previewCode validation.',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `urlpage` (`urlpage`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='Table with details for included pages' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ccms_pages`
--

TRUNCATE TABLE `ccms_pages`;

INSERT INTO `ccms_pages` (`page_id`, `user_ids`, `urlpage`, `module`, `toplevel`, `sublevel`, `menu_id`, `variant`, `pagetitle`, `subheader`, `description`, `keywords`, `srcfile`, `printable`, `islink`, `iscoding`, `published`) VALUES
(00001, '0', 'home', 'editor', 1, 0, 1, 'ccms', 'Home', 'The CompactCMS demo homepage', 'The CompactCMS demo homepage', 'compactcms, light-weight cms', 'home.php', 'Y', 'Y', 'N', 'Y'),
(00002, '0', 'contact', 'editor', 2, 0, 1, 'sweatbee', 'Contact form', 'A basic contact form using Ajax', 'This is an example of a basic contact form based using Ajax', 'compactcms, light-weight cms', 'contact.php', 'Y', 'Y', 'Y', 'Y');



-- --------------------------------------------------------

--
-- Table structure for table `ccms_users`
--

DROP TABLE IF EXISTS `ccms_users`;
CREATE TABLE IF NOT EXISTS `ccms_users` (
  `userID` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userPass` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userFirst` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `userLast` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `userEmail` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `userActive` smallint(1) NOT NULL,
  `userLevel` smallint(1) NOT NULL,
  `userToken` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userLastlog` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userCreationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table with users for CompactCMS administration';

--
-- Dumping data for table `ccms_users`
--

TRUNCATE TABLE `ccms_users`;

INSERT INTO `ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userCreationDate`, `userTimestamp`) VALUES
(00001, 'admin', '52dcb810931e20f7aa2f49b3510d3805', 'Xander', 'G.', 'xander@compactcms.nl', 1, 4, '5168774687486', '2010-08-30 06:44:57', '2010-08-30 06:44:57', '2010-08-30 08:44:57');



