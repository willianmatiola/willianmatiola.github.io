<?php
	if ( is_tag() || is_category() ) {
		$layout = olsen_light_get_layout_classes( 'layout_terms' );
	} else {
		$layout = olsen_light_get_layout_classes( 'layout_blog' );
	}
	$post_class  = $layout['post_class'];
	$post_col    = $layout['post_col'];
	$blog_layout = $layout['layout'];
	$is_classic  = false;

	global $wp_query;
	if( in_array( $layout['layout'], array( '2cols_side' ) ) && is_main_query() && $wp_query->current_post == 0 ) {
		$post_class = '';
		$post_col   = $layout['layout'] === '2cols_side' ? 'col-xs-12' : '';
		$is_classic = true;
	}

	if ( in_array( $blog_layout, array( 'classic_1side' ) ) ) {
		$is_classic = true;
	}

?>

<?php if ( ! empty( $post_col ) ) : ?>
	<div class="<?php echo esc_attr( $post_col ); ?>">
<?php endif; ?>

		<article id="entry-<?php the_ID(); ?>" <?php post_class( 'entry ' . $post_class ); ?> itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost">
			<?php if ( get_post_type() === 'post' ) : ?>
				<div class="entry-meta entry-meta-top">
					<p class="entry-categories">
						<?php the_category( ', ' ); ?>
					</p>
				</div>
			<?php endif; ?>

			<h2 class="entry-title" itemprop="headline">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php if ( get_post_type() === 'post' ) : ?>
				<div class="entry-meta entry-meta-bottom">
					<time class="entry-date" itemprop="datePublished" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					<a href="<?php echo esc_url( get_comments_link() ); ?>" class="entry-comments-no"><?php comments_number(); ?></a>
				</div>
			<?php endif; ?>

			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-featured">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'post-thumbnail', array( 'itemprop' => 'image' ) ); ?>
					</a>
				</div>
			<?php endif; ?>

			<div class="entry-content" itemprop="text">
				<?php if ( ! get_theme_mod( 'excerpt_on_classic_layout' ) ) {
					the_content( '' );
				} else {
					the_excerpt();
				} ?>
			</div>

			<div class="entry-utils group">
				<a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e( 'Continue Reading', 'olsen-light' ); ?></a>

				<?php get_template_part( 'part', 'social-sharing' ); ?>
			</div>

			<?php if ( 1 === get_theme_mod( 'blog_related', 0 ) && 'classic_1side' === $layout['layout'] ) { ?>
				<div class="entry-blog-related group">
					<?php get_template_part( 'part', 'related' ); ?>
				</div>
			<?php } ?>
		</article>

<?php if ( ! empty( $post_col ) ) : ?>
	</div>
<?php endif; ?>
