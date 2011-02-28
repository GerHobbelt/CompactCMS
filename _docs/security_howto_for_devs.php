<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Documentation - CompactCMS | Getting started and advanced tips</title>
<link rel="stylesheet" href="fmt/screen.css,layout.css,sprite.css,syntax.css" type="text/css" charset="utf-8">
<style type="text/css">
.light_up_editing {
	color: #FF0000;
}
</style>
</head><body class="container">

<h1 class="center-text">Security for websites: to do and <em>not</em> to do</h1>
<p>A website, like any other product which may be subjected to scrutiny by undesirables, is a technical challenge in two ways:</p>
<ol>
<li>
<p>first there's the functionality, i.e. the process which we want to present to the users, and</p>
</li>
<li>
<p>then there's the <em>crackability</em> of the thing, i.e. how much time and effort does it take before your website can be abused/defaced/otherwise molested into doing things the owners and users do not wish to experience?</p>
</li>
<li>
<p>Thirdly, there's also the diagnostical ability of the product, i.e. what have you got in there to assist in-depth issue analysis/diagnostics to keep maintenance a fast (and thus <em>cheap</em>) process: good diagnostic ability (or call it diagnostic empowerment) reduces maintenance costs by reducing the time and effort to validate/verify incoming issues and unambiguously pinpoint the error source. But Number 3 is a tale for another day; here we focus on Numbers 1 and 2.</p>
</li>
</ol>
<p>In my experience, getting #1 done is taking so much effort from the developers that #2 is quite often neglected. Where it gets attention, it's generally treated as a <em>fix</em> instead of an integral part of the original design. Some times a security audit is being done around the time of product release, but those results may not make it beyond the stage of the audit findings (the report) percolating down into the desk drawer of arbitrarily level management; weighting the cost against the benefits, the investment is postponed and the whole thing become a helter-kelter race of <em>quick fix</em> incident responses, no extras.</p>
<p>Hence the undesirables start with (and keep) the upper hand from the get-go. (A typical conversation: 'Q: what do we get for doing this? What's our earning point? A: you significantly reduce the risk of of getting your product damaged and publicly defaced, or otherwise taken out of your control against your wishes. This has serious implications for both your revenue stream and your public image. Q: This is a tall order you ask me to do and all you got are statistics to back you up. I want guarantees; can you give me that? After all, we haven't been hacked yet (oh, you said it was '<em>cracked</em>', right?), anyway, what's the point of doing this when nothing happened? A: It's&nbsp; a process, not an end. It's about awareness, it's not set in stone. The point is that you make it so hard for your adversaries to breach your control in any way that they keep out. They may go away and look for softer targets instead, but don't count on it. (The fact you haven't been breached just means you've been lucky so far.) Q: so you're saying you've got no guarantee to give me? A: no. Q: thank you. I'll take on that risk then when it happens. What you're telling me here is simply too costly to pay that much attention to, when the perceived revenue loss is rated like that.'<br /> Another way this can go down is this: 'Q: why should this bother us? There's nothing of great value there! A: well, there is. If it was worthless, you wouldn't want to have this product around at all, so any damage to it translates to a reduction of its worth to you. Having the damage done to you is the cost of fixing it again, which is a tough job and often turns out to demand more repairs than 'just this one'. It's like breaking &amp; entering: the door or window pane may be damaged, but the damage accrues: other valuables stolen; in terms of web services it can very quickly become of you being locked out by the world (e.g. you being blacklisted as a 'bad apple' so your emails get blocked or your website is taken off the air). Plus, when the breach has happened, things depend very much on your response time. That's not only how fast you notice the breach, but also, as importantly, how fast you plug the hole, repair the breach. And by that time you are not in control any more as some of those fixes quite probably mean assistance from external parties, who do not experience your urgency the way you are. Which translates to the cost of added agony as you have to wait for others outside your direct control to help you out.<br /> On the other hand, minding the matter from the start of inception of any product (website) and spending a bit of extra effort to rigorously reduce the number and size of possible security holes keeps the risk of these incident response costs close to nil when you put your product out in the open for others to use. Look at it as an insurance policy: instead of paying a huge bill of damages per incident, you spend a controlled amount (like an insurance fee) during design and development. The difference being you pay the largest fee during the development stages in the life cycle, while an insurance fee bleeds you for life.</p>
<h1 class="center-text">Security measures in coding: whitelist only known-good input, reject everything else</h1>
<p>First, one should realize that any interface to the world at large is a security risk area, similar to all (outer) elements of a house. (We shan't go into multi-stage security firewalls like the two-stage layout where you have locks on your doors and windows (stage 1) and a panic room (stage 2: the next stage to overcome when the previous one is breached. Here we look at the general patterns for securing any stage.)</p>
<p>This means that any bit of input coming in from the outside is suspect. You don't want to limit your regular process, so you have to strictly and unambiguously define the 'known-good' range of each bit of input. This is the upbeat for the method of whitelisting, which should be the preferred approach at every point in your system.</p>
<p>Generally speaking, there are two approaches towards securing a point of entry: blacklisting and whitelisting. Blacklisting is the act of stopping any known 'bad guy' from getting in. This implies that you have a complete set of identifications for all the 'bad guys'. Which translates to you keeping a large collection of 'mug shots' which you have check against each time. Apart of the huge burden of checking and comparing each of those shots, is the realization that any 'new' (or rather: 'yet unknown') baddy paying your door a visit is a certified breach: since the new guy only can turn out 'bad' once there's been some damage done, you always chase the facts, when you're acting alone or together does not matter: there's always at least one of you who will get the damage inflicted upon him; only after the culprit has been identified can a new 'mug shot' be produced, further delaying the blacklist updating process and hence further reducing its effectiveness in protecting your property. As such, blacklisting is not a good path to choose. From a technical perspective, however, it is the simplest and hence the first <em>fix</em> that will pop into your mind.</p>
<p>Whitelisting, on the other hand, only allows 'certified good' entries to pass through your gate; as you know your process, you can define exactly what you will regard as 'known good' inputs. And during both design and development this adage matters: were there's any doubt, err on the side of safety. If you are not sure you do handle a certain input correctly, then don't accept it on any grounds. Only when you have certified that your process can cope completely with the dubious entry, do you allow it in. That's what whitelisting is. You can do it on your own and you stay in control all the time. No need for outside cooperation either, so it's a very manageable process.</p>
<h2>Whitelisting and CompactCMS</h2>
<p>As I advocated the use of whitelisting throughout, so has this pattern been applied to CompactCMS: all 'external inputs' are scrutinized in a whitelisting filter process. In a few places this is, by 
necessity, a multi-phase process, but whenever possible we keep this a single stage process. There is a limit to the feasibility of KISS, but here is a perfect example of its usefulness: where we expect a number or a simple identifier, we only allow characters which can construct a number of identifier (digits, and letters, digits and a minus, respectively &mdash; we specifically deny _ underscores (though quite a few languages have those defined as part of their identifiers) as we know that our backend (a database) will treat an underscore as a special character: a wildcard. This depends on your brand of backend and a few other factors, but the simplest approach here is to only allow characters which do not, in any way, may be perceived as 'special' in any place. This excludes the '$' dollar, '~' tilde, '%' percent and '_' underscore from any useful 'simple filter' set. And, yes, this last bit may read as mixing the concept of blacklisting with our whitelist and wasn't that a Bad Thing To Do? Not really, because our approach is clearly one of whitelisting; we only enforce some rules on our accepted 'identifiers' (name tags) to make the filtering fast and easy for us: that is why we 'do' security from the point of inception: if this rule was not enforced from the design stage onwards, then we had to spend much more effort in our building security checkpoints: we'ld have to ensure that, say, underscores <em>done like that</em> would be okay, while other underscores would be illegal immigrants and pose a security risk. If this sounds vague and complex, that's because it is. Defining up front that you don't want any underscores in there is far easier to check and certify. Which gives us a faster, better and cheaper security filtering process for the same level of security.</p>
<p>&nbsp;</p>
<p>TBD: filterParam... etc...... + review this text; possibly split it into a 'introduction to security and why it should matter to you too' and a technical part describing how we went about doing what had to be done.</p>
<p>&nbsp;</p>
<h1>The gory details: what to do once the general patterns have been applied</h1>
<p>Here's where intelligence start to really matter. Beyond the level of the basic mugger (on the Internet, that would be your horde of 'script kiddies'), security breaches are not just a matter of twisting <em>this lock right here</em> and you're <em>in</em>. Cracking a product, and particularly web sites, becomes a process of active and passive surveillance before you can attack.</p>
<p>By the way: you may opine that your site <em>isn't that valuable</em>, but it's not always about face value: if I, the attacker, guess that there's other goodies to be had once I get through <em>your</em> fence, then your <em>front</em> is not what I am after. I simply don't care. All I need is to get <em>through</em>. It is what is <em>underneath</em>. For example, with hosted websites, one of those 'hidden treasures' is the <em>bandwidth</em>: when I <em>become root (a.k.a. 'you are <strong>pwned</strong>')</em>, I can use up <em>your entire bandwidth</em> for my own purposes. You, on the other hand, will be footing the bill at the end of the month when your hosting provider makes up the tally. Maybe your financial wizards are quite keen and note the <em>possible</em> increase in cost (when I stay within your limits, you won't be the wiser for a <em>long</em> time, though) and maybe even follow up on this, but meanwhile I've got a fibre channel (or more) to play with. You won't notice until your services are noticeably deteriorating and you are surprised by sudden lockout by some external parties, once it hurts enough. By then you have fielded a playground for undesirables for quite a while. At your peril as the bill is yours to pay.</p>
<h2>CompactCMS <code>authCode</code> sniffing</h2>
<p>This is one of those cute 'surveillance' bits I was referring to. Once I was finished with the initial security review and the consequent software source code upgrade, this one popped up on the radar. The authCode is a number (or rather: 'key') randomly generated by CompactCMS on a per-site basis to act as a <em>salt</em> for password encryption (more precisely: password <em>hashing</em>) and serves as a salt for particular authentication bits, such as watching page previews.</p>
<p>For improved security, none of the user passwords are listed literally in the database, but only their hashes are present there. These hashes suffice to authenticate a user when logging in, so our Number 1: the site functionality, is not impaired or restricted through this approach.</p>
<p>Of course we assume our users use reasonably strong pass<em>phrases</em>, but an attacker needs to take two hurdles before he knows the password of user X: first he has to achieve read access to the database user record for said user (the usual approach would be attempting some SQL injection anywhere on your site, which we try to prevent by the two-stage security filter of whitelist-filtering any input element (page requested, form field data entered, file uploaded, etc.) and rigorously ensuring all database queries pass through the MySQL::SQLValue() filtering call, which will do its best to format the query bits such that no 'escape attack', 'Unicode hack' or other (known) approach will make it through there. (I say 'known approach' here because the SQLValue() action will do its stinkin' best, but we have two components which we have no control over at this level of detail: the PHP engine (and its database 'plugin') and the database engine itself: any bug in there may be abused to create a breach, making this an overall semi-blacklisted 
approach by necessity. Your control can reach only reach so far...)</p>
<p>What we need for the password hash to crack is one or more of the following:</p>
<ul>
<li>a weak hash (MD5 has its published weaknesses, but you will see that we don't need to 'worry' about that one yet: social engineering and simple random keys used throughout the code are far weaker, as you'll see in a moment),</li>
<li>a weak passphrase (humans are known for that: try any date of birth in your family, the license plate of your car (if you are a man), the name of your spouse or child(ren), your zipcode and with that very limited set we've got an unruly high chance at getting in)</li>
<li>a weak salt (if we are using any at all)</li>
</ul>
<p>In the case of CompactCMS, a 5-digit number (authCode) is used as a salt. Which, by itself, is weak: in the current implementation (Nov/2010) it's about 16 bits of randomness, <em>tops</em>. However, we can 
cut that down to zero bits of hardship for an attacker, if he is smart enough to watch a preview page: by design, the preview pages are not session-limited, meaning you can send their preview URL to others for review of the page content, while the content of this page is <em>not</em> yet visible to the general public (i.e. is not <em> published</em> yet). The preview URL is just like it's regular URL will be, only augmented with a ?preview=&lt;authCode&gt; URL query part.</p>
<p>And that's exactly where the authCode is passed <em>verbatim</em> across the wire! Its lifetime is, by design, <em>infinite,</em> as it is generated for your site at the time of installation and all password hashes depend on its <em> stability over time</em>, for those would be <em>instantly invalidated</em> (and <em>access lost to everyone, including the site admin himself</em>) upon loss/change of the authCode <em>salt</em>.</p>
<p>Knowing the salt of your site does not make it cracked just yet, but it significantly impacts security as now the attacker just circumvented trying several thousand trials to guess a single passphrase: thanks to the known salt, he can go back to the age-old password attack method known as a dictionary attack and he has very much improved on his chances of breaching your security as now only the (also generally quite up to very weak) passphrases themselves stand in his way to gaining entrance: guess a user's passphrase using the dictionary attack is all he needs as he knows how to calculate the hash and now has your authCode salt. (Please, don't go into considering 'securing' that one through obscurity! Anyway, it's a moot point as our source code is publicly available; check the Net to find a plethora of reasons why you should not work with 'secret' a.k.a. proprietary hashes and ciphers.)</p>
<p>As security is all about the amount of effort the attacker must spend to gain entrance (and raising that level of effort to a degree where the willingness to go that distance is becoming close to zero), publishing your hashing salt like that just lower the bar by about a factor of 100K.</p>
<h3>How to fix this bugger then</h3>
<p>As we do not wish to reduce our functionality, hence do not wish to limit previews to 'during the current session only', we need to give the preview URL something that we consider 'safe enough' for that channel of access to our unpublished content, while keeping our authCode <em>salt</em> hidden from public view.</p>
<p>The consideration being that gaining root/user access to our site in any way is far more costly than viewing our unpublished content (which is correct, given that access the site and/or underlying services has a far larger impact on you and your surroundings - this does not neglect the worth of your content, but content gathered does not constitute a hostile web server takeover: the latter automatically implies the former however, so the need for protection is just that one bit more for the server) we may consider giving the URL preview a hashed authenticator instead.</p>
<p>Second, we may want to limit our preview to the current content only - as per today (Nov/2010) the CompactCMS design approaches the preview as a site-wide ability: a preview code gives access to all unpublished content.<br /> We decide that we are fine with this design decision.<br /> However, we do not wish to give the preview code an indefinite lifetime, so we can, for instance, mix in the name and timestamp of the entry page: a preview MUST then be started at the given page and will only be valid for as long as that page has the given timestamp: any update to the page invalidates access to the entire site through preview, which is an added boon to the current situation, where the preview has an infinite lifespan.</p>
<p>As we may, being a legitimate previewer, be browsing the site, each page needs to able to validate the preview code on its own: it does not know the 'entry page' name, so it cannot derive the last-changed timestamp from that and consequently cannot validate our preview code unless we help the visited page a little bit. Of course, the preview access being unauthenticated (only generally authorized), means that such page visits can, by themselves become entry points, at least until the preview authorization code expires. To make this a purely dynamic process (I don't like fixed 'time-outs') with a tolerable level of security built in, we can create a preview authorization hash from the following components:</p>
<ol>
<li>the original entry page record ID</li>
<li>the original entry page last-edit timestamp</li>
<li>the status of the original entry page published flag (thus invalidating preview for the site when the entry page is published)</li>
<li>as much other easily accessible data which makes this instance of the original entry page unique</li>
<li>our authCode, used as a <em>salt</em> for the hash</li>
</ol>
<p>For any page on the site to be able to validate such a preview hash, it needs access to the the original entry page record ID: it is not a security risk to publish this internal number while it will facilitate very fast access to the other data elements listed above. In fact, looking at the list above, the thought occurs that the easiest way to produce a reasonably safe preview hash with limited lifetime is to simply:</p>
<ol>
<li>take the record ID of the original entry page from which the preview URL was issued,</li>
<li>load its database record and simply merge all the fields of that record, which includes all the items above, except the last-edit timestamp, which can be easily obtained using the record data to fetch said timestamp for the given page file, and then</li>
<li>we add the authCode salt.</li>
</ol>
<p>This data series is then converted in a secure hash using the MD5 algorithm once again, thus producing a quite viable preview code consisting of one MD5 code and a numeric record ID, both of which are very easy to filter when they arrive as inputs later on while a preview is performed (or an attack is attempted through this mode).</p>
<h3>Postnatal notes</h3>
<p>Spreading preview codes for pages marked 'published' is ill-advised: whenever a preview authentication code is spread for a page, the lifetime of said authCode equals the the time until that page, or its attributes, are altered. We may assume that an already 'published' page is quite stable, hence implicitly produces a 
long-lived authCode. Keep in mind that an authCode is <em>site-wide</em>, so a 
long-lived authCode means <em>any</em> part of the (then hidden/unpublished) site is visible to anyone having obtained such an authCode (through any means) for a very long time!<br /> We counter this by doing two things:</p>
<p>We force the preview code to be generated for the <strong>un</strong>published page, i.e. act as if it was marked 'unpublished' at the time the page is generated. When you visit the page with such a preview code, it is automatically invalid when the page is published, but that does not matter as the page you currently access is already public, so you do not need the authCode to view it.</p>
<p>We add a 'last-edit' timestamp field to the pages table in the database and require any page update, however tiny, to also update that timestamp. (The added (small) benefit here is that we now have a quickly available 'last edited' timestamp to pass on to any web cache for the page: the 'last edit' timestamp of the HTML page itself only registers our editing actions with respect to the page content itself, not the page attributes<span class="style1">&mdash;all of which have an impact on the generated HTML output for the page, hence should 'tickle' any web cache to update to the latest state of affairs</span>!)</p>
<h1>Risk: session not strictly bound to client</h1>
<p>The current code (Nov/2010) contains the following to authenticate a running session, i.e. make sure the secured page X on the site is visited by the previously authenticated visitor herself and not some later session-stealing / replay-attacking visitor:</p>
<blockquote>
<pre>function checkAuth()
{
	$canarycage	= md5(<strong>session_id()</strong>);
	$currenthost = md5(<strong>$_SERVER['HTTP_HOST']</strong>);
	
	//if(md5(session_id())==$cage &amp;&amp; md5($_SERVER['HTTP_HOST']) == $host) {   // [i_a] bugfix
	if (!empty($_SESSION['id']) &amp;&amp; $canarycage == <strong>$_SESSION['id']</strong> 
		&amp;&amp; !empty($_SESSION['host']) &amp;&amp; $currenthost == <strong>$_SESSION['host']</strong>) 
	{
		return true;
	} 
	return false;
}

function SetAuthSafety()
{
<strong> $_SESSION['host'] = md5($_SERVER['HTTP_HOST']); $_SESSION['id'] = md5(session_id()); </strong>	
	unset($_SESSION['rc1']);
	unset($_SESSION['rc2']);
}
</pre>
</blockquote>
<p>The authenticity of the request is validated through checking two components, which have been stored in the server-side session store at the time of authentication: the active session ID and the <code> <a href="http://php.net/manual/en/reserved.variables.server.php">$_SERVER['HTTP_HOST']</a></code> value.</p>
<p>The latter is of much worth from a session security perspective as this value will be constant for the given web site.</p>
<p>Of course it is useful to check against so session IDs are at least verified to have originated from our own server, but we can do better than this! A session has a few more attributes which we can use to restrict access to authenticated entities only: when you visit a web site, you do so from a given machine. You are not expected to switch machines (or browsers!) midway during a session; if you travel through a web proxy or more (as set up by your ISP or other intermediary, e.g. the company you work at), then such machines are all configured to present a stable IP address to the outside world<span class="style1">&mdash;that means us! Hence whether you have direct access to the web server or have to go through a company proxy annex firewall or other intermediary does not matter with respect to your 'point of origin' as far as the web server is concerned. </span></p>
<p><span class="style1">However, when I'd be trying to steal or ride your session, given the code above, I can do so from any remote point of origin once I've obtained your session ID. 'Regular' CSRF attacks and such go through your own web browser, so what we are about to do is no help there, but we should restrict validity of any authenticated session as much as possible nevertheless as any such restriction limits the means available to an attacker. Hence we will add these components to the session validity check:</span></p>
<ol>
<li><code><a href="http://php.net/manual/en/reserved.variables.server.php">$_SERVER['REMOTE_ADDR']</a></code> : the point of origin (i.e. the IP address where the visitor is coming from; having a name instead of an IP (reverse DNS) does not help and only raises the cost in transfer delays)</li>
<li><code><a href="http://php.net/manual/en/reserved.variables.server.php">$_SERVER['HTTP_USER_AGENT']</a></code> : the software used to visit us (the browser software identifier): a user does not switch browser applications halfway through a running session. Are you?</li>
</ol>
<h3>Postnatal notes</h3>
<p>Turns out the CCMS lightbox module uses a Flash component to upload images (we knew that already) and, of course, that bloody bugger presents itself ever so slightly different than the browser it was invoked from! So the HTTP_USER_AGENT check had to go, or the lightbox would fail to operate. Bummer. Alas, all is not lost, we still get to check against the visitor IP address, which is a stable component, at least for the duration of the session.</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p class="large">old PHP code:</p>
<blockquote>
<pre>$btn_delete = getPOSTparam4IdOrNumber('btn_delete');
if($do=="delete" &amp;&amp; <span class="light_up_editing">$btn_delete=="dodelete"</span>) 
{
</pre>
</blockquote>
<p class="large">new PHP code:</p>
<blockquote>
<pre>$btn_delete = getPOSTparam4IdOrNumber('btn_delete');
if($do=="delete" &amp;&amp; <span class="light_up_editing">!empty($btn_delete)</span>)
{
</pre>
</blockquote>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>