<?php

if (! defined('ABSPATH')) {
    exit;
}

if (!function_exists('acf_add_options_page')) {
    include(APP_BEEBEE_REST_API . 'bee-admin/admin/library/framework/acf.php');

    add_filter('acf/settings/path', 'beebee_acf_settings_path');
     
    function beebee_acf_settings_path($path)
    {
        $path = APP_BEEBEE_REST_API . 'bee-admin/admin/library/framework/';
        return $path;
    }
     
    add_filter('acf/settings/dir', 'beebee_acf_settings_dir');
     
    function beebee_acf_settings_dir($dir)
    {
        $dir = APP_BEEBEE_API_URL . 'bee-admin/admin/library/framework/';
        return $dir;
    }

    add_filter('acf/settings/show_admin', '__return_false');
    add_filter('acf/settings/remove_wp_meta_box', '__return_false');

}
