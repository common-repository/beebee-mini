<?php
if ( !defined( 'ABSPATH' ) ) exit;
include( APP_BEEBEE_REST_API. 'bee-admin/admin/option.php' ); 
include( APP_BEEBEE_REST_API. 'bee-admin/admin/core/options.php' );
include( APP_BEEBEE_REST_API. 'bee-admin/admin/core/menu.php');
include( APP_BEEBEE_REST_API. 'bee-admin/admin/core/spider.php' );
include( APP_BEEBEE_REST_API. 'bee-admin/admin/core/interface.php' );
include( APP_BEEBEE_REST_API. 'bee-admin/admin/core/sanitization.php' );
include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/acf-extended/acf-extended.php' );


add_action( 'admin_enqueue_scripts', function () {
	wp_enqueue_script( 'appbeebee-swiper', APP_BEEBEE_API_URL.'bee-admin/static/swiper.min.js', array( 'jquery' ), get_plugin_data(APP_BEEBEE_API_PLUGIN)['Version']);
	wp_enqueue_style('appbeebee', APP_BEEBEE_API_URL.'bee-admin/static/style.css', array(),  get_plugin_data(APP_BEEBEE_API_PLUGIN)['Version'] );
	wp_enqueue_script( 'appbeebee', APP_BEEBEE_API_URL.'bee-admin/static/script.js', array( 'jquery' ),  get_plugin_data(APP_BEEBEE_API_PLUGIN)['Version']);
	wp_enqueue_script( 'appbeebee-plugin-install', APP_BEEBEE_API_URL.'bee-admin/static/newstyle.js', array( 'jquery' ),  get_plugin_data(APP_BEEBEE_API_PLUGIN)['Version']);
	add_thickbox();
	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	if (!defined('LLMS_PLUGIN_FILE')) {
		wp_enqueue_script( 'appbeebee-novel', APP_BEEBEE_API_URL.'bee-admin/static/novel.js', array( 'jquery' ),  get_plugin_data(APP_BEEBEE_API_PLUGIN)['Version']);
	}
} );


if( defined('APP_BEEBEE_API_PLUGIN') ){
add_action( 'admin_init', function() {
	register_setting( "beeapp-group", "beeapp", array( 'sanitize_callback' => 'validate_sanitize_appbeebee_options' ) );
});
}

add_action( 'admin_notices', function () {
	if( isset($_GET['page']) && trim($_GET['page']) == 'appbeebee' && isset($_REQUEST['settings-updated']) ) {
		$class = 'notice notice-success is-dismissible';
		$message = __( '设置已更新保存!', 'imahui' );
		printf( '<div class="%1$s"><p><strong>%2$s</strong></p></div>', esc_attr( $class ), esc_html( $message ) );
	}
} );
