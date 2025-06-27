<?php


// ******************* Basic block support ****************** //
add_theme_support('align-wide');
add_theme_support('editor-styles');
add_theme_support('responsive-embeds');
add_theme_support('wp-block-styles');
add_theme_support('block-templates');
add_theme_support('block-template-parts');
add_theme_support('core-block-patterns'); // often enabled for FSE themes
add_theme_support('post-meta');
add_theme_support('editor-color-palette', [
    [
        'name'  => __('Strong Blue', 'theme'),
        'slug'  => 'strong-blue',
        'color' => '#0073aa',
    ],
    [
        'name'  => __('Light Gray', 'theme'),
        'slug'  => 'light-gray',
        'color' => '#f5f5f5',
    ],
]);
add_theme_support('editor-font-sizes', [
    [
        'name' => __('Small', 'theme'),
        'size' => 12,
        'slug' => 'small'
    ],
    [
        'name' => __('Large', 'theme'),
        'size' => 36,
        'slug' => 'large'
    ]
]);

wp_enqueue_style( 'wp-block-library' );
function mytheme_enqueue_scripts() {
    wp_enqueue_script( 'wp-navigation' );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_scripts' );

function mytheme_block_editor_styles() {
    add_editor_style('css/editor-style.css');
}
add_action('after_setup_theme', 'mytheme_block_editor_styles');



// ******************* Add Libraries ****************** //
require_once get_template_directory() . '/editor/class-imagely-editor.php';
require_once('event-framework/lib/geocode.php');
require_once('event-framework/lib/recaptchalib.php');
require_once('event-framework/lib/api/functions.php');

include 'event-framework/event-framework.php';

add_filter('ef_theme_options_logo', 'tyler_set_theme_options_logo');

function tyler_set_theme_options_logo($url) {
    return get_template_directory_uri() . '/assets/images/schemes/basic/logo.png';
}

// Display the logo based on selected Color Scheme
function tyler_set_theme_logo() {
    // Get Theme Options
    $ef_options = EF_Event_Options::get_theme_options();

    $color_scheme = empty($ef_options['ef_color_palette']) ? 'basic' : $ef_options['ef_color_palette'];

    if (!empty($ef_options['ef_logo']) && $ef_options['ef_logo'] != 'http://') {
        $logo_url = $ef_options['ef_logo'];
    } else {
        $logo_url = get_template_directory_uri() . "/assets/images/schemes/$color_scheme/logo.png";
    }

    return $logo_url;
}



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
    wp_enqueue_style('tyler-bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_style('tyler-icons', get_template_directory_uri() . '/assets/css/icon.css');
    wp_enqueue_style('tyler-layout', get_template_directory_uri() . '/assets/css/layout.css');

    if (is_child_theme()) {
        wp_enqueue_style('tyler-parent-style', trailingslashit(get_template_directory_uri()) . 'style.css');
    }
    wp_enqueue_style('tyler-style', get_stylesheet_uri());

    // Color Schemes
    $color_scheme = empty($ef_options['ef_color_palette']) ? 'basic' : $ef_options['ef_color_palette'];
    if (isset($color_scheme) && $color_scheme != 'basic') {
        wp_enqueue_style($color_scheme . '-scheme', get_template_directory_uri() . '/assets/css/schemes/' . $color_scheme . '/layout.css');
    }

    

    // scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('tyler-bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), false, true);
    wp_enqueue_script('tyler-script', get_template_directory_uri() . '/js/main.js', array('jquery'), false, true);

    // home
    if (is_home() || is_front_page()) {
        wp_enqueue_script('tyler-home', get_template_directory_uri() . '/js/home.js', array('jquery'), false, true);
        wp_enqueue_script('jquery-tweet-machine', get_template_directory_uri() . '/js/tweetMachine.min.js', array('jquery'), false, true);
    }

    // single
    if (is_singular())
        

   

    // full schedule
    if (get_page_template_slug() == 'schedule.php') {
        wp_enqueue_script('tyler-schedule', get_template_directory_uri() . '/js/schedule.js', array('jquery'));
		wp_localize_script('tyler-schedule', 'ajax_object', array(
		'ajaxurl' => admin_url('admin-ajax.php')
	));
    }
}

add_action('admin_enqueue_scripts', 'tyler_admin_enqueue_scripts');

 

function tyler_admin_enqueue_scripts($hook) {
    global $post_type;

    if (in_array($hook, array('post.php', 'post-new.php'))) {
        if ($post_type == 'session') {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/assets/css/admin/smoothness/jquery-ui-1.10.3.custom.min.css');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_style('tyler-sortable', get_template_directory_uri() . '/assets/css/sortable.css');
        } else if (get_page_template_slug() == 'speakers.php') {
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('tyler-page-speakers-full-screen', get_template_directory_uri() . '/js/page-speakers-full-screen.js');
            wp_enqueue_style('tyler-sortable', get_template_directory_uri() . '/assets/css/sortable.css');
            wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/assets/css/admin/smoothness/jquery-ui-1.10.3.custom.min.css');
        }
    }
}

// ******************* Ajax ****************** //

 

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
    } elseif (in_array($order_by, array('ID', 'post_author', 'post_parent', 'menu_order'))) {
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

// widgets XYZ

add_filter('ef_widget_render', 'tyler_ef_widget_render', 10, 3);

function tyler_ef_widget_render($content, $id_base, $args) {
    ob_start();
    include(locate_template("components/templates/widgets/$id_base.php"));
    return ob_get_clean();
}



function tc_add_to_head()
{
	echo "<script type='text/javascript'>var speakerBioInLightBox=0;</script>";
}
add_action('wp_head', 'tc_add_to_head');

function tc_enqueue_scripts()
{
	wp_enqueue_style('tyler-style', get_template_directory_uri() . '/style.css');
	wp_enqueue_script('tc-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1', true);
 	$session_templates = ["schedule.php", "schedule-session-two.php", "schedule-session-three.php", "schedule-session-four.php", "schedule-session-five.php"];
	
		
	wp_enqueue_script('tyler-schedule', get_template_directory_uri() . '/js/schedule.js', array('jquery'));
	
	
	wp_localize_script('tyler-schedule', 'ajax_object', array(
		'ajaxurl' => admin_url('admin-ajax.php')
	));
	
}
add_action('wp_enqueue_scripts', 'tc_enqueue_scripts', 15);

function tc_child_meta_boxes($callback)
{
	// replace tyler's speaker meta box with a custom one, adding first and last name to the keynote checkbox
	remove_meta_box('metabox-speaker', 'speaker', 'normal');
	add_meta_box('metabox-speaker', __('Speaker Details', 'tyler-child'), 'tc_metabox_speaker', 'speaker', 'normal', 'high');

	// add locations to the sponsor edit page
	add_meta_box('metabox-sponsor-locations', __('Locations', 'tyler-child'), 'tc_metabox_sponsor', 'sponsor', 'normal', 'default');

	add_meta_box(
		'metabox-session-sponsors',
		__('Breaks', 'tyler-child'),
		'tc_metabox_session',
		['session', "sessiontwo", "sessionthree", "sessionfour","sessionfive"],
		'normal',
		'default'
	);
}
add_action('add_meta_boxes', 'tc_child_meta_boxes', 999);

function tc_save_meta_box($post_id, $post, $update)
{
	// save extra fields
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
		return $post_id;

	if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
		return $post_id;

	if ($post->post_type == "speaker") {
		// keynote is saved in parent theme
		tc_save_post_meta($post_id, "speaker_moderator");
		tc_save_post_meta($post_id, "first_name");
		tc_save_post_meta($post_id, "last_name");
		tc_save_post_meta($post_id, "speaker_title");
		tc_save_post_meta($post_id, "company_name");
	}
	if ($post->post_type == "sponsor")
		tc_save_post_meta($post_id, "sponsor_location");

	if (in_array($post->post_type, ["session", "sessiontwo", "sessionthree", "sessionfour","sessionfive"])) {
		tc_save_post_meta($post_id, "session_sponsor");
		tc_save_post_meta($post_id, "no_links");
	}
}
add_action('save_post', 'tc_save_meta_box', 10, 3);

function tc_save_post_meta($post_id, $field_name)
{
	$value = "";
	if (isset($_POST[$field_name]))
		$value = $_POST[$field_name];
	update_post_meta($post_id, $field_name, $value);
}

function tc_metabox_sponsor($post)
{
	// allowing selection of session locations for exhibitors (type of sponsors)
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_meta_data = get_post_custom($post->ID);
	if (isset($post_meta_data['sponsor_location']) && is_array($post_meta_data['sponsor_location']) && isset($post_meta_data['sponsor_location'][0])) {
		$sponsor_location = $post_meta_data['sponsor_location'][0];
	} else {
		$sponsor_location = '';
	}

?>
	<table class='form-table'>
		<tbody>
			<tr>
				<th scope='row'>
					<label for='sponsor_location'><?php _e('Exhibitor Location', 'tyler-child'); ?></label>
				</th>
				<td>
					<select name='sponsor_location' id='sponsor_location'>
						<option value=''></option>
						<?php
						$locations = get_terms(array(
							'taxonomy' => 'session-location',
							'hide_empty' => false,
						));
						if ($locations) {
							foreach ($locations as $location) {
								echo "<option value='" . $location->term_taxonomy_id . "'";
								if ($sponsor_location == $location->term_taxonomy_id)
									echo " selected";
								echo ">" . $location->name . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function tc_metabox_session($post)
{
	// allowing selection of sponsors for sessions. also set no links to sessions' single pages
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_meta_data = get_post_custom($post->ID);
	$session_sponsor = $post_meta_data['session_sponsor'][0];
	$no_links = $post_meta_data['no_links'][0];
?>
	<table class='form-table'>
		<tbody>
			<tr>
				<th scope='row'>
					<?php _e('Session Page', 'tyler-child'); ?>
				</th>
				<td>
					<label for='no_links'>
						<input type='checkbox' id='no_links' name='no_links' value='1' <?php if ($no_links == 1) echo "checked='checked'"; ?> />
						<?php _e("Remove links to the session page", 'tyler-child'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='session_sponsor'><?php _e('Break sponsored by', 'tyler-child'); ?></label>
				</th>
				<td>
					<select name='session_sponsor' id='session_sponsor'>
						<option value=''></option>
						<?php
						$sponsors = get_posts(array(
							'post_type' => 'sponsor',
							'nopaging' => true,
							'orderby' => 'title',
							'order' => 'ASC'
						));
						if ($sponsors) {
							foreach ($sponsors as $sponsor) {
								echo "<option value='" . $sponsor->ID . "'";
								if ($session_sponsor == $sponsor->ID)
									echo " selected";
								echo ">" . $sponsor->post_title . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function tc_metabox_speaker($post)
{
	// meta box for speakers, extended to the one used in parent theme
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_meta_data = get_post_custom($post->ID);

	// $speaker_keynote = $post_meta_data['speaker_keynote'][0];
    $speaker_keynote = isset($post_meta_data['speaker_keynote'][0]) ? $post_meta_data['speaker_keynote'][0] : '';


	$speaker_moderator = 0;
	if (array_key_exists('speaker_moderator', $post_meta_data))
		$speaker_moderator = $post_meta_data['speaker_moderator'][0];
?>
	<table class='form-table'>
		<tbody>
			<tr>
				<th scope='row'>
					<?php _e('Keynote', 'tyler-child'); ?>
				</th>
				<td>
					<label for='speaker_keynote'>
						<input type='checkbox' id='speaker_keynote' name='speaker_keynote' value='1' <?php if ($speaker_keynote == 1) echo "checked='checked'"; ?> />
						<?php _e('Keynote Speaker', 'tyler-child'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<?php _e('Moderator', 'tyler-child'); ?>
				</th>
				<td>
					<label for='speaker_moderator'>
						<input type='checkbox' id='speaker_moderator' name='speaker_moderator' value='1' <?php if ($speaker_moderator == 1) echo "checked='checked'"; ?> />
						<?php _e('Moderator', 'tyler-child'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='first_name'><?php _e('First Name', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='first_name' id='first_name' value='<?php echo isset($post_meta_data['first_name'][0]) ? $post_meta_data['first_name'][0] : ''; ?>'>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='last_name'><?php _e('Last Name', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='last_name' id='last_name' value='<?php echo isset($post_meta_data['last_name'][0]) ? $post_meta_data['last_name'][0] : ''; ?>' required>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='speaker_title'><?php _e('Title', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='speaker_title' id='speaker_title' value='<?php echo isset($post_meta_data['speaker_title'][0]) ? $post_meta_data['speaker_title'][0] : ''; ?>'>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='company_name'><?php _e('Company', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='company_name' id='company_name' value='<?php echo isset($post_meta_data['company_name'][0]) ? $post_meta_data['company_name'][0] : ''; ?>'>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function tc_register_taxonomies()
{
	// add a taxonomy to sponsors, called type. this will be used to set sponsors as exhibitors and supporting organizations.
	register_taxonomy('sponsor-type', 'sponsor', array(
		'hierarchical' => true,
		'show_in_rest' => true,		
		'labels' => array(
			'name' => __('Types', 'tyler-child'),
			'singular_name' => __('Type', 'tyler-child'),
			'search_items' => __('Search Types', 'tyler-child'),
			'all_items' => __('All Types', 'tyler-child'),
			'parent_item' => __('Parent Type', 'tyler-child'),
			'parent_item_colon' => __('Parent Type:', 'tyler-child'),
			'edit_item' => __('Edit Type', 'tyler-child'),
			'update_item' => __('Update Type', 'tyler-child'),
			'add_new_item' => __('Add New Type', 'tyler-child'),
			'new_item_name' => __('New Type', 'tyler-child'),
			'menu_name' => __('Types', 'tyler-child')
		),
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true
	));
}
add_action('init', 'tc_register_taxonomies');

function tc_modify_sponsor_custom_post_type($args, $post_type)
{
	// change the sponsor and speaker custom post type, have them support archives so that we will have list pages for them
	if ($post_type == "sponsor") {
		$new_args = array('has_archive' => true);
		return array_merge($args, $new_args);
	}
	if ($post_type == "speaker") {
		$new_args = array('has_archive' => true);
		return array_merge($args, $new_args);
	}
	return $args;
}
add_filter('register_post_type_args', 'tc_modify_sponsor_custom_post_type', 10, 2);

function tc_custom_post_order_sort($query)
{
	if ((!is_admin()) && (is_archive()) && ((is_tax("sponsor-type")) || (is_post_type_archive('sponsor')))) {	// change the order of sponsors to be by title, alphabetically
		$query->set('nopaging', true);
		$query->set('orderby', 'post_title');
		$query->set('order', 'ASC');
	}

	if ((!is_admin()) && (is_archive()) && (is_post_type_archive('speaker'))) {	// change the order of speakers to be by last name
		$query->set('nopaging', true);
		$query->set('meta_query', array(
			array(
				'key' => 'last_name',
				'compare' => 'EXISTS'
			)
		));
		$query->set('orderby', 'meta_value title');
		$query->set('order', 'ASC');
	}
	if ($query->get('post_type') === 'nav_menu_item') { 	// menu won't appear on speakers' page without this
		$query->set('meta_query', '');
		$query->set('meta_key', '');
		$query->set('orderby', '');
	}
}
add_action('pre_get_posts', 'tc_custom_post_order_sort');

remove_action('wp_ajax_get_schedule', array('EF_Session_Helper', 'fudge_ajax_get_schedule'));
remove_action('wp_ajax_nopriv_get_schedule', array('EF_Session_Helper', 'fudge_ajax_get_schedule'));

function tc_ajax_get_schedule()
{
	// this is the result of an ajax call to create the schedule. it replaces the original function in the parent theme as it
	// adds company name and job title to the speaker's info
	$ret = array(
		'sessions' => array(),
		'strings'  => array(
			'more_info' => __('More info', 'dxef')
		)
	);
	/**
	 * Added by Sandeep @codeable 
	 */
	$session_type = sanitize_text_field($_POST["session_type"]);
	$session_types = ["session", "sessiontwo", "sessionthree", "sessionfour","sessionfive"];
	if (!in_array($session_type, $session_types)) {
		echo json_encode($ret);
		die();
	}


	$timestamp      = !empty($_POST['data-timestamp']) ? $_POST['data-timestamp'] : 0;
	$location       = !empty($_POST['data-location']) && ctype_digit($_POST['data-location']) ? intval($_POST['data-location']) : '0';
	$track          = !empty($_POST['data-track']) && ctype_digit($_POST['data-track']) ? intval($_POST['data-track']) : '0';
	$wp_time_format = get_option("time_format");

	add_filter('posts_fields', array('EF_Session_Helper', 'ef_sessions_posts_fields'));
	add_filter('posts_orderby', array('EF_Session_Helper', 'ef_sessions_posts_orderby'));

	$session_loop_args = array(
		/*** Changed by Sandeep @codeable 
		 * 
		 * 'post_type'   => "session",
		 */
		'post_type'   => $session_type,
		'post_status' => 'publish',
		'nopaging'    => true,
		'meta_query'  => array(
			array(
				'key'     => 'session_time',
				'compare' => 'EXISTS',
			),
			array(
				'key'     => 'session_date',
				'compare' => 'EXISTS',
			)
		),
		'tax_query'   => array(),
		//'meta_key' => 'session_date',
		'orderby'     => 'meta_value',
		'order'       => 'ASC'
	);

	if ($timestamp > 0)
		$session_loop_args['meta_query'][] = array(
			'key'   => 'session_date',
			'value' => $timestamp
		);
	if ($location > 0)
		$session_loop_args['tax_query'][]  = array(
			'taxonomy' => 'session-location',
			'field'    => 'id',
			'terms'    => $location
		);
	if ($track > 0)
		$session_loop_args['tax_query'][]  = array(
			'taxonomy' => 'session-track',
			'field'    => 'id',
			'terms'    => $track
		);
	$sessions_loop                     = new WP_Query($session_loop_args);

	remove_filter('posts_fields', array('EF_Session_Helper', 'ef_sessions_posts_fields'));
	remove_filter('posts_orderby', array('EF_Session_Helper', 'ef_sessions_posts_orderby'));

	while ($sessions_loop->have_posts()) {
		$sessions_loop->the_post();
		global $post;

		$time = $post->time;
		if (!empty($time)) {
			$time_parts = explode(':', $time);
			if (count($time_parts) == 2)
				$time       = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
		}

		$end_time = $post->session_end_time;
		if (!empty($end_time)) {
			$time_parts = explode(':', $end_time);
			if (count($time_parts) == 2)
				$end_time   = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
		}

		$locations     = wp_get_post_terms(get_the_ID(), 'session-location');
		if ($locations && count($locations) > 0)
			$location      = $locations[0];
		else
			$location      = '';
		$tracks        = wp_get_post_terms(get_the_ID(), 'session-track', array('fields' => 'ids', 'count' => 1));
		if ($tracks && count($tracks) > 0)
			$track         = $tracks[0];
		else
			$track         = '';
		$speakers_list = get_post_meta(get_the_ID(), 'session_speakers_list', true);
		$speakers      = array();
		if ($speakers_list && count($speakers_list) > 0) {
			foreach ($speakers_list as $speaker_id)
				$speakers[] = array(
					'post_title' => get_the_title($speaker_id),
					'featured'   => get_post_meta($speaker_id, 'speaker_keynote', true),
					'url'        => get_permalink($speaker_id),
					'post_image' => get_the_post_thumbnail($speaker_id, array(54, 54), array('alt' => get_the_title($speaker_id))),
					'company'	 => get_post_meta($speaker_id, 'company_name', true),
					'speaker_title'	 => get_post_meta($speaker_id, 'speaker_title', true),
					'speaker_moderator' => get_post_meta($speaker_id, 'speaker_moderator', true),
					'desc'	 => apply_filters('the_content', get_post_field('post_content', $speaker_id))
				);
		}

		$session_date = get_post_meta(get_the_ID(), 'session_date', true);

		if (empty($session_date)) {
			// If session date is empty, get the Post publish time
			$session_date = get_the_date(get_option(' date_format'), $post->ID);
		} else {
			// else get the session_date
			$session_date = date_i18n(get_option('date_format'), $session_date);
		}

		$session_sponsor_id = get_post_meta(get_the_ID(), 'session_sponsor', true);
		$sponsor_logo = "";
		if (!empty($session_sponsor_id))
			$sponsor_logo = get_the_post_thumbnail_url($session_sponsor_id);

		array_push($ret['sessions'], array(
			'post_title' => get_the_title(),
			'url'        => get_permalink(get_the_ID()),
			'time'       => $time,
			'end_time'   => $end_time,
			'workshop'   => get_post_meta(get_the_ID(), 'session_workshop', true),
			'date'       => $session_date,
			'location'   => $location ? $location->name : '',
			'speakers'   => $speakers,
			'no_links'	 => get_post_meta(get_the_ID(), 'no_links', true),
			'sponsor_logo' => $sponsor_logo
		));
	}

	echo json_encode($ret);
	die;
}
add_action('wp_ajax_get_schedule', 'tc_ajax_get_schedule');
add_action('wp_ajax_nopriv_get_schedule', 'tc_ajax_get_schedule');



add_action('add_meta_boxes', 'wpt_add_event_metaboxes');
function wpt_add_event_metaboxes()
{
	
	add_meta_box(
		'workshop_post_show_id',
		'Show Post on Workshop page',
		'workshop_post_show',


		['session', "sessiontwo", "sessionthree", "sessionfour","sessionfive"],
		'side',
		'high'
	);
}
function workshop_post_show($post)
{
	global $post;
	wp_nonce_field(basename(__FILE__), 'session_fieldss');
	$session_workshop = get_post_meta($post->ID, 'session_workshop', true);
?>

	<p>
		<label for="session_workshop"><?php _e('Show in workshop page', 'dxef'); ?></label>
		<input type="checkbox" onclick="myFunction()" id="session_workshop" name="session_workshop" value="<?php echo $session_workshop; ?>" <?php if ($session_workshop == 1) {
																																					echo 'checked="checked"';
																																				} ?> />
		<script>
			function myFunction() {
				// Get the checkbox
				var checkBox = document.getElementById("session_workshop");
				// Get the output text



				// If the checkbox is checked, display the output text
				if (checkBox.checked == true) {
					checkBox.setAttribute("value", "1");
				} else {
					checkBox.setAttribute("value", "0");
					checkBox.removeAttribute("checked");
				}
			}
		</script>
	</p>


<?php
}

function wpt_save_events_meta($post_id, $post)
{

	if (!in_array($post->post_type, ["session", "sessiontwo", "sessionthree", "sessionfour","sessionfive"])) {
		return;
	}
	// Return if the user doesn't have edit permissions.
	if (! current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	// if ( ! isset( $_POST['session_workshop'] ) || ! wp_verify_nonce( $_POST['session_fieldss'], basename(__FILE__) ) ) {
	// 	return $post_id;
	// }

	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $events_meta.	
	$events_meta['session_workshop'] = isset($_POST['session_workshop']) ? esc_textarea($_POST['session_workshop']) : '';


	// Cycle through the $events_meta array.
	// Note, in this example we just have one item, but this is helpful if you have multiple.
	foreach ($events_meta as $key => $value) :

		// Don't store custom data twice
		if ('revision' === $post->post_type) {
			return;
		}

		if (get_post_meta($post_id, $key, false)) {
			// If the custom field already has a value, update it.
			update_post_meta($post_id, $key, $value);
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta($post_id, $key, $value);
		}

		if (! $value) {
			// Delete the meta key if there's no value
			delete_post_meta($post_id, $key);
		}

	endforeach;
}
add_action('save_post_session', 'wpt_save_events_meta', 1, 2);




function get_post_meta_cb($object, $field_name, $request)
{


	if (is_array($object)) 
	{
		$object_id = $object['ID'] ?? null;
		return get_post_meta($object_id, $field_name, true);
	} 
	
	elseif (is_object($object)) 
	{
		$object_id = $object->ID ?? null;
		return get_post_meta($object_id, $field_name, true);
	} 

}
function update_post_meta_cb($value, $object, $field_name)
{
	$post_meta_update = update_post_meta($object->ID, $field_name, $value);
	return $post_meta_update;
}
add_action('rest_api_init', function () {
	register_rest_field(
		'speaker',
		'first_name',
		array(
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
	register_rest_field(
		'speaker',
		'last_name',
		array(
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
	register_rest_field(
		'speaker',
		'speaker_title',
		array(
			'show_in_rest' => true,
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
	register_rest_field(
		'speaker',
		'company_name',
		array(
			'show_in_rest' => true,
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
});

/** Added by Sandeep @codeable */
add_action('init', function () {
	$post_types = [2 => "two", 3 => "three", 4 => "four", 5 => "five"];
	foreach ($post_types as $num => $type) {
		register_post_type('session' . $type, array(
			'labels' => array(
				'name' => __('Session ' . $num, 'dxef'),
				'singular_name' => __('Sessions ' . $num, 'dxef'),
				'add_new' => __('Add New', 'dxef'),
				'add_new_item' => __('Add New Session ' . $num, 'dxef'),
				'edit_item' => __('Edit Session ' . $num, 'dxef'),
				'new_item' => __('New Session ' . $num, 'dxef'),
				'view_item' => __('View Session ' . $num, 'dxef'),
				'search_items' => __('Search Session ' . $num, 'dxef'),
				'not_found' => __('No Sessions found', 'dxef'),
				'not_found_in_trash' => __('No Sessions found in trash', 'dxef'),
				'menu_name' => __('Sessions ' . $num, 'dxef')
			),
			'public' => true,
			'menu_position' => 215, // Change this number to adjust the order
			'show_in_rest' => true,
			'supports' => ['editor'],
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'sessions-' . $type
			),
			'taxonomies' => ['session-location', 'session-track'], // Associate taxonomies
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array(
				'title',
				'editor',
				'page-attributes',
				'thumbnail',
			)
		));
	}
}, 60);
add_action('add_meta_boxes', function () {
	foreach (["sessiontwo", 'sessionthree', "sessionfour","sessionfive"] as  $type) {
		add_meta_box(
			'metabox-session',
			__('Session Details', 'dxef'),
			'ef_metabox_session',
			$type,
			'normal',
			'high'
		);
		add_meta_box(
			'metabox-session-speakers',
			__('Speakers', 'dxef'),
			'ef_metabox_session_speakers',
			$type,
			'normal',
			'high'
		);
	}
});
add_action('save_post', function ($id) {
	if (isset($_POST['post_type']) && in_array($_POST['post_type'], ["sessiontwo", "sessionthree", 'sessionfour',"sessionfive"])) {
		if (isset($_POST['session_home']))
			update_post_meta($id, 'session_home', $_POST['session_home']);
		else
			delete_post_meta($id, 'session_home');

		if (isset($_POST['session_date']))
			update_post_meta($id, 'session_date', strtotime($_POST['session_date']));

		if (isset($_POST['session_time']))
			update_post_meta($id, 'session_time', $_POST['session_time']);

		if (isset($_POST['session_end_time']))
			update_post_meta($id, 'session_end_time', $_POST['session_end_time']);

		if (isset($_POST['session_registration_code']))
			update_post_meta($id, 'session_registration_code', $_POST['session_registration_code']);
		else
			delete_post_meta($id, 'session_registration_code');

		if (isset($_POST['session_registration_title']))
			update_post_meta($id, 'session_registration_title', $_POST['session_registration_title']);
		else
			delete_post_meta($id, 'session_registration_title');

		if (isset($_POST['session_registration_text']))
			update_post_meta($id, 'session_registration_text', $_POST['session_registration_text']);
		else
			delete_post_meta($id, 'session_registration_text');

		// AJAX Speakers Order
		if (isset($_POST['session_speakers_list'])) {
			$session_speakers = $_POST['session_speakers_list'];
			if (! empty($session_speakers[0])) {
				$session_speakers = explode(',', $session_speakers[0]);
				update_post_meta($id, 'session_speakers_list', $session_speakers);
			} else {
				delete_post_meta($id, 'session_speakers_list');
			}
		}
	}
});
add_action('admin_enqueue_scripts', function ($hook) {
	global $post_type;
	if (in_array($hook, array('post.php', 'post-new.php'))) {
		if (in_array($post_type, ["sessiontwo", "sessionthree", "sessionfour","sessionfive"])) {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/assets/css/admin/smoothness/jquery-ui-1.10.3.custom.min.css');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_style('tyler-sortable', get_template_directory_uri() . '/assets/css/sortable.css');
		}
	}
});
function sandeep_get_session_dates($post_type="session") {
	global $wpdb;

	$metas = $wpdb->get_results(
			"SELECT DISTINCT meta_value
			FROM $wpdb->postmeta
			INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID
			WHERE
			$wpdb->posts.post_type = '".$post_type."' AND
			$wpdb->posts.post_status = 'publish' AND
			$wpdb->postmeta.meta_key = 'session_date' AND
			$wpdb->postmeta.meta_value != ''
			ORDER BY meta_value ASC");

	return $metas;
}
function sandeep_get_terms_for_post_type($taxonomy, $post_type) {
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => -1, 
        'fields'         => 'ids',
    );
    $posts = get_posts($args);

    if (empty($posts)) {
        return array();
    }

    $term_ids = array();
    foreach ($posts as $post_id) {
        $terms = wp_get_post_terms($post_id, $taxonomy, array('fields' => 'ids'));
        if (!is_wp_error($terms) && !empty($terms)) {
            $term_ids = array_merge($term_ids, $terms);
        }
    }

    $term_ids = array_unique($term_ids);

    if (empty($term_ids)) {
        return array();
    }

    $terms = get_terms(array(
        'taxonomy'   => $taxonomy,
        'include'    => $term_ids,
        'hide_empty' => true, 
    ));

    if (is_wp_error($terms)) {
        return array(); // Return an empty array if there's an error
    }

    return $terms; // Return the list of terms
}





//Simon - Custom list of sponsors 

function list_sponsors_by_type_or_alphabetically($atts) {
    // Get shortcode attributes (allow filtering by sponsor-type)
    $atts = shortcode_atts(array(
        'sponsor_type'  => '', // Default: Show all sponsor types
        'posts_per_page' => -1 // Show all sponsors
    ), $atts, 'sponsor_list');

    // Start output buffering
    ob_start();

    // Query Arguments
    $query_args = array(
        'post_type'      => 'sponsor',
        'posts_per_page' => $atts['posts_per_page'],
        'orderby'        => 'title',
        'order'          => 'ASC'
    );

    // If a specific sponsor type is provided, filter by that taxonomy
    if (!empty($atts['sponsor_type'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'sponsor-type',
                'field'    => 'slug',
                'terms'    => $atts['sponsor_type']
            )
        );
    }

    // Get sponsors based on the query
    $sponsors = new WP_Query($query_args);

    if ($sponsors->have_posts()) {
        echo '<div class="sponsor-list-container">';
        echo '<ul class="sponsor-list">';

        $processed_sponsors = array(); // Prevent duplicate sponsors

        while ($sponsors->have_posts()) {
            $sponsors->the_post();

            // Get sponsor details
            $title = get_the_title();
            $post_id = get_the_ID();
            
            // Prevent duplicates when listing all sponsors
            if (empty($atts['sponsor_type']) && in_array($post_id, $processed_sponsors)) {
                continue;
            }

            $processed_sponsors[] = $post_id;

            $logo = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            $logo_url = $logo ? esc_url($logo[0]) : '';
            $link = get_permalink();

            echo '<li class="sponsor-item">';
            if ($logo_url) {
                echo '<a href="' . esc_url($link) . '" class="sponsor-logo">
                        <img src="' . $logo_url . '" alt="' . esc_attr($title) . '">
                      </a>';
            }
            //echo '<a href="' . esc_url($link) . '" class="sponsor-name">' . esc_html($title) . '</a>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';

        wp_reset_postdata();
    } else {
        echo '<p>No sponsors found.</p>';
    }

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('sponsor_list', 'list_sponsors_by_type_or_alphabetically');



function get_eventframework_option($key) {
    $options = get_option('event_framework');

    if (!empty($options['theme_options']) && is_array($options['theme_options'])) {
        foreach ($options['theme_options'] as $field) {
            if (isset($field['name']) && $field['name'] === 'eventframework[' . $key . ']') {
                return $field['value'];
            }
        }
    }

    return ''; // return empty string if not found
}



/**
 * Register block styles.
*/

if ( ! function_exists( 'tyler_block_styles' ) ) :
	/**
	 * Register custom block styles
	 */
	function tyler_block_styles() {

		register_block_style(
			'core/details',
			array(
				'name'         => 'arrow-icon-details',
				'label'        => __( 'Arrow icon', 'tyler' ),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		register_block_style(
			'core/post-terms',
			array(
				'name'         => 'pill',
				'label'        => __( 'Pill', 'tyler' ),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;					
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'tyler' ),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
		register_block_style(
			'core/navigation-link',
			array(
				'name'         => 'arrow-link',
				'label'        => __( 'With arrow', 'tyler' ),
				/*
				 * Styles for the custom arrow nav link block style
				 */
				'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'         => 'asterisk',
				'label'        => __( 'With asterisk', 'tyler' ),
				'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
			)
		);
	}
endif;

add_action( 'init', 'tyler_block_styles' );

/**
 * Enqueue block stylesheets.
 */

if ( ! function_exists( 'tyler_block_stylesheets' ) ) :
	/**
	 * Enqueue custom block stylesheets
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function tyler_block_stylesheets() {
		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'tyler-button-style-outline',
				'src'    => get_parent_theme_file_uri( 'assets/css/button-outline.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => get_parent_theme_file_path( 'assets/css/button-outline.css' ),
			)
		);
	}
endif;

add_action( 'init', 'tyler_block_stylesheets' );

/**
 * Register pattern categories.
 */

if ( ! function_exists( 'tyler_pattern_categories' ) ) :
	/**
	 * Register pattern categories
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function tyler_pattern_categories() {

		register_block_pattern_category(
			'tyler_page',
			array(
				'label'       => _x( 'Pages', 'Block pattern category', 'tyler' ),
				'description' => __( 'A collection of full page layouts.', 'tyler' ),
			)
		);
	}
endif;

add_action( 'init', 'tyler_pattern_categories' );


// Updated AJAX handler for all 5 session types

add_action('wp_ajax_get_sessions', 'handle_get_sessions');
add_action('wp_ajax_nopriv_get_sessions', 'handle_get_sessions');

function handle_get_sessions() {
    try {	
        $session_type = sanitize_text_field($_POST['session_type']);
        
        // Validate session type - now includes all 5 CPTs
        $valid_types = ['session', 'sessiontwo', 'sessionthree', 'sessionfour', 'sessionfive'];
        if (!in_array($session_type, $valid_types)) {
            wp_send_json_error('Invalid session type: ' . $session_type);
            return;
        }
        
        // Get the data using your existing functions
        $sessions = get_sessions_for_type($session_type);
        $tracks = sandeep_get_terms_for_post_type('session-track', $session_type);
        $locations = sandeep_get_terms_for_post_type('session-location', $session_type);
        $dates = sandeep_get_session_dates($session_type);
        
        wp_send_json_success([
            'sessions' => $sessions,
            'tracks' => $tracks,
            'locations' => $locations,
            'dates' => $dates,
            'session_type' => $session_type // For debugging
        ]);
        
    } catch (Exception $e) {
        wp_send_json_error('Error loading sessions: ' . $e->getMessage());
    }


}

// Helper function that works for all 5 session types
function get_sessions_for_type($session_type) {





    $args = [
        'post_type' => $session_type,
        'post_status' => 'publish',
        'posts_per_page' => 20,
        'meta_query' => [
            [
                'key' => 'session_date',
                'compare' => 'EXISTS'
            ]
        ]
    ];
    
    $posts = get_posts($args);
    $sessions = [];
    
    foreach ($posts as $post) {
        // Get session metadata
        $track_terms = get_the_terms($post->ID, 'session-track');
        $location_terms = get_the_terms($post->ID, 'session-location');

		$start_time = get_post_meta($post->ID, 'session_time', true);
		$end_time = get_post_meta($post->ID, 'session_end_time', true);
		$wp_time_format = get_option('time_format');

		if (!empty($start_time)) {
			$time_parts = explode(':', $start_time);
			if (count($time_parts) == 2)
				$start_time = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
		}		

		if (!empty($end_time)) {
			$time_parts = explode(':', $end_time);
			if (count($time_parts) == 2)
				$end_time   = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
		}
        
        $sessions[] = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'description' => $post->post_excerpt ?: wp_trim_words($post->post_content, 30),
            'date' => get_post_meta($post->ID, 'session_date', true),
            'time' => get_post_meta($post->ID, 'session_time', true),
			'start_time' => $start_time,
			'end_time' => $end_time,			      
            'track_id' => $track_terms ? $track_terms[0]->term_id : 0,
            'track_name' => $track_terms ? $track_terms[0]->name : 'No Track',
            'location_id' => $location_terms ? $location_terms[0]->term_id : 0,
            'location_name' => $location_terms ? $location_terms[0]->name : 'No Location',
            'url' => get_permalink($post->ID),
            'session_type' => $session_type
        ];
    }
    
    return $sessions;
}

// Keep your existing helper functions
if (!function_exists('sandeep_get_terms_for_post_type')) {
    function sandeep_get_terms_for_post_type($taxonomy, $post_type) {
        return get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false
        ]);
    }
}

if (!function_exists('sandeep_get_session_dates')) {
    function sandeep_get_session_dates($post_type) {
        global $wpdb;
        
        $results = $wpdb->get_results($wpdb->prepare("
            SELECT DISTINCT pm.meta_value 
            FROM {$wpdb->postmeta} pm 
            INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id 
            WHERE pm.meta_key = 'session_date' 
            AND p.post_type = %s 
            AND p.post_status = 'publish'
            ORDER BY pm.meta_value ASC
        ", $post_type));
        
        return $results;
    }
}


//XYZ

// Register the custom block
function register_speakers_block() {
    wp_register_script(
        'speaker-block',
        get_stylesheet_directory_uri() . '/assets/js/speaker-block.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-data', 'wp-core-data', 'wp-components'),
        filemtime(get_stylesheet_directory() . '/assets/js/speaker-block.js')
    );

    register_block_type('tyler-child/speakers-list', array(
        'editor_script' => 'speaker-block',
        'render_callback' => 'render_speakers_block'
    ));
}
add_action('init', 'register_speakers_block');

// Render callback for the speakers block
function render_speakers_block($attributes) {
    $speakers = get_posts(array(
        'post_type' => 'speaker',
        'posts_per_page' => -1,  
		'post_status' => 'publish', 
		'suppress_filters' => false, 		
		'orderby' => 'menu_order title',
		'order' => 'ASC'


    ));

    if (empty($speakers)) {
        return '<p>' . esc_html__('No speakers found.', 'tyler-child') . '</p>';
    }

    ob_start();
    ?>
    <div class="speakers-list-block">
        <?php foreach ($speakers as $speaker) : 
            $speaker_title = get_post_meta($speaker->ID, 'speaker_title', true);
            $company_name = get_post_meta($speaker->ID, 'company_name', true);
        ?>
            <a href="<?php echo esc_url(get_permalink($speaker->ID)); ?>" class="speaker-row-container">
                <div class="speaker-row">
                    <div class="speaker-img-container">
                        <?php echo get_the_post_thumbnail($speaker->ID, 'thumbnail', [
                            'title' => esc_attr($speaker->post_title),
                            'class' => 'img-speaker',
                            'alt' => esc_attr($speaker->post_title)
                        ]); ?>
                    </div>
                    <div class="speaker-details">
                        <h2><?php echo esc_html($speaker->post_title); ?></h2>
                        <?php if (!empty($speaker_title)) : ?>
                            <p class="position_title"><?php echo esc_html($speaker_title); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($company_name)) : ?>
                            <p class="speaker_company"><?php echo esc_html($company_name); ?></p>
                        <?php endif; ?>
                        <span class="hidden speaker_title">
                            <?php 
                            echo esc_html($speaker->post_title);
                            if (!empty($speaker_title)) {
                                echo ', ' . esc_html($speaker_title);
                            }
                            if (!empty($company_name)) {
                                echo ', ' . esc_html($company_name);
                            }
                            ?>
                        </span>
                        <span class="hidden desc">
                            <?php 
                            $content = apply_filters('the_content', $speaker->post_content);
                            echo $content; 
                            ?>
                        </span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}




/**
 * FSE Session Template Shortcodes and functionality
 */





 // Session Navigation Links
function session_navigation_shortcode() {
    if (!is_singular()) return '';
    
    $output = '';
    
    // Previous post link
    $prev_post = get_adjacent_post(true, '', true, 'category_name');
    if (!empty($prev_post)) {
        $output .= '<a href="' . esc_url($prev_post->guid) . '">' . esc_html($prev_post->post_title) . '</a>';
    }
    
    // Next post link  
    $next_post = get_adjacent_post(true, '', false, 'category_name');
    if (!empty($next_post)) {
        $output .= '<a href="' . esc_url($next_post->guid) . '">' . esc_html($next_post->post_title) . '</a>';
    }
    
    return $output;
}
add_shortcode('session_navigation', 'session_navigation_shortcode');

// Previous Link with Custom Ordering
function session_prev_link_shortcode() {
    if (!is_singular()) return '';
    
    if (function_exists('tyler_previous_post_link_plus')) {
        ob_start();
        tyler_previous_post_link_plus(array(
            'order_by' => 'custom', 
            'meta_key' => 'session_date', 
            'format' => '%link', 
            'link' => '<i class="icon-angle-left"></i>'
        ));
        return ob_get_clean();
    }
    
    return '';
}
add_shortcode('session_prev_link', 'session_prev_link_shortcode');

// Schedule Link
function session_schedule_link_shortcode() {
    if (!is_singular()) return '';
    
    $post_type = get_post_type();
    $post_type_obj = get_post_type_object($post_type);
    $schedule_page = '';
    
    if ($post_type_obj && isset($post_type_obj->rewrite['slug'])) {
        $schedule_page = $post_type_obj->rewrite['slug'];        
    }
    
    return '<a href="/' . esc_html($schedule_page) . '" title="' . esc_attr__('All', 'tyler') . '"><i class="icon-th-large"></i></a>';
}
add_shortcode('session_schedule_link', 'session_schedule_link_shortcode');

// Next Link with Custom Ordering
function session_next_link_shortcode() {
    if (!is_singular()) return '';
    
    if (function_exists('tyler_next_post_link_plus')) {
        ob_start();
        tyler_next_post_link_plus(array(
            'order_by' => 'custom', 
            'meta_key' => 'session_date', 
            'format' => '%link', 
            'link' => '<i class="icon-angle-right"></i>'
        ));
        return ob_get_clean();
    }
    
    return '';
}
add_shortcode('session_next_link', 'session_next_link_shortcode');

// Session Tracks
function session_tracks_shortcode() {
    if (!is_singular()) return '';
    
    // Get session fields (you'll need to adapt this to your setup)
    $single_session_fields = array();
    if (class_exists('EF_Query_Manager')) {
        $single_session_fields = EF_Query_Manager::get_single_session_fields();
    }
    
    if (empty($single_session_fields['tracks'])) return '';
    
    $tracks = $single_session_fields['tracks'];
    $schedule_page = '';
    
    $post_type = get_post_type();
    $post_type_obj = get_post_type_object($post_type);
    
    if ($post_type_obj && isset($post_type_obj->rewrite['slug'])) {
        $schedule_page = $post_type_obj->rewrite['slug'];        
    }
    
    $output = '<div>';
    
    foreach ($tracks as $track) {
        $style = '';
        if (!empty($track->color)) {
            $style = "style='background-color: " . esc_attr($track->color) . ";'";
        }
        
        $output .= '<a href="/' . esc_html($schedule_page) . '?track=' . esc_attr($track->term_id) . '" class="track-buttons">';
        $output .= '<span class="single-session-link btn btn-primary" ' . $style . '>';
        $output .= esc_html($track->name);
        $output .= '</span>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('session_tracks', 'session_tracks_shortcode');

// Session Details (Date, Time)
function session_details_shortcode() {
    if (!is_singular()) return '';
    
    // Get session fields
    $single_session_fields = array();
    if (class_exists('EF_Query_Manager')) {
        $single_session_fields = EF_Query_Manager::get_single_session_fields();
    }
    
    $output = '';
    
    // Date
    if (!empty($single_session_fields['date'])) {
        $date = $single_session_fields['date'];
        $output .= '<span class="date">' . __('Date:', 'tyler') . ' <strong>' . date_i18n(get_option('date_format'), $date) . '</strong></span>';
    }
    
    // Time
    if (!empty($single_session_fields['time']) && !empty($single_session_fields['end_time'])) {
        $time = $single_session_fields['time'];
        $end_time = $single_session_fields['end_time'];
        $output .= '<span class="time">' . __('Time:', 'tyler') . ' <strong>' . esc_html($time) . ' - ' . esc_html($end_time) . '</strong></span>';
    }
    
    return $output;
}
add_shortcode('session_details', 'session_details_shortcode');

// Session Speakers
function session_speakers_shortcode() {
    if (!is_singular()) return '';
    
    // Get session fields
    $single_session_fields = array();
    if (class_exists('EF_Query_Manager')) {
        $single_session_fields = EF_Query_Manager::get_single_session_fields();
    }
    
    if (empty($single_session_fields['speakers_list'])) return '';
    
    $speakers_list = $single_session_fields['speakers_list'];
    $output = '<span class="speakers-thumbs">';
    
    foreach ($speakers_list as $speaker_id) {
        $post_meta_data = get_post_custom($speaker_id);
        $speaker_name = get_the_title($speaker_id);
        $speaker_name_only = get_the_title($speaker_id);
        $speaker_title = '';
        $speaker_company = '';
        
        if (!empty($post_meta_data['speaker_title'][0])) {
            $speaker_title = $post_meta_data['speaker_title'][0];
            $speaker_name .= ", " . $speaker_title;
        }
        
        if (!empty($post_meta_data['company_name'][0])) {
            $speaker_company = $post_meta_data['company_name'][0];
            $speaker_name .= ", " . $speaker_company;
        }
        
        $featured_class = '';
        if (!empty($post_meta_data['speaker_keynote'][0]) && $post_meta_data['speaker_keynote'][0] == 1) {
            $featured_class = ' featured';
        }
        
        $output .= '<a href="' . esc_url(get_permalink($speaker_id)) . '" class="speaker' . $featured_class . '">';
        $output .= get_the_post_thumbnail($speaker_id, 'post-thumbnail', array('title' => get_the_title($speaker_id)));
        $output .= '<span class="name">';
        $output .= '<span><span class="speaker_name">' . esc_html($speaker_name_only) . '</span>';
        $output .= '<span class="speaker_title"><br>' . esc_html($speaker_title) . '</span>';
        $output .= '<span class="speaker_company"><br>' . esc_html($speaker_company) . '</span></span>';
        $output .= '</span>';
        $output .= '<span class="hidden speaker_title">' . esc_html($speaker_name) . '</span>';



        $output .= '</a>';
    }
    
    $output .= '</span>';
    
    return $output;
}
add_shortcode('session_speakers', 'session_speakers_shortcode');

// Session Registration
function session_registration_shortcode() {
    if (!is_singular()) return '';
    
    $registration_code = get_post_meta(get_the_ID(), 'session_registration_code', true);
    $registration_title = get_post_meta(get_the_ID(), 'session_registration_title', true);
    $registration_text = get_post_meta(get_the_ID(), 'session_registration_text', true);
    
    if (empty($registration_code)) return '';
    
    $output = '<div>';
    $output .= '<h2 class="text-center">' . esc_html($registration_title) . '</h2>';
    $output .= '<div>' . wp_kses_post($registration_code) . '</div>';
    
    if (!empty($registration_text)) {
        $output .= '<p>' . esc_html($registration_text) . '</p>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('session_registration', 'session_registration_shortcode');

// Helper function to get session fields if EF_Query_Manager is not available
function get_fallback_session_fields() {
    $post_id = get_the_ID();
    
    return array(
        'date' => get_post_meta($post_id, 'session_date', true),
        'time' => get_post_meta($post_id, 'session_time', true),
        'end_time' => get_post_meta($post_id, 'session_end_time', true),
        'speakers_list' => get_post_meta($post_id, 'session_speakers', true),
        'tracks' => wp_get_post_terms($post_id, 'session_track'), // Adjust taxonomy name as needed
        'locations' => wp_get_post_terms($post_id, 'session_location') // Adjust taxonomy name as needed
    );
}



/**
 * Add speaker meta fields functionality for FSE template
 */

// Enqueue script for speaker archive functionality
function enqueue_speaker_archive_script() {
    if (is_post_type_archive('speaker')) {
        wp_enqueue_script(
            'speaker-archive-script',
            get_stylesheet_directory_uri() . '/js/speaker-archive.js',
            array('jquery'),
            '1.0.2', // Updated version
            true
        );
        
        // Get all speakers data and pass to JavaScript
        $speakers_query = new WP_Query(array(
            'post_type' => 'speaker',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        
        $speakers_data = array();
        if ($speakers_query->have_posts()) {
            while ($speakers_query->have_posts()) {
                $speakers_query->the_post();
                $post_id = get_the_ID();
                
                // Get all meta data for debugging
                $all_meta = get_post_meta($post_id);
                $post_meta_data = get_post_custom($post_id);

                    
                
                // Try multiple ways to get the custom fields
                $speaker_title = '';
                $company_name = '';
                
                // Method 1: Original way with get_post_custom
                if (isset($post_meta_data['speaker_title'][0])) {
                    $speaker_title = $post_meta_data['speaker_title'][0];
                }
                if (isset($post_meta_data['company_name'][0])) {
                    $company_name = $post_meta_data['company_name'][0];
                }
                
                // Method 2: Try with get_post_meta if above didn't work
                if (empty($speaker_title)) {
                    $speaker_title = get_post_meta($post_id, 'speaker_title', true);
                }
                if (empty($company_name)) {
                    $company_name = get_post_meta($post_id, 'company_name', true);
                }
                
                // Method 3: Try with underscore prefix (some plugins use this)
                if (empty($speaker_title)) {
                    $speaker_title = get_post_meta($post_id, '_speaker_title', true);
                }
                if (empty($company_name)) {
                    $company_name = get_post_meta($post_id, '_company_name', true);
                }
                
                // Method 4: Try ACF fields if ACF is active
                if (function_exists('get_field')) {
                    if (empty($speaker_title)) {
                        $speaker_title = get_field('speaker_title', $post_id);
                    }
                    if (empty($company_name)) {
                        $company_name = get_field('company_name', $post_id);
                    }
                }

                    $post_meta_data = get_post_custom();
                    $speaker_title = $post_meta_data['speaker_title'][0] ?? '';
                    $company_name = $post_meta_data['company_name'][0] ?? '';
                
                $speakers_data[] = array(
                    'id' => $post_id,
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'speaker_title' => $speaker_title ?: '',
                    'company_name' => $company_name ?: '',
                    'content' => get_the_content(),
                    // Debug data
                    'all_meta_keys' => array_keys($all_meta),
                    'post_meta_data_keys' => array_keys($post_meta_data)
                );
            }
            wp_reset_postdata();
        }
        
        // Localize script with speakers data
        wp_localize_script('speaker-archive-script', 'speakersData', array(
            'speakers' => $speakers_data,
            'debug' => true
        ));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_speaker_archive_script');

// Add custom body class for speaker archive
function add_speaker_archive_body_class($classes) {
    if (is_post_type_archive('speakers')) {
        $classes[] = 'speaker-archive-page';
    }
    return $classes;
}
add_filter('body_class', 'add_speaker_archive_body_class');








/**
 * Shortcode approach for FSE Schedule Template
 * 
 * 
 * 
 * 
 */

// Schedule Filter Shortcode - Updated to handle multiple session types
function tyler_schedule_filter_shortcode($atts) {
    $atts = shortcode_atts(array(
        'session_type' => 'sessiontwo' // Default to sessiontwo for backward compatibility
    ), $atts);
    
    $session_type = $atts['session_type'];
    
    // Get data using your existing functions
    $session_dates = function_exists('sandeep_get_session_dates') ? sandeep_get_session_dates($session_type) : array();
    $session_tracks = function_exists('sandeep_get_terms_for_post_type') ? sandeep_get_terms_for_post_type('session-track', $session_type) : array();
    $session_locations = function_exists('sandeep_get_terms_for_post_type') ? sandeep_get_terms_for_post_type('session-location', $session_type) : array();
    
    // Generate unique container ID for multiple instances
    $container_id = 'schedule-' . $session_type . '-' . uniqid();
    
    ob_start();
    ?>
    <div class="schedule-container" data-session-type="<?php echo esc_attr($session_type); ?>" id="<?php echo esc_attr($container_id); ?>">
        <ul class="nav nav-tabs pull-right">
            <?php if (!empty($session_tracks)) { ?>
            <li>
                <a href="javascript:void(0)"><?php _e('Filter by track', 'tyler'); ?></a><ul><li><a href="#" data-track="0"><?php _e('All', 'tyler'); ?></a></li>
                    <?php foreach ($session_tracks as $session_track) { ?>
                        <li><a href="#" data-track="<?php echo esc_attr($session_track->term_id); ?>" data-workshop=""><?php echo esc_html($session_track->name); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            
            <?php if (!empty($session_locations)) { ?>
            <li>
                <a href="javascript:void(0)"><?php _e('Filter by location', 'tyler'); ?></a><ul><li><a href="#" data-location="0"><?php _e('All', 'tyler'); ?></a></li>
                    <?php foreach ($session_locations as $session_location) { ?>
                        <li><a href="#" data-location="<?php echo esc_attr($session_location->term_id); ?>"><?php echo esc_html($session_location->name); ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            
            <li class="active">
                <a href="javascript:void(0)" data-timestamp="0"><?php _e('Filter by days', 'tyler'); ?></a><?php if (!empty($session_dates)) { ?><ul><li><a href="#" data-timestamp="0"><?php _e('All', 'tyler'); ?></a></li>
                        <?php foreach ($session_dates as $session_date) { ?>
                        <?php if ($session_date->meta_value != "1580688000") { ?>
                            <li><a href="#" data-timestamp="<?php echo esc_attr($session_date->meta_value); ?>"><?php echo esc_html(date_i18n(get_option('date_format'), $session_date->meta_value)); ?></a></li>
                        <?php }} ?>
                    </ul>
                <?php } ?>
            </li>
        </ul>
        
        <div class="sessions list" data-session-type="<?php echo esc_attr($session_type); ?>"></div>
    </div>
    
    <script type="text/javascript">
    (function($) {
        // Set session_type for this specific container
        $(document).ready(function() {
            var container = $('#<?php echo esc_js($container_id); ?>');
            var session_type = '<?php echo esc_js($session_type); ?>';
            
            // Store session_type in container for later use
            container.data('session-type', session_type);
            
            // Update global session_type when this container is active
            window.session_type = session_type;
            
            // Initialize with this session type
            if (typeof updateSchedule === 'function') {
                updateSchedule(null, null, null);
            }
        });
    })(jQuery);
    </script>
    <?php
    
    return ob_get_clean();
}
add_shortcode('tyler_schedule_filter', 'tyler_schedule_filter_shortcode');

// Loader Image Shortcode
function tyler_loader_image_shortcode() {
    $image_url = get_template_directory_uri() . '/assets/images/ajax-loader.gif';
    return '<img alt="Loading" src="' . esc_url($image_url) . '" width="32" height="32" align="center" />';
}
add_shortcode('tyler_loader_image', 'tyler_loader_image_shortcode');

// AJAX handler for loading sessions (replicates your theme's AJAX functionality)
function tyler_load_sessions_ajax() {
    $session_type = isset($_POST['session_type']) ? sanitize_text_field($_POST['session_type']) : 'sessiontwo';
    $track = isset($_POST['track']) ? intval($_POST['track']) : 0;
    $location = isset($_POST['location']) ? intval($_POST['location']) : 0;
    $timestamp = isset($_POST['timestamp']) ? sanitize_text_field($_POST['timestamp']) : 0;
    
    // Build WP_Query args based on filters
    $args = array(
        'post_type' => $session_type,
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(),
        'tax_query' => array(),
        'orderby' => 'meta_value_num',
        'meta_key' => 'session_date',
        'order' => 'ASC'
    );
    
    // Add track filter
    if ($track > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'session-track',
            'field' => 'term_id',
            'terms' => $track
        );
    }
    
    // Add location filter
    if ($location > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'session-location',
            'field' => 'term_id',
            'terms' => $location
        );
    }
    
    // Add timestamp filter
    if ($timestamp > 0) {
        $args['meta_query'][] = array(
            'key' => 'session_date',
            'value' => $timestamp,
            'compare' => '='
        );
    }
    
    $sessions = new WP_Query($args);
    
    $current_date = '';
    $output = '';
    
    if ($sessions->have_posts()) {
        while ($sessions->have_posts()) {
            $sessions->the_post();
            
            // Get session meta data
            $session_date = get_post_meta(get_the_ID(), 'session_date', true);
            $session_time = get_post_meta(get_the_ID(), 'session_time', true);
            $session_end_time = get_post_meta(get_the_ID(), 'session_end_time', true);
            $session_locations = wp_get_post_terms(get_the_ID(), 'session-location');
            $session_sponsor = get_post_meta(get_the_ID(), 'session_sponsor', true);
            
            // Format date for display
            $display_date = $session_date ? date_i18n('F j, Y', $session_date) : '';
            
            // Add day floating header if date changed
            if ($display_date && $display_date !== $current_date) {
                $current_date = $display_date;
                $output .= '<div class="followWrap" style="height: 60px;"><div class="day-floating"><span>' . esc_html($display_date) . '</span></div></div>';
            }
            
            // Format time display
            $time_display = '';
            if ($session_time) {
                $time_display = esc_html($session_time);
                if ($session_end_time) {
                    $time_display .= ' - ' . esc_html($session_end_time);
                }
            }
            
            // Location display
            $location_display = '';
            if (!empty($session_locations)) {
                $location_display = esc_html($session_locations[0]->name);
            }
            
            // Get speakers - try different meta field names and handle multiple formats
            $speakers_list = get_post_meta(get_the_ID(), 'speakers_list', true);
            if (empty($speakers_list)) {
                $speakers_list = get_post_meta(get_the_ID(), 'speakers', true);
            }
            if (empty($speakers_list)) {
                $speakers_list = get_post_meta(get_the_ID(), 'session_speakers', true);
            }
            
            // Also try getting all speakers meta (in case stored as multiple meta entries)
            if (empty($speakers_list)) {
                $all_speakers = get_post_meta(get_the_ID(), 'speakers_list');
                if (!empty($all_speakers)) {
                    $speakers_list = $all_speakers;
                }
            }
            
            $speakers_html = '';
            if (!empty($speakers_list)) {
                $speakers_html .= '<span class="speakers-thumbs">';
                
                // Handle different formats of speakers data
                if (is_array($speakers_list)) {
                    // If it's an array, could be array of IDs or array of arrays
                    foreach ($speakers_list as $speaker_data) {
                        if (is_array($speaker_data)) {
                            // Handle serialized/array data
                            foreach ($speaker_data as $speaker_id) {
                                if (is_numeric($speaker_id)) {
                                    $speakers_html .= tyler_build_speaker_html($speaker_id);
                                }
                            }
                        } elseif (is_numeric($speaker_data)) {
                            // Direct speaker ID
                            $speakers_html .= tyler_build_speaker_html($speaker_data);
                        } elseif (is_string($speaker_data)) {
                            // String with comma-separated IDs
                            $speaker_ids = explode(',', $speaker_data);
                            foreach ($speaker_ids as $speaker_id) {
                                $speaker_id = trim($speaker_id);
                                if (is_numeric($speaker_id)) {
                                    $speakers_html .= tyler_build_speaker_html($speaker_id);
                                }
                            }
                        }
                    }
                } elseif (is_string($speakers_list)) {
                    // If it's a string with IDs separated by comma
                    $speaker_ids = explode(',', $speakers_list);
                    foreach ($speaker_ids as $speaker_id) {
                        $speaker_id = trim($speaker_id);
                        if (is_numeric($speaker_id)) {
                            $speakers_html .= tyler_build_speaker_html($speaker_id);
                        }
                    }
                } elseif (is_numeric($speakers_list)) {
                    // Single speaker ID
                    $speakers_html .= tyler_build_speaker_html($speakers_list);
                }
                
                $speakers_html .= '</span>';
            }
            
            // Build the session HTML matching the exact structure
            $output .= '<div class="session">';
            $output .= '<span class="time">' . $time_display . '</span>';
            $output .= '<div class="session-inner">';
            $output .= '<img class="session_sponsor" src="' . esc_url($session_sponsor) . '" style="">';
            $output .= '<a href="' . get_permalink() . '" class="title"><span>' . get_the_title() . '</span></a>';
            $output .= '<span class="location">' . $location_display . '</span>';
            $output .= $speakers_html;
            $output .= '<a href="' . get_permalink() . '" class="more">More info <i class="icon-angle-right"></i></a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        wp_reset_postdata();
    } else {
        $output = '<div class="no-sessions"><p>' . __('No sessions found for the selected criteria.', 'tyler') . '</p></div>';
    }
    
    echo $output;
    wp_die();
}

// Helper function to build speaker HTML
function tyler_build_speaker_html($speaker_id) {
    if (!is_numeric($speaker_id)) return '';
    
    $speaker_post = get_post($speaker_id);
    if (!$speaker_post) return '';
    
    $speaker_image = get_the_post_thumbnail($speaker_id, array(54, 54), array(
        'class' => 'attachment-54x54 size-54x54 wp-post-image',
        'loading' => 'lazy',
        'decoding' => 'async'
    ));
    
    $speaker_name = get_the_title($speaker_id);
    $speaker_title = get_post_meta($speaker_id, 'speaker_title', true);
    $speaker_company = get_post_meta($speaker_id, 'speaker_company', true);
    $speaker_desc = get_post_meta($speaker_id, 'speaker_description', true);
    
    // If no custom fields, try content
    if (empty($speaker_desc)) {
        $speaker_desc = get_post_field('post_content', $speaker_id);
    }
    
    $html = '<a href="' . get_permalink($speaker_id) . '" class="speaker">';
    
    if ($speaker_image) {
        $html .= $speaker_image;
    } else {
        // Fallback placeholder image
        $html .= '<img width="54" height="54" src="" class="attachment-54x54 size-54x54 wp-post-image" alt="' . esc_attr($speaker_name) . '">';
    }
    
    $html .= '<span class="name"><span class="text-fit">';
    $html .= '<span class="speaker_name">' . esc_html($speaker_name) . '</span>';
    if ($speaker_title) {
        $html .= '<span class="speaker_title"><br>' . esc_html($speaker_title) . '</span>';
    }
    if ($speaker_company) {
        $html .= '<span class="speaker_company"><br>' . esc_html($speaker_company) . '</span>';
    }
    $html .= '</span></span>';
    
    // Hidden speaker title (for tooltips/popups)
    $html .= '<span class="hidden speaker_title">';
    $html .= '<span class="speaker_name">' . esc_html($speaker_name) . '</span>';
    if ($speaker_title) {
        $html .= '<span class="speaker_title"><br>' . esc_html($speaker_title) . '</span>';
    }
    if ($speaker_company) {
        $html .= '<span class="speaker_company"><br>' . esc_html($speaker_company) . '</span>';
    }
    $html .= '</span>';
    
    // Hidden description
    if ($speaker_desc) {
        $html .= '<span class="hidden desc">';
        if (strpos($speaker_desc, '<') !== false) {
            // Already has HTML
            $html .= wp_kses_post($speaker_desc);
        } else {
            // Plain text, wrap in div
            $html .= '<div class="elementToProof">' . wp_kses_post(wpautop($speaker_desc)) . '</div>';
        }
        $html .= '</span>';
    }
    
    $html .= '</a>';
    
    return $html;
}
add_action('wp_ajax_load_sessions', 'tyler_load_sessions_ajax');
add_action('wp_ajax_nopriv_load_sessions', 'tyler_load_sessions_ajax');

// Enqueue AJAX script
function tyler_enqueue_ajax_script() {
    if (is_page_template('page-schedule-session-two.html') || has_shortcode(get_post()->post_content, 'tyler_sessions_list')) {
        wp_enqueue_script('jquery');
        wp_localize_script('jquery', 'ajax_object', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}
add_action('wp_enqueue_scripts', 'tyler_enqueue_ajax_script');