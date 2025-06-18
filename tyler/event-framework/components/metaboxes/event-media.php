<?php


add_action( 'add_meta_boxes', 'ef_event_media_metabox' );

function ef_event_media_metabox() {
	add_meta_box('metabox-media', __('Media Content', 'dxef'), 
		'ef_metabox_media', 'event-media', 'normal', 'high');
}

function ef_metabox_media($post) {
	$video_code = get_post_meta($post->ID, 'video_code', true);
	$video_type = get_post_meta($post->ID, 'video_type', true);
	?>
    <p>
        <label for="video_code"><?php _e('Video Code', 'dxef'); ?></label>
        <input type="text" class="widefat" id="video_code" name="video_code" value="<?php echo $video_code; ?>" />
    </p>
    <p>
        <label for="video_type"><?php _e('Video Type', 'dxef'); ?></label>
        <select class="widefat" id="video_type" name="video_type">
            <option value="youtube" <?php if ($video_type == 'youtube') echo 'selected="selected"'; ?>>Youtube</option>
            <option value="vimeo" <?php if ($video_type == 'vimeo') echo 'selected="selected"'; ?>>Vimeo</option>
        </select>
    </p>
    <?php
}

add_action( 'save_post', 'ef_event_media_save_post' );

function ef_event_media_save_post( $id ) {
	if( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'event-media' ) {
		if (isset($_POST['video_code']))
			update_post_meta($id, 'video_code', $_POST['video_code']);
		
		if (isset($_POST['video_type']))
			update_post_meta($id, 'video_type', $_POST['video_type']);
	}
}