
// wait for the content
function lazyload_done_now_init(basedir)
{
	// our uploader instance
	var up = new FancyUpload2($('lightbox-status'), $('lightbox-list'), { // options object
		// we console.log infos, remove that in production!!
		verbose: false,

		// url is read from the form, so you just have to change one place
		url: $('lightboxForm').action,

		// path to the SWF file
		path: basedir + 'lib/includes/js/fancyupload/Assets/Swiff.Uploader.swf',

		// remove that line to select all files, or edit it, add more items
		typeFilter: {
			'Images (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png'
		},

		// this is our browse button, *target* is overlayed with the Flash movie
		target: 'lightbox-browse',

		// pass along any session cookie data, etc. in the request section (PHP: $_GET[])
		appendCookieData: true,

		// count the number of errors:
		fail_count: 0,

		// graceful degradation, onLoad is only called if all went well with Flash
		onLoad: function() {
			$('lightbox-status').removeClass('hide'); // we show the actual UI
			$('lightbox-fallback').destroy(); // ... and hide the plain form

			// We relay the interactions with the overlayed flash to the link
			this.target.addEvents({
				click: function() {
					return false;
				},
				mouseenter: function() {
					this.addClass('hover');
				},
				mouseleave: function() {
					this.removeClass('hover');
					this.blur();
				},
				mousedown: function() {
					this.focus();
				}
			});

			// Interactions for the 2 other buttons

			$('lightbox-clear').addEvent('click', function() {
				up.remove(); // remove all files
				return false;
			});

			$('lightbox-upload').addEvent('click', function() {
				up.start(); // start upload
				return false;
			});
		},

		// Edit the following lines, it is your custom event handling

		/**
		 * Is called when files were not added, "files" is an array of invalid File classes.
		 *
		 * This example creates a list of error elements directly in the file list, which
		 * hide on click.
		 */
		onSelectFail: function(files) {
			files.each(function(file) {
				this.fail_count++;

				new Element('li', {
					'class': 'validation-error',
					html: file.validationErrorMessage || file.validationError,
					title: MooTools.lang.get('FancyUpload', 'removeTitle'),
					events: {
						click: function() {
							this.destroy();
						}
					}
				}).inject(this.list, 'top');
			}, this);
		},

		onBeforeStart: function() {
			this.fail_count = 0;

			up.setOptions({
				data: $('lightboxForm').toQueryString()  // [i_a] trailing comma removed, caused crash on IE7
			});
		},

		/**
		 * This one was directly in FancyUpload2 before, the event makes it
		 * easier for you, to add your own response handling (you probably want
		 * to send something else than JSON or different items).
		 */
		onFileSuccess: function(file, response) {
			//alert(file + ': ' + response);
			var json = new Object(JSON.decode(response, true) || {});

			if (json.get('status') == '1') {
				file.element.addClass('file-success');
				file.info.set('html', '<strong>&#8730;</strong> ' + json.get('width') + ' x ' + json.get('height') + 'px');
			} else {
				file.element.addClass('file-failed');
				file.info.set('html', '<strong>X</strong> ' + (json.get('error') ? (json.get('error') + ' #' + json.get('code')) : response));
			}
		},

		onComplete: function() {
			if (this.fail_count == 0)
			{
				location.reload();
				return false;
			}
			else
			{
				alert('' + this.fail_count + " items didn't correctly upload.\nPlease review the results and then reload the page to see\nthe thumbnails of the successfully uploaded images.");
				return true;
			}
		},

		/**
		 * onFail is called when the Flash movie got bashed by some browser plugin
		 * like Adblock or Flashblock.
		 */
		onFail: function(error) {
			switch (error) {
				case 'hidden': // works after enabling the movie and clicking refresh
					alert('To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).');
					break;
				case 'blocked': // This no *full* fail, it works after the user clicks the button
					alert('To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).');
					break;
				case 'empty': // Oh oh, wrong path
					//alert('Uploader did not finish loading, try reloading if necessary.');
					break;
				case 'flash': // no flash 9+ :(
					alert('To enable the embedded uploader, install the latest Adobe Flash plugin.');
					break;
			}
		}
	});





	if ($('createAlbum'))
	{
		new FormValidator.Inline($('createAlbum') /* ,
		{
			onFormValidate: function(passed, form, event)
			{
				event.stop();
				if (passed)
					form.submit();
			}
		} */ );
	}
}