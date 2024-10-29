<?php
/*
 * WordPress Utils Class For Router
 */

if (!defined('ABSPATH')) exit;
// add_filter('rest_url_prefix', function () {
// 	if( have_rows('mode_common','option') ):
// 		while ( have_rows('mode_common','option') ) : the_row();
// 			$newrestapi =  get_sub_field('safe')['restapi'];
// 		endwhile;
// 	else :
// 	endif;
// 	return $newrestapi;
// });


add_filter('bee_mp_thumbnail_url', function ($img_url, $args = [], $post_type, $thumb_type) {

	if (have_rows('mode_layout', 'option')) :
		while (have_rows('mode_layout', 'option')) : the_row();
			$cdn_type =  get_sub_field('thumbsize')['cdn'] ? get_sub_field('thumbsize')['cdn'] : 'none';
			$set_webp =  get_sub_field('thumbsize')['set']['webp'];
			$set_interlace =  get_sub_field('thumbsize')['set']['interlace'];
			$set_quality =  get_sub_field('thumbsize')['set']['quality'];
			if ($post_type === 'beebee_library') {
				if ($thumb_type === 'active') {
					$img_width =  get_sub_field('thumbsize')['library_active'] && get_sub_field('thumbsize')['library_active']['width'] ? get_sub_field('thumbsize')['library_active']['width'] : 0;
					$img_height =  get_sub_field('thumbsize')['library_active'] && get_sub_field('thumbsize')['library_active']['height'] ? get_sub_field('thumbsize')['library_active']['height'] : 0;
				} else if ($thumb_type === 'single') {
					$img_width =  get_sub_field('thumbsize')['library_single'] && get_sub_field('thumbsize')['library_single']['width'] ? get_sub_field('thumbsize')['library_single']['width'] : 0;
					$img_height =  get_sub_field('thumbsize')['library_single'] && get_sub_field('thumbsize')['library_single']['height'] ? get_sub_field('thumbsize')['library_single']['height'] : 0;
				} else if ($thumb_type === 'download') {
					$img_width =  get_sub_field('thumbsize')['library_full'] && get_sub_field('thumbsize')['library_full']['width'] ? get_sub_field('thumbsize')['library_full']['width'] : 0;
					$img_height =  get_sub_field('thumbsize')['library_full'] && get_sub_field('thumbsize')['library_full']['height'] ? get_sub_field('thumbsize')['library_full']['height'] : 0;
				} else {
					$img_width =  get_sub_field('thumbsize')['thumb'] && get_sub_field('thumbsize')['thumb']['width'] ? get_sub_field('thumbsize')['thumb']['width'] : 0;
					$img_height =  get_sub_field('thumbsize')['thumb'] && get_sub_field('thumbsize')['thumb']['height'] ? get_sub_field('thumbsize')['thumb']['height'] : 0;
				}
			} else {
				$img_width =  get_sub_field('thumbsize')['thumb'] && get_sub_field('thumbsize')['thumb']['width'] ? get_sub_field('thumbsize')['thumb']['width'] : 0;
				$img_height =  get_sub_field('thumbsize')['thumb'] && get_sub_field('thumbsize')['thumb']['height'] ? get_sub_field('thumbsize')['thumb']['height'] : 0;
			}

		// Do something...
		endwhile;
	else :
	// no rows found
	endif;
	if ($img_url==='' || ($img_url&&$cdn_type === 'none')) {
		return $img_url;
	}

	extract(wp_parse_args($args, [
		'mode'		=> null,
		'crop'		=> 1,
		'width'		=> $img_width,
		'height'	=> $img_height,
		'webp'		=> $set_webp,
		'interlace'	=> $set_interlace,
		'quality'	=> $set_quality
	]));

	if ($height > 10000) {
		$height = 0;
	}

	if ($width > 10000) {
		$width = 0;
	}

	$arg = '';

	if ($cdn_type === 'oss') {
		if ($width || $height) {
			if (is_null($mode)) {
				$crop	= $crop && ($width && $height);	// 只有都设置了宽度和高度才裁剪
				$mode	= $crop ? ',m_fill' : '';
			}

			$arg	.= '/resize' . $mode;

			if ($width) {
				$arg .= ',w_' . $width;
			}

			if ($height) {
				$arg .= ',h_' . $height;
			}
		}

		if ($webp) {
			$arg	.= '/format,webp';
		} else {
			if ($interlace) {
				$arg	.= '/interlace,1';
			}
		}

		if ($quality) {
			$arg	.= '/quality,Q_' . $quality;
		}

		if ($arg) {
			$arg	= 'x-oss-process=image' . $arg;

			if (strpos($img_url, 'x-oss-process=image')) {
				$img_url	= preg_replace('/x-oss-process=image\/(.*?)#/', '', $img_url);
			}

			$img_url	= add_query_arg([$arg => ''], $img_url) . '#';
		}
		return $img_url;
	} else if($cdn_type === 'cos') {
	
		if($width || $height){
			$arg	.= '/thumbnail/';
	
			if($width && $height){
				$arg	.= '!'.$width.'x'.$height.'r';
			}elseif($width){
				$arg	.= $width.'x';
			}elseif($height){
				$arg	.= 'x'.$height.'';
			}
	
			$crop	= $crop && ($width && $height);	// 只有都设置了宽度和高度才裁剪
	
			if($crop){
				$arg	.= '/gravity/Center/crop/';
	
				if($width && $height){
					$arg	.= $width.'x'.$height.'';
				}elseif($width){
					$arg	.= $width.'x';
				}elseif($height){
					$arg	.= 'x'.$height.'';
				}
			}
		}
	
		if($webp){
			$arg	.= '/format/webp';
		}else{
			if($quality){
				$arg	.= '/quality/'.$quality;
			}
	
			if($interlace){
				$arg	.= '/interlace/'.$interlace;
			}
		}
	
		if($arg){
			if(strpos($img_url, 'imageMogr2/')){
				$img_url	= preg_replace('/imageMogr2\/(.*?)#/', '', $img_url);
			}
	
			$arg	= 'imageMogr2'.$arg;
	
			if(strpos($img_url, 'watermark/')){
				$img_url	= $img_url.'|'.$arg;
			}else{
				$img_url	= add_query_arg([$arg=>''], $img_url);
			}
		}
	
		return $img_url;

	} else if($cdn_type === 'qiniu'){
	
		if($mode === null){
			$crop	= $crop && ($width && $height);	// 只有都设置了宽度和高度才裁剪
			$mode	= $mode?:($crop?1:2);
		}
	
		if($width || $height){
			$arg	= 'imageView2/'.$mode;
	
			if($width)		$arg .= '/w/'.$width;
			if($height) 	$arg .= '/h/'.$height;
			if($interlace)	$arg .= '/interlace/'.$interlace;
			if($quality)	$arg .= '/q/'.$quality;
	
			if(strpos($img_url, 'imageView2/')){
				$img_url	= preg_replace('/imageView2\/(.*?)#/', '', $img_url);
			}
	
			if(strpos($img_url, 'watermark/')){
				$img_url	= $img_url.'|'.$arg;
			}else{
				$img_url	= add_query_arg( array($arg => ''), $img_url );
			}
	
			$img_url	= $img_url.'#';
		}
	
		return $img_url;
	} else if($cdn_type === 'ucloud'){
	
		if($width || $height){
			$arg['iopcmd']	= 'thumbnail';
	
			if($width && $height){
				$arg['type']	= 13;
				$arg['height']	= $height;
				$arg['width']	= $width;
			}elseif($width){
				$arg['type']	= 4;
				$arg['width']	= $width;
			}elseif($height){
				$arg['type']	= 5;
				$arg['height']	= $height;
			}
	
			if(strpos($img_url, 'iopcmd=thumbnail') === false){
				$img_url	= add_query_arg($arg, $img_url );
				$img_url	= $img_url.'#';
			}
		}
	
		return $img_url;
	} else {
		return $img_url;
	}
	
}, 10, 4);


add_filter('rest_posts', function ($posts, $request) {
	$data = array();
	foreach ($posts as $post) {
		$_data = array();
		$post_id = $post->ID;
		$post_date = $post->post_date;
		$author_id = $post->post_author;
		$post_type = $post->post_type;
		$post_format = get_post_format($post_id);
		$author_avatar = get_user_meta($author_id, 'avatar', true);
		$taxonomies = get_object_taxonomies($post_type);
		$thumbnail = apply_filters('post_thumbnail', $post_id);
		$post_title = $post->post_title;
		$post_views = (int)get_post_meta($post_id, "views", true);
		$post_excerpt = $post->post_excerpt;
		$post_content = $post->post_content;
		$session = isset($request['access_token']) ? $request['access_token'] : '';
		if ($session) {
			$access_token = base64_decode($session);
			$users = MP_Auth::login($access_token);
			if ($users) {
				$user_id = $users->ID;
			} else {
				$user_id = 0;
			}
		} else {
			$user_id = 0;
		}
		// $_data["acf"]  = get_field( 'acf', $post_id );
		$_data["id"]  = $post_id;
		$_data["date"] = $post_date;
		$_data["newdate"] = datetime_before($post_date);
		$_data["week"] = get_wp_post_week($post_date);
		$_data["format"] = $post_format ? $post_format : 'standard';
		$_data["type"] = $post_type;
		if (get_post_meta($post_id, "source", true)) {
			$_data["meta"]["source"] = get_post_meta($post_id, "source", true);
		}
		$_data["meta"]["thumbnail"] = $thumbnail;
		if($post_type=='beebee_library') {
            $_data["meta"]["newthumbnail"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, 'active');
            $_data["meta"]["singleimg"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, 'single');
            $_data["meta"]["fullimg"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, 'download');
        } else {
            // $_data["meta"]["thumbnail"] = $thumbnail;
            $_data["meta"]["newthumbnail"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, '' );
        }
		$_data["meta"]["views"] = $post_views;
		$meta = apply_filters('custom_meta', $meta = array());
		if ($meta) {
			foreach ($meta as $meta_key) {
				$_data["meta"][$meta_key] = get_post_meta($post_id, $meta_key, true);
			}
		}
		$_data["comments"] = apply_filters( 'comment_type_count', $post_id, 'comment' );
		$_data["isfav"] = apply_filters( 'miniprogram_commented', $post_id, $user_id, 'fav' );
		$_data["favs"] = apply_filters( 'comment_type_count', $post_id, 'fav' );
		$_data["islike"] = apply_filters( 'miniprogram_commented', $post_id, $user_id, 'like' );
		$_data["likes"] = apply_filters('comment_type_count', $post_id, 'like');
		if ($post_type == 'beebee_topic') {
			$_data["post_likes"] = apply_filters('comment_type_list', $post_id, 'like');
		}
		// $_data["author"]["id"] = $author_id;
		// $_data["author"]["name"] = get_the_author_meta('nickname',$author_id);
		// if ($author_avatar) {
		// 	$_data["author"]["avatar"] = $author_avatar;
		// } else {
		// 	$_data["author"]["avatar"] = get_avatar_url($author_id);
		// }
		// $_data["author"]["description"] = get_the_author_meta('description',$author_id);
		if ($taxonomies) {
			foreach ($taxonomies as $taxonomy) {
				$terms = wp_get_post_terms($post_id, $taxonomy);
				foreach ($terms as $term) {
					$tax = array();
					$term_cover = get_term_meta($term->term_id, 'cover', true) ? get_term_meta($term->term_id, 'cover', true) : wp_miniprogram_option('thumbnail');
					$tax["id"] = $term->term_id;
					$tax["name"] = $term->name;
					$tax["description"] = $term->description;
					$tax["cover"] = apply_filters('mp_thumbnail_url', $term_cover);
					$taxonomy_val = $taxonomy . '_value';
					if ($taxonomy === 'post_tag') {
						$taxonomy_val = "tag";
					}
					if ($taxonomy === 'categorys') {
						$taxonomy_val = "category";
					}
					$_data[$taxonomy_val][] = $tax;
				}
			}
		}
		$_data["title"]["rendered"]  = html_entity_decode($post_title);
		$_data["excerpt"]["rendered"] = html_entity_decode(wp_strip_all_tags($post_excerpt));

		$_data['acf']["bee_topic_cover"] =  get_field('bee_topic_cover', $post_id);
		$_data['acf']["bee_topiccover_style_gallery"] =  get_field('bee_topiccover_style_gallery', $post_id);
		$_data['acf']["bee_quot_cnt"] = get_field('bee_quot_cnt', $post_id);
		$_data['acf']["bee_quot_coverstyle"] = get_field('bee_quot_coverstyle', $post_id);
		$_data['acf']["bee_quot_via"] = get_field('bee_quot_via', $post_id);
		$bee_to_library = get_field('bee_to_library', $post_id);
		if ($bee_to_library) {
			$__bee_to_library = array();
			foreach ($bee_to_library as $key => $app_k) {
				// if ($key >= 5) break;
				if (have_rows('bee_book_msggroup', $app_k)) :
					while (have_rows('bee_book_msggroup', $app_k)) : the_row();
						$bee_book_author =  get_sub_field('bee_book_author');
					// Do something...
					endwhile;
				else :
				// no rows found
				endif;
				$__bee_to_library[] = array(
					"bee_library_id" => $app_k,
					// "bee_library_thumb" => wp_get_attachment_image_src(get_post_thumbnail_id($app_k), 'medium')[0],
					"bee_library_thumb" => apply_filters('bee_mp_thumbnail_url', apply_filters('post_thumbnail', $app_k),[], 'beebee_library', 'active'),
					"bee_library_title" => get_the_title($app_k),
					"bee_library_pagestyle" => get_field('bee_book_pagestyle', $app_k),
					"bee_library_background" => get_field('bee_book_background', $app_k),
					"bee_library_type" => 'beebee_library',
					"type" => 'beebee_library',
					"title" => array(
						"rendered" => html_entity_decode(get_the_title($app_k)),
					),
					"excerpt" => array(
						"rendered" => html_entity_decode(wp_strip_all_tags(get_the_excerpt($app_k)))
					),
					"meta" => array(
						"thumbnail" => wp_get_attachment_image_src(get_post_thumbnail_id($app_k), 'medium')[0],
						"newthumbnail" => apply_filters('bee_mp_thumbnail_url', apply_filters('post_thumbnail', $app_k),[], 'beebee_library', 'active'),
						"singleimg" =>apply_filters('bee_mp_thumbnail_url', apply_filters('post_thumbnail', $app_k),[], 'beebee_library', 'single'),
						"fullimg" =>apply_filters('bee_mp_thumbnail_url', apply_filters('post_thumbnail', $app_k),[], 'beebee_library', 'download')
					),
					"id" => $app_k,
					"beebee_library_cats_value" => get_the_terms($app_k, 'beebee_library_cats'),
					"beebee_library_state_value" => get_the_terms($app_k, 'beebee_library_state'),
					"bee_library_author" => $bee_book_author?$bee_book_author:''
				);
			}
			$_data['acf']["bee_to_library"] = $__bee_to_library;
		}

		if (get_field('quotarea', $post_id)) {
			$_data["acf"]["quotarea"] = get_field('quotarea', $post_id);
		}
		if (get_field('bee_quot_radio', $post_id)) {
			$bee_quot_radio = get_field('bee_quot_radio', $post_id);
			if ($bee_quot_radio != '') {
				$p1 = '/%quotcnt%/';
				if ($bee_to_library[0] != false) {
					$r1 = get_field('bee_quot_cnt', $post_id) . '——摘自' . $_data['acf']["bee_to_library"][0]['bee_book_author'] . '' . $_data['acf']["bee_to_library"][0]['bee_library_title'] . '。';
				} else if (get_field('bee_quot_via', $post_id) != '') {
					$r1 = get_field('bee_quot_cnt', $post_id) . '——摘自' . get_field('bee_quot_via', $post_id);
				} else {
					$r1 = get_field('bee_quot_cnt', $post_id);
				}
				$bee_quot_radio = preg_replace($p1, $r1, $bee_quot_radio);
				$p2 = '/%sitename%/';
				if (have_rows('mode_common', 'option')) :
					while (have_rows('mode_common', 'option')) : the_row();
						$bee_site_name =  get_sub_field('site')['name'];
					// Do something...
					endwhile;
				else :
				// no rows found
				endif;
				$r2 = $bee_site_name != '' ? $bee_site_name : get_bloginfo('name');
				$bee_quot_radio = preg_replace($p2, $r2, $bee_quot_radio);
				$p3 = '/%postname%/';
				$r3 = html_entity_decode($post_title);
				$bee_quot_radio = preg_replace($p3, $r3, $bee_quot_radio);
				$p4 = '/%date%/';
				$r4 = $post_date;
				$bee_quot_radio = preg_replace($p4, $r4, $bee_quot_radio);
				$p5 = '/%author%/';
				$r5 = get_the_author_meta('nickname', $author_id);
				$bee_quot_radio = preg_replace($p5, $r5, $bee_quot_radio);
				$_data["acf"]["bee_quot_radio"] = $bee_quot_radio;
			}
		}
		// 小鱼哥 添加自定义字段结束
		if (wp_miniprogram_option("post_content")) {
			$_data["content"]["rendered"] = apply_filters('the_content', $post_content);
		}
		if (wp_miniprogram_option("post_picture")) {
			$_data["pictures"] = apply_filters('post_images', $post_id);
		}
		$data[] = $_data;
	}
	return $data;
}, 10, 2);

add_filter('mp_we_submit_pages', function ($post_id) {
	$post_type = get_post_type($post_id);
	$session = MP_Auth::we_miniprogram_access_token();
	$access_token = isset($session['access_token']) ? $session['access_token'] : '';
	if ($access_token) {
		$url = 'https://api.weixin.qq.com/wxa/search/wxaapi_submitpages?access_token=' . $access_token;
		if ($post_type == 'post' || $post_type == 'beebee_library' ||  $post_type == 'beebee_quot' || $post_type == 'beebee_topic') {
			$path = 'pages/single/single';
		} else if ($post_type == 'page') {
			$path = 'pages/page/page';
		} else {
			$path = '';
		}
		if ($path) {
			if ($post_type == 'post') {
				$posttype = 'posts';
			} else {
				$posttype = $post_type;
			}
			$pages = array('path' => $path, 'query' => 'posttype=' . $posttype . '&id=' . $post_id);
			$args = array('body' => json_encode(array('pages' => array($pages))));
			$response = wp_remote_post($url, $args);
			if (is_wp_error($response)) {
				return array("status" => 404, "code" => "error", "message" => "数据请求错误");
			} else {
				return json_decode($response['body'], true);
			}
		} else {
			return array("status" => 404, "code" => "error", "message" => "页面路径错误");
		}
	}
});


// 小鱼哥 增加用户操作数
add_filter('comment_type_user_count', function ($post_type, $user_id, $type) {
	$user = get_user_by('ID', $user_id);
	if (!$user) {
		return false;
	}
	$args = array('post_type' => $post_type, 'type__in' => array($type), 'user_id' => $user_id, 'count' => true, 'status' => 'approve');
	$counts = get_comments($args);
	return $counts ? $counts : 0;
}, 10, 3);
