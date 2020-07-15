(function($) {
	$(window).load( function() {
		$( '.olsen-light-sample-content-notice' ).parents( '.is-dismissible' ).on( 'click', 'button', function( e ) {
			console.log( $(this) );
			$.ajax( {
				type: 'post',
				url: ajaxurl,
				data: {
					action: 'olsen_light_dismiss_sample_content',
					nonce: olsen_light_SampleContent.dismiss_nonce,
					dismissed: true
				},
				dataType: 'text',
				success: function( response ) {
					// console.log( response );
				}
			} );
		});
	});
})(jQuery);
