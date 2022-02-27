<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/includes
 */

class WP_Punk_Api_i18n {
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wp-punk-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
