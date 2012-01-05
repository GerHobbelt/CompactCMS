/*
A not-so-entirely-dummy file for loading tinyMCE through the Combiner instead of using the tiny_mce_gzip method: 
the latter requires another writable cache directory (unless we alter the code) and doesn't benefit from our
merge techniques in Combiner: tiny_mce_gzip is a two-stage process (one JavaScript requests another over HTTP)
to load tinyMCE, while the Combiner can do it in one.
*/

