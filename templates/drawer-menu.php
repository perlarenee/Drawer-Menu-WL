<?php
/**
 * Drawer Menu Template
 * File: templates/drawer-menu.php
 *
 * @package DrawerMenuWL
 * @since 1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$drawer_class = isset( $drawer_class ) ? $drawer_class : '';
$menu_location = isset( $menu_location ) ? $menu_location : 'drawer-menu-wl';
?>

<div id="offcanvas-mobile-nav" class="<?php echo esc_attr( $drawer_class ); ?>" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Navigation menu', 'drawer-menu-wl' ); ?>">
	<input id="hamburger" class="hamburger" type="checkbox" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'drawer-menu-wl' ); ?>" />
	<label class="hamburger" for="hamburger" role="button" tabindex="0" aria-controls="offcanvas-mobile-nav" aria-expanded="false">
		<i aria-hidden="true"></i>
		<text aria-live="polite">
			<close><?php esc_html_e( 'close', 'drawer-menu-wl' ); ?></close>
			<open><?php esc_html_e( 'menu', 'drawer-menu-wl' ); ?></open>
		</text>
	</label>

	<section class="drawer-list" role="navigation" aria-label="<?php esc_attr_e( 'Main navigation', 'drawer-menu-wl' ); ?>">
		<div class="drawer-menu-content">

			<!-- Top Widget Area -->
			<?php if ( is_active_sidebar( 'drawer-menu-top' ) ) : ?>
				<div class="drawer-menu-widgets-top" role="complementary" aria-label="<?php esc_attr_e( 'Top menu widgets', 'drawer-menu-wl' ); ?>">
					<?php dynamic_sidebar( 'drawer-menu-top' ); ?>
				</div>
			<?php endif; ?>

			<!-- Navigation Menu -->
			<div class="drawer-menu-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => $menu_location,
						'container'      => false,
						'menu_class'     => '',
						'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menu">%3$s</ul>',
						'menu_id'        => 'drawer-menu',
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<!-- Bottom Widget Area -->
			<?php if ( is_active_sidebar( 'drawer-menu-bottom' ) ) : ?>
				<div class="drawer-menu-widgets-bottom" role="complementary" aria-label="<?php esc_attr_e( 'Bottom menu widgets', 'drawer-menu-wl' ); ?>">
					<?php dynamic_sidebar( 'drawer-menu-bottom' ); ?>
				</div>
			<?php endif; ?>

		</div>
	</section>
</div>