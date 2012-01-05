

// Process steps
var frm = $('installFrm');

/* form may NOT exist when the update check has detected an outdated restore SQLdump or config.inc.php file */
if (frm)
{
	frm.addEvent('submit', function(install)
	{
		new Event(install).stop();

		var install_div = $('install');
		var scroll = new Fx.Scroll(window, {wait: false, duration: 500, transition: Fx.Transitions.Quad.easeInOut});

		new Request.HTML({
			method: 'post',
			url: './installer.inc.php',
			update: install_div,
			onRequest:  function() {
				install_div.empty().addClass('loading');
			},
			onComplete: function() {
				install_div.removeClass('loading');
				scroll.toElement('install-wrapper');
				build_tips();
				//alert('loaded!');
			}
		}).send(frm);
	});
}

build_tips();




function build_tips()
{
	// Tips links
	$$('span.ss_help').each(function(element,index)
	{
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
				tip.setStyles({
					width: '600px'
				});
			}
		}
	});
}

