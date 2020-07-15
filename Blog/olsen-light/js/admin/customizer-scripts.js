jQuery( document ).ready( function( $ ) {

		var customizeInfo = $( "#customize-info" );		
							
		if ( customizeInfo.length ) {			
			customizeInfo.append( "<div class='customizer-upgrade-wrap'></div>");
			var customizerUpgradeWrap = $(".customizer-upgrade-wrap");	
			
			var upgradeButton = $("<a />", {
				class: 	"customizer-link customizer-upgrade",		
				target: "_blank",
				href : 	"https://www.cssigniter.com/themes/olsen/",
				text : 	olsen_light_customizer.upgrade_text
			});
				
			var docButton = $("<a />", {
				class: 	"customizer-link customizer-documentation",		
				target: "_blank",
				href : 	"https://www.cssigniter.com/docs/olsen-light/",
				text : 	olsen_light_customizer.documentation_text
			});
						
			customizerUpgradeWrap.append( upgradeButton );
			customizerUpgradeWrap.append( docButton );
		}
		
} );