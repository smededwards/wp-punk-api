<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      0.1.0
 *
 * @package    WP_Punk_Api
 * @subpackage WP_Punk_Api/public/partials
 */

get_header();
?>

<main class="site-content" role="main">

	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			include 'loop-template.php';
		}
	}
	?>

</main><!-- .site-content -->

<?php get_footer(); ?>
