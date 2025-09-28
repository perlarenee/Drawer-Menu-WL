<?php
/**
 * Uninstall Drawer Menu WL Plugin
 *
 * This file is called when the plugin is deleted via the WordPress admin.
 * It cleans up all plugin data from the database.
 *
 * @package DrawerMenuWL
 * @since 1.2.2
 */

// Exit if accessed directly or not uninstalling
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Clean up plugin data on uninstall
 */
function drawer_menu_wl_uninstall_cleanup() {

	// Remove any plugin options (none currently used, but future-proofing)
	delete_option( 'drawer_menu_wl_version' );
	delete_option( 'drawer_menu_wl_settings' );

	// Clean up any transients that might be set
	delete_transient( 'drawer_menu_wl_cache' );

	// Remove any user meta related to the plugin
	delete_metadata( 'user', 0, 'drawer_menu_wl_dismissed_notice', '', true );

	// Clean up any custom post meta (if plugin ever stores post-specific data)
	// Note: This plugin doesn't currently store post meta, but this provides future-proofing
	delete_metadata( 'post', 0, 'drawer_menu_wl_setting', '', true );

	// Remove any site options for multisite
	if ( is_multisite() ) {
		delete_site_option( 'drawer_menu_wl_network_settings' );
	}

	// Clear any cached data
	wp_cache_flush();
}

// Run the cleanup
drawer_menu_wl_uninstall_cleanup();