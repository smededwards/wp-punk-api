<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/admin/partials
 */

wp_nonce_field( 'beer_post_nonce_action', 'beer_post_nonce' ); 
?>

<div class="beer-info-box">

	<div class="beer-info-box__field">
		<div class="beer-info-box__label row">
			<label for="beer_id"><?php echo esc_html( 'ID' ); ?></label>
		</div>
		<div class="beer-info-box__input">
			<div class="beer-info-box__input-wrap">
				<input type="text" class="beer-text-fields large-text" id="beer_id" name="beer_id" readonly="readonly" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'beer_id', true ) ); ?>" />
			</div>
		</div>
	</div>
	<!-- .beer-info-box__field -->

	<div class="beer-info-box__field">
		<div class="beer-info-box__label">
			<label for="beer_tagline"><?php echo esc_html( 'Tagline' ); ?></label>
		</div>
		<div class="beer-info-box__input">
			<div class="beer-info-box__input-wrap">
				<input type="text" class="beer-text-fields large-text" id="beer_tagline" name="beer_tagline" value="<?php echo esc_attr( get_post_meta( get_the_ID(), 'beer_tagline', true ) ); ?>" />
			</div>
		</div>
	</div>
	<!-- .beer-info-box__field -->

</div>
