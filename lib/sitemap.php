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
		Before we set the sessionID, we'd better make darn sure it's a legitimate request instead of a hacker trying to get in:
		
		however, before we can access any $_SESSION[] variables do we have to load the session for the given ID.
		*/
		session_id($sesid);
		session_start();
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
		session_start();
	}
}

// Start session
check_session_sidpatch_and_start();




// Load MySQL Class and initiate connection
/*MARKER*/require_once(BASE_PATH . '/lib/class/mysql.class.php');

// Load generic functions
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');



// Check first whether installation directory exists
if(is_dir('./_install/') && is_file('./_install/index.php') && !$cfg['IN_DEVELOPMENT_ENVIRONMENT']) 
{
	header('Location: ' . makeAbsoluteURI('./_install/index.php'));
	exit();
}

/*
 initiate database connection; do this AFTER checking for the _install directory, because 
 otherwise error reports from this init will have precedence over the _install-dir-exists 
 error report!
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
if(in_array("admin",$location)) 
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
$pagereq = getGETparam4Filename('page');
$ccms['pagereq'] = $pagereq;

$ccms['printing'] = getGETparam4boolYN('printing', 'N');

$preview = getGETparam4IdOrNumber('preview'); // in fact, it's a hash plus ID!
$preview = IsValidPreviewCode($preview);
$ccms['preview'] = $preview;


// This files' current version
$ccms['ccms_version'] = $v = "1.4.2";

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
$modules = $db->SelectArray($cfg['db_prefix'].'modules', array('modActive' => "'1'"));
if (!$modules)
	$db->Kill();



// only execute the remainder of this file's code if we aren't running a 'minimal' run...
if (!defined('CCMS_PERFORM_MINIMAL_INIT'))
{


// OPERATION MODE ==
// 1) Start normal operation mode (if sitemap.php is not requested directly).
// This will fill all variables based on the requested page, or throw a 403/404 error when applicable.
if($current != "sitemap.php" && $current != "sitemap.xml" && $pagereq != "sitemap") 
{
	function setup_ccms_for_40x_error($code, $pagereq)
	{
		global $cfg, $ccms;
		
		// ERROR 403/404; if not 403, then we default to 404
		// Or if DB query returns zero, show error 404: file does not exist
		
		$ccms['module']      = 'error';
		$ccms['module_path'] = null;

		$ccms['language']   = $cfg['language'];
		$ccms['tinymce_language']   = $cfg['language'];
		$ccms['editarea_language']   = $cfg['language'];
		$ccms['sitename']   = $cfg['sitename'];
		$ccms['pagetitle']  = $ccms['lang']['system']['error_404title'];
		$ccms['subheader']  = $ccms['lang']['system']['error_404header'];
		$ccms['printable']  = "N";
		$ccms['published']  = "Y";
		// [i_a] fix: close <span> here as well
		$ccms['breadcrumb'] = '<span class="breadcrumb">&raquo; <a href="'.$cfg['rootdir'].'" title="'.ucfirst($cfg['sitename']).' Home">Home</a> &raquo '.$ccms['lang']['system']['error_404title'].'</span>';
		$ccms['iscoding']   = "Y";
		$ccms['rootdir']    = (substr($cfg['rootdir'],-1)!=='/'?$cfg['rootdir'].'/':$cfg['rootdir']);
		$ccms['urlpage']    = $pagereq; // "404" or 'real' page -- the pagename is already filtered so no bad feedback can happen here, when site is under attack
		$ccms['desc']       = $ccms['lang']['system']['error_404title'];
		$ccms['keywords']   = strval($code);
		$ccms['responsecode'] = $code;
		$ccms['toplevel']   = 0;
		$ccms['sublevel']   = 0;
		$ccms['menu_id']    = 0;
		$ccms['islink']     = 'N';

		$ccms['template'] = DetermineTemplateName(null, $ccms['printing']);
		
		switch ($code)
		{
		default:
			// assume 404 ~ default $ccms[] setup above.
			break;
			
		case 403:
			// patch the $ccms[] data for the 403 error:
			$ccms['pagetitle']  = $ccms['lang']['system']['error_403title'];
			$ccms['subheader']  = $ccms['lang']['system']['error_403header'];
			$ccms['breadcrumb'] = '<span class="breadcrumb">&raquo; <a href="'.$cfg['rootdir'].'" title="'.ucfirst($cfg['sitename']).' Home">Home</a> &raquo '.$ccms['lang']['system']['error_403title'].'</span>';
			//$ccms['urlpage']    = "403";
			$ccms['desc']       = $ccms['lang']['system']['error_403title'];
			//$ccms['keywords']   = "403";
			break;
		}

		$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
	}
	

	/**
	 Parse contents function
	 */
	function ccmsContent($page, $published, $preview, $force_load = false) 
	{
		/*
		Add every item which we have around here and want present in the module page being loaded in here.
		-->
		We want the db connection and the config ($cfg) and content ($ccms) arrays available anywhere inside the include()'d content.
		*/
		global $ccms, $cfg, $db, $modules, $v;
		
		$content = false;
		$failure = false;
		$msg = explode(' ::', $ccms['lang']['hints']['published']);
		ob_start();
			// Check for preview variable
			//
			// Warning message when page is disabled and authcode is correct
			if ($preview && $published != 'Y')
			{ 
				echo '<p class="unpublished_preview_note">'.$msg[0].': <strong>'.strtolower($ccms['lang']['backend']['disabled']).'</strong></p>';
			}

			// Parse content for active or preview mode
			if ($published=='Y' || $preview || $force_load)
			{
				$filepath = BASE_PATH. "/content/".$page.".php";
				
				if (is_file($filepath))
				{
					/*MARKER*/include($filepath);
				}
				else
				{
					$failure = 404;
				}
			}
			else  
			{ 
				// Parse 403 contents (disabled and no preview token)
				$failure = 403;
			}
			// All parsed function contents to $content variable
			$content = ob_get_contents();
		ob_end_clean();
		
		if ($failure && empty($ccms['responsecode']))
		{
			$ccms['responsecode'] = $failure;
			return false;
		}
		return $content;
	}

	// collect the menu entries first so we can peruse them in the breadcrumb code below.
	$menu_in_set = '1';
	for($i = 2; $i <= MENU_TARGET_COUNT; $i++) 
	{
		$menu_in_set .= ',' . $i;
	}
	$pagelist = $db->SelectArray($cfg['db_prefix'].'pages', "WHERE (`published`='Y'" . ($preview ? " OR `page_id`=" . MySQL::SQLValue($preview, MySQL::SQLVALUE_NUMBER) : '') . ") AND `menu_id` IN (".$menu_in_set.")", null, cvt_ordercode2list('I120'));
	if ($db->ErrorNumber()) $db->Kill();

	// Select the appropriate statement (home page versus specified page)
	//
	// This is a separate query for two reasons:
	// (1) the above might be cached as a whole one day, and 
	// (2) the requested page doesn't necessarily need to appear in any menu!
	$row = $db->SelectSingleRow($cfg['db_prefix'].'pages', array('urlpage' => MySQL::SQLValue((!empty($pagereq) ? $pagereq : 'home'), MySQL::SQLVALUE_TEXT))); 
	if ($db->ErrorNumber()) $db->Kill();

	// Start switch for pages, select all the right details
	if($row) 
	{
		// Internal reference
		$ccms['published']  = $row->published;
		$ccms['iscoding']   = $row->iscoding;

		// Content variables
		$ccms['language']   = $cfg['language'];
		$ccms['tinymce_language']   = $cfg['language'];
		$ccms['editarea_language']   = $cfg['language'];
		$ccms['sitename']   = $cfg['sitename'];
		$ccms['rootdir']    = (substr($cfg['rootdir'],-1)!=='/'?$cfg['rootdir'].'/':$cfg['rootdir']);
		$ccms['urlpage']    = $row->urlpage;
		$ccms['pagetitle']  = $row->pagetitle;
		$ccms['subheader']  = $row->subheader;
		
		// mirror the menu bielder below; orthogonal code: ALL descriptions with a ' ::' in there are split, not just the ones with a URL inside.
		$msg = explode(' ::', $row->description, 2);
		$ccms['desc']       = $msg[0];
		$ccms['desc_extra'] = (!empty($msg[1]) ? trim($msg[1]) : '');
		
		$ccms['keywords']   = $row->keywords;
		$ccms['title']      = ucfirst($ccms['pagetitle'])." - ".$ccms['sitename']." | ".$ccms['subheader'];
		$ccms['printable']  = $row->printable;
		$ccms['responsecode'] = 0; // default: 200 : OK
		$ccms['toplevel']   = $row->toplevel;
		$ccms['sublevel']   = $row->sublevel;
		$ccms['menu_id']    = $row->menu_id;
		$ccms['islink']     = $row->islink;

		// TEMPLATING ==
		// Check whether template exists, specify default or throw "no templates" error.
		$ccms['template'] = DetermineTemplateName($row->variant, $ccms['printing']);

		$ccms['module']      = $row->module;
		
if (0)
{
		// create a plugin/module instance tailored to this particular page
		if($row->module != "editor" && is_object($modules[$row->module]) && method_exists($modules[$row->module], 'getInstance')) 
		{
			$ccms['module_instance'] = $modules[$row->module]->getInstance($ccms);
			if (!is_object($ccms['module_instance']))
			{
				die('FATAL: module ' . $row->module . ' failed to initialize for page ' . $row->urlpage);
			}
		}
}
		
		// BREADCRUMB ==
		// Create breadcrumb for the current page
		$preview_checkcode = GenerateNewPreviewCode($row->page_id, null);
		
		$preview_qry = ($preview ? '?preview=' . $preview_checkcode : '');
		if($row->urlpage=="home") 
		{
			$ccms['breadcrumb'] = '<span class="breadcrumb">&raquo; <a href="'.$cfg['rootdir'].$preview_qry.'" title="'.ucfirst($cfg['sitename']).' Home">Home</a></span>';
		}
		else 
		{
			if($row->sublevel==0) 
			{
				$ccms['breadcrumb'] = '<span class="breadcrumb">&raquo; <a href="'.$cfg['rootdir'].$row->urlpage.'.html'.$preview_qry.'" title="'.$row->subheader.'">'.$row->pagetitle.'</a></span>';
			}
			else 
			{ 
				// sublevel page
				$parent = null;
				if (is_array($pagelist) && count($pagelist) > 0)
				{
					foreach($pagelist as $entry)
					{
						if ($entry['menu_id'] == $row->menu_id // make sure to check the menu_id: we want OUR parent, which sits in OUR menu!
							&& $entry['sublevel'] == 0  // accepts NULL or 0 which exactly what we want here!
							&& $entry['toplevel'] == $row->toplevel)
						{
							$parent = $entry;
							break;
						}
					}
				}
				
				if ($parent)
				{
					$ccms['breadcrumb'] = '<span class="breadcrumb">&raquo; <a href="'.$cfg['rootdir'].$parent['urlpage'].'.html'.$preview_qry.'" title="'.$parent['subheader'].'">'.$parent['pagetitle'].'</a>'
							. ' &raquo; <a href="'.$cfg['rootdir'].$row->urlpage.'.html'.$preview_qry.'" title="'.$row->subheader.'">'.$row->pagetitle.'</a></span>';
				}
				else
				{
					// no main node record found! get us 'home'!
					$ccms['breadcrumb'] = '<span class="breadcrumb">&raquo; <a href="'.$cfg['rootdir'].$preview_qry.'" title="'.ucfirst($cfg['sitename']).' Home">Home</a>'
							. ' &raquo; <a href="'.$cfg['rootdir'].$row->urlpage.'.html'.$preview_qry.'" title="'.$row->subheader.'">'.$row->pagetitle.'</a></span>';
				}
			}
		}
	} 
	else 
	{
		// ERROR 403/404; if not 403, then we default to 404
		// Or if DB query returns zero, show error 404: file does not exist
		
		setup_ccms_for_40x_error((get_response_code_string($pagereq, false) !== false ? intval($pagereq) : 404), $pagereq);
	}


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
			if ($row['urlpage'] == $pagereq || (empty($pagereq) && $row['urlpage'] == "home"))
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
			else if ($row['urlpage'] == "home")
			{
				$current_link = $cfg['rootdir'];
				$menu_item_class = 'menu_item_home';
			}
			else 
			{
				$current_link = $cfg['rootdir'] . $row['urlpage'] . '.html';
			}
			
			if (!empty($current_extra))
			{
				$current_extra = "\n<br/>\n" . $current_extra;
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
	The CONTENT collection should be the very last thing happening. 
	
	This is important for 'code' pages: these can only now assume that all
	$ccms[] entries for the current page have been set up completely.
	ATM no modules use this assumption (for example to modify/postprocess the $ccms[]
	data) but this code flow enables the existence of such modules (plugins)
	as they are loaded through the 'iscoding'-marked page.
	*/
	$ccms['content'] = ccmsContent($ccms['urlpage'], $ccms['published'], $preview);
	if ($ccms['content'] === false)
	{
		// failure occurred! produce a 'response code page' after all!
		
		$rcode = (is_http_response_code(intval($ccms['responsecode'])) ? intval($ccms['responsecode']) : 404);
		setup_ccms_for_40x_error($rcode, $pagereq);
		// and fetch the page equaling the responsecode:
		$ccms['content'] = ccmsContent($rcode, $ccms['published'], $preview, true);
	}
	
	if ($ccms['responsecode'] > 0)
	{
		send_response_status_header($ccms['responsecode']);
	}
}

// OPERATION MODE ==
// 3) Start dynamic sitemap creation used by spiders and various webmaster tools.
// e.g. You can use this function to submit a dynamic sitemap to Google Webmaster Tools.
else /* if($current == "sitemap.php" || $current == "sitemap.xml") */   // [i_a] if() removed so the GET URL index.php?page=sitemap doesn't slip through the cracks.
{
	$dir = $cfg['rootdir'];   // [i_a] the original substr($_SERVER[]) var would fail when called with this req URL: index.php?page=sitemap

	/*
	 Start generating sitemap
	 
	 See also: http://hsivonen.iki.fi/producing-xml/
	*/
	header ("content-type: application/xml");

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
	?>
	<urlset
		xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<?php
	// Select all published pages
	$rows = $db->SelectObjects($cfg['db_prefix'].'pages', array('published' => "'Y'"), array('urlpage', 'description', 'islink'));
	if ($rows === false) $db->Kill();

	foreach($rows as $row)
	{
		// Do not include external links in sitemap
		if(!regexUrl($row->description)) 
		{
			echo "<url>\n";
				if($row->urlpage == "home") 
				{
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."</loc>\n";
					echo "<priority>0.80</priority>\n";
				} 
				else if($row->islink == 'N') 
				{
					// [i_a] put pages which are not accessible through the menus (and thus the home/index page, at a higher scan priority.
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."".$row->urlpage.".html</loc>\n";
					echo "<priority>0.70</priority>\n";
				} 
				else 
				{
					echo "<loc>http://".$_SERVER['SERVER_NAME']."".$dir."".$row->urlpage.".html</loc>\n";
					echo "<priority>0.50</priority>\n";
				}
			echo "<changefreq>weekly</changefreq>\n";
			echo "</url>\n";
		}
	}
	echo "</urlset>";
	
	exit(); // [i_a] exit now; no need nor want to run the XML through the template engine
}


} // if (!defined('CCMS_PERFORM_MINIMAL_INIT'))


?>