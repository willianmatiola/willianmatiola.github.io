<?php $related = ci_get_related_posts( get_the_ID(), 3 ); ?>
<?php if ( $related->have_posts() ): ?>
	<div class="entry-related">
		<?php if ( is_single() && get_theme_mod( 'single_related_title', __( 'You may also like', 'olsen-light' ) ) ): ?>
			<h4><?php echo esc_html( get_theme_mod( 'single_related_title', __( 'You may also like', 'olsen-light' ) ) ); ?></h4>
		<?php elseif ( ! is_single() && get_theme_mod( 'blog_related_title', __( 'You may also like', 'olsen-light' ) ) ) : ?>
			<h4><?php echo esc_html( get_theme_mod( 'blog_related_title', __( 'You may also like', 'olsen-light' ) ) ); ?></h4>
		<?php endif; ?>

		<div class="row">
			<?php while ( $related->have_posts() ): $related->the_post(); ?>
				<div class="col-sm-4">
					<?php get_template_part( 'content', 'entry-widget' ); ?>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
<?php endif; ?>
