<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the speakers Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Speakers_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Speakers_Widget extends WP_Widget {
	
	/**
	 * Speakers Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Speakers_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_speakers', 'description' => __( 'Shows a section displaying speakers created in the Speakers custom post type.', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_speakers', $widget_name . __( ' Speaker List', 'dxef' ), $widget_ops );
		
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
		
		$speakerstitle			= isset( $instance['speakerstitle'] ) ? $instance['speakerstitle'] : '';
		$speakersubtitle		= isset( $instance['speakerssubtitle'] ) ? $instance['speakerssubtitle'] : '';
		$speakersviewprofiletext= isset( $instance['speakersviewprofiletext'] ) ? $instance['speakersviewprofiletext'] : '';
		$speakersviewalltext	= isset( $instance['speakersviewalltext'] ) ? $instance['speakersviewalltext'] : '';
		$speakerslist			= isset( $instance['speakerslist'] ) ? $instance['speakerslist'] : array();
		
		if( ! empty( $speakerslist ) ) {
			$speakerslist = array_filter( $speakerslist );
		}
		
		$speaker_args			= array(
										'post_type'		=> 'speaker',
										'posts_per_page'=> 9,
										'post__in'		=> $speakerslist,
										'orderby'		=> 'post__in'
									);
		
		$speakers_chunks	= array_chunk( get_posts( $speaker_args ), 3 );
		$speakers_chunks	= apply_filters( 'multievent_filter_posts_ef_speakers', $speakers_chunks, $speaker_args, $instance );
		
		$full_speakers_page = get_posts(
									array(
										'post_type'		=> 'page',
										'meta_key'		=> '_wp_page_template',
										'meta_value'	=> 'speakers.php'
									)
								);
		
	    echo stripslashes( $args['before_widget'] );
	   	echo apply_filters('ef_widget_render', '', $this->id_base, array(
            'title' => $speakerstitle,
            'subtitle' => $speakersubtitle,
			'speakersviewprofiletext' => $speakersviewprofiletext,
			'speakersviewalltext' => $speakersviewalltext,
			'speakerslist' => $speakerslist,
			'speakers_chunks' => $speakers_chunks,
			'full_speakers_page'=> $full_speakers_page
        ));
	    echo stripslashes( $args['after_widget'] );
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
        $instance['speakerstitle']				= strip_tags( $new_instance['speakerstitle'] );
		$instance['speakerssubtitle']			= strip_tags( $new_instance['speakerssubtitle'] );
		$instance['speakersviewprofiletext']	= strip_tags( $new_instance['speakersviewprofiletext'] );
		$instance['speakersviewalltext']		= strip_tags( $new_instance['speakersviewalltext'] );
		$instance['speakerslist']				= $new_instance['speakerslist'] ;
		
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
		
        $speakerstitle				= isset( $instance['speakerstitle'] ) ? $instance['speakerstitle'] : '';
        $speakerssubtitle			= isset( $instance['speakerssubtitle'] ) ? $instance['speakerssubtitle'] : '';
        $speakersviewprofiletext	= isset( $instance['speakersviewprofiletext'] ) ? $instance['speakersviewprofiletext'] : '';
        $speakersviewalltext		= isset( $instance['speakersviewalltext'] ) ? $instance['speakersviewalltext'] : '';
        
		?>
		
		<em><?php _e('Title:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakerstitle' ); ?>" value="<?php echo $speakerstitle; ?>"/>
		<br /><br />
		<em><?php _e('Subtitle:', 'dxef'); ?></em><br />
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakerssubtitle' ); ?>" value="<?php echo $speakerssubtitle; ?>"/>
		<br /><br />
		<em><?php _e('"View profile" Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakersviewprofiletext' ); ?>" value="<?php echo $speakersviewprofiletext; ?>"/>
		<br /><br />
		<em><?php _e('"View all speakers" Text:', 'dxef'); ?></em><br/>
		<input type="text" class="widefat" name="<?php echo $this->get_field_name( 'speakersviewalltext' ); ?>" value="<?php echo $speakersviewalltext; ?>"/>
		<br /><br />
		<em><?php _e('Speakers ID list:', 'dxef'); ?></em><br/>
		
		<style type="text/css">
			#speakers_list li {display:inline;}
			#speakers_list li input {width:30%;}
			#speakers_list li.separator {display:block;margin:3px 0;}
		</style>
		
		<ul id="speakers_list"><?php
			for ($i = 0; $i < 9; $i++) {
				if ($i == 0 || $i % 3 == 0) { ?>
					<li class="separator"><?php echo __( 'Row', 'dxef' ); ?> <?php echo $i / 3 + 1; ?></li><?php
				} ?>
				<li><input type="text" name="<?php echo $this->get_field_name( 'speakerslist' ); ?>[<?php echo $i;?>]" value="<?php if( isset( $instance['speakerslist'][$i] ) ) echo $instance['speakerslist'][$i];?>"/></li> <?php
			}?>
		</ul><?php 
	}
}

// Register Widget
register_widget( 'Ef_Speakers_Widget' );