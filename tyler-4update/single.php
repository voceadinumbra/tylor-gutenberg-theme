<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
    <?php $categories = get_the_category(); ?>
    <div class="heading">
        <div class="container">
            <h1><?php the_title() ?></h1>

            <div class="nav">
                <?php previous_post_link('%link', '<i class="icon-angle-left"></i>') ?>
                <?php if ($categories && count($categories) > 0) { ?>
                    <a href="<?php echo get_category_link($categories[0]); ?>" title="All"><i class="icon-th-large"></i></a>
                <?php } ?>
                <?php next_post_link('%link', '<i class="icon-angle-right"></i>') ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-8">
                <?php the_content(); ?>
                <div class="post-info">
                    Posted <?php the_date() ?> in: <strong>Event</strong> by <strong><?php the_author() ?></strong>
                </div>
                <hr style="margin-top: 0.25em" />
                <span class="share pull-right" style="margin: -20px 0 20px;">
                    <!-- AddThis Button BEGIN -->
                    <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=xa-529a404744177ed4"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a>
                    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-529a404744177ed4"></script>
                    <!-- AddThis Button END -->
                </span>
                <div class="nav-paging">
                    <div class="nav-previous pull-left">
                        <?php previous_post_link('%link', 'Older'); ?>
                    </div>
                    <div class="nav-next pull-right">
                        <?php next_post_link('%link', 'Newer'); ?>
                    </div>
                </div><!-- .nav-single -->
                <?php comments_template('', true); ?>
            </div>
            <div class="col-lg-4 sidebar visible-lg">
                <?php get_sidebar() ?>
            </div>
        </div>
    </div>
<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>