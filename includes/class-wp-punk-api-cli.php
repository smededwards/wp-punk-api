<?php

namespace WP_Punk_API;

/**
 * Class WP_Punk_API_CLI
 * 
 * Registers Command Line Interface commands for the plugin
 */
class WP_Punk_API_CLI {

	/**
	 * Constants
	 */
	// this is WP_PUNK_API_CLI_REST_SLUG
	const CLI_PREFIX = WP_PUNK_API_CLI_REST_SLUG;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'cli_init', [ $this, 'register_commands' ] );
	}

	/**
	 * Registers the CLI commands
	 */
	public function register_commands() {
		// This command will run the import_data() method from the WP_Punk_API class
		\WP_CLI::add_command( self::CLI_PREFIX . ' import', [ $this, 'import_data' ] );
	}

	/**
	 * Imports data from the API
	 */
	public function import_data() {
		// Get Post Details.
		$wp_punk_api     = new WP_Punk_API();
		$beer_data       = $wp_punk_api->get_data();
		$beer_import     = $wp_punk_api->import_data();
		$desired_posts   = count( $beer_data );
		$progress        = \WP_CLI\Utils\make_progress_bar( 'Generating Posts', $desired_posts );
		$posts_generated = 0;

		\WP_CLI::line( 'Getting the round in...' );

		// Run import_data method from WP_Punk_API class
		foreach ( $beer_data as $beer ) {
			$beer_import = $wp_punk_api->import_data( $beer );
			$progress->tick();
			$posts_generated++;

			// Break out of loop if we've generated the desired number of posts
			if ( $posts_generated === $desired_posts ) {
				break;
			}

			// Error handling.
			if ( is_wp_error( $beer_import ) ) {
				\WP_CLI::error( $beer_import->get_error_message() );
			}

			$progress->tick();
		}

		// Finish progress bar
		$progress->finish();

		// Success message
		\WP_CLI::success( 'Beers generated: ' . $posts_generated . '/' . $desired_posts );
	}
}
