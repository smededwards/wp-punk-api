<?php
/**
 * Fired during plugin deactivation
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/includes
 */

class WP_Punk_Api_Deactivator {
	public static function deactivate() {
		// Unregister the post type, so the rules are no longer in memory.
		unregister_post_type( 'beers' );

		// Clear the permalinks to remove our post type's rules from the database.
		flush_rewrite_rules();

		// Clean the scheduler on deactivation
		wp_clear_scheduled_hook( 'beer_update_list' );

		// Remove all beers from the database on plugin deactivation
		$plugin_public = new WP_Punk_Api();
		$plugin_public->clear_beers_from_db();
	}
}
