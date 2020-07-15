jQuery(function( $ ) {
	'use strict';

	var $body = $( 'body' );
	var isRTL = $body.hasClass('rtl');

	/* -----------------------------------------
	Responsive Menus Init with mmenu
	----------------------------------------- */
	var $mainNav = $('#masthead .navigation');
	var $topNav  = $('.top-bar .navigation');
	var $mobileWPMenu = $('#masthead .mobile-navigation');
	var $mobileMenu = $mobileWPMenu.length ? $mobileWPMenu : $mainNav;
	var $mobileNav = $( '#mobilemenu' );

	$mobileMenu.clone().removeAttr( 'id' ).removeClass().appendTo( $mobileNav );
	$mobileNav.find( 'li' ).removeAttr( 'id' );

	$mobileNav.mmenu({
		offCanvas: {
			position: 'top',
			zposition: 'front'
		},
		"autoHeight": true,
		"navbars": [
			{
				"position": "top",
				"content": [
					"prev",
					"title",
					"close"
				]
			}
		]
	});

	/* -----------------------------------------
	Main Navigation Init
	----------------------------------------- */
	$mainNav.add($topNav).superfish({
		delay: 300,
		animation: { opacity: 'show', height: 'show' },
		speed: 'fast',
		dropShadows: false
	});

	/* -----------------------------------------
	Responsive Videos with fitVids
	----------------------------------------- */
	$body.fitVids();


	/* -----------------------------------------
	Image Lightbox
	----------------------------------------- */
	$( ".ci-lightbox, a[data-lightbox^='gal']" ).magnificPopup({
		type: 'image',
		mainClass: 'mfp-with-zoom',
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true
		}
	} );


	/* -----------------------------------------
	Instagram Widget
	----------------------------------------- */
	var $instagramWrap = $('.footer-widget-area');
	var $instagramWidget = $instagramWrap.find('.instagram-pics');

	if ( $instagramWidget.length ) {
		var auto  = $instagramWrap.data('auto'),
			speed = $instagramWrap.data('speed');

		$instagramWidget.slick({
			slidesToShow: 8,
			slidesToScroll: 3,
			arrows: false,
			autoplay: auto == 1,
			speed: speed,
			rtl: isRTL,
			responsive: [
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 4
					}
				}
			]
		});
	}

	/* -----------------------------------------
	Main Carousel
	----------------------------------------- */
	var homeSlider = $( '.home-slider' );

	if ( homeSlider.length ) {
		var autoplay = homeSlider.data( 'autoplay' ),
			autoplayspeed = homeSlider.data( 'autoplayspeed' ),
			fade = homeSlider.data( 'fade' );

		homeSlider.slick({
			autoplay: autoplay == 1,
			autoplaySpeed: autoplayspeed,
			fade: fade == 1,
			rtl: isRTL,
		});
	}

	var $window = $(window);

	$window.load(function() {
		var $equals = $("#site-content > .row > div[class^='col']");

		/* -----------------------------------------
		Equalize Content area heights
		----------------------------------------- */
		$equals.matchHeight();

	});
});
