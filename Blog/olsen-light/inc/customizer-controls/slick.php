<?php
/**
 * Customize Slick Slider Control class.
 *
 * @see WP_Customize_Control
 */
class Olsen_Light_Customize_Slick_Control extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'slick';

	/**
	 * Taxonomy for category dropdown.
	 *
	 * @access public
	 * @var string
	 */
	protected $options = false;

	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		$this->options = $options;

		if ( ! isset( $args['settings'] ) ) {
			$manager->add_setting( $id . '_show', array(
				'default'           => 1,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );
			$manager->add_setting( $id . '_term', array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			) );
			$manager->add_setting( $id . '_postids', array(
				'default'           => '',
				'sanitize_callback' => array( $this, 'sanitize_post_ids' ),
			) );
			$manager->add_setting( $id . '_limit', array(
				'default'           => 5,
				'sanitize_callback' => array( $this, 'sanitize_positive_or_minus_one' ),
			) );
			$manager->add_setting( $id . '_show_recent', array(
				'default'           => 0,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );
			$manager->add_setting( $id . '_autoplay', array(
				'default'           => 1,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );
			$manager->add_setting( $id . '_autoplaySpeed', array(
				'default'           => 3000,
				'sanitize_callback' => 'absint',
			) );
			$manager->add_setting( $id . '_fade', array(
				'default'           => 1,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );
			$manager->add_setting( $id . '_hide_content', array(
				'default'           => 0,
				'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
			) );
			$this->settings = array(
				'show'          => $id . '_show',
				'term'          => $id . '_term',
				'postids'       => $id . '_postids',
				'show_recent'   => $id . '_show_recent',
				'limit'         => $id . '_limit',
				'autoplay'      => $id . '_autoplay',
				'autoplaySpeed' => $id . '_autoplaySpeed',
				'fade'          => $id . '_fade',
				'hide_content'  => $id . '_hide_content',
			);
		}
		parent::__construct( $manager, $id, $args );
	}

	protected function render_content() {
		if ( ! empty( $this->label ) ) :
			?><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><?php
		endif;

		if ( ! empty( $this->description ) ) :
			?><span class="description customize-control-description"><?php echo $this->description; ?></span><?php
		endif;

		?>
		<ul>
			<li>
				<label>
					<input type="checkbox" value="1" <?php $this->link( 'show' ); ?> <?php checked( $this->value( 'show' ), 1 ); ?> />
					<?php esc_html_e( 'Show slider.', 'olsen-light' ); ?>
				</label>
			</li>

			<li>
				<label>
					<input type="checkbox" value="1" <?php $this->link( 'autoplay' ); ?> <?php checked( $this->value( 'autoplay' ), 1 ); ?> />
					<?php esc_html_e( 'Auto slide.', 'olsen-light' ); ?>
				</label>
			</li>

			<li>
				<?php
					$options = wp_parse_args( $this->options, array(
						'taxonomy'          => 'category',
						'show_option_none'  => ' ',
						'selected'          => $this->value( 'term' ),
						'show_option_all'   => '',
						'orderby'           => 'id',
						'order'             => 'ASC',
						'show_count'        => 1,
						'hide_empty'        => 1,
						'child_of'          => 0,
						'exclude'           => '',
						'hierarchical'      => 1,
						'depth'             => 0,
						'tab_index'         => 0,
						'hide_if_empty'     => false,
						'option_none_value' => 0,
						'value_field'       => 'term_id',
					) );
					$options['echo'] = false;

					$dropdown = wp_dropdown_categories( $options );
					$dropdown = str_replace( '<select', '<select ' . $this->get_link( 'term' ), $dropdown );
					$dropdown = str_replace( "name='cat' id='cat' class='postform'", '', $dropdown );
					?><label><span class="customize-control-title"><?php esc_html_e( 'Source category', 'olsen-light' ); ?></span></label><?php
					echo $dropdown;
				?>
			</li>

			<li>
				<label>
					<span class="customize-control-title"><?php esc_html_e( 'Post IDs', 'olsen-light' ); ?></span>
					<span class="description customize-control-description"><?php _e( 'You can optionally provide a comma separated list of post IDs. The specific posts will be shown, overriding the source category above. The limit number is still honoured however.', 'olsen-light' ); ?></span>
					<input type="text" value="<?php echo esc_attr( $this->value( 'postids' ) ); ?>" <?php $this->link( 'postids' ); ?> />
				</label>
			</li>

			<li>
				<label>
					<span class="description customize-control-description"><?php esc_html_e( 'Check the box below to display your recent posts from all categories. The limit number is still in place and can be modified below.', 'olsen-light' ); ?></span>
					<input type="checkbox" value="0" <?php $this->link( 'show_recent' ); ?> <?php checked( $this->value( 'show_recent' ), 1 ); ?> />
					<?php esc_html_e( 'Show Recent Posts.', 'olsen-light' ); ?>
				</label>
			</li>

			<li>
				<label>
					<span class="customize-control-title"><?php esc_html_e( 'Limit posts:', 'olsen-light' ); ?></span>
					<input type="number" min="-1" step="1" value="<?php echo esc_attr( $this->value( 'limit' ) ); ?>" <?php $this->link( 'limit' ); ?> />
				</label>
			</li>

			<li>
				<label>
					<span class="customize-control-title"><?php esc_html_e( 'Slide change effect:', 'olsen-light' ); ?></span>
					<select <?php $this->link( 'fade' ); ?>>
						<option value="" <?php selected( $this->value( 'fade' ), '' ); ?>><?php echo esc_html_x( 'Slide', 'slick slider slide effect', 'olsen-light' ); ?></option>
						<option value="1" <?php selected( $this->value( 'fade' ), 1 ); ?>><?php echo esc_html_x( 'Fade', 'slick slider slide effect', 'olsen-light' ); ?></option>
					</select>
				</label>
			</li>

			<li>
				<label>
					<span class="customize-control-title"><?php esc_html_e( 'Pause between slides (in milliseconds):', 'olsen-light' ); ?></span>
					<input type="number" min="100" step="100" value="<?php echo esc_attr( $this->value( 'autoplaySpeed' ) ); ?>" <?php $this->link( 'autoplaySpeed' ); ?> />
				</label>
			</li>

			<li>
				<label>
					<input type="checkbox" value="0" <?php $this->link( 'hide_content' ); ?> <?php checked( $this->value( 'hide_content' ), 1 ); ?> />
					<?php esc_html_e( 'Hide post content box', 'olsen-light' ); ?>
				</label>
			</li>
		</ul>
		<?php

	}

	public static function sanitize_post_ids( $input ) {
		$input = explode( ',', $input );
		if( $input === false ) {
			return '';
		}
		$input = array_map( 'trim', $input );
		$input = array_map( 'absint', $input );
		$input = implode( ',', $input );

		return $input;
	}

	public static function sanitize_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		}

		return '';
	}

	public static function sanitize_positive_or_minus_one( $input ) {
		if ( intval( $input ) > 0 ) {
			return intval( $input );
		}

		return - 1;
	}

}
