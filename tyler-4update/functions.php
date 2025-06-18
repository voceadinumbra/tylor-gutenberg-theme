<?php
// ******************* Add Libraries ****************** //
require_once get_template_directory() . '/editor/class-imagely-editor.php';
require_once('event-framework/lib/facebook/facebook.php');
require_once('event-framework/lib/twitter.php');
require_once('event-framework/lib/geocode.php');
require_once('event-framework/lib/simplexlsx.class.php');
require_once('event-framework/lib/recaptchalib.php');
require_once('event-framework/lib/api/functions.php');

include 'event-framework/event-framework.php';

add_filter('ef_theme_options_logo', 'tyler_set_theme_options_logo');

function tyler_set_theme_options_logo($url) {
    return get_template_directory_uri() . '/images/schemes/basic/logo.png';
}

// Display the logo based on selected Color Scheme
function tyler_set_theme_logo() {
    // Get Theme Options
    $ef_options = EF_Event_Options::get_theme_options();

    $color_scheme = empty($ef_options['ef_color_palette']) ? 'basic' : $ef_options['ef_color_palette'];

    if (!empty($ef_options['ef_logo']) && $ef_options['ef_logo'] != 'http://') {
        $logo_url = $ef_options['ef_logo'];
    } else {
        $logo_url = get_template_directory_uri() . "/images/schemes/$color_scheme/logo.png";
    }

    return $logo_url;
}

function tyler_setup_social_networks() {
    global $twitter, $facebook;

    $facebookAppID  = get_option('ef_facebook_rsvp_widget_appid');
    $facebookSecret = get_option('ef_facebook_rsvp_widget_secret');

    if (!empty($facebookAppID) && !empty($facebookSecret))
        $facebook = new Facebook(array(
            'appId'  => $facebookAppID,
            'secret' => $facebookSecret,
        ));

    $twitterAccessToken       = get_option('ef_twitter_widget_accesstoken');
    $twitterAccessTokenSecret = get_option('ef_twitter_widget_accesstokensecret');
    $twitterConsumerKey       = get_option('ef_twitter_widget_consumerkey');
    $twitterConsumerSecret    = get_option('ef_twitter_widget_consumersecret');

    if (!empty($twitterAccessToken) && !empty($twitterAccessTokenSecret) && !empty($twitterConsumerKey) && !empty($twitterConsumerSecret)) {
        $twitter = new TwitterAPIExchange(array(
            'oauth_access_token'        => $twitterAccessToken,
            'oauth_access_token_secret' => $twitterAccessTokenSecret,
            'consumer_key'              => $twitterConsumerKey,
            'consumer_secret'           => $twitterConsumerSecret
        ));
    }
}

add_action('init', 'tyler_setup_social_networks');

add_action('after_setup_theme', 'tyler_after_theme_setup');

function tyler_after_theme_setup() {

// ******************* Localizations ****************** //
    load_theme_textdomain('tyler', get_template_directory() . '/languages/');

// ******************* Add Custom Menus ****************** //    
    add_theme_support('menus');

// ******************* Add Post Thumbnails ****************** //
    add_theme_support('post-thumbnails');
    add_image_size('tyler-speaker', 212, 212);
    add_image_size('tyler-media', 400, 245);
    add_image_size('tyler-blog-home', 355, 236);
    add_image_size('tyler-blog-list', 306, 188);
    add_image_size('tyler-blog-detail', 642, 428);
    add_image_size('tyler-content', 978, 389);

// ******************* Add Navigation Menu ****************** //    
    register_nav_menu('primary', __('Navigation Menu', 'tyler'));
}

// ******************* Scripts and Styles ****************** //
add_action('wp_enqueue_scripts', 'tyler_enqueue_scripts');

function tyler_enqueue_scripts() {
    // Get Theme Options
    $ef_options      = EF_Event_Options::get_theme_options();
    // styles
    wp_enqueue_style('tyler-google-font', '//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700');
    wp_enqueue_style('tyler-bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style('tyler-blueimp-gallery', get_template_directory_uri() . '/css/blueimp-gallery.min.css');
    wp_enqueue_style('tyler-jquery-scrollpane', get_template_directory_uri() . '/css/jquery.scrollpane.css');
    wp_enqueue_style('tyler-icons', get_template_directory_uri() . '/css/icon.css');
    wp_enqueue_style('tyler-layout', get_template_directory_uri() . '/css/layout.css');
    wp_enqueue_style('tyler-layout-mobile', get_template_directory_uri() . '/css/layout-mobile.css');

    if (is_child_theme()) {
        wp_enqueue_style('tyler-parent-style', trailingslashit(get_template_directory_uri()) . 'style.css');
    }
    wp_enqueue_style('tyler-style', get_stylesheet_uri());

    // Color Schemes
    $color_scheme = empty($ef_options['ef_color_palette']) ? 'basic' : $ef_options['ef_color_palette'];
    if (isset($color_scheme) && $color_scheme != 'basic') {
        wp_enqueue_style($color_scheme . '-scheme', get_template_directory_uri() . '/css/schemes/' . $color_scheme . '/layout.css');
    }

    if (get_page_template_slug() == 'twitter.php') {
        wp_enqueue_script('jquery-tweet-machine', get_template_directory_uri() . '/js/tweetMachine.min.js', array('jquery'), false, true);
        wp_enqueue_script('tyler-twitter', get_template_directory_uri() . '/js/twitter.js', array('jquery-tweet-machine'));
    }

    // scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('tyler-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), false, true);
    wp_enqueue_script('tyler-blueimp-gallery', get_template_directory_uri() . '/js/blueimp-gallery.min.js', array('jquery'), false, true);
    wp_enqueue_script('tyler-jquery-mousewheel', get_template_directory_uri() . '/js/jquery.mousewheel.js', array('jquery'), false, true);
    wp_enqueue_script('tyler-jquery-jscrollpane', get_template_directory_uri() . '/js/jquery.jscrollpane.min.js', array('jquery'), false, true);
    wp_enqueue_script('tyler-script', get_template_directory_uri() . '/js/main.js', array('jquery'), false, true);

    // home
    if (is_home() || is_front_page()) {
        wp_enqueue_script('tyler-home', get_template_directory_uri() . '/js/home.js', array('jquery'), false, true);
        wp_enqueue_script('jquery-tweet-machine', get_template_directory_uri() . '/js/tweetMachine.min.js', array('jquery'), false, true);
    }

    // single
    if (is_singular())
        wp_enqueue_script('comment-reply');

    // session
    if (is_singular(array('session'))) {
        if (!empty($ef_options['ef_add_this_pubid'])) {
            wp_enqueue_script('addthis', "//s7.addthis.com/js/300/addthis_widget.js#pubid={$ef_options['ef_add_this_pubid']}");
        }
    }

    // full schedule
    if (get_page_template_slug() == 'schedule.php') {
        wp_enqueue_script('tyler-schedule', get_template_directory_uri() . '/js/schedule.js', array('jquery'));
    }
}

add_action('admin_enqueue_scripts', 'tyler_admin_enqueue_scripts');

add_action('wp_head', 'tyler_twitter_template_hash');

function tyler_twitter_template_hash() {
    if (get_page_template_slug() == 'twitter.php') {
        ?>
        <script type="text/javascript">
            var tyler_twitter_hash = '<?php echo get_option('ef_twitter_widget_twitterhash'); ?>';
        </script>
        <?php
    }
}

function tyler_admin_enqueue_scripts($hook) {
    global $post_type;

    if (in_array($hook, array('post.php', 'post-new.php'))) {
        if ($post_type == 'session') {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/css/admin/smoothness/jquery-ui-1.10.3.custom.min.css');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_style('tyler-sortable', get_template_directory_uri() . '/css/sortable.css');
        } else if (get_page_template_slug() == 'speakers.php') {
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('tyler-page-speakers-full-screen', get_template_directory_uri() . '/js/page-speakers-full-screen.js');
            wp_enqueue_style('tyler-sortable', get_template_directory_uri() . '/css/sortable.css');
            wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/css/admin/smoothness/jquery-ui-1.10.3.custom.min.css');
        }
    }
}

// ******************* Ajax ****************** //

add_action('wp_ajax_nopriv_get_tweets', 'tyler_ajax_get_tweets');
add_action('wp_ajax_get_tweets', 'tyler_ajax_get_tweets');

function tyler_ajax_get_tweets() {
    global $twitter;
    $twitterhash = get_option('ef_twitter_widget_twitterhash');
    $tweets      = array();

    if (isset($twitter) && !empty($twitterhash)) {
        $url           = 'https://api.twitter.com/1.1/search/tweets.json';
        $getfield      = "?q={$_GET['queryParams']['q']}&count={$_GET['queryParams']['count']}";
        $requestMethod = 'GET';
        $store         = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest();
        $tweets        = json_decode($store);
    }

    echo json_encode($tweets->statuses);
    die;
}

add_action('wp_ajax_nopriv_get_schedule', array('EF_Session_Helper', 'fudge_ajax_get_schedule'));
add_action('wp_ajax_get_schedule', array('EF_Session_Helper', 'fudge_ajax_get_schedule'));


// ******************* Misc ****************** //

add_filter('manage_edit-speaker_columns', 'edit_speaker_columns');

function edit_speaker_columns($columns) {
    $new_columns = array(
        'cb'         => $columns['cb'],
        'title'      => $columns['title'],
        'menu_order' => __('Order', 'tyler'),
        'date'       => $columns['date'],
    );
    return $new_columns;
}

add_action('manage_posts_custom_column', 'edit_post_columns', 10, 2);

function edit_post_columns($column_name) {
    global $post;

    switch ($column_name) {
        case 'menu_order' :
            echo $post->menu_order;
            break;

        default:
    }
}

function getRelativeTime($date) {
    $diff = time() - strtotime($date);
    if ($diff < 60)
        return $diff . _n(' second', ' seconds', $diff, 'tyler') . __(' ago', 'tyler');
    $diff = round($diff / 60);
    if ($diff < 60)
        return $diff . _n(' minute', ' minutes', $diff, 'tyler') . __(' ago', 'tyler');
    $diff = round($diff / 60);
    if ($diff < 24)
        return $diff . _n(' hour', ' hours', $diff, 'tyler') . __(' ago', 'tyler');
    $diff = round($diff / 24);
    if ($diff < 7)
        return $diff . _n(' day', ' days', $diff, 'tyler') . __(' ago', 'tyler');
    $diff = round($diff / 7);
    if ($diff < 4)
        return $diff . _n(' week', ' weeks', $diff, 'tyler') . __(' ago', 'tyler');
    return __('on ', 'tyler') . date("F j, Y", strtotime($date));
}

add_filter('wp_nav_menu_items', 'tyler_wp_nav_menu_items', 10, 2);

function tyler_wp_nav_menu_items($items, $args) {
    if ($args->theme_location == 'primary' && is_active_widget(false, false, 'tyler_registration') && get_option('tyler_registration_widget_showtopmenu') == 1)
        $items .= '<li class="menu-item register"><a href="' . home_url('/') . '#tile_registration">' . __('Register', 'tyler') . '</a></li>';
    return $items;
}

function tyler_get_video_gallery_attribute($video_type, $video_code) {
    $ret = '';

    switch ($video_type) {
        case 'youtube':
            $ret = "type='text/html' href='https://www.youtube.com/watch?v=$video_code' data-youtube='$video_code'";
            break;
        case 'vimeo':
            $ret = "type='text/html' href='https://vimeo.com/$video_code' data-vimeo='$video_code'";
            break;
    }

    return $ret;
}

################################################################
/**
 * Retrieve adjacent post link.
 *
 * Can either be next or previous post link.
 *
 * Based on get_adjacent_post() from wp-includes/link-template.php
 *
 * @param array $r Arguments.
 * @param bool $previous Optional. Whether to retrieve previous post.
 * @return array of post objects.
 */

function tyler_get_adjacent_post_plus($r, $previous = true) {
    global $post, $wpdb;

    extract($r, EXTR_SKIP);

    if (empty($post))
        return null;

//	Sanitize $order_by, since we are going to use it in the SQL query. Default to 'post_date'.
    if (in_array($order_by, array('post_date', 'post_title', 'post_excerpt', 'post_name', 'post_modified'))) {
        $order_format = '%s';
    } elseif (in_array($order_by, array('ID', 'post_author', 'post_parent', 'menu_order', 'comment_count'))) {
        $order_format = '%d';
    } elseif ($order_by == 'custom' && !empty($meta_key)) { // Don't allow a custom sort if meta_key is empty.
        $order_format = '%s';
    } elseif ($order_by == 'numeric' && !empty($meta_key)) {
        $order_format = '%d';
    } else {
        $order_by     = 'post_date';
        $order_format = '%s';
    }

//	Sanitize $order_2nd. Only columns containing unique values are allowed here. Default to 'post_date'.
    if (in_array($order_2nd, array('post_date', 'post_title', 'post_modified'))) {
        $order_format2 = '%s';
    } elseif (in_array($order_2nd, array('ID'))) {
        $order_format2 = '%d';
    } else {
        $order_2nd     = 'post_date';
        $order_format2 = '%s';
    }

//	Sanitize num_results (non-integer or negative values trigger SQL errors)
    $num_results = intval($num_results) < 2 ? 1 : intval($num_results);

//	Queries involving custom fields require an extra table join
    if ($order_by == 'custom' || $order_by == 'numeric') {
        $current_post = get_post_meta($post->ID, $meta_key, TRUE);
        $order_by     = ($order_by === 'numeric') ? 'm.meta_value+0' : 'm.meta_value';
        $meta_join    = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $meta_key);
    } elseif ($in_same_meta) {
        $current_post = $post->$order_by;
        $order_by     = 'p.' . $order_by;
        $meta_join    = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $in_same_meta);
    } else {
        $current_post = $post->$order_by;
        $order_by     = 'p.' . $order_by;
        $meta_join    = '';
    }

//	Get the current post value for the second sort column
    $current_post2 = $post->$order_2nd;
    $order_2nd     = 'p.' . $order_2nd;

//	Get the list of post types. Default to current post type
    if (empty($post_type))
        $post_type = "'$post->post_type'";

//	Put this section in a do-while loop to enable the loop-to-first-post option
    do {
        $join                = $meta_join;
        $excluded_categories = $ex_cats;
        $included_categories = $in_cats;
        $excluded_posts      = $ex_posts;
        $included_posts      = $in_posts;
        $in_same_term_sql    = $in_same_author_sql  = $in_same_meta_sql    = $ex_cats_sql         = $in_cats_sql         = $ex_posts_sql        = $in_posts_sql        = '';

//		Get the list of hierarchical taxonomies, including customs (don't assume taxonomy = 'category')
        $taxonomies = array_filter(get_post_taxonomies($post->ID), "is_taxonomy_hierarchical");

        if (($in_same_cat || $in_same_tax || $in_same_format || !empty($excluded_categories) || !empty($included_categories)) && !empty($taxonomies)) {
            $cat_array    = $tax_array    = $format_array = array();

            if ($in_same_cat) {
                $cat_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
            }
            if ($in_same_tax && !$in_same_cat) {
                if ($in_same_tax === true) {
                    if ($taxonomies != array('category'))
                        $taxonomies = array_diff($taxonomies, array('category'));
                } else
                    $taxonomies = (array) $in_same_tax;
                $tax_array  = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
            }
            if ($in_same_format) {
                $taxonomies[] = 'post_format';
                $format_array = wp_get_object_terms($post->ID, 'post_format', array('fields' => 'ids'));
            }

            $join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy IN (\"" . implode('", "', $taxonomies) . "\")";

            $term_array       = array_unique(array_merge($cat_array, $tax_array, $format_array));
            if (!empty($term_array))
                $in_same_term_sql = "AND tt.term_id IN (" . implode(',', $term_array) . ")";

            if (!empty($excluded_categories)) {
//				Support for both (1 and 5 and 15) and (1, 5, 15) delimiter styles
                $delimiter           = ( strpos($excluded_categories, ',') !== false ) ? ',' : 'and';
                $excluded_categories = array_map('intval', explode($delimiter, $excluded_categories));
//				Three category exclusion methods are supported: 'strong', 'diff', and 'weak'.
//				Default is 'weak'. See the plugin documentation for more information.
                if ($ex_cats_method === 'strong') {
                    $taxonomies    = array_filter(get_post_taxonomies($post->ID), "is_taxonomy_hierarchical");
                    if (function_exists('get_post_format'))
                        $taxonomies[]  = 'post_format';
                    $ex_cats_posts = get_objects_in_term($excluded_categories, $taxonomies);
                    if (!empty($ex_cats_posts))
                        $ex_cats_sql   = "AND p.ID NOT IN (" . implode($ex_cats_posts, ',') . ")";
                } else {
                    if (!empty($term_array) && !in_array($ex_cats_method, array('diff', 'differential')))
                        $excluded_categories = array_diff($excluded_categories, $term_array);
                    if (!empty($excluded_categories))
                        $ex_cats_sql         = "AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
                }
            }

            if (!empty($included_categories)) {
                $in_same_term_sql    = ''; // in_cats overrides in_same_cat
                $delimiter           = ( strpos($included_categories, ',') !== false ) ? ',' : 'and';
                $included_categories = array_map('intval', explode($delimiter, $included_categories));
                $in_cats_sql         = "AND tt.term_id IN (" . implode(',', $included_categories) . ")";
            }
        }

//		Optionally restrict next/previous links to same author		
        if ($in_same_author)
            $in_same_author_sql = $wpdb->prepare("AND p.post_author = %d", $post->post_author);

//		Optionally restrict next/previous links to same meta value
        if ($in_same_meta && $r['order_by'] != 'custom' && $r['order_by'] != 'numeric')
            $in_same_meta_sql = $wpdb->prepare("AND m.meta_value = %s", get_post_meta($post->ID, $in_same_meta, TRUE));

//		Optionally exclude individual post IDs
        if (!empty($excluded_posts)) {
            $excluded_posts = array_map('intval', explode(',', $excluded_posts));
            $ex_posts_sql   = " AND p.ID NOT IN (" . implode(',', $excluded_posts) . ")";
        }

//		Optionally include individual post IDs
        if (!empty($included_posts)) {
            $included_posts = array_map('intval', explode(',', $included_posts));
            $in_posts_sql   = " AND p.ID IN (" . implode(',', $included_posts) . ")";
        }

        $adjacent = $previous ? 'previous' : 'next';
        $order    = $previous ? 'DESC' : 'ASC';
        $op       = $previous ? '<' : '>';

//		Optionally get the first/last post. Disable looping and return only one result.
        if ($end_post) {
            $order       = $previous ? 'ASC' : 'DESC';
            $num_results = 1;
            $loop        = false;
            if ($end_post === 'fixed') // display the end post link even when it is the current post
                $op          = $previous ? '<=' : '>=';
        }

//		If there is no next/previous post, loop back around to the first/last post.		
        if ($loop && isset($result)) {
            $op   = $previous ? '>=' : '<=';
            $loop = false; // prevent an infinite loop if no first/last post is found
        }

        $join = apply_filters("get_{$adjacent}_post_plus_join", $join, $r);

//		In case the value in the $order_by column is not unique, select posts based on the $order_2nd column as well.
//		This prevents posts from being skipped when they have, for example, the same menu_order.
        $where = apply_filters("get_{$adjacent}_post_plus_where", $wpdb->prepare("WHERE ( $order_by $op $order_format OR $order_2nd $op $order_format2 AND $order_by = $order_format ) AND p.post_type IN ($post_type) AND p.post_status = 'publish' $in_same_term_sql $in_same_author_sql $in_same_meta_sql $ex_cats_sql $in_cats_sql $ex_posts_sql $in_posts_sql", $current_post, $current_post2, $current_post), $r);

        $sort = apply_filters("get_{$adjacent}_post_plus_sort", "ORDER BY $order_by $order, $order_2nd $order LIMIT $num_results", $r);

        $query     = "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort";
        $query_key = 'adjacent_post_' . md5($query);
        $result    = wp_cache_get($query_key);
        if (false !== $result)
            return $result;

//		echo $query . '<br />';
//		Use get_results instead of get_row, in order to retrieve multiple adjacent posts (when $num_results > 1)
//		Add DISTINCT keyword to prevent posts in multiple categories from appearing more than once
        $result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort");
        if (null === $result)
            $result = '';
    } while (!$result && $loop);

    wp_cache_set($query_key, $result);
    return $result;
}

//Event Framwork Session Order By Session Date

/**
 * Display previous post link that is adjacent to the current post.
 *
 * Based on previous_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if previous post link is found, otherwise false.
 */
function tyler_previous_post_link_plus($args = '') {

    return tyler_adjacent_post_link_plus($args, '&laquo; %link', true);
}

/**
 * Display next post link that is adjacent to the current post.
 *
 * Based on next_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if next post link is found, otherwise false.
 */
function tyler_next_post_link_plus($args = '') {

    return tyler_adjacent_post_link_plus($args, '%link &raquo;', false);
}

/**
 * Display adjacent post link.
 *
 * Can be either next post link or previous.
 *
 * Based on adjacent_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @param bool $previous Optional, default is true. Whether display link to previous post.
 * @return bool True if next/previous post is found, otherwise false.
 */
function tyler_adjacent_post_link_plus($args = '', $format = '%link &raquo;', $previous = true) {

    $defaults = array(
        'order_by'       => 'post_date', 'order_2nd'      => 'post_date', 'meta_key'       => '', 'post_type'      => '',
        'loop'           => false, 'end_post'       => false, 'thumb'          => false, 'max_length'     => 0,
        'format'         => '', 'link'           => '%title', 'date_format'    => '', 'tooltip'        => '%title',
        'in_same_cat'    => false, 'in_same_tax'    => false, 'in_same_format' => false,
        'in_same_author' => false, 'in_same_meta'   => false,
        'ex_cats'        => '', 'ex_cats_method' => 'weak', 'in_cats'        => '', 'ex_posts'       => '', 'in_posts'       => '',
        'before'         => '', 'after'          => '', 'num_results'    => 1, 'return'         => false, 'echo'           => true
    );

    //If Post Types Order plugin is installed, default to sorting on menu_order
    if (function_exists('CPTOrderPosts')) {

        $defaults['order_by'] = 'menu_order';
    }

    $r = wp_parse_args($args, $defaults);
    if (empty($r['format'])) {
        $r['format'] = $format;
    }
    if (empty($r['date_format'])) {
        $r['date_format'] = get_option('date_format');
    }
    if (!function_exists('get_post_format')) {
        $r['in_same_format'] = false;
    }

    if ($previous && is_attachment()) {

        $posts   = array();
        $posts[] = & get_post($GLOBALS['post']->post_parent);
    } else {
        $posts = tyler_get_adjacent_post_plus($r, $previous);
    }

    //If there is no next/previous post, return false so themes may conditionally display inactive link text.
    if (!$posts) {
        return false;
    }

    //If sorting by date, display posts in reverse chronological order. Otherwise display in alpha/numeric order.
    if (($previous && $r['order_by'] != 'post_date') || (!$previous && $r['order_by'] == 'post_date')) {
        $posts = array_reverse($posts, true);
    }

    //Option to return something other than the formatted link		
    if ($r['return']) {

        if ($r['num_results'] == 1) {

            reset($posts);
            $post = current($posts);
            if ($r['return'] === 'id')
                return $post->ID;
            if ($r['return'] === 'href')
                return get_permalink($post);
            if ($r['return'] === 'object')
                return $post;
            if ($r['return'] === 'title')
                return $post->post_title;
            if ($r['return'] === 'date')
                return mysql2date($r['date_format'], $post->post_date);
        } elseif ($r['return'] === 'object') {

            return $posts;
        }
    }

    $output = $r['before'];

    //When num_results > 1, multiple adjacent posts may be returned. Use foreach to display each adjacent post.
    foreach ($posts as $post) {

        $title = $post->post_title;
        if (empty($post->post_title)) {

            $title = $previous ? __('Previous Post') : __('Next Post');
        }

        $title  = apply_filters('the_title', $title, $post->ID);
        $date   = mysql2date($r['date_format'], $post->post_date);
        $author = get_the_author_meta('display_name', $post->post_author);

        //Set anchor title attribute to long post title or custom tooltip text. Supports variable replacement in custom tooltip.
        if ($r['tooltip']) {
            $tooltip = str_replace('%title', $title, $r['tooltip']);
            $tooltip = str_replace('%date', $date, $tooltip);
            $tooltip = str_replace('%author', $author, $tooltip);
            $tooltip = ' title="' . esc_attr($tooltip) . '"';
        } else
            $tooltip = '';

        //Truncate the link title to nearest whole word under the length specified.
        $max_length = intval($r['max_length']) < 1 ? 9999 : intval($r['max_length']);
        if (strlen($title) > $max_length)
            $title      = substr($title, 0, strrpos(substr($title, 0, $max_length), ' ')) . '...';

        $rel = $previous ? 'prev' : 'next';

        $anchor = '<a href="' . get_permalink($post) . '" rel="' . $rel . '"' . $tooltip . '>';
        $link   = str_replace('%title', $title, $r['link']);
        $link   = str_replace('%date', $date, $link);
        $link   = $anchor . $link . '</a>';

        $format = str_replace('%link', $link, $r['format']);
        $format = str_replace('%title', $title, $format);
        $format = str_replace('%date', $date, $format);
        $format = str_replace('%author', $author, $format);
        if (($r['order_by'] == 'custom' || $r['order_by'] == 'numeric') && !empty($r['meta_key'])) {
            $meta   = get_post_meta($post->ID, $r['meta_key'], true);
            $format = str_replace('%meta', $meta, $format);
        } elseif ($r['in_same_meta']) {
            $meta   = get_post_meta($post->ID, $r['in_same_meta'], true);
            $format = str_replace('%meta', $meta, $format);
        }

        //Get the category list, including custom taxonomies (only if the %category variable has been used).
        if ((strpos($format, '%category') !== false) && version_compare(PHP_VERSION, '5.0.0', '>=')) {
            $term_list    = '';
            $taxonomies   = array_filter(get_post_taxonomies($post->ID), "is_taxonomy_hierarchical");
            if ($r['in_same_format'] && get_post_format($post->ID))
                $taxonomies[] = 'post_format';
            foreach ($taxonomies as &$taxonomy) {
                //No, this is not a mistake. Yes, we are testing the result of the assignment ( = ).
                //We are doing it this way to stop it from appending a comma when there is no next term.
                if ($next_term = get_the_term_list($post->ID, $taxonomy, '', ', ', '')) {
                    $term_list .= $next_term;
                    if (current($taxonomies))
                        $term_list .= ', ';
                }
            }
            $format = str_replace('%category', $term_list, $format);
        }

        //Optionally add the post thumbnail to the link. Wrap the link in a span to aid CSS styling.
        if ($r['thumb'] && has_post_thumbnail($post->ID)) {
            if ($r['thumb'] === true) // use 'post-thumbnail' as the default size
                $r['thumb'] = 'post-thumbnail';
            $thumbnail  = '<a class="post-thumbnail" href="' . get_permalink($post) . '" rel="' . $rel . '"' . $tooltip . '>' . get_the_post_thumbnail($post->ID, $r['thumb']) . '</a>';
            $format     = $thumbnail . '<span class="post-link">' . $format . '</span>';
        }

        //If more than one link is returned, wrap them in <li> tags		
        if (intval($r['num_results']) > 1)
            $format = '<li>' . $format . '</li>';

        $output .= $format;
    }

    $output .= $r['after'];

    //If echo is false, don't display anything. Return the link as a PHP string.
    if (!$r['echo'] || $r['return'] === 'output')
        return $output;

    $adjacent = $previous ? 'previous' : 'next';
    echo apply_filters("{$adjacent}_post_link_plus", $output, $r);

    return true;
}

/**
 *
 * Woocommerce Integration
 *
 */
if (is_active_widget(false, false, 'ef_registration') && in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10);
    add_action('woocommerce_after_shop_loop_item', 'tyler_woocommerce_after_shop_loop_item');
    add_action('wp_enqueue_scripts', 'tyler_woocommerce_wp_enqueue_scripts');
}

add_action('after_setup_theme', 'tyler_woocommerce_setup_theme');

function tyler_woocommerce_after_shop_loop_item() {
    global $post;

    echo '<td class="description"><span class="short-description">' . $post->post_excerpt . '</span></td>';
}

function tyler_woocommerce_setup_theme() {
    add_theme_support('woocommerce');
}

function tyler_woocommerce_wp_enqueue_scripts() {
    wp_enqueue_script('tyler-woocommerce', get_template_directory_uri() . '/js/woocommerce.js', array('jquery'), false, true);
}

add_action('init', 'tyler_components_init');

function tyler_components_init() {
    new RW_Taxonomy_Meta(array(
        'id'         => 'sponsor-tier-metas',
        'taxonomies' => array('sponsor-tier'),
        'title'      => '',
        'fields'     =>
        array(
            array(
                'name'  => __('Order', 'dxef'),
                'id'    => 'sponsor_tier_order',
                'style' => 'width:50px;',
                'type'  => 'text'
            )
        )
    ));
}

// widgets

add_filter('ef_widget_render', 'tyler_ef_widget_render', 10, 3);

function tyler_ef_widget_render($content, $id_base, $args) {
    ob_start();
    include(locate_template("components/templates/widgets/$id_base.php"));
    return ob_get_clean();
}

