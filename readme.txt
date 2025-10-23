=== Drawer Menu WL ===
Contributors: weblocomotive
Tags: menu, hamburger, drawer, off-canvas, mobile
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.3.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A beautiful, reusable off-canvas drawer menu plugin for WordPress with full customization options and Divi Theme Builder compatibility.

== Description ==

Drawer Menu WL creates stunning off-canvas drawer menus that slide smoothly from either side of your screen. Perfect for modern websites, especially those built with Divi Theme Builder.

= Key Features =

* **Smooth animations** - Beautiful slide-in transitions with auto-close on link click
* **Fully customizable** - Control colors, opacity, width, animation speed, and positioning
* **Mobile responsive** - Adapts perfectly to all screen sizes
* **Widget areas** - Add custom content to top and bottom of menu
* **Standalone hamburger** - Place animated hamburger icons anywhere with flexible sizing
* **Custom triggers** - Use any element to open the drawer
* **Menu integration** - Use shortcodes directly in WordPress menus
* **Keyboard support** - Close with ESC key, full accessibility support
* **Click outside to close** - Enhanced user experience
* **Auto-close on click** - Menu closes smoothly after clicking navigation links
* **Divi compatible** - Works seamlessly with Divi Theme Builder

= Powerful Shortcodes =

**Standalone Hamburger Icon:** (Multiple instances allowed)
`[drawer_hamburger]`

= Shortcode Parameters =

The `[drawer_hamburger]` shortcode supports extensive customization:

**Positioning:**
* `position` - relative, absolute, fixed, sticky (default: relative)
* `top`, `right`, `bottom`, `left` - CSS positioning values
* `z_index` - Stacking order (default: 9999)

**Sizing:**
* `size` - Sets both width and height (default: 40px)
* `width` - Override width specifically (overrides size)
* `height` - Override height specifically (overrides size)

**Styling:**
* `color` - Hamburger line color when closed (default: #333)
* `color_open` - Hamburger line color when open (default: #fff)
* `padding` - Inner spacing (default: 10px)
* `margin` - Outer spacing, supports CSS shorthand (default: 0)

**Display:**
* `show_text` - Show menu/close text labels (true/false, default: false)

**Examples:**

Standard square icon:
`[drawer_hamburger size="40px"]`

Rectangular icon:
`[drawer_hamburger width="60px" height="35px"]`

Fixed position with styling:
`[drawer_hamburger position="fixed" top="20px" right="20px" color="#000" color_open="#fff"]`

With margin shorthand:
`[drawer_hamburger margin="10px 20px 30px 40px"]`

Wide button style:
`[drawer_hamburger width="80px" height="30px" padding="15px" margin="0 auto"]`

= Use Cases =

* Theme Builder headers with inline hamburger icons
* Fixed hamburger icons in screen corners
* Custom menu buttons that open drawers
* Multiple trigger points throughout your site
* Inline menu items within WordPress navigation
* Responsive navigation with auto-close behavior

= Admin Settings =

Configure your drawer menu via **Settings → Drawer Menu WL**:

**General Settings:**
* Enable/disable drawer menu
* Show/hide default hamburger icon
* Set hamburger position (left or right)

**Appearance Settings:**
* Background color
* Background opacity (0-1)
* Text color
* Hamburger icon color

**Advanced Settings:**
* Drawer width for desktop
* Drawer width for mobile
* Animation speed

= Widget Areas =

The plugin provides two widget areas accessible via Appearance → Widgets:
* **Drawer Menu Top** - Content at the top of the drawer
* **Drawer Menu Bottom** - Content at the bottom of the drawer

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/drawer-menu-wl/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Settings → Drawer Menu WL** to configure the drawer
4. Go to **Appearance → Menus** and assign a menu to "Drawer Menu WL" location
5. Add `[drawer_hamburger]` shortcodes where you want trigger buttons
6. Optionally add widgets to the "Drawer Menu Top" and "Drawer Menu Bottom" areas

== Frequently Asked Questions ==

= How do I customize the drawer colors? =

Go to **Settings → Drawer Menu WL** and adjust the appearance settings, or use the admin panel to configure background color, text color, and hamburger color.

= Can I make the drawer slide from the left? =

Yes! In **Settings → Drawer Menu WL**, set the Hamburger Position to "Left".

= How do I hide the default hamburger and use my own trigger? =

In settings, uncheck "Show Default Hamburger" and add the 'drawer-menu-trigger' class to any element:
`<button class="drawer-menu-trigger">Open Menu</button>`

= Can I use a hamburger icon in my WordPress menu? =

Yes! Add a Custom Link to your menu and use `[drawer_hamburger]` as the Navigation Label.

= How do I create a rectangular hamburger icon? =

Use the width and height parameters:
`[drawer_hamburger width="60px" height="35px"]`

= Can I use CSS margin shorthand? =

Yes! The margin parameter accepts all CSS shorthand formats:
`[drawer_hamburger margin="10px 20px"]`
`[drawer_hamburger margin="5px 10px 15px 20px"]`

= Does the menu close automatically when I click a link? =

Yes! As of version 1.3.0, the menu automatically closes with a smooth transition after clicking any navigation link.

= How do I position a hamburger icon in a fixed location? =

Use the standalone hamburger shortcode with positioning:
`[drawer_hamburger position="fixed" top="20px" right="20px"]`

= Is this compatible with Divi? =

Absolutely! The plugin was designed with Divi Theme Builder in mind and works perfectly with it.

= Can I use multiple hamburger triggers on the same page? =

Yes! You can use multiple `[drawer_hamburger]` shortcodes throughout your site. They all control the same drawer menu.

== Screenshots ==

1. Drawer menu open with custom content and navigation
2. Hamburger icon animation states
3. Admin settings panel
4. Widget areas configuration
5. Mobile responsive design
6. Rectangular hamburger icon example
7. Auto-close behavior demonstration

== Changelog ==

= 1.3.0 =
* Added auto-close functionality - menu closes smoothly after clicking navigation links
* Added separate width and height parameters for rectangular hamburger icons
* Enhanced margin/padding to accept CSS shorthand notation (e.g., "10px 20px 30px 40px")
* Added centralized settings panel (Settings → Drawer Menu WL)
* Improved drawer menu auto-output in footer
* Enhanced accessibility features
* Better focus trap management
* Improved keyboard navigation support
* Updated documentation with new shortcode parameters

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

= 1.3.0 =
Major feature update! Auto-close on link click, rectangular hamburger icons with separate width/height controls, CSS shorthand support for margin/padding, and new centralized settings panel. Recommended update for all users.

= 1.2.2 =
This version includes important security improvements and WordPress coding standards compliance. Update recommended.

= 1.2.0 =
Major feature update! New standalone hamburger shortcode with full positioning control and styling options.

== Support ==

For support, feature requests, or bug reports:

* Visit the [WordPress.org support forum](https://wordpress.org/support/plugin/drawer-menu-wl/)
* Check the [GitHub repository](https://github.com/perlarenee/Drawer-Menu-WL) for documentation and issues
* Contact Web Locomotive through our website at [weblocomotive.com](https://weblocomotive.com)