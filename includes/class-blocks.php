<?php

namespace WP_Punk_API;

/**
 * Class Blocks
 * 
 * Registers the blocks for the plugin
 */
class Blocks {

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
		add_filter ( 'allowed_block_types_all',     [ $this, 'allowed_block_types' ] );
		add_filter ( 'block_categories_all',        [ $this, 'custom_block_category' ], 10, 2 );
	}

	/**
	 * Enqueues the block assets
	 */
	public function enqueue_block_assets() {
		wp_enqueue_script(
			self::BLOCK_NAME,
			self::BLOCK_DIR . '/index.js',
			[ 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ],
			self::BLOCK_DIR . '/index.js'
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
			self::BLOCK_DIR . '/editor.js'
		);
	}

	/**
	 * Filter allowed block types
	 */
	public function allowed_block_types( $allowed_blocks ) {
		// Only allow the paragraph block on the custom post type
		if ( get_post_type() === CPT::POST_TYPE ) {
			$allowed_blocks = [
				'core/paragraph',
			];
		}

		return $allowed_blocks;
	}

	/**
	 * Custom block category
	 */
	public function custom_block_category( $categories, $post ) {
		return array_merge(
			$categories,
			[
				[
					'slug'  => self::BLOCK_NAMESPACE,
					'title' => __( 'WP Punk API', WP_PUNK_API_TEXT_DOMAIN ),
				],
			]
		);
	}
}
