/* global tinymce, MakeDynamicStylesheet */
var MakeDynamicStylesheet = MakeDynamicStylesheet || {};

(function(tinymce, DynamicStylesheet) {
	if (DynamicStylesheet.tinymce) {
		tinymce.PluginManager.add('ttfmake_dynamic_stylesheet', function (editor, url) {
			editor.on('init', function () {
				DynamicStylesheet.tinymceInit(editor);
			});

			editor.addCommand('Make_Reset_Dynamic_Stylesheet', function () {
				DynamicStylesheet.resetStylesheet();
			});
		});
	}
})(tinymce, MakeDynamicStylesheet);