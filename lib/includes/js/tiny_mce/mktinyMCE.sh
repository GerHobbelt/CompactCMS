#! /bin/bash

rm -rf tmp
ant clean
ant build_full
ant moxiedoc
ant release

cp tmp/tinymce/tiny_mce.js .
cp tmp/tinymce/tiny_mce_src.js .
cp tmp/tinymce/tiny_mce_full.js .
cp tmp/tinymce/tiny_mce_jquery.js .
cp tmp/tinymce/tiny_mce_jquery_src.js .
cp tmp/tinymce/tiny_mce_prototype.js .
cp tmp/tinymce/tiny_mce_prototype_src.js .
cp tmp/tinymce/jquery.tinymce.js .
cp tmp/tinymce/tiny_mce_popup.js .

rm -rf tmp/



