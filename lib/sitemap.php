<?php
/**
 * Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
 *
 * Last changed: $LastChangedDate$
 * @author $Author$
 * @version $Revision$
 * @package CompactCMS.nl
 * @license GNU General Public License v3
 *
 * This file is part of CompactCMS.
 *
 * CompactCMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * CompactCMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * A reference to the original author of CompactCMS and its copyright
 * should be clearly visible AT ALL TIMES for the user of the back-
 * end. You are NOT allowed to remove any references to the original
 * author, communicating the product to be your own, without written
 * permission of the original copyright owner.
 *
 * You should have received a copy of the GNU General Public License
 * along with CompactCMS. If not, see <http://www.gnu.org/licenses/>.
 *
 * > Contact me for any inquiries.
 * > E: Xander@CompactCMS.nl
 * > W: http://community.CompactCMS.nl/forum
**/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { die('Illegal entry point!'); } /*MARKER*/

// Define default location
if (!defined('BASE_PATH')) die('BASE_PATH not defined!');

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');





function check_session_sidpatch_and_start()
{
	global $cfg, $ccms;

	$getid = 'SID'.md5($cfg['authcode'].'x');
	$sesid = session_id();
	// bloody hack for FancyUpload FLASH component which doesn't pass along cookies:
	if (!empty($_GET[$getid]))
	{
		$sesid = preg_replace('/[^A-Za-z0-9]/', 'X', $_GET[$getid]);

		/*
		 * Before we set the sessionID, we'd better make darn sure it's a legitimate request instead of a hacker trying to get in:
		 *
		 * however, before we can access any $_SESSION[] variables do we have to load the session for the given ID.
		 */
		session_id($sesid);
		if (!session_start()) die('session_start(SIDPATCH) failed');
		//session_write_close();
		if (!empty($_GET['SIDCHK']) && !empty($_SESSION['fup1']) && $_SESSION['fup1'] == $_GET['SIDCHK'])
		{
			//echo " :: legal session ID forced! \n";
			//session_id($sesid);
		}
		else
		{
			//echo " :: illegal session override! IGNORED! \n";

			// do NOT nuke the session; this might have been a interloper trying a DoS attack... let it all run its natural course.
			$_SESSION['fup1'] = md5(mt_rand().time().mt_rand());

			die_and_goto_url(null, $ccms['lang']['auth']['featnotallowed']); // default URL: login!
		}
	}
	else
	{
		if (!session_start()) die('session_start(SIDCHECK_ALT) failed');
	}
}

// Start session
check_session_sidpatch_and_start();




// Load MySQL Class and initiate connection
/*MARKER*/require_once(BASE_PATH . '/lib/class/mysql.class.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');



// Check first whether installation directory exists
$cfg['install_dir_exists'] = (is_dir(BASE_PATH . '/_install/') && is_file(BASE_PATH . '/_install/index.php'));
$cfg['install_dir_override'] = ($cfg['IN_DEVELOPMENT_ENVIRONMENT'] || is_file(BASE_PATH . '/_install/install_check_override.txt'));

if ($cfg['install_dir_exists'] && !$cfg['install_dir_override'])
{
	header('Location: ' . makeAbsoluteURI('./_install/index.php'));
	exit();
}

/*
 * initiate database connection; do this AFTER checking for the _install directory, because
 * otherwise error reports from this init will have precedence over the _install-dir-exists
 * error report!
 */
$db = new MySQL();


// LANGUAGE ==
// multilingual support per page through language cfg override:
$language = getGETparam4IdOrNumber('lang');
if (empty($language))
{
	$language = $cfg['language'];
}
// blow away $cfg['language'] to ensure the language file(s) are loaded this time - it's our first anyhow.
unset($cfg['language']);
$language = SetUpLanguageAndLocale($language);



// SECURITY ==
// Include security file only for administration directory
$location = explode("/", $_SERVER['PHP_SELF']);
$php_src_is_admin_code = in_array("admin", $location);
if($php_src_is_admin_code)
{
	/*MARKER*/require_once(BASE_PATH . '/admin/includes/security.inc.php');
}



// DATABASE ==
// All set! Now this statement will connect to the database
if(!$db->Open($cfg['db_name'], $cfg['db_host'], $cfg['db_user'], $cfg['db_pass']))
{
	$db->Kill($ccms['lang']['system']['error_database']);
}

// ENVIRONMENT ==
// Some variables to help this file orientate on its environment
$current = basename(filterParam4FullFilePath($_SERVER['REQUEST_URI']));


// [i_a] $curr_page was identical (enough) to $pagereq before
$pagereq = checkSpecialPageName(getGETparam4Filename('page'), SPG_GIVE_PAGENAME);
$ccms['pagereq'] = $pagereq;

$ccms['printing'] = getGETparam4boolYN('printing', 'N');

$preview = getGETparam4IdOrNumber('preview'); // in fact, it's a hash plus ID!
$preview = IsValidPreviewCode($preview);
$ccms['preview'] = ($preview ? 'Y' : 'N');

//$ccms['responsecode'] = null; // default: 200 : OK
//$ccms['page_id'] = false;
//$ccms['page_name'] = false;
//$ccms['content'] = false;
//$ccms['template'] = null;



// This files' current version
$ccms['ccms_version'] = $v = "1.4.2";






// preparation for plugins, et.c which want to load JavaScript files through the template:
$ccms['CSS.required_files'] = array();
$ccms['CSS.inline'] = array();
$ccms['JS.required_files'] = array();
$ccms['JS.done'] = array();






// TEMPLATES ==
// Read and list the available templates
if ($handle = @opendir(BASE_PATH . '/lib/templates/'))
{
	$template = array();

	while (false !== ($file = readdir($handle)))
	{
		if ($file != "." && $file != ".." && strmatch_tail($file, ".tpl.html"))
		{
			// Add the templates to an array for use through-out CCMS, while removing the extension .tpl.html (=9)
			$template_name = substr($file,0,-9);
			if ($template_name != $cfg['default_template'])
			{
				$template[] = substr($file,0,-9);
			}
		}
	}
	closedir($handle);

	// sort the order of the templates; also make sure that the 'default' template is placed at index [0] so that 404 pages and others pick that one.
	sort($template, SORT_LOCALE_STRING);
	if (!empty($cfg['default_template']))
	{
		array_unshift($template, $cfg['default_template']);
	}
	$ccms['template_collection'] = $template;
}
else
{
	die($ccms['lang']['system']['error_templatedir']);
}

// GENERAL FUNCTIONS ==
// [i_a] moved to common.inc.php


// Fill active module array and load the plugin code
$modules = $db->SelectArray($cfg['db_prefix'].'modules');
if ($db->ErrorNumber())
	$db->Kill();
// add index to quickly access items in the $modules array:
$modules_index = array();
for($i = count($modules) - 1; $i >= 0; $i--)
{
	$modules_index[$modules[$i]['modName']] = $i;
}



// load the global permissions so we can ask what a given user may and may not do later on:
if (checkAuth() && ($php_src_is_admin_code || !empty($_SESSION['ccms_userID'])))
{
	// only do this extra work when we can expect to actually /use/ it:
	$perm = new CcmsGlobalPermissions($db, $cfg['db_prefix']);
}
else
{
	$perm = new CcmsGlobalPermissions(); // NIL permissions
}





/*======================================================================================
 *
 * Only execute the remainder of this file's code if we aren't running a 'minimal' run...
 *
 *======================================================================================*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT'))
{


if($current != "sitemap.php" && $current != 'sitemap.xml' && $pagereq != 'sitemap')
{
	/*
	 * OPERATION MODE ==
	 *
	 * 1) Start normal operation mode (if sitemap.php is not requested directly).
	 *
	 * This will fill all variables based on the requested page, or throw a 403/404 error when applicable.
	 */

	/*
	 * set $ccms[$item] only when it has not been set before.
	 *
	 * Return the actual value of $ccms[$item]
	 */
	function set_ccms_opt($item, $value, $overwrite_on_empty = false)
	{
		global $ccms;

		if (!isset($ccms[$item]))
		{
			$ccms[$item] = $value;
		}
		else if ($overwrite_on_empty && empty($ccms[$item]))
		{
			$ccms[$item] = $value;
		}
		return $ccms[$item];
	}


	function setup_ccms_for_40x_error($rcode, $pagereq)
	{
		global $cfg, $ccms;

		// ERROR 403/404; if not 403, then we default to 404
		// Or if DB query returns zero, show error 404: file does not exist

		set_ccms_opt('module', 'error');
		set_ccms_opt('module_info', null);

		set_ccms_opt('cfg', $cfg);
		set_ccms_opt('language', $cfg['language']);
		set_ccms_opt('tinymce_language', $cfg['language']);
		set_ccms_opt('editarea_language', $cfg['language']);
		set_ccms_opt('sitename', $cfg['sitename']);
		$pagetitle = $ccms['lang']['system']['error_404title'];
		$subheader = $ccms['lang']['system']['error_404header'];
		set_ccms_opt('printable', "N");
		set_ccms_opt('published', "Y");

		$bc = array();
		$bc[] = '<a href="'.$cfg['rootdir'].'" title="'.ucfirst($cfg['sitename']).' '.$ccms['lang']['system']['home'].'">'.$ccms['lang']['system']['home'].'</a>';
		$bc[] = $ccms['lang']['system']['error_404title'];

		set_ccms_opt('iscoding', "Y");
		set_ccms_opt('rootdir', $cfg['rootdir']);
		set_ccms_opt('urlpage', $pagereq); // "404" or 'real' page -- the pagename is already filtered so no bad feedback can happen here, when site is under attack
		$desc = $ccms['lang']['system']['error_404title'];
		set_ccms_opt('desc_extra', '');
		set_ccms_opt('keywords', strval($rcode));
		set_ccms_opt('responsecode', $rcode);
		set_ccms_opt('page_name', $pagereq);
		set_ccms_opt('toplevel', 0);
		set_ccms_opt('sublevel', 0);
		set_ccms_opt('menu_id', 0);
		set_ccms_opt('islink', 'N');

		$tpl = DetermineTemplateName(null, $ccms['printing']);
		$tplel = explode('/', $tpl);
		set_ccms_opt('templatedir', $tplel[0]);
		set_ccms_opt('template', $tpl);

		$content = $ccms['lang']['system']['error_404content'];

		switch ($rcode)
		{
		default:
			// assume 404 ~ default $ccms[] setup above.
			break;

		case 403:
			// patch the $ccms[] data for the 403 error:
			$pagetitle = $ccms['lang']['system']['error_403title'];
			$subheader = $ccms['lang']['system']['error_403header'];

			array_pop($bc);  // ditch the '404' entry and replace it with...
			$bc[] = $ccms['lang']['system']['error_403title'];

			$content = $ccms['lang']['system']['error_403content'];

			$desc = $ccms['lang']['system']['error_403title'];
			break;
		}
		set_ccms_opt('breadcrumb', $bc);

		set_ccms_opt('pagetitle', $pagetitle);
		set_ccms_opt('subheader', $subheader);
		set_ccms_opt('desc', $desc);

		set_ccms_opt('title', ucfirst($ccms['pagetitle']).' - '.$ccms['sitename'].' | '.$ccms['subheader']);

		// even under error conditions, we need the side-effect of the content loader code: a properly initialized template!
		$rendered_page = ccmsContent(null, 'Y', false);
		//$content = $rendered_page['content'];
		//$rcode = $rendered_page['responsecode'];

		set_ccms_opt('content', $content);
	}


	/**
	 * Load the page ($page) Parse contents function when it is either signalled as published ($published),
	 * identified by the preview page ID code ($preview).
	 *
	 * Return ['content'] == FALSE when the page does not exist or could not be loaded due to either the restrictions above
	 * or file system access rights denying the web server read access to the specified page. A suitable HTTP responsecode
	 * will be available in ['responsecode'].
	 *
	 * Return the rendered page content and ['responsecode'] == FALSE otherwise.
	 */
	function ccmsContent($page, $published, $preview)
	{
		/*
		 * Add every item which we have around here and want present in the module page being loaded in here.
		 *   -->
		 * We want the db connection and the config ($cfg) and content ($ccms) arrays available anywhere inside the include()'d content.
		 */
		global $ccms, $cfg, $db, $modules, $modules_index, $v;

		$content = false;
		$ccms_load_failure = false; // this variable should have a name that will not get used/redefined inadvertedly in the loaded PHP page!
		$msg = explode(' ::', $ccms['lang']['hints']['published']);
		ob_start();
			$tpl_init_path = BASE_PATH . '/lib/templates/' . $ccms['templatedir'] . '/init.inc.php';

			// Parse content for active or preview mode
			$filepath = BASE_PATH . '/content/' . $page . '.php';

			if (!empty($page) && is_file($filepath))
			{
				if (is_readable($filepath))
				{
					// Check for preview variable
					//
					// Warning message when page is disabled and authcode is correct
					if ($preview && $published != 'Y')
					{
						echo '<p class="unpublished_preview_note">'.$msg[0].': <strong>'.strtolower($ccms['lang']['backend']['disabled']).'</strong></p>';
					}

					// load template init code BEFORE we load the page content itself:
					if (is_file($tpl_init_path))
					{
						/*MARKER*/include($tpl_init_path);
					}

					/*MARKER*/include($filepath);
				}
				else
				{
					$ccms_load_failure = 403;
				}
			}
			else
			{
				$ccms_load_failure = 404;
			}

			// Make sure to load the template init code at least once, even when the content page couldn't be loaded:
			if ($ccms_load_failure && is_file($tpl_init_path))
			{
				/*MARKER*/include($tpl_init_path);
			}

			// All parsed function contents to $content variable
			$content = ob_get_contents();
		ob_end_clean();

		$rv = array();
		$rv['content'] = $content;
		$rv['responsecode'] = $ccms_load_failure;
		return $rv;
	}



	// collect the menu entries first so we can peruse them in the breadcrumb code below.
	$menu_in_set = '1';
	for($i = 2; $i <= MENU_TARGET_COUNT; $i++)
	{
		$menu_in_set .= ',' . $i;
	}
	$pagelist = $db->SelectArray($cfg['db_prefix'].'pages', "WHERE (`published`='Y'" . ($preview ? " OR `page_id`=" . MySQL::SQLValue($preview, MySQL::SQLVALUE_NUMBER) : '') . ") AND `menu_id` IN (".$menu_in_set.")", null, cvt_ordercode2list('I120'));
	if ($db->ErrorNumber()) $db->Kill();

	// OPERATION MODE ==
	// 2) Start site structure generation to a default maximum of MENU_TARGET_COUNT menus
	// Use the various menu item variables to get a dynamic structured list (ul). Current item marked with class="current".

	// more flexible approach than before; fewer queries (1 instead of 6..10) to boot.
	// flexibility in the sense that when user has assigned same top/sub numbers to multiple entries, this version will not b0rk
	// but dump the items in alphabetic order instead.
	// Also, when sub menu items with a top# that has no entry itself, is found, such an item will be assigned a 'dummy' top node.

	if(is_array($pagelist) && count($pagelist) > 0)
	{
		$current_menuID = 0;
		$current_top = 0;
		$current_structure = null;
		$top_idx = 0;
		$sub_idx = 0;
		$sub_done = false;
		$dummy_top_written = false;

		/*
		When a submenu item is located which doesn't have a proper topmenu item set up as well, a dummy top is written.

		To simplify the flow within the loop, the loop is executed /twice/ for such elements: the first time through,
		the top item will be written (as if it existed in the database), the next time through the subitem itself is
		generated.

		The same re-cycling mode is used to switch from one menu to the next (note the 'continue;' in there).
		*/
		for ($i = 0; $dummy_top_written || $i < count($pagelist); )
		{
			if (!$dummy_top_written)
			{
				$row = $pagelist[$i++];
			}
			$dummy_top_written = false;

			// whether we have found the (expectedly) accompanying toplevel menu item.
			$top_done = ($row['sublevel'] != 0 && $row['toplevel'] == $current_top && $row['menu_id'] == $current_menuID);

			if ($row['menu_id'] != $current_menuID)
			{
				if ($current_top > 0)
				{
					// terminate generation of previous menu
					if ($sub_done)
					{
						$ccms[$current_structure] .= "</li></ul>\n";
					}
					$ccms[$current_structure] .= "</li></ul>\n";
				}

				// forward to next menu...
				$current_menuID = $row['menu_id'];
				$current_top = 0;
				$current_structure = 'structure' . $current_menuID;
				$top_idx = 0;
				$sub_idx = 0;
				$sub_done = false;

				// Start this menu root item: UL
				$ccms[$current_structure] = '<ul>';

				// prevent loading the next record on the next round through the loop:
				$dummy_top_written = true;
				continue;
			}
			else if ($row['toplevel'] != $current_top || $row['sublevel'] == 0)
			{
				// terminate generation of previous submenu
				if ($current_top > 0)
				{
					if ($sub_done)
					{
						$ccms[$current_structure] .= "</li></ul>\n";
					}
					$ccms[$current_structure] .= "</li>\n";
				}

				$current_top = $row['toplevel'];
				$top_idx++;
				$sub_idx = 0;
				$sub_done = false;

				if (!$top_done && $row['sublevel'] != 0)
				{
					// write a dummy top
					$dummy_top_written = true;
				}
			}
			else if ($row['sublevel'] != 0)
			{
				if ($sub_done)
				{
					$ccms[$current_structure] .= "</li>\n";
				}
				else
				{
					$ccms[$current_structure] .= "\n<ul class=\"sublevel\">\n";
				}
				$sub_idx++;
				$sub_done = true;
			}

			// Specify special link attributes if applicable
			$current_class = '';
			$current_extra = '';
			$current_link = '';
			if ($row['urlpage'] == $pagereq)
			{
				// 'home' has a pagereq=='', but we still want to see the 'current' class for that one.
				// (The original code didn't do this, BTW!)
				$current_class = 'current';
			}

			$msg = explode(' ::', $row['description'], 2);
			$current_descr = $msg[0];
			$current_extra = (!empty($msg[1]) ? trim($msg[1]) : '');

			$menu_item_class = '';
			if ($dummy_top_written)
			{
				$current_class = '';
				$menu_item_class = 'menu_item_dummy';
			}
			else if ($row['islink'] != "Y")
			{
				$current_link = '#';
				$menu_item_class = 'menu_item_nolink';
			}
			else if (regexUrl($current_descr))
			{
				$current_class = 'to_external_url';
				$menu_item_class = 'menu_item_extref';
				$current_link = $msg[0];
			}
			else if (($msv = checkSpecialPageName($row['urlpage'], SPG_GIVE_MENU_SPECIAL)) !== null)
			{
				$current_link = $msv['link'];
				$menu_item_class = $msv['class'];
			}
			else
			{
				$current_link = $cfg['rootdir'] . $row['urlpage'] . '.html';
			}

			if (!empty($current_extra))
			{
				$current_extra = '<div class="menu_item_extra">' . $current_extra . '</div>';
			}


			// What text to show for the links
			$link_text = ucfirst($row['pagetitle']);
			$link_title = ucfirst($row['subheader']);

			$current_link_classes = trim($current_class . ' ' . $menu_item_class);
			if (!empty($current_link_classes))
			{
				$current_link_classes = 'class="' . $current_link_classes . '"';
			}
			$menu_item_text = '<a '.$current_link_classes.' href="'.$current_link.'" title="'.$link_title.'">'.$link_text.'</a>'.$current_extra;

			$menu_top_class = 'menu_item' . ($top_idx % 2);
			$menu_sub_class = 'menu_item' . ($sub_idx % 2);

			if ($dummy_top_written)
			{
				$menu_item_text = '<span ' . $current_link_classes . '>-</span>';
				$ccms[$current_structure] .= '<li class="' . trim( /* $current_class . ' ' . */ $menu_top_class . ' ' . $menu_item_class) . '">' . $menu_item_text;
			}
			else if ($row['sublevel'] != 0)
			{
				$ccms[$current_structure] .= '<li class="' . trim($current_class . ' ' . $menu_sub_class . ' ' . $menu_item_class) . '">' . $menu_item_text;
			}
			else
			{
				$ccms[$current_structure] .= '<li class="' . trim($current_class . ' ' . $menu_top_class . ' ' . $menu_item_class) . '">' . $menu_item_text;
			}
		}

		// now that we're done in the loop, terminate the last menu:
		if ($current_top > 0)
		{
			// terminate generation of previous menu
			if ($sub_done)
			{
				$ccms[$current_structure] .= '</li></ul>';
			}
			$ccms[$current_structure] .= '</li></ul>';
		}
	}

	/*
	 * OPERATION MODE ==
	 *
	 * Select the appropriate statement (home page versus specified page)
	 *
	 * This is a separate query for two reasons:
	 *
	 * (1) the above might be cached as a whole one day, and
	 *
	 * (2) the requested page doesn't necessarily need to appear in any menu!
	 *
	 * - We fetch the page
	 * - If it does not exist (or when there's no database record at all), we
	 *   try to fetch a suitable error page
	 * - If that one isn't available for whatever reason, we default to a basic
	 *   error response (the content of which depending on the situation at hand
	 *
	 * Hence we run up to two rounds below: one round for good pages; the second
	 * round is for when we need to report an error (through a user-editable error page)
	 *
	 * NOTE: since we wish to keep as much info from the intended page as possible,
	 *       we mix&merge the two rows in the second round.
	 */
	$dbpage = $pagereq;
	$pagerow = null;
	$rcode = (is_http_response_code($pagereq) ? intval($pagereq) : false);
	//set_ccms_opt('content', '<pre>rcode = ' . $rcode . ', ' . $pagereq);

	for ($round = 0; $round < 2; $round++)
	{
		$row = $db->SelectSingleRow($cfg['db_prefix'].'pages', array('urlpage' => MySQL::SQLValue($dbpage, MySQL::SQLVALUE_TEXT)));
		if ($db->ErrorNumber()) $db->Kill();

		if($row)
		{
			// don't collect ANY data from unpublished pages (security issue: don't leak titles, etc. when marked as UNpublished!)
			if($preview || $row->published == 'Y')
			{
				if (!$pagerow)
				{
					$pagerow = $row;
				}
				else
				{
					// mix original data into the error record:
				}

				// Internal reference
				set_ccms_opt('published', $row->published);
				set_ccms_opt('iscoding', $row->iscoding);

				// Content variables
				set_ccms_opt('cfg', $cfg);
				set_ccms_opt('page_id', $row->page_id);
				set_ccms_opt('page_name', $row->urlpage);
				set_ccms_opt('language', $cfg['language']);
				set_ccms_opt('tinymce_language', $cfg['language']);
				set_ccms_opt('editarea_language', $cfg['language']);
				set_ccms_opt('sitename', $cfg['sitename']);
				set_ccms_opt('rootdir', $cfg['rootdir']);
				set_ccms_opt('urlpage', $row->urlpage);
				set_ccms_opt('pagetitle', $row->pagetitle);
				set_ccms_opt('subheader', $row->subheader);

				// mirror the menu builder below; orthogonal code: ALL descriptions with a ' ::' in there are split, not just the ones with a URL inside.
				$msg = explode(' ::', $row->description, 2);
				set_ccms_opt('desc', $msg[0]);
				set_ccms_opt('desc_extra', (!empty($msg[1]) ? trim($msg[1]) : ''));

				set_ccms_opt('keywords', $row->keywords);
				set_ccms_opt('title', ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader']);
				set_ccms_opt('printable', $row->printable);
				//set_ccms_opt('responsecode', null);           // default: 200 : OK
				$m_id = intval($row->menu_id);
				set_ccms_opt('menu_id', $m_id);
				if ($m_id)
				{
					set_ccms_opt('toplevel', intval($row->toplevel));
					set_ccms_opt('sublevel', intval($row->sublevel));
				}
				else
				{
					set_ccms_opt('toplevel', 0);
					set_ccms_opt('sublevel', 0);
				}
				set_ccms_opt('islink', $row->islink);

				// TEMPLATING ==
				// Check whether template exists, specify default or throw "no templates" error.
				$tpl = DetermineTemplateName($row->variant, $ccms['printing']);
				$tplel = explode('/', $tpl);
				set_ccms_opt('templatedir', $tplel[0]);
				set_ccms_opt('template', $tpl);

				set_ccms_opt('module', $row->module);
				set_ccms_opt('module_info', $modules[$modules_index[$row->module]]);

				if (0)
				{
					// create a plugin/module instance tailored to this particular page
					if(is_object($modules[$row->module]) && method_exists($modules[$row->module], 'getInstance'))
					{
						set_ccms_opt('module_instance', $modules[$row->module]->getInstance($ccms));
						if (!is_object($ccms['module_instance']))
						{
							die('FATAL: module ' . $row->module . ' failed to initialize for page ' . $row->urlpage);
						}
					}
				}

				// BREADCRUMB ==
				// Create breadcrumb for the current page
				$preview_checkcode = GenerateNewPreviewCode($row->page_id, null);
				set_ccms_opt('previewcode', $preview_checkcode);

				$preview_qry = ($preview ? '?preview=' . $preview_checkcode : '');

				$bc = array();
				$bc[] = '<a href="'.$cfg['rootdir'].$preview_qry.'" title="'.ucfirst($cfg['sitename']).' '.$ccms['lang']['system']['home'].'">'.$ccms['lang']['system']['home'].'</a>';
				if(!checkSpecialPageName($row->urlpage, SPG_IS_HOMEPAGE))
				{
					if($row->sublevel == 0)
					{
						$bc[] = '<a href="'.$cfg['rootdir'].$row->urlpage.'.html'.$preview_qry.'" title="'.$row->subheader.'">'.$row->pagetitle.'</a>';
					}
					else
					{
						// sublevel page
						if (is_array($pagelist) && count($pagelist) > 0)
						{
							foreach($pagelist as $entry)
							{
								if ($entry['menu_id'] == $row->menu_id // make sure to check the menu_id: we want OUR parent, which sits in OUR menu!
									&& $entry['sublevel'] == 0  // accepts NULL or 0 which exactly what we want here!
									&& $entry['toplevel'] == $row->toplevel)
								{
									$bc[] = '<a href="'.$cfg['rootdir'].$entry['urlpage'].'.html'.$preview_qry.'" title="'.$entry['subheader'].'">'.$entry['pagetitle'].'</a>';
									break;
								}
							}
						}

						$bc[] = '<a href="'.$cfg['rootdir'].$row->urlpage.'.html'.$preview_qry.'" title="'.$row->subheader.'">'.$row->pagetitle.'</a>';
					}
				}
				set_ccms_opt('breadcrumb', $bc);


				/*
				 * The CONTENT collection should be the very last thing happening.
				 *
				 * This is important for 'code' pages: these can only now assume that all
				 * $ccms[] entries for the current page have been set up completely.
				 * ATM no modules use this assumption (for example to modify/postprocess the $ccms[]
				 * data) but this code flow enables the existence of such modules (plugins)
				 * as they are loaded through the 'iscoding'-marked page.
				 */
				$rendered_page = ccmsContent($row->urlpage, $row->published, $preview);
				$content = $rendered_page['content'];
				if (!$rcode)
				{
					$rcode = $rendered_page['responsecode'];
				}

				if ($content === false)
				{
					// failure occurred! produce a 'response code page' after all!
					if (!$rcode)
					{
						$rcode = 404;
					}
					set_ccms_opt('responsecode', $rcode);
					//setup_ccms_for_40x_error($rcode, $pagereq);

					$dbpage = $rcode;

					// we now know we're in a state of error handling: loop so we use the second round to fetch the error page itself.
					continue;
				}
				else
				{
					set_ccms_opt('content', $content);
					set_ccms_opt('responsecode', $rcode);
				}

				// we're done!
				break;
			}
			else
			{
				// Parse 403 contents (disabled and no preview token)
				$content = false;
				if (!$rcode)
				{
					$rcode = 403;
				}
				set_ccms_opt('page_name', $pagereq);
				set_ccms_opt('responsecode', $rcode);

				$dbpage = $rcode;

				// loop so we use the second round to fetch the error page itself.
			}
		}
		else
		{
			// if DB query returns zero, show error 404: file does not exist
			//$ccms['page_id'] = false;
			//$ccms['page_name'] = false;

			$content = false;
			if (!$rcode)
			{
				$rcode = 404;
			}
			set_ccms_opt('page_name', $pagereq);
			set_ccms_opt('responsecode', $rcode);

			$dbpage = $rcode;

			// loop so we use the second round to fetch the error page itself.
		}
	} // end of 2-round loop

	if ($content === false || $rcode !== false)
	{
		// failure occurred! produce a 'response code page' after all!

		if (!$rcode)
		{
			$rcode = 404;
		}
		setup_ccms_for_40x_error($rcode, $pagereq);
	}

	if (is_http_response_code($ccms['responsecode']))
	{
		send_response_status_header($ccms['responsecode']);
	}
}
else /* if($current == "sitemap.php" || $current == "sitemap.xml") */   // [i_a] if() removed so the GET URL index.php?page=sitemap doesn't slip through the cracks.
{
	/*
	 * OPERATION MODE ==
	 *
	 * 3) Start dynamic sitemap creation used by spiders and various webmaster tools.
	 *
	 * e.g. You can use this function to submit a dynamic sitemap to Google Webmaster Tools.
	 */

	$dir = $cfg['rootdir'];   // [i_a] the original substr($_SERVER[]) var would fail when called with this req URL: index.php?page=sitemap

	/*
	 Start generating sitemap

	 See also: http://hsivonen.iki.fi/producing-xml/
	*/
	header("content-type: application/xml");

	echo <<<EOT42
<?xml version="1.0" encoding="UTF-8"?>
<urlset
	xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
EOT42;

	// Select all published pages
	$rows = $db->SelectObjects($cfg['db_prefix'].'pages', array('published' => "'Y'"), array('urlpage', 'description', 'islink'));
	if ($rows === false) $db->Kill();

	foreach($rows as $row)
	{
		// Do not include external links in sitemap
		if(!regexUrl($row->description))
		{
			echo "<url>\n";
				if(($ssv = checkSpecialPageName($row->urlpage, SPG_GIVE_SITEMAP_SPECIAL)) !== null)
				{
					if (!empty($ssv['loc']))
					{
						echo '<loc>' . $ssv['loc'] . "</loc>\n";
						echo '<priority>' . $ssv['prio'] . "</priority>\n";
					}
				}
				else if($row->islink == 'N')
				{
					// [i_a] put pages which are not accessible through the menus (and thus the home/index page, at a higher scan priority.
					echo "<loc>http://".$_SERVER['SERVER_NAME'].''.$dir.''.$row->urlpage.".html</loc>\n";
					echo "<priority>0.70</priority>\n";
				}
				else
				{
					echo "<loc>http://".$_SERVER['SERVER_NAME'].''.$dir.''.$row->urlpage.".html</loc>\n";
					echo "<priority>0.50</priority>\n";
				}
			echo "<changefreq>weekly</changefreq>\n";
			echo "</url>\n";
		}
	}
	echo "</urlset>\n";

	exit(); // [i_a] exit now; no need nor want to run the XML through the template engine
}


} // if (!defined('CCMS_PERFORM_MINIMAL_INIT'))


?>