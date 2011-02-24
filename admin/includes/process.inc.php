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








$do_update_or_livefilter = (($do_action == 'update' && $_SERVER['REQUEST_METHOD'] == 'GET') || ($do_action == 'livefilter' && $_SERVER['REQUEST_METHOD'] == 'POST'));




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

	if ($do_action == 'livefilter' && $_SERVER['REQUEST_METHOD'] == 'POST')
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
			echo '<span class="sprite livefilter livefilter_active" rel="' . $name . '" title="' . ucfirst($ccms['lang']['forms']['edit_remove']) . ' ' . strtolower($title) . ' -- ' . $ccms['lang']['forms']['filter_showing'] . ': \'' . htmlspecialchars($_SESSION[$name], ENT_COMPAT, 'UTF-8') . '\'">&#160;</span>';
		}
		else
		{
			echo '<span class="sprite livefilter livefilter_add" rel="' . $name . '" title="' . ucfirst($ccms['lang']['forms']['add']) . ' ' . strtolower($title) . '">&#160;</span>';
		}
	}

	
	/*
	Required to keep the HTML output compliant: a 'id' attribute must be globally unique in the page!
	*/
	function mk_unique_page_id($page_id)
	{
		static $count;
		
		if (empty($count))
		{
			$count = 1;
		}
		else
		{
			$count++;
		}
		return 'pageitem' . $count . '-' . rm0lead($page_id);
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
				<th class="span-3a nowrap"><?php gen_span4pagelist_filterheader('filter_pages_name', $ccms['lang']['forms']['filename']); echo $ccms['lang']['forms']['filename']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['filename'] . ' ' . $ccms['lang']['hints']['filter']; ?>">&#160;</span></th>
				<th class="span-4a nowrap"><?php gen_span4pagelist_filterheader('filter_pages_title', $ccms['lang']['forms']['pagetitle']);  echo $ccms['lang']['forms']['pagetitle']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['pagetitle'] . ' ' . $ccms['lang']['hints']['filter']; ?>">&#160;</span></th>
				<th class="span-6a nowrap"><?php gen_span4pagelist_filterheader('filter_pages_subheader', $ccms['lang']['forms']['subheader']); echo $ccms['lang']['forms']['subheader']; ?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['subheader'] . ' ' . $ccms['lang']['hints']['filter']; ?>">&#160;</span></th>
				<th class="span-2a nowrap center-text">
					<?php
					if($perm->is_level_okay('managePageEditing', $_SESSION['ccms_userLevel']))
					{
						echo $ccms['lang']['forms']['printable'];
						?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['printable']; ?>">&#160;</span>
					<?php
					}
					?>
				</th>
				<th class="span-2a nowrap center-text">
					<?php
					if($perm->is_level_okay('managePageActivation', $_SESSION['ccms_userLevel']))
					{
						echo $ccms['lang']['forms']['published'];
						?><span class="ss_sprite_16 ss_help" title="<?php echo $ccms['lang']['hints']['published']; ?>">&#160;</span>
					<?php
					}
					?>
				</th>
				<th class="span-2a nowrap center-text">
					<?php
					if($perm->is_level_okay('managePageCoding', $_SESSION['ccms_userLevel']))
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
			$owner = explode('||', strval($row->user_ids));
			if((empty($row->user_ids) && $perm->is_level_okay('manageOwners', $_SESSION['ccms_userLevel'])) || in_array($_SESSION['ccms_userID'], $owner) || $perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
			{
				// Determine file specific variables
				$module = '../lib/modules/'.$row->module.'/'.$row->module.'.Manage.php';

				$editing_mode = $perm->is_level_okay('managePageEditing', $_SESSION['ccms_userLevel']);
				$renaming_mode = false;

				// Define $isEven for alternate table coloring
				if($i % 2 != 1)
				{
					if($row->published === 'N')
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
					if($row->published === 'N')
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
				if (checkSpecialPageName($row->urlpage, SPG_IS_NONREMOVABLE))
				{
				?>
					<span class="ss_sprite_16 ss_bullet_red" title="<?php echo $ccms['lang']['auth']['featnotallowed'] . ' : ' . ucfirst($ccms['lang']['backend']['delete']); ?>">&#160;</span>
				<?php
				}
				else if (in_array($row->urlpage, $cfg['restrict']) && !in_array($_SESSION['ccms_userID'], $owner) && !$perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
				{
				?>
					<span class="ss_sprite_16 ss_bullet_orange" title="<?php echo $ccms['lang']['auth']['featnotallowed'] . ' : ' . ucfirst($ccms['lang']['backend']['delete']); ?>">&#160;</span>
				<?php
				}
				else if (!$perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
				{
				?>
					<span class="ss_sprite_16 ss_bullet_yellow" title="<?php echo $ccms['lang']['auth']['featnotallowed'] . ' : ' . ucfirst($ccms['lang']['backend']['delete']); ?>">&#160;</span>
				<?php
				}
				else
				{
					$renaming_mode = true;
				?>
					<input type="checkbox" id="page_id_<?php echo $i;?>" name="page_id[]" value="<?php echo $_SESSION['rc1'].$_SESSION['rc2'].'-'.rm0lead($row->page_id); ?>" />
				<?php
				}
				?>
				</td>
				<td>
					<?php
					if ($renaming_mode)
					{
						?>
						<span class="ss_sprite_16 ss_pencil_go liverename" title="<?php echo $ccms['lang']['backend']['rename_file']; ?>" rel="<?php echo $row->urlpage; ?>" id="<?php echo mk_unique_page_id($row->page_id); ?>" >&#160;</span>
						<?php
					}
					?>
					<label for="page_id_<?php echo $i;?>"><em><abbr title="<?php echo $row->urlpage; ?>.html"><?php echo substr($row->urlpage, 0, 25); ?></abbr></em></label>
				</td>
				<td>
					<span id="<?php echo mk_unique_page_id($row->page_id); ?>" class="<?php echo ($editing_mode ? 'sprite-hover liveedit' : ''); ?>" rel="pagetitle"><?php echo $row->pagetitle; ?></span>
				</td>
				<td>
					<span id="<?php echo mk_unique_page_id($row->page_id); ?>" class="<?php echo ($editing_mode ? 'sprite-hover liveedit' : ''); ?>" rel="subheader"><?php echo $row->subheader; ?></span>
				</td>
				<td class="center-text">
					<?php
					if($editing_mode)
					{
					?>
						<a id="printable-<?php echo rm0lead($row->page_id); ?>" rel="<?php echo $row->printable; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php
							if($row->printable == 'Y')
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
					if($perm->is_level_okay('managePageActivation', $_SESSION['ccms_userLevel']))
					{
					?>
						<a id="published-<?php echo rm0lead($row->page_id); ?>" rel="<?php echo $row->published; ?>" class="sprite editinplace" title="<?php echo $ccms['lang']['backend']['changevalue']; ?>"><?php
							if($row->published == 'Y')
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
					if($perm->is_level_okay('managePageCoding', $_SESSION['ccms_userLevel']))
					{
					?>
						<?php
						if($row->module == 'editor')
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
							echo '&ndash;';
						}
						?>
					<?php
					}
					?>
				</td>
				<?php
				// Check for restrictions
				if ($editing_mode &&
					($row->iscoding != 'Y' || $perm->is_level_okay('managePageCoding', $_SESSION['ccms_userLevel'])) && // deny editing of code pages unless the user has PageCoding permissions
					 (!in_array($row->urlpage, $cfg['restrict']) || in_array($_SESSION['ccms_userID'], $owner))) // only the OWNER is ALWAYS allowed editing access for a restricted page! (unless the owner doesn't have code edit perms and it's a code page)
				{
					$preview_checkcode = GenerateNewPreviewCode($row->page_id);

				?>
					<td class="last right-text nowrap">
						<a href="<?php echo $module; ?>?page_id=<?php echo rm0lead($row->page_id); ?>&amp;action=edit"
						   rel="Edit <?php echo $row->urlpage.'.html'; ?>"
						   class="tabs sprite edit"
						><?php echo $ccms['lang']['backend']['editpage']; ?></a>
						|
						<a href="../<?php echo checkSpecialPageName($row->urlpage, SPG_GIVE_PAGE_URL) . '?preview=' . $preview_checkcode; ?>"
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
				if($i % 2 != 1)
				{
					if($row->published === 'N')
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
					if($row->published === 'N')
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
				<td colspan="5"><strong><?php echo $ccms['lang']['forms']['description']; ?></strong>: <span id="<?php echo mk_unique_page_id($row->page_id); ?>" class="<?php echo ($editing_mode ? 'sprite-hover liveedit' : ''); ?>" rel="description"><?php echo $description; ?></span></td>
				<td colspan="2" class="right-text" style="padding-right:5px;">
					<?php
					if($row->module == 'editor')
					{
						if(!empty($row->toplevel) && !empty($row->menu_id))
						{
							echo '<em>' . $ccms['lang']['backend']['inmenu'] . ':</em> ';
							echo '<strong>' . strtolower($ccms['lang']['menu'][$row->menu_id]) . '</strong> | <em>' . $ccms['lang']['backend']['item'] . ' </em> <strong>' . $row->toplevel . ':' . $row->sublevel . '</strong>';
						}
						else
						{
							echo '<em>'.$ccms['lang']['backend']['notinmenu'].'</em>';
						}
					}
					else //if($row->module != 'editor')
					{
						// TODO: add a module/plugin hook to provide the proper icon/formatting for the module name:
						$modID = '<span class="ss_sprite_16 ss_information">&#160;</span><strong>' . ucfirst($row->module) . '</strong></span>';

						switch ($row->module)
						{
						default:
							$modID = '<span class="ss_sprite_16 ss_brick">&#160;</span><strong>'.ucfirst($row->module).'</strong>';
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
	exit();
}



/**
 *
 * Render the entire menu list
 *
 */
if($do_action == 'renderlist' && $_SERVER['REQUEST_METHOD'] == 'GET' && checkAuth())
{
	// TODO : add interface bits to allow changing this sort order (e.g. click on headers?)
	$menu_sortorder = getGETparam4IdOrNumber('m_order', 'I12LH0');

	if($perm->is_level_okay('manageMenu', $_SESSION['ccms_userLevel']))
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
				if ($i % 2 != 1)
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
							<optgroup label="<?php echo $ccms['lang']['backend']['menutitle']; ?>">
								<?php
								$y = 1;
								while($y <= MENU_TARGET_COUNT)
								{
								?>
									<option <?php echo ($row->menu_id == $y) ? 'selected="selected"' : ''; ?> value="<?php echo $y; ?>"><?php echo $ccms['lang']['menu'][$y]; ?></option>
									<?php
									$y++;
								}
								?>
							</optgroup>
							<optgroup label="<?php echo $ccms['lang']['backend']['floatingtitle']; ?>">
								<option <?php echo ($row->menu_id == 0) ? 'selected="selected"' : ''; ?> value="0"><?php echo $ccms['lang']['backend']['none']; ?></option>
							</optgroup>
						</select>
					</td>
					<td>
						<select class="span-3 last" name="template[<?php echo $pageIdAsStr; ?>]">
							<optgroup label="<?php echo $ccms['lang']['backend']['template']; ?>">
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
									<option <?php echo ($row->toplevel==$z) ? 'selected="selected"' : ""; ?> value="<?php echo $z; ?>"><?php echo $z; ?></option>
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
									<option <?php echo ($row->sublevel==$y) ? 'selected="selected"' : ""; ?> value="<?php echo $y; ?>"><?php echo $y; ?></option>
									<?php
									$y++;
								}
								?>
							</optgroup>
						</select>
					</td>
					<td class="center-text" id="td-islink-<?php echo $pageIdAsStr; ?>">
						<?php
						if(($cv = checkSpecialPageName($row->urlpage, SPG_MUST_BE_LINKED_IN_MENU)) !== null)
						{
						?>
							<input type="checkbox" <?php echo ($cv ? 'checked="checked"' : ''); ?> disabled="disabled" />
						<?php
						}
						else
						{
						?>
							<input type="checkbox" name="islink[<?php echo $pageIdAsStr; ?>]" id="islink-<?php echo $pageIdAsStr; ?>" class="islink" <?php echo($row->islink === 'Y') ? 'checked="checked"' : null; ?> />
						<?php
						}
						?>
					</td>
					<td class="last">
						<abbr title="<?php echo $row->urlpage; ?>.html"><?php echo substr($row->urlpage, 0, 25); ?></abbr>
						<input type="hidden" name="page_id[<?php echo $pageIdAsStr; ?>]" value="<?php echo $pageIdAsStr; ?>" id="page_id"/>
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
	exit();
}








/**
 *
 * Auto-renum ('reorder') the entire menu list
 *
 */
if($do_action == 'reordermenu' && $_SERVER['REQUEST_METHOD'] == 'GET' && checkAuth())
{
	$error = null;

	// TODO : add interface bits to allow changing this sort order (e.g. click on headers?)
	$menu_sortorder = getGETparam4IdOrNumber('m_order', 'I12LH0');

	if($perm->is_level_okay('manageMenu', $_SESSION['ccms_userLevel']))
	{
		// Open recordset for sites' pages
		$rows = $db->SelectObjects($cfg['db_prefix'].'pages', null, null, cvt_ordercode2list($menu_sortorder));
		if ($rows === false) $db->Kill();

		// Check whether the recordset is not empty
		if(count($rows) > 0)
		{
			$major_level = 0;
			$minor_level = 0;

			$old_major_level = -1;

			foreach($rows as $row)
			{
				if ($row->toplevel != $old_major_level)
				{
					$old_major_level = $row->toplevel;

					$major_level++;
					$minor_level = 0;
					$row->toplevel = $major_level;
				}
				else if ($row->sublevel == 0)
				{
					$major_level++;
					$minor_level = 0;
					$row->toplevel = $major_level;
				}

				if ($row->sublevel != 0)
				{
					$row->toplevel = $major_level;

					$minor_level++;
					$row->sublevel = $minor_level;
				}

				$values = array();
				$values['toplevel'] = MySQL::SQLValue($row->toplevel,MySQL::SQLVALUE_NUMBER);
				$values['sublevel'] = MySQL::SQLValue($row->sublevel,MySQL::SQLVALUE_NUMBER);

				if (!$db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($row->page_id,MySQL::SQLVALUE_NUMBER))))
				{
					$error = $db->MyDyingMessage();
				}
			}
		}
	}
	else
	{
		$error = $ccms['lang']['auth']['featnotallowed'];
	}

	if(empty($error))
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_accept" title="'.$ccms['lang']['backend']['success'].'">&#160;</span>'.$ccms['lang']['backend']['success'].'</p><p>'.$ccms['lang']['backend']['reordermenu_done'].'</p>';
	}
	else
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'">&#160;</span>'.$ccms['lang']['system']['error_correct'].'</p><p class="fault">- '.$error.'</p>';
	}
	exit();
}






/**
 *
 * Process the request for new page creation
 *
 */
if($target_form == 'create' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	// Remove all system friendly characters; map index and home, etc. to their default names so we don't get duplicates for special pages
	$post_urlpage = checkSpecialPageName(getPOSTparam4Filename('urlpage'), SPG_GIVE_PAGENAME);

	// Check for non-empty module variable
	$post_module = strtolower(getPOSTparam4Filename('module', 'editor'));

	// Set variables
	$pagetitle  = getPOSTparam4DisplayHTML('pagetitle');
	$subheader  = getPOSTparam4DisplayHTML('subheader');
	$description = getPOSTparam4DisplayHTML('description');

	// Check radio button values
	$printable_pref = getPOSTparam4boolYN('printable', 'Y');
	$published_pref = getPOSTparam4boolYN('published', 'Y');
	$iscoding_pref  = getPOSTparam4boolYN('iscoding', 'N');

	// Start with a clean sheet
	$errors = array();

	// are you allowed to run this action?
	if(!$perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
	{
		$errors[] = $ccms['lang']['auth']['featnotallowed']; // $ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' ;
	}

	if(strstr($post_urlpage, '.') !== FALSE)
		{ $errors[] = $ccms['lang']['system']['error_filedots']; }
	if (strlen($post_urlpage) < 3)
		{ $errors[] = $ccms['lang']['system']['error_filesize']; }
	if (strlen($pagetitle) < 3)
		{ $errors[] = $ccms['lang']['system']['error_pagetitle']; }
	if (strlen($subheader) < 3)
		{ $errors[] = $ccms['lang']['system']['error_subtitle']; }
	if (strlen($description) < 3)
		{ $errors[] = $ccms['lang']['system']['error_description']; }
	if (strlen($post_urlpage) > 50)
		{ $errors[] = $ccms['lang']['system']['error_filesize_2']; }
	if (strlen($pagetitle) > 100)
		{ $errors[] = $ccms['lang']['system']['error_pagetitle_2']; }
	if (strlen($subheader) > 200)
		{ $errors[] = $ccms['lang']['system']['error_subtitle_2']; }
	if (strlen($description) > 250)
		{ $errors[] = $ccms['lang']['system']['error_description_2']; }

	if (0) // it's okay to try to create special pages, particularly when you're trying to recover manually from a completely b0rked install/upgrade!
	{		
		if (in_array($post_urlpage, array('403', '404', 'sitemap', 'home', 'index')))
		{ 
			$errors[] = $ccms['lang']['system']['error_reserved']; 
		}
	}

	$file_freshly_created = false;

	if(count($errors) == 0)
	{
		$filepath = BASE_PATH . '/content/' . $post_urlpage . '.php';

		/*
		WARNING:

		Tolerate it when the file already exists: do not overwrite the contents then.

		This 'feature' exists to help recover broken upgrades / updates: you can thus
		manually add the page records in the database again without lossing the original
		content of the page.
		*/
		if (!file_exists($filepath))
		{
			// Create the actual file
			$filehandle = fopen($filepath, 'w');
			if(!$filehandle)
			{
				$errors[] = $ccms['lang']['system']['error_write'];
			}
			else
			{
				// Write default contents to newly created file
				if($post_module === 'editor')
				{
					if (!fwrite($filehandle, '<p>'.$ccms['lang']['backend']['newfiledone'].'</p>'))
					{
						$errors[] = $ccms['lang']['system']['error_write'];
					}
				}
				else
				{
					// Write require_once tag to file (modname.Show.php)
					$iscoding_pref = true;
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

			if(count($errors) == 0)
			{
				$file_freshly_created = true;
			}
		}
	}

	if(count($errors) == 0)
	{
		// Insert new page into database
		$values = array(); // [i_a] make sure $values is an empty array to start with here

		// $arrayVariable["column name"] = formatted SQL value
		$values['urlpage']      = MySQL::SQLValue($post_urlpage,MySQL::SQLVALUE_TEXT);
		$values['module']       = MySQL::SQLValue($post_module,MySQL::SQLVALUE_TEXT);
		$values['toplevel']     = MySQL::SQLValue(1,MySQL::SQLVALUE_NUMBER);
		$values['sublevel']     = MySQL::SQLValue(0,MySQL::SQLVALUE_NUMBER);
		$values['menu_id']      = MySQL::SQLValue(1,MySQL::SQLVALUE_NUMBER); // [i_a] set to the same value as the DEFAULT as specced in the SQL DB
		$values['pagetitle']    = MySQL::SQLValue($pagetitle,MySQL::SQLVALUE_TEXT);
		$values['subheader']    = MySQL::SQLValue($subheader,MySQL::SQLVALUE_TEXT);
		$values['description']  = MySQL::SQLValue($description,MySQL::SQLVALUE_TEXT);
		$values['srcfile']      = MySQL::SQLValue($post_urlpage.".php",MySQL::SQLVALUE_TEXT);
		$values['printable']    = MySQL::SQLValue($printable_pref,MySQL::SQLVALUE_Y_N);
		$values['published']    = MySQL::SQLValue($published_pref,MySQL::SQLVALUE_Y_N);
		$values['iscoding']     = MySQL::SQLValue($iscoding_pref,MySQL::SQLVALUE_Y_N);
		// the default 'owner' is the one who creates the page:
		$values['user_ids']     = MySQL::SQLValue(intval($_SESSION['ccms_userID']),MySQL::SQLVALUE_TEXT);

		$result = $db->InsertRow($cfg['db_prefix'].'pages', $values);
		// Check for errors
		if($result)
		{
		}
		elseif($db->ErrorNumber() == 1062)
		{
			$errors[] = $ccms['lang']['system']['error_rec_exists'];
		}
		else
		{
			$errors[] = $db->Error(); // Some error which has not been antipicated.

			// Rollback the entire 'transaction': delete the content carrying file when the DB action went bad:
			if ($file_freshly_created)
			{
				@unlink($filepath);
			}
		}
	}

	if(count($errors) != 0)
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'">&#160;</span>'.$ccms['lang']['system']['error_correct'].'</p><ul>';
		foreach($errors as $value)
		{
			echo '<li><p class="fault">' . $value . '</p></li>';
		}
		echo '</ul>';
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
if($target_form == 'delete' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	if(!empty($_POST['page_id']))
	{
		if($perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
		{
			echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_accept" title="'.$ccms['lang']['backend']['success'].'">&#160;</span>'.$ccms['lang']['backend']['statusdelete'].'</p>';

			// Loop through all submitted page ids
			foreach ($_POST['page_id'] as $index)
			{
				$value = explode('-', $index, 2);
				$page_id = filterParam4Number($value[1]);
				if($page_id != 0 && $value[0] == $_SESSION['rc1'] . $_SESSION['rc2'])   // [i_a] complete validation check: test both rc1 and rc2 in the explode+if()
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
					if($module != 'editor')
					{
						$filter = array(); // [i_a] make sure $filter is an empty array to start with here
						$filter['pageID'] = MySQL::SQLValue($correct_filename, MySQL::SQLVALUE_TEXT);
						$delmod = $db->DeleteRows($cfg['db_prefix'].'mod'.$module, $filter);
						$delcfg = $db->DeleteRows($cfg['db_prefix'].'cfg'.$module, $filter);
					}

					if ($result)
					{
						// Delete the actual file
						if(@unlink('../../content/'.$correct_filename.'.php'))
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
					die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
				}
			}
		}
		else
		{
			die($ccms['lang']['auth']['featnotallowed']);
		}
	}
	else
	{
		echo '<p class="h1 ss_has_sprite"><span class="ss_sprite_16 ss_exclamation" title="'.$ccms['lang']['system']['error_general'].'">&#160;</span>'.$ccms['lang']['system']['error_correct'].'</p><p class="fault">- '.$ccms['lang']['system']['error_selection'].'</p>';
	}
	exit();
}




/**
 *
 * Save the menu order, individual templating & menu allocation preferences
 *
 */
if($target_form == 'menuorder' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	$error = null;

	// are you allowed to run this action?
	if($perm->is_level_okay('manageMenu', $_SESSION['ccms_userLevel']))
	{
		if(!empty($_POST['page_id']))
		{
			foreach ($_POST['page_id'] as $page_id)
			{
				$page_id = filterParam4Number($page_id);
				$toplevel = filterParam4Number($_POST['toplevel'][$page_id]);
				$sublevel = filterParam4Number($_POST['sublevel'][$page_id]);
				$templatename = filterParam4Filename($_POST['template'][$page_id]);
				$menu_id = filterParam4Number($_POST['menuid'][$page_id]);
				if (!$page_id || !$toplevel || empty($templatename))
				{
					$error = $ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' ;
					break;
				}

				$values = array(); // [i_a] make sure $values is an empty array to start with here
				$values['toplevel'] = MySQL::SQLValue($toplevel, MySQL::SQLVALUE_NUMBER);
				$values['sublevel'] = MySQL::SQLValue($sublevel, MySQL::SQLVALUE_NUMBER);
				$values['variant']  = MySQL::SQLValue($templatename, MySQL::SQLVALUE_TEXT);
				$values['menu_id']  = MySQL::SQLValue($menu_id, MySQL::SQLVALUE_NUMBER);

				// Execute the update
				if(!$db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER))))
				{
					$error = $db->Error();
				}
			}
		}
		// else: no pages specified; which is okay with us here...
	}
	else
	{
		$error = $ccms['lang']['auth']['featnotallowed'];
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
if($do_action == 'islink' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	if($perm->is_level_okay('manageMenu', $_SESSION['ccms_userLevel']))
	{
		$page_id = getPOSTparam4Number('id');
		$islink_in_menu = getPOSTparam4boolYN('cvalue', 'N');

		$values = array(); // [i_a] make sure $values is an empty array to start with here
		$values['islink'] = MySQL::SQLValue($islink_in_menu, MySQL::SQLVALUE_Y_N);

		if ($db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER))))
		{
			if($values['islink'] == 'Y')
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
	else
	{
		die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
	}
	exit();
}




/**
 *
 * Edit print, publish or iscoding preference
 *
 */
if($do_action == 'editinplace' && $_SERVER['REQUEST_METHOD'] == 'GET' && checkAuth())
{
	/*
	 Only allow if it one of the editinplace entries we know and the user has sufficient rights.

	 Minor remark here: we don't care when user has ANY of these permissions; that's a little less
	 strict than the user iterface code which may legally trigger this action. Alas.
	*/
	if ($perm->is_level_okay('managePageEditing', $_SESSION['ccms_userLevel']) ||
		$perm->is_level_okay('managePageActivation', $_SESSION['ccms_userLevel']) ||
		$perm->is_level_okay('managePageCoding', $_SESSION['ccms_userLevel']))
	{
		// Explode variable with all necessary information
		$page_id = explode('-', getGETparam4IdOrNumber('id'), 2);

		$page_num = filterParam4Number($page_id[1]);

		// Set the action for this call
		if($page_num && ($page_id[0] == 'printable' || $page_id[0] == 'published' || $page_id[0] == 'iscoding'))
		{
			$action  = $page_id[0];
		}
		else
		{
			die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
		}

		$values = array(); // [i_a] make sure $values is an empty array to start with here
		// TOGGLE the flag (printable/published/iscoding) state:
		$values[$action] = MySQL::SQLValue(!getGETparam4boolean('s'),MySQL::SQLVALUE_Y_N);

		if ($db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_num,MySQL::SQLVALUE_NUMBER))))
		{
			if($values[$action] == 'Y')
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
	else
	{
		die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
	}
	exit();
}



/**
 *
 * Edit-in-place update action
 *
 */
if($do_action == 'liveedit' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	if ($perm->is_level_okay('managePageEditing', $_SESSION['ccms_userLevel']))
	{
		$content = getPOSTparam4DisplayHTML('content');
		if(empty($content) || strlen($content) < 3 || strlen($content) > 240)
		{
			die($ccms['lang']['system']['error_value']);
		}

		// Continue with content update
		$page_idcode = explode('-', getPOSTparam4IdOrNumber('id'), 2);
		$page_id = filterParam4Number(count($page_idcode) == 2 ? $page_idcode[1] : 0);

		$dest    = getGETparam4IdOrNumber('part');

		if ($page_id > 0 && !empty($dest))
		{
			$values = array(); // [i_a] make sure $values is an empty array to start with here
			$values[$dest] = MySQL::SQLValue($content,MySQL::SQLVALUE_TEXT);

			if (!$db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER))))
			{
				$db->Kill();
			}
			else
			{
				echo $content;
			}
		}
		else
		{
			die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ', ' . getGETparam4IdOrNumber('id') . ')' );
		}
	}
	else
	{
		die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
	}
	exit();
}








/**
 *
 * Change the page/file name inline.
 *
 * To make it a proper transaction, first try to change the filename, and only if that succeeds edit the database record.
 *
 */
if($do_action == 'liverename' && $_SERVER['REQUEST_METHOD'] == 'POST' && checkAuth())
{
	$page_idcode = explode('-', getPOSTparam4IdOrNumber('id'), 2);
	$page_id = filterParam4Number(count($page_idcode) == 2 ? $page_idcode[1] : 0);
	
	if ($page_id > 0)
	{
		$row = $db->SelectSingleRow($cfg['db_prefix'].'pages', array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER)));
		if (!$row) $db->Kill();

		$owner = explode('||', strval($row->user_ids));
		$oldname = $row->urlpage;
		
		if (checkSpecialPageName($row->urlpage, SPG_IS_NONREMOVABLE)
			|| (in_array($row->urlpage, $cfg['restrict']) && !in_array($_SESSION['ccms_userID'], $owner) && !$perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
			|| !$perm->is_level_okay('managePages', $_SESSION['ccms_userLevel']))
		{
			die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' ); // feature not allowed, really...
		}
		else
		{
			$newname = getPOSTparam4Filename('newname');
			if(empty($newname) || strlen($newname) < 3 || strlen($newname) > 240)
			{
				die($ccms['lang']['system']['error_value']);
			}

			$old_filepath = BASE_PATH . '/content/' . $oldname . '.php';
			$new_filepath = BASE_PATH . '/content/' . $newname . '.php';
			if (!file_exists($old_filepath))
			{
				die($ccms['lang']['system']['error_deleted']);
			}
			else if (file_exists($new_filepath))
			{
				die($ccms['lang']['system']['error_rename_target_exists']);
			}
			else if (!rename($old_filepath, $new_filepath))
			{
				die($ccms['lang']['system']['error_rename']);
			}
			else
			{
				// successfully renamed the file; now adjust the database record accordingly!
				$values = array();
				$values['urlpage'] = MySQL::SQLValue($newname,MySQL::SQLVALUE_TEXT);

				if (!$db->UpdateRow($cfg['db_prefix'].'pages', $values, array('page_id' => MySQL::SQLValue($page_id,MySQL::SQLVALUE_NUMBER))))
				{
					$db->Kill();
				}
				else
				{
					printf($ccms['lang']['backend']['file_renamed_from_to'], $oldname, $newname);
				}
			}
		}
	}
	else
	{
		die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );
	}
	exit();
}










// when we get here, an illegal command was fed to us!
header('Location: ' . makeAbsoluteURI($cfg['rootdir'] . 'lib/includes/auth.inc.php?status=error&msg='.rawurlencode($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' )));
//die('status=error&action-was=' . $do_action . '&check=' . (1 * checkAuth()) . '&msg='.rawurlencode($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' ));
die($ccms['lang']['system']['error_forged'] . ' (' . __FILE__ . ', ' . __LINE__ . ')' );

?>