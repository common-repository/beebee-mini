<?php
/*
Plugin Name: Beebee Mini 比比小程序
Plugin URI: https://beebee.work/2022/09/08/bi-bi-xiao-cheng-xu-hou-tai-cha-jian-beebeemini/
Description: 这里有很多漂亮的原创的微信小程序模板，完全基于开源的程序打造。自1.3.x版本之后的比比后台插件请从比比网进行免费下载。
Version: 1.3.2
Author:  hellobeebee
Author URI: https://beebee.work/
requires at least: 5.5
tested up to: 6.1.1
*/

define('APP_BEEBEE_REST_API', plugin_dir_path(__FILE__));
define('APP_BEEBEE_API_URL', plugin_dir_url(__FILE__));
define('APP_BEEBEE_URL', plugins_url('', __FILE__));
define('APP_BEEBEE_API_PLUGIN',  __FILE__);
add_action('plugins_loaded', function () {
	include(APP_BEEBEE_REST_API . 'bee-admin/admin/admin.php');
	include(APP_BEEBEE_REST_API . 'bee-include/include/include.php');
	include(APP_BEEBEE_REST_API . 'bee-include/router/router.php');
	
});

// include_once(APP_BEEBEE_REST_API . 'bee-admin/admin/core/theme_temp.php');

include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/rest-api/class-acf-to-rest-api.php' );
include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/acf-importer/acf-importer.php' );
// include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/acf-options-import-export/src/acf/andyp_plugin_register.php' );
// include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/acf-options-import-export/src/acf_admin_page.php' );
// include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/acf-options-import-export/src/import_export_class.php' );


// 为插件添加设置快捷链接
add_filter('plugin_action_links', function ($links, $file) {
	if (plugin_basename(__FILE__) !== $file) {
		return $links;
	}
	$settings_link = '<a href="' . add_query_arg(array('page' => 'appbeebee'), admin_url('admin.php')) . '">' . esc_html__('设置', 'appbeebee') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}, 10, 2);

function appbeebee_options_manage_page( ) {
	$option = array(
		'id' 		=> 'beeapp-form',
		'options'	=> 'beeapp',
		"group"		=> "beeapp-group"
	);
	$options = apply_filters( 'appbeebee_setting_options', $path = APP_BEEBEE_REST_API . 'bee-content/themes' );
	require_once( APP_BEEBEE_REST_API. 'bee-admin/admin/core/settings.php' );
		
}