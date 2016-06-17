/**
 * @package Make
 */

/* global jQuery, wp, MakePreview */

(function($, wp, MakePreview) {
	'use strict';

	if ( ! wp || ! wp.customize || ! MakePreview ) { return; }

	var api = wp.customize,
		Make;

	/**
	 * MakePreview
	 *
	 * Starts with the following data properties added via script localization:
	 * - ajaxurl
	 * - webfonturl
	 * - fontSettings
	 * - styleSettings
	 */

	// Shared functionality
	Make = $.extend(MakePreview, {
		cache: {
			ajax:  {},
			fonts: {},
			style: {}
		},

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

	// Style previews
	Make = $.extend(Make, {
		initStyles: function() {
			var self = this;

			self.styleSettings = self.styleSettings || {};
			$.each(self.styleSettings, function(i, settingId) {
				api(settingId, function(setting) {
					setting.bind(self.updateStyles);
				});
			});
		},

		getStylesValues: function(settings) {
			var style = {};

			$.each(settings, function(i, settingId) {
				api(settingId, function(setting) {
					style[settingId] = setting();
				});
			});

			return style;
		},

		/**
		 * @link https://css-tricks.com/snippets/javascript/inject-new-css-rules/
		 *
		 * @param content
		 */
		loadStyles: function(content) {
			var styleId = 'make-preview-style',
				$newStyles = $('<div>', {
					id: styleId,
					html: '&shy;' + content
				});

			// Remove old preview stylesheet
			$('#'+styleId).remove();

			// Add new preview stylesheet
			if (content) {
				$newStyles.appendTo('body');
			}
		},

		updateStyles: function() {
			var self = Make,
				data = {
					action:         'make-css-inline',
					'make-preview': self.getStylesValues(self.styleSettings)
				};

			self.sendRequest(data, function(response) {
				if ('undefined' !== response) {
					self.loadStyles(response);
				}
			});
		}
	});

	// Font Loader
	Make = $.extend(Make, {
		initFontLoader: function() {
			var self = this;

			$.getScript(self.webfonturl, function() {
				self.fontSettings = self.fontSettings || {};
				$.each(self.fontSettings, function(i, settingId) {
					api(settingId, function(setting) {
						setting.bind(self.updateFonts);
					});
				});

				self.updateFonts();
			});
		},

		getFontValues: function(settings) {
			var fonts = {};

			$.each(settings, function(i, settingId) {
				api(settingId, function(setting) {
					fonts[settingId] = setting();
				});
			});

			return fonts;
		},

		loadFonts: function(data) {
			if ('object' === typeof WebFont) {
				WebFont.load(data);
			}
		},

		updateFonts: function() {
			var self = Make,
				data = {
					action:         'make-font-json',
					'make-preview': self.getFontValues(self.fontSettings)
				};

			self.sendRequest(data, function(response) {
				if ('object' === typeof response.data) {
					self.loadFonts(response.data);
				}
			});
		}
	});

	$(document).ready(function() {
		Make.initStyles();
		Make.initFontLoader();
	});

	/**
	 * Asynchronous updating
	 */
	// Site Title
	api('blogname', function(value) {
		value.bind(function(to) {
			var $content = $('.site-title'),
				$logo = $('.custom-logo'),
				$branding = $('.site-branding'),
				$title, $to;
			if (! $content.length) {
				$title = ('<h1 class="site-title">');
				if ($logo.length > 0) {
					$logo.after($title);
				} else {
					$branding.prepend($title);
				}
				$content = $('.site-title');
			}
			if (! to) {
				$content.remove();
			}
			$to = $('<a>').text(to);
			$content.html($to);
		});
	});
	api('hide-site-title', function(value) {
		value.bind(function(to) {
			var $title = $('.site-title');
			if (true == to) {
				$title.addClass('screen-reader-text');
			} else {
				$title.removeClass('screen-reader-text');
			}
		});
	});

	// Tagline
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			var $content = $('.site-description');
			if ( ! $content.length ) {
				$('.site-branding').append('<span class="site-description">' + to + '</span>');
			}
			if ( ! to ) {
				$content.remove();
			}
			$content.text( to );
		});
	});
	api('hide-tagline', function(value) {
		value.bind(function(to) {
			var $tagline = $('.site-description');
			if (true == to) {
				$tagline.addClass('screen-reader-text');
			} else {
				$tagline.removeClass('screen-reader-text');
			}
		});
	});

	// Search Field
	api('label-search-field', function(value) {
		value.bind(function(to) {
			var $content = $('input.search-field');
			$content.attr('placeholder', to);
		});
	});

	// Mobile Menu Label
	api( 'navigation-mobile-label', function( value ) {
		value.bind( function( to ) {
			var $content = $('.menu-toggle');
			$content.text( to );
		} );
	} );

	// Sticky Label
	api( 'general-sticky-label', function( value ) {
		value.bind( function( to ) {
			var $content = $('.sticky-post-label');
			if ( ! $content.length ) {
				$('.post .entry-header').append('<span class="sticky-post-label">' + to + '</span>');
			}
			if ( ! to ) {
				$content.remove();
			}
			$content.text( to );
		} );
	} );

	// Read More Label
	api( 'label-read-more', function( value ) {
		value.bind( function( to ) {
			var $content = $('.more-link');
			$content.text( to );
		} );
	} );

	// Site Layout
	api('general-layout', function(value) {
		value.bind(function(to) {
			var $body = $('body');

			switch(to) {
				case 'full-width':
					$body.removeClass('boxed').addClass('full-width');
					break;
				case 'boxed':
					$body.removeClass('full-width').addClass('boxed');
					break;
			}
		});
	});

	// Header branding position
	api('header-branding-position', function(value) {
		value.bind(function(to) {
			var $body = $('body');

			if ('right' === to) {
				$body.addClass('branding-right');
			} else {
				$body.removeClass('branding-right');
			}
		});
	});

	// Header Bar text position
	api('header-bar-content-layout', function(value) {
		value.bind(function(to) {
			var $body = $('body');

			if ('flipped' === to) {
				$body.addClass('header-bar-flipped');
			} else {
				$body.removeClass('header-bar-flipped');
			}
		});
	});

	// Header Text
	api( 'header-text', function( value ) {
		value.bind( function( to ) {
			var $content = $('.header-text'),
				$headerBarMenu = $('.menu-header-bar-container');

			// Don't add text if the header bar menu exists
			if ( $headerBarMenu.length > 0 ) {
				return;
			}

			if ( ! $content.length ) {
				// Check for sub header
				var $container = $('.header-bar');
				if ( ! $container.length ) {
					$('#site-header').prepend('<div class="header-bar"><div class="container"></div></div>');
				}

				$('.header-bar .container').append('<span class="header-text">' + to + '</span>');
			}
			if ( ! to ) {
				$content.remove();
			}
			$content.html( to );
		} );
	} );

	// Footer Text
	api( 'footer-text', function( value ) {
		value.bind( function( to ) {
			var $content = $('.footer-text');
			if ( ! $content.length ) {
				$('.site-info').before('<div class="footer-text">' + to + '</div>');
			}
			if ( ! to ) {
				$content.remove();
			}
			$content.html( to );
		} );
	} );
})(jQuery, wp, MakePreview);
