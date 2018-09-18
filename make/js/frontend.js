/*!
 * Script for initializing globally-used functions and libs.
 *
 * @since 1.0.0
 */

/* global jQuery, MakeFrontEnd */

var MakeFrontEnd = MakeFrontEnd || {};

(function($, MakeFrontEnd) {
	'use strict';

	if ( ! MakeFrontEnd ) { return; }

	/**
	 * MakeFrontEnd
	 *
	 * Starts with the following data properties added via script localization:
	 * - fitvids
	 */

	var Make = $.extend(MakeFrontEnd, {
		/**
		 * Object for storing data.
		 *
		 * @since 1.0.0
		 */
		cache: {},

		/**
		 * Initialize the script.
		 *
		 * @since 1.0.0
		 */
		init: function() {
			this.cacheElements();
			this.bindEvents();
		},

		/**
		 * Cache jQuery objects for later.
		 *
		 * @since 1.0.0
		 */
		cacheElements: function() {
			this.cache = {
				$window: $(window),
				$document: $(document)
			};
		},

		/**
		 * Set up event listeners.
		 *
		 * @since 1.0.0
		 */
		bindEvents: function() {
			var self = this;

			self.cache.$document.ready(function() {
				self.navigationInit();
				self.skipLinkFocusFix();
				self.navigationHoverFix();
				self.fitVidsInit( $('.ttfmake-embed-wrapper') );
				self.handleGalleryOverlayOnMobileDevices();
			} );

			// Infinite Scroll support
			self.cache.$document.on('post-load', function() {
				// FitVids
				var $elements = $('.ttfmake-embed-wrapper:not(:has(".fluid-width-video-wrapper"))');
				self.fitVidsInit($elements);
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
			var container, button, menu, links, subMenus;

			button = document.getElementsByClassName( 'menu-toggle' )[0];
			if ( 'undefined' === typeof button ) {
				return;
			}

			container = button.parentElement;
			if ( ! container ) {
				return;
			}

			menu = container.getElementsByTagName( 'ul' )[0];

			// Hide menu toggle button if menu is empty and return early.
			if ( 'undefined' === typeof menu ) {
				button.style.display = 'none';
				return;
			}

			menu.setAttribute( 'aria-expanded', 'false' );
			if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
				menu.className += ' nav-menu';
			}

			button.onclick = function() {
				if ( -1 !== container.className.indexOf( 'toggled' ) ) {
					container.className = container.className.replace( ' toggled', '' );
					button.setAttribute( 'aria-expanded', 'false' );
					menu.setAttribute( 'aria-expanded', 'false' );
				} else {
					container.className += ' toggled';
					button.setAttribute( 'aria-expanded', 'true' );
					menu.setAttribute( 'aria-expanded', 'true' );
				}
			};

			// Get all the link elements within the menu.
			links    = menu.getElementsByTagName( 'a' );
			subMenus = menu.getElementsByTagName( 'ul' );

			var $links = $(links);

			$links.focus(function() {
				$(this).parents('li').addClass('focus');
			}).blur(function() {
				$(this).parents('li').removeClass('focus');
			});

			// Set menu items with submenus to aria-haspopup="true".
			for ( var i = 0, len = subMenus.length; i < len; i++ ) {
				subMenus[i].parentNode.setAttribute( 'aria-haspopup', 'true' );
			}
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
				is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

			if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
				window.addEventListener( 'hashchange', function() {
					var id = location.hash.substring( 1 ),
						element;

					if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
						return;
					}

					element = document.getElementById( id );

					if ( element ) {
						if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
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
		fitVidsInit: function($elements) {
			// Make sure lib is loaded.
			if (! $.fn.fitVids) {
				return;
			}

			var self = this,
				$container = $elements || $('.ttfmake-embed-wrapper'),
				selectors = self.fitvids.selectors || '',
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
		},

		/**
		 * Handle gallery overlay show / hide on mobile devices "hover".
		 *
		 * @since  1.8.6
		 *
		 * @return void
		 */
		handleGalleryOverlayOnMobileDevices: function() {
			$('.builder-section-gallery .builder-gallery-item').on('touchstart', function() {
				var $this = $(this);

				if ($this.find('.builder-gallery-content').length) {
					$this.addClass('touchstart');
				}
			});

			$('body:not(.builder-gallery-item)').on('touchstart', function() {
				$('.builder-gallery-item').removeClass('touchstart');
			});
		}
	});

	Make.init();
})(jQuery, MakeFrontEnd);