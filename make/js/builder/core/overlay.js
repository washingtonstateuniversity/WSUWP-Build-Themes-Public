( function ( $, _, Backbone, builderSettings, sectionData ) {
	'use strict';

	window.make.overlays = {};

	/**
	 *
	 * Content overlay class
	 *
	 */
	window.make.overlays.content = Backbone.View.extend( {
		events: {
			'click .ttfmake-overlay-close-update': 'onUpdate',
			'click .ttfmake-overlay-close-discard': 'onDiscard',
			'click .ttfmake-overlay-wrapper': 'onWrapperClick',
		},

		editor: false,
		$editor: false,
		$textarea: false,

		initialize: function( options ) {
			// this.model is the section origin model
			// and is set automatically through
			// the options parameter
			this.buttonLabel = options.buttonLabel;
			this.changeset = new Backbone.Model();
			this.$editor = $( '#wp-make_content_editor-wrap' );
			this.$textarea = $( '#make_content_editor' );
			this.field = options.field;

			return this.render();
		},

		render: function() {
			this.setElement( document.getElementById( 'ttfmake-tinymce-overlay' ) );

			if ( this.buttonLabel ) {
				$( '.ttfmake-overlay-close-update', this.$el ).text( this.buttonLabel );
			}

			return this;
		},

		open: function() {
			var $body = $( 'body' );

			// Fill editor with current content
			this.setContent();

			// Show the overlay
			$body.addClass( 'modal-open' );
			this.$el.css( 'display', 'table' );

			if ( 'visual' === this.getMode() ) {
				// Focus on visual editor
				this.getEditor().focus();
				this.getEditor().on( 'keydown', this.onKeyDown );
			} else {
				// Focus on code editor
				this.$textarea.focus();
				this.$textarea.on( 'keydown', this.onKeyDown );
			}

			$body.on( 'keydown', this.onKeyDown );
		},

		getEditor: function() {
			return tinyMCE.get( 'make_content_editor' );
		},

		setContent: function () {
			if ( 'visual' === this.getMode() ) {
				this.getEditor().setContent( switchEditors.wpautop( this.model.get( this.field ) ) );
			} else {
				this.$textarea.val( switchEditors.pre_wpautop( this.model.get( this.field ) ) );
			}
		},

		getContent: function() {
			return 'visual' === this.getMode() ?
				this.getEditor().getContent(): this.$textarea.val();
		},

		getMode: function() {
			return this.$editor.hasClass( 'tmce-active' ) ? 'visual' : 'text';
		},

		setStyle: function( style ) {
			if ( this.getEditor() ) {
				$( this.getEditor().getBody() ).css( style );
			}
		},

		onUpdate: function( e ) {
			e.preventDefault();
			e.stopPropagation();

			this.model.set( this.field, this.getContent() );
			this.remove();
		},

		onDiscard: function( e ) {
			e.preventDefault();
			e.stopPropagation();

			this.remove();
		},

		onWrapperClick: function( e ) {
			if ( $( e.target ).is( '.ttfmake-overlay-wrapper' ) ) {
				e.preventDefault();
				e.stopPropagation();

				this.remove();
			}
		},

		onKeyDown: function( e ) {
			if ( 27 === e.keyCode ) {
				e.preventDefault();
				e.stopPropagation();

				window.make.overlay.remove();
			}
		},

		remove: function() {
			// Remove view events
			this.undelegateEvents();

			var $body = $( 'body' );

			// Remove DOM events
			$body.off( 'keydown', this.onKeyDown );
			this.$textarea.off( 'keydown', this.onKeyDown );

			if ( this.getEditor() ) {
				this.getEditor().off( 'keydown', this.onKeyDown );
				// Clear selection
				this.getEditor().selection.select( this.getEditor().getBody(), true );
				this.getEditor().selection.collapse( false );
			}

			$body.removeClass( 'modal-open' );
			this.$el.hide();
		},
	} );

	/**
	 *
	 * Media overlay class
	 *
	 */
	window.make.overlays.media = Backbone.View.extend( {
		removeImageTemplate: wp.media.template( 'ttfmake-media-overlay-remove-image' ),

		events: {
			'click .ttfmake-remove-image-from-modal': 'onRemoveClick'
		},

		initialize: function( options ) {
			// this.model is the section origin model
			// and is set automatically through
			// the options parameter
			this.changeset = new Backbone.Model();
			// This field will be set on the model
			// to the selected attachment id.
			// A [field]-url will be set
			// with the selected attachment url.
			this.field = options.field;

			this.frame = wp.media.frames.frame = wp.media( {
				title: options.title,
				className: 'media-frame ttfmake-builder-uploader',
				multiple: false,
				library: { type: options.type },
			} );

			this.frame.on( 'open', this.onFrameOpen, this );
			this.frame.on( 'select', this.onSelect, this );
			this.frame.on( 'selection:toggle', this.onToggleSelection, this );
			this.frame.on( 'escape', this.onDiscard, this );

			// Dev helper
			// var trigger = this.frame.trigger;
			// this.frame.trigger = function() {
			// 	console.log(arguments);
			// 	return trigger.apply( this, arguments );
			// }
		},

		render: function() {
			this.setElement( this.frame.$el );

			if ( this.model.get( this.field ) ) {
				$( '.media-sidebar', this.$el ).append( this.removeImageTemplate() );
			}
		},

		open: function() {
			this.frame.open();
		},

		onFrameOpen: function() {
			this.render();

			var attachmentID = this.model.get( this.field );

			if ( attachmentID ) {
				var selection = this.frame.state().get( 'selection' );
				var attachment = wp.media.attachment( attachmentID );
				selection.add( [ attachment ] );
				$( '.ttfmake-media-overlay-remove-image', this.$el ).show();
			}
		},

		onToggleSelection: function() {
			if ( this.frame.state().get( 'selection' ).size() ) {
				$( '.ttfmake-media-overlay-remove-image', this.$el ).show();
			} else {
				$( '.ttfmake-media-overlay-remove-image', this.$el ).hide();
			}
		},

		onRemoveClick: function( e ) {
			e.preventDefault();
			this.onRemove();
		},

		onSelect: function() {
			var selection = this.frame.state().get( 'selection' );

			if ( selection.size() ) {
				var attachment = selection.first().toJSON();
				this.changeset.set( this.field, attachment.id );
				this.changeset.set( this.field + '-url', attachment.url );
			} else {
				this.changeset.clear();
			}

			this.model.set( this.changeset.toJSON() );
			this.remove();
		},

		onRemove: function() {
			this.changeset.set( this.field, '' );
			this.changeset.set( this.field + '-url', '' );
			this.model.set( this.changeset.toJSON() );
			this.remove();
		},

		onDiscard: function( e ) {
			this.remove();
		},

		remove: function() {
			this.undelegateEvents();
			this.frame.close();
			this.frame.detach();
		},
	} );

	window.make.classes.configuration = {};

	/**
	 *
	 * Configuration overlay class
	 *
	 */
	window.make.overlays.configuration = Backbone.View.extend( {
		template: wp.template( 'ttfmake-overlay-configuration' ),
		className: 'ttfmake-overlay ttfmake-configuration-overlay',
		id: 'ttfmake-overlay-configuration',
		title: 'Configure section',
		buttonLabel: 'Update changes',

		events: {
			'click .ttfmake-overlay-close-update': 'onUpdate',
			'click .ttfmake-overlay-close-discard': 'onDiscard',
			'click .ttfmake-overlay-wrapper': 'onWrapperClick',
		},

		initialize: function( options, settings ) {
			// this.model is the section origin model
			// and is set automatically through
			// the options parameter
			this.title = options.title || this.title;
			this.buttonLabel = options.buttonLabel || this.buttonLabel;
			this.settings = settings;
			this.changeset = new Backbone.Model();
			this.controls = {};

			return this.render();
		},

		render: function() {
			this.setElement( this.template( this ) );

			var $body = $( '.ttfmake-overlay-body', this.$el );

			// Render controls
			_( this.settings ).each( function( setting ) {
				var view = window.make.classes.configuration[setting.type];

				if ( view ) {
					var control = new view( setting, this );
					this.controls[setting.name] = control;
					$body.append( control.render().$el );
				}
			}, this );

			// Wrap controls in divs according to dividers
			$( '.ttfmake-configuration-divider-wrap', this.$el ).each( function() {
				$( this ).nextUntil( '.ttfmake-configuration-divider-wrap' ).wrapAll( '<div />' );
			} );

			// Apply section data from section model
			this.applyValues( this.model.toJSON() );

			this.on( 'setting-updated', this.onSettingUpdated, this );

			return this;
		},

		open: function() {
			var $body = $( 'body' );
			$body.addClass( 'modal-open' );

			// Show the overlay
			$body.append( this.$el );
			this.$el.css( 'display', 'table' );
			$body.on( 'keydown', this.onKeyDown );

			// Focus on first input
			$( 'input, select', this.$el ).first().focus();

			// Scroll to the open divider
			var $overlay = $( '.ttfmake-overlay-body', this.$el );
			var $dividers = $( '.ttfmake-configuration-divider-wrap', this.$el );

			if ( ! $dividers.length ) {
				return;
			}

			// This can later be removed ...
			$dividers.removeClass( 'open-wrap' );
			// ... together with the `open` class in the settings

			var $divider = $dividers.first();
			if ( this.model.get( 'open-divider' ) ) {
				var name = this.model.get( 'open-divider' );
				$divider = $( '[data-name="' + name + '"]', this.$el ).parent();
			}

			$divider.addClass( 'open-wrap' );
			var offset = $divider.position().top + $overlay.scrollTop() - $divider.outerHeight();
			$overlay.scrollTop( offset );
		},

		applyValues: function( values ) {
			for ( var field in values ) {
				var value = values[field];
				this.applyValue( field, value );
			}
		},

		applyValue: function( field, value ) {
			var control = this.controls[field];

			if ( control ) {
				control.setValue( value );
			}
		},

		onSettingUpdated: function( setting, options ) {
			options = options || {}
			this.changeset.set( setting.name, setting.value );
			console.log( 'Setting updated: ', setting.name, setting.value );

			if ( options.immediate ) {
				this.model.set( setting.name, setting.value, options );
			}
		},

		onUpdate: function( e ) {
			e.preventDefault();
			e.stopPropagation();

			this.model.set( this.changeset.toJSON() );
			this.remove();
		},

		onDiscard: function( e ) {
			e.preventDefault();
			e.stopPropagation();

			this.remove();
		},

		onWrapperClick: function( e ) {
			if ( $( e.target ).is( '.ttfmake-overlay-wrapper' ) ) {
				e.preventDefault();
				e.stopPropagation();

				this.remove();
			}
		},

		onKeyDown: function( e ) {
			if ( 27 === e.keyCode ) {
				e.preventDefault();
				e.stopPropagation();

				window.make.overlay.remove();
			}
		},

		remove: function() {
			var $body = $( 'body' );

			// Remove DOM events
			$body.off( 'keydown', this.onKeyDown );

			for ( var name in this.controls ) {
				this.controls[name].remove();
			}

			$body.removeClass( 'modal-open' );

			// Remove view events
			Backbone.View.prototype.remove.apply( this, arguments );
		}
	} );

	/**
	 *
	 * Control base class
	 *
	 */
	window.make.classes.configuration.control = Backbone.View.extend( {
		className: 'ttfmake-configuration-overlay-input-wrap',

		initialize: function( setting, overlay ) {
			this.overlay = overlay;
			this.setting = setting;
		},

		render: function() {
			var html = this.template( this.setting );

			// Apply user-defined classes
			if ( this.setting.class ) {
				var classes = this.setting.class.split( ' ' );

				classes = _( classes ).map( function( cssClass ) {
					return cssClass + '-wrap';
				} ).join( ' ' );

				this.$el.addClass( classes );
			}

			this.$el.html( html );

			return this;
		},

		setDataAttribute: function() {
			this.$el.attr( 'data-value', this.getValue() );
		},

		setValue: function( value ) {
			// Noop
		},

		getValue: function() {
			// Noop
		},

		enable: function() {
			// Noop
		},

		disable: function() {
			// Noop
		},

		settingUpdated: function() {
			this.setDataAttribute();
			this.overlay.trigger( 'setting-updated', { name: this.setting.name, value: this.getValue() } );
		}
	} );

	/**
	 *
	 * Divider control
	 *
	 */
	window.make.classes.configuration.divider = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-divider' ),

		events: {
			'click': 'settingUpdated',
		},

		settingUpdated: function(e) {
			e.preventDefault();
			e.stopPropagation();

			var $divider = this.$el;
			var $dividers = $( '.ttfmake-configuration-divider-wrap' ).not( this.$el );
			var $body = this.$el.parents( '.ttfmake-overlay-body' );

			$dividers.each( function() {
				var $this = $( this );
				$this.next().slideUp( 200, function() {
					$this.removeClass( 'open-wrap' );
				} );
			} );

			$divider.next().slideDown( {
				duration: 200,

				step: function() {
					var offset = $divider.position().top + $body.scrollTop() - $divider.outerHeight();
					$body.scrollTop( offset );
				},

				complete: function() {
					$divider.addClass( 'open-wrap' );
				}
			} );

			// Always sync the current open divider name
			this.overlay.trigger( 'setting-updated', { name: 'open-divider', value: this.getValue() }, { immediate: true, silent: true } );
		},

		getValue: function() {
			return this.setting.name;
		},
	} );

	/**
	 *
	 * Description control
	 *
	 */
	window.make.classes.configuration.description = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-description' ),
	} );

	/**
	 *
	 * Section title control
	 *
	 */
	window.make.classes.configuration.section_title = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-section_title' ),

		events: {
			'keyup input[type=text]' : 'settingUpdated',
		},

		setValue: function( value ) {
			$( 'input', this.$el ).val( value );
		},

		getValue: function() {
			return $( 'input', this.$el ).val();
		},

		enable: function() {
			$( 'input', this.$el ).prop( 'disabled', false );
		},

		disable: function() {
			$( 'input', this.$el ).prop( 'disabled', true );
		},
	} );

	/**
	 *
	 * Select control
	 *
	 */
	window.make.classes.configuration.select = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-select' ),

		events: {
			'change select' : 'settingUpdated',
		},

		setValue: function( value ) {
			$( 'select', this.$el ).val( value );
			this.setDataAttribute();
		},

		getValue: function() {
			return $( 'select', this.$el ).val();
		},

		enable: function() {
			$( 'select', this.$el ).prop( 'disabled', false );
		},

		disable: function() {
			$( 'select', this.$el ).prop( 'disabled', true );
		},
	} );

	/**
	 *
	 * Checkbox control
	 *
	 */
	window.make.classes.configuration.checkbox = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-checkbox' ),

		events: {
			'change input' : 'settingUpdated',
		},

		setValue: function( value ) {
			value = '' + value;
			$( 'input', this.$el ).prop( 'checked', '1' === value );
		},

		getValue: function() {
			var $input = $( 'input', this.$el );
			return $input.is( ':checked' ) ? 1: 0;
		},

		settingUpdated: function() {
			if ( 1 === this.getValue() ) {
				this.$el.addClass( 'checked' );
			} else {
				this.$el.removeClass( 'checked' );
			}

			window.make.classes.configuration.control.prototype.settingUpdated.apply( this, arguments );
		},

		enable: function() {
			$( 'input', this.$el ).prop( 'disabled', false );
		},

		disable: function() {
			$( 'input', this.$el ).prop( 'disabled', true );
		},
	} );

	/**
	 *
	 * Text control
	 *
	 */
	window.make.classes.configuration.text = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-text' ),

		events: {
			'change input[type=text]' : 'settingUpdated',
		},

		setValue: function( value ) {
			$( 'input', this.$el ).val( value );
		},

		getValue: function() {
			return $( 'input', this.$el ).val();
		},

		enable: function() {
			$( 'input', this.$el ).prop( 'disabled', false );
		},

		disable: function() {
			$( 'input', this.$el ).prop( 'disabled', true );
		},
	} );

	/**
	 *
	 * Color control
	 *
	 */
	window.make.classes.configuration.color = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-color' ),
		widget: false,

		render: function() {
			window.make.classes.configuration.control.prototype.render.apply( this, arguments );

 			var palettes = _( builderSettings.palettes );
			palettes = palettes.isArray() ? palettes.toArray(): palettes.values();

			this.widget = $( 'input', this.$el ).wpColorPicker( {
				hide: false,
				palettes: palettes,
				defaultColor: this.getValue(),
				change: this.onColorPick.bind( this ),
				clear: this.onColorClear.bind( this ),
			} );

			$( 'body' ).off( 'click.wpcolorpicker' );

			return this;
		},

		onColorPick: function( e, widget ) {
			this.overlay.trigger( 'setting-updated', { name: this.setting.name, value: widget.color.toString() } );
		},

		onColorClear: function( e, widget ) {
			this.overlay.trigger( 'setting-updated', { name: this.setting.name, value: '' } );
		},

		setValue: function( value ) {
			this.widget.wpColorPicker( 'color', value );
		},

		getValue: function() {
			return $( '.ttfmake-text-background-color', this.$el ).val();
		},

		remove: function() {
			// this.widget.wpColorPicker( 'destroy' );
		}
	} );

	/**
	 *
	 * Image control
	 *
	 */
	window.make.classes.configuration.image = window.make.classes.configuration.control.extend( {
		template: wp.template( 'ttfmake-settings-image' ),

		events: {
			'click .ttfmake-media-uploader-placeholder': 'onMediaAdd',
		},

		initialize: function( setting, overlay ) {
			window.make.classes.configuration.control.prototype.initialize.apply( this, arguments );

			this.model = new Backbone.Model();
			this.listenTo( this.model, 'change:background-image-url', this.onBackgroundChanged );
		},

		onMediaAdd: function( e ) {
			window.make.media = new window.make.overlays.media( {
				model: this.model,
				field: 'background-image',
				type: 'image',
				title: $( e.target ).data( 'title' )
			} );

			window.make.media.open();
		},

		onBackgroundChanged: function( settingModel ) {
			var $placeholder = $( '.ttfmake-media-uploader-placeholder', this.$el );
			var backgroundImageURL = settingModel.get( 'background-image-url' );

			$placeholder.css( 'background-image', 'url(' + backgroundImageURL + ')' );

			if ( '' !== backgroundImageURL ) {
				this.$el.addClass( 'ttfmake-has-image-set' );
			} else {
				this.$el.removeClass( 'ttfmake-has-image-set' );
			}

			this.overlay.trigger( 'setting-updated', { name: 'background-image', value: settingModel.get( 'background-image' ) } );
			this.overlay.trigger( 'setting-updated', { name: 'background-image-url', value: settingModel.get( 'background-image-url' ) } );
		},

		setValue: function( value ) {
			var $placeholder = $( '.ttfmake-media-uploader-placeholder', this.$el );

			if ( value ) {
				var url = this.overlay.model.get( 'background-image-url' )
					|| this.overlay.changeset.get( 'background-image-url' );
				this.model.set( 'background-image', value );
				this.model.set( 'background-image-url', url );
				$placeholder.css( 'background-image', 'url(' + url + ')' );
				this.$el.addClass( 'ttfmake-has-image-set' );
			} else {
				this.model.set( 'background-image', '' );
				this.model.set( 'background-image-url', '' );
				$placeholder.css( 'background-image', '' );
				this.$el.removeClass( 'ttfmake-has-image-set' );
			}
		},
	} );

} ) ( jQuery, _, Backbone, ttfmakeBuilderSettings, ttfMakeSections );