<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/admin
 */

class WP_Punk_Api_Admin {
	// The ID of this plugin.
	private $plugin_name;	

	// The version of this plugin.
	private $version;

	// Initialize the class and set its properties.
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	// Register the stylesheets for the admin area.
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-punk-api-admin.css', array(), $this->version, 'all' );
	}

	// Register meta boxes.
	public function beer_register_meta_boxes() {
		add_meta_box(
			'beer-info', // Unique ID
			esc_html__( 'Beer Details', 'wp-punk-api' ), // Title
			array( $this, 'beer_display_meta_box' ), // Callback function
			'beers', // beer custom post type
			'normal', // Context
			'default' // Priority
		);
	}

	// Meta box display callback.
	public function beer_display_meta_box( $post ) {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wp-punk-api-admin-view.php';
	}
	
	//Handles saving the meta box.
	public function beer_save_meta_boxes( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['beer_post_nonce'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['beer_post_nonce'], 'beer_post_nonce_action' ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( wp_is_post_autosave( $post_id ) ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( '/' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return $post_id;
		}

		// OK, it's safe for us to save the data now.
		$fields = [
			'beer_id',
			'beer_tagline',
		];

		foreach ( $fields as $field ) {
			if ( array_key_exists( $field, $_POST ) ) {
				// Update the meta field.
				update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
			}
		}
	}
}
