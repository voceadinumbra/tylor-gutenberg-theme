<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Comments Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Comments_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Comments_Widget extends WP_Widget {
	
	/**
	 * Comments Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Comments_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_comments', 'description' => __( 'Shows a section displaying the latest comments of your posts.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_comments', $widget_name . __( ' Latest comments', 'dxef' ), $widget_ops );
		
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
		
		$commentstitle	= isset( $instance['commentstitle'] ) ? $instance['commentstitle'] : '';
		$commentstotal	= isset( $instance['commentstotal'] ) ? $instance['commentstotal'] : '';
		$comments		= $commentstotal ? get_comments(array('number'=>$commentstotal)) : get_comments();
		//isset( $instance['comments'] ) ? $instance['comments'] : '';
		
		echo stripslashes($args['before_widget']);
		
		echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $commentstitle,
            'subtitle' => $commentstotal,
            'comments' => $comments
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
		$instance['commentstitle']	= strip_tags( $new_instance['commentstitle'] );
		$instance['commentstotal']	= strip_tags( $new_instance['commentstotal'] );
		
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
		
		$commentstitle	= isset( $instance['commentstitle'] ) ? $instance['commentstitle'] : '';
	    $commentstotal	= isset( $instance['commentstotal'] ) ? $instance['commentstotal'] : '';?>
	    
	    <em><?php _e('Title:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'commentstitle' ); ?>" value="<?php echo stripslashes( $commentstitle ); ?>" />
	    <br /><br />
	    <em><?php _e('Number of comments to show:', 'dxef'); ?></em><br />
	    <input type="text" name="<?php echo $this->get_field_name( 'commentstotal' ); ?>" size="3" value="<?php echo stripslashes( $commentstotal ); ?>" />
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
	}
}

// Register Widget

function function_Ef_Comments_Widget() {
	return register_widget( 'Ef_Comments_Widget' );
}
add_action( 'widgets_init', 'function_Ef_Comments_Widget');