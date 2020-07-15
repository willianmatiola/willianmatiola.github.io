<?php if( in_the_loop() ): ?>
	<ul class="socials">
		<?php
			$thumb_id = get_post_thumbnail_id();

			$target = '';
			if ( get_theme_mod( 'social_target', 1 ) == 1 ) {
				$target = 'target="_blank"';
			}

			$facebook = add_query_arg( array(
				'u' => get_permalink(),
			), 'https://www.facebook.com/sharer.php' );

			$twitter = add_query_arg( array(
				'url' => get_permalink(),
			), 'https://twitter.com/share' );

			$linkedin = add_query_arg( array(
				'url' => rawurlencode( get_permalink() ),
			), 'https://www.linkedin.com/shareArticle?mini=true' );

			$pinterest = add_query_arg( array(
				'url'         => get_permalink(),
				'description' => get_the_title(),
				'media'       => olsen_light_get_image_src( get_post_thumbnail_id(), 'large' ),
			), 'https://pinterest.com/pin/create/bookmarklet/' );
		?>
		<li><a href="<?php echo esc_url( $facebook ); ?>" <?php echo $target; ?> class="social-icon"><i class="fa fa-facebook"></i></a></li>
		<li><a href="<?php echo esc_url( $twitter ); ?>" <?php echo $target; ?> class="social-icon"><i class="fa fa-twitter"></i></a></li>
		<li><a href="<?php echo esc_url( $linkedin ); ?>" <?php echo $target; ?> class="social-icon"><i class="fa fa-linkedin"></i></a></li>
		<?php if ( ! empty( $thumb_id ) ): ?>
			<li><a href="<?php echo esc_url( $pinterest ); ?>" <?php echo $target; ?> class="social-icon"><i class="fa fa-pinterest"></i></a></li>
		<?php endif; ?>
	</ul>
<?php endif; ?>
