<html>
<body>
<pre>
<?php
show('1 + "10.5"');                // $foo is float (11.5)
show('1 + "-1.3e3"');              // $foo is float (-1299)
show('1 + "bob-1.3e3"');           // $foo is integer (1)
show('1 + "bob3"');                // $foo is integer (1)
show('1 + "10 Small Pigs"');       // $foo is integer (11)
show('4 + "10.2 Little Piggies"'); // $foo is float (14.2)
show('"10.0 pigs " + 1');          // $foo is float (11)
show('"10.0 pigs " + 1.0');        // $foo is float (11)     
show('00042');                   // int: 34 (octal decoding!)
show('"00042"');                   // int: 34 (octal decoding!)
show('"00042" == 42');                   // int: 34 (octal decoding!)
show('"00042" == 34');                   // int: 34 (octal decoding!)
show('intval("00042")');         // int: 42
show('00099');                   // int: 0 (invalid octal number!)
show('"00099"');                   // int: 0 (invalid octal number!)
show('intval("00099")');         // int: 99
show('intval(\'00099\')');         // int: 99
?>

For more information on this conversion, see the Unix manual page for strtod(3).

To test any of the examples in this section, cut and paste the examples and insert the following line to see what's going on:

<?php
function show($foo)
{
    $rv = eval('return ' . $foo . ';');
    echo "\$foo = $foo; // \$foo is " . gettype($rv) . " ($rv)<br />\n";
}
?>

<p>link: <a href="/c/admin/includes/fancyupload/Source/FileManager.js,../../../../../../../Language/Language.en.js,Additions.js,Uploader/Fx.ProgressBar.js,Uploader/Swiff.Uploader.js,Uploader.js"> multiple js with ../ bits inside the URL</a>

<p>link: <a href="/c/admin/includes/fancyupload/dummy.js,Source/FileManager.js,Language/Language.en.js,Source/Additions.js,Source/Uploader/Fx.ProgressBar.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader.js"> same: multiple js, now without the ../ bits inside the URL</a>

<p>link: <a href="/c/admin/img/styles/base.css,layout.css,editor.css,sprite.css,last_minute_fixes.css"> /c/admin/img/styles/base.css,layout.css,editor.css,sprite.css,last_minute_fixes.css</a> -- when combiner is configured to NOT run CSS minification, 
         the comments should exhibit that the encoding is indeed UTF-8: no screwed-up names in there, 
         e.g. the 'Based on work by' comment line

<p>link: <a href="/c/lib/includes/js/mootools-core.js,mootools-more.js,common.js,mocha.js"> /c/lib/includes/js/mootools-core.js,mootools-more.js,common.js,mocha.js </a>

<p>link: <a href="/c/_install/install.css"> /c/_install/install.css </a> -- should NEVER be processed by the RewriteEngine!

<p>link: <a href="/c/admin/img/styles/sprite.php"> /c/admin/img/styles/sprite.php </a>

<p>link: <a href="/c/sitemap.xml"> /c/sitemap.xml </a> -- MUST spit out XML with application/xml mime type, NOT text/xml

<p>link: <a href="/c/lib/modules/lightbox/resources/style.css"> /c/lib/modules/lightbox/resources/style.css </a>

<p>link: <a href="/c/admin/includes/modules/translation/translation.Manage.php"> /c/admin/includes/modules/translation/translation.Manage.php </a> -- direct access to the admin translation help page

<p>link: <a href="/c/_install/"> /c/_install/ </a> -- direct access to the installer (IFF it's there)

<p> link: <a href="/c/admin/includes/tiny_mce/tiny_mce_ccms.js?cb=XXXXXXX"> /c/admin/includes/tiny_mce/tiny_mce_ccms.js?cb=XXXXXXX </a> -- see if the combiner processes the tinyMCE implicit source merge correctly + test the callback code generator at the end of the JS output

<p> link: <a href="/c/admin/includes/tiny_mce/examples/index.html"> /c/admin/includes/tiny_mce/examples/index.html </a> -- the tinyMCE examples directory for testing

<p> link: <a href="/c/admin/includes/fancyupload/Source/Uploader/Swiff.Uploader.js,Fx.ProgressBar.js,../../FancyUpload2.js,../../modLightbox.js"> /c/admin/includes/fancyupload/Source/Uploader/Swiff.Uploader.js,Fx.ProgressBar.js,../../FancyUpload2.js,../../modLightbox.js </a> -- it's about the ',../' bits in there: FF3.6 reduces those CLIENT-side, so this type of mixed URL will NEVER work on such a browser. Hence the need for the dummy.js:

<p> link <a href="/c/admin/includes/fancyupload/dummy.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader/Fx.ProgressBar.js,FancyUpload2.js,modLightbox.js"> /c/admin/includes/fancyupload/dummy.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader/Fx.ProgressBar.js,FancyUpload2.js,modLightbox.js </a> -- which is /meant/ to be exactly the same as the one above, but this one does NOT get damaged by '..'-path optimizing browsers such as FF3.6!
