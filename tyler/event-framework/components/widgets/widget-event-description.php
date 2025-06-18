<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Event Description Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Event_Description_Widget Widget Class.
 * 
 * 
 * @package Event Description
 * @since 1.0.0
 */
class Ef_Event_Description_Widget extends WP_Widget {

	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Event_Description_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_event_description', 'description' => __( 'Shows the event description', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_event_description', $widget_name . __( ' Event Description', 'dxef' ), $widget_ops );
		
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
		
		$eventdescriptiontitle		= isset( $instance['eventdescriptiontitle'] ) ? $instance['eventdescriptiontitle'] : '';
	    $eventdescriptionsubtitle	= isset( $instance['eventdescriptionsubtitle'] ) ? $instance['eventdescriptionsubtitle'] : '';
	    $eventdescriptioncontent	= isset( $instance['eventdescriptioncontent'] ) ? $instance['eventdescriptioncontent'] : '';
	
	    echo stripslashes( $args['before_widget'] ); 
	    echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $eventdescriptiontitle,
            'subtitle' => $eventdescriptionsubtitle,
            'content' => $eventdescriptioncontent
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
		
		$instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
		$instance['eventdescriptiontitle']		= strip_tags( $new_instance['eventdescriptiontitle'] );
		$instance['eventdescriptionsubtitle']	= strip_tags( $new_instance['eventdescriptionsubtitle'] );
		$instance['eventdescriptioncontent']	= $new_instance['eventdescriptioncontent'];
		
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
		
	    $eventdescriptiontitle		= isset( $instance['eventdescriptiontitle'] ) ? $instance['eventdescriptiontitle'] : '';
	    $eventdescriptionsubtitle	= isset( $instance['eventdescriptionsubtitle'] ) ? $instance['eventdescriptionsubtitle'] : '';
	    $eventdescriptioncontent	= isset( $instance['eventdescriptioncontent'] ) ? $instance['eventdescriptioncontent'] : '';?>
	    
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'eventdescriptiontitle' ); ?>" value="<?php echo stripslashes($eventdescriptiontitle); ?>" />
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'eventdescriptionsubtitle' ); ?>" value="<?php echo stripslashes($eventdescriptionsubtitle); ?>" />
		<br /><br />
		<em><?php _e('Content:', 'dxef'); ?></em><br />
		<textarea id="eventdescriptioncontent" name="<?php echo $this->get_field_name( 'eventdescriptioncontent' ); ?>" class="widefat" rows="10"><?php echo esc_html($eventdescriptioncontent);?></textarea>
		<br /><br />
		<input type="hidden" name="submitted" value="1" /><?php
		
	}
}

//Register Widget

function function_Ef_Event_Description_Widget() {
	return register_widget( 'Ef_Event_Description_Widget' );
}
add_action( 'widgets_init', 'function_Ef_Event_Description_Widget');