/* global jQuery, MakeIconPicker */
var MakeIconPicker = MakeIconPicker || {};

(function($, picker) {
	var iconWindow, iconInsert, iconRemove, iconValue, iconUnicode, iconStyle;

	picker = $.extend(picker, {
		/**
		 * Stores the callback to use when inserting the icon.
		 *
		 * @since 1.4.1.
		 */
		callback: {},

		/**
		 * Stores the element representing the currently chosen icon in the picker.
		 *
		 * @since 1.4.1.
		 */
		el: {},

		/**
		 * Stores the icon data.
		 *
		 * @since 1.7.0.
		 */
		icons: {},
		iconCategories: {},

		/**
		 * Load the JSON data for all the icons.
		 *
		 * @since 1.7.0.
		 */
		loadIcons: function() {
			if ($.isEmptyObject(picker.icons)) {
				$.getJSON(picker.sources.fontawesome, function(data) {
					picker.icons = data;
				});
			}

			if ($.isEmptyObject(picker.iconCategories)) {
				$.getJSON(picker.sources.fontawesomecats, function (data) {
					picker.iconCategories = data;
				});
			}
		},

		/**
		 * Opens a TinyMCE modal window, and initializes all of the Icon Picker
		 * functionality.
		 *
		 * @since 1.4.1.
		 *
		 * @param editor
		 * @param callback
		 * @param value
		 */
		open: function(editor, callback, value) {
			// Store the callback for later.
			this.callback = callback;

			// Check for an existing value
			var currentValue = ( 'undefined' !== typeof value ) ? value : '';

			// Open the window.
			iconWindow = editor.windowManager.open( {
				title: 'Choose an icon',
				id: 'ttfmake-icon-picker',
				autoScroll: true,
				width: 420,
				height: 500,
				items: [
					{
						type: 'textbox',
						name: 'chosenIcon',
						hidden: true,
						value: currentValue,
						onPostRender: function() {
							// Store this control for later use.
							iconValue = this;
						}
					},
					{
						type: 'textbox',
						name: 'chosenIconUnicode',
						hidden: true,
						value: '',
						onPostRender: function() {
							// Store this control for later use.
							iconUnicode = this;
						}
					},
					{
						type: 'textbox',
						name: 'chosenIconStyle',
						hidden: true,
						value: '',
						onPostRender: function() {
							iconStyle = this;
						}
					},
					{
						type: 'container',
						layout: 'flex',
						align: 'stretch',
						direction: 'column',
						padding: 20,
						items: picker.getIconCategories()
					}
				],
				buttons: [
					picker.getRemoveButton(),
					picker.getInsertButton()
				],
				onclose: function() {
					// Clear parameters to there are no collisions if the Icon Picker
					// is opened again.
					picker.callback = {};
					picker.el = {};
				}
			} );
		},

		/**
		 * Construct the definitions for each icon category section.
		 *
		 * @since 1.4.1.
		 *
		 * @returns {Array}
		 */
		getIconCategories: function() {
			var items = [],
				category, grid;

			$.each(picker.iconCategories, function(index, cat) {
				// Icon category label.
				category = {
					type: 'container',
					html: '<span>' + tinymce.i18n.translate(cat.label) + '</span>',
					style: 'padding: 20px 0 10px;'
				};
				items.push(category);

				// Icon grid container.
				grid = {
					type: 'container',
					layout: 'grid',
					columns: 10,
					spacing: 1,
					defaults: {
						type: 'container',
						minWidth: 36,
						minHeight: 36,
						classes: 'icon-choice',
						border: '1 1 1 1',
						style: 'border-color: #ffffff; border-style: solid;'
					},
					items: picker.getIconGrid(cat.icons)
				};
				items.push(grid);
			});

			return items;
		},

		/**
		 * Construct the definitions for each icon control in a grid.
		 *
		 * @since 1.4.1.
		 *
		 * @param icons
		 * @returns {Array}
		 */
		getIconGrid: function(icons) {
			var grid = [],
				icon;

			$.each(icons, function(index, data) {
				var iconData = picker.icons[data];
				
				function highlight( self ) {
					picker.el = self.getEl();
					picker.el.style.borderColor = '#d9d9d9';
					picker.el.style.color = '#2ea2cc';
				}

				function unhighlight() {
					picker.el.style.borderColor = '#ffffff';
					picker.el.style.color = 'inherit';
					picker.el = {};
				}

				var style = (iconData.style.indexOf('brands') !== -1) ? 'fab': 'fas';

				icon = {
					html: '<div data-icon-value="' + iconData.id + '" data-icon-style="' + style + '" data-icon-unicode="' + iconData.unicode + '" style="padding: 4px 0; text-align: center;"><i title="' + iconData.label + '" class="' + style + ' ' + iconData.id + '"></i></div>',
					onPostRender: function() {
						var currentValue = picker.getChosenIcon();
						if ( currentValue == iconData.id ) {
							// Highlight the selected icon.
							highlight( this );
						}
					},
					onclick: function() {
						var value, unicode, style;

						// Un-highlight the previously selected icon.
						if ( 'undefined' !== typeof picker.el.style ) {
							unhighlight();
						}

						// Highlight the selected icon.
						highlight( this );

						// Get the icon ID and unicode and store them in the hidden text fields.
						value = $( picker.el ).find( '[data-icon-value]' ).data( 'icon-value' );
						style = $( picker.el ).find( '[data-icon-style]' ).data( 'icon-style' );
						unicode = $( picker.el ).find( '[data-icon-unicode]' ).data( 'icon-unicode' );
						iconWindow.find( '#chosenIcon' ).value( value );
						iconWindow.find( '#chosenIconUnicode' ).value( unicode );
						iconWindow.find( '#chosenIconStyle' ).value( style );

						// Enable the insert button
						iconInsert.disabled( false );
					}
				};

				grid.push(icon);
			});

			return grid;
		},

		/**
		 * Get the "Choose" button for the modal window.
		 *
		 * @since 1.4.1.
		 *
		 * @returns object
		 */
		getInsertButton: function() {
			var button = {
				text: 'Choose',
				id: 'ttfmake-icon-picker-insert',
				name: 'iconInsert',
				classes: 'button-primary',
				disabled: true,
				onPostRender: function() {
					// Store this control so it can be accessed later.
					iconInsert = this;
				},
				onclick: function() {
					// Get the currently selected icon.
					var value = picker.getChosenIcon(),
						unicode = picker.getChosenUnicode(),
						style = picker.getChosenStyle();

					if ( 'function' === typeof picker.callback ) {
						// Fire the callback.
						picker.callback(value, unicode, style);

						// Close the modal.
						iconWindow.fire( 'submit' );
					}
				}
			};

			return button;
		},

		/**
		 * Get the "Remove" button for the modal window.
		 *
		 * @since 1.4.1.
		 *
		 * @returns object
		 */
		getRemoveButton: function() {
			var button = {
				text: 'Remove',
				id: 'ttfmake-icon-picker-remove',
				name: 'iconRemove',
				classes: 'button-secondary',
				hidden: true,
				onPostRender: function() {
					// Store this control so it can be accessed later.
					iconRemove = this;

					//
					if ('' !== picker.getChosenIcon()) {
						this.visible(true);
					}
				},
				onclick: function() {
					if ( 'function' === typeof picker.callback ) {
						// Fire the callback.
						picker.callback('');

						// Close the modal.
						iconWindow.fire('submit');
					}
				}
			};

			return button;
		},

		/**
		 * Grabs the selected icon ID from the hidden text field.
		 *
		 * @since 1.4.1.
		 *
		 * @returns string
		 */
		getChosenIcon: function() {
			return iconValue.value();
		},

		/**
		 * Grabs the selected icon unicode from the hidden text field.
		 *
		 * @since 1.4.1.
		 *
		 * @returns string
		 */
		getChosenUnicode: function() {
			return iconUnicode.value();
		},

		getChosenStyle: function() {
			return iconStyle.value();
		}
	});

	// Kick things off.
	picker.loadIcons();
})(jQuery, MakeIconPicker);