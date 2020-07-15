<?php get_header(); ?>

<?php
	$blog_layout_option = '';

	if ( is_tag() || is_category() ) {
		$layout = olsen_light_get_layout_classes( 'layout_terms' );
	} else {
		$layout = olsen_light_get_layout_classes( 'layout_blog' );
	}

	$content_col       = $layout['content_col'];
	$sidebar_right_col = $layout['sidebar_right_col'];
	$main_class        = $layout['main_class'];
	$post_col          = $layout['post_col'];
?>

<div class="row">
	<div class="<?php echo esc_attr( $content_col ); ?>">
		<main id="content" class="<?php echo esc_attr( $main_class ); ?>" role="main" itemprop="mainContentOfPage" itemscope="itemscope" itemtype="http://schema.org/Blog">

			<div class="row">
				<div class="col-md-12">

					<?php if( is_search() ): ?>
						<?php
							global $wp_query;

							$found = $wp_query->found_posts;
							$none  = esc_html__( 'No results found. Please broaden your terms and search again.', 'olsen-light' );
							$one   = esc_html__( 'Just one result found. We either nailed it, or you might want to broaden your terms and search again.', 'olsen-light' );
							$many  = esc_html( sprintf( _n( '%d result found.', '%d results found.', $found, 'olsen-light' ), $found ) );
						?>
						<article class="entry">
							<h2 class="entry-title">
								<?php esc_html_e( 'Search results' , 'olsen-light' ); ?>
							</h2>

							<div class="entry-content" itemprop="text">
								<p><?php olsen_light_e_inflect( $found, $none, $one, $many ); ?></p>
								<?php if ( $found < 2 ) {
									get_search_form();
								} ?>
							</div>

							<div class="entry-utils group"></div>
						</article>
					<?php endif; ?>

					<?php if ( ! empty( $post_col ) ) : ?>
						<div class="row">
					<?php endif; ?>

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', 'entry' ); ?>
						<?php endwhile; ?>

					<?php if ( ! empty( $post_col ) ) : ?>
						</div>
					<?php endif; ?>

				</div>
			</div>

			<?php olsen_light_pagination(); ?>
		</main>
	</div>

	<div class="<?php echo esc_attr( $sidebar_right_col ); ?>">
		<?php get_sidebar(); ?>
	</div>

</div><!-- /row -->

<?php get_footer(); ?>
