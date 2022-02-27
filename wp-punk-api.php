<?php
/**
 * Plugin Name:       WP Punk API
 * Plugin URI:        http://github.com/smededwards
 * Description:       Create custom post type 'Beers' and display list from Brewdog's Punk API.
 * Version:           0.1.0
 * Author:            Michael Edwards
 * Author URI:        https://smededwards.com
 * Text Domain:       wp-punk-api
 * Domain Path:       /languages
 *
 * @since             0.1.0
 * @package           WP_Punk_Api
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'WP_PUNK_API_VERSION', '0.1.0' );

/**
 * The code that runs during plugin activation.
 */
function activate_wp_punk_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-punk-api-activator.php';
	WP_Punk_Api_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wp_punk_api() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-punk-api-deactivator.php';
	WP_Punk_Api_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_punk_api' );
register_deactivation_hook( __FILE__, 'deactivate_wp_punk_api' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-punk-api.php';

/**
 * Begins execution of the plugin.
 */
function run_wp_punk_api() {
	$plugin = new WP_Punk_Api();
	$plugin->run();
}
run_wp_punk_api();
