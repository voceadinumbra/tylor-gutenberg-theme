<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Registration Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Registration_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Registration_Widget extends WP_Widget {

	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Registration_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_registration', 'description' => __( 'Shows registration information & ticket display', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_registration', $widget_name . __( ' Registration', 'dxef' ), $widget_ops );
		
	}
	
	/**
	 * Output of Widget Content
	 * 
	 * Handle to outputs the
	 * content of the widget
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		
		$registrationtitle 				= isset( $instance['registrationtitle'] ) ? $instance['registrationtitle'] : '';
	    $registrationsubtitle 			= isset( $instance['registrationsubtitle'] ) ? $instance['registrationsubtitle'] : '';
	    $registrationtext 				= isset( $instance['registrationtext'] ) ? $instance['registrationtext'] : '';
	    $registrationeventbrite 		= isset( $instance['registrationeventbrite'] ) ? $instance['registrationeventbrite'] : '';
	    $registrationshowtopmenu 		= isset( $instance['registrationshowtopmenu'] ) ? $instance['registrationshowtopmenu'] : '';
	    $registrationshowcalltoaction	= isset( $instance['registrationshowcalltoaction'] ) ? $instance['registrationshowcalltoaction'] : '';
	
	    echo stripslashes($args['before_widget']);
	    echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $registrationtitle,
            'subtitle' => $registrationsubtitle,
			'registrationtext' => $registrationtext,
			'registrationeventbrite' => $registrationeventbrite,
			'registrationshowtopmenu' => $registrationshowtopmenu,
			'registrationshowcalltoaction' => $registrationshowcalltoaction
        ));
	    echo stripslashes($args['after_widget']);
	}
	/**
	 * Update Widget Setting
	 * 
	 * Handle to updates the widget control options
	 * for the particular instance of the widget
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		if (isset($_POST['submitted'])) {
			update_option('ef_registration_widget_title', isset( $new_instance['registrationtitle'] ) ? $new_instance['registrationtitle'] : '' );
			update_option('ef_registration_widget_subtitle', isset( $new_instance['registrationsubtitle'] ) ? $new_instance['registrationsubtitle'] : '' );
			update_option('ef_registration_widget_text', isset( $new_instance['registrationtext'] ) ? $new_instance['registrationtext'] : '' );
			update_option('ef_registration_widget_eventbrite', isset( $new_instance['registrationeventbrite'] ) ? $new_instance['registrationeventbrite'] : '' );
			if ( isset( $new_instance['registrationshowtopmenu'] ) ) {
				update_option( 'ef_registration_widget_showtopmenu', 1 );
			} else {
				update_option( 'ef_registration_widget_showtopmenu', 0 );
			}
		
			if ( isset( $new_instance['registrationshowcalltoaction'] ) ) {
				update_option( 'ef_registration_widget_showcalltoaction', 1 );
			} else {
				update_option( 'ef_registration_widget_showcalltoaction', 0 );
			}
		}

		$instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
		$instance['registrationtitle']	= strip_tags( $new_instance['registrationtitle'] );
		$instance['registrationsubtitle']	= strip_tags( $new_instance['registrationsubtitle'] );
		$instance['registrationtext']	= $new_instance['registrationtext'];
		$instance['registrationeventbrite']	=  $new_instance['registrationeventbrite'];
		$instance['registrationshowtopmenu']	= strip_tags( $new_instance['registrationshowtopmenu'] );
		$instance['registrationshowcalltoaction']	= strip_tags( $new_instance['registrationshowcalltoaction'] );
		
		return $instance;
	}
	
	/**
	 * Display Widget Form
	 * 
	 * Displays the widget
	 * form in the admin panel
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function form( $instance ) {
	
		$registrationtitle 				= isset( $instance['registrationtitle'] ) ? $instance['registrationtitle'] : '';
	    $registrationsubtitle 			= isset( $instance['registrationsubtitle'] ) ? $instance['registrationsubtitle'] : '';
	    $registrationtext 				= isset( $instance['registrationtext'] ) ? $instance['registrationtext'] : '';
	    $registrationeventbrite 		= isset( $instance['registrationeventbrite'] ) ? $instance['registrationeventbrite'] : '';
	    $registrationshowtopmenu 		= isset( $instance['registrationshowtopmenu'] ) ? $instance['registrationshowtopmenu'] : '';
	    $registrationshowcalltoaction	= isset( $instance['registrationshowcalltoaction'] ) ? $instance['registrationshowcalltoaction'] : '';?>
	    
	    <em><?php _e('Title:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'registrationtitle' ); ?>" value="<?php echo stripslashes($registrationtitle); ?>" />
	    <br /><br />
	    <em><?php _e('Subtitle:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'registrationsubtitle' ); ?>" value="<?php echo stripslashes($registrationsubtitle); ?>" />
	    <br /><br />
	    <em><?php _e('Main Text:', 'dxef'); ?></em><br />
	    <textarea rows="10" class="widefat" name="<?php echo $this->get_field_name( 'registrationtext' ); ?>"><?php echo esc_html($registrationtext); ?></textarea>
	    <br /><br />
	    <em><?php _e('Registration Embed Code:', 'dxef'); ?></em><br />
	    <textarea rows="10" class="widefat" name="<?php echo $this->get_field_name( 'registrationeventbrite' ); ?>"><?php echo esc_html($registrationeventbrite); ?></textarea>
	    <br /><br />
	    <em><?php _e('Show in top menu:', 'dxef'); ?></em><br />
	    <input type="checkbox" name="<?php echo $this->get_field_name( 'registrationshowtopmenu' ); ?>" value="1" <?php checked( $registrationshowtopmenu, 1 ); ?> />
	    <br /><br />
	    <em><?php _e('Show call to action:', 'dxef'); ?></em><br />
	    <input type="checkbox" name="<?php echo $this->get_field_name( 'registrationshowcalltoaction' ); ?>" value="1" <?php checked( $registrationshowcalltoaction, 1 ); ?> />
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" />
	    <?php
	}
}

add_filter('wp_nav_menu_items', 'ef_wp_nav_menu_items', 10, 2);

/**
 * Add Register Menu In MenuBar
 * 
 * Handle to displays register
 * menu in menuBar
 * 
 * @package Event Framework
 * @since 1.0.0
 */
function ef_wp_nav_menu_items( $items, $args ) {
	
	$widget_ef_registration = get_option('widget_ef_registration');
	
	if ( $args->theme_location == 'primary' && is_active_widget( false, false, 'ef_registration' ) && is_array( $widget_ef_registration ) ) {
		foreach( $widget_ef_registration as $key => $reg_widget ) {
	        if( empty( $reg_widget ) ) {
	        	unset( $widget_ef_registration[$key] );
	        	update_option( 'widget_ef_registration', $widget_ef_registration ); 
	        }
			elseif( isset( $reg_widget['registrationshowtopmenu'] ) && $reg_widget['registrationshowtopmenu'] == 1 ) {
				$items .= '<li class="menu-item register"><a href="' . home_url( '/' ) . '#tile_registration">' . __('Register', 'dxef') . '</a></li>';
				break;
			}
		}
	}
	
	return $items;
}

// Register Widget
register_widget( 'Ef_Registration_Widget' );