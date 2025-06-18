<?php

register_post_type('speaker', array(
    'labels'             => array(
        'name'               => __('Speakers', 'dxef'),
        'singular_name'      => __('Speaker', 'dxef'),
        'add_new'            => __('Add New', 'dxef'),
        'add_new_item'       => __('Add New Speaker', 'dxef'),
        'edit_item'          => __('Edit Speaker', 'dxef'),
        'new_item'           => __('New Speaker', 'dxef'),
        'all_items'          => __('All Speakers', 'dxef'),
        'view_item'          => __('View Speaker', 'dxef'),
        'search_items'       => __('Search Speakers', 'dxef'),
        'not_found'          => __('No Speakers found', 'dxef'),
        'not_found_in_trash' => __('No Speakers found in trash', 'dxef'),
        'menu_name'          => __('Speakers', 'dxef')
    ),
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_rest' => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array(
        'slug' => 'speakers'
    ),
    'capability_type'    => 'post',
    'has_archive'        => false,
    'hierarchical'       => false,
    'menu_position'      => 5,
    'supports'           => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail',
        'page-attributes'
    )
));

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */
function tyler_speaker_updated_messages($messages) {

    global $post, $post_ID;

    $messages['speaker'] = array(
        0  => '', // Unused. Messages start at index 1.
        1  => sprintf(__('Speaker updated. <a href="%s">View Speaker</a>', 'wpSpeaker'), esc_url(get_permalink($post_ID))),
        2  => __('Custom field updated.', 'wpSpeaker'),
        3  => __('Custom field deleted.', 'wpSpeaker'),
        4  => __('Speaker updated.', 'wpSpeaker'),
        /* translators: %s: date and time of the revision */
        5  => isset($_GET['revision']) ? sprintf(__('Speaker restored to revision from %s', 'wpSpeaker'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => sprintf(__('Speaker published. <a href="%s">View Speaker</a>', 'wpSpeaker'), esc_url(get_permalink($post_ID))),
        7  => __('Speaker saved.', 'wpSpeaker'),
        8  => sprintf(__('Speaker submitted. <a target="_blank" href="%s">Preview Speaker</a>', 'wpSpeaker'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        9  => sprintf(__('Speaker scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Speaker</a>', 'wpSpeaker'),
                // translators: Publish box date format, see http://php.net/date
                date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
        10 => sprintf(__('Speaker draft updated. <a target="_blank" href="%s">Preview Speaker</a>', 'wpSpeaker'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
    );

    return $messages;
}

add_filter('post_updated_messages', 'tyler_speaker_updated_messages');
