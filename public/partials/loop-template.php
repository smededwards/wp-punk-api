<?php
	$beer_id	=	get_post_meta( get_the_ID(), 'beer_id', true );
	$beer_tagline	=	get_post_meta( get_the_ID(), 'beer_tagline', true );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php if ( is_archive() ) : ?>
			<span class="dashicons dashicons-beer"></span>
			<a href="<?php the_permalink(); ?>">
				<?php the_title() ?>
			</a>	
		<?php else : ?>
			<h1 class="entry-title">
				<?php the_title() ?>
			</h1>
		<?php endif; ?>
	</header><!-- .entry-header -->
	
	<?php if ( is_single() ) : ?>
		<div class="entry-content">

			<?php if ( $beer_tagline ) : ?>
				<div class="entry-tagline">
					<p class="entry-tagline__paragraph">
						<strong>
							<?php echo esc_html('Tagline:'); ?>
						</strong>
						<?php echo esc_html( $beer_tagline ); ?>
					</p>
				</div>
			<?php endif; ?>
			
			<div class="entry-description">
				<p class="entry-description__paragraph">
					<strong>
						<?php echo esc_html('Description:'); ?>
					</strong>
					<?php echo get_the_content();?>
				</p>
			</div>

		</div><!-- .entry-content -->
	<?php endif; ?>

</article><!-- #post-id ?> -->
