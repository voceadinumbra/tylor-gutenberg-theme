<?php

add_action( 'wp_ajax_nopriv_get_media', 'ef_ajax_get_media' );
add_action( 'wp_ajax_get_media', 'ef_ajax_get_media' );

function ef_ajax_get_media() {
	$ret = array(
		'media' => array(),
	);

	if (isset($_POST['data-id']) && ctype_digit($_POST['data-id'])) {
		$term_id = intval($_POST['data-id']);
		$media_loop_args = array(
				'post_type' => 'event-media',
				'post_status' => 'publish',
				'nopaging' => true
		);

		if ($term_id > 0)
			$media_loop_args['tax_query'] = array(
					array(
							'taxonomy' => 'media-type',
							'field' => 'id',
							'terms' => array($term_id)
					)
			);
		$media_loop = new WP_Query($media_loop_args);
		while ($media_loop->have_posts()) {
			$media_loop->the_post();
			$video_code = get_post_meta(get_the_ID(), 'video_code', true);
			$ret['media'][] = array(
					'post_title' => get_the_title(),
					'post_content' => get_the_content(),
					'post_image' => get_the_post_thumbnail(get_the_ID(), 'tyler-media'),
					'post_video_code' => $video_code,
					'post_video_attributes' => tyler_get_video_gallery_attribute(get_post_meta(get_the_ID(), 'video_type', true), $video_code),
					'post_image_big_url' => wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()), 'full'),
			);
		}
	}

	$ret['media'] = array_chunk($ret['media'], 8);
	echo json_encode($ret);
	die;
}