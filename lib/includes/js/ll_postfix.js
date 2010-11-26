/*
There's some serious issues with load order when loading multiple JavaScript files and depending on this 'load order' IN ANY WAY in either 'page internal scripts' or other JavaScript external files, which are loaded sepearately.
(After all, it's not all the time that you can dump all JS loads together in one flattened external <script>...)

So what we do here is provide is little and quite rude 'service' which is to expect the 'internal script' already to have been executed (or at least parsed) and invoke a
predefined function made available in there:

When we use the 'lazy loader' for ALL the JavaScript stuff we can guarantee load order and make sure the 'page internal script bits' can provide us with those much needed special configuration bits.

The way to accomplish this is by having all external JS scripts 'lazy loaded' and, where needed, have them invoke functions and stuff defined in the page itself.
*/

