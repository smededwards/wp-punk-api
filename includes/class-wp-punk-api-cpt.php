<?php

namespace WP_Punk_API;

/**
 * Class WP_Punk_API_CPT
 * 
 * Registers the Custom Post Type for the plugin
 */
class WP_Punk_API_CPT {

	/**
	 * Constants
	 */
	const POST_TYPE               = 'beers';
	const POST_TYPE_SINGULAR      = 'beer';
	const POST_TYPE_NAME          = 'Beers';
	const POST_TYPE_NAME_SINGULAR = 'Beer';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init',           [ $this, 'register_post_type' ] );
		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
		add_action( 'save_post',      [ $this, 'save_post' ] );
	}

	/**
	 * Registers the Custom Post Type
	 */
	public function register_post_type() {
		$name          = self::POST_TYPE_NAME;
		$singular_name = self::POST_TYPE_SINGULAR;

		$labels = [
			'name'                  => __( $name, WP_PUNK_API_TEXT_DOMAIN ),
			'singular_name'         => __( $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'add_new'               => __( 'Add New', WP_PUNK_API_TEXT_DOMAIN ),
			'add_new_item'          => __( 'Add New ' . $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'edit_item'             => __( 'Edit ' . $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'new_item'              => __( 'New ' . $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'view_item'             => __( 'View ' . $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'view_items'            => __( 'View ' . $name, WP_PUNK_API_TEXT_DOMAIN ),
			'search_items'          => __( 'Search ' . $name, WP_PUNK_API_TEXT_DOMAIN ),
			'not_found'             => __( 'No ' . $name . ' found', WP_PUNK_API_TEXT_DOMAIN ),
			'not_found_in_trash'    => __( 'No ' . $name . ' found in trash', WP_PUNK_API_TEXT_DOMAIN ),
			'all_items'             => __( 'All ' . $name, WP_PUNK_API_TEXT_DOMAIN ),
			'archives'              => __( $singular_name . ' Archives', WP_PUNK_API_TEXT_DOMAIN ),
			'attributes'            => __( $singular_name . ' Attributes', WP_PUNK_API_TEXT_DOMAIN ),
			'insert_into_item'      => __( 'Insert into ' . $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'uploaded_to_this_item' => __( 'Uploaded to this ' . $singular_name, WP_PUNK_API_TEXT_DOMAIN ),
			'filter_items_list'     => __( 'Filter ' . $name . ' list', WP_PUNK_API_TEXT_DOMAIN ),
			'items_list_navigation' => __( $name . ' list navigation', WP_PUNK_API_TEXT_DOMAIN ),
			'items_list'            => __( $name . ' list', WP_PUNK_API_TEXT_DOMAIN ),
		];

		$args = [
			'labels'              => $labels,
			'description'         => __( 'Custom Post Type for ' . $name, WP_PUNK_API_TEXT_DOMAIN ),
			'menu_icon'           => 'dashicons-beer',
			'menu_position'       => 20,
			'can_export'          => true,
			'exclude_from_search' => false,
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'publicly_queryable'  => true,
			'has_archive'         => strtolower( $name ),
			'supports'            => [ 'title', 'editor' ],
			'rewrite'             => [ 'slug' => strtolower( $singular_name ) ],
		];

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Register Meta Boxes
	 */
	public function register_meta_boxes( $post_type ) {
		if ( self::POST_TYPE === $post_type ) {
			add_meta_box(
				WP_PUNK_API_TEXT_DOMAIN . '-meta-boxes',
				__( self::POST_TYPE_NAME_SINGULAR . ' Details', WP_PUNK_API_TEXT_DOMAIN ),
				[ $this, 'render_meta_boxes' ],
				self::POST_TYPE,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Render Meta Boxes
	 */
	public function render_meta_boxes( $post ) {
		// Get the fields
		$fields      = \WP_Punk_API\WP_Punk_API::BEER_META_FIELDS;

		// Set default field types
		$field_types = [
			'id'           => 'number',
			'tagline'      => 'text',
			'image_url'    => 'url',
			'ibu'          => 'number',
			'url'          => 'url',
			'abv'          => 'number',
			'food_pairing' => 'textarea',
		];

		// Capitalize keywords for the label
		foreach( $keywords as $keyword ) {
			$fields = array_map( function( $field ) use ( $keyword ) {
				return str_replace( $keyword, strtoupper( $keyword ), $field );
			}, $fields );
		}

		// Capitalize keywords in the label
		$keywords = ['id', 'ibu', 'url', 'abv'];

		// Meta Prefix
		$meta_prefix = \WP_Punk_API\WP_Punk_API::BEER_META_PREFIX;

		?>
		<table class="form-table">
			<?php foreach( $fields as $field ) : ?>
				<tr>
					<th scope="row">
						<label for="<?= $field_labels; ?>"><?= ucwords( str_replace( '_', ' ', $field ) ); ?></label>
					</th>
					<td>
						<?php if ( $field_types[ $field ] === 'textarea' ) : ?>
							<textarea class="regular-text"
												name="<?= $field; ?>"
												id="<?= $field; ?>"
												rows="5"
							>
								<?= get_post_meta( $post->ID, $meta_prefix . '_' . $field, true ); ?>
							</textarea>
						<?php elseif ( isset( $field_types[ $field ] ) ) : ?>
							<input class="regular-text"
										 name="<?= $field; ?>" id="<?= $field; ?>" 
										 type="<?= $field_types[ $field ]; ?>" 
										 value="<?= get_post_meta( $post->ID, $meta_prefix . '_' . $field, true ); ?>"
							>
						<?php else : ?>
							<input class="regular-text" 
										 id="<?= $field; ?>" 
										 name="<?= $field; ?>"
										 type="text"
										 value="<?= get_post_meta( $post->ID, $meta_prefix . '_' . $field, true ); ?>"
							>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}

	/**
	 * Save Post
	 */
	public function save_post( $post_id ) {
		// Check if the post being saved is of the correct post type
		if ( self::POST_TYPE !== get_post_type( $post_id ) ) {
			return;
		}

		// Check if the current user has permission to edit the post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save the fields
		$fields = \WP_Punk_API\WP_Punk_API::BEER_META_FIELDS;
		$meta_prefix = \WP_Punk_API\WP_Punk_API::BEER_META_PREFIX;

		foreach( $fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				update_post_meta( $post_id, $meta_prefix . '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
			}
		}
	}
}
