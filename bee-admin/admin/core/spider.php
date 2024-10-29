<?php
if ( !defined( 'ABSPATH' ) ) exit;

//开始增加自动获取豆瓣书影音脚本
add_action('wp_ajax_nopriv_automsglibrary', 'automsglibrary_callback');
add_action('wp_ajax_automsglibrary', 'automsglibrary_callback');

//开始增加自动获取豆瓣书影音脚本
add_action('wp_ajax_nopriv_automsgmovie', 'automsgmovie_callback');
add_action('wp_ajax_automsgmovie', 'automsgmovie_callback');

add_action('wp_ajax_nopriv_automsgapp', 'automsgapp_callback');
add_action('wp_ajax_automsgapp', 'automsgapp_callback');


function cut($content, $start, $end)
{
	$r = explode($start, $content);
	if (isset($r[1])) {
		$r = explode($end, $r[1]);
		return $r[0];
	}
	return '';
}

function automsglibrary_callback()
{
	$isbn = sanitize_text_field($_GET['isbn']);
	$surl = 'https://book.douban.com/isbn/' . $isbn . '/';
	$response = wp_remote_get($surl);
	if ( is_array( $response ) && !is_wp_error($response) && $response['response']['code'] == '200' ) {
		$data = $response['body'];
	}
	$search = array(" ", "　", "\n", "\r", "\t");
	$replace = array("", "", "", "", "");
	$data_1 = cut($data, 'application/ld+json">', '</script>');
	$data_1 = json_decode($data_1, true);
	$res['isbn'] = $data_1['isbn'];
	$res['title'] = $data_1['name'];

	$authors = $data_1['author'];
	if (!empty($authors)) {
		$__authors = '';
		foreach ($authors as $author) {
			$__authors .= sprintf(
				'%1$s ',
				$author['name']
			);
		} 
		$res['author'] = $__authors;
	}

	$res['url'] = $data_1['url'];

	$res['id'] = cut($res['url'], 'subject/', '/');

	$res['logo'] = cut($data, 'data-pic="', '"');

	$res['logos'] = str_replace('subject/l/public','subject/s/public',$res['logo']); 

	
	$dbscore_txt = cut($data, 'property="v:average">', '</strong>');
	$dbscore = str_replace($search, $replace, $dbscore_txt);
	$res['dbscore'] = $dbscore;

	$publisher_txt = cut($data, '出版社:</span>', '<br>');
	$publisher = str_replace($search, $replace, $publisher_txt);
	$publisher2 = strip_tags($publisher);
	$res['publisher'] = $publisher2;

	$published_txt = cut($data, '出版年:</span>', '<br/>');
	$published = str_replace($search, $replace, $published_txt);
	$res['published'] = $published;

	$page_txt = cut($data, '页数:</span>', '<br/>');
	$page = str_replace($search, $replace, $page_txt);
	$res['page'] = $page;

	$translator_html = cut($data, '译者</span>:', '</span><br/>');
	$translator_txt = strip_tags($translator_html);
	$translator = str_replace($search, $replace, $translator_txt);
	$res['translator'] = $translator;

	$price_txt = cut($data, '定价:</span>', '<br/>');
	$price = str_replace($search, $replace, $price_txt);
	if ($price == '') {
		$price = '未知';
	}
	$res['price'] = $price;

	$designed_txt = cut($data, '装帧:</span>', '<br/>');
	$designed = str_replace($search, $replace, $designed_txt);
	$res['designed'] = $designed;

	// $description = cut($data,'class="intro">','</p>');
	// $description = explode('<p>',$description)[1];
	// if($description==''){
	//   $description ='未知';
	// }
	// $res['description'] =$description;

	$res = json_encode($res, true);
	esc_html(_e($res));
	
	exit;
}


function automsgmovie_callback()
{
	$dbid = sanitize_text_field($_GET['dbid']);
	$surl = 'https://movie.douban.com/subject/' . $dbid . '/';
	$response = wp_remote_get($surl);
	if ( is_array( $response ) && !is_wp_error($response) && $response['response']['code'] == '200' ) {
		$data = $response['body'];
	}
	$search = array(" ", "　", "\n", "\r", "\t");
	$replace = array("", "", "", "", "");
	$data_1 = cut($data, 'application/ld+json">', '</script>');
    $data_1 = preg_replace("/\"description\"(.*)\"([\s\S]*?)\"\,/","\"description\": \"\",",$data_1);
	$data_1 = json_decode($data_1, true);
	// $res['isbn'] = $data_1['isbn'];
	$res['title'] = $data_1['name'];

	$directors = $data_1['director'];
        if (!empty($directors)) {
            $__directors = '';
            foreach ($directors as $director) {
                if ($director != end($directors)) {
                    // 不是最后一项
                    $__directors .= sprintf(
                        '%1$s / ',
                        $director['name']
                    );
                } else {
                    // 最后一项
                    $__directors .= sprintf(
                        '%1$s',
                        $director['name']
                    );
                }
            }
            $res['director'] = $__directors; //导演
        }

        $authors = $data_1['author'];
        if (!empty($authors)) {
            $__authors = '';
            foreach ($authors as $author) {
                if ($author != end($authors)) {
                    // 不是最后一项
                    $__authors .= sprintf(
                        '%1$s / ',
                        $author['name']
                    );
                } else {
                    // 最后一项
                    $__authors .= sprintf(
                        '%1$s',
                        $author['name']
                    );
                }
            }
            $res['writer'] = $__authors; //编剧
        }

        $actors = $data_1['actor'];
        if (!empty($actors)) {
            $__actors = '';
            foreach ($actors as $actor) {
                if ($actor != end($actors)) {
                    // 不是最后一项
                    $__actors .= sprintf(
                        '%1$s / ',
                        $actor['name']
                    );
                } else {
                    // 最后一项
                    $__actors .= sprintf(
                        '%1$s',
                        $actor['name']
                    );
                }
            }
            $res['star'] = $__actors; //演员
        }
        $res['url'] = 'https://movie.douban.com'.$data_1['url'];
        $res['id'] = cut($res['url'], 'subject/', '/');

        $res['logos'] = $data_1['image']; //图标
        // $res['logo'] = cut($data, 'data-pic="', '"');

	$res['logo'] = str_replace('s_ratio_poster/','l_ratio_poster/',$res['logos']); 

        $res['datePublished'] = $data_1['datePublished']; //上映时间

        $res['type'] = $data_1['genre']; //类型

        $aggregateRating = $data_1['aggregateRating'];
        $res['dbscore'] = $aggregateRating['ratingValue']; //评分

        $area_txt = cut($data, '地区:</span>', '<br/>');
        $area = str_replace($search, $replace, $area_txt);
        $res['area'] = $area; //制片国家
        $opened_txt = cut($data, '"year">(', ')');
        $opened = str_replace($search, $replace, $opened_txt);
        $res['opened'] = $opened; //上映年份

        $language_txt = cut($data, '语言:</span>', '<br/>');
        $language = str_replace($search, $replace, $language_txt);
        $res['language'] = $language; //语言

        $alias_txt = cut($data, '又名:</span>', '<br/>');
        $alias = str_replace($search, $replace, $alias_txt);
        $res['alias'] = $alias; //又名

        $time_html = cut($data, '片长:</span>', '<br/>');
        $time_txt = strip_tags($time_html);
        $time = str_replace($search, $replace, $time_txt);
        $res['time'] = $time; //片长

        $imdb_html = cut($data, 'IMDb:</span>', '<br>');
        $imdb_txt = strip_tags($imdb_html);
        $imdb = str_replace($search, $replace, $imdb_txt);
        $res['imdb'] = $imdb; //片长

	$res = json_encode($res, true);
	esc_html(_e($res));
	
	exit;
}

function automsgapp_callback()
{
	$appid = sanitize_text_field($_GET['appid']);
	$surl = 'https://apps.apple.com/cn/app/id' . $appid . '/';
	$response = wp_remote_get($surl);
	if ( is_array( $response ) && !is_wp_error($response) && $response['response']['code'] == '200' ) {
		$data = $response['body'];
	}
	$search = array(" ", "　", "\n", "\r", "\t");
	$replace = array("", "", "", "", "");
	$data_1 = cut($data, 'product-hero">', '<style>');
	// $data_1 = json_decode($data_1, true);
	$res['id'] = $appid;
	// $res['title'] = $data_1['name'];

	// $authors = $data_1['author'];
	// if (!empty($authors)) {
	// 	$__authors = '';
	// 	foreach ($authors as $author) {
	// 		$__authors .= sprintf(
	// 			'%1$s ',
	// 			$author['name']
	// 		);
	// 	} 
	// 	$res['author'] = $__authors;
	// }

	// $res['url'] = $data_1['url'];
    $url_txt = cut($data, 'property="og:url" content="', '" class="ember-view"');
    $url = str_replace($search, $replace, $url_txt);
	$res['url'] = $url;

	// $res['id'] = cut($res['url'], '/id', '');

	$res['logos'] = cut($data_1, 'srcset="', ' 1x');
    $res['logo'] = cut($data_1, ' 1x, ', ' 2x');

	// $res['logos'] = str_replace('subject/l/public','subject/s/public',$res['logo']); 

    $title_txt = cut($data, 'app-header__title">', '<span');
    $title = str_replace($search, $replace, $title_txt);
	$res['title'] = $title;

    $slogan_txt = cut($data, 'app-header__subtitle">', '</h2>');
    $slogan = str_replace($search, $replace, $slogan_txt);
	$res['slogan'] = $slogan;

    $identity_txt = cut($data, 'app-header__identity">', '</h2>');
    $identity_a = preg_replace("/<a[^>]*href=[^>]*>|<\/[^a]*a[^>]*>/i","",$identity_txt);
	$identity = str_replace($search, $replace, $identity_a);
	$res['identity'] = $identity;


	
	$score_txt = cut($data, 'star-rating__count">', ' • ');
	$score = str_replace($search, $replace, $score_txt);
	$res['score'] = $score;

	$genre_txt = cut($data, '类別</dt>', '</div>');
    // preg_match_all("/<dd[^>]*>[^<]*</dd>/i",$genre_txt,$genre_dd);
    $genre_dd = preg_replace("/<dd[^>]*>|<\/dd>/i","",$genre_txt);
    // preg_match_all("/<a[^>]*>[^<]*</a>/i",$genre_dd,$genre_a);
    $genre_a = preg_replace("/<a[^>]*>|<\/a>/i","",$genre_dd);
    $genre = str_replace($search, $replace, $genre_a);
	$res['genre'] = $genre;

    $price_txt = cut($data, '价格</dt>', '</div>');
    $price_dd = preg_replace("/<dd[^>]*>|<\/dd>/i","",$price_txt);
    $price = str_replace($search, $replace, $price_dd);
	$res['price'] = $price;

    $version_txt = cut($data, 'latest__version">版本', '</p>');
    $version = str_replace($search, $replace, $version_txt);
	$res['version'] = $version;

    $upload_txt = cut($data, 'whats-new__latest">', '</div>');
    // $match='/<time[^>]*>|<\/time>/i';//a标签正则匹配
    preg_match_all("/<time[^>]*>(.*)<\/time>/i",$upload_txt,$upload_time); //开始匹配
    // $upload_time = preg_replace("/<time[^>]*>|<\/time>/i","",$upload_txt);
    $upload = str_replace($search, $replace, $upload_time['1']['0']);
	$res['upload'] = $upload;

    $size_txt = cut($data, '大小</dt>', '</div>');
    $size_dd = preg_replace("/<dd[^>]*>|<\/dd>/i","",$size_txt);
    $size = str_replace($search, $replace, $size_dd);
	$res['size'] = $size;

	// $description = cut($data,'class="intro">','</p>');
	// $description = explode('<p>',$description)[1];
	// if($description==''){
	//   $description ='未知';
	// }
	// $res['description'] =$description;

	$res = json_encode($res, true);
	esc_html(_e($res));
	
	exit;
}