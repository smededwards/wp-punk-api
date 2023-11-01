<?php
/**
 * The template for displaying single posts
 */
$meta_prefix  = \WP_Punk_API\CPT::POST_TYPE_SINGULAR;
$tagline      = get_post_meta(get_the_ID(), $meta_prefix . '_tagline', true );
$image_url    = get_post_meta(get_the_ID(), $meta_prefix . '_image_url', true );
$abv          = get_post_meta(get_the_ID(), $meta_prefix . '_abv', true );
$ibu          = get_post_meta(get_the_ID(), $meta_prefix . '_ibu', true );
$food_pairing = get_post_meta(get_the_ID(), $meta_prefix . '_food_pairing', true );

get_header();
?>
<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header alignwide aligncenter">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<img src="<?= esc_url( get_the_post_thumbnail_url( $post->ID, 'medium' ) ); ?>" alt="<?php the_title(); ?>">
			</header>
			<div class="entry-content alignwide">
				<p><strong>Description:</strong></p>
				<?php the_content(); ?>
				<div class="beer-meta">
					<p><strong>Tagline:</strong> <?= $tagline; ?></p>
					<p><strong>ABV:</strong> <?= $abv; ?></p>
					<p><strong>IBU:</strong> <?= $ibu; ?></p>
					<p><strong>Food Pairing:</strong> <?= $food_pairing; ?></p>
					</div>
			</div>
		</article>
	<?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>
