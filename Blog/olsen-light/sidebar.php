<div class="sidebar sidebar-right" role="complementary" itemtype="http://schema.org/WPSideBar" itemscope="itemscope">
	<?php
		if( is_page() && is_active_sidebar( 'page' ) ) {
			dynamic_sidebar( 'page' );
		} else {
			dynamic_sidebar( 'blog' );
		}
	?>
</div><!-- /sidebar -->
