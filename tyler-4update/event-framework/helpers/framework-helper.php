<?php

/**
 * Helper class for Framework Specific settings
 * 
 * @author metodiew
 *
 */
class EF_Framework_Helper {
	
	private static $default_framework_name = 'Event Framework';
	
	/**
	 * 
	 */
	public static function get_framework_name( $name = false ) {
		
		return self::$default_framework_name;
	}
	
	/**
	 * Return current activated theme name
	 */
	public static function get_theme_name() {
		
		$theme_name = wp_get_theme();
				
		return $theme_name['Name'];
	}
	
	/**
	 * 
	 */
	public static function get_widget_name() {
		$widget_name = wp_get_theme();
		
		return $widget_name['Name'];
	}
	
	public static function get_framework_logo_src() {
		$img_path = EF_ASSETS_URL . 'images/logo-sample.png';
		
		return apply_filters( 'ef_theme_options_logo', $img_path );
	}
	
	
}