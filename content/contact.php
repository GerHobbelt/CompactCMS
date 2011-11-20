<?php 

/************************************************

               PHP INIT SECTION

************************************************/

/* make sure no-one can run anything here if they didn't arrive through 'proper channels' */
if(!defined("COMPACTCMS_CODE")) { define("COMPACTCMS_CODE", 1); } /*MARKER*/

/*
We're only processing form requests / actions here, no need to load the page content in sitemap.php, etc. 
*/
if (!defined('CCMS_PERFORM_MINIMAL_INIT')) { define('CCMS_PERFORM_MINIMAL_INIT', true); }


// Define default location
if (!defined('BASE_PATH'))
{
	$base = str_replace('\\','/',dirname(dirname(__FILE__)));
	define('BASE_PATH', $base);
}

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/config.inc.php');

// Load basic configuration
/*MARKER*/require_once(BASE_PATH . '/lib/includes/common.inc.php');




/************************************************
 *
 *             START OF LOCAL CODE 
 *
 *  (support functions have been loaded now)
 *
 *
 ************************************************/


/*
 CONFIGURATION:

 Use a captcha to prevent spam (and annoy users)?
*/
define('USE_CAPTCHA_AGAINST_SPAM', true); 

/*
 CONFIGURATION:

 Do we use spammer detection honeytrap methods?

 That is: provide bogus field(s) like 'email' and such
 where automated fillers will go while we will ensure users
 do NOT go there; we also provide a session-based unique
 marker to identify whether the form submit originated from
 this webpage (at this time) or was produced by a spammer
 automaton.
*/
define('USE_HONEYTRAP_AGAINST_SPAM', true);




/*
 * Load session if not already done by CCMS: this is mandatory for the form 
 * to work as it won't have the benefit of CCMS doing its prep work for it 
 * on form submission!
 */
if (empty($_SESSION))
{
	if (!session_start()) die('session_start() failed');
}


function POST2str($var, $def = '')
{
	// prevent PHP barfing a hairball in E_STRICT:
	if (!isset($_POST) || empty($_POST[$var]))
		return $def;
	return strval($_POST[$var]);
}

function SESSION2str($var, $def = '')
{
	// prevent PHP barfing a hairball in E_STRICT:
	if (!isset($_SESSION) || empty($_SESSION[$var]))
		return $def;
	return strval($_SESSION[$var]);
}



// Check whether this is a send request
$action_type = getGETparam4IdOrNumber('do');

// debugging:
if ($cfg['IN_DEVELOPMENT_ENVIRONMENT'] && 0)
{
	$extra = array('action_type' => $action_type,
					'req_method_is_post' => $_SERVER['REQUEST_METHOD'] == 'POST',
					'captcha-on' => USE_CAPTCHA_AGAINST_SPAM,
					'captcha-match-a' => POST2str('verification', 'x'),
					'captcha-match-b' => SESSION2str('ccms_captcha', 'y'),
					'captcha-match' => POST2str('verification', 'x') == SESSION2str('ccms_captcha', 'y'),
					'honeypot-on' => USE_HONEYTRAP_AGAINST_SPAM,
					'honeypot-check1' => POST2str('email', '') == '',
					'honeypot-check2-a' => POST2str('darling_jar', 'x'),
					'honeypot-check2-b' => SESSION2str('ccms_contactform_honeypot', 'y'),
					'honeypot-check2' => POST2str('darling_jar', 'x') == SESSION2str('ccms_contactform_honeypot', 'y')
					);
	dump_request_to_logfile($extra); 
}

$error = null;
$success = null;
$is_form_post = false;

/*
Fetch values early, so we can keep their content on error: message is not 
immediately lost to user on faulty submit.

Security notes:

Considering the fact that we keep POST-ed data around when the POST itself is deemed
invalid, this user-friendly approach introduces a security hole if we're not careful.

Scenario:

Post to this URL to have your own sender/subject/content set up. Since
it will be a 'faulty' post, the content will be kept around and you 
must enter the captcha and run the submit JavaScript code in this page.

If you DO have captchas turned on, that captcha field must match our own
session-stored number for the post to make it through to email. Since that
would only happen (with high probability) when we're actually receiving a 
POST originating from this form, where a user copied the captcha into the 
approapriate field, we say we're pretty safe. 
That is: when the captcha approach holds.

If you do NOT have captchas turned on, then we still need a way to ensure 
it was 'us' (this form) and a 'human user' instead of a spammer machine doing
the POSTing: in that case, we check whether the honeytrap email field is
set by the spammer or the spammer didn't list the correct random identifier
for the current instantiation of this form.
If this approach holds (it is regarded as weaker than the captcha) we're safe
from email spammers (robotic form abusers).

Of course both anti-spam approaches may be used simultaneously in order to 
improve our chances of rejecting spam.


No matter what, the POSTed values are all filtered before we access them, so
the only remaining 'risk' is that someone pre-fills the form for a user, who
then needs to enter the captcha or at least hit the submit button.

Thanks to the filtering, the POSTed content won't be able to aid in XSS attacks,
no matter what is happening.
*/
$subject = getPOSTparam4EmailSubjectLine('subject');
$message = getPOSTparam4EmailBody('message');
$sender = getPOSTparam4HumanName('name');
$emailaddress = getPOSTparam4Email('abcdef');

	
// If the action type is equal to send, then continue
if ($action_type == 'send' 
	&& $_SERVER['REQUEST_METHOD'] == 'POST')
{
	$is_form_post = true;

	// make sure it's a valid action:
	if ((USE_CAPTCHA_AGAINST_SPAM ? POST2str('verification', 'x') == SESSION2str('ccms_captcha', 'y') : true)
		&& (USE_HONEYTRAP_AGAINST_SPAM ? POST2str('email', '') == '' && POST2str('darling_jar', 'x') == SESSION2str('ccms_contactform_honeypot', 'y') : true)
		) 
	{
		if (empty($emailaddress) || strcspn($emailaddress, '<"\'') != strlen($emailaddress))
		{
			// email filter allows quoted prefix before the '<' ; we DO NOT as we have both parts separated here...
			$error = 'You specified an invalid email address';
		}
		else if (empty($sender) || strpos($sender, '"') !== false )
		{
			// ... nor do we allow a double-quote inside the 'human name' preceeding part of the address.
			$error = 'You specified an invalid email sender name';
		}
		else if (!empty($sender) && !empty($emailaddress) && !empty($subject) && !empty($message))
		{
			/*
			We REQUIRE a FILLED subject line and message body as well
			*/
			
			$headers = 'From: "' . $sender . '" <' . $emailaddress . ">\r\n";
			// To send HTML mail, the Content-type header must be set
			$headers .= 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

			$mailmessage = "<html>\n<head>\n<title>Email</title>\n</head>\n<body>\n" . $message . "\n</body>\n</html>\n";

			// See http://php.net/manual/en/function.mail.php Warnings
			$mailmessage = str_replace("\n.", "\n..", $mailmessage);
			
			ob_start();
				$result = @mail("<YOUR_ADDRESS_HERE>", $subject, $mailmessage, $headers);
				$content = ob_get_contents();
			ob_end_clean();
			if($result) 
			{
				$success = 'Your message has been sent. Thanks!';
			} 
			else 
			{
				$content = trim($content);
				if (empty($content))
				{
					$content = 'The SMTP data may be misconfigured. Anyhow, the email could not be sent.';
				}
				$error = "Error while processing your e-mail:\n" . htmlentities($content, ENT_NOQUOTES, 'UTF-8');
			}
		}
		else
		{
			$error = 'You haven\'t specified either a valid email sender or a subject line or message body';
		}
	}
	else
	{
		$error = 'Invalid data. Nothing sent!';
	}
}

// always generate a fresh captcha code on each round:

// do NOT destroy an active session: it would have the effect of forced logging out of authenticated users of the (admin) website.
if (0)
{
	// destroy the session if it existed before: start a new session
	session_unset();
	session_destroy();
	session_regenerate_id();
	session_start();
}

if (USE_CAPTCHA_AGAINST_SPAM)
{
	$_SESSION['ccms_captcha'] = mt_rand(100100, 998998); 
}
if (USE_HONEYTRAP_AGAINST_SPAM)
{
	$_SESSION['ccms_contactform_honeypot'] = mt_rand(); 
}



if (!$is_form_post)
{
	$ccms['CSS.inline'][] = <<<EOT42

#contactForm, #contactForm tbody, #contactForm tr, #contactForm td 
{
	border: none 0 transparent;
	background-color: transparent;
}
#contactForm td
{
	vertical-align: top;
}
#contactForm tr
{
	/* make color scheme for alternating rows independent of template colors: */
	background-color: rgba(255, 255, 255, 0.1);
}
#contactForm tr.odd_row
{
	background-color: rgba(0, 0, 0, 0.1);
}
#contactForm tr.focus
{
	background-color: rgba(255, 182, 0, 0.1);
}
#contactForm input, #contactForm textarea
{
	width: 100%;
}
#contactForm input#verification
{
	width: 25%;
}

#contactForm input#email
{
	/* both are needed to hide the field from disability-assisting screen readers. */
	display: none;
	visibility: hidden;
}

EOT42;


	// TODO: JS and regular header and footer handling for pages (template engine)

	//<script type="text/javascript" charset="utf-8">
	tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/includes/js/mootools-core.js'); 
	tmpl_set_autoprio($ccms['JS.required_files'], $cfg['rootdir'] . 'lib/includes/js/mootools-more.js');


	//TODO
	//window.addEvent('domready', function(){
	$ccms['JS.done'][] = <<<EOT42

	// Do: set-up form send functionality
	function sendForm() {
		var myFx 	= new Fx.Tween($('status'), { duration:500 });
		var scroll	= new Fx.Scroll(window, { 
							wait: false, 
							duration: 500, 
							transition: Fx.Transitions.Quad.easeInOut 
						});
		var contactForm = new Request.HTML({
			url: './content/contact.php?do=send',
			method: 'post',
			update: 'response',
			data: $('contactForm'),
			onRequest: function() {
				myFx.start('opacity', 0, 1);
				$('status').set('text','Form is being processed');
				$('status').set('class','notice');
			},
			onComplete: function(response) {
				register_contactform();
				myFx.start('opacity', 1, 0.8);
				$('status').set('text','Form submitted');
				$('status').set('class','success');
				scroll.toElement('response');
			}
		}).send();
	}
	
	function highlight_current_row(input_el)
	{
		var tr = input_el.getParent('tr');
		
		$$('#contactForm tr').each(function(tr_el) {
			tr_el.removeClass('focus');
		});
		
		tr.addClass('focus');
	}
	
	function reg_focus_events(el) 
	{
		el.addEvent('click', function(e) {
			// e.stop();
			var t = $(e.target);
			highlight_current_row(t);
		});	
		el.addEvent('keydown', function(e) {
			// e.stop();
			var t = $(e.target);
			highlight_current_row(t);
		});	
		el.addEvent('blur', function(e) {
			// e.stop();
			var t = $(e.target);
			var tr = t.getParent('tr');
			tr.removeClass('focus');
		});
	}

	function register_contactform()
	{
		// Do: send contact form
		new FormValidator.Inline($('contactForm'), 
		{
			onFormValidate: function(passed, form, event)
			{
				event.stop();
				if (passed) 
					sendForm();
			}
		});
		
		$$('#contactForm tr input').each(function(el) {
			reg_focus_events(el);
		});
		$$('#contactForm tr textarea').each(function(el) {
			reg_focus_events(el);
		});
	}

	register_contactform();
	
EOT42;
//});

?>
<p>This is a simple contact form to show how you are able to code e.g. PHP code directly within the CCMS back-end. 
Feel free to modify the styling of this basic form to suit your websites' look &amp; feel. Don't forget to adjust 
the &lt;YOUR_ADDRESS_HERE&gt; line to your own e-mail address.</p>

<div id="status"><!-- spinner --></div>
<?php
} // !$is_form_post
?>
<div id="response">
<?php
if (!empty($error))
{
	echo '<div class="error message">' . nl2br($error) . '</div>';
}
else if (!empty($success))
{
	echo '<div class="success message">' . nl2br($success) . '</div>';
}
?>	
<form action="" id="contactForm" method="post" accept-charset="utf-8">	
	<fieldset>
		<legend>Contact form</legend>
		
		<table>
		<tbody>
		<tr class="odd_row">
		<td>
			<label for="name">Your name</label>
		</td>
		<td>
			<input type="text" name="name" id="name" class="text required" value="<?php echo htmlentities($sender, ENT_COMPAT, 'UTF-8'); ?>" />
		</td>
		</tr>
		<tr>
		<td>
			<label for="abcdef">Your e-mail</label>
		</td>
		<td>
			<input type="text" name="abcdef" id="from" class="text required validate-email" value="<?php echo htmlentities($emailaddress, ENT_COMPAT, 'UTF-8'); ?>" />
		</td>
		</tr>
		<tr class="odd_row">
		<td>
			<label for="subject">Subject</label>
		</td>
		<td>
			<input type="text" name="subject" id="subject" class="text required" value="<?php echo htmlentities($subject, ENT_COMPAT, 'UTF-8'); ?>" />
		</td>
		</tr>
		<tr>
		<td>
			<label for="message">Message content</label>
		</td>
		<td>
			<textarea name="message" id="message" class="minLength:10" rows="8" cols="40" style="width: 100%"><?php echo /* htmlentities( */ $message /* , ENT_NOQUOTES, 'UTF-8') */ ; ?></textarea>
		</td>
		</tr>
<?php
if (USE_CAPTCHA_AGAINST_SPAM)
{
?>		
		<tr class="odd_row">
		<td colspan="2">
			<p>And to check that this message isn't automated... Please re-enter <span style="font-weight:bold; color:#f00;"><?php echo $_SESSION['ccms_captcha']; ?></span>.</p>
		</td>
		</tr>
		<tr class="odd_row">
		<td>
			<label for="verification">Verification</label>
		</td>
		<td>
			<input type="text" name="verification" maxlength="6" value="" id="verification" class="required validate-match matchInput:'captcha_check' matchName:'captcha' text"/>
		</td>
		</tr>
<?php
}
?>
		</tbody>
		</table>

		<input type="hidden" name="captcha_check" value="<?php echo $_SESSION['ccms_captcha']; ?>" id="captcha_check" />
		
<?php
if (USE_HONEYTRAP_AGAINST_SPAM)
{
?>		
		<input type="hidden" name="darling_jar" value="<?php echo $_SESSION['ccms_contactform_honeypot']; ?>" id="darling_jar" />
		<input name="email" id="email" />
<?php
}
?>
		
		<div class="right">
			<button type="submit">Send e-mail &rarr;</button>
		</div>
	</fieldset>
</form>
</div>
