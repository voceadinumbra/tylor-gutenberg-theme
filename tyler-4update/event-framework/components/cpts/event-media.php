<?php
register_post_type ( 'event-media', array (
		'labels' => array (
				'name' => __ ( 'Event Media', 'dxef' ),
				'singular_name' => __ ( 'Event Media', 'dxef' ),
				'add_new' => __ ( 'Add New', 'dxef' ),
				'add_new_item' => __ ( 'Add New Event Media', 'dxef' ),
				'edit_item' => __ ( 'Edit Event Media', 'dxef' ),
				'new_item' => __ ( 'New Event Media', 'dxef' ),
				'view_item' => __ ( 'View Event Media', 'dxef' ),
				'search_items' => __ ( 'Search Event Media', 'dxef' ),
				'not_found' => __ ( 'No Event Media found', 'dxef' ),
				'not_found_in_trash' => __ ( 'No Event Media found in trash', 'dxef' ),
				'menu_name' => __ ( 'Event Media', 'dxef' ) 
		),
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => true,
		'query_var' => false,
		'supports' => array (
				'title',
				'editor',
				'thumbnail' 
		) 
) );

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */  
function tyler_event_media_updated_messages( $messages ) {

	global $post, $post_ID;

	$messages['event-media'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __( 'Event Media updated. <a href="%s">View Event Media</a>', 'wpEvent Media' ), esc_url( get_permalink($post_ID) ) ),
		2 => __( 'Custom field updated.', 'wpEvent Media' ),
		3 => __( 'Custom field deleted.', 'wpEvent Media' ),
		4 => __( 'Event Media updated.', 'wpEvent Media' ),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __( 'Event Media restored to revision from %s', 'wpEvent Media' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __( 'Event Media published. <a href="%s">View Event Media</a>', 'wpEvent Media' ), esc_url( get_permalink($post_ID) ) ),
		7 => __( 'Event Media saved.', 'wpEvent Media' ),
		8 => sprintf( __( 'Event Media submitted. <a target="_blank" href="%s">Preview Event Media</a>', 'wpEvent Media' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __( 'Event Media scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Event Media</a>', 'wpEvent Media'),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __( 'Event Media draft updated. <a target="_blank" href="%s">Preview Event Media</a>', 'wpEvent Media' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

add_filter( 'post_updated_messages', 'tyler_event_media_updated_messages' );