<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/public
 */

class WP_Punk_Api_Public {

	// The ID of this plugin.
	private $plugin_name;

	// The version of this plugin.
	private $version;

	// Initialize the class and set its properties.
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	// Register the stylesheets for the public-facing side of the site.
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-punk-api-public.css', array(), $this->version, 'all' );
	}

	// Register a beer post type
	public function beer_cpt() {
		$labels = array(
			'name'								=> _x( 'Beers', 'post type general name', 'wp-punk-api' ),
			'singular_name'				=> _x( 'Beer', 'post type singular name', 'wp-punk-api' ),
			'menu_name' 					=> _x( 'Beers', 'admin menu', 'wp-punk-api' ),
			'name_admin_bar'			=> _x( 'Beer', 'add new on admin bar', 'wp-punk-api' ),
			'add_new'							=> _x( 'Add New', 'beers', 'wp-punk-api' ),
			'add_new_item'				=> __( 'Add New Beer', 'wp-punk-api' ),
			'new_item'						=> __( 'New Beer', 'wp-punk-api' ),
			'edit_item'						=> __( 'Edit Beer', 'wp-punk-api' ),
			'view_item'						=> __( 'View Beer', 'wp-punk-api' ),
			'view_items'					=> __( 'View Beers', 'wp-punk-api' ),
			'all_items'						=> __( 'All Beers', 'wp-punk-api' ),
			'search_items'				=> __( 'Search Beers', 'wp-punk-api' ),
			'parent_item_colon'		=> __( 'Parent Beers:', 'wp-punk-api' ),
			'not_found'						=> __( 'No beers found.', 'wp-punk-api' ),
			'not_found_in_trash'	=> __( 'No beers found in Trash.', 'wp-punk-api' ),
		);

		$args = array(
			'labels'							=> $labels,
			'description'					=> __( 'Custom Post Type for Beers.', 'wp-punk-api' ),
			'public'							=> true,
			'publicly_queryable'	=> true,
			'show_ui'							=> true,
			'show_in_menu'				=> true,
			'query_var'						=> true,
			'rewrite'							=> ['slug' => 'beers'],
			'capability_type'			=> 'post',
			'has_archive'					=> true,
			'menu_icon'						=> 'dashicons-beer',
			'menu_position'				=> 20,
			'hierarchical'				=> true,
			'show_in_rest'				=> false,
			'rest_base'						=> 'beers',
			'supports'						=> ['title', 'editor'],
		);
		register_post_type( 'beers', $args );
	}

	// Load Single template for Beer Custom Post Type
	public function beer_cpt_template( $single_template ) {
		global $post;
		if ( 'beers' === $post->post_type ) {
			$single_template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/wp-punk-api-public-view.php';
		}
		return $single_template;
	}

	// Load Archive template for Beer Custom Post Type
	public function beer_archive_template( $archive_beer_template ) {
		if ( is_post_type_archive( 'beers' ) ) {
			$archive_beer_template = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/archive-beers.php';
		}
		return $archive_beer_template;
	}

	// The Code below will modify the main WordPress loop, before the queries fired, to only sort Beers by title on the archive page
	public function pre_get_beer_custom_posts( $query ) {
		if ( ( is_archive( 'beers' ) ) && $query->is_main_query() ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}
		return $query;
	}

	// Fetch remote data source API from Punk API and store data to custom post type 'beers'
	public function beer_sync_api() {
		$current_page	= ( ! empty( $_POST['current_page'] ) ) ? $_POST['current_page'] : 1;
		$api_url	= 'https://api.punkapi.com/v2/beers?per_page=10&page=' . $current_page; // API from Punk API
		$beers_data = [];
		$response = wp_remote_get( $api_url );
		$response_body = wp_remote_retrieve_body( $response );
		$beers_data = json_decode( $response_body ); // Convert JSON string to PHP variable

		// API not available
		if ( ! is_array( $beers_data ) || empty( $beers_data ) ) {
			echo esc_html( 'External API unavailable.' );
			return false;
		}
		
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			return "Something went wrong:" . $error_message;
		}

		foreach ( $beers_data as $beer ) {
			$beer_slug = sanitize_title($beer->id . '-' . $beer->name);

			$existing_beer = get_page_by_path($beer_slug, 'OBJECT', 'beers');

			// if beer does not exist in DB, add beer
			if ( $existing_beer === null ) {
				// add beer to WP
				$this->add_beer( $beer );
			} else {
				$existing_beer_modified = $this->get_existing_beer_data( $existing_beer );
				$beer_meta_api         = $this->get_beer_post_data( $beer )['meta_input'];

				// Only update meta fields if data is changed
				if ( $beer_meta_api !== $existing_beer_modified ) {
					foreach ( $beer_meta_api as $key => $value ) {
						update_post_meta( $existing_beer->ID, $key, sanitize_text_field( $value ) );
					}
				}
			}
		}

		// Increment page number
		$current_page = $current_page + 1;

		// Recursive ajax request for beer_sync_api
		wp_remote_post(
			admin_url( 'admin-ajax.php?action=beer_sync_api' ), [
				'blocking'  => false, // allows you to trigger a non-blocking request
				'sslverify' => false, // the site is self-signed or is invalid, but reasonably sure that it can be trusted
				'timeout'   => 30, // timeout in seconds
				'body'      => [
					'current_page' => $current_page,
				],
			]
		);
	}

	// Insert beer to WordPress database
	public function add_beer( $beer ) {
		$beer_post_data	=	$this->get_beer_post_data( $beer );
		$beer_post_data['import_id'] = $beer->id;
		
		// Insert the post into the database
		wp_insert_post( $beer_post_data );
	}

	// Get beer data from remote source API and set meta as value
	public function get_beer_post_data( $beer ) {
		$beer_post_data = [
			'post_title'		=> sanitize_text_field( $beer->name ),
			'post_name'			=> sanitize_text_field( $beer->id . ' - ' . $beer->name ),
			'post_content'	=> sanitize_text_field( $beer->description ),
			'post_type'			=> 'beers', // custom post type
			'post_status'		=> 'publish',
			'meta_input'		=> [
				'beer_id'				=> sanitize_text_field( $beer->id ),
				'beer_tagline'	=> sanitize_text_field( $beer->tagline ),
			],
		];
		return $beer_post_data;
	}

	// Get existing beer meta data from WordPress
	public function get_existing_beer_data( $existing_beer ) {
		$post_meta = get_post_meta( $existing_beer );

		// beer post meta fields
		$beer_meta_data = [
			'beer_id'				=> $post_meta['beer_id'][0],
			'beer_tagline'	=> $post_meta['beer_tagline'][0],
		];
		return $beer_meta_data;
	}
}
