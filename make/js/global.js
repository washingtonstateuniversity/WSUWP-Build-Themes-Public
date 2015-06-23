/*!
 * Script for initializing globally-used functions and libs.
 *
 * @since 1.0.0
 */
/* global jQuery, ttfmakeGlobal */
(function($, Make) {
	'use strict';

	var ttfmake = {
		/**
		 *
		 */
		cache: {},

		/**
		 *
		 */
		init: function() {
			this.cacheElements();
			this.bindEvents();
		},

		/**
		 *
		 */
		cacheElements: function() {
			this.cache = {
				$window: $(window),
				$document: $(document)
			};
		},

		/**
		 *
		 */
		bindEvents: function() {
			var self = this;

			self.cache.$document.ready(function() {
				self.navigationInit();
				self.skipLinkFocusFix();
				self.navigationHoverFix();
				self.fitVidsInit($('.ttfmake-embed-wrapper'), Make);
			} );

			// Infinite Scroll support
			self.cache.$document.on('post-load', function() {
				// FitVids
				var $elements = $('.ttfmake-embed-wrapper:not(:has(".fluid-width-video-wrapper"))');
				self.fitVidsInit($elements, Make);
			});
		},

		/**
		 * Initialize the mobile menu functionality.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		navigationInit: function() {
			var container, button, menu;

			container = document.getElementById( 'site-navigation' );
			if ( ! container ) {
				return;
			}

			button = container.getElementsByTagName( 'span' )[0];
			if ( 'undefined' === typeof button ) {
				return;
			}

			menu = container.getElementsByTagName( 'ul' )[0];

			// Hide menu toggle button if menu is empty and return early.
			if ( 'undefined' === typeof menu ) {
				button.style.display = 'none';
				return;
			}

			if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
				menu.className += ' nav-menu';
			}

			button.onclick = function() {
				if ( -1 !== container.className.indexOf( 'toggled' ) ) {
					container.className = container.className.replace( ' toggled', '' );
				} else {
					container.className += ' toggled';
				}
			};
		},

		/**
		 * Fix tab destination after 'Skip to content' link has been clicked.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		skipLinkFocusFix: function() {
			var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
				is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
				is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1,
				eventMethod;

			if ( ( is_webkit || is_opera || is_ie ) && 'undefined' !== typeof( document.getElementById ) ) {
				eventMethod = ( window.addEventListener ) ? 'addEventListener' : 'attachEvent';
				window[ eventMethod ]( 'hashchange', function() {
					var element = document.getElementById( location.hash.substring( 1 ) );

					if ( element ) {
						if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
							element.tabIndex = -1;
						}

						element.focus();
					}
				}, false );
			}
		},

		/**
		 * Bind a click event to nav menu items with sub menus.
		 *
		 * Fixes an issue with the sub menus not appearing correctly in some situations on iPads.
		 *
		 * @link http://blog.travelvictoria.com.au/2012/03/31/make-sure-your-websites-drop-down-menus-work-on-an-ipad/
		 *
		 * @since
		 *
		 * @return void
		 */
		navigationHoverFix: function() {
			this.cache.$dropdown = this.cache.$dropdown || $('li:has(ul)', '#site-navigation');
			this.cache.$dropdown.on('click', function() {
				return true;
			});
		},

		/**
		 * Initialize FitVids.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		fitVidsInit: function($elements, Make) {
			// Make sure lib is loaded.
			if (! $.fn.fitVids) {
				return;
			}

			var $container = $elements || $('.ttfmake-embed-wrapper'),
				selectors = Make.fitvids.selectors || '',
				args = {};

			// Get custom selectors
			if (selectors) {
				args.customSelector = selectors;
			}

			// Run FitVids
			$container.fitVids(args);

			// Fix padding issue with Blip.tv. Note that this *must* happen after Fitvids runs.
			// The selector finds the Blip.tv iFrame, then grabs the .fluid-width-video-wrapper div sibling.
			$container.find('.fluid-width-video-wrapper:nth-child(2)').css({ 'paddingTop': 0 });
		}
	};

	ttfmake.init();
})(jQuery, ttfmakeGlobal);