<?php
function cptui_register_my_taxes() {

	/**
	 * Taxonomy: 音频分类.
	 */

	$labels = [
		"name" => __( "音频分类", "appbeebee" ),
		"singular_name" => __( "音频分类", "appbeebee" ),
	];

	$args = [
		"label" => __( "音频分类", "appbeebee" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'beebee_library_cats', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "beebee_library_cats",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
			];
	register_taxonomy( "beebee_library_cats", [ "beebee_library" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes' );
