/*
Only here to help the CCMS combiner: combine.inc.php, e.g. when processing requests like:

	<script type="text/javascript" src="../../../lib/includes/js/mootools-filemanager/dummy.js,Source/FileManager.js,Language/Language.en.js,Source/Uploader/Fx.ProgressBar.js,Source/Uploader/Swiff.Uploader.js,etc.etc.etc."></script>

because the same request written like this, won't work well with some (if not all) browsers -- tested with FF3.6:

	<script type="text/javascript" src="../../../lib/includes/js/mootools-filemanager/Source/FileManager.js,../Language/Language.en.js,Uploader/Fx.ProgressBar.js,Uploader/Swiff.Uploader.js,etc.etc.etc."></script>

as the ',../' in there is 'reduced' before the webserver+rewrite engine can get its hands on the URL.
*/