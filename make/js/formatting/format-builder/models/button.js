/* global Backbone, jQuery, _, MakeFormatBuilder */
var MakeFormatBuilder = MakeFormatBuilder || {};

(function(Backbone, $, _, builder) {
	'use strict';

	/**
	 * Defines the format parameters to register with the TinyMCE Formatter.
	 *
	 * @since 1.4.1.
	 */
	builder.definitions.button = {
		inline: 'a',
		classes: 'ttfmake-button',
		selector: 'a',
	};

	/**
	 * Define the selector for detecting this format in existing content.
	 *
	 * @since 1.4.1.
	 */
	builder.nodes.button = 'a.ttfmake-button';

	/**
	 * Defines the listbox item in the 'Choose a format' dropdown.
	 *
	 * @since 1.4.1.
	 *
	 * @returns object
	 */
	builder.choices.button = function() {
		var content = builder.currentSelection.getContent(),
			choice;

		// This choice is disabled if no content is selected.
		choice = {
			value: 'button',
			text: 'Button',
			disabled: ( '' == content )
		};

		return choice;
	};

	/**
	 * The Button format model.
	 *
	 * @since 1.4.1.
	 */
	builder.formats = builder.formats || {};
	builder.formats.button = builder.FormatModel.extend({
		/**
		 * Default format option values.
		 *
		 * @since 1.4.1.
		 */
		defaults: {
			update: false,
			id: 0,
			url: '',
			target: false,
			fontSize: builder.userSettings.fontSizeButton,
			fontWeight: 'bold',
			colorBackground: builder.userSettings.colorButtonBackground,
			colorBackgroundHover: builder.userSettings.colorButtonBackgroundHover || builder.userSettings.colorButtonBackground,
			colorText: builder.userSettings.colorButtonText,
			colorTextHover: builder.userSettings.colorButtonTextHover || builder.userSettings.colorButtonText,
			paddingHorz: '10',
			paddingVert: '4',
			borderRadius: '100',
			icon: '',
			cssClass: '',
		},

		/**
		 * Populate the options with any existing values.
		 *
		 * @since 1.4.1.
		 */
		initialize: function() {
			var node = builder.getParentNode(builder.nodes.button);
			var $currentNode = $(builder.currentSelection.getNode());

			// Create a new element ID.
			this.set('id', this.createID());

			// Check to see if we're updating an existing format.
			if (true === this.get('update')) {
				// Formatted button
				this.parseAttributes(node);
			} else if (true === $currentNode.is('a')) {
				// Plain anchor tag
				this.set('url', $currentNode.attr('href') || '#');
			}
		},

		/**
		 * Defines the fields in the options form.
		 *
		 * @since 1.4.1.
		 *
		 * @returns array
		 */
		getOptionFields: function() {
			var items = [
				{
					type: 'textbox',
					name: 'url',
					label: 'URL',
					classes: 'monospace',
					value: this.escape('url')
				},
				{
					type: 'checkbox',
					name: 'target',
					label: 'Open link in a new window/tab',
					checked: this.get('target')
				},
				{
					type: 'textbox',
					name: 'fontSize',
					label: 'Font Size (px)',
					size: 3,
					classes: 'monospace',
					value: this.escape('fontSize')
				},
				{
					type: 'listbox',
					name: 'fontWeight',
					label: 'Font Weight',
					value: this.escape('fontWeight'),
					values: [
						{
							text: 'normal',
							value: 'normal'
						},
						{
							text: 'bold',
							value: 'bold'
						}
					]
				},
				builder.getColorButton( 'colorBackground', 'Background Color' ),
				builder.getColorButton( 'colorBackgroundHover', 'Background Color (hover)' ),
				builder.getColorButton( 'colorText', 'Text Color' ),
				builder.getColorButton( 'colorTextHover', 'Text Color (hover)' ),
				{
					type: 'textbox',
					name: 'paddingHorz',
					label: 'Horizontal Padding (px)',
					size: 3,
					classes: 'monospace',
					value: this.escape('paddingHorz')
				},
				{
					type: 'textbox',
					name: 'paddingVert',
					label: 'Vertical Padding (px)',
					size: 3,
					classes: 'monospace',
					value: this.escape('paddingVert')
				},
				{
					type: 'textbox',
					name: 'borderRadius',
					label: 'Border Radius (px)',
					size: 3,
					classes: 'monospace',
					value: this.escape('borderRadius')
				},
				builder.getIconButton( 'icon', 'Icon' ),
				{
					type: 'textbox',
					name: 'cssClass',
					label: 'CSS Class',
					classes: 'monospace',
					value: this.escape('cssClass')
				}
			];

			return this.wrapOptionFields(items);
		},

		/**
		 * Parse an existing format node and extract its format options.
		 *
		 * @since 1.4.1.
		 *
		 * @param node
		 */
		parseAttributes: function( node ) {
			var self = this,
				$node = $(node),
				icon, iconClasses, fontWeight, fontSize, paddingHorz, paddingVert, borderRadius;

			// Get an existing ID.
			if ( $node.attr('id') ) this.set('id', $node.attr('id'));

			// The href attribute can't actually be empty, but we'll show the option field as blank if it's just a #.
			if (! $node.attr('href') || '#' == $node.attr('href')) {
				this.set('url', '');
			} else {
				this.set('url', $node.attr('href'));
			}

			// Background color hover
			if ( $node.attr('data-hover-background-color') ) this.set('colorBackgroundHover', $node.attr('data-hover-background-color'));
			// Text color hover
			if ( $node.attr('data-hover-color') ) this.set('colorTextHover', $node.attr('data-hover-color'));
			// Target (Open link in new window)
			if ( '_blank' === $node.attr('target') ) this.set('target', true);
			// Background color
			if ( $node.css('backgroundColor') ) this.set('colorBackground', $node.css('backgroundColor'));
			// Text color
			if ( $node.css('color') ) this.set('colorText', $node.css('color'));
			// Font weight
			if ( $node.css('fontWeight') ) {
				fontWeight = $node.css('fontWeight');
				if (400 == fontWeight) {
					fontWeight = 'normal';
				}
				if (700 == fontWeight) {
					fontWeight = 'bold';
				}
				this.set('fontWeight', fontWeight);
			}
			// Font size
			if ( $node.css('fontSize') ) {
				fontSize = parseInt( $node.css('fontSize') );
				this.set('fontSize', fontSize + ''); // Convert integer to string for TinyMCE
			}
			// Horizontal padding
			if ( $node.css('paddingLeft') ) {
				paddingHorz = parseInt( $node.css('paddingLeft') );
				this.set('paddingHorz', paddingHorz + ''); // Convert integer to string for TinyMCE
			}
			// Vertical padding
			if ( $node.css('paddingTop') ) {
				paddingVert = parseInt( $node.css('paddingTop') );
				this.set('paddingVert', paddingVert + ''); // Convert integer to string for TinyMCE
			}
			// Border radius
			if ( $node.css('borderTopLeftRadius') ) {
				borderRadius = parseInt( $node.css('borderTopLeftRadius') );
				this.set('borderRadius', borderRadius + ''); // Convert integer to string for TinyMCE
			}

			// Parse the icon.
			icon = $node.find('i.ttfmake-button-icon');
			if (icon.length > 0) {
				iconClasses = icon.attr('class').split(/\s+/);
				// Look for relevant classes on the <i> element.
				$.each(iconClasses, function(index, iconClass) {
					if (iconClass.match(/^fa-/)) {
						self.set('icon', iconClass);
						return false;
					}
				});
			}

			// Parse CSS Class
			var classes = $node.attr( 'class' ).split( ' ' );
			classes = classes.filter( function( cssClass ) {
				return cssClass !== 'ttfmake-button';
			} );
			classes = classes.join( ' ' );
			this.set( 'cssClass', classes );
		},

		/**
		 * Insert the format markup into the editor.
		 *
		 * @since 1.4.1.
		 */
		insert: function() {
			var $node, $icon;

			// If not updating an existing format, apply to the current selection using the Formatter.
			if (true !== this.get('update')) {
				builder.editor.formatter.apply('button');
			}

			// Make sure the right node is selected.
			$node = $(builder.currentSelection.getNode());
			if (! $node.is(builder.nodes.button)) {
				$node = $node.find(builder.nodes.button);
			}

			// Set the element ID, if it doesn't have one yet.
			if (! $node.attr('id')) {
				$node.attr('id', this.escape('id'));
			}

			// Set the href attribute if the URL option is blank.
			if (! this.escape('url')) {
				// TinyMCE won't allow an <a> tag with no href to wrap content.
				this.set('url', '#');
			}

			// Add attributes.
			$node.attr({
				href: this.escape('url'),
				'data-hover-background-color': this.escape('colorBackgroundHover'),
				'data-hover-color': this.escape('colorTextHover')
			});
			if ( 'true' == this.get('target') ) {
				$node.attr('target', '_blank');
			} else {
				$node.removeAttr('target');
			}

			// Add inline styles.
			$node.css({
				backgroundColor: this.escape('colorBackground'),
				color: this.escape('colorText'),
				fontSize: this.escape('fontSize') + 'px',
				fontWeight: this.escape('fontWeight'),
				padding: this.escape('paddingVert') + 'px ' + this.escape('paddingHorz') + 'px',
				borderRadius: this.escape('borderRadius') + 'px'
			});

			// Remove any existing icons.
			$node.find('i.ttfmake-button-icon').remove();

			// Add the current icon, if one is set.
			if ('' !== this.get('icon')) {
				// Build the icon.
				$icon = $('<i>');
				$icon.attr('class', 'ttfmake-button-icon fa ' + this.escape('icon'));

				// Add the new icon.
				$node.prepend(' ');
				$node.prepend($icon);
			}

			// Add CSS Class
			$node.addClass(this.escape('cssClass'));

			// Remove TinyMCE attributes that break things when trying to update an existing format.
			$node.removeAttr('data-mce-href');
			$node.removeAttr('data-mce-style');
		},

		/**
		 * Remove the existing format node.
		 *
		 * @since 1.4.1.
		 */
		remove: function() {
			var node = builder.getParentNode(builder.nodes.button),
				content;

			// Remove the icon if it exists.
			$(node).find('i.ttfmake-button-icon').remove();

			// Get inner content.
			content = $(node).html().trim();

			// Set the selection to the whole node.
			builder.currentSelection.select(node);

			// Replace the current selection with the inner content.
			builder.currentSelection.setContent(content);
		}
	});
})(Backbone, jQuery, _, MakeFormatBuilder);