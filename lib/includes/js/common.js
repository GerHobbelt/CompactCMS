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

function lazyloading_commonJS_done(site_basedir)
{
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

				input.addEvent('keydown', function(e) {
					if (e.key == 'enter')
					{
						this.fireEvent('blur');
					}
				});
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
							el.set('html','<img src="../admin/img/saving.gif" alt="Saving" />');
						},
						onComplete: function() {
							el.set("class","sprite-hover liveedit");
						}
					}).send();
				});
			});
		});


		$$('.liverename').each(function(el) {

			el.addEvent('click',function() {
				el.set('class','liverename2');
				var before = el.get('rel').trim();
				//el.set('html','');

				var input = new Element('textarea', { 'wrap':'soft', 'class':'textarea', 'text':before });

				input.addEvent('click', function (e) {
					e.stop();
					return;
				});

				input.addEvent('keydown', function(e) {
					if (e.key == 'enter')
					{
						this.fireEvent('blur');
					}
				});
				input.inject(el).select();

				var notify = $('notify_res').empty();
				var status = $('notify').addClass('loading');

				//add blur event to input
				input.addEvent('blur', function() {
					//get value, place it in original element
					val = input.get('value').trim();
					el.set('rel', val);

					//save respective record
					var request = new Request.HTML({
						url:'./includes/process.inc.php?action=liverename',
						method:'post',
						update: notify,
						data: 'action=liverename&id='+el.get('id')+'&newname='+encodeURIComponent(val),
						onRequest: function() {
							el.set('html','<img src="../admin/img/saving.gif" alt="Saving" />');
						},
						onComplete: function() {
							status.removeClass('loading');
							refreshContent();
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
				initTooltips();
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

		$('reorder_menu').addEvent('click', function(e) {
			//e = new Event(e);
			e.stop();

			if ($('menuFields'))
			{
				var status = $('notify').addClass('loading');
				var menudepth = $('menuFields').addClass('loading');
				var notify = $('notify_res').empty();

				new Request.HTML({
					method: 'get',
					url: './includes/process.inc.php?action=reordermenu',
					update: notify,
					onComplete: function() {
						editin_init();
						editfilter_init();
						editPlace();
						externalLinks();
						renderList();
						doEditor();
						menudepth.removeClass('loading');
						status.removeClass('loading');
						initTooltips();
					}
				}).send();
			}
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
				//alert('set filter!');
				//el.msg_hdr = el.getParent().get('text').trim().toLowerCase();
				//set_filter_msgs(el);

				el.addEvent('click',function()
				{
					var dyn_list = $('dyn_list');

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
							onRequest: function()
							{
								var status = $('notify').addClass('loading');
								var notify = $('notify_res');

								notify.setStyle('border', 'none');

								// el.set("html","<img src='../admin/img/saving.gif' alt='Saving' />");
								dyn_list = dyn_list.empty().addClass('loading');
							},
							onComplete: function()
							{
								var status = $('notify');
								status.removeClass('loading');
								dyn_list.removeClass('loading');

								// re-register this event as the new content will replace the current node as well as a lot of others - a la 'update'
								editin_init();
								editfilter_init();
								editPlace();
								externalLinks();
								renderList();
								doEditor();
								initTooltips();
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
					initTooltips();
				}
			}).send();
		}
	}

	// Change linkage preference
	function isLink() {
		$$('.islink').addEvent('click', function(islink) {
			var item_id = this.id.split('-')[1];
			var cvalue  = this.checked;
			var status  = $('notify').addClass('loading');
			var islink  = $('td-islink-'+item_id).addClass('printloading');

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
				onComplete: function(domtree, els, resp_html, resp_js) {
					//alert('JS: ' + resp_js);
					//alert('evalJS: ' + (1 * this.options.evalScripts));
					editin_init();
					editfilter_init();
					editPlace();
					externalLinks();
					renderList();
					doEditor();
					dyn_list.removeClass('loading');
					status.removeClass('loading');
					initTooltips();
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
			function(){
				//alert('site_basedir = ' + site_basedir);
				MUI.initialize({
						path: {
							root: site_basedir + 'lib/includes/js/mochaui/'
						}
					});
				//MUI.create('famfamfam');
				if (!MUI.loadPluginGroups(function()
					{
						// done loading!
						//alert('loadplugingroups = TRUE: all plugins loaded now');
						MUI.myChain.callChain();
					}))
				{
					// everything has been loaded already!
					//alert('loadplugingroups = FALSE: all plugins loading DONE');
					MUI.myChain.callChain();
				}
			},
			function()
			{
				//alert('chain 2');
				MUI.Desktop.initialize();
				//MUI.desktop = new MUI.Desktop();
				MUI.myChain.callChain();
			},
			function()
			{
				//alert('chain 3');
				if (!MUI.taskbar)
				{
					alert('internal error: MUI.taskbar has not been initialized. MochaUI execution is corrupted.');
				}
				//MUI.taskbar.draw();
				MUI.myChain.callChain();
			},
			function()
			{
				//alert('chain 4');
				initializeWindows();
				//alert('chain 4b');
				/*
				now the MUI desktop needs a little readjustment as it turns out
				MochaUI isn't accounting properly for the taskbar (scrollbar
				is partly disappearing behind it!): the next line turns out to
				be the right magick dust to jiggle the internals.

				Make FF3 happy:
				*/
				MUI.desktop.setDesktopSize();

				//alert('chain 4-z');
				MUI.myChain.callChain();
			}
		).callChain();
	}

	/**
	*
	* Single calls
	*
	*/
	/*
	Fade non-focused divs

	DON'T do it for IE5/6/7/8 as it looks bloody darn ugly there and it's not a
	usability mandatory thing, so the easy way out here is to just NOT do it.
	*/
	if (Browser.name != 'ie')
	{
		$$('.container-25').each(function(container)
		{
			var divs = container.getChildren('div');
			/*
			Except the 'logo' div: shading that one is odd (doesn't carry any actionable goods) and looks absolutely crappy in IE besides
			*/
			divs = divs.erase(container.getChildren('.logo'));
			divs = divs.erase(container.getChildren('.load_notice'));
			divs.each(function(child) {
				var siblings = divs.erase(child);
				child.addEvents({
					mouseenter: function() { siblings.tween('opacity',0.6); },
					mouseleave: function() { siblings.tween('opacity',1); }
				});
			});
		});
	}

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
	function initTooltips()
	{
		$$('span.ss_help').each(function(element,index) {
			var t = element.get('title');
			if (t)
			{
				var content = t.split('::');
				element.store('tip:title', content[0]);
				element.store('tip:text', content[1]);
			}
		});

		// Create the tooltips
		var tipz = new Tips('.ss_help',{
			className: 'ss_help',
			fixed: true,
			hideDelay: 50,
			showDelay: 50,
			onShow: function(tip, el) {
				/* copy of the Tips.js code: */
				tip.setStyle('display', 'block');

				/* ... plus our custom edit: */
				var txt = el.retrieve('tip:text');
				if (typeof txt != 'null' && txt.length > 500)
				{
					/* make tip box twice as wide for very large texts: */
					tip.setStyles({
						width: '600px'
					});
				}
			}
		});
	}

	// invoke once to set up the tooltips in the index page itself:
	initTooltips();
	//alert('tooltips init');

	/**
	*
	* Editor window preferences
	*
	*/
	initializeWindows = function()
	{
		var reduce_dimensions = function(w, h)
		{
			// Calculate Window size so it'll always fit on screen (for 'reasonable' screen sizes).
			var dimensions = window.getSize();
			var contentdims = $('desktop').getCoordinates();

			dimensions.w = dimensions.x - 20; /* minus scrollbar width */
			dimensions.h = dimensions.y - 85; /* minus MUI dockbar */
			dimensions.w = Math.min(9910, Math.max(400, dimensions.w));
			dimensions.h = Math.min(9640, Math.max(250, dimensions.h));

			dimensions.w2 = dimensions.x - 20; /* minus scrollbar width */
			dimensions.h2 = dimensions.y - 85; /* minus MUI dockbar */
			dimensions.x = 0;
			dimensions.y = 0;

			// var d = dimensions; -- http://my.opera.com/GreyWyvern/blog/show.dml/1725165
			var d =
			{
				w: dimensions.w,
				h: dimensions.h,
				w2: dimensions.w2,
				h2: dimensions.h2,
				x: dimensions.x,
				y: dimensions.y
			};

			if (d.w > w)
			{
				d.w = w;
			}
			if (d.h > h)
			{
				d.h = h;
			}

			/*
			calculate x/y left/top corner:
			*/
			d.x = (d.w2 - d.w) / 2;
			if (d.x < 0) d.x = 0;
			d.y = (d.h2 - d.h) / 2;
			if (d.y < 0) d.y = 0;

			return d;
		};

		MUI.editWindow = function(id,url,title,dims)
		{
			if (0) debugger;
			new MUI.Window({
				id: id+'_ccms',
				title: title,
				content: {
					url: url,
					loadMethod: 'iframe'
				},
				width: dims.w,
				height: dims.h,
				x: dims.x,
				y: dims.y,
				padding: {top: 0, right:0, left:0, bottom:0},

				//,useCanvas: false // (boolean) Set this to false if you don't want a canvas body.
				//,useCanvasControls: false // (boolean) Set this to false if you wish to use images for the buttons.

				onLoaded: function(){
					MUI.notification('Window content was loaded.');
				},
				onCloseComplete: function(){
					MUI.notification('The window is closed.');
				},
				onMinimize: function(){
					MUI.notification('Window was minimized.');
				},
				onMaximize: function(){
					MUI.notification('Window was maximized.');
				},
				onRestore: function(){
					MUI.notification('Window was restored.');
				},
				onResize: function(){
					//MUI.notification('Window was resized.');

					var windowEl = this.el.windowEl;
					var options = this.options;

					var coords = windowEl.getCoordinates();
					var wrappercoords = this.el.contentWrapper.getCoordinates();
					var desktopcoords = window.getCoordinates();

					MUI.notification('Window was resized: xywh: ' + coords.left + ',' + coords.top + ',' + coords.width + ','+ coords.height + ', wrapper xxywh: ' + wrappercoords.left + ',' + wrappercoords.top + ',' + wrappercoords.width + ','+ wrappercoords.height, {
						closeAfter: 3500,
						width: 520,
						height: 240
					});
				},
				onFocus: function(){
					MUI.notification('Window was focused.');
				},
				onBlur: function(){
					MUI.notification('Window lost focus.');
				},
				onDragStart: function(){
					MUI.notification('Window is being dragged.');
				},
				onDragComplete: function(){
					MUI.notification('Window drag complete.');
				}
			});
		};
		$$('a.tabs').addEvent('click', function(e)
		{
			new Event(e).stop();
			//alert(dump(this, 0, 1, 60, "function,object,string:empty"));
			/*
			REDUCE the dimensions of several particular windows:
			*/
			var dims = reduce_dimensions(30000, 30000);

			switch (this.id)
			{
			case "sys-perm":
				/* grid: 20x12.5 / 16 high with feedback msg */
				dims = reduce_dimensions(20*40, 16*40);
				break;

			case "sys-pow":
				/* 24xY @ 8 users: 5 for the left side ==> ~2.5 per user. Y = 3.5+1.5+ ~15/23 per page, plus extra 2 for feedback message */
				var ownercount = get_admin_user_count();
				var pagecount = get_total_page_count();
				var cx = 5 + 2.5 * ownercount;
				var cy = 2.0 + 3.5 + 1.5 + 15.0 * pagecount / 23.0;

				dims = reduce_dimensions(cx*40, cy*40);
				break;

			case "sys-tmp":
				/* now the template editor window is 33x15.5; wider is okay but looks a bit overdone, really */
				dims = reduce_dimensions(33*40, 15.5*40);
				break;

			case "sys-usr":
				/* 22.5 x 13.5 / 15.5 ; height grows at about 4.8 blocks per 8 users + 4 hdr/ftr; plus extra 2 for feedback message */
				var ownercount = get_admin_user_count();
				var cy = 2 + 2 + 2 + 4.8 * ownercount / 8;
				if (cy < 15.5) cy = 15.5;
				dims = reduce_dimensions(22.5*40, cy*40);
				break;

			case "sys-bck":
				/* 22 x 19 - of course dependent on the number of backup archives available for download. */
				dims = reduce_dimensions(22*40, 19*40);
				break;

			case "sys-tran":
				break;

			default:
				/* News/Comments/etc. windows are not readily identifiable by their id, alas. So we have to check their URL: */
				var url = this.href;
				if (url)
				{
					if (url.indexOf('/lib/modules/news/news.Manage.php?') >= 0)
					{
						/* NEWS: 21 x MAXHEIGHT (to facilitate large-ish tinyMCE content in the news item when editing a single item) */
						dims = reduce_dimensions(21*40, 666*40);
					}
					else if (url.indexOf('/lib/modules/comment/comment.Manage.php?') >= 0)
					{
						/* COMMENT: 29x MAXHEIGHT */
						dims = reduce_dimensions(29*40, 666*40);
					}
					else if (url.indexOf('/lib/modules/lightbox/lightbox.Manage.php?') >= 0)
					{
						/* lightbox: 24x12 */
						dims = reduce_dimensions(24*40, 12*40);
					}
					else if (url.indexOf('/lib/modules/editor/editor.Manage.php?') >= 0)
					{
						/* REGULAR EDIT: 30x14.5 */
						dims = reduce_dimensions(30*40, 14.5*40);
					}
					else if (url.indexOf('/admin/includes/process.inc.php?') >= 0)
					{
						/* REGULAR EDIT: 30x14.5 */
						dims = reduce_dimensions(30*40, 14.5*40);
					}
				}
			}
			MUI.editWindow(this.id,this.href,this.rel, dims);
		});

		MUI.clockWindow = function(){
			var cl = new MUI.Window({
				id: 'clock',
				title: 'Clock',
				cssClass: 'transparent',
				shape: 'gauge',
				headerHeight: 30,
				width: 160,
				height: 160,
				x: 10,
				y: 10,
				padding: { top: 0, right: 0, bottom: 0, left: 0 },
				//useCanvas: false, // (boolean) Set this to false if you don't want a canvas body.
				//useCanvasControls: false, // (boolean) Set this to false if you wish to use images for the buttons.
				content: {
					url: '../lib/includes/js/coolclock/clock.html', // '{plugins}coolclock/demo.html',
					require: {
						js: [
							'../lib/includes/js/coolclock/coolclock.js,moreskins.js' // '{plugins}coolclock/scripts/coolclock.js,moreskins.js'
						],
						onload: function(){
							if (CoolClock)
							{
								if (0) // diag / test only code
								{
									var clocks = [];
									for (var skin in CoolClock.config.skins)
									{
										var extra_style = '';
										if (skin == 'chunkySwissOnBlack')
										{
											extra_style = ' style="background-color: black;" ';
										}

										clocks.push('<div class="skintitle"><p>'+skin+'</p>');
										clocks.push('<div class="clock"' + extra_style + '><canvas class="CoolClock:'+skin+'"                ></canvas></div>');
										//clocks.push('<div class="clock"' + extra_style + '><canvas class="CoolClock:'+skin+':::::logClock"   ></canvas></div>');
										//clocks.push('<div class="clock"' + extra_style + '><canvas class="CoolClock:'+skin+':::::logClockRev"></canvas></div>');
										clocks.push('</div>');
									}
									// shuffle three times for luck
									var dcl = $('democlock_collective');
									dcl.innerHTML = clocks.join('\n');
								}

								CoolClock.config.defaultSkin = 'default_v1';
								CoolClock.findAndCreateClocks($('clock'));

								if (0) // diag / test only code
								{
									//alert('all clocks:');
									CoolClock.config.defaultRadius = 25;
									CoolClock.findAndCreateClocks();
								}
							}
						}
					}}
			});

			// when the window already exists (user clicked on icon for the second time), the cl == null
			if (cl && cl.el && cl.el.title && cl.el.title.hide)
			{
				// override the hide() function for the title so that the 'gauge' code in Themes.js does NOT hide the entire window on mouseleave:
				cl.el.title.hide = function(){
					// compare to hide method in Core.js: WE do NOT hide the MUI Window instance as well!
					this.setStyle('display', 'none');
					return this;
				};
			}
		};

		$$('.clock').addEvent('click', function(e){
			e.stop();
			MUI.clockWindow();
		});

		// Deactivate menu header links
		//$$('a.returnFalse').each(function(el) {
		//  el.addEvent('click', function(e) {
		//      new Event(e).stop();
		//  });
		//});

		/*
		resize event has the problem that it is triggered continually when in IE (and tests reveal it's similar in FF3)
		and we do NOT want to spend CPU cycles on repeated updated of the MUI window sizes all the time, so we follow the
		advice found here:

		http://mbccs.blogspot.com/2007/11/fixing-window-resize-event-in-ie.html
		http://mootools-users.660466.n2.nabble.com/Moo-Detecting-window-resize-td3713058.html
		*/
		var resizeTimeout;

		var realResize = function(){

			// resize and move all MUI windows around to ensure they fit the new screen size:
			MUI.each(function(instance){
				if (!instance.isTypeOf || !instance.isTypeOf('MUI.Window') || instance.isMinimized) return;

				var windowEl = instance.el.windowEl;
				var options = instance.options;

				var coords = windowEl.getCoordinates();
				var wrappercoords = instance.el.contentWrapper.getCoordinates();
				var desktopcoords = this.getCoordinates();
				// calculate how much space the window decoration is consuming:
				var decoheight = coords.height - wrappercoords.height;
				var decowidth = coords.width - wrappercoords.width;
				var scrollbarwidth = 20;
				var scrollbarheight = 20;

				if (0) debugger;

				var dw = desktopcoords.width;
				var dh = desktopcoords.height;
				var w = wrappercoords.width;
				var h = wrappercoords.height;

				// adjust w.h of the window if it doesn't fit the new screen size:
				dw -= scrollbarwidth + decowidth;
				dh -= scrollbarheight + decoheight;

				var change = false;
				if (w > dw) { w = dw; change = true; }
				if (h > dh) { h = dh; change = true; }

				// the odd bit is that the top/left corner is defined in terms of decorated MUI window, while its w.h spec is for the /content/, i.e. the wrapper inside.
				var x = coords.left;
				var y = coords.top;

				// move window to left/top when the screen size is small enough to step into the right/bottom edge:
				if (x + w > dw) { x = dw - w; change = true; }
				if (y + h > dh) { y = dh - h; change = true; }

				// what to do about   options.shadowBlur?

				// don't bother resizing when the need isn't there for this one.
				if (!change) return;

				MUI.notification('window.resize triggered: change: ' + (1*change), {
					closeAfter: 1500,
					width: 520,
					height: 240
				});

				options = {
					width: w,
					height: h,
					top: y,
					left: x,
					centered: false
				};
				windowEl.resize(options);

				// if (windowEl.getStyle('visibility') == 'visible')
			}.bind(window /* this */ ));
		};

		window.addEvent('resize', function(e){
			$clear(resizeTimeout);
			resizeTimeout = realResize.delay(200, this);
		});




		// Build windows onLoad
		//MUI.myChain.callChain();
	};

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
	externalLinks();
	//alert('external links init');


	// Close only (load_notice / notice_wrapper)
	function slideNotice(mode)
	{
		var notice_wrapper = $('load_notice'); // notice_wrapper
		if (notice_wrapper)
		{
			var fx = notice_wrapper.get('reveal');
			fx.addEvent('complete', function(){
				//alert('done 1!');
			});
			notice_wrapper.dissolve();
		}
	}



	if ($('addForm')) /* [i_a] extra check due to permissions cutting out certain parts of the page */
	{
		new FormValidator($('addForm') /* ,
		{
			onFormValidate: function(passed, form, event)
			{
				event.stop();
				if (passed)
					form.submit();
			}
		} */ );
		//alert('addform init');
	}


	/*
	admin index screen:

	make sure the outer scrollbars disappear in IE6; they screw up the screen on 1024px wide
	displays:

	CSS:
	html { overflow: hidden; }
	*/
	$('admin_index_page').setStyle('overflow', 'hidden');



	/*
	we're done completely, so we hide the warning notice now. We are GO!
	*/
	//alert('almost done');
	slideNotice('hide');
	//alert('notice slide hide');
}





/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL (default: 0)
 *    max_depth - OPTIONAL (default: 1) maximum recursive dump level; how deep to dig into the object/array
 *    max_lines - OPTIONAL (default: 50) maximum # of lines of text to produce as 'dump' text (handy, for example, when you feed it to alert())
 *    no_show   - OPTIONAL (default: null) which types of info NOT to show (accepts a comma-separated set of types, 'string:empty' is a special one!)
 * Returns  : The textual representation of the array/object.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 * Patched by Ger Hobbelt (max_depth, max_lines, limited_text(), function dump v.s. array/object dump)
 */
function dump(arr, level, max_depth, max_lines, no_show)
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
	if (!no_show) no_show = '';

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
				var shown_any = false;

				dumped_text = level_padding + "("+typeof(arr)+") => [\n";
				for(var item in arr)
				{
					var value = arr[item];
					var show = ((','+no_show+',').indexOf(','+typeof(value)+',') < 0);
					if (!show) continue;
					var show_nonempty = ((','+no_show+',').indexOf(','+typeof(value)+':empty,') >= 0);
					if (show_nonempty)
					{
						//alert('nonempty! '+item+", "+limited_text(value, 60));
						switch (typeof(value))
						{
						case 'string':
							show = (value.length > 0);
							break;

						case 'undefined':
							show = false;
							break;

						case 'number':
							show = (value != 0);
							break;

						case 'boolean':
							show = value;
							break;
						}
						if (!show) continue;
					}

					dumped_text += level_padding + "  '" + item + "' => ";
					dumped_text += dump(value,level+1,max_depth,max_lines,no_show);

					shown_any = true;
				}
				dumped_text += level_padding + "]\n";

				if (!shown_any)
				{
					dumped_text = level_padding + "("+typeof(arr)+"):null\n";
				}
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
			break;

		default:
			//Strings/Chars/Numbers etc.
			dumped_text = "("+typeof(arr)+")=='"+limited_text(arr)+"'\n";
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
