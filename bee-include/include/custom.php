<?php
/*
 * WordPress Custom API Data Hooks
 */
 
if( !defined( 'ABSPATH' ) ) exit;


if( wp_miniprogram_option("we_submit") ) {
	add_action('publish_post','we_miniprogram_posts_submit_pages',10,1);
	add_filter( 'bulk_post_updated_messages', 'pum_bulk_messages', 10, 2 );
	add_action('publish_to_publish',function () {
		remove_action('publish_post','we_miniprogram_posts_submit_pages',10,1);
	},11,1);
}
function we_miniprogram_posts_submit_pages( $post_id ) {
	$submit = array( );
	$submit['wechat'] = apply_filters( 'mp_we_submit_pages', $post_id );
	return $submit;
}