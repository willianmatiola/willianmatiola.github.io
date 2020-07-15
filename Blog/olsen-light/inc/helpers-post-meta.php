<?php
add_action( 'after_setup_theme', 'olsen_light_setup_helpers_post_meta' );
function olsen_light_setup_helpers_post_meta() {
	add_image_size( 'ci_featgal_small_thumb', 100, 100, true );

	add_action( 'wp_ajax_olsen_light_featgal_AJAXPreview', 'olsen_light_featgal_AJAXPreview' );
}

add_action( 'admin_enqueue_scripts', 'olsen_light_admin_register_post_meta_scripts' );
function olsen_light_admin_register_post_meta_scripts( $hook ) {
	$theme = wp_get_theme();

	wp_register_style( 'olsen-light-post-meta', get_template_directory_uri() . '/inc/css/post-meta.css', array(), $theme->get( 'Version' ) );
	wp_register_script( 'olsen-light-post-meta', get_template_directory_uri() . '/inc/js/post-meta.js', array(
		'media-editor',
		'jquery',
		'jquery-ui-sortable'
	), $theme->get( 'Version' ) );

	$settings = array(
		'ajaxurl'             => admin_url( 'admin-ajax.php' ),
		'tSelectFile'         => esc_html__( 'Select file', 'olsen-light' ),
		'tSelectFiles'        => esc_html__( 'Select files', 'olsen-light' ),
		'tUseThisFile'        => esc_html__( 'Use this file', 'olsen-light' ),
		'tUseTheseFiles'      => esc_html__( 'Use these files', 'olsen-light' ),
		'tUpdateGallery'      => esc_html__( 'Update gallery', 'olsen-light' ),
		'tLoading'            => esc_html__( 'Loading...', 'olsen-light' ),
		'tPreviewUnavailable' => esc_html__( 'Gallery preview not available.', 'olsen-light' ),
		'tRemoveImage'        => esc_html__( 'Remove image', 'olsen-light' ),
		'tRemoveFromGallery'  => esc_html__( 'Remove from gallery', 'olsen-light' ),
	);
	wp_localize_script( 'olsen-light-post-meta', 'olsen_light_PostMeta', $settings );
}

//
// Various wrapping functions for easier custom fields creation.
//

function olsen_light_prepare_metabox( $post_type ) {
	wp_nonce_field( basename( __FILE__ ), $post_type . '_nonce' );
}

function olsen_light_can_save_meta( $post_type ) {
	global $post;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}

	if ( isset( $_POST['post_view'] ) and $_POST['post_view'] == 'list' ) {
		return false;
	}

	if ( ! isset( $_POST['post_type'] ) or $_POST['post_type'] != $post_type ) {
		return false;
	}

	if ( ! isset( $_POST[ $post_type . '_nonce' ] ) or ! wp_verify_nonce( $_POST[ $post_type . '_nonce' ], basename( __FILE__ ) ) ) {
		return false;
	}

	$post_type_obj = get_post_type_object( $post->post_type );
	if ( ! current_user_can( $post_type_obj->cap->edit_post, $post->ID ) ) {
		return false;
	}

	return true;
}

function olsen_light_metabox_gallery( $gid = 1 ) {
	global $post;
	$post_id = $post->ID;

	olsen_light_featgal_print_meta_html( $post_id, $gid );
}

function olsen_light_metabox_gallery_save( $POST, $gid = 1 ) {
	global $post;
	$post_id = $post->ID;

	olsen_light_featgal_update_meta( $post_id, $POST, $gid );
}

function olsen_light_metabox_input( $fieldname, $label, $params = array() ) {
	global $post;

	$defaults = array(
		'label_class' => '',
		'input_class' => 'widefat',
		'input_type'  => 'text',
		'esc_func'    => 'esc_attr',
		'before'      => '<p class="ci-field-group ci-field-input">',
		'after'       => '</p>',
		'default'     => ''
	);
	$params = wp_parse_args( $params, $defaults );

	$custom_keys = get_post_custom_keys( $post->ID );

	if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys ) ) {
		$value = get_post_meta( $post->ID, $fieldname, true );
		$value = call_user_func( $params['esc_func'], $value );
	} else {
		$value = $params['default'];
	}

	echo $params['before'];

	if ( ! empty( $label ) ) {
		?><label for="<?php echo esc_attr( $fieldname ); ?>" class="<?php echo esc_attr( $params['label_class'] ); ?>"><?php echo $label; ?></label><?php
	}

	?><input id="<?php echo esc_attr( $fieldname ); ?>" type="<?php echo esc_attr( $params['input_type'] ); ?>" name="<?php echo esc_attr( $fieldname ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo esc_attr( $params['input_class'] ); ?>" /><?php

	echo $params['after'];

}

function olsen_light_metabox_textarea( $fieldname, $label, $params = array() ) {
	global $post;

	$defaults = array(
		'label_class' => '',
		'input_class' => 'widefat',
		'esc_func'    => 'esc_textarea',
		'before'      => '<p class="ci-field-group ci-field-textarea">',
		'after'       => '</p>',
		'default'     => ''
	);
	$params = wp_parse_args( $params, $defaults );

	$custom_keys = get_post_custom_keys( $post->ID );

	if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys ) ) {
		$value = get_post_meta( $post->ID, $fieldname, true );
		$value = call_user_func( $params['esc_func'], $value );
	} else {
		$value = $params['default'];
	}

	echo $params['before'];

	if ( ! empty( $label ) ) {
		?><label for="<?php echo esc_attr( $fieldname ); ?>" class="<?php echo esc_attr( $params['label_class'] ); ?>"><?php echo $label; ?></label><?php
	}

	?><textarea id="<?php echo esc_attr( $fieldname ); ?>" name="<?php echo esc_attr( $fieldname ); ?>" class="<?php echo esc_attr( $params['input_class'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea><?php

	echo $params['after'];

}

function olsen_light_metabox_dropdown( $fieldname, $options, $label, $params = array() ) {
	global $post;
	$options = (array) $options;

	$defaults = array(
		'before'  => '<p class="ci-field-group ci-field-dropdown">',
		'after'   => '</p>',
		'default' => ''
	);
	$params = wp_parse_args( $params, $defaults );

	$custom_keys = get_post_custom_keys( $post->ID );

	if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys ) ) {
		$value = get_post_meta( $post->ID, $fieldname, true );
	} else {
		$value = $params['default'];
	}

	echo $params['before'];

	if ( ! empty( $label ) ) {
		?><label for="<?php echo esc_attr( $fieldname ); ?>"><?php echo $label; ?></label><?php
	}

	?>
		<select id="<?php echo esc_attr( $fieldname ); ?>" name="<?php echo esc_attr( $fieldname ); ?>">
			<?php foreach ( $options as $opt_val => $opt_label ): ?>
				<option value="<?php echo esc_attr( $opt_val ); ?>" <?php selected( $value, $opt_val ); ?>><?php echo esc_html( $opt_label ); ?></option>
			<?php endforeach; ?>
		</select>
	<?php

	echo $params['after'];
}

// $fieldname is the actual name="" attribute common to all radios in the group.
// $optionname is the id of the radio, so that the label can be associated with it.
function olsen_light_metabox_radio( $fieldname, $optionname, $optionval, $label, $params = array() ) {
	global $post;

	$defaults = array(
		'before'  => '<p class="ci-field-group ci-field-radio">',
		'after'   => '</p>',
		'default' => ''
	);
	$params = wp_parse_args( $params, $defaults );

	$custom_keys = get_post_custom_keys( $post->ID );

	if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys ) ) {
		$value = get_post_meta( $post->ID, $fieldname, true );
	} else {
		$value = $params['default'];
	}

	echo $params['before'];
	?>
		<input type="radio" class="radio" id="<?php echo esc_attr( $optionname ); ?>" name="<?php echo esc_attr( $fieldname ); ?>" value="<?php echo esc_attr( $optionval ); ?>" <?php checked( $value, $optionval ); ?> />
		<label for="<?php echo esc_attr( $optionname ); ?>" class="radio"><?php echo $label; ?></label>
	<?php
	echo $params['after'];
}

function olsen_light_metabox_checkbox( $fieldname, $value, $label, $params = array() ) {
	global $post;

	$defaults = array(
		'before'  => '<p class="ci-field-group ci-field-checkbox">',
		'after'   => '</p>',
		'default' => ''
	);
	$params = wp_parse_args( $params, $defaults );

	$custom_keys = get_post_custom_keys( $post->ID );

	if ( is_array( $custom_keys ) && in_array( $fieldname, $custom_keys ) ) {
		$checked = get_post_meta( $post->ID, $fieldname, true );
	} else {
		$checked = $params['default'];
	}

	echo $params['before'];
	?>
		<input type="checkbox" id="<?php echo esc_attr( $fieldname ); ?>" class="check" name="<?php echo esc_attr( $fieldname ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $checked, $value ); ?> />
		<label for="<?php echo esc_attr( $fieldname ); ?>"><?php echo $label; ?></label>
	<?php
	echo $params['after'];
}

function olsen_light_metabox_open_tab( $title ) {
	?>
	<div class="ci-cf-section">
		<?php if ( ! empty( $title ) ): ?>
			<h3 class="ci-cf-title"><?php echo esc_html( $title ); ?></h3>
		<?php endif; ?>
		<div class="ci-cf-inside">
	<?php
}

function olsen_light_metabox_close_tab() {
	?>
		</div>
	</div>
	<?php
}

function olsen_light_metabox_open_collapsible( $title ) {
	?>
	<div class="postbox" style="margin-top:20px">
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'olsen-light' ); ?>"><br></div>
		<h3 class="hndle"><?php echo esc_html( $title ); ?></h3>
		<div class="inside">
	<?php
}

function olsen_light_metabox_close_collapsible() {
	?>
		</div>
	</div>
	<?php
}

function olsen_light_metabox_guide( $strings, $params = array() ) {
	$defaults = array(
		'type'        => 'auto', // auto, p, ol, ul
		'before'      => '',
		'before_each' => '',
		'after'       => '',
		'after_each'  => '',
	);
	$params = wp_parse_args( $params, $defaults );

	if ( empty( $strings ) ) {
		return;
	}

	if ( $params['type'] == 'auto' ) {
		if ( is_array( $strings ) && count( $strings ) > 1 ) {
			$params['type'] = 'ol';
		} else {
			$params['type'] = 'p';
		}
	}

	if ( is_string( $strings ) ) {
		$strings = array( $strings );
	}

	if ( $params['type'] == 'p' ) {
		$params['before_each'] = '<p class="ci-cf-guide">';
		$params['after_each']  = '</p>';
	} elseif ( $params['type'] == 'ol' ) {
		$params['before']      = '<ol class="ci-cf-guide">';
		$params['before_each'] = '<li>';
		$params['after']       = '</ol>';
		$params['after_each']  = '</li>';
	} elseif ( $params['type'] == 'ul' ) {
		$params['before']      = '<ul class="ci-cf-guide">';
		$params['before_each'] = '<li>';
		$params['after']       = '</ul>';
		$params['after_each']  = '</li>';
	}

	echo $params['before'];
	foreach ( $strings as $string ) {
		echo $params['before_each'] . $string . $params['after_each'];
	}
	echo $params['after'];
}

function olsen_light_bind_metabox_to_page_template( $metabox_id, $template_file, $js_var ) {
	if ( is_string( $template_file ) && ( '' === $template_file || 'default' === $template_file ) ) {
		$template_file = array( '', 'default' );
	} elseif ( is_array( $template_file ) && ( in_array( '', $template_file, true ) || in_array( 'default', $template_file, true ) ) ) {
		$template_file = array_unique( array_merge( $template_file, array( '', 'default' ) ) );
	}

	if ( is_array( $template_file ) ) {
		$template_file = implode( "', '", $template_file );
	}

	$css = sprintf( '<style type="text/css">%s { display: none; }</style>', '#' . $metabox_id );

	$js = <<<ENDJS
    (function($) {
		$('head').append('{$css}');

	    $(window).load( function() {
			var template_box = $( '#page_template, .editor-page-attributes__template select' );
			var {$js_var} = $( '#{$metabox_id}' );
			if ( template_box.length > 0 ) {
				var {$js_var}_template = [ '{$template_file}' ];
		
				if ( $.inArray( template_box.val(), {$js_var}_template ) > -1 ) {
					{$js_var}.show();
				}
		
				template_box.change( function() {
					if ( $.inArray( template_box.val(), {$js_var}_template ) > -1 ) {
						{$js_var}.show();
						if ( typeof google === 'object' && typeof google.maps === 'object' ) {
							if ( {$js_var}.find( '.gllpLatlonPicker' ).length > 0 ) {
								google.maps.event.trigger( window, 'resize', {} );
							}
						}
					} else {
						{$js_var}.hide();
					}
				} );
			} else {
				{$js_var}.hide();
			}
	    } );
    })(jQuery);
ENDJS;

	olsen_light_add_inline_js( $js, sanitize_key( 'metabox_template_' . $metabox_id . '_' . $template_file ) );
}

function olsen_light_bind_metabox_to_post_format( $metabox_id, $post_format, $js_var ) {
	if ( is_array( $post_format ) ) {
		$post_format = implode( "', '", $post_format );
	}

	$css = sprintf( '<style type="text/css">%s { display: none; }</style>', '#' . $metabox_id );

	$js = <<<ENDJS
	(function($) {

		$('head').append('{$css}');

		$(window).load( function() {
			var formats_box = $('input[type=radio][name=post_format], .editor-post-format select');
			if ( formats_box.length > 0 ) {
				var {$js_var} = $('#{$metabox_id}');
				var {$js_var}_format = ['{$post_format}'];
		
				if ( $('body').hasClass('block-editor-page') ) {
					var post_format_selected = $( '.editor-post-format select' ).find(':selected').val();
				} else {
					var post_format_selected = $('#post-formats-select input.post-format:checked').val();
				}
			
				if( $.inArray( post_format_selected, {$js_var}_format ) > -1 ) {
					{$js_var}.show();
				}
		
				formats_box.change( function() {
					if( $.inArray( $(this).val(), {$js_var}_format ) > -1 ) {
						{$js_var}.show();
					} else {
						{$js_var}.hide();
					}
				} );
			}
		});
	})(jQuery);
ENDJS;

	olsen_light_add_inline_js( $js, sanitize_key( 'metabox_format_' . $metabox_id . '_' . $post_format ) );
}





/**
 * Creates the necessary gallery HTML code for use in metaboxes.
 *
 * @param int|bool $post_id The post ID where the gallery's default values should be loaded from. If empty, the global $post object's ID is used.
 * @param int $gid The gallery ID (instance). Only needed when a post has more than one galleries. Defaults to 1.
 * @return void
 */
function olsen_light_featgal_print_meta_html( $post_id = false, $gid = 1 ) {
	if ( $post_id == false ) {
		global $post;
		$post_id = $post->ID;
	}

	$gid = absint( $gid );
	if ( $gid < 1 ) {
		$gid = 1;
	}

	$ids  = get_post_meta( $post_id, 'ci_featured_gallery_' . $gid, true );
	$rand = get_post_meta( $post_id, 'ci_featured_gallery_rand_' . $gid, true );

	$custom_keys = get_post_custom_keys( $post_id );

	?>
	<div class="ci-media-manager-gallery">
		<input type="button" class="ci-upload-to-gallery button" value="<?php esc_attr_e( 'Add Images', 'olsen-light' ); ?>"/>
		<input type="hidden" class="ci-upload-to-gallery-ids" name="ci_featured_gallery_<?php echo esc_attr( $gid ); ?>" value="<?php echo esc_attr( $ids ); ?>"/>
		<p><label class="ci-upload-to-gallery-random"><input type="checkbox" name="ci_featured_gallery_rand_<?php echo esc_attr( $gid ); ?>" value="rand" <?php checked( $rand, 'rand' ); ?> /> <?php esc_html_e( 'Randomize order', 'olsen-light' ); ?></label></p>
		<div class="ci-upload-to-gallery-preview group">
			<?php
				$images = olsen_light_featgal_get_images( $ids );
				if ( $images !== false and is_array( $images ) ) {
					foreach ( $images as $image ) {
						?>
						<div class="thumb">
							<img src="<?php echo esc_url( $image['url'] ); ?>" data-img-id="<?php echo esc_attr( $image['id'] ); ?>">
							<a href="#" class="close media-modal-icon" title="<?php esc_attr_e( 'Remove from gallery', 'olsen-light' ); ?>"></a>
						</div>
						<?php
					}
				}
			?>
			<p class="ci-upload-to-gallery-preview-text"><?php esc_html_e( 'Your gallery images will appear here', 'olsen-light' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Looks for gallery custom fields in an array, sanitizes and stores them in post meta.
 * Uses substr() so return values are the same.
 *
 * @param int $post_id The post ID where the gallery's custom fields should be stored.
 * @param array $POST An array that contains gallery custom field values. Usually $_POST should be passed.
 * @param int $gid The gallery ID (instance). Only needed when a post has more than one galleries. Defaults to 1.
 * @return void|bool Nothing on success, boolean false on invalid parameters.
 */
function olsen_light_featgal_update_meta( $post_id, $POST, $gid = 1 ) {
	if ( absint( $post_id ) < 1 ) {
		return false;
	}

	if ( ! is_array( $POST ) ) {
		return false;
	}

	$gid = absint( $gid );
	if ( $gid < 1 ) {
		$gid = 1;
	}

	$f_ids  = 'ci_featured_gallery_' . $gid;
	$f_rand = 'ci_featured_gallery_rand_' . $gid;

	$ids         = array();
	$ids_string  = '';
	$rand_string = '';
	if ( ! empty( $POST[ $f_ids ] ) ) {
		$ids = explode( ',', $POST[ $f_ids ] );
		$ids = array_filter( $ids );

		if ( count( $ids ) > 0 ) {
			$ids        = array_map( 'intval', $ids );
			$ids        = array_map( 'abs', $ids );
			$ids_string = implode( ',', $ids );
		}
	}

	if ( ! empty( $POST[ $f_rand ] ) and $POST[ $f_rand ] == 'rand' ) {
		$rand_string = 'rand';
	}

	update_post_meta( $post_id, $f_ids, $ids_string );
	update_post_meta( $post_id, $f_rand, $rand_string );

}

function olsen_light_featgal_get_ids( $post_id = false, $gid = 1 ) {
	if ( $post_id == false ) {
		global $post;
		$post_id = $post->ID;
	} else {
		$post_id = absint( $post_id );
	}

	$gid = absint( $gid );
	if ( $gid < 1 ) {
		$gid = 1;
	}

	$ids  = get_post_meta( $post_id, 'ci_featured_gallery_' . $gid, true );
	$rand = get_post_meta( $post_id, 'ci_featured_gallery_rand_' . $gid, true );

	$ids = explode( ',', $ids );
	$ids = array_filter( $ids );

	if ( 'rand' == $rand ) {
		shuffle( $ids );
	}

	return $ids;
}

function olsen_light_featgal_get_attachments( $post_id = false, $gid = 1, $extra_args = array() ) {
	if ( $post_id == false ) {
		global $post;
		$post_id = $post->ID;
	} else {
		$post_id = absint( $post_id );
	}

	$gid = absint( $gid );
	if ( $gid < 1 ) {
		$gid = 1;
	}

	$ids  = get_post_meta( $post_id, 'ci_featured_gallery_' . $gid, true );
	$rand = get_post_meta( $post_id, 'ci_featured_gallery_rand_' . $gid, true );

	$ids = explode( ',', $ids );
	$ids = array_filter( $ids );

	$args = array(
		'post_type'        => 'attachment',
		'post_mime_type'   => 'image',
		'post_status'      => 'any',
		'posts_per_page'   => - 1,
		'suppress_filters' => true,
	);

	$custom_keys = get_post_custom_keys( $post_id );
	if( is_null( $custom_keys ) ) {
		$custom_keys = array();
	}

	if ( ! in_array( 'ci_featured_gallery_' . $gid, $custom_keys ) ) {
		$args['post_parent'] = $post_id;
		$args['order']       = 'ASC';
		$args['orderby']     = 'menu_order';
	} elseif ( count( $ids ) > 0 ) {
		$args['post__in'] = $ids;
		$args['orderby']  = 'post__in';

		if ( $rand == 'rand' ) {
			$args['orderby'] = 'rand';
		}
	} else {
		// Make sure we return an empty result set.
		$args['post__in'] = array( - 1 );
	}

	if ( is_array( $extra_args ) and count( $extra_args ) > 0 ) {
		$args = array_merge( $args, $extra_args );
	}

	return new WP_Query( $args );
}

/**
 * Reads $_POST["ids"] for a comma separated list of image attachment IDs, prints a JSON array of image URLs and exits.
 * Hooked to wp_ajax_olsen_light_featgal_AJAXPreview for AJAX updating of the galleries' previews.
 */
function olsen_light_featgal_AJAXPreview() {
	$ids  = $_POST['ids'];
	$urls = olsen_light_featgal_get_images( $ids );
	if ( $urls === false ) {
		echo 'FAIL';
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			wp_die();
		} else {
			die;
		}
	} else {
		wp_send_json( $urls );
	}
}

/**
 * Reads $csv for a comma separated list of image attachment IDs. Returns a php array of image URLs and IDs, or false.
 *
 * @param string $csv A comma separated list of image attachment IDs.
 * @return array|bool
 */
function olsen_light_featgal_get_images( $csv = false ) {
	$ids = explode(',', $csv);
	$ids = array_filter($ids);

	if ( count( $ids ) > 0 ) {
		$ids         = array_map( 'intval', $ids );
		$ids         = array_map( 'abs', $ids );
		$urls        = array();

		global $_wp_additional_image_sizes;

		$image_sizes = $_wp_additional_image_sizes;

		foreach ( $ids as $id ) {
			$thumb_file = olsen_light_get_image_src( $id, 'ci_featgal_small_thumb' );

			$file = parse_url( $thumb_file );
			$file = pathinfo( $file['path'] );
			$file = basename( $file['basename'], '.' . $file['extension'] );

			$size = $image_sizes['ci_featgal_small_thumb']['width'] . 'x' . $image_sizes['ci_featgal_small_thumb']['height'];
			if ( olsen_light_substr_right( $file, strlen( $size ) ) == $size ) {
				$file = $thumb_file;
			} else {
				$file = olsen_light_get_image_src( $id, 'thumbnail' );
			}

			$data = array(
				'id'  => $id,
				//'url' => ci_get_image_src($id, 'ci_featgal_small_thumb')
				'url' => $file
			);

			$urls[] = $data;
		}
		return $urls;
	} else {
		return false;
	}
}