<?php
add_action( 'customize_register', 'olsen_light_customize_register', 100 );
/**
 * Registers all theme-related options to the Customizer.
 *
 * @param WP_Customize_Manager $wpc Reference to the customizer's manager object.
 */
function olsen_light_customize_register( $wpc ) {

	$wpc->add_section( 'top-bar', array(
		'title'       => _x( 'Top Bar Options', 'customizer section title', 'olsen-light' ),
		'description' => __( 'To show/hide the top bar set/unset the menu in the top bar menu location.', 'olsen-light' ),
		'priority'    => 1
	) );

	$wpc->add_section( 'header', array(
		'title'    => esc_html_x( 'Header Options', 'customizer section title', 'olsen-light' ),
		'priority' => 10
	) );

	$wpc->get_panel( 'nav_menus' )->priority = 2;

	$wpc->add_section( 'layout', array(
		'title'    => esc_html_x( 'Layout Options', 'customizer section title', 'olsen-light' ),
		'priority' => 20
	) );

	$wpc->add_section( 'homepage', array(
		'title'    => _x( 'Front Page Carousel', 'customizer section title', 'olsen-light' ),
		'priority' => 25
	) );

	// The following line doesn't work in a some PHP versions. Apparently, get_panel( 'widgets' ) returns an array,
	// therefore a cast to object is needed. http://wordpress.stackexchange.com/questions/160987/warning-creating-default-object-when-altering-customize-panels
	//$wpc->get_panel( 'widgets' )->priority = 55;
	$panel_widgets = (object) $wpc->get_panel( 'widgets' );
    $panel_widgets->priority = 55;

	$wpc->add_section( 'social', array(
		'title'       => esc_html_x( 'Social Networks', 'customizer section title', 'olsen-light' ),
		'description' => esc_html__( 'Enter your social network URLs. Leaving a URL empty will hide its respective icon.', 'olsen-light' ),
		'priority'    => 60
	) );

	$wpc->add_section( 'single_post', array(
		'title'       => esc_html_x( 'Posts Options', 'customizer section title', 'olsen-light' ),
		'description' => esc_html__( 'These options affect your individual posts.', 'olsen-light' ),
		'priority'    => 70
	) );

	$wpc->add_section( 'single_page', array(
		'title'       => esc_html_x( 'Pages Options', 'customizer section title', 'olsen-light' ),
		'description' => esc_html__( 'These options affect your individual pages.', 'olsen-light' ),
		'priority'    => 80
	) );

	$wpc->add_section( 'footer', array(
		'title'    => esc_html_x( 'Footer Options', 'customizer section title', 'olsen-light' ),
		'priority' => 100
	) );

	// Section 'static_front_page' is not defined when there are no pages.
	if ( get_pages() ) {
		$wpc->get_section( 'static_front_page' )->priority = 110;
	}

	$wpc->add_section( 'theme_upgrade', array(
		'title'    => esc_html_x( 'Upgrade to Pro', 'customizer section title', 'olsen-light' ),
		'priority' => 130
	) );



	//
	// Group options by registering the setting first, and the control right after.
	//

	//
	// Layout
	//
	$choices = array(
		'classic_1side'       => _x( 'Classic - One Sidebar', 'page layout', 'olsen-light' ),
		'2cols_side'          => _x( 'Two columns - Sidebar', 'page layout', 'olsen-light' ),
	);
	$wpc->add_setting( 'layout_blog', array(
		'default'           => 'classic_1side',
		'sanitize_callback' => 'olsen_light_sanitize_blog_terms_layout',
	) );
	$wpc->add_control( 'layout_blog', array(
		'type'        => 'select',
		'section'     => 'layout',
		'label'       => __( 'Blog layout', 'olsen-light' ),
		'description' => __( 'Applies to the home page and blog-related pages.', 'olsen-light' ),
		'choices'     => $choices,
	) );

	$wpc->add_setting( 'layout_terms', array(
		'default'           => 'classic_1side',
		'sanitize_callback' => 'olsen_light_sanitize_blog_terms_layout',
	) );
	$wpc->add_control( 'layout_terms', array(
		'type'        => 'select',
		'section'     => 'layout',
		'label'       => __( 'Categories and Tags layout', 'olsen-light' ),
		'description' => __( 'Applies to the categories and tags listing pages.', 'olsen-light' ),
		'choices'     => $choices,
	) );

	$wpc->add_setting( 'excerpt_length', array(
		'default'           => 55,
		'sanitize_callback' => 'absint',
	) );
	$wpc->add_control( 'excerpt_length', array(
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 10,
			'step' => 1,
		),
		'section'     => 'layout',
		'label'       => esc_html__( 'Automatically generated excerpt length (in words)', 'olsen-light' ),
	) );

	$wpc->add_setting( 'excerpt_on_classic_layout', array(
		'default'           => '',
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'excerpt_on_classic_layout', array(
		'type'    => 'checkbox',
		'section' => 'layout',
		'label'   => esc_html__( 'Display the excerpt instead of the content.', 'olsen-light' ),
	) );

	$wpc->add_setting( 'pagination_method', array(
		'default'           => 'numbers',
		'sanitize_callback' => 'olsen_light_sanitize_pagination_method',
	) );
	$wpc->add_control( 'pagination_method', array(
		'type'    => 'select',
		'section' => 'layout',
		'label'   => esc_html__( 'Pagination method', 'olsen-light' ),
		'choices' => array(
			'numbers' => esc_html_x( 'Numbered links', 'pagination method', 'olsen-light' ),
			'text'    => esc_html_x( '"Previous - Next" links', 'pagination method', 'olsen-light' ),
		),
	) );

	$wpc->add_setting( 'blog_related', array(
		'default'           => 0,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'blog_related', array(
		'type'    => 'checkbox',
		'section' => 'layout',
		'label'   => __( 'Show related posts in blog listing. Applies to classic layouts only.', 'olsen-light' ),
	) );

	$wpc->add_setting( 'blog_related_title', array(
		'default'           => __( 'You may also like', 'olsen-light' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wpc->add_control( 'blog_related_title', array(
		'type'    => 'text',
		'section' => 'layout',
		'label'   => __( 'Blog Related Posts section title.', 'olsen-light' ),
	) );


	//
	// Top Bar
	//
	$wpc->add_setting( 'topbar_socials', array(
		'default'           => 1,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'topbar_socials', array(
		'type'    => 'checkbox',
		'section' => 'top-bar',
		'label'   => __( 'Show social icons.', 'olsen-light' ),
	) );


	//
	// Header
	//
	$wpc->add_setting( 'header_socials', array(
		'default'           => 1,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'header_socials', array(
		'type'    => 'checkbox',
		'section' => 'header',
		'label'   => esc_html__( 'Show social icons.', 'olsen-light' ),
	) );

	$wpc->add_setting( 'header_searchform', array(
		'default'           => 0,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'header_searchform', array(
		'type'    => 'checkbox',
		'section' => 'header',
		'label'   => __( 'Show search form.', 'olsen-light' ),
	) );


	//
	// Footer
	//
	$wpc->add_setting( 'footer_socials', array(
		'default'           => 1,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'footer_socials', array(
		'type'    => 'checkbox',
		'section' => 'footer',
		'label'   => esc_html__( 'Show social icons.', 'olsen-light' ),
	) );

	$wpc->add_setting( 'footer_credits', array(
		'default'           => 1,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'footer_credits', array(
		'type'    => 'checkbox',
		'section' => 'footer',
		'label'   => esc_html__( 'Show credits text.', 'olsen-light' ),
	) );


	if ( class_exists( 'null_instagram_widget' ) ) {
		$wpc->add_setting( 'instagram_auto', array(
			'default'           => 1,
			'sanitize_callback' => 'olsen_light_sanitize_checkbox',
		) );
		$wpc->add_control( 'instagram_auto', array(
			'type'    => 'checkbox',
			'section' => 'footer',
			'label'   => esc_html__( 'WP Instagram: Slideshow.', 'olsen-light' ),
		) );

		$wpc->add_setting( 'instagram_speed', array(
			'default'           => 300,
			'sanitize_callback' => 'olsen_light_sanitize_intval_or_empty',
		) );
		$wpc->add_control( 'instagram_speed', array(
			'type'    => 'number',
			'section' => 'footer',
			'label'   => esc_html__( 'WP Instagram: Slideshow Speed.', 'olsen-light' ),
		) );
	}


	//
	// Social
	//
	$wpc->add_setting( 'site_socials', array(
		'default'           => '',
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'site_socials', array(
		'type'        => 'checkbox',
		'section'     => 'social',
		'label'       => esc_html__( 'Site-wide social icons.', 'olsen-light' ),
		'description' => esc_html__( 'Shows floating icons on the side of your website. Not visible on mobile devices.', 'olsen-light' ),
	) );

	$wpc->add_setting( 'social_target', array(
		'default'           => 1,
		'sanitize_callback' => 'olsen_light_sanitize_checkbox',
	) );
	$wpc->add_control( 'social_target', array(
		'type'    => 'checkbox',
		'section' => 'social',
		'label'   => esc_html__( 'Open social and sharing links in a new tab.', 'olsen-light' ),
	) );

	$networks = olsen_light_get_social_networks();

	foreach ( $networks as $network ) {
		$wpc->add_setting( 'social_' . $network['name'], array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wpc->add_control( 'social_' . $network['name'], array(
			'type'    => 'url',
			'section' => 'social',
			'label'   => esc_html( sprintf( _x( '%s URL', 'social network url', 'olsen-light' ), $network['label'] ) ),
		) );
	}

	$wpc->add_setting( 'rss_feed', array(
		'default'           => get_bloginfo( 'rss2_url' ),
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wpc->add_control( 'rss_feed', array(
		'type'    => 'url',
		'section' => 'social',
		'label'   => esc_html__( 'RSS Feed', 'olsen-light' ),
	) );


	//
	// Single Post
	//
	$wpc->add_setting( 'single_related_title', array(
		'default'           => esc_html__( 'You may also like', 'olsen-light' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wpc->add_control( 'single_related_title', array(
		'type'    => 'text',
		'section' => 'single_post',
		'label'   => esc_html__( 'Related Posts section title', 'olsen-light' ),
	) );


	//
	// Homepage
	//
	$wpc->add_control( new Olsen_Light_Customize_Slick_Control( $wpc, 'home_slider', array(
		'section'     => 'homepage',
		'label'       => __( 'Home Slider', 'olsen-light' ),
		'description' => __( 'Fine-tune the homepage slider.', 'olsen-light' ),
	), array(
		'taxonomy' => 'category',
	) ) );


	//
	// Theme Upgrade
	//
	$wpc->add_setting( 'upgrade_text', array(
		'default' => '',
	) );
	$wpc->add_control( new Olsen_Light_Customize_Static_Text_Control( $wpc, 'upgrade_text', array(
		'section'     => 'theme_upgrade',
		'label'       => esc_html__( 'Olsen Pro', 'olsen-light' ),
		'description' => array(
			esc_html__( 'Do you enjoy Olsen Light? Upgrade to Pro now and get:', 'olsen-light' ),
			'<ul>' .
				'<li>' . esc_html__( 'Multiple layouts', 'olsen-light' ) . '</li>' .
				'<li>' . esc_html__( 'Infinite style variations', 'olsen-light' ) . '</li>' .
				'<li>' . esc_html__( 'Post formats support', 'olsen-light' ) . '</li>' .
				'<li>' . esc_html__( 'More Customizer options', 'olsen-light' ) . '</li>' .
			'</ul>',
			'<a href="https://www.cssigniter.com/themes/olsen/" class="customizer-link customizer-upgrade">' . esc_html__( 'Upgrade To Pro', 'olsen-light' ) . '</a>',
			'<a href="https://www.cssigniter.com/docs/olsen-light/" class="customizer-link customizer-documentation">' . esc_html__( 'Documentation', 'olsen-light' ) . '</a>'
		),
	) ) );


	//
	// site_tagline Section
	//
	$wpc->add_setting( 'logo', array(
		'default'           => get_template_directory_uri() . '/images/logo.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wpc->add_control( new WP_Customize_Image_Control( $wpc, 'logo', array(
		'section'     => 'title_tagline',
		'label'       => esc_html__( 'Logo', 'olsen-light' ),
		'description' => esc_html__( 'If an image is selected, it will replace the default textual logo (site name) on the header.', 'olsen-light' ),
	) ) );

	$wpc->add_setting( 'logo_padding_top', array(
		'default'           => '',
		'sanitize_callback' => 'olsen_light_sanitize_intval_or_empty',
	) );
	$wpc->add_control( 'logo_padding_top', array(
		'type'    => 'number',
		'section' => 'title_tagline',
		'label'   => esc_html__( 'Logo top padding', 'olsen-light' ),
	) );

	$wpc->add_setting( 'logo_padding_bottom', array(
		'default'           => '',
		'sanitize_callback' => 'olsen_light_sanitize_intval_or_empty',
	) );
	$wpc->add_control( 'logo_padding_bottom', array(
		'type'    => 'number',
		'section' => 'title_tagline',
		'label'   => esc_html__( 'Logo bottom padding', 'olsen-light' ),
	) );

	$wpc->add_setting( 'footer_logo', array(
		'default'           => get_template_directory_uri() . '/images/logo.png',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wpc->add_control( new WP_Customize_Image_Control( $wpc, 'footer_logo', array(
		'section'     => 'title_tagline',
		'label'       => esc_html__( 'Footer logo', 'olsen-light' ),
		'description' => esc_html__( 'If an image is selected, it will replace the default textual logo (site name) on the footer.', 'olsen-light' ),
	) ) );

}

add_action( 'customize_register', 'olsen_light_customize_register_custom_controls', 9 );
/**
 * Registers custom Customizer controls.
 *
 * @param WP_Customize_Manager $wpc Reference to the customizer's manager object.
 */
function olsen_light_customize_register_custom_controls( $wpc ) {
	require get_template_directory() . '/inc/customizer-controls/static-text.php';
	require get_template_directory() . '/inc/customizer-controls/slick.php';
}
