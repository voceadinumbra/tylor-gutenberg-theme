<?php 
$ef_options = EF_Event_Options::get_theme_options();
?>
<article>
    <div class="image">
        <<?php echo !is_single() ? 'a href="'. get_permalink() .'" rel="bookmark"' : 'span'?>>
            <span class="date">
                <span class="month"><?php the_time('M') ?></span>
                <span class="day"><?php the_time('d') ?></span>
                <span class="year"><?php the_time('Y') ?></span>
            </span>
            <?php the_post_thumbnail(); ?>
        </<?php echo !is_single() ? 'a' : 'span'?>>
    </div>

        <div class="post-content">
            <div class="text-fit">

                <strong class="heading">
                    <?php if(!is_single()) : ?>
                        <a href="<?php echo get_permalink() ?>" rel="bookmark">
                            <?php the_title(); ?>
                        </a>
                    <?php else: ?>
                        <?php the_title(); ?>
                    <?php endif; ?>
                </strong>

                <div class="perex">
                   <?php echo the_excerpt(); ?>
                </div>

            </div>

			<?php if ( ! empty(  $ef_options['ef_add_this_pubid'] ) ) { ?>
	            <span class="share">
	                <!-- AddThis Button BEGIN -->
	                <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=<?php echo $ef_options['ef_add_this_pubid']; ?>">
	                    <img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/>
	                </a>
	                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $ef_options['ef_add_this_pubid']; ?>"></script>
	                <!-- AddThis Button END -->
	            </span>
			<?php } ?>
			
        </div>
</article>