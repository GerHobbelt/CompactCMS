/******
 *
 *	EditArea
 * 	Developped by Christophe Dolivet
 *	Released under LGPL, Apache and BSD licenses (use the one you want)
 *
******/

function EditAreaLoader(){
	var t=this;
	t.version= "0.8.2";
	date= new Date();
	t.start_time=date.getTime();
	t.win= "loading";	// window loading state
	t.error= false;	// to know if load is interrrupt
	t.baseURL="";
	//t.suffix="";
	t.template="";
	t.lang= {};	// array of loaded speech language
	t.load_syntax= {};	// array of loaded syntax language for highlight mode
	t.syntax= {};	// array of initilized syntax language for highlight mode
	t.loadedFiles= [];
	t.waiting_loading= {}; 	// files that must be loaded in order to allow the script to really start
	// scripts that must be loaded in the iframe
	t.scripts_to_load= [];
	t.sub_scripts_to_load= [];
	t.syntax_display_name= { 'css':'CSS','html':'HTML','js':'Javascript','js':'Javascript','js2':'Javascript','php':'Php','robotstxt':'Robots txt','sql':'SQL','xml':'XML' };

	t.resize= []; // contain resizing datas
	t.hidden= []; // store datas of the hidden textareas
	// ^^^^ fix for http://sourceforge.net/tracker/?func=detail&aid=3196666&group_id=164008&atid=829997

	t.default_settings= {
		//id: "src"	// id of the textarea to transform
		debug: false
		,smooth_selection: true
		,font_size: "10"		// not for IE
		,font_family: "monospace"	// can be "verdana,monospace". Allow non monospace font but Firefox get smaller tabulation with non monospace fonts. IE doesn't change the tabulation width and Opera doesn't take this option into account...
		,start_highlight: false	// if start with highlight
		,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font,|, change_smooth_selection, highlight, reset_highlight, word_wrap, |, help"
		,begin_toolbar: ""		//  "new_document, save, load, |"
		,end_toolbar: ""		// or end_toolbar
		,is_multi_files: false		// enable the multi file mode (the textarea content is ignored)
		,allow_resize: "both"	// possible values: "no", "both", "x", "y"
		,show_line_colors: false	// if the highlight is disabled for the line currently beeing edited (if enabled => heavy CPU use)
		,min_width: 400
		,min_height: 125
		,replace_tab_by_spaces: false
		,allow_toggle: true		// true or false
		,language: "en"
		,syntax: ""
		,syntax_selection_allow: "css,html,js,js,js,php,robotstxt,sql,xml"
		,ignore_unsupported_syntax: false
		,display: "onload" 		// onload or later
		,max_undo: 30
		,browsers: "known"	// all or known
		,plugins: "" // comma separated plugin list
		,gecko_spellcheck: false	// enable/disable by default the gecko_spellcheck
		,fullscreen: false
		,is_editable: true
		,cursor_position: "begin"
		,word_wrap: false		// define if the text is wrapped of not in the textarea
		,autocompletion: false	// NOT IMPLEMENTED
		,load_callback: ""		// click on load button (function name)
		,save_callback: ""		// click on save button (function name)
		,change_callback: ""	// textarea onchange trigger (function name)
		,submit_callback: ""	// form submited (function name)
		,EA_init_callback: ""	// EditArea initialized (function name)
		,EA_delete_callback: ""	// EditArea deleted (function name)
		,EA_load_callback: ""	// EditArea fully loaded and displayed (function name)
		,EA_unload_callback: ""	// EditArea delete while being displayed (function name)
		,EA_toggle_on_callback: ""	// EditArea toggled on (function name)
		,EA_toggle_off_callback: ""	// EditArea toggled off (function name)
		,EA_file_switch_on_callback: ""	// a new tab is selected (called for the newly selected file)
		,EA_file_switch_off_callback: ""	// a new tab is selected (called for the previously selected file)
		,EA_file_close_callback: ""		// close a tab
	};

	t.advanced_buttons = [
			// id, button img, command (it will try to find the translation of "id"), is_file_specific
			['new_document', 'newdocument.gif', 'new_document', false],
			['search', 'search.gif', 'show_search', false],
			['go_to_line', 'go_to_line.gif', 'go_to_line', false],
			['undo', 'undo.gif', 'undo', true],
			['redo', 'redo.gif', 'redo', true],
			['change_smooth_selection', 'smooth_selection.gif', 'change_smooth_selection_mode', true],
			['reset_highlight', 'reset_highlight.gif', 'resync_highlight', true],
			['highlight', 'highlight.gif','change_highlight', true],
			['help', 'help.gif', 'show_help', false],
			['save', 'save.gif', 'save', false],
			['load', 'load.gif', 'load', false],
			['fullscreen', 'fullscreen.gif', 'toggle_full_screen', false],
			['word_wrap', 'word_wrap.gif', 'toggle_word_wrap', true],
			['autocompletion', 'autocompletion.gif', 'toggle_autocompletion', true]
		];

	// navigator identification
	t.set_browser_infos(t);

	if(t.isIE>=6 || t.isGecko || ( t.isWebKit && !t.isSafari<3 ) || t.isOpera>=9  || t.isCamino )
		t.isValidBrowser=true;
	else
		t.isValidBrowser=false;

	t.set_base_url();

	for(var i=0; i<t.scripts_to_load.length; i++){
		setTimeout("editAreaLoader.load_script('"+t.baseURL + t.scripts_to_load[i]+ ".js');", 1);	// let the time to Object editAreaLoader to be created before loading additionnal scripts
		t.waiting_loading[t.scripts_to_load[i]+ ".js"]= false;
	}
	t.add_event(window, "load", EditAreaLoader.prototype.window_loaded);
};

EditAreaLoader.prototype ={
	has_error : function(errcode){
		this.error= true;
		this.error_code = errcode; // [i_a] 1..6
		// set to empty all EditAreaLoader functions
		for(var i in EditAreaLoader.prototype){
			EditAreaLoader.prototype[i]=function(){};
		}
	},

	// add browser informations to the object passed in parameter
	set_browser_infos : function(o){
		ua= navigator.userAgent;

		// general detection
		o.isWebKit	= /WebKit/.test(ua);
		o.isGecko	= !o.isWebKit && /Gecko/.test(ua);
		o.isMac		= /Mac/.test(ua);

		o.isIE	= (navigator.appName == "Microsoft Internet Explorer");
		if(o.isIE){
			o.isIE = ua.replace(/^.*?MSIE\s+([0-9\.]+).*$/, "$1");
			if(o.isIE<6)
				o.has_error(1);
		}

		if(o.isOpera = (ua.indexOf('Opera') != -1)){
			o.isOpera= ua.replace(/^.*?Opera.*?([0-9\.]+).*$/i, "$1");
			if(o.isOpera<9)
				o.has_error(2);
			o.isIE=false;
		}

		if(o.isFirefox =(ua.indexOf('Firefox') != -1))
			o.isFirefox = ua.replace(/^.*?Firefox.*?([0-9\.]+).*$/i, "$1");
		// Firefox clones
		if( ua.indexOf('Iceweasel') != -1 )
			o.isFirefox	= ua.replace(/^.*?Iceweasel.*?([0-9\.]+).*$/i, "$1");
		if( ua.indexOf('GranParadiso') != -1 )
			o.isFirefox	= ua.replace(/^.*?GranParadiso.*?([0-9\.]+).*$/i, "$1");
		if( ua.indexOf('BonEcho') != -1 )
			o.isFirefox	= ua.replace(/^.*?BonEcho.*?([0-9\.]+).*$/i, "$1");
		if( ua.indexOf('SeaMonkey') != -1)
			o.isFirefox = (ua.replace(/^.*?SeaMonkey.*?([0-9\.]+).*$/i, "$1") ) + 1;

		if(o.isCamino =(ua.indexOf('Camino') != -1))
			o.isCamino = ua.replace(/^.*?Camino.*?([0-9\.]+).*$/i, "$1");

		if(o.isSafari =(ua.indexOf('Safari') != -1))
			o.isSafari= ua.replace(/^.*?Version\/([0-9]+\.[0-9]+).*$/i, "$1");

		if(o.isChrome =(ua.indexOf('Chrome') != -1)) {
			o.isChrome = ua.replace(/^.*?Chrome.*?([0-9\.]+).*$/i, "$1");
			o.isSafari	= false;
		}

	},

	window_loaded : function(){
		editAreaLoader.win="loaded";

		// add events on forms
		if (document.forms) {
			for (var i=0; i<document.forms.length; i++) {
				var form = document.forms[i];
				form.edit_area_replaced_submit=null;
				try {

					form.edit_area_replaced_submit = form.onsubmit;
					form.onsubmit="";
				} catch (e) {// Do nothing
				}
				editAreaLoader.add_event(form, "submit", EditAreaLoader.prototype.submit);
				editAreaLoader.add_event(form, "reset", EditAreaLoader.prototype.reset);
			}
		}
		editAreaLoader.add_event(window, "unload", function(){for(var i in editAreas){editAreaLoader.delete_instance(i);}});	// ini callback
	},

	// init the checkup of the selection of the IE textarea
	init_ie_textarea : function(id){
		var a=document.getElementById(id);
		try{
			if(a && typeof(a.focused)=="undefined"){
				a.focus();
				a.focused=true;
				a.selectionStart= a.selectionEnd= 0;
				get_IE_selection(a);
				editAreaLoader.add_event(a, "focus", IE_textarea_focus);
				editAreaLoader.add_event(a, "blur", IE_textarea_blur);

			}
		}catch(ex){}
	},

	init : function(settings){
		var t=this,s=settings,i;

		if(!s["id"])
			t.has_error(4); // no textarea ID specified in settings
		if(t.error)
			return false;
		// if an instance of the editor already exists for this textarea => delete the previous one
		if(editAreas[s["id"]])
			t.delete_instance(s["id"]);

		// init settings
		for(i in t.default_settings){
			if(typeof(s[i])=="undefined")
				s[i]=t.default_settings[i];
		}

		// [i_a] check that the syntax is one allowed
		if ((',' + s["syntax_selection_allow"] + ',').indexOf(',' + s["syntax"] + ',') < 0)
		{
			if (!s["ignore_unsupported_syntax"])
			{
				t.has_error(3); // unsupported syntax specified in settings
				return false;
			}
			else
			{
				s["syntax"] = "";
			}
		}

		if(s["browsers"]=="known" && t.isValidBrowser==false){
			t.has_error(6); // unsupported browser detected while settings state EditArea will be employed for known browsers only
			return false;
		}

		if(s["begin_toolbar"].length>0)
			s["toolbar"]= s["begin_toolbar"] +","+ s["toolbar"];
		if(s["end_toolbar"].length>0)
			s["toolbar"]= s["toolbar"] +","+ s["end_toolbar"];
		s["tab_toolbar"]= s["toolbar"].replace(/ /g,"").split(",");

		s["plugins"]= s["plugins"].replace(/ /g,"").split(",");
		for(i=0; i<s["plugins"].length; i++){
			if(s["plugins"][i].length==0)
				s["plugins"].splice(i,1);
		}
	//	alert(settings["plugins"].length+": "+ settings["plugins"].join(","));
		t.get_template();
		t.load_script(t.baseURL + "langs/"+ s["language"] + ".js");

		if(s["syntax"].length>0){
			s["syntax"]=s["syntax"].toLowerCase();
			t.load_script(t.baseURL + "reg_syntax/"+ s["syntax"] + ".js");
		}
		//alert(this.template);

		editAreas[s["id"]]= {"settings": s};
		editAreas[s["id"]]["displayed"]=false;
		editAreas[s["id"]]["hidden"]=false;

		//if(settings["display"]=="onload")
		t.start(s["id"]);

		return true;
	},

	// delete an instance of an EditArea
	delete_instance : function(id){
		var d=document,fs=window.frames,span,iframe;
		editAreaLoader.execCommand(id, "EA_delete");
		if(fs["frame_"+id] && fs["frame_"+id].editArea)
		{
			if(editAreas[id]["displayed"])
				editAreaLoader.toggle(id, "off");
			fs["frame_"+id].editArea.execCommand("EA_unload");
		}

		// remove toggle infos and debug textarea
		span= d.getElementById("EditAreaArroundInfos_"+id);
		if(span)
			span.parentNode.removeChild(span);

		// remove the iframe
		iframe= d.getElementById("frame_"+id);
		if(iframe){
			iframe.parentNode.removeChild(iframe);
			//delete iframe;
			try {
				delete fs["frame_"+id];
			} catch (e) {// Do nothing
			}
		}

		delete editAreas[id];
	},


	start : function(id){
		var t=this,d=document,f,span,father,next,html='',html_toolbar_content='',template,content,i;

		// check that the window is loaded
		if(t.win!="loaded"){
			setTimeout("editAreaLoader.start('"+id+"');", 50);
			return;
		}

		// check that all needed scripts are loaded
		for( i in t.waiting_loading){
			if(t.waiting_loading[i]!="loaded" && typeof(t.waiting_loading[i])!="function"){
				setTimeout("editAreaLoader.start('"+id+"');", 50);
				return;
			}
		}

		// wait until language and syntax files are loaded
		if(!t.lang[editAreas[id]["settings"]["language"]] || (editAreas[id]["settings"]["syntax"].length>0 && !t.load_syntax[editAreas[id]["settings"]["syntax"]]) ){
			setTimeout("editAreaLoader.start('"+id+"');", 50);
			return;
		}
		// init the regexp for syntax highlight
		if(editAreas[id]["settings"]["syntax"].length>0)
			t.init_syntax_regexp();


		// display toggle option and debug area
		if(!d.getElementById("EditAreaArroundInfos_"+id) && (editAreas[id]["settings"]["debug"] || editAreas[id]["settings"]["allow_toggle"]))
		{
			span= d.createElement("span");
			span.id= "EditAreaArroundInfos_"+id;
			if(editAreas[id]["settings"]["allow_toggle"]){
				checked=(editAreas[id]["settings"]["display"]=="onload")?"checked='checked'":"";
				html+="<div id='edit_area_toggle_"+i+"'>";
				html+="<input id='edit_area_toggle_checkbox_"+ id +"' class='toggle_"+ id +"' type='checkbox' onclick='editAreaLoader.toggle(\""+ id +"\");' accesskey='e' "+checked+" />";
				html+="<label for='edit_area_toggle_checkbox_"+ id +"'>{$toggle}</label></div>";
			}
			if(editAreas[id]["settings"]["debug"])
				html+="<textarea id='edit_area_debug_"+ id +"' spellcheck='off' style='z-index: 20; width: 100%; height: 120px;overflow: auto; border: solid black 1px;'></textarea><br />";
			html= t.translate(html, editAreas[id]["settings"]["language"]);
			span.innerHTML= html;
			father= d.getElementById(id).parentNode;
			next= d.getElementById(id).nextSibling;
			if(next==null)
				father.appendChild(span);
			else
				father.insertBefore(span, next);
		}

		if(!editAreas[id]["initialized"])
		{
			t.execCommand(id, "EA_init");	// ini callback
			if(editAreas[id]["settings"]["display"]=="later"){
				editAreas[id]["initialized"]= true;
				return;
			}
		}

		if(t.isIE){	// launch IE selection checkup
			t.init_ie_textarea(id);
		}

		// get toolbar content
		var area=editAreas[id];

		for(i=0; i<area["settings"]["tab_toolbar"].length; i++){
		//	alert(this.tab_toolbar[i]+"\n"+ this.get_control_html(this.tab_toolbar[i]));
			html_toolbar_content+= t.get_control_html(area["settings"]["tab_toolbar"][i], area["settings"]["language"]);
		}
		// translate toolbar text here for chrome 2
		html_toolbar_content = t.translate(html_toolbar_content, area["settings"]["language"], "template");


		// create javascript import rules for the iframe if the javascript has not been already loaded by the compressor
		if(!t.iframe_script){
			t.iframe_script="";
			for(i=0; i<t.sub_scripts_to_load.length; i++)
				t.iframe_script+='<script language="javascript" type="text/javascript" src="'+ t.baseURL + t.sub_scripts_to_load[i] +'.js"></script>';
		}

		// add plugins scripts if not already loaded by the compressor (but need to load language in all the case)
		for(i=0; i<area["settings"]["plugins"].length; i++){
			//if(typeof(area["settings"]["plugins"][i])=="function") continue;
			if(!t.all_plugins_loaded)
				t.iframe_script+='<script language="javascript" type="text/javascript" src="'+ t.baseURL + 'plugins/' + area["settings"]["plugins"][i] + '/' + area["settings"]["plugins"][i] +'.js"></script>';
			t.iframe_script+='<script language="javascript" type="text/javascript" src="'+ t.baseURL + 'plugins/' + area["settings"]["plugins"][i] + '/langs/' + area["settings"]["language"] +'.js"></script>';
		}


		// create css link for the iframe if the whole css text has not been already loaded by the compressor
		if(!t.iframe_css){
			t.iframe_css="<link href='"+ t.baseURL +"edit_area.css' rel='stylesheet' type='text/css' />";
		}


		// create template
		template= t.template.replace(/\[__BASEURL__\]/g, t.baseURL);
		template= template.replace("[__TOOLBAR__]",html_toolbar_content);


		// fill template with good language sentences
		template= t.translate(template, area["settings"]["language"], "template");

		// add css_code
		template= template.replace("[__CSSRULES__]", t.iframe_css);
		// add js_code
		template= template.replace("[__JSCODE__]", t.iframe_script);

		// add version_code
		template= template.replace("[__EA_VERSION__]", t.version);
		//template=template.replace(/\{\$([^\}]+)\}/gm, this.traduc_template);

		//editAreas[area["settings"]["id"]]["template"]= template;

		area.textarea=d.getElementById(area["settings"]["id"]);
		editAreas[area["settings"]["id"]]["textarea"]=area.textarea;

		// if removing previous instances from DOM before (fix from Marcin)
		if(typeof(window.frames["frame_"+area["settings"]["id"]])!='undefined')
			delete window.frames["frame_"+area["settings"]["id"]];

		// insert template in the document after the textarea
		father= area.textarea.parentNode;
	/*	var container= document.createElement("div");
		container.id= "EditArea_frame_container_"+area["settings"]["id"];
	*/
		content= d.createElement("iframe");
		content.name= "frame_"+area["settings"]["id"];
		content.id= "frame_"+area["settings"]["id"];
		content.style.borderWidth= "0px";
		setAttribute(content, "frameBorder", "0"); // IE
		content.style.overflow="hidden";
		content.style.display="none";


		next= area.textarea.nextSibling;
		if(next==null)
			father.appendChild(content);
		else
			father.insertBefore(content, next) ;
		f=window.frames["frame_"+area["settings"]["id"]];
		f.document.open();
		f.editAreas=editAreas;
		f.area_id= area["settings"]["id"];
		f.document.area_id= area["settings"]["id"];
		f.document.write(template);
		f.document.close();

	//	frame.editAreaLoader=this;
		//editAreas[area["settings"]["id"]]["displayed"]=true;

	},

	toggle : function(id, toggle_to){

	/*	if((editAreas[id]["displayed"]==true  && toggle_to!="on") || toggle_to=="off"){
			this.toggle_off(id);
		}else if((editAreas[id]["displayed"]==false  && toggle_to!="off") || toggle_to=="on"){
			this.toggle_on(id);
		}*/
		if(!toggle_to)
			toggle_to= (editAreas[id]["displayed"]==true)?"off":"on";
		if(editAreas[id]["displayed"]==true  && toggle_to=="off"){
			this.toggle_off(id);
		}else if(editAreas[id]["displayed"]==false  && toggle_to=="on"){
			this.toggle_on(id);
		}

		return false;
	},

	// static function
	toggle_off : function(id){
		var fs=window.frames,f,t,parNod,nxtSib,selStart,selEnd,scrollTop,scrollLeft;
		if(fs["frame_"+id])
		{
			f	= fs["frame_"+id];
			t	= editAreas[id]["textarea"];
			if(f.editArea.fullscreen['isFull'])
				f.editArea.toggle_full_screen(false);
			editAreas[id]["displayed"]=false;

			// set wrap to off to keep same display mode (some browser get problem with this, so it need more complex operation
			t.wrap = "off";	// for IE
			setAttribute(t, "wrap", "off");	// for Firefox
			parNod = t.parentNode;
			nxtSib = t.nextSibling;
			parNod.removeChild(t);
			parNod.insertBefore(t, nxtSib);

			// restore values
			t.value= f.editArea.textarea.value;
			selStart	= f.editArea.last_selection["selectionStart"];
			selEnd		= f.editArea.last_selection["selectionEnd"];
			scrollTop	= f.document.getElementById("result").scrollTop;
			scrollLeft	= f.document.getElementById("result").scrollLeft;


			document.getElementById("frame_"+id).style.display='none';

			t.style.display="inline";

			try{	// IE will give an error when trying to focus an invisible or disabled textarea
				t.focus();
			}
			catch(e){
			}
			if(this.isIE){
				t.selectionStart= selStart;
				t.selectionEnd	= selEnd;
				t.focused		= true;
				set_IE_selection(t);
			}else{
				if(this.isOpera && this.isOpera < 9.6 ){	// Opera bug when moving selection start and selection end
					t.setSelectionRange(0, 0);
				}
				try{
					t.setSelectionRange(selStart, selEnd);
				}
				catch(e){
				}
			}
			t.scrollTop= scrollTop;
			t.scrollLeft= scrollLeft;
			f.editArea.execCommand("toggle_off");

		}
	},

	// static function
	toggle_on : function(id){
		var fs=window.frames,f,t,selStart=0,selEnd=0,scrollTop=0,scrollLeft=0,curPos,elem;

		if(fs["frame_"+id])
		{
			f	= fs["frame_"+id];
			t	= editAreas[id]["textarea"];
			area= f.editArea;
			area.textarea.value= t.value;

			// store display values;
			curPos	= editAreas[id]["settings"]["cursor_position"];

			if(t.use_last==true)
			{
				selStart	= t.last_selectionStart;
				selEnd		= t.last_selectionEnd;
				scrollTop	= t.last_scrollTop;
				scrollLeft	= t.last_scrollLeft;
				t.use_last=false;
			}
			else if( curPos == "auto" )
			{
				try{
					selStart	= t.selectionStart;
					selEnd		= t.selectionEnd;
					scrollTop	= t.scrollTop;
					scrollLeft	= t.scrollLeft;
					//alert(scrollTop);
				}catch(ex){}
			}

			// set to good size
			this.set_editarea_size_from_textarea(id, document.getElementById("frame_"+id));
			t.style.display="none";
			document.getElementById("frame_"+id).style.display="inline";
			area.execCommand("focus"); // without this focus opera doesn't manage well the iframe body height


			// restore display values
			editAreas[id]["displayed"]=true;
			area.execCommand("update_size");

			f.document.getElementById("result").scrollTop= scrollTop;
			f.document.getElementById("result").scrollLeft= scrollLeft;
			area.area_select(selStart, selEnd-selStart);
			area.execCommand("toggle_on");


		}
		else
		{
		/*	if(this.isIE)
				get_IE_selection(document.getElementById(id));	*/
			elem= document.getElementById(id);
			elem.last_selectionStart= elem.selectionStart;
			elem.last_selectionEnd= elem.selectionEnd;
			elem.last_scrollTop= elem.scrollTop;
			elem.last_scrollLeft= elem.scrollLeft;
			elem.use_last=true;
			editAreaLoader.start(id);
		}
	},

	set_editarea_size_from_textarea : function(id, frame){
		var elem,width,height;
		elem	= document.getElementById(id);

		width	= Math.max(editAreas[id]["settings"]["min_width"], elem.offsetWidth)+"px";
		height	= Math.max(editAreas[id]["settings"]["min_height"], elem.offsetHeight)+"px";
		if(elem.style.width.indexOf("%")!=-1)
			width	= elem.style.width;
		if(elem.style.height.indexOf("%")!=-1)
			height	= elem.style.height;
		//alert("h: "+height+" w: "+width);

		frame.style.width= width;
		frame.style.height= height;
	},

	set_base_url : function(){
		var t=this,elems,i,docBasePath;

		if( !this.baseURL ){
			elems = document.getElementsByTagName('script');

			for( i=0; i<elems.length; i++ ){
				if (elems[i].src && elems[i].src.match(/edit_area_[^\\\/]*$/i) ) {
					var src = unescape( elems[i].src ); // use unescape for utf-8 encoded urls
					src = src.substring(0, src.lastIndexOf('/'));
					this.baseURL = src;
					this.file_name= elems[i].src.substr(elems[i].src.lastIndexOf("/")+1);
					break;
				}
			}
		}

		docBasePath	= document.location.href;
		if (docBasePath.indexOf('?') != -1)
			docBasePath	= docBasePath.substring(0, docBasePath.indexOf('?'));
		docBasePath	= docBasePath.substring(0, docBasePath.lastIndexOf('/'));

		// If not HTTP absolute
		if (t.baseURL.indexOf('://') == -1 && t.baseURL.charAt(0) != '/') {
			// If site absolute
			t.baseURL = docBasePath + "/" + t.baseURL;
		}
		t.baseURL	+="/";
	},

	get_button_html : function(id, img, exec, isFileSpecific, baseURL) {
		var cmd,html;
		if(!baseURL)
			baseURL= this.baseURL;
		cmd	= 'editArea.execCommand(\'' + exec + '\')';
		html	= '<a id="a_'+ id +'" href="javascript:' + cmd + '" onclick="' + cmd + ';return false;" onmousedown="return false;" target="_self" fileSpecific="'+ (isFileSpecific?'yes':'no') +'">';
		html	+= '<img id="' + id + '" src="'+ baseURL +'images/' + img + '" title="{$' + id + '}" width="20" height="20" class="editAreaButtonNormal" onmouseover="editArea.switchClass(this,\'editAreaButtonOver\');" onmouseout="editArea.restoreClass(this);" onmousedown="editArea.restoreAndSwitchClass(this,\'editAreaButtonDown\');" /></a>';
		return html;
	},

	get_control_html : function(button_name, lang) {
		var t=this,i,but,html,si;
		for (i=0; i<t.advanced_buttons.length; i++)
		{
			but = t.advanced_buttons[i];
			if (but[0] == button_name)
			{
				return t.get_button_html(but[0], but[1], but[2], but[3]);
			}
		}

		switch (button_name){
			case "*":
			case "return":
				return "<br />";
			case "|":
		  	case "separator":
				return '<img src="'+ t.baseURL +'images/spacer.gif" width="1" height="15" class="editAreaSeparatorLine">';
			case "select_font":
				html= "<select id='area_font_size' onchange='javascript:editArea.execCommand(\"change_font_size\")' fileSpecific='yes'>";
				html+="<option value='-1'>{$font_size}</option>";
				si=[8,9,10,11,12,14];
				for( i=0;i<si.length;i++){
					html+="<option value='"+si[i]+"'>"+si[i]+" pt</option>";
				}
				html+="</select>";
				return html;
			case "syntax_selection":
				html= "<select id='syntax_selection' onchange='javascript:editArea.execCommand(\"change_syntax\", this.value)' fileSpecific='yes'>";
				html+="<option value='-1'>{$syntax_selection}</option>";
				html+="</select>";
				return html;
		}

		return "<span id='tmp_tool_"+button_name+"'>["+button_name+"]</span>";
	},


	get_template : function(){
		if(this.template=="")
		{
			var xhr_object = null;
			if(window.XMLHttpRequest) // Firefox
				xhr_object = new XMLHttpRequest();
			else if(window.ActiveXObject) // Internet Explorer
				xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
			else { // XMLHttpRequest not supported
				alert("XMLHTTPRequest not supported. EditArea not loaded");
				return;
			}

			xhr_object.open("GET", this.baseURL+"template.html", false);
			xhr_object.send(null);
			if(xhr_object.readyState == 4)
				this.template=xhr_object.responseText;
			else
				this.has_error(5);
		}
	},

	// translate text
	translate : function(text, lang, mode){
		if(mode=="word")
			text=editAreaLoader.get_word_translation(text, lang);
		else if(mode="template"){
			editAreaLoader.current_language= lang;
			text=text.replace(/\{\$([^\}]+)\}/gm, editAreaLoader.translate_template);
		}
		return text;
	},

	translate_template : function(){
		return editAreaLoader.get_word_translation(EditAreaLoader.prototype.translate_template.arguments[1], editAreaLoader.current_language);
	},

	get_word_translation : function(val, lang){
		var i;

		for( i in editAreaLoader.lang[lang]){
			if(i == val)
				return editAreaLoader.lang[lang][i];
		}
		return "_"+val;
	},

	load_script : function(url){
		var t=this,d=document,script,head;

		if( t.loadedFiles[url] )
			return;
		//alert("load: "+url);
		try{
			script= d.createElement("script");
			script.type= "text/javascript";
			script.src= url;
			script.charset= "UTF-8";
			d.getElementsByTagName("head")[0].appendChild(script);
		}
		catch(e){
			d.write('<sc'+'ript language="javascript" type="text/javascript" src="' + url + '" charset="UTF-8"></sc'+'ript>');
		}

		t.loadedFiles[url] = true;
	},

	add_event : function(obj, name, handler) {
		try{
			if (obj.attachEvent) {
				obj.attachEvent("on" + name, handler);
			} else{
				obj.addEventListener(name, handler, false);
			}
		}
		catch(e){
		}
	},

	remove_event : function(obj, name, handler){
		try{
			if (obj.detachEvent)
				obj.detachEvent("on" + name, handler);
			else
				obj.removeEventListener(name, handler, false);
		}
		catch(e){
		}
	},


	// reset all the editareas in the form that have been reseted
	reset : function(e){
		var formObj,is_child,i,x;

		formObj = editAreaLoader.isIE ? window.event.srcElement : e.target;
		if(formObj.tagName!='FORM')
			formObj= formObj.form;

		for( i in editAreas ){
			is_child= false;
			for( x=0;x<formObj.elements.length;x++ ) {
				if(formObj.elements[x].id == i)
					is_child=true;
			}

			if(window.frames["frame_"+i] && is_child && editAreas[i]["displayed"]==true){

				var exec= 'window.frames["frame_'+ i +'"].editArea.textarea.value= document.getElementById("'+ i +'").value;';
				exec+= 'window.frames["frame_'+ i +'"].editArea.execCommand("focus");';
				exec+= 'window.frames["frame_'+ i +'"].editArea.check_line_selection();';
				exec+= 'window.frames["frame_'+ i +'"].editArea.execCommand("reset");';
				window.setTimeout(exec, 10);
			}
		}
		return;
	},


	// prepare all the textarea replaced by an editarea to be submited
	submit : function(e){
		var formObj,is_child,fs=window.frames,i,x;
		formObj = editAreaLoader.isIE ? window.event.srcElement : e.target;
		if(formObj.tagName!='FORM')
			formObj= formObj.form;

		for( i in editAreas){
			is_child= false;
			for( x=0;x<formObj.elements.length;x++ ) {
				if(formObj.elements[x].id == i)
					is_child=true;
			}

			if(is_child)
			{
				if(fs["frame_"+i] && editAreas[i]["displayed"]==true)
					document.getElementById(i).value= fs["frame_"+ i].editArea.textarea.value;
				editAreaLoader.execCommand(i,"EA_submit");
			}
		}
		if( typeof(formObj.edit_area_replaced_submit) == "function" ){
			res= formObj.edit_area_replaced_submit();
			if(res==false){
				if(editAreaLoader.isIE)
					return false;
				else
					e.preventDefault();
			}
		}
		return;
	},

	// allow to get the value of the editarea
	getValue : function(id){
        if(window.frames["frame_"+id] && editAreas[id]["displayed"]==true){
            return window.frames["frame_"+ id].editArea.textarea.value;
        }else if(elem=document.getElementById(id)){
        	return elem.value;
        }
        return false;
    },

    // allow to set the value of the editarea
    setValue : function(id, new_val){
    	var fs=window.frames;

        if( ( f=fs["frame_"+id] ) && editAreas[id]["displayed"]==true){
			f.editArea.textarea.value= new_val;
			f.editArea.execCommand("focus");
			f.editArea.check_line_selection(false);
			f.editArea.execCommand("onchange");
        }else if(elem=document.getElementById(id)){
        	elem.value= new_val;
        }
    },

    // allow to get infos on the selection: array(start, end)
    getSelectionRange : function(id){
    	var sel,eA,fs=window.frames;

    	sel= {"start": 0, "end": 0};
        if(fs["frame_"+id] && editAreas[id]["displayed"]==true){
        	eA= fs["frame_"+ id].editArea;

			sel["start"]	= eA.textarea.selectionStart;
			sel["end"]		= eA.textarea.selectionEnd;

        }else if( elem=document.getElementById(id) ){
        	sel= getSelectionRange(elem);
        }
        return sel;
    },

    // allow to set the selection with the given start and end positions
    setSelectionRange : function(id, new_start, new_end){
    	var fs=window.frames;

        if(fs["frame_"+id] && editAreas[id]["displayed"]==true){
            fs["frame_"+ id].editArea.area_select(new_start, new_end-new_start);
			// make an auto-scroll to the selection
			if(!this.isIE){
				fs["frame_"+ id].editArea.check_line_selection(false);
				fs["frame_"+ id].editArea.scroll_to_view();
			}
        }else if(elem=document.getElementById(id)){
        	setSelectionRange(elem, new_start, new_end);
        }
    },

    getSelectedText : function(id){
    	var sel= this.getSelectionRange(id);

        return this.getValue(id).substring(sel["start"], sel["end"]);
    },

	setSelectedText : function(id, new_val){
		var fs=window.frames,d=document,sel,text,scrollTop,scrollLeft,new_sel_end;

		new_val	= new_val.replace(/\r/g, "");
		sel		= this.getSelectionRange(id);
		text	= this.getValue(id);
		if(fs["frame_"+id] && editAreas[id]["displayed"]==true){
			scrollTop	= fs["frame_"+ id].document.getElementById("result").scrollTop;
			scrollLeft	= fs["frame_"+ id].document.getElementById("result").scrollLeft;
		}else{
			scrollTop	= d.getElementById(id).scrollTop;
			scrollLeft	= d.getElementById(id).scrollLeft;
		}

		text	= text.substring(0, sel["start"])+ new_val +text.substring(sel["end"]);
		this.setValue(id, text);
		new_sel_end	= sel["start"]+ new_val.length;
		this.setSelectionRange(id, sel["start"], new_sel_end);


		// fix \r problem for selection length count on IE & Opera
		if(new_val != this.getSelectedText(id).replace(/\r/g, "")){
			this.setSelectionRange(id, sel["start"], new_sel_end+ new_val.split("\n").length -1);
		}
		// restore scrolling position
		if(fs["frame_"+id] && editAreas[id]["displayed"]==true){
			fs["frame_"+ id].document.getElementById("result").scrollTop= scrollTop;
			fs["frame_"+ id].document.getElementById("result").scrollLeft= scrollLeft;
			fs["frame_"+ id].editArea.execCommand("onchange");
		}else{
			d.getElementById(id).scrollTop= scrollTop;
			d.getElementById(id).scrollLeft= scrollLeft;
		}
    },

    insertTags : function(id, open_tag, close_tag){
    	var old_sel,new_sel;

    	old_sel	= this.getSelectionRange(id);
    	text	= open_tag + this.getSelectedText(id) + close_tag;

		editAreaLoader.setSelectedText(id, text);

    	new_sel	= this.getSelectionRange(id);
    	if(old_sel["end"] > old_sel["start"])	// if text was selected, cursor at the end
    		this.setSelectionRange(id, new_sel["end"], new_sel["end"]);
    	else // cursor in the middle
    		this.setSelectionRange(id, old_sel["start"]+open_tag.length, old_sel["start"]+open_tag.length);
    },

    // hide both EditArea and normal textarea
	hide : function(id){
		var fs= window.frames,d=document,t=this,scrollTop,scrollLeft,span;
		if(d.getElementById(id) && !t.hidden[id])
		{
			t.hidden[id]= {};
			t.hidden[id]["selectionRange"]= t.getSelectionRange(id);
			if(d.getElementById(id).style.display!="none")
			{
				t.hidden[id]["scrollTop"]= d.getElementById(id).scrollTop;
				t.hidden[id]["scrollLeft"]= d.getElementById(id).scrollLeft;
			}

			if(fs["frame_"+id])
			{
				t.hidden[id]["toggle"]= editAreas[id]["displayed"];

				if(fs["frame_"+id] && editAreas[id]["displayed"]==true){
					scrollTop	= fs["frame_"+ id].document.getElementById("result").scrollTop;
					scrollLeft	= fs["frame_"+ id].document.getElementById("result").scrollLeft;
				}else{
					scrollTop	= d.getElementById(id).scrollTop;
					scrollLeft	= d.getElementById(id).scrollLeft;
				}
				t.hidden[id]["scrollTop"]= scrollTop;
				t.hidden[id]["scrollLeft"]= scrollLeft;

				if(editAreas[id]["displayed"]==true)
					editAreaLoader.toggle_off(id);
			}

			// hide toggle button and debug box
			span= d.getElementById("EditAreaArroundInfos_"+id);
			if(span){
				span.style.display='none';
			}

			// hide textarea
			d.getElementById(id).style.display= "none";
		}
	},

	// restore hidden EditArea and normal textarea
	show : function(id){
		var fs= window.frames,d=document,t=this,span;
		if((elem=d.getElementById(id)) && t.hidden[id])
		{
			elem.style.display= "inline";
			elem.scrollTop= t.hidden[id]["scrollTop"];
			elem.scrollLeft= t.hidden[id]["scrollLeft"];
			span= d.getElementById("EditAreaArroundInfos_"+id);
			if(span){
				span.style.display='inline';
			}

			if(fs["frame_"+id])
			{

				// restore toggle button and debug box


				// restore textarea
				elem.style.display= "inline";

				// restore EditArea
				if(t.hidden[id]["toggle"]==true)
					editAreaLoader.toggle_on(id);

				scrollTop	= t.hidden[id]["scrollTop"];
				scrollLeft	= t.hidden[id]["scrollLeft"];

				if(fs["frame_"+id] && editAreas[id]["displayed"]==true){
					fs["frame_"+ id].document.getElementById("result").scrollTop	= scrollTop;
					fs["frame_"+ id].document.getElementById("result").scrollLeft	= scrollLeft;
				}else{
					elem.scrollTop	= scrollTop;
					elem.scrollLeft	= scrollLeft;
				}

			}
			// restore selection
			sel	= t.hidden[id]["selectionRange"];
			t.setSelectionRange(id, sel["start"], sel["end"]);
			delete t.hidden[id];
		}
	},

	// get the current file datas (for multi file editing mode)
	getCurrentFile : function(id){
		return this.execCommand(id, 'get_file', this.execCommand(id, 'curr_file'));
	},

	// get the given file datas (for multi file editing mode)
	getFile : function(id, file_id){
		return this.execCommand(id, 'get_file', file_id);
	},

	// get all the openned files datas (for multi file editing mode)
	getAllFiles : function(id){
		return this.execCommand(id, 'get_all_files()');
	},

	// open a file (for multi file editing mode)
	openFile : function(id, file_infos){
		return this.execCommand(id, 'open_file', file_infos);
	},

	// close the given file (for multi file editing mode)
	closeFile : function(id, file_id){
		return this.execCommand(id, 'close_file', file_id);
	},

	// close the given file (for multi file editing mode)
	setFileEditedMode : function(id, file_id, to){
		var reg1,reg2;
		reg1	= new RegExp('\\\\', 'g');
		reg2	= new RegExp('"', 'g');
		return this.execCommand(id, 'set_file_edited_mode("'+ file_id.replace(reg1, '\\\\').replace(reg2, '\\"') +'", '+ to +')');
	},


	// allow to access to editarea functions and datas (for advanced users only)
	execCommand : function(id, cmd, fct_param){
		switch(cmd){
			case "EA_init":
				if(editAreas[id]['settings']["EA_init_callback"].length>0)
					eval(editAreas[id]['settings']["EA_init_callback"]+"('"+ id +"');");
				break;
			case "EA_delete":
				if(editAreas[id]['settings']["EA_delete_callback"].length>0)
					eval(editAreas[id]['settings']["EA_delete_callback"]+"('"+ id +"');");
				break;
			case "EA_submit":
				if(editAreas[id]['settings']["submit_callback"].length>0)
					eval(editAreas[id]['settings']["submit_callback"]+"('"+ id +"');");
				break;
		}
        if(window.frames["frame_"+id] && window.frames["frame_"+ id].editArea){
			if(fct_param!=undefined)
				return eval('window.frames["frame_'+ id +'"].editArea.'+ cmd +'(fct_param);');
			else
				return eval('window.frames["frame_'+ id +'"].editArea.'+ cmd +';');
        }
        return false;
    }
};

	var editAreaLoader= new EditAreaLoader();
	var editAreas= {};


/****
 * This page contains some general usefull functions for javascript
 *
 ****/  
	
	
	// need to redefine this functiondue to IE problem
	function getAttribute( elm, aName ) {
		var aValue,taName,i;
		try{
			aValue = elm.getAttribute( aName );
		}catch(exept){}
		
		if( ! aValue ){
			for( i = 0; i < elm.attributes.length; i ++ ) {
				taName = elm.attributes[i] .name.toLowerCase();
				if( taName == aName ) {
					aValue = elm.attributes[i] .value;
					return aValue;
				}
			}
		}
		return aValue;
	};
	
	// need to redefine this function due to IE problem
	function setAttribute( elm, attr, val ) {
		if(attr=="class"){
			elm.setAttribute("className", val);
			elm.setAttribute("class", val);
		}else{
			elm.setAttribute(attr, val);
		}
	};
	
	/* return a child element
		elem: element we are searching in
		elem_type: type of the eleemnt we are searching (DIV, A, etc...)
		elem_attribute: attribute of the searched element that must match
		elem_attribute_match: value that elem_attribute must match
		option: "all" if must return an array of all children, otherwise return the first match element
		depth: depth of search (-1 or no set => unlimited)
	*/
	function getChildren(elem, elem_type, elem_attribute, elem_attribute_match, option, depth)
	{           
		if(!option)
			var option="single";
		if(!depth)
			var depth=-1;
		if(elem){
			var children= elem.childNodes;
			var result=null;
			var results= [];
			for (var x=0;x<children.length;x++) {
				strTagName = new String(children[x].tagName);
				children_class="?";
				if(strTagName!= "undefined"){
					child_attribute= getAttribute(children[x],elem_attribute);
					if((strTagName.toLowerCase()==elem_type.toLowerCase() || elem_type=="") && (elem_attribute=="" || child_attribute==elem_attribute_match)){
						if(option=="all"){
							results.push(children[x]);
						}else{
							return children[x];
						}
					}
					if(depth!=0){
						result=getChildren(children[x], elem_type, elem_attribute, elem_attribute_match, option, depth-1);
						if(option=="all"){
							if(result.length>0){
								results= results.concat(result);
							}
						}else if(result!=null){                                                                          
							return result;
						}
					}
				}
			}
			if(option=="all")
			   return results;
		}
		return null;
	};       
	
	function isChildOf(elem, parent){
		if(elem){
			if(elem==parent)
				return true;
			while(elem.parentNode != 'undefined'){
				return isChildOf(elem.parentNode, parent);
			}
		}
		return false;
	};
	
	function getMouseX(e){

		if(e!=null && typeof(e.pageX)!="undefined"){
			return e.pageX;
		}else{
			return (e!=null?e.x:event.x)+ document.documentElement.scrollLeft;
		}
	};
	
	function getMouseY(e){
		if(e!=null && typeof(e.pageY)!="undefined"){
			return e.pageY;
		}else{
			return (e!=null?e.y:event.y)+ document.documentElement.scrollTop;
		}
	};
	
	function calculeOffsetLeft(r){
		return calculeOffset(r,"offsetLeft")
	};
	
	function calculeOffsetTop(r){
		return calculeOffset(r,"offsetTop")
	};
	
	function calculeOffset(element,attr){
		var offset=0;
		while(element){
			offset+=element[attr];
			element=element.offsetParent
		}
		return offset;
	};
	
	/** return the computed style
	 *	@param: elem: the reference to the element
	 *	@param: prop: the name of the css property	 
	 */
	function get_css_property(elem, prop)
	{
		if(document.defaultView)
		{
			return document.defaultView.getComputedStyle(elem, null).getPropertyValue(prop);
		}
		else if(elem.currentStyle)
		{
			var prop = prop.replace(/-\D/gi, function(sMatch)
			{
				return sMatch.charAt(sMatch.length - 1).toUpperCase();
			});
			return elem.currentStyle[prop];
		}
		else return null;
	}
	
/****
 * Moving an element 
 ***/  
	
	var _mCE;	// currently moving element
	
	/* allow to move an element in a window
		e: the event
		id: the id of the element
		frame: the frame of the element 
		ex of use:
			in html:	<img id='move_area_search_replace' onmousedown='return parent.start_move_element(event,"area_search_replace", parent.frames["this_frame_id"]);' .../>  
		or
			in javascript: document.getElementById("my_div").onmousedown= start_move_element
	*/
	function start_move_element(e, id, frame){
		var elem_id=(e.target || e.srcElement).id;
		if(id)
			elem_id=id;		
		if(!frame)
			frame=window;
		if(frame.event)
			e=frame.event;
			
		_mCE= frame.document.getElementById(elem_id);
		_mCE.frame=frame;
		frame.document.onmousemove= move_element;
		frame.document.onmouseup= end_move_element;
		/*_mCE.onmousemove= move_element;
		_mCE.onmouseup= end_move_element;*/
		
		//alert(_mCE.frame.document.body.offsetHeight);
		
		mouse_x= getMouseX(e);
		mouse_y= getMouseY(e);
		//window.status=frame+ " elem: "+elem_id+" elem: "+ _mCE + " mouse_x: "+mouse_x;
		_mCE.start_pos_x = mouse_x - (_mCE.style.left.replace("px","") || calculeOffsetLeft(_mCE));
		_mCE.start_pos_y = mouse_y - (_mCE.style.top.replace("px","") || calculeOffsetTop(_mCE));
		return false;
	};
	
	function end_move_element(e){
		_mCE.frame.document.onmousemove= "";
		_mCE.frame.document.onmouseup= "";		
		_mCE=null;
	};
	
	function move_element(e){
		var newTop,newLeft,maxLeft;

		if( _mCE.frame && _mCE.frame.event )
			e=_mCE.frame.event;
		newTop	= getMouseY(e) - _mCE.start_pos_y;
		newLeft	= getMouseX(e) - _mCE.start_pos_x;
		
		maxLeft	= _mCE.frame.document.body.offsetWidth- _mCE.offsetWidth;
		max_top	= _mCE.frame.document.body.offsetHeight- _mCE.offsetHeight;
		newTop	= Math.min(Math.max(0, newTop), max_top);
		newLeft	= Math.min(Math.max(0, newLeft), maxLeft);
		
		_mCE.style.top	= newTop+"px";
		_mCE.style.left	= newLeft+"px";		
		return false;
	};
	
/***
 * Managing a textarea (this part need the navigator infos from editAreaLoader
 ***/ 
	
	var nav= editAreaLoader.nav;
	
	// allow to get infos on the selection: array(start, end)
	function getSelectionRange(textarea){
		return {"start": textarea.selectionStart, "end": textarea.selectionEnd};
	};
	
	// allow to set the selection
	function setSelectionRange(t, start, end){
		t.focus();
		
		start	= Math.max(0, Math.min(t.value.length, start));
		end		= Math.max(start, Math.min(t.value.length, end));
	
		if( nav.isOpera && nav.isOpera < 9.6 ){	// Opera bug when moving selection start and selection end
			t.selectionEnd = 1;	
			t.selectionStart = 0;			
			t.selectionEnd = 1;	
			t.selectionStart = 0;		
		}
		t.selectionStart	= start;
		t.selectionEnd		= end;		
		//textarea.setSelectionRange(start, end);
		
		if(nav.isIE)
			set_IE_selection(t);
	};

	
	// set IE position in Firefox mode (textarea.selectionStart and textarea.selectionEnd). should work as a repeated task
	function get_IE_selection(t){
		var d=document,div,range,stored_range,elem,scrollTop,relative_top,line_start,line_nb,range_start,range_end,tab;
		if(t && t.focused)
		{	
			if(!t.ea_line_height)
			{	// calculate the lineHeight
				div= d.createElement("div");
				div.style.fontFamily= get_css_property(t, "font-family");
				div.style.fontSize= get_css_property(t, "font-size");
				div.style.visibility= "hidden";			
				div.innerHTML="0";
				d.body.appendChild(div);
				t.ea_line_height= div.offsetHeight;
				d.body.removeChild(div);
			}
			//t.focus();
			range = d.selection.createRange();
			try
			{
				stored_range = range.duplicate();
				stored_range.moveToElementText( t );
				stored_range.setEndPoint( 'EndToEnd', range );
				if(stored_range.parentElement() == t){
					// the range don't take care of empty lines in the end of the selection
					elem		= t;
					scrollTop	= 0;
					while(elem.parentNode){
						scrollTop+= elem.scrollTop;
						elem	= elem.parentNode;
					}
				
				//	var scrollTop= t.scrollTop + document.body.scrollTop;
					
				//	var relative_top= range.offsetTop - calculeOffsetTop(t) + scrollTop;
					relative_top= range.offsetTop - calculeOffsetTop(t)+ scrollTop;
				//	alert("rangeoffset: "+ range.offsetTop +"\ncalcoffsetTop: "+ calculeOffsetTop(t) +"\nrelativeTop: "+ relative_top);
					line_start	= Math.round((relative_top / t.ea_line_height) +1);
					
					line_nb		= Math.round(range.boundingHeight / t.ea_line_height);
					
					range_start	= stored_range.text.length - range.text.length;
					tab	= t.value.substr(0, range_start).split("\n");			
					range_start	+= (line_start - tab.length)*2;		// add missing empty lines to the selection
					t.selectionStart = range_start;
					
					range_end	= t.selectionStart + range.text.length;
					tab	= t.value.substr(0, range_start + range.text.length).split("\n");			
					range_end	+= (line_start + line_nb - 1 - tab.length)*2;
					t.selectionEnd = range_end;
				}
			}
			catch(e){}
		}
		if( t && t.id )
		{
			setTimeout("get_IE_selection(document.getElementById('"+ t.id +"'));", 50);
		}
	};
	
	function IE_textarea_focus(){
		event.srcElement.focused= true;
	}
	
	function IE_textarea_blur(){
		event.srcElement.focused= false;
	}
	
	// select the text for IE (take into account the \r difference)
	function set_IE_selection( t ){
		var nbLineStart,nbLineStart,nbLineEnd,range;
		if(!window.closed){ 
			nbLineStart=t.value.substr(0, t.selectionStart).split("\n").length - 1;
			nbLineEnd=t.value.substr(0, t.selectionEnd).split("\n").length - 1;
			try
			{
				range = document.selection.createRange();
				range.moveToElementText( t );
				range.setEndPoint( 'EndToStart', range );
				range.moveStart('character', t.selectionStart - nbLineStart);
				range.moveEnd('character', t.selectionEnd - nbLineEnd - (t.selectionStart - nbLineStart)  );
				range.select();
			}
			catch(e){}
		}
	};
	
	
	editAreaLoader.waiting_loading["elements_functions.js"]= "loaded";

	
	EditAreaLoader.prototype.start_resize_area= function(){
		var d=document,a,div,width,height,father;
		
		d.onmouseup= editAreaLoader.end_resize_area;
		d.onmousemove= editAreaLoader.resize_area;
		editAreaLoader.toggle(editAreaLoader.resize["id"]);		
		
		a	= editAreas[editAreaLoader.resize["id"]]["textarea"];
		div	= d.getElementById("edit_area_resize");
		if(!div){
			div= d.createElement("div");
			div.id="edit_area_resize";
			div.style.border="dashed #888888 1px";
		}
		width	= a.offsetWidth -2;
		height	= a.offsetHeight -2;
		
		div.style.display	= "block";
		div.style.width		= width+"px";
		div.style.height	= height+"px";
		father= a.parentNode;
		father.insertBefore(div, a);
		
		a.style.display="none";
				
		editAreaLoader.resize["start_top"]= calculeOffsetTop(div);
		editAreaLoader.resize["start_left"]= calculeOffsetLeft(div);		
	};
	
	EditAreaLoader.prototype.end_resize_area= function(e){
		var d=document,div,a,width,height;
		
		d.onmouseup="";
		d.onmousemove="";		
		
		div		= d.getElementById("edit_area_resize");		
		a= editAreas[editAreaLoader.resize["id"]]["textarea"];
		width	= Math.max(editAreas[editAreaLoader.resize["id"]]["settings"]["min_width"], div.offsetWidth-4);
		height	= Math.max(editAreas[editAreaLoader.resize["id"]]["settings"]["min_height"], div.offsetHeight-4);
		if(editAreaLoader.isIE==6){
			width-=2;
			height-=2;	
		}
		a.style.width		= width+"px";
		a.style.height		= height+"px";
		div.style.display	= "none";
		a.style.display		= "inline";
		a.selectionStart	= editAreaLoader.resize["selectionStart"];
		a.selectionEnd		= editAreaLoader.resize["selectionEnd"];
		editAreaLoader.toggle(editAreaLoader.resize["id"]);
		
		return false;
	};
	
	EditAreaLoader.prototype.resize_area= function(e){		
		var allow,newHeight,newWidth;
		allow	= editAreas[editAreaLoader.resize["id"]]["settings"]["allow_resize"];
		if(allow=="both" || allow=="y")
		{
			newHeight	= Math.max(20, getMouseY(e)- editAreaLoader.resize["start_top"]);
			document.getElementById("edit_area_resize").style.height= newHeight+"px";
		}
		if(allow=="both" || allow=="x")
		{
			newWidth= Math.max(20, getMouseX(e)- editAreaLoader.resize["start_left"]);
			document.getElementById("edit_area_resize").style.width= newWidth+"px";
		}
		
		return false;
	};
	
	editAreaLoader.waiting_loading["resize_area.js"]= "loaded";

	EditAreaLoader.prototype.get_regexp= function(text_array){
		//res="( |=|\\n|\\r|\\[|\\(|µ|)(";
		res="(\\b)(";
		for(i=0; i<text_array.length; i++){
			if(i>0)
				res+="|";
			//res+="("+ tab_text[i] +")";
			//res+=tab_text[i].replace(/(\.|\?|\*|\+|\\|\(|\)|\[|\]|\{|\})/g, "\\$1");
			res+=this.get_escaped_regexp(text_array[i]);
		}
		//res+=")( |\\.|:|\\{|\\(|\\)|\\[|\\]|\'|\"|\\r|\\n|\\t|$)";
		res+=")(\\b)";
		reg= new RegExp(res);
		
		return res;
	};
	
	
	EditAreaLoader.prototype.get_escaped_regexp= function(str){
		return str.toString().replace(/(\.|\?|\*|\+|\\|\(|\)|\[|\]|\}|\{|\$|\^|\|)/g, "\\$1");
	};
	
	EditAreaLoader.prototype.init_syntax_regexp= function(){
		var lang_style= {};	
		for(var lang in this.load_syntax){
			if(!this.syntax[lang])	// init the regexp if not already initialized
			{
				this.syntax[lang]= {};
				this.syntax[lang]["keywords_reg_exp"]= {};
				this.keywords_reg_exp_nb=0;
			
				if(this.load_syntax[lang]['KEYWORDS']){
					param="g";
					if(this.load_syntax[lang]['KEYWORD_CASE_SENSITIVE']===false)
						param+="i";
					for(var i in this.load_syntax[lang]['KEYWORDS']){
						if(typeof(this.load_syntax[lang]['KEYWORDS'][i])=="function") continue;
						this.syntax[lang]["keywords_reg_exp"][i]= new RegExp(this.get_regexp( this.load_syntax[lang]['KEYWORDS'][i] ), param);
						this.keywords_reg_exp_nb++;
					}
				}
				
				if(this.load_syntax[lang]['OPERATORS']){
					var str="";
					var nb=0;
					for(var i in this.load_syntax[lang]['OPERATORS']){
						if(typeof(this.load_syntax[lang]['OPERATORS'][i])=="function") continue;
						if(nb>0)
							str+="|";				
						str+=this.get_escaped_regexp(this.load_syntax[lang]['OPERATORS'][i]);
						nb++;
					}
					if(str.length>0)
						this.syntax[lang]["operators_reg_exp"]= new RegExp("("+str+")","g");
				}
				
				if(this.load_syntax[lang]['DELIMITERS']){
					var str="";
					var nb=0;
					for(var i in this.load_syntax[lang]['DELIMITERS']){
						if(typeof(this.load_syntax[lang]['DELIMITERS'][i])=="function") continue;
						if(nb>0)
							str+="|";
						str+=this.get_escaped_regexp(this.load_syntax[lang]['DELIMITERS'][i]);
						nb++;
					}
					if(str.length>0)
						this.syntax[lang]["delimiters_reg_exp"]= new RegExp("("+str+")","g");
				}
				
				
		//		/(("(\\"|[^"])*"?)|('(\\'|[^'])*'?)|(//(.|\r|\t)*\n)|(/\*(.|\n|\r|\t)*\*/)|(<!--(.|\n|\r|\t)*-->))/gi
				var syntax_trace=[];
				
		//		/("(?:[^"\\]*(\\\\)*(\\"?)?)*("|$))/g
				
				this.syntax[lang]["quotes"]={};
				var quote_tab= [];
				if(this.load_syntax[lang]['QUOTEMARKS']){
					for(var i in this.load_syntax[lang]['QUOTEMARKS']){	
						if(typeof(this.load_syntax[lang]['QUOTEMARKS'][i])=="function") continue;			
						var x=this.get_escaped_regexp(this.load_syntax[lang]['QUOTEMARKS'][i]);
						this.syntax[lang]["quotes"][x]=x;
						//quote_tab[quote_tab.length]="("+x+"(?:\\\\"+x+"|[^"+x+"])*("+x+"|$))";
						//previous working : quote_tab[quote_tab.length]="("+x+"(?:[^"+x+"\\\\]*(\\\\\\\\)*(\\\\"+x+"?)?)*("+x+"|$))";
						quote_tab[quote_tab.length]="("+ x +"(\\\\.|[^"+ x +"])*(?:"+ x +"|$))";
						
						syntax_trace.push(x);			
					}			
				}
						
				this.syntax[lang]["comments"]={};
				if(this.load_syntax[lang]['COMMENT_SINGLE']){
					for(var i in this.load_syntax[lang]['COMMENT_SINGLE']){	
						if(typeof(this.load_syntax[lang]['COMMENT_SINGLE'][i])=="function") continue;						
						var x=this.get_escaped_regexp(this.load_syntax[lang]['COMMENT_SINGLE'][i]);
						quote_tab[quote_tab.length]="("+x+"(.|\\r|\\t)*(\\n|$))";
						syntax_trace.push(x);
						this.syntax[lang]["comments"][x]="\n";
					}			
				}		
				// (/\*(.|[\r\n])*?\*/)
				if(this.load_syntax[lang]['COMMENT_MULTI']){
					for(var i in this.load_syntax[lang]['COMMENT_MULTI']){
						if(typeof(this.load_syntax[lang]['COMMENT_MULTI'][i])=="function") continue;							
						var start=this.get_escaped_regexp(i);
						var end=this.get_escaped_regexp(this.load_syntax[lang]['COMMENT_MULTI'][i]);
						quote_tab[quote_tab.length]="("+start+"(.|\\n|\\r)*?("+end+"|$))";
						syntax_trace.push(start);
						syntax_trace.push(end);
						this.syntax[lang]["comments"][i]=this.load_syntax[lang]['COMMENT_MULTI'][i];
					}			
				}		
				if(quote_tab.length>0)
					this.syntax[lang]["comment_or_quote_reg_exp"]= new RegExp("("+quote_tab.join("|")+")","gi");
				
				if(syntax_trace.length>0) //   /((.|\n)*?)(\\*("|'|\/\*|\*\/|\/\/|$))/g
					this.syntax[lang]["syntax_trace_regexp"]= new RegExp("((.|\n)*?)(\\\\*("+ syntax_trace.join("|") +"|$))", "gmi");
				
				if(this.load_syntax[lang]['SCRIPT_DELIMITERS']){
					this.syntax[lang]["script_delimiters"]= {};
					for(var i in this.load_syntax[lang]['SCRIPT_DELIMITERS']){
						if(typeof(this.load_syntax[lang]['SCRIPT_DELIMITERS'][i])=="function") continue;							
						this.syntax[lang]["script_delimiters"][i]= this.load_syntax[lang]['SCRIPT_DELIMITERS'];
					}			
				}
				
				this.syntax[lang]["custom_regexp"]= {};
				if(this.load_syntax[lang]['REGEXPS']){
					for(var i in this.load_syntax[lang]['REGEXPS']){
						if(typeof(this.load_syntax[lang]['REGEXPS'][i])=="function") continue;
						var val= this.load_syntax[lang]['REGEXPS'][i];
						if(!this.syntax[lang]["custom_regexp"][val['execute']])
							this.syntax[lang]["custom_regexp"][val['execute']]= {};
						this.syntax[lang]["custom_regexp"][val['execute']][i]={'regexp' : new RegExp(val['search'], val['modifiers'])
																			, 'class' : val['class']};
					}
				}
				
				if(this.load_syntax[lang]['STYLES']){							
					lang_style[lang]= {};
					for(var i in this.load_syntax[lang]['STYLES']){
						if(typeof(this.load_syntax[lang]['STYLES'][i])=="function") continue;
						if(typeof(this.load_syntax[lang]['STYLES'][i]) != "string"){
							for(var j in this.load_syntax[lang]['STYLES'][i]){							
								lang_style[lang][j]= this.load_syntax[lang]['STYLES'][i][j];
							}
						}else{
							lang_style[lang][i]= this.load_syntax[lang]['STYLES'][i];
						}
					}
				}
				// build style string
				var style="";		
				for(var i in lang_style[lang]){
					if(lang_style[lang][i].length>0){
						style+= "."+ lang +" ."+ i.toLowerCase() +" span{"+lang_style[lang][i]+"}\n";
						style+= "."+ lang +" ."+ i.toLowerCase() +"{"+lang_style[lang][i]+"}\n";				
					}
				}
				this.syntax[lang]["styles"]=style;
			}
		}				
	};
	
	editAreaLoader.waiting_loading["reg_syntax.js"]= "loaded";

editAreaLoader.iframe_script= "<script type='text/javascript'>/******\n"
+" *\n"
+" *	EditArea\n"
+" * 	Developped by Christophe Dolivet\n"
+" *	Released under LGPL, Apache and BSD licenses (use the one you want)\n"
+" *\n"
+"******/\n"
+"\n"
+"	function EditArea(){\n"
+"		var t=this;\n"
+"		t.error= false;	// to know if load is interrrupt\n"
+"\n"
+"		t.inlinePopup= [{popup_id: \"area_search_replace\", icon_id: \"search\"},\n"
+"									{popup_id: \"edit_area_help\", icon_id: \"help\"}];\n"
+"		t.plugins= {};\n"
+"\n"
+"		t.line_number=0;\n"
+"\n"
+"		parent.editAreaLoader.set_browser_infos(t); 	// navigator identification\n"
+"		// fix IE8 detection as we run in IE7 emulate mode through X-UA <meta> tag\n"
+"		if( t.isIE >= 8 )\n"
+"			t.isIE	= 7;\n"
+"\n"
+"		t.last_selection={};\n"
+"		t.last_text_to_highlight=\"\";\n"
+"		t.last_hightlighted_text= \"\";\n"
+"		t.syntax_list= [];\n"
+"		t.allready_used_syntax= {};\n"
+"		t.check_line_selection_timer= 50;	// the timer delay for modification and/or selection change detection\n"
+"\n"
+"		t.textareaFocused= false;\n"
+"		t.highlight_selection_line= null;\n"
+"		t.previous= [];\n"
+"		t.next= [];\n"
+"		t.last_undo=\"\";\n"
+"		t.files= {};\n"
+"		t.filesIdAssoc= {};\n"
+"		t.curr_file= '';\n"
+"		//t.loaded= false;\n"
+"		t.assocBracket={};\n"
+"		t.revertAssocBracket= {};\n"
+"		// bracket selection init\n"
+"		t.assocBracket[\"(\"]=\")\";\n"
+"		t.assocBracket[\"{\"]=\"}\";\n"
+"		t.assocBracket[\"[\"]=\"]\";\n"
+"		for(var index in t.assocBracket){\n"
+"			t.revertAssocBracket[t.assocBracket[index]]=index;\n"
+"		}\n"
+"		t.is_editable= true;\n"
+"\n"
+"\n"
+"		/*t.textarea=\"\";\n"
+"\n"
+"		t.state=\"declare\";\n"
+"		t.code = []; // store highlight syntax for languagues*/\n"
+"		// font datas\n"
+"		t.lineHeight= 16;\n"
+"		/*t.default_font_family= \"monospace\";\n"
+"		t.default_font_size= 10;*/\n"
+"		t.tab_nb_char= 8;	//nb of white spaces corresponding to a tabulation\n"
+"		if(t.isOpera)\n"
+"			t.tab_nb_char= 6;\n"
+"\n"
+"		t.is_tabbing= false;\n"
+"\n"
+"		t.fullscreen= {'isFull': false};\n"
+"\n"
+"		t.isResizing=false;	// resize var\n"
+"\n"
+"		// init with settings and ID (area_id is a global var defined by editAreaLoader on iframe creation\n"
+"		t.id= area_id;\n"
+"		t.settings= editAreas[t.id][\"settings\"];\n"
+"\n"
+"		if((\"\"+t.settings['replace_tab_by_spaces']).match(/^[0-9]+$/))\n"
+"		{\n"
+"			t.tab_nb_char= t.settings['replace_tab_by_spaces'];\n"
+"			t.tabulation=\"\";\n"
+"			for(var i=0; i<t.tab_nb_char; i++)\n"
+"				t.tabulation+=\" \";\n"
+"		}else{\n"
+"			t.tabulation=\"\t\";\n"
+"		}\n"
+"\n"
+"		// retrieve the init parameter for syntax\n"
+"		if(t.settings[\"syntax_selection_allow\"] && t.settings[\"syntax_selection_allow\"].length>0)\n"
+"			t.syntax_list= t.settings[\"syntax_selection_allow\"].replace(/ /g,\"\").split(\",\");\n"
+"\n"
+"		if(t.settings['syntax'])\n"
+"			t.allready_used_syntax[t.settings['syntax']]=true;\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.init= function(){\n"
+"		var t=this, a, s=t.settings;\n"
+"		t.textarea			= _$(\"textarea\");\n"
+"		t.container			= _$(\"container\");\n"
+"		t.result			= _$(\"result\");\n"
+"		t.content_highlight	= _$(\"content_highlight\");\n"
+"		t.selection_field	= _$(\"selection_field\");\n"
+"		t.selection_field_text= _$(\"selection_field_text\");\n"
+"		t.processing_screen	= _$(\"processing\");\n"
+"		t.editor_area		= _$(\"editor\");\n"
+"		t.tab_browsing_area	= _$(\"tab_browsing_area\");\n"
+"		t.test_font_size	= _$(\"test_font_size\");\n"
+"		a = t.textarea;\n"
+"\n"
+"		if(!s['is_editable'])\n"
+"			t.set_editable(false);\n"
+"\n"
+"		t.set_show_line_colors( s['show_line_colors'] );\n"
+"\n"
+"		if(syntax_selec= _$(\"syntax_selection\"))\n"
+"		{\n"
+"			// set up syntax selection list in the toolbar\n"
+"			for(var i=0; i<t.syntax_list.length; i++) {\n"
+"				var syntax= t.syntax_list[i];\n"
+"				var option= document.createElement(\"option\");\n"
+"				option.value= syntax;\n"
+"				if(syntax==s['syntax'])\n"
+"					option.selected= \"selected\";\n"
+"				dispSyntax	= parent.editAreaLoader.syntax_display_name[ syntax ];\n"
+"				option.innerHTML= typeof( dispSyntax ) == 'undefined' ? syntax.substring( 0, 1 ).toUpperCase() + syntax.substring( 1 ) : dispSyntax;//t.get_translation(\"syntax_\" + syntax, \"word\");\n"
+"				syntax_selec.appendChild(option);\n"
+"			}\n"
+"		}\n"
+"\n"
+"		// add plugins buttons in the toolbar\n"
+"		spans= parent.getChildren(_$(\"toolbar_1\"), \"span\", \"\", \"\", \"all\", -1);\n"
+"\n"
+"		for(var i=0; i<spans.length; i++){\n"
+"\n"
+"			id=spans[i].id.replace(/tmp_tool_(.*)/, \"$1\");\n"
+"			if(id!= spans[i].id){\n"
+"				for(var j in t.plugins){\n"
+"					if(typeof(t.plugins[j].get_control_html)==\"function\" ){\n"
+"						html=t.plugins[j].get_control_html(id);\n"
+"						if(html!=false){\n"
+"							html= t.get_translation(html, \"template\");\n"
+"							var new_span= document.createElement(\"span\");\n"
+"							new_span.innerHTML= html;\n"
+"							var father= spans[i].parentNode;\n"
+"							spans[i].parentNode.replaceChild(new_span, spans[i]);\n"
+"							break; // exit the for loop\n"
+"						}\n"
+"					}\n"
+"				}\n"
+"			}\n"
+"		}\n"
+"\n"
+"		// init datas\n"
+"		//a.value	= 'a';//editAreas[t.id][\"textarea\"].value;\n"
+"\n"
+"		if(s[\"debug\"])\n"
+"		{\n"
+"			t.debug=parent.document.getElementById(\"edit_area_debug_\"+t.id);\n"
+"		}\n"
+"		// init size\n"
+"		//this.update_size();\n"
+"\n"
+"		if(_$(\"redo\") != null)\n"
+"			t.switchClassSticky(_$(\"redo\"), 'editAreaButtonDisabled', true);\n"
+"\n"
+"		// insert css rules for highlight mode\n"
+"		if(typeof(parent.editAreaLoader.syntax[s[\"syntax\"]])!=\"undefined\"){\n"
+"			for(var i in parent.editAreaLoader.syntax){\n"
+"				if (typeof(parent.editAreaLoader.syntax[i][\"styles\"]) != \"undefined\"){\n"
+"					t.add_style(parent.editAreaLoader.syntax[i][\"styles\"]);\n"
+"				}\n"
+"			}\n"
+"		}\n"
+"\n"
+"		// init key events\n"
+"		if(t.isOpera)\n"
+"			_$(\"editor\").onkeypress	= keyDown;\n"
+"		else\n"
+"			_$(\"editor\").onkeydown	= keyDown;\n"
+"\n"
+"		for(var i=0; i<t.inlinePopup.length; i++){\n"
+"			if(t.isOpera)\n"
+"				_$(t.inlinePopup[i][\"popup_id\"]).onkeypress	= keyDown;\n"
+"			else\n"
+"				_$(t.inlinePopup[i][\"popup_id\"]).onkeydown	= keyDown;\n"
+"		}\n"
+"\n"
+"		if(s[\"allow_resize\"]==\"both\" || s[\"allow_resize\"]==\"x\" || s[\"allow_resize\"]==\"y\")\n"
+"			t.allow_resize(true);\n"
+"\n"
+"		parent.editAreaLoader.toggle(t.id, \"on\");\n"
+"		//a.focus();\n"
+"		// line selection init\n"
+"		t.change_smooth_selection_mode(editArea.smooth_selection);\n"
+"		// highlight\n"
+"		t.execCommand(\"change_highlight\", s[\"start_highlight\"]);\n"
+"\n"
+"		// get font size datas\n"
+"		t.set_font(editArea.settings[\"font_family\"], editArea.settings[\"font_size\"]);\n"
+"\n"
+"		// set unselectable text\n"
+"		children= parent.getChildren(document.body, \"\", \"selec\", \"none\", \"all\", -1);\n"
+"		for(var i=0; i<children.length; i++){\n"
+"			if(t.isIE)\n"
+"				children[i].unselectable = true; // IE\n"
+"			else\n"
+"				children[i].onmousedown= function(){return false};\n"
+"		/*	children[i].style.MozUserSelect = \"none\"; // Moz\n"
+"			children[i].style.KhtmlUserSelect = \"none\";  // Konqueror/Safari*/\n"
+"		}\n"
+"\n"
+"		a.spellcheck= s[\"gecko_spellcheck\"];\n"
+"\n"
+"		/** Browser specific style fixes **/\n"
+"\n"
+"		// fix rendering bug for highlighted lines beginning with no tabs\n"
+"		if( t.isFirefox >= '3' ) {\n"
+"			t.content_highlight.style.paddingLeft= \"1px\";\n"
+"			t.selection_field.style.paddingLeft= \"1px\";\n"
+"			t.selection_field_text.style.paddingLeft= \"1px\";\n"
+"		}\n"
+"\n"
+"		if(t.isIE && t.isIE < 8 ){\n"
+"			a.style.marginTop= \"-1px\";\n"
+"		}\n"
+"		/*\n"
+"		if(t.isOpera){\n"
+"			t.editor_area.style.position= \"absolute\";\n"
+"		}*/\n"
+"\n"
+"		if( t.isSafari ){\n"
+"			t.editor_area.style.position	= \"absolute\";\n"
+"			if( t.isSafari < 4.1) // fix for http://sourceforge.net/tracker/?func=detail&aid=3013420&group_id=164008&atid=829999\n"
+"				a.style.marginLeft		=\"-3px\";\n"
+"			if( t.isSafari < 3.2 ) // Safari 3.0 (3.1?)\n"
+"				a.style.marginTop	=\"1px\";\n"
+"		}\n"
+"\n"
+"		// si le textarea n'est pas grand, un click sous le textarea doit provoquer un focus sur le textarea\n"
+"		parent.editAreaLoader.add_event(t.result, \"click\", function(e){ if((e.target || e.srcElement)==editArea.result) { editArea.area_select(editArea.textarea.value.length, 0);}  });\n"
+"\n"
+"		if(s['is_multi_files']!=false)\n"
+"			t.open_file({'id': t.curr_file, 'text': ''});\n"
+"\n"
+"		t.set_word_wrap( s['word_wrap'] );\n"
+"\n"
+"		setTimeout(\"editArea.focus();editArea.manage_size();editArea.execCommand('EA_load');\", 10);\n"
+"		//start checkup routine\n"
+"		t.check_undo();\n"
+"		t.check_line_selection(true);\n"
+"		t.scroll_to_view();\n"
+"\n"
+"		for(var i in t.plugins){\n"
+"			if(typeof(t.plugins[i].onload)==\"function\")\n"
+"				t.plugins[i].onload();\n"
+"		}\n"
+"		if(s['fullscreen']==true)\n"
+"			t.toggle_full_screen(true);\n"
+"\n"
+"		//debugger;\n"
+"		parent.editAreaLoader.add_event(window, \"resize\", function() {\n"
+"			console.log('window: resize');\n"
+"			editArea.update_size(window);\n"
+"		});\n"
+"		parent.editAreaLoader.add_event(parent.window, \"resize\", function() {\n"
+"			console.log('parent.window: resize');\n"
+"			editArea.update_size(parent.window);\n"
+"		});\n"
+"		parent.editAreaLoader.add_event(top.window, \"resize\", function() {\n"
+"			console.log('top.window: resize');\n"
+"			editArea.update_size(top.window);\n"
+"		});\n"
+"		parent.editAreaLoader.add_event(window, \"unload\", function(){\n"
+"			// in case where editAreaLoader have been already cleaned\n"
+"			if( parent.editAreaLoader )\n"
+"			{\n"
+"				parent.editAreaLoader.remove_event(parent.window, \"resize\", function() {\n"
+"					console.log('loader:parent.window: resize');\n"
+"					editArea.update_size(parent.window);\n"
+"				});\n"
+"		  		parent.editAreaLoader.remove_event(top.window, \"resize\", function() {\n"
+"					console.log('loader:top.window: resize');\n"
+"					editArea.update_size(top.window);\n"
+"				});\n"
+"			}\n"
+"			if(editAreas[editArea.id] && editAreas[editArea.id][\"displayed\"]){\n"
+"				editArea.execCommand(\"EA_unload\");\n"
+"			}\n"
+"		});\n"
+"\n"
+"\n"
+"		/*date= new Date();\n"
+"		alert(date.getTime()- parent.editAreaLoader.start_time);*/\n"
+"	};\n"
+"\n"
+"\n"
+"\n"
+"	//called by the toggle_on\n"
+"	EditArea.prototype.update_size= function(opt_win){\n"
+"		var d=document,pd=parent.document,height,width,popup,maxLeft,maxTop;\n"
+"\n"
+"		console.log('edit_area:resize');\n"
+"\n"
+"		if( typeof editAreas != 'undefined' && editAreas[editArea.id] && editAreas[editArea.id][\"displayed\"]==true){\n"
+"			if(editArea.fullscreen['isFull']){\n"
+"				pd.getElementById(\"frame_\"+editArea.id).style.width  = pd.getElementsByTagName(\"html\")[0].clientWidth + \"px\";\n"
+"				pd.getElementById(\"frame_\"+editArea.id).style.height = pd.getElementsByTagName(\"html\")[0].clientHeight + \"px\";\n"
+"			}\n"
+"			console.log('parent w/h: ' + pd.getElementsByTagName(\"html\")[0].clientWidth + ', ' + pd.getElementsByTagName(\"html\")[0].clientHeight + ', ' +\n"
+"						'doc w/h: ' + d.getElementsByTagName(\"html\")[0].clientWidth + ', ' + d.getElementsByTagName(\"html\")[0].clientHeight);\n"
+"			/*\n"
+"			Calculate the position and size of the edit_area relative to the parent when we get here the first time around.\n"
+"\n"
+"			This info is used for future resizes, both shrink and grow.\n"
+"			*/\n"
+"			var t = this, s = t.settings;\n"
+"			var pw = pd.getElementsByTagName(\"html\")[0].clientWidth;\n"
+"			var ph = pd.getElementsByTagName(\"html\")[0].clientHeight;\n"
+"			var dw = d.getElementsByTagName(\"html\")[0].clientWidth;\n"
+"			var dh = d.getElementsByTagName(\"html\")[0].clientHeight;\n"
+"\n"
+"			if (typeof t.our_location == 'undefined')\n"
+"			{\n"
+"				t.our_location = {\n"
+"					delta_w: pw - dw,\n"
+"					delta_h: ph - dh,\n"
+"					parent_w: pw,\n"
+"					parent_h: ph,\n"
+"					doc_w: dw,\n"
+"					doc_h: dh\n"
+"				};\n"
+"				console.log('our deltas: w/h: ' + t.our_location.delta_w + ', ' + t.our_location.delta_h);\n"
+"			}\n"
+"			var o = t.our_location;\n"
+"\n"
+"			if(editArea.tab_browsing_area.style.display=='block' && ( !editArea.isIE || editArea.isIE >= 8 ) )\n"
+"			{\n"
+"				editArea.tab_browsing_area.style.height	= \"0px\";\n"
+"				editArea.tab_browsing_area.style.height	= (editArea.result.offsetTop - editArea.tab_browsing_area.offsetTop -1)+\"px\";\n"
+"			}\n"
+"\n"
+"			// todo:\n"
+"			// - resize area to max of original w+h when growing instead of shrinking\n"
+"			// - fix toolbar resize; buttons end up in the proper spot now, but the toolbar width is still overlarge.\n"
+"\n"
+"			height	= d.body.offsetHeight - editArea.get_all_toolbar_height() - 4;\n"
+"			// always ensure that the edit_area fits within the constraints of the screen, so that we can always see the toolbar, etc.:\n"
+"			if (height > ph - o.delta_h && !editArea.fullscreen['isFull'])\n"
+"			{\n"
+"				console.log('reduce height to fit: ' + height + ', ' + (pw - o.delta_h) + ', ' + s.min_height);\n"
+"				height = pw - o.delta_h;\n"
+"				if (height < s.min_height)\n"
+"					height = s.min_height;\n"
+"\n"
+"				pd.getElementById(\"frame_\"+editArea.id).style.height = (height + o.delta_h) + \"px\";\n"
+"			}\n"
+"			editArea.result.style.height	= height +\"px\";\n"
+"\n"
+"			width	= d.body.offsetWidth -2;\n"
+"			// always ensure that the edit_area fits within the constraints of the screen, so that we can always see the toolbar, etc.:\n"
+"			if (width > pw - o.delta_w && !editArea.fullscreen['isFull'])\n"
+"			{\n"
+"				console.log('reduce height to fit: ' + width + ', ' + (pw - o.delta_w) + ', ' + s.min_width);\n"
+"				width = pw - o.delta_w;\n"
+"				if (width < s.min_width)\n"
+"					width = s.min_width;\n"
+"\n"
+"				pd.getElementById(\"frame_\"+editArea.id).style.width = (width + o.delta_w) + \"px\";\n"
+"			}\n"
+"			editArea.result.style.width		= width+\"px\";\n"
+"			console.log(\"result h: \"+ height+\" w: \"+width+\", toolbar h: \"+editArea.get_all_toolbar_height()+\", body_h: \"+document.body.offsetHeight);\n"
+"\n"
+"			// check that the popups don't get out of the screen\n"
+"			for(i = 0; i < editArea.inlinePopup.length; i++)\n"
+"			{\n"
+"				popup	= _$(editArea.inlinePopup[i][\"popup_id\"]);\n"
+"				maxLeft	= d.body.offsetWidth - popup.offsetWidth;\n"
+"				maxTop	= d.body.offsetHeight - popup.offsetHeight;\n"
+"				if( popup.offsetTop > maxTop )\n"
+"					popup.style.top		= maxTop+\"px\";\n"
+"				if( popup.offsetLeft > maxLeft )\n"
+"					popup.style.left	= maxLeft+\"px\";\n"
+"			}\n"
+"\n"
+"			console.log(', wh: ' + width + ', ' + height);\n"
+"\n"
+"			editArea.manage_size( true );\n"
+"			editArea.fixLinesHeight( editArea.textarea.value, 0,-1);\n"
+"		}\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.manage_size= function(onlyOneTime){\n"
+"		if(!editAreas[this.id])\n"
+"			return false;\n"
+"\n"
+"		if(editAreas[this.id][\"displayed\"]==true && this.textareaFocused)\n"
+"		{\n"
+"			var area_height,resized= false;\n"
+"\n"
+"			//1) Manage display width\n"
+"			//1.1) Calc the new width to use for display\n"
+"			if( !this.settings['word_wrap'] )\n"
+"			{\n"
+"				var area_width= this.textarea.scrollWidth;\n"
+"				area_height= this.textarea.scrollHeight;\n"
+"				// bug on old opera versions\n"
+"				if(this.isOpera && this.isOpera < 9.6 ){\n"
+"					area_width=10000;\n"
+"				}\n"
+"				//1.2) the width is not the same, we must resize elements\n"
+"				if(this.textarea.previous_scrollWidth!=area_width)\n"
+"				{\n"
+"					this.container.style.width= area_width+\"px\";\n"
+"					this.textarea.style.width= area_width+\"px\";\n"
+"					this.content_highlight.style.width= area_width+\"px\";\n"
+"					this.textarea.previous_scrollWidth=area_width;\n"
+"					resized=true;\n"
+"				}\n"
+"			}\n"
+"			// manage wrap width\n"
+"			if( this.settings['word_wrap'] )\n"
+"			{\n"
+"				newW=this.textarea.offsetWidth;\n"
+"				if( this.isFirefox || this.isIE )\n"
+"					newW-=2;\n"
+"				if( this.isSafari )\n"
+"					newW-=6;\n"
+"				this.content_highlight.style.width=this.selection_field_text.style.width=this.selection_field.style.width=this.test_font_size.style.width=newW+\"px\";\n"
+"			}\n"
+"\n"
+"			//2) Manage display height\n"
+"			//2.1) Calc the new height to use for display\n"
+"			if( this.isOpera || this.isFirefox || this.isSafari ) {\n"
+"				area_height= this.getLinePosTop( this.last_selection[\"nb_line\"] + 1 );\n"
+"			} else {\n"
+"				area_height = this.textarea.scrollHeight;\n"
+"			}\n"
+"			//2.2) the width is not the same, we must resize elements\n"
+"			if(this.textarea.previous_scrollHeight!=area_height)\n"
+"			{\n"
+"				this.container.style.height= (area_height+2)+\"px\";\n"
+"				this.textarea.style.height= area_height+\"px\";\n"
+"				this.content_highlight.style.height= area_height+\"px\";\n"
+"				this.textarea.previous_scrollHeight= area_height;\n"
+"				resized=true;\n"
+"			}\n"
+"\n"
+"			//3) if there is new lines, we add new line numbers in the line numeration area\n"
+"			if(this.last_selection[\"nb_line\"] >= this.line_number)\n"
+"			{\n"
+"				var newLines= '', destDiv=_$(\"line_number\"), start=this.line_number, end=this.last_selection[\"nb_line\"]+100;\n"
+"				for( i = start+1; i < end; i++ )\n"
+"				{\n"
+"					newLines+='<div id=\"line_'+ i +'\">'+i+\"</div>\";\n"
+"					this.line_number++;\n"
+"				}\n"
+"				destDiv.innerHTML= destDiv.innerHTML + newLines;\n"
+"				if(this.settings['word_wrap']){\n"
+"					this.fixLinesHeight( this.textarea.value, start, -1 );\n"
+"				}\n"
+"			}\n"
+"\n"
+"			//4) be sure the text is well displayed\n"
+"			this.textarea.scrollTop=\"0\";  // fix for https://sourceforge.net/tracker/?func=detail&aid=3088085&group_id=164008&atid=829997\n"
+"			this.textarea.scrollLeft=\"0\";\n"
+"			if(resized==true){\n"
+"				this.scroll_to_view();\n"
+"			}\n"
+"		}\n"
+"\n"
+"		if(!onlyOneTime)\n"
+"			setTimeout(\"editArea.manage_size();\", 100);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.execCommand= function(cmd, param){\n"
+"\n"
+"		for(var i in this.plugins){\n"
+"			if(typeof(this.plugins[i].execCommand)==\"function\"){\n"
+"				if(!this.plugins[i].execCommand(cmd, param))\n"
+"					return;\n"
+"			}\n"
+"		}\n"
+"		switch(cmd){\n"
+"			case \"save\":\n"
+"				if(this.settings[\"save_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"save_callback\"]+\"('\"+ this.id +\"', editArea.textarea.value);\");\n"
+"				break;\n"
+"			case \"load\":\n"
+"				if(this.settings[\"load_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"load_callback\"]+\"('\"+ this.id +\"');\");\n"
+"				break;\n"
+"			case \"onchange\":\n"
+"				if(this.settings[\"change_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"change_callback\"]+\"('\"+ this.id +\"');\");\n"
+"				break;\n"
+"			case \"EA_load\":\n"
+"				if(this.settings[\"EA_load_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"EA_load_callback\"]+\"('\"+ this.id +\"');\");\n"
+"				break;\n"
+"			case \"EA_unload\":\n"
+"				if(this.settings[\"EA_unload_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"EA_unload_callback\"]+\"('\"+ this.id +\"');\");\n"
+"				break;\n"
+"			case \"toggle_on\":\n"
+"				if(this.settings[\"EA_toggle_on_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"EA_toggle_on_callback\"]+\"('\"+ this.id +\"');\");\n"
+"				break;\n"
+"			case \"toggle_off\":\n"
+"				if(this.settings[\"EA_toggle_off_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"EA_toggle_off_callback\"]+\"('\"+ this.id +\"');\");\n"
+"				break;\n"
+"			case \"re_sync\":\n"
+"				if(!this.do_highlight)\n"
+"					break;\n"
+"			case \"file_switch_on\":\n"
+"				if(this.settings[\"EA_file_switch_on_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"EA_file_switch_on_callback\"]+\"(param);\");\n"
+"				break;\n"
+"			case \"file_switch_off\":\n"
+"				if(this.settings[\"EA_file_switch_off_callback\"].length>0)\n"
+"					eval(\"parent.\"+this.settings[\"EA_file_switch_off_callback\"]+\"(param);\");\n"
+"				break;\n"
+"			case \"file_close\":\n"
+"				if(this.settings[\"EA_file_close_callback\"].length>0)\n"
+"					return eval(\"parent.\"+this.settings[\"EA_file_close_callback\"]+\"(param);\");\n"
+"				break;\n"
+"\n"
+"			default:\n"
+"				if(typeof(eval(\"editArea.\"+cmd))==\"function\")\n"
+"				{\n"
+"					if(this.settings[\"debug\"])\n"
+"						eval(\"editArea.\"+ cmd +\"(param);\");\n"
+"					else\n"
+"						try{eval(\"editArea.\"+ cmd +\"(param);\");}catch(e){};\n"
+"				}\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.get_translation= function(word, mode){\n"
+"		if(mode==\"template\")\n"
+"			return parent.editAreaLoader.translate(word, this.settings[\"language\"], mode);\n"
+"		else\n"
+"			return parent.editAreaLoader.get_word_translation(word, this.settings[\"language\"]);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.add_plugin= function(plug_name, plug_obj){\n"
+"		for(var i=0; i<this.settings[\"plugins\"].length; i++){\n"
+"			if(this.settings[\"plugins\"][i]==plug_name){\n"
+"				this.plugins[plug_name]=plug_obj;\n"
+"				plug_obj.baseURL=parent.editAreaLoader.baseURL + \"plugins/\" + plug_name + \"/\";\n"
+"				if( typeof(plug_obj.init)==\"function\" )\n"
+"					plug_obj.init();\n"
+"			}\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.load_css= function(url){\n"
+"		try{\n"
+"			link = document.createElement(\"link\");\n"
+"			link.type = \"text/css\";\n"
+"			link.rel= \"stylesheet\";\n"
+"			link.media=\"all\";\n"
+"			link.href = url;\n"
+"			head = document.getElementsByTagName(\"head\");\n"
+"			head[0].appendChild(link);\n"
+"		}catch(e){\n"
+"			document.write(\"<link href='\"+ url +\"' rel='stylesheet' type='text/css' />\");\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.load_script= function(url){\n"
+"		try{\n"
+"			script = document.createElement(\"script\");\n"
+"			script.type = \"text/javascript\";\n"
+"			script.src  = url;\n"
+"			script.charset= \"UTF-8\";\n"
+"			head = document.getElementsByTagName(\"head\");\n"
+"			head[0].appendChild(script);\n"
+"		}catch(e){\n"
+"			document.write(\"<script type='text/javascript' src='\" + url + \"' charset=\\\"UTF-8\\\"><\"+\"/script>\");\n"
+"		}\n"
+"	};\n"
+"\n"
+"	// add plugin translation to language translation array\n"
+"	EditArea.prototype.add_lang= function(language, values){\n"
+"		if(!parent.editAreaLoader.lang[language])\n"
+"			parent.editAreaLoader.lang[language]={};\n"
+"		for(var i in values)\n"
+"			parent.editAreaLoader.lang[language][i]= values[i];\n"
+"	};\n"
+"\n"
+"	// short cut for document.getElementById()\n"
+"	function _$(id){return document.getElementById( id );};\n"
+"\n"
+"	var editArea = new EditArea();\n"
+"	parent.editAreaLoader.add_event(window, \"load\", init);\n"
+"\n"
+"	function init(){\n"
+"		setTimeout(\"editArea.init();  \", 10);\n"
+"	};\n"
+"	EditArea.prototype.focus = function() {\n"
+"		this.textarea.focus();\n"
+"		this.textareaFocused=true;\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.check_line_selection= function(timer_checkup){\n"
+"		var changes, infos, new_top, new_width,i;\n"
+"		\n"
+"		var t1=t2=t2_1=t3=tLines=tend= new Date().getTime();\n"
+"		// l'editeur n'existe plus => on quitte\n"
+"		if(!editAreas[this.id])\n"
+"			return false;\n"
+"		\n"
+"		if(!this.smooth_selection && !this.do_highlight)\n"
+"		{\n"
+"			//do nothing\n"
+"		}\n"
+"		else if(this.textareaFocused && editAreas[this.id][\"displayed\"]==true && this.isResizing==false)\n"
+"		{\n"
+"			infos	= this.get_selection_infos();\n"
+"			changes	= this.checkTextEvolution( typeof( this.last_selection['full_text'] ) == 'undefined' ? '' : this.last_selection['full_text'], infos['full_text'] );\n"
+"		\n"
+"			t2= new Date().getTime();\n"
+"			\n"
+"			// if selection change\n"
+"			if(this.last_selection[\"line_start\"] != infos[\"line_start\"] || this.last_selection[\"line_nb\"] != infos[\"line_nb\"] || infos[\"full_text\"] != this.last_selection[\"full_text\"] || this.reload_highlight || this.last_selection[\"selectionStart\"] != infos[\"selectionStart\"] || this.last_selection[\"selectionEnd\"] != infos[\"selectionEnd\"] || !timer_checkup )\n"
+"			{\n"
+"				// move and adjust text selection elements\n"
+"				new_top		= this.getLinePosTop( infos[\"line_start\"] );\n"
+"				new_width	= Math.max(this.textarea.scrollWidth, this.container.clientWidth -50);\n"
+"				this.selection_field.style.top=this.selection_field_text.style.top=new_top+\"px\";\n"
+"				if(!this.settings['word_wrap']){	\n"
+"					this.selection_field.style.width=this.selection_field_text.style.width=this.test_font_size.style.width=new_width+\"px\";\n"
+"				}\n"
+"				\n"
+"				// usefull? => _$(\"cursor_pos\").style.top=new_top+\"px\";	\n"
+"		\n"
+"				if(this.do_highlight==true)\n"
+"				{\n"
+"					// fill selection elements\n"
+"					var curr_text	= infos[\"full_text\"].split(\"\\n\");\n"
+"					var content		= \"\";\n"
+"					//alert(\"length: \"+curr_text.length+ \" i: \"+ Math.max(0,infos[\"line_start\"]-1)+ \" end: \"+Math.min(curr_text.length, infos[\"line_start\"]+infos[\"line_nb\"]-1)+ \" line: \"+infos[\"line_start\"]+\" [0]: \"+curr_text[0]+\" [1]: \"+curr_text[1]);\n"
+"					var start		= Math.max(0,infos[\"line_start\"]-1);\n"
+"					var end			= Math.min(curr_text.length, infos[\"line_start\"]+infos[\"line_nb\"]-1);\n"
+"					\n"
+"					//curr_text[start]= curr_text[start].substr(0,infos[\"curr_pos\"]-1) +\"¤_overline_¤\"+ curr_text[start].substr(infos[\"curr_pos\"]-1);\n"
+"					for(i=start; i< end; i++){\n"
+"						content+= curr_text[i]+\"\\n\";	\n"
+"					}\n"
+"					\n"
+"					// add special chars arround selected characters\n"
+"					selLength	= infos['selectionEnd'] - infos['selectionStart'];\n"
+"					content		= content.substr( 0, infos[\"curr_pos\"] - 1 ) + \"\\r\\r\" + content.substr( infos[\"curr_pos\"] - 1, selLength ) + \"\\r\\r\" + content.substr( infos[\"curr_pos\"] - 1 + selLength );\n"
+"					content		= '<span>'+ content.replace(/&/g,\"&amp;\").replace(/</g,\"&lt;\").replace(/>/g,\"&gt;\").replace(\"\\r\\r\", '</span><strong>').replace(\"\\r\\r\", '</strong><span>') +'</span>';\n"
+"					\n"
+"					if( this.isIE || ( this.isOpera && this.isOpera < 9.6 ) ) {\n"
+"						this.selection_field.innerHTML= \"<pre>\" + content.replace(/^\\r?\\n/, \"<br>\") + \"</pre>\";\n"
+"					} else {\n"
+"						this.selection_field.innerHTML= content;\n"
+"					}\n"
+"					this.selection_field_text.innerHTML = this.selection_field.innerHTML;\n"
+"					t2_1 = new Date().getTime();\n"
+"					// check if we need to update the highlighted background \n"
+"					if(this.reload_highlight || (infos[\"full_text\"] != this.last_text_to_highlight && (this.last_selection[\"line_start\"]!=infos[\"line_start\"] || this.show_line_colors || this.settings['word_wrap'] || this.last_selection[\"line_nb\"]!=infos[\"line_nb\"] || this.last_selection[\"nb_line\"]!=infos[\"nb_line\"]) ) )\n"
+"					{\n"
+"						this.maj_highlight(infos);\n"
+"					}\n"
+"				}		\n"
+"			}\n"
+"			t3= new Date().getTime();\n"
+"			\n"
+"			// manage line heights\n"
+"			if( this.settings['word_wrap'] && infos[\"full_text\"] != this.last_selection[\"full_text\"])\n"
+"			{\n"
+"				// refresh only 1 line if text change concern only one line and that the total line number has not changed\n"
+"				if( changes.newText.split(\"\\n\").length == 1 && this.last_selection['nb_line'] && infos['nb_line'] == this.last_selection['nb_line'] )\n"
+"				{\n"
+"					this.fixLinesHeight( infos['full_text'], changes.lineStart, changes.lineStart );\n"
+"				}\n"
+"				else\n"
+"				{\n"
+"					this.fixLinesHeight( infos['full_text'], changes.lineStart, -1 );\n"
+"				}\n"
+"			}\n"
+"		\n"
+"			tLines= new Date().getTime();\n"
+"			// manage bracket finding\n"
+"			if( infos[\"line_start\"] != this.last_selection[\"line_start\"] || infos[\"curr_pos\"] != this.last_selection[\"curr_pos\"] || infos[\"full_text\"].length!=this.last_selection[\"full_text\"].length || this.reload_highlight || !timer_checkup )\n"
+"			{\n"
+"				// move _cursor_pos\n"
+"				var selec_char= infos[\"curr_line\"].charAt(infos[\"curr_pos\"]-1);\n"
+"				var no_real_move=true;\n"
+"				if(infos[\"line_nb\"]==1 && (this.assocBracket[selec_char] || this.revertAssocBracket[selec_char]) ){\n"
+"					\n"
+"					no_real_move=false;					\n"
+"					//findEndBracket(infos[\"line_start\"], infos[\"curr_pos\"], selec_char);\n"
+"					if(this.findEndBracket(infos, selec_char) === true){\n"
+"						_$(\"end_bracket\").style.visibility	=\"visible\";\n"
+"						_$(\"cursor_pos\").style.visibility	=\"visible\";\n"
+"						_$(\"cursor_pos\").innerHTML			= selec_char;\n"
+"						_$(\"end_bracket\").innerHTML			= (this.assocBracket[selec_char] || this.revertAssocBracket[selec_char]);\n"
+"					}else{\n"
+"						_$(\"end_bracket\").style.visibility	=\"hidden\";\n"
+"						_$(\"cursor_pos\").style.visibility	=\"hidden\";\n"
+"					}\n"
+"				}else{\n"
+"					_$(\"cursor_pos\").style.visibility	=\"hidden\";\n"
+"					_$(\"end_bracket\").style.visibility	=\"hidden\";\n"
+"				}\n"
+"				//alert(\"move cursor\");\n"
+"				this.displayToCursorPosition(\"cursor_pos\", infos[\"line_start\"], infos[\"curr_pos\"]-1, infos[\"curr_line\"], no_real_move);\n"
+"				if(infos[\"line_nb\"]==1 && infos[\"line_start\"]!=this.last_selection[\"line_start\"])\n"
+"					this.scroll_to_view();\n"
+"			}\n"
+"			this.last_selection=infos;\n"
+"		}\n"
+"		\n"
+"		tend= new Date().getTime();\n"
+"		//if( (tend-t1) > 7 )\n"
+"		//	console.log( \"tps total: \"+ (tend-t1) + \" tps get_infos: \"+ (t2-t1)+ \" tps selec: \"+ (t2_1-t2)+ \" tps highlight: \"+ (t3-t2_1) +\" tps lines: \"+ (tLines-t3) +\" tps cursor+lines: \"+ (tend-tLines)+\" \\n\" );\n"
+"		\n"
+"		\n"
+"		if(timer_checkup){\n"
+"			setTimeout(\"editArea.check_line_selection(true)\", this.check_line_selection_timer);\n"
+"		}\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.get_selection_infos= function(){\n"
+"		var sel={}, start, end, len, str;\n"
+"	\n"
+"		this.getIESelection();\n"
+"		start	= this.textarea.selectionStart;\n"
+"		end		= this.textarea.selectionEnd;		\n"
+"		\n"
+"		if( this.last_selection[\"selectionStart\"] == start && this.last_selection[\"selectionEnd\"] == end && this.last_selection[\"full_text\"] == this.textarea.value )\n"
+"		{	\n"
+"			return this.last_selection;\n"
+"		}\n"
+"			\n"
+"		if(this.tabulation!=\"\t\" && this.textarea.value.indexOf(\"\t\")!=-1) \n"
+"		{	// can append only after copy/paste \n"
+"			len		= this.textarea.value.length;\n"
+"			this.textarea.value	= this.replace_tab(this.textarea.value);\n"
+"			start	= end	= start+(this.textarea.value.length-len);\n"
+"			this.area_select( start, 0 );\n"
+"		}\n"
+"		\n"
+"		sel[\"selectionStart\"]	= start;\n"
+"		sel[\"selectionEnd\"]		= end;		\n"
+"		sel[\"full_text\"]		= this.textarea.value;\n"
+"		sel[\"line_start\"]		= 1;\n"
+"		sel[\"line_nb\"]			= 1;\n"
+"		sel[\"curr_pos\"]			= 0;\n"
+"		sel[\"curr_line\"]		= \"\";\n"
+"		sel[\"indexOfCursor\"]	= 0;\n"
+"		sel[\"selec_direction\"]	= this.last_selection[\"selec_direction\"];\n"
+"\n"
+"		//return sel;	\n"
+"		var splitTab= sel[\"full_text\"].split(\"\\n\");\n"
+"		var nbLine	= Math.max(0, splitTab.length);		\n"
+"		var nbChar	= Math.max(0, sel[\"full_text\"].length - (nbLine - 1));	// (remove \\n caracters from the count)\n"
+"		if( sel[\"full_text\"].indexOf(\"\\r\") != -1 )\n"
+"			nbChar	= nbChar - ( nbLine - 1 );		// (remove \\r caracters from the count)\n"
+"		sel[\"nb_line\"]	= nbLine;		\n"
+"		sel[\"nb_char\"]	= nbChar;\n"
+"	\n"
+"		if(start>0){\n"
+"			str					= sel[\"full_text\"].substr(0,start);\n"
+"			sel[\"curr_pos\"]		= start - str.lastIndexOf(\"\\n\");\n"
+"			sel[\"line_start\"]	= Math.max(1, str.split(\"\\n\").length);\n"
+"		}else{\n"
+"			sel[\"curr_pos\"]=1;\n"
+"		}\n"
+"		if(end>start){\n"
+"			sel[\"line_nb\"]=sel[\"full_text\"].substring(start,end).split(\"\\n\").length;\n"
+"		}\n"
+"		sel[\"indexOfCursor\"]=start;		\n"
+"		sel[\"curr_line\"]=splitTab[Math.max(0,sel[\"line_start\"]-1)];\n"
+"	\n"
+"		// determine in which direction the selection grow\n"
+"		if(sel[\"selectionStart\"] == this.last_selection[\"selectionStart\"]){\n"
+"			if(sel[\"selectionEnd\"]>this.last_selection[\"selectionEnd\"])\n"
+"				sel[\"selec_direction\"]= \"down\";\n"
+"			else if(sel[\"selectionEnd\"] == this.last_selection[\"selectionStart\"])\n"
+"				sel[\"selec_direction\"]= this.last_selection[\"selec_direction\"];\n"
+"		}else if(sel[\"selectionStart\"] == this.last_selection[\"selectionEnd\"] && sel[\"selectionEnd\"]>this.last_selection[\"selectionEnd\"]){\n"
+"			sel[\"selec_direction\"]= \"down\";\n"
+"		}else{\n"
+"			sel[\"selec_direction\"]= \"up\";\n"
+"		}\n"
+"		\n"
+"		_$(\"nbLine\").innerHTML	= nbLine;		\n"
+"		_$(\"nbChar\").innerHTML	= nbChar;		\n"
+"		_$(\"linePos\").innerHTML	= sel[\"line_start\"];\n"
+"		_$(\"currPos\").innerHTML	= sel[\"curr_pos\"];\n"
+"\n"
+"		return sel;		\n"
+"	};\n"
+"	\n"
+"	// set IE position in Firefox mode (textarea.selectionStart and textarea.selectionEnd)\n"
+"	EditArea.prototype.getIESelection= function(){\n"
+"		var selectionStart, selectionEnd, range, stored_range;\n"
+"		\n"
+"		if( !this.isIE )\n"
+"			return false;\n"
+"			\n"
+"		// make it work as nowrap mode (easier for range manipulation with lineHeight)\n"
+"		if( this.settings['word_wrap'] )\n"
+"			this.textarea.wrap='off';\n"
+"			\n"
+"		try{\n"
+"			range			= document.selection.createRange();\n"
+"			stored_range	= range.duplicate();\n"
+"			stored_range.moveToElementText( this.textarea );\n"
+"			stored_range.setEndPoint( 'EndToEnd', range );\n"
+"			if( stored_range.parentElement() != this.textarea )\n"
+"				throw \"invalid focus\";\n"
+"				\n"
+"			// the range don't take care of empty lines in the end of the selection\n"
+"			var scrollTop	= this.result.scrollTop + document.body.scrollTop;\n"
+"			var relative_top= range.offsetTop - parent.calculeOffsetTop(this.textarea) + scrollTop;\n"
+"			var line_start	= Math.round((relative_top / this.lineHeight) +1);\n"
+"			var line_nb		= Math.round( range.boundingHeight / this.lineHeight );\n"
+"						\n"
+"			selectionStart	= stored_range.text.length - range.text.length;		\n"
+"			selectionStart	+= ( line_start - this.textarea.value.substr(0, selectionStart).split(\"\\n\").length)*2;		// count missing empty \\r to the selection\n"
+"			selectionStart	-= ( line_start - this.textarea.value.substr(0, selectionStart).split(\"\\n\").length ) * 2;\n"
+"			\n"
+"			selectionEnd	= selectionStart + range.text.length;		\n"
+"			selectionEnd	+= (line_start + line_nb - 1 - this.textarea.value.substr(0, selectionEnd ).split(\"\\n\").length)*2;			\n"
+"		\n"
+"			this.textarea.selectionStart	= selectionStart;\n"
+"			this.textarea.selectionEnd		= selectionEnd;\n"
+"		}\n"
+"		catch(e){}\n"
+"		\n"
+"		// restore wrap mode\n"
+"		if( this.settings['word_wrap'] )\n"
+"			this.textarea.wrap='soft';\n"
+"	};\n"
+"	\n"
+"	// select the text for IE (and take care of \\r caracters)\n"
+"	EditArea.prototype.setIESelection= function(){\n"
+"		var a = this.textarea, nbLineStart, nbLineEnd, range;\n"
+"		\n"
+"		if( !this.isIE )\n"
+"			return false;\n"
+"		\n"
+"		nbLineStart	= a.value.substr(0, a.selectionStart).split(\"\\n\").length - 1;\n"
+"		nbLineEnd 	= a.value.substr(0, a.selectionEnd).split(\"\\n\").length - 1;\n"
+"		range		= document.selection.createRange();\n"
+"		range.moveToElementText( a );\n"
+"		range.setEndPoint( 'EndToStart', range );\n"
+"		\n"
+"		range.moveStart('character', a.selectionStart - nbLineStart);\n"
+"		range.moveEnd('character', a.selectionEnd - nbLineEnd - (a.selectionStart - nbLineStart)  );\n"
+"		range.select();\n"
+"	};\n"
+"	\n"
+"	\n"
+"	\n"
+"	EditArea.prototype.checkTextEvolution=function(lastText,newText){\n"
+"		// ch will contain changes datas\n"
+"		var ch={},baseStep=200, cpt=0, end, step,tStart=new Date().getTime();\n"
+"	\n"
+"		end		= Math.min(newText.length, lastText.length);\n"
+"        step	= baseStep;\n"
+"        // find how many chars are similar at the begin of the text						\n"
+"		while( cpt<end && step>=1 ){\n"
+"            if(lastText.substr(cpt, step) == newText.substr(cpt, step)){\n"
+"                cpt+= step;\n"
+"            }else{\n"
+"                step= Math.floor(step/2);\n"
+"            }\n"
+"		}\n"
+"		\n"
+"		ch.posStart	= cpt;\n"
+"		ch.lineStart= newText.substr(0, ch.posStart).split(\"\\n\").length -1;						\n"
+"		\n"
+"		cpt_last	= lastText.length;\n"
+"        cpt			= newText.length;\n"
+"        step		= baseStep;			\n"
+"        // find how many chars are similar at the end of the text						\n"
+"		while( cpt>=0 && cpt_last>=0 && step>=1 ){\n"
+"            if(lastText.substr(cpt_last-step, step) == newText.substr(cpt-step, step)){\n"
+"                cpt-= step;\n"
+"                cpt_last-= step;\n"
+"            }else{\n"
+"                step= Math.floor(step/2);\n"
+"            }\n"
+"		}\n"
+"		\n"
+"		ch.posNewEnd	= cpt;\n"
+"		ch.posLastEnd	= cpt_last;\n"
+"		if(ch.posNewEnd<=ch.posStart){\n"
+"			if(lastText.length < newText.length){\n"
+"				ch.posNewEnd= ch.posStart + newText.length - lastText.length;\n"
+"				ch.posLastEnd= ch.posStart;\n"
+"			}else{\n"
+"				ch.posLastEnd= ch.posStart + lastText.length - newText.length;\n"
+"				ch.posNewEnd= ch.posStart;\n"
+"			}\n"
+"		} \n"
+"		ch.newText		= newText.substring(ch.posStart, ch.posNewEnd);\n"
+"		ch.lastText		= lastText.substring(ch.posStart, ch.posLastEnd);			            \n"
+"		\n"
+"		ch.lineNewEnd	= newText.substr(0, ch.posNewEnd).split(\"\\n\").length -1;\n"
+"		ch.lineLastEnd	= lastText.substr(0, ch.posLastEnd).split(\"\\n\").length -1;\n"
+"		\n"
+"		ch.newTextLine	= newText.split(\"\\n\").slice(ch.lineStart, ch.lineNewEnd+1).join(\"\\n\");\n"
+"		ch.lastTextLine	= lastText.split(\"\\n\").slice(ch.lineStart, ch.lineLastEnd+1).join(\"\\n\");\n"
+"		//console.log( ch );\n"
+"		return ch;	\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.tab_selection= function(){\n"
+"		if(this.is_tabbing)\n"
+"			return;\n"
+"		this.is_tabbing=true;\n"
+"		//infos=getSelectionInfos();\n"
+"		//if( document.selection ){\n"
+"		this.getIESelection();\n"
+"		/* Insertion du code de formatage */\n"
+"		var start = this.textarea.selectionStart;\n"
+"		var end = this.textarea.selectionEnd;\n"
+"		var insText = this.textarea.value.substring(start, end);\n"
+"		\n"
+"		/* Insert tabulation and ajust cursor position */\n"
+"		var pos_start=start;\n"
+"		var pos_end=end;\n"
+"		if (insText.length == 0) {\n"
+"			// if only one line selected\n"
+"			this.textarea.value = this.textarea.value.substr(0, start) + this.tabulation + this.textarea.value.substr(end);\n"
+"			pos_start = start + this.tabulation.length;\n"
+"			pos_end=pos_start;\n"
+"		} else {\n"
+"			start= Math.max(0, this.textarea.value.substr(0, start).lastIndexOf(\"\\n\")+1);\n"
+"			endText=this.textarea.value.substr(end);\n"
+"			startText=this.textarea.value.substr(0, start);\n"
+"			tmp= this.textarea.value.substring(start, end).split(\"\\n\");\n"
+"			insText= this.tabulation+tmp.join(\"\\n\"+this.tabulation);\n"
+"			this.textarea.value = startText + insText + endText;\n"
+"			pos_start = start;\n"
+"			pos_end= this.textarea.value.indexOf(\"\\n\", startText.length + insText.length);\n"
+"			if(pos_end==-1)\n"
+"				pos_end=this.textarea.value.length;\n"
+"			//pos = start + repdeb.length + insText.length + ;\n"
+"		}\n"
+"		this.textarea.selectionStart = pos_start;\n"
+"		this.textarea.selectionEnd = pos_end;\n"
+"		\n"
+"		//if( document.selection ){\n"
+"		if(this.isIE)\n"
+"		{\n"
+"			this.setIESelection();\n"
+"			setTimeout(\"editArea.is_tabbing=false;\", 100);	// IE can't accept to make 2 tabulation without a little break between both\n"
+"		}\n"
+"		else\n"
+"		{ \n"
+"			this.is_tabbing=false;\n"
+"		}	\n"
+"		\n"
+"  	};\n"
+"	\n"
+"	EditArea.prototype.invert_tab_selection= function(){\n"
+"		var t=this, a=this.textarea;\n"
+"		if(t.is_tabbing)\n"
+"			return;\n"
+"		t.is_tabbing=true;\n"
+"		//infos=getSelectionInfos();\n"
+"		//if( document.selection ){\n"
+"		t.getIESelection();\n"
+"		\n"
+"		var start	= a.selectionStart;\n"
+"		var end		= a.selectionEnd;\n"
+"		var insText	= a.value.substring(start, end);\n"
+"		\n"
+"		/* Tab remove and cursor seleciton adjust */\n"
+"		var pos_start=start;\n"
+"		var pos_end=end;\n"
+"		if (insText.length == 0) {\n"
+"			if(a.value.substring(start-t.tabulation.length, start)==t.tabulation)\n"
+"			{\n"
+"				a.value		= a.value.substr(0, start-t.tabulation.length) + a.value.substr(end);\n"
+"				pos_start	= Math.max(0, start-t.tabulation.length);\n"
+"				pos_end		= pos_start;\n"
+"			}	\n"
+"			/*\n"
+"			a.value = a.value.substr(0, start) + t.tabulation + insText + a.value.substr(end);\n"
+"			pos_start = start + t.tabulation.length;\n"
+"			pos_end=pos_start;*/\n"
+"		} else {\n"
+"			start		= a.value.substr(0, start).lastIndexOf(\"\\n\")+1;\n"
+"			endText		= a.value.substr(end);\n"
+"			startText	= a.value.substr(0, start);\n"
+"			tmp			= a.value.substring(start, end).split(\"\\n\");\n"
+"			insText		= \"\";\n"
+"			for(i=0; i<tmp.length; i++){				\n"
+"				for(j=0; j<t.tab_nb_char; j++){\n"
+"					if(tmp[i].charAt(0)==\"\t\"){\n"
+"						tmp[i]=tmp[i].substr(1);\n"
+"						j=t.tab_nb_char;\n"
+"					}else if(tmp[i].charAt(0)==\" \")\n"
+"						tmp[i]=tmp[i].substr(1);\n"
+"				}		\n"
+"				insText+=tmp[i];\n"
+"				if(i<tmp.length-1)\n"
+"					insText+=\"\\n\";\n"
+"			}\n"
+"			//insText+=\"_\";\n"
+"			a.value		= startText + insText + endText;\n"
+"			pos_start	= start;\n"
+"			pos_end		= a.value.indexOf(\"\\n\", startText.length + insText.length);\n"
+"			if(pos_end==-1)\n"
+"				pos_end=a.value.length;\n"
+"			//pos = start + repdeb.length + insText.length + ;\n"
+"		}\n"
+"		a.selectionStart = pos_start;\n"
+"		a.selectionEnd = pos_end;\n"
+"		\n"
+"		//if( document.selection ){\n"
+"		if(t.isIE){\n"
+"			// select the text for IE\n"
+"			t.setIESelection();\n"
+"			setTimeout(\"editArea.is_tabbing=false;\", 100);	// IE can accept to make 2 tabulation without a little break between both\n"
+"		}else\n"
+"			t.is_tabbing=false;\n"
+"  	};\n"
+"	\n"
+"	EditArea.prototype.press_enter= function(){		\n"
+"		if(!this.smooth_selection)\n"
+"			return false;\n"
+"		this.getIESelection();\n"
+"		var scrollTop= this.result.scrollTop;\n"
+"		var scrollLeft= this.result.scrollLeft;\n"
+"		var start=this.textarea.selectionStart;\n"
+"		var end= this.textarea.selectionEnd;\n"
+"		var start_last_line= Math.max(0 , this.textarea.value.substring(0, start).lastIndexOf(\"\\n\") + 1 );\n"
+"		var begin_line= this.textarea.value.substring(start_last_line, start).replace(/^([ \t]*).*/gm, \"$1\");\n"
+"		var lineStart = this.textarea.value.substring(0, start).split(\"\\n\").length;\n"
+"		if(begin_line==\"\\n\" || begin_line==\"\\r\" || begin_line.length==0)\n"
+"		{\n"
+"			return false;\n"
+"		}\n"
+"			\n"
+"		if(this.isIE || ( this.isOpera && this.isOpera < 9.6 ) ){\n"
+"			begin_line=\"\\r\\n\"+ begin_line;\n"
+"		}else{\n"
+"			begin_line=\"\\n\"+ begin_line;\n"
+"		}	\n"
+"		//alert(start_last_line+\" strat: \"+start +\"\\n\"+this.textarea.value.substring(start_last_line, start)+\"\\n_\"+begin_line+\"_\")\n"
+"		this.textarea.value= this.textarea.value.substring(0, start) + begin_line + this.textarea.value.substring(end);\n"
+"		\n"
+"		this.area_select(start+ begin_line.length ,0);\n"
+"		// during this process IE scroll back to the top of the textarea\n"
+"		if(this.isIE){\n"
+"			this.result.scrollTop	= scrollTop;\n"
+"			this.result.scrollLeft	= scrollLeft;\n"
+"		}\n"
+"		return true;\n"
+"		\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.findEndBracket= function(infos, bracket){\n"
+"			\n"
+"		var start=infos[\"indexOfCursor\"];\n"
+"		var normal_order=true;\n"
+"		//curr_text=infos[\"full_text\"].split(\"\\n\");\n"
+"		if(this.assocBracket[bracket])\n"
+"			endBracket=this.assocBracket[bracket];\n"
+"		else if(this.revertAssocBracket[bracket]){\n"
+"			endBracket=this.revertAssocBracket[bracket];\n"
+"			normal_order=false;\n"
+"		}	\n"
+"		var end=-1;\n"
+"		var nbBracketOpen=0;\n"
+"		\n"
+"		for(var i=start; i<infos[\"full_text\"].length && i>=0; ){\n"
+"			if(infos[\"full_text\"].charAt(i)==endBracket){				\n"
+"				nbBracketOpen--;\n"
+"				if(nbBracketOpen<=0){\n"
+"					//i=infos[\"full_text\"].length;\n"
+"					end=i;\n"
+"					break;\n"
+"				}\n"
+"			}else if(infos[\"full_text\"].charAt(i)==bracket)\n"
+"				nbBracketOpen++;\n"
+"			if(normal_order)\n"
+"				i++;\n"
+"			else\n"
+"				i--;\n"
+"		}\n"
+"		\n"
+"		//end=infos[\"full_text\"].indexOf(\"}\", start);\n"
+"		if(end==-1)\n"
+"			return false;	\n"
+"		var endLastLine=infos[\"full_text\"].substr(0, end).lastIndexOf(\"\\n\");			\n"
+"		if(endLastLine==-1)\n"
+"			line=1;\n"
+"		else\n"
+"			line= infos[\"full_text\"].substr(0, endLastLine).split(\"\\n\").length + 1;\n"
+"					\n"
+"		var curPos= end - endLastLine - 1;\n"
+"		var endLineLength	= infos[\"full_text\"].substring(end).split(\"\\n\")[0].length;\n"
+"		this.displayToCursorPosition(\"end_bracket\", line, curPos, infos[\"full_text\"].substring(endLastLine +1, end + endLineLength));\n"
+"		return true;\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.displayToCursorPosition= function(id, start_line, cur_pos, lineContent, no_real_move){\n"
+"		var elem,dest,content,posLeft=0,posTop,fixPadding,topOffset,endElem;	\n"
+"\n"
+"		elem		= this.test_font_size;\n"
+"		dest		= _$(id);\n"
+"		content		= \"<span id='test_font_size_inner'>\"+lineContent.substr(0, cur_pos).replace(/&/g,\"&amp;\").replace(/</g,\"&lt;\")+\"</span><span id='endTestFont'>\"+lineContent.substr(cur_pos).replace(/&/g,\"&amp;\").replace(/</g,\"&lt;\")+\"</span>\";\n"
+"		if( this.isIE || ( this.isOpera && this.isOpera < 9.6 ) ) {\n"
+"			elem.innerHTML= \"<pre>\" + content.replace(/^\\r?\\n/, \"<br>\") + \"</pre>\";\n"
+"		} else {\n"
+"			elem.innerHTML= content;\n"
+"		}\n"
+"		\n"
+"\n"
+"		endElem		= _$('endTestFont');\n"
+"		topOffset	= endElem.offsetTop;\n"
+"		fixPadding	= parseInt( this.content_highlight.style.paddingLeft.replace(\"px\", \"\") );\n"
+"		posLeft 	= 45 + endElem.offsetLeft + ( !isNaN( fixPadding ) && topOffset > 0 ? fixPadding : 0 );\n"
+"		posTop		= this.getLinePosTop( start_line ) + topOffset;// + Math.floor( ( endElem.offsetHeight - 1 ) / this.lineHeight ) * this.lineHeight;\n"
+"	\n"
+"		// detect the case where the span start on a line but has no display on it\n"
+"		if( this.isIE && cur_pos > 0 && endElem.offsetLeft == 0 )\n"
+"		{\n"
+"			posTop	+=	this.lineHeight;\n"
+"		}\n"
+"		if(no_real_move!=true){	// when the cursor is hidden no need to move him\n"
+"			dest.style.top=posTop+\"px\";\n"
+"			dest.style.left=posLeft+\"px\";	\n"
+"		}\n"
+"		// usefull for smarter scroll\n"
+"		dest.cursor_top=posTop;\n"
+"		dest.cursor_left=posLeft;	\n"
+"	//	_$(id).style.marginLeft=posLeft+\"px\";\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.getLinePosTop= function(start_line){\n"
+"		var elem= _$('line_'+ start_line), posTop=0;\n"
+"		if( elem )\n"
+"			posTop	= elem.offsetTop;\n"
+"		else\n"
+"			posTop	= this.lineHeight * (start_line-1);\n"
+"		return posTop;\n"
+"	};\n"
+"	\n"
+"	\n"
+"	// return the dislpayed height of a text (take word-wrap into account)\n"
+"	EditArea.prototype.getTextHeight= function(text){\n"
+"		var t=this,elem,height;\n"
+"		elem		= t.test_font_size;\n"
+"		content		= text.replace(/&/g,\"&amp;\").replace(/</g,\"&lt;\");\n"
+"		if( t.isIE || ( this.isOpera && this.isOpera < 9.6 ) ) {\n"
+"			elem.innerHTML= \"<pre>\" + content.replace(/^\\r?\\n/, \"<br>\") + \"</pre>\";\n"
+"		} else {\n"
+"			elem.innerHTML= content;\n"
+"		}\n"
+"		height	= elem.offsetHeight;\n"
+"		height	= Math.max( 1, Math.floor( elem.offsetHeight / this.lineHeight ) ) * this.lineHeight;\n"
+"		return height;\n"
+"	};\n"
+"\n"
+"	/**\n"
+"	 * Fix line height for the given lines\n"
+"	 * @param Integer linestart\n"
+"	 * @param Integer lineEnd End line or -1 to cover all lines\n"
+"	 */\n"
+"	EditArea.prototype.fixLinesHeight= function( textValue, lineStart,lineEnd ){\n"
+"		var aText = textValue.split(\"\\n\");\n"
+"		if( lineEnd == -1 )\n"
+"			lineEnd	= aText.length-1;\n"
+"		for( var i = Math.max(0, lineStart); i <= lineEnd; i++ )\n"
+"		{\n"
+"			if( elem = _$('line_'+ ( i+1 ) ) )\n"
+"			{\n"
+"				elem.style.height= typeof( aText[i] ) != \"undefined\" ? this.getTextHeight( aText[i] )+\"px\" : this.lineHeight;\n"
+"			}\n"
+"		}\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.area_select= function(start, length){\n"
+"		this.textarea.focus();\n"
+"		\n"
+"		start	= Math.max(0, Math.min(this.textarea.value.length, start));\n"
+"		end		= Math.max(start, Math.min(this.textarea.value.length, start+length));\n"
+"\n"
+"		if(this.isIE)\n"
+"		{\n"
+"			this.textarea.selectionStart	= start;\n"
+"			this.textarea.selectionEnd		= end;		\n"
+"			this.setIESelection();\n"
+"		}\n"
+"		else\n"
+"		{\n"
+"			// Opera bug when moving selection start and selection end\n"
+"			if(this.isOpera && this.isOpera < 9.6 )\n"
+"			{	\n"
+"				this.textarea.setSelectionRange(0, 0);\n"
+"			}\n"
+"			this.textarea.setSelectionRange(start, end);\n"
+"		}\n"
+"		this.check_line_selection();\n"
+"	};\n"
+"	\n"
+"	\n"
+"	EditArea.prototype.area_get_selection= function(){\n"
+"		var text=\"\";\n"
+"		if( document.selection ){\n"
+"			var range = document.selection.createRange();\n"
+"			text=range.text;\n"
+"		}else{\n"
+"			text= this.textarea.value.substring(this.textarea.selectionStart, this.textarea.selectionEnd);\n"
+"		}\n"
+"		return text;			\n"
+"	};	//replace tabulation by the good number of white spaces\n"
+"	EditArea.prototype.replace_tab= function(text){\n"
+"		return text.replace(/((\\n?)([^\t\\n]*)\t)/gi, editArea.smartTab);		// slower than simple replace...\n"
+"	};\n"
+"\n"
+"	// call by the replace_tab function\n"
+"	EditArea.prototype.smartTab= function(){\n"
+"		val=\"                   \";\n"
+"		return EditArea.prototype.smartTab.arguments[2] + EditArea.prototype.smartTab.arguments[3] + val.substr(0, editArea.tab_nb_char - (EditArea.prototype.smartTab.arguments[3].length)%editArea.tab_nb_char);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.show_waiting_screen= function(){\n"
+"		width	= this.editor_area.offsetWidth;\n"
+"		height	= this.editor_area.offsetHeight;\n"
+"		if( !(this.isIE && this.isIE<6) )\n"
+"		{\n"
+"			width	-= 2;\n"
+"			height	-= 2;\n"
+"		}\n"
+"		this.processing_screen.style.display= \"block\";\n"
+"		this.processing_screen.style.width	= width+\"px\";\n"
+"		this.processing_screen.style.height	= height+\"px\";\n"
+"		this.waiting_screen_displayed		= true;\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.hide_waiting_screen= function(){\n"
+"		this.processing_screen.style.display=\"none\";\n"
+"		this.waiting_screen_displayed= false;\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.add_style= function(styles){\n"
+"		if(styles.length>0){\n"
+"			newcss = document.createElement(\"style\");\n"
+"			newcss.type=\"text/css\";\n"
+"			newcss.media=\"all\";\n"
+"			if(newcss.styleSheet){ // IE\n"
+"				newcss.styleSheet.cssText = styles;\n"
+"			} else { // W3C\n"
+"				newcss.appendChild(document.createTextNode(styles));\n"
+"			}\n"
+"			document.getElementsByTagName(\"head\")[0].appendChild(newcss);\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.set_font= function(family, size){\n"
+"		var t=this, a=this.textarea, s=this.settings, elem_font, i, elem;\n"
+"		// list all elements concerned by font changes\n"
+"		var elems= [\"textarea\", \"content_highlight\", \"cursor_pos\", \"end_bracket\", \"selection_field\", \"selection_field_text\", \"line_number\"];\n"
+"\n"
+"		if(family && family!=\"\")\n"
+"			s[\"font_family\"]= family;\n"
+"		if(size && size>0)\n"
+"			s[\"font_size\"]	= size;\n"
+"		if( t.isOpera && t.isOpera < 9.6 )	// opera<9.6 can't manage non monospace font\n"
+"			s['font_family']=\"monospace\";\n"
+"\n"
+"		// update the select tag\n"
+"		if( elem_font = _$(\"area_font_size\") )\n"
+"		{\n"
+"			for( i = 0; i < elem_font.length; i++ )\n"
+"			{\n"
+"				if( elem_font.options[i].value && elem_font.options[i].value == s[\"font_size\"] )\n"
+"					elem_font.options[i].selected=true;\n"
+"			}\n"
+"		}\n"
+"\n"
+"		/*\n"
+"		 * somethimes firefox has rendering mistake with non-monospace font for text width in textarea vs in div for changing font size (eg: verdana change between 11pt to 12pt)\n"
+"		 * => looks like a browser internal random bug as text width can change while content_highlight is updated\n"
+"		 * we'll check if the font-size produce the same text width inside textarea and div and if not, we'll increment the font-size\n"
+"		 *\n"
+"		 * This is an ugly fix\n"
+"		 */\n"
+"		if( t.isFirefox )\n"
+"		{\n"
+"			var nbTry = 3;\n"
+"			do {\n"
+"				var div1 = document.createElement( 'div' ), text1 = document.createElement( 'textarea' );\n"
+"				var styles = {\n"
+"					width:		'40px',\n"
+"					overflow:	'scroll',\n"
+"					zIndex: 	50,\n"
+"					visibility:	'hidden',\n"
+"					fontFamily:	s[\"font_family\"],\n"
+"					fontSize:	s[\"font_size\"]+\"pt\",\n"
+"					lineHeight:	t.lineHeight+\"px\",\n"
+"					padding:	'0',\n"
+"					margin:		'0',\n"
+"					border:		'none',\n"
+"					whiteSpace:	'nowrap'\n"
+"				};\n"
+"				var diff, changed = false;\n"
+"				for( i in styles )\n"
+"				{\n"
+"					div1.style[ i ]		= styles[i];\n"
+"					text1.style[ i ]	= styles[i];\n"
+"				}\n"
+"				// no wrap for this text\n"
+"				text1.wrap = 'off';\n"
+"				text1.setAttribute('wrap', 'off');\n"
+"				t.container.appendChild( div1 );\n"
+"				t.container.appendChild( text1 );\n"
+"				// try to make FF to bug\n"
+"				div1.innerHTML 		= text1.value	= 'azertyuiopqsdfghjklm';\n"
+"				div1.innerHTML 		= text1.value	= text1.value+'wxcvbn^p*ù$!:;,,';\n"
+"				diff	=  text1.scrollWidth - div1.scrollWidth;\n"
+"\n"
+"				// firefox return here a diff of 1 px between equals scrollWidth (can't explain)\n"
+"				if( Math.abs( diff ) >= 2 )\n"
+"				{\n"
+"					s[\"font_size\"]++;\n"
+"					changed	= true;\n"
+"				}\n"
+"				t.container.removeChild( div1 );\n"
+"				t.container.removeChild( text1 );\n"
+"				nbTry--;\n"
+"			}while( changed && nbTry > 0 );\n"
+"		}\n"
+"\n"
+"\n"
+"		// calc line height\n"
+"		elem					= t.test_font_size;\n"
+"		elem.style.fontFamily	= \"\"+s[\"font_family\"];\n"
+"		elem.style.fontSize		= s[\"font_size\"]+\"pt\";\n"
+"		elem.innerHTML			= \"0\";\n"
+"		t.lineHeight			= elem.offsetHeight;\n"
+"\n"
+"		// update font for all concerned elements\n"
+"		for( i=0; i<elems.length; i++)\n"
+"		{\n"
+"			elem	= _$(elems[i]);\n"
+"			elem.style.fontFamily	= s[\"font_family\"];\n"
+"			elem.style.fontSize		= s[\"font_size\"]+\"pt\";\n"
+"			elem.style.lineHeight	= t.lineHeight+\"px\";\n"
+"		}\n"
+"		// define a css for <pre> tags\n"
+"		t.add_style(\"pre{font-family:\"+s[\"font_family\"]+\"}\");\n"
+"\n"
+"		// old opera and IE>=8 doesn't update font changes to the textarea\n"
+"		if( ( t.isOpera && t.isOpera < 9.6 ) || t.isIE >= 8 )\n"
+"		{\n"
+"			var parNod = a.parentNode, nxtSib = a.nextSibling, start= a.selectionStart, end= a.selectionEnd;\n"
+"			parNod.removeChild(a);\n"
+"			parNod.insertBefore(a, nxtSib);\n"
+"			t.area_select(start, end-start);\n"
+"		}\n"
+"\n"
+"		// force update of selection field\n"
+"		this.focus();\n"
+"		this.update_size();\n"
+"		this.check_line_selection();\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.change_font_size= function(){\n"
+"		var size=_$(\"area_font_size\").value;\n"
+"		if(size>0)\n"
+"			this.set_font(\"\", size);\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.open_inline_popup= function(popup_id){\n"
+"		this.close_all_inline_popup();\n"
+"		var popup= _$(popup_id);\n"
+"		var editor= _$(\"editor\");\n"
+"\n"
+"		// search matching icon\n"
+"		for(var i=0; i<this.inlinePopup.length; i++){\n"
+"			if(this.inlinePopup[i][\"popup_id\"]==popup_id){\n"
+"				var icon= _$(this.inlinePopup[i][\"icon_id\"]);\n"
+"				if(icon){\n"
+"					this.switchClassSticky(icon, 'editAreaButtonSelected', true);\n"
+"					break;\n"
+"				}\n"
+"			}\n"
+"		}\n"
+"		// check size\n"
+"		popup.style.height=\"auto\";\n"
+"		popup.style.overflow= \"visible\";\n"
+"\n"
+"		if(document.body.offsetHeight< popup.offsetHeight){\n"
+"			popup.style.height= (document.body.offsetHeight-10)+\"px\";\n"
+"			popup.style.overflow= \"auto\";\n"
+"		}\n"
+"\n"
+"		if(!popup.positionned){\n"
+"			var new_left= editor.offsetWidth /2 - popup.offsetWidth /2;\n"
+"			var new_top= editor.offsetHeight /2 - popup.offsetHeight /2;\n"
+"			//var new_top= area.offsetHeight /2 - popup.offsetHeight /2;\n"
+"			//var new_left= area.offsetWidth /2 - popup.offsetWidth /2;\n"
+"			//alert(\"new_top: (\"+new_top+\") = calculeOffsetTop(area) (\"+calculeOffsetTop(area)+\") + area.offsetHeight /2(\"+ area.offsetHeight /2+\") - popup.offsetHeight /2(\"+popup.offsetHeight /2+\") - scrollTop: \"+document.body.scrollTop);\n"
+"			popup.style.left= new_left+\"px\";\n"
+"			popup.style.top= new_top+\"px\";\n"
+"			popup.positionned=true;\n"
+"		}\n"
+"		popup.style.visibility=\"visible\";\n"
+"\n"
+"		//popup.style.display=\"block\";\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.close_inline_popup= function(popup_id){\n"
+"		var popup= _$(popup_id);\n"
+"		// search matching icon\n"
+"		for(var i=0; i<this.inlinePopup.length; i++){\n"
+"			if(this.inlinePopup[i][\"popup_id\"]==popup_id){\n"
+"				var icon= _$(this.inlinePopup[i][\"icon_id\"]);\n"
+"				if(icon){\n"
+"					this.switchClassSticky(icon, 'editAreaButtonNormal', false);\n"
+"					break;\n"
+"				}\n"
+"			}\n"
+"		}\n"
+"\n"
+"		popup.style.visibility=\"hidden\";\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.close_all_inline_popup= function(e){\n"
+"		for(var i=0; i<this.inlinePopup.length; i++){\n"
+"			this.close_inline_popup(this.inlinePopup[i][\"popup_id\"]);\n"
+"		}\n"
+"		this.textarea.focus();\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.show_help= function(){\n"
+"\n"
+"		this.open_inline_popup(\"edit_area_help\");\n"
+"\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.new_document= function(){\n"
+"		this.textarea.value=\"\";\n"
+"		this.area_select(0,0);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.get_all_toolbar_height= function(){\n"
+"		var area= _$(\"editor\");\n"
+"		var results= parent.getChildren(area, \"div\", \"class\", \"area_toolbar\", \"all\", \"0\");	// search only direct children\n"
+"		//results= results.concat(getChildren(area, \"table\", \"class\", \"area_toolbar\", \"all\", \"0\"));\n"
+"		var height=0;\n"
+"		for(var i=0; i<results.length; i++){\n"
+"			height+= results[i].offsetHeight;\n"
+"		}\n"
+"		//alert(\"toolbar height: \"+height);\n"
+"		return height;\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.go_to_line= function(line){\n"
+"		if(!line)\n"
+"		{\n"
+"			var icon= _$(\"go_to_line\");\n"
+"			if(icon != null){\n"
+"				this.restoreClass(icon);\n"
+"				this.switchClassSticky(icon, 'editAreaButtonSelected', true);\n"
+"			}\n"
+"\n"
+"			line= prompt(this.get_translation(\"go_to_line_prompt\"));\n"
+"			if(icon != null)\n"
+"				this.switchClassSticky(icon, 'editAreaButtonNormal', false);\n"
+"		}\n"
+"		if(line && line!=null && line.search(/^[0-9]+$/)!=-1){\n"
+"			var start=0;\n"
+"			var lines= this.textarea.value.split(\"\\n\");\n"
+"			if(line > lines.length)\n"
+"				start= this.textarea.value.length;\n"
+"			else{\n"
+"				for(var i=0; i<Math.min(line-1, lines.length); i++)\n"
+"					start+= lines[i].length + 1;\n"
+"			}\n"
+"			this.area_select(start, 0);\n"
+"		}\n"
+"\n"
+"\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.change_smooth_selection_mode= function(setTo){\n"
+"		//alert(\"setTo: \"+setTo);\n"
+"		if(this.do_highlight)\n"
+"			return;\n"
+"\n"
+"		if(setTo != null){\n"
+"			if(setTo === false)\n"
+"				this.smooth_selection=true;\n"
+"			else\n"
+"				this.smooth_selection=false;\n"
+"		}\n"
+"		var icon= _$(\"change_smooth_selection\");\n"
+"		this.textarea.focus();\n"
+"		if(this.smooth_selection===true){\n"
+"			//setAttribute(icon, \"class\", getAttribute(icon, \"class\").replace(/ selected/g, \"\") );\n"
+"			/*setAttribute(icon, \"oldClassName\", \"editAreaButtonNormal\" );\n"
+"			setAttribute(icon, \"className\", \"editAreaButtonNormal\" );*/\n"
+"			//this.restoreClass(icon);\n"
+"			//this.restoreAndSwitchClass(icon,'editAreaButtonNormal');\n"
+"			this.switchClassSticky(icon, 'editAreaButtonNormal', false);\n"
+"\n"
+"			this.smooth_selection=false;\n"
+"			this.selection_field.style.display= \"none\";\n"
+"			_$(\"cursor_pos\").style.display= \"none\";\n"
+"			_$(\"end_bracket\").style.display= \"none\";\n"
+"		}else{\n"
+"			//setAttribute(icon, \"class\", getAttribute(icon, \"class\") + \" selected\");\n"
+"			//this.switchClass(icon,'editAreaButtonSelected');\n"
+"			this.switchClassSticky(icon, 'editAreaButtonSelected', false);\n"
+"			this.smooth_selection=true;\n"
+"			this.selection_field.style.display= \"block\";\n"
+"			_$(\"cursor_pos\").style.display= \"block\";\n"
+"			_$(\"end_bracket\").style.display= \"block\";\n"
+"		}\n"
+"	};\n"
+"\n"
+"	// the auto scroll of the textarea has some lacks when it have to show cursor in the visible area when the textarea size change\n"
+"	// show specifiy whereas it is the \"top\" or \"bottom\" of the selection that is showned\n"
+"	EditArea.prototype.scroll_to_view= function(show){\n"
+"		var zone, lineElem;\n"
+"		if(!this.smooth_selection)\n"
+"			return;\n"
+"		zone= _$(\"result\");\n"
+"\n"
+"		// manage height scroll\n"
+"		var cursor_pos_top= _$(\"cursor_pos\").cursor_top;\n"
+"		if(show==\"bottom\")\n"
+"		{\n"
+"			//cursor_pos_top+=  (this.last_selection[\"line_nb\"]-1)* this.lineHeight;\n"
+"			cursor_pos_top+= this.getLinePosTop( this.last_selection['line_start'] + this.last_selection['line_nb'] - 1 );\n"
+"		}\n"
+"\n"
+"		var max_height_visible= zone.clientHeight + zone.scrollTop;\n"
+"		var miss_top	= cursor_pos_top + this.lineHeight - max_height_visible;\n"
+"		if(miss_top>0){\n"
+"			//alert(miss_top);\n"
+"			zone.scrollTop=  zone.scrollTop + miss_top;\n"
+"		}else if( zone.scrollTop > cursor_pos_top){\n"
+"			// when erase all the content -> does'nt scroll back to the top\n"
+"			//alert(\"else: \"+cursor_pos_top);\n"
+"			zone.scrollTop= cursor_pos_top;\n"
+"		}\n"
+"\n"
+"		// manage left scroll\n"
+"		//var cursor_pos_left= parseInt(_$(\"cursor_pos\").style.left.replace(\"px\",\"\"));\n"
+"		var cursor_pos_left= _$(\"cursor_pos\").cursor_left;\n"
+"		var max_width_visible= zone.clientWidth + zone.scrollLeft;\n"
+"		var miss_left= cursor_pos_left + 10 - max_width_visible;\n"
+"		if(miss_left>0){\n"
+"			zone.scrollLeft= zone.scrollLeft + miss_left + 50;\n"
+"		}else if( zone.scrollLeft > cursor_pos_left){\n"
+"			zone.scrollLeft= cursor_pos_left ;\n"
+"		}else if( zone.scrollLeft == 45){\n"
+"			// show the line numbers if textarea align to it's left\n"
+"			zone.scrollLeft=0;\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.check_undo= function(only_once){\n"
+"		if(!editAreas[this.id])\n"
+"			return false;\n"
+"		if(this.textareaFocused && editAreas[this.id][\"displayed\"]==true){\n"
+"			var text=this.textarea.value;\n"
+"			if(this.previous.length<=1)\n"
+"				this.switchClassSticky(_$(\"undo\"), 'editAreaButtonDisabled', true);\n"
+"\n"
+"			if(!this.previous[this.previous.length-1] || this.previous[this.previous.length-1][\"text\"] != text){\n"
+"				this.previous.push({\"text\": text, \"selStart\": this.textarea.selectionStart, \"selEnd\": this.textarea.selectionEnd});\n"
+"				if(this.previous.length > this.settings[\"max_undo\"]+1)\n"
+"					this.previous.shift();\n"
+"\n"
+"			}\n"
+"			if(this.previous.length >= 2)\n"
+"				this.switchClassSticky(_$(\"undo\"), 'editAreaButtonNormal', false);\n"
+"		}\n"
+"\n"
+"		if(!only_once)\n"
+"			setTimeout(\"editArea.check_undo()\", 3000);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.undo= function(){\n"
+"		//alert(\"undo\"+this.previous.length);\n"
+"		if(this.previous.length > 0)\n"
+"		{\n"
+"			this.getIESelection();\n"
+"		//	var pos_cursor=this.textarea.selectionStart;\n"
+"			this.next.push( { \"text\": this.textarea.value, \"selStart\": this.textarea.selectionStart, \"selEnd\": this.textarea.selectionEnd } );\n"
+"			var prev= this.previous.pop();\n"
+"			if( prev[\"text\"] == this.textarea.value && this.previous.length > 0 )\n"
+"				prev	=this.previous.pop();\n"
+"			this.textarea.value	= prev[\"text\"];\n"
+"			this.last_undo		= prev[\"text\"];\n"
+"			this.area_select(prev[\"selStart\"], prev[\"selEnd\"]-prev[\"selStart\"]);\n"
+"			this.switchClassSticky(_$(\"redo\"), 'editAreaButtonNormal', false);\n"
+"			this.resync_highlight(true);\n"
+"			//alert(\"undo\"+this.previous.length);\n"
+"			this.check_file_changes();\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.redo= function(){\n"
+"		if(this.next.length > 0)\n"
+"		{\n"
+"			/*this.getIESelection();*/\n"
+"			//var pos_cursor=this.textarea.selectionStart;\n"
+"			var next= this.next.pop();\n"
+"			this.previous.push(next);\n"
+"			this.textarea.value= next[\"text\"];\n"
+"			this.last_undo= next[\"text\"];\n"
+"			this.area_select(next[\"selStart\"], next[\"selEnd\"]-next[\"selStart\"]);\n"
+"			this.switchClassSticky(_$(\"undo\"), 'editAreaButtonNormal', false);\n"
+"			this.resync_highlight(true);\n"
+"			this.check_file_changes();\n"
+"		}\n"
+"		if(	this.next.length == 0)\n"
+"			this.switchClassSticky(_$(\"redo\"), 'editAreaButtonDisabled', true);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.check_redo= function(){\n"
+"		if(editArea.next.length == 0 || editArea.textarea.value!=editArea.last_undo){\n"
+"			editArea.next= [];	// undo the ability to use \"redo\" button\n"
+"			editArea.switchClassSticky(_$(\"redo\"), 'editAreaButtonDisabled', true);\n"
+"		}\n"
+"		else\n"
+"		{\n"
+"			this.switchClassSticky(_$(\"redo\"), 'editAreaButtonNormal', false);\n"
+"		}\n"
+"	};\n"
+"\n"
+"\n"
+"	// functions that manage icons roll over, disabled, etc...\n"
+"	EditArea.prototype.switchClass = function(element, class_name, lock_state) {\n"
+"		var lockChanged = false;\n"
+"\n"
+"		if (typeof(lock_state) != \"undefined\" && element != null) {\n"
+"			element.classLock = lock_state;\n"
+"			lockChanged = true;\n"
+"		}\n"
+"\n"
+"		if (element != null && (lockChanged || !element.classLock)) {\n"
+"			element.oldClassName = element.className;\n"
+"			element.className = class_name;\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.restoreAndSwitchClass = function(element, class_name) {\n"
+"		if (element != null && !element.classLock) {\n"
+"			this.restoreClass(element);\n"
+"			this.switchClass(element, class_name);\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.restoreClass = function(element) {\n"
+"		if (element != null && element.oldClassName && !element.classLock) {\n"
+"			element.className = element.oldClassName;\n"
+"			element.oldClassName = null;\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.setClassLock = function(element, lock_state) {\n"
+"		if (element != null)\n"
+"			element.classLock = lock_state;\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.switchClassSticky = function(element, class_name, lock_state) {\n"
+"		var lockChanged = false;\n"
+"		if (typeof(lock_state) != \"undefined\" && element != null) {\n"
+"			element.classLock = lock_state;\n"
+"			lockChanged = true;\n"
+"		}\n"
+"\n"
+"		if (element != null && (lockChanged || !element.classLock)) {\n"
+"			element.className = class_name;\n"
+"			element.oldClassName = class_name;\n"
+"		}\n"
+"	};\n"
+"\n"
+"	//make the \"page up\" and \"page down\" buttons works correctly\n"
+"	EditArea.prototype.scroll_page= function(params){\n"
+"		var dir= params[\"dir\"], shift_pressed= params[\"shift\"];\n"
+"		var lines= this.textarea.value.split(\"\\n\");\n"
+"		var new_pos=0, length=0, char_left=0, line_nb=0, curLine=0;\n"
+"		var toScrollAmount	= _$(\"result\").clientHeight -30;\n"
+"		var nbLineToScroll	= 0, diff= 0;\n"
+"\n"
+"		if(dir==\"up\"){\n"
+"			nbLineToScroll	= Math.ceil( toScrollAmount / this.lineHeight );\n"
+"\n"
+"			// fix number of line to scroll\n"
+"			for( i = this.last_selection[\"line_start\"]; i - diff > this.last_selection[\"line_start\"] - nbLineToScroll ; i-- )\n"
+"			{\n"
+"				if( elem = _$('line_'+ i) )\n"
+"				{\n"
+"					diff +=  Math.floor( ( elem.offsetHeight - 1 ) / this.lineHeight );\n"
+"				}\n"
+"			}\n"
+"			nbLineToScroll	-= diff;\n"
+"\n"
+"			if(this.last_selection[\"selec_direction\"]==\"up\"){\n"
+"				for(line_nb=0; line_nb< Math.min(this.last_selection[\"line_start\"]-nbLineToScroll, lines.length); line_nb++){\n"
+"					new_pos+= lines[line_nb].length + 1;\n"
+"				}\n"
+"				char_left=Math.min(lines[Math.min(lines.length-1, line_nb)].length, this.last_selection[\"curr_pos\"]-1);\n"
+"				if(shift_pressed)\n"
+"					length=this.last_selection[\"selectionEnd\"]-new_pos-char_left;\n"
+"				this.area_select(new_pos+char_left, length);\n"
+"				view=\"top\";\n"
+"			}else{\n"
+"				view=\"bottom\";\n"
+"				for(line_nb=0; line_nb< Math.min(this.last_selection[\"line_start\"]+this.last_selection[\"line_nb\"]-1-nbLineToScroll, lines.length); line_nb++){\n"
+"					new_pos+= lines[line_nb].length + 1;\n"
+"				}\n"
+"				char_left=Math.min(lines[Math.min(lines.length-1, line_nb)].length, this.last_selection[\"curr_pos\"]-1);\n"
+"				if(shift_pressed){\n"
+"					//length=this.last_selection[\"selectionEnd\"]-new_pos-char_left;\n"
+"					start= Math.min(this.last_selection[\"selectionStart\"], new_pos+char_left);\n"
+"					length= Math.max(new_pos+char_left, this.last_selection[\"selectionStart\"] )- start ;\n"
+"					if(new_pos+char_left < this.last_selection[\"selectionStart\"])\n"
+"						view=\"top\";\n"
+"				}else\n"
+"					start=new_pos+char_left;\n"
+"				this.area_select(start, length);\n"
+"\n"
+"			}\n"
+"		}\n"
+"		else\n"
+"		{\n"
+"			var nbLineToScroll= Math.floor( toScrollAmount / this.lineHeight );\n"
+"			// fix number of line to scroll\n"
+"			for( i = this.last_selection[\"line_start\"]; i + diff < this.last_selection[\"line_start\"] + nbLineToScroll ; i++ )\n"
+"			{\n"
+"				if( elem = _$('line_'+ i) )\n"
+"				{\n"
+"					diff +=  Math.floor( ( elem.offsetHeight - 1 ) / this.lineHeight );\n"
+"				}\n"
+"			}\n"
+"			nbLineToScroll	-= diff;\n"
+"\n"
+"			if(this.last_selection[\"selec_direction\"]==\"down\"){\n"
+"				view=\"bottom\";\n"
+"				for(line_nb=0; line_nb< Math.min(this.last_selection[\"line_start\"]+this.last_selection[\"line_nb\"]-2+nbLineToScroll, lines.length); line_nb++){\n"
+"					if(line_nb==this.last_selection[\"line_start\"]-1)\n"
+"						char_left= this.last_selection[\"selectionStart\"] -new_pos;\n"
+"					new_pos+= lines[line_nb].length + 1;\n"
+"\n"
+"				}\n"
+"				if(shift_pressed){\n"
+"					length=Math.abs(this.last_selection[\"selectionStart\"]-new_pos);\n"
+"					length+=Math.min(lines[Math.min(lines.length-1, line_nb)].length, this.last_selection[\"curr_pos\"]);\n"
+"					//length+=Math.min(lines[Math.min(lines.length-1, line_nb)].length, char_left);\n"
+"					this.area_select(Math.min(this.last_selection[\"selectionStart\"], new_pos), length);\n"
+"				}else{\n"
+"					this.area_select(new_pos+char_left, 0);\n"
+"				}\n"
+"\n"
+"			}else{\n"
+"				view=\"top\";\n"
+"				for(line_nb=0; line_nb< Math.min(this.last_selection[\"line_start\"]+nbLineToScroll-1, lines.length, lines.length); line_nb++){\n"
+"					if(line_nb==this.last_selection[\"line_start\"]-1)\n"
+"						char_left= this.last_selection[\"selectionStart\"] -new_pos;\n"
+"					new_pos+= lines[line_nb].length + 1;\n"
+"				}\n"
+"				if(shift_pressed){\n"
+"					length=Math.abs(this.last_selection[\"selectionEnd\"]-new_pos-char_left);\n"
+"					length+=Math.min(lines[Math.min(lines.length-1, line_nb)].length, this.last_selection[\"curr_pos\"])- char_left-1;\n"
+"					//length+=Math.min(lines[Math.min(lines.length-1, line_nb)].length, char_left);\n"
+"					this.area_select(Math.min(this.last_selection[\"selectionEnd\"], new_pos+char_left), length);\n"
+"					if(new_pos+char_left > this.last_selection[\"selectionEnd\"])\n"
+"						view=\"bottom\";\n"
+"				}else{\n"
+"					this.area_select(new_pos+char_left, 0);\n"
+"				}\n"
+"\n"
+"			}\n"
+"		}\n"
+"		//console.log( new_pos, char_left, length, nbLineToScroll, toScrollAmount, _$(\"result\").clientHeigh );\n"
+"		this.check_line_selection();\n"
+"		this.scroll_to_view(view);\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.start_resize= function(e){\n"
+"		parent.editAreaLoader.resize[\"id\"]		= editArea.id;\n"
+"		parent.editAreaLoader.resize[\"start_x\"]	= (e)? e.pageX : event.x + document.body.scrollLeft;\n"
+"		parent.editAreaLoader.resize[\"start_y\"]	= (e)? e.pageY : event.y + document.body.scrollTop;\n"
+"		if(editArea.isIE)\n"
+"		{\n"
+"			editArea.textarea.focus();\n"
+"			editArea.getIESelection();\n"
+"		}\n"
+"		parent.editAreaLoader.resize[\"selectionStart\"]	= editArea.textarea.selectionStart;\n"
+"		parent.editAreaLoader.resize[\"selectionEnd\"]	= editArea.textarea.selectionEnd;\n"
+"		parent.editAreaLoader.start_resize_area();\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.toggle_full_screen= function(to){\n"
+"		var t=this, p=parent, a=t.textarea, html, frame, selStart, selEnd, old, icon;\n"
+"		if(typeof(to)==\"undefined\")\n"
+"			to= !t.fullscreen['isFull'];\n"
+"		old			= t.fullscreen['isFull'];\n"
+"		t.fullscreen['isFull']= to;\n"
+"		icon		= _$(\"fullscreen\");\n"
+"		selStart	= t.textarea.selectionStart;\n"
+"		selEnd		= t.textarea.selectionEnd;\n"
+"		html		= p.document.getElementsByTagName(\"html\")[0];\n"
+"		frame		= p.document.getElementById(\"frame_\"+t.id);\n"
+"\n"
+"		if(to && to!=old)\n"
+"		{	// toogle on fullscreen\n"
+"\n"
+"			t.fullscreen['old_overflow']	= p.get_css_property(html, \"overflow\");\n"
+"			t.fullscreen['old_height']		= p.get_css_property(html, \"height\");\n"
+"			t.fullscreen['old_width']		= p.get_css_property(html, \"width\");\n"
+"			t.fullscreen['old_scrollTop']	= html.scrollTop;\n"
+"			t.fullscreen['old_scrollLeft']	= html.scrollLeft;\n"
+"			t.fullscreen['old_zIndex']		= p.get_css_property(frame, \"z-index\");\n"
+"			if(t.isOpera){\n"
+"				html.style.height	= \"100%\";\n"
+"				html.style.width	= \"100%\";\n"
+"			}\n"
+"			html.style.overflow	= \"hidden\";\n"
+"			html.scrollTop		= 0;\n"
+"			html.scrollLeft		= 0;\n"
+"\n"
+"			frame.style.position	= \"absolute\";\n"
+"			frame.style.width		= html.clientWidth+\"px\";\n"
+"			frame.style.height		= html.clientHeight+\"px\";\n"
+"			frame.style.display		= \"block\";\n"
+"			frame.style.zIndex		= \"999999\";\n"
+"			frame.style.top			= \"0px\";\n"
+"			frame.style.left		= \"0px\";\n"
+"\n"
+"			// if the iframe was in a div with position absolute, the top and left are the one of the div,\n"
+"			// so I fix it by seeing at witch position the iframe start and correcting it\n"
+"			frame.style.top			= \"-\"+p.calculeOffsetTop(frame)+\"px\";\n"
+"			frame.style.left		= \"-\"+p.calculeOffsetLeft(frame)+\"px\";\n"
+"\n"
+"		//	parent.editAreaLoader.execCommand(t.id, \"update_size();\");\n"
+"		//	var body=parent.document.getElementsByTagName(\"body\")[0];\n"
+"		//	body.appendChild(frame);\n"
+"\n"
+"			t.switchClassSticky(icon, 'editAreaButtonSelected', false);\n"
+"			t.fullscreen['allow_resize']= t.resize_allowed;\n"
+"			t.allow_resize(false);\n"
+"\n"
+"			//t.area_select(selStart, selEnd-selStart);\n"
+"\n"
+"\n"
+"			// opera can't manage to do a direct size update\n"
+"			if(t.isFirefox){\n"
+"				p.editAreaLoader.execCommand(t.id, \"update_size();\");\n"
+"				t.area_select(selStart, selEnd-selStart);\n"
+"				t.scroll_to_view();\n"
+"				t.focus();\n"
+"			}else{\n"
+"				setTimeout(\"parent.editAreaLoader.execCommand('\"+ t.id +\"', 'update_size();');editArea.focus();\", 10);\n"
+"			}\n"
+"\n"
+"\n"
+"		}\n"
+"		else if(to!=old)\n"
+"		{	// toogle off fullscreen\n"
+"			frame.style.position=\"static\";\n"
+"			frame.style.zIndex= t.fullscreen['old_zIndex'];\n"
+"\n"
+"			if(t.isOpera)\n"
+"			{\n"
+"				html.style.height	= \"auto\";\n"
+"				html.style.width	= \"auto\";\n"
+"				html.style.overflow	= \"auto\";\n"
+"			}\n"
+"			else if(t.isIE && p!=top)\n"
+"			{	// IE doesn't manage html overflow in frames like in normal page...\n"
+"				html.style.overflow	= \"auto\";\n"
+"			}\n"
+"			else\n"
+"			{\n"
+"				html.style.overflow	= t.fullscreen['old_overflow'];\n"
+"			}\n"
+"			html.scrollTop	= t.fullscreen['old_scrollTop'];\n"
+"			html.scrollLeft	= t.fullscreen['old_scrollLeft'];\n"
+"\n"
+"			p.editAreaLoader.hide(t.id);\n"
+"			p.editAreaLoader.show(t.id);\n"
+"\n"
+"			t.switchClassSticky(icon, 'editAreaButtonNormal', false);\n"
+"			if(t.fullscreen['allow_resize'])\n"
+"				t.allow_resize(t.fullscreen['allow_resize']);\n"
+"			if(t.isFirefox){\n"
+"				t.area_select(selStart, selEnd-selStart);\n"
+"				setTimeout(\"editArea.scroll_to_view();\", 10);\n"
+"			}\n"
+"\n"
+"			//p.editAreaLoader.remove_event(p.window, \"resize\", editArea.update_size);\n"
+"		}\n"
+"\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.allow_resize= function(allow){\n"
+"		var resize= _$(\"resize_area\");\n"
+"		if(allow){\n"
+"\n"
+"			resize.style.visibility=\"visible\";\n"
+"			parent.editAreaLoader.add_event(resize, \"mouseup\", editArea.start_resize);\n"
+"		}else{\n"
+"			resize.style.visibility=\"hidden\";\n"
+"			parent.editAreaLoader.remove_event(resize, \"mouseup\", editArea.start_resize);\n"
+"		}\n"
+"		this.resize_allowed= allow;\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.change_syntax= function(new_syntax, is_waiting){\n"
+"	//	alert(\"cahnge to \"+new_syntax);\n"
+"		// the syntax is the same\n"
+"		if(new_syntax==this.settings['syntax'])\n"
+"			return true;\n"
+"\n"
+"		// check that the syntax is one allowed\n"
+"		var founded= false;\n"
+"		for(var i=0; i<this.syntax_list.length; i++)\n"
+"		{\n"
+"			if(this.syntax_list[i]==new_syntax)\n"
+"				founded= true;\n"
+"		}\n"
+"\n"
+"		if(founded==true)\n"
+"		{\n"
+"			// the reg syntax file is not loaded\n"
+"			if(!parent.editAreaLoader.load_syntax[new_syntax])\n"
+"			{\n"
+"				// load the syntax file and wait for file loading\n"
+"				if(!is_waiting)\n"
+"					parent.editAreaLoader.load_script(parent.editAreaLoader.baseURL + \"reg_syntax/\" + new_syntax + \".js\");\n"
+"				setTimeout(\"editArea.change_syntax('\"+ new_syntax +\"', true);\", 100);\n"
+"				this.show_waiting_screen();\n"
+"			}\n"
+"			else\n"
+"			{\n"
+"				if(!this.allready_used_syntax[new_syntax])\n"
+"				{	// the syntax has still not been used\n"
+"					// rebuild syntax definition for new languages\n"
+"					parent.editAreaLoader.init_syntax_regexp();\n"
+"					// add style to the new list\n"
+"					this.add_style(parent.editAreaLoader.syntax[new_syntax][\"styles\"]);\n"
+"					this.allready_used_syntax[new_syntax]=true;\n"
+"				}\n"
+"				// be sure that the select option is correctly updated\n"
+"				var sel= _$(\"syntax_selection\");\n"
+"				if(sel && sel.value!=new_syntax)\n"
+"				{\n"
+"					for(var i=0; i<sel.length; i++){\n"
+"						if(sel.options[i].value && sel.options[i].value == new_syntax)\n"
+"							sel.options[i].selected=true;\n"
+"					}\n"
+"				}\n"
+"\n"
+"			/*	if(this.settings['syntax'].length==0)\n"
+"				{\n"
+"					this.switchClassSticky(_$(\"highlight\"), 'editAreaButtonNormal', false);\n"
+"					this.switchClassSticky(_$(\"reset_highlight\"), 'editAreaButtonNormal', false);\n"
+"					this.change_highlight(true);\n"
+"				}\n"
+"				*/\n"
+"				this.settings['syntax']= new_syntax;\n"
+"				this.resync_highlight(true);\n"
+"				this.hide_waiting_screen();\n"
+"				return true;\n"
+"			}\n"
+"		}\n"
+"		return false;\n"
+"	};\n"
+"\n"
+"\n"
+"	// check if the file has changed\n"
+"	EditArea.prototype.set_editable= function(is_editable){\n"
+"		if(is_editable)\n"
+"		{\n"
+"			document.body.className= \"\";\n"
+"			this.textarea.readOnly= false;\n"
+"			this.is_editable= true;\n"
+"		}\n"
+"		else\n"
+"		{\n"
+"			document.body.className= \"non_editable\";\n"
+"			this.textarea.readOnly= true;\n"
+"			this.is_editable= false;\n"
+"		}\n"
+"\n"
+"		if(editAreas[this.id][\"displayed\"]==true)\n"
+"			this.update_size();\n"
+"	};\n"
+"\n"
+"	/***** Wrap mode *****/\n"
+"\n"
+"	// toggling function for set_wrap_mode\n"
+"	EditArea.prototype.toggle_word_wrap= function(){\n"
+"		this.set_word_wrap( !this.settings['word_wrap'] );\n"
+"	};\n"
+"\n"
+"\n"
+"	// open a new tab for the given file\n"
+"	EditArea.prototype.set_word_wrap= function(to){\n"
+"		var t=this, a= t.textarea;\n"
+"		if( t.isOpera && t.isOpera < 9.8 )\n"
+"		{\n"
+"			this.settings['word_wrap']= false;\n"
+"			t.switchClassSticky( _$(\"word_wrap\"), 'editAreaButtonDisabled', true );\n"
+"			return false;\n"
+"		}\n"
+"\n"
+"		if( to )\n"
+"		{\n"
+"			wrap_mode = 'soft';\n"
+"			this.container.className+= ' word_wrap';\n"
+"			this.container.style.width=\"\";\n"
+"			this.content_highlight.style.width=\"\";\n"
+"			a.style.width=\"100%\";\n"
+"			if( t.isIE && t.isIE < 7 )	// IE 6 count 50 px too much\n"
+"			{\n"
+"				a.style.width	= ( a.offsetWidth-5 )+\"px\";\n"
+"			}\n"
+"\n"
+"			t.switchClassSticky( _$(\"word_wrap\"), 'editAreaButtonSelected', false );\n"
+"		}\n"
+"		else\n"
+"		{\n"
+"			wrap_mode = 'off';\n"
+"			this.container.className	= this.container.className.replace(/word_wrap/g, '');\n"
+"			t.switchClassSticky( _$(\"word_wrap\"), 'editAreaButtonNormal', true );\n"
+"		}\n"
+"		this.textarea.previous_scrollWidth = '';\n"
+"		this.textarea.previous_scrollHeight = '';\n"
+"\n"
+"		a.wrap= wrap_mode;\n"
+"		a.setAttribute('wrap', wrap_mode);\n"
+"		// only IE can change wrap mode on the fly without element reloading\n"
+"		if(!this.isIE)\n"
+"		{\n"
+"			var start=a.selectionStart, end= a.selectionEnd;\n"
+"			var parNod = a.parentNode, nxtSib = a.nextSibling;\n"
+"			parNod.removeChild(a);\n"
+"			parNod.insertBefore(a, nxtSib);\n"
+"			this.area_select(start, end-start);\n"
+"		}\n"
+"		// reset some optimisation\n"
+"		this.settings['word_wrap']	= to;\n"
+"		this.focus();\n"
+"		this.update_size();\n"
+"		this.check_line_selection();\n"
+"	};\n"
+"	/***** tabbed files managing functions *****/\n"
+"\n"
+"	// open a new tab for the given file\n"
+"	EditArea.prototype.open_file= function(settings){\n"
+"\n"
+"		if(settings['id']!=\"undefined\")\n"
+"		{\n"
+"			var id= settings['id'];\n"
+"			// create a new file object with defautl values\n"
+"			var new_file= {};\n"
+"			new_file['id']			= id;\n"
+"			new_file['title']		= id;\n"
+"			new_file['text']		= \"\";\n"
+"			new_file['last_selection']	= \"\";\n"
+"			new_file['last_text_to_highlight']	= \"\";\n"
+"			new_file['last_hightlighted_text']	= \"\";\n"
+"			new_file['previous']	= [];\n"
+"			new_file['next']		= [];\n"
+"			new_file['last_undo']	= \"\";\n"
+"			new_file['smooth_selection'] = (settings['smooth_selection'] ? settings['smooth_selection'] : this.settings['smooth_selection']); // fix for http://sourceforge.net/tracker/?func=detail&aid=3179689&group_id=164008&atid=829999\n"
+"			new_file['do_highlight']= (settings['start_highlight'] ? settings['start_highlight'] : this.settings['start_highlight']);\n"
+"			new_file['syntax']		= (settings['syntax'] ? settings['syntax'] : this.settings['syntax']);\n"
+"			new_file['scroll_top']	= 0;\n"
+"			new_file['scroll_left']	= 0;\n"
+"			new_file['selection_start']= 0;\n"
+"			new_file['selection_end']= 0;\n"
+"			new_file['edited']		= false;\n"
+"			new_file['font_size']	= (settings[\"font_size\"] ? settings[\"font_size\"] : this.settings[\"font_size\"]);\n"
+"			new_file['font_family']	= (settings[\"font_family\"] ? settings[\"font_family\"] : this.settings[\"font_family\"]);\n"
+"			new_file['word_wrap']	= (settings[\"word_wrap\"] ? settings[\"word_wrap\"] : this.settings[\"word_wrap\"]);\n"
+"			new_file['toolbar']		= {'links':{}, 'selects': {}};\n"
+"			new_file['compare_edited_text']= new_file['text'];\n"
+"\n"
+"\n"
+"			this.files[id]= new_file;\n"
+"			this.update_file(id, settings);\n"
+"			this.files[id]['compare_edited_text']= this.files[id]['text'];\n"
+"\n"
+"\n"
+"			var html_id= 'tab_file_'+encodeURIComponent(id);\n"
+"			this.filesIdAssoc[html_id]= id;\n"
+"			this.files[id]['html_id']= html_id;\n"
+"\n"
+"			if(!_$(this.files[id]['html_id']) && id!=\"\")\n"
+"			{\n"
+"				// be sure the tab browsing area is displayed\n"
+"				this.tab_browsing_area.style.display= \"block\";\n"
+"				var elem= document.createElement('li');\n"
+"				elem.id= this.files[id]['html_id'];\n"
+"				var close= \"<img src=\\\"\"+ parent.editAreaLoader.baseURL +\"images/close.gif\\\" title=\\\"\"+ this.get_translation('close_tab', 'word') +\"\\\" onclick=\\\"editArea.execCommand('close_file', editArea.filesIdAssoc['\"+ html_id +\"']);return false;\\\" class=\\\"hidden\\\" onmouseover=\\\"this.className=''\\\" onmouseout=\\\"this.className='hidden'\\\" />\";\n"
+"				elem.innerHTML= \"<a onclick=\\\"javascript:editArea.execCommand('switch_to_file', editArea.filesIdAssoc['\"+ html_id +\"']);\\\" selec=\\\"none\\\"><b><span><strong class=\\\"edited\\\">*</strong>\"+ this.files[id]['title'] + close +\"</span></b></a>\";\n"
+"				_$('tab_browsing_list').appendChild(elem);\n"
+"				var elem= document.createElement('text');\n"
+"				this.update_size();\n"
+"			}\n"
+"\n"
+"			// open file callback (for plugin)\n"
+"			if(id!=\"\")\n"
+"				this.execCommand('file_open', this.files[id]);\n"
+"\n"
+"			this.switch_to_file(id, true);\n"
+"			return true;\n"
+"		}\n"
+"		else\n"
+"			return false;\n"
+"	};\n"
+"\n"
+"	// close the given file\n"
+"	EditArea.prototype.close_file= function(id){\n"
+"		if(this.files[id])\n"
+"		{\n"
+"			this.switch_to_file(id); // fix for http://sourceforge.net/tracker/?func=detail&aid=3033375&group_id=164008&atid=829999\n"
+"			this.save_file(id);\n"
+"\n"
+"			// close file callback\n"
+"			if(this.execCommand('file_close', this.files[id])!==false)\n"
+"			{\n"
+"				// remove the tab in the toolbar\n"
+"				var li= _$(this.files[id]['html_id']);\n"
+"				li.parentNode.removeChild(li);\n"
+"				// select a new file\n"
+"				if(id== this.curr_file)\n"
+"				{\n"
+"					var next_file= \"\";\n"
+"					var is_next= false;\n"
+"					for(var i in this.files)\n"
+"					{\n"
+"						if( is_next )\n"
+"						{\n"
+"							next_file	= i;\n"
+"							break;\n"
+"						}\n"
+"						else if( i == id )\n"
+"							is_next		= true;\n"
+"						else\n"
+"							next_file	= i;\n"
+"					}\n"
+"					// display the next file\n"
+"					this.switch_to_file(next_file);\n"
+"				}\n"
+"				// clear datas\n"
+"				delete (this.files[id]);\n"
+"				this.update_size();\n"
+"			}\n"
+"		}\n"
+"	};\n"
+"\n"
+"	// backup current file datas\n"
+"	EditArea.prototype.save_file= function(id){\n"
+"		var t= this, save, a_links, a_selects, save_butt, img, i;\n"
+"		if(t.files[id])\n"
+"		{\n"
+"			var save= t.files[id];\n"
+"			save['last_selection']			= t.last_selection;\n"
+"			save['last_text_to_highlight']	= t.last_text_to_highlight;\n"
+"			save['last_hightlighted_text']	= t.last_hightlighted_text;\n"
+"			save['previous']				= t.previous;\n"
+"			save['next']					= t.next;\n"
+"			save['last_undo']				= t.last_undo;\n"
+"			save['smooth_selection']		= t.smooth_selection;\n"
+"			save['do_highlight']			= t.do_highlight;\n"
+"			save['syntax']					= t.settings['syntax'];\n"
+"			save['text']					= t.textarea.value;\n"
+"			save['scroll_top']				= t.result.scrollTop;\n"
+"			save['scroll_left']				= t.result.scrollLeft;\n"
+"			save['selection_start']			= t.last_selection[\"selectionStart\"];\n"
+"			save['selection_end']			= t.last_selection[\"selectionEnd\"];\n"
+"			save['font_size']				= t.settings[\"font_size\"];\n"
+"			save['font_family']				= t.settings[\"font_family\"];\n"
+"			save['word_wrap']				= t.settings[\"word_wrap\"];\n"
+"			save['toolbar']					= {'links':{}, 'selects': {}};\n"
+"\n"
+"			// save toolbar buttons state for fileSpecific buttons\n"
+"			a_links= _$(\"toolbar_1\").getElementsByTagName(\"a\");\n"
+"			for( i=0; i<a_links.length; i++ )\n"
+"			{\n"
+"				if( a_links[i].getAttribute('fileSpecific') == 'yes' )\n"
+"				{\n"
+"					save_butt	= {};\n"
+"					img			= a_links[i].getElementsByTagName('img')[0];\n"
+"					save_butt['classLock']		= img.classLock;\n"
+"					save_butt['className']		= img.className;\n"
+"					save_butt['oldClassName']	= img.oldClassName;\n"
+"\n"
+"					save['toolbar']['links'][a_links[i].id]= save_butt;\n"
+"				}\n"
+"			}\n"
+"\n"
+"			// save toolbar select state for fileSpecific buttons\n"
+"			a_selects= _$(\"toolbar_1\").getElementsByTagName(\"select\");\n"
+"			for( i=0; i<a_selects.length; i++)\n"
+"			{\n"
+"				if(a_selects[i].getAttribute('fileSpecific')=='yes')\n"
+"				{\n"
+"					save['toolbar']['selects'][a_selects[i].id]= a_selects[i].value;\n"
+"				}\n"
+"			}\n"
+"\n"
+"			t.files[id]= save;\n"
+"\n"
+"			return save;\n"
+"		}\n"
+"\n"
+"		return false;\n"
+"	};\n"
+"\n"
+"	// update file_datas\n"
+"	EditArea.prototype.update_file= function(id, new_values){\n"
+"		for(var i in new_values)\n"
+"		{\n"
+"			this.files[id][i]= new_values[i];\n"
+"		}\n"
+"	};\n"
+"\n"
+"	// display file datas\n"
+"	EditArea.prototype.display_file= function(id){\n"
+"		var t = this, a= t.textarea, new_file, a_lis, a_selects, a_links, a_options, i, j;\n"
+"\n"
+"		// we're showing the empty file\n"
+"		if(id=='')\n"
+"		{\n"
+"			a.readOnly= true;\n"
+"			t.tab_browsing_area.style.display= \"none\";\n"
+"			_$(\"no_file_selected\").style.display= \"block\";\n"
+"			t.result.className= \"empty\";\n"
+"			// clear current datas\n"
+"			if(!t.files[''])\n"
+"			{\n"
+"				t.open_file({id: ''});\n"
+"			}\n"
+"		}\n"
+"		// we try to show a non existent file, so we left\n"
+"		else if( typeof( t.files[id] ) == 'undefined' )\n"
+"		{\n"
+"			return false;\n"
+"		}\n"
+"		// display a normal file\n"
+"		else\n"
+"		{\n"
+"			t.result.className= \"\";\n"
+"			a.readOnly= !t.is_editable;\n"
+"			_$(\"no_file_selected\").style.display= \"none\";\n"
+"			t.tab_browsing_area.style.display= \"block\";\n"
+"		}\n"
+"\n"
+"		// ensure to have last state for undo/redo actions\n"
+"		t.check_redo(true);\n"
+"		t.check_undo(true);\n"
+"		t.curr_file= id;\n"
+"\n"
+"		// replace selected tab file\n"
+"		a_lis= t.tab_browsing_area.getElementsByTagName('li');\n"
+"		for( i=0; i<a_lis.length; i++)\n"
+"		{\n"
+"			if(a_lis[i].id == t.files[id]['html_id'])\n"
+"				a_lis[i].className='selected';\n"
+"			else\n"
+"				a_lis[i].className='';\n"
+"		}\n"
+"\n"
+"		// replace next files datas\n"
+"		new_file= t.files[id];\n"
+"\n"
+"		// restore text content\n"
+"		a.value= new_file['text'];\n"
+"\n"
+"		// restore font-size\n"
+"		t.set_font(new_file['font_family'], new_file['font_size']);\n"
+"\n"
+"		// restore selection and scroll\n"
+"		t.area_select(new_file['selection_start'], new_file['selection_end'] - new_file['selection_start']);\n"
+"		t.manage_size(true);\n"
+"		t.result.scrollTop= new_file['scroll_top'];\n"
+"		t.result.scrollLeft= new_file['scroll_left'];\n"
+"\n"
+"		// restore undo, redo\n"
+"		t.previous=	new_file['previous'];\n"
+"		t.next=	new_file['next'];\n"
+"		t.last_undo=	new_file['last_undo'];\n"
+"		t.check_redo(true);\n"
+"		t.check_undo(true);\n"
+"\n"
+"		// restore highlight\n"
+"		t.execCommand(\"change_highlight\", new_file['do_highlight']);\n"
+"		t.execCommand(\"change_syntax\", new_file['syntax']);\n"
+"\n"
+"		// smooth mode\n"
+"		t.execCommand(\"change_smooth_selection_mode\", new_file['smooth_selection']);\n"
+"\n"
+"		// word_wrap\n"
+"		t.execCommand(\"set_word_wrap\", new_file['word_wrap']);\n"
+"\n"
+"		// restore links state in toolbar\n"
+"		a_links= new_file['toolbar']['links'];\n"
+"		for( i in a_links)\n"
+"		{\n"
+"			if( img =  _$(i).getElementsByTagName('img')[0] )\n"
+"			{\n"
+"				img.classLock	= a_links[i]['classLock'];\n"
+"				img.className	= a_links[i]['className'];\n"
+"				img.oldClassName= a_links[i]['oldClassName'];\n"
+"			}\n"
+"		}\n"
+"\n"
+"		// restore select state in toolbar\n"
+"		a_selects = new_file['toolbar']['selects'];\n"
+"		for( i in a_selects)\n"
+"		{\n"
+"			a_options	= _$(i).options;\n"
+"			for( j=0; j<a_options.length; j++)\n"
+"			{\n"
+"				if( a_options[j].value == a_selects[i] )\n"
+"					_$(i).options[j].selected=true;\n"
+"			}\n"
+"		}\n"
+"\n"
+"	};\n"
+"\n"
+"	// change tab for displaying a new one\n"
+"	EditArea.prototype.switch_to_file= function(file_to_show, force_refresh){\n"
+"		if(file_to_show!=this.curr_file || force_refresh)\n"
+"		{\n"
+"			this.save_file(this.curr_file);\n"
+"			if(this.curr_file!='')\n"
+"				this.execCommand('file_switch_off', this.files[this.curr_file]);\n"
+"			this.display_file(file_to_show);\n"
+"			if(file_to_show!='')\n"
+"				this.execCommand('file_switch_on', this.files[file_to_show]);\n"
+"		}\n"
+"	};\n"
+"\n"
+"	// get all infos for the given file\n"
+"	EditArea.prototype.get_file= function(id){\n"
+"		if(id==this.curr_file)\n"
+"			this.save_file(id);\n"
+"		return this.files[id];\n"
+"	};\n"
+"\n"
+"	// get all available files infos\n"
+"	EditArea.prototype.get_all_files= function(){\n"
+"		tmp_files= this.files;\n"
+"		this.save_file(this.curr_file);\n"
+"		if(tmp_files[''])\n"
+"			delete(this.files['']);\n"
+"		return tmp_files;\n"
+"	};\n"
+"\n"
+"\n"
+"	// check if the file has changed\n"
+"	EditArea.prototype.check_file_changes= function(){\n"
+"\n"
+"		var id= this.curr_file;\n"
+"		if(this.files[id] && this.files[id]['compare_edited_text']!=undefined)\n"
+"		{\n"
+"			if(this.files[id]['compare_edited_text'].length==this.textarea.value.length && this.files[id]['compare_edited_text']==this.textarea.value)\n"
+"			{\n"
+"				if(this.files[id]['edited']!= false)\n"
+"					this.set_file_edited_mode(id, false);\n"
+"			}\n"
+"			else\n"
+"			{\n"
+"				if(this.files[id]['edited']!= true)\n"
+"					this.set_file_edited_mode(id, true);\n"
+"			}\n"
+"		}\n"
+"	};\n"
+"\n"
+"	// set if the file is edited or not\n"
+"	EditArea.prototype.set_file_edited_mode= function(id, to){\n"
+"		// change CSS for edited tab\n"
+"		if(this.files[id] && _$(this.files[id]['html_id']))\n"
+"		{\n"
+"			var link= _$(this.files[id]['html_id']).getElementsByTagName('a')[0];\n"
+"			if(to==true)\n"
+"			{\n"
+"				link.className= 'edited';\n"
+"			}\n"
+"			else\n"
+"			{\n"
+"				link.className= '';\n"
+"				if(id==this.curr_file)\n"
+"					text= this.textarea.value;\n"
+"				else\n"
+"					text= this.files[id]['text'];\n"
+"				this.files[id]['compare_edited_text']= text;\n"
+"			}\n"
+"\n"
+"			this.files[id]['edited']= to;\n"
+"		}\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.set_show_line_colors = function(new_value){\n"
+"		this.show_line_colors = new_value;\n"
+"\n"
+"		if( new_value )\n"
+"			this.selection_field.className	+= ' show_colors';\n"
+"		else\n"
+"			this.selection_field.className	= this.selection_field.className.replace( / show_colors/g, '' );\n"
+"	};var EA_keys = {8:\"Retour arriere\",9:\"Tabulation\",12:\"Milieu (pave numerique)\",13:\"Entrer\",16:\"Shift\",17:\"Ctrl\",18:\"Alt\",19:\"Pause\",20:\"Verr Maj\",27:\"Esc\",32:\"Space\",33:\"Page up\",34:\"Page down\",35:\"End\",36:\"Begin\",37:\"Left\",38:\"Up\",39:\"Right\",40:\"Down\",44:\"Impr ecran\",45:\"Inser\",46:\"Suppr\",91:\"Menu Demarrer Windows / touche pomme Mac\",92:\"Menu Demarrer Windows\",93:\"Menu contextuel Windows\",112:\"F1\",113:\"F2\",114:\"F3\",115:\"F4\",116:\"F5\",117:\"F6\",118:\"F7\",119:\"F8\",120:\"F9\",121:\"F10\",122:\"F11\",123:\"F12\",144:\"Verr Num\",145:\"Arret defil\"};\n"
+"\n"
+"\n"
+"\n"
+"function keyDown(e){\n"
+"	if(!e){	// if IE\n"
+"		e=event;\n"
+"	}\n"
+"	\n"
+"	// send the event to the plugins\n"
+"	for(var i in editArea.plugins){\n"
+"		if(typeof(editArea.plugins[i].onkeydown)==\"function\"){\n"
+"			if(editArea.plugins[i].onkeydown(e)===false){ // stop propaging\n"
+"				if(editArea.isIE)\n"
+"					e.keyCode=0;\n"
+"				return false;\n"
+"			}\n"
+"		}\n"
+"	}\n"
+"\n"
+"	var target_id=(e.target || e.srcElement).id;\n"
+"	var use=false;\n"
+"	if (EA_keys[e.keyCode])\n"
+"		letter=EA_keys[e.keyCode];\n"
+"	else\n"
+"		letter=String.fromCharCode(e.keyCode);\n"
+"	\n"
+"	var low_letter= letter.toLowerCase();\n"
+"			\n"
+"	if(letter==\"Page up\" && !AltPressed(e) && !editArea.isOpera){\n"
+"		editArea.execCommand(\"scroll_page\", {\"dir\": \"up\", \"shift\": ShiftPressed(e)});\n"
+"		use=true;\n"
+"	}else if(letter==\"Page down\" && !AltPressed(e) && !editArea.isOpera){\n"
+"		editArea.execCommand(\"scroll_page\", {\"dir\": \"down\", \"shift\": ShiftPressed(e)});\n"
+"		use=true;\n"
+"	}else if(editArea.is_editable==false){\n"
+"		// do nothing but also do nothing else (allow to navigate with page up and page down)\n"
+"		return true;\n"
+"	}else if(letter==\"Tabulation\" && target_id==\"textarea\" && !CtrlPressed(e) && !AltPressed(e)){	\n"
+"		if(ShiftPressed(e))\n"
+"			editArea.execCommand(\"invert_tab_selection\");\n"
+"		else\n"
+"			editArea.execCommand(\"tab_selection\");\n"
+"		\n"
+"		use=true;\n"
+"		if(editArea.isOpera || (editArea.isFirefox && editArea.isMac) )	// opera && firefox mac can't cancel tabulation events...\n"
+"			setTimeout(\"editArea.execCommand('focus');\", 1);\n"
+"	}else if(letter==\"Entrer\" && target_id==\"textarea\"){\n"
+"		if(editArea.press_enter())\n"
+"			use=true;\n"
+"	}else if(letter==\"Entrer\" && target_id==\"area_search\"){\n"
+"		editArea.execCommand(\"area_search\");\n"
+"		use=true;\n"
+"	}else  if(letter==\"Esc\"){\n"
+"		editArea.execCommand(\"close_all_inline_popup\", e);\n"
+"		use=true;\n"
+"	}else if(CtrlPressed(e) && !AltPressed(e) && !ShiftPressed(e)){\n"
+"		switch(low_letter){\n"
+"			case \"f\":				\n"
+"				editArea.execCommand(\"area_search\");\n"
+"				use=true;\n"
+"				break;\n"
+"			case \"r\":\n"
+"				editArea.execCommand(\"area_replace\");\n"
+"				use=true;\n"
+"				break;\n"
+"			case \"q\":\n"
+"				editArea.execCommand(\"close_all_inline_popup\", e);\n"
+"				use=true;\n"
+"				break;\n"
+"			case \"h\":\n"
+"				editArea.execCommand(\"change_highlight\");			\n"
+"				use=true;\n"
+"				break;\n"
+"			case \"g\":\n"
+"				setTimeout(\"editArea.execCommand('go_to_line');\", 5);	// the prompt stop the return false otherwise\n"
+"				use=true;\n"
+"				break;\n"
+"			case \"e\":\n"
+"				editArea.execCommand(\"show_help\");\n"
+"				use=true;\n"
+"				break;\n"
+"			case \"z\":\n"
+"				use=true;\n"
+"				editArea.execCommand(\"undo\");\n"
+"				break;\n"
+"			case \"y\":\n"
+"				use=true;\n"
+"				editArea.execCommand(\"redo\");\n"
+"				break;\n"
+"			default:\n"
+"				break;			\n"
+"		}		\n"
+"	}		\n"
+"	\n"
+"	// check to disable the redo possibility if the textarea content change\n"
+"	if(editArea.next.length > 0){\n"
+"		setTimeout(\"editArea.check_redo();\", 10);\n"
+"	}\n"
+"	\n"
+"	setTimeout(\"editArea.check_file_changes();\", 10);\n"
+"	\n"
+"	\n"
+"	if(use){\n"
+"		// in case of a control that sould'nt be used by IE but that is used => THROW a javascript error that will stop key action\n"
+"		if(editArea.isIE)\n"
+"			e.keyCode=0;\n"
+"		return false;\n"
+"	}\n"
+"	//alert(\"Test: \"+ letter + \" (\"+e.keyCode+\") ALT: \"+ AltPressed(e) + \" CTRL \"+ CtrlPressed(e) + \" SHIFT \"+ ShiftPressed(e));\n"
+"	\n"
+"	return true;\n"
+"	\n"
+"};\n"
+"\n"
+"\n"
+"// return true if Alt key is pressed\n"
+"function AltPressed(e) {\n"
+"	if (window.event) {\n"
+"		return (window.event.altKey);\n"
+"	} else {\n"
+"		if(e.modifiers)\n"
+"			return (e.altKey || (e.modifiers % 2));\n"
+"		else\n"
+"			return e.altKey;\n"
+"	}\n"
+"};\n"
+"\n"
+"// return true if Ctrl key is pressed\n"
+"function CtrlPressed(e) {\n"
+"	if (window.event) {\n"
+"		return (window.event.ctrlKey);\n"
+"	} else {\n"
+"		return (e.ctrlKey || (e.modifiers==2) || (e.modifiers==3) || (e.modifiers>5));\n"
+"	}\n"
+"};\n"
+"\n"
+"// return true if Shift key is pressed\n"
+"function ShiftPressed(e) {\n"
+"	if (window.event) {\n"
+"		return (window.event.shiftKey);\n"
+"	} else {\n"
+"		return (e.shiftKey || (e.modifiers>3));\n"
+"	}\n"
+"};\n"
+"	EditArea.prototype.show_search = function(){\n"
+"		if(_$(\"area_search_replace\").style.visibility==\"visible\"){\n"
+"			this.hidden_search();\n"
+"		}else{\n"
+"			this.open_inline_popup(\"area_search_replace\");\n"
+"			var text= this.area_get_selection();\n"
+"			var search= text.split(\"\\n\")[0];\n"
+"			_$(\"area_search\").value= search;\n"
+"			_$(\"area_search\").focus();\n"
+"		}\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.hidden_search= function(){\n"
+"		/*_$(\"area_search_replace\").style.visibility=\"hidden\";\n"
+"		this.textarea.focus();\n"
+"		var icon= _$(\"search\");\n"
+"		setAttribute(icon, \"class\", getAttribute(icon, \"class\").replace(/ selected/g, \"\") );*/\n"
+"		this.close_inline_popup(\"area_search_replace\");\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.area_search= function(mode){\n"
+"		\n"
+"		if(!mode)\n"
+"			mode=\"search\";\n"
+"		_$(\"area_search_msg\").innerHTML=\"\";		\n"
+"		var search=_$(\"area_search\").value;		\n"
+"		\n"
+"		this.textarea.focus();		\n"
+"		this.textarea.textareaFocused=true;\n"
+"		\n"
+"		var infos= this.get_selection_infos();	\n"
+"		var start= infos[\"selectionStart\"];\n"
+"		var pos=-1;\n"
+"		var pos_begin=-1;\n"
+"		var length=search.length;\n"
+"		\n"
+"		if(_$(\"area_search_replace\").style.visibility!=\"visible\"){\n"
+"			this.show_search();\n"
+"			return;\n"
+"		}\n"
+"		if(search.length==0){\n"
+"			_$(\"area_search_msg\").innerHTML=this.get_translation(\"search_field_empty\");\n"
+"			return;\n"
+"		}\n"
+"		// advance to the next occurence if no text selected\n"
+"		if(mode!=\"replace\" ){\n"
+"			if(_$(\"area_search_reg_exp\").checked)\n"
+"				start++;\n"
+"			else\n"
+"				start+= search.length;\n"
+"		}\n"
+"		\n"
+"		//search\n"
+"		if(_$(\"area_search_reg_exp\").checked){\n"
+"			// regexp search\n"
+"			var opt=\"m\";\n"
+"			if(!_$(\"area_search_match_case\").checked)\n"
+"				opt+=\"i\";\n"
+"			var reg= new RegExp(search, opt);\n"
+"			pos= infos[\"full_text\"].substr(start).search(reg);\n"
+"			pos_begin= infos[\"full_text\"].search(reg);\n"
+"			if(pos!=-1){\n"
+"				pos+=start;\n"
+"				length=infos[\"full_text\"].substr(start).match(reg)[0].length;\n"
+"			}else if(pos_begin!=-1){\n"
+"				length=infos[\"full_text\"].match(reg)[0].length;\n"
+"			}\n"
+"		}else{\n"
+"			if(_$(\"area_search_match_case\").checked){\n"
+"				pos= infos[\"full_text\"].indexOf(search, start); \n"
+"				pos_begin= infos[\"full_text\"].indexOf(search); \n"
+"			}else{\n"
+"				pos= infos[\"full_text\"].toLowerCase().indexOf(search.toLowerCase(), start); \n"
+"				pos_begin= infos[\"full_text\"].toLowerCase().indexOf(search.toLowerCase()); \n"
+"			}		\n"
+"		}\n"
+"		\n"
+"		// interpret result\n"
+"		if(pos==-1 && pos_begin==-1){\n"
+"			_$(\"area_search_msg\").innerHTML=\"<strong>\"+search+\"</strong> \"+this.get_translation(\"not_found\");\n"
+"			return;\n"
+"		}else if(pos==-1 && pos_begin != -1){\n"
+"			begin= pos_begin;\n"
+"			_$(\"area_search_msg\").innerHTML=this.get_translation(\"restart_search_at_begin\");\n"
+"		}else\n"
+"			begin= pos;\n"
+"		\n"
+"		//_$(\"area_search_msg\").innerHTML+=\"<strong>\"+search+\"</strong> found at \"+begin+\" strat at \"+start+\" pos \"+pos+\" curs\"+ infos[\"indexOfCursor\"]+\".\";\n"
+"		if(mode==\"replace\" && pos==infos[\"indexOfCursor\"]){\n"
+"			var replace= _$(\"area_replace\").value;\n"
+"			var new_text=\"\";			\n"
+"			if(_$(\"area_search_reg_exp\").checked){\n"
+"				var opt=\"m\";\n"
+"				if(!_$(\"area_search_match_case\").checked)\n"
+"					opt+=\"i\";\n"
+"				var reg= new RegExp(search, opt);\n"
+"				new_text= infos[\"full_text\"].substr(0, begin) + infos[\"full_text\"].substr(start).replace(reg, replace);\n"
+"			}else{\n"
+"				new_text= infos[\"full_text\"].substr(0, begin) + replace + infos[\"full_text\"].substr(begin + length);\n"
+"			}\n"
+"			this.textarea.value=new_text;\n"
+"			this.area_select(begin, length);\n"
+"			this.area_search();\n"
+"		}else\n"
+"			this.area_select(begin, length);\n"
+"	};\n"
+"	\n"
+"	\n"
+"	\n"
+"	\n"
+"	EditArea.prototype.area_replace= function(){		\n"
+"		this.area_search(\"replace\");\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.area_replace_all= function(){\n"
+"	/*	this.area_select(0, 0);\n"
+"		_$(\"area_search_msg\").innerHTML=\"\";\n"
+"		while(_$(\"area_search_msg\").innerHTML==\"\"){\n"
+"			this.area_replace();\n"
+"		}*/\n"
+"	\n"
+"		var base_text= this.textarea.value;\n"
+"		var search= _$(\"area_search\").value;		\n"
+"		var replace= _$(\"area_replace\").value;\n"
+"		if(search.length==0){\n"
+"			_$(\"area_search_msg\").innerHTML=this.get_translation(\"search_field_empty\");\n"
+"			return ;\n"
+"		}\n"
+"		\n"
+"		var new_text=\"\";\n"
+"		var nb_change=0;\n"
+"		if(_$(\"area_search_reg_exp\").checked){\n"
+"			// regExp\n"
+"			var opt=\"mg\";\n"
+"			if(!_$(\"area_search_match_case\").checked)\n"
+"				opt+=\"i\";\n"
+"			var reg= new RegExp(search, opt);\n"
+"			nb_change= infos[\"full_text\"].match(reg).length;\n"
+"			new_text= infos[\"full_text\"].replace(reg, replace);\n"
+"			\n"
+"		}else{\n"
+"			\n"
+"			if(_$(\"area_search_match_case\").checked){\n"
+"				var tmp_tab=base_text.split(search);\n"
+"				nb_change= tmp_tab.length -1 ;\n"
+"				new_text= tmp_tab.join(replace);\n"
+"			}else{\n"
+"				// case insensitive\n"
+"				var lower_value=base_text.toLowerCase();\n"
+"				var lower_search=search.toLowerCase();\n"
+"				\n"
+"				var start=0;\n"
+"				var pos= lower_value.indexOf(lower_search);				\n"
+"				while(pos!=-1){\n"
+"					nb_change++;\n"
+"					new_text+= this.textarea.value.substring(start , pos)+replace;\n"
+"					start=pos+ search.length;\n"
+"					pos= lower_value.indexOf(lower_search, pos+1);\n"
+"				}\n"
+"				new_text+= this.textarea.value.substring(start);				\n"
+"			}\n"
+"		}			\n"
+"		if(new_text==base_text){\n"
+"			_$(\"area_search_msg\").innerHTML=\"<strong>\"+search+\"</strong> \"+this.get_translation(\"not_found\");\n"
+"		}else{\n"
+"			this.textarea.value= new_text;\n"
+"			_$(\"area_search_msg\").innerHTML=\"<strong>\"+nb_change+\"</strong> \"+this.get_translation(\"occurrence_replaced\");\n"
+"			// firefox and opera doesn't manage with the focus if it's done directly\n"
+"			//editArea.textarea.focus();editArea.textarea.textareaFocused=true;\n"
+"			setTimeout(\"editArea.textarea.focus();editArea.textarea.textareaFocused=true;\", 100);\n"
+"		}\n"
+"		\n"
+"		\n"
+"	};\n"
+"	// change_to: \"on\" or \"off\"\n"
+"	EditArea.prototype.change_highlight= function(change_to){\n"
+"		if(this.settings[\"syntax\"].length==0 && change_to==false){\n"
+"			this.switchClassSticky(_$(\"highlight\"), 'editAreaButtonDisabled', true);\n"
+"			this.switchClassSticky(_$(\"reset_highlight\"), 'editAreaButtonDisabled', true);\n"
+"			return false;\n"
+"		}\n"
+"		\n"
+"		if(this.do_highlight==change_to)\n"
+"			return false;\n"
+"	\n"
+"			\n"
+"		this.getIESelection();\n"
+"		var pos_start= this.textarea.selectionStart;\n"
+"		var pos_end= this.textarea.selectionEnd;\n"
+"		\n"
+"		if(this.do_highlight===true || change_to==false)\n"
+"			this.disable_highlight();\n"
+"		else\n"
+"			this.enable_highlight();\n"
+"		this.textarea.focus();\n"
+"		this.textarea.selectionStart = pos_start;\n"
+"		this.textarea.selectionEnd = pos_end;\n"
+"		this.setIESelection();\n"
+"				\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.disable_highlight= function(displayOnly){\n"
+"		var t= this, a=t.textarea, new_Obj, old_class, new_class;\n"
+"			\n"
+"		t.selection_field.innerHTML=\"\";\n"
+"		t.selection_field_text.innerHTML=\"\";\n"
+"		t.content_highlight.style.visibility=\"hidden\";\n"
+"		// replacing the node is far more faster than deleting it's content in firefox\n"
+"		new_Obj= t.content_highlight.cloneNode(false);\n"
+"		new_Obj.innerHTML= \"\";			\n"
+"		t.content_highlight.parentNode.insertBefore(new_Obj, t.content_highlight);\n"
+"		t.content_highlight.parentNode.removeChild(t.content_highlight);	\n"
+"		t.content_highlight= new_Obj;\n"
+"		old_class= parent.getAttribute( a,\"class\" );\n"
+"		if(old_class){\n"
+"			new_class= old_class.replace( \"hidden\",\"\" );\n"
+"			parent.setAttribute( a, \"class\", new_class );\n"
+"		}\n"
+"	\n"
+"		a.style.backgroundColor=\"transparent\";	// needed in order to see the bracket finders\n"
+"		\n"
+"		//var icon= document.getElementById(\"highlight\");\n"
+"		//setAttribute(icon, \"class\", getAttribute(icon, \"class\").replace(/ selected/g, \"\") );\n"
+"		//t.restoreClass(icon);\n"
+"		//t.switchClass(icon,'editAreaButtonNormal');\n"
+"		t.switchClassSticky(_$(\"highlight\"), 'editAreaButtonNormal', true);\n"
+"		t.switchClassSticky(_$(\"reset_highlight\"), 'editAreaButtonDisabled', true);\n"
+"	\n"
+"		t.do_highlight=false;\n"
+"	\n"
+"		t.switchClassSticky(_$(\"change_smooth_selection\"), 'editAreaButtonSelected', true);\n"
+"		if(typeof(t.smooth_selection_before_highlight)!=\"undefined\" && t.smooth_selection_before_highlight===false){\n"
+"			t.change_smooth_selection_mode(false);\n"
+"		}\n"
+"		\n"
+"	//	this.textarea.style.backgroundColor=\"#FFFFFF\";\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.enable_highlight= function(){\n"
+"		var t=this, a=t.textarea, new_class;\n"
+"		t.show_waiting_screen();\n"
+"			\n"
+"		t.content_highlight.style.visibility=\"visible\";\n"
+"		new_class	=parent.getAttribute(a,\"class\")+\" hidden\";\n"
+"		parent.setAttribute( a, \"class\", new_class );\n"
+"		\n"
+"		// IE can't manage mouse click outside text range without this\n"
+"		if( t.isIE )\n"
+"			a.style.backgroundColor=\"#FFFFFF\";	\n"
+"\n"
+"		t.switchClassSticky(_$(\"highlight\"), 'editAreaButtonSelected', false);\n"
+"		t.switchClassSticky(_$(\"reset_highlight\"), 'editAreaButtonNormal', false);\n"
+"		\n"
+"		t.smooth_selection_before_highlight=t.smooth_selection;\n"
+"		if(!t.smooth_selection)\n"
+"			t.change_smooth_selection_mode(true);\n"
+"		t.switchClassSticky(_$(\"change_smooth_selection\"), 'editAreaButtonDisabled', true);\n"
+"		\n"
+"		\n"
+"		t.do_highlight=true;\n"
+"		t.resync_highlight();\n"
+"					\n"
+"		t.hide_waiting_screen();	\n"
+"	};\n"
+"	\n"
+"	/**\n"
+"	 * Ask to update highlighted text\n"
+"	 * @param Array infos - Array of datas returned by EditArea.get_selection_infos()\n"
+"	 */\n"
+"	EditArea.prototype.maj_highlight= function(infos){\n"
+"		// for speed mesure\n"
+"		var debug_opti=\"\",tps_start= new Date().getTime(), tps_middle_opti=new Date().getTime();\n"
+"		var t=this, hightlighted_text, updated_highlight;	\n"
+"		var textToHighlight=infos[\"full_text\"], doSyntaxOpti = false, doHtmlOpti = false, stay_begin=\"\", stay_end=\"\", trace_new , trace_last;\n"
+"		\n"
+"		if(t.last_text_to_highlight==infos[\"full_text\"] && t.resync_highlight!==true)\n"
+"			return;\n"
+"					\n"
+"		//  OPTIMISATION: will search to update only changed lines\n"
+"		if(t.reload_highlight===true){\n"
+"			t.reload_highlight=false;\n"
+"		}else if(textToHighlight.length==0){\n"
+"			textToHighlight=\"\\n \";\n"
+"		}else{\n"
+"			// get text change datas\n"
+"			changes = t.checkTextEvolution(t.last_text_to_highlight,textToHighlight);\n"
+"			\n"
+"			// check if it can only reparse the changed text\n"
+"			trace_new		= t.get_syntax_trace(changes.newTextLine).replace(/\\r/g, '');\n"
+"			trace_last		= t.get_syntax_trace(changes.lastTextLine).replace(/\\r/g, '');\n"
+"			doSyntaxOpti	= ( trace_new == trace_last );\n"
+"			\n"
+"			// check if the difference comes only from a new line created \n"
+"			// => we have to remember that the editor can automaticaly add tabulation or space after the new line) \n"
+"			if( !doSyntaxOpti && trace_new == \"\\n\"+trace_last && /^[ \t\s]*\\n[ \t\s]*$/.test( changes.newText.replace(/\\r/g, '') ) && changes.lastText ==\"\" )\n"
+"			{\n"
+"				doSyntaxOpti	= true;\n"
+"			}\n"
+"			\n"
+"			// we do the syntax optimisation\n"
+"			if( doSyntaxOpti ){\n"
+"						\n"
+"				tps_middle_opti=new Date().getTime();	\n"
+"			\n"
+"				stay_begin= t.last_hightlighted_text.split(\"\\n\").slice(0, changes.lineStart).join(\"\\n\");\n"
+"				if(changes.lineStart>0)\n"
+"					stay_begin+= \"\\n\";\n"
+"				stay_end= t.last_hightlighted_text.split(\"\\n\").slice(changes.lineLastEnd+1).join(\"\\n\");\n"
+"				if(stay_end.length>0)\n"
+"					stay_end= \"\\n\"+stay_end;\n"
+"					\n"
+"				// Final check to see that we're not in the middle of span tags\n"
+"				if( stay_begin.split('<span').length != stay_begin.split('</span').length \n"
+"					|| stay_end.split('<span').length != stay_end.split('</span').length )\n"
+"				{\n"
+"					doSyntaxOpti	= false;\n"
+"					stay_end		= '';\n"
+"					stay_begin		= '';\n"
+"				}\n"
+"				else\n"
+"				{\n"
+"					if(stay_begin.length==0 && changes.posLastEnd==-1)\n"
+"						changes.newTextLine+=\"\\n\";\n"
+"					textToHighlight=changes.newTextLine;\n"
+"				}\n"
+"			}\n"
+"			if(t.settings[\"debug\"]){\n"
+"				var ch =changes;\n"
+"				debug_opti= ( doSyntaxOpti?\"Optimisation\": \"No optimisation\" )\n"
+"					+\" start: \"+ch.posStart +\"(\"+ch.lineStart+\")\"\n"
+"					+\" end_new: \"+ ch.posNewEnd+\"(\"+ch.lineNewEnd+\")\"\n"
+"					+\" end_last: \"+ ch.posLastEnd+\"(\"+ch.lineLastEnd+\")\"\n"
+"					+\"\\nchanged_text: \"+ch.newText+\" => trace: \"+trace_new\n"
+"					+\"\\nchanged_last_text: \"+ch.lastText+\" => trace: \"+trace_last\n"
+"					//debug_opti+= \"\\nchanged: \"+ infos[\"full_text\"].substring(ch.posStart, ch.posNewEnd);\n"
+"					+ \"\\nchanged_line: \"+ch.newTextLine\n"
+"					+ \"\\nlast_changed_line: \"+ch.lastTextLine\n"
+"					+\"\\nstay_begin: \"+ stay_begin.slice(-100)\n"
+"					+\"\\nstay_end: \"+ stay_end.substr( 0, 100 );\n"
+"					//debug_opti=\"start: \"+stay_begin_len+ \"(\"+nb_line_start_unchanged+\") end: \"+ (stay_end_len)+ \"(\"+(splited.length-nb_line_end_unchanged)+\") \";\n"
+"					//debug_opti+=\"changed: \"+ textToHighlight.substring(stay_begin_len, textToHighlight.length-stay_end_len)+\" \\n\";\n"
+"					\n"
+"					//debug_opti+=\"changed: \"+ stay_begin.substr(stay_begin.length-200)+ \"----------\"+ textToHighlight+\"------------------\"+ stay_end.substr(0,200) +\"\\n\";\n"
+"					+\"\\n\";\n"
+"			}\n"
+"	\n"
+"			\n"
+"			// END OPTIMISATION\n"
+"		}\n"
+"\n"
+"		tps_end_opti	= new Date().getTime();	\n"
+"				\n"
+"		// apply highlight\n"
+"		updated_highlight	= t.colorize_text(textToHighlight);\n"
+"		tpsAfterReg			= new Date().getTime();\n"
+"		\n"
+"		/***\n"
+"		 * see if we can optimize for updating only the required part of the HTML code\n"
+"		 * \n"
+"		 * The goal here will be to find the text node concerned by the modification and to update it\n"
+"		 */\n"
+"		//-------------------------------------------\n"
+"		\n"
+"		// disable latest optimization tricks (introduced in 0.8.1 and removed in 0.8.2), TODO: check for another try later\n"
+"		doSyntaxOpti	= doHtmlOpti = false;\n"
+"		if( doSyntaxOpti )\n"
+"		{\n"
+"			try\n"
+"			{\n"
+"				var replacedBloc, i, nbStart = '', nbEnd = '', newHtml, lengthOld, lengthNew;\n"
+"				replacedBloc		= t.last_hightlighted_text.substring( stay_begin.length, t.last_hightlighted_text.length - stay_end.length );\n"
+"				\n"
+"				lengthOld	= replacedBloc.length;\n"
+"				lengthNew	= updated_highlight.length;\n"
+"				\n"
+"				// find the identical caracters at the beginning\n"
+"				for( i=0; i < lengthOld && i < lengthNew && replacedBloc.charAt(i) == updated_highlight.charAt(i) ; i++ )\n"
+"				{\n"
+"				}\n"
+"				nbStart = i;\n"
+"				// find the identical caracters at the end\n"
+"				for( i=0; i + nbStart < lengthOld && i + nbStart < lengthNew && replacedBloc.charAt(lengthOld-i-1) == updated_highlight.charAt(lengthNew-i-1) ; i++ )\n"
+"				{\n"
+"				}\n"
+"				nbEnd	= i;\n"
+"				//console.log( nbStart, nbEnd, replacedBloc, updated_highlight );\n"
+"				// get the changes\n"
+"				lastHtml	= replacedBloc.substring( nbStart, lengthOld - nbEnd );\n"
+"				newHtml		= updated_highlight.substring( nbStart, lengthNew - nbEnd );\n"
+"				\n"
+"				// We can do the optimisation only if we havn't touch to span elements\n"
+"				if( newHtml.indexOf('<span') == -1 && newHtml.indexOf('</span') == -1 \n"
+"					&& lastHtml.indexOf('<span') == -1 && lastHtml.indexOf('</span') == -1 )\n"
+"				{\n"
+"					var beginStr, nbOpendedSpan, nbClosedSpan, nbUnchangedChars, span, textNode;\n"
+"					doHtmlOpti		= true;\n"
+"					beginStr		= t.last_hightlighted_text.substr( 0, stay_begin.length + nbStart );\n"
+"					// fix special chars\n"
+"					newHtml			= newHtml.replace( /&lt;/g, '<').replace( /&gt;/g, '>').replace( /&amp;/g, '&');\n"
+"		\n"
+"					nbOpendedSpan	= beginStr.split('<span').length - 1;\n"
+"					nbClosedSpan	= beginStr.split('</span').length - 1;\n"
+"					// retrieve the previously opened span (Add 1 for the first level span?)\n"
+"					span 			= t.content_highlight.getElementsByTagName('span')[ nbOpendedSpan ];\n"
+"					\n"
+"					//--------[\n"
+"					// get the textNode to update\n"
+"					\n"
+"					// if we're inside a span, we'll take the one that is opened (can be a parent of the current span)\n"
+"					parentSpan		= span;\n"
+"					maxStartOffset	= maxEndOffset = 0;\n"
+"					\n"
+"					// it will be in the child of the root node \n"
+"					if( nbOpendedSpan == nbClosedSpan )\n"
+"					{\n"
+"						while( parentSpan.parentNode != t.content_highlight && parentSpan.parentNode.tagName != 'PRE' )\n"
+"						{\n"
+"							parentSpan	= parentSpan.parentNode;\n"
+"						}\n"
+"					}\n"
+"					// get the last opened span\n"
+"					else\n"
+"					{\n"
+"						maxStartOffset	= maxEndOffset = beginStr.length + 1;\n"
+"						// move to parent node for each closed span found after the lastest open span\n"
+"						nbClosed = beginStr.substr( Math.max( 0, beginStr.lastIndexOf( '<span', maxStartOffset - 1 ) ) ).split('</span').length - 1;\n"
+"						while( nbClosed > 0 )\n"
+"						{\n"
+"							nbClosed--;\n"
+"							parentSpan = parentSpan.parentNode;\n"
+"						}\n"
+"						\n"
+"						// find the position of the last opended tag\n"
+"						while( parentSpan.parentNode != t.content_highlight && parentSpan.parentNode.tagName != 'PRE' && ( tmpMaxStartOffset = Math.max( 0, beginStr.lastIndexOf( '<span', maxStartOffset - 1 ) ) ) < ( tmpMaxEndOffset = Math.max( 0, beginStr.lastIndexOf( '</span', maxEndOffset - 1 ) ) ) )\n"
+"						{\n"
+"							maxStartOffset	= tmpMaxStartOffset;\n"
+"							maxEndOffset	= tmpMaxEndOffset;\n"
+"						}\n"
+"					}\n"
+"					// Note: maxEndOffset is no more used but maxStartOffset will be used\n"
+"					\n"
+"					if( parentSpan.parentNode == t.content_highlight || parentSpan.parentNode.tagName == 'PRE' )\n"
+"					{\n"
+"						maxStartOffset	= Math.max( 0, beginStr.indexOf( '<span' ) );\n"
+"					}\n"
+"					\n"
+"					// find the matching text node (this will be one that will be at the end of the beginStr\n"
+"					if( maxStartOffset == beginStr.length )\n"
+"					{\n"
+"						nbSubSpanBefore	= 0;\n"
+"					}\n"
+"					else\n"
+"					{\n"
+"						lastEndPos 				= Math.max( 0, beginStr.lastIndexOf( '>', maxStartOffset ) );\n"
+"		\n"
+"						// count the number of sub spans\n"
+"						nbSubSpanBefore			= beginStr.substr( lastEndPos ).split('<span').length-1;\n"
+"					}\n"
+"					\n"
+"					// there is no sub-span before\n"
+"					if( nbSubSpanBefore == 0 )\n"
+"					{\n"
+"						textNode	= parentSpan.firstChild;\n"
+"					}\n"
+"					// we need to find where is the text node modified\n"
+"					else\n"
+"					{\n"
+"						// take the last direct child (no sub-child)\n"
+"						lastSubSpan	= parentSpan.getElementsByTagName('span')[ nbSubSpanBefore - 1 ];\n"
+"						while( lastSubSpan.parentNode != parentSpan )\n"
+"						{\n"
+"							lastSubSpan	= lastSubSpan.parentNode;\n"
+"						}\n"
+"\n"
+"						// associate to next text node following the last sub span\n"
+"						if( lastSubSpan.nextSibling == null || lastSubSpan.nextSibling.nodeType != 3 )\n"
+"						{\n"
+"							textNode	= document.createTextNode('');\n"
+"							lastSubSpan.parentNode.insertBefore( textNode, lastSubSpan.nextSibling );\n"
+"						}\n"
+"						else\n"
+"						{\n"
+"							textNode	= lastSubSpan.nextSibling;\n"
+"						}\n"
+"					}\n"
+"					//--------]\n"
+"					\n"
+"					\n"
+"					//--------[\n"
+"					// update the textNode content\n"
+"					\n"
+"					// number of caracters after the last opened of closed span\n"
+"					//nbUnchangedChars = ( lastIndex = beginStr.lastIndexOf( '>' ) ) == -1 ? beginStr.length : beginStr.length - ( lastIndex + 1 );\n"
+"					//nbUnchangedChars =  ? beginStr.length : beginStr.substr( lastIndex + 1 ).replace( /&lt;/g, '<').replace( /&gt;/g, '>').replace( /&amp;/g, '&').length;\n"
+"					\n"
+"					if( ( lastIndex = beginStr.lastIndexOf( '>' ) ) == -1 )\n"
+"					{\n"
+"						nbUnchangedChars	= beginStr.length;\n"
+"					}\n"
+"					else\n"
+"					{\n"
+"						nbUnchangedChars	= beginStr.substr( lastIndex + 1 ).replace( /&lt;/g, '<').replace( /&gt;/g, '>').replace( /&amp;/g, '&').length; 	\n"
+"						//nbUnchangedChars	+= beginStr.substr( ).replace( /&/g, '&amp;').replace( /</g, '&lt;').replace( />/g, '&gt;').length - beginStr.length;\n"
+"					}\n"
+"					//alert( nbUnchangedChars );\n"
+"					//	console.log( span, textNode, nbOpendedSpan,nbClosedSpan,  span.nextSibling, textNode.length, nbUnchangedChars, lastHtml, lastHtml.length, newHtml, newHtml.length );\n"
+"					//	alert( textNode.parentNode.className +'-'+ textNode.parentNode.tagName+\"\\n\"+ textNode.data +\"\\n\"+ nbUnchangedChars +\"\\n\"+ lastHtml.length +\"\\n\"+ newHtml +\"\\n\"+ newHtml.length  );\n"
+"				//	console.log( nbUnchangedChars, lastIndex, beginStr.length, beginStr.replace(/&/g, '&amp;'), lastHtml.length, '|', newHtml.replace( /\t/g, 't').replace( /\\n/g, 'n').replace( /\\r/g, 'r'), lastHtml.replace( /\t/g, 't').replace( /\\n/g, 'n').replace( /\\r/, 'r') );\n"
+"				//	console.log( textNode.data.replace(/&/g, '&amp;') );\n"
+"					// IE only manage \\r for cariage return in textNode and not \\n or \\r\\n\n"
+"					if( t.isIE )\n"
+"					{\n"
+"						nbUnchangedChars	-= ( beginStr.substr( beginStr.length - nbUnchangedChars ).split(\"\\n\").length - 1 );\n"
+"						//alert( textNode.data.replace(/\\r/g, '_r').replace(/\\n/g, '_n')); \n"
+"						textNode.replaceData( nbUnchangedChars, lastHtml.replace(/\\n/g, '').length, newHtml.replace(/\\n/g, '') );\n"
+"					}\n"
+"					else\n"
+"					{\n"
+"						textNode.replaceData( nbUnchangedChars, lastHtml.length, newHtml );\n"
+"					}\n"
+"					//--------]\n"
+"				}\n"
+"			}\n"
+"			// an exception shouldn't occured but if replaceData failed at least it won't break everything\n"
+"			catch( e )\n"
+"			{\n"
+"		//		throw e;\n"
+"			//	console.log( e );\n"
+"				doHtmlOpti	= false;\n"
+"			}\n"
+"			\n"
+"		}\n"
+"	\n"
+"		/*** END HTML update's optimisation ***/\n"
+"		// end test\n"
+"		\n"
+"	//			console.log(  (TPS6-TPS5), (TPS5-TPS4), (TPS4-TPS3), (TPS3-TPS2), (TPS2-TPS1), _CPT );\n"
+"		// get the new highlight content\n"
+"		tpsAfterOpti2		= new Date().getTime();\n"
+"		hightlighted_text	= stay_begin + updated_highlight + stay_end;\n"
+"		if( !doHtmlOpti )\n"
+"		{\n"
+"			// update the content of the highlight div by first updating a clone node (as there is no display in the same time for t node it's quite faster (5*))\n"
+"			var new_Obj= t.content_highlight.cloneNode(false);\n"
+"			if( ( t.isIE && t.isIE < 8 ) || ( t.isOpera && t.isOpera < 9.6 ) )\n"
+"				new_Obj.innerHTML= \"<pre><span class='\"+ t.settings[\"syntax\"] +\"'>\" + hightlighted_text + \"</span></pre>\";	\n"
+"			else\n"
+"				new_Obj.innerHTML= \"<span class='\"+ t.settings[\"syntax\"] +\"'>\"+ hightlighted_text +\"</span>\";\n"
+"	\n"
+"			t.content_highlight.parentNode.replaceChild(new_Obj, t.content_highlight);\n"
+"		\n"
+"			t.content_highlight= new_Obj;\n"
+"		}\n"
+"		\n"
+"		t.last_text_to_highlight= infos[\"full_text\"];\n"
+"		t.last_hightlighted_text= hightlighted_text;\n"
+"		\n"
+"		tps3=new Date().getTime();\n"
+"	\n"
+"		if(t.settings[\"debug\"]){\n"
+"			//lineNumber=tab_text.length;\n"
+"			//t.debug.value+=\" \\nNB char: \"+_$(\"src\").value.length+\" Nb line: \"+ lineNumber;\n"
+"		\n"
+"			t.debug.value= \"Tps optimisation \"+(tps_end_opti-tps_start)\n"
+"				+\" | tps reg exp: \"+ (tpsAfterReg-tps_end_opti)\n"
+"				+\" | tps opti HTML : \"+ (tpsAfterOpti2-tpsAfterReg) + ' '+ ( doHtmlOpti ? 'yes' : 'no' )\n"
+"				+\" | tps update highlight content: \"+ (tps3-tpsAfterOpti2)\n"
+"				+\" | tpsTotal: \"+ (tps3-tps_start)\n"
+"				+ \"(\"+tps3+\")\\n\"+ debug_opti;\n"
+"		//	t.debug.value+= \"highlight\\n\"+hightlighted_text;*/\n"
+"		}\n"
+"		\n"
+"	};\n"
+"	\n"
+"	EditArea.prototype.resync_highlight= function(reload_now){\n"
+"		this.reload_highlight=true;\n"
+"		this.last_text_to_highlight=\"\";\n"
+"		this.focus();		\n"
+"		if(reload_now)\n"
+"			this.check_line_selection(false); \n"
+"	};	\n"
+"	/*EditArea.prototype.comment_or_quotes= function(v0, v1, v2, v3, v4,v5,v6,v7,v8,v9, v10){\n"
+"		new_class=\"quotes\";\n"
+"		if(v6 && v6 != undefined && v6!=\"\")\n"
+"			new_class=\"comments\";\n"
+"		return \"µ__\"+ new_class +\"__µ\"+v0+\"µ_END_µ\";\n"
+"\n"
+"	};*/\n"
+"\n"
+"/*	EditArea.prototype.htmlTag= function(v0, v1, v2, v3, v4,v5,v6,v7,v8,v9, v10){\n"
+"		res=\"<span class=htmlTag>\"+v2;\n"
+"		alert(\"v2: \"+v2+\" v3: \"+v3);\n"
+"		tab=v3.split(\"=\");\n"
+"		attributes=\"\";\n"
+"		if(tab.length>1){\n"
+"			attributes=\"<span class=attribute>\"+tab[0]+\"</span>=\";\n"
+"			for(i=1; i<tab.length-1; i++){\n"
+"				cut=tab[i].lastIndexOf(\"&nbsp;\");\n"
+"				attributes+=\"<span class=attributeVal>\"+tab[i].substr(0,cut)+\"</span>\";\n"
+"				attributes+=\"<span class=attribute>\"+tab[i].substr(cut)+\"</span>=\";\n"
+"			}\n"
+"			attributes+=\"<span class=attributeVal>\"+tab[tab.length-1]+\"</span>\";\n"
+"		}\n"
+"		res+=attributes+v5+\"</span>\";\n"
+"		return res;\n"
+"	};*/\n"
+"\n"
+"	// determine if the selected text if a comment or a quoted text\n"
+"	EditArea.prototype.comment_or_quote= function(){\n"
+"		var new_class=\"\", close_tag=\"\", sy, arg, i;\n"
+"		sy 		= parent.editAreaLoader.syntax[editArea.current_code_lang];\n"
+"		arg		= EditArea.prototype.comment_or_quote.arguments[0];\n"
+"\n"
+"		for( i in sy[\"quotes\"] ){\n"
+"			if(arg.indexOf(i)==0){\n"
+"				new_class=\"quotesmarks\";\n"
+"				close_tag=sy[\"quotes\"][i];\n"
+"			}\n"
+"		}\n"
+"		if(new_class.length==0)\n"
+"		{\n"
+"			for(var i in sy[\"comments\"]){\n"
+"				if( arg.indexOf(i)==0 ){\n"
+"					new_class=\"comments\";\n"
+"					close_tag=sy[\"comments\"][i];\n"
+"				}\n"
+"			}\n"
+"		}\n"
+"		// for single line comment the \\n must not be included in the span tags\n"
+"		if(close_tag==\"\\n\"){\n"
+"			return \"µ__\"+ new_class +\"__µ\"+ arg.replace(/(\\r?\\n)?$/m, \"µ_END_µ$1\");\n"
+"		}else{\n"
+"			// the closing tag must be set only if the comment or quotes is closed\n"
+"			reg= new RegExp(parent.editAreaLoader.get_escaped_regexp(close_tag)+\"$\", \"m\");\n"
+"			if( arg.search(reg)!=-1 )\n"
+"				return \"µ__\"+ new_class +\"__µ\"+ arg +\"µ_END_µ\";\n"
+"			else\n"
+"				return \"µ__\"+ new_class +\"__µ\"+ arg;\n"
+"		}\n"
+"	};\n"
+"\n"
+"/*\n"
+"	// apply special tags arround text to highlight\n"
+"	EditArea.prototype.custom_highlight= function(){\n"
+"		res= EditArea.prototype.custom_highlight.arguments[1]+\"µ__\"+ editArea.reg_exp_span_tag +\"__µ\" + EditArea.prototype.custom_highlight.arguments[2]+\"µ_END_µ\";\n"
+"		if(EditArea.prototype.custom_highlight.arguments.length>5)\n"
+"			res+= EditArea.prototype.custom_highlight.arguments[ EditArea.prototype.custom_highlight.arguments.length-3 ];\n"
+"		return res;\n"
+"	};\n"
+"	*/\n"
+"\n"
+"	// return identication that allow to know if revalidating only the text line won't make the syntax go mad\n"
+"	EditArea.prototype.get_syntax_trace= function(text){\n"
+"		if(this.settings[\"syntax\"].length>0 && parent.editAreaLoader.syntax[this.settings[\"syntax\"]][\"syntax_trace_regexp\"])\n"
+"			return text.replace(parent.editAreaLoader.syntax[this.settings[\"syntax\"]][\"syntax_trace_regexp\"], \"$3\");\n"
+"		else\n"
+"			return text; // fix for http://sourceforge.net/tracker/?func=detail&aid=3137461&group_id=164008&atid=829999\n"
+"	};\n"
+"\n"
+"\n"
+"	EditArea.prototype.colorize_text= function(text){\n"
+"		//text=\"<div id='result' class='area' style='position: relative; z-index: 4; height: 500px; overflow: scroll;border: solid black 1px;'> \";\n"
+"	  /*\n"
+"		if(this.isOpera){\n"
+"			// opera can't use pre element tabulation cause a tab=6 chars in the textarea and 8 chars in the pre\n"
+"			text= this.replace_tab(text);\n"
+"		}*/\n"
+"\n"
+"		text= \" \"+text; // for easier regExp\n"
+"\n"
+"		/*if(this.do_html_tags)\n"
+"			text= text.replace(/(<[a-z]+ [^>]*>)/gi, '[__htmlTag__]$1[_END_]');*/\n"
+"		if(this.settings[\"syntax\"].length>0)\n"
+"			text= this.apply_syntax(text, this.settings[\"syntax\"]);\n"
+"\n"
+"		// remove the first space added\n"
+"		return text.substr(1).replace(/&/g,\"&amp;\").replace(/</g,\"&lt;\").replace(/>/g,\"&gt;\").replace(/µ_END_µ/g,\"</span>\").replace(/µ__([a-zA-Z0-9]+)__µ/g,\"<span class='$1'>\");\n"
+"	};\n"
+"\n"
+"	EditArea.prototype.apply_syntax= function(text, lang){\n"
+"		var sy;\n"
+"		this.current_code_lang=lang;\n"
+"\n"
+"		if(!parent.editAreaLoader.syntax[lang])\n"
+"			return text;\n"
+"\n"
+"		sy = parent.editAreaLoader.syntax[lang];\n"
+"		if(sy[\"custom_regexp\"]['before']){\n"
+"			for( var i in sy[\"custom_regexp\"]['before']){\n"
+"				var convert=\"$1µ__\"+ sy[\"custom_regexp\"]['before'][i]['class'] +\"__µ$2µ_END_µ$3\";\n"
+"				text= text.replace(sy[\"custom_regexp\"]['before'][i]['regexp'], convert);\n"
+"			}\n"
+"		}\n"
+"\n"
+"		if(sy[\"comment_or_quote_reg_exp\"]){\n"
+"			//setTimeout(\"_$('debug_area').value=editArea.comment_or_quote_reg_exp;\", 500);\n"
+"			text= text.replace(sy[\"comment_or_quote_reg_exp\"], this.comment_or_quote);\n"
+"		}\n"
+"\n"
+"		if(sy[\"keywords_reg_exp\"]){\n"
+"			for(var i in sy[\"keywords_reg_exp\"]){\n"
+"				text= text.replace(sy[\"keywords_reg_exp\"][i], 'µ__'+i+'__µ$2µ_END_µ');\n"
+"			}\n"
+"		}\n"
+"\n"
+"		if(sy[\"delimiters_reg_exp\"]){\n"
+"			text= text.replace(sy[\"delimiters_reg_exp\"], 'µ__delimiters__µ$1µ_END_µ');\n"
+"		}\n"
+"\n"
+"		if(sy[\"operators_reg_exp\"]){\n"
+"			text= text.replace(sy[\"operators_reg_exp\"], 'µ__operators__µ$1µ_END_µ');\n"
+"		}\n"
+"\n"
+"		if(sy[\"custom_regexp\"]['after']){\n"
+"			for( var i in sy[\"custom_regexp\"]['after']){\n"
+"				var convert=\"$1µ__\"+ sy[\"custom_regexp\"]['after'][i]['class'] +\"__µ$2µ_END_µ$3\";\n"
+"				text= text.replace(sy[\"custom_regexp\"]['after'][i]['regexp'], convert);\n"
+"			}\n"
+"		}\n"
+"\n"
+"		return text;\n"
+"	};\n"
+"/**\n"
+" * Charmap plugin\n"
+" * by Christophe Dolivet\n"
+" * v0.1 (2006/09/22)\n"
+" * \n"
+" *    \n"
+" * This plugin allow to use a visual keyboard allowing to insert any UTF-8 characters in the text.\n"
+" * \n"
+" * - plugin name to add to the plugin list: \"charmap\"\n"
+" * - plugin name to add to the toolbar list: \"charmap\" \n"
+" * - possible parameters to add to EditAreaLoader.init(): \n"
+" * 		\"charmap_default\": (String) define the name of the default character range displayed on popup display\n"
+" * 							(default: \"arrows\")\n"
+" * \n"
+" * \n"
+" */\n"
+"   \n"
+"var EditArea_charmap= {\n"
+"	/**\n"
+"	 * Get called once this file is loaded (editArea still not initialized)\n"
+"	 *\n"
+"	 * @return nothing	 \n"
+"	 */	 	 	\n"
+"	init: function(){	\n"
+"		this.default_language=\"Arrows\";\n"
+"	}\n"
+"	\n"
+"	/**\n"
+"	 * Returns the HTML code for a specific control string or false if this plugin doesn't have that control.\n"
+"	 * A control can be a button, select list or any other HTML item to present in the EditArea user interface.\n"
+"	 * Language variables such as {$lang_somekey} will also be replaced with contents from\n"
+"	 * the language packs.\n"
+"	 * \n"
+"	 * @param {string} ctrl_name: the name of the control to add	  \n"
+"	 * @return HTML code for a specific control or false.\n"
+"	 * @type string	or boolean\n"
+"	 */	\n"
+"	,get_control_html: function(ctrl_name){\n"
+"		switch(ctrl_name){\n"
+"			case \"charmap\":\n"
+"				// Control id, button img, command\n"
+"				return parent.editAreaLoader.get_button_html('charmap_but', 'charmap.gif', 'charmap_press', false, this.baseURL);\n"
+"		}\n"
+"		return false;\n"
+"	}\n"
+"	/**\n"
+"	 * Get called once EditArea is fully loaded and initialised\n"
+"	 *	 \n"
+"	 * @return nothing\n"
+"	 */	 	 	\n"
+"	,onload: function(){ \n"
+"		if(editArea.settings[\"charmap_default\"] && editArea.settings[\"charmap_default\"].length>0)\n"
+"			this.default_language= editArea.settings[\"charmap_default\"];\n"
+"	}\n"
+"	\n"
+"	/**\n"
+"	 * Is called each time the user touch a keyboard key.\n"
+"	 *	 \n"
+"	 * @param (event) e: the keydown event\n"
+"	 * @return true - pass to next handler in chain, false - stop chain execution\n"
+"	 * @type boolean	 \n"
+"	 */\n"
+"	,onkeydown: function(e){\n"
+"		\n"
+"	}\n"
+"	\n"
+"	/**\n"
+"	 * Executes a specific command, this function handles plugin commands.\n"
+"	 *\n"
+"	 * @param {string} cmd: the name of the command being executed\n"
+"	 * @param {unknown} param: the parameter of the command	 \n"
+"	 * @return true - pass to next handler in chain, false - stop chain execution\n"
+"	 * @type boolean	\n"
+"	 */\n"
+"	,execCommand: function(cmd, param){\n"
+"		// Handle commands\n"
+"		switch(cmd){\n"
+"			case \"charmap_press\":\n"
+"				win= window.open(this.baseURL+\"popup.html\", \"charmap\", \"width=500,height=270,scrollbars=yes,resizable=yes\");\n"
+"				win.focus();\n"
+"				return false;\n"
+"		}\n"
+"		// Pass to next handler in chain\n"
+"		return true;\n"
+"	}\n"
+"	\n"
+"};\n"
+"\n"
+"// Adds the plugin class to the list of available EditArea plugins\n"
+"editArea.add_plugin(\"charmap\", EditArea_charmap);\n"
+"/**\n"
+" * Plugin designed for test prupose. It add a button (that manage an alert) and a select (that allow to insert tags) in the toolbar.\n"
+" * This plugin also disable the \"f\" key in the editarea, and load a CSS and a JS file\n"
+" */  \n"
+"var EditArea_test= {\n"
+"	/**\n"
+"	 * Get called once this file is loaded (editArea still not initialized)\n"
+"	 *\n"
+"	 * @return nothing	 \n"
+"	 */	 	 	\n"
+"	init: function(){	\n"
+"		//	alert(\"test init: \"+ this._someInternalFunction(2, 3));\n"
+"		editArea.load_css(this.baseURL+\"css/test.css\");\n"
+"		editArea.load_script(this.baseURL+\"test2.js\");\n"
+"	}\n"
+"	/**\n"
+"	 * Returns the HTML code for a specific control string or false if this plugin doesn't have that control.\n"
+"	 * A control can be a button, select list or any other HTML item to present in the EditArea user interface.\n"
+"	 * Language variables such as {$lang_somekey} will also be replaced with contents from\n"
+"	 * the language packs.\n"
+"	 * \n"
+"	 * @param {string} ctrl_name: the name of the control to add	  \n"
+"	 * @return HTML code for a specific control or false.\n"
+"	 * @type string	or boolean\n"
+"	 */	\n"
+"	,get_control_html: function(ctrl_name){\n"
+"		switch(ctrl_name){\n"
+"			case \"test_but\":\n"
+"				// Control id, button img, command\n"
+"				return parent.editAreaLoader.get_button_html('test_but', 'test.gif', 'test_cmd', false, this.baseURL);\n"
+"			case \"test_select\":\n"
+"				html= \"<select id='test_select' onchange='javascript:editArea.execCommand(\\\"test_select_change\\\")' fileSpecific='no'>\"\n"
+"					+\"			<option value='-1'>{$test_select}</option>\"\n"
+"					+\"			<option value='h1'>h1</option>\"\n"
+"					+\"			<option value='h2'>h2</option>\"\n"
+"					+\"			<option value='h3'>h3</option>\"\n"
+"					+\"			<option value='h4'>h4</option>\"\n"
+"					+\"			<option value='h5'>h5</option>\"\n"
+"					+\"			<option value='h6'>h6</option>\"\n"
+"					+\"		</select>\";\n"
+"				return html;\n"
+"		}\n"
+"		return false;\n"
+"	}\n"
+"	/**\n"
+"	 * Get called once EditArea is fully loaded and initialised\n"
+"	 *	 \n"
+"	 * @return nothing\n"
+"	 */	 	 	\n"
+"	,onload: function(){ \n"
+"		alert(\"test load\");\n"
+"	}\n"
+"	\n"
+"	/**\n"
+"	 * Is called each time the user touch a keyboard key.\n"
+"	 *	 \n"
+"	 * @param (event) e: the keydown event\n"
+"	 * @return true - pass to next handler in chain, false - stop chain execution\n"
+"	 * @type boolean	 \n"
+"	 */\n"
+"	,onkeydown: function(e){\n"
+"		var str= String.fromCharCode(e.keyCode);\n"
+"		// desactivate the \"f\" character\n"
+"		if(str.toLowerCase()==\"f\"){\n"
+"			return true;\n"
+"		}\n"
+"		return false;\n"
+"	}\n"
+"	\n"
+"	/**\n"
+"	 * Executes a specific command, this function handles plugin commands.\n"
+"	 *\n"
+"	 * @param {string} cmd: the name of the command being executed\n"
+"	 * @param {unknown} param: the parameter of the command	 \n"
+"	 * @return true - pass to next handler in chain, false - stop chain execution\n"
+"	 * @type boolean	\n"
+"	 */\n"
+"	,execCommand: function(cmd, param){\n"
+"		// Handle commands\n"
+"		switch(cmd){\n"
+"			case \"test_select_change\":\n"
+"				var val= document.getElementById(\"test_select\").value;\n"
+"				if(val!=-1)\n"
+"					parent.editAreaLoader.insertTags(editArea.id, \"<\"+val+\">\", \"</\"+val+\">\");\n"
+"				document.getElementById(\"test_select\").options[0].selected=true; \n"
+"				return false;\n"
+"			case \"test_cmd\":\n"
+"				alert(\"user clicked on test_cmd\");\n"
+"				return false;\n"
+"		}\n"
+"		// Pass to next handler in chain\n"
+"		return true;\n"
+"	}\n"
+"	\n"
+"	/**\n"
+"	 * This is just an internal plugin method, prefix all internal methods with a _ character.\n"
+"	 * The prefix is needed so they doesn't collide with future EditArea callback functions.\n"
+"	 *\n"
+"	 * @param {string} a Some arg1.\n"
+"	 * @param {string} b Some arg2.\n"
+"	 * @return Some return.\n"
+"	 * @type unknown\n"
+"	 */\n"
+"	,_someInternalFunction : function(a, b) {\n"
+"		return a+b;\n"
+"	}\n"
+"};\n"
+"\n"
+"// Adds the plugin class to the list of available EditArea plugins\n"
+"editArea.add_plugin(\"test\", EditArea_test);\n"
+"</script>";
editAreaLoader.all_plugins_loaded=true;
editAreaLoader.template= "<?xml version=\"1.0\" encoding=\"UTF-8\"?> <!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\"> <html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" > <head> <title>EditArea</title> <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" /> <meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\"/> [__CSSRULES__] [__JSCODE__] </head> <body> <div id='editor'> <div class='area_toolbar' id='toolbar_1'>[__TOOLBAR__]</div> <div class='area_toolbar' id='tab_browsing_area'><ul id='tab_browsing_list' class='menu'> <li> </li> </ul></div> <div id='result'> <div id='no_file_selected'></div> <div id='container'> <div id='cursor_pos' class='edit_area_cursor'>&nbsp;</div> <div id='end_bracket' class='edit_area_cursor'>&nbsp;</div> <div id='selection_field'></div> <div id='line_number' selec='none'></div> <div id='content_highlight'></div> <div id='test_font_size'></div> <div id='selection_field_text'></div> <textarea id='textarea' wrap='off' onchange='editArea.execCommand(\"onchange\");' onfocus='javascript:editArea.textareaFocused=true;' onblur='javascript:editArea.textareaFocused=false;'> </textarea> </div> </div> <div class='area_toolbar' id='toolbar_2'> <table class='statusbar' cellspacing='0' cellpadding='0'> <tr> <td class='total' selec='none'>{$position}:</td> <td class='infos' selec='none'> {$line_abbr} <span  id='linePos'>0</span>, {$char_abbr} <span id='currPos'>0</span> </td> <td class='total' selec='none'>{$total}:</td> <td class='infos' selec='none'> {$line_abbr} <span id='nbLine'>0</span>, {$char_abbr} <span id='nbChar'>0</span> </td> <td class='resize'> <span id='resize_area'><img src='[__BASEURL__]images/statusbar_resize.gif' alt='resize' selec='none'></span> </td> </tr> </table> </div> </div> <div id='processing'> <div id='processing_text'> {$processing} </div> </div> <div id='area_search_replace' class='editarea_popup'> <table cellspacing='2' cellpadding='0' style='width: 100%'> <tr> <td selec='none'>{$search}</td> <td><input type='text' id='area_search' /></td> <td id='close_area_search_replace'> <a onclick='Javascript:editArea.execCommand(\"hidden_search\")'><img selec='none' src='[__BASEURL__]images/close.gif' alt='{$close_popup}' title='{$close_popup}' /></a><br /> </tr><tr> <td selec='none'>{$replace}</td> <td><input type='text' id='area_replace' /></td> <td><img id='move_area_search_replace' onmousedown='return parent.start_move_element(event,\"area_search_replace\", parent.frames[\"frame_\"+editArea.id]);'  src='[__BASEURL__]images/move.gif' alt='{$move_popup}' title='{$move_popup}' /></td> </tr> </table> <div class='button'> <input type='checkbox' id='area_search_match_case' /><label for='area_search_match_case' selec='none'>{$match_case}</label> <input type='checkbox' id='area_search_reg_exp' /><label for='area_search_reg_exp' selec='none'>{$reg_exp}</label> <br /> <a onclick='Javascript:editArea.execCommand(\"area_search\")' selec='none'>{$find_next}</a> <a onclick='Javascript:editArea.execCommand(\"area_replace\")' selec='none'>{$replace}</a> <a onclick='Javascript:editArea.execCommand(\"area_replace_all\")' selec='none'>{$replace_all}</a><br /> </div> <div id='area_search_msg' selec='none'></div> </div> <div id='edit_area_help' class='editarea_popup'> <div class='close_popup'> <a onclick='Javascript:editArea.execCommand(\"close_all_inline_popup\")'><img src='[__BASEURL__]images/close.gif' alt='{$close_popup}' title='{$close_popup}' /></a> </div> <div><h2>Editarea [__EA_VERSION__]</h2><br /> <h3>{$shortcuts}:</h3> {$tab}: {$add_tab}<br /> {$shift}+{$tab}: {$remove_tab}<br /> {$ctrl}+f: {$search_command}<br /> {$ctrl}+r: {$replace_command}<br /> {$ctrl}+h: {$highlight}<br /> {$ctrl}+g: {$go_to_line}<br /> {$ctrl}+z: {$undo}<br /> {$ctrl}+y: {$redo}<br /> {$ctrl}+e: {$help}<br /> {$ctrl}+q, {$esc}: {$close_popup}<br /> {$accesskey} E: {$toggle}<br /> <br /> <em>{$about_notice}</em> <br /><div class='copyright'>&copy; Christophe Dolivet 2007-2010</div> </div> </div> </body> </html> ";
editAreaLoader.iframe_css= "<style>body,html{margin:0;padding:0;height:100%;border:none;overflow:hidden;background-color:#FFF;}body,html,table,form,textarea{font:12px monospace,sans-serif;}#editor{border:solid #888 1px;overflow:hidden;}#result{z-index:4;overflow-x:auto;overflow-y:scroll;border-top:solid #888 1px;border-bottom:solid #888 1px;position:relative;clear:both;}#result.empty{overflow:hidden;}#container{overflow:hidden;border:solid blue 0;position:relative;z-index:10;padding:0 5px 0 45px;}#textarea{position:relative;top:0;left:0;margin:0;padding:0;width:100%;height:100%;overflow:hidden;z-index:7;border-width:0;background-color:transparent;resize:none;}#textarea,#textarea:hover{outline:none;}#content_highlight{white-space:pre;margin:0;padding:0;position:absolute;z-index:4;overflow:visible;}#selection_field,#selection_field_text{margin:0;background-color:#E1F2F9;position:absolute;z-index:5;top:-100px;padding:0;white-space:pre;overflow:hidden;}#selection_field.show_colors {z-index:3;background-color:#EDF9FC;}#selection_field strong{font-weight:normal;}#selection_field.show_colors *,#selection_field_text * {visibility:hidden;}#selection_field_text{background-color:transparent;}#selection_field_text strong{font-weight:normal;background-color:#3399FE;color:#FFF;visibility:visible;}#container.word_wrap #content_highlight,#container.word_wrap #selection_field,#container.word_wrap #selection_field_text,#container.word_wrap #test_font_size{white-space:pre-wrap;white-space:-moz-pre-wrap !important;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;width:99%;}#line_number{position:absolute;overflow:hidden;border-right:solid black 1px;z-index:8;width:38px;padding:0 5px 0 0;margin:0 0 0 -45px;text-align:right;color:#AAAAAA;}#test_font_size{padding:0;margin:0;visibility:hidden;position:absolute;white-space:pre;}pre{margin:0;padding:0;}.hidden{opacity:0.2;}#result .edit_area_cursor{position:absolute;z-index:6;background-color:#FF6633;top:-100px;margin:0;}#result .edit_area_selection_field .overline{background-color:#996600;}.editarea_popup{border:solid 1px #888888;background-color:#ECE9D8;width:250px;padding:4px;position:absolute;visibility:hidden;z-index:15;top:-500px;}.editarea_popup,.editarea_popup table{font-family:sans-serif;font-size:10pt;}.editarea_popup img{border:0;}.editarea_popup .close_popup{float:right;line-height:16px;border:0;padding:0;}.editarea_popup h1,.editarea_popup h2,.editarea_popup h3,.editarea_popup h4,.editarea_popup h5,.editarea_popup h6{margin:0;padding:0;}.editarea_popup .copyright{text-align:right;}div#area_search_replace{}div#area_search_replace img{border:0;}div#area_search_replace div.button{text-align:center;line-height:1.7em;}div#area_search_replace .button a{cursor:pointer;border:solid 1px #888888;background-color:#DEDEDE;text-decoration:none;padding:0 2px;color:#000000;white-space:nowrap;}div#area_search_replace a:hover{background-color:#EDEDED;}div#area_search_replace  #move_area_search_replace{cursor:move;border:solid 1px #888;}div#area_search_replace  #close_area_search_replace{text-align:right;vertical-align:top;white-space:nowrap;}div#area_search_replace  #area_search_msg{height:18px;overflow:hidden;border-top:solid 1px #888;margin-top:3px;}#edit_area_help{width:350px;}#edit_area_help div.close_popup{float:right;}.area_toolbar{width:100%;margin:0;padding:0;background-color:#ECE9D8;text-align:center;}.area_toolbar,.area_toolbar table{font:11px sans-serif;}.area_toolbar img{border:0;vertical-align:middle;}.area_toolbar input{margin:0;padding:0;}.area_toolbar select{font-family:'MS Sans Serif',sans-serif,Verdana,Arial;font-size:7pt;font-weight:normal;margin:2px 0 0 0 ;padding:0;vertical-align:top;background-color:#F0F0EE;}table.statusbar{width:100%;}.area_toolbar td.infos{text-align:center;width:130px;border-right:solid 1px #888;border-width:0 1px 0 0;padding:0;}.area_toolbar td.total{text-align:right;width:50px;padding:0;}.area_toolbar td.resize{text-align:right;}.area_toolbar span#resize_area{cursor:nw-resize;visibility:hidden;}.editAreaButtonNormal,.editAreaButtonOver,.editAreaButtonDown,.editAreaSeparator,.editAreaSeparatorLine,.editAreaButtonDisabled,.editAreaButtonSelected {border:0; margin:0; padding:0; background:transparent;margin-top:0;margin-left:1px;padding:0;}.editAreaButtonNormal {border:1px solid #ECE9D8 !important;cursor:pointer;}.editAreaButtonOver {border:1px solid #0A246A !important;cursor:pointer;background-color:#B6BDD2;}.editAreaButtonDown {cursor:pointer;border:1px solid #0A246A !important;background-color:#8592B5;}.editAreaButtonSelected {border:1px solid #C0C0BB !important;cursor:pointer;background-color:#F4F2E8;}.editAreaButtonDisabled {filter:progid:DXImageTransform.Microsoft.Alpha(opacity=30);-moz-opacity:0.3;opacity:0.3;border:1px solid #F0F0EE !important;cursor:pointer;}.editAreaSeparatorLine {margin:1px 2px;background-color:#C0C0BB;width:2px;height:18px;}#processing{display:none;background-color:#ECE9D8;border:solid #888 1px;position:absolute;top:0;left:0;width:100%;height:100%;z-index:100;text-align:center;}#processing_text{position:absolute;left:50%;top:50%;width:200px;height:20px;margin-left:-100px;margin-top:-10px;text-align:center;}#tab_browsing_area{display:none;background-color:#CCC9A8;border-top:1px solid #888;text-align:left;margin:0;}#tab_browsing_list {padding:0;margin:0;list-style-type:none;white-space:nowrap;}#tab_browsing_list li {float:left;margin:-1px;}#tab_browsing_list a {position:relative;display:block;text-decoration:none;float:left;cursor:pointer;line-height:14px;}#tab_browsing_list a span {display:block;color:#000;background:#ECE9D8;border:1px solid #888;border-width:1px 1px 0;text-align:center;padding:2px 2px 1px 4px;position:relative;}#tab_browsing_list a b {display:block;border-bottom:2px solid #617994;}#tab_browsing_list a .edited {display:none;}#tab_browsing_list a.edited .edited {display:inline;}#tab_browsing_list a img{margin-left:7px;}#tab_browsing_list a.edited img{margin-left:3px;}#tab_browsing_list a:hover span {background:#F4F2E8;border-color:#0A246A;}#tab_browsing_list .selected a span{background:#046380;color:#FFF;}#no_file_selected{height:100%;width:150%;background:#CCC;display:none;z-index:20;position:absolute;}.non_editable #editor{border-width:0 1px;}.non_editable .area_toolbar{display:none;}#auto_completion_area{background:#FFF;border:solid 1px #888;position:absolute;z-index:15;width:280px;height:180px;overflow:auto;display:none;}#auto_completion_area a,#auto_completion_area a:visited{display:block;padding:0 2px 1px;color:#000;text-decoration:none;}#auto_completion_area a:hover,#auto_completion_area a:focus,#auto_completion_area a.focus{background:#D6E1FE;text-decoration:none;}#auto_completion_area ul{margin:0;padding:0;list-style:none inside;}#auto_completion_area li{padding:0;}#auto_completion_area .prefix{font-style:italic;padding:0 3px;}</style>";
editAreaLoader.load_syntax["css"] = {
	'DISPLAY_NAME' : 'CSS'
	,'COMMENT_SINGLE' : {1 : '@'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : ['"', "'"]
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'attributes' : [
			'aqua', 'azimuth', 'background-attachment', 'background-color',
			'background-image', 'background-position', 'background-repeat',
			'background', 'border-bottom-color', 'border-bottom-style',
			'border-bottom-width', 'border-left-color', 'border-left-style',
			'border-left-width', 'border-right', 'border-right-color',
			'border-right-style', 'border-right-width', 'border-top-color',
			'border-top-style', 'border-top-width','border-bottom', 'border-collapse',
			'border-left', 'border-width', 'border-color', 'border-spacing',
			'border-style', 'border-top', 'border',  'caption-side',
			'clear', 'clip', 'color', 'content', 'counter-increment', 'counter-reset',
			'cue-after', 'cue-before', 'cue', 'cursor', 'direction', 'display',
			'elevation', 'empty-cells', 'float', 'font-family', 'font-size',
			'font-size-adjust', 'font-stretch', 'font-style', 'font-variant',
			'font-weight', 'font', 'height', 'letter-spacing', 'line-height',
			'list-style', 'list-style-image', 'list-style-position', 'list-style-type',
			'margin-bottom', 'margin-left', 'margin-right', 'margin-top', 'margin',
			'marker-offset', 'marks', 'max-height', 'max-width', 'min-height',
			'min-width', 'opacity', 'orphans', 'outline', 'outline-color', 'outline-style',
			'outline-width', 'overflow', 'padding-bottom', 'padding-left',
			'padding-right', 'padding-top', 'padding', 'page', 'page-break-after',
			'page-break-before', 'page-break-inside', 'pause-after', 'pause-before',
			'pause', 'pitch', 'pitch-range',  'play-during', 'position', 'quotes',
			'richness', 'right', 'size', 'speak-header', 'speak-numeral', 'speak-punctuation',
			'speak', 'speech-rate', 'stress', 'table-layout', 'text-align', 'text-decoration',
			'text-indent', 'text-shadow', 'text-transform', 'top', 'unicode-bidi',
			'vertical-align', 'visibility', 'voice-family', 'volume', 'white-space', 'widows',
			'width', 'word-spacing', 'z-index', 'bottom', 'left'
		]
		,'values' : [
			'above', 'absolute', 'always', 'armenian', 'aural', 'auto', 'avoid',
			'baseline', 'behind', 'below', 'bidi-override', 'black', 'blue', 'blink', 'block', 'bold', 'bolder', 'both',
			'capitalize', 'center-left', 'center-right', 'center', 'circle', 'cjk-ideographic', 
            'close-quote', 'collapse', 'condensed', 'continuous', 'crop', 'crosshair', 'cross', 'cursive',
			'dashed', 'decimal-leading-zero', 'decimal', 'default', 'digits', 'disc', 'dotted', 'double',
			'e-resize', 'embed', 'extra-condensed', 'extra-expanded', 'expanded',
			'fantasy', 'far-left', 'far-right', 'faster', 'fast', 'fixed', 'fuchsia',
			'georgian', 'gray', 'green', 'groove', 'hebrew', 'help', 'hidden', 'hide', 'higher',
			'high', 'hiragana-iroha', 'hiragana', 'icon', 'inherit', 'inline-table', 'inline',
			'inset', 'inside', 'invert', 'italic', 'justify', 'katakana-iroha', 'katakana',
			'landscape', 'larger', 'large', 'left-side', 'leftwards', 'level', 'lighter', 'lime', 'line-through', 'list-item', 'loud', 'lower-alpha', 'lower-greek', 'lower-roman', 'lowercase', 'ltr', 'lower', 'low',
			'maroon', 'medium', 'message-box', 'middle', 'mix', 'monospace',
			'n-resize', 'narrower', 'navy', 'ne-resize', 'no-close-quote', 'no-open-quote', 'no-repeat', 'none', 'normal', 'nowrap', 'nw-resize',
			'oblique', 'olive', 'once', 'open-quote', 'outset', 'outside', 'overline',
			'pointer', 'portrait', 'purple', 'px',
			'red', 'relative', 'repeat-x', 'repeat-y', 'repeat', 'rgb', 'ridge', 'right-side', 'rightwards',
			's-resize', 'sans-serif', 'scroll', 'se-resize', 'semi-condensed', 'semi-expanded', 'separate', 'serif', 'show', 'silent', 'silver', 'slow', 'slower', 'small-caps', 'small-caption', 'smaller', 'soft', 'solid', 'spell-out', 'square',
			'static', 'status-bar', 'super', 'sw-resize',
			'table-caption', 'table-cell', 'table-column', 'table-column-group', 'table-footer-group', 'table-header-group', 'table-row', 'table-row-group', 'teal', 'text', 'text-bottom', 'text-top', 'thick', 'thin', 'transparent',
			'ultra-condensed', 'ultra-expanded', 'underline', 'upper-alpha', 'upper-latin', 'upper-roman', 'uppercase', 'url',
			'visible',
			'w-resize', 'wait', 'white', 'wider',
			'x-fast', 'x-high', 'x-large', 'x-loud', 'x-low', 'x-small', 'x-soft', 'xx-large', 'xx-small',
			'yellow', 'yes'
		]
		,'specials' : [
			'important'
		]
	}
	,'OPERATORS' :[
		':', ';', '!', '.', '#'
	]
	,'DELIMITERS' :[
		'{', '}'
	]
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #6381F8;'
		,'KEYWORDS' : {
			'attributes' : 'color: #48BDDF;'
			,'values' : 'color: #2B60FF;'
			,'specials' : 'color: #FF0000;'
			}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #60CA00;'
				
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'css.js'] = true;


/*
* last update: 2006-08-24
*/

editAreaLoader.load_syntax["html"] = {
	'DISPLAY_NAME' : 'HTML'
	,'COMMENT_SINGLE' : {}
	,'COMMENT_MULTI' : {'<!--' : '-->'}
	,'QUOTEMARKS' : {1: "'", 2: '"'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
	}
	,'OPERATORS' :[
	]
	,'DELIMITERS' :[
	]
	,'REGEXPS' : {
		'doctype' : {
			'search' : '()(<!DOCTYPE[^>]*>)()'
			,'class' : 'doctype'
			,'modifiers' : ''
			,'execute' : 'before' // before or after
		}
		,'tags' : {
			'search' : '(<)(/?[a-z][^ \r\n\t>]*)([^>]*>)'
			,'class' : 'tags'
			,'modifiers' : 'gi'
			,'execute' : 'before' // before or after
		}
		,'attributes' : {
			'search' : '( |\n|\r|\t)([^ \r\n\t=]+)(=)'
			,'class' : 'attributes'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
	}
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #6381F8;'
		,'KEYWORDS' : {
			}
		,'OPERATORS' : 'color: #E775F0;'
		,'DELIMITERS' : ''
		,'REGEXPS' : {
			'attributes': 'color: #B1AC41;'
			,'tags': 'color: #E62253;'
			,'doctype': 'color: #8DCFB5;'
			,'test': 'color: #00FF00;'
		}	
	}		
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'html.js'] = true;


// patch applied: https://sourceforge.net/tracker/?func=detail&aid=3107951&group_id=164008&atid=829999

// resorted and then augmented

editAreaLoader.load_syntax["js"] = {
	'DISPLAY_NAME' : 'Javascript'
	,'COMMENT_SINGLE' : {1 : '//'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'as',
			'break',
			'case',
			'catch',
			'continue',
			'decodeURI',
			'delete',
			'do',

			'else',
			'encodeURI',
			'eval',
			'finally',
			'for',
			'if',
			'in',
			'is',
			'item',

			'instanceof',
			'return',
			'switch',
			'this',
			'throw',
			'try',
			'typeof',
			'void',

			'while',
			'write',
			'with'
		]
 		,'keywords' : [
			'abstract',
			'Anchor',
			'Area',
			'Array',
			'assign',
			'Boolean',
			'Button',
			'byte',
			'callee',
			'char',
			'Checkbox',
			'class',
			'closed',
			'const',
			'constructor',
			'Date',
			'debugger',
			'default',
			'defaultStatus',
			'document',
			'double',
			'Element',
			'export',
			'extends',
			'false',
			'FileUpload',
			'final',
			'float',
			'Form',
			'Frame',
			'frames',
			'function',
			'getClass',
			'goto',
			'Hidden',
			'History',
			'Image',
			'implements',
			'import',
			'Infinity',
			'innerHeight',
			'innerWidth',
			'java',
			'JavaArray',
			'JavaClass',
			'JavaObject',
			'JavaPackage',
			'length',
			'Link',
			'location',
			'locationbar',
			'long',
			'Math',
			'menubar',
			'MimeType',
			'namespace',
			'NaN',
			'native',
			'navigator',
			'netscape',
			'new',
			'null',
			'Number',
			'Object',
			'onBlur',
			'onError',
			'onFocus',
			'onLoad',
			'onUnload',
			'opener',
			'Option',
			'outerHeight',
			'outerWidth',
			'package',
			'Packages',
			'pageXoffset',
			'pageYoffset',
			'parent',
			'Password',
			'personalbar',
			'Plugin',
			'private',
			'protected',
			'prototype',
			'public',
			'Radio',
			'ref',
			'RegExp',
			'Reset',
			'scrollbars',
			'Select',
			'self',
			'short',
			'statusbar',
			'String',
			'Submit',
			'sun',
			'super',
			'synchronized',
			'Text',
			'Textarea',
			'throws',
			'toolbar',
			'top',
			'transient',
			'true',
			'use',
			'var',
			'window'
		]
		,'functions' : [
 			// common functions for Window object
			'alert',
			'arguments',
			'back',
			'blur',
			'caller',
			'captureEvents',
			'clearInterval',
			'clearTimeout',
			'close',
			'confirm',
			'escape',
			'eval',
			'find',
			'focus',
			'forward',
			'handleEvent',
			'home',
			'isFinite',
			'isNan',
			'moveBy',
			'moveTo',
			'name',
			'navigate',
			'onblur',
			'onerror',
			'onfocus',
			'onload',
			'onmove',
			'onresize',
			'onunload',
			'open',
			'parseFloat',
			'parseInt',
			'print',
			'prompt',
			'releaseEvents',
			'resizeBy',
			'resizeTo',
			'routeEvent',
			'scroll',
			'scrollBy',
			'scrollTo',
			'setInterval',
			'setTimeout',
			'status',
			'stop',
			'taint',
			'toString',
			'unescape',
			'untaint',
			'unwatch',
			'valueOf',
			'watch'
		]
	}
	,'OPERATORS' :[
		'+', '-', '/', '*', '=', '<', '>', '%', '!'
	]
	,'DELIMITERS' :[
		'(', ')', '[', ']', '{', '}'
	]
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #6381F8;'
		,'KEYWORDS' : {
			'statements' : 'color: #60CA00;'
			,'keywords' : 'color: #48BDDF;'
			,'functions' : 'color: #2B60FF;'
		}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #0038E1;'

	}
	,'AUTO_COMPLETION' :  {
		"default": {	// the name of this definition group. It's posisble to have different rules inside the same definition file
			"REGEXP": { "before_word": "[^a-zA-Z0-9_]|^"	// \\s|\\.|
						,"possible_words_letters": "[a-zA-Z0-9_]+"
						,"letter_after_word_must_match": "[^a-zA-Z0-9_]|$"
						,"prefix_separator": "\\."
					}
			,"CASE_SENSITIVE": true
			,"MAX_TEXT_LENGTH": 100		// the maximum length of the text being analyzed before the cursor position
			,"KEYWORDS": {
				'': [	// the prefix of thoses items
						/**
						 * 0 : the keyword the user is typing
						 * 1 : (optionnal) the string inserted in code ("{@}" being the new position of the cursor, "§" beeing the equivalent to the value the typed string indicated if the previous )
						 * 		If empty the keyword will be displayed
						 * 2 : (optionnal) the text that appear in the suggestion box (if empty, the string to insert will be displayed)
						 */
						 ['Array', '§()', '']
						,['alert', '§({@})', 'alert(String message)']
						,['document']
						,['window']
 					]
				,'window' : [
						 ['location']
						,['document']
						,['scrollTo', 'scrollTo({@})', 'scrollTo(Int x,Int y)']
 					]
				,'location' : [
						 ['href']
					]
			}
		}
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'js.js'] = true;


// patch applied: https://sourceforge.net/tracker/?func=detail&aid=3107951&group_id=164008&atid=829999

editAreaLoader.load_syntax["js"] = {
	'DISPLAY_NAME' : 'Javascript'
	,'COMMENT_SINGLE' : {1 : '//'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'as',
			'break',
			'case',
			'catch',
			'continue',
			'decodeURI',
			'delete',
			'do',

			'else',
			'encodeURI',
			'eval',
			'finally',
			'for',
			'if',
			'in',
			'is',
			'item',

			'instanceof',
			'return',
			'switch',
			'this',
			'throw',
			'try',
			'typeof',
			'void',

			'while',
			'write',
			'with'
		]
 		,'keywords' : [
			'abstract',
			'Anchor',
			'Area',
			'Array',
			'assign',
			'Boolean',
			'Button',
			'byte',
			'callee',
			'char',
			'Checkbox',
			'class',
			'closed',
			'const',
			'constructor',
			'Date',
			'debugger',
			'default',
			'defaultStatus',
			'document',
			'double',
			'Element',
			'export',
			'extends',
			'false',
			'FileUpload',
			'final',
			'float',
			'Form',
			'Frame',
			'frames',
			'function',
			'getClass',
			'goto',
			'Hidden',
			'History',
			'Image',
			'implements',
			'import',
			'Infinity',
			'innerHeight',
			'innerWidth',
			'java',
			'JavaArray',
			'JavaClass',
			'JavaObject',
			'JavaPackage',
			'length',
			'Link',
			'location',
			'locationbar',
			'long',
			'Math',
			'menubar',
			'MimeType',
			'namespace',
			'NaN',
			'native',
			'navigator',
			'netscape',
			'new',
			'null',
			'Number',
			'Object',
			'onBlur',
			'onError',
			'onFocus',
			'onLoad',
			'onUnload',
			'opener',
			'Option',
			'outerHeight',
			'outerWidth',
			'package',
			'Packages',
			'pageXoffset',
			'pageYoffset',
			'parent',
			'Password',
			'personalbar',
			'Plugin',
			'private',
			'protected',
			'prototype',
			'public',
			'Radio',
			'ref',
			'RegExp',
			'Reset',
			'scrollbars',
			'Select',
			'self',
			'short',
			'statusbar',
			'String',
			'Submit',
			'sun',
			'super',
			'synchronized',
			'Text',
			'Textarea',
			'throws',
			'toolbar',
			'top',
			'transient',
			'true',
			'use',
			'var',
			'window'
		]
		,'functions' : [
 			// common functions for Window object
			'alert',
			'arguments',
			'back',
			'blur',
			'caller',
			'captureEvents',
			'clearInterval',
			'clearTimeout',
			'close',
			'confirm',
			'escape',
			'eval',
			'find',
			'focus',
			'forward',
			'handleEvent',
			'home',
			'isFinite',
			'isNan',
			'moveBy',
			'moveTo',
			'name',
			'navigate',
			'onblur',
			'onerror',
			'onfocus',
			'onload',
			'onmove',
			'onresize',
			'onunload',
			'open',
			'parseFloat',
			'parseInt',
			'print',
			'prompt',
			'releaseEvents',
			'resizeBy',
			'resizeTo',
			'routeEvent',
			'scroll',
			'scrollBy',
			'scrollTo',
			'setInterval',
			'setTimeout',
			'status',
			'stop',
			'taint',
			'toString',
			'unescape',
			'untaint',
			'unwatch',
			'valueOf',
			'watch'
		]
	}
	,'OPERATORS' :[
		'+', '-', '/', '*', '=', '<', '>', '%', '!'
	]
	,'DELIMITERS' :[
		'(', ')', '[', ']', '{', '}'
	]
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #6381F8;'
		,'KEYWORDS' : {
			'statements' : 'color: #60CA00;'
			,'keywords' : 'color: #48BDDF;'
			,'functions' : 'color: #2B60FF;'
		}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #0038E1;'

	}
	,'AUTO_COMPLETION' :  {
		"default": {	// the name of this definition group. It's posisble to have different rules inside the same definition file
			"REGEXP": { "before_word": "[^a-zA-Z0-9_]|^"	// \\s|\\.|
						,"possible_words_letters": "[a-zA-Z0-9_]+"
						,"letter_after_word_must_match": "[^a-zA-Z0-9_]|$"
						,"prefix_separator": "\\."
					}
			,"CASE_SENSITIVE": true
			,"MAX_TEXT_LENGTH": 100		// the maximum length of the text being analyzed before the cursor position
			,"KEYWORDS": {
				'': [	// the prefix of thoses items
						/**
						 * 0 : the keyword the user is typing
						 * 1 : (optionnal) the string inserted in code ("{@}" being the new position of the cursor, "§" beeing the equivalent to the value the typed string indicated if the previous )
						 * 		If empty the keyword will be displayed
						 * 2 : (optionnal) the text that appear in the suggestion box (if empty, the string to insert will be displayed)
						 */
						 ['Array', '§()', '']
						,['alert', '§({@})', 'alert(String message)']
						,['document']
						,['window']
 					]
				,'window' : [
						 ['location']
						,['document']
						,['scrollTo', 'scrollTo({@})', 'scrollTo(Int x,Int y)']
 					]
				,'location' : [
						 ['href']
					]
			}
		}
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'js.js.bak'] = true;


// patch applied: https://sourceforge.net/tracker/?func=detail&aid=3107951&group_id=164008&atid=829999

editAreaLoader.load_syntax["js"] = {
	'DISPLAY_NAME' : 'Javascript'
	,'COMMENT_SINGLE' : {1 : '//'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'as',
			'break',
			'case',
			'catch',
			'continue',
			'decodeURI',
			'delete',
			'do',

			'else',
			'encodeURI',
			'eval',
			'finally',
			'for',
			'if',
			'in',
			'is',
			'item',

			'instanceof',
			'return',
			'switch',
			'this',
			'throw',
			'try',
			'typeof',
			'void',

			'while',
			'write',
			'with'
		]
 		,'keywords' : [
			'abstract',
			'Anchor',
			'Area',
			'Array',
			'assign',
			'Boolean',
			'Button',
			'byte',
			'callee',
			'char',
			'Checkbox',
			'class',
			'closed',
			'const',
			'constructor',
			'Date',
			'debugger',
			'default',
			'defaultStatus',
			'document',
			'double',
			'Element',
			'export',
			'extends',
			'false',
			'FileUpload',
			'final',
			'float',
			'Form',
			'Frame',
			'frames',
			'function',
			'getClass',
			'goto',
			'Hidden',
			'History',
			'Image',
			'implements',
			'import',
			'Infinity',
			'innerHeight',
			'innerWidth',
			'java',
			'JavaArray',
			'JavaClass',
			'JavaObject',
			'JavaPackage',
			'length',
			'Link',
			'location',
			'locationbar',
			'long',
			'Math',
			'menubar',
			'MimeType',
			'namespace',
			'NaN',
			'native',
			'navigator',
			'Navigator',
			'netscape',
			'new',
			'null',
			'Number',
			'Object',
			'onBlur',
			'onError',
			'onFocus',
			'onLoad',
			'onUnload',
			'opener',
			'Option',
			'outerHeight',
			'outerWidth',
			'package',
			'Packages',
			'pageXoffset',
			'pageYoffset',
			'parent',
			'Password',
			'personalbar',
			'Plugin',
			'private',
			'protected',
			'prototype',
			'public',
			'Radio',
			'ref',
			'RegExp',
			'Reset',
			'scrollbars',
			'Select',
			'self',
			'short',
			'statusbar',
			'String',
			'Submit',
			'sun',
			'super',
			'synchronized',
			'Text',
			'Textarea',
			'throws',
			'toolbar',
			'top',
			'transient',
			'true',
			'use',
			'var',
			'window',
		]
		,'functions' : [
 			// common functions for Window object
			'alert',
			'arguments',
			'back',
			'blur',
			'caller',
			'captureEvents',

			'clearInterval',
			'clearTimeout',
			'close',
			'confirm',
			'escape',

			'eval',
			'find',
			'focus',
			'forward',
			'handleEvent',
			'home',

			'isFinite',
			'isNan',
			'moveBy',
			'moveTo',
			'name',
			'navigate',

			'onblur',
			'onerror',
			'onfocus',
			'onload',
			'onmove',
			'onresize',

			'onunload',
			'open',
			'parseFloat',
			'parseInt',
			'print',
			'prompt',

			'releaseEvents',
			'resizeBy',
			'resizeTo',
			'routeEvent',
			'scroll',

			'scrollBy',
			'scrollTo',
			'setInterval',
			'setTimeout',
			'status',

			'stop',
			'taint',
			'toString',
			'unescape',
			'untaint',
			'unwatch',

			'valueOf',
			'watch'
		]
	}
	,'OPERATORS' :[
		'+', '-', '/', '*', '=', '<', '>', '%', '!'
	]
	,'DELIMITERS' :[
		'(', ')', '[', ']', '{', '}'
	]
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #6381F8;'
		,'KEYWORDS' : {
			'statements' : 'color: #60CA00;'
			,'keywords' : 'color: #48BDDF;'
			,'functions' : 'color: #2B60FF;'
		}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #0038E1;'

	}
	,'AUTO_COMPLETION' :  {
		"default": {	// the name of this definition group. It's posisble to have different rules inside the same definition file
			"REGEXP": { "before_word": "[^a-zA-Z0-9_]|^"	// \\s|\\.|
						,"possible_words_letters": "[a-zA-Z0-9_]+"
						,"letter_after_word_must_match": "[^a-zA-Z0-9_]|$"
						,"prefix_separator": "\\."
					}
			,"CASE_SENSITIVE": true
			,"MAX_TEXT_LENGTH": 100		// the maximum length of the text being analyzed before the cursor position
			,"KEYWORDS": {
				'': [	// the prefix of thoses items
						/**
						 * 0 : the keyword the user is typing
						 * 1 : (optionnal) the string inserted in code ("{@}" being the new position of the cursor, "§" beeing the equivalent to the value the typed string indicated if the previous )
						 * 		If empty the keyword will be displayed
						 * 2 : (optionnal) the text that appear in the suggestion box (if empty, the string to insert will be displayed)
						 */
 						 ['Array', '§()', '']
						,['alert', '§({@})', 'alert(String message)']
						,['document']
						,['window']
 					]
				,'window' : [
						 ['location']
						,['document']
						,['scrollTo', 'scrollTo({@})', 'scrollTo(Int x,Int y)']
 					]
				,'location' : [
						 ['href']
					]
			}
		}
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'js2.js.bak'] = true;


editAreaLoader.load_syntax["php"] = {
	'DISPLAY_NAME' : 'Php'
	,'COMMENT_SINGLE' : {1 : '//', 2 : '#'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'include', 'require', 'include_once', 'require_once',
			'for', 'foreach', 'as', 'if', 'elseif', 'else', 'while', 'do', 'endwhile',
            'endif', 'switch', 'case', 'endswitch',
			'return', 'break', 'continue'
		]
		,'reserved' : [
			'_GET', '_POST', '_SESSION', '_SERVER', '_FILES', '_ENV', '_COOKIE', '_REQUEST',
			'null', '__LINE__', '__FILE__',
			'false', '&lt;?php', '?&gt;', '&lt;?',
			'&lt;script language', '&lt;/script&gt;',
			'true', 'var', 'default',
			'function', 'class', 'new', '&amp;new', 'this',
			'__FUNCTION__', '__CLASS__', '__METHOD__', 'PHP_VERSION',
			'PHP_OS', 'DEFAULT_INCLUDE_PATH', 'PEAR_INSTALL_DIR', 'PEAR_EXTENSION_DIR',
			'PHP_EXTENSION_DIR', 'PHP_BINDIR', 'PHP_LIBDIR', 'PHP_DATADIR', 'PHP_SYSCONFDIR',
			'PHP_LOCALSTATEDIR', 'PHP_CONFIG_FILE_PATH', 'PHP_OUTPUT_HANDLER_START', 'PHP_OUTPUT_HANDLER_CONT',
			'PHP_OUTPUT_HANDLER_END', 'E_ERROR', 'E_WARNING', 'E_PARSE', 'E_NOTICE',
			'E_CORE_ERROR', 'E_CORE_WARNING', 'E_COMPILE_ERROR', 'E_COMPILE_WARNING', 'E_USER_ERROR',
			'E_USER_WARNING', 'E_USER_NOTICE', 'E_ALL'
			
		]
		,'functions' : [
			'func_num_args', 'func_get_arg', 'func_get_args', 'strlen', 'strcmp', 'strncmp', 'strcasecmp', 'strncasecmp', 'each', 'error_reporting', 'define', 'defined',
			'trigger_error', 'user_error', 'set_error_handler', 'restore_error_handler', 'get_declared_classes', 'get_loaded_extensions',
			'extension_loaded', 'get_extension_funcs', 'debug_backtrace',
			'constant', 'bin2hex', 'sleep', 'usleep', 'time', 'mktime', 'gmmktime', 'strftime', 'gmstrftime', 'strtotime', 'date', 'gmdate', 'getdate', 'localtime', 'checkdate', 'flush', 'wordwrap', 'htmlspecialchars', 'htmlentities', 'html_entity_decode', 'md5', 'md5_file', 'crc32', 'getimagesize', 'image_type_to_mime_type', 'phpinfo', 'phpversion', 'phpcredits', 'strnatcmp', 'strnatcasecmp', 'substr_count', 'strspn', 'strcspn', 'strtok', 'strtoupper', 'strtolower', 'strpos', 'strrpos', 'strrev', 'hebrev', 'hebrevc', 'nl2br', 'basename', 'dirname', 'pathinfo', 'stripslashes', 'stripcslashes', 'strstr', 'stristr', 'strrchr', 'str_shuffle', 'str_word_count', 'strcoll', 'substr', 'substr_replace', 'quotemeta', 'ucfirst', 'ucwords', 'strtr', 'addslashes', 'addcslashes', 'rtrim', 'str_replace', 'str_repeat', 'count_chars', 'chunk_split', 'trim', 'ltrim', 'strip_tags', 'similar_text', 'explode', 'implode', 'setlocale', 'localeconv',
			'parse_str', 'str_pad', 'chop', 'strchr', 'sprintf', 'printf', 'vprintf', 'vsprintf', 'sscanf', 'fscanf', 'parse_url', 'urlencode', 'urldecode', 'rawurlencode', 'rawurldecode', 'readlink', 'linkinfo', 'link', 'unlink', 'exec', 'system', 'escapeshellcmd', 'escapeshellarg', 'passthru', 'shell_exec', 'proc_open', 'proc_close', 'rand', 'srand', 'getrandmax', 'mt_rand', 'mt_srand', 'mt_getrandmax', 'base64_decode', 'base64_encode', 'abs', 'ceil', 'floor', 'round', 'is_finite', 'is_nan', 'is_infinite', 'bindec', 'hexdec', 'octdec', 'decbin', 'decoct', 'dechex', 'base_convert', 'number_format', 'fmod', 'ip2long', 'long2ip', 'getenv', 'putenv', 'getopt', 'microtime', 'gettimeofday', 'getrusage', 'uniqid', 'quoted_printable_decode', 'set_time_limit', 'get_cfg_var', 'magic_quotes_runtime', 'set_magic_quotes_runtime', 'get_magic_quotes_gpc', 'get_magic_quotes_runtime',
			'import_request_variables', 'error_log', 'serialize', 'unserialize', 'memory_get_usage', 'var_dump', 'var_export', 'debug_zval_dump', 'print_r','highlight_file', 'show_source', 'highlight_string', 'ini_get', 'ini_get_all', 'ini_set', 'ini_alter', 'ini_restore', 'get_include_path', 'set_include_path', 'restore_include_path', 'setcookie', 'header', 'headers_sent', 'connection_aborted', 'connection_status', 'ignore_user_abort', 'parse_ini_file', 'is_uploaded_file', 'move_uploaded_file', 'intval', 'floatval', 'doubleval', 'strval', 'gettype', 'settype', 'is_null', 'is_resource', 'is_bool', 'is_long', 'is_float', 'is_int', 'is_integer', 'is_double', 'is_real', 'is_numeric', 'is_string', 'is_array', 'is_object', 'is_scalar',
			'ereg', 'ereg_replace', 'eregi', 'eregi_replace', 'split', 'spliti', 'join', 'sql_regcase', 'dl', 'pclose', 'popen', 'readfile', 'rewind', 'rmdir', 'umask', 'fclose', 'feof', 'fgetc', 'fgets', 'fgetss', 'fread', 'fopen', 'fpassthru', 'ftruncate', 'fstat', 'fseek', 'ftell', 'fflush', 'fwrite', 'fputs', 'mkdir', 'rename', 'copy', 'tempnam', 'tmpfile', 'file', 'file_get_contents', 'stream_select', 'stream_context_create', 'stream_context_set_params', 'stream_context_set_option', 'stream_context_get_options', 'stream_filter_prepend', 'stream_filter_append', 'fgetcsv', 'flock', 'get_meta_tags', 'stream_set_write_buffer', 'set_file_buffer', 'set_socket_blocking', 'stream_set_blocking', 'socket_set_blocking', 'stream_get_meta_data', 'stream_register_wrapper', 'stream_wrapper_register', 'stream_set_timeout', 'socket_set_timeout', 'socket_get_status', 'realpath', 'fnmatch', 'fsockopen', 'pfsockopen', 'pack', 'unpack', 'get_browser', 'crypt', 'opendir', 'closedir', 'chdir', 'getcwd', 'rewinddir', 'readdir', 'dir', 'glob', 'fileatime', 'filectime', 'filegroup', 'fileinode', 'filemtime', 'fileowner', 'fileperms', 'filesize', 'filetype', 'file_exists', 'is_writable', 'is_writeable', 'is_readable', 'is_executable', 'is_file', 'is_dir', 'is_link', 'stat', 'lstat', 'chown',
			'touch', 'clearstatcache', 'mail', 'ob_start', 'ob_flush', 'ob_clean', 'ob_end_flush', 'ob_end_clean', 'ob_get_flush', 'ob_get_clean', 'ob_get_length', 'ob_get_level', 'ob_get_status', 'ob_get_contents', 'ob_implicit_flush', 'ob_list_handlers', 'ksort', 'krsort', 'natsort', 'natcasesort', 'asort', 'arsort', 'sort', 'rsort', 'usort', 'uasort', 'uksort', 'shuffle', 'array_walk', 'count', 'end', 'prev', 'next', 'reset', 'current', 'key', 'min', 'max', 'in_array', 'array_search', 'extract', 'compact', 'array_fill', 'range', 'array_multisort', 'array_push', 'array_pop', 'array_shift', 'array_unshift', 'array_splice', 'array_slice', 'array_merge', 'array_merge_recursive', 'array_keys', 'array_values', 'array_count_values', 'array_reverse', 'array_reduce', 'array_pad', 'array_flip', 'array_change_key_case', 'array_rand', 'array_unique', 'array_intersect', 'array_intersect_assoc', 'array_diff', 'array_diff_assoc', 'array_sum', 'array_filter', 'array_map', 'array_chunk', 'array_key_exists', 'pos', 'sizeof', 'key_exists', 'assert', 'assert_options', 'version_compare', 'ftok', 'str_rot13', 'aggregate',
			'session_name', 'session_module_name', 'session_save_path', 'session_id', 'session_regenerate_id', 'session_decode', 'session_register', 'session_unregister', 'session_is_registered', 'session_encode',
			'session_start', 'session_destroy', 'session_unset', 'session_set_save_handler', 'session_cache_limiter', 'session_cache_expire', 'session_set_cookie_params', 'session_get_cookie_params', 'session_write_close', 'preg_match', 'preg_match_all', 'preg_replace', 'preg_replace_callback', 'preg_split', 'preg_quote', 'preg_grep', 'overload', 'ctype_alnum', 'ctype_alpha', 'ctype_cntrl', 'ctype_digit', 'ctype_lower', 'ctype_graph', 'ctype_print', 'ctype_punct', 'ctype_space', 'ctype_upper', 'ctype_xdigit', 'virtual', 'apache_request_headers', 'apache_note', 'apache_lookup_uri', 'apache_child_terminate', 'apache_setenv', 'apache_response_headers', 'apache_get_version', 'getallheaders', 'mysql_connect', 'mysql_pconnect', 'mysql_close', 'mysql_select_db', 'mysql_create_db', 'mysql_drop_db', 'mysql_query', 'mysql_unbuffered_query', 'mysql_db_query', 'mysql_list_dbs', 'mysql_list_tables', 'mysql_list_fields', 'mysql_list_processes', 'mysql_error', 'mysql_errno', 'mysql_affected_rows', 'mysql_insert_id', 'mysql_result', 'mysql_num_rows', 'mysql_num_fields', 'mysql_fetch_row', 'mysql_fetch_array', 'mysql_fetch_assoc', 'mysql_fetch_object', 'mysql_data_seek', 'mysql_fetch_lengths', 'mysql_fetch_field', 'mysql_field_seek', 'mysql_free_result', 'mysql_field_name', 'mysql_field_table', 'mysql_field_len', 'mysql_field_type', 'mysql_field_flags', 'mysql_escape_string', 'mysql_real_escape_string', 'mysql_stat',
			'mysql_thread_id', 'mysql_client_encoding', 'mysql_get_client_info', 'mysql_get_host_info', 'mysql_get_proto_info', 'mysql_get_server_info', 'mysql_info', 'mysql', 'mysql_fieldname', 'mysql_fieldtable', 'mysql_fieldlen', 'mysql_fieldtype', 'mysql_fieldflags', 'mysql_selectdb', 'mysql_createdb', 'mysql_dropdb', 'mysql_freeresult', 'mysql_numfields', 'mysql_numrows', 'mysql_listdbs', 'mysql_listtables', 'mysql_listfields', 'mysql_db_name', 'mysql_dbname', 'mysql_tablename', 'mysql_table_name', 'pg_connect', 'pg_pconnect', 'pg_close', 'pg_connection_status', 'pg_connection_busy', 'pg_connection_reset', 'pg_host', 'pg_dbname', 'pg_port', 'pg_tty', 'pg_options', 'pg_ping', 'pg_query', 'pg_send_query', 'pg_cancel_query', 'pg_fetch_result', 'pg_fetch_row', 'pg_fetch_assoc', 'pg_fetch_array', 'pg_fetch_object', 'pg_fetch_all', 'pg_affected_rows', 'pg_get_result', 'pg_result_seek', 'pg_result_status', 'pg_free_result', 'pg_last_oid', 'pg_num_rows', 'pg_num_fields', 'pg_field_name', 'pg_field_num', 'pg_field_size', 'pg_field_type', 'pg_field_prtlen', 'pg_field_is_null', 'pg_get_notify', 'pg_get_pid', 'pg_result_error', 'pg_last_error', 'pg_last_notice', 'pg_put_line', 'pg_end_copy', 'pg_copy_to', 'pg_copy_from',
			'pg_trace', 'pg_untrace', 'pg_lo_create', 'pg_lo_unlink', 'pg_lo_open', 'pg_lo_close', 'pg_lo_read', 'pg_lo_write', 'pg_lo_read_all', 'pg_lo_import', 'pg_lo_export', 'pg_lo_seek', 'pg_lo_tell', 'pg_escape_string', 'pg_escape_bytea', 'pg_unescape_bytea', 'pg_client_encoding', 'pg_set_client_encoding', 'pg_meta_data', 'pg_convert', 'pg_insert', 'pg_update', 'pg_delete', 'pg_select', 'pg_exec', 'pg_getlastoid', 'pg_cmdtuples', 'pg_errormessage', 'pg_numrows', 'pg_numfields', 'pg_fieldname', 'pg_fieldsize', 'pg_fieldtype', 'pg_fieldnum', 'pg_fieldprtlen', 'pg_fieldisnull', 'pg_freeresult', 'pg_result', 'pg_loreadall', 'pg_locreate', 'pg_lounlink', 'pg_loopen', 'pg_loclose', 'pg_loread', 'pg_lowrite', 'pg_loimport', 'pg_loexport',
			'echo', 'print', 'global', 'static', 'exit', 'array', 'empty', 'eval', 'isset', 'unset', 'die'

		]
	}
	,'OPERATORS' :[
		'+', '-', '/', '*', '=', '<', '>', '%', '!', '&&', '||'
	]
	,'DELIMITERS' :[
		'(', ')', '[', ']', '{', '}'
	]
	,'REGEXPS' : {
		// highlight all variables ($...)
		'variables' : {
			'search' : '()(\\$\\w+)()'
			,'class' : 'variables'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
	}
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #879EFA;'
		,'KEYWORDS' : {
			'reserved' : 'color: #48BDDF;'
			,'functions' : 'color: #0040FD;'
			,'statements' : 'color: #60CA00;'
			}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #2B60FF;'
		,'REGEXPS' : {
			'variables' : 'color: #E0BD54;'
		}		
	}
	,'AUTO_COMPLETION' :  {
		"default": {	// the name of this definition group. It's posisble to have different rules inside the same definition file
			"REGEXP": { "before_word": "[^a-zA-Z0-9_]|^"	// \\s|\\.|
						,"possible_words_letters": "[a-zA-Z0-9_\$]+"
						,"letter_after_word_must_match": "[^a-zA-Z0-9_]|$"
						,"prefix_separator": "\\-\\>|\\:\\:"
					}
			,"CASE_SENSITIVE": true
			,"MAX_TEXT_LENGTH": 100		// the maximum length of the text being analyzed before the cursor position
			,"KEYWORDS": {
					'': [	// the prefix of thoses items
						/**
						 * 0 : the keyword the user is typing
						 * 1 : (optionnal) the string inserted in code ("{@}" being the new position of the cursor, "§" beeing the equivalent to the value the typed string indicated if the previous )
						 * 		If empty the keyword will be displayed
						 * 2 : (optionnal) the text that appear in the suggestion box (if empty, the string to insert will be displayed)
						 */
						 ['$_POST']
			    		,['$_GET']
			    		,['$_SESSION']
			    		,['$_SERVER']
			    		,['$_FILES']
			    		,['$_ENV']
			    		,['$_COOKIE']
			    		,['$_REQUEST']
			    		// magic methods
			    		,['__construct', '§( {@} )']
			    		,['__destruct', '§( {@} )']
			    		,['__sleep', '§( {@} )']
			    		,['__wakeup', '§( {@} )']
			    		,['__toString', '§( {@} )']
			    		// include
			    		,['include', '§ "{@}";']
			    		,['include_once', '§ "{@}";']
			    		,['require', '§ "{@}";']
			    		,['require_once', '§ "{@}";']
			    		// statements
			    		,['for', '§( {@} )']
			    		,['foreach', '§( {@} )']
			    		,['if', '§( {@} )']
			    		,['elseif', '§( {@} )']
			    		,['while', '§( {@} )']
			    		,['switch', '§( {@} )']
			    		,['break']
			    		,['case']
			    		,['continue']
			    		,['do']
			    		,['else']
			    		,['endif']
			    		,['endswitch']
			    		,['endwhile']
			    		,['return']
			    		// function
			    		,['unset', '§( {@} )']
					]
				}
			}
		,"live": {	
			
			// class NAME: /class\W+([a-z]+)\W+/gi
			// method: /^(public|private|protected)?\s*function\s+([a-z][a-z0-9\_]*)\s*(\([^\{]*\))/gmi
			// static: /^(public|private|protected)?\s+static\s+(public|private|protected)?\s*function\s+([a-z][a-z0-9\_]*)\s*(\([^\{]*\))/gmi 
			// attributes: /(\$this\-\>|(?:var|public|protected|private)\W+\$)([a-z0-9\_]+)(?!\()\b/gi 
			// 		v1 : /(\$this\-\>|var\W+|public\W+|protected\W+|private\W+)([a-z0-9\_]+)\W*(=|;)/gi 
			// var type: /(\$(this\-\>)?[a-z0-9\_]+)\s*\=\s*new\s+([a-z0-9\_])+/gi 
			
			
			"REGEXP": { "before_word": "[^a-zA-Z0-9_]|^"	// \\s|\\.|
						,"possible_words_letters": "[a-zA-Z0-9_\$]+"
						,"letter_after_word_must_match": "[^a-zA-Z0-9_]|$"
						,"prefix_separator": "\\-\\>"
					}
			,"CASE_SENSITIVE": true
			,"MAX_TEXT_LENGTH": 100		// the maximum length of the text being analyzed before the cursor position
			,"KEYWORDS": {
					'$this': [	// the prefix of thoses items
						['test']
					]
				}
			}
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'php.js'] = true;


editAreaLoader.load_syntax["robotstxt"] = {
	'DISPLAY_NAME' : 'Robots txt',
	'COMMENT_SINGLE' : {1 : '#'},
	'COMMENT_MULTI' : {},
	'QUOTEMARKS' : [],
	'KEYWORD_CASE_SENSITIVE' : false,
	'KEYWORDS' : {
		'attributes' : ['User-agent', 'Disallow', 'Allow', 'Crawl-delay'],
		'values' : ['*'],
		'specials' : ['*']
	},
	'OPERATORS' :[':'],
	'DELIMITERS' :[],
	'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;',
		'QUOTESMARKS': 'color: #6381F8;',
		'KEYWORDS' : {
			'attributes' : 'color: #48BDDF;',
			'values' : 'color: #2B60FF;',
			'specials' : 'color: #FF0000;'
			},
	'OPERATORS' : 'color: #FF00FF;',
	'DELIMITERS' : 'color: #60CA00;'			
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'robotstxt.js'] = true;


editAreaLoader.load_syntax["sql"] = {
	'DISPLAY_NAME' : 'SQL'
	,'COMMENT_SINGLE' : {1 : '--'}
	,'COMMENT_MULTI' : {'/*' : '*/'}
	,'QUOTEMARKS' : {1: "'", 2: '"', 3: '`'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
		'statements' : [
			'select', 'SELECT', 'where', 'order', 'by',
			'insert', 'from', 'update', 'grant', 'left join', 'right join', 
            'union', 'group', 'having', 'limit', 'alter', 'LIKE','IN','CASE'
		]
		,'reserved' : [
			'null', 'enum', 'int', 'boolean', 'add', 'varchar'
			
		]
		,'functions' : [
   'ABS','ACOS','ADDDATE','ADDTIME','AES_DECRYPT','AES_ENCRYPT','ASCII','ASIN','ATAN2 ATAN','ATAN','AVG','BENCHMARK','DISTINCT','BIN','BIT_AND','BIT_COUNT','BIT_LENGTH','BIT_OR','BIT_XOR','CAST','CEILING CEIL','CHAR_LENGTH','CHAR',
'CHARACTER_LENGTH','CHARSET','COALESCE','COERCIBILITY','COLLATION','COMPRESS','CONCAT_WS','CONCAT','CONNECTION_ID','CONV','CONVERT_TZ','COS','COT','COUNT','CRC32','CURDATE','CURRENT_DATE','CURRENT_TIME','CURRENT_TIMESTAMP','CURRENT_USER','CURTIME','DATABASE','DATE_ADD','DATE_FORMAT','DATE_SUB','DATE','DATEDIFF','DAY','DAYNAME','DAYOFMONTH',
'DAYOFWEEK','DAYOFYEAR','DECODE','DEFAULT','DEGREES','DES_DECRYPT','DES_ENCRYPT','ELT','ENCODE','ENCRYPT','EXP','EXPORT_SET','EXTRACT','FIELD','FIND_IN_SET','FLOOR','FORMAT','FOUND_ROWS','FROM_DAYS','FROM_UNIXTIME','GET_FORMAT','GET_LOCK','GREATEST','GROUP_CONCAT','HEX','HOUR','IF','IFNULL','INET_ATON','INET_NTOA',
'INSERT','INSTR','INTERVAL','IS_FREE_LOCK','IS_USED_LOCK','ISNULL','LAST_DAY','LAST_INSERT_ID','LCASE','LEAST','LEFT','LENGTH','LN','LOAD_FILE','LOCALTIME','LOCALTIMESTAMP','LOCATE','LOG10','LOG2','LOG','LOWER','LPAD','LTRIM','MAKE_SET','MAKEDATE','MAKETIME','MASTER_POS_WAIT','MAX','MD5','MICROSECOND',
'MID','MIN','MINUTE','MOD','MONTH','MONTHNAME','NOW','NULLIF','OCT','OCTET_LENGTH','OLD_PASSWORD','ORD','PASSWORD','PERIOD_ADD','PERIOD_DIFF','PI','POSITION','POW','POWER','PROCEDURE ANALYSE','QUARTER','QUOTE','RADIANS','RAND','RELEASE_LOCK','REPEAT','REPLACE','REVERSE','RIGHT','ROUND',
'RPAD','RTRIM','SEC_TO_TIME','SECOND','SESSION_USER','SHA1','SHA','SIGN','SIN','SOUNDEX','SOUNDS LIKE','SPACE','SQRT','STD','STDDEV','STR_TO_DATE','STRCMP','SUBDATE','SUBSTRING_INDEX','SUBSTRING','SUBSTR','SUBTIME','SUM','SYSDATE','SYSTEM_USER','TAN','TIME_FORMAT','TIME_TO_SEC','TIME','TIMEDIFF',
'TIMESTAMP','TO_DAYS','TRIM','TRUNCATE','UCASE','UNCOMPRESS','UNCOMPRESSED_LENGTH','UNHEX','UNIX_TIMESTAMP','UPPER','USER','UTC_DATE','UTC_TIME','UTC_TIMESTAMP','UUID','VALUES','VARIANCE','WEEK','WEEKDAY','WEEKOFYEAR','YEAR','YEARWEEK'
		]
	}
	,'OPERATORS' :[
     'AND','&&','BETWEEN','BINARY','&','|','^','/','DIV','<=>','=','>=','>','<<','>>','IS','NULL','<=','<','-','%','!=','<>','!','||','OR','+','REGEXP','RLIKE','XOR','~','*'
	]
	,'DELIMITERS' :[
		'(', ')', '[', ']', '{', '}'
	]
	,'REGEXPS' : {
		// highlight all variables (@...)
		'variables' : {
			'search' : '()(\\@\\w+)()'
			,'class' : 'variables'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
	}
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #879EFA;'
		,'KEYWORDS' : {
			'reserved' : 'color: #48BDDF;'
			,'functions' : 'color: #0040FD;'
			,'statements' : 'color: #60CA00;'
			}
		,'OPERATORS' : 'color: #FF00FF;'
		,'DELIMITERS' : 'color: #2B60FF;'
		,'REGEXPS' : {
			'variables' : 'color: #E0BD54;'
		}		
	}
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'sql.js'] = true;


/*
* last update: 2006-08-24
*/

editAreaLoader.load_syntax["xml"] = {
	'DISPLAY_NAME' : 'XML'
	,'COMMENT_SINGLE' : {}
	,'COMMENT_MULTI' : {'<!--' : '-->'}
	,'QUOTEMARKS' : {1: "'", 2: '"'}
	,'KEYWORD_CASE_SENSITIVE' : false
	,'KEYWORDS' : {
	}
	,'OPERATORS' :[
	]
	,'DELIMITERS' :[
	]
	,'REGEXPS' : {
		'xml' : {
			'search' : '()(<\\?[^>]*?\\?>)()'
			,'class' : 'xml'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
		,'cdatas' : {
			'search' : '()(<!\\[CDATA\\[.*?\\]\\]>)()'
			,'class' : 'cdata'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
		,'tags' : {
			'search' : '(<)(/?[a-z][^ \r\n\t>]*)([^>]*>)'
			,'class' : 'tags'
			,'modifiers' : 'gi'
			,'execute' : 'before' // before or after
		}
		,'attributes' : {
			'search' : '( |\n|\r|\t)([^ \r\n\t=]+)(=)'
			,'class' : 'attributes'
			,'modifiers' : 'g'
			,'execute' : 'before' // before or after
		}
	}
	,'STYLES' : {
		'COMMENTS': 'color: #AAAAAA;'
		,'QUOTESMARKS': 'color: #6381F8;'
		,'KEYWORDS' : {
			}
		,'OPERATORS' : 'color: #E775F0;'
		,'DELIMITERS' : ''
		,'REGEXPS' : {
			'attributes': 'color: #B1AC41;'
			,'tags': 'color: #E62253;'
			,'xml': 'color: #8DCFB5;'
			,'cdata': 'color: #50B020;'
		}	
	}		
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'reg_syntax/' + 'xml.js'] = true;


/*
 *	Bulgarian translation
 *	Author:		Valentin Hristov
 *	Company:	SOFTKIT Bulgarian
 *	Site:		http://www.softkit-bg.com
 */
editAreaLoader.lang["bg"]={
new_document: "нов документ",
search_button: "търсене и замяна",
search_command: "търси следващия / отвори прозорец с търсачка",
search: "търсене",
replace: "замяна",
replace_command: "замяна / отвори прозорец с търсачка",
find_next: "намери следващия",
replace_all: "замени всички",
reg_exp: "реголярни изрази",
match_case: "чуствителен към регистъра",
not_found: "няма резултат.",
occurrence_replaced: "замяната е осъществена.",
search_field_empty: "Полето за търсене е празно",
restart_search_at_begin: "До края на документа. Почни с началото.",
move_popup: "премести прозореца с търсачката",
font_size: "--Размер на шрифта--",
go_to_line: "премени към реда",
go_to_line_prompt: "премени към номера на реда:",
undo: "отмени",
redo: "върни",
change_smooth_selection: "включи/изключи някой от функциите за преглед (по красиво, но повече натоварва)",
highlight: "превключване на оцветяване на синтаксиса включена/изключена",
reset_highlight: "въстанови оцветяване на синтаксиса (ако не е синхронизиран с текста)",
word_wrap: "режим на пренасяне на дълги редове",
help: "за програмата",
save: "съхрани",
load: "зареди",
line_abbr: "Стр",
char_abbr: "Стлб",
position: "Позиция",
total: "Всичко",
close_popup: "затвори прозореца",
shortcuts: "Бързи клавиши",
add_tab: "добави табулация в текста",
remove_tab: "премахни табулацията в текста",
about_notice: "Внимание: използвайте функцията оцветяване на синтаксиса само за малки текстове",
toggle: "Превключи редактор",
accesskey: "Бърз клавиш",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Зареждане...",
fullscreen: "на цял екран",
syntax_selection: "--Синтаксис--",
close_tab: "Затвори файла"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'bg.js'] = true;


editAreaLoader.lang["cs"]={
new_document: "Nový dokument",
search_button: "Najdi a nahraď",
search_command: "Hledej další / otevři vyhledávací pole",
search: "Hledej",
replace: "Nahraď",
replace_command: "Nahraď / otevři vyhledávací pole",
find_next: "Najdi další",
replace_all: "Nahraď vše",
reg_exp: "platné výrazy",
match_case: "vyhodnocené výrazy",
not_found: "nenalezené.",
occurrence_replaced: "výskyty nahrazené.",
search_field_empty: "Pole vyhledávání je prázdné",
restart_search_at_begin: "Dosažen konec souboru, začínám od začátku.",
move_popup: "Přesuň vyhledávací okno",
font_size: "--Velikost textu--",
go_to_line: "Přejdi na řádek",
go_to_line_prompt: "Přejdi na řádek:",
undo: "krok zpět",
redo: "znovu",
change_smooth_selection: "Povolit nebo zakázat některé ze zobrazených funkcí (účelnější zobrazení požaduje větší zatížení procesoru)",
highlight: "Zvýrazňování syntaxe zap./vyp.",
reset_highlight: "Obnovit zvýraznění (v případě nesrovnalostí)",
word_wrap: "toggle word wrapping mode",
help: "O programu",
save: "Uložit",
load: "Otevřít",
line_abbr: "Ř.",
char_abbr: "S.",
position: "Pozice",
total: "Celkem",
close_popup: "Zavřít okno",
shortcuts: "Zkratky",
add_tab: "Přidat tabulování textu",
remove_tab: "Odtsranit tabulování textu",
about_notice: "Upozornění! Funkce zvýrazňování textu je k dispozici pouze pro malý text",
toggle: "Přepnout editor",
accesskey: "Přístupová klávesa",
tab: "Záložka",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Zpracovávám ...",
fullscreen: "Celá obrazovka",
syntax_selection: "--vyber zvýrazňovač--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'cs.js'] = true;


editAreaLoader.lang["de"]={
new_document: "Neues Dokument",
search_button: "Suchen und Ersetzen",
search_command: "Weitersuchen / &ouml;ffne Suchfeld",
search: "Suchen",
replace: "Ersetzen",
replace_command: "Ersetzen / &ouml;ffne Suchfeld",
find_next: "Weitersuchen",
replace_all: "Ersetze alle Treffer",
reg_exp: "regul&auml;re Ausdr&uuml;cke",
match_case: "passt auf den Begriff<br />",
not_found: "Nicht gefunden.",
occurrence_replaced: "Die Vorkommen wurden ersetzt.",
search_field_empty: "Leeres Suchfeld",
restart_search_at_begin: "Ende des zu durchsuchenden Bereiches erreicht. Es wird die Suche von Anfang an fortgesetzt.", //find a shorter translation
move_popup: "Suchfenster bewegen",
font_size: "--Schriftgr&ouml;&szlig;e--",
go_to_line: "Gehe zu Zeile",
go_to_line_prompt: "Gehe zu Zeilennummmer:",
undo: "R&uuml;ckg&auml;ngig",
redo: "Wiederherstellen",
change_smooth_selection: "Aktiviere/Deaktiviere einige Features (weniger Bildschirmnutzung aber mehr CPU-Belastung)",
highlight: "Syntax Highlighting an- und ausschalten",
reset_highlight: "Highlighting zur&uuml;cksetzen (falls mit Text nicht konform)",
word_wrap: "Toggle word wrapping mode",
help: "Info",
save: "Speichern",
load: "&Ouml;ffnen",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Position",
total: "Gesamt",
close_popup: "Popup schlie&szlig;en",
shortcuts: "Shortcuts",
add_tab: "Tab zum Text hinzuf&uuml;gen",
remove_tab: "Tab aus Text entfernen",
about_notice: "Bemerkung: Syntax Highlighting ist nur f&uuml;r kurze Texte",
toggle: "Editor an- und ausschalten",
accesskey: "Accesskey",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "In Bearbeitung...",
fullscreen: "Full-Screen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'de.js'] = true;


editAreaLoader.lang["dk"]={
new_document: "nyt tomt dokument",
search_button: "s&oslash;g og erstat",
search_command: "find n&aelig;ste / &aring;ben s&oslash;gefelt",
search: "s&oslash;g",
replace: "erstat",
replace_command: "erstat / &aring;ben s&oslash;gefelt",
find_next: "find n&aelig;ste",
replace_all: "erstat alle",
reg_exp: "regular expressions",
match_case: "forskel på store/sm&aring; bogstaver<br />",
not_found: "not found.",
occurrence_replaced: "occurences replaced.",
search_field_empty: "Search field empty",
restart_search_at_begin: "End of area reached. Restart at begin.",
move_popup: "flyt søgepopup",
font_size: "--Skriftstørrelse--",
go_to_line: "g&aring; til linie",
go_to_line_prompt: "gå til linienummer:",
undo: "fortryd",
redo: "gentag",
change_smooth_selection: "sl&aring; display funktioner til/fra (smartere display men mere CPU kr&aelig;vende)",
highlight: "sl&aring; syntax highlight til/fra",
reset_highlight: "nulstil highlight (hvis den er desynkroniseret fra teksten)",
word_wrap: "toggle word wrapping mode",
help: "om",
save: "gem",
load: "hent",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Position",
total: "Total",
close_popup: "luk popup",
shortcuts: "Genveje",
add_tab: "tilf&oslash;j tabulation til tekst",
remove_tab: "fjern tabulation fra tekst",
about_notice: "Husk: syntax highlight funktionen b&oslash;r kun bruge til sm&aring; tekster",
toggle: "Sl&aring; editor til / fra",
accesskey: "Accesskey",
tab: "Tab",
shift: "Skift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Processing...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'dk.js'] = true;


editAreaLoader.lang["en"]={
new_document: "new empty document",
search_button: "search and replace",
search_command: "search next / open search area",
search: "search",
replace: "replace",
replace_command: "replace / open search area",
find_next: "find next",
replace_all: "replace all",
reg_exp: "regular expressions",
match_case: "match case",
not_found: "not found.",
occurrence_replaced: "occurences replaced.",
search_field_empty: "Search field empty",
restart_search_at_begin: "End of area reached. Restart at begin.",
move_popup: "move search popup",
font_size: "--Font size--",
go_to_line: "go to line",
go_to_line_prompt: "go to line number:",
undo: "undo",
redo: "redo",
change_smooth_selection: "enable/disable some display features (smarter display but more CPU charge)",
highlight: "toggle syntax highlight on/off",
reset_highlight: "reset highlight (if desyncronized from text)",
word_wrap: "toggle word wrapping mode",
help: "about",
save: "save",
load: "load",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Position",
total: "Total",
close_popup: "close popup",
shortcuts: "Shortcuts",
add_tab: "add tabulation to text",
remove_tab: "remove tabulation to text",
about_notice: "Notice: syntax highlight function is only for small text",
toggle: "Toggle editor",
accesskey: "Accesskey",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Processing...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'en.js'] = true;


editAreaLoader.lang["eo"]={
new_document: "nova dokumento (vakigas la enhavon)",
search_button: "ser&#265;i / anstata&#365;igi",
search_command: "pluser&#265;i / malfermi la ser&#265;o-fenestron",
search: "ser&#265;i",
replace: "anstata&#365;igi",
replace_command: "anstata&#365;igi / malfermi la ser&#265;o-fenestron",
find_next: "ser&#265;i",
replace_all: "anstata&#365;igi &#265;ion",
reg_exp: "regula esprimo",
match_case: "respekti la usklecon",
not_found: "ne trovita.",
occurrence_replaced: "anstata&#365;igoj plenumitaj.",
search_field_empty: "La kampo estas malplena.",
restart_search_at_begin: "Fino de teksto &#285;isrirata, &#265;u da&#365;rigi el la komenco?",
move_popup: "movi la ser&#265;o-fenestron",
font_size: "--Tipara grando--",
go_to_line: "iri al la linio",
go_to_line_prompt: "iri al la linio numero:",
undo: "rezigni",
redo: "refari",
change_smooth_selection: "ebligi/malebligi la funkcioj de vidigo (pli bona vidigo, sed pli da &#349;ar&#285;o de la &#265;eforgano)",
highlight: "ebligi/malebligi la sintaksan kolorigon",
reset_highlight: "repravalorizi la sintaksan kolorigon (se malsinkronigon de la teksto)",
word_wrap: "toggle word wrapping mode",
help: "pri",
save: "registri",
load: "&#349;ar&#285;i",
line_abbr: "Ln",
char_abbr: "Sg",
position: "Pozicio",
total: "Sumo",
close_popup: "fermi la &#349;prucfenestron",
shortcuts: "Fulmoklavo",
add_tab: "aldoni tabon en la tekston",
remove_tab: "forigi tablon el la teksto",
about_notice: "Noto: la sintaksa kolorigo estas nur prikalkulita por mallongaj tekstoj.",
toggle: "baskuligi la redaktilon",
accesskey: "Fulmoklavo",
tab: "Tab",
shift: "Maj",
ctrl: "Ktrl",
esc: "Esk",
processing: "&#349;argante...",
fullscreen: "plenekrane",
syntax_selection: "--Sintakso--",
close_tab: "Fermi la dosieron"
};
editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'eo.js'] = true;


editAreaLoader.lang["es"]={
new_document: "nuevo documento vacío",
search_button: "buscar y reemplazar",
search_command: "buscar siguiente / abrir área de búsqueda",
search: "buscar",
replace: "reemplazar",
replace_command: "reemplazar / abrir área de búsqueda",
find_next: "encontrar siguiente",
replace_all: "reemplazar todos",
reg_exp: "expresiones regulares",
match_case: "coincidir capitalización",
not_found: "no encontrado.",
occurrence_replaced: "ocurrencias reemplazadas.",
search_field_empty: "Campo de búsqueda vacío",
restart_search_at_begin: "Se ha llegado al final del área. Se va a seguir desde el principio.",
move_popup: "mover la ventana de búsqueda",
font_size: "--Tamaño de la fuente--",
go_to_line: "ir a la línea",
go_to_line_prompt: "ir a la línea número:",
undo: "deshacer",
redo: "rehacer",
change_smooth_selection: "activar/desactivar algunas características de visualización (visualización más inteligente pero más carga de CPU)",
highlight: "intercambiar resaltado de sintaxis",
reset_highlight: "reinicializar resaltado (si no esta sincronizado con el texto)",
word_wrap: "toggle word wrapping mode",
help: "acerca",
save: "guardar",
load: "cargar",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Posición",
total: "Total",
close_popup: "recuadro de cierre",
shortcuts: "Atajos",
add_tab: "añadir tabulado al texto",
remove_tab: "borrar tabulado del texto",
about_notice: "Aviso: el resaltado de sintaxis sólo funciona para texto pequeño",
toggle: "Cambiar editor",
accesskey: "Tecla de acceso",
tab: "Tab",
shift: "Mayúsc",
ctrl: "Ctrl",
esc: "Esc",
processing: "Procesando...",
fullscreen: "pantalla completa",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'es.js'] = true;


editAreaLoader.lang["fi"]={
new_document: "uusi tyhjä dokumentti",
search_button: "etsi ja korvaa",
search_command: "etsi seuraava / avaa etsintävalikko",
search: "etsi",
replace: "korvaa",
replace_command: "korvaa / avaa etsintävalikko",
find_next: "etsi seuraava",
replace_all: "korvaa kaikki",
reg_exp: "säännölliset lausekkeet",
match_case: "täsmää kirjainkokoon",
not_found: "ei löytynyt.",
occurrence_replaced: "esiintymää korvattu.",
search_field_empty: "Haettava merkkijono on tyhjä",
restart_search_at_begin: "Alueen loppu saavutettiin. Aloitetaan alusta.",
move_popup: "siirrä etsintävalikkoa",
font_size: "--Fontin koko--",
go_to_line: "siirry riville",
go_to_line_prompt: "mene riville:",
undo: "peruuta",
redo: "tee uudelleen",
change_smooth_selection: "kytke/sammuta joitakin näyttötoimintoja (Älykkäämpi toiminta, mutta suurempi CPU kuormitus)",
highlight: "kytke syntaksikorostus päälle/pois",
reset_highlight: "resetoi syntaksikorostus (jos teksti ei ole synkassa korostuksen kanssa)",
word_wrap: "toggle word wrapping mode",
help: "tietoja",
save: "tallenna",
load: "lataa",
line_abbr: "Rv",
char_abbr: "Pos",
position: "Paikka",
total: "Yhteensä",
close_popup: "sulje valikko",
shortcuts: "Pikatoiminnot",
add_tab: "lisää sisennys tekstiin",
remove_tab: "poista sisennys tekstistä",
about_notice: "Huomautus: syntaksinkorostus toimii vain pienelle tekstille",
toggle: "Kytke editori",
accesskey: "Pikanäppäin",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Odota...",
fullscreen: "koko ruutu",
syntax_selection: "--Syntaksi--",
close_tab: "Sulje tiedosto"
};
editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'fi.js'] = true;


editAreaLoader.lang["fr"]={
new_document: "nouveau document (efface le contenu)",
search_button: "rechercher / remplacer",
search_command: "rechercher suivant / ouvrir la fen&ecirc;tre de recherche",
search: "rechercher",
replace: "remplacer",
replace_command: "remplacer / ouvrir la fen&ecirc;tre de recherche",
find_next: "rechercher",
replace_all: "tout remplacer",
reg_exp: "expr. r&eacute;guli&egrave;re",
match_case: "respecter la casse",
not_found: "pas trouv&eacute;.",
occurrence_replaced: "remplacements &eacute;ffectu&eacute;s.",
search_field_empty: "Le champ de recherche est vide.",
restart_search_at_begin: "Fin du texte atteint, poursuite au d&eacute;but.",
move_popup: "d&eacute;placer la fen&ecirc;tre de recherche",
font_size: "--Taille police--",
go_to_line: "aller &agrave; la ligne",
go_to_line_prompt: "aller a la ligne numero:",
undo: "annuler",
redo: "refaire",
change_smooth_selection: "activer/d&eacute;sactiver des fonctions d'affichage (meilleur affichage mais plus de charge processeur)",
highlight: "activer/d&eacute;sactiver la coloration syntaxique",
reset_highlight: "r&eacute;initialiser la coloration syntaxique (si d&eacute;syncronis&eacute;e du texte)",
word_wrap: "activer/d&eacute;sactiver les retours &agrave; la ligne automatiques",
help: "&agrave; propos",
save: "sauvegarder",
load: "charger",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Position",
total: "Total",
close_popup: "fermer le popup",
shortcuts: "Racourcis clavier",
add_tab: "ajouter une tabulation dans le texte",
remove_tab: "retirer une tabulation dans le texte",
about_notice: "Note: la coloration syntaxique n'est pr&eacute;vue que pour de courts textes.",
toggle: "basculer l'&eacute;diteur",
accesskey: "Accesskey",
tab: "Tab",
shift: "Maj",
ctrl: "Ctrl",
esc: "Esc",
processing: "chargement...",
fullscreen: "plein &eacute;cran",
syntax_selection: "--Syntaxe--",
close_tab: "Fermer le fichier"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'fr.js'] = true;


editAreaLoader.lang["hr"]={
new_document: "Novi dokument",
search_button: "Traži i izmijeni",
search_command: "Traži dalje / Otvori prozor za traženje",
search: "Traži",
replace: "Izmijeni",
replace_command: "Izmijeni / Otvori prozor za traženje",
find_next: "Traži dalje",
replace_all: "Izmjeni sve",
reg_exp: "Regularni izrazi",
match_case: "Bitna vel. slova",
not_found: "nije naðeno.",
occurrence_replaced: "izmjenjenih.",
search_field_empty: "Prazno polje za traženje!",
restart_search_at_begin: "Došao do kraja. Poèeo od poèetka.",
move_popup: "Pomakni prozor",
font_size: "--Velièina teksta--",
go_to_line: "Odi na redak",
go_to_line_prompt: "Odi na redak:",
undo: "Vrati natrag",
redo: "Napravi ponovo",
change_smooth_selection: "Ukljuèi/iskljuèi neke moguænosti prikaza (pametniji prikaz, ali zagušeniji CPU)",
highlight: "Ukljuèi/iskljuèi bojanje sintakse",
reset_highlight: "Ponovi kolorizaciju (ako je nesinkronizirana s tekstom)",
word_wrap: "toggle word wrapping mode",
help: "O edit_area",
save: "Spremi",
load: "Uèitaj",
line_abbr: "Ln",
char_abbr: "Zn",
position: "Pozicija",
total: "Ukupno",
close_popup: "Zatvori prozor",
shortcuts: "Kratice",
add_tab: "Dodaj tabulaciju",
remove_tab: "Makni tabulaciju",
about_notice: "Napomena: koloriziranje sintakse je samo za kratke kodove",
toggle: "Prebaci naèin ureðivanja",
accesskey: "Accesskey",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Procesiram...",
fullscreen: "Cijeli prozor",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'hr.js'] = true;


editAreaLoader.lang["it"]={
new_document: "nuovo documento vuoto",
search_button: "cerca e sostituisci",
search_command: "trova successivo / apri finestra di ricerca",
search: "cerca",
replace: "sostituisci",
replace_command: "sostituisci / apri finestra di ricerca",
find_next: "trova successivo",
replace_all: "sostituisci tutti",
reg_exp: "espressioni regolari",
match_case: "confronta maiuscole/minuscole<br />",
not_found: "non trovato.",
occurrence_replaced: "occorrenze sostituite.",
search_field_empty: "Campo ricerca vuoto",
restart_search_at_begin: "Fine del testo raggiunta. Ricomincio dall'inizio.",
move_popup: "sposta popup di ricerca",
font_size: "-- Dimensione --",
go_to_line: "vai alla linea",
go_to_line_prompt: "vai alla linea numero:",
undo: "annulla",
redo: "ripeti",
change_smooth_selection: "abilita/disabilita alcune caratteristiche della visualizzazione",
highlight: "abilita/disabilita colorazione della sintassi",
reset_highlight: "aggiorna colorazione (se non sincronizzata)",
word_wrap: "toggle word wrapping mode",
help: "informazioni su...",
save: "salva",
load: "carica",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Posizione",
total: "Totale",
close_popup: "chiudi popup",
shortcuts: "Scorciatoie",
add_tab: "aggiungi tabulazione",
remove_tab: "rimuovi tabulazione",
about_notice: "Avviso: la colorazione della sintassi vale solo con testo piccolo",
toggle: "Abilita/disabilita editor",
accesskey: "Accesskey",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "In corso...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'it.js'] = true;


editAreaLoader.lang["ja"]={
new_document: "新規作成",
search_button: "検索・置換",
search_command: "次を検索 / 検索窓を表示",
search: "検索",
replace: "置換",
replace_command: "置換 / 置換窓を表示",
find_next: "次を検索",
replace_all: "全置換",
reg_exp: "正規表現",
match_case: "大文字小文字の区別",
not_found: "見つかりません。",
occurrence_replaced: "置換しました。",
search_field_empty: "検索対象文字列が空です。",
restart_search_at_begin: "終端に達しました、始めに戻ります",
move_popup: "検索窓を移動",
font_size: "--フォントサイズ--",
go_to_line: "指定行へ移動",
go_to_line_prompt: "指定行へ移動します:",
undo: "元に戻す",
redo: "やり直し",
change_smooth_selection: "スムース表示の切り替え（CPUを使います）",
highlight: "構文強調表示の切り替え",
reset_highlight: "構文強調表示のリセット",
word_wrap: "toggle word wrapping mode",
help: "ヘルプを表示",
save: "保存",
load: "読み込み",
line_abbr: "行",
char_abbr: "文字",
position: "位置",
total: "合計",
close_popup: "ポップアップを閉じる",
shortcuts: "ショートカット",
add_tab: "タブを挿入する",
remove_tab: "タブを削除する",
about_notice: "注意：構文強調表示は短いテキストでしか有効に機能しません。",
toggle: "テキストエリアとeditAreaの切り替え",
accesskey: "アクセスキー",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "処理中です...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'ja.js'] = true;


editAreaLoader.lang["mk"]={
new_document: "Нов документ",
search_button: "Најди и замени",
search_command: "Барај следно / Отвори нов прозорец за пребарување",
search: "Барај",
replace: "Замени",
replace_command: "Замени / Отвори прозорец за пребарување",
find_next: "најди следно",
replace_all: "Замени ги сите",
reg_exp: "Регуларни изрази",
match_case: "Битна е големината на буквите",
not_found: "не е пронајдено.",
occurrence_replaced: "замени.",
search_field_empty: "Полето за пребарување е празно",
restart_search_at_begin: "Крај на областа. Стартувај од почеток.",
move_popup: "Помести го прозорецот",
font_size: "--Големина на текстот--",
go_to_line: "Оди на линија",
go_to_line_prompt: "Оди на линија со број:",
undo: "Врати",
redo: "Повтори",
change_smooth_selection: "Вклучи/исклучи некои карактеристики за приказ (попаметен приказ, но поголемо оптеретување за процесорот)",
highlight: "Вклучи/исклучи осветлување на синтакса",
reset_highlight: "Ресетирај го осветлувањето на синтакса (доколку е десинхронизиранo со текстот)",
word_wrap: "toggle word wrapping mode",
help: "За",
save: "Зачувај",
load: "Вчитај",
line_abbr: "Лн",
char_abbr: "Зн",
position: "Позиција",
total: "Вкупно",
close_popup: "Затвори го прозорецот",
shortcuts: "Кратенки",
add_tab: "Додај табулација на текстот",
remove_tab: "Отстрани ја табулацијата",
about_notice: "Напомена: Осветлувањето на синтанса е само за краток текст",
toggle: "Смени начин на уредување",
accesskey: "Accesskey",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Обработувам...",
fullscreen: "Цел прозорец",
syntax_selection: "--Синтакса--",
close_tab: "Избери датотека"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'mk.js'] = true;


editAreaLoader.lang["nl"]={
new_document: "nieuw leeg document",
search_button: "zoek en vervang",
search_command: "zoek volgende / zoekscherm openen",
search: "zoek",
replace: "vervang",
replace_command: "vervang / zoekscherm openen",
find_next: "volgende vinden",
replace_all: "alles vervangen",
reg_exp: "reguliere expressies",
match_case: "hoofdletter gevoelig",
not_found: "niet gevonden.",
occurrence_replaced: "object vervangen.",
search_field_empty: "Zoek veld leeg",
restart_search_at_begin: "Niet meer instanties gevonden, begin opnieuw",
move_popup: "versleep zoek scherm",
font_size: "--Letter grootte--",
go_to_line: "Ga naar regel",
go_to_line_prompt: "Ga naar regel nummer:",
undo: "Ongedaan maken",
redo: "Opnieuw doen",
change_smooth_selection: "zet wat schermopties aan/uit (kan langzamer zijn)",
highlight: "zet syntax highlight aan/uit",
reset_highlight: "reset highlight (indien gedesynchronizeerd)",
word_wrap: "toggle word wrapping mode",
help: "informatie",
save: "opslaan",
load: "laden",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Positie",
total: "Totaal",
close_popup: "Popup sluiten",
shortcuts: "Snelkoppelingen",
add_tab: "voeg tabs toe in tekst",
remove_tab: "verwijder tabs uit tekst",
about_notice: "Notitie: syntax highlight functie is alleen voor kleine tekst",
toggle: "geavanceerde bewerkingsopties",
accesskey: "Accessknop",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Verwerken...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'nl.js'] = true;


editAreaLoader.lang["pl"]={
new_document: "nowy dokument",
search_button: "znajdź i zamień",
search_command: "znajdź następny",
search: "znajdź",
replace: "zamień",
replace_command: "zamień",
find_next: "następny",
replace_all: "zamień wszystko",
reg_exp: "wyrażenie regularne",
match_case: "uwzględnij wielkość liter<br />",
not_found: "nie znaleziono.",
occurrence_replaced: "wystąpień zamieniono.",
search_field_empty: "Nie wprowadzono tekstu",
restart_search_at_begin: "Koniec dokumentu. Wyszukiwanie od początku.",
move_popup: "przesuń okienko wyszukiwania",
font_size: "Rozmiar",
go_to_line: "idź do linii",
go_to_line_prompt: "numer linii:",
undo: "cofnij",
redo: "przywróć",
change_smooth_selection: "włącz/wyłącz niektóre opcje wyglądu (zaawansowane opcje wyglądu obciążają procesor)",
highlight: "włącz/wyłącz podświetlanie składni",
reset_highlight: "odśwież podświetlanie składni (jeśli rozsynchronizowało się z tekstem)",
word_wrap: "toggle word wrapping mode",
help: "o programie",
save: "zapisz",
load: "otwórz",
line_abbr: "Ln",
char_abbr: "Zn",
position: "Pozycja",
total: "W sumie",
close_popup: "zamknij okienko",
shortcuts: "Skróty klawiaturowe",
add_tab: "dodaj wcięcie do zaznaczonego tekstu",
remove_tab: "usuń wcięcie",
about_notice: "Uwaga: podświetlanie składni nie jest zalecane dla długich tekstów",
toggle: "Włącz/wyłącz edytor",
accesskey: "Alt+",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Przetwarzanie...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'pl.js'] = true;


editAreaLoader.lang["pt"]={
new_document: "Novo documento",
search_button: "Localizar e substituir",
search_command: "Localizar próximo",
search: "Localizar",
replace: "Substituir",
replace_command: "Substituir",
find_next: "Localizar",
replace_all: "Subst. tudo",
reg_exp: "Expressões regulares",
match_case: "Diferenciar maiúsculas e minúsculas",
not_found: "Não encontrado.",
occurrence_replaced: "Ocorrências substituidas",
search_field_empty: "Campo localizar vazio.",
restart_search_at_begin: "Fim das ocorrências. Recomeçar do inicio.",
move_popup: "Mover janela",
font_size: "--Tamanho da fonte--",
go_to_line: "Ir para linha",
go_to_line_prompt: "Ir para a linha:",
undo: "Desfazer",
redo: "Refazer",
change_smooth_selection: "Opções visuais",
highlight: "Cores de sintaxe",
reset_highlight: "Resetar cores (se não sincronizado)",
word_wrap: "toggle word wrapping mode",
help: "Sobre",
save: "Salvar",
load: "Carregar",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Posição",
total: "Total",
close_popup: "Fechar",
shortcuts: "Shortcuts",
add_tab: "Adicionar tabulação",
remove_tab: "Remover tabulação",
about_notice: "Atenção: Cores de sintaxe são indicados somente para textos pequenos",
toggle: "Exibir editor",
accesskey: "Accesskey",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Processando...",
fullscreen: "fullscreen",
syntax_selection: "--Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'pt.js'] = true;


editAreaLoader.lang["ru"]={
new_document: "новый пустой документ",
search_button: "поиск и замена",
search_command: "искать следующий / открыть панель поиска",
search: "поиск",
replace: "замена",
replace_command: "заменить / открыть панель поиска",
find_next: "найти следующее",
replace_all: "заменить все",
reg_exp: "регулярное выражение",
match_case: "учитывать регистр",
not_found: "не найдено.",
occurrence_replaced: "вхождение заменено.",
search_field_empty: "Поле поиска пустое",
restart_search_at_begin: "Достигнут конец документа. Начинаю с начала.",
move_popup: "переместить окно поиска",
font_size: "--Размер шрифта--",
go_to_line: "перейти к строке",
go_to_line_prompt: "перейти к строке номер:",
undo: "отменить",
redo: "вернуть",
change_smooth_selection: "включить/отключить некоторые функции просмотра (более красиво, но больше использует процессор)",
highlight: "переключить подсветку синтаксиса включена/выключена",
reset_highlight: "восстановить подсветку (если разсинхронизирована от текста)",
word_wrap: "toggle word wrapping mode",
help: "о программе",
save: "сохранить",
load: "загрузить",
line_abbr: "Стр",
char_abbr: "Стлб",
position: "Позиция",
total: "Всего",
close_popup: "закрыть всплывающее окно",
shortcuts: "Горячие клавиши",
add_tab: "добавить табуляцию в текст",
remove_tab: "убрать табуляцию из текста",
about_notice: "Внимание: функция подсветки синтаксиса только для небольших текстов",
toggle: "Переключить редактор",
accesskey: "Горячая клавиша",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Обработка...",
fullscreen: "полный экран",
syntax_selection: "--Синтакс--",
close_tab: "Закрыть файл"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'ru.js'] = true;


editAreaLoader.lang["sk"]={
new_document: "nový prázdy dokument",
search_button: "vyhľadaj a nahraď",
search_command: "hľadaj ďalsšie / otvor vyhľadávacie pole",
search: "hľadaj",
replace: "nahraď",
replace_command: "nahraď / otvor vyhľadávacie pole",
find_next: "nájdi ďalšie",
replace_all: "nahraď všetko",
reg_exp: "platné výrazy",
match_case: "zhodujúce sa výrazy",
not_found: "nenájdené.",
occurrence_replaced: "výskyty nahradené.",
search_field_empty: "Pole vyhľadávanie je prádzne",
restart_search_at_begin: "End of area reached. Restart at begin.",
move_popup: "presuň vyhľadávacie okno",
font_size: "--Veľkosť textu--",
go_to_line: "prejdi na riadok",
go_to_line_prompt: "prejdi na riadok:",
undo: "krok späť",
redo: "prepracovať",
change_smooth_selection: "povoliť/zamietnúť niektoré zo zobrazených funkcií (účelnejšie zobrazenie vyžaduje  väčšie zaťaženie procesora CPU)",
highlight: "prepnúť zvýrazňovanie syntaxe zap/vyp",
reset_highlight: "zrušiť zvýrazňovanie (ak je nesynchronizované s textom)",
word_wrap: "toggle word wrapping mode",
help: "o programe",
save: "uložiť",
load: "načítať",
line_abbr: "Ln",
char_abbr: "Ch",
position: "Pozícia",
total: "Spolu",
close_popup: "zavrieť okno",
shortcuts: "Skratky",
add_tab: "pridať tabulovanie textu",
remove_tab: "odstrániť tabulovanie textu",
about_notice: "Upozornenie: funkcia zvýrazňovania syntaxe je dostupná iba pre malý text",
toggle: "Prepnúť editor",
accesskey: "Accesskey",
tab: "Záložka",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "Spracúvam...",
fullscreen: "cel=a obrazovka",
syntax_selection: "--Vyber Syntax--",
close_tab: "Close file"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'sk.js'] = true;


editAreaLoader.lang["zh"]={
new_document: "新建空白文档",
search_button: "查找与替换",
search_command: "查找下一个 / 打开查找框",
search: "查找",
replace: "替换",
replace_command: "替换 / 打开查找框",
find_next: "查找下一个",
replace_all: "全部替换",
reg_exp: "正则表达式",
match_case: "匹配大小写",
not_found: "未找到.",
occurrence_replaced: "处被替换.",
search_field_empty: "查找框没有内容",
restart_search_at_begin: "已到到文档末尾. 从头重新查找.",
move_popup: "移动查找对话框",
font_size: "--字体大小--",
go_to_line: "转到行",
go_to_line_prompt: "转到行:",
undo: "恢复",
redo: "重做",
change_smooth_selection: "启用/禁止一些显示特性(更好看但更耗费资源)",
highlight: "启用/禁止语法高亮",
reset_highlight: "重置语法高亮(当文本显示不同步时)",
word_wrap: "toggle word wrapping mode",
help: "关于",
save: "保存",
load: "加载",
line_abbr: "行",
char_abbr: "字符",
position: "位置",
total: "总计",
close_popup: "关闭对话框",
shortcuts: "快捷键",
add_tab: "添加制表符(Tab)",
remove_tab: "移除制表符(Tab)",
about_notice: "注意：语法高亮功能仅用于较少内容的文本(文件内容太大会导致浏览器反应慢)",
toggle: "切换编辑器",
accesskey: "快捷键",
tab: "Tab",
shift: "Shift",
ctrl: "Ctrl",
esc: "Esc",
processing: "正在处理中...",
fullscreen: "全屏编辑",
syntax_selection: "--语法--",
close_tab: "关闭文件"
};

editAreaLoader.loadedFiles[editAreaLoader.baseURL + 'langs/' + 'zh.js'] = true;


