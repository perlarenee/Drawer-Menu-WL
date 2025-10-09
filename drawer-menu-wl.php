<?php
/**
 * Plugin Name: Drawer Menu WL
 * Plugin URI: https://github.com/perlarenee/Drawer-Menu-WL
 * Description: A reusable off-canvas drawer menu with widget areas that works with any WordPress theme, especially Divi.
 * Version: 1.3.0
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
define( 'DRAWER_MENU_WL_VERSION', '1.3.0' );
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
	 * Constructor - Set up hooks and filters
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Auto-output drawer menu on all pages
		add_action( 'wp_footer', array( $this, 'output_drawer_menu' ), 1 );

		// Enable shortcodes in menu items.
		add_filter( 'wp_nav_menu_items', array( $this, 'do_shortcode_in_menus' ), 10, 2 );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'do_shortcode_in_menu_items' ), 10, 2 );

		// Activation/Deactivation hooks.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Admin hooks.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
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
		add_shortcode( 'drawer_hamburger', array( $this, 'drawer_hamburger_shortcode' ) );
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
	 * Enqueue admin scripts
	 *
	 * @since 1.3.0
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( 'settings_page_drawer-menu-wl' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		
		wp_add_inline_script( 'wp-color-picker', '
			jQuery(document).ready(function($) {
				$(".color-picker").wpColorPicker();
			});
		' );
	}

	/**
	 * Output drawer menu automatically in footer
	 *
	 * @since 1.3.0
	 */
	public function output_drawer_menu() {
		// Get options from settings
		$options = get_option( 'drawer_menu_wl_options', $this->get_default_options() );
		
		// Don't output if explicitly disabled
		if ( empty( $options['enabled'] ) ) {
			return;
		}
		
		// Render the drawer menu
		$this->render_drawer_menu( $options );
	}

	/**
	 * Get default options
	 *
	 * @since 1.3.0
	 * @return array
	 */
	private function get_default_options() {
		return array(
			'enabled'              => true,
			'menu_location'        => 'drawer-menu-wl',
			'show_hamburger'       => true,
			'background_color'     => '#1184F0',
			'background_opacity'   => '0.95',
			'text_color'           => '#FEFEFE',
			'hamburger_color'      => '#fff',
			'hamburger_position'   => 'right',
			'drawer_width_desktop' => '45vw',
			'drawer_width_mobile'  => '100vw',
			'animation_speed'      => '0.45s',
		);
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
		// Merge with defaults
		$args = wp_parse_args( $args, $this->get_default_options() );

		// Extract sanitized values
		$menu_location = $args['menu_location'];
		$show_hamburger = $args['show_hamburger'];

		// Add class for hidden hamburger
		$drawer_class = empty( $show_hamburger ) ? 'drawer-hidden-trigger' : '';

		// Ensure the base stylesheet is enqueued
		if ( ! wp_style_is( 'drawer-menu-wl-css', 'enqueued' ) ) {
			wp_enqueue_style(
				'drawer-menu-wl-css',
				DRAWER_MENU_WL_URL . 'assets/css/drawer-menu.css',
				array(),
				DRAWER_MENU_WL_VERSION
			);
		}

		// Generate dynamic CSS
		$dynamic_css = $this->generate_dynamic_css( $args );

		// Output CSS
		echo '<style type="text/css">' . wp_strip_all_tags( $dynamic_css ) . '</style>';

		// Load template
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
		// Use values from args
		$background_color = $args['background_color'];
		$background_opacity = $args['background_opacity'];
		$text_color = $args['text_color'];
		$hamburger_color = $args['hamburger_color'];
		$hamburger_position = $args['hamburger_position'];
		$drawer_width_desktop = $args['drawer_width_desktop'];
		$drawer_width_mobile = $args['drawer_width_mobile'];
		$animation_speed = $args['animation_speed'];

		// Convert hex to rgba for background
		$bg_rgb = $this->hex_to_rgb( $background_color );
		$background_rgba = "rgba({$bg_rgb['r']}, {$bg_rgb['g']}, {$bg_rgb['b']}, {$background_opacity})";

		// Determine positioning
		$hamburger_side = ( 'left' === $hamburger_position ) ? 'left: 10%' : 'right: 10%';
		$drawer_side = ( 'left' === $hamburger_position ) ? 'left' : 'right';
		$drawer_transform = ( 'left' === $hamburger_position ) ? 'translateX(-100vw)' : 'translateX(100vw)';
		$drawer_radius = ( 'left' === $hamburger_position ) ? 'border-bottom-right-radius' : 'border-bottom-left-radius';

		// Generate CSS
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
	 * Sanitize hamburger shortcode attributes
	 *
	 * @since 1.2.2
	 * @param array $atts Raw shortcode attributes.
	 * @return array Sanitized attributes.
	 */
	private function sanitize_hamburger_attributes( $atts ) {
		$sanitized = array();

		// Sanitize position
		$valid_positions = array( 'relative', 'absolute', 'fixed', 'sticky' );
		$sanitized['position'] = in_array( $atts['position'], $valid_positions, true ) ? $atts['position'] : 'relative';

		// Sanitize positioning values
		$sanitized['top'] = $this->sanitize_css_value( $atts['top'], '' );
		$sanitized['right'] = $this->sanitize_css_value( $atts['right'], '' );
		$sanitized['left'] = $this->sanitize_css_value( $atts['left'], '' );
		$sanitized['bottom'] = $this->sanitize_css_value( $atts['bottom'], '' );

		// Sanitize colors
		$color = sanitize_hex_color( $atts['color'] );
		$sanitized['color'] = $color ? $color : '#333';

		$color_open = sanitize_hex_color( $atts['color_open'] );
		$sanitized['color_open'] = $color_open ? $color_open : '#fff';

		// Sanitize size and spacing
		$sanitized['size'] = $this->sanitize_css_value( $atts['size'], '40px' );
		$sanitized['padding'] = $this->sanitize_css_value( $atts['padding'], '10px' );
		$sanitized['margin'] = $this->sanitize_css_value( $atts['margin'], '0' );

		// Sanitize z-index
		$sanitized['z_index'] = absint( $atts['z_index'] ) ? absint( $atts['z_index'] ) : 9999;

		// Sanitize boolean show_text
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
		// Allow common CSS units and values
		if ( preg_match( '/^[0-9]*\.?[0-9]+(px|em|rem|%|vh|vw|vmin|vmax|s|ms)$/', $value ) ) {
			return $value;
		}

		// Allow 'auto', 'inherit', 'initial', 'none', '0'
		if ( in_array( $value, array( 'auto', 'inherit', 'initial', 'none', '0' ), true ) ) {
			return $value;
		}

		// Allow simple numbers
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
	 * Register plugin settings
	 *
	 * @since 1.3.0
	 */
	public function register_settings() {
		register_setting(
			'drawer_menu_wl_options',
			'drawer_menu_wl_options',
			array( $this, 'sanitize_options' )
		);

		// General Settings Section
		add_settings_section(
			'drawer_menu_general',
			__( 'General Settings', 'drawer-menu-wl' ),
			array( $this, 'general_section_callback' ),
			'drawer-menu-wl'
		);

		add_settings_field(
			'enabled',
			__( 'Enable Drawer Menu', 'drawer-menu-wl' ),
			array( $this, 'checkbox_field' ),
			'drawer-menu-wl',
			'drawer_menu_general',
			array( 'field' => 'enabled', 'label' => __( 'Display drawer menu on site', 'drawer-menu-wl' ) )
		);

		add_settings_field(
			'show_hamburger',
			__( 'Show Default Hamburger', 'drawer-menu-wl' ),
			array( $this, 'checkbox_field' ),
			'drawer-menu-wl',
			'drawer_menu_general',
			array( 'field' => 'show_hamburger', 'label' => __( 'Display built-in hamburger icon', 'drawer-menu-wl' ) )
		);

		add_settings_field(
			'hamburger_position',
			__( 'Hamburger Position', 'drawer-menu-wl' ),
			array( $this, 'select_field' ),
			'drawer-menu-wl',
			'drawer_menu_general',
			array(
				'field' => 'hamburger_position',
				'options' => array(
					'left'  => __( 'Left', 'drawer-menu-wl' ),
					'right' => __( 'Right', 'drawer-menu-wl' ),
				),
			)
		);

		// Appearance Section
		add_settings_section(
			'drawer_menu_appearance',
			__( 'Appearance Settings', 'drawer-menu-wl' ),
			array( $this, 'appearance_section_callback' ),
			'drawer-menu-wl'
		);

		add_settings_field(
			'background_color',
			__( 'Background Color', 'drawer-menu-wl' ),
			array( $this, 'color_field' ),
			'drawer-menu-wl',
			'drawer_menu_appearance',
			array( 'field' => 'background_color' )
		);

		add_settings_field(
			'background_opacity',
			__( 'Background Opacity', 'drawer-menu-wl' ),
			array( $this, 'text_field' ),
			'drawer-menu-wl',
			'drawer_menu_appearance',
			array( 'field' => 'background_opacity', 'description' => __( 'Value between 0 and 1 (e.g., 0.95)', 'drawer-menu-wl' ) )
		);

		add_settings_field(
			'text_color',
			__( 'Text Color', 'drawer-menu-wl' ),
			array( $this, 'color_field' ),
			'drawer-menu-wl',
			'drawer_menu_appearance',
			array( 'field' => 'text_color' )
		);

		add_settings_field(
			'hamburger_color',
			__( 'Hamburger Color', 'drawer-menu-wl' ),
			array( $this, 'color_field' ),
			'drawer-menu-wl',
			'drawer_menu_appearance',
			array( 'field' => 'hamburger_color' )
		);

		// Advanced Section
		add_settings_section(
			'drawer_menu_advanced',
			__( 'Advanced Settings', 'drawer-menu-wl' ),
			array( $this, 'advanced_section_callback' ),
			'drawer-menu-wl'
		);

		add_settings_field(
			'drawer_width_desktop',
			__( 'Drawer Width (Desktop)', 'drawer-menu-wl' ),
			array( $this, 'text_field' ),
			'drawer-menu-wl',
			'drawer_menu_advanced',
			array( 'field' => 'drawer_width_desktop', 'description' => __( 'e.g., 45vw, 400px', 'drawer-menu-wl' ) )
		);

		add_settings_field(
			'drawer_width_mobile',
			__( 'Drawer Width (Mobile)', 'drawer-menu-wl' ),
			array( $this, 'text_field' ),
			'drawer-menu-wl',
			'drawer_menu_advanced',
			array( 'field' => 'drawer_width_mobile', 'description' => __( 'e.g., 100vw, 300px', 'drawer-menu-wl' ) )
		);

		add_settings_field(
			'animation_speed',
			__( 'Animation Speed', 'drawer-menu-wl' ),
			array( $this, 'text_field' ),
			'drawer-menu-wl',
			'drawer_menu_advanced',
			array( 'field' => 'animation_speed', 'description' => __( 'e.g., 0.45s, 300ms', 'drawer-menu-wl' ) )
		);
	}

	/**
	 * Section callbacks
	 */
	public function general_section_callback() {
		echo '<p>' . esc_html__( 'Configure basic drawer menu settings.', 'drawer-menu-wl' ) . '</p>';
	}

	public function appearance_section_callback() {
		echo '<p>' . esc_html__( 'Customize the look and feel of your drawer menu.', 'drawer-menu-wl' ) . '</p>';
	}

	public function advanced_section_callback() {
		echo '<p>' . esc_html__( 'Advanced configuration options.', 'drawer-menu-wl' ) . '</p>';
	}

	/**
	 * Field callbacks
	 */
	public function checkbox_field( $args ) {
		$options = get_option( 'drawer_menu_wl_options', $this->get_default_options() );
		$field = $args['field'];
		$checked = isset( $options[ $field ] ) ? $options[ $field ] : false;
		?>
		<label>
			<input type="checkbox" name="drawer_menu_wl_options[<?php echo esc_attr( $field ); ?>]" value="1" <?php checked( $checked, 1 ); ?> />
			<?php echo esc_html( $args['label'] ); ?>
		</label>
		<?php
	}

	public function select_field( $args ) {
		$options = get_option( 'drawer_menu_wl_options', $this->get_default_options() );
		$field = $args['field'];
		$value = isset( $options[ $field ] ) ? $options[ $field ] : '';
		?>
		<select name="drawer_menu_wl_options[<?php echo esc_attr( $field ); ?>]">
			<?php foreach ( $args['options'] as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	public function text_field( $args ) {
		$options = get_option( 'drawer_menu_wl_options', $this->get_default_options() );
		$field = $args['field'];
		$value = isset( $options[ $field ] ) ? $options[ $field ] : '';
		?>
		<input type="text" name="drawer_menu_wl_options[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" />
		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
		<?php
	}

	public function color_field( $args ) {
		$options = get_option( 'drawer_menu_wl_options', $this->get_default_options() );
		$field = $args['field'];
		$value = isset( $options[ $field ] ) ? $options[ $field ] : '';
		?>
		<input type="text" name="drawer_menu_wl_options[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="color-picker" />
		<?php
	}

	/**
	 * Sanitize options
	 *
	 * @since 1.3.0
	 */
	public function sanitize_options( $input ) {
		$sanitized = array();
		$defaults = $this->get_default_options();

		$sanitized['enabled'] = isset( $input['enabled'] ) ? 1 : 0;
		$sanitized['show_hamburger'] = isset( $input['show_hamburger'] ) ? 1 : 0;
		$sanitized['menu_location'] = sanitize_text_field( $input['menu_location'] ?? $defaults['menu_location'] );
		$sanitized['hamburger_position'] = in_array( $input['hamburger_position'] ?? '', array( 'left', 'right' ), true ) 
			? $input['hamburger_position'] 
			: $defaults['hamburger_position'];

		// Sanitize colors
		$sanitized['background_color'] = sanitize_hex_color( $input['background_color'] ?? '' ) ?: $defaults['background_color'];
		$sanitized['text_color'] = sanitize_hex_color( $input['text_color'] ?? '' ) ?: $defaults['text_color'];
		$sanitized['hamburger_color'] = sanitize_hex_color( $input['hamburger_color'] ?? '' ) ?: $defaults['hamburger_color'];

		// Sanitize opacity
		$opacity = floatval( $input['background_opacity'] ?? $defaults['background_opacity'] );
		$sanitized['background_opacity'] = ( $opacity >= 0 && $opacity <= 1 ) ? $opacity : $defaults['background_opacity'];

		// Sanitize CSS values
		$sanitized['drawer_width_desktop'] = $this->sanitize_css_value( $input['drawer_width_desktop'] ?? '', $defaults['drawer_width_desktop'] );
		$sanitized['drawer_width_mobile'] = $this->sanitize_css_value( $input['drawer_width_mobile'] ?? '', $defaults['drawer_width_mobile'] );
		$sanitized['animation_speed'] = $this->sanitize_css_value( $input['animation_speed'] ?? '', $defaults['animation_speed'] );

		return $sanitized;
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
			<h1><?php esc_html_e( 'Drawer Menu WL Settings', 'drawer-menu-wl' ); ?></h1>
			
			<form method="post" action="options.php">
				<?php
				settings_fields( 'drawer_menu_wl_options' );
				do_settings_sections( 'drawer-menu-wl' );
				submit_button();
				?>
			</form>

			<hr>

			<div class="card">
				<h2><?php esc_html_e( 'Usage', 'drawer-menu-wl' ); ?></h2>
				<p><?php esc_html_e( 'The drawer menu is automatically added to all pages when enabled above.', 'drawer-menu-wl' ); ?></p>
				
				<h3><?php esc_html_e( 'Setup Steps', 'drawer-menu-wl' ); ?></h3>
				<ol>
					<li><?php esc_html_e( 'Ensure "Enable Drawer Menu" is checked above', 'drawer-menu-wl' ); ?></li>
					<li><?php esc_html_e( 'Go to Appearance → Menus and create/assign a menu to "Drawer Menu WL"', 'drawer-menu-wl' ); ?></li>
					<li><?php esc_html_e( 'Go to Appearance → Widgets and add content to "Drawer Menu Top" and "Drawer Menu Bottom" areas if needed', 'drawer-menu-wl' ); ?></li>
					<li><?php esc_html_e( 'Customize appearance settings above', 'drawer-menu-wl' ); ?></li>
				</ol>

				<h3><?php esc_html_e( 'Custom Hamburger Triggers', 'drawer-menu-wl' ); ?></h3>
				<p><?php esc_html_e( 'To add custom hamburger icons anywhere on your site:', 'drawer-menu-wl' ); ?></p>
				
				<h4><?php esc_html_e( 'Using Shortcode', 'drawer-menu-wl' ); ?></h4>
				<code>[drawer_hamburger]</code>
				<p><?php esc_html_e( 'Available attributes: position, top, right, left, bottom, color, color_open, size, padding, margin, z_index, show_text', 'drawer-menu-wl' ); ?></p>
				<p><strong><?php esc_html_e( 'Example:', 'drawer-menu-wl' ); ?></strong></p>
				<code>[drawer_hamburger position="fixed" top="20px" right="20px" color="#333" color_open="#fff"]</code>

				<h4><?php esc_html_e( 'Using CSS Class', 'drawer-menu-wl' ); ?></h4>
				<p><?php esc_html_e( 'Add class "drawer-menu-trigger" to any element:', 'drawer-menu-wl' ); ?></p>
				<code>&lt;button class="drawer-menu-trigger"&gt;Open Menu&lt;/button&gt;</code>

				<h3><?php esc_html_e( 'Using in Divi', 'drawer-menu-wl' ); ?></h3>
				<p><?php esc_html_e( 'The drawer menu works automatically. To add custom hamburger icons in Divi:', 'drawer-menu-wl' ); ?></p>
				<ol>
					<li><?php esc_html_e( 'Add a Code Module or Text Module to your layout', 'drawer-menu-wl' ); ?></li>
					<li><?php esc_html_e( 'Insert the [drawer_hamburger] shortcode', 'drawer-menu-wl' ); ?></li>
					<li><?php esc_html_e( 'If using default hamburger, uncheck "Show Default Hamburger" above', 'drawer-menu-wl' ); ?></li>
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
		
		// Set default options if they don't exist
		if ( false === get_option( 'drawer_menu_wl_options' ) ) {
			add_option( 'drawer_menu_wl_options', $this->get_default_options() );
		}
		
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