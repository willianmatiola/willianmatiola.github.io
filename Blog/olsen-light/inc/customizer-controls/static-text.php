<?php
/**
 * Customize Text Control class.
 *
 * @see WP_Customize_Control
 */
class Olsen_Light_Customize_Static_Text_Control extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'static-text';

	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	protected function render_content() {
		if ( ! empty( $this->label ) ) :
			?><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><?php
		endif;

		if ( ! empty( $this->description ) ) :
			?><div class="description customize-control-description"><?php

			if( is_array( $this->description ) ) {
				echo '<p>' . implode( '</p><p>', $this->description ) . '</p>';
			} else {
				echo $this->description;
			}

			?></div><?php
		endif;

	}

}
