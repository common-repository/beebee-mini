<?php
function cptui_register_my_cpts() {

	/**
	 * Post Type: 素材.
	 */

	$labels = [
		"name" => __( "素材", "appbeebee" ),
		"singular_name" => __( "素材", "appbeebee" ),
		"menu_name" => __( "素材", "appbeebee" ),
		"all_items" => __( "所有素材", "appbeebee" ),
		"add_new" => __( "添加新素材", "appbeebee" ),
		"add_new_item" => __( "添加新素材", "appbeebee" ),
		"edit_item" => __( "编辑素材", "appbeebee" ),
		"new_item" => __( "新素材", "appbeebee" ),
		"view_item" => __( "查看素材", "appbeebee" ),
		"view_items" => __( "查看素材", "appbeebee" ),
		"search_items" => __( "搜索素材", "appbeebee" ),
		"not_found" => __( "没有找到素材", "appbeebee" ),
		"featured_image" => __( "素材图", "appbeebee" ),
		"set_featured_image" => __( "设置素材图", "appbeebee" ),
		"remove_featured_image" => __( "移除素材图", "appbeebee" ),
		"use_featured_image" => __( "使用素材图", "appbeebee" ),
		"archives" => __( "素材存档", "appbeebee" ),
		"uploaded_to_this_item" => __( "更新素材", "appbeebee" ),
		"filter_items_list" => __( "筛选素材列表", "appbeebee" ),
		"items_list" => __( "素材列表", "appbeebee" ),
		"name_admin_bar" => __( "素材", "appbeebee" ),
	];

	$args = [
		"label" => __( "素材", "appbeebee" ),
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
		"rewrite" => [ "slug" => "beebee_library", "with_front" => true ],
		"query_var" => true,
		"menu_position" => 5,
		"menu_icon" => "dashicons-format-gallery",
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "comments", "author" ],
		"taxonomies" => [ "post_tag" ],
	];

	register_post_type( "beebee_library", $args );

	/**
	 * Post Type: 合辑.
	 */

	$labels = [
		"name" => __( "合辑", "appbeebee" ),
		"singular_name" => __( "合辑", "appbeebee" ),
		"menu_name" => __( "合辑", "appbeebee" ),
		"all_items" => __( "所有合辑", "appbeebee" ),
		"add_new" => __( "添加新合辑", "appbeebee" ),
		"add_new_item" => __( "添加新合辑", "appbeebee" ),
		"edit_item" => __( "编辑合辑", "appbeebee" ),
		"new_item" => __( "新合辑", "appbeebee" ),
		"view_item" => __( "查看合辑", "appbeebee" ),
		"view_items" => __( "查看合辑", "appbeebee" ),
		"search_items" => __( "搜索合辑", "appbeebee" ),
		"not_found" => __( "没有找到合辑", "appbeebee" ),
		"featured_image" => __( "合辑封面", "appbeebee" ),
		"set_featured_image" => __( "设置合辑封面", "appbeebee" ),
		"remove_featured_image" => __( "移除封面", "appbeebee" ),
		"use_featured_image" => __( "使用封面", "appbeebee" ),
		"archives" => __( "合辑存档", "appbeebee" ),
		"uploaded_to_this_item" => __( "更新合辑", "appbeebee" ),
		"filter_items_list" => __( "筛选合辑列表", "appbeebee" ),
		"items_list" => __( "合辑列表", "appbeebee" ),
	];

	$args = [
		"label" => __( "合辑", "appbeebee" ),
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
		"rewrite" => [ "slug" => "beebee_topic", "with_front" => true ],
		"query_var" => true,
		"menu_position" => 5,
		"menu_icon" => "dashicons-image-filter",
		"supports" => [ "title", "editor", "thumbnail", "excerpt", "comments", "author" ],
		"taxonomies" => [ "post_tag" ],
	];

	register_post_type( "beebee_topic", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );
