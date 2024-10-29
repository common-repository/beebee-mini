<?php

if ( !defined( 'ABSPATH' ) ) exit;

function setting_sanitize_bee_enum( $input, $option ) {
	$output = '';
	if ( array_key_exists( $input, $option['options'] ) ) {
		$output = $input;
	}
	return $output;
}
add_filter( 'setting_sanitize_gallery', 'setting_sanitize_bee_enum', 10, 2 );
