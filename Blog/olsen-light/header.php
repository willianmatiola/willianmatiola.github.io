<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> itemscope="itemscope" itemtype="http://schema.org/WebPage">
<?php wp_body_open(); ?>

<?php if ( get_theme_mod( 'site_socials' ) == 1 ): ?>
	<div class="site-socials">
		<?php get_template_part( 'part', 'social-icons' ); ?>
	</div>
<?php endif; ?>

<div id="page">

	<?php if ( has_nav_menu( 'top_menu' ) ) :

		$mobile_empty = 1 !== get_theme_mod( 'topbar_socials', 1 ) ? 'mobile-empty' : '';
	?>

		<div class="top-bar group <?php echo esc_attr( $mobile_empty ); ?>">
			<nav class="nav" role="navigation">
				<?php wp_nav_menu( array(
					'theme_location' => 'top_menu',
					'container'      => '',
					'menu_id'        => '',
					'menu_class'     => 'navigation'
				) ); ?>
			</nav>

			<?php if ( get_theme_mod( 'topbar_socials', 1 ) ) : ?>
				<div class="site-tools">
						<?php get_template_part( 'part-social-icons' ); ?>
				</div><!-- /site-tools -->
			<?php endif; ?>
		</div><!-- /top-bar -->
	<?php endif; ?>

	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<header id="masthead" class="site-header group" role="banner" itemscope="itemscope" itemtype="http://schema.org/Organization">

					<div class="site-logo">
						<div itemprop="name">
							<a itemprop="url" href="<?php echo esc_url( home_url() ); ?>">
								<?php if ( get_theme_mod( 'logo', get_template_directory_uri() . '/images/logo.png' ) ): ?>
									<img itemprop="logo"
									     src="<?php echo esc_url( get_theme_mod( 'logo', get_template_directory_uri() . '/images/logo.png' ) ); ?>"
									     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
								<?php else: ?>
									<?php bloginfo( 'name' ); ?>
								<?php endif; ?>
							</a>
						</div>

						<?php if ( get_bloginfo( 'description' ) ): ?>
							<p class="tagline"><?php bloginfo( 'description' ); ?></p>
						<?php endif; ?>
					</div><!-- /site-logo -->

					<div class="site-bar group">
						<nav class="nav" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
							<?php wp_nav_menu( array(
								'theme_location' => 'main_menu',
								'container'      => '',
								'menu_id'        => '',
								'menu_class'     => 'navigation'
							) ); ?>

							<a class="mobile-nav-trigger" href="#mobilemenu"><i class="fa fa-navicon"></i> <?php esc_html_e( 'Menu', 'olsen-light' ); ?></a>
						</nav>
						<?php if ( has_nav_menu( 'mobile_menu' ) ) : ?>
								<nav class="mobile-nav-location" role="navigation">
									<?php wp_nav_menu( array(
										'theme_location' => 'mobile_menu',
										'container'      => '',
										'menu_id'        => '',
										'menu_class'     => 'mobile-navigation',
									) ); ?>
								</nav>
							<?php endif; ?>
						<div id="mobilemenu"></div>

						<?php $has_search = get_theme_mod( 'header_searchform', 0 ); ?>

							<div class="site-tools <?php echo $has_search === 1 ? 'has-search' : ''; ?>">
								<?php if ( $has_search === 1 ) {
									get_search_form();
								} ?>

								<?php if ( get_theme_mod( 'header_socials', 1 ) === 1 ) {
									get_template_part( 'part-social-icons' );
								} ?>

							</div><!-- /site-tools -->
					</div><!-- /site-bar -->

				</header>

				<?php if ( is_home() ) {
					get_template_part( 'part', 'slider' );
				} ?>

				<div id="site-content">
