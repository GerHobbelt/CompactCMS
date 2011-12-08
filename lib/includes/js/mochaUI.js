/*
 ---

 script: core.js

 description: MUI - A Web Applications User Interface Framework.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 note:
 This documentation is taken directly from the javascript source files. It is built using Natural Docs.

 requires:
 - Core/Array
 - Core/Element
 - Core/Browser
 - Core/Request
 - Core/Request.HTML
 - Hash
 - More/Assets

 provides: [MUI, MochaUI, MUI.Require, NamedClass]

 ...
 */

var MochaUI;
var MUI = MochaUI = (MUI || {});

MUI.append = function(hash){
	Object.append(MUI, hash);
}.bind(MUI);

Browser.webkit = (Browser.safari || Browser.chrome);

MUI.append({
	'options': {
		theme: 'default',
		advancedEffects: false, // Effects that require fast browsers and are cpu intensive.
		standardEffects: true,  // Basic effects that tend to run smoothly.

		path: {
			root:		'../',						// Path to root of other source folders
			source:		'{root}Source/',			// Path to MochaUI source JavaScript
			themes:		'{root}Source/Themes/'		// Path to MochaUI Themes
		},

		pluginGroups: {
			'controls':{path:'{root}Source/Controls/',singularName:'control'},
			'plugins':{path:'{root}Source/Plugins/',singularName:'plugin'}
		},

		themes: ['Default','Charcoal']
	}
});

MUI.append({
	version: '1.0.0',
	initialized: false,
	instances: new Object(),
	registered: new Object(),
	idCount: 0,
	ieSupport: 'excanvas',					// Makes it easier to switch between Excanvas and Moocanvas for testing
	//pluginGroups: ['controls','plugins'],
	path: MUI.options.path,					// depreciated, will be removed

	initialize: function(options){
		if (options){
			if (options.path) options.path = Object.append(MUI.options.path, options.path);
			if (options.pluginGroups) options.pluginGroups = Object.append(MUI.options.pluginGroups, options.pluginGroups);
			Object.append(MUI.options, options);
		}
		Object.each(MUI.options.pluginGroups, MUI.addPluginGroup);
		MUI.initialized = true;
	},

	replaceFields: function(str, values){
		if (values == null) return str;

		if (typeOf(str) == 'string'){
			var keys = str.match(/\{+(\w*)\}+/g);
			if (keys == null) return str;

			// make sure root path and plugin package paths are always checked for
			Object.each(MUI.options.pluginGroups, function(g, name){
				keys.push('{' + name + '}')
			});
			keys.push('{root}');

			keys.each(function(key){
				var name = key.replace(/[\{\}]/g, '');
				if (name == null || name == '') return;

				if (values[name] == null) return;
				var re = new RegExp('\\{' + name + '\\}', 'g');
				str = str.replace(re, values[name]);
			});
			return str;
		}
		if (typeOf(str) == 'array'){
			for (var i = 0; i < str.length; i++){
				str[i] = MUI.replaceFields(str[i], values);
			}
		}
		return str;
	},

	replacePaths: function(files){
		if (!MUI.initialized) MUI.initialize();
		var paths = Object.append({'theme':MUI.options.path.themes + MUI.options.theme + '/'}, MUI.options.path);
		return MUI.replaceFields(files, paths);
	},

	files: new Object({'{source}Core/core.js': 'loaded'}),

	getID: function(el){
		var type = typeOf(el);
		if (type == 'string') return el;    // [i_a] statement order: typeof, the string check
		if (type == 'element') return el.id;
		else if (type == 'object' && el.id) return el.id;
		else if (type == 'object' && el.options && el.options.id) return el.options.id;
		return el;
	},

	get: function(el){
		var id = this.getID(el);
		el = $(id);
		if (el && el.retrieve('instance')) return el.retrieve('instance');
		return this.instances[id];
	},

	set: function(el, instance){
		el = this.getID(el);
		this.instances[el] = instance;
		return instance;
	},

	erase: function(el){
		el = this.getID(el);
		var val = this.instances[el];
		delete this.instances[el];
		return val;
	},

	each: function(func){
		Object.each(this.instances, func);
		return this;
	},

	addPluginGroup: function(group, name){
		MUI.options.pluginGroups[name] = group;
		MUI.options.path[name] = group.path;
	},

	loadPluginGroups:function(onload){
		var js = [];
		Object.each(MUI.options.pluginGroups, function(group, name){
			if (MUI.files['{' + name + '}mui-' + name + '.js'] != 'loaded'){
				MUI[name] = [];
				Object.append(js, ['{' + name + '}mui-' + name + '.js']);
			}
		});
		if (js.length > 0) new MUI.Require({'js':js, 'onload':onload });
		else return false;  // returns false to signal that everything is loaded
		return true;   // returns true to signal that it loading something
	},

	load:function(options){
		options.loadOnly = true;
		MUI.create(options);
	},

	create:function(options){
		if (typeOf(options) == 'string') options = {control:options};
		if (!MUI.initialized) MUI.initialize();
		if (this.loadPluginGroups(function(){
			MUI.create(options);
		})) return;

		var name = options.control.replace(/(^MUI\.)/i, '');
		var cname = name.toLowerCase();

		// try and locate the requested item
		var config;
		var pgName;
		Object.each(MUI.options.pluginGroups, function(group, name){
			if (MUI[name][cname] != null){
				pgName = name;
				config = MUI[name][cname];
			}
		});
		if (config == null) return;

		var path = {};
		var sname = MUI.options.pluginGroups[pgName].singularName;
		if (!config.location) config.location = cname;
		path[sname] = '{' + pgName + '}' + config.location + '/';

		if (config.paths) Object.each(config.paths, function(tpath, name){
			MUI.options.path[name] = MUI.replaceFields(tpath, path);
		});

		var js;
		if (!config.js) js = [path[sname] + cname + '.js'];
		else js = config.js;

		js = MUI.replaceFields(js, path);

		if (js.length > 0 && MUI.files[js[0]] == 'loaded' && !options.fromHTML){
			if (config.loadOnly || options.loadOnly) return null;
			var klass = MUI[name];
			var obj = new klass(options);
			if (options.onNew) options.onNew(obj);
			return obj;
		}

		if (options.fromHTML) js.push(path[sname] + cname + '_html.js');

		var css = [];
		if (config.css) css = config.css;
		css = MUI.replaceFields(css, path);

		new MUI.Require({
			'js':js,
			'css':css,
			'onload':function(){
				if (config.loadOnly || options.loadOnly) return;
				var klass = MUI[name];
				var obj = new klass(options);
				if (options.onNew) options.onNew(obj);
				if (options.fromHTML) ret.fromHTML();
			}
		});
		return null;
	},

	reloadIframe: function(iframe){
		var src = $(iframe).src;
		Browser.firefox ? $(iframe).src = src : top.frames[iframe].location.reload(true);
	},

	notification: function(message, options){
		// [i_a] augment the notification window to carry larger messages
		options = Object.append({
			control: 'MUI.Window',
			loadMethod: 'html',
			closeAfter: 1500,
			type: 'notification',
			cssClass: 'notification',
			content: message,
			width: 220,
			height: 40,
			y: 53,
			padding: {top: 10, right: 12, bottom: 10, left: 12},
			shadowBlur: 5
		}, options);
		MUI.create(options);
	},

	toggleAdvancedEffects: function(link){
		if (MUI.options.advancedEffects){
			MUI.options.advancedEffects = false;
			if (this.toggleAdvancedEffectsLink) this.toggleAdvancedEffectsLink.destroy();
		} else {
			MUI.options.advancedEffects = true;
			if (link){
				this.toggleAdvancedEffectsLink = new Element('div', {
					'class': 'check',
					'id': 'toggleAdvancedEffects_check'
				}).inject(link);
			}
		}
	},

	toggleStandardEffects: function(link){
		if (MUI.options.standardEffects){
			MUI.options.standardEffects = false;
			if (this.toggleStandardEffectsLink) this.toggleStandardEffectsLink.destroy();
		} else {
			MUI.options.standardEffects = true;
			if (link){
				this.toggleStandardEffectsLink = new Element('div', {
					'class': 'check',
					'id': 'toggleStandardEffects_check'
				}).inject(link);
			}
		}
	},

	getData: function(item, property, dfault){
		if (!dfault) dfault = '';
		if (!item || !property) return dfault;
		if (item[property] == null) return dfault;
		return item[property];
	},

	hideSpinner: function(instance){
		if (instance == null) instance = MUI.get(this.id);
		var spinner = $$('.spinner');
		if (instance && instance.el && instance.el.spinner) spinner = instance.el.spinner;
		if ((instance == null || (instance && instance.showSpinner == null)) && spinner){
			var t = (typeof spinner);
			if (t == 'array' || t == 'object') spinner = spinner[0];
			if (spinner) MUI.each(function(instance){
				if (instance.isTypeOf && instance.isTypeOf('MUI.Spinner')) spinner = instance.el.spinner;
			});
			if (!spinner) return;
			(function(){
				var count = this.retrieve("count");
				this.store("count", count ? count - 1 : 0);
				if (count <= 1) this.setStyle('display', 'none');
			}).delay(500, spinner);
			return;
		}
		if (instance && instance.hideSpinner) instance.hideSpinner();
	},

	showSpinner: function(instance){
		if (instance == null) instance = MUI.get(this.id);
		var spinner = $$('.spinner');
		if (instance && instance.el && instance.el.spinner) spinner = instance.el.spinner;
		if ((instance == null || (instance && instance.showSpinner == null)) && spinner){
			var t = (typeof spinner);
			if (t == 'array' || t == 'object') spinner = spinner[0];
			if (spinner) MUI.each(function(instance){
				if (instance.isTypeOf && instance.isTypeOf('MUI.Spinner')) spinner = instance.el.spinner;
			});
			if (!spinner) return;
			var count = spinner.retrieve("count");
			spinner.store("count", count ? count + 1 : 1).show();
			return;
		}
		if (instance && instance.showSpinner) instance.showSpinner();
	},

	register: function(namespace, funcs, depth){
		try{
			if (depth == null) depth = 4;
			if (depth < 0) return;
			for (var name in funcs){
				if (name == '') continue;
				var func = funcs[name];
				if (typeOf(func) != 'function') continue;
				if (typeOf(func) == 'object'){
					MUI.register(namespace + '.' + name, func, depth - 1);
					return;
				}
				MUI.registered[namespace + '.' + name] = func;
			}
		} catch(e){
		}
	},

	getRegistered: function(bind, name, args){
		return function(ev){
			MUI.registered[name].apply(bind, [ev].append(args));
		};
	},

	getWrappedEvent: function(bind, func, args){
		return function(ev){
			func.apply(bind, [ev].append(args));
		};
	},

	getPartnerLoader: function(bind, content){
		return function(ev){
			ev.stop();
			if ($(content.element)) MUI.Content.update(content);
		};
	}
});

var NamedClass = function(name, members){
	members.className = name;
	members.isTypeOf = function(cName){
		if (cName == this.className) return true;
		if (!this.constructor || !this.constructor.parent) return false;
		return this.isTypeOf.apply(this.constructor.parent.prototype, cName);
	};
	return new Class(members);
};

function fixPNG(myImage){
	if (Browser.ie6 && document.body.filters){
		var imgID = (myImage.id) ? "id='" + myImage.id + "' " : "";
		var imgClass = (myImage.className) ? "class='" + myImage.className + "' " : "";
		var imgTitle = (myImage.title) ? "title='" + myImage.title + "' " : "title='" + myImage.alt + "' ";
		var imgStyle = "display:inline-block;" + myImage.style.cssText;
		myImage.outerHTML = "<span " + imgID + imgClass + imgTitle
				+ " style=\"" + "width:" + myImage.width
				+ "px; height:" + myImage.height
				+ "px;" + imgStyle + ";"
				+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
				+ "(src=\'" + myImage.src + "\', sizingMethod='scale');\"></span>";
	}
}

Element.implement({

	shake: function(radius, duration){
		radius = radius || 3;
		duration = duration || 500;
		duration = (duration / 50).toInt() - 1;
		var parent = this.getParent();
		if (parent != $(document.body) && parent.getStyle('position') == 'static'){
			parent.setStyle('position', 'relative');
		}
		var position = this.getStyle('position');
		if (position == 'static'){
			this.setStyle('position', 'relative');
			position = 'relative';
		}
		if (Browser.ie){
			parent.setStyle('height', parent.getStyle('height'));
		}
		var coords = this.getPosition(parent);
		if (position == 'relative' && !Browser.opera){
			coords.x -= parent.getStyle('paddingLeft').toInt();
			coords.y -= parent.getStyle('paddingTop').toInt();
		}
		var morph = this.retrieve('morph');
		var oldOptions;
		if (morph){
			morph.cancel();
			oldOptions = morph.options;
		}

		this.set('morph', {
			duration:50,
			link:'chain'
		});

		for (var i = 0; i < duration; i++){
			morph.start({
				top:coords.y + Number.random(-radius, radius),
				left:coords.x + Number.random(-radius, radius)
			});
		}

		morph.start({
			top:coords.y,
			left:coords.x
		}).chain(function(){
			if (oldOptions){
				this.set('morph', oldOptions);
			}
		}.bind(this));

		return this;
	},

	hide: function(){
		var instance = MUI.get(this.id);
		if (instance != null && instance.hide != null){
			instance.hide();
			return;
		}

		this.setStyle('display', 'none');
		return this;
	},

	show: function(){
		var instance = MUI.get(this.id);
		if (instance != null && instance.show != null){
			instance.show();
			return;
		}

		this.setStyle('display', 'block');
		return this;
	},

	close: function(){
		var instance = MUI.get(this.id);
		if (instance == null || instance.isClosing || instance.close == null) return;
		instance.close();
	},

	resize: function(options){
		var instance = MUI.get(this.id);
		if (instance == null || instance.resize == null){
			if (options.width != null) this.setStyle('width', options.width);
			if (options.height != null) this.setStyle('height', options.height);
		} else instance.resize(options);
		return this;
	}

});

/*// Mootools Patch: Fixes issues in Safari, Chrome, and Internet Explorer caused by processing text as XML.
 Request.HTML.implement({

 processHTML: function(text){
 var match = text.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
 text = (match) ? match[1] : text;
 var container = new Element('div');
 return container.set('html', text);
 }

 });*/

// This makes it so Request will work to some degree locally
if (location.protocol == 'file:'){

	Request.implement({
		isSuccess : function(status){
			return (status == 0 || (status >= 200) && (status < 300));
		}
	});

	Browser.Request = function(){
		return Function.attempt(function(){
			return new ActiveXObject('MSXML2.XMLHTTP');
		}, function(){
			return new XMLHttpRequest();
		});
	};

}

MUI.Require = new Class({

	Implements: [Options],

	options: {
		css: [],
		images: [],
		js: []
		//onload: null
	},

	initialize: function(options){
		this.setOptions(options);
		options = this.options;

		this.assetsToLoad = options.css.length + options.images.length + options.js.length;
		this.assetsLoaded = 0;

		var cssLoaded = 0;

		// Load CSS before images and JavaScript

		if (options.css.length){
			options.css.each(function(sheet){

				this.getAsset(sheet, function(){
					if (cssLoaded == options.css.length - 1){
						if (this.assetsLoaded == this.assetsToLoad - 1) this.requireOnload();
						else {
							// Add a little delay since we are relying on cached CSS from XHR request.
							this.assetsLoaded++;
							this.requireContinue.delay(50, this);
						}
					} else {
						cssLoaded++;
						this.assetsLoaded++;
					}
				}.bind(this));
			}.bind(this));
		} else if (!options.js.length && !options.images.length){
			this.options.onload();
			return true;
		} else this.requireContinue.delay(50, this); // Delay is for Safari
	},

	requireOnload: function(){
		this.assetsLoaded++;
		if (this.assetsLoaded == this.assetsToLoad){
			this.options.onload();
			return true;
		}
	},

	requireContinue: function(){
		var options = this.options;
		if (options.images.length){
			options.images.each(function(image){
				this.getAsset(image, this.requireOnload.bind(this));
			}.bind(this));
		}

		if (options.js.length){
			options.js.each(function(script){
				this.getAsset(script, this.requireOnload.bind(this));
			}.bind(this));
		}
	},

	getAsset: function(source, onload){
		// If the asset is loaded, fire the onload function.
		if (MUI.files[source] == 'loaded'){
			if (typeof onload == 'function'){
				onload();
			}
			return true;
		}

		// If the asset is loading, wait until it is loaded and then fire the onload function.
		// If asset doesn't load by a number of tries, fire onload anyway.
		else if (MUI.files[source] == 'loading'){
			var tries = 0;
			var checker = (function(){
				tries++;
				if (MUI.files[source] == 'loading' && tries < '100') return;
				clearInterval(checker);
				if (typeof onload == 'function'){
					onload();
				}
			}).periodical(50);
		} else {  // If the asset is not yet loaded or loading, start loading the asset.
			MUI.files[source] = 'loading';

			properties = {
				'onload': onload != 'undefined' ? onload : null
			};

			// Add to the onload function
			var oldonload = properties.onload;
			properties.onload = function(){
				MUI.files[source] = 'loaded';
				if (oldonload) oldonload();
			}.bind(this);

			var sourcePath = MUI.replacePaths(source);
			switch (sourcePath.match(/\.\w+$/)[0]){
				case '.js': return Asset.javascript(sourcePath, properties);
				case '.css': return Asset.css(sourcePath, properties);
				case '.jpg':
				case '.png':
				case '.gif': return Asset.image(sourcePath, properties);
			}

			alert('The required file "' + source + '" could not be loaded');
		}
	}
});

Object.append(Asset, {
	// Get the CSS with XHR before appending it to document.head so that we can have an onload callback.
	css: function(source, properties){
		properties = Object.append({
			id: null,
			media: 'screen',
			onload: null
		}, properties);

		new Request({
			method: 'get',
			url: source,
			onComplete: function(){
				newSheet = new Element('link', {
					'id': properties.id,
					'rel': 'stylesheet',
					'media': properties.media,
					'type': 'text/css',
					'href': source
				}).inject(document.head);
				properties.onload();
			}.bind(this),
			onFailure: function(){
			},
			onSuccess: function(){
			}.bind(this)
		}).send();
	},

	getCSSRule: function(selector){
		for (var ii = 0; ii < document.styleSheets.length; ii++){
			var mySheet = document.styleSheets[ii];
			var myRules = mySheet.cssRules ? mySheet.cssRules : mySheet.rules;
			for (var i = 0; i < myRules.length; i++){
				if (myRules[i].selectorText == selector){
					return myRules[i];
				}
			}
		}
		return false;
	}
});

(function(){
	var realConsole = window.console || null,
			fn = function(){},
			disabledConsole = {
				log: fn,
				warn: fn,
				info: fn,
				enable: function(quiet){
					window.dbg = realConsole ? realConsole : disabledConsole;
					if (!quiet) window.dbg.log('dbg enabled.');
				},
				disable: function(){
					window.dbg = disabledConsole;
				}
			};

	if (realConsole){
		realConsole.disable = disabledConsole.disable;
		realConsole.enable = disabledConsole.enable;
	}

	disabledConsole.enable(true);
})();


/*
 ---

 script: canvas.js

 description: Namespace for all canvas drawing functions.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires: [MochaUI/MUI]

 provides: [MUI.Canvas]

 ...
 */

MUI.files['{source}Core/canvas.js'] = 'loaded';

MUI.Canvas = Object.append((MUI.Canvas || {}), {

	drawBox: function(ctx, width, height, shadowBlur, shadowOffset, shadows, headerHeight, cornerRadius, bodyBgColor, headerStartColor, headerStopColor){
		var shadowBlur2x = shadowBlur * 2;

		// This is the drop shadow. It is created onion style.
		if (shadows){
			for (var x = 0; x <= shadowBlur; x++){
				this.roundedRect(
					ctx,
					shadowOffset.x + x,
					shadowOffset.y + x,
					width - (x * 2) - shadowOffset.x,
					height - (x * 2) - shadowOffset.y,
					cornerRadius + (shadowBlur - x),
					[0, 0, 0],
					x == shadowBlur ? .29 : .065 + (x * .01)
				);
			}
		}

		// Window body.
		this._drawBodyRoundedRect(
			ctx, // context
			shadowBlur - shadowOffset.x, // x
			shadowBlur - shadowOffset.y, // y
			width - shadowBlur2x, // width
			height - shadowBlur2x, // height
			cornerRadius, // corner radius
			bodyBgColor // Footer color
		);

		if (headerHeight){
			// Window header.
			this._drawTopRoundedRect(
				ctx, // context
				shadowBlur - shadowOffset.x, // x
				shadowBlur - shadowOffset.y, // y
				width - shadowBlur2x, // width
				headerHeight, // height
				cornerRadius, // corner radius
				headerStartColor, // Header gradient's top color
				headerStopColor // Header gradient's bottom color
			);
		}
	},

	drawGauge: function(ctx, width, height, shadowBlur, shadowOffset, shadows, canvasHeader, headerHeight, bodyBgColor, useCSS3){
		if (shadows && !useCSS3){
			if (Browser.webkit){
				var color=Asset.getCSSRule('.mochaCss3Shadow').style.backgroundColor;
				ctx.shadowColor = color.replace(/rgb/g,'rgba');
				ctx.shadowOffsetX = shadowOffset.x;
				ctx.shadowOffsetY = shadowOffset.y;
				ctx.shadowBlur = shadowBlur;
			} else for (var x = 0; x <= shadowBlur; x++){
				MUI.Canvas.circle(
					ctx,
					width * .5 + shadowOffset.x,
					(height + headerHeight) * .5 + shadowOffset.x,
					(width * .5) - (x * 2) - shadowOffset.x,
					[0, 0, 0],
					x == shadowBlur ? .75 : .075 + (x * .04)
				);
			}
		}
		MUI.Canvas.circle(
			ctx,
			width * .5 - shadowOffset.x,
			(height + headerHeight) * .5 - shadowOffset.y,
			(width * .5) - shadowBlur,
			bodyBgColor,
			1
		);

		if (Browser.webkit){
			ctx.shadowColor = "rgba(0,0,0,0)";
			ctx.shadowOffsetX = 0;
			ctx.shadowOffsetY = 0;
			ctx.shadowBlur = 0;
		}

		if(canvasHeader) {
			// Draw gauge header
			canvasHeader.setStyles({
				'top': shadowBlur - shadowOffset.y,
				'left': shadowBlur - shadowOffset.x
			});
			ctx = canvasHeader.getContext('2d');
			ctx.clearRect(0, 0, width, 100);
			ctx.beginPath();
			ctx.lineWidth = 24;
			ctx.lineCap = 'round';
			ctx.moveTo(13, 13);
			ctx.lineTo(width - (shadowBlur * 2) - 13, 13);
			ctx.strokeStyle = 'rgba(0, 0, 0, .65)';
			ctx.stroke();
		}
	},

	drawBoxCollapsed: function(ctx, width, height, shadowBlur, shadowOffset, shadows, headerHeight, cornerRadius, headerStartColor, headerStopColor){
		var shadowBlur2x = shadowBlur * 2;

		// This is the drop shadow. It is created onion style.
		if (shadows){
			for (var x = 0; x <= shadowBlur; x++){
				this.roundedRect(
					ctx,
					shadowOffset.x + x,
					shadowOffset.y + x,
					width - (x * 2) - shadowOffset.x,
					height - (x * 2) - shadowOffset.y,
					cornerRadius + (shadowBlur - x),
					[0, 0, 0],
					x == shadowBlur ? .3 : .06 + (x * .01)
				);
			}
		}

		// Window header
		this._drawTopRoundedRect2(
			ctx, // context
			shadowBlur - shadowOffset.x, // x
			shadowBlur - shadowOffset.y, // y
			width - shadowBlur2x, // width
			headerHeight + 2, // height
			shadowBlur,
			cornerRadius, // corner radius
			headerStartColor, // Header gradient's top color
			headerStopColor // Header gradient's bottom color
		);

	},

	drawMaximizeButton: function(ctx, x, y, rgbBg, aBg, rgb, a){
		// Circle
		ctx.beginPath();
		ctx.arc(x, y, 7, 0, Math.PI * 2, true);
		ctx.fillStyle = 'rgba(' + rgbBg.join(',') + ',' + aBg + ')';
		ctx.fill();
		// X sign
		ctx.strokeStyle = 'rgba(' + rgb.join(',') + ',' + a + ')';
		ctx.lineWidth = 2;
		ctx.beginPath();
		ctx.moveTo(x, y - 3.5);
		ctx.lineTo(x, y + 3.5);
		ctx.moveTo(x - 3.5, y);
		ctx.lineTo(x + 3.5, y);
		ctx.stroke();
	},

	drawCloseButton: function(ctx, x, y, rgbBg, aBg, rgb, a){
		// Circle
		ctx.beginPath();
		ctx.arc(x, y, 7, 0, Math.PI * 2, true);
		ctx.fillStyle = 'rgba(' + rgbBg.join(',') + ',' + aBg + ')';
		ctx.fill();
		// Plus sign
		ctx.strokeStyle = 'rgba(' + rgb.join(',') + ',' + a + ')';
		ctx.lineWidth = 2;
		ctx.beginPath();
		ctx.moveTo(x - 3, y - 3);
		ctx.lineTo(x + 3, y + 3);
		ctx.moveTo(x + 3, y - 3);
		ctx.lineTo(x - 3, y + 3);
		ctx.stroke();
	},

	drawMinimizeButton: function(ctx, x, y, rgbBg, aBg, rgb, a){
		// Circle
		ctx.beginPath();
		ctx.arc(x, y, 7, 0, Math.PI * 2, true);
		ctx.fillStyle = 'rgba(' + rgbBg.join(',') + ',' + aBg + ')';
		ctx.fill();
		// Minus sign
		ctx.strokeStyle = 'rgba(' + rgb.join(',') + ',' + a + ')';
		ctx.lineWidth = 2;
		ctx.beginPath();
		ctx.moveTo(x - 3.5, y);
		ctx.lineTo(x + 3.5, y);
		ctx.stroke();
	},

	roundedRect: function(ctx, x, y, width, height, radius, rgb, a){
		ctx.fillStyle = 'rgba(' + rgb.join(',') + ',' + a + ')';
		ctx.beginPath();
		ctx.moveTo(x, y + radius);
		ctx.lineTo(x, y + height - radius);
		ctx.quadraticCurveTo(x, y + height, x + radius, y + height);
		ctx.lineTo(x + width - radius, y + height);
		ctx.quadraticCurveTo(x + width, y + height, x + width, y + height - radius);
		ctx.lineTo(x + width, y + radius);
		ctx.quadraticCurveTo(x + width, y, x + width - radius, y);
		ctx.lineTo(x + radius, y);
		ctx.quadraticCurveTo(x, y, x, y + radius);
		ctx.fill();
	},

	triangle: function(ctx, x, y, width, height, rgb, a){
		ctx.beginPath();
		ctx.moveTo(x + width, y);
		ctx.lineTo(x, y + height);
		ctx.lineTo(x + width, y + height);
		ctx.closePath();
		ctx.fillStyle = 'rgba(' + rgb.join(',') + ',' + a + ')';
		ctx.fill();
	},

	circle: function(ctx, x, y, diameter, rgb, a){
		ctx.beginPath();
		ctx.arc(x, y, diameter, 0, Math.PI * 2, true);
		ctx.fillStyle = 'rgba(' + rgb.join(',') + ',' + a + ')';
		ctx.fill();
	},

	_drawBodyRoundedRect: function(ctx, x, y, width, height, radius, rgb){
		ctx.fillStyle = 'rgba(' + rgb.join(',') + ', 1)';
		ctx.beginPath();
		ctx.moveTo(x, y + radius);
		ctx.lineTo(x, y + height - radius);
		ctx.quadraticCurveTo(x, y + height, x + radius, y + height);
		ctx.lineTo(x + width - radius, y + height);
		ctx.quadraticCurveTo(x + width, y + height, x + width, y + height - radius);
		ctx.lineTo(x + width, y + radius);
		ctx.quadraticCurveTo(x + width, y, x + width - radius, y);
		ctx.lineTo(x + radius, y);
		ctx.quadraticCurveTo(x, y, x, y + radius);
		ctx.fill();
	},

	_drawTopRoundedRect: function(ctx, x, y, width, height, radius, headerStartColor, headerStopColor){
		var lingrad = ctx.createLinearGradient(0, 0, 0, height);
		lingrad.addColorStop(0, 'rgb(' + headerStartColor.join(',') + ')');
		lingrad.addColorStop(1, 'rgb(' + headerStopColor.join(',') + ')');
		ctx.fillStyle = lingrad;
		ctx.beginPath();
		ctx.moveTo(x, y);
		ctx.lineTo(x, y + height);
		ctx.lineTo(x + width, y + height);
		ctx.lineTo(x + width, y + radius);
		ctx.quadraticCurveTo(x + width, y, x + width - radius, y);
		ctx.lineTo(x + radius, y);
		ctx.quadraticCurveTo(x, y, x, y + radius);
		ctx.fill();
	},

	_drawTopRoundedRect2: function(ctx, x, y, width, height, shadowBlur, radius, headerStartColor, headerStopColor){
		// Chrome is having trouble rendering the LinearGradient in this particular case
		if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1){
			ctx.fillStyle = 'rgba(' + headerStopColor.join(',') + ', 1)';
		} else {
			var lingrad = ctx.createLinearGradient(0, shadowBlur - 1, 0, height + shadowBlur + 3);
			lingrad.addColorStop(0, 'rgb(' + headerStartColor.join(',') + ')');
			lingrad.addColorStop(1, 'rgb(' + headerStopColor.join(',') + ')');
			ctx.fillStyle = lingrad;
		}
		ctx.beginPath();
		ctx.moveTo(x, y + radius);
		ctx.lineTo(x, y + height - radius);
		ctx.quadraticCurveTo(x, y + height, x + radius, y + height);
		ctx.lineTo(x + width - radius, y + height);
		ctx.quadraticCurveTo(x + width, y + height, x + width, y + height - radius);
		ctx.lineTo(x + width, y + radius);
		ctx.quadraticCurveTo(x + width, y, x + width - radius, y);
		ctx.lineTo(x + radius, y);
		ctx.quadraticCurveTo(x, y, x, y + radius);
		ctx.fill();
	}

});

/*
 ---

 script: content.js

 description: core content update routines

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires:
 - MochaUI/MUI

 provides: [MUI.Content.update]

 ...
 */

MUI.files['{source}Core/content.js'] = 'loaded';

MUI.Content = Object.append((MUI.Content || {}), {

	Providers: {},

	Filters: {},

	update: function(content){

		// set defaults for options
		/*		content = Object.append({
		 instance:		null,			// the instance of the control to be updated, this is normally used internally only
		 element:		null,			// the element to inject into, or the instance name
		 method:		null,			// the method to use to make request, 'POST' or 'GET'
		 data:			null,			// the data payload to send to the url
		 content:		null,			// used to feed content instead of requesting from a url endpoint
		 loadMethod:	null,			// the provider that will be used to make the request
		 url:			null,			// the url endpoint to make the request to
		 prepUrl:		null,			// callback that is executed to prepare the url. syntax: prepUrl.run([url,values,instance],this) return url;
		 require:		{},				// used to add additional css, images, or javascript
		 paging:		{},				// used to specify paging parameters
		 filters:		[],				// used to make post request processing/filtering of data, can be used to convert request to JSON
		 persist:		false			// true if you want to persist the request, false if you do not.
		 // if it is a string value the string will be used to persist the data instead of the request URL.
		 // if it is an array, it will assume the array is an array of strings and each string represents a cache key that is also the name of a hash value that needs to cached individually.
		 //onLoaded:		null			// fired when content is loaded
		 }, content);*/

		// set defaults for require option
		content.require = Object.append({
			css: [],			// the style sheets to load before the request is made
			images: [],			// the images to preload before the request is made
			js: [],				// the JavaScript that is loaded and called after the request is made
			onload: function(){
			}// the event that is fired after all required files are loaded
		}, content.require);

		// set defaults for paging
		content.paging = Object.append(MUI.Content.PagingOptions, content.paging);

		// detect subcontrol content
		if (content.control){
			if (!content.options) content.options = {};
			if (content.url) content.options.url = content.url;
			if (content.loadMethod) content.options.loadMethod = content.loadMethod;
			content.loadMethod = 'control';
		}

		// make sure loadMethod has a value
		if (!content.loadMethod){
			if (instance == null || instance.options == null || !instance.options.loadMethod){
				if (!content.url) content.loadMethod = 'html';
				else content.loadMethod = 'xhr';
			} else {
				content.loadMethod = instance.options.loadMethod;
			}
		}

		var instance = content.instance;
		var element = content.element = $(content.element);
		if (!instance && element) instance = element.retrieve('instance');
		content.instance = instance;

		// -- argument pre-processing override --
		// allow controls to process any custom arguments, titles, scrollbars, etc..
		if (instance && instance.updateStart) instance.updateStart(content);

		// no content or url and not a subcontrol? nothing else to do beyond this point
		if (!content.url && !content.content && content.loadMethod != 'control') return content;

		// replace in path replacement fields,  and prepare the url
		content.doPrepUrl = (function(prepUrl){
			return function(content){
				if (content.url){
					// create standard field replacements from data, paging, and path hashes
					var values = Object.merge(content.data || {}, content.paging || {}, MUI.options.path || {});
					// call the prepUrl callback if it was defined
					if (prepUrl) return prepUrl.apply(this, [content.url, values, instance]);
					return MUI.replaceFields(content.url, values);
				}
			};
		})(content.prepUrl);

		// -- content removal --
		// allow controls option to clear their own content
		var removeContent = false;
		if (instance && instance.updateClear) removeContent = instance.updateClear(content);

		// Remove old content.
		if (removeContent && element) element.empty().show();

		// prepare function to persist the data
		if (content.persist && MUI.Content.Providers[content.loadMethod].canPersist){
			// if given string to use as persist key then use it
			if (typeOf(content.persist) == 'string') content.persistKey = content.persist;
			if (typeOf(content.persist) == 'array') content.persistKey = content.persist;
			content.persist = true;
		} else content.persist = false;

		content.persistLoad = function(){
			this.persistKey = this.doPrepUrl(this);
			if (this.persist){
				if (typeOf(this.persistKey) == 'string'){
					// load the response
					var content = MUI.Persist.get(this.persistKey, this.url);
					if (content) return content;
				}
			}
			return this.content;
		}.bind(content);

		content.persistStore = function(response){
			if (!this.persist) return response;

			// store the response
			if (typeOf(this.persistKey) == 'string') MUI.Persist.set(this.persistKey, response, this.url);
			if (typeOf(this.persistKey) == 'array'){
				response = JSON.decode(response);
				this.persistKey.each(function(key){
					MUI.Persist.set(key, response[key], this.url);
				}, this);
				return null;
			}
			return response;
		}.bind(content);

		// prepare function to fire onLoaded event
		content.fireLoaded = function(){
			var fireEvent = true;
			var instance = this.instance;
			if (instance && instance.updateEnd) fireEvent = instance.updateEnd(this);
			if (fireEvent){
				if (this.require.js.length){
					// process javascript dependencies
					new MUI.Require({
						js: this.require.js,
						onload: function(){
							if (Browser.opera) this.require.onload.delay(100);
							else this.require.onload();
							if (this.onLoaded && this.onLoaded != null){
								this.onLoaded(element, this);
							} else {
								if (instance) instance.fireEvent('loaded', [element, this]);
							}
						}.bind(this)
					});
				} else {
					if (this.onLoaded && this.onLoaded != null){
						// call onLoaded directly
						this.onLoaded(element, this);
					} else {
						// fire the event
						if (instance) instance.fireEvent('loaded', [element, this]);
					}
				}
			}
		}.bind(content);

		// now perform dependencies requests for images and style sheets
		if (content.require.css.length || content.require.images.length){
			new MUI.Require({
				css: content.require.css,
				images: content.require.images,
				onload: function(){
					MUI.Content.Providers[this.loadMethod].doRequest(this);
				}.bind(content)
			});
		} else {
			MUI.Content.Providers[content.loadMethod].doRequest(content);
		}

		return content;
	},

	processFilters: function(content){
		if (typeof content == 'string') return content;
		Object.each(content.filters, function(filter){
			content.content = filter(content.content, content);
		});
		return content.content;
	},

	canPage:function(content){
		return !(!content || !content.fireLoaded || !content.paging || content.paging.pageSize <= 0 || content.paging.total == 0);
	},

	firstPage: function(content){
		if (!MUI.Content.canPage(content)) return this;
		content.paging.page = 1;
		if (content.instance && content.instance.updateStart) content.instance.updateStart(content);
		MUI.Content.Providers[content.loadMethod].doRequest(content);
		return this;
	},

	prevPage: function(content){
		if (!MUI.Content.canPage(content)) return this;
		content.paging.page--;
		if (content.paging.page < 1 && content.paging.wrap) return this.lastPage(content);
		if (content.paging.page < 1) content.paging.page = 1;
		if (content.instance && content.instance.updateStart) content.instance.updateStart(content);
		MUI.Content.Providers[content.loadMethod].doRequest(content);
		return this;
	},

	nextPage: function(content){
		if (!MUI.Content.canPage(content)) return this;
		content.paging.page++;
		var lastPage = Math.round(content.paging.total / content.paging.pageSize);
		if (content.paging.page > lastPage && content.paging.wrap) return this.firstPage();
		if (content.paging.page > lastPage) content.paging.page = lastPage;
		if (content.instance && content.instance.updateStart) content.instance.updateStart(content);
		MUI.Content.Providers[content.loadMethod].doRequest(content);
		return this;
	},

	lastPage: function(content){
		if (!MUI.Content.canPage(content)) return this;
		content.paging.page = Math.round(content.paging.total / content.paging.pageSize);
		if (content.instance && content.instance.updateStart) content.instance.updateStart(content);
		MUI.Content.Providers[content.loadMethod].doRequest(content);
		return this;
	},

	gotoPage: function(content, page){
		if (!MUI.Content.canPage(content)) return this;
		if (!page) page = 1;
		page = parseInt('' + page);
		var lastPage = parseInt(content.paging.total / content.paging.pageSize);
		if (page > lastPage) page = lastPage;
		if (page < 1) page = 1;
		content.paging.page = page;
		if (content.instance && content.instance.updateStart) content.instance.updateStart(content);
		MUI.Content.Providers[content.loadMethod].doRequest(content);
		return this;
	},

	setPageSize: function(content, max){
		var paging = content.paging;
		if (!MUI.Content.canPage(content)) return this;
		max = parseInt('' + max);
		if (max <= 0) return this;
		paging.pageSize = max;
		paging.page = 1;
		paging.pageMax = parseInt(paging.total / paging.pageSize);
		if (content.instance && content.instance.updateStart) content.instance.updateStart(content);
		MUI.Content.Providers[content.loadMethod].doRequest(content);
		return this;
	},

	setRecords: function(content){
		if (!content.content) return null;
		var paging = content.paging;

		var records;
		if (!paging || !paging.recordsField || !content.content[paging.recordsField]) records = content.content;
		else records = content.content[paging.recordsField];

		['total','page','pageMax','pageSize','page','last','first'].each(function(options, name){
			options.paging[name] = MUI.getData(options.content, options.paging[name + 'Field'], 0);
		}.bind(this, content));
		delete content.content;

		if (!content.fireLoaded || !paging || paging.pageSize <= 0)
			return content.records = records;

		if (!content.records) content.records = records;
		else {
			for (var i = 0,t = ((paging.page - 1) * paging.pageSize); i < records.length; i++,t++){
				content.records[t] = records[i];
			}
		}
	},

	getRecords: function(content){
		var records = content.records;
		if (!records) return null;
		var paging = content.paging;

		if (!content.fireLoaded || !paging || paging.pageSize <= 0) return records;

		var retval = [];
		for (var i = ((paging.page - 1) * paging.pageSize),t = 0; t < paging.pageSize && i < records.length; i++,t++){
			retval[t] = records[i];
		}
		return retval;
	}

});

MUI.updateContent = MUI.Content.update;

MUI.Content.Filters.tree = function(response, content, node){
	var usePaging = node == null && content.paging && content.paging.size > 0 && content.paging.recordsField;
	var data = response, i;

	if (node == null) content = Object.append(content, {
		fieldParentID: 'parentID',
		fieldID: 'ID',
		fieldNodes: 'nodes',
		topID: '0'
	});

	if (usePaging) data = response[content.paging.recordsField];

	if (node == null){
		for (i = 0; i < data.length; i++){
			if (data[i][content.fieldID] == content.topID){
				node = data[i];
				break;
			}
		}
	}

	if (node != null){
		var id = node[content.fieldID];
		node[content.fieldNodes] = [];
		for (i = 0; i < data.length; i++){
			if (data[i][content.fieldParentID] == id && data[i][content.fieldID] != id){
				node[content.fieldNodes].push(data[i]);
				MUI.Content.Filters.tree(data, content, data[i]);
			}
		}
	}

	if (usePaging) response[content.paging.recordsField] = node;

	return node;
};

MUI.Content.Providers.xhr = {

	canPersist:		true,

	canPage:		false,

	doRequest: function(content){
		// if js is required, but no url, fire loaded to proceed with js-only
		if (content.url == null && content.require.js && content.require.js.length != 0){
			Browser.ie6 ? content.fireLoaded.delay(50, content) : content.fireLoaded();
			return null;
		}

		// load persisted data if it exists
		content.content = content.persistLoad(content);

		// process content passed to options.content or persisted data
		if (content.content){
			content.content = MUI.Content.processFilters(content);
			Browser.ie6 ? content.fireLoaded.delay(50, content) : content.fireLoaded();
			return;
		}

		var request=new Request({
			url: content.persistKey,
			method: content.method ? content.method : 'get',
			data: content.data ? new Object(content.data).toQueryString() : '',
			evalScripts: function(script){
				content.javascript = script;
			},
			evalResponse: false,
			onRequest: function(){
				MUI.showSpinner(this.instance);
			}.bind(content),
			onFailure: function(response){
				var content = this;
				var instance = this.instance;
				var getTitle = new RegExp('<title>[\n\r\\s]*(.*)[\n\r\\s]*</title>', 'gmi');
				var error = getTitle.exec(response.responseText);
				if (!error) error = [500, 'Unknown'];

				var updateSetContent = true;
				content.error = error;
				content.errorMessage = '<h3>Error: ' + error[1] + '</h3>';
				if (instance && instance.updateSetContent) updateSetContent = instance.updateSetContent(content);
				if (this.element){
					if (updateSetContent) this.element.set('html', content.errorMessage);
					MUI.hideSpinner(instance);
				}
			}.bind(content),
			onSuccess: function(text){
				content = this._content;
				var instance = content.instance;
				text = content.persistStore(text);
				text = MUI.Content.processFilters(text, content);
				MUI.hideSpinner(instance);

				var js = content.javascript, html = text;

				// convert text files to html
				if (this.getHeader('Content-Type') == 'text/plain') html = html.replace(/\n/g, '<br>');

				var updateSetContent = true;
				content.content = html;
				if (instance && instance.updateSetContent) updateSetContent = instance.updateSetContent(content);
				if (updateSetContent){
					if (content.element) content.element.set('html', content.content);
					var evalJS = true;
					if (instance && instance.options && instance.options.evalScripts) evalJS = instance.options.evalScripts;
					if (evalJS && js) Browser.exec(js);
				}

				Browser.ie6 ? content.fireLoaded.delay(50, content) : content.fireLoaded();
			},
			onComplete: function(){
			}
		});
		request._content = content;
		request.send();
	}
};

MUI.Content.Providers.json = {

	canPersist:		true,

	canPage:		 true,

	_checkRecords: function(content){  // check to see if records already downloaded and fir onLoaded if it does
		var paging = content.paging;
		if (content.records && paging.pageSize == 0){
			Browser.ie6 ? content.fireLoaded.delay(50, content) : content.fireLoaded();
			return true;	// return them all if they exists and paging is turned off
		}
		if (content.records && paging.pageSize > 0){							// if paging is on make sure we have that page
			var first = ((paging.page - 1) * paging.pageSize);
			var last = first + paging.pageSize - 1;
			var total = content.records.length;
			//if (!paging.pageMax) paging.pageMax = parseInt(paging.total / paging.pageSize);
			if (total > first && total > last){
				for (var i = first; i <= last; i++){
					if (!content.records[i]) return false;
				}
				// if in scope then fire loaded to make control know we have the records
				Browser.ie6 ? content.fireLoaded.delay(50, content) : content.fireLoaded();
				return true
			}
		}
		return false;
	},

	doRequest: function(content){
		if (content.content && !content.url){
			Browser.ie6 ? content.fireLoaded.delay(50, this) : content.fireLoaded();
			return;
		}

		if (!this._checkRecords(content)){
			// load persisted data if it exists
			content.content = JSON.decode(content.persistLoad(content));
			MUI.Content.setRecords(content);												// see if any records are there
		} else content.persistKey = content.doPrepUrl(content);

		if (!this._checkRecords(content)){
			// still not found so load
			new Request({
				url: content.persistKey,
				update: content.element,
				method: content.method ? content.method : 'get',
				data: content.data ? new Object(content.data).toQueryString() : '',
				evalScripts: false,
				evalResponse: false,
				headers: {'Content-Type':'application/json'},
				onRequest: function(){
					MUI.showSpinner(this.instance);
				}.bind(content),
				onFailure: function(){
					var updateSetContent = true;
					this.error = [500, 'Error Loading XMLHttpRequest'];
					this.errorMessage = '<p><strong>Error Loading XMLHttpRequest</strong></p>';
					if (this.instance && this.instance.updateSetContent) updateSetContent = this.instance.updateSetContent(this);

					if (this.element){
						if (updateSetContent) this.element.set('html', this.errorMessage);
						this.element.hideSpinner(this.instance);
					}
				}.bind(content),
				onException: function(){
				}.bind(content),
				onSuccess: function(json){
					this.persistStore(json);
					if (json != null){	// when multiple results are persisted, null is returned.  decoding takes place in persistStore instead, and filtering is not allowed
						json = JSON.decode(json);
						this.content = json;
						MUI.Content.setRecords(this);
						json = MUI.Content.processFilters(this);
					}
					MUI.hideSpinner(content.instance);
					Browser.ie6 ? this.fireLoaded.delay(50, this) : this.fireLoaded();
				}.bind(content),
				onComplete: function(){
				}.bind(content)
			}).send();
		}
	}
};

MUI.Content.Providers.iframe = {

	canPersist:		false,

	canPage:		false,

	doRequest: function(content){
		var updateSetContent = true;
		var instance = content.instance;
		if (instance && instance.updateSetContent) updateSetContent = instance.updateSetContent(content);
		var element = content.element;

		if (updateSetContent && element){
			var iframeEl = new Element('iframe', {
				id: element.id + '_iframe',
				name: element.id + '_iframe',
				'class': 'mochaIframe',
				src: content.doPrepUrl(content),
				marginwidth: 0,
				marginheight: 0,
				frameBorder: 0,
				scrolling: 'auto',
				styles: {
					height: element.offsetHeight - element.getStyle('border-top').toInt() - element.getStyle('border-bottom').toInt(),
					width: instance && instance.el.panel ? element.offsetWidth - element.getStyle('border-left').toInt() - element.getStyle('border-right').toInt() : '100%'
				}
			}).inject(element);
			if (instance) instance.el.iframe = iframeEl;

			// Add onload event to iframe so we can hide the spinner and run fireLoaded()
			iframeEl.addEvent('load', function(){
				MUI.hideSpinner(instance);
				Browser.ie6 ? this.fireLoaded.delay(50, this) : this.fireLoaded();
			}.bind(content));
		}
	}

};

MUI.Content.Providers.html = {

	canPersist:		false,

	canPage:		false,

	doRequest: function(content){
		var elementTypes = new Array('element', 'textnode', 'whitespace', 'collection');

		var updateSetContent = true;
		if (content.instance && content.instance.updateSetContent) updateSetContent = content.instance.updateSetContent(content);
		if (updateSetContent && content.element){
			if (elementTypes.contains(typeOf(content.content))) content.content.inject(content.element);
			else content.element.set('html', content.content);
		}

		Browser.ie6 ? content.fireLoaded.delay(50, content) : content.fireLoaded();
	}

};

MUI.Content.Providers.control = {

	canPersist:		false,

	canPage:		false,

	doRequest: function(content){
		//var options2 = content.options;
		// remove unneeded items that cause recursion
		// delete content.options;
		delete content.instance;
		MUI.create(content);
	}

};

MUI.append({

	WindowPanelShared: {

		/// intercepts workflow from MUI.Content.update
		/// sets title and scroll bars of this window
		updateStart: function(options){
			if (!options.position) options.position = 'content';
			if (options.position == 'content'){
				options.element = this.el.content;
				this.addPadding(options);

				// set title if given option to do so
				if (options.title && this.el && this.el.title){
					this.options.title = options.title;
					this.el.title.set('html', options.title);
				}

				// Set scrollbars if loading content in main content container.
				// Always use 'hidden' for iframe windows
				this.el.contentWrapper.setStyles({
					'overflow': this.options.scrollbars && options.loadMethod != 'iframe' ? 'auto' : 'hidden'
				});
			}
			return false;  // not used but expected
		},

		/// intercepts workflow from MUI.Content.update
		updateClear: function(options){
			if (options.position == 'content'){
				this.el.content.show().empty();
				var iframes = this.el.contentWrapper.getElements('.mochaIframe');
				if (iframes) iframes.destroy();

				// Panels are not loaded into the padding div, so we remove them separately.
				this.el.content.getAllNext('.column').destroy();
				this.el.content.getAllNext('.columnHandle').destroy();

				return false;
			}
			return true;
		},

		/// intercepts workflow from MUI.Content.update
		updateSetContent: function(options){
			if (options.position == 'content'){
				if (options.loadMethod == 'html') this.el.content.addClass('pad');
				if (options.loadMethod == 'iframe'){
					this.el.content.removeClass('pad');
					this.el.content.setStyle('padding', '0px');
					this.el.content.hide();
					options.element = this.el.contentWrapper;
				}
			}
			return true;	// tells MUI.Content.update to update the content
		},

		addPadding: function(options){
			if (!options) options = Object.clone(this.options);

			if (options.padding == null) options.padding = this.options.padding;
			if (options.padding || options.padding == 0){
				// copy padding from main options if not passed in
				if (typeOf(options.padding) != 'number')
					Object.append(options.padding, this.options.padding);
				if (typeOf(options.padding) == 'number')
					options.padding = {top: options.padding, left: options.padding, right: options.padding, bottom: options.padding};

				// update padding if requested
				this.el.content.setStyles({
					'padding-top': options.padding.top,
					'padding-bottom': options.padding.bottom,
					'padding-left': options.padding.left,
					'padding-right': options.padding.right
				});
			}
			return this;
		},

		removePadding: function(){
			this.el.content.setStyle('padding', 0);
			return this;
		},

		empty: function(){
			this.el.content.empty();
			return this;
		},

		getSection: function(section){
			var retval;
			this.sections.each(function(s){
				if (s.section == section) retval = s;
			});
			return retval;
		}
	}
});

MUI.Content.PagingOptions = {
	// main paging options
	pageSize:		0,			// if >0 then paging is turned on
	page:			0,			// the page index offset (index*size)+1 = first record, (index*size)+size = last record
	pageMax:		0,			// the last page in the that (largest value of index).

	// informational values, set by return results, if they are change after contents are returned, they can be used to change what the pager is displaying
	total:			0,			// starts out as zero until filled in when data is received
	first:			1,			// first record showing in current page
	last:			10,			// last record showing in current page

	// additional options
	sort:			'',			// fields to search by, comma separated list of fields or array of strings.  Will be passed to server end-point.
	dir:			'asc',		// 'asc' ascending, 'desc' descending
	recordsField:	'data',		// 'element' in the json hash that contains the data
	totalField:		'total',	// 'element' in the json hash that contains the total records in the overall set
	pageField:		'page',		// 'element' in the json hash that contains the maximum pages that can be selected
	pageMaxField:	'pageMax',	// 'element' in the json hash that contains the maximum pages that can be selected
	pageSizeField:	'pageSize',	// 'element' in the json hash that contains the size of the page
	firstField:		'first',	// 'element' in the json hash that contains the size of the page
	lastField:		'last',		// 'element' in the json hash that contains the maximum pages that can be selected
	lookAhead:		0,			// # of pages to request in the background and cache
	wrap:			false,		// true if you want paging to wrap when user hits next page and they are at the last page, or from the first to the last page

	pageOptions:	[10, 20, 50, 100, 200]	// per page options available to user
};

/*
 ---

 script: themes.js

 description: MUI - Allows for switching themes dynamically.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 note:
 This documentation is taken directly from the javascript source files. It is built using Natural Docs.

 syntax:
 (start code)
 new MUI.Themes.init(newTheme);
 (end)

 example:
 (start code)
 new MUI.Themes.init('charcoal');
 (end)

 arguments:
 newTheme - (string) The theme name

 requires:
 - Core/Element
 - Core/Class
 - Core/Options
 - Core/Events
 - MUI
 - MUI.Core

 provides: [MUI.Themes]
 ...
 */

MUI.files['{source}Core/themes.js'] = 'loaded';

MUI.Themes = {

	init: function(newTheme){
		this.newTheme = newTheme.toLowerCase();
		if (!this.newTheme || this.newTheme == null || this.newTheme == MUI.options.theme.toLowerCase()) return false;

		if ($('spinner')) $('spinner').show();

		this.oldURIs = [];
		this.oldSheets = [];
		var themesPath = MUI.replacePaths(MUI.options.path.themes);

		$$('link').each(function(link){
			var href = link.get('href');
			if (href.contains(themesPath + MUI.options.theme)){
				this.oldURIs.push(href);
				this.oldSheets.push(link);
			}
		}.bind(this));

		Object.each(MUI.files, function(value, key){
			if (key.contains(themesPath + MUI.options.theme)){
				this.oldURIs.push(key);
			}
		}.bind(this));

		this.newSheetURLs = this.oldURIs.map(function(item){
			return item.replace('/' + MUI.options.theme + '/', '/' + MUI.Themes.newTheme + '/');
		}.bind(this));

		this.sheetsToLoad = this.oldURIs.length;
		this.sheetsLoaded = 0;

		// Download new stylesheets and add them to an array
		this.newSheets = [];
		this.newSheetURLs.each(function(link){
			var href = link;
			var cssRequest = new Request({
				method: 'get',
				url: href,
				onComplete: function(){
					var newSheet = new Element('link', {
						'rel': 'stylesheet',
						'media': 'screen',
						'type': 'text/css',
						'href': href
					});
					this.newSheets.push(newSheet);
				}.bind(this),
				onFailure: function(){
					this.themeLoadSuccess = false;
					if ($('spinner')) $('spinner').hide();
					MUI.notification('Stylesheets did not load.');
				},
				onSuccess: function(){
					this.sheetsLoaded++;
					if (this.sheetsLoaded == this.sheetsToLoad){
						this.updateThemeStylesheets();
						this.themeLoadSuccess = true;
					}
				}.bind(this)
			});
			cssRequest.send();

		}.bind(this));

		return true;
	},

	updateThemeStylesheets: function(){

		this.oldSheets.each(function(sheet){
			sheet.destroy();
		});

		this.newSheets.each(function(sheet){
			MUI.files[sheet.get('href')] = 1;
			sheet.inject(document.head);
		});

		// Delay gives the stylesheets time to take effect. IE6 needs more delay.
		if (Browser.ie){
			this.redraw.delay(1250, this);
		} else {
			this.redraw.delay(250, this);
		}

	},

	redraw: function(){

		$$('.replaced').removeClass('replaced');

		// Redraw open windows
		$$('.mocha').each(function(element){
			var instance = element.retrieve('instance');

			// Convert CSS colors to Canvas colors.
			instance._setColors();
			instance.redraw();
		});

		if (MUI.taskbar) MUI.taskbar.setTaskbarColors();

		// Reformat layout
		if (MUI.Desktop && MUI.Desktop.desktop){
			var checker = (function(){
				// Make sure the style sheets are really ready.
				if (MUI.Desktop.desktop.getStyle('overflow') != 'hidden'){
					return;
				}
				clearInterval(checker);
				MUI.Desktop.setDesktopSize();
			}).periodical(50);
		}

		if ($('spinner')) $('spinner').hide();
		MUI.options.theme = this.newTheme;

		/*
		 this.cookie = new Hash.Cookie('mochaUIthemeCookie', {duration: 3600});
		 this.cookie.empty();
		 this.cookie.set('theme', MUI.options.theme);
		 this.cookie.save();
		 */

	}

};

window.addEvent('load', function(){
	/*
	 // Load theme the user was last using. This needs work.
	 var cookie = new Hash.Cookie('mochaUIthemeCookie', {duration: 3600});
	 var themeCookie = cookie.load();
	 if (cookie.getKeys().length){
	 if (themeCookie.get('theme') != MUI.Themes.options.theme){
	 MUI.Themes.init.delay(1000, MUI.Themes, themeCookie.get('theme'));
	 }
	 }
	 */

	if ($('themeControl')){
		$('themeControl').getElements('option').setProperty('selected', 'false');
		if ($('chooseTheme')) $('chooseTheme').setProperty('selected', 'true');
	}
});


/*
 ---

 name: Desktop

 script: desktop.js

 description: MUI - Creates main desktop control that loads rest of desktop.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires:
 - MochaUI/MUI
 - MUI.Column
 - MUI.Panel

 provides: [MUI.Desktop]

 ...
 */

MUI.files['{controls}desktop/desktop.js'] = 'loaded';

MUI.Desktop = {

	options: {
		// Naming options:
		// If you change the IDs of the MochaUI Desktop containers in your HTML, you need to change them here as well.
		desktop:			'desktop',
		desktopHeader:		'desktopHeader',
		desktopFooter:		'desktopFooter',
		desktopNavBar:		'desktopNavbar',
		pageWrapper:		'pageWrapper',
		page:				'page',
		desktopFooterWrapper:'desktopFooterWrapper',

		createTaskbar:			true,
		taskbarOptions:		{}
	},

	initialize: function(options){
		if (options) Object.append(MUI.Desktop.options, options);

		if (MUI.desktop) return;	// only one desktop allowed
		MUI.desktop = this;

		this.desktop = $(this.options.desktop);
		this.desktopHeader = $(this.options.desktopHeader);
		this.desktopNavBar = $(this.options.desktopNavBar);
		this.pageWrapper = $(this.options.pageWrapper);
		this.page = $(this.options.page);
		this.desktopFooter = $(this.options.desktopFooter);

		if (!this.options.taskbarOptions.container) this.options.taskbarOptions.container = this.desktop;
		if (this.options.createTaskbar) this.taskbar = new MUI.Taskbar(this.options.taskbarOptions);
		if (!this.taskbar) this.setDesktopSize();  // This is run on taskbar initialize so no need to do it twice.
		this._menuInitialize();

		// Resize desktop, page wrapper, modal overlay, and maximized windows when browser window is resized
		window.addEvent('resize', function(){
			this._onBrowserResize();
		}.bind(this));

	},

	setDesktopSize: function(){
		var windowDimensions = window.getCoordinates();

		// Setting the desktop height may only be needed by IE7
		if (this.desktop) this.desktop.setStyle('height', windowDimensions.height);

		// Set pageWrapper height so the taskbar doesn't cover the pageWrapper scrollbars.
		if (this.pageWrapper){
			var taskbarOffset = this.taskbar ? this.taskbar.getHeight() : 0;
			var pageWrapperHeight = windowDimensions.height;
			pageWrapperHeight -= this.pageWrapper.getStyle('border-top').toInt();
			pageWrapperHeight -= this.pageWrapper.getStyle('border-bottom').toInt();
			if (this.desktopHeader) pageWrapperHeight -= this.desktopHeader.offsetHeight;
			if (this.desktopFooter) pageWrapperHeight -= this.desktopFooter.offsetHeight;
			pageWrapperHeight -= taskbarOffset;
			if (pageWrapperHeight < 0) pageWrapperHeight = 0;
			this.pageWrapper.setStyle('height', pageWrapperHeight);
		}

		///*		if (MUI.Columns.instances.getKeys().length > 0){ // Conditional is a fix for a bug in IE6 in the no toolbars demo.
		MUI.Desktop.resizePanels();
		//}*/
	},

	resizePanels: function(){
		MUI.panelHeight(null, null, 'all');
		MUI.rWidth();
	},

	saveWorkspace: function(){
		this.cookie = new Hash.Cookie('mochaUIworkspaceCookie', {duration: 3600});
		this.cookie.empty();

		MUI.each(function(instance){
			if (instance.className != 'MUI.Window') return;
			instance._saveValues();
			this.cookie.set(instance.options.id, {
				'id': instance.options.id,
				'top': instance.options.y,
				'left': instance.options.x,
				'width': instance.el.contentWrapper.getStyle('width').toInt(),
				'height': instance.el.contentWrapper.getStyle('height').toInt()
			});
		}.bind(this));
		this.cookie.save();

		new MUI.Window({
			loadMethod: 'html',
			type: 'notification',
			addClass: 'notification',
			content: 'Workspace saved.',
			closeAfter: '1400',
			width: 200,
			height: 40,
			y: 53,
			padding: {top: 10, right: 12, bottom: 10, left: 12},
			shadowBlur: 5
		});
	},

	loadingCallChain: function(){
		if ($$('.mocha').length == 0 && this.myChain){
			this.myChain.callChain();
		}
	},

	loadWorkspace: function(){
		var cookie = new Hash.Cookie('mochaUIworkspaceCookie', {duration: 3600});
		var workspaceWindows = cookie.load();

		if (!cookie.getKeys().length){
			new MUI.Window({
				loadMethod: 'html',
				type: 'notification',
				addClass: 'notification',
				content: 'You have no saved workspace.',
				closeAfter: '1400',
				width: 220,
				height: 40,
				y: 25,
				padding: {top: 10, right: 12, bottom: 10, left: 12},
				shadowBlur: 5
			});
			return;
		}

		var doLoadWorkspace = (function(workspaceWindows){
			workspaceWindows.each(function(workspaceWindow){
				windowFunction = MUI[workspaceWindow.id + 'Window'];
				if (windowFunction) windowFunction();
				// currently disabled positioning of windows, that would need to be passed to the MUI.Window call
				/*if (windowFunction){
				 windowFunction({
				 width: workspaceWindow.width,
				 height: workspaceWindow.height
				 });
				 var windowEl = $(workspaceWindow.id);
				 windowEl.setStyles({
				 'top': workspaceWindow.top,
				 'left': workspaceWindow.left
				 });
				 var instance = windowEl.retrieve('instance');
				 instance.el.contentWrapper.setStyles({
				 'width': workspaceWindow.width,
				 'height': workspaceWindow.height
				 });
				 instance.redraw();
				 }*/
			}.bind(this));
			this.loadingWorkspace = false;
		}).bind(this);

		if ($$('.mocha').length != 0){
			this.loadingWorkspace = true;
			this.myChain = new Chain();
			this.myChain.chain(
					function(){
						$$('.mocha').each(function(el){
							el.close();
						});
						this.myChain.callChain();
					}.bind(this),
					doLoadWorkspace
					);
			this.myChain.callChain();
		} else doLoadWorkspace(workspaceWindows);
	},

	_menuInitialize: function(){
		// Fix for dropdown menus in IE6
		if (Browser.ie6 && this.desktopNavBar){
			this.desktopNavBar.getElements('li').each(function(element){
				element.addEvent('mouseenter', function(){
					this.addClass('ieHover');
				});
				element.addEvent('mouseleave', function(){
					this.removeClass('ieHover');
				});
			});
		}
	},

	_onBrowserResize: function(){
		this.setDesktopSize();

		// Resize maximized windows to fit new browser window size
		setTimeout(function(){
			MUI.each(function(instance){
				var options = instance.options;
				if (instance.className != 'MUI.Window') return;
				if (instance.isMaximized){

					// Hide iframe while resize for better performance
					if (instance.el.iframe) instance.el.iframe.setStyle('visibility', 'hidden');

					var resizeDimensions;
					if (options.container) resizeDimensions = $(options.container).getCoordinates();
					else resizeDimensions = document.getCoordinates();
					var shadowBlur = options.shadowBlur;
					var shadowOffset = options.shadowOffset;
					var newHeight = resizeDimensions.height - options.headerHeight - options.footerHeight;
					newHeight -= instance.el.contentBorder.getStyle('border-top').toInt();
					newHeight -= instance.el.contentBorder.getStyle('border-bottom').toInt();
					newHeight -= instance._getAllSectionsHeight();

					instance.resize({
						width: resizeDimensions.width,
						height: newHeight,
						top: resizeDimensions.top + shadowOffset.y - shadowBlur,
						left: resizeDimensions.left + shadowOffset.x - shadowBlur
					});

					instance.redraw();
					if (instance.el.iframe){
						instance.el.iframe.setStyles({
							'height': instance.el.contentWrapper.getStyle('height')
						});
						instance.el.iframe.setStyle('visibility', 'visible');
					}
				}
			}.bind(this));
		}.bind(this), 100);
	}

};

MUI.Windows = Object.append((MUI.Windows || {}), {

	arrangeCascade: function(){

		var viewportTopOffset = 30;    // Use a negative number if necessary to place first window where you want it
		var viewportLeftOffset = 20;
		var windowTopOffset = 50;    // Initial vertical spacing of each window
		var windowLeftOffset = 40;

		// See how much space we have to work with
		var coordinates = document.getCoordinates();

		var openWindows = 0;
		MUI.each(function(instance){
			if (instance.className != 'MUI.Window') return;
			if (!instance.isMinimized && instance.options.draggable) openWindows ++;
		});

		var topOffset = ((windowTopOffset * (openWindows + 1)) >= (coordinates.height - viewportTopOffset)) ?
				(coordinates.height - viewportTopOffset) / (openWindows + 1) : windowTopOffset;
		var leftOffset = ((windowLeftOffset * (openWindows + 1)) >= (coordinates.width - viewportLeftOffset - 20)) ?
				(coordinates.width - viewportLeftOffset - 20) / (openWindows + 1) : windowLeftOffset;

		var x = viewportLeftOffset;
		var y = viewportTopOffset;
		$$('.mocha').each(function(windowEl){
			var instance = windowEl.retrieve('instance');
			if (!instance.isMinimized && !instance.isMaximized && instance.options.draggable){
				instance.focus();
				x += leftOffset;
				y += topOffset;

				if (!MUI.options.advancedEffects){
					windowEl.setStyles({
						'top': y,
						'left': x
					});
				} else {
					var cascadeMorph = new Fx.Morph(windowEl, {
						'duration': 550
					});
					cascadeMorph.start({
						'top': y,
						'left': x
					});
				}
			}
		}.bind(this));
	},

	arrangeTile: function(){

		var viewportTopOffset = 30;    // Use a negative number if neccessary to place first window where you want it
		var viewportLeftOffset = 20;

		var x = 10;
		var y = 80;

		var windowsNum = 0;

		MUI.each(function(instance){
			if (instance.className != 'MUI.Window') return;
			if (!instance.isMinimized && !instance.isMaximized){
				windowsNum++;
			}
		});

		var cols = 3;
		var rows = Math.ceil(windowsNum / cols);

		var coordinates = document.getCoordinates();

		var col_width = ((coordinates.width - viewportLeftOffset) / cols);
		var col_height = ((coordinates.height - viewportTopOffset) / rows);

		var row = 0;
		var col = 0;

		MUI.each(function(instance){
			if (instance.className != 'MUI.Window') return;
			if (!instance.isMinimized && !instance.isMaximized && instance.options.draggable){

				var left = (x + (col * col_width));
				var top = (y + (row * col_height));

				instance.redraw();
				instance.focus();

				if (MUI.options.advancedEffects){
					var tileMorph = new Fx.Morph(instance.el.windowEl, {
						'duration': 550
					});
					tileMorph.start({
						'top': top,
						'left': left
					});
				} else {
					instance.el.windowEl.setStyles({
						'top': top,
						'left': left
					});
				}

				if (++col === cols){
					row++;
					col = 0;
				}
			}
		}.bind(this));
	}
});

MUI.Window = (MUI.Window || new NamedClass('MUI.Window', {}));
MUI.Window.implement({

	maximize: function(){
		if (this.isMinimized) this._restoreMinimized();

		var options = this.options;
		var windowDrag = this.windowDrag;
		var windowEl = this.el.windowEl;

		// If window no longer exists or is maximized, stop
		if (this.isMaximized) return this;
		if (this.isCollapsed) this.collapseToggle();
		this.isMaximized = true;

		// If window is restricted to a container, it should not be draggable when maximized.
		if (this.options.restrict){
			windowDrag.detach();
			if (options.resizable) this._detachResizable();
			this.el.titleBar.setStyle('cursor', 'default');
		}

		// If the window has a container that is not the desktop
		// temporarily move the window to the desktop while it is minimized.
		if (options.container != MUI.Desktop.desktop){
			MUI.Desktop.desktop.grab(windowEl);
			if (options.restrict) windowDrag.container = this.el.desktop;
		}

		// Save original position
		this.oldTop = windowEl.getStyle('top');
		this.oldLeft = windowEl.getStyle('left');

		// save original corner radius
		if (!options.radiusOnMaximize){
			this.oldRadius = options.cornerRadius;
			this.oldShadowBlur = options.shadowBlur;
			this.oldShadowOffset = options.shadowOffset;

			options.cornerRadius = 0;
			options.shadowBlur = 0;
			options.shadowOffset = {'x': 0, 'y': 0};
		}

		// Save original dimensions
		var contentWrapper = this.el.contentWrapper;
		contentWrapper.oldWidth = contentWrapper.getStyle('width');
		contentWrapper.oldHeight = contentWrapper.getStyle('height');

		// Hide iframe
		// Iframe should be hidden when minimizing, maximizing, and moving for performance and Flash issues
		if (this.el.iframe){
			if (!Browser.ie) this.el.iframe.setStyle('visibility', 'hidden');
			else this.el.iframe.hide();
		}

		var resizeDimensions;
		if (options.container) resizeDimensions = $(options.container).getCoordinates();
		else resizeDimensions = document.getCoordinates();
		var shadowBlur = options.shadowBlur;
		var shadowOffset = options.shadowOffset;
		var newHeight = resizeDimensions.height - options.headerHeight - options.footerHeight;
		newHeight -= this.el.contentBorder.getStyle('border-top').toInt();
		newHeight -= this.el.contentBorder.getStyle('border-bottom').toInt();
		newHeight -= this._getAllSectionsHeight();

		this.resize({
			width: resizeDimensions.width,
			height: newHeight,
			top: resizeDimensions.top + shadowOffset.y - shadowBlur,
			left: resizeDimensions.left + shadowOffset.x - shadowBlur
		});
		this.fireEvent('maximize', [this]);

		if (this.el.maximizeButton) this.el.maximizeButton.setProperty('title', 'Restore');
		this.focus();

		return this;
	},

	_restoreMaximized: function(){
		var options = this.options;

		// Window exists and is maximized ?
		if (!this.isMaximized) return this;

		this.isMaximized = false;

		if (!options.radiusOnMaximize){
			options.cornerRadius = this.oldRadius;
			options.shadowBlur = this.oldShadowBlur;
			options.shadowOffset = this.oldShadowOffset;
		}

		if (options.restrict){
			this.windowDrag.attach();
			if (options.resizable) this._reattachResizable();
			this.el.titleBar.setStyle('cursor', 'move');
		}

		// Hide iframe
		// Iframe should be hidden when minimizing, maximizing, and moving for performance and Flash issues
		if (this.el.iframe){
			if (!Browser.ie) this.el.iframe.setStyle('visibility', 'hidden');
			else this.el.iframe.hide();
		}

		var contentWrapper = this.el.contentWrapper;
		this.resize({
			width: contentWrapper.oldWidth,
			height: contentWrapper.oldHeight,
			top: this.oldTop,
			left: this.oldLeft
		});
		this.fireEvent('restore', [this]);

		if (this.el.maximizeButton) this.el.maximizeButton.setProperty('title', 'Maximize');
		return this;
	}

});

MUI.append({

	// Panel Height
	panelHeight: function(column, changing, action){
		if (column != null){
			MUI.panelHeight2($(column), changing, action);
		} else {
			$$('.column').each(function(column){
				MUI.panelHeight2(column, null, action);
			}.bind(this));
		}
	},

	panelHeight2: function(column, changing, action){
		var parent = column.getParent();
		var columnHeight = parent.getStyle('height').toInt();
		if (Browser.ie6 && parent == MUI.Desktop.pageWrapper){
			columnHeight -= 1;
		}
		column.setStyle('height', columnHeight);

		// Get column panels
		var panels = [];
		column.getChildren('.panelWrapper').each(function(panelWrapper){
			panels.push(panelWrapper.getElement('.panel'));
		}.bind(this));

		// Get expanded column panels
		var panelsExpanded = [];
		column.getChildren('.expanded').each(function(panelWrapper){
			panelsExpanded.push(panelWrapper.getElement('.panel'));
		}.bind(this));

		// makes sure at least one panel is expanded for the
		if (action == 'all' && panelsExpanded.length == 0 && panels.length > 0){
			MUI.get(panels[0]).expand();

			// if this is not the main column than we can collapse the column to get desired effect
			var columnInstance = MUI.get(column);
			if (columnInstance.options.position != 'main'){
				columnInstance.collapse();
			}
		}

		// All the panels in the column whose height will be effected.
		var panelsToResize = [];

		// The panel with the greatest height. Remainders will be added to this panel
		var tallestPanel;
		var tallestPanelHeight = 0;

		this.panelsTotalHeight = 0; // Height of all the panels in the column
		this.height = 0; // Height of all the elements in the column

		// Set panel resize partners
		panels.each(function(panel){
			var instance = MUI.get(panel.id);
			if (panel.getParent().hasClass('expanded') && panel.getParent().getNext('.expanded')){
				instance.partner = panel.getParent().getNext('.expanded').getElement('.panel');
				instance.resize.attach();
				instance.el.handle.setStyles({
					'display': 'block',
					'cursor': Browser.webkit ? 'row-resize' : 'n-resize'
				}).removeClass('detached');
			} else {
				instance.resize.detach();
				instance.el.handle.setStyles({
					'display': 'none',
					'cursor': null
				}).addClass('detached');
			}
			if (panel.getParent().getNext('.panelWrapper') == null){
				instance.el.handle.hide();
			}
		}.bind(this));

		// Add panels to panelsToResize
		// Get the total height of all the resizable panels
		// Get the total height of all the column's children
		column.getChildren().each(function(panelWrapper){

			panelWrapper.getChildren().each(function(el){

				if (el.hasClass('panel')){
					var instance = MUI.get(el.id);

					// Are any next siblings Expanded?
					var anyNextSiblingsExpanded = function(el){
						var test;
						el.getParent().getAllNext('.panelWrapper').each(function(sibling){
							var siblingInstance = MUI.get(sibling.getElement('.panel').id);
							if (!siblingInstance.isCollapsed){
								test = true;
							}
						}.bind(this));
						return test;
					}.bind(this);

					// If a next sibling is expanding, are any of the nexts siblings of the expanding sibling Expanded?
					var anyExpandingNextSiblingsExpanded = function(){
						var test;
						changing.getParent().getAllNext('.panelWrapper').each(function(sibling){
							var siblingInstance = MUI.get(sibling.getElement('.panel').id);
							if (!siblingInstance.isCollapsed){
								test = true;
							}
						}.bind(this));
						return test;
					}.bind(this);

					// Is the panel that is collapsing, expanding, or new located after this panel?
					var anyNextContainsChanging = function(el){
						var allNext = [];
						el.getParent().getAllNext('.panelWrapper').each(function(panelWrapper){
							allNext.push(panelWrapper.getElement('.panel'));
						}.bind(this));
						return allNext.contains(changing);
					}.bind(this);

					var nextExpandedChanging = function(el){
						var test;
						if (el.getParent().getNext('.expanded')){
							if (el.getParent().getNext('.expanded').getElement('.panel') == changing) test = true;
						}
						return test;
					};

					// NEW PANEL
					// Resize panels that are "new" or not collapsed
					if (action == 'new'){
						if (!instance.isCollapsed && el != changing){
							panelsToResize.push(el);
							this.panelsTotalHeight += el.offsetHeight.toInt();
						}
					}

					// COLLAPSING PANELS and CURRENTLY EXPANDED PANELS
					// Resize panels that are not collapsed.
					// If a panel is collapsing resize any expanded panels below.
					// If there are no expanded panels below it, resize the expanded panels above it.
					else if (action == null || action == 'collapsing'){
						if (!instance.isCollapsed && (!anyNextContainsChanging(el) || !anyNextSiblingsExpanded(el))){
							panelsToResize.push(el);
							this.panelsTotalHeight += el.offsetHeight.toInt();
						}
					}

					// EXPANDING PANEL
					// Resize panels that are not collapsed and are not expanding.
					// Resize any expanded panels below the expanding panel.
					// If there are no expanded panels below the expanding panel, resize the first expanded panel above it.
					else if (action == 'expanding' && !instance.isCollapsed && el != changing){
						if (!anyNextContainsChanging(el) || (!anyExpandingNextSiblingsExpanded(el) && nextExpandedChanging(el))){
							panelsToResize.push(el);
							this.panelsTotalHeight += el.offsetHeight.toInt();
						}
					}

					if (el.style.height){
						this.height += el.getStyle('height').toInt();
					}
				} else {
					this.height += el.offsetHeight.toInt();
				}
			}.bind(this));

			panelsToResize.each(function(panel){
				var MUIPanel = MUI.get(panel.id);
				if (action != 'new') MUIPanel.fireEvent('resize', [MUIPanel]);
			});

		}.bind(this));

		// Get the remaining height
		var remainingHeight = column.offsetHeight.toInt() - this.height;

		this.height = 0;

		// Get height of all the column's children
		column.getChildren().each(function(el){
			this.height += el.offsetHeight.toInt();
		}.bind(this));

		remainingHeight = column.offsetHeight.toInt() - this.height;

		panelsToResize.each(function(panel){
			var ratio = this.panelsTotalHeight / panel.offsetHeight.toInt();
			var newPanelHeight = panel.getStyle('height').toInt() + (remainingHeight / ratio);
			if (newPanelHeight < 1){
				newPanelHeight = 0;
			}
			panel.setStyle('height', newPanelHeight);
		}.bind(this));

		// Make sure the remaining height is 0. If not add/subtract the
		// remaining height to the tallest panel. This makes up for browser resizing,
		// off ratios, and users trying to give panels too much height.

		// Get height of all the column's children
		this.height = 0;
		column.getChildren().each(function(panelWrapper){
			panelWrapper.getChildren().each(function(el){
				this.height += el.offsetHeight.toInt();
				if (el.hasClass('panel') && el.getStyle('height').toInt() > tallestPanelHeight){
					tallestPanel = el;
					tallestPanelHeight = el.getStyle('height').toInt();
				}
			}.bind(this));
		}.bind(this));

		remainingHeight = column.offsetHeight.toInt() - this.height;

		if (remainingHeight != 0 && tallestPanelHeight > 0){
			tallestPanel.setStyle('height', tallestPanel.getStyle('height').toInt() + remainingHeight);
			if (tallestPanel.getStyle('height') < 1){
				tallestPanel.setStyle('height', 0);
			}
		}

		parent.getChildren('.columnHandle').each(function(handle){
			var parent = handle.getParent();
			if (parent.getStyle('height').toInt() < 1) return; // Keeps IE7 and 8 from throwing an error when collapsing a panel within a panel
			var handleHeight = parent.getStyle('height').toInt() - handle.getStyle('border-top').toInt() - handle.getStyle('border-bottom').toInt();
			if (Browser.ie6 && parent == MUI.Desktop.pageWrapper){
				handleHeight -= 1;
			}
			handle.setStyle('height', handleHeight);
		});

		panelsExpanded.each(function(panel){
			MUI.resizeChildren(panel);
		}.bind(this));
	},

	resizeChildren: function(panel){ // May rename this resizeIframeEl()
		var instance = MUI.get(panel.id);
		var contentWrapper = instance.el.contentWrapper;

		if (instance.el.iframe){
			if (!Browser.ie){
				instance.el.iframe.setStyles({
					'height': contentWrapper.getStyle('height'),
					'width': contentWrapper.offsetWidth - contentWrapper.getStyle('border-left').toInt() - contentWrapper.getStyle('border-right').toInt()
				});
			} else {
				// The following hack is to get IE8 RC1 IE8 Standards Mode to properly resize an iframe
				// when only the vertical dimension is changed.
				instance.el.iframe.setStyles({
					'height': contentWrapper.getStyle('height'),
					'width': contentWrapper.offsetWidth - contentWrapper.getStyle('border-left').toInt() - contentWrapper.getStyle('border-right').toInt() - 1
				});
				instance.el.iframe.setStyles({
					'width': contentWrapper.offsetWidth - contentWrapper.getStyle('border-left').toInt() - contentWrapper.getStyle('border-right').toInt()
				});
			}
		}
	},

	rWidth: function(container){ // Remaining Width
		if (container == null){
			container = MUI.Desktop.desktop;
		}
		container.getElements('.rWidth').each(function(column){
			var currentWidth = column.offsetWidth.toInt();
			currentWidth -= column.getStyle('border-left').toInt();
			currentWidth -= column.getStyle('border-right').toInt();

			var parent = column.getParent();
			this.width = 0;

			// Get the total width of all the parent element's children
			parent.getChildren().each(function(el){
				if (el.hasClass('mocha') != true){
					this.width += el.offsetWidth.toInt();
				}
			}.bind(this));

			// Add the remaining width to the current element
			var remainingWidth = parent.offsetWidth.toInt() - this.width;
			var newWidth = currentWidth + remainingWidth;
			if (newWidth < 1) newWidth = 0;
			column.setStyle('width', newWidth);

			// fire all panel resize events and the column resize event
			var instance = MUI.get(column.id);
			[instance].combine(instance.getPanels()).each(function(panel){
				panel.fireEvent('resize', [panel]);
			}, this);

			column.getElements('.panel').each(function(panel){
				MUI.resizeChildren(panel);
			}.bind(this));
		});
	}

});



/*
 ---

 script: Panel.js

 description: Panel is used to create a content area in a column

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires:
 - MochaUI/MUI
 - MUI.Desktop
 - MUI.Column

 provides: [MUI.Panel]

 ...
 */

MUI.files['{controls}panel/panel.js'] = 'loaded';

MUI.Panel = new NamedClass('MUI.Panel', {

	Implements: [Events, Options],

	options: {
		id:						null,			// id of the main div tag for the panel
		container:				null,			// the name of the column to insert this panel into
		drawOnInit:				true,			// true to inject panel into DOM when panel is first created

		// content section update options
		content:				false,			// used to update the content section of the panel.
		// if it is a string it assumes that the content is html and it will be injected into the content div.
		// if it is an array then assume we need to update multiple sections of the panel
		// if it is not a string or array it assumes that is a hash and just the content section will have .

		// header
		header:					true,			// true to create a panel header when panel is created
		title:					false,			// the title inserted into the panel's header

		// footer
		footer:					false,			// true to create a panel footer when panel is created

		// Style options:
		height:					125,			// the desired height of the panel
		cssClass:				'',				// css class to add to the main panel div
		scrollbars:				true,			// true to allow scrollbars to be shown
		padding:				8,				// default padding for the panel

		// Other:
		collapsible:			true,			// can the panel be collapsed
		isCollapsed:			 false

		// Events
		//onLoaded:				null, // called every time content is loaded using MUI.Content
		//onDrawBegin:			null,
		//onDrawEnd:			null,
		//onResize:				null,
		//onCollapse:			null,
		//onExpand:				null
	},

	initialize: function(options){
		this.setOptions(options);

		Object.append(this, {
			oldHeight: 0,
			partner: null,
			el: {}
		});

		// If panel has no ID, give it one.
		this.id = this.options.id = this.options.id || 'panel' + (++MUI.idCount);
		MUI.set(this.id, this);

		if (this.options.drawOnInit) this.draw();
	},

	draw: function(container){
		var options = this.options;
		if (!container) container = options.container;
		var parent = MUI.get(options.container);
		if (!container) container = parent.el.element;
		if (typeOf(container) == 'string') container = $(container);
		if (typeOf(container) != 'element') return;

		// Check if panel already exists
		if (this.el.panel) return this;

		this.fireEvent('drawBegin', [this]);
		this.showHandle = container.getChildren().length != 0;

		var div = options.element ? options.element : $(options.id + '_wrapper');
		if (!div) div = new Element('div', {'id': options.id + '_wrapper'}).inject(container);
		div.empty().addClass('panelWrapper expanded');
		this.el.element = div;

		this.el.panel = new Element('div', {
			'id': options.id,
			'class': 'panel',
			'styles': {
				'height': options.height
			}
		}).inject(div)
				.addClass(options.cssClass)
				.store('instance', this);

		this.el.content = new Element('div', {
			'id': options.id + '_pad',
			'class': 'pad'
		}).inject(this.el.panel);

		// make sure we have a content sections
		this.sections = [];

		switch (typeOf(options.content)){
			case 'string':
				// was passed html, so make sure it is added
				this.sections.push({
					loadMethod: 'html',
					content: options.content
				});
				break;
			case 'array':
				this.sections = options.content;
				break;
			default:
				this.sections.push(options.content);
		}

		// This is in order to use the same variable as the windows do in MUI.Content.update.
		this.el.contentWrapper = this.el.panel;

		var headerItems = [];
		var footerItems = [];
		var snum = 0;
		this.sections.each(function(section){
			if (!section.position || section.position == 'content'){
				if (section.loadMethod == 'iframe') section.padding = 0;  // Iframes have their own padding.
				section.container = this.el.content;
				return;
			}
			var id = options.id + '_' + (section.name || 'section' + (snum++));
			if (!section.control) section.control = 'MUI.DockHtml';
			if (!section.id) section.id = id;
			section.partner = this.id;
			if (section.position == 'header') headerItems.unshift(section);
			if (section.position == 'footer') footerItems.unshift(section);
		}, this);

		if (options.header){
			this.el.header = new Element('div', {
				'id': options.id + '_header',
				'class': 'panel-header',
				'styles': { 'display': options.header ? 'block' : 'none' }
			}).inject(this.el.panel, 'before');

			if (options.collapsible){
				this._collapseToggleInit();
				headerItems.unshift({content:this.el.collapseToggle, divider:false});
			}

			if (options.title){
				this.el.title = new Element('h2', {
					'id': options.id + '_title',
					'html': options.title
				});
				headerItems.push({id:options.id + 'headerContent',content:this.el.title,orientation:'left', divider:false});
			}

			MUI.create({
				control: 'MUI.Dock',
				container: this.el.panel,
				element: this.el.header,
				id: options.id + '_header',
				cssClass: 'panel-header',
				docked:headerItems
			});
		}

		if (options.footer){
			this.el.footer = new Element('div', {
				'id': options.id + '_footer',
				'class': 'panel-footer',
				'styles': { 'display': options.footer ? 'block' : 'none' }
			}).inject(this.el.panel, 'after');

			MUI.create({
				control: 'MUI.Dock',
				container: this.el.element,
				id: options.id + '_footer',
				cssClass: 'panel-footer',
				docked: footerItems
			});
		}

		if (parent && parent.options.sortable){
			parent.options.container.retrieve('sortables').addItems(this.el.element);
			if (this.el.header){
				this.el.header.setStyle('cursor', 'move');
				this.el.header.addEvent('mousedown', function(e){
					e = e.stop();
					e.target.focus();
				});
				this.el.header.setStyle('cursor', 'default');
			}
		}

		this.el.handle = new Element('div', {
			'id': options.id + '_handle',
			'class': 'horizontalHandle',
			'styles': {
				'display': this.showHandle ? 'block' : 'none'
			}
		}).inject(this.el.element);

		this.el.handleIcon = new Element('div', {
			'id': options.id + '_handle_icon',
			'class': 'handleIcon'
		}).inject(this.el.handle);

		this._addResizeBottom();

		// load/build all of the additional  content sections
		this.sections.each(function(section){
			if (section.position == 'header' || section.position == 'footer') return;
			if (section.onLoaded) section.onLoaded = section.onLoaded.bind(this);
			if (!section.instance) section.instance = this;

			MUI.Content.update(section);
		}, this);

		// Do this when creating and removing panels
		if (!container) return;
		container.getChildren('.panelWrapper').removeClass('bottomPanel').getLast().addClass('bottomPanel');
		MUI.panelHeight(container, this.el.panel, 'new');

		Object.each(this.el, (function(ele){
			if (ele != this.el.headerToolbox) ele.store('instance', this);
		}).bind(this));

		if (options.isCollapsed) this._collapse();

		this.fireEvent('drawEnd', [this]);

		return this;
	},

	close: function(){
		var container = this.options.container;
		this.isClosing = true;

		var parent = MUI.get(container);
		if (parent.options.sortable)
			parent.options.container.retrieve('sortables').removeItems(this.el.element);

		this.el.element.destroy();

		if (MUI.Desktop) MUI.Desktop.resizePanels();

		// Do this when creating and removing panels
		var panels = $(container).getElements('.panelWrapper');
		panels.removeClass('bottomPanel');
		if (panels.length > 0) panels.getLast().addClass('bottomPanel');

		MUI.erase(this.options.id);
		this.fireEvent('close', [this]);
		return this;
	},

	collapse: function(){
		var panelWrapper = this.el.element;
		var options = this.options;

		// Get siblings and make sure they are not all collapsed.
		// If they are all collapsed and the current panel is collapsing
		// Then collapse the column.
		var expandedSiblings = [];

		panelWrapper.getAllPrevious('.panelWrapper').each(function(sibling){
			var panel = sibling.getElement('.panel');
			if (!panel) return;
			if (!MUI.get(panel.id).isCollapsed) expandedSiblings.push(panel.id);
		});

		panelWrapper.getAllNext('.panelWrapper').each(function(sibling){
			if (!MUI.get(sibling.getElement('.panel').id).isCollapsed)
				expandedSiblings.push(sibling.getElement('.panel').id);
		});

		var parent = MUI.get($(options.container));
		if (parent.isTypeOf('MUI.Column')){
			if (expandedSiblings.length == 0 && parent.options.placement != 'main'){
				parent.toggle();
				return;
			} else if (expandedSiblings.length == 0 && parent.options.placement == 'main'){
				return;
			}
		}

		this._collapse(true);

		return this;
	},

	_collapse: function(fireevent){
		var panelWrapper = this.el.element;
		var options = this.options;

		// Collapse Panel
		var panel = this.el.panel;
		this.oldHeight = panel.getStyle('height').toInt();
		if (this.oldHeight < 10) this.oldHeight = 20;
		this.el.content.setStyle('position', 'absolute'); // This is so IE6 and IE7 will collapse the panel all the way
		panel.setStyle('height', 0);
		this.isCollapsed = true;
		panelWrapper.addClass('collapsed')
				.removeClass('expanded');
		MUI.panelHeight(options.container, panel, 'collapsing');
		MUI.panelHeight(); // Run this a second time for panels within panels
		this.el.collapseToggle.removeClass('panel-collapsed')
				.addClass('panel-expand')
				.setProperty('title', 'Expand Panel');
		if (fireevent) this.fireEvent('collapse', [this]);

		return this;
	},

	expand: function(){

		// Expand Panel
		this.el.content.setStyle('position', null); // This is so IE6 and IE7 will collapse the panel all the way
		this.el.panel.setStyle('height', this.oldHeight);
		this.isCollapsed = false;
		this.el.element.addClass('expanded')
				.removeClass('collapsed');
		MUI.panelHeight(this.options.container, this.el.panel, 'expanding');
		MUI.panelHeight(); // Run this a second time for panels within panels
		this.el.collapseToggle.removeClass('panel-expand')
				.addClass('panel-collapsed')
				.setProperty('title', 'Collapse Panel');
		this.fireEvent('expand', [this]);

		return this;
	},

	toggle: function(){
		if (this.isCollapsed) this.expand();
		else this.collapse();
		return this;
	},

	_collapseToggleInit: function(){
		this.el.collapseToggle = new Element('div', {
			'id': this.options.id + '_collapseToggle',
			'class': 'panel-collapse icon16',
			'styles': {
				'width': 16,
				'height': 16
			},
			'title': 'Collapse Panel'
		}).addEvent('click', function(){
			this.toggle();
		}.bind(this));
	},

	_addResizeBottom: function(){
		var instance = this;
		var element = this.el.panel;

		var handle = this.el.handle;
		handle.setStyle('cursor', Browser.webkit ? 'row-resize' : 'n-resize');
		var partner = this.partner;
		var min = 0;
		var max = function(){
			return element.getStyle('height').toInt() + partner.getStyle('height').toInt();
		}.bind(this);

		if (Browser.ie){
			handle.addEvents({
				'mousedown': function(){
					handle.setCapture();
				},
				'mouseup': function(){
					handle.releaseCapture();
				}
			});
		}
		this.resize = element.makeResizable({
			handle: handle,
			modifiers: {x: false, y: 'height'},
			limit: {y: [min, max]},
			invert: false,

			onBeforeStart: function(){
				partner = instance.partner;
				this.originalHeight = element.getStyle('height').toInt();
				this.partnerOriginalHeight = partner.getStyle('height').toInt();
			}.bind(this),

			onStart: function(){
				if (instance.el.iframe){
					if (Browser.ie){
						instance.el.iframe.hide();
						partner.getElements('iframe').hide();
					} else {
						instance.el.iframe.setStyle('visibility', 'hidden');
						partner.getElements('iframe').setStyle('visibility', 'hidden');
					}
				}
			}.bind(this),

			onDrag: function(){
				var partnerHeight = this.partnerOriginalHeight;
				partnerHeight += (this.originalHeight - element.getStyle('height').toInt());
				partner.setStyle('height', partnerHeight);
				MUI.resizeChildren(element, element.getStyle('height').toInt());
				MUI.resizeChildren(partner, partnerHeight);
				element.getChildren('.column').each(function(column){
					MUI.panelHeight(column);
				});
				partner.getChildren('.column').each(function(column){
					MUI.panelHeight(column);
				});
			}.bind(this),

			onComplete: function(){
				var partnerHeight = this.partnerOriginalHeight;
				partnerHeight += (this.originalHeight - element.getStyle('height').toInt());
				partner.setStyle('height', partnerHeight);
				MUI.resizeChildren(element, element.getStyle('height').toInt());
				MUI.resizeChildren(partner, partnerHeight);
				element.getChildren('.column').each(function(column){
					MUI.panelHeight(column);
				});
				partner.getChildren('.column').each(function(column){
					MUI.panelHeight(column);
				});
				if (instance.el.iframe){
					if (Browser.ie){
						instance.el.iframe.show();
						partner.getElements('iframe').show();
						// The following hack is to get IE8 Standards Mode to properly resize an iframe
						// when only the vertical dimension is changed.
						var width = instance.el.iframe.getStyle('width').toInt();
						instance.el.iframe.setStyle('width', width - 1);
						MUI.rWidth();
						instance.el.iframe.setStyle('width', width);
					} else {
						instance.el.iframe.setStyle('visibility', 'visible');
						partner.getElements('iframe').setStyle('visibility', 'visible');
					}
				}

				instance.fireEvent('resize', [this]);
				MUI.get(partner).fireEvent('resize', [this]);
			}.bind(this)
		});
	}

}).implement(MUI.WindowPanelShared);


/*
 ---

 script: Column.js

 description: Column control for horizontal layouts.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires:
 - MochaUI/MUI
 - MUI.Desktop
 - MUI.Panel

 provides: [MUI.Column]

 ...
 */

MUI.files['{controls}column/column.js'] = 'loaded';

MUI.Column = new NamedClass('MUI.Column', {

	Implements: [Events, Options],

	options: {
		id:				null,
		container:		null,
		drawOnInit:		true,

		placement:		null,
		width:			null,
		resizeLimit:	[],
		sortable:		true,
		isCollapsed:	false,

		panels:			[]

		//onDrawBegin:	null,
		//onDrawEnd:	null,
		//onResize:		null,
		//onCollapse:	null,
		//onExpand:		null
	},

	initialize: function(options){
		this.setOptions(options);

		Object.append(this, {
			isCollapsed: false,
			oldWidth: 0,
			el: {}
		});

		// If column has no ID, give it one.
		this.id = this.options.id = this.options.id || 'column' + (++MUI.idCount);
		MUI.set(this.id, this);

		if (this.options.drawOnInit) this.draw();
	},

	draw: function(){
		// todo: need to domready adding to container
		// todo: need to except external container
 
		var options = this.options;

		this.fireEvent('drawBegin', [this]);

		if (options.container == null) options.container = MUI.desktop.el.content;
		else $(options.container).setStyle('overflow', 'hidden');
		if (typeOf(options.container) == 'string') options.container = $(options.container);

		// Check if column already exists
		if (this.el.column) return this;
		else MUI.set(options.id, this);

		var parentInstance = MUI.get(options.container);
		if(parentInstance && (parentInstance.isTypeOf('MUI.Panel') || parentInstance.isTypeOf('MUI.Window'))) {
			// If loading columns into a panel, hide the regular content container.
			if (parentInstance.el.element.getElement('.pad') != null) parentInstance.el.element.getElement('.pad').hide();

			// If loading columns into a window, hide the regular content container.
			if (parentInstance.el.element.getElement('.mochaContent') != null)  parentInstance.el.element.getElement('.mochaContent').hide();
		}

		// make or use existing element
		if (options.element) this.el.column = options.element;
		else if ($(options.id)) this.el.column = $(options.id);
		else this.el.column = new Element('div', {'id': options.id}).inject($(options.container));
		this.el.element = this.el.column;

		// parent container's height
		var parent = this.el.column.getParent();
		var columnHeight = parent.getStyle('height').toInt();

		// format column element correctly
		this.el.column.addClass('column expanded')
				.setStyle('width', options.placement == 'main' ? null : options.width)
				.store('instance', this)
				.setStyle('height', columnHeight);

		if (options.sortable){
			if (!options.container.retrieve('sortables')){
				var sortables = new Sortables(this.el.column, {
					opacity: 0.2,
					handle: '.panel-header',
					constrain: false,
					clone: true,
					revert: { duration: 500, transition: 'quad:in'},
					onStart: function(element, clone){
						var pos = element.getPosition(document.body);
						clone.inject(document.body).setStyles({
							'z-index': 1999,
							'opacity': 0.65,
							'margin-left': pos.x,
							'margin-top': pos.y - clone.getStyle('top').toInt()
						});
					},
					onSort: function(){
						$$('.column').each(function(column){
							column.getChildren('.panelWrapper').removeClass('bottomPanel');
							if (column.getChildren('.panelWrapper').getLast()){
								column.getChildren('.panelWrapper').getLast().addClass('bottomPanel');
							}
							column.getChildren('.panelWrapper').each(function(panelWrapper){
								var panel = panelWrapper.getElement('.panel');
								var column = panelWrapper.getParent().id;
								var instance = MUI.get(panel.id);
								if (instance){
									instance.options.column = column;
									var nextPanel = panel.getParent().getNext('.expanded');
									if (nextPanel){
										nextPanel = nextPanel.getElement('.panel');
									}
									instance.partner = nextPanel;
								}
							});
							MUI.panelHeight();
						}.bind(this));
					}.bind(this)
				});
				options.container.store('sortables', sortables);
			} else {
				options.container.retrieve('sortables').addLists(this.el.column);
			}
		}
		if (options.placement == 'main') this.el.column.addClass('rWidth');

		switch (options.placement){
			case 'left':
				this.el.handle = new Element('div', {
					'id': options.id + '_handle',
					'class': 'columnHandle'
				}).inject(this.el.column, 'after');

				this.el.handleIcon = new Element('div', {
					'id': options.id + '_handle_icon',
					'class': 'handleIcon'
				}).inject(this.el.handle);

				this._addResize(this.el.column, options.resizeLimit[0], options.resizeLimit[1], 'right');
				break;
			case 'right':
				this.el.handle = new Element('div', {
					'id': options.id + '_handle',
					'class': 'columnHandle'
				}).inject(this.el.column, 'before');

				this.el.handleIcon = new Element('div', {
					'id': options.id + '_handle_icon',
					'class': 'handleIcon'
				}).inject(this.el.handle);
				this._addResize(this.el.column, options.resizeLimit[0], options.resizeLimit[1], 'left');
				break;
		}

		if (options.isCollapsed && this.options.placement != 'main') this.toggle();

		if (this.el.handle != null){
			this.el.handle.addEvent('dblclick', function(){
				this.toggle();
			}.bind(this));
		}

		MUI.rWidth();

		if (options.panels){
			for(var i=0;i<options.panels.length;i++) {
				var panel=options.panels[i];

				if (!panel.id) panel.id = options.id + 'Panel' + i;
				panel.container = this.el.column;
				panel.column = options.id;
				panel.element = new Element('div', {'id':panel.id+'_wrapper'}).inject(this.el.column);
				panel.control = 'MUI.Panel';
				MUI.create(panel);
			}
		}

		//this.fireEvent('drawEnd', [this]);
		return this;
	},

	getPanels: function(){
		var panels = [];
		$(this.el.column).getElements('.panel').each(function(panelEl){
			var panel = MUI.get(panelEl.id);
			if (panel) panels.push(panel);
		});
		return panels;
	},

	collapse: function(){
		var column = this.el.column;

		this.oldWidth = column.getStyle('width').toInt();

		this.resize.detach();
		this.el.handle.removeEvents('dblclick');
		this.el.handle.addEvent('click', function(){
			this.expand();
		}.bind(this));
		this.el.handle.setStyle('cursor', 'pointer').addClass('detached');

		column.setStyle('width', 0);
		this.isCollapsed = true;
		column.addClass('collapsed');
		column.removeClass('expanded');
		MUI.rWidth();
		this.fireEvent('collapse', [this]);

		return this;
	},

	expand : function(){
		var column = this.el.column;

		column.setStyle('width', this.oldWidth);
		this.isCollapsed = false;
		column.addClass('expanded');
		column.removeClass('collapsed');

		this.el.handle.removeEvents('click');
		this.el.handle.addEvent('dblclick', function(){
			this.collapse();
		}.bind(this));
		this.resize.attach();
		this.el.handle.setStyle('cursor', Browser.webkit ? 'col-resize' : 'e-resize').addClass('attached');

		MUI.rWidth();
		this.fireEvent('expand', [this]);

		return this;
	},

	toggle: function(){
		if (!this.isCollapsed) this.collapse();
		else this.expand();
		return this;
	},

	close: function(){
		var self = this;
		self.isClosing = true;

		// Destroy all the panels in the column.
		var panels = self.getPanels();
		panels.each(function(panel){
			panel.close();
		}.bind(this));

		if (Browser.ie){
			self.el.column.dispose();
			if (self.el.handle != null) self.el.handle.dispose();
		} else {
			self.el.column.destroy();
			if (self.el.handle != null) self.el.handle.destroy();
		}

		if (MUI.Desktop) MUI.Desktop.resizePanels();

		var sortables = self.options.container.retrieve('sortables');
		if (sortables) sortables.removeLists(this.el.column);

		Array.each(this.el, function(el){
			el.destroy();
		});
		this.el = {};

		MUI.erase(self.options.id);
		return this;
	},

	_addResize: function(element, min, max, where){
		var instance = this;
		if (!$(element)) return;
		element = $(element);

		var handle = (where == 'left') ? element.getPrevious('.columnHandle') : element.getNext('.columnHandle');
		handle.setStyle('cursor', Browser.webkit ? 'col-resize' : 'e-resize');

		if (!min) min = 50;
		if (!max) max = 250;
		if (Browser.ie){
			handle.addEvents({
				'mousedown': function(){
					handle.setCapture();
				},
				'mouseup': function(){
					handle.releaseCapture();
				}
			});
		}

		this.resize = element.makeResizable({
			handle: handle,
			modifiers: {
				x: 'width',
				y: false
			},
			invert: (where == 'left'),
			limit: {
				x: [min, max]
			},
			onStart: function(){
				element.getElements('iframe').setStyle('visibility', 'hidden');
				if (where == 'left'){
					element.getPrevious('.column').getElements('iframe').setStyle('visibility', 'hidden');
				} else {
					element.getNext('.column').getElements('iframe').setStyle('visibility', 'hidden');
				}
			}.bind(this),
			onDrag: function(){
				if (Browser.firefox){
					$$('.panel').each(function(panel){
						if (panel.getElements('.mochaIframe').length == 0){
							panel.hide(); // Fix for a rendering bug in FF
						}
					});
				}
				MUI.rWidth(element.getParent());
				if (Browser.firefox){
					$$('.panel').show(); // Fix for a rendering bug in FF
				}
				if (Browser.ie6){
					element.getChildren().each(function(el){
						var width = $(element).getStyle('width').toInt();
						width -= el.getStyle('border-right').toInt();
						width -= el.getStyle('border-left').toInt();
						width -= el.getStyle('padding-right').toInt();
						width -= el.getStyle('padding-left').toInt();
						el.setStyle('width', width);
					}.bind(this));
				}
			}.bind(this),
			onComplete: function(){
				var partner = (where == 'left') ? element.getPrevious('.column') : element.getNext('.column'),
						partnerInstance = MUI.get(partner);


				MUI.rWidth(element.getParent());
				element.getElements('iframe').setStyle('visibility', 'visible');
				partner.getElements('iframe').setStyle('visibility', 'visible');

				[instance].combine(instance.getPanels())
						.include(partnerInstance)
						.combine(partnerInstance.getPanels())
						.each(function(panel){
					if (panel.el.panel && panel.el.panel.getElement('.mochaIframe') != null) MUI.resizeChildren(panel.el.panel);
					panel.fireEvent('resize', [panel]);
				});

			}.bind(this)
		});
	}

});

MUI.append({

	// Panel Height
	panelHeight: function(column, changing, action){
		if (column != null){
			MUI.panelHeight2($(column), changing, action);
		} else {
			$$('.column').each(function(column){
				MUI.panelHeight2(column);
			}.bind(this));
		}
	},

	panelHeight2: function(column, changing, action){
		var parent = column.getParent();
		var columnHeight = parent.getStyle('height').toInt();
		if (Browser.ie6 && parent == MUI.Desktop.pageWrapper){
			columnHeight -= 1;
		}
		column.setStyle('height', columnHeight);

		// Get column panels
		var panels = [];
		column.getChildren('.panelWrapper').each(function(panelWrapper){
			panels.push(panelWrapper.getElement('.panel'));
		}.bind(this));

		// Get expanded column panels
		var panelsExpanded = [];
		column.getChildren('.expanded').each(function(panelWrapper){
			panelsExpanded.push(panelWrapper.getElement('.panel'));
		}.bind(this));

		// All the panels in the column whose height will be effected.
		var panelsToResize = [];

		// The panel with the greatest height. Remainders will be added to this panel
		var tallestPanel;
		var tallestPanelHeight = 0;

		this.panelsTotalHeight = 0; // Height of all the panels in the column
		this.height = 0; // Height of all the elements in the column

		// Set panel resize partners
		panels.each(function(panel){
			var instance = MUI.get(panel.id);
			if (panel.getParent().hasClass('expanded') && panel.getParent().getNext('.expanded')){
				instance.partner = panel.getParent().getNext('.expanded').getElement('.panel');
				instance.resize.attach();
				instance.el.handle.setStyles({
					'display': 'block',
					'cursor': Browser.webkit ? 'row-resize' : 'n-resize'
				}).removeClass('detached');
			} else {
				instance.resize.detach();
				instance.el.handle.setStyles({
					'display': 'none',
					'cursor': null
				}).addClass('detached');
			}
			if (panel.getParent().getNext('.panelWrapper') == null){
				instance.el.handle.hide();
			}
		}.bind(this));

		// Add panels to panelsToResize
		// Get the total height of all the resizable panels
		// Get the total height of all the column's children
		column.getChildren().each(function(panelWrapper){

			panelWrapper.getChildren().each(function(el){

				if (el.hasClass('panel')){
					var instance = MUI.get(el.id);

					// Are any next siblings Expanded?
					anyNextSiblingsExpanded = function(el){
						var test;
						el.getParent().getAllNext('.panelWrapper').each(function(sibling){
							var siblingInstance = MUI.get(sibling.getElement('.panel').id);
							if (!siblingInstance.isCollapsed){
								test = true;
							}
						}.bind(this));
						return test;
					}.bind(this);

					// If a next sibling is expanding, are any of the nexts siblings of the expanding sibling Expanded?
					var anyExpandingNextSiblingsExpanded = function(){
						var test;
						changing.getParent().getAllNext('.panelWrapper').each(function(sibling){
							var siblingInstance = MUI.get(sibling.getElement('.panel').id);
							if (!siblingInstance.isCollapsed){
								test = true;
							}
						}.bind(this));
						return test;
					}.bind(this);

					// Is the panel that is collapsing, expanding, or new located after this panel?
					var anyNextContainsChanging = function(el){
						var allNext = [];
						el.getParent().getAllNext('.panelWrapper').each(function(panelWrapper){
							allNext.push(panelWrapper.getElement('.panel'));
						}.bind(this));
						return allNext.contains(changing);
					}.bind(this);

					var nextExpandedChanging = function(el){
						var test;
						if (el.getParent().getNext('.expanded')){
							if (el.getParent().getNext('.expanded').getElement('.panel') == changing) test = true;
						}
						return test;
					};

					// NEW PANEL
					// Resize panels that are "new" or not collapsed
					if (action == 'new'){
						if (!instance.isCollapsed && el != changing){
							panelsToResize.push(el);
							this.panelsTotalHeight += el.offsetHeight.toInt();
						}
					}

					// COLLAPSING PANELS and CURRENTLY EXPANDED PANELS
					// Resize panels that are not collapsed.
					// If a panel is collapsing resize any expanded panels below.
					// If there are no expanded panels below it, resize the expanded panels above it.
					else if (action == null || action == 'collapsing'){
						if (!instance.isCollapsed && (!anyNextContainsChanging(el) || !anyNextSiblingsExpanded(el))){
							panelsToResize.push(el);
							this.panelsTotalHeight += el.offsetHeight.toInt();
						}
					}

					// EXPANDING PANEL
					// Resize panels that are not collapsed and are not expanding.
					// Resize any expanded panels below the expanding panel.
					// If there are no expanded panels below the expanding panel, resize the first expanded panel above it.
					else if (action == 'expanding' && !instance.isCollapsed && el != changing){
						if (!anyNextContainsChanging(el) || (!anyExpandingNextSiblingsExpanded(el) && nextExpandedChanging(el))){
							panelsToResize.push(el);
							this.panelsTotalHeight += el.offsetHeight.toInt();
						}
					}

					if (el.style.height){
						this.height += el.getStyle('height').toInt();
					}
				} else {
					this.height += el.offsetHeight.toInt();
				}
			}.bind(this));

			panelsToResize.each(function(panel){
				var MUIPanel = MUI.get(panel.id);
				if (action != 'new') MUIPanel.fireEvent('resize', [MUIPanel]);
			});

		}.bind(this));

		// Get the remaining height
		var remainingHeight = column.offsetHeight.toInt() - this.height;

		this.height = 0;

		// Get height of all the column's children
		column.getChildren().each(function(el){
			this.height += el.offsetHeight.toInt();
		}.bind(this));

		remainingHeight = column.offsetHeight.toInt() - this.height;

		panelsToResize.each(function(panel){
			var ratio = this.panelsTotalHeight / panel.offsetHeight.toInt();
			var newPanelHeight = panel.getStyle('height').toInt() + (remainingHeight / ratio);
			if (newPanelHeight < 1){
				newPanelHeight = 0;
			}
			panel.setStyle('height', newPanelHeight);
		}.bind(this));

		// Make sure the remaining height is 0. If not add/subtract the
		// remaining height to the tallest panel. This makes up for browser resizing,
		// off ratios, and users trying to give panels too much height.

		// Get height of all the column's children
		this.height = 0;
		column.getChildren().each(function(panelWrapper){
			panelWrapper.getChildren().each(function(el){
				this.height += el.offsetHeight.toInt();
				if (el.hasClass('panel') && el.getStyle('height').toInt() > tallestPanelHeight){
					tallestPanel = el;
					tallestPanelHeight = el.getStyle('height').toInt();
				}
			}.bind(this));
		}.bind(this));

		remainingHeight = column.offsetHeight.toInt() - this.height;

		if (remainingHeight != 0 && tallestPanelHeight > 0){
			tallestPanel.setStyle('height', tallestPanel.getStyle('height').toInt() + remainingHeight);
			if (tallestPanel.getStyle('height') < 1){
				tallestPanel.setStyle('height', 0);
			}
		}

		parent.getChildren('.columnHandle').each(function(handle){
			var parent = handle.getParent();
			if (parent.getStyle('height').toInt() < 1) return; // Keeps IE7 and 8 from throwing an error when collapsing a panel within a panel
			var handleHeight = parent.getStyle('height').toInt() - handle.getStyle('border-top').toInt() - handle.getStyle('border-bottom').toInt();
			if (Browser.ie6 && parent == MUI.Desktop.pageWrapper){
				handleHeight -= 1;
			}
			handle.setStyle('height', handleHeight);
		});

		panelsExpanded.each(function(panel){
			MUI.resizeChildren(panel);
		}.bind(this));

	},

	resizeChildren: function(panel){ // May rename this resizeIframeEl()
		var instance = MUI.get(panel.id);
		var contentWrapper = instance.el.contentWrapper;

		if (instance.el.iframe){
			if (!Browser.ie){
				instance.el.iframe.setStyles({
					'height': contentWrapper.getStyle('height'),
					'width': contentWrapper.offsetWidth - contentWrapper.getStyle('border-left').toInt() - contentWrapper.getStyle('border-right').toInt()
				});
			} else {
				// The following hack is to get IE8 RC1 IE8 Standards Mode to properly resize an iframe
				// when only the vertical dimension is changed.
				instance.el.iframe.setStyles({
					'height': contentWrapper.getStyle('height'),
					'width': contentWrapper.offsetWidth - contentWrapper.getStyle('border-left').toInt() - contentWrapper.getStyle('border-right').toInt() - 1
				});
				instance.el.iframe.setStyles({
					'width': contentWrapper.offsetWidth - contentWrapper.getStyle('border-left').toInt() - contentWrapper.getStyle('border-right').toInt()
				});
			}
		}

	},

	rWidth: function(container){ // Remaining Width
		if (container == null) container = MUI.Desktop.desktop;
		if (container == null) return;
		container.getElements('.rWidth').each(function(column){
			var currentWidth = column.offsetWidth.toInt();
			currentWidth -= column.getStyle('border-left').toInt();
			currentWidth -= column.getStyle('border-right').toInt();

			var parent = column.getParent();
			this.width = 0;

			// Get the total width of all the parent element's children
			parent.getChildren().each(function(el){
				if (el.hasClass('mocha') != true){
					this.width += el.offsetWidth.toInt();
				}
			}.bind(this));

			// Add the remaining width to the current element
			var remainingWidth = parent.offsetWidth.toInt() - this.width;
			var newWidth = currentWidth + remainingWidth;
			if (newWidth < 1) newWidth = 0;
			column.setStyle('width', newWidth);

			// fire all panel resize events and the column resize event
			var instance = MUI.get(column.id);
			[instance].combine(instance.getPanels()).each(function(panel){
				panel.fireEvent('resize', [panel]);
			}, this);

			column.getElements('.panel').each(function(panel){
				panel.setStyle('width', newWidth - panel.getStyle('border-left').toInt() - panel.getStyle('border-right').toInt());
				MUI.resizeChildren(panel);
			}.bind(this));

		});
	}

});

/*
 ---

 script: Taskbar.js

 description: Implements the taskbar. Enables window minimize.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires:
 - MochaUI/MUI
 - MochaUI/MUI.Desktop

 provides: [MUI.Taskbar]

 ...
 */

MUI.files['{controls}taskbar/taskbar.js'] = 'loaded';

MUI.Taskbar = (MUI.Taskbar || new NamedClass('MUI.Taskbar', {}));
MUI.Taskbar.implement({

	Implements: [Events, Options],

	options: {
		id:				'taskbar',
		container:		null,
		drawOnInit:		true,

		useControls:	true,			// Toggles autohide and taskbar placement controls.
		position:		'bottom',		// Position the taskbar starts in, top or bottom.
		visible:		true,			// is the taskbar visible
		autoHide:		 false,			// True when taskbar autohide is set to on, false if set to off
		menuCheck:		'taskbarCheck',	// the name of the element in the menu that needs to be checked if taskbar is shown
		cssClass:		'taskbar'

		//onDrawBegin:	null,
		//onDrawEnd:	null,
		//onMove:		null,
		//onTabCreated:	null,
		//onTabSet:		null,
		//onHide:		null,
		//onShow:		null
	},

	initialize: function(options){
		this.setOptions(options);
		this.el = {};

		// If taskbar has no ID, give it one.
		this.id = this.options.id = this.options.id || 'taskbar' + (++MUI.idCount);
		MUI.set(this.id, this);

		if (MUI.taskbar) return;	// [i_a] only one taskbar allowed
		MUI.taskbar = this;

		if (this.options.drawOnInit) this.draw();
	},

	draw: function(){
		var o = this.options;

		this.fireEvent('drawBegin', [this]);

		if (MUI.desktop && MUI.desktop.el && MUI.desktop.el.footer) this.desktopFooter = MUI.desktop.el.footer;

		var isNew = false;
		var div = o.element ? o.element :$(o.id + 'Wrapper');
		if (!div){
			div = new Element('div', {'id': o.id + 'Wrapper'});
			isNew = true;
		}
		div.set('class', o.cssClass + 'Wrapper');
		div.empty();
		this.el.wrapper = this.el.element = div.store('instance', this);

		var defaultBottom = this.desktopFooter ? this.desktopFooter.offsetHeight : 0;
		this.el.wrapper.setStyles({
			'display':	'block',
			'position':	'absolute',
			'top':		null,
			'bottom':	defaultBottom,
			'left':		0,
			'class':	o.cssClass + 'Wrapper'
		});

		this.el.taskbar = new Element('div', {'id': this.options.id,'class':o.cssClass}).inject(this.el.wrapper);

		if (this.options.useControls){
			this.el.placement = new Element('div', {'id': this.options.id + 'Placement','class':o.cssClass + 'Placement'}).inject(this.el.taskbar).setStyle('cursor', 'default');
			this.el.autohide = new Element('div', {'id': this.options.id + 'AutoHide','class':o.cssClass + 'AutoHide'}).inject(this.el.taskbar).setStyle('cursor', 'default');
		}

		this.el.sort = new Element('div', {'id': this.options.id + 'Sort'}).inject(this.el.taskbar);
		this.el.clear = new Element('div', {'id': this.options.id + 'Clear', 'class': 'clear'}).inject(this.el.sort);

		this._initialize();
		this.fireEvent('drawEnd', [this]);
	},

	setTaskbarColors: function(){
		var enabled = Asset.getCSSRule('.taskbarButtonEnabled');
		if (enabled && enabled.style.backgroundColor)
			this.enabledButtonColor = new Color(enabled.style.backgroundColor);

		var disabled = Asset.getCSSRule('.taskbarButtonDisabled');
		if (disabled && disabled.style.backgroundColor)
			this.disabledButtonColor = new Color(disabled.style.backgroundColor);

		var color = Asset.getCSSRule('.taskbarButtonTrue');
		if (color && color.style.backgroundColor)
			this.trueButtonColor = new Color(color.style.backgroundColor);

		this._renderTaskControls();
	},

	getHeight: function(){
		return this.el.wrapper.offsetHeight;
	},

	move: function(position){
		var ctx = this.el.canvas.getContext('2d');
		// Move taskbar to top position
		if (position == 'top' || this.el.wrapper.getStyle('position') != 'relative'){
			if (position == 'top') return;

			this.el.wrapper.setStyles({
				'position':	'relative',
				'bottom':	null
			}).addClass('top');
			if (MUI.desktop) MUI.desktop.setDesktopSize();
			this.el.wrapper.setProperty('position', 'top');
			ctx.clearRect(0, 0, 100, 100);
			MUI.Canvas.circle(ctx, 5, 4, 3, this.enabledButtonColor, 1.0);
			MUI.Canvas.circle(ctx, 5, 14, 3, this.disabledButtonColor, 1.0);
			$(this.options.id + 'Placement').setProperty('title', 'Position Taskbar Bottom');
			$(this.options.id + 'AutoHide').setProperty('title', 'Auto Hide Disabled in Top Taskbar Position');
			this.options.autoHide = false;
			this.options.position = 'top';
		} else {
			if (position == 'bottom') return;

			// Move taskbar to bottom position
			this.el.wrapper.setStyles({
				'position':	'absolute',
				'bottom':	this.desktopFooter ? this.desktopFooter.offsetHeight : 0
			}).removeClass('top');
			if (MUI.desktop) MUI.desktop.setDesktopSize();
			this.el.wrapper.setProperty('position', 'bottom');
			ctx.clearRect(0, 0, 100, 100);
			MUI.Canvas.circle(ctx, 5, 4, 3, this.enabledButtonColor, 1.0);
			MUI.Canvas.circle(ctx, 5, 14, 3, this.enabledButtonColor, 1.0);
			$(this.options.id + 'Placement').setProperty('title', 'Position Taskbar Top');
			$(this.options.id + 'AutoHide').setProperty('title', 'Turn Auto Hide On');
			this.options.position = 'bottom';
		}

		this.fireEvent('move', [this, this.options.position]);
	},

	createTab: function(instance){
		var taskbarTab = new Element('div', {
			'id': instance.options.id + '_taskbarTab',
			'class': 'taskbarTab',
			'title': titleText
		}).inject($(this.options.id + 'Clear'), 'before');

		taskbarTab.addEvent('mousedown', function(e){
			new Event(e).stop();
			this.timeDown = Date.now();
		}.bind(instance));

		taskbarTab.addEvent('mouseup', function(){
			this.timeUp = Date.now();
			if ((this.timeUp - this.timeDown) < 275){
				// If the visibility of the windows on the page are toggled off, toggle visibility on.
				if (!MUI.Windows.windowsVisible){
					MUI.Windows.toggleAll();
					if (this.isMinimized) this._restoreMinimized.delay(25, this);
					else this.focus();
					return;
				}
				// If window is minimized, restore window.
				if (this.isMinimized) this._restoreMinimized.delay(25, this);
				else {
					var windowEl = this.el.windowEl;
					if (windowEl.hasClass('isFocused') && this.options.minimizable) this.minimize.delay(25, this);
					else this.focus();

					// if the window is not minimized and is outside the viewport, center it in the viewport.
					var coordinates = document.getCoordinates();
					if (windowEl.getStyle('left').toInt() > coordinates.width || windowEl.getStyle('top').toInt() > coordinates.height)
						this.center();
				}
			}
		}.bind(instance));

		this.sortables.addItems(taskbarTab);

		var titleText = instance.el.title.innerHTML;

		new Element('div', {
			'id': instance.options.id + '_taskbarTabText',
			'class': this.options.cssClass + 'Text'
		}).set('html', titleText.substring(0, 19) + (titleText.length > 19 ? '...' : '')).inject($(taskbarTab));

		// Need to resize everything in case the taskbar wraps when a new tab is added
		if (this.desktopFooter) MUI.desktop.setDesktopSize();
		this.fireEvent('tabCreated', [this, instance]);
	},

	makeTabActive: function(instance){
		var css = this.options.cssClass;

		if (!instance){
			// getWindowWithHighestZindex is used in case the currently focused window is closed.
			var windowEl = MUI.Windows._getWithHighestZIndex();
			instance = windowEl.retrieve('instance');
		}

		$$('.' + css + 'Tab').removeClass('activeTab');
		if (instance.isMinimized != true){
			instance.el.windowEl.addClass('isFocused');
			var currentButton = $(instance.options.id + '_taskbarTab');
			if (currentButton != null) currentButton.addClass('activeTab');
		} else instance.el.windowEl.removeClass('isFocused');
		this.fireEvent('tabSet', [this,instance]);
	},

	show: function(){
		this.el.wrapper.setStyle('display', 'block');
		this.options.visible = true;
		if (this.desktopFooter) MUI.desktop.setDesktopSize();
		this.fireEvent('show', [this]);
	},

	hide: function(){
		this.el.wrapper.setStyle('display', 'none');
		this.options.visible = false;
		if (this.desktopFooter) MUI.desktop.setDesktopSize();
		this.fireEvent('hide', [this]);
	},

	toggle: function(){
		if (!this.options.visible) this.show();
		else this.hide();
	},

	_initialize: function(){
		var css = this.options.cssClass;

		if (this.options.useControls){
			// Insert canvas
			this.el.canvas = new Element('canvas', {
				'id':	 this.options.id + 'Canvas',
				'width':  '15',
				'height': '18',
				'class' : css + 'Canvas'
			}).inject(this.el.taskbar);

			// Dynamically initialize canvas using excanvas. This is only required by IE
			if (Browser.ie && MUI.ieSupport == 'excanvas'){
				G_vmlCanvasManager.initElement(this.el.canvas);
			}
		}

		// Position top or bottom selector
		this.el.placement.setProperty('title', 'Position Taskbar Top');

		// Attach event
		this.el.placement.addEvent('click', function(){
			this.move();
		}.bind(this));

		// Auto Hide toggle switch
		this.el.autohide.setProperty('title', 'Turn Auto Hide On');

		// Attach event Auto Hide
		this.el.autohide.addEvent('click', function(){
			this._doAutoHide();
		}.bind(this));

		this.setTaskbarColors();

		if (this.options.position == 'top') this.move();

		// Add check mark to menu if link exists in menu
		if ($(this.options.menuCheck)) this.sidebarCheck = new Element('div', {
			'class': 'check',
			'id': this.options.id + '_check'
		}).inject($(this.options.menuCheck));

		this.sortables = new Sortables('.' + css + 'Sort', {
			opacity: 1,
			constrain: true,
			clone: false,
			revert: false
		});

		if (this.options.autoHide) this._doAutoHide(true);
		if (this.desktopFooter) MUI.desktop.setDesktopSize();
	},

	_doAutoHide: function(notoggle){
		if (this.el.wrapper.getProperty('position') == 'top')
			return false;

		var ctx = this.el.canvas.getContext('2d');
		if (!notoggle) this.options.autoHide = !this.options.autoHide;	// Toggle

		if (this.options.autoHide){
			$(this.options.id + 'AutoHide').setProperty('title', 'Turn Auto Hide Off');
			MUI.Canvas.circle(ctx, 5, 14, 3, this.trueButtonColor, 1.0);
			document.addEvent('mousemove', this._autoHideEvent.bind(this));
		} else {
			$(this.options.id + 'AutoHide').setProperty('title', 'Turn Auto Hide On');
			MUI.Canvas.circle(ctx, 5, 14, 3, this.enabledButtonColor, 1.0);
			document.removeEvent('mousemove', this._autoHideEvent.bind(this));
		}
	},

	_autoHideEvent: function(event){
		if (!this.options.autoHide) return;
		var hotspotHeight;
		if (!this.desktopFooter){
			hotspotHeight = this.el.wrapper.offsetHeight;
			if (hotspotHeight < 25) hotspotHeight = 25;
		}
		else if (this.desktopFooter){
			hotspotHeight = this.el.wrapper.offsetHeight + this.desktopFooter.offsetHeight;
			if (hotspotHeight < 25) hotspotHeight = 25;
		}
		if (!this.desktopFooter && event.client.y > (document.getCoordinates().height - hotspotHeight)){
			if (!this.options.visible) this.show();
		}
		else if (this.desktopFooter && event.client.y > (document.getCoordinates().height - hotspotHeight)){
			if (!this.options.visible) this.show();
		}
		else if (this.options.visible) this.hide();
	},

	_renderTaskControls: function(){
		// Draw taskbar controls
		var ctx = this.el.canvas.getContext('2d');
		ctx.clearRect(0, 0, 100, 100);
		MUI.Canvas.circle(ctx, 5, 4, 3, this.enabledButtonColor, 1.0);

		if (this.el.wrapper.getProperty('position') == 'top') MUI.Canvas.circle(ctx, 5, 14, 3, this.disabledButtonColor, 1.0);
		else if (this.options.autoHide) MUI.Canvas.circle(ctx, 5, 14, 3, this.trueButtonColor, 1.0);
		else MUI.Canvas.circle(ctx, 5, 14, 3, this.enabledButtonColor, 1.0);
	}

});

MUI.Windows = Object.append((MUI.Windows || {}), {

	minimizeAll: function(){
		$$('.mocha').each(function(windowEl){
			var instance = windowEl.retrieve('instance');
			if (!instance.isMinimized && instance.options.minimizable){
				instance.minimize();
			}
		}.bind(this));
	}

});

MUI.Window = (MUI.Window || new NamedClass('MUI.Window', {}));
MUI.Window.implement({

	minimize: function(){
		if (this.isMinimized) return this;
		this.isMinimized = true;

		// Hide iframe
		// Iframe should be hidden when minimizing, maximizing, and moving for performance and Flash issues
		if (this.el.iframe){
			// Some elements are still visible in IE8 in the iframe when the iframe's visibility is set to hidden.
			if (!Browser.ie) this.el.iframe.setStyle('visibility', 'hidden');
			else this.el.iframe.hide();
		}

		this.hide(); // Hide window and add to taskbar

		// Fixes a scrollbar issue in Mac FF2
		if (Browser.Platform.mac && Browser.firefox && Browser.version < 3){
			this.el.contentWrapper.setStyle('overflow', 'hidden');
		}

		if (MUI.desktop) MUI.desktop.setDesktopSize();

		// Have to use timeout because window gets focused when you click on the minimize button
		setTimeout(function(){
			//this.el.windowEl.setStyle('zIndex', 1);
			this.el.windowEl.removeClass('isFocused');
			MUI.taskbar.makeTabActive();
		}.bind(this), 100);

		this.fireEvent('minimize', [this]);
		return this;
	},

	_restoreMinimized: function(){
		if (!this.isMinimized) return;

		if (!MUI.Windows.windowsVisible) MUI.Windows.toggleAll();
		this.show(); // show the window
		MUI.desktop.setDesktopSize();
		if (this.options.scrollbars && !this.el.iframe) this.el.contentWrapper.setStyle('overflow', 'auto'); // Part of Mac FF2 scrollbar fix
		if (this.isCollapsed) this.collapseToggle();

		if (this.el.iframe){  // Show iframe
			if (!Browser.ie) this.el.iframe.setStyle('visibility', 'visible');
			else this.el.iframe.show();
		}

		this.isMinimized = false;
		this.focus();
		this.fireEvent('restore', [this]);
	}

});

/*
 ---

 script: Window.js

 description: Build windows.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 requires: [MochaUI/MUI]

 provides: [MUI.Windows]

 ...
 */

MUI.files['{controls}window/window.js'] = 'loaded';

MUI.Windows = Object.append((MUI.Windows || {}), {
	indexLevel:	 100,			// Used for window z-Index
	windowsVisible: true,		// Ctrl-Alt-Q to toggle window visibility
	focusingWindow: false,

	options: {
		id:					null,
		title:				'New Window',
		icon:				false,
		type:				'window',

		// content section update options
		content:			false,			// used to update the content section of the panel.
		// if it is a string it assumes that the content is html and it will be injected into the content div.
		// if it is an array then assume we need to update multiple sections of the panel
		// if it is not a string or array it assumes that is a hash and just the content section will have .

		// Container options
		container:			null,
		shape:				'box',

		// Window Controls
		collapsible:		true,
		minimizable:		true,
		maximizable:		true,
		closable:			true,

		// Close options
		storeOnClose:		false,
		closeAfter:			false,

		// Modal options
		modalOverlayClose:	true,

		// Draggable
		draggable:			null,
		draggableGrid:		false,
		draggableLimit:		false,
		draggableSnap:		false,

		// Resizable
		resizable:			null,
		resizeLimit:		{'x': [250, 2500], 'y': [125, 2000]},

		// Style options:
		cssClass:			'',
		width:				300,
		height:				125,
		headerHeight:		25,
		footerHeight:		25,
		cornerRadius:		8,
		radiusOnMaximize:	false,
		x:					null,
		y:					null,
		scrollbars:			true,
		padding:			{top: 10, right: 12, bottom: 10, left: 12},
		shadowBlur:			5,
		shadowOffset:		{'x': 0, 'y': 1},
		controlsOffset:		{'right': 6, 'top': 6},
		useCanvas:			true,
		useCanvasControls:	true,
		useCSS3:			true,
		useSpinner:			true

		// Events
		//onLoaded:			null, // called every time content is loaded using MUI.Content
		//onDrawBegin:		null,
		//onDrawEnd:		null,
		//onFocus:			null,
		//onBlur:			null,
		//onResize:			null,
		//onMinimize:		null,
		//onMaximize:		null,
		//onRestore:		null,
		//onClose:			null,
		//onCloseComplete:	null,
		//onDragStart:		null,
		//onDragComplete:	null
	},

	closeAll: function(){
		$$('.mocha').each(function(windowEl){
			windowEl.close();
		}.bind(this));
	},

	toggleAll: function(){
		MUI.each(function(instance){
			if (!instance.isTypeOf || !instance.isTypeOf('MUI.Window') || instance.isMinimized) return;
			var windowEl = instance.el.windowEl;
			if (windowEl.getStyle('visibility') == 'visible'){
				if (instance.iframe) instance.el.iframe.setStyle('visibility', 'hidden');
				if (instance.sections){
					instance.sections.each(function(section){
						if (section.position == 'content') return;
						var el = section.wrap ? section.wrapperEl : section.element;
						if (el) el.setStyle('visibility', 'hidden');
					});
				}
				if (instance.el.contentBorder) instance.el.contentBorder.setStyle('visibility', 'hidden');
				windowEl.setStyle('visibility', 'hidden');
				MUI.Windows.windowsVisible = false;
			} else {
				windowEl.setStyle('visibility', 'visible');
				if (instance.el.contentBorder) instance.el.contentBorder.setStyle('visibility', 'visible');
				if (instance.iframe) instance.el.iframe.setStyle('visibility', 'visible');
				if (instance.sections){
					instance.sections.each(function(section){
						if (section.position == 'content') return;
						var el = section.wrap ? section.wrapperEl : section.element;
						if (el) el.setStyle('visibility', 'visible');
					});
				}
				MUI.Windows.windowsVisible = true;
			}
		}.bind(this));
	},

	blurAll: function(){
		if (!MUI.Windows.focusingWindow){
			$$('.mocha').each(function(windowEl){
				var instance = windowEl.retrieve('instance');
				if (instance.options.type != 'modal' && instance.options.type != 'modal2'){
					windowEl.removeClass('isFocused');
				}
			});
			$$('.taskbarTab').removeClass('activetaskbarTab');
		}
	},

	newFromHTML: function(){

		$$('.mocha').each(function(el){
			// Get the window title and destroy that element, so it does not end up in window content
			if (Browser.opera || Browser.ie7){
				el.hide(); // Required by Opera, and probably IE7
			}
			var title = el.getElement('h3.mochaTitle');

			if (Browser.opera) el.show();

			var elDimensions = el.getStyles('height', 'width');
			var properties = {
				id: el.getProperty('id'),
				height: elDimensions.height.toInt(),
				width: elDimensions.width.toInt(),
				x: el.getStyle('left').toInt(),
				y: el.getStyle('top').toInt()
			};

			// If there is a title element, set title and destroy the element so it does not end up in window content
			if (title){
				properties.title = title.innerHTML;
				title.destroy();
			}

			// Get content and destroy the element
			properties.content = el.innerHTML;
			el.destroy();

			// Create window
			new MUI.Window(properties, true);
		}.bind(this));
	},

	newFromJSON: function(newWindows){

		newWindows.each(function(options){
			var temp = new Object(options);

			temp.each(function(value, key){
				if (typeOf(value) != 'string') return;
				if (value.substring(0, 8) == 'function'){
					eval("options." + key + " = " + value);
				}
			});

			new MUI.Window(options);
		});

	},

	_underlayInitialize: function(){
		/*
		 The underlay is inserted directly under windows when they are being dragged or resized
		 so that the cursor is not captured by iframes or other plugins (such as Flash)
		 underneath the window.
		 */
		var windowUnderlay = new Element('div', {
			'id': 'windowUnderlay',
			'styles': {
				'height': parent.getCoordinates().height,
				'opacity': .01,
				'display': 'none'
			}
		}).inject(document.body);
	},

	_setUnderlaySize: function(){
		$('windowUnderlay').setStyle('height', parent.getCoordinates().height);
	},

	_getWithHighestZIndex: function(){
		this.highestZindex = 0;
		$$('.mocha').each(function(element){
			this.zIndex = element.getStyle('zIndex');
			if (this.zIndex >= this.highestZindex){
				this.highestZindex = this.zIndex;
			}
		}.bind(this));
		$$('.mocha').each(function(element){
			if (element.getStyle('zIndex') == this.highestZindex){
				this.windowWithHighestZindex = element;
			}
		}.bind(this));
		return this.windowWithHighestZindex;
	}

});

MUI.Window = (MUI.Window || new NamedClass('MUI.Window', {}));
MUI.Window.implement({

	Implements: [Events, Options],

	options: MUI.Windows.options,

	initialize: function(options){
		this.setOptions(MUI.Windows.options); // looks strange, but is here to allow global options to be set externally to Window.js
		this.setOptions(options);

		// Shorten object chain
		options = this.options;

		Object.append(this, {
			mochaControlsWidth: 0,
			minimizeButtonX: 0,  // Minimize button horizontal position
			maximizeButtonX: 0,  // Maximize button horizontal position
			closeButtonX: 0,  // Close button horizontal position
			headerFooterShadow: options.headerHeight + options.footerHeight + (options.shadowBlur * 2),
			oldTop: 0,
			oldLeft: 0,
			isMaximized: false,
			isMinimized: false,
			isCollapsed: false,
			el: {}
		});

		if (options.type != 'window'){
			options.container = document.body;
			options.minimizable = false;
		}
		if (!options.container) options.container = MUI.Desktop && MUI.Desktop.desktop ? MUI.Desktop.desktop : document.body;

		// Set this.options.resizable to default if it was not defined
		if (options.resizable == null) options.resizable = !(options.type != 'window' || options.shape == 'gauge');

		// Set this.options.draggable if it was not defined
		if (options.draggable == null) options.draggable = options.type == 'window';

		// Gauges are not maximizable or resizable
		if (options.shape == 'gauge' || options.type == 'notification'){
			options.collapsible = false;
			options.maximizable = false;
			options.contentBgColor = 'transparent';
			options.scrollbars = false;
			options.footerHeight = 0;
			options.useCSS3 = false;
		}
		if (options.type == 'notification'){
			options.closable = false;
			options.headerHeight = 0;
		}

		// Minimizable, taskbar is required and window cannot be modal
		if (MUI.taskbar){
			if (options.type != 'modal' && options.type != 'modal2') this.options.minimizable = options.minimizable;
		} else options.minimizable = false;

		// Maximizable, desktop is required
		options.maximizable = MUI.Desktop && MUI.Desktop.desktop && options.maximizable && options.type != 'modal' && options.type != 'modal2';

		if (this.options.type == 'modal2'){
			this.options.shadowBlur = 0;
			this.options.shadowOffset = {'x': 0, 'y': 0};
			this.options.useSpinner = false;
			this.options.useCanvas = false;
			this.options.footerHeight = 0;
			this.options.headerHeight = 0;
		}

		// If window has no ID, give it one.
		this.id = options.id = options.id || 'win' + (++MUI.idCount);

		this.el.windowEl = $(options.id);

		// Condition under which to use CSS3, needs shadow, border-radius and gradient support
		if (!options.useCSS3) this.useCSS3 = false;
		else if (Browser.firefox && Browser.version >= 3.6) this.useCSS3 = true; // FF3.6
		else if (Browser.webkit && Browser.version >= 4) this.useCSS3 = true; // S4
		else this.useCSS3 = Browser.ie && Browser.version > 9; // ie9

		// if somebody wants CSS3 but not canvas and condition are false for css3
		// i.e. IE8 Test CSS3 Body
		if (options.useCSS3 && !this.useCSS3 && !this.options.useCanvas) options.shadowBlur = 0;

		this.draw();

		// Return window object
		return this;
	},

	draw: function(){
		var options = this.options;

		// Check if window already exists and is not in progress of closing
		if (this.el.windowEl && !this.isClosing){
			// Restore if minimized
			if (this.isMinimized) this._restoreMinimized();

			// Expand and focus if collapsed
			else if (this.isCollapsed){
				this.collapseToggle();
				this.focus.delay(10, this);
			} else if (this.el.windowEl.hasClass('windowClosed')){

				if (this.el.check) this.el.check.show();

				this.el.windowEl.removeClass('windowClosed');
				this.el.windowEl.setStyle('opacity', 0);
				this.el.windowEl.addClass('mocha');

				if (MUI.taskbar && options.type == 'window'){
					var currentButton = $(options.id + '_taskbarTab');
					if (currentButton) currentButton.show();
					MUI.Desktop.setDesktopSize();
				}

				this._showNewWindow();
			} else { // Else focus
				var coordinates = document.getCoordinates();
				if (this.el.windowEl.getStyle('left').toInt() > coordinates.width || this.el.windowEl.getStyle('top').toInt() > coordinates.height)
					this.center();
				this.focus.delay(10, this);
				if (MUI.options.standardEffects) this.el.windowEl.shake();
			}
			return this;
		} else MUI.set(options.id, this);

		this.isClosing = false;
		this.fireEvent('drawBegin', [this]);

		// Create window div
		MUI.Windows.indexLevel++;
		this.el.windowEl = new Element('div', {
			'class': this.useCSS3 ? 'mocha css3' : 'mocha',
			'id': options.id,
			'styles': {
				'position': 'absolute',
				'width': options.width,
				'height': options.height,
				'display': 'block',
				'opacity': 0,
				'zIndex': MUI.Windows.indexLevel += 2
			}
		});

		if (this.options.type == 'modal' || this.options.type == 'modal2') this.el.windowEl.addClass('modal');

		this.el.windowEl.store('instance', this);
		this.el.windowEl.addClass(options.cssClass);
		if (options.type == 'modal2') this.el.windowEl.addClass('modal2');

		// Fix a mouseover issue with gauges in IE7
		if (Browser.ie && options.shape == 'gauge') this.el.windowEl.setStyle('backgroundImage', 'url(../images/spacer.gif)');

		if ((this.options.type == 'modal' || options.type == 'modal2') && Browser.Platform.mac && Browser.firefox){
			if (Browser.version < 3) this.el.windowEl.setStyle('position', 'fixed');
		}

		// Insert sub elements inside el.windowEl
		this._insertWindowElements();

		// Set title
		this.el.title.set('html', options.title);

		this.el.contentWrapper.setStyle('overflow', 'hidden');

		if (options.shape == 'gauge'){
			if (options.useCanvasControls) this.el.canvasControls.setStyle('visibility', 'hidden');
			else this.el.controls.setStyle('visibility', 'hidden');
			this.el.windowEl.addEvent('mouseover', function(){
				this.mouseover = true;
				var showControls = function(){
					if (this.mouseover){
						if (options.useCanvasControls) this.el.canvasControls.setStyle('visibility', 'visible');
						else this.el.controls.setStyle('visibility', 'visible');
						this.el.canvasHeader.setStyle('visibility', 'visible');
						this.el.title.show();
					}
				};
				showControls.delay(0, this);

			}.bind(this));
			this.el.windowEl.addEvent('mouseleave', function(){
				this.mouseover = false;
				if (this.options.useCanvasControls) this.el.canvasControls.setStyle('visibility', 'hidden');
				else this.el.controls.setStyle('visibility', 'hidden');
				this.el.canvasHeader.setStyle('visibility', 'hidden');
				this.el.title.hide(); // should this really hide the entire window like it does now?
			}.bind(this));
		}

		// Inject window into DOM
		this.el.windowEl.inject($(options.container));

		// Convert CSS colors to Canvas colors.
		this._setColors();
		if (options.type != 'notification') this._setMochaControlsWidth();

		// load/build all of the additional  content sections
		if (this.sections) this.sections.each(function(section){
			if (section.onLoaded) section.onLoaded = section.onLoaded.bind(this);
			section.instance = this;
			MUI.Content.update(section);
		}, this);

		this.redraw();

		// Attach events to the window
		this._attachDraggable();
		this._attachResizable();
		this._setupEvents();

		if (options.resizable) this._adjustHandles();

		// Position window. If position not specified by user then center the window on the page.
		var dimensions = (options.container == document.body || options.container == MUI.Desktop.desktop) ? window.getSize() : $(this.options.container).getSize();
		var x,y;
		if (options.y){
			y = options.y - options.shadowBlur;
		} else {
			if (MUI.Desktop && MUI.Desktop.desktop){
				y = (dimensions.y * .5) - (this.el.windowEl.offsetHeight * .5);
				if (y < -options.shadowBlur) y = -options.shadowBlur;
			} else {
				y = window.getScroll().y + (window.getSize().y * .5) - (this.el.windowEl.offsetHeight * .5);
				if (y < -options.shadowBlur) y = -options.shadowBlur;
			}
		}

		if (this.options.x == null){
			x = (dimensions.x * .5) - (this.el.windowEl.offsetWidth * .5);
			if (x < -options.shadowBlur) x = -options.shadowBlur;
		} else x = options.x - options.shadowBlur;

		this.el.windowEl.setStyles({
			'top': y,
			'left': x
		});

		// Create opacityMorph
		this.opacityMorph = new Fx.Morph(this.el.windowEl, {
			'duration': 350,
			transition: Fx.Transitions.Sine.easeInOut,
			onComplete: function(){
				if (Browser.ie) this.redraw();
			}.bind(this)
		});

		this._showNewWindow();

		// This is a generic morph that can be reused later by functions like centerWindow()
		// It returns the el.windowEl element rather than this Class.
		this.morph = new Fx.Morph(this.el.windowEl, {
			'duration': 200
		});
		this.el.windowEl.store('morph', this.morph);

		this.resizeMorph = new Fx.Elements([this.el.contentWrapper, this.el.windowEl], {
			duration: 400,
			transition: Fx.Transitions.Sine.easeInOut,
			onStart: function(){
				this.resizeAnimation = this.redraw.periodical(20, this);
			}.bind(this),
			onComplete: function(){
				clearInterval(this.resizeAnimation);
				this.redraw();
				// Show iframe
				if (this.el.iframe) this.el.iframe.setStyle('visibility', 'visible');
			}.bind(this)
		});
		this.el.windowEl.store('resizeMorph', this.resizeMorph);

		// Add check mark to menu if link exists in menu
		// Need to make sure the check mark is not added to links not in menu
		if ($(this.el.windowEl.id + 'LinkCheck')){
			this.el.check = new Element('div', {
				'class': 'check',
				'id': this.options.id + '_check'
			}).inject(this.el.windowEl.id + 'LinkCheck');
		}

		Object.each(this.el, (function(ele){
			if(ele!=this.el.spinner) ele.store('instance', this);
		}).bind(this));

		if (this.options.closeAfter) this.close.delay(this.options.closeAfter, this);
		if (MUI.taskbar && this.options.type == 'window') MUI.taskbar.createTab(this);
		this.fireEvent('drawEnd', [this]);
		return this;
	},

	redraw: function(shadows){
		if (shadows == null) shadows = true;
		if (this.drawingWindow) return;
		this.drawingWindow = true;

		if (this.isCollapsed){
			this._drawWindowCollapsed(shadows);
			return;
		}

		var options = this.options;
		var shadowBlur = this.useCSS3 ? 0 : options.shadowBlur;
		var shadowBlur2x = this.useCSS3 ? 0 : shadowBlur * 2;
		var shadowOffset = this.options.shadowOffset;

		this.el.overlay.setStyle('width', this.el.contentWrapper.offsetWidth);

		// Resize iframe when window is resized
		if (this.el.iframe) this.el.iframe.setStyle('height', this.el.contentWrapper.offsetHeight);

		var borderHeight = this.el.contentBorder.getStyle('border-top').toInt() + this.el.contentBorder.getStyle('border-bottom').toInt();

		this.headerFooterShadow = options.headerHeight + options.footerHeight + shadowBlur2x;

		var width = this.el.contentWrapper.getStyle('width').toInt() + shadowBlur2x;
		var height = this.el.contentWrapper.getStyle('height').toInt() + this.headerFooterShadow + borderHeight;
		if (this.sections) this.sections.each(function(section){
			if (section.position == 'content') return;
			var el = section.wrap ? section.wrapperEl : section.element;
			height += el.getStyle('height').toInt() + el.getStyle('border-top').toInt();
		});

		this.el.windowEl.setStyles({
			'height': height,
			'width': width
		});
		this.el.titleBar.setStyles({
			'width': width - shadowBlur2x,
			'height': options.headerHeight
		});

		if (this.useCSS3) this._css3SetStyles();
		else {
			this.el.overlay.setStyles({
				'height': height,
				'top': shadowBlur - shadowOffset.y,
				'left': shadowBlur - shadowOffset.x
			});

			if (this.options.useCanvas){
				if (Browser.ie){
					this.el.canvas.height = 20000;
					this.el.canvas.width = 50000;
				}
				this.el.canvas.height = height;
				this.el.canvas.width = width;
			}

			// Part of the fix for IE6 select z-index bug
			if (Browser.ie6) this.el.zIndexFix.setStyles({'width': width, 'height': height});

			// Make sure loading icon is placed correctly.
			if (options.useSpinner && options.shape != 'gauge' && options.type != 'notification'){
				this.el.spinner.setStyles({
					'left': shadowBlur - shadowOffset.x,
					'bottom': shadowBlur + shadowOffset.y + 8
				});
			}

			if (this.options.useCanvas){
				// Draw Window
				var ctx = this.el.canvas.getContext('2d');
				ctx.clearRect(0, 0, width, height);

				switch (options.shape){
					case 'box':
						MUI.Canvas.drawBox(ctx, width, height, shadowBlur, shadowOffset, shadows, this.options.type != 'notification' ? this.options.headerHeight : 0, this.options.cornerRadius, this.bodyBgColor, this.headerStartColor, this.headerStopColor);
						break;
					case 'gauge':
						MUI.Canvas.drawGauge(ctx, width, height, shadowBlur, shadowOffset, shadows, this.el.canvasHeader, this.options.headerHeight, this.bodyBgColor, this.useCSS3);
						break;
				}

				if (options.resizable && !this.isMaximized){
					if (!this.resizableColor)
					{
						alert('resizableColor not set!');
					}
					MUI.Canvas.triangle(ctx, width - (shadowBlur + shadowOffset.x + 17), height - (shadowBlur + shadowOffset.y + 18), 11, 11, this.resizableColor, 1.0);
					// Invisible dummy object. The last element drawn is not rendered consistently while resizing in IE6 and IE7
					if (Browser.ie) MUI.Canvas.triangle(ctx, 0, 0, 10, 10, this.resizableColor, 0);
				}
			}
		}

		if (options.type != 'notification' && options.useCanvasControls) this._drawControls(width, height, shadows);

		// Resize panels if there are any
		if (MUI.Desktop && this.el.contentWrapper.getChildren('.column').length != 0){
			MUI.rWidth(this.el.contentWrapper);
			this.el.contentWrapper.getChildren('.column').each(function(column){
				MUI.panelHeight(column);
			});
		}

		this.drawingWindow = false;
		return this;
	},

	restore: function(){
		if (this.isMinimized){
			if (this._restoreMinimized) this._restoreMinimized();
		}
		else if (this.isMaximized && this._restoreMaximized) this._restoreMaximized();
		return this;
	},

	center: function(){
		var windowEl = this.el.windowEl;
		var options = this.options;
		var dimensions = $(options.container).getDimensions();

		var windowPosTop = window.getScroll().y + (window.getSize().y * .5) - (windowEl.offsetHeight * .5);
		if (windowPosTop < -options.shadowBlur) windowPosTop = -options.shadowBlur;
		var windowPosLeft = (dimensions.width * .5) - (windowEl.offsetWidth * .5);
		if (windowPosLeft < -options.shadowBlur) windowPosLeft = -options.shadowBlur;
		if (MUI.options.advancedEffects){
			this.morph.start({
				'top': windowPosTop,
				'left': windowPosLeft
			});
		} else {
			windowEl.setStyles({
				'top': windowPosTop,
				'left': windowPosLeft
			});
		}

		return this;
	},

	resize: function(options){
		var windowEl = this.el.windowEl;

		options = Object.append({
			width: null,
			height: null,
			top: null,
			left: null,
			centered: true
		}, options);

		var oldWidth = windowEl.getStyle('width').toInt();
		var oldHeight = windowEl.getStyle('height').toInt();
		var oldTop = windowEl.getStyle('top').toInt();
		var oldLeft = windowEl.getStyle('left').toInt();

		var top, left;
		if (options.centered){
			top = typeof(options.top) != 'undefined' ? options.top : oldTop - ((options.height - oldHeight) * .5);
			left = typeof(options.left) != 'undefined' ? options.left : oldLeft - ((options.width - oldWidth) * .5);
		} else {
			top = typeof(options.top) != 'undefined' ? options.top : oldTop;
			left = typeof(options.left) != 'undefined' ? options.left : oldLeft;
		}

		if (MUI.options.advancedEffects){
			windowEl.retrieve('resizeMorph').start({
				'0': {
					'height': options.height,
					'width':  options.width
				},
				'1': {
					'top': top,
					'left': left
				}
			});
		} else {
			windowEl.setStyles({
				'top': top,
				'left': left
			});
			this.el.contentWrapper.setStyles({
				'height': options.height,
				'width':  options.width
			});
			this.redraw();
			// Show iframe
			if (this.el.iframe){
				if (Browser.ie) this.el.iframe.show();
				else this.el.iframe.setStyle('visibility', 'visible');
			}
		}

		return this;
	},

	hide: function(){
		this.el.windowEl.setStyle('display', 'none');
		return this;
	},

	show: function(){
		this.el.windowEl.setStyle('display', 'block');
		return this;
	},

	focus: function(fireEvent){
		if (fireEvent == null) fireEvent = true;
		MUI.Windows.focusingWindow = true; // This is used with blurAll
		(function(){
			MUI.Windows.focusingWindow = false;
		}).delay(170, this);

		// Only focus when needed
		var windowEl = this.el.windowEl;
		if ($$('.mocha').length == 0) return this;
		if (windowEl.hasClass('isFocused')) return this;

		if (this.options.type == 'notification'){
			windowEl.setStyle('zIndex', 11001);
			return this;
		}

		MUI.Windows.indexLevel += 2;
		windowEl.setStyle('zIndex', MUI.Windows.indexLevel);

		// Used when dragging and resizing windows
		$('windowUnderlay').setStyle('zIndex', MUI.Windows.indexLevel - 1).inject($(windowEl), 'after');

		// Fire onBlur for the window that lost focus.
		MUI.each(function(instance){
			if (instance.className != 'MUI.Window') return;
			if (instance.el.windowEl.hasClass('isFocused')){
				instance.fireEvent('blur', [this]);
			}
			instance.el.windowEl.removeClass('isFocused');
		});

		if (MUI.taskbar && this.options.type == 'window') MUI.taskbar.makeTabActive();
		windowEl.addClass('isFocused');

		if (fireEvent) this.fireEvent('focus', [this]);
		return this;
	},

	hideSpinner: function(){
		if (this.el.spinner) this.el.spinner.hide();
		return this;
	},

	showSpinner: function(){
		if (this.el.spinner) this.el.spinner.show();
		return this;
	},

	close: function(){
		// Does window exist and is not already in process of closing ?
		if (this.isClosing) return this;

		this.isClosing = true;
		this.fireEvent('close', [this]);

		if (this.options.storeOnClose){
			this._storeOnClose();
			return this;
		}
		if (this.check) this.check.destroy();

		if ((this.options.type == 'modal' || this.options.type == 'modal2') && Browser.ie6){
			$('modalFix').hide();
		}

		if (!MUI.options.advancedEffects){
			if ((this.options.type == 'modal' || this.options.type == 'modal2') && $$('.modal').length < 2) $('modalOverlay').setStyle('opacity', 0);
			this._doClosingJobs();
		} else {
			// Redraws IE windows without shadows since IE messes up canvas alpha when you change element opacity
			if (Browser.ie) this.redraw(false);
			if ((this.options.type == 'modal' || this.options.type == 'modal2') && $$('.modal').length < 2){
				MUI.Modal.modalOverlayCloseMorph.start({
					'opacity': 0
				});
			}
			var closeMorph = new Fx.Morph(this.el.windowEl, {
				duration: 120,
				onComplete: function(){
					this._doClosingJobs();
					return true;
				}.bind(this)
			});
			closeMorph.start({
				'opacity': .4
			});
		}
		return this;
	},

	collapseToggle: function(){
		var handles = this.el.windowEl.getElements('.handle');
		if (this.isMaximized) return this;
		if (this.isCollapsed){
			this.isCollapsed = false;
			this.redraw();
			this.el.contentBorder.setStyles({
				visibility: 'visible',
				position: null,
				top: null,
				left: null
			});
			if (this.sections){
				this.sections.each(function(section){
					if (section.position == 'content') return;
					var el = section.wrap ? section.wrapperEl : section.element;
					if (el) el.setStyles({
						visibility: 'visible',
						position: null,
						top: null,
						left: null
					});
				});
			}
			if (this.el.iframe) this.el.iframe.setStyle('visibility', 'visible');
			handles.show();
		} else {
			this.isCollapsed = true;
			handles.hide();
			if (this.el.iframe) this.el.iframe.setStyle('visibility', 'hidden');
			this.el.contentBorder.setStyles({
				visibility: 'hidden',
				position: 'absolute',
				top: -10000,
				left: -10000
			});
			if (this.sections){
				this.sections.each(function(section){
					if (section.position == 'content') return;
					var el = section.wrap ? section.wrapperEl : section.element;
					if (el) el.setStyles({
						visibility: 'hidden',
						position: 'absolute',
						top: -10000,
						left: -10000
					});
				});
			}
			this._drawWindowCollapsed();
		}
		return this;
	},

	dynamicResize: function(){
		var contentEl = this.el.content;
		this.el.contentWrapper.setStyles({
			'height': contentEl.offsetHeight,
			'width': contentEl.offsetWidth
		});
		this.redraw();
	},

	_saveValues: function(){
		var coordinates = this.el.windowEl.getCoordinates();
		this.options.x = coordinates.left.toInt();
		this.options.y = coordinates.top.toInt();
		return this;
	},

	_setupEvents: function(){
		var windowEl = this.el.windowEl;
		// Set events
		// Note: if a button does not exist, its due to properties passed to newWindow() stating otherwise
		if (this.el.closeButton) this.el.closeButton.addEvent('click', function(e){
			e.stop();
			windowEl.close();
		}.bind(this));

		if (this.options.type == 'window'){
			windowEl.addEvent('mousedown', function(e){
				if (Browser.ie) e.stop();
				this.focus();
				if (windowEl.getStyle('top').toInt() < -this.options.shadowBlur){
					windowEl.setStyle('top', -this.options.shadowBlur);
				}
			}.bind(this));
		}

		if (this.el.minimizeButton) this.el.minimizeButton.addEvent('click', function(e){
			e.stop();
			this.minimize();
		}.bind(this));

		if (this.el.maximizeButton) this.el.maximizeButton.addEvent('click', function(e){
			e.stop();
			if (this.isMaximized) this._restoreMaximized();
			else this.maximize();
		}.bind(this));

		if (this.options.collapsible){
			// Keep titlebar text from being selected on double click in Safari.
			this.el.title.addEvent('selectstart', function(e){
				e.stop();
			}.bind(this));

			if (Browser.ie){
				this.el.titleBar.addEvent('mousedown', function(){
					this.el.title.setCapture();
				}.bind(this));
				this.el.titleBar.addEvent('mouseup', function(){
					this.el.title.releaseCapture();
				}.bind(this));
			}

			this.el.titleBar.addEvent('dblclick', function(e){
				e.stop();
				this.collapseToggle();
			}.bind(this));
		}
	},

	_adjustHandles: function(){
		var shadowBlur = this.options.shadowBlur;
		var shadowBlur2x = shadowBlur * 2;
		var shadowOffset = this.options.shadowOffset;
		var top = shadowBlur - shadowOffset.y - 1;
		var right = shadowBlur + shadowOffset.x - 1;
		var bottom = shadowBlur + shadowOffset.y - 1;
		var left = shadowBlur - shadowOffset.x - 1;

		var coordinates = this.el.windowEl.getCoordinates();
		var width = coordinates.width - shadowBlur2x + 2;
		var height = coordinates.height - shadowBlur2x + 2;

		this.el.n.setStyles({
			'top': top,
			'left': left + 10,
			'width': width - 20
		});
		this.el.e.setStyles({
			'top': top + 10,
			'right': right,
			'height': height - 30
		});
		this.el.s.setStyles({
			'bottom': bottom,
			'left': left + 10,
			'width': width - 30
		});
		this.el.w.setStyles({
			'top': top + 10,
			'left': left,
			'height': height - 20
		});
		this.el.ne.setStyles({
			'top': top,
			'right': right
		});
		this.el.se.setStyles({
			'bottom': bottom,
			'right': right
		});
		this.el.sw.setStyles({
			'bottom': bottom,
			'left': left
		});
		this.el.nw.setStyles({
			'top': top,
			'left': left
		});
	},

	_setColors: function(){
		// Convert CSS colors to Canvas colors.
		if (this.options.useCanvas && !this.useCSS3){

			// Set TitlebarColor
			var pattern = /\?(.*?)\)/;
			if (this.el.titleBar.getStyle('backgroundImage') != 'none'){
				var gradient = this.el.titleBar.getStyle('backgroundImage');
				gradient = gradient.match(pattern)[1];
				gradient = gradient.parseQueryString();
				var gradientFrom = gradient.from;
				var gradientTo = gradient.to.replace(/\"/, ''); // IE7 was adding a quotation mark in. No idea why.

				this.headerStartColor = new Color(gradientFrom);
				this.headerStopColor = new Color(gradientTo);
				this.el.titleBar.addClass('replaced');
			} else if (this.el.titleBar.getStyle('background-color') !== '' && this.el.titleBar.getStyle('background-color') !== 'transparent'){
				this.headerStartColor = new Color(this.el.titleBar.getStyle('background-color')).mix('#fff', 20);
				this.headerStopColor = new Color(this.el.titleBar.getStyle('background-color')).mix('#000', 20);
				this.el.titleBar.addClass('replaced');
			}
			else
			{
				this.headerStartColor = new Color('#ff0');
				this.headerStopColor = new Color('#ff0');
				this.el.titleBar.addClass('replaced');
			}

			// Set BodyBGColor
			if (this.el.windowEl.getStyle('background-color') !== '' && this.el.windowEl.getStyle('background-color') !== 'transparent'){
				this.bodyBgColor = new Color(this.el.windowEl.getStyle('background-color'));
				this.el.windowEl.addClass('replaced');
			}
			else
			{
				this.bodyBgColor = new Color('#ff0');
				this.el.windowEl.addClass('replaced');
			}

			// Set resizableColor, the color of the SE corner resize handle
			if (this.options.resizable)
			{
				if (this.el.se.getStyle('background-color') !== '' && this.el.se.getStyle('background-color') !== 'transparent'){
					this.resizableColor = new Color(this.el.se.getStyle('background-color'));
					this.el.se.addClass('replaced');
				}
				else
				{
					this.resizableColor = new Color('#ff0');
					this.el.se.addClass('replaced');
				}
			}
		}

		if (this.options.useCanvasControls){
			if (this.el.minimizeButton){
				// Set Minimize Button Foreground Color
				if (this.el.minimizeButton.getStyle('color') !== '' && this.el.minimizeButton.getStyle('color') !== 'transparent')
				{
					this.minimizeColor = new Color(this.el.minimizeButton.getStyle('color'));
				}
				else
				{
					this.minimizeColor = new Color('#ff0');
				}

				// Set Minimize Button Background Color
				if (this.el.minimizeButton.getStyle('background-color') !== '' && this.el.minimizeButton.getStyle('background-color') !== 'transparent'){
					this.minimizeBgColor = new Color(this.el.minimizeButton.getStyle('background-color'));
					this.el.minimizeButton.addClass('replaced');
				}
				else
				{
					this.minimizeBgColor = new Color('#ff0');
					this.el.minimizeButton.addClass('replaced');
				}
			}

			if (this.el.maximizeButton){
				// Set Maximize Button Foreground Color
				if (this.el.maximizeButton.getStyle('color') !== '' && this.el.maximizeButton.getStyle('color') !== 'transparent')
				{
					this.maximizeColor = new Color(this.el.maximizeButton.getStyle('color'));
				}
				else
				{
					this.maximizeColor = new Color('#ff0');
					this.el.minimizeButton.addClass('replaced');
				}

				// Set Maximize Button Background Color
				if (this.el.maximizeButton.getStyle('background-color') !== '' && this.el.maximizeButton.getStyle('background-color') !== 'transparent'){
					this.maximizeBgColor = new Color(this.el.maximizeButton.getStyle('background-color'));
					this.el.maximizeButton.addClass('replaced');
				}
				else
				{
					this.maximizeBgColor = new Color('#ff0');
					this.el.maximizeButton.addClass('replaced');
				}
			}

			if (this.el.closeButton){
				// Set Close Button Foreground Color
				if (this.el.closeButton.getStyle('color') !== '' && this.el.closeButton.getStyle('color') !== 'transparent')
				{
					this.closeColor = new Color(this.el.closeButton.getStyle('color'));
				}
				else
				{
					this.closeColor = new Color('#ff0');
				}

				// Set Close Button Background Color
				if (this.el.closeButton.getStyle('background-color') !== '' && this.el.closeButton.getStyle('background-color') !== 'transparent'){
					this.closeBgColor = new Color(this.el.closeButton.getStyle('background-color'));
					this.el.closeButton.addClass('replaced');
				}
				else
				{
					this.closeBgColor = new Color('#ff0');
					this.el.closeButton.addClass('replaced');
				}
			}
		}
	},

	_setMochaControlsWidth: function(){
		this.mochaControlsWidth = 0;
		var options = this.options;
		if (options.minimizable){
			this.mochaControlsWidth += (this.el.minimizeButton.getStyle('margin-left').toInt() + this.el.minimizeButton.getStyle('width').toInt());
		}
		if (options.maximizable){
			this.mochaControlsWidth += (this.el.maximizeButton.getStyle('margin-left').toInt() + this.el.maximizeButton.getStyle('width').toInt());
		}
		if (options.closable){
			this.mochaControlsWidth += (this.el.closeButton.getStyle('margin-left').toInt() + this.el.closeButton.getStyle('width').toInt());
		}
		this.el.controls.setStyle('width', this.mochaControlsWidth);
		if (options.useCanvasControls){
			this.el.canvasControls.setProperty('width', this.mochaControlsWidth);
		}
	},

	_insertWindowElements: function(){
		var options = this.options;
		var height = options.height;
		var width = options.width;
		var id = options.id;

		var cache = {};

		if (Browser.ie6){
			cache.zIndexFix = new Element('iframe', {
				'id': id + '_zIndexFix',
				'class': 'zIndexFix',
				'scrolling': 'no',
				'marginWidth': 0,
				'marginHeight': 0,
				'src': '',
				'styles': {
					'position': 'absolute' // This is set here to make theme transitions smoother
				}
			}).inject(this.el.windowEl);
		}

		cache.overlay = new Element('div', {
			'id': id + '_overlay',
			'class': 'mochaOverlay',
			'styles': {
				'position': 'absolute', // This is set here to make theme transitions smoother
				'top': 0,
				'left': 0
			}
		}).inject(this.el.windowEl);

		cache.titleBar = new Element('div', {
			'id': id + '_titleBar',
			'class': 'mochaTitlebar',
			'styles': {
				'cursor': options.draggable ? 'move' : 'default'
			}
		}).inject(cache.overlay, 'top');

		cache.title = new Element('h3', {
			'id': id + '_title',
			'class': 'mochaTitle'
		}).inject(cache.titleBar);

		if (options.icon != false){
			cache.title.setStyles({
				'padding-left': 28,
				'background': 'url(' + options.icon + ') 5px 4px no-repeat'
			});
		}

		cache.contentBorder = new Element('div', {
			'id': id + '_contentBorder',
			'class': 'mochaContentBorder'
		}).inject(cache.overlay);

		cache.contentWrapper = new Element('div', {
			'id': id + '_contentWrapper',
			'class': 'mochaContentWrapper',
			'styles': {
				'width': width + 'px',
				'height': height + 'px'
			}
		}).inject(cache.contentBorder);

		if (this.options.shape == 'gauge'){
			cache.contentBorder.setStyle('borderWidth', 0);
		}

		cache.content = new Element('div', {
			'id': id + '_content',
			'class': 'mochaContent'
		}).inject(cache.contentWrapper);

		if (this.options.useCanvas && !this.useCSS3){
			if (!Browser.ie){
				cache.canvas = new Element('canvas', {
					'id': id + '_canvas',
					'class': 'mochaCanvas',
					'width': 10,
					'height': 10
				}).inject(this.el.windowEl);
			} else if (Browser.ie){
				cache.canvas = new Element('canvas', {
					'id': id + '_canvas',
					'class': 'mochaCanvas',
					'width': 50000, // IE8 excanvas requires these large numbers
					'height': 20000,
					'styles': {
						'position': 'absolute',
						'top': 0,
						'left': 0
					}
				}).inject(this.el.windowEl);

				if (MUI.ieSupport == 'excanvas'){
					G_vmlCanvasManager.initElement(cache.canvas);
					cache.canvas = this.el.windowEl.getElement('.mochaCanvas');
				}
			}
		}

		cache.controls = new Element('div', {
			'id': id + '_controls',
			'class': 'mochaControls'
		}).inject(cache.overlay, 'after');

		cache.footer = new Element('div', {
			'id': id + '_footer',
			'class': 'mochaWindowFooter',
			'styles': {'width': width - 30}
		}).inject(cache.overlay, 'bottom');

		// make sure we have a content sections
		this.sections = [];

		switch (typeOf(options.content)){
			case 'string':
				// was passed html, so make sure it is added
				this.sections.push({
					loadMethod:'html',
					content:options.content
				});
				break;
			case 'array':
				this.sections = options.content;
				break;
			default:
				this.sections.push(options.content);
		}

		var snum = 0;
		this.sections.each(function(section, idx){
			var intoEl = cache.contentBorder;

			section.element = this.el.windowEl;
			snum++;
			var id = this.options.id + '_' + (section.section || 'section' + snum);

			section = Object.append({
				'wrap': true,
				'position': 'content',
				'empty': false,
				'height': 29,
				'id': id,
				'css': null,
				'loadMethod': 'xhr',
				'method': 'get'
			}, section);

			if (section.position == 'content'){
				if (section.loadMethod == 'iframe') this.options.padding = 0;  // Iframes have their own padding.
				section.element = cache.content;
				this.sections[idx] = section;
				return;
			}

			var wrap = section.wrap;
			var where = section.position == 'bottom' ? 'after' : 'before';
			var empty = section.empty;
			if (section.position == 'header' || section.position == 'footer'){
				if (!section.css) section.css = 'mochaToolbar';
				intoEl = section.position == 'header' ? cache.titleBar : cache.footer;
				where = 'bottom';
				wrap = false;
			} else empty = false; // can't empty in content border area

			if (wrap){
				section.wrapperEl = new Element('div', {
					'id': section.id + '_wrapper',
					'class': section.css + 'Wrapper',
					'styles': {'height': section.height}
				}).inject(intoEl, where);

				if (section.position == 'bottom') section.wrapperEl.addClass('bottom');
				intoEl = section.wrapperEl;
				cache[section.wrapperEl.id] = intoEl;
			}

			if (empty) intoEl.empty();
			section.element = new Element('div', {
				'id': section.id,
				'class': section.css,
				'styles': {'height': section.height}
			}).inject(intoEl);

			section.wrapperEl = intoEl;
			if (section.wrap && section.position == 'bottom') section.element.addClass('bottom');

			this.sections[idx] = section;
			cache[section.element.id]=section.element;
		}, this);

		if (options.useCanvasControls){
			cache.canvasControls = new Element('canvas', {
				'id': id + '_canvasControls',
				'class': 'mochaCanvasControls',
				'width': 14,
				'height': 14
			}).inject(this.el.windowEl);

			if (Browser.ie && MUI.ieSupport == 'excanvas'){
				G_vmlCanvasManager.initElement(cache.canvasControls);
				cache.canvasControls = this.el.windowEl.getElement('.mochaCanvasControls');
			}
		}

		if (options.closable){
			cache.closeButton = new Element('div', {
				'id': id + '_closeButton',
				'class': 'mochaCloseButton mochaWindowButton',
				'title': 'Close'
			}).inject(cache.controls);
		}

		if (options.maximizable){
			cache.maximizeButton = new Element('div', {
				'id': id + '_drawMaximizeButton',
				'class': 'mochaMaximizeButton mochaWindowButton',
				'title': 'Maximize'
			}).inject(cache.controls);
		}

		if (options.minimizable){
			cache.minimizeButton = new Element('div', {
				'id': id + '_minimizeButton',
				'class': 'mochaMinimizeButton mochaWindowButton',
				'title': 'Minimize'
			}).inject(cache.controls);
		}

		if (options.useSpinner && options.shape != 'gauge' && options.type != 'notification'){
			cache.spinner = new Element('div', {
				'id': id + '_spinner',
				'class': 'spinner',
				'styles': {
					'width': 16,
					'height': 16
				}
			}).inject(cache.footer, 'bottom');
		}

		if (this.options.shape == 'gauge'){
			cache.canvasHeader = new Element('canvas', {
				'id': id + '_canvasHeader',
				'class': 'mochaCanvasHeader',
				'width': this.options.width,
				'height': 26
			}).inject(this.el.windowEl, 'bottom');

			if (Browser.ie && MUI.ieSupport == 'excanvas'){
				G_vmlCanvasManager.initElement(cache.canvasHeader);
				cache.canvasHeader = this.el.windowEl.getElement('.mochaCanvasHeader');
			}
		}

		if (Browser.ie) cache.overlay.setStyle('zIndex', 2);

		// For Mac Firefox 2 to help reduce scrollbar bugs in that browser
		if (Browser.Platform.mac && Browser.firefox && Browser.version < 3){
			cache.overlay.setStyle('overflow', 'auto');
		}

		if (options.resizable){
			cache.n = new Element('div', {
				'id': id + '_resizeHandle_n',
				'class': 'handle',
				'styles': {
					'top': 0,
					'left': 10,
					'cursor': 'n-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.ne = new Element('div', {
				'id': id + '_resizeHandle_ne',
				'class': 'handle corner',
				'styles': {
					'top': 0,
					'right': 0,
					'cursor': 'ne-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.e = new Element('div', {
				'id': id + '_resizeHandle_e',
				'class': 'handle',
				'styles': {
					'top': 10,
					'right': 0,
					'cursor': 'e-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.se = new Element('div', {
				'id': id + '_resizeHandle_se',
				'class': 'handle cornerSE',
				'styles': {
					'bottom': 0,
					'right': 0,
					'cursor': 'se-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.s = new Element('div', {
				'id': id + '_resizeHandle_s',
				'class': 'handle',
				'styles': {
					'bottom': 0,
					'left': 10,
					'cursor': 's-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.sw = new Element('div', {
				'id': id + '_resizeHandle_sw',
				'class': 'handle corner',
				'styles': {
					'bottom': 0,
					'left': 0,
					'cursor': 'sw-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.w = new Element('div', {
				'id': id + '_resizeHandle_w',
				'class': 'handle',
				'styles': {
					'top': 10,
					'left': 0,
					'cursor': 'w-resize'
				}
			}).inject(cache.overlay, 'after');

			cache.nw = new Element('div', {
				'id': id + '_resizeHandle_nw',
				'class': 'handle corner',
				'styles': {
					'top': 0,
					'left': 0,
					'cursor': 'nw-resize'
				}
			}).inject(cache.overlay, 'after');
		}
		Object.append(this.el, cache);

	},

	_showNewWindow: function(){
		var options = this.options;
		if (options.type == 'modal' || options.type == 'modal2'){
			MUI.currentModal = this.el.windowEl;
			if (Browser.ie6) $('modalFix').show();
			$('modalOverlay').show();
			if (MUI.options.advancedEffects){
				MUI.Modal.modalOverlayCloseMorph.cancel();
				MUI.Modal.modalOverlayOpenMorph.start({
					'opacity': .6
				});
				this.el.windowEl.setStyle('zIndex', 11000);
				this.opacityMorph.start({
					'opacity': 1
				});
			} else {
				$('modalOverlay').setStyle('opacity', .6);
				this.el.windowEl.setStyles({
					'zIndex': 11000,
					'opacity': 1
				});
			}

			$$('.taskbarTab').removeClass('activetaskbarTab');
			$$('.mocha').removeClass('isFocused');
			this.el.windowEl.addClass('isFocused');

		} else if (MUI.options.advancedEffects){
			// IE cannot handle both element opacity and VML alpha at the same time.
			if (Browser.ie) this.redraw(false);
			this.opacityMorph.start({'opacity': 1});
			this.focus.delay(10, this);
		} else {
			this.el.windowEl.setStyle('opacity', 1);
			this.focus.delay(10, this);
		}
	},

	_getAllSectionsHeight: function(){
		// Get the total height of all of the custom sections in the content area.
		var height = 0;
		if (this.sections){
			this.sections.each(function(section){
				if (section.position == 'content') return;
				height += section.wrapperEl.getStyle('height').toInt() + section.wrapperEl.getStyle('border-top').toInt();
			});
		}
		return height;
	},

	_css3SetStyles: function(){
		var options = this.options;
		var color = Asset.getCSSRule('.mochaCss3Shadow').style.backgroundColor;
		['', '-o-', '-webkit-', '-moz-'].each(function(pre){
			this.el.windowEl.setStyle(pre + 'box-shadow', options.shadowOffset.x + 'px ' + options.shadowOffset.y + 'px ' + options.shadowBlur + 'px ' + color);
			this.el.windowEl.setStyle(pre + 'border-radius', options.cornerRadius + 'px');
			this.el.titleBar.setStyle(pre + 'border-radius', options.cornerRadius + 'px');
		}, this);
	},

	_attachDraggable: function(){
		var windowEl = this.el.windowEl;
		if (!this.options.draggable) return;
		this.windowDrag = new Drag.Move(windowEl, {
			handle: this.el.titleBar,
			container: this.options.container ? $(this.options.container) : false,
			grid: this.options.draggableGrid,
			limit: this.options.draggableLimit,
			snap: this.options.draggableSnap,
			onStart: function(){
				if (this.options.type != 'modal' && this.options.type != 'modal2'){
					this.focus();
					$('windowUnderlay').show();
				}
				if (this.el.iframe){
					if (!Browser.ie) this.el.iframe.setStyle('visibility', 'hidden');
					else this.el.iframe.hide();
				}
				this.fireEvent('dragStart', [this]);
			}.bind(this),
			onComplete: function(){
				if (this.options.type != 'modal' && this.options.type != 'modal2')
					$('windowUnderlay').hide();

				if (this.el.iframe){
					if (!Browser.ie) this.el.iframe.setStyle('visibility', 'visible');
					else this.el.iframe.show();
				}
				// Store new position in options.
				this._saveValues();
				this.fireEvent('dragComplete', [this]);
			}.bind(this)
		});
	},

	_attachResizable: function(){
		if (!this.options.resizable) return;
		this.resizable1 = this.el.windowEl.makeResizable({
			handle: [this.el.n, this.el.ne, this.el.nw],
			limit: {
				y: [
					function(){
						return this.el.windowEl.getStyle('top').toInt() + this.el.windowEl.getStyle('height').toInt() - this.options.resizeLimit.y[1];
					}.bind(this),
					function(){
						return this.el.windowEl.getStyle('top').toInt() + this.el.windowEl.getStyle('height').toInt() - this.options.resizeLimit.y[0];
					}.bind(this)
				]
			},
			modifiers: {x: false, y: 'top'},
			onStart: function(){
				this._resizeOnStart();
				this.coords = this.el.contentWrapper.getCoordinates();
				this.y2 = this.coords.top.toInt() + this.el.contentWrapper.offsetHeight;
			}.bind(this),
			onDrag: function(){
				this.coords = this.el.contentWrapper.getCoordinates();
				this.el.contentWrapper.setStyle('height', this.y2 - this.coords.top.toInt());
				this._resizeOnDrag();
			}.bind(this),
			onComplete: function(){
				this._resizeOnComplete();
			}.bind(this)
		});

		this.resizable2 = this.el.contentWrapper.makeResizable({
			handle: [this.el.e, this.el.ne],
			limit: {
				x: [this.options.resizeLimit.x[0] - (this.options.shadowBlur * 2), this.options.resizeLimit.x[1] - (this.options.shadowBlur * 2) ]
			},
			modifiers: {x: 'width', y: false},
			onStart: function(){
				this._resizeOnStart();
			}.bind(this),
			onDrag: function(){
				this._resizeOnDrag();
			}.bind(this),
			onComplete: function(){
				this._resizeOnComplete();
			}.bind(this)
		});

		this.resizable3 = this.el.contentWrapper.makeResizable({
			container: this.options.container ? $(this.options.container) : false,
			handle: this.el.se,
			limit: {
				x: [this.options.resizeLimit.x[0] - (this.options.shadowBlur * 2), this.options.resizeLimit.x[1] - (this.options.shadowBlur * 2) ],
				y: [this.options.resizeLimit.y[0] - this.headerFooterShadow, this.options.resizeLimit.y[1] - this.headerFooterShadow]
			},
			modifiers: {x: 'width', y: 'height'},
			onStart: function(){
				this._resizeOnStart();
			}.bind(this),
			onDrag: function(){
				this._resizeOnDrag();
			}.bind(this),
			onComplete: function(){
				this._resizeOnComplete();
			}.bind(this)
		});

		this.resizable4 = this.el.contentWrapper.makeResizable({
			handle: [this.el.s, this.el.sw],
			limit: {
				y: [this.options.resizeLimit.y[0] - this.headerFooterShadow, this.options.resizeLimit.y[1] - this.headerFooterShadow]
			},
			modifiers: {x: false, y: 'height'},
			onStart: function(){
				this._resizeOnStart();
			}.bind(this),
			onDrag: function(){
				this._resizeOnDrag();
			}.bind(this),
			onComplete: function(){
				this._resizeOnComplete();
			}.bind(this)
		});

		this.resizable5 = this.el.windowEl.makeResizable({
			handle: [this.el.w, this.el.sw, this.el.nw],
			limit: {
				x: [
					function(){
						return this.el.windowEl.getStyle('left').toInt() + this.el.windowEl.getStyle('width').toInt() - this.options.resizeLimit.x[1];
					}.bind(this),
					function(){
						return this.el.windowEl.getStyle('left').toInt() + this.el.windowEl.getStyle('width').toInt() - this.options.resizeLimit.x[0];
					}.bind(this)
				]
			},
			modifiers: {x: 'left', y: false},
			onStart: function(){
				this._resizeOnStart();
				this.coords = this.el.contentWrapper.getCoordinates();
				this.x2 = this.coords.left.toInt() + this.el.contentWrapper.offsetWidth;
			}.bind(this),
			onDrag: function(){
				this.coords = this.el.contentWrapper.getCoordinates();
				this.el.contentWrapper.setStyle('width', this.x2 - this.coords.left.toInt());
				this._resizeOnDrag();
			}.bind(this),
			onComplete: function(){
				this._resizeOnComplete();
			}.bind(this)
		});
	},

	_resizeOnStart: function(){
		$('windowUnderlay').show();
		if (this.el.iframe){
			if (Browser.ie) this.el.iframe.hide();
			else this.el.iframe.setStyle('visibility', 'hidden');
		}
	},

	_resizeOnDrag: function(){
		// Fix for a rendering glitch in FF when resizing a window with panels in it
		if (Browser.firefox){
			this.el.windowEl.getElements('.panel').each(function(panel){
				panel.store('oldOverflow', panel.getStyle('overflow'));
				panel.setStyle('overflow', 'visible');
			});
		}
		this.redraw();
		this._adjustHandles();
		if (Browser.firefox){
			this.el.windowEl.getElements('.panel').each(function(panel){
				panel.setStyle('overflow', panel.retrieve('oldOverflow')); // Fix for a rendering bug in FF
			});
		}
	},

	_resizeOnComplete: function(){
		$('windowUnderlay').hide();
		if (this.el.iframe){
			if (Browser.ie){
				this.el.iframe.show();
				// The following hack is to get IE8 RC1 IE8 Standards Mode to properly resize an iframe
				// when only the vertical dimension is changed.
				this.el.iframe.setStyle('width', '99%');
				this.el.iframe.setStyle('height', this.el.contentWrapper.offsetHeight);
				this.el.iframe.setStyle('width', '100%');
				this.el.iframe.setStyle('height', this.el.contentWrapper.offsetHeight);
			} else this.el.iframe.setStyle('visibility', 'visible');
		}

		// Resize panels if there are any
		var columns = this.el.contentWrapper.getChildren('.column');
		if (columns != null && columns.length>0){
			MUI.rWidth(this.el.contentWrapper);
			columns.each(function(column){
				MUI.panelHeight(column);
			});
		}

		this.fireEvent('resize', [this]);
	},

	_detachResizable: function(){
		this.resizable1.detach();
		this.resizable2.detach();
		this.resizable3.detach();
		this.resizable4.detach();
		this.resizable5.detach();
		this.el.windowEl.getElements('.handle').hide();
	},

	_reattachResizable: function(){
		this.resizable1.attach();
		this.resizable2.attach();
		this.resizable3.attach();
		this.resizable4.attach();
		this.resizable5.attach();
		this.el.windowEl.getElements('.handle').show();
	},

	_drawWindowCollapsed: function(shadows){
		var options = this.options;
		var shadowBlur = this.useCSS3 ? 0 : options.shadowBlur;
		var shadowBlur2x = this.useCSS3 ? 0 : shadowBlur * 2;
		var shadowOffset = this.useCSS3 ? 0 : options.shadowOffset;

		var height = options.headerHeight + shadowBlur2x + 2;
		var width = this.el.contentWrapper.getStyle('width').toInt() + shadowBlur2x;
		this.el.windowEl.setStyle('height', height);

		// Set width
		this.el.windowEl.setStyle('width', width);
		this.el.overlay.setStyle('width', width);
		this.el.titleBar.setStyles({
			'width': width - shadowBlur2x,
			'height': options.headerHeight
		});

		if (this.useCSS3) this._css3SetStyles();
		else {
			this.el.overlay.setStyles({
				'height': height,
				'top': shadowBlur - shadowOffset.y,
				'left': shadowBlur - shadowOffset.x
			});

			// Part of the fix for IE6 select z-index bug
			if (Browser.ie6) this.el.zIndexFix.setStyles({
				'width': width,
				'height': height
			});

			// Draw Window
			if (this.options.useCanvas){
				this.el.canvas.height = height;
				this.el.canvas.width = width;

				var ctx = this.el.canvas.getContext('2d');
				ctx.clearRect(0, 0, width, height);

				MUI.Canvas.drawBoxCollapsed(ctx, width, height, shadowBlur, shadowOffset, shadows, this.options.cornerRadius, this.headerStartColor, this.headerStopColor);
				if (options.useCanvasControls) this._drawControls(width, height, shadows);

				// Invisible dummy object. The last element drawn is not rendered consistently while resizing in IE6 and IE7
				if (Browser.ie) MUI.Canvas.triangle(ctx, 0, 0, 10, 10, [0, 0, 0], 0);
			}
		}

		this.drawingWindow = false;
		return this;

	},

	_drawControls : function(){
		var options = this.options;
		var shadowBlur = this.useCSS3 ? 0 : options.shadowBlur;
		var shadowOffset = this.useCSS3 ? 0 : options.shadowOffset;
		var controlsOffset = options.controlsOffset;

		// Make sure controls are placed correctly.
		this.el.controls.setStyles({
			'right': shadowBlur + shadowOffset.x + controlsOffset.right,
			'top': shadowBlur - shadowOffset.y + controlsOffset.top
		});

		this.el.canvasControls.setStyles({
			'right': shadowBlur + shadowOffset.x + controlsOffset.right,
			'top': shadowBlur - shadowOffset.y + controlsOffset.top
		});

		// Calculate X position for controlbuttons
		//var mochaControlsWidth = 52;
		this.closeButtonX = options.closable ? this.mochaControlsWidth - 7 : this.mochaControlsWidth + 12;
		this.maximizeButtonX = this.closeButtonX - (options.maximizable ? 19 : 0);
		this.minimizeButtonX = this.maximizeButtonX - (options.minimizable ? 19 : 0);

		var ctx2 = this.el.canvasControls.getContext('2d');
		ctx2.clearRect(0, 0, 100, 100);

		if (this.options.closable) MUI.Canvas.drawCloseButton(ctx2, this.closeButtonX, 7, this.closeBgColor, 1.0, this.closeColor, 1.0);
		if (this.options.maximizable) MUI.Canvas.drawMaximizeButton(ctx2, this.maximizeButtonX, 7, this.maximizeBgColor, 1.0, this.maximizeColor, 1.0);
		if (this.options.minimizable){
			MUI.Canvas.drawMinimizeButton(ctx2, this.minimizeButtonX, 7, this.minimizeBgColor, 1.0, this.minimizeColor, 1.0);

			// Invisible dummy object. The last element drawn is not rendered consistently while resizing in IE6 and IE7
			if (Browser.ie){
				MUI.Canvas.circle(ctx2, 0, 0, 3, this.minimizeBgColor, 0);
			}
		}
	},

	_doClosingJobs: function(){
		var windowEl = this.el.windowEl;
		windowEl.setStyle('visibility', 'hidden');
		// Destroy throws an error in IE8
		if (Browser.ie) windowEl.dispose();
		else windowEl.destroy();
		this.fireEvent('closeComplete', [this]);

		if (this.options.type != 'notification'){
			var newFocus = MUI.Windows._getWithHighestZIndex();
			this.focus(newFocus);
		}

		MUI.erase(this.options.id);
		if (!MUI.Desktop) return;
		if (MUI.Desktop.loadingWorkspace) MUI.Desktop.loadingCallChain();

		if (MUI.taskbar && this.options.type == 'window'){
			var currentButton = $(this.options.id + '_taskbarTab');
			if (currentButton) MUI.taskbar.sortables.removeItems(currentButton).destroy();
			// Need to resize everything in case the taskbar becomes smaller when a tab is removed
			MUI.Desktop.setDesktopSize();
		}
	},

	_storeOnClose: function(){
		if (this.el.check) this.el.check.hide();

		var windowEl = this.el.windowEl;
		windowEl.setStyle('zIndex', -1);
		windowEl.addClass('windowClosed');
		windowEl.removeClass('mocha');

		if (MUI.taskbar && this.options.type == 'window'){
			var currentButton = $(this.options.id + '_taskbarTab');
			if (currentButton) currentButton.hide();
			MUI.Desktop.setDesktopSize();
		}

		this.fireEvent('closeComplete', [this]);

		if (this.options.type != 'notification'){
			var newFocus = MUI.Windows._getWithHighestZIndex();
			this.focus(newFocus);
		}

		this.isClosing = false;
	}

}).implement(MUI.WindowPanelShared);

Element.implement({

	minimize: function(){
		var instance = MUI.get(this.id);
		if (instance == null || instance.minimize == null) return this;
		instance.minimize();
		return this;
	},

	maximize: function(){
		var instance = MUI.get(this.id);
		if (instance == null || instance.maximize == null) return this;
		instance.maximize();
		return this;
	},

	restore: function(){
		var instance = MUI.get(this.id);
		if (instance == null || instance.restore == null) return this;
		instance.restore();
		return this;
	},

	center: function(){
		var instance = MUI.get(this.id);
		if (instance == null || instance.center == null) return this;
		instance.center();
		return this;
	}

});

document.addEvents({
	'keydown': function(event){  // Toggle window visibility with Ctrl-Alt-Q
		if (event.key == 'q' && event.control && event.alt) MUI.Windows.toggleAll();
	},

	'mousedown': function(){  // Blur all windows if user clicks anywhere else on the page
		MUI.Windows.blurAll.delay(50);
	}
});

window.addEvents({
	'domready': function(){
		MUI.Windows._underlayInitialize();
	},

	'resize': function(){
		if ($('windowUnderlay')) MUI.Windows._setUnderlaySize();
		else MUI.Windows._underlayInitialize();
	}
});


/*
 ---

 script: Modal.js

 description: Create modal dialog windows.

 copyright: (c) 2010 Contributors in (/AUTHORS.txt).

 license: MIT-style license in (/MIT-LICENSE.txt).

 See Also: <Window>

 requires:
 - MochaUI/MUI
 - MochaUI/MUI.Windows

 provides: [MUI.Modal]

 ...
 */

MUI.files['{controls}window/modal.js'] = 'loaded';

MUI.Modal = new NamedClass('MUI.Modal', {

	Extends: MUI.Window,

	options: {
		type: 'modal'
	},

	initialize: function(options){
		if(!options.type) options.type='modal';

		if (!$('modalOverlay')){
			this._modalInitialize();
			window.addEvent('resize', function(){
				this._setModalSize();
			}.bind(this));
		}
		this.parent(options);
	},

	_modalInitialize: function(){
		var modalOverlay = new Element('div', {
			'id': 'modalOverlay',
			'styles': {
				'height': document.getCoordinates().height,
				'opacity': .6
			}
		}).inject(document.body);

		modalOverlay.setStyles({
			'position': Browser.ie6 ? 'absolute' : 'fixed'
		});

		modalOverlay.addEvent('click', function(){
			var instance = MUI.get(MUI.currentModal.id);
			if (instance.options.modalOverlayClose) MUI.currentModal.close();
		});
		
		if (Browser.ie6){
			var modalFix = new Element('iframe', {
				'id': 'modalFix',
				'scrolling': 'no',
				'marginWidth': 0,
				'marginHeight': 0,
				'src': '',
				'styles': {
					'height': document.getCoordinates().height
				}
			}).inject(document.body);
		}

		MUI.Modal.modalOverlayOpenMorph = new Fx.Morph($('modalOverlay'), {
			'duration': 150
		});
		MUI.Modal.modalOverlayCloseMorph = new Fx.Morph($('modalOverlay'), {
			'duration': 150,
			onComplete: function(){
				$('modalOverlay').hide();
				if (Browser.ie6){
					$('modalFix').hide();
				}
			}.bind(this)
		});
	},

	_setModalSize: function(){
		$('modalOverlay').setStyle('height', document.getCoordinates().height);
		if (Browser.ie6) $('modalFix').setStyle('height', document.getCoordinates().height);
	}

});

