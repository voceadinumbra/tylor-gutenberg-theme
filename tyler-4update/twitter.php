<?php
// Template Name: Twitter Full Page
get_header();
?>
<section id="twitter_fullscreen">
    <div class="container">
        <?php
        global $twitter;

        $twitterhash = get_option('ef_twitter_widget_twitterhash');
        $tweets = array();
        if (isset($twitter) && !empty($twitterhash)) {
            $url = 'https://api.twitter.com/1.1/search/tweets.json';
            $getfield = "?q=$twitterhash&count=7";
            $requestMethod = 'GET';
            $store = $twitter->setGetfield($getfield)
                    ->buildOauth($url, $requestMethod)
                    ->performRequest();
            $tweets = json_decode($store);
        }
        
        $full_twitter_page = get_posts(array(
        	'post_type' => 'page',
        ));
        ?>
              
        <!-- TWITTER -->
	    <div id="twitter_div">
            <ul id="twitter_update_list">
            </ul>
            <a class="btn secondary-bkg-color" title="<?php _e('Back', 'tyler'); ?>" href="<?php echo home_url(); ?>"><?php _e('Back', 'tyler'); ?></a>
        </div>
    </div>
</section>
<?php get_footer(); ?>