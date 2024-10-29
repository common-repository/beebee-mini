<?php
/*
 * router files
 */
include( APP_BEEBEE_REST_API. 'bee-include/router/posts.php' );
include( APP_BEEBEE_REST_API. 'bee-include/router/comments.php' ); 
include( APP_BEEBEE_REST_API. 'bee-include/router/users.php' ); 
include( APP_BEEBEE_REST_API. 'bee-include/router/qrcode.php' );
include( APP_BEEBEE_REST_API. 'bee-include/router/audioview.php' );
add_action( 'rest_api_init', function () {
	$controller = array();
	$controller[] = new WP_REST_BEE_Posts_Router();
	$controller[] = new WP_REST_BEE_Comments_Router();
	$controller[] = new WP_REST_BEE_Users_Router();
	$controller[] = new WP_REST_BEE_Qrcode_Router();
	$controller[] = new WP_REST_BEE_AV_Router();
	foreach ( $controller as $control ) {
		$control->register_routes();
	}
} );

