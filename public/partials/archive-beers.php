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

$description = get_the_archive_description();
?>

<main class="site-content" role="main">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			<?php if ( $description ) : ?>
				<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
		</header><!-- .page-header -->

		<div class="beer-post-wrap">
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php include 'loop-template.php'; ?>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>

	<?php require_once 'pagination.php'; ?>
</main><!-- .site-content -->

<?php get_footer(); ?>
