(function(){var a=tinymce.each;tinymce.create("tinymce.plugins.AdvListPlugin",{init:function(c,d){var e=this;e.editor=c;function b(g){var f=[];a(g.split(/,/),function(h){f.push({title:"advlist."+(h=="default"?"def":h.replace(/-/g,"_")),styles:{listStyleType:h=="default"?"":h}})});return f}e.numlist=c.getParam("advlist_number_styles")||b("default,lower-alpha,lower-greek,lower-roman,upper-alpha,upper-roman");e.bullist=c.getParam("advlist_bullet_styles")||b("default,circle,disc,square")},createControl:function(c,b){var f=this,e,h;if(c=="numlist"||c=="bullist"){if(f[c][0].title=="advlist.def"){h=f[c][0]}function d(i,k){var j=true;a(k.styles,function(m,l){if(f.editor.dom.getStyle(i,l)!=m){j=false;return false}});return j}function g(){var k,i=f.editor,l=i.dom,j=i.selection;k=l.getParent(j.getNode(),"ol,ul");if(!k||k.nodeName==(c=="bullist"?"OL":"UL")||d(k,h)){i.execCommand(c=="bullist"?"InsertUnorderedList":"InsertOrderedList")}if(h){k=l.getParent(j.getNode(),"ol,ul");if(k){l.setStyles(k,h.styles);k.removeAttribute("data-mce-style")}}i.focus()}e=b.createSplitButton(c,{title:"advanced."+c+"_desc","class":"mce_"+c,onclick:function(){g()}});e.onRenderMenu.add(function(i,j){j.onShowMenu.add(function(){var m=f.editor.dom,l=m.getParent(f.editor.selection.getNode(),"ol,ul"),k;if(l||h){k=f[c];a(j.items,function(n){var o=true;n.setSelected(0);if(l&&!n.isDisabled()){a(k,function(p){if(p.id==n.id){if(!d(l,p)){o=false;return false}}});if(o){n.setSelected(1)}}});if(!l){j.items[h.id].setSelected(1)}}});j.add({id:f.editor.dom.uniqueId(),title:"advlist.types","class":"mceMenuItemTitle",titleItem:true}).setDisabled(1);a(f[c],function(k){k.id=f.editor.dom.uniqueId();j.add({id:k.id,title:k.title,onclick:function(){h=k;g()}})})});return e}},getInfo:function(){return{longname:"Advanced lists",author:"Moxiecode Systems AB",authorurl:"http://tinymce.moxiecode.com",infourl:"http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/advlist",version:tinymce.majorVersion+"."+tinymce.minorVersion}}});tinymce.PluginManager.add("advlist",tinymce.plugins.AdvListPlugin)})();