<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Media Grid Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Footer_Text_Columns_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Media_Grid_Widget extends WP_Widget {

	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Media_Grid_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_media_grid', 'description' => __( 'Shows text columns organized in columns', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_media_grid', $widget_name . __( ' Media Grid', 'dxef' ), $widget_ops );
		
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
		
		$mediatitle = isset( $instance['mediatitle'] ) ? $instance['mediatitle'] : '';
	    $mediasubtitle = isset( $instance['mediasubtitle'] ) ? $instance['mediasubtitle'] : '';
	
	    echo stripslashes($args['before_widget']);
	    echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $mediatitle,
            'subtitle' => $mediasubtitle
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
		$instance['mediatitle']	= strip_tags( $new_instance['mediatitle'] );
		$instance['mediasubtitle']	= strip_tags( $new_instance['mediasubtitle'] );

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
		
		$mediatitle = isset( $instance['mediatitle'] ) ? $instance['mediatitle'] : '';
	    $mediasubtitle = isset( $instance['mediasubtitle'] ) ? $instance['mediasubtitle'] : '';?>
	    
	    <em><?php _e('Title:', 'dxef'); ?></em>
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'mediatitle' ); ?>" value="<?php echo stripslashes($mediatitle); ?>"/>
	    <br /><br />
	    <em><?php _e('Subtitle:', 'dxef'); ?></em>
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'mediasubtitle' ); ?>" value="<?php echo stripslashes($mediasubtitle); ?>"/>
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
		
	}
}

// Register Widget
register_widget( 'Ef_Media_Grid_Widget' );