<?php
if (!defined('ABSPATH')) exit;

add_action('acf/init', 'beebee_acf_op_init');
function beebee_acf_op_init()
{
    if (function_exists('acf_add_options_sub_page')) {
        $acf_add_options_sub_page = acf_add_options_sub_page(
            array(
                'page_title' => '主题设置',
                'menu_slug' => 'appbeebee-theme-setting',
                'menu_title' => '主题设置',
                'capability'      => 'manage_options',
                'position' => '',
                'parent_slug'     => 'appbeebee',
                'icon_url' => '',
                'redirect' => true,
                'post_id' => 'options',
                'autoload' => false,
                'update_button'   => '保存',
                'updated_message' => '设置已保存！'
            )
        );
    }
}



add_filter('manage_beebee_library_posts_columns', 'my_edit_beebee_library_columns');
function my_edit_beebee_library_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => esc_html(__('标题')),
        'id' => esc_html(__('ID')),
        'quottitle' => esc_html(__('音频标题')),
        'quotsinger' => esc_html(__('音频来源')),
        'quotcover' => esc_html(__('音频封面')),
        'quotsrc' => esc_html(__('音频试听')),
        'beebee_library_cats' => esc_html(__('分类')),
        'date' => esc_html(__('Date'))
    );
    return $columns;
}

add_action('admin_head', 'add_beebee_library_css');
function add_beebee_library_css()
{
?>
    <style type="text/css">

.table-view-list .column-title {
    width: 15%;

}
        .table-view-list .column-quotcover {
            width: 8%;
        }

        .table-view-list .column-quotsrc {
            width: 20%;
        }

        .table-view-list .column-quottitle {
            width: 12%;
        }

        .table-view-list .column-id {
            width: 6%;
        }

        .table-view-list .column-beebee_library_cats {
            width: 6%;
        }

        .table-view-list .column-date {
            width: 10%;
        }

        .table-view-list ol {
            margin: 0 0 0 1.2em;
        }

        .table-view-list .audio {
            width: 100%;
        }
    </style>
<?php
}

add_action('manage_beebee_library_posts_custom_column', 'my_manage_beebee_library_columns', 10, 2);
function my_manage_beebee_library_columns($column, $post_id)
{
    global $post;

    if (have_rows('quotarea', $post_id)) :
        while (have_rows('quotarea', $post_id)) : the_row();
            $quot_title =  get_sub_field('title');
            $quot_singer =  get_sub_field('singer');
            $quot_cover =  get_sub_field('coverImgUrl');
            $quot_src =  get_sub_field('src');
        // Do something...
        endwhile;
    else :
    // no rows found
    endif;

    switch ($column) {
        case 'id':
            $id = get_the_ID($post_id);
            esc_html(printf(__('%s'),  $id));
            break;

        case 'quottitle':
            if (empty($quot_title))
            esc_html(_e('-'));
            else
            esc_html(printf(__('%s'),  $quot_title));
            break;
        case 'quotsinger':
            if (empty($quot_singer))
            esc_html(_e('-'));
            else
            esc_html(printf(__('%s'),  $quot_singer));
            break;
        case 'quotcover':
            if (empty($quot_cover))
            esc_html(_e('未知'));
            else
            esc_html(printf(__('<div style="width:54px;height:54px;border-radius: 3px;background-image:url(%s);background-size: cover;background-repeat: no-repeat;background-position: center;"></div>'), $quot_cover));
            break;
        case 'quotsrc':
            if (empty($quot_src))
            esc_html(_e('未知'));
            else
            esc_html(printf(__('<audio controls remaining="no" src="%s" class="audio"></audio>'), $quot_src));
            break;
        case 'beebee_library_cats':
            $terms = get_the_terms($post_id, 'beebee_library_cats');
            if (!empty($terms)) {
                $out = array();
                foreach ($terms as $term) {
                    $out[] = sprintf(
                        '<a href="%s">%s</a>',
                        esc_url(add_query_arg(array('post_type' => $post->post_type, 'beebee_library_cats' => $term->slug), 'edit.php')),
                        esc_html(sanitize_term_field('name', $term->name, $term->term_id, 'beebee_library_cats', 'display'))
                    );
                }
                esc_html(_e(join(', ', $out)));
            } else {
                esc_html(_e('没有分类'));
            }
            break;
        default:
            break;
    }
}

//添加一个新的列 ID
function ssid_column($cols)
{
    $cols['ssid'] = 'ID';
    return $cols;
}
//显示 ID
function ssid_value($column_name, $id)
{
    if ($column_name == 'ssid')
    esc_html(_e($id));
}
function ssid_return_value($value, $column_name, $id)
{
    if ($column_name == 'ssid')
        $value = $id;
    return $value;
}

function rating_column($cols)
{

    $cols = array(
        'cb' => '<input type="checkbox" />',
        'author' => esc_html(__('作者')),
        'comment' => esc_html(__('评论')),
        'rating' => esc_html(__('评星')),
        'response' => esc_html(__('回复至')),
        'date' => esc_html(__('提交于')),
        'type' => esc_html(__('类型')),
        'ssid' => esc_html(__('ID')),
    );
    return $cols;
}
//显示 ID
function rating_value($column_name, $id)
{
    $rating = get_comment_meta($id, 'rating', true);
    if ($rating == 1) {
        $ratingtxt = '★';
    } else if ($rating == 2) {
        $ratingtxt = '★★';
    } else if ($rating == 3) {
        $ratingtxt = '★★★';
    } else if ($rating == 4) {
        $ratingtxt = '★★★★';
    } else if ($rating == 5) {
        $ratingtxt = '★★★★★';
    } else {
        $ratingtxt = '没有评分';
    }
    if ($column_name == 'rating')

    esc_html(_e($ratingtxt));
}
function ssid_add()
{
    add_filter('manage_posts_columns', 'ssid_column');
    add_action('manage_posts_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_pages_columns', 'ssid_column');
    add_action('manage_pages_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_media_columns', 'ssid_column');
    add_action('manage_media_custom_column', 'ssid_value', 10, 2);
    add_filter('manage_link-manager_columns', 'ssid_column');
    add_action('manage_link_custom_column', 'ssid_value', 10, 2);
    add_action('manage_edit-link-categories_columns', 'ssid_column');
    add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);
    foreach (get_taxonomies() as $taxonomy) {
        add_action("manage_edit-${taxonomy}_columns", 'ssid_column');
        add_filter("manage_${taxonomy}_custom_column", 'ssid_return_value', 10, 3);
    }
    add_action('manage_users_columns', 'ssid_column');
    add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);
    add_action('manage_edit-comments_columns', 'ssid_column');
    add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
    add_action('manage_edit-comments_columns', 'rating_column');
    add_action('manage_comments_custom_column', 'rating_value', 1, 2);
}
add_action('admin_init', 'ssid_add');
