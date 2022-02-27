<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/includes
 */

class WP_Punk_Api {

	// The loader that's responsible for maintaining and registering all hooks that power the plugin.
	protected $loader;

	// The unique identifier of this plugin.
	protected $plugin_name;

	// The current version of the plugin.
	protected $version;

	// Define the core functionality of the plugin.
	public function __construct() {
		if ( defined( 'WP_PUNK_API_VERSION' ) ) {
			$this->version = WP_PUNK_API_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'wp-punk-api';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	// Load the required dependencies for this plugin.
	private function load_dependencies() {
		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-punk-api-loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-punk-api-i18n.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-punk-api-admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-punk-api-public.php';

		$this->loader = new WP_Punk_Api_Loader();
	}
	// Register all of the hooks related to the admin area functionality of the plugin.
	private function define_admin_hooks() {
		$plugin_admin = new WP_Punk_Api_Admin( $this->get_plugin_name(), $this->get_version() );
		
		// Register admin styles
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		// Register custom fields
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'beer_register_meta_boxes' );

		// Save beer post custom fields
		$this->loader->add_action( 'save_post', $plugin_admin, 'beer_save_meta_boxes', 10, 2 );
	}

	// Register all of the hooks related to the public-facing functionality of the plugin.
	private function define_public_hooks() {
		$plugin_public = new WP_Punk_Api_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

		// Create Beers Custom Post Type
		$this->loader->add_action( 'init', $plugin_public, 'beer_cpt' );

		// Filter the single_template for beer custom post type
		$this->loader->add_filter( 'single_template', $plugin_public, 'beer_cpt_template' );

		// Filter the home_template for beer custom post type
		$this->loader->add_filter( 'archive_template', $plugin_public, 'beer_archive_template' );

		// Alter main query on Beers archive page to show sort posts by title
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'pre_get_beer_custom_posts' );

		// Fire wp cron beer_sync_api to update data
		$this->loader->add_action( 'beer_update_list', $plugin_public, 'beer_sync_api' );

		// Hande Ajax requests
		$this->loader->add_action( 'wp_ajax_nopriv_beer_sync_api', $plugin_public, 'beer_sync_api' );
		$this->loader->add_action( 'wp_ajax_beer_sync_api', $plugin_public, 'beer_sync_api' );
	}


	// Delete all beer posts from the database
	public function clear_beers_from_db() {
		global $wpdb;
		$wpdb->query( "DELETE FROM wp_posts WHERE post_type='beers'" );
		$wpdb->query( 'DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);' );
		$wpdb->query( 'DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)' );
	}


	//  Run the loader to execute all of the hooks with WordPress.
	public function run() {
		$this->loader->run();
	}
	
	// The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality.
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	// The reference to the class that orchestrates the hooks with the plugin.
	public function get_loader() {
		return $this->loader;
	}

	// Retrieve the version number of the plugin.
	public function get_version() {
		return $this->version;
	}
}
