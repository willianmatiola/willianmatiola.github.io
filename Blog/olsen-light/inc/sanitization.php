<?php
/////////////////////////////////////////////////////////////////////////////////////////
//   Standard sanitization functions.
/////////////////////////////////////////////////////////////////////////////////////////
/**
 * Sanitizes a checkbox value.
 *
 * @param int|string|bool $input Input value to sanitize.
 * @return int|string Returns 1 if $input evaluates to 1, an empty string otherwise.
 */
function olsen_light_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	}

	return '';
}


/**
 * Sanitizes a checkbox value. Value is passed by reference.
 *
 * Useful when sanitizing form checkboxes. Since browsers don't send any data when a checkbox
 * is not checked, olsen_light_sanitize_checkbox() throws an error.
 * olsen_light_sanitize_checkbox_ref() however evaluates &$input as null so no errors are thrown.
 *
 * @param int|string|bool &$input Input value to sanitize.
 * @return int|string Returns 1 if $input evaluates to 1, an empty string otherwise.
 */
function olsen_light_sanitize_checkbox_ref( &$input ) {
	if ( $input == 1 ) {
		return 1;
	}

	return '';
}


/**
 * Sanitizes the pagination method option.
 *
 * @param string $option Value to sanitize. Either 'numbers' or 'text'.
 * @return string
 */
function olsen_light_sanitize_pagination_method( $option ) {
	if( in_array( $option, array( 'numbers', 'text' ) ) ) {
		return $option;
	}

	return 'numbers';
}


/**
 * Sanitizes integer input while differentiating zero from empty string.
 *
 * @param int|string $input Input value to sanitize.
 * @return int|string Integer value, 0, or an empty string otherwise.
 */
function olsen_light_sanitize_intval_or_empty( $input ) {
	if ( $input === false || $input === '' ) {
		return '';
	}

	if ( $input == 0 ) {
		return 0;
	}

	return intval( $input );
}


/**
 * Returns a sanitized hex color code.
 *
 * @param string $str The color string to be sanitized.
 * @param bool $return_hash Whether to return the color code prepended by a hash.
 * @param string $return_fail The value to return on failure.
 * @return string A valid hex color code on success, an empty string on failure.
 */
function olsen_light_sanitize_hex_color( $str, $return_hash = true, $return_fail = '' ) {
	if( $str === false || empty( $str ) || $str == 'false' ) {
		return $return_fail;
	}

	// Allow keywords and predefined colors
	if ( in_array( $str, array( 'transparent', 'initial', 'inherit', 'black', 'silver', 'gray', 'grey', 'white', 'maroon', 'red', 'purple', 'fuchsia', 'green', 'lime', 'olive', 'yellow', 'navy', 'blue', 'teal', 'aqua', 'orange', 'aliceblue', 'antiquewhite', 'aquamarine', 'azure', 'beige', 'bisque', 'blanchedalmond', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgrey', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'limegreen', 'linen', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'oldlace', 'olivedrab', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'whitesmoke', 'yellowgreen', 'rebeccapurple' ) ) ) {
		return $str;
	}

	// Include the hash if not there.
	// The regex below depends on in.
	if ( substr( $str, 0, 1 ) != '#' ) {
		$str = '#' . $str;
	}

	preg_match( '/(#)([0-9a-fA-F]{6})/', $str, $matches );

	if ( count( $matches ) == 3 ) {
		if ( $return_hash ) {
			return $matches[1] . $matches[2];
		} else {
			return $matches[2];
		}
	}

	return $return_fail;
}


/**
 * Return a list of allowed tags and attributes for a given context.
 *
 * @param string $context The context for which to retrieve tags.
 *                        Currently available contexts: guide
 * @return array List of allowed tags and their allowed attributes.
 */
function olsen_light_get_allowed_tags( $context = '' ) {
	$allowed = array(
		'a'       => array(
			'href'   => true,
			'title'  => true,
			'class'  => true,
			'target' => true,
			'rel'    => true,
		),
		'abbr'    => array( 'title' => true, ),
		'acronym' => array( 'title' => true, ),
		'b'       => array( 'class' => true, ),
		'br'      => array(),
		'code'    => array( 'class' => true, ),
		'em'      => array( 'class' => true, ),
		'i'       => array( 'class' => true, ),
		'img'     => array(
			'alt'    => true,
			'class'  => true,
			'src'    => true,
			'width'  => true,
			'height' => true,
		),
		'li'      => array( 'class' => true, ),
		'ol'      => array( 'class' => true, ),
		'p'       => array( 'class' => true, ),
		'pre'     => array( 'class' => true, ),
		'span'    => array( 'class' => true, ),
		'strong'  => array( 'class' => true, ),
		'ul'      => array( 'class' => true, ),
	);

	switch ( $context ) {
		case 'guide':
			unset( $allowed['p'] );
			break;
		default:
			break;
	}

	return apply_filters( 'olsen_light_get_allowed_tags', $allowed, $context );
}

/**
 * Sanitize user-provided CSS code, as recommended in https://make.wordpress.org/themes/2015/02/10/custom-css-boxes-in-themes/
 *
 * @param string $string The CSS code to sanitize.
 * @return string
 */
function olsen_light_sanitize_custom_css( $string ) {
	$string = wp_strip_all_tags( $string, false );

	return $string;
}
