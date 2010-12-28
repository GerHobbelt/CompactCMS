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
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Compress all output and coding
header('Content-type: text/html; charset=UTF-8');

// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(dirname(__FILE__))));
	define('BASE_PATH', $base);
}

// Include general configuration
/*MARKER*/require_once(BASE_PATH . '/lib/sitemap.php');

class FbX extends CcmsAjaxFbException {}; // nasty way to do 'shorthand in PHP -- I do miss my #define macros! :'-|

// Some security functions


/* make darn sure only authenticated users can get past this point in the code */
if(empty($_SESSION['ccms_userID']) || empty($_SESSION['ccms_userName']) || !CheckAuth()) 
{
	// this situation should've caught inside sitemap.php-->security.inc.php above! This is just a safety measure here.
	die($ccms['lang']['auth']['featnotallowed']); 
}


if(!isset($_SESSION['rc1']) || !isset($_SESSION['rc2'])) 
{
	$_SESSION['rc1'] = mt_rand('12345', '98765'); 
	$_SESSION['rc2'] = mt_rand('1234', '9876');
}

// Prevent PHP warning by setting default (null) values
$do_action = getGETparam4IdOrNumber('action');


// Get permissions
$perm = $db->SelectSingleRowArray($cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

// Fill active module array
// $modules = $db->SelectArray($cfg['db_prefix'].'modules', array('modActive' => "'1'"));    // [i_a] already collected in sitemap.php 


$do_update_or_livefilter = (($do_action == "update" && $_SERVER['REQUEST_METHOD'] != "POST") || ($do_action == "livefilter" && $_SERVER['REQUEST_METHOD'] == "POST"));




// Set the target for PHP processing
$target_form = getPOSTparam4IdOrNumber('form');


/**
 *
 * Render the dynamic list with files
 *
 */
if ($do_update_or_livefilter && checkAuth()) 
{
	$dynlist_sortorder = getGETparam4IdOrNumber('dlorder', $cfg['admin_page_dynlist_order']);
	
	$filter_pages_name = '';
	$filter_pages_title = '';
	$filter_pages_subheader = '';

	$page_selectquery_restriction = '';

	$filter_pages_name = (!empty($_SESSION['filter_pages_name']) ? $_SESSION['filter_pages_name'] : '');
	$filter_pages_title = (!empty($_SESSION['filter_pages_title']) ? $_SESSION['filter_pages_title'] : '');
	$filter_pages_subheader = (!empty($_SESSION['filter_pages_subheader']) ? $_SESSION['filter_pages_subheader'] : '');

	if ($do_action == "livefilter" && $_SERVER['REQUEST_METHOD'] == "POST") 
	{
		switch (getPOSTparam4IdOrNumber('part'))
		{
		default:
			die("Invalid input");
			
		case 'filter_pages_name':
			$filter_pages_name = getPOSTparam4DisplayHTML('content');
			break;
			
		case 'filter_pages_title':
			$filter_pages_title = getPOSTparam4DisplayHTML('content');
			break;
			
		case 'filter_pages_subheader':
			$filter_pages_subheader = getPOSTparam4DisplayHTML('content');
			break;
		}
		$_SESSION['filter_pages_name'] = $filter_pages_name;
		$_SESSION['filter_pages_title'] = $filter_pages_title;
		$_SESSION['filter_pages_subheader'] = $filter_pages_subheader;
	}


	// construct the WHERE clause for the page list now:
	if (!empty($filter_pages_name) || !empty($filter_pages_title) || !empty($filter_pages_subheader))
	{
		if (!empty($filter_pages_name))
		{
			$page_selectquery_restriction = "urlpage LIKE '%" . MySQL::SQLFix($filter_pages_name) . "%'";
		}
		if (!empty($filter_pages_title))
		{
			$page_selectquery_restriction .= (strlen($page_selectquery_restriction) > 0 ? ' AND ' : '');
			$page_selectquery_restriction .= "pagetitle LIKE '%" . MySQL::SQLFix($filter_pages_title) . "%'";
		}
		if (!empty($filter_pages_subheader))
		{
			$page_selectquery_restriction .= (strlen($page_selectquery_restriction) > 0 ? ' AND ' : '');
			$page_selectquery_restriction .= "subheader LIKE '%" . MySQL::SQLFix($filter_pages_subheader) . "%'";
		}
		
		$page_selectquery_restriction = 'WHERE ' . $page_selectquery_restriction;
	}

	
	
	function gen_span4pagelist_filterheader($name, $title)
	{
		global $ccms;
		
		if (!empty($name) && !empty($_SESSION[$name]))
		{
			echo '<span class="sprite livefilter livefilter_active" rel="' . $name . '" title="' . ucfirst($ccms['lang']['forms']['edit_remove']) . ' ' . strtolower($title) . ' -- ' . $ccms['lang']['forms']['filter_showing'] . ': \'' . htmlspecialchars($_SESSION[$name]) . '\'">&#160;</span>';
		}
		else
		{
			echo '<span class="sprite livefilter livefilter_add" rel="' . $name . '" title="' . ucfirst($ccms['lang']['forms']['add']) . ' ' . strtolower($title) . '">&#160;</span>';
		}
	}

	
	// Open recordset for sites' pages
	$rows = $db->SelectObjects($cfg['db_prefix'].'pages', $page_selectquery_restriction, null, cvt_ordercode2list($dynlist_sortorder));
	if ($rows === false) $db->Kill();

	// Check whether the recordset is not empty
	if(count($rows) > 0) 
	{
		?>
		<table id="table_manage" cellspacing="0" cellpadding="0">
			<tr>
				<th class="span-1a">&#160;</th>
				<th class="span-3a nowrap"><?php gen_span4pagelist_filterheader('filter_pages_name', $ccms['lang']['forms']['filename']); echo $ccms['lang']['forms']['filename']; ?><span class="ss_sprite_16 ss_help2" title="<?php echo $ccms['lang']['hints']['filename'] . ' ' . $ccms['lang']['hints']['filter']; ?>">&#160;</span></th>
				<th class="span-4a nowrap"><?php gen_span4pagelist_filterheader('filter_pages_title', $ccms['lang']['forms']['pagetitle']);  echo $ccms['lang']['forms']['pagetitle']; ?><span class="ss_sprite_16 ss_help2" title="<?php echo $ccms['lang']['hints']['pagetitle'] . ' ' . $ccms['lang']['hints']['filter']; ?>">&#160;</span></th>
				<th class="span-6a nowrap"><?php gen_span4pagelist_filterheader('filter_pages_subheader', $ccms['lang']['forms']['subheader']); echo $ccms['lang']['forms']['subheader']; ?><span class="ss_sprite_16 ss_help2" title="<?php echo $ccms['lang']['hints']['subheader'] . ' ' . $ccms['lang']['hints']['filter']; ?>">&#160;</span></th>
				<th class="span-2a nowrap center-text"><?php echo $ccms['lang']['forms']['printable']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['printable']; ?>">&#160;</span></th>
				<th class="span-2a nowrap center-text">
					<?php 
					if($_SESSION['ccms_userLevel']>=$perm['manageActivity']) 
					{ 
						echo $ccms['lang']['forms']['published']; 
						?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['published']; ?>">&#160;</span>
					<?php 
					} 
					?>
				</th>
				<th class="span-2a nowrap center-text">
					<?php
					if($_SESSION['ccms_userLevel']>=$perm['manageVarCoding']) 
					{ 
						echo $ccms['lang']['forms']['iscoding']; 
						?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['iscoding']; ?>">&#160;</span>
					<?php 
					} 
					?>
				</th>
				<th class="span-5a nowrap last">&#160;</th>
			</tr>
		
		<?php
		
		$i = 0;

		// Get previously opened DB stream
		foreach($rows as $row)
		{
			// Check whether current user is owner, or no owners at all
			$owner = @explode('||', $row->user_ids);
			if(empty($row->user_ids) || $perm['manageOwners']==0 || $_SESSION['ccms_userLevel']>=4 || in_array($_SESSION['ccms_userID'], $owner)) 
			{
				// Determine file specific variables
				if($row->module=="editor") 
				{
					$module = './includes/process.inc.php';
				} 
				else 
				{
					$module = '../lib/modules/'.$row->module.'/'.$row->module.'.Manage.php';
				}
				
				// Define $isEven for alternate table coloring
				if($i%2 != 1) 
				{
					if($row->published === "N") 
					{
						echo '<tr class="unpub_altrgb">';
					} 
					else 
					{
						echo '<tr class="altrgb">';
					}
				} 
				else 
				{ 
					if($row->published === "N") 
					{
						echo '<tr class="unpub">';
					} 
					else 
					{
						echo '<tr>'; 
					}
				} 
				?>
				<td class="leftpad-2">
				<?php 
				if($_SESSION['ccms_userLevel']<$perm['managePages'] || $row->urlpage == "home" || (in_array($row->urlpage, $cfg['restrict']) && !in_array($_SESSION['ccms_userID'], $owner))) 
				{ 
				?>
					<span class="ss_sprite_16 ss_bullet_red" title="<?php echo $ccms['lang']['auth']['featnotallowed']; ?>">&#160;</span>
				<?php 
				} 
				else 
				{ 
				?>
					<input type="checkbox" id="page_id_<?php echo $i;?>" name="page_id[]" value="<?php echo $_SESSION['rc1'].$_SESSION['rc2'].rm0lead($row->page_id); ?>" />
				<?php 
				} 
				?>
				</td>
				<td>
					<label for="page_id_<?php echo $i;?>"><em><abbr title="<?php echo $row->urlpage; ?>.html"><?php echo substr($row->urlpage,0,25); ?></abbr></em></label>
				</td>
				<td>
					<span id="<?php echo rm0lead($row->page_id); ?>" class="sprite-hover liveedit" rel="pagetitle"><?php echo $row->pagetitle; ?></span>
				</td>
				<td>
					<span id="<?php echo rm0lead($row->page_id); ?>" class="sprite-hover liveedit" rel="subheader"><?php echo $row->subheader; ?></span>
				</td>
				<td class="center-text">
					<a id="printable-<?php echo rm0lead($row->page_id); ?>" rel="<?php echo $row->printable; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php 
						if($row->printable == "Y")
						{ 
							echo $ccms['lang']['backend']['yes']; 
						} 
						else 
						{
							echo $ccms['lang']['backend']['no']; 
						}
						?></a>
				</td>
				<td class="center-text">
					<?php 
					if($_SESSION['ccms_userLevel']>=$perm['manageActivity']) 
					{ 
					?>
						<a id="published-<?php echo rm0lead($row->page_id); ?>" rel="<?php echo $row->published; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php 
							if($row->published == "Y") 
							{ 
								echo $ccms['lang']['backend']['yes']; 
							} 
							else 
							{
								echo $ccms['lang']['backend']['no']; 
							}
							?></a>
					<?php 
					} 
					?>
				</td>
				<td class="center-text">
					<?php 
					if($_SESSION['ccms_userLevel']>=$perm['manageVarCoding']) 
					{ 
					?>
						<?php 
						if($row->module=="editor") 
						{ 
						?>
							<a id="iscoding-<?php echo rm0lead($row->page_id); ?>" rel="<?php echo $row->iscoding; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php 
							if($row->iscoding == "Y") 
							{ 
								echo '<span class="signal-coding">'.$ccms['lang']['backend']['yes'].'</span>'; 
							} 
							else 
							{
								echo $ccms['lang']['backend']['no']; 
							}
							?></a>
						<?php 
						} 
						else 
						{
							echo "&ndash;"; 
						}
						?>
					<?php 
					} 
					?>
				</td>
				<?php 
				// Check for restrictions
				if(!in_array($row->urlpage, $cfg['restrict']) || in_array($_SESSION['ccms_userID'], $owner)) // only the OWNER is allowed editing access for a restricted page!
				{ 
					$preview_checkcode = GenerateNewPreviewCode($row->page_id);
					
				?>
					<td class="last right-text nowrap">
						<a id="<?php echo $row->urlpage;?>" 
							href="<?php echo $module; ?>?file=<?php echo $row->urlpage; ?>&amp;action=edit&amp;restrict=<?php echo $row->iscoding; ?>&amp;active=<?php echo $row->published;?>" 
							rel="Edit <?php echo $row->urlpage.'.html';?>" 
							class="tabs sprite edit"
						><?php echo $ccms['lang']['backend']['editpage']; ?></a>
						| 
						<a href="../<?php echo ($row->urlpage != "home" ? $row->urlpage . '.html' : '') . '?preview=' . $preview_checkcode; ?>" 
							class="external"
						><?php echo $ccms['lang']['backend']['previewpage']; ?></a>
					</td>
				<?php 
				} 
				else 
				{ 
				?>
					<td class="last right-text nowrap">
						<div style="margin: 2px;"><span class="ss_sprite_16 ss_exclamation" title="<?php echo $ccms['lang']['backend']['restrictpage']; ?>">&#160;</span><?php echo $ccms['lang']['backend']['restrictpage'];?></div>
					</td>
				<?php 
				} 
				?>
				</tr>
				
				<?php 
				if($i%2 != 1) 
				{
					if($row->published === "N") 
					{
						echo '<tr class="unpub_altrgb">';
					} 
					else     
					{
						echo '<tr class="altrgb">';
					}
				} 
				else 
				{ 
					if($row->published === "N") 
					{
						echo '<tr class="unpub">';
					} 
					else 
					{
						echo '<tr>'; 
					}
				} 
				?>
				<td>&#160;</td>
				<?php 
				// [i_a] make sure URLs in descriptions are not damaged by UCfirst() 
				$description = $row->description;
				if (!regexUrl($description))                 
				{
					$description = ucfirst($description);
				}
				?>
				<td colspan="5"><strong><?php echo $ccms['lang']['forms']['description']; ?></strong>: <span id="<?php echo rm0lead($row->page_id); ?>" class="sprite-hover liveedit" rel="description"><?php echo $description; ?></span></td>
				<td colspan="2" class="right-text" style="padding-right:5px;">
					<?php 
					if($row->module=="editor" && !empty($row->toplevel)) 
					{ 
					?>
						<em><?php echo $ccms['lang']['backend']['inmenu']; ?>:</em> 
							<?php echo '<strong>' . strtolower($ccms['lang']['menu'][$row->menu_id]) . '</strong> | <em>' . $ccms['lang']['backend']['item'] . ' </em> <strong>' . $row->toplevel . ':' . $row->sublevel . '</strong>'; 
						?>
					<?php 
					} 
					elseif($row->module!="editor") 
					{ 
						// TODO: add a module/plugin hook to provide the proper icon/formatting for the module name:
						$modID = '<span class="ss_sprite_16 ss_information">&#160;</span><strong>' . ucfirst($row->module) . '</strong></span>';
						
						switch ($row->module)
						{
						default:
							break;
						
						case 'lightbox':
							$modID = '<span class="ss_sprite_16 ss_images">&#160;</span><strong>'.ucfirst($row->module).'</strong>';
							break;
							
						case 'news':
							$modID = '<span class="ss_sprite_16 ss_newspaper">&#160;</span><strong>'.ucfirst($row->module).'</strong>';
							break;
							
						case 'comment':
							$modID = '<span class="ss_sprite_16 ss_comments">&#160;</span><strong>'.ucfirst($row->module).'</strong>';
							break;
						}
						echo $modID . ' '.strtolower($ccms['lang']['forms']['module']);
					} 
					else 
					{
						echo "<em>".$ccms['lang']['backend']['notinmenu']."</em>"; 
					}
					?>
				</td>
				</tr>
			<?php
			} 
			
			// move on	
			$i++;
		} 
		?>
		</table>
	<?php 
	}
	else
	{
		echo '<p class="center-text ss_has_sprite" style="padding-top:5px;"><span class="ss_sprite_16 ss_bullet_red">&#160;</span>'.$ccms['lang']['system']['noresults'].'</p>'; 
	}
}
	

	
/**
 *
 * Render the entire menu list
 *
 */
if($do_action == "renderlist" && $_SERVER['REQUEST_METHOD'] != "POST" && checkAuth()) 
{
	$menu_sortorder = getGETparam4IdOrNumber('m_order', 'I12LH0');
	
	if(isset($_SESSION['ccms_userLevel']) && $_SESSION['ccms_userLevel'] >= $perm['manageMenu']) 
	{
		// Open recordset for sites' pages
		$rows = $db->SelectObjects($cfg['db_prefix'].'pages', null, null, cvt_ordercode2list($menu_sortorder));
		if ($rows === false) $db->Kill();

		// Check whether the recordset is not empty
		if(count($rows) > 0) 
		{
		?>
			<table id="table_menu" cellspacing="0" cellpadding="0">
			<tr>
				<th class="nowrap"><?php echo $ccms['lang']['backend']['menutitle']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['menuid']; ?>">&#160;</span></th>
				<th class="nowrap"><?php echo $ccms['lang']['backend']['template']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['template']; ?>">&#160;</span></th>
				<th class="nowrap"><?php echo $ccms['lang']['backend']['toplevel']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['toplevel']; ?>">&#160;</span></th>
				<th class="nowrap"><?php echo $ccms['lang']['backend']['sublevel']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['sublevel']; ?>">&#160;</span></th>
				<th class="nowrap"><?php echo $ccms['lang']['backend']['linktitle']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['activelink']; ?>">&#160;</span></th>
				<th class="nowrap last"><?php echo $ccms['lang']['forms']['pagetitle']; ?></th>
			</tr>
			<?php
			
			$i = 0;
			// Get previously opened DB stream
			foreach($rows as $row)
			{
				// Define $isEven for alternate table coloring
				$isEven = !($i % 2);
				if($isEven != '1') 
				{
					echo '<tr class="altcolor">';
				} 
				else 
				{ 
					echo '<tr>'; 
				} 
				$pageIdAsStr = rm0lead($row->page_id); // empty cannot be; rm0lead() makes sure zero is actually "0"
				?>
					<td>
						<select class="span-2 last" name="menuid[<?php echo $pageIdAsStr; ?>]">
							<optgroup label="Menu">
								<?php 
								$y = 1; 
								while($y<=MENU_TARGET_COUNT) 
								{ 
								?>
									<option <?php echo ($row->menu_id==$y) ? "selected=\"selected\"" : ""; ?> value="<?php echo $y; ?>"><?php echo $ccms['lang']['menu'][$y]; ?></option>
									<?php 
									$y++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td>
						<select class="span-3 last" name="template[<?php echo $pageIdAsStr; ?>]">
							<optgroup label="<?php echo $ccms['lang']['backend']['template'];?>">
								<?php 
								for ($x = 0; $x < count($template); $x++) 
								{ 
								?>
									<option <?php echo ($row->variant==$template[$x]) ? 'selected="selected"' : ''; ?> value="<?php echo $template[$x]; ?>"><?php echo ucfirst($template[$x]); ?></option>
								<?php 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td>
						<select class="span-2 last" name="toplevel[<?php echo $pageIdAsStr; ?>]">
							<optgroup label="Toplevel">
								<?php 
								$z = 1; 
								while($z <= count($rows)) 
								{ 
								?>
									<option <?php echo ($row->toplevel==$z) ? "selected=\"selected\"" : ""; ?> value="<?php echo $z; ?>"><?php echo $z; ?></option>
									<?php 
									$z++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td>
						<select class="span-2 last" name="sublevel[<?php echo $pageIdAsStr; ?>]">
							<optgroup label="Sublevel">
								<?php 
								$y = 0; 
								while($y+1 < count($rows)) 
								{ 
								?>
									<option <?php echo ($row->sublevel==$y) ? "selected=\"selected\"" : ""; ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
									<?php 
									$y++; 
								} 
								?>
							</optgroup>
						</select>
					</td>
					<td class="center-text" id="td-islink-<?php echo $pageIdAsStr; ?>">
						<?php 
						if($row->urlpage == "home") 
						{ 
						?>
							<input type="checkbox" checked="checked" disabled="disabled" />
						<?php 
						} 
						else 
						{ 
						?>
							<input type="checkbox" name="islink" id="<?php echo $pageIdAsStr; ?>" class="islink" <?php echo($row->islink==="Y")?'checked="checked"':null;?> />
						<?php 
						} 
						?>
					</td>
					<td class="last">
						<?php echo $row->urlpage; ?><em>(.html)</em>
						<input type="hidden" name="pageid[]" value="<?php echo $pageIdAsStr; ?>" id="pageid"/>
					</td>
				</tr>
				<?php 
				$i++; 
			} 
			?>
			</table>
		<?php 
		} 
		else
		{
			echo '<p class="center-text ss_has_sprite" style="padding-top:5px;"><span class="ss_sprite_16 ss_bullet_red">&#160;</span>'.$ccms['lang']['system']['noresults'].'</p>'; 
		}
	}
	else 
	{
		echo '<p class="center-text ss_has_sprite" style="padding-top:5px;"><span class="ss_sprite_16 ss_delete">&#160;</span>'.$ccms['lang']['auth']['featnotallowed'].'</p>';
	} 
}




/**
 *
 * Process the request for new page creation
 *
 */
if($target_form == "create" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Remove all none system friendly characters
	$post_urlpage = getPOSTparam4Filename('urlpage'); 
	$post_urlpage = strtolower(str_replace(' ','-',$post_urlpage));
	
	// Check for non-empty module variable
	$post_module = strtolower(getPOSTparam4Filename('module', 'editor'));
	
	// Set variables
	$pagetitle	= getPOSTparam4DisplayHTML('pagetitle');
	$subheader	= getPOSTparam4DisplayHTML('subheader');
	$description = getPOSTparam4DisplayHTML('description');
	
	// Check radio button values
	$printable_pref = getPOSTparam4boolYN('printable', 'Y');
	$published_pref = getPOSTparam4boolYN('published', 'Y');
	$iscoding_pref	= getPOSTparam4boolYN('iscoding', 'N');
	
	// Start with a clean sheet
	$errors=array();
	
	if(strstr($post_urlpage, '.') !== FALSE) 
		{ $errors[] = "- ".$ccms['lang']['system']['error_filedots']; }
	if ($post_urlpage=='' || strlen($post_urlpage)<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_filesize']; }
	if ($pagetitle=='' || strlen($pagetitle)<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_pagetitle']; }
	if ($subheader=='' || strlen($subheader)<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_subtitle']; }
	if ($description=='' || strlen($description)<3)
		{ $errors[] = "- ".$ccms['lang']['system']['error_description']; }
	if (strlen($post_urlpage) > 50)
		{ $errors[] = "- ".$ccms['lang']['system']['error_filesize_2']; }
	if (strlen($pagetitle) > 100)
		{ $errors[] = "- ".$ccms['lang']['system']['error_pagetitle_2']; }
	if (strlen($subheader) > 200)
		{ $errors[] = "- ".$ccms['lang']['system']['error_subtitle_2']; }
	if (strlen($description) > 250)
		{ $errors[] = "- ".$ccms['lang']['system']['error_description_2']; }
	if ($post_urlpage=='403' || $post_urlpage=='404' || $post_urlpage=='sitemap' || $post_urlpage=='home')
		{ $errors[] = "- ".$ccms['lang']['system']['error_reserved']; }
	
	if(count($errors) == 0)
	{
		// Insert new page into database
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		// $arrayVariable["column name"] = formatted SQL value
		$values['urlpage']		= MySQL::SQLValue($post_urlpage,MySQL::SQLVALUE_TEXT);
		$values['module']		= MySQL::SQLValue($post_module,MySQL::SQLVALUE_TEXT);
		$values['toplevel']		= MySQL::SQLValue(1,MySQL::SQLVALUE_NUMBER);
		$values['sublevel']		= MySQL::SQLValue(0,MySQL::SQLVALUE_NUMBER);
		$values['menu_id']		= MySQL::SQLValue(1,MySQL::SQLVALUE_NUMBER); // [i_a] set to the same value as the DEFAULT as specced in the SQL DB
		$values['pagetitle']	= MySQL::SQLValue($pagetitle,MySQL::SQLVALUE_TEXT);
		$values['subheader']	= MySQL::SQLValue($subheader,MySQL::SQLVALUE_TEXT);
		$values['description']	= MySQL::SQLValue($description,MySQL::SQLVALUE_TEXT);
		$values['srcfile']		= MySQL::SQLValue($post_urlpage.".php",MySQL::SQLVALUE_TEXT);
		$values['printable']	= MySQL::SQLValue($printable_pref,MySQL::SQLVALUE_Y_N);
		$values['published']	= MySQL::SQLValue($published_pref,MySQL::SQLVALUE_Y_N);
		$values['iscoding']		= MySQL::SQLValue($iscoding_pref,MySQL::SQLVALUE_Y_N);
		// the default 'owner' is the one who creates the page:
		$values['user_ids']		= MySQL::SQLValue(intval($_SESSION['ccms_userID']),MySQL::SQLVALUE_TEXT);
		
		// Execute the insert
		$result = $db->TransactionBegin();
		if ($result)
		{
			$result = $db->InsertRow($cfg['db_prefix'].'pages', $values);

			// Check for errors
			if($result) 
			{
				// Create the actual file
				$filehandle = fopen("../../content/".$post_urlpage.".php", 'w');
				if(!$filehandle) 
				{
					$errors[] = $ccms['lang']['system']['error_write'];
				} 
				else 
				{
					// Write default contents to newly created file
					if($post_module==="editor") 
					{
						if (!fwrite($filehandle, "<p>".$ccms['lang']['backend']['newfiledone']."</p>"))
						{
							$errors[] = $ccms['lang']['system']['error_write'];
						}
					} 
					// Write require_once tag to file (modname.Show.php)
					else 
					{
						if (!fwrite($filehandle, "<?php require_once(BASE_PATH . '/lib/modules/$post_module/$post_module.Show.php'); ?>"))
						{
							$errors[] = $ccms['lang']['system']['error_write'];
						}
					}
				}
				// Report success in notify area
				if(!fclose($filehandle)) 
				{
					$errors[] = $ccms['lang']['system']['error_create'];
				}
			} 
			elseif($db->ErrorNumber() == 1062) 
			{
				$errors[] = $ccms['lang']['system']['error_exists'];
			} 
			else
			{
				$errors[] = $db->Error(); // Some error which has not been antipicated.
			}
			
			// commit or abort TXN:
			if(count($errors) != 0)
			{
				$result = $db->TransactionRollback();
			}
			else
			{
				$result = $db->TransactionEnd();
			}
			if (!$result)
			{
				$errors[] = $db->Error(); // Transaction commit/rollback error.
			}
		}
		else
		{
			$errors[] = $db->Error(); // Transaction init failure.
		}
	}

	if(count($errors) != 0)
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'">&#160;</span>'.$ccms['lang']['system']['error_correct'].'</p>';
		while (list($key,$value) = each($errors))
		{
			echo '<p class="fault">'.$value.'</p>';
		}
		exit(); // Prevent AJAX from continuing
	}

	// success!
	echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_accept" title="'.$ccms['lang']['backend']['success'].'">&#160;</span>'.$ccms['lang']['backend']['newfilecreated'].'</p><p>'.$ccms['lang']['backend']['starteditbody'].'</p>';
	exit();
}

/**
 *
 * Process the request for page deletion
 *
 */
if($target_form == "delete" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	if(!empty($_POST['page_id'])) 
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_accept" title="'.$ccms['lang']['backend']['success'].'">&#160;</span>'.$ccms['lang']['backend']['statusdelete'].'</p>';
	
		// Loop through all submitted page ids
		foreach ($_POST['page_id'] as $index) 
		{
			$value = explode($_SESSION['rc2'], $index);
			$page_id = filterParam4Number($value[1]);
			if($page_id != 0 && $value[0] == $_SESSION['rc1']) 	// [i_a] complete validation check: test both rc1 and rc2 in the explode+if()
			{
				// Select file name and module with given page_id
				$pagerow = $db->SelectSingleRowArray($cfg['db_prefix'].'pages', array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)), array('urlpage', 'module'));
				if (!$pagerow) $db->Kill();
				$correct_filename = $pagerow['urlpage'];
				$module = $pagerow['module'];
				
				// Delete details from the database
				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values['page_id'] = MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER);
				$result = $db->DeleteRows($cfg['db_prefix'].'pages', $values);
				
				// Delete linked rows from module tables
				if($module!='editor') 
				{
					$filter = array(); // [i_a] make sure $filter is an empty array to start with here
					$filter['pageID'] = MySQL::SQLValue($correct_filename,MySQL::SQLVALUE_TEXT);
					$delmod = $db->DeleteRows($cfg['db_prefix'].'mod'.$module, $filter);
					$delcfg = $db->DeleteRows($cfg['db_prefix'].'cfg'.$module, $filter);
				}
				
				if ($result) 
				{
					// Delete the actual file
					if(@unlink("../../content/".$correct_filename.".php")) 
					{
						echo '<p>- '.ucfirst($correct_filename).' '.$ccms['lang']['backend']['statusremoved'].'</p>';
					} 
					else 
					{
						die($ccms['lang']['system']['error_delete']);
					}
				} 
				else 
				{
					die($db->Error($ccms['lang']['system']['error_general']));
				}
			} 
			else 
			{
				die($ccms['lang']['system']['error_forged']);
			}
		}
	} 
	else 
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'">&#160;</span>'.$ccms['lang']['system']['error_correct'].'</p><p class="fault">- '.$ccms['lang']['system']['error_selection'].'</p>';
	}
}

/**
 *
 * Save the menu order, individual templating & menu allocation preferences
 *
 */
if($target_form == "menuorder" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$error = null;
	
	foreach ($_POST['pageid'] as $page_id) 
	{
		$page_id = filterParam4Number($page_id);
		$toplevel = filterParam4Number($_POST['toplevel'][$page_id]);
		$sublevel = filterParam4Number($_POST['sublevel'][$page_id]);
		$templatename = filterParam4Filename($_POST['template'][$page_id]);
		$menu_id = filterParam4Number($_POST['menuid'][$page_id]);
		if (!$page_id || !$toplevel || empty($templatename) || !$menu_id)
		{
			$error = $ccms['lang']['system']['error_forged'];
			break;
		}
		
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values["toplevel"]	= MySQL::SQLValue($toplevel, MySQL::SQLVALUE_NUMBER);
		$values["sublevel"]	= MySQL::SQLValue($sublevel, MySQL::SQLVALUE_NUMBER);
		$values["variant"]	= MySQL::SQLValue($templatename, MySQL::SQLVALUE_TEXT);
		$values["menu_id"]	= MySQL::SQLValue($menu_id, MySQL::SQLVALUE_NUMBER);
		
		// Execute the update
		if(!$db->UpdateRows($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)))) 
		{
			$error = $db->Error();
		}
	}
	
	if(empty($error)) 
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_accept" title="'.$ccms['lang']['backend']['success'].'">&#160;</span>'.$ccms['lang']['backend']['success'].'</p><p>'.$ccms['lang']['backend']['orderprefsaved'].'</p>';
	} 
	else 
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'">&#160;</span>'.$ccms['lang']['system']['error_correct'].'</p><p class="fault">- '.$error.'</p>';
	}
	exit();
}

 /**
 *
 * Set actual hyperlink behind menu item to true/false
 *
 */
if($do_action == "islink" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$page_id = getPOSTparam4Number('id');
	$islink_in_menu = getPOSTparam4boolYN('cvalue', 'N');
	
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values['islink'] = MySQL::SQLValue($islink_in_menu, MySQL::SQLVALUE_Y_N);
	
	if ($db->UpdateRows($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)))) 
	{
		if($values["islink"] == "Y") 
		{ 
			echo $ccms['lang']['backend']['yes']; 
		} 
		else 
		{
			echo $ccms['lang']['backend']['no'];
		}
	} 
	else 
	{
		$db->Kill();
	}
}

/**
 *
 * Edit print, publish or iscoding preference
 *
 */
if($do_action == "editinplace" && $_SERVER['REQUEST_METHOD'] == "GET" && checkAuth()) 
{
	// Explode variable with all necessary information
	$page_id = explode("-", getGETparam4IdOrNumber('id'), 2); // [i_a] fix for page_id's which have dashes in their own name...
	
	$page_num = filterParam4Number($page_id[1]);
	
	// Set the action for this call
	if($page_num && ($page_id[0] == "printable" || $page_id[0] == "published" || $page_id[0] == "iscoding")) 
	{
		$action	 = $page_id[0];
	} 
	else 
	{
		die($ccms['lang']['system']['error_forged']);
	}
	
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	// TOGGLE the flag (printable/published/iscoding) state:
	$values[$action] = MySQL::SQLValue(!getGETparam4boolean('s'),MySQL::SQLVALUE_Y_N);
	
	if ($db->UpdateRows($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_num,MySQL::SQLVALUE_NUMBER)))) 
	{
		if($values[$action] == "Y")
		{ 
			echo $ccms['lang']['backend']['yes']; 
		} 
		else 
		{
			echo $ccms['lang']['backend']['no'];
		}
	} 
	else 
	{
		$db->Kill($ccms['lang']['system']['error_general']);
	}
}

/**
 *
 * Check latest version
 *
 */
$version_recent = @file_get_contents("http://www.compactcms.nl/version/".$v.".txt");
if(version_compare($version_recent, $v) != '1') 
{ 
	$version = $ccms['lang']['backend']['uptodate']; 
} 
else 
{
	$version = $ccms['lang']['backend']['outofdate']." <a href=\"http://www.compactcms.nl/changes.html\" class=\"external\" rel=\"external\">".$ccms['lang']['backend']['considerupdate']."</a>.";
}

/**
 *
 * Edit-in-place update action
 *
 */
if($do_action == "liveedit" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$content = getPOSTparam4DisplayHTML('content');
	if(empty($content) || strlen($content) < 3 || strlen($content) > 240) 
	{
		die($ccms['lang']['system']['error_value']);
	}
	
	// Continue with content update
	$page_id		= getPOSTparam4Number('id');
	$dest			= getGETparam4IdOrNumber('part');
	
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values[$dest] = MySQL::SQLValue($content,MySQL::SQLVALUE_TEXT);
	
	if (!$db->UpdateRows($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER))))
	{
		$db->Kill();
	}
	else
	{
		echo $content;
	}
}

/**
 *
 * Save the edited template and check for authority
 *
 */
if($do_action == "save-template" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	FbX::SetFeedbackLocation($cfg['rootdir'] . "/admin/includes/modules/template-editor/backend.php");
	try
	{
		// Only if current user has the rights
		if($_SESSION['ccms_userLevel']>=$perm['manageTemplate']) 
		{
			$filenoext	= getGETparam4FullFilePath('template');
			$filename	= BASE_PATH . "/lib/templates/" . $filenoext;
			
			$content	= $_POST['content']; // RAW CONTENT: the template may contain ANYTHING.
			
			if (is_writable_ex($filename)) 
			{
				if (!$handle = fopen($filename, 'w'))  throw new FbX($ccms['lang']['system']['error_openfile']." (".$filename.").");
				if (fwrite($handle, $content) === FALSE) 
				{
					fclose($handle);
					throw new FbX($ccms['lang']['system']['error_write']." (".$filename.").");
				}
				// Do on success
				fclose($handle);
				header('Location: ' . makeAbsoluteURI('./modules/template-editor/backend.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved']).'&template='.$filenoext));
				exit();
			} 
			else 
			{
				throw new FbX($ccms['lang']['system']['error_chmod']);
			} 
		} 
		else 
		{
			throw new FbX($ccms['lang']['auth']['featnotallowed']);
		}
	}
	catch (CcmsAjaxFbException $e)
	{
		$e->croak();
	}
}

/**
 *
 * Create a new user as posted by an authorized user
 *
 */
if($do_action == "add-user" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
	{
		//$i=count(array_filter($_POST));
		//if($i <= 6) error
		
		$userName = strtolower(getPOSTparam4IdOrNumber('user'));
		$userPass = md5($_POST['userPass'].$cfg['authcode']);
		$userFirst = getPOSTparam4HumanName('userFirstname');
		$userLast = getPOSTparam4HumanName('userLastname');
		$userEmail = getPOSTparam4Email('userEmail');
		$userActive = getPOSTparam4boolean('userActive');
		$userLevel = getPOSTparam4Number('userLevel');
		if (empty($userName) || empty($_POST['userPass']) || empty($userFirst) || empty($userLast) || empty($userEmail) || !$userLevel)
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_tooshort'])));
			exit();
		}
			
		// Set variables
		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values['userName']		= MySQL::SQLValue($userName,MySQL::SQLVALUE_TEXT);
		$values['userPass']		= MySQL::SQLValue($userPass,MySQL::SQLVALUE_TEXT);
		$values['userFirst']	= MySQL::SQLValue($userFirstname,MySQL::SQLVALUE_TEXT);
		$values['userLast']		= MySQL::SQLValue($userLastname,MySQL::SQLVALUE_TEXT);
		$values['userEmail']	= MySQL::SQLValue($userEmail,MySQL::SQLVALUE_TEXT);
		$values['userActive']	= MySQL::SQLValue($userActive,MySQL::SQLVALUE_BOOLEAN);
		$values['userLevel']	= MySQL::SQLValue($userLevel,MySQL::SQLVALUE_NUMBER);
		// TODO: userToken is currently UNUSED. -- should be used to augment the $cfg['authcode'] where applicable
		$values['userToken']	= MySQL::SQLValue(mt_rand('123456789','987654321'),MySQL::SQLVALUE_NUMBER);
		
		// Execute the insert
		$result = $db->InsertRow($cfg['db_prefix'].'users', $values);
		
		// Check for errors
		if($result) 
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
			exit();
		} 
		else 
		{
			$db->Kill();
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
}

/**
 *
 * Edit user details as posted by an authorized user
 *
 */
if($do_action == "edit-user-details" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$userID = getPOSTparam4Number('userID');
	$userFirst = getPOSTparam4HumanName('first');
	$userLast = getPOSTparam4HumanName('last');
	$userEmail = getPOSTparam4Email('email');
	
	// Only if current user has the rights
	if(($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) || $_SESSION['ccms_userID'] == $userID) 
	{
		// Check length of values
		if(strlen($userFirst)>2&&strlen($userLast)>2&&strlen($userEmail)>6) 
		{
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values["userFirst"]= MySQL::SQLValue($userFirst,MySQL::SQLVALUE_TEXT);
			$values["userLast"]	= MySQL::SQLValue($userLast,MySQL::SQLVALUE_TEXT);
			$values["userEmail"]= MySQL::SQLValue($userEmail,MySQL::SQLVALUE_TEXT);
			
			if ($db->UpdateRows($cfg['db_prefix'].'users', $values, array("userID" => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER)))) 
			{
				if($userID==$_SESSION['ccms_userID']) 
				{
					$_SESSION['ccms_userFirst']	= $userFirst; // getPOSTparam4HumanName already does the htmlentities() encoding, so we're safe to use & display these values as they are now.
					$_SESSION['ccms_userLast']	= $userLast;
				}
				
				header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
				exit();
			}
			else
				$db->Kill();
		} 
		else 
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_tooshort'])));
			exit();
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
}
 
/**
 *
 * Edit users' password as posted by an authorized user
 *
 */
 
if($do_action == "edit-user-password" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	$userID = getPOSTparam4Number('userID');
	
	// Only if current user has the rights
	if(($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) || $_SESSION['ccms_userID']==$userID) 
	{
		$passphrase_len = strlen($_POST['userPass']);
		
		if($passphrase_len > 6 && md5($_POST['userPass']) === md5($_POST['cpass'])) 
		{
			$userPassHash = md5($_POST['userPass'].$cfg['authcode']);
			
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values['userPass'] = MySQL::SQLValue($userPassHash,MySQL::SQLVALUE_TEXT);
			
			if ($db->UpdateRows($cfg['db_prefix'].'users', $values, array("userID" => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER)))) 
			{
				header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
				exit();
			}
			else
				$db->Kill();
		} 
		elseif($passphrase_len <= 6) 
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/user.Edit.php?userID='.$userID.'&status=error&msg='.rawurlencode($ccms['lang']['system']['error_passshort'])));
			exit();
		} 
		else 
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/user.Edit.php?userID='.$userID.'&status=error&msg='.rawurlencode($ccms['lang']['system']['error_passnequal'])));
			exit();
		}
	} 
	else
	{	
		die($ccms['lang']['auth']['featnotallowed']);
	}
}

/**
 *
 * Edit user level as posted by an authorized user
 *
 */
 
if($do_action == "edit-user-level" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
	{
		$userID = getPOSTparam4Number('userID');
		$userActive = getPOSTparam4boolean('userActive');
		$userLevel = getPOSTparam4Number('userLevel');
		if ($userLevel > 0)
		{
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values["userLevel"] = MySQL::SQLValue($userLevel,MySQL::SQLVALUE_NUMBER);
			$values["userActive"] = MySQL::SQLValue($userActive,MySQL::SQLVALUE_BOOLEAN);
				
			if ($db->UpdateRows($cfg['db_prefix'].'users', $values, array('userID' => MySQL::SQLValue($userID,MySQL::SQLVALUE_NUMBER)))) 
			{
				if($userID==$_SESSION['ccms_userID']) 
				{
					$_SESSION['ccms_userLevel'] = $userLevel;
				}
			
				header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['settingssaved'])));
				exit();
			}
			else
			{
				$db->Kill();
			}
		}
		else 
		{
			die($ccms['lang']['system']['error_forged']);
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
}

/**
 *
 * Delete a user as posted by an authorized user
 *
 */
if($do_action == "delete-user" && $_SERVER['REQUEST_METHOD'] == "POST" && checkAuth()) 
{
	// Only if current user has the rights
	if($perm['manageUsers']>0 && $_SESSION['ccms_userLevel']>=$perm['manageUsers']) 
	{
		$total = (isset($_POST['userID']) ? count($_POST['userID']) : 0);
		
		if($total==0) 
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_selection'])));
			exit();
		}
		
		// Delete details from the database
		$i=0;
		foreach ($_POST['userID'] as $user_num) 
		{
			$user_num = filterParam4Number($user_num);
			
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values['userID'] = MySQL::SQLValue($user_num, MySQL::SQLVALUE_NUMBER);
			$result = $db->DeleteRows($cfg['db_prefix'].'users', $values);
			$i++;
		}
		// Check for errors
		if($result && $i == $total) 
		{
			header('Location: ' . makeAbsoluteURI('./modules/user-management/backend.php?status=notice&msg='.rawurlencode($ccms['lang']['backend']['fullremoved'])));
			exit();
		} 
		else 
		{
			$db->Kill();
		}
	} 
	else 
	{
		die($ccms['lang']['auth']['featnotallowed']);
	}
}

/**
 *
 * Generate the WYSIWYG or code editor for editing purposes (prev. editor.php)
 *
 */
if($do_action == "edit" && $_SERVER['REQUEST_METHOD'] != "POST" && checkAuth()) 
{
	// Set the necessary variables
	$name 		= getGETparam4Filename('file');
	$iscoding	= getGETparam4boolYN('restrict', 'N');
	$active		= getGETparam4boolYN('active', 'N');
	$filename	= BASE_PATH . "/content/".$name.".php";
	
	// Check for editor.css in template directory
	$template	= $db->SelectSingleValue($cfg['db_prefix'].'pages', array('urlpage' => MySQL::SQLValue($name, MySQL::SQLVALUE_TEXT)), array('variant'));
	if (!$template) $db->Kill();
	$css = "";
	if (is_file($cfg['rootdir'] . '/lib/templates/'.$template.'/editor.css')) 
	{
		$css = $cfg['rootdir'] . '/lib/templates/'.$template.'/editor.css';
	}
	
	// Check for filename	
	if(!empty($filename)) 
	{
		$handle = @fopen($filename, "r");
		if ($handle) 
		{
			// PHP5+ Feature
			// $contents = stream_get_contents($handle);
			// PHP4 Compatibility
			$contents = fread($handle, filesize($filename));
			$contents = str_replace("<br />", "<br>", $contents);
			fclose($handle);
		} 
		else 
		{ 
			die($ccms['lang']['system']['error_deleted']);
		}
	} 
	
	// Get keywords for current file
	$keywords = $db->SelectSingleValue($cfg['db_prefix'].'pages', array('urlpage' => MySQL::SQLValue($name, MySQL::SQLVALUE_TEXT)), array('keywords'));
	
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS - <?php echo $ccms['lang']['editor']['editorfor']." ".$name; ?></title>
		<link rel="stylesheet" type="text/css" href="../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	<?php 
	// Load TinyMCE (compressed for faster loading) 
	if($cfg['wysiwyg'] && $iscoding != 'Y')
	{
	?>
		<!-- File uploader styles -->
		<link rel="stylesheet" media="all" type="text/css" href="../../lib/includes/js/fancyupload/Css/FileManager.css,Additions.css" />
	<?php 
	} 
	// else : load Editarea for code editing
	?>
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../img/styles/ie.css" />
	<![endif]-->
	</head>
	
	<body>
	<div class="module" id="edit-page">
		<h2><?php echo $ccms['lang']['backend']['editpage']." $name<em>.html</em>"; ?></h2>
		<p><?php echo $ccms['lang']['editor']['instruction']; ?></p>
		
		<form action="./process.inc.php?page=<?php echo $name; ?>&amp;restrict=<?php echo $iscoding; ?>&amp;active=<?php echo $active; ?>&amp;action=save-changes" method="post" name="save">
			<textarea id="content" name="content"><?php echo htmlspecialchars(trim($contents)); ?></textarea>
			<!--<br/>-->
			<label for="keywords"><?php echo $ccms['lang']['editor']['keywords']; ?></label>
			<input type="input" class="text span-25" maxlength="250" name="keywords" value="<?php echo $keywords; ?>" id="keywords">
			<input type="hidden" name="code" value="<?php echo getGETparam4boolYN('restrict', 'N'); ?>" id="code" />
			<div class="right">
				<button type="submit" name="do" id="submit"><span class="ss_sprite_16 ss_disk">&#160;</span><?php echo $ccms['lang']['editor']['savebtn']; ?></button>
				<a class="button" href="../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['cancelbtn']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['cancelbtn']; ?></a>
			</div>
		</form>

	<textarea id="jslog" class="log span-25" readonly="readonly">
	</textarea>

	</div>
	<script type="text/javascript">
function confirmation()
{
	var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", '<?php echo $name; ?>_ccms');
	}
	return false;
}



	
var jsLogEl = document.getElementById('jslog');


	<?php 
	// Load TinyMCE (compressed for faster loading) 
	if($cfg['wysiwyg'] && $iscoding != 'Y')
	{
	?>
var js = [
	'../../lib/includes/js/the_goto_guy.js',
	'../../lib/includes/js/tiny_mce/tiny_mce_dev.js',
	'../../lib/includes/js/mootools-core.js,mootools-more.js',
	'../../lib/includes/js/fancyupload/dummy.js,Source/FileManager.js,<?php
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
		elements: 'content',
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

	//});
}
	<?php 
	} 
	else 
	{ 
		// Alternative to tinyMCE: load Editarea for code editing
		?>
	
var js = [
	'../../lib/includes/js/the_goto_guy.js',
	'../../lib/includes/js/edit_area/edit_area_full.js'
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
editAreaLoader.init(
	{
		id:"content",
		is_multi_files:false,
		allow_toggle:false,
		word_wrap:true,
		start_highlight:true,
		<?php echo 'language:"'.$cfg['editarea_language'].'",'; ?>
		syntax:"html"
	});

	//});
}
	<?php 
	} 
	?>
	
	
	
	
	
	


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
	when loading the flattened tinyMCE JS, this is (almost) identical to invoking the lazyload-done hook 'jsComplete()';
	however, tinyMCE 'dev' sources (tiny_mce_dev.js) employs its own lazyload-similar system, so having loaded /that/
	file does /NOT/ mean that the tinyMCE editor has been loaded completely, on the contrary!
	*/
	tinyMCEPreInit = {
		  suffix: '_src' /* '_src' when you load the _src or _dev version, '' when you want to load the stripped+minified version of tinyMCE plugins */
		, base: <?php echo '"' . $cfg['rootdir'] . 'lib/includes/js/tiny_mce"'; ?>
		, query: 'load_callback=jsComplete' /* specify a URL query string, properly urlescaped, to pass special arguments to tinyMCE, e.g. 'api=jquery'; must have an 'adapter' for that one, 'debug=' to add tinyMCE firebug-lite debugging code */
	};

	LazyLoad.js(js, jsComplete);
}
	</script>
	<script type="text/javascript" src="../../lib/includes/js/lazyload/lazyload.js" charset="utf-8"></script>
	</body>
	</html>
<?php 

	exit();
}

 /**
 *
 * Processing save page (prev. handler.inc.php)
 *
 */
if($do_action == "save-changes" && checkAuth()) 
{
	// Strip slashes for certain servers (DEPRECIATED for PHP6)
	if (get_magic_quotes_gpc()) 
	{
		function stripslashes_deep($value) 
		{
			if(is_array($value)) {
				$value = array_map('stripslashes_deep',$value);
			} else {
				$value = stripslashes($value);
			}
			return $value;
		}
	
		$_POST = array_map('stripslashes_deep', $_POST);
		$_GET = array_map('stripslashes_deep', $_GET);
	}

	$name 		= getGETparam4Filename('page');
	$active		= getGETparam4boolYN('active', 'N');
	$type		= (getPOSTparam4boolean('code') ? "code" : "text");
	$content	= $_POST['content']; // [i_a] must be RAW HTML, no htmlspecialchars(). Filtering required if malicious input risk expected.
	$filename	= BASE_PATH . '/content/' . $name . '.php';
	$keywords	= getPOSTparam4DisplayHTML('keywords');

	if (is_writable_ex($filename)) 
	{
		if (!$handle = fopen($filename, 'w')) 
		{
			die("[ERR105] ".$ccms['lang']['system']['error_openfile']." (".$filename.").");
		}
		if (fwrite($handle, $content) === FALSE) 
		{
			die("[ERR106] ".$ccms['lang']['system']['error_write']." (".$filename.").");
		}
		fclose($handle);
	} 
	else 
	{
		die($ccms['lang']['system']['error_chmod']);
	}
		
	// Save keywords to database
	$values = array(); // [i_a] make sure $values is an empty array to start with here
	$values["keywords"] = MySQL::SQLValue($keywords,MySQL::SQLVALUE_TEXT);
	
	if ($db->UpdateRows($cfg['db_prefix'].'pages', $values, array("urlpage" => MySQL::SQLValue($name, MySQL::SQLVALUE_TEXT)))) 
	{
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cfg['language']; ?>">
	<head>
		<title>CompactCMS <?php echo $ccms['lang']['backend']['administration']; ?></title>
		<link rel="stylesheet" type="text/css" href="../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
	</head>

	<body>
		<div id="handler-wrapper" class="module">
			
			<?php 
			if($active!='Y') 
			{
			?>
				<p class="notice"><?php $msg = explode('::', $ccms['lang']['hints']['published']); echo $msg[0].": <strong>".strtolower($ccms['lang']['backend']['disabled'])."</strong>"; ?></p>
			<?php 
			} 
			?>
			<p class="success"><?php echo $ccms['lang']['editor']['savesuccess']; ?><em><?php echo $name; ?>.html</em>.</p>
			<hr/>
			<?php 
			if($type=="code") 
			{ 
			?>
				<p><pre><?php echo htmlentities(file_get_contents($filename)); ?></pre></p>
			<?php 
			} 
			else /* if($type=="text") */
			{ 
			?>
				<p><?php echo file_get_contents($filename); ?></p>
			<?php 
			} 
			
			$preview_checkcode = GenerateNewPreviewCode(null, $name);
			?>
			<hr/>
			<p>
				<a href="../../<?php echo $name; ?>.html?preview=<?php echo $preview_checkcode;?>" class="external" target="_blank"><?php echo $ccms['lang']['editor']['preview']; ?></a>
			</p>
			<div class="right">
				<a class="button" href="process.inc.php?file=<?php echo $name; ?>&amp;action=edit&amp;restrict=<?php echo getGETparam4boolYN('restrict', 'N'); ?>&amp;active=<?php echo $active; ?>"><span class="ss_sprite_16 ss_arrow_undo">&#160;</span><?php echo $ccms['lang']['editor']['backeditor']; ?></a>
				<a class="button" href="../index.php" onClick="return confirmation();" title="<?php echo $ccms['lang']['editor']['closewindow']; ?>"><span class="ss_sprite_16 ss_cross">&#160;</span><?php echo $ccms['lang']['editor']['closewindow']; ?></a>
			</div>
		</div>
	<script type="text/javascript" src="../../lib/includes/js/the_goto_guy.js" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
function confirmation()
{
	//var answer = <?php echo (strpos($cfg['verify_alert'], 'X') !== false ? 'confirm("'.$ccms['lang']['editor']['confirmclose'].'")' : 'true'); ?>;
	var answer = true;
	if(answer)
	{
		return !close_mochaUI_window_or_goto_url("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>", '<?php echo $name; ?>_ccms');
	}
	return false;
}
	</script>
	</body>
	</html>
	<?php 	
	} 
	else 
	{
		$db->Kill();
	}
} 
?>