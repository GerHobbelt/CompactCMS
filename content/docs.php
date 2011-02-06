<h1>Getting started and advanced tips</h1>
<p>On this page you'll find documentation on the installation and configuration of CompactCMS.</p>
<ol>
<li><a href="#req">Requirements</a></li>
<li><a href="#quick">Quick installation</a></li>
<li><a href="#full">Full installation instructions</a></li>
<li><a href="#vars">Configuration variables</a>
<ul>
<li><a href="#stv">Standard variables</a></li>
<li><a href="#exv">Extended configuration</a></li>
<li><a href="#sev">Security variables</a></li>
<li><a href="#msv">MySQL variables</a></li>
<li><a href="#otv">Other variables</a></li>
</ul>
</li>
<li><a href="#mysql">MySQL database structure</a></li>
<li><a href="#upgrade">Upgrading</a></li>
<li><a href="#tplvar">Template variables</a></li>
</ol>
<h2>1 Requirements</h2>
<p>Before you consider to install CompactCMS on your server to manage your next or current website, it is important that you understand that CompactCMS has a few, although standard, system requirements you need to meet for the <acronym title="Content Management System">CMS</acronym> to work. If you're not sure whether you meet the requirements stated below, please refer to your hosting company.</p>
<ul>
<li>An Apache server on any operating system (Linux and Windows tested)<br /><em>PHP should not run as a CGI extension on IIS</em></li>
<li>At least PHP4</li>
<li>A MySQL database</li>
<li>The Apache mod_rewrite() module activated</li>
</ul>
<h2>2 Quick installation</h2>
<p>After downloading the package, you should follow the numbered steps below to get you started with the CompactCMS installation.</p>
<ol>
<li>Extract all files by using any compression software. Be sure to keep the folder structure intact while extracting.</li>
<li>Upload all of the extracted files to a remote location of your choosing.</li>
<li>Create a new database, or note down details from a current one to be used for your CCMS install</li>
<li>Point your web browser to the directory where you uploaded the files to. This will show you the installer.</li>
<li>After installing, be sure to remove the /_install/ directory before trying to access the backend under /admin/.</li>
</ol>
<p>That is all there is to it. The installer should guide you through the necessary steps. If anything goes wrong, refer to the next chapter for manual installation instructions. To start designing your own template, take a look at the included examples. <a href="#tplvar">Chapter seven</a> lists all of the template variables that you can use.</p>
<h2>3 Manual installation instructions</h2>
<p><strong>Step 1: extract files</strong><br /> Extract all files by using Winzip, Winrar or any other compression software kit. Be sure to keep the folder structure intact while extracting.</p>
<p><strong>Step 2: edit configuration file</strong><br /> Open the file <strong>/lib/config.inc.php</strong> and edit the commented variables. Do <strong>NOT</strong> change the $cfg['authcode'] variable (default is 12345), as this value is required to log you in using the default credentials. An elaboration on the variables used, can be found in <a href="#vars">chapter 4</a>.</p>
<p><em>Tip: if you later on run intro trouble, please make sure these settings are correct. Database name, username and password are case-sensitive.</em></p>
<p><strong>Step 3: edit .htaccess file</strong><br /> <em>If you're installing CompactCMS in the root of your public folder the .htaccess file will not need changing. You can then skip this step.</em></p>
<p>If you have installed CompactCMS in <strong>another directory</strong> than the root, you should open up the &rdquo;.htaccess&rdquo; file included within the archive. By default the root configuration is enabled. If you want to use the sub directory configuration you should set the <strong>RewriteBase</strong> variable appropriately.</p>
<div id="highlighter_221311" class="syntaxhighlighter  plain">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>01</code></td>
<td class="content"><code class="plain plain"># For an installation under root (default)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>02</code></td>
<td class="content"><code class="plain plain"># Example: www.mysite.com/index.php</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>03</code></td>
<td class="content"><code class="plain plain">RewriteBase /</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>04</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>05</code></td>
<td class="content"><code class="plain plain"># For an installation under /cms</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>06</code></td>
<td class="content"><code class="plain plain"># Example: www.mysite.com/cms/index.php</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>07</code></td>
<td class="content"><code class="plain plain"># RewriteBase /cms/</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>08</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>09</code></td>
<td class="content"><code class="plain plain"># For an installation under /test/compactcms</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>10</code></td>
<td class="content"><code class="plain plain"># Example: www.mysite.com/test/compactcms/index.php</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>11</code></td>
<td class="content"><code class="plain plain"># RewriteBase /test/compactcms/</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p><em>Also see the file itself for further details. In case you already have such a file, you should merge the two together into one file.</em></p>
<p><strong>Step 4: create database structure</strong><br /> Create a new database (use the name you specified in the configuration) and import the <a href="#mysql">structure as shown below</a> or use the structure.sql file in the included /_docs/ directory.</p>
<p>The instructions below indicate on how to import the structure.sql file.</p>
<ol>
<li>First select the <strong>import tab</strong></li>
<li>Then click <strong>&ldquo;Browse..&rdquo;</strong> and select the structure.sql file in the /_docs/ directory</li>
<li>Hit the <strong>&ldquo;Go&rdquo;</strong> button</li>
</ol>
<p><strong>Step 5: upload to server</strong><br /> Upload all files to your webserver. You'll now be able to access the backend under www.yoursite.com/admin/. Your default user credentials are username: <strong>admin</strong>, password: <strong>pass</strong>. Don't forget to change these default values through the backend. If you cannot log in, make sure that $cfg['authcode'] is set to 12345 in the /lib/config.inc.php file.</p>
<p><strong>Step 6: set permissions</strong><br /> The following files require specific chmod() actions. Use your FTP software to change these values to the ones listed below</p>
<ul>
<li>./content/ to at least 0666 (0755 might work, 0777 is necessary on some servers)</li>
<li>./content/home.php to 0666</li>
<li>./content/contact.php to 0666</li>
<li>./media/ to 0777</li>
<li>./media/albums/ to 0777</li>
<li>./media/files/ to 0777</li>
<li>./lib/includes/cache/ to 0777</li>
<li>./lib/templates/ccms.tpl.html to 0666 (or any other template file)</li>
</ul>
<p><strong>A note on file permissions:</strong><br />It is true that 0777 forms a security risk for your installation under a Linux installation. All web scripts have this risk if running without a FTP layer (having sufficient risks on its own). If your hosting provider is doing a good job though, chmod values should not pose a risk. You could consider creating all of the mentioned directories through the Apache server itself (making Apache the file owner) and only manage the contents through the CCMS backend. This will lower the requirements from 0777 to 0666. Unfortunately you will then not be able to upload modifications through FTP, as the file owner is not your FTP account, but the Apache server.</p>
<p>&nbsp;</p>
<h2>4 Configuration variables</h2>
<p>Below you'll find a list of all the variables currently used by CompactCMS. The <a href="#stv">standard variables (4.1)</a> and <a href="#msv">MySQL variables (4.4)</a> need changing for CompactCMS to work. The other variables should work out of the box and should be changed when in need of changed behavior of CompactCMS.</p>
<p>&nbsp;</p>
<h3>4.1 Standard variables</h3>
<p><strong>Variable: <em>$cfg['sitename']</em></strong><br /> This should include the name of your website. It can be used for showing a copyright line in the footer of each page and by default will show in the header tag of each webpage.</p>
<p>Examples:</p>
<div id="highlighter_745727" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'sitename'</code><code class="php plain">] = </code><code class="php string">"CompactCMS"</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'sitename'</code><code class="php plain">] = </code><code class="php string">"Soccer Fansite"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['language']</em></strong><br /> This will affect the language of the administration. Please therefore make sure that the language your selecting is available in the &rdquo;/admin/includes/languages/&rdquo; directory. This variable is furthermore used for indicating to browsers and search engines which language your website is in.</p>
<p>Examples:</p>
<div id="highlighter_71735" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'language'</code><code class="php plain">] = </code><code class="php string">"en"</code><code class="php plain">; </code><code class="php comments">//-&gt; english</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'language'</code><code class="php plain">] = </code><code class="php string">"nl"</code><code class="php plain">; </code><code class="php comments">//-&gt; dutch </code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>3</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'language'</code><code class="php plain">] = </code><code class="php string">"de"</code><code class="php plain">; </code><code class="php comments">//-&gt; german</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['rootdir']</em></strong><br /> The CCMS script needs to know where to look for its files relative to the document root. Set this variable to reflect where your installation resides. Always add a trailing slash.</p>
<p>Examples:</p>
<div id="highlighter_297923" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'rootdir'</code><code class="php plain">] = </code><code class="php string">"/"</code><code class="php plain">; </code><code class="php comments">//-&gt; For an install under root (www.yoursite.com/)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'rootdir'</code><code class="php plain">] = </code><code class="php string">"ccms/"</code><code class="php plain">; </code><code class="php comments">//-&gt; If under a sub directory (www.yoursite.com/ccms/)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>3</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'rootdir'</code><code class="php plain">] = </code><code class="php string">"tests/cms/"</code><code class="php plain">; </code><code class="php comments">//-&gt; For www.yoursite.com/tests/cms/</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['authcode']</em></strong><br /> This unique code is used to encrypt (salt) your CCMS passwords and allows you to preview pages that are unpublished. <strong>Be careful to change your authcode after install, it will make your current user credentials unusable. Choose carefully!</strong></p>
<p>Examples:</p>
<div id="highlighter_271693" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'authcode'</code><code class="php plain">] = </code><code class="php string">"12345"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'authcode'</code><code class="php plain">] = </code><code class="php string">"A1b2C"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p>&nbsp;</p>
<h3>4.2 Extended configuration</h3>
<p><strong>Variable: <em>$cfg['version']</em></strong> <em>[true|false]</em><br /> Description</p>
<p>Examples:</p>
<div id="highlighter_929732" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'version'</code><code class="php plain">] = </code><code class="php string">"true"</code><code class="php plain">; </code><code class="php comments">//-&gt; Check for new version on initial load (default)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'version'</code><code class="php plain">] = </code><code class="php string">"false"</code><code class="php plain">; </code><code class="php comments">//-&gt; Disable checking for a new version</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['iframe']</em></strong> <em>[true|false]</em><br /> By default the management of iframes is not supported. Giving your end-users the possibility to include external pages within the local website brings along risks. Most importantly it could open doors to include malicious scripts which can cause the webserver to go down. As long iframes are not needed, keep the value set to false. If iframes are needed set this value to true, but make sure you limit access to the administration. Enabling support for iframes is on your own risk.</p>
<p>Examples:</p>
<div id="highlighter_838229" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'iframe'</code><code class="php plain">] = </code><code class="php string">"false"</code><code class="php plain">; </code><code class="php comments">//-&gt; Do not support iframe management (default)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'iframe'</code><code class="php plain">] = </code><code class="php string">"true"</code><code class="php plain">; </code><code class="php comments">//-&gt; Enable support for iframe management</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong> Variable: <em>$cfg['wysiwyg']</em></strong> <em>[true|false]</em><br /> To prevent coding from being broken due to the use the <acronym title="What You See Is What You Get">WYSIWYG</acronym> editor, this editor can be disabled all together. By setting this variable to false, all editable content will be loaded in a regular textarea. Preventing the loss of coding.</p>
<p>Examples:</p>
<div id="highlighter_903893" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'wysiwyg'</code><code class="php plain">] = true; </code><code class="php comments">//-&gt; Enable the WYSIWYG editor [true/false]</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p>&nbsp;</p>
<h3>4.3 Security variables</h3>
<p><strong> Variable: <em>$cfg['protect']</em></strong></p>
<p>Examples:</p>
<div id="highlighter_31792" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'protect'</code><code class="php plain">] = true; </code><code class="php comments">//-&gt; This will require you to login.</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p>&nbsp;</p>
<h3>4.4 MySQL database variables</h3>
<p><strong>Variable: <em>$cfg['mysql_host']</em></strong><br /> Enter the host of your MySQL server here. This should be &ldquo;localhost&rdquo; in 96% of the cases.</p>
<p>Examples:</p>
<div id="highlighter_929130" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'mysql_host'</code><code class="php plain">] = </code><code class="php string">"localhost"</code><code class="php plain">; </code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'mysql_host'</code><code class="php plain">] = </code><code class="php string">"127.0.0.1"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['mysql_user']</em></strong><br /> The username for accessing your MySQL database. This is not likely to be &ldquo;root&rdquo; unless you're on dedicated or local hosting.</p>
<p>Example:</p>
<div id="highlighter_745731" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'mysql_user'</code><code class="php plain">] = </code><code class="php string">"root"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['mysql_pass']</em></strong><br /> Set this variable to your MySQL password which belong to the username specified above. Make sure you always have a password set to prevent others from accessing your database (unless on local).</p>
<p>Example:</p>
<div id="highlighter_276791" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'mysql_pass'</code><code class="php plain">] = </code><code class="php string">"compactcms"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<hr class="space" />
<p><strong>Variable: <em>$cfg['mysql_db']</em></strong><br /> The name of the database you are planning to use for CompactCMS. This could be anything. Note that you don't need a separate database for CompactCMS to work. The *.sql file included with this package only creates one table.</p>
<p>Example:</p>
<div id="highlighter_613740" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'mysql_db'</code><code class="php plain">] = </code><code class="php string">"compactcms"</code><code class="php plain">;</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p>&nbsp;</p>
<h3>4.5 Other variables</h3>
<p><strong>Variable: <em>$cfg['restrict']</em></strong><br /> If you have one or multiple files with specific <acronym title="Hypertext Preprocessor">PHP</acronym> coding, you should restrict editor access to these files. Opening either Javascript or <acronym title="Hypertext Preprocessor">PHP</acronym> coding within the online editor will cause loss of code. Specify the pages that contain special code using this variable. Access to these files will then be restricted for the administration. These files can only be modified with your favourite editor, directly using a <acronym title="File Transfer Protocol">FTP</acronym> program (download - edit - upload).</p>
<p>! Use the filenames without extension.</p>
<p>Examples:</p>
<div id="highlighter_435347" class="syntaxhighlighter  php">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>1</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'restrict'</code><code class="php plain">] = </code><code class="php keyword">array</code><code class="php plain">(</code><code class="php string">"sitemap"</code><code class="php plain">, </code><code class="php string">"phpform"</code><code class="php plain">);</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>2</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'restrict'</code><code class="php plain">] = </code><code class="php keyword">array</code><code class="php plain">(</code><code class="php string">"sitemap"</code><code class="php plain">);</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>3</code></td>
<td class="content"><code class="php variable">$cfg</code><code class="php plain">[</code><code class="php string">'restrict'</code><code class="php plain">] = </code><code class="php keyword">array</code><code class="php plain">(</code><code class="php string">"faq"</code><code class="php plain">, </code><code class="php string">"contact"</code><code class="php plain">, </code><code class="php string">"newsletter"</code><code class="php plain">);</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p><em>Tip: to see if you configured this variable right, open up the admin and check whether the edit and preview links are replaced by &ldquo;Restriced content&rdquo; for the specified pages.</em> <a id="mysql" name="mysql"></a></p>
<h2>5 MySQL database structure</h2>
<p>Below you'll find the contents of the "structure.sql" file. If you know how to use this syntax, then go ahead and copy &amp; paste the code. Otherwise use the structure.sql file included with the archive and import it into your database using PhpMyAdmin. See step 4 above on how to import the structure.sql file.</p>
<div id="highlighter_514055" class="syntaxhighlighter  sql">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>001</code></td>
<td class="content"><code class="sql comments">-- phpMyAdmin SQL Dump</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>002</code></td>
<td class="content"><code class="sql comments">-- version 3.2.0.1</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>003</code></td>
<td class="content"><code class="sql comments">-- <a href="http://www.phpmyadmin.net/">http://www.phpmyadmin.net</a></code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>004</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>005</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>006</code></td>
<td class="content"><code class="sql keyword">SET</code> <code class="sql plain">SQL_MODE=</code><code class="sql string">"NO_AUTO_VALUE_ON_ZERO"</code><code class="sql plain">;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>007</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>008</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>009</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>010</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>011</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_cfgcomment`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>012</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>013</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>014</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_cfgcomment`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>015</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_cfgcomment` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>016</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`cfgID` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>017</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`pageID` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">CHARACTER</code> <code class="sql keyword">SET</code> <code class="sql plain">latin1 </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>018</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showLocale` </code><code class="sql keyword">varchar</code><code class="sql plain">(5) </code><code class="sql keyword">CHARACTER</code> <code class="sql keyword">SET</code> <code class="sql plain">latin1 </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'eng'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>019</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showMessage` </code><code class="sql keyword">int</code><code class="sql plain">(5) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>020</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`cfgID`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>021</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci AUTO_INCREMENT=1 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>022</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>023</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>024</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>025</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>026</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_cfgnews`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>027</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>028</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>029</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_cfgnews`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>030</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_cfgnews` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>031</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`cfgID` </code><code class="sql keyword">int</code><code class="sql plain">(5) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>032</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`pageID` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>033</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showLocale` </code><code class="sql keyword">varchar</code><code class="sql plain">(5) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'eng'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>034</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showMessage` </code><code class="sql keyword">int</code><code class="sql plain">(5) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'3'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>035</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showAuthor` enum(</code><code class="sql string">'0'</code><code class="sql plain">,</code><code class="sql string">'1'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'1'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>036</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showDate` enum(</code><code class="sql string">'0'</code><code class="sql plain">,</code><code class="sql string">'1'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'1'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>037</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`showTeaser` enum(</code><code class="sql string">'0'</code><code class="sql plain">,</code><code class="sql string">'1'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'0'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>038</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`cfgID`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>039</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM&nbsp; </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci COMMENT=</code><code class="sql string">'Configuration variables for modNews'</code> <code class="sql plain">AUTO_INCREMENT=2 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>040</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>041</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>042</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>043</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>044</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_cfgpermissions`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>045</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>046</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>047</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_cfgpermissions`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>048</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_cfgpermissions` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>049</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageUsers` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'3'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage user accounts (add, modify, delete)'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>050</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageOwners` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'3'</code> <code class="sql plain">COMMENT </code><code class="sql string">'To allow to appoint certain users to a specific page'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>051</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`managePages` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'1'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage pages (add, delete)'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>052</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageMenu` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'2'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage menu preferences'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>053</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageTemplate` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'3'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage all of the available templates'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>054</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageModules` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'5'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage modules'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>055</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`managePageActivation` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'2'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage the activeness of pages'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>056</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`managePageCoding` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'3'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users set whether a page contains coding (wysiwyg vs code editor)'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>057</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageModBackup` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'3'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users delete current back-up files'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>058</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageModNews` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'2'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage news items through the news module (add, modify, delete)'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>059</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageModLightbox` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'2'</code> <code class="sql plain">COMMENT </code><code class="sql string">'From what user level on can users manage albums throught the lightbox module (add, modify, delete)'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>060</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`manageModComment` </code><code class="sql keyword">int</code><code class="sql plain">(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'2'</code> <code class="sql plain">COMMENT </code><code class="sql string">'The level of a user that is allowed to manage comments'</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>061</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>062</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>063</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>064</code></td>
<td class="content"><code class="sql comments">-- Dumping data for table `ccms_cfgpermissions`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>065</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>066</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>067</code></td>
<td class="content"><code class="sql keyword">INSERT</code> <code class="sql keyword">INTO</code> <code class="sql plain">`ccms_cfgpermissions` (`manageUsers`, `manageOwners`, `managePages`, `manageMenu`, `manageTemplate`, `manageModules`, `managePageActivation`, `managePageCoding`, `manageModBackup`, `manageModNews`, `manageModLightbox`, `manageModComment`) </code><code class="sql keyword">VALUES</code> <code class="sql plain">(3, 0, 2, 2, 4, 4, 2, 4, 3, 2, 2, 2);</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>068</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>069</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>070</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>071</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>072</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_modcomment`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>073</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>074</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>075</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_modcomment`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>076</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_modcomment` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>077</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentID` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>078</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`pageID` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>079</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentName` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>080</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentEmail` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>081</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentUrl` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">DEFAULT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>082</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentContent` text </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>083</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentRate` enum(</code><code class="sql string">'1'</code><code class="sql plain">,</code><code class="sql string">'2'</code><code class="sql plain">,</code><code class="sql string">'3'</code><code class="sql plain">,</code><code class="sql string">'4'</code><code class="sql plain">,</code><code class="sql string">'5'</code><code class="sql plain">) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>084</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentTimestamp` </code><code class="sql keyword">timestamp</code> <code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql color2">CURRENT_TIMESTAMP</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>085</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`commentHost` </code><code class="sql keyword">varchar</code><code class="sql plain">(20) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>086</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`commentID`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>087</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 COMMENT=</code><code class="sql string">'Table containing comment posts for CompactCMS guestbook mo'</code> <code class="sql plain">AUTO_INCREMENT=1 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>088</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>089</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>090</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>091</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>092</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_modnews`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>093</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>094</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>095</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_modnews`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>096</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_modnews` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>097</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`newsID` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>098</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userID` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>099</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`pageID` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>100</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`newsTitle` </code><code class="sql keyword">varchar</code><code class="sql plain">(200) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>101</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`newsTeaser` text </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>102</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`newsContent` text </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>103</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`newsModified` datetime </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>104</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`newsPublished` enum(</code><code class="sql string">'0'</code><code class="sql plain">,</code><code class="sql string">'1'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>105</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`newsID`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>106</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci AUTO_INCREMENT=1 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>107</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>108</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>109</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>110</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>111</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_modules`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>112</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>113</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>114</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_modules`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>115</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_modules` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>116</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modID` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>117</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modName` </code><code class="sql keyword">varchar</code><code class="sql plain">(200) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">COMMENT </code><code class="sql string">'File name'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>118</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modTitle` </code><code class="sql keyword">varchar</code><code class="sql plain">(200) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">COMMENT </code><code class="sql string">'Friendly name'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>119</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modLocation` text </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>120</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modVersion` </code><code class="sql keyword">decimal</code><code class="sql plain">(5,2) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>121</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modPermissionName` </code><code class="sql keyword">varchar</code><code class="sql plain">(200) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'0'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>122</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`modActive` enum(</code><code class="sql string">'0'</code><code class="sql plain">,</code><code class="sql string">'1'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>123</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`modID`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>124</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM&nbsp; </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci COMMENT=</code><code class="sql string">'Table with the installed modules, their version and activene'</code> <code class="sql plain">AUTO_INCREMENT=4 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>125</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>126</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>127</code></td>
<td class="content"><code class="sql comments">-- Dumping data for table `ccms_modules`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>128</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>129</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>130</code></td>
<td class="content"><code class="sql keyword">INSERT</code> <code class="sql keyword">INTO</code> <code class="sql plain">`ccms_modules` (`modID`, `modName`, `modTitle`, `modLocation`, `modVersion`, `modPermissionName`, `modActive`) </code><code class="sql keyword">VALUES</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>131</code></td>
<td class="content"><code class="sql plain">(00001, </code><code class="sql string">'News'</code><code class="sql plain">, </code><code class="sql string">'News'</code><code class="sql plain">, </code><code class="sql string">'./lib/modules/news/news.Manage.php'</code><code class="sql plain">, 1.00, </code><code class="sql string">'manageModNews'</code><code class="sql plain">, </code><code class="sql string">'1'</code><code class="sql plain">),</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>132</code></td>
<td class="content"><code class="sql plain">(00002, </code><code class="sql string">'Lightbox'</code><code class="sql plain">, </code><code class="sql string">'Lightbox'</code><code class="sql plain">, </code><code class="sql string">'./lib/modules/lightbox/lightbox.Manage.php'</code><code class="sql plain">, 1.00, </code><code class="sql string">'manageModLightbox'</code><code class="sql plain">, </code><code class="sql string">'1'</code><code class="sql plain">),</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>133</code></td>
<td class="content"><code class="sql plain">(00003, </code><code class="sql string">'Comment'</code><code class="sql plain">, </code><code class="sql string">'Comments'</code><code class="sql plain">, </code><code class="sql string">'./lib/modules/comment/comment.Manage.php'</code><code class="sql plain">, 1.10, </code><code class="sql string">'manageModComment'</code><code class="sql plain">, </code><code class="sql string">'1'</code><code class="sql plain">);</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>134</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>135</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>136</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>137</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>138</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_pages`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>139</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>140</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>141</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_pages`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>142</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_pages` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>143</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`page_id` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>144</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`user_ids` </code><code class="sql keyword">varchar</code><code class="sql plain">(300) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'0'</code> <code class="sql plain">COMMENT </code><code class="sql string">'Separated by ||'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>145</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`urlpage` </code><code class="sql keyword">varchar</code><code class="sql plain">(50) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>146</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`module` </code><code class="sql keyword">varchar</code><code class="sql plain">(20) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'editor'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>147</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`toplevel` tinyint(5) </code><code class="sql keyword">DEFAULT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>148</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`sublevel` tinyint(5) </code><code class="sql keyword">DEFAULT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>149</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`menu_id` </code><code class="sql keyword">int</code><code class="sql plain">(5) </code><code class="sql keyword">DEFAULT</code> <code class="sql string">'1'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>150</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`variant` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'ccms'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>151</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`pagetitle` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>152</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`subheader` </code><code class="sql keyword">varchar</code><code class="sql plain">(200) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>153</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`description` </code><code class="sql keyword">varchar</code><code class="sql plain">(250) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>154</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`keywords` </code><code class="sql keyword">varchar</code><code class="sql plain">(255) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>155</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`srcfile` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>156</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`printable` enum(</code><code class="sql string">'Y'</code><code class="sql plain">,</code><code class="sql string">'N'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'Y'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>157</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`islink` enum(</code><code class="sql string">'Y'</code><code class="sql plain">,</code><code class="sql string">'N'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'Y'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>158</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`iscoding` enum(</code><code class="sql string">'Y'</code><code class="sql plain">,</code><code class="sql string">'N'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'N'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>159</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`published` enum(</code><code class="sql string">'Y'</code><code class="sql plain">,</code><code class="sql string">'N'</code><code class="sql plain">) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'Y'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>160</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`page_id`),</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>161</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">UNIQUE</code> <code class="sql keyword">KEY</code> <code class="sql plain">`urlpage` (`urlpage`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>162</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM&nbsp; </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci ROW_FORMAT=</code><code class="sql keyword">DYNAMIC</code> <code class="sql plain">COMMENT=</code><code class="sql string">'Table with details for included pages'</code> <code class="sql plain">AUTO_INCREMENT=3 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>163</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>164</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>165</code></td>
<td class="content"><code class="sql comments">-- Dumping data for table `ccms_pages`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>166</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>167</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>168</code></td>
<td class="content"><code class="sql keyword">INSERT</code> <code class="sql keyword">INTO</code> <code class="sql plain">`ccms_pages` (`page_id`, `user_ids`, `urlpage`, `module`, `toplevel`, `sublevel`, `menu_id`, `variant`, `pagetitle`, `subheader`, `description`, `keywords`, `srcfile`, `printable`, `islink`, `iscoding`, `published`) </code><code class="sql keyword">VALUES</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>169</code></td>
<td class="content"><code class="sql plain">(00001, </code><code class="sql string">'0'</code><code class="sql plain">, </code><code class="sql string">'home'</code><code class="sql plain">, </code><code class="sql string">'editor'</code><code class="sql plain">, 1, 0, 1, </code><code class="sql string">'ccms'</code><code class="sql plain">, </code><code class="sql string">'Home'</code><code class="sql plain">, </code><code class="sql string">'The CompactCMS demo homepage'</code><code class="sql plain">, </code><code class="sql string">'The CompactCMS demo homepage'</code><code class="sql plain">, </code><code class="sql string">'compactcms, light-weight cms'</code><code class="sql plain">, </code><code class="sql string">'home.php'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">, </code><code class="sql string">'N'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">),</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>170</code></td>
<td class="content"><code class="sql plain">(00002, </code><code class="sql string">'0'</code><code class="sql plain">, </code><code class="sql string">'contact'</code><code class="sql plain">, </code><code class="sql string">'editor'</code><code class="sql plain">, 2, 0, 1, </code><code class="sql string">'sweatbee'</code><code class="sql plain">, </code><code class="sql string">'Contact form'</code><code class="sql plain">, </code><code class="sql string">'A basic contact form using Ajax'</code><code class="sql plain">, </code><code class="sql string">'This is an example of a basic contact form based using Ajax'</code><code class="sql plain">, </code><code class="sql string">'compactcms, light-weight cms'</code><code class="sql plain">, </code><code class="sql string">'contact.php'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">, </code><code class="sql string">'Y'</code><code class="sql plain">);</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>171</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>172</code></td>
<td class="content"><code class="sql comments">-- --------------------------------------------------------</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>173</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>174</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>175</code></td>
<td class="content"><code class="sql comments">-- Table structure for table `ccms_users`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>176</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>177</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>178</code></td>
<td class="content"><code class="sql keyword">DROP</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF EXISTS `ccms_users`;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>179</code></td>
<td class="content"><code class="sql keyword">CREATE</code> <code class="sql keyword">TABLE</code> <code class="sql plain">IF </code><code class="sql color1">NOT</code> <code class="sql plain">EXISTS `ccms_users` (</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>180</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userID` </code><code class="sql keyword">int</code><code class="sql plain">(5) unsigned zerofill </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql plain">AUTO_INCREMENT,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>181</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userName` </code><code class="sql keyword">varchar</code><code class="sql plain">(50) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>182</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userPass` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>183</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userFirst` </code><code class="sql keyword">varchar</code><code class="sql plain">(50) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>184</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userLast` </code><code class="sql keyword">varchar</code><code class="sql plain">(25) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>185</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userEmail` </code><code class="sql keyword">varchar</code><code class="sql plain">(75) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>186</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userActive` tinyint(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>187</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userLevel` tinyint(1) </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>188</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userToken` </code><code class="sql keyword">varchar</code><code class="sql plain">(100) </code><code class="sql keyword">COLLATE</code> <code class="sql plain">utf8_unicode_ci </code><code class="sql color1">NOT</code> <code class="sql color1">NULL</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>189</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userLastlog` </code><code class="sql keyword">timestamp</code> <code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql string">'0000-00-00 00:00:00'</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>190</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql plain">`userTimestamp` </code><code class="sql keyword">timestamp</code> <code class="sql color1">NOT</code> <code class="sql color1">NULL</code> <code class="sql keyword">DEFAULT</code> <code class="sql color2">CURRENT_TIMESTAMP</code> <code class="sql keyword">ON</code> <code class="sql keyword">UPDATE</code> <code class="sql color2">CURRENT_TIMESTAMP</code><code class="sql plain">,</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>191</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">PRIMARY</code> <code class="sql keyword">KEY</code> <code class="sql plain">(`userID`),</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>192</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;</code><code class="sql keyword">UNIQUE</code> <code class="sql keyword">KEY</code> <code class="sql plain">`userName` (`userName`)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>193</code></td>
<td class="content"><code class="sql plain">) ENGINE=MyISAM&nbsp; </code><code class="sql keyword">DEFAULT</code> <code class="sql plain">CHARSET=utf8 </code><code class="sql keyword">COLLATE</code><code class="sql plain">=utf8_unicode_ci COMMENT=</code><code class="sql string">'Table with users for CompactCMS administration'</code> <code class="sql plain">AUTO_INCREMENT=2 ;</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>194</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>195</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>196</code></td>
<td class="content"><code class="sql comments">-- Dumping data for table `ccms_users`</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>197</code></td>
<td class="content"><code class="sql comments">--</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>198</code></td>
<td class="content">&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>199</code></td>
<td class="content"><code class="sql keyword">INSERT</code> <code class="sql keyword">INTO</code> <code class="sql plain">`ccms_users` (`userID`, `userName`, `userPass`, `userFirst`, `userLast`, `userEmail`, `userActive`, `userLevel`, `userToken`, `userLastlog`, `userTimestamp`) </code><code class="sql keyword">VALUES</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>200</code></td>
<td class="content"><code class="sql plain">(00001, </code><code class="sql string">'admin'</code><code class="sql plain">, </code><code class="sql string">'52dcb810931e20f7aa2f49b3510d3805'</code><code class="sql plain">, </code><code class="sql string">'Xander'</code><code class="sql plain">, </code><code class="sql string">'G.'</code><code class="sql plain">, </code><code class="sql string">'xander@compactcms.nl'</code><code class="sql plain">, 1, 4, </code><code class="sql string">'5168774687486'</code><code class="sql plain">, </code><code class="sql string">'2010-09-01 06:00:00'</code><code class="sql plain">, </code><code class="sql string">'2010-09-01 09:00:00'</code><code class="sql plain">);</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
<p>&nbsp;</p>
<h2>6 Upgrading instructions</h2>
<p>You are encouraged to do a clean install if upgrading to 1.4.1. The file structure has significantly changed.</p>
<p>Make sure you back-up your /content/ and media directories, your templates and database tables. You can - after doing a clean install - restore these manually. In doing so, please make sure that:</p>
<ul>
<li>You restore your database <em>data</em> only. Keep the latest <em>structure</em> in tact.</li>
<li>The media files have a default fixed location. Restore to the /media/ folder in your installation root.</li>
<li>You should use the new {%%}rootdir%} directive in your templates to point to images, JS and CSS.</li>
<li>CCMS assumes your homepage contents to be stored in /content/home.php.</li>
</ul>
<p>I apologize for making upgrading to 1.4.1 relatively hard. If unsure, consider not upgrading at all. Otherwise, be sure to keep a back-up of your current pages, media, templates and database data while trying to manually upgrade to 1.4.1.</p>
<p>&nbsp;</p>
<h2>7 Template variables</h2>
<p>The default templates give you a working example of the various variables that can be used within your template to show content from your CompactCMS back-end. For example when you request the file "contact.html" all of the variables below will be filled with the appropriate content. The variable {%%}urlpage%} becomes "contact" and the {%%}pagetitle%} could become something like "Contact us now!" depending what value you have entered in the back-end.</p>
<p>Please refer to the example templates for a demonstration of how these variables are used.</p>
<div id="highlighter_850166" class="syntaxhighlighter  plain">
<div class="bar">
<div class="toolbar"><a class="item viewSource" style="width: 16px; height: 16px;" title="view source" href="#viewSource">view source</a>
<div class="item copyToClipboard">&nbsp;</div>
<a class="item printSource" style="width: 16px; height: 16px;" title="print" href="#printSource">print</a><a class="item about" style="width: 16px; height: 16px;" title="?" href="#about">?</a></div>
</div>
<div class="lines">
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>01</code></td>
<td class="content"><code class="plain plain">&gt;&gt; General variables</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>02</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">urlpage%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | The current filename that is being shown</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>03</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">rootdir%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | The directory your CCMS is installed under, relative to the root (for external files)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>04</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">title%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Use this tag within you &lt;title&gt;&lt;/title&gt; tag for optimal SEO</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>05</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">pagetitle%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Displays the current pagetitle (good for a &lt;h1&gt; tag)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>06</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">subheader%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | A short slogan, descriptive text for the current page (&lt;h2&gt;)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>07</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">sitename%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Use this anywhere you want to show your sitename (e.g.: copyright)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>08</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">desc%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | An "extensive" description of the current page (use as meta description)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>09</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">keywords%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Keywords (tags) for the current page as specified in the back-end (use as meta keywords)</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>10</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">breadcrumb%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Show the breadcrumb/pathway to the current file</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>11</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">printable%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Use as: </code>{%%}<code class="plain plain">IF printable (eq Y)%}&lt;a href="print/</code>{%%}<code class="plain plain">urlpage%}.html"&gt;Print&lt;/a&gt;</code>{%%}<code class="plain plain">/IF printable%}</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>12</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>13</code></td>
<td class="content"><code class="plain plain">&gt;&gt; Menu items</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>14</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">mainmenu%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Prints an ordered list (&lt;ul&gt;) with all current published files in specified menu</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>15</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">leftmenu%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | ''</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>16</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">rightmenu%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | '' </code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>17</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">footermenu%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | ''</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>18</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">extramenu%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | ''</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>19</code></td>
<td class="content"><code class="spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>&nbsp;</td>
</tr>
</tbody>
</table>
</div>
<div class="line alt2">
<table>
<tbody>
<tr>
<td class="number"><code>20</code></td>
<td class="content"><code class="plain plain">&gt;&gt; Content tag</code></td>
</tr>
</tbody>
</table>
</div>
<div class="line alt1">
<table>
<tbody>
<tr>
<td class="number"><code>21</code></td>
<td class="content"><code class="plain plain">- </code>{%%}<code class="plain plain">content%}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | The content from the current file that is being requested</code></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>