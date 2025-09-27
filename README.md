=== Drawer Menu WL ===
Contributors: wl
Tags: drawer, menu, navigation, hamburger, divi, responsive, mobile
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A beautiful, reusable off-canvas drawer menu with widget areas that works with any WordPress theme.

== Description ==

Drawer Menu WL provides a sleek, animated off-canvas drawer menu that's perfect for any WordPress theme, especially Divi. Features include:

* **Smooth off-canvas animation** - Slides in from the right with beautiful transitions
* **Widget areas** - Add content to the top and bottom of your menu
* **Smart positioning** - Automatically adjusts for admin bars and fixed headers
* **Customizable colors** - Change colors via shortcode parameters
* **Mobile responsive** - Adapts to different screen sizes
* **Easy integration** - Simple shortcode implementation
* **Divi compatible** - Works perfectly with Divi Theme Builder
* **Click outside to close** - Enhanced user experience
* **Escape key support** - Close menu with keyboard

== Installation ==

1. Upload the `drawer-menu-wl` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance → Menus and assign a menu to the "Drawer Menu WL" location
4. Add the shortcode [drawer_menu] to your theme

== Usage ==

**Basic usage:**
`[drawer_menu]`

**With custom colors:**
`[drawer_menu background_color="#ff6b35" text_color="#ffffff" hamburger_color="#fff"]`

**Setup steps:**
1. Create or assign a menu to "Drawer Menu WL" location
2. Optionally add widgets to "Drawer Menu Top" and "Drawer Menu Bottom" widget areas
3. Add the shortcode to your Divi Theme Builder header template or anywhere else

== Features ==

* Off-canvas drawer animation
* Customizable colors via shortcode
* Widget areas for additional content
* Smart header detection and positioning
* Mobile and desktop responsive
* Works with Divi fixed headers
* Click outside and ESC key to close

== Frequently Asked Questions ==

= How do I change the menu colors? =
Use the shortcode parameters: `[drawer_menu background_color="#your-color" text_color="#your-color"]`

= Does it work with Divi Theme Builder? =
Yes! Add the shortcode to a Code Module in your Theme Builder header template.

= Can I add content above and below the menu? =
Yes! Use the "Drawer Menu Top" and "Drawer Menu Bottom" widget areas.

== Changelog ==

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
* Added custom trigger support - use any element with class 'drawer-menu-trigger' to open menu
* Added 'show_hamburger' option to hide default hamburger and only show as close button
* Improved click outside detection to exclude custom triggers
* Enhanced flexibility for custom implementations

= 1.0.0 =
* Initial release
* Off-canvas drawer menu with animations
* Widget area support
* Divi Theme Builder compatibility
* Smart header positioning
