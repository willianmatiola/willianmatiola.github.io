<?php if ( get_theme_mod( 'home_slider_show', 1 ) == 1 ) : ?>
	<?php
		$q    = false;
		$args = false;

		if ( get_theme_mod( 'home_slider_show_recent', 0 ) == 1 ) {
			$args = array(
				'post_type'      => 'post',
				'posts_per_page' => get_theme_mod( 'home_slider_limit' ),
			);
		} elseif ( get_theme_mod( 'home_slider_postids' ) ) {
			$args = array(
				'post_type'      => 'post',
				'post__in'       => explode( ',', get_theme_mod( 'home_slider_postids' ) ),
				'posts_per_page' => get_theme_mod( 'home_slider_limit' ),
				'orderby'        => 'post__in',
			);
		} elseif ( get_theme_mod( 'home_slider_term' ) ) {
			$args = array(
				'post_type'      => 'post',
				'tax_query'      => array(
					array(
						'taxonomy' => 'category',
						'terms'    => get_theme_mod( 'home_slider_term' ),
					),
				),
				'posts_per_page' => get_theme_mod( 'home_slider_limit' ),
			);
		}

		if ( false !== $args ) {
			$q = new WP_Query( $args );
		}

		$attributes = sprintf( 'data-autoplay="%s" data-autoplayspeed="%s" data-fade="%s"',
			esc_attr( get_theme_mod( 'home_slider_autoplay', 1 ) ),
			esc_attr( get_theme_mod( 'home_slider_autoplaySpeed', 3000 ) ),
			esc_attr( get_theme_mod( 'home_slider_fade', 1 ) )
		);
	?>
	<?php if( $args !== false && $q !== false && $q->have_posts() ): ?>

		<div class="slick-slider home-slider" <?php echo $attributes; ?>>
			<?php while( $q->have_posts() ): $q->the_post(); ?>
				<div class="slide">
					<?php the_post_thumbnail( 'ci_slider' ); ?>

					<?php if ( ! get_theme_mod( 'home_slider_hide_content' ) ) : ?>
					<div class="slide-content">
						<?php if ( get_post_type() === 'post' ) : ?>
							<div class="entry-meta entry-meta-top">
								<p class="entry-categories">
									<?php the_category( ', ' ); ?>
								</p>
							</div>
						<?php endif; ?>

						<h2 class="entry-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>

						<?php if ( get_post_type() === 'post' ) : ?>
							<div class="entry-meta entry-meta-bottom">
								<time class="entry-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								<a href="<?php echo esc_url( get_comments_link() ); ?>" class="entry-comments-no"><?php comments_number(); ?></a>
							</div>
						<?php endif; ?>

						<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Continue Reading', 'olsen-light' ); ?></a>
					</div>
					<?php endif; // hide content ?>
				</div>
			<?php endwhile; ?>
		</div>

	<?php endif; ?>
<?php endif; ?>
