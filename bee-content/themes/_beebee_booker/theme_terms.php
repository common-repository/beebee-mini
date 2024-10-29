<?php
function cptui_register_my_taxes() {

	/**
	 * Taxonomy: 书目分类.
	 */

	$labels = [
		"name" => __( "书目分类", "appbeebee" ),
		"singular_name" => __( "书目分类", "appbeebee" ),
		"all_items" => __( "所有类别", "appbeebee" ),
		"edit_item" => __( "编辑类别", "appbeebee" ),
		"view_item" => __( "查看类别", "appbeebee" ),
		"update_item" => __( "更新类别", "appbeebee" ),
		"add_new_item" => __( "添加新类别", "appbeebee" ),
		"new_item_name" => __( "新类别", "appbeebee" ),
		"parent_item" => __( "父类别", "appbeebee" ),
		"parent_item_colon" => __( "父类别：", "appbeebee" ),
		"search_items" => __( "搜索类别", "appbeebee" ),
		"popular_items" => __( "热门类别", "appbeebee" ),
		"add_or_remove_items" => __( "类别增删", "appbeebee" ),
		"choose_from_most_used" => __( "选择使用最多的类别", "appbeebee" ),
		"not_found" => __( "没有找到类别", "appbeebee" ),
		"no_terms" => __( "没有类别", "appbeebee" ),
		"items_list" => __( "类别列表", "appbeebee" ),
		"back_to_items" => __( "返回书目分类", "appbeebee" ),
	];

	$args = [
		"label" => __( "书目分类", "appbeebee" ),
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
		"show_in_quick_edit" => true,
			];
	register_taxonomy( "beebee_library_cats", [ "beebee_library" ], $args );

	/**
	 * Taxonomy: 专题分类.
	 */

	$labels = [
		"name" => __( "专题分类", "appbeebee" ),
		"singular_name" => __( "专题分类", "appbeebee" ),
		"all_items" => __( "所有类别", "appbeebee" ),
		"edit_item" => __( "编辑类别", "appbeebee" ),
		"view_item" => __( "查看类别", "appbeebee" ),
		"update_item" => __( "更新类别", "appbeebee" ),
		"add_new_item" => __( "添加新类别", "appbeebee" ),
		"new_item_name" => __( "新类别", "appbeebee" ),
		"parent_item" => __( "父类别", "appbeebee" ),
		"parent_item_colon" => __( "父类别：", "appbeebee" ),
		"search_items" => __( "搜索类别", "appbeebee" ),
		"popular_items" => __( "热门类别", "appbeebee" ),
		"add_or_remove_items" => __( "类别增删", "appbeebee" ),
		"choose_from_most_used" => __( "选择使用最多的类别", "appbeebee" ),
		"not_found" => __( "没有找到类别", "appbeebee" ),
		"no_terms" => __( "没有类别", "appbeebee" ),
		"items_list" => __( "类别列表", "appbeebee" ),
		"back_to_items" => __( "返回专题分类", "appbeebee" ),
	];

	$args = [
		"label" => __( "专题分类", "appbeebee" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'beebee_topic_cats', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "beebee_topic_cats",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => true,
			];
	register_taxonomy( "beebee_topic_cats", [ "beebee_topic" ], $args );

	/**
	 * Taxonomy: 语录分类.
	 */

	$labels = [
		"name" => __( "语录分类", "appbeebee" ),
		"singular_name" => __( "语录分类", "appbeebee" ),
		"all_items" => __( "所有类别", "appbeebee" ),
		"edit_item" => __( "编辑类别", "appbeebee" ),
		"view_item" => __( "查看类别", "appbeebee" ),
		"update_item" => __( "更新类别", "appbeebee" ),
		"add_new_item" => __( "添加新类别", "appbeebee" ),
		"new_item_name" => __( "新类别", "appbeebee" ),
		"parent_item" => __( "父类别", "appbeebee" ),
		"parent_item_colon" => __( "父类别：", "appbeebee" ),
		"search_items" => __( "搜索类别", "appbeebee" ),
		"popular_items" => __( "热门类别", "appbeebee" ),
		"add_or_remove_items" => __( "类别增删", "appbeebee" ),
		"choose_from_most_used" => __( "选择使用最多的类别", "appbeebee" ),
		"not_found" => __( "没有找到类别", "appbeebee" ),
		"no_terms" => __( "没有类别", "appbeebee" ),
		"items_list" => __( "类别列表", "appbeebee" ),
		"back_to_items" => __( "返回语录分类", "appbeebee" ),
	];

	$args = [
		"label" => __( "语录分类", "appbeebee" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'beebee_quot_cats', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "beebee_quot_cats",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => true,
			];
	register_taxonomy( "beebee_quot_cats", [ "beebee_quot" ], $args );

	/**
	 * Taxonomy: 书目状态.
	 */

	$labels = [
		"name" => __( "书目状态", "appbeebee" ),
		"singular_name" => __( "书目状态", "appbeebee" ),
		"menu_name" => __( "书目状态", "appbeebee" ),
		"all_items" => __( "所有状态", "appbeebee" ),
		"edit_item" => __( "编辑状态", "appbeebee" ),
		"view_item" => __( "查看状态", "appbeebee" ),
		"update_item" => __( "更新状态", "appbeebee" ),
		"add_new_item" => __( "添加新状态", "appbeebee" ),
		"new_item_name" => __( "新状态", "appbeebee" ),
		"parent_item" => __( "父状态", "appbeebee" ),
		"parent_item_colon" => __( "父状态：", "appbeebee" ),
		"search_items" => __( "搜索状态", "appbeebee" ),
		"popular_items" => __( "热门状态", "appbeebee" ),
		"add_or_remove_items" => __( "状态增删", "appbeebee" ),
		"choose_from_most_used" => __( "选择使用最多的状态", "appbeebee" ),
		"not_found" => __( "没有找到状态", "appbeebee" ),
		"no_terms" => __( "没有状态", "appbeebee" ),
		"items_list" => __( "状态列表", "appbeebee" ),
		"back_to_items" => __( "返回书目状态", "appbeebee" ),
	];

	$args = [
		"label" => __( "书目状态", "appbeebee" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'beebee_library_state', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "beebee_library_state",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => true,
			];
	register_taxonomy( "beebee_library_state", [ "beebee_library" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes' );
