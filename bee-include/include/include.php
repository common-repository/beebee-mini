<?php
if (!defined('ABSPATH')) exit;
include( APP_BEEBEE_REST_API. 'bee-include/include/hooks.php' ); 
include( APP_BEEBEE_REST_API. 'bee-include/include/filter.php' );
include( APP_BEEBEE_REST_API. 'bee-include/include/notices.php' );
// include( APP_BEEBEE_REST_API. 'bee-include/include/custom.php' );

function getmiss($isinclude)
{
    $settings = '';
    $isinclude= '';
    $options = apply_filters('appbeebee_setting_options', $path = APP_BEEBEE_REST_API . 'bee-content/themes');
    $option = $options['beebee-theme-choose']['fields']['choosetheme']['options'];
    if ($option) {
        $settings = isset(get_option('beeapp')['choosetheme']) ? get_option('beeapp')['choosetheme'] : '';
        foreach ($option as $key => $option) {
            if ($key == $settings) {
                foreach ($option['miss'] as $key => $miss) {
                    if ($miss['isinclude']) {
                        $isinclude = $key;
                    }
                }
            }
        }  
    }
    return $isinclude;
}
add_filter('getmiss', 'getmiss');

add_filter('rest_prepare_beebee_topic', 'rest_prepare_fields', 10, 3);
add_filter('rest_prepare_beebee_library', 'rest_prepare_fields', 10, 3);
add_filter('rest_prepare_beebee_quot', 'rest_prepare_fields', 10, 3);
add_filter('rest_prepare_beebee_face', 'rest_prepare_fields', 10, 3);
// add_filter('rest_prepare_course', 'rest_prepare_course_fields', 10, 3);

