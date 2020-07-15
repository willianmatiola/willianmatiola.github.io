<?php
require get_template_directory() . '/inc/helpers.php';
require get_template_directory() . '/inc/sanitization.php';
require get_template_directory() . '/inc/functions.php';
require get_template_directory() . '/inc/helpers-post-meta.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/customizer-styles.php';

/**
 * Common theme features.
 */
require_once get_theme_file_path( '/common/common.php' );

add_action( 'after_setup_theme', 'olsen_light_content_width', 0 );
function olsen_light_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'olsen_light_content_width', 665 );
}

add_action( 'after_setup_theme', 'olsen_light_setup' );
if( !function_exists( 'olsen_light_setup' ) ) :
function olsen_light_setup() {

	if ( ! defined( 'OLSEN_LIGHT_NAME' ) ) {
		define( 'OLSEN_LIGHT_NAME', 'olsen-light' );
	}

	load_theme_textdomain( 'olsen-light', get_template_directory() . '/languages' );

	/*
	 * Theme supports.
	 */
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Image sizes.
	 */
	set_post_thumbnail_size( 665, 435, true );
	add_image_size( 'ci_slider', 1110, 600, true );
	add_image_size( 'olsen_light_square', 200, 200, true );

	/*
	 * Navigation menus.
	 */
	register_nav_menus( array(
		'main_menu'   => esc_html__( 'Main Menu', 'olsen-light' ),
		'top_menu'    => esc_html__( 'Top Menu', 'olsen-light' ),
		'footer_menu' => esc_html__( 'Footer Menu', 'olsen-light' ),
		'mobile_menu' => esc_html__( 'Mobile Menu', 'olsen-light' ),
	) );

	/*
	 * Default hooks
	 */
	// Prints the inline JS scripts that are registered for printing, and removes them from the queue.
	add_action( 'admin_footer', 'olsen_light_print_inline_js' );
	add_action( 'wp_footer', 'olsen_light_print_inline_js' );

	// Handle the dismissible sample content notice.
	global $pagenow;
	if ( 'themes.php' === $pagenow && ! empty( $_GET['page'] ) && 'pt-one-click-demo-import' === $_GET['page'] ) {
		add_action( 'admin_notices', 'olsen_light_admin_notice_sample_content_ocdi' );
	} else {
		add_action( 'admin_notices', 'olsen_light_admin_notice_sample_content' );
		add_action( 'wp_ajax_olsen_light_dismiss_sample_content', 'olsen_light_ajax_dismiss_sample_content' );
	}


	// Wraps post counts in span.ci-count
	// Needed for the default widgets, however more appropriate filters don't exist.
	add_filter( 'get_archives_link', 'olsen_light_wrap_archive_widget_post_counts_in_span', 10, 2 );
	add_filter( 'wp_list_categories', 'olsen_light_wrap_category_widget_post_counts_in_span', 10, 2 );
}
endif;



add_action( 'wp_enqueue_scripts', 'olsen_light_enqueue_scripts' );
function olsen_light_enqueue_scripts() {

	/*
	 * Styles
	 */
	$theme = wp_get_theme();

	$font_url = '';
	/* translators: If there are characters in your language that are not supported by Lora and Lato, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Lora and Lato fonts: on or off', 'olsen-light' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lora:400,700,400italic,700italic|Lato:400,400italic,700,700italic' ), '//fonts.googleapis.com/css' );
	}
	wp_register_style( 'olsen-light-google-font', esc_url( $font_url ) );

	wp_register_style( 'olsen-light-base', get_template_directory_uri() . '/css/base.css', array(), $theme->get( 'Version' ) );
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.7.0' );
	wp_register_style( 'olsen-light-magnific', get_template_directory_uri() . '/css/magnific.css', array(), '1.0.0' );
	wp_register_style( 'olsen-light-slick', get_template_directory_uri() . '/css/slick.css', array(), '1.5.7' );
	wp_register_style( 'olsen-light-mmenu', get_template_directory_uri() . '/css/mmenu.css', array(), '5.2.0' );

	wp_enqueue_style( 'olsen-light-style', get_template_directory_uri() . '/style.css', array(
		'olsen-light-google-font',
		'olsen-light-base',
		'olsen-light-common',
		'font-awesome',
		'olsen-light-magnific',
		'olsen-light-slick',
		'olsen-light-mmenu'
	), $theme->get( 'Version' ) );

	if( is_child_theme() ) {
		wp_enqueue_style( 'olsen-light-style-child', get_stylesheet_directory_uri() . '/style.css', array(
			'olsen-light-style',
		), $theme->get( 'Version' ) );
	}

	/*
	 * Scripts
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_register_script( 'olsen-light-superfish', get_template_directory_uri() . '/js/superfish.js', array( 'jquery' ), '1.7.5', true );
	wp_register_script( 'olsen-light-matchHeight', get_template_directory_uri() . '/js/jquery.matchHeight.js', array( 'jquery' ), $theme->get( 'Version' ), true );
	wp_register_script( 'olsen-light-slick', get_template_directory_uri() . '/js/slick.js', array( 'jquery' ), '1.5.7', true );
	wp_register_script( 'olsen-light-mmenu-offcanvas', get_template_directory_uri() . '/js/jquery.mmenu.offcanvas.js', array( 'jquery' ), '5.2.0', true );
	wp_register_script( 'olsen-light-mmenu-navbars', get_template_directory_uri() . '/js/jquery.mmenu.navbars.js', array( 'jquery' ), '5.2.0', true );
	wp_register_script( 'olsen-light-mmenu-autoheight', get_template_directory_uri() . '/js/jquery.mmenu.autoheight.js', array( 'jquery' ), '5.2.0', true );
	wp_register_script( 'olsen-light-mmenu', get_template_directory_uri() . '/js/jquery.mmenu.oncanvas.js', array( 'jquery', ), '5.2.0', true );
	wp_register_script( 'olsen-light-fitVids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.1', true );
	wp_register_script( 'olsen-light-magnific', get_template_directory_uri() . '/js/jquery.magnific-popup.js', array( 'jquery' ), '1.0.0', true );

	/*
	 * Enqueue
	 */
	wp_enqueue_script( 'olsen-light-front-scripts', get_template_directory_uri() . '/js/scripts.js', array(
		'jquery',
		'olsen-light-superfish',
		'olsen-light-matchHeight',
		'olsen-light-slick',
		'olsen-light-mmenu',
		'olsen-light-mmenu-offcanvas',
		'olsen-light-mmenu-navbars',
		'olsen-light-mmenu-autoheight',
		'olsen-light-fitVids',
		'olsen-light-magnific'
	), $theme->get( 'Version' ), true );

}

add_action( 'admin_enqueue_scripts', 'olsen_light_admin_enqueue_scripts' );
function olsen_light_admin_enqueue_scripts( $hook ) {
	$theme = wp_get_theme();

	/*
	 * Styles
	 */


	/*
	 * Scripts
	 */
	wp_register_script( 'olsen-light-customizer', get_template_directory_uri() . '/js/admin/customizer-scripts.js', array( 'jquery' ), $theme->get( 'Version' ), true );
	$params = array(
		'documentation_text' => esc_html__( 'Documentation', 'olsen-light' ),
		'upgrade_text'       => esc_html__( 'Upgrade to Pro', 'olsen-light' ),
	);
	wp_localize_script( 'olsen-light-customizer', 'olsen_light_customizer', $params );


	/*
	 * Enqueue
	 */
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'olsen-light-post-meta' );
		wp_enqueue_script( 'olsen-light-post-meta' );
	}

	if ( in_array( $hook, array( 'profile.php', 'user-edit.php' ) ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'olsen-light-post-meta' );
		wp_enqueue_script( 'olsen-light-post-meta' );
	}

	if ( in_array( $hook, array( 'widgets.php', 'customize.php' ) ) ) {
		wp_enqueue_media();
		wp_enqueue_style( 'olsen-light-post-meta' );
		wp_enqueue_script( 'olsen-light-post-meta' );
		wp_enqueue_script( 'olsen-light-customizer' );
	}

}

add_action( 'customize_controls_print_styles', 'olsen_light_enqueue_customizer_styles' );
function olsen_light_enqueue_customizer_styles() {
	$theme = wp_get_theme();

	wp_register_style( 'olsen-light-customizer-styles', get_template_directory_uri() . '/css/admin/customizer-styles.css', array(), $theme->get( 'Version' ) );
	wp_enqueue_style( 'olsen-light-customizer-styles' );
}


add_action( 'widgets_init', 'olsen_light_widgets_init' );
function olsen_light_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html_x( 'Blog', 'widget area', 'olsen-light' ),
		'id'            => 'blog',
		'description'   => esc_html__( 'This is the main sidebar.', 'olsen-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Pages', 'widget area', 'olsen-light' ),
		'id'            => 'page',
		'description'   => esc_html__( 'This sidebar appears on your static pages. If empty, the Blog sidebar will be shown instead.', 'olsen-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html_x( 'Footer Sidebar', 'widget area', 'olsen-light' ),
		'id'            => 'footer-widgets',
		'description'   => esc_html__( 'Special site-wide sidebar for the WP Instagram Widget plugin.', 'olsen-light' ),
		'before_widget' => '<aside id="%1$s" class="widget group %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'olsen_light_load_widgets' );
function olsen_light_load_widgets() {
	require get_template_directory() . '/inc/widgets/about-me.php';
	require get_template_directory() . '/inc/widgets/latest-posts.php';
	require get_template_directory() . '/inc/widgets/socials.php';
}

add_filter( 'excerpt_length', 'olsen_light_excerpt_length' );
function olsen_light_excerpt_length( $length ) {
	return get_theme_mod( 'excerpt_length', 55 );
}


add_filter( 'previous_posts_link_attributes', 'olsen_light_previous_posts_link_attributes' );
function olsen_light_previous_posts_link_attributes( $attrs ) {
	$attrs .= ' class="paging-standard paging-older"';
	return $attrs;
}
add_filter( 'next_posts_link_attributes', 'olsen_light_next_posts_link_attributes' );
function olsen_light_next_posts_link_attributes( $attrs ) {
	$attrs .= ' class="paging-standard paging-newer"';
	return $attrs;
}

add_filter( 'wp_page_menu', 'olsen_light_wp_page_menu', 10, 2 );
function olsen_light_wp_page_menu( $menu, $args ) {
	preg_match( '#^<div class="(.*?)">(?:.*?)</div>$#', $menu, $matches );
	$menu = preg_replace( '#^<div class=".*?">#', '', $menu, 1 );
	$menu = preg_replace( '#</div>$#', '', $menu, 1 );
	$menu = preg_replace( '#^<ul>#', '<ul class="' . esc_attr( $args['menu_class'] ) . '">', $menu, 1 );
	return $menu;
}


add_filter( 'the_content', 'olsen_light_lightbox_rel', 12 );
add_filter( 'get_comment_text', 'olsen_light_lightbox_rel' );
add_filter( 'wp_get_attachment_link', 'olsen_light_lightbox_rel' );
if ( ! function_exists( 'olsen_light_lightbox_rel' ) ) :
function olsen_light_lightbox_rel( $content ) {
	global $post;
	if ( ! is_admin() && ! empty( $post ) ) {
		$pattern     = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
		$replacement = '<a$1href=$2$3.$4$5 data-lightbox="gal[' . $post->ID . ']"$6>$7</a>';
		$content     = preg_replace( $pattern, $replacement, $content );
	}

	return $content;
}
endif;


add_filter( 'wp_link_pages_args', 'olsen_light_wp_link_pages_args' );
function olsen_light_wp_link_pages_args( $params ) {
	$params = array_merge( $params, array(
		'before' => '<p class="link-pages">' . esc_html__( 'Pages:', 'olsen-light' ),
		'after'  => '</p>',
	) );

	return $params;
}

if ( ! function_exists( 'olsen_light_get_social_networks') ) {
	function olsen_light_get_social_networks() {
		return array(
			array(
				'name'  => 'facebook',
				'label' => esc_html__( 'Facebook', 'olsen-light' ),
				'icon'  => 'fa-facebook',
			),
			array(
				'name'  => 'twitter',
				'label' => esc_html__( 'Twitter', 'olsen-light' ),
				'icon'  => 'fa-twitter',
			),
			array(
				'name'  => 'pinterest',
				'label' => esc_html__( 'Pinterest', 'olsen-light' ),
				'icon'  => 'fa-pinterest',
			),
			array(
				'name'  => 'instagram',
				'label' => esc_html__( 'Instagram', 'olsen-light' ),
				'icon'  => 'fa-instagram',
			),
			array(
				'name'  => 'linkedin',
				'label' => esc_html__( 'LinkedIn', 'olsen-light' ),
				'icon'  => 'fa-linkedin',
			),
			array(
				'name'  => 'tumblr',
				'label' => esc_html__( 'Tumblr', 'olsen-light' ),
				'icon'  => 'fa-tumblr',
			),
			array(
				'name'  => 'flickr',
				'label' => esc_html__( 'Flickr', 'olsen-light' ),
				'icon'  => 'fa-flickr',
			),
			array(
				'name'  => 'bloglovin',
				'label' => esc_html__( 'Bloglovin', 'olsen-light' ),
				'icon'  => 'fa-heart',
			),
			array(
				'name'  => 'youtube',
				'label' => esc_html__( 'YouTube', 'olsen-light' ),
				'icon'  => 'fa-youtube',
			),
			array(
				'name'  => 'vimeo',
				'label' => esc_html__( 'Vimeo', 'olsen-light' ),
				'icon'  => 'fa-vimeo',
			),
			array(
				'name'  => 'dribbble',
				'label' => esc_html__( 'Dribbble', 'olsen-light' ),
				'icon'  => 'fa-dribbble',
			),
			array(
				'name'  => 'wordpress',
				'label' => esc_html__( 'WordPress', 'olsen-light' ),
				'icon'  => 'fa-wordpress',
			),
			array(
				'name'  => '500px',
				'label' => esc_html__( '500px', 'olsen-light' ),
				'icon'  => 'fa-500px',
			),
			array(
				'name'  => 'soundcloud',
				'label' => esc_html__( 'Soundcloud', 'olsen-light' ),
				'icon'  => 'fa-soundcloud',
			),
			array(
				'name'  => 'spotify',
				'label' => esc_html__( 'Spotify', 'olsen-light' ),
				'icon'  => 'fa-spotify',
			),
			array(
				'name'  => 'vine',
				'label' => esc_html__( 'Vine', 'olsen-light' ),
				'icon'  => 'fa-vine',
			),
			array(
				'name'  => 'tripadvisor',
				'label' => esc_html__( 'Trip Advisor', 'olsen-light' ),
				'icon'  => 'fa-tripadvisor',
			),
			array(
				'name'  => 'telegram',
				'label' => esc_html__( 'Telegram', 'olsen-light' ),
				'icon'  => 'fa-telegram',
			),
		);
	}
}

if ( ! function_exists( 'olsen_light_get_layout_classes' ) ) {
	function olsen_light_get_layout_classes( $setting ) {
		$layout            = get_theme_mod( $setting, 'classic_1side' );
		$content_col       = '';
		$sidebar_right_col = '';
		$main_class        = 'entries-classic';
		$post_class        = '';
		$post_col          = '';

		switch ( $layout ) {
			case 'classic_1side':
				$content_col       = 'col-md-8';
				$sidebar_right_col = 'col-md-4';
				break;
			case '2cols_side' :
				$content_col       = 'col-md-8';
				$sidebar_right_col = 'col-md-4';
				$main_class        = 'entries-grid';
				$post_class        = 'entry-grid';
				$post_col          = 'col-sm-6';
				break;
		}

		return array(
			'layout'            => $layout,
			'content_col'       => $content_col,
			'sidebar_right_col' => $sidebar_right_col,
			'main_class'        => $main_class,
			'post_class'        => $post_class,
			'post_col'          => $post_col
		);
	}
}

if ( ! function_exists( 'olsen_light_sanitize_blog_terms_layout' ) ):
function olsen_light_sanitize_blog_terms_layout( $layout ) {
	$layouts = array(
		'classic_1side',
		'2cols_side',
	);
	if ( in_array( $layout, $layouts ) ) {
		return $layout;
	}

	return 'classic_1side';
}
endif;


function olsen_light_admin_notice_sample_content_ocdi() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$documentation_url = 'https://www.cssigniter.com/docs/olsen-light/#importing-sample-content';

	?>
	<div class="notice notice-info">
		<div class="olsen-light-sample-content-notice">
			<p>
				<?php
					/* translators: %s is a URL. */
					echo wp_kses( sprintf( __( '<a href="%s" target="_blank">Download the theme\'s sample content</a> to get things moving.', 'olsen-light' ), esc_url( $documentation_url ) ),
						olsen_light_get_allowed_tags( 'guide' )
					);
				?>
			</p>
		</div>
	</div>
	<?php
}


if ( ! defined( 'OLSEN_LIGHT_WHITELABEL' ) || false === (bool) OLSEN_LIGHT_WHITELABEL ) {
	add_action( 'pt-ocdi/after_import', 'olsen_light_ocdi_after_import_setup' );
}

add_filter( 'pt-ocdi/timeout_for_downloading_import_file', 'olsen_light_ocdi_download_timeout' );
function olsen_light_ocdi_download_timeout( $timeout ) {
	return 60;
}

function olsen_light_ocdi_after_import_setup() {
	// Set up nav menus.
	$main_menu      = get_term_by( 'name', 'Main', 'nav_menu' );
	$secondary_menu = get_term_by( 'name', 'Footer', 'nav_menu' );

	if ( $main_menu && $secondary_menu ) {
		set_theme_mod( 'nav_menu_locations', array(
			'main_menu'   => $main_menu->term_id,
			'footer_menu' => $secondary_menu->term_id,
		) );
	}

	update_option( 'show_on_front', 'posts' );

	// Try to force a term recount.
	// wp_defer_term_counting( false ) doesn't work properly as there are post imported from different AJAX requests.
	$taxonomies = get_taxonomies( array(), 'names' );
	foreach ( $taxonomies as $taxonomy ) {
		$terms             = get_terms( $taxonomy, array( 'hide_empty' => false ) );
		$term_taxonomy_ids = wp_list_pluck( $terms, 'term_taxonomy_id' );

		wp_update_term_count( $term_taxonomy_ids, $taxonomy );
	}
}

/* Add .opening custom class in TinyMCE */
add_filter( 'tiny_mce_before_init', 'olsen_light_insert_wp_editor_formats' );
if ( ! function_exists( 'olsen_light_insert_wp_editor_formats' ) ) :
	function olsen_light_insert_wp_editor_formats( $init_array ) {
		// Define the style_formats array
		$style_formats = array(
			// Each array child is a format with it's own settings
			array(
				'title'   => esc_html__( 'Opening Paragraph', 'olsen-light' ),
				'block'   => 'div',
				'classes' => 'opening',
				'wrapper' => true,
			),
		);
		// Insert the array, JSON ENCODED, into 'style_formats'
		$init_array['style_formats'] = wp_json_encode( $style_formats );

		return $init_array;
	}
endif;

add_filter( 'mce_buttons_2', 'olsen_light_mce_buttons_2' );
if ( ! function_exists( 'olsen_light_mce_buttons_2' ) ) :
	function olsen_light_mce_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );

		return $buttons;
	}
endif;
