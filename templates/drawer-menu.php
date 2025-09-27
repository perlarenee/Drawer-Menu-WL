<?php
/**
 * Drawer Menu Template 
 * File: templates/drawer-menu.php
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="offcanvas-mobile-nav" class="<?php echo esc_attr($drawer_class); ?>">
  <input id="hamburger" class="hamburger" type="checkbox"/>
  <label class="hamburger" for="hamburger">
    <i></i>
    <text>
      <close><?php _e('close', 'drawer-menu-wl'); ?></close>
      <open><?php _e('menu', 'drawer-menu-wl'); ?></open>
    </text>
  </label>

  <section class="drawer-list">
    <div class="drawer-menu-content">
      
      <!-- Top Widget Area -->
      <?php if (is_active_sidebar('drawer-menu-top')) : ?>
        <div class="drawer-menu-widgets-top">
          <?php dynamic_sidebar('drawer-menu-top'); ?>
        </div>
      <?php endif; ?>
      
      <!-- Navigation Menu -->
      <div class="drawer-menu-navigation">
        <?php
          wp_nav_menu(array(
            'theme_location' => $menu_location,
            'container' => false,
            'menu_class' => '',
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'menu_id' => 'drawer-menu',
            'fallback_cb' => false,
          ));
        ?>
      </div>
      
      <!-- Bottom Widget Area -->
      <?php if (is_active_sidebar('drawer-menu-bottom')) : ?>
        <div class="drawer-menu-widgets-bottom">
          <?php dynamic_sidebar('drawer-menu-bottom'); ?>
        </div>
      <?php endif; ?>
      
    </div>
  </section>
</div>