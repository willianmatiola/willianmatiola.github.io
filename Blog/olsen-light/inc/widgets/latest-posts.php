<?php 
if ( ! class_exists( 'CI_Widget_Latest_Posts' ) ):
	class CI_Widget_Latest_Posts extends WP_Widget {

		function __construct() {
			$widget_ops  = array( 'description' => esc_html__( 'Displays a number of the latest (or random) posts from a specific category.', 'olsen-light' ) );
			$control_ops = array();
			parent::__construct( 'ci-latest-posts', esc_html__( 'Theme - Latest Posts', 'olsen-light' ), $widget_ops, $control_ops );
		}

		function widget($args, $instance) {
			extract($args);
			$title       = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$category    = $instance['category'];
			$random      = $instance['random'];
			$count       = $instance['count'];
			$item_layout = $instance['item_layout'];

			if ( 0 == $count ) {
				return;
			}

			$args = array(
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => $count
			);

			if ( 1 == $random ) {
				$args['orderby'] = 'rand';
				unset( $args['order'] );
			}

			if( ! empty( $category ) && $category > 0 ) {
				$args['cat'] = intval( $category );
			}

			$q = new WP_Query( $args );


			$layout_classes = '';
			if( $item_layout == 'small_image' ) {
				$layout_classes = 'widget_posts_list_alt';
			}

			if( $q->have_posts() ) {

				echo $before_widget;

				if ( ! empty( $title ) ) {
					echo $before_title . $title . $after_title;
				}

				?>
				<ul class="widget_posts_list <?php echo esc_attr( $layout_classes ); ?>">
					<?php while( $q->have_posts() ): $q->the_post(); ?>
						<li>
							<?php get_template_part( 'content', 'entry-widget' ); ?>
						</li>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ul>
				<?php

				echo $after_widget;
			}

		} // widget

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']       = sanitize_text_field( $new_instance['title'] );
			$instance['category']    = olsen_light_sanitize_intval_or_empty( $new_instance['category'] );
			$instance['random']      = olsen_light_sanitize_checkbox( $new_instance['random'] );
			$instance['count']       = intval( $new_instance['count'] ) > 0 ? intval( $new_instance['count'] ) : 1;
			$instance['item_layout'] = in_array( $new_instance['item_layout'], array( 'big_image', 'small_image' ) ) ? $new_instance['item_layout'] : 'big_image';

			return $instance;
		} // save

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
				'title'       => '',
				'category'    => '',
				'random'      => '',
				'count'       => 3,
				'item_layout' => 'big_image',
			) );

			$title       = $instance['title'];
			$category    = $instance['category'];
			$random      = $instance['random'];
			$count       = $instance['count'];
			$item_layout = $instance['item_layout'];

			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'olsen-light' ); ?></label><input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat"/></p>

			<p><label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Category to display the latest posts from (optional):', 'olsen-light' ); ?></label>
			<?php wp_dropdown_categories( array(
				'taxonomy'          => 'category',
				'show_option_all'   => '',
				'show_option_none'  => ' ',
				'option_none_value' => '',
				'show_count'        => 1,
				'echo'              => 1,
				'selected'          => $category,
				'hierarchical'      => 1,
				'name'              => $this->get_field_name( 'category' ),
				'id'                => $this->get_field_id( 'category' ),
				'class'             => 'postform widefat',
			) ); ?>

			<p><label for="<?php echo $this->get_field_id('random'); ?>"><input type="checkbox" name="<?php echo $this->get_field_name('random'); ?>" id="<?php echo $this->get_field_id('random'); ?>" value="1" <?php checked($random, 1);?> /><?php esc_html_e('Show random posts.', 'olsen-light'); ?></label></p>

			<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php esc_html_e('Number of posts to show:', 'olsen-light'); ?></label><input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" min="1" step="1" value="<?php echo esc_attr($count); ?>" class="widefat" /></p>

			<p>
				<label for="<?php echo $this->get_field_id( 'item_layout' ); ?>"><?php esc_html_e( 'Item Layout:', 'olsen-light' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'item_layout' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'item_layout' ); ?>">
					<option value="big_image" <?php selected( 'big_image', $item_layout ); ?>><?php _ex( 'Vertical (big image)', 'item layout', 'olsen-light' ); ?></option>
					<option value="small_image" <?php selected( 'small_image', $item_layout ); ?>><?php _ex( 'Horizontal (small image)', 'item layout', 'olsen-light' ); ?></option>
				</select>
			</p>
			<?php

		} // form


	} // class

	register_widget( 'CI_Widget_Latest_Posts' );

endif;