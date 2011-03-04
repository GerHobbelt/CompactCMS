/*
Re: [GitHub] bug fix [rgrove/jsmin-php GH-3]
				
SToto98
 to ger
	
	
Hi,
I have another issue with regexp and jsmin.
If the JS code use this kind of regexp : bv=/^\/\// then the last double slashes is seen as a comment by jsmin which strip them (and all code following) in resulted source code...

This regexp is from Jquery 1.5 (minimified version).

Thanks for your help.

Seb

https://github.com/rgrove/jsmin-php/pull/3#issuecomment-829740

*/

function test(){
	var bv=/^\/\//;
}
