<!-- TWITTER -->
<div id="tile_twitter" class="container widget">
    <div class="row twitter">
        <div class="col-md-4">
            <div class="view bg-twitter">
                <i class="icon-twitter-alt"></i>
                <div class="view-inner">
                    <iframe id="twitter-widget-0" src="https://platform.twitter.com/widgets/tweet_button.1384994725.html#_=1385458781193&amp;button_hashtag=<?php echo $args['twitterhash']; ?>&amp;count=none&amp;id=twitter-widget-0&amp;lang=en&amp;size=m&amp;type=hashtag" class="twitter-hashtag-button twitter-tweet-button twitter-hashtag-button twitter-count-none" title="Twitter Tweet Button" data-twttr-rendered="true" style="width: 118px; height: 20px; margin:0; padding:0; border:none;"></iframe>
                    <?php if ($args['full_twitter_page'] && count($args['full_twitter_page']) > 0) { ?>
                        <a href="<?php echo get_permalink($args['full_twitter_page'][0]->ID); ?>"><?php echo stripslashes($args['twitterlinktext']); ?> <i class="icon-angle-right"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="tweet featured">
                <?php
                if (!empty($args['tweets']) && property_exists($args['tweets'], 'statuses') && count($args['tweets']->statuses) > 0) {
                    ?>
                    <img class="avatar" src="<?php echo $args['tweets']->statuses[0]->user->profile_image_url_https; ?>" alt="<?php echo $args['tweets']->statuses[0]->user->name; ?>">
                    <div class="text text-fit">
                        <?php echo $args['tweets']->statuses[0]->text; ?>
                    </div>
                    <div class="date">
                        <?php echo getRelativeTime($args['tweets']->statuses[0]->created_at); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row twitter hidden-xs">
        <?php
        if (!empty($args['tweets']) && property_exists($args['tweets'], 'statuses') && count($args['tweets']->statuses) > 1) {
            for ($i = 1; $i <= 3; $i++) {
                if (isset($args['tweets']->statuses[$i])) {
                    ?>
                    <div class="col-sm-4">
                        <div class="tweet bg-gray">
                            <img class="avatar" src="<?php echo $args['tweets']->statuses[$i]->user->profile_image_url_https; ?>" alt="<?php echo $args['tweets']->statuses[$i]->user->name; ?>">
                            <div class="text text-fit">
                                <?php echo $args['tweets']->statuses[$i]->text; ?>
                            </div>
                            <div class="date">
                                <?php echo getRelativeTime($args['tweets']->statuses[0]->created_at); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>