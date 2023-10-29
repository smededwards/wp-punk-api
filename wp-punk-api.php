<?php
/**
 * Plugin Name: WP Punk API
 * Plugin URI:  https://github.com/smededwards/wp-punk-api
 * Description: A plugin that connects to the Punk API, and imports the data into a Custom Post Type called Beers
 * Author:      Michael Edwards
 * Author URI:  https://smededwards.com
 * Text Domain: wp-punk-api
 * Domain Path: /languages
 * Version:     0.0.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// If the class already exists, bail
if ( class_exists( 'WP_Punk_API' ) ) {
	return;
}

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();

// Define constants
define( 'WP_PUNK_API_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_PUNK_API_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WP_PUNK_API_TEXT_DOMAIN', 'wp-punk-api' );
define( 'WP_PUNK_API_CLI_REST_SLUG', 'punk-api' );

// Require plugin files
require_once WP_PUNK_API_PLUGIN_DIR . 'includes/class-wp-punk-api.php';
require_once WP_PUNK_API_PLUGIN_DIR . 'includes/class-wp-punk-api-blocks.php';
require_once WP_PUNK_API_PLUGIN_DIR . 'includes/class-wp-punk-api-cli.php';
require_once WP_PUNK_API_PLUGIN_DIR . 'includes/class-wp-punk-api-cpt.php';

// Instantiate plugin classes
$wp_punk_api        = new WP_Punk_API\WP_Punk_API();
$wp_punk_api_blocks = new WP_Punk_API\WP_Punk_API_Blocks();
$wp_punk_api_cli    = new WP_Punk_API\WP_Punk_API_CLI();
$wp_punk_api_cpt    = new WP_Punk_API\WP_Punk_API_CPT();

// Register activation hook
register_activation_hook( __FILE__, function() {
} );

// Register deactivation hook
register_deactivation_hook( __FILE__, function() {
	// Flush rewrite rules
	flush_rewrite_rules();
} );
