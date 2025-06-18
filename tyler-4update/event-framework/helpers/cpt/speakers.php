<?php

class EF_Speakers_Helper {
	
	private static $field_types = array(
		'title' => 'speakers_full_title_',
		'order' => 'speakers_full_order_',
		'keynote' => 'speaker_keynote'
	);
	
	public static function get_meta( $id, $meta_key, $order = null ) {
		echo self::print_meta( $id, $meta_key, $order ); 
	}
	
	public static function print_meta( $id, $meta_key, $order = null ) {
		$post_meta_key = $meta_key;
		
		if ( isset( self::$field_types[$meta_key] ) ) {
			$post_meta_key = self::$field_types[$meta_key];
		}
		
		if ( ! is_null( $order ) ) {
			$post_meta_key .= $order;
		}
		
		return get_post_meta(get_the_ID(), $post_meta_key, true);
	}
	
	public static function get_speakers_url() {
		$full_speakers_page = get_posts(array(
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => 'speakers.php'
		));
		
		$full_speakers_url = '';
		
		if ( $full_speakers_page && count( $full_speakers_page ) > 0 )
			$full_speakers_url = get_permalink( $full_speakers_page[0]->ID );
		
		return $full_speakers_url;
	}
	
	public static function ef_speaker_sessions_posts_fields( $sql ) {
		global $wpdb;
		return $sql . ", $wpdb->postmeta.meta_value as date, mt2.meta_value as time";
	}
	
	public static function ef_speaker_sessions_posts_orderby( $sql ) {
		return $sql . ", mt2.meta_value ASC";
	}
	
}