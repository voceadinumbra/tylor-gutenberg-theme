<?php
/**
 * Category Archive Template
 */

get_header();
?>

<div class="heading">
    <div class="container">
        <h1><?php echo esc_html(single_cat_title('', false)); ?></h1>
    </div>
</div>

<div class="container">
    <?php if ($description = term_description()) : ?>
        <div>
            <?php echo wp_kses_post($description); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xs-12 col-lg-8">
            <?php if (have_posts()) : ?>
                <div class="articles vertical">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('content', get_post_type()); ?>
                    <?php endwhile; ?>
                </div>
                <nav class="nav-paging">
                    <div class="nav-previous pull-left">
                        <?php next_posts_link(esc_html__('Older posts', 'tyler')); ?>
                    </div>
                    <div class="nav-next pull-right">
                        <?php previous_posts_link(esc_html__('Newer posts', 'tyler')); ?>
                    </div>
                </nav>
            <?php else : ?>
                <p><?php esc_html_e('Nothing Found', 'tyler'); ?></p>
            <?php endif; ?>
        </div>
        <div class="col-lg-4 sidebar visible-lg">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>