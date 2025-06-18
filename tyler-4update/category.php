<?php get_header(); ?>

<div class="heading">
    <div class="container">
        <h1><?php echo single_cat_title('', false); ?></h1>
    </div>
</div>
<div class="container">
    <div style="padding-bottom: 2em">
        <p><?php echo term_description(); ?></p>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-8">
            <?php if (have_posts()) : ?>
                <div class="articles vertical">
                    <?php /* The loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('content', get_post_type()); ?>
                    <?php endwhile; ?>
                </div>
                <div class="nav-paging">
                    <div class="nav-previous pull-left"><?php next_posts_link('Older posts'); ?></div>
                    <div class="nav-next pull-right"><?php previous_posts_link('Newer posts'); ?></div>
                </div>
            <?php else : ?>
                <?php _e('Nothing Found', 'tyler'); ?>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 sidebar visible-lg">
            <?php get_sidebar() ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>