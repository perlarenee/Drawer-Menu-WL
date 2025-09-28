<?php
/**
 * Plugin Name: Drawer Menu WL
 * Plugin URI: https://weblocomotive.com
 * Description: A reusable off-canvas drawer menu with widget areas that works with any WordPress theme, especially Divi.
 * Version: 1.2.2
 * Author: Web Locomotive
 * Author URI: https://weblocomotive.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: drawer-menu-wl
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 *
 * @package DrawerMenuWL
 * @since 1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'DRAWER_MENU_WL_VERSION', '1.2.2' );
define( 'DRAWER_MENU_WL_PATH', plugin_dir_path( __FILE__ ) );
define( 'DRAWER_MENU_WL_URL', plugin_dir_url( __FILE__ ) );
define( 'DRAWER_MENU_WL_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Drawer Menu WL Class
 *
 * @since 1.0.0
 */
class DrawerMenuWL {

	/**
	 * Flag to track if drawer menu has been rendered
	 *
	 * @since 1.2.2
	 * @var bool
	 */
	private static $drawer_menu_rendered = false;

	/**
	 * Constructor - Set up hooks and filters
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Enable shortcodes in menu items.
		add_filter( 'wp_nav_menu_items', array( $this, 'do_shortcode_in_menus' ), 10, 2 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'do_shortcode_in_menu_items' ), 10, 2 );

		// Activation/Deactivation hooks.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Admin hooks.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}
    
	/**
	 * Initialize plugin
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Register menu location.
		$this->register_menu_location();

		// Register shortcodes.
		add_shortcode( 'drawer_menu', array( $this, 'drawer_menu_shortcode' ) );
		add_shortcode( 'drawer_hamburger', array( $this, 'drawer_hamburger_shortcode' ) );

		// Note: load_plugin_textdomain() is no longer needed for WordPress.org hosted plugins.
		// WordPress automatically loads translations when needed.
	}

	/**
	 * Register custom menu location
	 *
	 * @since 1.0.0
	 */
	public function register_menu_location() {
		register_nav_menus(
			array(
				'drawer-menu-wl' => __( 'Drawer Menu WL', 'drawer-menu-wl' ),
			)
		);
	}
    
	/**
	 * Register widget areas
	 *
	 * @since 1.0.0
	 */
	public function register_widgets() {
		register_sidebar(
			array(
				'name'          => __( 'Drawer Menu Top', 'drawer-menu-wl' ),
				'id'            => 'drawer-menu-top',
				'description'   => __( 'Widget area at the top of the drawer menu', 'drawer-menu-wl' ),
				'before_widget' => '<div id="%1$s" class="drawer-menu-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="drawer-menu-widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => __( 'Drawer Menu Bottom', 'drawer-menu-wl' ),
				'id'            => 'drawer-menu-bottom',
				'description'   => __( 'Widget area at the bottom of the drawer menu', 'drawer-menu-wl' ),
				'before_widget' => '<div id="%1$s" class="drawer-menu-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="drawer-menu-widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	/**
	 * Enqueue CSS and JS assets
	 *
	 * @since 1.0.0
	 */
	public function enqueue_assets() {
		// Enqueue CSS.
		wp_enqueue_style(
			'drawer-menu-wl-css',
			DRAWER_MENU_WL_URL . 'assets/css/drawer-menu.css',
			array(),
			DRAWER_MENU_WL_VERSION
		);

		// Enqueue JS.
		wp_enqueue_script(
			'drawer-menu-wl-js',
			DRAWER_MENU_WL_URL . 'assets/js/drawer-menu.js',
			array(),
			DRAWER_MENU_WL_VERSION,
			true
		);
	}
    
	/**
	 * Drawer menu shortcode
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function drawer_menu_shortcode( $atts ) {
		// Check if drawer menu has already been rendered
		if ( self::$drawer_menu_rendered ) {
			return '<!-- Drawer Menu WL: Only one drawer menu per page is allowed -->';
		}

		// Mark as rendered
		self::$drawer_menu_rendered = true;

		$atts = shortcode_atts(
			array(
				'menu_location'       => 'drawer-menu-wl',
				'show_hamburger'      => 'true',
				'background_color'    => '#1184F0',
				'background_opacity'  => '0.95',
				'text_color'          => '#FEFEFE',
				'hamburger_color'     => '#fff',
				'hamburger_position'  => 'right',
				'drawer_width_desktop' => '45vw',
				'drawer_width_mobile' => '100vw',
				'animation_speed'     => '0.45s',
			),
			$atts,
			'drawer_menu'
		);

		// Sanitize attributes.
		$atts = $this->sanitize_shortcode_attributes( $atts );


		// Start output buffering.
		ob_start();

		// Include template with custom settings.
		$this->render_drawer_menu( $atts );

		return ob_get_clean();
	}
    
	/**
	 * Standalone hamburger shortcode
	 *
	 * @since 1.2.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function drawer_hamburger_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'position'   => 'relative',
				'top'        => '',
				'right'      => '',
				'left'       => '',
				'bottom'     => '',
				'color'      => '#333',
				'color_open' => '#fff',
				'size'       => '40px',
				'padding'    => '10px',
				'margin'     => '0',
				'z_index'    => '9999',
				'show_text'  => 'false',
			),
			$atts,
			'drawer_hamburger'
		);

		// Sanitize attributes.
		$atts = $this->sanitize_hamburger_attributes( $atts );

		// Generate unique ID for this instance.
		$unique_id = 'drawer-hamburger-' . wp_unique_id();
        
		// Build position styles.
		$position_styles = array();
		$position_styles[] = 'position: ' . esc_attr( $atts['position'] );

		if ( ! empty( $atts['top'] ) ) {
			$position_styles[] = 'top: ' . esc_attr( $atts['top'] );
		}
		if ( ! empty( $atts['right'] ) ) {
			$position_styles[] = 'right: ' . esc_attr( $atts['right'] );
		}
		if ( ! empty( $atts['left'] ) ) {
			$position_styles[] = 'left: ' . esc_attr( $atts['left'] );
		}
		if ( ! empty( $atts['bottom'] ) ) {
			$position_styles[] = 'bottom: ' . esc_attr( $atts['bottom'] );
		}

		$position_styles[] = 'z-index: ' . esc_attr( $atts['z_index'] );
		$position_styles[] = 'padding: ' . esc_attr( $atts['padding'] );
		$position_styles[] = 'margin: ' . esc_attr( $atts['margin'] );
		$position_styles[] = 'width: ' . esc_attr( $atts['size'] );
		$position_styles[] = 'height: ' . esc_attr( $atts['size'] );

		$position_style = implode( '; ', $position_styles );
        
		ob_start();
		?>
		<style>
			.<?php echo esc_attr( $unique_id ); ?> {
				display: inline-block;
				cursor: pointer;
				<?php echo esc_attr( $position_style ); ?>
			}
            
            /* Lower z-index when menu is open so it doesn't show above drawer */
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> {
                z-index: 1;
            }
            
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon {
                position: relative;
                width: 100%;
                height: 2px;
                background-color: <?php echo esc_attr( $atts['color'] ); ?>;
                transition: all 0.35s;
                display: block;
                top: 50%;
                transform: translateY(-50%);
            }
            
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:before,
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:after {
                content: "";
                position: absolute;
                width: 100%;
                height: 2px;
                background-color: <?php echo esc_attr( $atts['color'] ); ?>;
                left: 0;
                transition: transform 0.35s;
                transform-origin: 50% 50%;
            }
            
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:before {
                transform: translateY(-8px);
            }
            
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:after {
                transform: translateY(8px);
            }
            
            /* When menu is open */
            #offcanvas-mobile-nav input.hamburger:checked ~ * .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon,
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon {
                background-color: transparent;
            }
            
            #offcanvas-mobile-nav input.hamburger:checked ~ * .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:before,
            #offcanvas-mobile-nav input.hamburger:checked ~ * .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:after,
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:before,
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:after {
                background-color: <?php echo esc_attr($atts['color_open']); ?>;
            }
            
            #offcanvas-mobile-nav input.hamburger:checked ~ * .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:before,
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:before {
                transform: rotate(45deg);
            }
            
            #offcanvas-mobile-nav input.hamburger:checked ~ * .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:after,
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-icon:after {
                transform: rotate(-45deg);
            }
            
            <?php if ($atts['show_text'] === 'true') : ?>
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-text {
                text-transform: uppercase;
                font-size: 0.8em;
                text-align: center;
                display: block;
                margin-top: 8px;
                transition: opacity 0.25s;
            }
            
            .<?php echo esc_attr( $unique_id ); ?> .hamburger-text-close {
                display: none;
            }
            
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-text-open {
                display: none;
            }
            
            body.drawer-menu-open .<?php echo esc_attr( $unique_id ); ?> .hamburger-text-close {
                display: block;
            }
            <?php endif; ?>
        </style>
        
		<div class="<?php echo esc_attr( $unique_id ); ?> drawer-menu-trigger">
			<span class="hamburger-icon"></span>
			<?php if ( 'true' === $atts['show_text'] ) : ?>
				<span class="hamburger-text hamburger-text-open"><?php esc_html_e( 'menu', 'drawer-menu-wl' ); ?></span>
				<span class="hamburger-text hamburger-text-close"><?php esc_html_e( 'close', 'drawer-menu-wl' ); ?></span>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
    }
    
	/**
	 * Enable shortcodes in nav menu items
	 *
	 * @since 1.0.0
	 * @param string $items Menu items HTML.
	 * @param object $args Menu arguments.
	 * @return string Processed menu items.
	 */
	public function do_shortcode_in_menus( $items, $args ) {
		return do_shortcode( $items );
	}

	/**
	 * Enable shortcodes in individual menu item titles
	 *
	 * @since 1.0.0
	 * @param string $item_output Menu item HTML output.
	 * @param object $item Menu item object.
	 * @return string Processed menu item.
	 */
	public function do_shortcode_in_menu_items( $item_output, $item ) {
		return do_shortcode( $item_output );
	}
    
	/**
	 * Render drawer menu template
	 *
	 * @since 1.0.0
	 * @param array $args Sanitized shortcode arguments.
	 */
	private function render_drawer_menu( $args = array() ) {

		// Extract sanitized values (these should all be set since they come from sanitization).
		$menu_location = $args['menu_location'];
		$show_hamburger = $args['show_hamburger'];

		// Add class for hidden hamburger.
		$drawer_class = ( 'false' === $show_hamburger ) ? 'drawer-hidden-trigger' : '';

		// Ensure the base stylesheet is enqueued before adding inline styles.
		if ( ! wp_style_is( 'drawer-menu-wl-css', 'enqueued' ) ) {
			wp_enqueue_style(
				'drawer-menu-wl-css',
				DRAWER_MENU_WL_URL . 'assets/css/drawer-menu.css',
				array(),
				DRAWER_MENU_WL_VERSION
			);
		}

		// Generate dynamic CSS with sanitized values.
		$dynamic_css = $this->generate_dynamic_css( $args );


		// Output CSS directly since wp_add_inline_style may have timing issues.
		echo '<style type="text/css">' . esc_html( wp_strip_all_tags( $dynamic_css ) ) . '</style>';

		// Load template.
		include DRAWER_MENU_WL_PATH . 'templates/drawer-menu.php';
	}
    
	/**
	 * Generate dynamic CSS for drawer menu
	 *
	 * @since 1.2.2
	 * @param array $args Drawer menu arguments.
	 * @return string Generated CSS.
	 */
	private function generate_dynamic_css( $args ) {

		// Use sanitized values directly.
		$background_color = $args['background_color'];
		$background_opacity = $args['background_opacity'];
		$text_color = $args['text_color'];
		$hamburger_color = $args['hamburger_color'];
		$hamburger_position = $args['hamburger_position'];
		$drawer_width_desktop = $args['drawer_width_desktop'];
		$drawer_width_mobile = $args['drawer_width_mobile'];
		$animation_speed = $args['animation_speed'];


		// Convert hex to rgba for background.
		$bg_rgb = $this->hex_to_rgb( $background_color );
		$background_rgba = "rgba({$bg_rgb['r']}, {$bg_rgb['g']}, {$bg_rgb['b']}, {$background_opacity})";

		// Determine positioning.
		$hamburger_side = ( 'left' === $hamburger_position ) ? 'left: 10%' : 'right: 10%';
		$drawer_side = ( 'left' === $hamburger_position ) ? 'left' : 'right';
		$drawer_transform = ( 'left' === $hamburger_position ) ? 'translateX(-100vw)' : 'translateX(100vw)';
		$drawer_radius = ( 'left' === $hamburger_position ) ? 'border-bottom-right-radius' : 'border-bottom-left-radius';

		// Generate CSS.
		$css = "
			#offcanvas-mobile-nav .drawer-list {
				background-color: {$background_rgba} !important;
				{$drawer_side}: 0;
				transform: {$drawer_transform};
				{$drawer_radius}: 100vw;
				transition: width 0.475s ease-out, transform {$animation_speed} ease, border-radius 0.8s 0.1s ease;
			}

			@media (min-width: 768px) {
				#offcanvas-mobile-nav .drawer-list {
					width: {$drawer_width_desktop};
				}
			}

			@media (max-width: 767px) {
				#offcanvas-mobile-nav .drawer-list {
					width: {$drawer_width_mobile};
				}
			}

			#offcanvas-mobile-nav input.hamburger:checked ~ .drawer-list {
				transform: translateX(0);
				{$drawer_radius}: 0;
			}

			#offcanvas-mobile-nav label.hamburger {
				{$hamburger_side};
			}

			#offcanvas-mobile-nav label.hamburger i,
			#offcanvas-mobile-nav label.hamburger i:before,
			#offcanvas-mobile-nav label.hamburger i:after {
				background-color: {$hamburger_color};
			}

			#offcanvas-mobile-nav label.hamburger text open {
				color: {$hamburger_color};
			}

			#offcanvas-mobile-nav input.hamburger:checked ~ label.hamburger i:before,
			#offcanvas-mobile-nav input.hamburger:checked ~ label.hamburger i:after {
				background-color: {$text_color};
			}

			#offcanvas-mobile-nav input.hamburger:checked ~ label.hamburger text close {
				color: {$text_color};
			}

			#offcanvas-mobile-nav.drawer-hidden-trigger label.hamburger {
				opacity: 0;
				pointer-events: none;
				transition: opacity 0.3s ease;
			}

			#offcanvas-mobile-nav.drawer-hidden-trigger input.hamburger:checked ~ label.hamburger {
				opacity: 1;
				pointer-events: auto;
			}
		";

		return $css;
	}

	/**
	 * Sanitize shortcode attributes for drawer menu
	 *
	 * @since 1.2.2
	 * @param array $atts Raw shortcode attributes.
	 * @return array Sanitized attributes.
	 */
	private function sanitize_shortcode_attributes( $atts ) {
		$sanitized = array();

		// Sanitize menu location - allow hyphens for menu slugs.
		$sanitized['menu_location'] = sanitize_text_field( $atts['menu_location'] );

		// Sanitize boolean show_hamburger.
		$sanitized['show_hamburger'] = in_array( $atts['show_hamburger'], array( 'true', 'false' ), true ) ? $atts['show_hamburger'] : 'true';

		// Sanitize colors (hex values) - fix the logic.
		$bg_color = sanitize_hex_color( $atts['background_color'] );
		$sanitized['background_color'] = $bg_color ? $bg_color : '#1184F0';

		$text_color = sanitize_hex_color( $atts['text_color'] );
		$sanitized['text_color'] = $text_color ? $text_color : '#FEFEFE';

		$hamburger_color = sanitize_hex_color( $atts['hamburger_color'] );
		$sanitized['hamburger_color'] = $hamburger_color ? $hamburger_color : '#fff';

		// Sanitize opacity (0-1).
		$opacity = floatval( $atts['background_opacity'] );
		$sanitized['background_opacity'] = ( $opacity >= 0 && $opacity <= 1 ) ? $opacity : 0.95;

		// Sanitize position.
		$sanitized['hamburger_position'] = in_array( $atts['hamburger_position'], array( 'left', 'right' ), true ) ? $atts['hamburger_position'] : 'right';

		// Sanitize CSS values.
		$sanitized['drawer_width_desktop'] = $this->sanitize_css_value( $atts['drawer_width_desktop'], '45vw' );
		$sanitized['drawer_width_mobile'] = $this->sanitize_css_value( $atts['drawer_width_mobile'], '100vw' );
		$sanitized['animation_speed'] = $this->sanitize_css_value( $atts['animation_speed'], '0.45s' );

		return $sanitized;
	}

	/**
	 * Sanitize hamburger shortcode attributes
	 *
	 * @since 1.2.2
	 * @param array $atts Raw shortcode attributes.
	 * @return array Sanitized attributes.
	 */
	private function sanitize_hamburger_attributes( $atts ) {
		$sanitized = array();

		// Sanitize position.
		$valid_positions = array( 'relative', 'absolute', 'fixed', 'sticky' );
		$sanitized['position'] = in_array( $atts['position'], $valid_positions, true ) ? $atts['position'] : 'relative';

		// Sanitize positioning values.
		$sanitized['top'] = $this->sanitize_css_value( $atts['top'], '' );
		$sanitized['right'] = $this->sanitize_css_value( $atts['right'], '' );
		$sanitized['left'] = $this->sanitize_css_value( $atts['left'], '' );
		$sanitized['bottom'] = $this->sanitize_css_value( $atts['bottom'], '' );

		// Sanitize colors.
		$color = sanitize_hex_color( $atts['color'] );
		$sanitized['color'] = $color ? $color : '#333';

		$color_open = sanitize_hex_color( $atts['color_open'] );
		$sanitized['color_open'] = $color_open ? $color_open : '#fff';

		// Sanitize size and spacing.
		$sanitized['size'] = $this->sanitize_css_value( $atts['size'], '40px' );
		$sanitized['padding'] = $this->sanitize_css_value( $atts['padding'], '10px' );
		$sanitized['margin'] = $this->sanitize_css_value( $atts['margin'], '0' );

		// Sanitize z-index.
		$sanitized['z_index'] = absint( $atts['z_index'] ) ? absint( $atts['z_index'] ) : 9999;

		// Sanitize boolean show_text.
		$sanitized['show_text'] = in_array( $atts['show_text'], array( 'true', 'false' ), true ) ? $atts['show_text'] : 'false';

		return $sanitized;
	}

	/**
	 * Sanitize CSS value
	 *
	 * @since 1.2.2
	 * @param string $value CSS value to sanitize.
	 * @param string $default Default value if sanitization fails.
	 * @return string Sanitized CSS value.
	 */
	private function sanitize_css_value( $value, $default = '' ) {
		// Allow common CSS units and values - improved regex for better decimal support.
		if ( preg_match( '/^[0-9]*\.?[0-9]+(px|em|rem|%|vh|vw|vmin|vmax|s|ms)$/', $value ) ) {
			return $value;
		}

		// Allow 'auto', 'inherit', 'initial', 'none', '0'.
		if ( in_array( $value, array( 'auto', 'inherit', 'initial', 'none', '0' ), true ) ) {
			return $value;
		}

		// Allow simple numbers (for things like z-index).
		if ( is_numeric( $value ) ) {
			return $value;
		}

		return $default;
	}

	/**
	 * Convert hex color to RGB array
	 *
	 * @since 1.0.0
	 * @param string $hex Hex color value.
	 * @return array RGB values.
	 */
	private function hex_to_rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );
		if ( 6 !== strlen( $hex ) ) {
			return array(
				'r' => 0,
				'g' => 0,
				'b' => 0,
			);
		}

		return array(
			'r' => hexdec( substr( $hex, 0, 2 ) ),
			'g' => hexdec( substr( $hex, 2, 2 ) ),
			'b' => hexdec( substr( $hex, 4, 2 ) ),
		);
	}

	/**
	 * Darken a hex color
	 *
	 * @since 1.0.0
	 * @param string $hex Hex color value.
	 * @param int    $percent Percentage to darken.
	 * @return string Darkened hex color.
	 */
	private function darken_color( $hex, $percent ) {
		$hex = str_replace( '#', '', $hex );
		if ( 6 !== strlen( $hex ) ) {
			return $hex; // Invalid hex.
		}

		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );

		$r = max( 0, min( 255, $r - ( $r * $percent / 100 ) ) );
		$g = max( 0, min( 255, $g - ( $g * $percent / 100 ) ) );
		$b = max( 0, min( 255, $b - ( $b * $percent / 100 ) ) );

		return '#' . str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT )
				  . str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT )
				  . str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );
	}
    
	/**
	 * Add admin menu
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'Drawer Menu Settings', 'drawer-menu-wl' ),
			__( 'Drawer Menu WL', 'drawer-menu-wl' ),
			'manage_options',
			'drawer-menu-wl',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Admin page content
	 *
	 * @since 1.0.0
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Drawer Menu WL', 'drawer-menu-wl' ); ?></h1>
			<div class="card">
				<h2><?php esc_html_e( 'How to Use', 'drawer-menu-wl' ); ?></h2>
				<p><?php esc_html_e( 'Use the shortcodes anywhere in your site:', 'drawer-menu-wl' ); ?></p>
                
                <h3><?php esc_html_e('Main Drawer Menu', 'drawer-menu-wl'); ?></h3>
                <code>[drawer_menu]</code>
                
                <h3><?php esc_html_e('Standalone Hamburger Icon', 'drawer-menu-wl'); ?></h3>
                <code>[drawer_hamburger]</code>
                <p><?php esc_html_e('Use this to place a hamburger icon anywhere that controls the drawer menu.', 'drawer-menu-wl'); ?></p>
                
                <h3><?php esc_html_e('Shortcode Options', 'drawer-menu-wl'); ?></h3>
                <ul>
                    <li><code>show_hamburger="true"</code> - <?php esc_html_e('Show default hamburger (false to use custom trigger)', 'drawer-menu-wl'); ?></li>
                    <li><code>background_color="#1184F0"</code> - <?php esc_html_e('Menu background color', 'drawer-menu-wl'); ?></li>
                    <li><code>background_opacity="0.95"</code> - <?php esc_html_e('Background opacity (0-1)', 'drawer-menu-wl'); ?></li>
                    <li><code>text_color="#FEFEFE"</code> - <?php esc_html_e('Menu text color', 'drawer-menu-wl'); ?></li>
                    <li><code>hamburger_color="#fff"</code> - <?php esc_html_e('Hamburger icon color', 'drawer-menu-wl'); ?></li>
                    <li><code>hamburger_position="right"</code> - <?php esc_html_e('Position (left or right)', 'drawer-menu-wl'); ?></li>
                    <li><code>drawer_width_desktop="45vw"</code> - <?php esc_html_e('Width on desktop', 'drawer-menu-wl'); ?></li>
                    <li><code>drawer_width_mobile="100vw"</code> - <?php esc_html_e('Width on mobile', 'drawer-menu-wl'); ?></li>
                    <li><code>animation_speed="0.45s"</code> - <?php esc_html_e('Animation duration', 'drawer-menu-wl'); ?></li>
                </ul>
                
                <h3><?php esc_html_e('Using Custom Triggers', 'drawer-menu-wl'); ?></h3>
                <p><?php esc_html_e('To use your own button/element to open the drawer:', 'drawer-menu-wl'); ?></p>
                <ol>
                    <li><?php esc_html_e('Set show_hamburger="false" in the shortcode', 'drawer-menu-wl'); ?></li>
                    <li><?php esc_html_e('Add class "drawer-menu-trigger" to any element you want to use as a trigger', 'drawer-menu-wl'); ?></li>
                </ol>
                <p><strong><?php esc_html_e('Example:', 'drawer-menu-wl'); ?></strong></p>
                <code>[drawer_menu show_hamburger="false"]</code><br>
                <code>&lt;button class="drawer-menu-trigger"&gt;Open Menu&lt;/button&gt;</code>
                
                <h3><?php esc_html_e('Hamburger Shortcode Options', 'drawer-menu-wl'); ?></h3>
                <ul>
                    <li><code>position="relative"</code> - <?php esc_html_e('CSS position (fixed, relative, absolute, sticky)', 'drawer-menu-wl'); ?></li>
                    <li><code>top="20px"</code> - <?php esc_html_e('Top position', 'drawer-menu-wl'); ?></li>
                    <li><code>right="20px"</code> - <?php esc_html_e('Right position', 'drawer-menu-wl'); ?></li>
                    <li><code>left=""</code> - <?php esc_html_e('Left position', 'drawer-menu-wl'); ?></li>
                    <li><code>bottom=""</code> - <?php esc_html_e('Bottom position', 'drawer-menu-wl'); ?></li>
                    <li><code>color="#333"</code> - <?php esc_html_e('Icon color when closed', 'drawer-menu-wl'); ?></li>
                    <li><code>color_open="#fff"</code> - <?php esc_html_e('Icon color when open', 'drawer-menu-wl'); ?></li>
                    <li><code>size="40px"</code> - <?php esc_html_e('Icon size', 'drawer-menu-wl'); ?></li>
                    <li><code>padding="10px"</code> - <?php esc_html_e('Padding around icon', 'drawer-menu-wl'); ?></li>
                    <li><code>margin="0"</code> - <?php esc_html_e('Margin', 'drawer-menu-wl'); ?></li>
                    <li><code>z_index="9999"</code> - <?php esc_html_e('Z-index', 'drawer-menu-wl'); ?></li>
                    <li><code>show_text="false"</code> - <?php esc_html_e('Show menu/close text labels', 'drawer-menu-wl'); ?></li>
                </ul>
                
                <h3><?php esc_html_e('Using in WordPress Menus', 'drawer-menu-wl'); ?></h3>
                <p><?php esc_html_e('You can add the hamburger shortcode directly to menu items:', 'drawer-menu-wl'); ?></p>
                <ol>
                    <li><?php esc_html_e('Go to Appearance → Menus', 'drawer-menu-wl'); ?></li>
                    <li><?php esc_html_e('Add a Custom Link', 'drawer-menu-wl'); ?></li>
                    <li><?php esc_html_e('In "Navigation Label" field, enter: [drawer_hamburger]', 'drawer-menu-wl'); ?></li>
                    <li><?php esc_html_e('Leave URL as # or remove it', 'drawer-menu-wl'); ?></li>
                </ol>
                
                <h3><?php esc_html_e('Setup Steps', 'drawer-menu-wl'); ?></h3>
                <ol>
                    <li><?php esc_html_e('Go to Appearance → Menus and create/assign a menu to "Drawer Menu WL"', 'drawer-menu-wl'); ?></li>
                    <li><?php esc_html_e('Add widgets to "Drawer Menu Top" and "Drawer Menu Bottom" areas if needed', 'drawer-menu-wl'); ?></li>
                    <li><?php esc_html_e('Add [drawer_menu] shortcode to your Divi Theme Builder header or anywhere else', 'drawer-menu-wl'); ?></li>
                </ol>
            </div>
        </div>
        <?php
    }
    
	/**
	 * Plugin activation
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		$this->register_menu_location();
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}
}

// Initialize the plugin.
new DrawerMenuWL();