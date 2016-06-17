/* global tinymce, jQuery, MakeFormatBuilder */
var MakeFormatBuilder = MakeFormatBuilder || {};

(function(tinymce, $, builder) {
	tinymce.PluginManager.add('ttfmake_format_builder', function (editor, url) {
		editor.addCommand('Make_Format_Builder', function () {
			builder.open(editor);
		});

		editor.addButton('ttfmake_format_builder', {
			icon   : 'ttfmake-format-builder',
			tooltip: 'Format Builder',
			cmd    : 'Make_Format_Builder'
		});

		editor.on('init', function () {
			$.each(builder.definitions, function (name, defs) {
				editor.formatter.register(name, defs);
			});
		});
	});
})(tinymce, jQuery, MakeFormatBuilder);