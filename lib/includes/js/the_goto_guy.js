/*
A generic functions to 'pop back to' the main admin screen (or other place of your desire).

Note theat there a few issues to keep in mind (which, among others, triggered the refactoring
of this bit of functionality into a separate JS file.
The other bit of the 'making sure' is the fact that the <a href="..." link this function
is probably part of (as an onClick() invoked item) MUST point at your default destination,
so you go there if all else fails, including this function (in which case a return value is
MOST PROBABLY 'false', but we CANNOT GUARANTEE that we can detect whether we fail or succeed
from in here.

Anyway, on to the caveats:

The default install of IE6 has a 'strict security' setting (which I like from the PoV of
security :-) ) and will prevent any window.location.href rewriting from JavaScript as a part
of that policy. THAT is what we are trying to fight/circumvent in here!

We also detect whether we were invoked from inside a MochaUI window or not (due to the user
having opened this page using right-click > open in new window / open in new tab.
In the latter case, we try to replace the current window with the main admin screen, but we
MAY NOT SUCCEED in doing so. Instead, IE6 may decide to open another window, which will get
focus by default, which is, ahhh, kinda o-kay... but not really to our liking.

As an extra, this function detects whether mootools was loaded or not: only when it is, do we
bother checking for MochaUI.

																Nov 2010, Ger Hobbelt

POST TESTING NOTE:
	Browsers (including IE6 when running in (default install!) strict safety mode) will
	cooperate, UNLESS YOU FORGET to code the onclick/onsubmit as:
	  onevent="return func();"
	If you forget that 'return' in there, your false/true answer to the optional
	'are you sure' check (or other activity coded in that func()) will go unused.
	ALWAYS return a value, because the alternative is an IMPLIED return value of
	NULL which compares false to anything, i.e. will act as if you returned /true/ and
	take the href="link" link next!
*/

function close_mochaUI_window_or_goto_url(url, our_own_id)
{
	/* be anal about checking the existence of objects and their parts; too many little things have gone wrong in IE6/7/8 already :-( */
	var has_mocha = (parent && parent.MochaUI && (typeof parent.MochaUI.closeWindow == 'function') && (typeof parent.$ == 'function'));
	var ok = false;

	if (has_mocha && our_own_id)
	{
		var obj = parent.$(our_own_id);

		try
		{
			ok = parent.MochaUI.closeWindow(obj); // returns true or nothing...
		}
		catch(e)
		{
		}
	}
	if (!ok)
	{
		if (!url)
		{
			/* go back one in browser history */
			if (parent)
			{
				parent.window.history.go(-1);
			}
			else
			{
				window.history.go(-1);
			}
		}
		else
		{
			/* try another tactic; most probably we're in our window and want to go somewhere... */
			/*
			if (window.top != window.self)
			{
				alert("This window is not the topmost window! Am I in a frame?");
			}
			else
			{
				alert("This window is the topmost window!");
			}
			*/
			if (typeof window.top.location.replace == 'function')
			{
				ok = window.top.location.replace(url);
			}
			else
			{
				/* first try to overwrite our current top href; if we succeed (FF et al) we're good to go! */
				window.top.location.href = url;
				if (window.top.location.href != url)
				{
					/*
					try to open the URL in the top window (which is us unless we're inside an iframe,
					in which case we want the top window anyway!
					*/
					var wobj = window.open(url, '_top');
					ok = (wobj !== null && wobj.location.href == url);
				}
			}
		}
	}
	return !ok;
}




/*
WARNING:

mootools: Request.HTML method does process JavaScript, but does NOT process external script loads like this line:

   <script type="text/javascript" src="<?php echo $cfg['rootdir']; ?>lib/includes/js/the_goto_guy.js?cb=jump_if_not_top" charset="utf-8"></script>

which implies that we CANNOT have such external scripts in auth.inc.php and succeed in reloading the page towards showing a correct login page
when the session expired for whatever reason.

OUR FIX:

To make sure that the in-page JavaScript code is reduced to the bare minimum required, we place the bulk of the detection/redirection action
here, as The Goto Guy is the only JavaScript file loaded with EVERY admin page, thus ensuring the auth.inc.php script code can access this code,
no matter which page or JS Request.HTML action loaded it.

This function checks whether the given DOM tree is inside an iframe or other construct; when it is not the top/master page itself, we redirect.
*/
function jump_if_not_top(reqd_url_piece /* "<?php echo $_SERVER['PHP_SELF']; ?>" */, redirect_to_url /* "<?php echo makeAbsoluteURI($_SERVER['PHP_SELF']); ?>" */ )
{
	/*
	 * make sure we are NOT loaded in a [i]frame (~ MochaUI window)
	 *
	 * code bit taken from mootools 'domready' internals; rest derived from
	 *   http://tjkdesign.com/articles/frames/4.asp#breaking
	 */
	var isFramed = false;
	// Thanks to Rich Dougherty <http://www.richdougherty.com/>
	try
	{
		isFramed = (window.frameElement != null);
	}
	catch(e){}
	/* another way to detect placement in a frame/iframe */
	try
	{
		var f = (top != this);
		if (f) isFramed = true;
	}
	catch(e){}
	/* and for those rare occasions where the login screen is (inadvertedly) loaded through an AJAX load into a <div> or other in the current document: */
	try
	{
		if (this.location && this.location.href)
		{
			var f = (this.location.href.indexOf(reqd_url_piece) < 0);
			if (f) isFramed = true;
		}
	}
	catch(e){}

	if (isFramed)
	{
		close_mochaUI_window_or_goto_url(redirect_to_url, null);
	}
}





function goto_url(url)
{
	var ok = false;
	
	if (url)
	{
		/*
		if (window.top != window.self)
		{
			alert("This window is not the topmost window! Am I in a frame?");
		}
		else
		{
			alert("This window is the topmost window!");
		}
		*/
		if (typeof window.location.replace == 'function')
		{
			ok = window.location.replace(url);
		}
		else
		{
			/* first try to overwrite our current href; if we succeed (FF et al) we're good to go! */
			window.location.href = url;
			if (window.location.href != url)
			{
				/*
				try to open the URL in the window / iframe
				*/
				var wobj = window.open(url, '_self');
				ok = (wobj !== null && wobj.location.href == url);
			}
		}
	}
	
	return !ok;
}




