=== Drawer Menu WL ===
Contributors: weblocomotive
Tags: menu, hamburger, drawer, off-canvas, mobile
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A beautiful, reusable off-canvas drawer menu plugin for WordPress with full customization options and Divi Theme Builder compatibility.

== Description ==

Drawer Menu WL creates stunning off-canvas drawer menus that slide smoothly from either side of your screen. Perfect for modern websites, especially those built with Divi Theme Builder.

= Key Features =

* **Smooth animations** - Beautiful slide-in transitions from left or right
* **Fully customizable** - Control colors, opacity, width, animation speed, and positioning
* **Mobile responsive** - Adapts perfectly to all screen sizes
* **Widget areas** - Add custom content to top and bottom of menu
* **Standalone hamburger** - Place animated hamburger icons anywhere
* **Custom triggers** - Use any element to open the drawer
* **Menu integration** - Use shortcodes directly in WordPress menus
* **Keyboard support** - Close with ESC key
* **Click outside to close** - Enhanced user experience
* **Divi compatible** - Works seamlessly with Divi Theme Builder

= Two Powerful Shortcodes =

**Main Drawer Menu:** (Only one per page allowed)
`[drawer_menu]`

**Standalone Hamburger Icon:** (Multiple instances allowed)
`[drawer_hamburger]`

= Use Cases =

* Theme Builder headers with inline hamburger icons
* Fixed hamburger icons in screen corners
* Custom menu buttons that open drawers
* Multiple trigger points throughout your site
* Inline menu items within WordPress navigation

= Customization Options =

* Background color and opacity
* Text and hamburger icon colors
* Left or right positioning
* Desktop and mobile widths
* Animation speed control
* Hamburger icon styling and positioning

= Widget Areas =

The plugin provides two widget areas accessible via Appearance → Widgets:
* **Drawer Menu Top** - Content at the top of the drawer
* **Drawer Menu Bottom** - Content at the bottom of the drawer

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/drawer-menu-wl/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Appearance → Menus** and assign a menu to "Drawer Menu WL" location
4. Add the shortcode `[drawer_menu]` to your theme or page
5. Optionally add widgets to the "Drawer Menu Top" and "Drawer Menu Bottom" areas

== Frequently Asked Questions ==

= How do I customize the drawer colors? =

Use the shortcode parameters:
`[drawer_menu background_color="#2c3e50" text_color="#ecf0f1" hamburger_color="#e74c3c"]`

= Can I make the drawer slide from the left? =

Yes! Use the hamburger_position parameter:
`[drawer_menu hamburger_position="left"]`

= How do I hide the default hamburger and use my own trigger? =

Set show_hamburger to false and add the 'drawer-menu-trigger' class to any element:
`[drawer_menu show_hamburger="false"]`
`<button class="drawer-menu-trigger">Open Menu</button>`

= Can I use a hamburger icon in my WordPress menu? =

Yes! Add a Custom Link to your menu and use `[drawer_hamburger]` as the Navigation Label.

= How do I position a hamburger icon in a fixed location? =

Use the standalone hamburger shortcode with positioning:
`[drawer_hamburger position="fixed" top="20px" right="20px"]`

= Is this compatible with Divi? =

Absolutely! The plugin was designed with Divi Theme Builder in mind and works perfectly with it.

= Can I use multiple drawer menus on the same page? =

No, only one `[drawer_menu]` shortcode per page is allowed to prevent conflicts. However, you can use multiple `[drawer_hamburger]` shortcodes to create multiple trigger points for the same drawer.

== Screenshots ==

1. Drawer menu open with custom content and navigation
2. Hamburger icon animation states
3. Admin settings and shortcode documentation
4. Widget areas configuration
5. Mobile responsive design

== Changelog ==

= 1.2.2 =
* Fixed z-index issue with standalone hamburger when drawer is open
* Standalone hamburger now hides behind drawer panel when menu is active
* Enhanced security and WordPress coding standards compliance
* Added proper readme.txt file
* Improved accessibility with ARIA labels
* Added uninstall cleanup
* Limited to one drawer menu per page to prevent conflicts

= 1.2.1 =
* Added shortcode support in WordPress menu items
* Can now use [drawer_hamburger] directly in menu navigation labels
* Documentation updated with menu usage instructions

= 1.2.0 =
* Added standalone [drawer_hamburger] shortcode for flexible placement
* Full positioning control (fixed, relative, absolute, sticky)
* Customizable hamburger styling (color, size, padding, margin)
* Optional menu/close text labels
* Added body class 'drawer-menu-open' when drawer is active
* Toggle functionality - clicking hamburger now closes menu if open

= 1.1.0 =
* Added custom trigger support - use any element with class 'drawer-menu-trigger'
* Added 'show_hamburger' option to hide default hamburger
* Improved click outside detection to exclude custom triggers
* Enhanced flexibility for custom implementations

= 1.0.0 =
* Initial release
* Off-canvas drawer menu with animations
* Widget area support
* Divi Theme Builder compatibility

== Upgrade Notice ==

= 1.2.2 =
This version includes important security improvements and WordPress coding standards compliance. Update recommended.

= 1.2.0 =
Major feature update! New standalone hamburger shortcode with full positioning control and styling options.

== Support ==

For support, feature requests, or bug reports:

* Visit the [WordPress.org support forum](https://wordpress.org/support/plugin/drawer-menu-wl/)
* Check the [GitHub repository](https://github.com/perlarenee/Drawer-Menu-WL) for documentation and issues
* Contact Web Locomotive through our website at [weblocomotive.com](https://weblocomotive.com)