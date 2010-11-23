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

window.addEvent('domready', function(){

	function editin_init() {
		$$('.liveedit').each(function(el) {

			el.addEvent('click',function() {
				el.set('class','liveedit2');
				var before = el.get('html').trim();
				el.set('html','');

				var input = new Element('textarea', { 'wrap':'soft', 'class':'textarea', 'text':before });

				input.addEvent('click', function (e) {
					e.stop();
					return;
				});

				input.addEvent('keydown', function(e) { if(e.key == 'enter') { this.fireEvent('blur'); } });
				input.inject(el).select();

				//add blur event to input
				input.addEvent('blur', function() {
					//get value, place it in original element
					val = input.get('value').trim();
					el.set('text',val);

					//save respective record
					var content = el.get('text');
					var request = new Request.HTML({
						url:'./includes/process.inc.php?action=liveedit&part='+el.get('rel'),
						method:'post',
						update: el,
						data: 'action=liveedit&id='+el.get('id')+'&content='+encodeURIComponent(content),
						onRequest: function() {
							el.set("html","<img src='./img/saving.gif' alt='Saving' />");
						},
						onComplete: function() {
							el.set("class","sprite-hover liveedit");
						}
					}).send();
				});
			});
		});
	}

	// Show list onload
	if ($('dyn_list'))
	{
		var dyn_list = $('dyn_list').empty().addClass('loading');

		var req = new Request.HTML({
			method: 'get',
			url: './includes/process.inc.php?action=update',
			update: dyn_list,
			onComplete: function() {
				// We're done, so hide loading class
				dyn_list.removeClass('loading');
				/*
				Hide the create and menu contents

				WARNING: permissions may have made these elements NONEXISTENT so we must check whether they are.
				*/
				var menu_wrapper = $('menu_wrapper');
				if (menu_wrapper)
				{
					menu_wrapper.slide('hide');
				}
				var form_wrapper = $('form_wrapper');
				if (form_wrapper)
				{
					form_wrapper.slide('hide');
				}
				// Execute other functions
				editin_init();
				editfilter_init();
				editPlace();
				externalLinks();
				renderList();
				doEditor();
			}
		}).send();
	}

	/**
	*
	* Actions based on user clicks
	*
	*/
	// Process new page
	if ($('addForm'))
	{
		$('addForm').addEvent('submit', function(add) {
			new Event(add).stop();

			// Setting waiting style
			var notify = $('notify_res').empty();
			var status = $('notify').addClass('loading');
			closeMenu();

			// Wait for response and act
			new Request.HTML({
				method: 'post',
				url: './includes/process.inc.php',
				update: notify,
				onComplete: function() {
					status.removeClass('loading');
					refreshContent();
				}
			}).post($('addForm'));
		});
	}

	// Process delete page
	if ($('delete'))
	{
		$('delete').addEvent('submit', function(remove) {
			var agree = confirm($('ad_msg01').value);

			if(agree) {
				closeMenu();
				new Event(remove).stop();

				//var url = this.href;
				var notify = $('notify_res').empty();
				var status = $('notify').addClass('loading');

				// Wait for response and act
				new Request.HTML({
					method: 'post',
					url: './includes/process.inc.php',
					update: notify,
					onComplete: function() {
						status.removeClass('loading');
						refreshContent();
					}
				}).post($('delete'));
			} else {
				new Event(remove).stop();
			}
		});
	}

	// Process menu order preference
	if ($('menuForm'))
	{
		$('menuForm').addEvent('submit', function(menu) {
			new Event(menu).stop();

			// Setting waiting style
			var notify = $('notify_res').empty();
			var status = $('notify').addClass('loading');

			// Wait for response and act
			new Request.HTML({
				url: './includes/process.inc.php',
				method: 'post',
				update: notify,
				onComplete: function() {
					status.removeClass('loading');
					refreshContent();
				}
			}).post($('menuForm'));
		});
	}

	/**
	*
	* Functions
	*
	*/
	// a la editin_init() but shows a little edit field where you can spec your search/filter criterium, which will trigger a re-render of the entire pages dyn_list.
	function editfilter_init()
	{
		if ($('dyn_list')) // don't check for $('.livefilter') as there ain't one but 3 and the check will FAIL then!
		{
			$$('.livefilter').each(function(el)
			{
				el.msg_hdr = el.getParent().get('text').trim().toLowerCase();
				set_filter_msgs(el);

				//el.msg_edit = '';
				//el.msg_showing = '';
				//el.msg_add = '';

				el.addEvent('click',function()
				{
					el.set('class','livefilter2');
					var before = el.get('title').trim();
					var orightml = el.get('html');
					//el.set('title','');

					// strip user-friendly text off 'before' to arrive at the raw content:
					var li = before.lastIndexOf(": '");
					if (li >= 0)
					{
						before = before.substring(li + 3, before.length);
						li = before.lastIndexOf("'");
						if (li >= 0)
						{
							before = before.substring(0, li);
						}
						else
						{
							// unexpected; start with a nil filter
							before = '';
						}
					}
					else
					{
						before = '';
					}

					var input = new Element('textarea', { 'wrap':'soft', 'class':'textarea', 'text':before });

					input.addEvent('click', function (e) {
						e.stop();
						return;
					});

					input.addEvent('keydown', function(e) { if(e.key == 'enter') { this.fireEvent('blur'); } });
					input.inject(el).select();

					//add blur event to input
					input.addEvent('blur', function() {
						//get value, place it in original element
						var content = input.get('value').trim();
						//el.set('text',content);

						//save respective record
						var request = new Request.HTML({
							url:'./includes/process.inc.php?action=livefilter&part='+el.get('rel'),
							method:'post',
							update: dyn_list,
							data: 'action=livefilter&part='+el.get('rel')+'&content='+encodeURIComponent(content),
							onRequest: function() {
								el.set("html","<img src='./img/saving.gif' alt='Saving' />");
							},
							onComplete: function() {
								el.set("html",orightml);

								var titlemsg = '';
								if (content.length > 0)
								{
									titlemsg = el.msg_edit + " " + el.msg_hdr + " -- " + el.msg_showing + ": '" + content + "'";
									el.set("class","sprite livefilter livefilter_active");
								}
								else
								{
									titlemsg = el.msg_add + " " + el.msg_hdr;
									el.set("class","sprite livefilter livefilter_add");
								}
								el.set("title",titlemsg);

								// re-register this event as the new content will replace the current node as well as a lot of others - a la 'update'
								editin_init();
								//editfilter_init();
								editPlace();
								externalLinks();
								renderList();
								doEditor();
							}
						}).send();
					});
				});
			});
		}
	}

	// Render menu depth list
	function renderList() {
		if ($('menuFields'))
		{
			var menudepth = $('menuFields').addClass('loading');

			new Request.HTML({
				method: 'get',
				url: './includes/process.inc.php?action=renderlist',
				update: menudepth,
				onComplete: function() {
					menudepth.removeClass('loading');
					isLink();
				}
			}).send();
		}
	}

	// Change linkage preference
	function isLink() {
		$$('.islink').addEvent('click', function(islink) {
			var item_id	= this.id;
			var cvalue	= this.checked;
			var status	= $('notify').addClass('loading');
			var islink	= $('td-islink-'+item_id).addClass('printloading');

			new Request({
				url:'./includes/process.inc.php?action=islink',
				method:'post',
				autoCancel:true,
				data:'cvalue=' + cvalue + '&action=islink&id='+item_id,
				onSuccess: function() {
					status.removeClass('loading');
					islink.removeClass('printloading');
				},
				onFailure: function() {
					$('notify').set('text','Undocumented error!');
				}
			}).send();
		});
	}

	// Refresh on print/publish update
	function refreshContent() {
		if ($('dyn_list'))
		{
			var dyn_list = $('dyn_list').empty().addClass('loading');
			var notify = $('notify_res');
			var status = $('notify').addClass('loading');

			notify.setStyle('border', 'none');

			new Request.HTML({
				method: 'get',
				url: './includes/process.inc.php?action=update',
				update: dyn_list,
				onComplete: function() {
					editin_init();
					//editfilter_init();
					editPlace();
					externalLinks();
					renderList();
					doEditor();
					dyn_list.removeClass('loading');
					status.removeClass('loading');
				}
			}).send();
		}
	}

	// Change print or publish value
	function editPlace() {
		$$('.editinplace').addEvent('click', function(editinplace) {
			new Event(editinplace).stop();
			closeMenu();

			var url = './includes/process.inc.php?action=editinplace&id='+this.id+'&s='+this.rel;
			var status = $(this.id).addClass('printloading');

			new Request.HTML({
				method: 'get',
				url: url,
				update: status,
				onComplete: function() {
					status.removeClass('printloading');
					refreshContent();
				}
			}).send();
		});
	}

	// Apply editor window to all $$('.tabs') links
	function doEditor() {
		MUI.myChain = new Chain();
		MUI.myChain.chain(
			function()
			{
				MUI.Desktop.initialize();
			},
			function()
			{
				MUI.Dock.initialize();
			},
			function()
			{
				initializeWindows();
			}
		).callChain();
	}

	/**
	*
	* Single calls
	*
	*/
	// Fade non-focused divs
	$$('.container').each(function(container)
	{
		var divs = container.getChildren('div');
		/*
		Except the 'logo' div: shading that one is odd (doesn't carry any actionable goods) and looks absolutely crappy in IE besides
		*/
		divs = divs.erase(container.getChildren('.logo'));
		divs.each(function(child) {
			var siblings = divs.erase(child);
			child.addEvents({
				mouseenter: function() { siblings.tween('opacity',0.6); },
				mouseleave: function() { siblings.tween('opacity',1); }
			});
		});
	});

	// Toggle editor options
	if($('editor-options')) // [i_a] got to have at least ONE item out there.
	{
		var edtOpt = new Fx.Slide('editor-options');

		if ($('f_mod'))
		{
			$('f_mod').addEvent('change', function(e){
				e = new Event(e);
				e.stop();

				if(this.value!='editor') {
					edtOpt.slideOut();
				} else {
					edtOpt.slideIn();
				}
			});
		}
	}

	// Collapsible
	$$('.toggle').addEvent('click', function(e){
		e = new Event(e);
		e.stop();
		var target = new Fx.Slide(this.rel);
		target.toggle();
	});

	// Close only (menuDepth)
	function closeMenu() {
		/*
		WARNING: permissions may have made these elements NONEXISTENT so we must check whether they are.
		*/
		var menu_wrapper = $('menu_wrapper');
		if (menu_wrapper)
		{
			var menuClose = new Fx.Slide('menu_wrapper');
			menuClose.slideOut();
		}
	}

	// Tips links
	//
	// [i_a] I didn't feel like changing the Tips class in mootools to use different classes depending on
	//       the amount of content shown in the tip, so we hacked it. Ugly, IMHO, but this was quickly
	//       done after futilely messing around with the mootools code for a while.
	$$('span.ss_help').each(function(element,index) {
		var content = element.get('title').split('::');
		element.store('tip:title', content[0]);
		element.store('tip:text', content[1]);
	});
	$$('span.ss_help2').each(function(element,index) {
		var content = element.get('title').split('::');
		element.store('tip:title', content[0]);
		element.store('tip:text', content[1]);
	});

	// Create the tooltips
	var tipz = new Tips('.ss_help',{
		className: 'ss_help',
		fixed: true,
		hideDelay: 50,
		showDelay: 50
	});
	tipz = new Tips('.ss_help2',{
		className: 'ss_help_large',
		fixed: true,
		hideDelay: 50,
		showDelay: 50
	});

	/**
	*
	* Editor window preferences
	*
	*/
	initializeWindows = function(){
		// Calculate Window size so it'll always fit on screen (for 'reasonable' screen sizes).
		var dimensions = window.getSize();
		dimensions.x -= 20; /* minus scrollbar width */
		dimensions.y -= 85; /* minus MUI dockbar */
		dimensions.x = Math.min(9910, Math.max(400, dimensions.x));
		dimensions.y = Math.min(9640, Math.max(250, dimensions.y));
		//alert("dimensions: " + dimensions.x + ", " + dimensions.y);

		// Examples
		MUI.editWindow = function(id,url,title){
			new MUI.Window({
				id: id+'_ccms',
				title: title,
				loadMethod: 'iframe',
				contentURL: url,
				width: dimensions.x,
				height: dimensions.y,
				x: 0,
				y: 0,
				padding: {top: 0, right:0, left:0, bottom:0},
				//useCanvas: false, // (boolean) Set this to false if you don't want a canvas body.
				//useCanvasControls: false, // (boolean) Set this to false if you wish to use images for the buttons.
				toolbar: false
			});
		};
		$$('a.tabs').addEvent('click', function(e){
			new Event(e).stop();
			MUI.editWindow(this.id,this.href,this.rel);
		});

		MUI.clockWindow = function(){
			new MUI.Window({
				id: 'clock',
				title: 'Clock',
				addClass: 'transparent',
				contentURL: '../lib/includes/js/plugins/clock.html',
				shape: 'gauge',
				headerHeight: 30,
				width: 160,
				height: 160,
				x: 10,
				y: 10,
				padding: { top: 0, right: 0, bottom: 0, left: 0 },
				//useCanvas: false, // (boolean) Set this to false if you don't want a canvas body.
				//useCanvasControls: false, // (boolean) Set this to false if you wish to use images for the buttons.
				require: {
					js: ['../lib/includes/js/plugins/clock.js'],
					onload: function(){
						if (CoolClock) new CoolClock();
					}
				}
			});
		};

		$$('.clock').addEvent('click', function(e){
			new Event(e).stop();
			MUI.clockWindow();
		});

		// Deactivate menu header links
		$$('a.returnFalse').each(function(el) {
			el.addEvent('click', function(e) {
				new Event(e).stop();
			});
		});

		// Build windows onLoad
		//MUI.myChain.callChain();
	};
});

// External links script (rel=external)
function externalLinks() {
	if (!document.getElementsByTagName) return;
	var anchors = document.getElementsByTagName("a");

	for (var i=0; i<anchors.length; i++) {
   	var anchor = anchors[i];
		if (anchor.getAttribute("href") &&
			anchor.getAttribute("class") == "external")
			anchor.target = "_blank";
	}
}
window.onload = externalLinks;


/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL (default: 0)
 *    max_depth - OPTIONAL (default: 1) maximum recursive dump level; how deep to dig into the object/array
 *    max_lines - OPTIONAL (default: 50) maximum # of lines of text to produce as 'dump' text (handy, for example, when you feed it to alert())
 * Returns  : The textual representation of the array/object.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 * Patched by Ger Hobbelt (max_depth, max_lines, limited_text(), function dump v.s. array/object dump)
 */
function dump(arr, level, max_depth, max_lines)
{
	var limited_text = function(text, limit)
	{
		var data = ''+text; // convert anything to string
		if (!limit || limit < 0) limit = 128;
		var datalen = data.length;
		if (datalen > limit)
		{
			data = data.substring(0, limit - 1) + " (more...)";
		}
		return data;
	}
	var dumped_text = "";
	if (!level) level = 0;
	if (!max_depth) max_depth = 1;
	if (!max_lines || max_lines < 0) max_lines = 50;

	//The padding given at the beginning of the line.
	var level_padding = (level == 0 ? "" : "\n");
	for(var j = 1; j < level + 1; j++)
		level_padding += "    ";

	try
	{
		switch (typeof(arr))
		{
		case 'object':
			if (level >= max_depth)
			{
				dumped_text = "("+typeof(arr)+") ("+limited_text(arr, 60)+")\n";
			}
			else
			{
				//Array/Hashes/Objects
				dumped_text = level_padding + "("+typeof(arr)+") => [\n";
				for(var item in arr)
				{
					var value = arr[item];

					dumped_text += level_padding + "  '" + item + "' => ";
					dumped_text += dump(value,level+1,max_depth,max_lines);
				}
				dumped_text += level_padding + "]\n";
			}
			break;

		case 'function':
			{
				var funcname = ''+arr;
				var idx = funcname.indexOf('{');
				if (idx > 0)
				{
					funcname = funcname.substring(0, idx - 1);
				}
				dumped_text = funcname+"\n";
			}
			break;

		case 'undefined':
			dumped_text = "("+typeof(arr)+")\n";

		default:
			{
				//Strings/Chars/Numbers etc.
				dumped_text = "("+typeof(arr)+")=='"+limited_text(arr)+"'\n";
			}
			break;
		}
	}
	catch(e)
	{
		dumped_text = "==FAILURE==("+typeof(arr)+")\n";
	}

	var nl_index = 0;
	for ( ; max_lines > 0; max_lines--, nl_index++)
	{
		nl_index = dumped_text.indexOf("\n", nl_index);
		if (nl_index < 0)
		{
			nl_index = dumped_text.length;
			break;
		}
	}
	var dumped_continued = (dumped_text.length > nl_index ? "(continued...)" : "");
	return dumped_text.substring(0, nl_index) + dumped_continued;
}


function makehtml(text)
{
	var textneu = text.replace(/&/g,"&amp;");
	textneu = textneu.replace(/</g,"&lt;");
	textneu = textneu.replace(/>/g,"&gt;");
	textneu = textneu.replace(/\r\n/g,"<br>");
	textneu = textneu.replace(/\n/g,"<br>");
	textneu = textneu.replace(/\r/g,"<br>");
	return(textneu);
}
