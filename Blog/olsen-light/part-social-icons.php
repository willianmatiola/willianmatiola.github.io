<?php
	$networks = olsen_light_get_social_networks();
	$user     = array();
	$global   = array();
	$used     = array();
	$has_rss  = get_theme_mod( 'rss_feed', get_bloginfo( 'rss2_url' ) ) ? true : false;

	if( in_the_loop() ) {
		foreach( $networks as $network ) {
			if( get_the_author_meta( 'user_' . $network['name'] ) ) {
				$user[ $network['name'] ] = get_the_author_meta( 'user_' . $network['name'] );
			}
		}
	}

	foreach( $networks as $network ) {
		if( get_theme_mod( 'social_' . $network['name'] ) ) {
			$global[ $network['name'] ] = get_theme_mod( 'social_' . $network['name'] );
		}
	}

	// Determine whether we should use the user's socials.
	if ( count( $user ) > 0 ) {
		$used = $user;
	} elseif ( count( $global ) > 0 ) {
		$used = $global;
	}

	// Only the content should show the user's socials however.
	if( ! in_the_loop() ) {
		$used = $global;
	}

	// Set the target attribute for social icons.
	$target = '';
	if ( get_theme_mod( 'social_target', 1 ) == 1 ) {
		$target = 'target="_blank"';
	}

	if ( ( in_the_loop() && count( $used ) > 0 ) || ( ! in_the_loop() && ( count( $used ) > 0 || $has_rss ) ) ) {
		?>
		<ul class="socials">
			<?php
				foreach ( $networks as $network ) {
					if ( ! empty( $used[ $network['name'] ] ) ) {
						echo sprintf( '<li><a href="%s" %s><i class="fa %s"></i></a></li>',
							esc_url( $used[ $network['name'] ] ),
							$target,
							esc_attr( $network['icon'] )
						);
					}
				}
			?>
			<?php if( ! in_the_loop() && $has_rss ): ?>
				<li><a href="<?php echo esc_url( get_theme_mod( 'rss_feed', get_bloginfo( 'rss2_url' ) ) ); ?>" <?php echo $target; ?>><i class="fa fa-rss"></i></a></li>
			<?php endif; ?>
		</ul>
		<?php
	}
