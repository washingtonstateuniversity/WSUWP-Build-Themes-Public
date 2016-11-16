=== Make ===

Contributors: thethemefoundry
Tags: black, blue, green, gray, orange, red, white, yellow, dark, light, one-column, two-columns, three-columns, four-columns, left-sidebar, right-sidebar, fixed-layout, fluid-layout, responsive-layout, buddypress, custom-background, custom-colors, custom-header, custom-menu, editor-style, featured-images, flexible-header, full-width-template, sticky-post, theme-options, threaded-comments, translation-ready, photoblogging

Requires at least: 4.4
Tested up to: 4.5.3
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A free drag and drop WordPress theme for business websites.

== Description ==

Build a website that means business. With Make’s powerful drag and drop page builder and hundreds of Customizer options, you can effortlessly brand your site without touching a line of code. Add full-width banners, columns, and galleries to showcase your best work. The intuitive builder delivers clean, lean code — not shortcodes — so you can trust your content remains portable. Make lets you control page layout options, including sidebar display, on all your posts and pages. Choose from hundreds of Google Fonts and upload custom backgrounds everywhere. Built on a fully responsive grid, Make renders as beautifully on tablets and phone screens as it does on desktop. Make scales with your business and is fully compatible with popular plugins like WooCommerce, Gravity Forms, Contact Form 7, Jetpack, and others. For documentation on the page builder and getting started with Make, see: https://thethemefoundry.com/make-help/

== Installation ==

1. In your admin panel, go to Appearance > Themes and click the Add New button.
2. Click Upload and Choose File, then select the theme's .zip file. Click Install Now.
3. Click Activate to use your new theme right away.

== Frequently Asked Questions ==

Please see the Make documentation: https://thethemefoundry.com/make-help/

== Changelog ==

= 1.7.10 - November 16 2016 =
* Bug fix: Unconsistent HTML entity decoding was causing random data loss.

= 1.7.9 - October 26 2016 =
* Bug fix: Previewing builder pages was corrupting sections data.

= 1.7.8 - October 19 2016 =
* Improved: Overcome server POST data cap. See: https://github.com/thethemefoundry/make/issues/264.
* Bug fix: Drag handles for widgets in single widgetized columns were disappearing.

= 1.7.7 - July 15 2016 =
* Bug fix: The markup creating links in Gallery section items was broken.

= 1.7.6 - July 13 2016 =
* Improved: Minor stylesheet changes and enhancements. See the complete diff here: https://github.com/thethemefoundry/make/commit/3821a67298b484d142514f6f9bbcaade85cc0071#diff-8
* New filter: `make_builder_get_gallery_item_onclick` modifies the onclick attribute of Gallery section items.

= 1.7.5 - June 22 2016 =
* Improved: Better integration with the WP Retina 2x plugin for improved retina logos.
* Bug fix: Layout view algorithm was reverting to 'post' view when nothing matched.
* Updated: The latest list of Google fonts.

= 1.7.4 - June 7 2016 =
* Changed: Make now only supports WordPress 4.4 and higher.
* Bug fix: Current menu item font weight was not applied correctly.
* Bug fix: Some style settings were not updated correctly in the Customizer preview pane.
* New filter: `make_entry_thumbnail_size` modifies the image size used for post featured images.

= 1.7.3 - May 23 2016 =
* Bug fix: Display issue with the custom logo in Internet Explorer.
* Changed: Wording changes in various parts of the UI.
* Changed: Added notice that will appear for Make Plus users when they have an older version of the plugin installed.

= 1.7.2 - May 11 2016 =
* Bug fix: Display issues with the custom logo.

= 1.7.1 - May 5 2016 =
* Improved: Better detection for plugin integrations.
* Bug fix: Paragraph tags were getting stripped out on Builder pages in some cases.
* Bug fix: Typography style settings were not loading correctly into the content editor window.
* Bug fix: Breadcrumb toggles in the Layout panel were missing.
* Bug fix: Links in Make notices were having their href attributes stripped out.

= 1.7.0 - May 4 2016 =
* Changed: Big under-the-hood changes to the code for improved efficiency and maintainability. Many functions and hooks have been deprecated.
  * See the beta announcement for a complete list: https://thethemefoundry.com/blog/make-1-7-beta-1/
* Improved: Instant style previews in the Customizer.
* Improved: New interface for managing Social Icons.
* Improved: Support for the Custom Logo functionality introduced in WordPress 4.5.
* New feature: Color and typography settings for buttons.
* New feature: One-click migration of theme settings from parent to child theme.
* Improved: Yoast SEO breadcrumb now available for the Posts page.
* Improved: Gallery slider waits for all images to load before fully initializing.
* Improved: Google Fonts DNS is now pre-fetched when fonts are being used.
* Improved: Added theme support for partial widget refresh in the Customizer.
* New feature: Modify the search field label via the Customizer.
* New feature: Swedish translation. Props Erik Holmquist.
* Changed: Section title template now uses WP's the_archive_title() template tag.
* Changed: The default content width is now 960 instead of 620.
* Bux fix: PHP error messages on image attachment pages.
* Bux fix: Prevent the getimagesize read error that periodically appeared in some situations.
* Updated: Font Awesome icon library updated to version 4.6.1.
* Updated: The latest list of Google fonts.
* Changed: Added a notice that Make will soon drop support for WP 4.2 and 4.3.

= 1.6.7 - December 17 2015 =
* Bug fix: Builder content would lose paragraphs and line breaks in some situations when switching between editors.
* Improved: Better styling of the WooCommerce cart page on narrow screens < 480px wide.
* Updated: Font Awesome icon library updated to version 4.5.0.
* Updated: The latest list of Google fonts.
* Changed: Make now only supports WordPress 4.2 and higher.

= 1.6.6 - December 8 2015 =
* Updated: Styles in the Builder and Customizer are now compatible with WordPress 4.4.
* Improved: Range slider options in the Customizer now perform better.
* Fixed: Some font family options didn't work correctly if the Global body font was not set to the default.
* Fixed: Custom logo attachment ID couldn't be determined from its URL in some situations.
* Fixed: The Button format wrapped awkwardly to the next line if too long.
* Fixed: Builder script now requires WP's media views script as a dependency.

= 1.6.5 - October 23 2015 =
* Improved: Site title and tagline are now treated as screen reader text when hidden (instead of removed) for better accessibility.
* Improved: Better handling of admin notices.
* Updated: Larger theme screenshot.
* Updated: The latest list of Google fonts.
* Bug fix: Don't show Yoast SEO's breadcrumb on a static front page (since it is only "Home").
* Changed: Added a notice that Make will soon drop support for WP 4.0 and 4.1.

= 1.6.4 - August 31 2015 =
* New feature: Support for Yoast SEO's breadcrumb functionality.
  * Choose which views display the breadcrumb in the Customizer's Layout panel.
* Improved: Better responsive layout for the WooCommerce product grid, as well as cart and checkout pages.
* Bug fix: The Detail color option was not correctly setting the color for post categories and tags.

= 1.6.3 - August 18 2015 =
* Improved: Reduced the top margin on the Gallery Slider when it is displaying the navigation dots.
* Improved: Better translation strings and notes in the EXIF template tag.
* Updated: The latest list of Google fonts. Added Tamil and Thai font subset options.
* Changed: The EXIF data markup no longer wraps each data label in a span.
* Changed: Renamed the Menu locations to use the word "Navigation" instead of "Menu".
* Changed: Removed the `ttfmake` prefix from most 3rd party script IDs when registering them.
* Bug fix: FitVids was not successfully getting added as a script dependency. This caused some embedded videos to not scale correctly.
* Bug fix: Builder sections receiving focus due to an anchor tag were outlined in blue in Webkit browsers.

= 1.6.2.1 - August 4 2015 =
* Bug fix: Undefined function fatal error.
* Bug fix: Customizer control classes will now attempt to autoload if they have not been defined yet.

= 1.6.2 - July 31 2015 =
* Updated: Ensure compatibility with upcoming 4.3 version of WordPress:
  * Deprecate Make's Favicon and Apple Touch Icon options in favor of the new Site Icon option.
  * Adjust styling of Customizer sections.
* Updated: Font Awesome icon library updated to version 4.4.0.
* Updated: The latest list of Google fonts.
* Improved: Pages using the Builder template can now set a featured image (though it will not render on the page by default).
* Improved: Better handling of localization:
  * Parent and child themes have separate text domains.
  * Translation files for the parent theme can be stored in the child theme directory to prevent loss during updates.
  * Improved translator notes for some strings.
  * Ensure that all translated strings are escaped for security hardening.
* Improved: The Format Builder now uses the Global color scheme for color defaults.
* Bug fix: PHP fatal error in RSS feed when feed item contained embedded video.
* Bux fix: The `make_sanitize_text_allowed_tags` filter was not applied correctly.
* Bug fix: In rare cases, some WooCommerce pages were not rendering correctly if they used the Builder template.

= 1.6.1 - June 19 2015 =
* New feature: All default sections now have background image and background color options.
* Improved: Cycle2 slider script only loads when content requires it.
* Bug fix: H1 typography settings no longer affect the site title (which has its own typography settings).
* Updated: Mobile navigation script now matches latest version in the _s theme.
* New filter: `make_required_files` modifies the list of theme files to load.
* Changed: Prevent Make from activating if WordPress version is less than 4.0.

= 1.6.0 - June 2 2015 =
* Improved: Video embeds can now be set to specific widths and alignments while still behaving responsively on narrow screens.
* Improved: Make now enqueues the parent stylesheet if the child theme is version 1.1 or higher (instead of relying on a CSS @import statement).
* Improved: Several minor Builder UI tweaks.
* Fixed: Builder content preview panes sometimes weren't refreshing in Firefox.
* Fixed: Google Maps embeds now only resize to specific dimensions when added in post content.
* Fixed: Taxonomy icons now align with their lists properly in the post footer even when other meta elements are present.
* New filter: `make_builder_js_templates` modifies the array of JS templates loaded on the Page Builder screen.
* Updated: The latest list of Google fonts.
* Changed: Make now only supports WordPress 4.0 and higher.
* Changed: New theme screenshot. CC0 compatible.

= 1.5.2 - April 20 2015 =
* Added options for Arabic and Hebrew in Google Fonts character subsets.
* Fixed url encoding issues with Google Fonts URL.
* Fixed issue causing Format Builder's button URL to not update correctly.
* Fixed some instances of default stylesheet overriding Customizer typography settings.
* Fixed wrong version number for FontAwesome library in some places.
* Fixed fatal error in Customizer for WP versions before 4.0.
* Updated Google Fonts.
* Updated Dutch translation. Props @LeoOosterloo.

= 1.5.1 - February 27 2015 =
* Added Customizer options to remove header and footer boundary padding.
* Added style support for the new official Twitter plugin.
* Fixed broken mailto link for email icon in header/footer social icons.
* Fixed extra space added below footer in Chrome browsers.
* Added a notice that Make will drop support for WP 3.9 soon.

= 1.5.0 - February 16 2015 =
* Customizer overhaul
  * Added new typography options: line height, font weight, font style, letter spacing, word spacing, link underlining.
  * Added new typography option elements: widget title (separate from widget body), footer widget title, footer widget body.
  * Added font weight option for links.
  * Added Chosen.js for improved font choice UI.
  * Improved UI for other typography choices.
  * Added new color options: global link hover/focus, header bar links, footer links, sidebar color options, main menu color options.
  * Improved background image positioning options to account for both horizontal and vertical positioning.
  * Added opacity dimension to background colors.
  * Added option to customize "Read more" link text.
  * Added new main menu options: font weight and background color for current item.
  * Added options to change social icon size in header and footer.
  * Reorganized files and functions in Make's customizer module.
  * Reorganized Customizer panels, sections, and controls.
* Improved social profiles custom menu by enabling email and RSS icons.
* Improved handling of long content in Gallery section's item descriptions.
* Improved display of Banner section in narrow view.
* Fixed differing container widths on narrow view in Boxed mode.
* Deprecated function ttfmake_display_favicons.
* Deprecated function ttfmake_body_layout_classes.
* Added style support for Postmatic.
* Fixed styling of WooCommerce coupon field.
* Updated Cycle2 to 2.1.6.
* Updated FontAwesome to 4.3.0.
* Added Russian translation.
* Updated Dutch translation.

= 1.4.9 - January 26 2015 =
* Fixed bug that displayed page duplication info on custom post type screens.
* Fixed undefined function error in WP versions less than 4.0.
* Fixed doubled content in document title tag.
* Added formal system for showing/hiding admin notices.
* Added a notice when Make is installed on a site running a WordPress version older than 3.9.
* Added notices for when an older version of Make Plus is installed.

= 1.4.8 - January 12 2015 =
* Fixed bug preventing Builder section duplication in some cases
* Fixed line breaks in post comment count in Webkit browsers
* Fixed content editor in Builder overlay resizable
* Deprecated unused Builder functions
* Added new filter hook: `make_content_width`
* Added theme support for title tag
* Added Russian translation

= 1.4.7 - December 22 2014 =
* Fixed bug where Customizer's font-family options weren't showing correct selected choice
* Fixed issue with the custom logo not appearing correctly in some server environments
* Added additional inline documentation for some action and filter hooks
* Added Estonian translation

= 1.4.6 - December 12 2014 =
* Fixed several small compatibility issues in WordPress 4.1
* Fixed error thrown by Format Builder on some admin screens
* Fixed entry date layout issue in Chrome caused by excess whitespace in HTML

= 1.4.5 - December 1 2014 =
* Fixed raw CSS appearing in rich snippet content in some situations
* Fixed post meta alignment issues
* Fixed blurry Page Builder overlays in Safari
* Updated Google Fonts list
* Updated documentation links
* Updated Dutch translation

= 1.4.4 - November 7 2014 =
* Fixed inaccessible Attachment Display Settings panel when editing pages
* Added Dutch translation

= 1.4.3 - October 23 2014 =
* Improved text sanitization in some instances to allow more HTML tags and attributes
* Fixed incorrect text color being applied to Header Bar menu items
* Other minor code improvements

= 1.4.2 - October 6 2014 =
* Fixed Column configuration data not saving correctly in the Page Builder

= 1.4.1 - September 30 2014 =
* Added the Format Builder tool to the Visual Editor
* Added the Insert Icon button to the Visual Editor
* Removed old button, alert, and list formatting options in favor of the Format Builder
* Fixed minor issues with the Page Builder
* Updated German translations

= 1.4.0 - September 23 2014 =
* Updated Page Builder interface to improve performance, reduce clutter and better match WordPress' flat design

= 1.3.2 - September 9 2014 =
* Fixed fatal error in PHP 5.2.

= 1.3.1 - September 8 2014 =
* Fixed fatal error in PHP 5.2.

= 1.3.0 - September 3 2014 =
* Added support for WordPress 4.0 and Customizer panels
* Updated organization of Customizer options to utilize panels
* Added individual font family and size options for each header level (H1 - H6)
* Added other new font options: Tagline family, Sub-menu family and size, Widget family
* Added lots of new filter and action hooks for developers, along with inline documentation
* Updated FontAwesome library to 4.2. Includes support for 5 new social profile icons: Angel List, Last.fm, Slideshare, Twitch, and Yelp
* Fixed incorrect header font size defaults
* Fixed post navigation arrow orientation
* Fixed theme name in German translation

= 1.2.2 - August 14 2014 =
* Fixed a bug that caused some style and script assets to not load correctly on some web host configurations

= 1.2.1 - August 12 2014 =
* Fixed issue where Page Builder was hidden in certain situations when adding a new page
* Updated theme screenshot with CC0-compatible image
* Added missing text domain strings
* Removed query string parameters from Make Plus links

= 1.2.0 - August 9 2014 =
* Added ability to override some auxiliary stylesheets and scripts in child theme
* Added ability for CPTs to use the builder
* Added a "Maintain aspect ratio" option for banner sections for better responsive control
* Added IDs for individual text columns
* Added menu in the header bar
* Added filters to control font size
* Added notice for users trying to install Make Plus as a theme
* Fixed issue where captions on non-linked gallery items would not reveal on iOS
* Fixed issue where HTML added to Header/Footer text fields appeared as plain text in the Customizer
* Fixed alignment issues with submenus
* Fixed issue that caused submenus to fall below some content
* Fixed JS errors that occurred when rich text editor was turned off
* Fixed issue with broken default background color
* Improved the responsiveness of banner sections
* Improved consistency of textdomain handling

= 1.1.1 - July 9 2014 =
* Added Japanese translations
* Added license information file
* Fixed an incorrect label in the Customizer
* Fixed issue where footer text was double sanitized
* Fixed issue with dropdown menus being unreachable on an iPad

= 1.1.0 - June 24 2014 =
* Added control for showing comment count
* Added controls for positioning author, date, and comment count
* Added control for aligning featured images

= 1.0.11 - June 24 2014 =
* Improved messaging about Make Plus
* Improved sorting of footer links in builder sections
* Fixed ID sanitization bugs where ID values were greater than the maximum allowed integer value
* Fixed bug that did not allow anyone but Super Admins to save banner sections in Multisite
* Fixed a bug that defaulted comments to being hidden on posts
* Removed unnecessary class from banner sections
* Added a notice about sidebars not being available on builder template pages
* Added more social icons

= 1.0.10 - June 10 2014 =
* Improved consistency in styling between custom menus and default menus
* Improved JetPack share button styling
* Fixed an issue with dynamically added TinyMCE instances affecting already added instances
* Added link to social menu support documentation

= 1.0.9 - June 7 2014 =
* Fixed PHP notice edge case when $post object is not set when saving post
* Fixed issue of white font not showing on TinyMCE background
* Updated Font Awesome to 4.1.0

= 1.0.8 - June 4 2014 =
* Removed Make Plus information from the admin bar
* Added Make Plus information to the Customizer
* Improved aspects of the builder to prepare for additional premium features

= 1.0.7 - May 31 2014 =
* Fixed bug that prevented default font from showing in the editor styles
* Fixed Photon conflict that rendered custom logo functionality unusable
* Added filter builder section footer action links
* Added builder API function for removing builder sections
* Added information about Style Kits, Easy Digital Downloads, and Page Duplicator
* Added German and Finnish translations

= 1.0.6 - May 22 2014 =
* Added Make Plus information
* Fixed bug with images not displaying properly when aspect ratio was set to none in the Gallery section
* Removed sanitization of Customizer description section as these never receive user input

= 1.0.5 - May 20 2014 =
* Improved styling of widgets
* Improved whitespacing in the builder interface
* Improved language in builder
* Improved builder icons
* Added styles to make sure empty text columns hold their width
* Added functionality to disable header items in the font select lists
* Added filter for showing/hiding footer credit
* Added styling for WooCommerce product tag cloud

= 1.0.4 - May 16 2014 =
* Improved banner slide image position
* Added underline for footer link
* Added function to determine if companion plugin is installed
* Added TinyMCE buttons from builder to other TinyMCE instances
* Builder API improvements
  * Added ability for templates to exist outside of a parent or child theme
  * Added class for noting whether a builder page is displayed or not
  * Added wrapper functions for getting images used in the builder for easier filterability
  * Added actions for altering builder from 3rd party code
  * Added event for after section is removed
  * Removed save post actions when builder isn't being saved
  * Improved the abstraction of data saving functions for easier global use
  * Improved timing of events to prevent unfortunate code loading issues
  * Fixed bug with determining next/prev section that could cause a fatal error

= 1.0.3 - May 13 2014 =
* Improved tagline to be more readable
* Improved CSS code styling without any functional changes

= 1.0.2 - May 2 2014 =
* Removed RTL stylesheet as it was just a placeholder
* Improved testimonial display in the TinyMCE editor
* Fixed bug with broken narrow menu when using default menu

= 1.0.1 - May 2 2014 =
* Improved builder section descriptions
* Improved compatibility for JetPack "component" plugins
* Improved margin below widgets in narrow view
* Improved spacing of elements in the customizer
* Fixed bug with overlay in gallery section
* Fixed bug with secondary color being applied to responsive menus

= 1.0.0 - May 1 2014 =
* Initial release

== Upgrade Notice ==

= 1.7.7 =
Fixed a bug that was causing Gallery section item links to not work.

= 1.7.0 =
Big under-the-hood changes to the code for improved efficiency and maintainability. Many functions and hooks have been deprecated.
See the beta announcement for a complete list: https://thethemefoundry.com/blog/make-1-7-beta-1/

== Credits ==

The following third party resources are included in this theme. Each resource uses a GPL compatible license that is
listed below.

* Chosen      - MIT license (https://github.com/harvesthq/chosen/blob/master/LICENSE.md)
* Cycle2      - Dual MIT license (http://malsup.github.com/mit-license.txt) and GPL (http://malsup.github.com/gpl-license-v2.txt)
* Fitvids     - WTFPL (http://sam.zoy.org/wtfpl/)
* FontAwesome - MIT license (http://opensource.org/licenses/mit-license.html)
* Noneditable - LGPL (http://www.tinymce.com/license)
* Screenshot  - CC0 (http://creativecommons.org/publicdomain/zero/1.0/)
