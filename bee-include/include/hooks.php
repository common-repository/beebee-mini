<?php
if (!defined('ABSPATH')) exit;

add_filter('rest_prepare_post', function ($data, $post, $request) {
    $_data = $data->data;
    $post_id = $post->ID;
    if (is_miniprogram() || is_debug()) {
        $post_date = $post->post_date;
        $_data["newdate"] = datetime_before($post_date);
        $post_type = $post->post_type;
        $thumbnail = apply_filters('post_thumbnail', $post_id);
        $_data["meta"]["newthumbnail"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, '' );
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
    }
    $data->data = $_data;
    return $data;
}, 10, 3);


if (defined('LLMS_PLUGIN_FILE')) {
    add_filter('llms_rest_prepare_course_object_response', function ($data, $course, $request) {

        $course_id = $course->get('id');
        $course    = new LLMS_Course($course_id);
        $sections  = $course->get_sections();
        $courses_lessons = $course->get_lessons('ids');
        // array_unshift( $courses_lessons, '' );
        // 				unset( $courses_lessons[0] );
        $all_lesson_count  = 0;

        $current_lesson    = 0;
        if (!empty($courses_lessons)) {
            $all_lesson_count = count($courses_lessons);
        }

        if ($sections) {
            $coursea = array();
            foreach ($sections as $section) {
                $sectiona = array();
                $sectiona['id'] = $section->get('id');
                $sectiona['title'] = get_the_title($section->get('id'));
                $sectiona['order'] = $section->order;
                $lessons = $section->get_lessons();
                foreach ($lessons as $lesson) {
                    $lessona = array();

                    $lessona['id'] = $lesson->id;
                    $lessona['title'] = $lesson->title;
                    $lessona['thumbnail'] = apply_filters('post_thumbnail', $lesson->id);
                    $lessona['type'] = $lesson->type;
                    $lessona['free_lesson'] = $lesson->free_lesson;
                    $lessona['order'] = $lesson->order;

                    $lessona['views'] = (int)get_post_meta($lesson->id, "views", true);

                    $number_of_lessons = 1;
                    foreach ($courses_lessons as $courses_lesson) {

                        if ($lessona['id'] == $courses_lesson) {
                            $current_lesson = $number_of_lessons;
                        }
                        $number_of_lessons++;
                    }

                    $lessona['current'] = $current_lesson;

                    $sectiona['lessons'][] = $lessona;
                    $sectiona['total_lessons'] = count($lessons);
                }
                $coursea['sections'][] = $sectiona;
                $coursea['total_sections'] = count($sections);
            }
        }
        $data['content']['rendered'] = $coursea;
        $data['content']['library_id'] = get_field('bee_to_library', $course_id);
        $data['content']['course_state'] = get_field('state', $course_id);
        $data['content']['all_lesson_count'] = $all_lesson_count;
        $data['content']['showsectionmsg'] = get_field('showsectionmsg', $course_id);

        $data['content']['section_name'] = get_field('section_name', $course_id);
        $data['content']['lesson_name'] = get_field('lesson_name', $course_id);
        $data['content']['showthumb'] = get_field('showthumb', $course_id);
        $data['content']['showname'] = get_field('showname', $course_id);
        $data['content']['showtitle'] = get_field('showtitle', $course_id);
        return $data;
    }, 10, 3);

    add_filter('llms_rest_prepare_lesson_object_response', function ($data, $lesson, $request) {
        $lesson_id = $lesson->get('id');

        $lesson    = new LLMS_Lesson($lesson_id);
        $course_id = $lesson->get_parent_course();
        $course    = new LLMS_Course($course_id);
        $sections  = $course->get_sections();
        $section_id = $lesson->get_parent_section();
        $section    = new LLMS_Course($section_id);
        // $lesson_id = get_the_ID();
        $prev_id = $lesson->get_previous_lesson();
        $next_id = $lesson->get_next_lesson();

        $prevlesson = new LLMS_Lesson($prev_id);
        $nextlesson = new LLMS_Lesson($next_id);

        $prev_section_id = $prevlesson->get_parent_section();
        $next_section_id = $nextlesson->get_parent_section();
        $prevsectionlesson = new LLMS_Lesson($prev_section_id);
        $nextsectionlesson = new LLMS_Lesson($next_section_id);

        $prevlast = false;
        $nextlast = false;


        $courses_lessons = $course->get_lessons('ids');
        array_unshift($courses_lessons, '');
        unset($courses_lessons[0]);
        $all_lesson_count  = 0;
        $number_of_lessons = 1;
        $current_lesson    = 0;
        if (!empty($courses_lessons)) {

            $all_lesson_count = count($courses_lessons);

            foreach ($courses_lessons as $courses_lesson) {

                if ($lesson_id == $courses_lesson) {
                    $current_lesson = $number_of_lessons;
                }
                $number_of_lessons++;
            }
        }

        $library_id = get_field('bee_to_library', $course_id)[0] ? get_field('bee_to_library', $course_id)[0] : 0;
        

        if ($prev_id) {
            $data['content']['prev_current_lesson'] = ($current_lesson - 1);
        } else {
            $prev_id = $library_id;
            $prevlast = true;
        }

        if ($next_id) {
            $data['content']['next_current_lesson'] = ($current_lesson + 1);
        } else {
            $next_id = $library_id;
            $nextlast = true;
        }
        $data['content']['prevlast'] = $prevlast;
        $data['content']['nextlast'] = $nextlast;
        $data['content']['course_id'] = $course_id;
        if ($library_id != 0) {
            $data['content']['library']['id']  = $library_id;
            $data['content']['library']['thumbnail']  = apply_filters('post_thumbnail', $library_id);
            $data['content']['library']['title']  = html_entity_decode(get_post($library_id)->post_title);
            $data['content']['library']['excerpt']  = html_entity_decode(get_post($library_id)->post_excerpt);
        }
        $data['content']['previous_id'] = $prev_id;
        $data['content']['prev_lesson_order'] = $prevlesson->order;
        $data['content']['prev_section_order'] = $prevsectionlesson->order;
        // $data['content']['prev_current_lesson'] = $prev_current_lesson;
        $data['content']['prev_lesson_title'] = $prevlesson->title;
        $data['content']['next_id'] = $next_id;
        $data['content']['next_lesson_order'] = $nextlesson->order;
        $data['content']['next_section_order'] = $nextsectionlesson->order;
        // $data['content']['next_current_lesson'] = $next_current_lesson;
        $data['content']['next_lesson_title'] = $nextlesson->title;
        $data['content']['lesson_order'] = $lesson->order;
        $data['content']['section_order'] = $section->order;
        $data['content']['free_lesson'] = $lesson->free_lesson;
        $data['content']['all_lesson_count'] = $all_lesson_count;
        $data['content']['current_lesson'] = $current_lesson;
        $data['content']['showsectionmsg'] = get_field('showsectionmsg', $course_id);
        $data['content']['section_name'] = get_field('section_name', $course_id);
        $data['content']['lesson_name'] = get_field('lesson_name', $course_id);
        $data['content']['showthumb'] = get_field('showthumb', $course_id);
        $data['content']['showname'] = get_field('showname', $course_id);
        $data['content']['showtitle'] = get_field('showtitle', $course_id);
        $data['content']['acf']["cntctrl"] = get_field('cntctrl', $lesson_id);
        $data['content']['acf']["before_entry"] = get_field('before_entry', $lesson_id);
        $data['content']['acf']["after_entry"] = get_field('after_entry', $lesson_id);
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
        $data['content']["islike"] = apply_filters('miniprogram_commented', $lesson_id, $user_id, 'like');
        $data['content']["likes"] = apply_filters('comment_type_count', $lesson_id, 'like');
        $data['content']["post_likes"] = apply_filters('comment_type_list', $lesson_id, 'like');


        return $data;
    }, 10, 3);
}

function rest_prepare_fields($data, $post, $request)
{
    $isinclude = '';
    $options = apply_filters('getmiss', $isinclude);
    if (!defined($options)) {
        return;
    }
    $_data = $data->data;
    $post_id = $post->ID;
    if (is_miniprogram() || is_debug()) {
        $post_date = $post->post_date;
        $post_modified = $post->post_modified;
        $post_type = $post->post_type;
        $author_id = $post->post_author;
        $author_avatar = get_user_meta($author_id, 'avatar', true);
        $taxonomies = get_object_taxonomies($_data['type']);
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
        $_data["id"]  = $post_id;
        $_data["options"] = $options;
        $_data["date"] = $post_date;
        $_data["modified"] = $post_modified;
        $_data["newdate"] = datetime_before($post_date);
        $_data["week"] = get_wp_post_week($post_date);
        unset($_data['author']);
        $_data["author"]["id"] = $author_id;
        $_data["author"]["name"] = get_the_author_meta('nickname', $author_id);
        if ($author_avatar) {
            $_data["author"]["avatar"] = $author_avatar;
        } else {
            $_data["author"]["avatar"] = get_avatar_url($author_id);
        }
        $_data["author"]["description"] = get_the_author_meta('description', $author_id);
        if (get_post_meta($post_id, "source", true)) {
            $_data["meta"]["source"] = get_post_meta($post_id, "source", true);
        }
        $thumbnail = apply_filters('post_thumbnail', $post_id);
        $_data["meta"]["thumbnail"] = $thumbnail;
        if($post_type=='beebee_library') {
            $_data["meta"]["newthumbnail"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, 'active');
            $_data["meta"]["singleimg"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, 'single');
            $_data["meta"]["fullimg"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, 'download');
        } else {
            // $_data["meta"]["thumbnail"] = $thumbnail;
            $_data["meta"]["newthumbnail"] = apply_filters('bee_mp_thumbnail_url', $thumbnail,[], $post_type, '' );
        }
        // $_data["meta"]["thumbnail"] = apply_filters('post_thumbnail', $post_id);
        $_data["meta"]["views"] = $post_views;
        $_data["meta"]["count"] = mp_count_post_content_text_length(wp_strip_all_tags($post_content));
        $_data["comments"] = apply_filters('comment_type_count', $post_id, 'comment');
        $_data["isfav"] = apply_filters('miniprogram_commented', $post_id, $user_id, 'fav');
        $_data["favs"] = apply_filters('comment_type_count', $post_id, 'fav');
        $_data["islike"] = apply_filters('miniprogram_commented', $post_id, $user_id, 'like');
        $_data["likes"] = apply_filters('comment_type_count', $post_id, 'like');

        $headcnt_gallery =  get_field('headcnt_gallery', $post_id);
        if ($headcnt_gallery) {
            $__headcnt_gallery_new = array();
            $__headcnt_gallery_single = array();
            $__headcnt_gallery_full = array();
            foreach ($headcnt_gallery as $key => $app_k) {
                // $__headcnt_gallery_new[] = array(
                //     $key => $app_k
                // ); 
                $__headcnt_gallery_new[$key] = apply_filters('bee_mp_thumbnail_url', $app_k,[], 'beebee_library', 'active');
                $__headcnt_gallery_single[$key] = apply_filters('bee_mp_thumbnail_url', $app_k,[], 'beebee_library', 'single');
                $__headcnt_gallery_full[$key] = apply_filters('bee_mp_thumbnail_url', $app_k,[], 'beebee_library', 'download');
            }
            $_data['acf']["headcnt_gallery_newthumbnail"] = $__headcnt_gallery_new;
            $_data['acf']["headcnt_gallery_singleimg"] = $__headcnt_gallery_single;
            $_data['acf']["headcnt_gallery_fullimg"] = $__headcnt_gallery_full;

        }

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

        if (have_rows('mode_single', 'option')) :
            while (have_rows('mode_single', 'option')) : the_row();
                $quot_global =  get_sub_field('page_single')['quot'];
            // Do something...
            endwhile;
        else :
        // no rows found
        endif;
        if($quot_global){
        if ($quot_global['radiotype'] == '1') {
            $bee_quot_radio = get_field('bee_quot_radio', $post_id);
        } else if ($quot_global['radiotype'] == '2') {
            $bee_quot_radio = $quot_global['quotradio'];
        }
       
        // $bee_quot_radio = get_field( 'bee_quot_radio', $post_id );    
       
        if ($bee_quot_radio != '') {
            $p1 = '/%quotcnt%/';
            if (get_field('bee_quot_via', $post_id) != '') {
                $r1 = get_field('bee_quot_cnt', $post_id) . '——摘自' . get_field('bee_quot_via', $post_id);
            } else if ($bee_to_library[0] != false) {
                $r1 = get_field('bee_quot_cnt', $post_id) . '——摘自' . $_data['acf']["bee_to_library"][0]['bee_library_author'] . '' . $_data['acf']["bee_to_library"][0]['bee_library_title'] . '。';
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



        // $_data["bee_to_library"] = get_field( 'bee_to_library', $post_id );

        if ($post_type == 'beebee_topic') {
            $_data["post_likes"] = apply_filters('comment_type_list', $post_id, 'like');
        }

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
        $_data["title"]["rendered"] = html_entity_decode($post_title);
        $_data["excerpt"]["rendered"] = html_entity_decode(wp_strip_all_tags($post_excerpt));

        if (isset($request['id'])) {
            if (!update_post_meta($post_id, 'views', ($post_views + 1))) {
                add_post_meta($post_id, 'views', 1, true);
            }
            $media_cover = get_post_meta($post_id, 'cover', true);
            $media_author = get_post_meta($post_id, 'author', true);
            $media_title = get_post_meta($post_id, 'title', true);
            $media_video = get_post_meta($post_id, 'video', true);
            $media_audio = get_post_meta($post_id, 'audio', true);
            if (wp_miniprogram_option('mediaon') && ($media_video || $media_audio)) {
                if ($media_cover) {
                    $_data["media"]['cover'] = $media_cover;
                } else {
                    $_data["media"]['cover'] = apply_filters('post_thumbnail', $post_id);
                }
                if ($media_author) {
                    $_data["media"]['author'] = $media_author;
                }
                if ($media_title) {
                    $_data["media"]['title'] = $media_title;
                }
                if ($media_video) {
                    $_data["media"]['video'] = $media_video;
                }
                if ($media_audio) {
                    $_data["media"]['audio'] = $media_audio;
                }
            }
            if ($post_type == 'beebee_library') {
                // 小鱼哥 增加 评分字段
                $args = array(
                    'post_id' => get_the_ID(),
                    'status'  => 'approve'
                );

                $comments = get_comments($args);
                $ratings  = array();
                $count    = 0;
                $count_rating_5    = 0;
                $count_rating_4    = 0;
                $count_rating_3    = 0;
                $count_rating_2    = 0;
                $count_rating_1    = 0;

                foreach ($comments as $comment) {

                    $rating = get_comment_meta($comment->comment_ID, 'rating', true);

                    if (!empty($rating)) {
                        $ratings[] = absint($rating);
                        $count++;
                    }
                    if ($rating == '5') {
                        $count_rating_5++;
                    }
                    if ($rating == '4') {
                        $count_rating_4++;
                    }
                    if ($rating == '3') {
                        $count_rating_3++;
                    }
                    if ($rating == '2') {
                        $count_rating_2++;
                    }
                    if ($rating == '1') {
                        $count_rating_1++;
                    }
                }

                if (0 != count($ratings)) {

                    $avg = (array_sum($ratings) / count($ratings));

                    $rating_avg = array(
                        'avg'   => round($avg, 1),
                        'count' => $count,
                        'count5' => $count_rating_5,
                        'count4' => $count_rating_4,
                        'count3' => $count_rating_3,
                        'count2' => $count_rating_2,
                        'count1' => $count_rating_1
                    );
                }
                $_data['rating_avg'] = $rating_avg;
            }
            if (is_smart_miniprogram()) {
                $custom_keywords = get_post_meta($post_id, "keywords", true);
                if (!$custom_keywords) {
                    $custom_keywords = "";
                    $tags = wp_get_post_tags($post_id);
                    foreach ($tags as $tag) {
                        $custom_keywords = $custom_keywords . $tag->name . ",";
                    }
                }
                $_data["smartprogram"]["title"] = $_data["title"]["rendered"] . '-' . get_bloginfo('name');
                $_data["smartprogram"]["keywords"] = $custom_keywords;
                $_data["smartprogram"]["description"] = $_data["excerpt"]["rendered"];
                $_data["smartprogram"]["image"] = apply_filters('post_images', $post_id);
                $_data["smartprogram"]["visit"] = array('pv' => $post_views);
                $_data["smartprogram"]["comments"] =  apply_filters('comment_type_count', $post_id, 'comment');
                $_data["smartprogram"]["likes"] = apply_filters('comment_type_count', $post_id, 'like');
                $_data["smartprogram"]["collects"] = apply_filters('comment_type_count', $post_id, 'fav');
            }

            if (!$media_video) {
                $_data["content"]["rendered"] = apply_filters('the_video_content', $post_content);
            }
            // $_data["content"]["rendered"] = 'fffffff';
            $_data["post_likes"] = apply_filters('comment_type_list', $post_id, 'like');
            // $_data["post_favs"] = apply_filters( 'comment_type_list', $post_id, 'fav' );
            // $_data["post_likes"] = apply_filters( 'comment_type_list', $post_id, 'like' );
            if (wp_miniprogram_option("prevnext")) {

                foreach (get_taxonomies() as $taxonomy) {
                    $category = get_the_terms($post_id, $taxonomy);
                    $next = get_next_post($category[0]->term_id, '', $taxonomy);
                    $previous = get_previous_post($category[0]->term_id, '', $taxonomy);
                }

                if (!empty($next->ID)) {
                    $_data["next_post"]["id"] = $next->ID;
                    $_data["next_post"]["title"]["rendered"] = $next->post_title;
                    $_data["next_post"]["thumbnail"] = apply_filters('post_thumbnail', $next->ID);
                    $_data["next_post"]["views"] = (int)get_post_meta($next->ID, "views", true);
                }
                if (!empty($previous->ID)) {
                    $_data["prev_post"]["id"] = $previous->ID;
                    $_data["prev_post"]["title"]["rendered"] = $previous->post_title;
                    $_data["prev_post"]["thumbnail"] = apply_filters('post_thumbnail', $previous->ID);
                    $_data["prev_post"]["views"] = (int)get_post_meta($previous->ID, "views", true);
                }
            }
        } else {

            if (!wp_miniprogram_option("post_content")) {
                unset($_data['content']);
            }
            if (wp_miniprogram_option("post_picture")) {
                $_data["pictures"] = apply_filters('post_images', $post_id);
            }
        }
    }


    if (is_miniprogram()) {
        unset($_data[$post_type . '_cats']);
        unset($_data[$post_type . '_state']);
        unset($_data['categories']);
        unset($_data['tags']);
        unset($_data["_edit_lock"]);
        unset($_data["_edit_last"]);
        unset($_data['featured_media']);
        unset($_data['ping_status']);
        unset($_data['template']);
        unset($_data['slug']);
        unset($_data['status']);
        unset($_data['modified_gmt']);
        unset($_data['post_format']);
        unset($_data['date_gmt']);
        unset($_data['guid']);
        unset($_data['curies']);
        unset($_data['modified']);
        unset($_data['status']);
        unset($_data['comment_status']);
        unset($_data['sticky']);
        unset($_data['_links']);
    } else {
        unset($_data['excerpt']);
        unset($_data['content']['rendered']);
        unset($_data['acf']);
    }
    $data->data = $_data;
    return $data;
}
