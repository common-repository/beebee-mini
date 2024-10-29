<?php
function cptui_register_my_cpts() {

	/**
	 * Post Type: 音频.
	 */

	$labels = [
		"name" => __( "音频", "appbeebee" ),
		"singular_name" => __( "音频", "appbeebee" ),
		"menu_name" => __( "音频", "appbeebee" ),
		"all_items" => __( "所有音频", "appbeebee" ),
		"add_new" => __( "添加新音频", "appbeebee" ),
		"add_new_item" => __( "添加新音频", "appbeebee" ),
		"edit_item" => __( "编辑音频", "appbeebee" ),
		"new_item" => __( "新音频", "appbeebee" ),
		"view_item" => __( "查看音频", "appbeebee" ),
		"view_items" => __( "查看音频", "appbeebee" ),
		"search_items" => __( "搜索音频", "appbeebee" ),
		"not_found" => __( "没有找到音频", "appbeebee" ),
		"featured_image" => __( "音频封面", "appbeebee" ),
		"set_featured_image" => __( "设置音频封面", "appbeebee" ),
		"remove_featured_image" => __( "移除封面", "appbeebee" ),
		"use_featured_image" => __( "使用封面", "appbeebee" ),
		"archives" => __( "音频存档", "appbeebee" ),
		"uploaded_to_this_item" => __( "更新音频", "appbeebee" ),
		"filter_items_list" => __( "筛选音频列表", "appbeebee" ),
		"items_list" => __( "音频列表", "appbeebee" ),
		"name_admin_bar" => __( "音频", "appbeebee" ),
		"item_published" => __( "音频已发布", "appbeebee" ),
		"item_updated" => __( "音频已更新", "appbeebee" ),
	];

	$args = [
		"label" => __( "音频", "appbeebee" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => true,
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
		"menu_icon" => "dashicons-format-audio",
	];

	register_post_type( "beebee_library", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );
