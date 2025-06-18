<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the call-to-action Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Calltoaction_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */

class Ef_Calltoaction_Widget extends WP_Widget {
	
	/**
	 * Calltoaction Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Calltoaction_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_calltoaction', 'description' => __( 'Shows a section displaying the call to action.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_calltoaction', $widget_name . __( ' Call to Action', 'dxef' ), $widget_ops );
		
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
		
		$calltoactiontitle		= isset( $instance['calltoactiontitle'] ) ? $instance['calltoactiontitle'] : '';
		$calltoactionsubtitle	= isset( $instance['calltoactionsubtitle'] ) ? $instance['calltoactionsubtitle'] : '';
		$calltoactionbuttontext	= isset( $instance['calltoactionbuttontext'] ) ? $instance['calltoactionbuttontext'] : '';
		$calltoactionbuttonlink	= isset( $instance['calltoactionbuttonlink'] ) ? $instance['calltoactionbuttonlink'] : '';
		
	    echo stripslashes( $args['before_widget'] );
		echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $calltoactiontitle,
            'subtitle' => $calltoactionsubtitle,
            'buttonlink' => $calltoactionbuttonlink,
            'buttontext' => $calltoactionbuttontext
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
        $instance['calltoactiontitle']		= strip_tags( $new_instance['calltoactiontitle'] );
		$instance['calltoactionsubtitle']	= strip_tags( $new_instance['calltoactionsubtitle'] );
		$instance['calltoactionbuttontext']	= strip_tags( $new_instance['calltoactionbuttontext'] );
		$instance['calltoactionbuttonlink']	= strip_tags( $new_instance['calltoactionbuttonlink'] );
		
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
		
		$calltoactiontitle		= isset( $instance['calltoactiontitle'] ) ? $instance['calltoactiontitle'] : '';
        $calltoactionsubtitle	= isset( $instance['calltoactionsubtitle'] ) ? $instance['calltoactionsubtitle'] : '';
        $calltoactionbuttontext	= isset( $instance['calltoactionbuttontext'] ) ? $instance['calltoactionbuttontext'] : '';
        $calltoactionbuttonlink	= isset( $instance['calltoactionbuttonlink'] ) ? $instance['calltoactionbuttonlink'] : '';?>
        
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'calltoactiontitle' ); ?>" value="<?php echo stripslashes($calltoactiontitle); ?>" />
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'calltoactionsubtitle' ); ?>" value="<?php echo stripslashes($calltoactionsubtitle); ?>" />
		<br /><br />
		<em><?php _e('Button text:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'calltoactionbuttontext' ); ?>" value="<?php echo stripslashes($calltoactionbuttontext); ?>" />
		<br /><br />
		<em><?php _e('Button url:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'calltoactionbuttonlink' ); ?>" value="<?php echo stripslashes($calltoactionbuttonlink); ?>" />
		<br /><br /><?php
	}
}

// Register Widget

function function_Ef_Calltoaction_Widget() {
	return register_widget( 'Ef_Calltoaction_Widget' );
}
add_action( 'widgets_init', 'function_Ef_Calltoaction_Widget');