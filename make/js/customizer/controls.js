/**
 * @package Make
 */

/* global jQuery, wp, MakeControls */

(function($, wp, MakeControls) {
	'use strict';

	if ( ! wp || ! wp.customize || ! MakeControls ) { return; }

	var api = wp.customize,
		Make;

	/**
	 * MakeControls
	 *
	 * Starts with the following data properties added via script localization:
	 * - ajaxurl
	 * - fontsettings
	 * - l10n
	 */

	// Setup
	Make = $.extend(MakeControls, {
		cache: {
			$document: $(document),
			ajax:      {}
		},

		rtl: $('body').hasClass('rtl'),

		sendRequest: function(data, callback) {
			var self = this;

			// Allow additional data to be added to Ajax requests
			if ('undefined' !== typeof data.action && 'object' === typeof self.cache.ajax[data.action]) {
				data = $.extend(data, self.cache.ajax[data.action]);
			}

			$.post(self.ajaxurl, data, function(response) {
				if ('function' === typeof callback) {
					callback(response);
				}
			});
		}
	});

	// Font choice loader
	Make = $.extend(Make, {
		fontElements: $(),

		initFont: function() {
			var self = this;

			self.cache.$document.ready(function() {
				self.getFontElements();

				self.fontElements.each(function() {
					if (self.rtl) {
						$(this).addClass('chosen-rtl');
					}

					$(this).chosen({
						no_results_text: self.l10n.chosen_no_results_fonts,
						search_contains: true,
						width          : '100%'
					});

					$(this).on('chosen:showing_dropdown', self.updateFontElements);
				});
			});
		},

		getFontElements: function() {
			var self = this;

			self.fontSettings = self.fontSettings || {};
			$.each(self.fontSettings, function(i, settingId) {
				api.control('ttfmake_' + settingId, function(control) {
					var $element = control.container.find('select');
					$element.data('settingId', settingId);
					self.fontElements = self.fontElements.add($element);
				});
			});
		},

		updateFontElements: function() {
			var self = Make,
				data = {
					action: 'make-font-choices'
				};

			self.fontElements.each(function() {
				$(this)
					.html('<option>' + self.l10n.chosen_loading + '</option>')
					.trigger('chosen:updated');
			});

			self.sendRequest(data, function(response) {
				if (response) {
					self.insertFontChoices(response);
				}
			});
		},

		insertFontChoices: function(content) {
			var self = this;

			self.fontElements.each(function() {
				var $element = $(this),
					settingId = $element.data('settingId');

				$element.html(content);

				api(settingId, function(setting) {
					var v = setting();
					$element
						.val(v)
						.trigger('chosen:updated')
						.off('chosen:showing_dropdown', self.updateFontElements);
				});
			});
		}
	});

	$(document).ready(function() {
		Make.initFont();
	});

	/**
	 * Initialize instances of MAKE_Customizer_Control_BackgroundPosition
	 *
	 * @since 1.7.0.
	 */
	api.controlConstructor.make_backgroundposition = api.Control.extend({
		ready: function() {
			var control = this,
				$container = control.container.find('.make-backgroundposition-container');

			// Initialize the buttonset.
			$container.buttonset();

			// Listen for changes to the buttonset.
			$container.on('change', 'input:radio', function() {
				var value = $(this).parent().find('input:radio:checked').val();
				control.setting.set(value);
			});

			// Update the buttonset if the setting changes.
			control.setting.bind(function(value) {
				$container.find('input:radio').filter('[value=' + value + ']').prop('checked', true);
			});
		}
	});

	/**
	 * Initialize instances of MAKE_Customizer_Control_Radio
	 *
	 * @since 1.7.0.
	 */
	api.controlConstructor.make_radio = api.Control.extend({
		ready: function() {
			var control = this,
				$container = control.container.find('.make-radio-container');

			$container.each(function() {
				if ($(this).hasClass('make-radio-buttonset-container') || $(this).hasClass('make-radio-image-container')) {
					$(this).buttonset();
				}
			});

			// Listen for changes to the radio group.
			$container.on('change', 'input:radio', function() {
				var value = $(this).parent().find('input:radio:checked').val();
				control.setting.set(value);
			});

			// Update the radio group if the setting changes.
			control.setting.bind(function(value) {
				$container.find('input:radio').filter('[value=' + value + ']').prop('checked', true);
			});
		}
	});

	/**
	 * Initialize instances of MAKE_Customizer_Control_Radio
	 *
	 * @since 1.8.4.
	 */
	api.controlConstructor.make_select = api.Control.extend({
		ready: function() {
			var control = this,
				$container = control.container.find('.make-select-container'),
				$input = $('select', $container);

			// Listen for changes to the radio group.
			$input.on('change', function() {
				var value = $(this).val();
				control.setting.set(value);
			});
		}
	});

	/**
	 * Initialize instances of MAKE_Customizer_Control_Range
	 *
	 * @since 1.7.0.
	 */
	api.controlConstructor.make_range = api.Control.extend({
		ready: function() {
			var control = this,
				$container = control.container.find('.make-range-container');

			$container.each(function() {
				var $input = $(this).find('.make-range-input'),
					$slider = $(this).find('.make-range-slider'),
					value = parseFloat( $input.val() ),
					min = parseFloat( $input.attr('min') ),
					max = parseFloat( $input.attr('max') ),
					step = parseFloat( $input.attr('step') );

				// Configure the slider
				$slider.slider({
					value : value,
					min   : min,
					max   : max,
					step  : step,
					slide : function(e, ui) { $input.val(ui.value) }
				});

				// Debounce the slide event so the preview pane doesn't update too often
				$slider.on('slide', _.debounce(function(e, ui) {
					$input.keyup().trigger('change');
				}, 300));

				// Sync values of number input and slider
				$input.val( $slider.slider('value')).on('change', function() {
					$slider.slider('value', $(this).val());
				});

				// Listen for changes to the range.
				$input.on('change', function() {
					var value = $(this).val();
					control.setting.set(value);
				});

				// Update the range if the setting changes.
				control.setting.bind(function(value) {
					$input.val(value);
				});
			});
		}
	});

	/**
	 * Initialize instances of MAKE_Customizer_Control_SocialIcons
	 *
	 * @since 1.7.0.
	 */
	api.controlConstructor.make_socialicons = api.Control.extend({
		/**
		 * Additions to the default initialize routine.
		 *
		 * @since 1.7.0.
		 *
		 * @param id
		 * @param options
		 */
		initialize: function(id, options) {
			var control = this;

			// Add template functions
			options.params.itemTemplate = control.getItemTemplate();
			options.params.listTemplate = control.getListTemplate();

			// Do parent stuff
			api.Control.prototype.initialize.apply(control, arguments);
		},

		/**
		 * Generate a templating function for the item sub-template.
		 *
		 * @since 1.7.0.
		 */
		getItemTemplate: function() {
			var control = this,
				templateID = 'customize-control-make_socialicons-item';

			// Replace the container element's content with the control.
			if ( 0 !== $( '#tmpl-' + templateID ).length ) {
				return wp.template( templateID );
			}
		},

		/**
		 * Generate a templating function for the list overlay sub-template.
		 *
		 * @since 1.7.0.
		 */
		getListTemplate: function() {
			var control = this,
				templateID = 'customize-control-make_socialicons-list';

			// Replace the container element's content with the control.
			if ( 0 !== $( '#tmpl-' + templateID ).length ) {
				return wp.template( templateID );
			}
		},

		/**
		 * Kick things off when the template has been embedded.
		 *
		 * @since 1.7.0.
		 */
		ready: function() {
			var control = this,
				$container = control.container.find('.make-socialicons-container'),
				$stage = $container.find('.make-socialicons-stage'),
				$addbutton = $container.find('#add-icon_' + control.id),
				$emailtoggle = $container.find('#email-toggle_' + control.id),
				$rsstoggle = $container.find('#rss-toggle_' + control.id),
				$rsshelp = $container.find('#rss-help_' + control.id),
				$newwindow = $container.find('#new-window_' + control.id),
				$iconslink = $container.find('#list-icons_' + control.id);

			// Set up sortable items
			$stage.sortable({
				placeholder: 'make-socialicons-item-placeholder',
				create: function() {
					$stage.on('sortupdate', function() {
						control.updateValue();
					});
				}
			});

			// Add icon button
			$addbutton.on('click', function(evt) {
				evt.preventDefault();

				var $item;

				$item = $(control.params.itemTemplate({type:'link'}));
				$stage.append($item);
				$item.find('input').focus();
				control.doneTyping($item);
			});

			// Remove button
			$stage.on('click', '.make-socialicons-item-remove', function(evt) {
				evt.preventDefault();
				$(this).parent().remove();
				control.updateValue();
			});

			// Item inputs
			$stage.on('make:socialicons:donetyping', 'input', function() {
				control.sendIconRequest($(this).parent());
				control.updateValue();
			});

			// Existing items
			$stage.find('.make-socialicons-item').each(function() {
				control.doneTyping($(this));
				control.sendIconRequest($(this));
			});

			// Email toggle
			$emailtoggle.on('change', function(evt) {
				var checked = $(evt.target).prop('checked'),
					$item;

				if (checked) {
					$item = $(control.params.itemTemplate({type:'email'}));
					$stage.append($item);
					$item.find('input').focus();
					control.doneTyping($item);
					control.sendIconRequest( $stage.find('.make-socialicons-item-email') );
				} else {
					$stage.find('.make-socialicons-item-email').remove();
					control.updateValue();
				}
			});
			if (! $emailtoggle.prop('checked')) {
				$stage.find('.make-socialicons-item-email').remove();
			}

			// RSS toggle
			$rsstoggle.on('change', function(evt) {
				var checked = $(evt.target).prop('checked'),
					$item;

				if (checked) {
					$item = $(control.params.itemTemplate({type:'rss'}));
					$stage.append($item);
					$item.find('input').focus();
					control.doneTyping($item);
					control.sendIconRequest( $stage.find('.make-socialicons-item-rss') );
					$rsshelp.show();
				} else {
					$stage.find('.make-socialicons-item-rss').remove();
					$rsshelp.hide();
				}

				control.updateValue();
			});
			if (! $rsstoggle.prop('checked')) {
				$stage.find('.make-socialicons-item-rss').remove();
				$rsshelp.hide();
			}

			// New window toggle
			$newwindow.on('change', function() {
				control.updateValue();
			});

			// Available icons link
			$iconslink.on('click', function(evt) {
				evt.preventDefault();

				var $openList = $('#make-socialicons-list-wrapper');

				if ($openList.length < 1) {
					control.sendListRequest(function(data) {
						var $newList = $(control.params.listTemplate(data));
						$container.append($newList);
					});
				}
			});

			// Close button for icons list
			$container.on('click', '#make-socialicons-list-close', function(evt) {
				evt.preventDefault();
				$(this).parents('#make-socialicons-list-wrapper').remove();
			});
		},

		/**
		 * Listen for a pause in typing into an icon item's input and trigger an event.
		 *
		 * @link http://stackoverflow.com/a/14042239
		 *
		 * @since 1.7.0.
		 *
		 * @param $el jQuery element set
		 */
		doneTyping: function($el) {
			var timeout = 900,
				timeoutReference,
				doneTyping = function($el){
					if (! timeoutReference) return;
					timeoutReference = null;
					$el.find('input').trigger('make:socialicons:donetyping');
				};

			$el.find('input').on('keyup keypress paste', function(evt) {
				// This catches the backspace button in chrome, but also prevents
				// the event from triggering too preemptively. Without this line,
				// using tab/shift+tab will make the focused element fire the callback.
				if ('keyup' == evt.type && evt.keyCode != 8) return;

				// Check if timeout has been set. If it has, "reset" the clock and
				// start over again.
				if (timeoutReference) clearTimeout(timeoutReference);
				timeoutReference = setTimeout(function() {
					// if we made it here, our timeout has elapsed. Fire the
					// callback
					doneTyping($el);
				}, timeout);
			}).on('blur', function() {
				// If we can, fire the event since we're leaving the field
				doneTyping($el);
			});
		},

		/**
		 * Look up an icon match for the current URL in an item input.
		 *
		 * @since 1.7.0.
		 *
		 * @param jQuery    $el    The item container
		 */
		sendIconRequest: function($el) {
			var control = this,
				data, type, content;

			type = $el.data('type');
			content = $el.find('input').val();

			data = {
				action: 'make-social-icons',
				type: type,
				content: content
			};

			$.post(MakeControls.ajaxurl, data, function(response) {
				if ('undefined' !== response.data) {
					control.updateIcon($el, response.data);
				}
			});
		},

		/**
		 * Retrieve the data for all available icons.
		 *
		 * @since 1.7.0.
		 *
		 * @param function    callback
		 */
		sendListRequest: function(callback) {
			var data = {
				action: 'make-social-icons-list'
			};

			$.post(MakeControls.ajaxurl, data, function(response) {
				if ('undefined' !== response.data && 'function' === typeof callback) {
					callback(response.data);
				}
			});
		},

		/**
		 * Update the icon classes in the item handle, or remove them.
		 *
		 * @since 1.7.0.
		 *
		 * @param jQuery    $el        The item container
		 * @param string    classes    String of class names
		 */
		updateIcon: function($el, classes) {
			$el.find('.make-socialicons-item-handle i').removeAttr('class');

			if (classes) {
				$el.find('.make-socialicons-item-handle i').addClass(classes);
			}
		},

		/**
		 * Update the value field with data from all the inputs.
		 *
		 * @since 1.7.0.
		 */
		updateValue: function() {
			var control = this,
				$items = control.container.find('.make-socialicons-item'),
				$options = control.container.find('.make-socialicons-options input'),
				$value = control.container.find('.make-socialicons-value'),
				newValue = { items: [] };

			$items.each(function() {
				var type = $(this).data('type'),
					content = $(this).find('input').val();

				newValue.items.push({type: type, content: content});
			});

			$options.each(function() {
				var name = $(this).data('name');
				newValue[name] = $(this).prop('checked');
			});

			newValue = JSON.stringify(newValue);

			if ($value.val() != newValue) {
				$value.val(newValue);
				control.setting.set(newValue);
			}
		}
	});

	/**
	 * Initialize the section for displaying Make errors
	 *
	 * @since 1.7.0.
	 */
	api.sectionConstructor.make_error = api.Section.extend({
		/**
		 * Kick things off when the template is embedded.
		 *
		 * @since 1.7.0.
		 */
		ready: function() {
			var section    = this,
				$container = section.container,
				$content   = $('#make-error-detail-container');

			if ($content.length > 0) {
				$container.html( $content.html() );

				$container.on('click', '#make-show-errors', function(evt) {
					evt.preventDefault();
					$container.find('#make-error-detail-wrapper').addClass('make-error-detail-wrapper--active');
				});

				$container.on('click', '#make-error-detail-close', function(evt) {
					evt.preventDefault();
					$container.find('#make-error-detail-wrapper').removeClass('make-error-detail-wrapper--active');
				});
			}
		},

		/**
		 * Override Section.isContextuallyActive method.
		 *
		 * @since 1.7.0.
		 *
		 * @returns {Boolean}
		 */
		isContextuallyActive: function() {
			return $('#make-error-detail-container').length > 0;
		}
	});

	/**
	 * Visibility toggling for some controls
	 */
	$.each({
		'general-layout': {
			controls: [ 'ttfmake_background-info' ],
			callback: function( to ) { return 'full-width' === to; }
		},
		'main-background-color-transparent': {
			controls: [ 'ttfmake_main-background-color' ],
			callback: function( to ) { return ! to; }
		},
		'header-background-transparent': {
			controls: [ 'ttfmake_header-background-color' ],
			callback: function( to ) { return ! to; }
		},
		'header-bar-background-transparent': {
			controls: [ 'ttfmake_header-bar-background-color' ],
			callback: function( to ) { return ! to; }
		},
		'footer-background-transparent': {
			controls: [ 'ttfmake_footer-background-color' ],
			callback: function( to ) { return ! to; }
		},
		'background_image': {
			controls: [ 'ttfmake_background_position_x', 'ttfmake_background_attachment', 'ttfmake_background_size' ],
			callback: function( to ) { return !! to; }
		},
		'header-background-image': {
			controls: [ 'ttfmake_header-background-repeat', 'ttfmake_header-background-position', 'ttfmake_header-background-attachment', 'ttfmake_header-background-size' ],
			callback: function( to ) { return !! to; }
		},
		'main-background-image': {
			controls: [ 'ttfmake_main-background-repeat', 'ttfmake_main-background-position', 'ttfmake_main-background-attachment', 'ttfmake_main-background-size' ],
			callback: function( to ) { return !! to; }
		},
		'footer-background-image': {
			controls: [ 'ttfmake_footer-background-repeat', 'ttfmake_footer-background-position', 'ttfmake_footer-background-attachment', 'ttfmake_footer-background-size' ],
			callback: function( to ) { return !! to; }
		},
		'header-layout': {
			controls: [ 'ttfmake_header-branding-position' ],
			callback: function( to ) { return ( '1' == to || '3' == to ); }
		},
		'header-show-social': {
			controls: [ 'ttfmake_font-size-header-bar-icon' ],
			callback: function( to ) { return !! to; }
		},
		'footer-show-social': {
			controls: [ 'ttfmake_font-size-footer-icon' ],
			callback: function( to ) { return !! to; }
		},
		'layout-blog-featured-images': {
			controls: [ 'ttfmake_layout-blog-featured-images-alignment' ],
			callback: function( to ) { return ( 'post-header' === to ); }
		},
		'layout-archive-featured-images': {
			controls: [ 'ttfmake_layout-archive-featured-images-alignment' ],
			callback: function( to ) { return ( 'post-header' === to ); }
		},
		'layout-search-featured-images': {
			controls: [ 'ttfmake_layout-search-featured-images-alignment' ],
			callback: function( to ) { return ( 'post-header' === to ); }
		},
		'layout-post-featured-images': {
			controls: [ 'ttfmake_layout-post-featured-images-alignment' ],
			callback: function( to ) { return ( 'post-header' === to ); }
		},
		'layout-page-featured-images': {
			controls: [ 'ttfmake_layout-page-featured-images-alignment' ],
			callback: function( to ) { return ( 'post-header' === to ); }
		},
		'layout-blog-post-date': {
			controls: [ 'ttfmake_layout-blog-post-date-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-archive-post-date': {
			controls: [ 'ttfmake_layout-archive-post-date-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-search-post-date': {
			controls: [ 'ttfmake_layout-search-post-date-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-post-post-date': {
			controls: [ 'ttfmake_layout-post-post-date-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-page-post-date': {
			controls: [ 'ttfmake_layout-page-post-date-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-blog-post-author': {
			controls: [ 'ttfmake_layout-blog-post-author-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-archive-post-author': {
			controls: [ 'ttfmake_layout-archive-post-author-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-search-post-author': {
			controls: [ 'ttfmake_layout-search-post-author-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-post-post-author': {
			controls: [ 'ttfmake_layout-post-post-author-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-page-post-author': {
			controls: [ 'ttfmake_layout-page-post-author-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-blog-comment-count': {
			controls: [ 'ttfmake_layout-blog-comment-count-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-archive-comment-count': {
			controls: [ 'ttfmake_layout-archive-comment-count-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-search-comment-count': {
			controls: [ 'ttfmake_layout-search-comment-count-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-post-comment-count': {
			controls: [ 'ttfmake_layout-post-comment-count-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		},
		'layout-page-comment-count': {
			controls: [ 'ttfmake_layout-page-comment-count-location' ],
			callback: function( to ) { return ( 'none' !== to ); }
		}
	}, function( settingId, o ) {
		api( settingId, function( setting ) {
			$.each( o.controls, function( i, controlId ) {
				api.control( controlId, function( control ) {
					var visibility = function( to ) {
						control.container.toggle( o.callback( to ) );
					};

					visibility( setting.get() );
					setting.bind( visibility );
				});
			});
		});
	});
})(jQuery, wp, MakeControls);
