<?php
if (!defined('ABSPATH')) exit;

function appbeebee_setting_options($path)
{
	$options = array(
		'beebee-theme-choose' => [
			'title' => '主题选择',
			'summary' => '<p>点击主题启用进行小程序相关配置</p>',
			'fields' => [
				'choosetheme'		=> [
					'title' => '主题列表',
					'type' => 'gallery',
				]
			],
		]
	);
	$options['beebee-theme-choose']['fields']['choosetheme']['options'] = [];
	if (!file_exists($path)) {
		return [];
	}
	$handle = opendir($path);
	if ($handle) {
		while (($file = readdir($handle)) !== false) {
			$newPath = $path . DIRECTORY_SEPARATOR . $file;
			if (is_dir($newPath) && $file != '.' && $file != '..') {
				$json_string = file_get_contents($newPath.'/theme.json');
				$data = json_decode($json_string, true);
				$options['beebee-theme-choose']['fields']['choosetheme']['options'][$file] = $data;		
			} 
		}
	}
	@closedir($handle);
	return $options;
}
add_filter('appbeebee_setting_options', 'appbeebee_setting_options', 10, 1);


function appbeebee_options_theme_page( ) {
    $path = APP_BEEBEE_REST_API . 'bee-content/themes';
        $settings = isset(get_option('beeapp')['choosetheme']) ? get_option('beeapp')['choosetheme'] : '';
		file_exists($path.'/'. $settings.'/theme_menu.php') ? require_once( $path.'/'. $settings.'/theme_menu.php' ): '' ; 
// 		file_exists($path.'/'. $settings.'/theme_temp.php') ? require_once( $path.'/'. $settings.'/theme_temp.php' ): '' ; 
		file_exists($path.'/'. $settings.'/theme_meta.php') ? require_once( $path.'/'. $settings.'/theme_meta.php' ): '' ; 
		file_exists($path.'/'. $settings.'/theme_terms.php') ? require_once( $path.'/'. $settings.'/theme_terms.php' ): '' ; 
		file_exists($path.'/'. $settings.'/theme_types.php') ? require_once( $path.'/'. $settings.'/theme_types.php' ): '' ; 
}
if( defined('APP_BEEBEE_API_PLUGIN') ){
add_action( 'options_theme', 'appbeebee_options_theme_page' );
do_action( 'options_theme' );
}

