<?php
function cptui_register_my_cpts_beebee_face() {

	/**
	 * Post Type: 挂件.
	 */

	$labels = [
		"name" => __( "挂件", "appbeebee" ),
		"singular_name" => __( "挂件", "appbeebee" ),
	];

	$args = [
		"label" => __( "挂件", "appbeebee" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "beebee_face", "with_front" => true ],
		"query_var" => true,
		"menu_position" => 3,
		"menu_icon" => "dashicons-universal-access-alt",
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "comments" ],
	];

	register_post_type( "beebee_face", $args );
}

add_action( 'init', 'cptui_register_my_cpts_beebee_face' );
