# Drawer Menu WL

A beautiful, reusable off-canvas drawer menu plugin for WordPress with full customization options and Divi Theme Builder compatibility.

![Version](https://img.shields.io/badge/version-1.2.2-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0%2B-blue.svg)
![PHP](https://img.shields.io/badge/php-7.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0-green.svg)

## Features

- ✨ **Smooth off-canvas animation** - Slides in from left or right with beautiful transitions
- 🎨 **Fully customizable** - Colors, opacity, width, animation speed, and positioning
- 📱 **Mobile responsive** - Adapts perfectly to all screen sizes
- 🔧 **Widget areas** - Add custom content to top and bottom of menu
- 🎯 **Standalone hamburger** - Place hamburger icons anywhere with full styling control
- 🔗 **Custom triggers** - Use any element to open the drawer
- 📋 **Menu integration** - Use shortcodes directly in WordPress menus
- ⌨️ **Keyboard support** - Close with ESC key
- 🖱️ **Click outside to close** - Enhanced user experience
- 🎨 **Divi compatible** - Works perfectly with Divi Theme Builder

## Installation

### From GitHub
1. Download or clone this repository
2. Upload the `drawer-menu-wl` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to **Appearance → Menus** and assign a menu to "Drawer Menu WL" location
5. Add the shortcode `[drawer_menu]` to your theme

### From WordPress Repository
1. Go to **Plugins → Add New** in your WordPress admin
2. Search for "Drawer Menu WL"
3. Click **Install Now** and then **Activate**
4. Follow steps 4-5 above

## Shortcode Documentation

### Main Drawer Menu: `[drawer_menu]`

**Note:** Only one `[drawer_menu]` per page is allowed to prevent conflicts.

#### Basic Usage
```
[drawer_menu]
```

#### All Options
| Parameter | Default | Description |
|-----------|---------|-------------|
| `show_hamburger` | `true` | Show default hamburger icon |
| `background_color` | `#1184F0` | Menu background color |
| `background_opacity` | `0.95` | Background opacity (0-1) |
| `text_color` | `#FEFEFE` | Menu text color |
| `hamburger_color` | `#fff` | Hamburger icon color |
| `hamburger_position` | `right` | Position (left or right) |
| `drawer_width_desktop` | `45vw` | Width on desktop |
| `drawer_width_mobile` | `100vw` | Width on mobile |
| `animation_speed` | `0.45s` | Animation duration |

#### Examples

**Hide default hamburger (use custom trigger):**
```
[drawer_menu show_hamburger="false"]
```

**Custom colors:**
```
[drawer_menu background_color="#2c3e50" text_color="#ecf0f1" hamburger_color="#e74c3c"]
```

**Slide from left:**
```
[drawer_menu hamburger_position="left"]
```

**Complete customization:**
```
[drawer_menu 
    show_hamburger="true" 
    background_color="#2c3e50" 
    background_opacity="0.98" 
    text_color="#ecf0f1" 
    hamburger_color="#e74c3c" 
    hamburger_position="right" 
    drawer_width_desktop="60vw" 
    drawer_width_mobile="90vw" 
    animation_speed="0.3s"
]
```

### Standalone Hamburger: `[drawer_hamburger]`

Place an animated hamburger icon anywhere that controls the drawer menu.

#### All Options
| Parameter | Default | Description |
|-----------|---------|-------------|
| `position` | `relative` | CSS position (fixed, relative, absolute, sticky) |
| `top` | - | Top position |
| `right` | - | Right position |
| `left` | - | Left position |
| `bottom` | - | Bottom position |
| `color` | `#333` | Icon color when closed |
| `color_open` | `#fff` | Icon color when open |
| `size` | `40px` | Icon size |
| `padding` | `10px` | Padding around icon |
| `margin` | `0` | Margin |
| `z_index` | `9999` | Z-index |
| `show_text` | `false` | Show menu/close text labels |

#### Examples

**Inline with custom styling:**
```
[drawer_hamburger color="#333" size="30px" padding="10px" margin="0 15px 0 0"]
```

**Fixed position top-right:**
```
[drawer_hamburger position="fixed" top="20px" right="20px" color="#fff" color_open="#000"]
```

**With text labels:**
```
[drawer_hamburger show_text="true"]
```

### Custom Triggers

Use any element as a trigger by adding the `drawer-menu-trigger` class:

```html
[drawer_menu show_hamburger="false"]

<button class="drawer-menu-trigger">Open Menu</button>
<span class="drawer-menu-trigger">☰</span>
<div class="drawer-menu-trigger">Custom Icon</div>
```

### WordPress Menu Integration

1. Go to **Appearance → Menus**
2. Add a **Custom Link**
3. In "Navigation Label" field, enter: `[drawer_hamburger]`
4. Leave URL as `#` or remove it
5. Save menu

**With custom styling:**
```
[drawer_hamburger color="#333" size="25px"]
```

## Widget Areas

The plugin provides two widget areas:

- **Drawer Menu Top** - Content at the top of the drawer
- **Drawer Menu Bottom** - Content at the bottom of the drawer

Add widgets at **Appearance → Widgets**

## Use Cases

- **Theme Builder header** with inline hamburger
- **Fixed hamburger icon** in corner of screen
- **Custom menu button** that opens drawer
- **Multiple trigger points** throughout site
- **Inline menu item** within WordPress navigation

## Changelog

### 1.2.2
- Fixed z-index issue with standalone hamburger when drawer is open
- Standalone hamburger now hides behind drawer panel when menu is active
- Enhanced security and WordPress coding standards compliance
- Added proper readme.txt file
- Improved accessibility with ARIA labels
- Added uninstall cleanup
- Limited to one drawer menu per page to prevent conflicts

### 1.2.1
- Added shortcode support in WordPress menu items
- Can now use [drawer_hamburger] directly in menu navigation labels
- Documentation updated with menu usage instructions

### 1.2.0
- Added standalone [drawer_hamburger] shortcode for flexible placement
- Full positioning control (fixed, relative, absolute, sticky)
- Customizable hamburger styling (color, size, padding, margin)
- Optional menu/close text labels
- Added body class 'drawer-menu-open' when drawer is active
- Toggle functionality - clicking hamburger now closes menu if open

### 1.1.0
- Added custom trigger support - use any element with class 'drawer-menu-trigger'
- Added 'show_hamburger' option to hide default hamburger
- Improved click outside detection to exclude custom triggers
- Enhanced flexibility for custom implementations

### 1.0.0
- Initial release
- Off-canvas drawer menu with animations
- Widget area support
- Divi Theme Builder compatibility

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher

## License

GPL v2 or later

## Author

Web Locomotive

## Development & Support

- **GitHub Repository:** [Drawer-Menu-WL](https://github.com/perlarenee/Drawer-Menu-WL)
- **Issues & Bug Reports:** [GitHub Issues](https://github.com/perlarenee/Drawer-Menu-WL/issues)
- **WordPress Plugin Directory:** [Drawer Menu WL](https://wordpress.org/plugins/drawer-menu-wl/)
- **Author:** [Web Locomotive](https://weblocomotive.com)

## Contributing

Contributions are welcome! Please:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

Please follow WordPress coding standards and include appropriate documentation.