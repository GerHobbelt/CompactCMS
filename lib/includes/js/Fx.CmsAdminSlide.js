/*
---

script: Fx.CmsAdminSlide.js

name: Fx.CmsAdminSlide

description: Effect to slide an element in and out of view. Custom version for 
	CompactCMS admin screen, where the surrounding <fieldset> also needs additional
	manipulation to really shrink the section (element) into near-oblivion:
	there we animate the margin-bottom and height to create a nice 'folded' look.
	
	Derived from the Fx.Slide code by Valerio Proietti.


license: MIT-style license

authors:
  - Ger Hobbelt

requires:
  - Core/Fx
  - Core/Element.Style
  - /MooTools.More

provides: [Fx.CmsAdminSlide]

...
*/

Fx.CmsAdminSlide = new Class({

	Extends: Fx,

	options: {
		//mode: 'vertical',
		wrapper: false,
		hideOverflow: true,
		resetHeight: true,   // <-- another default than Fx.Slide
		
		// extra options for our custom slide effect:
		wrapper_2: false,	// should reference the <fieldset> of the section in our admin page 
		wrapper_2_height: 7,			// animate to become 7px; 'height' MUST be the first entry in here
		wrapper_2_margin_bottom: 20		// animate to become 20px 
	},

	initialize: function(element, options){
		element = this.element = this.subject = document.id(element);
		this.parent(options);
		options = this.options;

		var wrapper = element.retrieve('wrapper'),
			wrapper_2 = element.retrieve('wrapper_2'),
			styles = element.getStyles('margin', 'position', 'overflow');

		if (options.hideOverflow) styles = Object.append(styles, {overflow: 'hidden'});
		if (options.wrapper) wrapper = document.id(options.wrapper).setStyles(styles);
		if (options.wrapper_2) wrapper_2 = document.id(options.wrapper_2) /* .setStyles(styles) */ ;

		if (!wrapper) wrapper = new Element('div', {
			styles: styles
		}).wraps(element);

		if (!wrapper_2) wrapper_2 = wrapper.getParent('fieldset');
		element.store('wrapper_2', wrapper_2);
		
		element.store('wrapper', wrapper).setStyle('margin', 0);
		if (element.getStyle('overflow') == 'visible') element.setStyle('overflow', 'hidden');

		this.now = [];
		this.open = true;
		this.wrapper = wrapper;
		this.wrapper_2 = wrapper_2;

		this.addEvent('complete', function(){
			this.open = (this.wrapper['offset' + this.layout.capitalize()] != 0);
			if (this.open && this.options.resetHeight) 
			{
				this.wrapper.setStyle('height', '');
				if (this.wrapper_2)
				{
					this.wrapper_2.setStyle('height', '');
					this.wrapper_2.setStyle('margin-bottom', '');
					// also reset the margin/offset cache, so new measurements are made upon the next toggle/hide action:
					this.offset_2 = 0;
					this.margin_2 = 0;
				}
			}
		}, true);
	},

	vertical: function(){
		this.margin = 'margin-top';
		this.layout = 'height';
		this.offset = this.element.offsetHeight;
		if (this.wrapper_2 && !this.margin_2)
		{
			this.margin_2 = this.wrapper_2.getStyle('margin-bottom').toInt();
		}
		if (this.wrapper_2 && !this.offset_2)
		{
			this.offset_2 = this.wrapper_2.scrollHeight; // offsetHeight is too large by 16px (margin + borders); this one has the correct value
		}
	},

	set: function(now){
		this.element.setStyle(this.margin, now[0]);
		this.wrapper.setStyle(this.layout, now[1]);
		if (this.wrapper_2)
		{
			this.wrapper_2.setStyle('height', now[2]);
			this.wrapper_2.setStyle('margin-bottom', now[3]);
		}
		return this;
	},

	compute: function(from, to, delta){
		return [0, 1, 2, 3].map(function(i){
			return Fx.compute(from[i], to[i], delta);
		});
	},

	start: function(how){
		if (!this.check(how)) return this;
		this.vertical();
		
		var margin = this.element.getStyle(this.margin).toInt(),
			layout = this.wrapper.getStyle(this.layout).toInt(),
			caseIn = [[margin, layout, this.options.wrapper_2_height, this.options.wrapper_2_margin_bottom], [0, this.offset, this.offset_2, this.margin_2]],
			caseOut = [[margin, layout, this.offset_2, this.margin_2], [-this.offset, 0, this.options.wrapper_2_height, this.options.wrapper_2_margin_bottom]],
			start;

		switch (how){
			case 'in': start = caseIn; break;
			case 'out': start = caseOut; break;
			case 'toggle': start = (layout == 0) ? caseIn : caseOut;
		}
		return this.parent(start[0], start[1]);
	},

	slideIn: function(){
		return this.start('in');
	},

	slideOut: function(){
		return this.start('out');
	},

	hide: function(){
		this.vertical();
		this.open = false;
		return this.set([-this.offset, 0, this.options.wrapper_2_height, this.options.wrapper_2_margin_bottom]);
	},

	show: function(){
		this.vertical();
		this.open = true;
		this.set([0, this.offset, this.offset_2, this.margin_2]);
		if (this.options.resetHeight) 
		{
			this.wrapper.setStyle('height', '');
			if (this.wrapper_2)
			{
				this.wrapper_2.setStyle('height', '');
				this.wrapper_2.setStyle('margin-bottom', '');
				// also reset the margin/offset cache, so new measurements are made upon the next toggle/hide action:
				this.offset_2 = 0;
				this.margin_2 = 0;
			}
		}
		return this;
	},

	toggle: function(){
		return this.start('toggle');
	}

});

Element.Properties.custom_slide = {

	set: function(options){
		this.get('custom_slide').cancel().setOptions(options);
		return this;
	},

	get: function(){
		var slide = this.retrieve('custom_slide');
		if (!slide)
		{
			slide = new Fx.CmsAdminSlide(this, {link: 'cancel'});
			this.store('custom_slide', slide);
		}
		return slide;
	}

};

Element.implement({

	custom_slide: function(how){
		how = how || 'toggle';
		var slide = this.get('custom_slide'), toggle;
		switch (how)
		{
			case 'hide': slide.hide(); break;
			
			case 'show': slide.show(); break;
			
			case 'in': slide.slideIn(); break;
			
			case 'out': slide.slideOut(); break;
			
			case 'toggle':
				var flag = this.retrieve('custom_slide:flag', slide.open);
				slide[flag ? 'slideOut' : 'slideIn']();
				this.store('custom_slide:flag', !flag);
				toggle = true;
				break;
			
			default: 
				slide.start(how);
				break;
		}
		if (!toggle) this.eliminate('custom_slide:flag');
		return this;
	}

});
