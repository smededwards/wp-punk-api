<?php

namespace WP_Punk_API;

/**
 * Class WP_Punk_API_Blocks
 * 
 * Registers the blocks for the plugin
 */
class WP_Punk_API_Blocks {

	/**
	 * Constants
	 */
	const BLOCK_NAME      = 'wp-punk-api-blocks';
	const BLOCK_NAMESPACE = 'wp-punk-api';
	const BLOCK_DIR  	    = WP_PUNK_API_PLUGIN_URL . 'build';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action ( 'enqueue_block_assets',        [ $this, 'enqueue_block_assets' ] );
		add_action ( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ], 10, 1 );
	}

	/**
	 * Enqueues the block assets
	 */
	public function enqueue_block_assets() {
		wp_enqueue_script(
			self::BLOCK_NAME,
			self::BLOCK_DIR . '/index.js',
			[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ],
			filemtime( self::BLOCK_DIR . '/index.js' )
		);
	}

	/**
	 * Enqueues the block editor assets
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script(
			self::BLOCK_NAME . '-editor',
			self::BLOCK_DIR . '/editor.js',
			[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ],
			filemtime( self::BLOCK_DIR . '/editor.js' )
		);
	}
}
