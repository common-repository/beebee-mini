<?php

if (!defined('ABSPATH')) exit;

function register_appbeebee_manage_menu()
{
    add_menu_page(
        '比比小程序原创主题面板',
        '比比小程序',
        'manage_options',
        'appbeebee',
        '',
        'dashicons-icon-appbeebee-logo',
        2
    );

    add_submenu_page('appbeebee', '主题面板', '主题面板', 'manage_options', 'appbeebee', function () {
        appbeebee_options_manage_page();
    });

    // submenu hook
	do_action("yjw_add_diy_submenu");
}


// if( defined('APP_BEEBEE_API_PLUGIN') ){
add_action('admin_menu', 'register_appbeebee_manage_menu');
// }

function beebee_add_page($title,$slug,$page_template=''){   
    $allPages = get_pages();//获取所有页面   
    $exists = false;   
    foreach( $allPages as $page ){   
        //通过页面别名来判断页面是否已经存在   
        if( strtolower( $page->post_name ) == strtolower( $slug ) ){   
            $exists = true;   
        }   
    }   
    if( $exists == false ) {   
        $new_page_id = wp_insert_post(   
            array(   
                'post_title' => $title,   
                'post_type'     => 'page',   
                'post_name'  => $slug,   
                'comment_status' => 'closed',   
                'ping_status' => 'closed',   
                'post_content' => '',   
                'post_status' => 'publish',   
                'post_author' => 1,   
                'menu_order' => 0   
            )   
        );   
        //如果插入成功 且设置了模板   
        if($new_page_id && $page_template!=''){   
            //保存页面模板信息   
            update_post_meta($new_page_id, '_wp_page_template',  $page_template);   
        }   
    }   
}

add_action('admin_notices', 'plug_association', 10);
function plug_association()
{
    $out = array();
    $output = '';
    $settings = '';
    $currenttheme = '';
    $display = false;
    $options = apply_filters('appbeebee_setting_options', $path = APP_BEEBEE_REST_API . 'bee-content/themes');
    $option = $options['beebee-theme-choose']['fields']['choosetheme']['options'];
    if (is_admin() && $option) {
        $settings = isset(get_option('beeapp')['choosetheme']) ? get_option('beeapp')['choosetheme'] : '';

        foreach ($option as $key => $option) {
            if ($key == $settings) {
                $currenttheme = $option['title'] . $option['vision'];
                foreach ($option['miss'] as $key => $miss) {
                    if (!defined($key)) {
                        $display = true;
                    }
                    $out[] = sprintf(
                        ' <a href="%s" class="%s" aria-label="%s" data-title="%s">%s</a> %s ',
                        esc_url(network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $miss['url'] . '&TB_iframe=true&width=600&height=550')),
                        "thickbox open-plugin-details-modal open-plugin-details-modal-new",
                        esc_attr($miss['name'] . '的更多信息'),
                        esc_attr($miss['name']),
                        esc_attr($miss['name']),
                        esc_attr($miss['vision'])
                    );
                }
            }
        }
        if( defined('APP_BEEBEE_API_PLUGIN') ){
        $output .= '<div class="error notice is-dismissible"><p>您当前启用的小程序主题【<a href="admin.php?page=appbeebee">' . $currenttheme . '</a>】，须安装并启用' . join(__(', '), $out) . '插件</p></div>';
        } else {
        $output .= '<div class="error notice is-dismissible"><p>您当前启用的小程序主题【<a href="admin.php?page=appbeebee">' . $currenttheme . '</a>】判定为盗版，正版授权渠道在唯一指定公众号[APP比比]购买</p></div>';
        }
        if ($display) {
            esc_html(_e($output));
        }
    }
}
