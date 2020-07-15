<?php
add_action( 'wp_head', 'olsen_light_customizer_css' );
if( ! function_exists( 'olsen_light_customizer_css' ) ):
function olsen_light_customizer_css() {
    ?><style type="text/css"><?php

		//
		// Logo
		//
		if( get_theme_mod( 'logo_padding_top' ) || get_theme_mod( 'logo_padding_bottom' ) ) {
			?>
			.site-logo img {
				<?php if( get_theme_mod( 'logo_padding_top' ) ): ?>
					padding-top: <?php echo intval( get_theme_mod( 'logo_padding_top' ) ); ?>px;
				<?php endif; ?>
				<?php if( get_theme_mod( 'logo_padding_bottom' ) ): ?>
					padding-bottom: <?php echo intval( get_theme_mod( 'logo_padding_bottom' ) ); ?>px;
				<?php endif; ?>
			}
			<?php
		}

	?></style><?php
}
endif;
