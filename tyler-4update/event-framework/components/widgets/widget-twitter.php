<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Register the Latest Tweets Widget
 *
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Latest_Tweets_Widget Widget Class.
 *
 *
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Latest_Tweets_Widget extends WP_Widget {

    /**
     * Contact Widget setup.
     *
     * @package Latest Tweets
     * @since 1.0.0
     */
    function Ef_Latest_Tweets_Widget() {

	$widget_name = EF_Framework_Helper::get_widget_name();

	/* Widget settings. */
	$widget_ops = array('classname' => 'ef_twitter', 'description' => __('Shows a section displaying latest tweets', 'dxef'));

	/* Create the widget. */
	$this->WP_Widget('ef_twitter', $widget_name . __(' Latest Tweets', 'dxef'), $widget_ops);
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
    function widget($args, $instance) {

	global $twitter;

	$twitterlinktext		 = isset($instance['twitterlinktext']) ? $instance['twitterlinktext'] : '';
	$twitterhash			 = isset($instance['twitterhash']) ? $instance['twitterhash'] : '';
	$twitteraccesstoken		 = isset($instance['twitteraccesstoken']) ? $instance['twitteraccesstoken'] : '';
	$twitteraccesstokensecret	 = isset($instance['twitteraccesstokensecret']) ? $instance['twitteraccesstokensecret'] : '';
	$twitterconsumerkey		 = isset($instance['twitterconsumerkey']) ? $instance['twitterconsumerkey'] : '';
	$twitterconsumersecret		 = isset($instance['twitterconsumersecret']) ? $instance['twitterconsumersecret'] : '';

	$full_twitter_page	 = get_posts(array(
	    'post_type'	 => 'page',
	    'meta_key'	 => '_wp_page_template',
	    'meta_value'	 => 'twitter.php'
	));
	$tweets			 = array();

	if (isset($twitter) && !empty($twitterhash)) {
	    $url		 = 'https://api.twitter.com/1.1/search/tweets.json';
	    $getfield	 = "?q=#$twitterhash&count=4";
	    $requestMethod	 = 'GET';
	    $store		 = $twitter->setGetfield($getfield)
		    ->buildOauth($url, $requestMethod)
		    ->performRequest();
	    $tweets		 = json_decode($store);
	}

	echo stripslashes($args['before_widget']);
	echo apply_filters('ef_widget_render', '', $this->id_base, array(
	    'twitterlinktext'	 => $twitterlinktext,
	    'full_twitter_page'	 => $full_twitter_page,
	    'twitterhash'		 => $twitterhash,
	    'tweets'		 => $tweets
	));
	echo stripslashes($args['after_widget']);
    }

    /**
     * Update Widget Setting
     *
     * Handle to updates the widget control options
     * for the particular instance of the widget
     *
     * @package Latest Tweets
     * @since 1.0.0
     */
    function update($new_instance, $old_instance) {

	if (isset($_POST['submitted'])) {
	    update_option('ef_twitter_widget_twitterlinktext', isset($new_instance['twitterlinktext']) ? $new_instance['twitterlinktext'] : '' );
 	    update_option('ef_twitter_widget_accesstoken', isset($new_instance['twitteraccesstoken']) ? $new_instance['twitteraccesstoken'] : '' );
	    update_option('ef_twitter_widget_accesstokensecret', isset($new_instance['twitteraccesstokensecret']) ? $new_instance['twitteraccesstokensecret'] : '' );
	    update_option('ef_twitter_widget_consumerkey', isset($new_instance['twitterconsumerkey']) ? $new_instance['twitterconsumerkey'] : '' );
	    update_option('ef_twitter_widget_consumersecret', isset($new_instance['twitterconsumersecret']) ? $new_instance['twitterconsumersecret'] : '' );
	}

	$instance = $old_instance;

	/* Set the instance to the new instance. */
	$instance = $new_instance;

	/* Input fields */
	$instance['twitterlinktext']		 = strip_tags($new_instance['twitterlinktext']);
	$instance['twitterhash']		 = strip_tags($new_instance['twitterhash']);
	$instance['twitteraccesstoken']		 = strip_tags($new_instance['twitteraccesstoken']);
	$instance['twitteraccesstokensecret']	 = strip_tags($new_instance['twitteraccesstokensecret']);
	$instance['twitterconsumerkey']		 = strip_tags($new_instance['twitterconsumerkey']);
	$instance['twitterconsumersecret']	 = strip_tags($new_instance['twitterconsumersecret']);

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
    function form($instance) {

	$twitterlinktext		 = isset($instance['twitterlinktext']) ? $instance['twitterlinktext'] : '';
	$twitterhash			 = isset($instance['twitterhash']) ? $instance['twitterhash'] : '';
	$twitteraccesstoken		 = isset($instance['twitteraccesstoken']) ? $instance['twitteraccesstoken'] : '';
	$twitteraccesstokensecret	 = isset($instance['twitteraccesstokensecret']) ? $instance['twitteraccesstokensecret'] : '';
	$twitterconsumerkey		 = isset($instance['twitterconsumerkey']) ? $instance['twitterconsumerkey'] : '';
	$twitterconsumersecret		 = isset($instance['twitterconsumersecret']) ? $instance['twitterconsumersecret'] : '';
	?>

	<em><?php _e('Link Text:', 'dxef'); ?></em><br />
	<input type="text" class="widefat" name="<?php echo $this->get_field_name('twitterlinktext'); ?>" value="<?php echo stripslashes($twitterlinktext); ?>"/><br/>
	<br /><br />
	<em><?php _e('Event Hashtag Keyword:', 'dxef'); ?></em><br />
	<input type="text" class="widefat" name="<?php echo $this->get_field_name('twitterhash'); ?>" value="<?php echo stripslashes($twitterhash); ?>"/><br/>
	<small><?php _e('(Leave out the #)', 'dxef'); ?></small>
	<br /><br />
	<em><?php _e('Access Token:', 'dxef'); ?></em><br />
	<input type="text" class="twitteraccesstoken" name="<?php echo $this->get_field_name('twitteraccesstoken'); ?>" value="<?php echo stripslashes($twitteraccesstoken); ?>"/>
	<br /><br />
	<em><?php _e('Access Token Secret:', 'dxef'); ?></em><br />
	<input type="text" class="twitteraccesstokensecret" name="<?php echo $this->get_field_name('twitteraccesstokensecret'); ?>" value="<?php echo stripslashes($twitteraccesstokensecret); ?>"/>
	<br /><br />
	<em><?php _e('Consumer Key:', 'dxef'); ?></em><br />
	<input type="text" class="twitterconsumerkey" name="<?php echo $this->get_field_name('twitterconsumerkey'); ?>" value="<?php echo stripslashes($twitterconsumerkey); ?>"/>
	<br /><br />
	<em><?php _e('Consumer Secret:', 'dxef'); ?></em><br />
	<input type="text" class="twitterconsumersecret" name="<?php echo $this->get_field_name('twitterconsumersecret'); ?>" value="<?php echo stripslashes($twitterconsumersecret); ?>"/>
	<br /><br />
	<input type="hidden" name="submitted" value="1" /><?php
    }

}

// Register Widget

function function_Ef_Latest_Tweets_Widget() {
	return register_widget( 'Ef_Latest_Tweets_Widget' );
}
add_action( 'widgets_init', 'function_Ef_Latest_Tweets_Widget');
