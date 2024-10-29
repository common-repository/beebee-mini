<?php

if ( !defined( 'ABSPATH' ) ) exit;

class WP_REST_BEE_AV_Router extends WP_REST_Controller {

	public function __construct( ) {
		$this->namespace     = 'mp/v1';
    	$this->resource_name = 'audioview';
	}

	public function register_routes() {

		register_rest_route( $this->namespace, '/' . $this->resource_name, array(
			array(
				'methods'             	=> WP_REST_Server::CREATABLE,
				'callback'            	=> array( $this, 'wp_post_audioview' ),
				'permission_callback' 	=> array( $this, 'wp_audioview_permissions_check' ),
				'args'                	=> $this->audioview_collection_params(),
			)
		));
		
	}

	public function wp_audioview_permissions_check($request) {
		return true;
	}

	public function audioview_collection_params() {
		$params = array();
		$params['id'] = array(
			'default'			 => 0,
			'description'        => __( '帖子ID。如果等于0以外的其他内容，则将更新具有该ID的帖子。默认值为0。' ),
			'type'               => 'integer',
		);
		return $params;
	}

	public function wp_post_audioview( $request ) {
		$post_id = $request['id'];
		$post_views = (int)get_post_meta($post_id, "views", true);
		
		if (!update_post_meta($post_id, 'views', ($post_views + 1))) {
			add_post_meta($post_id, 'views', 1, true);
		}
		$result["status"]		= 200; 
			$result["code"]			= "success";
			$result["message"]		= "qrcode creat success"; 
			$result["audioview"] 		= $post_views;
		$response = rest_ensure_response( $result );
		return $response;
	}
}