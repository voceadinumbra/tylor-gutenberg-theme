<?php
/**
 * Archive Template
 */

get_header();
?>

<div class="heading">
    <div class="container">
        <h1>
            <?php
            if (is_day()) {
                printf(esc_html__('Daily Archives: %s', 'tyler'), esc_html(get_the_date()));
            } elseif (is_month()) {
                printf(esc_html__('Monthly Archives: %s', 'tyler'), esc_html(get_the_date(_x('F Y', 'monthly archives date format', 'tyler'))));
            } elseif (is_year()) {
                printf(esc_html__('Yearly Archives: %s', 'tyler'), esc_html(get_the_date(_x('Y', 'yearly archives date format', 'tyler'))));
            } elseif (is_author()) {
                printf(esc_html__('All posts by: %s', 'tyler'), esc_html(get_the_author()));
            } elseif (is_tag()) {
                printf(esc_html__('Tag Archives: %s', 'tyler'), esc_html(single_tag_title('', false)));
            } else {
                esc_html_e('Archives', 'tyler');
            }
            ?>
        </h1>
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