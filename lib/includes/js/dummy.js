/*
Only here to help the CCMS combiner: combine.inc.php, e.g. when processing requests like:

	<script type="text/javascript" src="../lib/includes/js/mochaui/Source/dummy.js,Core/Core.js,Core/Canvas.js,Core/Content.js,Core/Desktop.js,Controls/column/Column.js,Controls/panel/Panel.js,Controls/taskbar/Taskbar.js,Controls/window/Window.js,Controls/window/Modal.js,Core/Themes.js"></script>

because the same request written like this, won't work well with some (if not all) browsers -- tested with FF3.6:

	<script type="text/javascript" src="../lib/includes/js/mochaui/Source/Core/Core.js,Canvas.js,Content.js,Desktop.js,../Controls/column/Column.js,../Controls/panel/Panel.js,../Controls/taskbar/Taskbar.js,../Controls/window/Window.js,../Controls/window/Modal.js,Themes.js"></script>

as the ',../' in there is 'reduced' before the webserver+rewrite engine can get its hands on the URL.
*/