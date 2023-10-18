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
		$data     = json_decode( wp_remote_retrieve_body( $response ) );

		return $data;
	}

	/**
	 * Import data from the API
	 */
	public function import_data() {

		// Get the data from the API
		$beers        = $this->get_data();
		$meta_prefix = 'beer';

		foreach( $beers as $beer ) {

			// Check if the post already exists
			$beer_exists = new \WP_Query( [
				'post_type'      => \WP_Punk_API\WP_Punk_API_CPT::POST_TYPE,
				'post_title'     => $beer->name,
				'posts_per_page' => -1,
				'meta_key'       => $meta_prefix . '_id',
				'meta_value'     => $beer->id,
			] );

			// If the post doesn't exist, create it
			if ( ! $beer_exists->have_posts() ) {
				$beer_id = wp_insert_post( [
					'post_title'   => $beer->name,
					'post_name'    => sanitize_title( $beer->id . '-' . $beer->name ),
					'post_content' => $beer->description,
					'post_status'  => 'publish',
					'post_type'    => \WP_Punk_API\WP_Punk_API_CPT::POST_TYPE,
				] );

				// Add the meta data
				$beer_meta = [
					'id'           => $beer->id,
					'tagline'      => $beer->tagline,
					'first_brewed' => $beer->first_brewed,
					'image_url'    => $beer->image_url,
					'abv'          => $beer->abv,
					'ibu'          => $beer->ibu,
				];

				// convert food pairing array to string
				$food_pairing = implode( ', ', $beer->food_pairing );

				// Add the food pairing meta data
				$beer_meta['food_pairing'] = $food_pairing;

				foreach( $beer_meta as $key => $value ) {
					update_post_meta( $beer_id, $meta_prefix . '_' . $key, $value );
				}
			}
		}
	}
}
