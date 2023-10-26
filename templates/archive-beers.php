<?php
/**
 * The template for displaying archive pages
 */
$description = get_the_archive_description();
get_header();
?>
<?php if ( have_posts() ) : ?>
	<header class="page-header alignwide">
		<h1 class="page-title"><?= get_the_archive_title(); ?></h1>
		<section class="archive-description">
			<?= $description; ?>
		</section>
	</header>
	<div class="archive-content alignwide">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<a href="<?php the_permalink(); ?>">
					<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
					<img src="<?= esc_url( get_the_post_thumbnail_url( $post->ID, 'medium' ) ); ?>" alt="<?php the_title(); ?>">
				</a>
			</article>
		<?php endwhile; ?>
	</div>
<?php endif; ?>
<?php require_once 'pagination.php'; ?>
<?php get_footer(); ?>
