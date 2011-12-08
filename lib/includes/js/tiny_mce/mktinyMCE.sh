#! /bin/bash

rm -rf tmp
ant clean
ant build_full
ant moxiedoc
ant release

cp tmp/tinymce/jscripts/tiny_mce/tiny_mce.js                jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_src.js            jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_full.js           jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_jquery.js         jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_jquery_src.js     jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_prototype.js      jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_prototype_src.js  jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/jquery.tinymce.js          jscripts/tiny_mce/
cp tmp/tinymce/jscripts/tiny_mce/tiny_mce_popup.js          jscripts/tiny_mce/

# rm -rf tmp/



