<?php
/**
 * Fired during plugin activation
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/includes
 * @author     Michael Edwards <smededwards@gmail.com>
 */

class WP_Punk_Api_Activator {
	public static function activate() {
		$plugin_name   = new WP_Punk_Api_i18n(); // Get plugin textdomain
		$plugin_public = new WP_Punk_Api_Public( $plugin_name->load_plugin_textdomain(), WP_PUNK_API_VERSION );

		// Trigger function that registers the custom post type plugin.
		$plugin_public->beer_cpt();

		if ( ! wp_next_scheduled( 'beer_update_list' ) ) {
			wp_schedule_event( time(), 'daily', 'beer_update_list' );
		}

		// Clear the permalinks after the post type has been registered.
		flush_rewrite_rules();
	}
}
