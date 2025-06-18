<?php
/**
 * Content Template
 */

$ef_options = class_exists('EF_Event_Options') ? EF_Event_Options::get_theme_options() : [];
$is_single = is_single();
$permalink = esc_url(get_permalink());
$title = esc_html(get_the_title());
?>

<article>
    <div class="image">
        <?php if (!$is_single) : ?>
            <a href="<?php echo $permalink; ?>" rel="bookmark">
        <?php else : ?>
            <span>
        <?php endif; ?>
                <span class="date">
                    <span class="month"><?php the_time('M'); ?></span>
                    <span class="day"><?php the_time('d'); ?></span>
                    <span class="year"><?php the_time('Y'); ?></span>
                </span>
                <?php
                the_post_thumbnail('medium', [
                    'alt' => $title,
                    'title' => $title
                ]);
                ?>
        <?php echo !$is_single ? '</a>' : '</span>'; ?>
    </div>

    <div class="post-content">
        <div class="text-fit">
            <strong class="heading">
                <?php if (!$is_single) : ?>
                    <a href="<?php echo $permalink; ?>" rel="bookmark"><?php echo $title; ?></a>
                <?php else : ?>
                    <?php echo $title; ?>
                <?php endif; ?>
            </strong>

            <div class="perex">
                <?php the_excerpt(); ?>
            </div>
        </div>
    </div>
</article>