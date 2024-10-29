<?php
function cptui_register_my_cpts() {

/**
 * Post Type: 专题.
 */

$labels = [
    "name" => __( "专题", "appbeebee" ),
    "singular_name" => __( "专题", "appbeebee" ),
    "menu_name" => __( "专题", "appbeebee" ),
    "all_items" => __( "所有专题", "appbeebee" ),
    "add_new" => __( "添加新专题", "appbeebee" ),
    "add_new_item" => __( "添加新专题", "appbeebee" ),
    "edit_item" => __( "编辑专题", "appbeebee" ),
    "new_item" => __( "新专题", "appbeebee" ),
    "view_item" => __( "查看专题", "appbeebee" ),
    "view_items" => __( "查看专题", "appbeebee" ),
    "search_items" => __( "搜索专题", "appbeebee" ),
    "not_found" => __( "没有找到专题", "appbeebee" ),
    "featured_image" => __( "专题封面", "appbeebee" ),
    "set_featured_image" => __( "设置专题封面", "appbeebee" ),
    "remove_featured_image" => __( "移除封面", "appbeebee" ),
    "archives" => __( "专题存档", "appbeebee" ),
    "uploaded_to_this_item" => __( "更新专题", "appbeebee" ),
    "filter_items_list" => __( "筛选专题列表", "appbeebee" ),
    "items_list" => __( "专题列表", "appbeebee" ),
];

$args = [
    "label" => __( "专题", "appbeebee" ),
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
    "supports" => [ "title", "editor", "thumbnail", "excerpt", "custom-fields" ],
    "taxonomies" =>  [
        "post_tag"
    ],
];

register_post_type( "beebee_topic", $args );

/**
 * Post Type: 书目.
 */

$labels = [
    "name" => __( "书目", "appbeebee" ),
    "singular_name" => __( "书目", "appbeebee" ),
    "all_items" => __( "所有书目", "appbeebee" ),
    "add_new" => __( "添加新书目", "appbeebee" ),
    "add_new_item" => __( "添加新书目", "appbeebee" ),
    "edit_item" => __( "编辑书目", "appbeebee" ),
    "new_item" => __( "新书目", "appbeebee" ),
    "view_item" => __( "查看书籍", "appbeebee" ),
    "view_items" => __( "查看书目", "appbeebee" ),
    "search_items" => __( "搜索书目", "appbeebee" ),
    "not_found" => __( "没有找到书目", "appbeebee" ),
    "featured_image" => __( "书籍封面", "appbeebee" ),
    "set_featured_image" => __( "设置书籍封面", "appbeebee" ),
    "remove_featured_image" => __( "移除封面", "appbeebee" ),
    "use_featured_image" => __( "使用书籍封面", "appbeebee" ),
    "archives" => __( "书目存档", "appbeebee" ),
    "uploaded_to_this_item" => __( "更新书籍", "appbeebee" ),
    "filter_items_list" => __( "筛选书籍列表", "appbeebee" ),
    "items_list" => __( "书籍列表", "appbeebee" ),
];

$args = [
    "label" => __( "书目", "appbeebee" ),
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
    "menu_icon" => "dashicons-book",
    "supports" => [ "title", "editor", "thumbnail", "excerpt", "custom-fields" ],
    "taxonomies" =>  [
        "post_tag"
    ],
];

register_post_type( "beebee_library", $args );

/**
 * Post Type: 语录.
 */

$labels = [
    "name" => __( "语录", "appbeebee" ),
    "singular_name" => __( "语录", "appbeebee" ),
    "menu_name" => __( "语录", "appbeebee" ),
    "all_items" => __( "所有语录", "appbeebee" ),
    "add_new" => __( "添加新语录", "appbeebee" ),
    "add_new_item" => __( "添加新语录", "appbeebee" ),
    "edit_item" => __( "编辑语录", "appbeebee" ),
    "new_item" => __( "新语录", "appbeebee" ),
    "view_item" => __( "查看语录", "appbeebee" ),
    "view_items" => __( "查看语录", "appbeebee" ),
    "search_items" => __( "搜索语录", "appbeebee" ),
    "not_found" => __( "没有找到语录", "appbeebee" ),
    "archives" => __( "语录存档", "appbeebee" ),
    "uploaded_to_this_item" => __( "更新语录", "appbeebee" ),
    "filter_items_list" => __( "删选语录列表", "appbeebee" ),
    "items_list" => __( "语录列表", "appbeebee" ),
];

$args = [
    "label" => __( "语录", "appbeebee" ),
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
    "rewrite" => [ "slug" => "beebee_quot", "with_front" => true ],
    "query_var" => true,
    "menu_position" => 5,
    "menu_icon" => "dashicons-coffee",
    "supports" => [ "title", "editor", "thumbnail", "excerpt", "custom-fields", "author" ],
    "taxonomies" =>  [
        "post_tag"
    ],
];

register_post_type( "beebee_quot", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );


