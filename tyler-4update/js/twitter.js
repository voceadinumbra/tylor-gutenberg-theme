jQuery(document).ready(function(){
    jQuery('#twitter_update_list').tweetMachine(
        '#' + tyler_twitter_hash, {
            backendScript: ajaxurl,
            tweetFormat: '<li><img class="avatar" src=""/><span class="content"></span><a href="" class="time"></a></li>',
            limit: 4,
            rate: 30000
        });
});