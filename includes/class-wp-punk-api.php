<?php

namespace WP_Punk_API;

/**
 * Class WP_Punk_API
 * 
 * Registers the API endpoints for the plugin
 */
class WP_Punk_API {

	/**
	 * Constants
	 */
	const REST_SLUG = 'punk-api';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init',    [ $this, 'import_data' ] );
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Registers the API endpoints
	 */
	public function register_routes() {

		// Register the route
		register_rest_route( self::REST_SLUG, \WP_Punk_API\WP_Punk_API_CPT::POST_TYPE, [
			'methods'  => 'GET',
			'callback' => [ $this, 'get_data' ],
		] );
	}

	/**
	 * Gets the data from the API
	 */
	public function get_data() {
		$api_url  = $_ENV['API_URL'];
		$api_key  = \WP_Punk_API\WP_Punk_API_CPT::POST_TYPE;
		$response = wp_remote_get( $api_url . $api_key );
		$data 	  = json_decode( wp_remote_retrieve_body( $response ) );

		return $data;
	}

	/**
	 * Import data from the API
	 */
	public function import_data() {

		// Get the data from the API
		$beers = $this->get_data();

		foreach( $beers as $beer ) {

			// Check if the post already exists
			$beer_exists = get_page_by_title( $beer->name, OBJECT, \WP_Punk_API\WP_Punk_API_CPT::POST_TYPE );

			// If the post doesn't exist, create it
			if ( !$beer_exists ) {
				$beer_id = wp_insert_post( [
					'post_title'   => $beer->name,
					'post_name'    => sanitize_title( $beer->id . '-' . $beer->name ),
					'post_status'  => 'publish',
					'post_type'    => \WP_Punk_API\WP_Punk_API_CPT::POST_TYPE,
				] );
			}
		}
	}
}

new WP_Punk_API();