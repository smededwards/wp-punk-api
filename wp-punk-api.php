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

// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();
