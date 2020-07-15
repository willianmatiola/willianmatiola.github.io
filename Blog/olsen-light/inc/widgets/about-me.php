<?php
if ( ! class_exists( 'CI_Widget_About' ) ):
	class CI_Widget_About extends WP_Widget {

		function __construct() {
			$widget_ops  = array( 'description' => esc_html__( 'Provide information for the blog author, accompanied by a picture.', 'olsen-light' ) );
			$control_ops = array();
			parent::__construct( 'ci-about', $name = esc_html__( 'Theme - About Me', 'olsen-light' ), $widget_ops, $control_ops );
		}

		function widget( $args, $instance ) {
			extract( $args );
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

			$text            = $instance['text'];
			$image           = $instance['image'];
			$round           = $instance['round'];
			$invert_color    = $instance['invert_color'];
			$greeting_text   = $instance['greeting_text'];
			$signature_text  = $instance['signature_text'];
			$signature_image = $instance['signature_image'];

			$invert_class  = $invert_color ? 'widget-attention' : '';
			$before_widget = str_replace( 'class="', 'class="' . $invert_class . ' ', $before_widget );

			echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			echo '<div class="widget_about group">';

			if ( $image ) {
				$attachment = wp_prepare_attachment_for_js( $image );

				echo sprintf( '<p class="widget_about_avatar"><img src="%s" class="%s" alt="%s" /></p>',
					esc_url( olsen_light_get_image_src( $image, 'olsen_light_square' ) ),
					esc_attr( $round == 1 ? 'img-round' : '' ),
					esc_attr( $attachment['alt'] )
				);
			}

			echo wpautop( do_shortcode( wp_kses_post( $text ) ) );

			if ( ! empty( $greeting_text ) || ! empty( $signature_text ) || ! empty( $signature_image ) ) {
				?>
				<p class="widget_about_sig">
					<?php echo esc_html( $greeting_text ); ?>
					<?php if ( ! empty( $signature_image ) ): ?>
						<?php echo wp_get_attachment_image( $signature_image, 'post-thumbnail' ); ?>
					<?php elseif ( ! empty( $signature_text ) ): ?>
						<span><?php echo esc_html( $signature_text ); ?></span>
					<?php endif; ?>
				</p>
				<?php
			}

			echo '</div>';

			echo $after_widget;
		} // widget

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']           = sanitize_text_field( $new_instance['title'] );
			$instance['image']           = intval( $new_instance['image'] );
			$instance['round']           = olsen_light_sanitize_checkbox_ref( $new_instance['round'] );
			$instance['invert_color']    = olsen_light_sanitize_checkbox_ref( $new_instance['invert_color'] );
			$instance['text']            = wp_kses_post( $new_instance['text'] );
			$instance['greeting_text']   = sanitize_text_field( $new_instance['greeting_text'] );
			$instance['signature_text']  = sanitize_text_field( $new_instance['signature_text'] );
			$instance['signature_image'] = intval( $new_instance['signature_image'] );

			return $instance;
		}

		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array(
				'title'           => '',
				'image'           => '',
				'round'           => 1,
				'invert_color'    => '',
				'text'            => '',
				'greeting_text'   => '',
				'signature_text'  => '',
				'signature_image' => '',
			) );

			$title           = $instance['title'];
			$image           = $instance['image'];
			$round           = $instance['round'];
			$invert_color    = $instance['invert_color'];
			$text            = $instance['text'];
			$greeting_text   = $instance['greeting_text'];
			$signature_text  = $instance['signature_text'];
			$signature_image = $instance['signature_image'];

			?>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'olsen-light' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" /></p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>"><?php esc_html_e( 'Author Image:', 'olsen-light' ); ?></label>
				<div class="ci-upload-preview">
					<div class="upload-preview">
						<?php if ( ! empty( $image ) ): ?>
							<?php
								$image_url = olsen_light_get_image_src( $image, 'ci_featgal_small_thumb' );
								echo sprintf( '<img src="%s" /><a href="#" class="close media-modal-icon" title="%s"></a>',
									$image_url,
									esc_attr__( 'Remove image', 'olsen-light' )
								);
							?>
						<?php endif; ?>
					</div>
					<input type="hidden" class="ci-uploaded-id" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php echo esc_attr( $image ); ?>" />
					<input id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" type="button" class="button ci-media-button" value="<?php esc_attr_e( 'Select Image', 'olsen-light' ); ?>" />
				</div>
			</p>

			<p><label for="<?php echo $this->get_field_id( 'round' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'round' ); ?>" id="<?php echo $this->get_field_id( 'round' ); ?>" value="1" <?php checked( $round, 1 ); ?> /><?php esc_html_e( 'Show round image. For this to work, you need a square image. (200&times;200px or higher).', 'olsen-light' ); ?></label></p>
			<p><label for="<?php echo $this->get_field_id( 'invert_color' ); ?>"><input type="checkbox" name="<?php echo $this->get_field_name( 'invert_color' ); ?>" id="<?php echo $this->get_field_id( 'invert_color' ); ?>" value="1" <?php checked( $invert_color, 1 ); ?> /><?php esc_html_e( 'Invert widget color scheme.', 'olsen-light' ); ?></label></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'About text:', 'olsen-light' ); ?></label><textarea rows="10" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" class="widefat"><?php echo esc_textarea( $text ); ?></textarea></p>

			<p><label for="<?php echo esc_attr( $this->get_field_id( 'greeting_text' ) ); ?>"><?php esc_html_e( 'Greeting (sign off) text:', 'olsen-light' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'greeting_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'greeting_text' ) ); ?>" type="text" value="<?php echo esc_attr( $greeting_text ); ?>" class="widefat" /></p>
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'signature_text' ) ); ?>"><?php esc_html_e( 'Signature text:', 'olsen-light' ); ?></label><input id="<?php echo esc_attr( $this->get_field_id( 'signature_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'signature_text' ) ); ?>" type="text" value="<?php echo esc_attr( $signature_text ); ?>" class="widefat" /></p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'signature_image' ) ); ?>"><?php esc_html_e( 'Signature Image:', 'olsen-light' ); ?></label>
				<div class="ci-upload-preview">
					<div class="upload-preview">
						<?php if ( ! empty( $signature_image ) ): ?>
							<?php
								$image_url = olsen_light_get_image_src( $signature_image, 'ci_featgal_small_thumb' );
								echo sprintf( '<img src="%s" /><a href="#" class="close media-modal-icon" title="%s"></a>',
									$image_url,
									esc_attr__( 'Remove image', 'olsen-light' )
								);
							?>
						<?php endif; ?>
					</div>
					<input type="hidden" class="ci-uploaded-id" name="<?php echo esc_attr( $this->get_field_name( 'signature_image' ) ); ?>" value="<?php echo esc_attr( $signature_image ); ?>" />
					<input id="<?php echo esc_attr( $this->get_field_id( 'signature_image' ) ); ?>" type="button" class="button ci-media-button" value="<?php esc_attr_e( 'Select Image', 'olsen-light' ); ?>" />
				</div>
			</p>

			<?php
		} // form

	} // class

	register_widget( 'CI_Widget_About' );

endif;