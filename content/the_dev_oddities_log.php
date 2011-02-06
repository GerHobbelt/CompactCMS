<h1 class="center-text">Bloody Hell, I should keep a log of this <em>shit</em>!</h1>
<p>So I do now. It's simply way too, ah, odd, what you come across when testing a CMS on multiple browsers while doing development. Currently (Q4 2010) I'm working on a nice little nifty CMS (CompactCMS) and doing the things I do, I've had already quite a surprises this last month (after I went from plugging security gaps to actively turning the bugger into something I would truly like to present to folks who want 'a website').</p>
<p>But this is not about that, this is about surprises during development, so without further ado, here's about the creepy things I ran into.</p>
<h1 class="center-text">WTF #x+1 : form <code>&lt;input type="submit"&gt;</code> buttons in IE7 don't send the 'value' but their inner HTML instead</h1>
<p>Take the CCMS backup admin page. It didn't work in IE7, but did quite well in IE8 and FF3. So after another hour of agony I stopped blaming, once again, my JavaScript skills and browser oddities there and instead did the rational thing when a JavaScript mootools spinner is not showing up, but the server says the POST was done: plonk this bit into the receiving page to see what goes wrong in the backend, that being the important part of the failure collection:</p>
<blockquote>
<pre>&lt;?php
if (1)  // &lt;-- Just my trick to quickly turn a whole chunk of code on or off.
        //     Did my 'C' roots (#if 0 ... #endif) show through? <span class="ss_sprite ss_emoticon_evilgrin">&nbsp;</span>
{
	global $ccms;
	global $cfg;

	echo '&lt;h1&gt;My Code&lt;/h1&gt;';
	echo "&lt;pre&gt;\nbtn_backup = $btn_backup\ndo = $do\nbtn_delete = $btn_delete\n";
	echo "&lt;/pre&gt;";
	echo '&lt;h1&gt;$_SERVER&lt;/h1&gt;';
	echo "&lt;pre&gt;";
	var_dump($_SERVER);
	echo "&lt;/pre&gt;";
	echo '&lt;h1&gt;$_ENV&lt;/h1&gt;';
	echo "&lt;pre&gt;";
	var_dump($_ENV);
	echo "&lt;/pre&gt;";
	echo '&lt;h1&gt;$_SESSION&lt;/h1&gt;';
	echo "&lt;pre&gt;";
	var_dump($_SESSION);
	echo "&lt;/pre&gt;";
	echo '&lt;h1&gt;$_POST&lt;/h1&gt;';
	echo "&lt;pre&gt;";
	var_dump($_POST);
	echo "&lt;/pre&gt;";
	echo '&lt;h1&gt;$_GET&lt;/h1&gt;';
	echo "&lt;pre&gt;";
	var_dump($_GET);
	echo "&lt;/pre&gt;";
	echo '&lt;h1&gt;$cfg&lt;/h1&gt;';
	echo "&lt;pre&gt;";
	var_dump($cfg);
	echo "&lt;/pre&gt;";
}
?&gt;</pre>
</blockquote>
<p>and see what the top bit gets me under IE8:</p>
<blockquote>
<h1>My Code</h1>
<pre>btn_backup = 
do = delete
btn_delete = dodelete
</pre>
</blockquote>
<p>while under IE7 it says:</p>
<blockquote>
<h1>My Code</h1>
<pre>btn_backup = _SPAN_class_ss_sprite_ss_package_add_Create_SPAN_
do = backup
btn_delete = 
</pre>
</blockquote>
<p>WTF?</p>
<p>Well, see the relevant raw entries then:</p>
<p class="large">IE8</p>
<blockquote>
<h1>$_POST</h1>
<pre>array(1) {
  ["btn_delete"]=&gt;
  string(8) "dodelete"
}
</pre>
<h1>$_GET</h1>
<pre>array(1) {
  ["do"]=&gt;
  string(6) "delete"
}
</pre>
</blockquote>
<p class="large">IE7</p>
<blockquote>
<h1>$_POST</h1>
<pre>array(1) {
  ["btn_backup"]=&gt;
  string(53) "<span class="ss_sprite ss_package_add">Create!</span>"
}
</pre>
<h1>$_GET</h1>
<pre>array(1) {
  ["do"]=&gt;
  string(6) "backup"
}
</pre>
</blockquote>
<p>Note the cute inclusion of that sprite in there, by the way. The button is sending the entire <code>innerHTML</code> across instead of the <code>value</code>! WTF again.</p>
<p>The PHP form code doesn't look suspicious:</p>
<blockquote>
<pre>?&gt;
&lt;div class="right"&gt;
&lt;button type="submit" 
        onclick="return confirmation_delete();" 
        name="btn_delete" 
        <strong>value="dodelete"</strong>&gt;
  <strong>&lt;span class="ss_sprite_16 ss_package_delete"&gt;&amp;#160;&lt;/span&gt;&lt;?php echo $ccms['lang']['backend']['delete'];?&gt;</strong>
&lt;/button&gt;
&lt;a class="button" href="../../../../IE_sink.php" onClick="confirmation();" title="&lt;?php echo $ccms['lang']['editor']['cancelbtn']; ?&gt;"&gt;
  &lt;span class="ss_sprite_16 ss_cross"&gt;&amp;#160;&lt;/span&gt;&lt;?php echo $ccms['lang']['editor']['cancelbtn']; ?&gt;
&lt;/a&gt;
&lt;/div&gt;
&lt;?php 
</pre>
</blockquote>
<p>Cool with me. (The <code>confirmation_delete()</code> JavaScript call would ask the obnoxious 'are you sure?' question and then return <dfn>true</dfn> or <dfn>false</dfn>, so no worries there.</p>
<p>In fact, the way out here is allowing everything in our backend like this (we do validate the session and counter hacking attempts through the session-based <code>authCheck()</code> anyway, so we're allowed a little flexibility here<span class="style1">&mdash;I don't have to <em>like</em> it though</span>):</p>
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
<p>And now apply that a zillion times to all the other backend bits which rely on the button sending the <code>value</code> property... <em>I'll blow that for a dollar!</em></p>
<h1>Bingo! The IE6 background-color versus float:left / float:right disappearance trick!</h1>
<p>Got another occurrence of this crappy behaviour in the backup management module: the &lt;h2&gt; title is completely gone, while the text of the warning message shows up, but the background does not:</p>
<p>relevant CSS:</p>
<pre>&nbsp;</pre>
<p>HTML</p>
<pre id="line1">&lt;/<span class="end-tag">head</span>&gt;&lt;<span class="start-tag">body</span>&gt;
&lt;<span class="start-tag">div</span><span class="attribute-name"> id</span>=<span class="attribute-value">"backup-module" </span><span class="attribute-name">class</span>=<span class="attribute-value">"module"</span>&gt;
	&lt;<span class="start-tag">div</span><span class="attribute-name"> class</span>=<span class="attribute-value">"span-8 colborder"</span>&gt;
		&lt;<span class="start-tag">h2</span>&gt;Create new back-up&lt;/<span class="end-tag">h2</span>&gt;
		&lt;<span class="start-tag">p</span>&gt;To prevent possible loss of data due to whatever external event, it's wise to create back-ups of your files regularly.&lt;/<span class="end-tag">p</span>&gt;
		&lt;<span class="start-tag">form</span><span class="attribute-name"> id</span>=<span class="attribute-value">"create-arch" </span><span class="attribute-name">action</span>=<span class="attribute-value">"/c/admin/includes/modules/backup-restore/backend.php?do=backup" </span><span class="attribute-name">method</span>=<span class="attribute-value">"post" </span><span class="attribute-name">accept-charset</span>=<span class="attribute-value">"utf-8" </span><span class="light_up_editing">class="clearfix"</span>&gt;
			&lt;<span class="start-tag">button</span><span class="attribute-name"> type</span>=<span class="attribute-value">"submit" </span><span class="attribute-name">name</span>=<span class="attribute-value">"btn_backup" </span><span class="attribute-name">value</span>=<span class="attribute-value">"dobackup"</span>&gt;&lt;<span class="start-tag">span</span><span class="attribute-name"> class</span>=<span class="attribute-value">"ss_sprite ss_package_add"</span>&gt;Create!&lt;/<span class="end-tag">span</span>&gt;&lt;/<span class="end-tag">button</span>&gt;
		&lt;/<span class="end-tag">form</span>&gt;
		&lt;<span class="start-tag">div</span><span class="attribute-name"> class</span>=<span class="attribute-value">"warning error left-text </span><span class="light_up_editing">clear</span><span class="attribute-value">"</span>&gt;
			&lt;<span class="start-tag">h2</span>&gt;Warning&lt;/<span class="end-tag">h2</span>&gt;
			&lt;<span class="start-tag">p</span><span class="attribute-name"> class</span>=<span class="attribute-value">"left"</span>&gt;Please be aware that your &lt;<span class="start-tag">dfn</span>&gt;lightbox&lt;/<span class="end-tag">dfn</span>&gt; albums' images are &lt;<span class="start-tag">strong</span>&gt;not&lt;/<span class="end-tag">strong</span>&gt; backed up! &lt;/<span class="end-tag">p</span>&gt;
			&lt;<span class="start-tag">p</span><span class="attribute-name"> class</span>=<span class="attribute-value">"left"</span>&gt;The album descriptions &lt;<span class="start-tag">strong</span>&gt;are&lt;/<span class="end-tag">strong</span>&gt;, but the images themselves and their thumbnails are &lt;<span class="start-tag">strong</span>&gt;not included in these backups&lt;/<span class="end-tag">strong</span>&gt;. &lt;/<span class="end-tag">p</span>&gt;
			&lt;<span class="start-tag">p</span><span class="attribute-name"> class</span>=<span class="attribute-value">"left"</span>&gt;If you want backups of those, then you will need to confer with your site administrator about an additional backup system to help you backup and restore these (possibly large) file collections.&lt;/<span class="end-tag">p</span>&gt;
		&lt;/<span class="end-tag">div</span>&gt;
	&lt;/<span class="end-tag">div</span>&gt;
	&lt;<span class="start-tag">div</span><span class="attribute-name"> class</span>=<span class="attribute-value">"span-16 last"</span>&gt;
		&lt;<span class="start-tag">h2</span>&gt;Available back-ups&lt;/<span class="end-tag">h2</span>&gt;
		...&nbsp;</pre>
<p>Turns out the info at <a href="http://www.dracos.co.uk/code/ie6-css-bug/"> http://www.dracos.co.uk/code/ie6-css-bug/</a> is right, but note that you shouldn't limit yourself to tweaking just the parent to make it have hasLayout; you may also need to tweak the grandparent, like I had to do.</p>
<p>The thing that made me try that was the fact that running a backup did also move that warning message into the proper place, the difference being another added &lt;div&gt; at the top where the feedback message appears.</p>
<h1>JavaScript : why your onsubmit / onclick actions don't work like they should</h1>
<p>Found out the hard way<span class="style1">&mdash;the only way when I once again succumb to the prevalent 'common sense' out there and forego reading the reference manual front to back; in this case the ECMA spec plus a few other</span> choice bits. (See also the the_goto_guy.js script for code and further reading):</p>
<p>IE6 in strict mode<span class="style1">&mdash;</span>which is the default setting on a clean install, so you'll find this on otherwise 'pristine', yet old, machines<span class="style1">&mdash;is not the only one who performs a 'double action' for HTML / JS like this (it seems to work like this on all browsers I tested up to now: latest Chrome, Opera, FireFox and the various MSIE's: 6, 7 and 8):</span></p>
<p class="large">JavaScript:</p>
<pre>&lt;script type="text/javascript"&gt;
function confirmation()
{
	var answer=confirm('&lt;?php echo $ccms['lang']['editor']['confirmclose']; ?&gt;');
	if(answer)
	{
		try
		{
			parent.MochaUI.closeWindow(parent.$('sys-usr_ccms'));
		}
		catch(e)
		{
		}
	}
	else
	{
		<strong>return false;</strong>
	}
}
&lt;/script&gt;	</pre>
<p class="large">HTML</p>
<pre>&lt;a class="button" <strong>href="../index.php" onClick="confirmation();"</strong> title="..."&gt;Going down, Mr. Tyler?&lt;/a&gt;</pre>
<p>which means when such a 'page' is opened in a separate tab instead (heck, I can right-click and 'Open in a new tab', can't I?) and hence the mochaUI parent will not be there and the resulting fault is caught in the try/catch, things may look good, but <strong><em>notice that this function does not return a value explicitly for every execution path</em></strong>: not when the mochaUI is loaded, nor when it isn't.</p>
<h2>Now you <em>may</em> think things are hunky dory, but they are <em>not</em></h2>
<p>It's just your tests having missed this one, because what actually happens is that the function returns a '<code>undefined</code>' as an <em>implied return value</em> and the browser picks this value... <strong>no, wait! not even close!</strong> Because you've been stacking coding sloppers like a Saturday night high-roller is stacking chips in the casino: as the onSubmit / onClick action code itself does not return the value returned by the function does the browser see an 'undefined' as result <em>all the time</em>. And what was <a href="http://www.mapbender.org/JavaScript_pitfalls:_null,_false,_undefined,_NaN"> that JavaScript pitfall</a> again? Yeah, '<code>undefined</code>' as one of the == equality comparison arguments will <em>always</em> produce a <code>false</code> as an evaluation answer, no matter what you put on the other side. And that is apparently what a lot of browsers do internally after <strong>having run the onsubmit/onclick action: no return means </strong> <em><strong>yes</strong></em>. So the href=... / action=... part of that link or button / form will be executed as well. With a bit of 'luck' you did this to one of your forms and now you end up with anything ranging from 'odd behaviour' to double entries in your database: two records created for the price of one click.</p>
<p>Which puts a whole 'nuther meaning to 'two for one'.</p>
<p>The corrected code:</p>
<p class="large">JavaScript:</p>
<pre>&lt;script type="text/javascript"&gt;
function confirmation()
{
	var answer=confirm('&lt;?php echo $ccms['lang']['editor']['confirmclose']; ?&gt;');
	if(answer)
	{
		try
		{
			return <span class="light_up_editing">(true !== </span>parent.MochaUI.closeWindow(parent.$('sys-usr_ccms'))<span class="light_up_editing">)</span>;<span class="light_up_editing"> // when it fails, mochaUI doesn't always seem to return 'false' either... </span>		}
		catch(e)
		{
		}
<span class="light_up_editing"> // answer == true, so we're going to 'fall back' to the coded href= action here... </span>	}
<span class="light_up_editing"> <strong>return answer;</strong> </span>}
&lt;/script&gt;	</pre>
<p class="large">HTML</p>
<pre>&lt;a class="button" <strong>href="../index.php" onClick="<span class="light_up_editing">return </span>confirmation();"</strong> title="..."&gt;Going down, Mr. Tyler?&lt;/a&gt;</pre>
<p><strong>The Moral of this Story? </strong>Sloppy coding, like Rock &amp; Roll, will never die.</p>
<p class="small quiet">But then it's late at night and I'm feeling sarcastic.</p>
<p>By the way: an alternative 'hack' that may be viable in particular circumstances, e.g. when coding AJAX driven &lt;form&gt;s and not wanting to be bothered by some line of code crashing the JavaScript interpreter and leaving your submit click in unfaithful hands, is to do away with the href= / action= attribute entirely. Now the browser won't know what to do when your JavaScript doesn't deliver like intended:</p>
<p class="large">HTML</p>
<pre>&lt;a class="button" <span class="style2">href="../index.php"</span> <strong>onClick="<span>return </span>confirmation();"</strong> title="..."&gt;Going down, Mr. Tyler?&lt;/a&gt;
...or...
&lt;form <span class="style2">action="Process.php"</span> method="post" accept-charset="utf-8" <strong>onsubmit="return </strong><strong>refresh_adminmain();"</strong>&gt;...&lt;/form&gt;</pre>
<p><strong>i.e.</strong></p>
<pre>&lt;a class="button" <strong> onClick="<span>return </span>confirmation();"</strong> title="..."&gt;Going down, Mr. Tyler?&lt;/a&gt;
...or...
&lt;form  method="post" accept-charset="utf-8" <strong>onsubmit="return </strong><strong>refresh_adminmain();"</strong>&gt;...&lt;/form&gt;</pre>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>