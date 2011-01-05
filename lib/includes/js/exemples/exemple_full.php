<?php
/* ************************************************************
Copyright (C) 2008 - 2010 by Xander Groesbeek (CompactCMS.nl)
Revision:	CompactCMS - v 1.4.2
	
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



$do	= getGETparam4IdOrNumber('do');
$status = getGETparam4IdOrNumber('status');
$status_message = getGETparam4DisplayHTML('msg');


// Set the default template
$dir_temp = BASE_PATH . "/lib/templates/";
$get_temp = getGETparam4FullFilePath('template', $template[0].'.tpl.html');
$chstatus = is_writable_ex($dir_temp.$get_temp); // @dev: to test the error feedback on read-only on Win+UNIX: add '|| 1' here.
	
// Check for filename	
if(!empty($get_temp)) 
{
	if(@fopen($dir_temp.$get_temp, "r")) 
	{
		$handle = fopen($dir_temp.$get_temp, "r");
		// PHP5+ Feature
		// $contents = stream_get_contents($handle);
		// PHP4 Compatibility
		$contents = @fread($handle, filesize($dir_temp.$get_temp));
		$contents = str_replace("<br />", "<br>", $contents);
		fclose($handle);
	} 
} 

// Get permissions
$perm = $db->QuerySingleRowArray("SELECT * FROM ".$cfg['db_prefix'].'cfgpermissions');
if (!$perm) $db->Kill("INTERNAL ERROR: 1 permission record MUST exist!");

if($_SESSION['ccms_userLevel']<$perm['manageTemplate']) 
{
	$chstatus = false; // templates are viewable but NOT WRITABLE when user doesn't have permission to manage these.
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Template Editing module</title>
		<link rel="stylesheet" type="text/css" href="../../img/styles/base.css,liquid.css,layout.css,sprite.css,last_minute_fixes.css" />
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="../../img/styles/ie.css" />
		<![endif]-->
<?php

// TODO: call edit_area_compressor.php only from the combiner: combine.inc.php when constructing the edit_area.js file for the first time.

?>
	<script language="Javascript" type="text/javascript" src="../edit_area/edit_area_full.js"></script>
		<script type="text/javascript">
		// initialisation
		editAreaLoader.init({
			id: "example_1"	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: "both"
			,allow_toggle: true
			,word_wrap: true
		<?php echo ',language:"'.$cfg['editarea_language'].'"'; ?>
			,syntax:"html"
		});
		
		editAreaLoader.init({
			id: "example_2"	// id of the textarea to transform	
			,start_highlight: true
			,allow_toggle: false
			,language: "en"
			,syntax: "html"	
			,toolbar: "search, go_to_line, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"
			,syntax_selection_allow: "css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas,brainfuck"
			,is_multi_files: true
			,EA_load_callback: "editAreaLoaded"
			,show_line_colors: true
		});
		
		editAreaLoader.init({
			id: "example_3"	// id of the textarea to transform	
			,start_highlight: true	
			,font_size: "8"
			,font_family: "verdana, monospace"
			,allow_resize: "y"
			,allow_toggle: false
			,language: "fr"
			,syntax: "css"	
			,toolbar: "new_document, save, load, |, charmap, |, search, go_to_line, |, undo, redo, |, select_font, |, change_smooth_selection, highlight, reset_highlight, |, help"
			,load_callback: "my_load"
			,save_callback: "my_save"
			,plugins: "charmap"
			,charmap_default: "arrows"
				
		});
		
		editAreaLoader.init({
			id: "example_4"	// id of the textarea to transform		
			//,start_highlight: true	// if start with highlight
			//,font_size: "10"	
			,allow_resize: "no"
			,allow_toggle: true
			,language: "de"
			,syntax: "python"
			,load_callback: "my_load"
			,save_callback: "my_save"
			,display: "later"
			,replace_tab_by_spaces: 4
			,min_height: 350
		});
		
		// callback functions
		function my_save(id, content){
			alert("Here is the content of the EditArea '"+ id +"' as received by the save callback function:\n"+content);
		}
		
		function my_load(id){
			editAreaLoader.setValue(id, "The content is loaded from the load_callback function into EditArea");
		}
		
		function test_setSelectionRange(id){
			editAreaLoader.setSelectionRange(id, 100, 150);
		}
		
		function test_getSelectionRange(id){
			var sel =editAreaLoader.getSelectionRange(id);
			alert("start: "+sel["start"]+"\nend: "+sel["end"]); 
		}
		
		function test_setSelectedText(id){
			text= "[REPLACED SELECTION]"; 
			editAreaLoader.setSelectedText(id, text);
		}
		
		function test_getSelectedText(id){
			alert(editAreaLoader.getSelectedText(id)); 
		}
		
		function editAreaLoaded(id){
			if(id=="example_2")
			{
				open_file1();
				open_file2();
			}
		}
		
		function open_file1()
		{
			var new_file= {id: "to\\ é # € to", text: "$authors= array();\n$news= array();", syntax: 'php', title: 'beautiful title'};
			editAreaLoader.openFile('example_2', new_file);
		}
		
		function open_file2()
		{
			var new_file= {id: "Filename", text: "<a href=\"toto\">\n\tbouh\n</a>\n<!-- it's a comment -->", syntax: 'html'};
			editAreaLoader.openFile('example_2', new_file);
		}
		
		function close_file1()
		{
			editAreaLoader.closeFile('example_2', "to\\ é # € to");
		}
		
		function toogle_editable(id)
		{
			editAreaLoader.execCommand(id, 'set_editable', !editAreaLoader.execCommand(id, 'is_editable'));
		}
	

	
	
function confirmation()
{
	var answer=confirm(<?php echo"'".$ccms['lang']['editor']['confirmclose']."'";?>);
	if(answer)
	{
		try
		{
			parent.MochaUI.closeWindow(parent.$('sys-bck_ccms'));
		}
		catch(e)
		{
			if (typeof top.location.replace == "function")
			{
				top.location.replace("<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>");
			}
			else
			{
				top.location.href = "<?php echo makeAbsoluteURI($cfg['rootdir'] . 'admin/index.php'); ?>";
			}
		}
		return true;
	}
	else
	{
		return false;
	}
}
</script>
</head>
<body>
<h2>EditArea examples</h2>
<p>Retrieve EditArea on <a href='http://sourceforge.net/projects/editarea'>sourceforge</a> or on 
	my personal <a href='http://www.cdolivet.com/index.php?page=editArea'>website</a>.
</p>
<form action='' method='post'>
	<fieldset>
		<legend>Example 1</legend>
		<p>Test in English with php syntax, highlighted, toggle enabled, word-wrap enabled, resize enabled and default toolbar. Also offer the possibility to switch on/off the readonly mode.</p>
		<textarea id="example_1" style="height: 350px; width: 100%;" name="test_1">
<?php	
	$authors	= array();
	$news		= array();
	/* this is a long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long long comment for showing word-wrap feature */
	$query	= "SELECT author, COUNT(id) as 'nb_news' FROM news_messages GROUP BY author";
	$result	= mysql_query($query, $DBnews);
	while( $line = mysql_fetch_assoc($result) ){
		$authors[$line["author"]]	= $line["author"];
		$news[$line["author"]]		= $line['nb_news'];
	}
	
	$list= sprintf("('%s')", implode("', '", $authors));
	
	
	$query="SELECT p.people_id, p.name, p.fname, p.status, team_name, t.leader_id=p.people_id as 'team_leader', w.name as 'wp_name', w.type
			FROM people p, teams t, wppartis wp, wps w
			WHERE p.people_id IN $list AND p.org_id=t.team_id AND wp.team_id=t.team_id AND wp.wp_id=w.wp_id 
			GROUP BY p.people_id";
	if(isset($_GET["order"]) && $_GET["order"]!="nb_news")
		$query.=" ORDER BY ".$_GET["order"];
		
	$result=mysql_query($query, $DBkal);
	while($line = mysql_fetch_assoc($result)){
		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", $line["name"], $line["fname"],
			$news[$line["people_id"]], $line["status"], $line["team_name"], ($line["team_leader"]=="1")?"yes":"no", $line["type"], $line["wp_name"]);
	
	}
	printf("</table>");
?>
		</textarea>
		<p>Custom controls:<br />
			<input type='button' onclick='alert(editAreaLoader.getValue("example_1"));' value='get value' />
			<input type='button' onclick='editAreaLoader.setValue("example_1", "new_value");' value='set value' />
			<input type='button' onclick='test_getSelectionRange("example_1");' value='getSelectionRange' />
			<input type='button' onclick='test_setSelectionRange("example_1");' value='setSelectionRange' />
			<input type='button' onclick='test_getSelectedText("example_1");' value='getSelectedText' />
			<input type='button' onclick='test_setSelectedText("example_1");' value='setSelectedText' />
			<input type='button' onclick='editAreaLoader.insertTags("example_1", "[OPEN]", "[CLOSE]");' value='insertTags' />
			<input type='button' onclick='toogle_editable("example_1");' value='Toggle readonly mode' />
		</p>
	</fieldset>
	<fieldset>
		<legend>Example 2</legend>
		<p>Multi file mode example with syntax selection option. The highlight colors of the selected line is also shown.</p>
		<textarea id="example_2" style="height: 250px; width: 100%;" name="test_2">
		</textarea>
		<p>Custom controls:<br />
			<input type='button' onclick='open_file1()' value='open file 1' />
			<input type='button' onclick='open_file2()' value='open file 2' />
			<input type='button' onclick='close_file1()' value='close file 1' />
		</p>
	</fieldset>
	<fieldset>
		<legend>Example 3</legend>
		<p>Test in French with css syntax, verdana font, smaller default font size, toggle disabled, vertical only resize, custom toolbar, visual keyboard plugin, save and load callback examples.</p>
<textarea id="example_3" style="height: 350px; width: 100%;" name="test_3">
/* toolbar buttons (inspired from tinyMCE ones)*/
.editAreaButtonNormal, .editAreaButtonOver, .editAreaButtonDown, .editAreaSeparator, .editAreaSeparatorLine, .editAreaButtonDisabled, .editAreaButtonSelected {
	border: 0px; margin: 0px; padding: 0px; background: transparent;
	margin-top: 0px;
	margin-left: 1px;
	padding: 0px;
}

.editAreaButtonNormal {
	border: 1px solid #ECE9D8 !important;
	cursor: pointer;
}

.editAreaButtonOver {
	border: 1px solid #0A246A !important;
	cursor: pointer;
	background-color: #B6BDD2;
}

.editAreaButtonDown {
	cursor: pointer;
	border: 1px solid #0A246A !important;
	background-color: #8592B5;
}

.editAreaButtonSelected {
	border: 1px solid #C0C0BB !important;
	cursor: pointer;
	background-color: #F4F2E8;
}

.editAreaButtonDisabled {
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=30);
	-moz-opacity:0.3;
	opacity: 0.3;
	border: 1px solid #F0F0EE !important;
	cursor: pointer;
}

.editAreaSeparatorLine {
	margin: 1px 2px;
	background-color: #C0C0BB;
	width: 2px;
	height: 18px;
}		
</textarea>
	</fieldset>
	<fieldset>
		<legend>example 4</legend>
		<p>Test in German with Python syntax, toggle enabled for a later display, with highlight not enabled by default, without resizing possibility (but with larger minimum height when editor is toggled), and with tabs replaced by 4 spaces.</p>
<textarea id="example_4" style="height: 50px; width: 100%;" name="test_4">
import Blender
 
class Python:
	# Instancie un objet
	# cls = la classe Python et non pas l'object instancié
	def __new__(cls):
		pass
	
	# Constructeur de l'objet
	def __init__(self):
		self.items = [1, 2, 3]
	
	# Destructeur
	def __del__(self):
		print "Pourquoi tant de haine ?"
	
	# Utilisé pour : "len(p)"
	def __len__(self):
		return len(self.items)
	
	# Utilisé pour : "p[x]"
	def __getitem__(self, key):
		return self.items[key]
	
	# Utilisé pour : "x in p"
	def __contains__(self, value):
		return (value in self.items)
	
	# Utilisé pour : "for x in p"
	def __iter__(self):
		for x in self.items:
			yield x
</textarea>
	</fieldset>
</form>
</body>
</html>
