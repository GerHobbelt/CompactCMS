/*
Only here to help the CCMS combiner: combine.inc.php, e.g. when processing requests like:

	<script type="text/javascript" src="../../../lib/includes/js/fancyupload/dummy.js,Source/FileManager.js,Language/Language.en.js,Source/Additions.js,Source/Uploader/Fx.ProgressBar.js,Source/Uploader/Swiff.Uploader.js,Source/Uploader.js"></script>

because the same request written like this, won't work well with some (if not all) browsers -- tested with FF3.6:

	<script type="text/javascript" src="../../../lib/includes/js/fancyupload/Source/FileManager.js,../Language/Language.en.js,Additions.js,Uploader/Fx.ProgressBar.js,Uploader/Swiff.Uploader.js,Uploader.js"></script>

as the ',../' in there is 'reduced' before the webserver+rewrite engine can get its hands on the URL.
*/