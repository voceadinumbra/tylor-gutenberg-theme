<?php
/**
 * Speakers Archive Template
 */

get_header();
?>

<div class="heading">
    <div class="container">
        <h1><?php esc_html_e('Speakers', 'tyler-child'); ?></h1>
    </div>
</div>

<div class="container container-speakers">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $post_meta_data = get_post_custom();
            $speaker_title = $post_meta_data['speaker_title'][0] ?? '';
            $company_name = $post_meta_data['company_name'][0] ?? '';
            ?>
            <a href="<?php the_permalink(); ?>" class="speaker-row-container">
                <div class="speaker-row">
                    <div class="speaker-img-container">
                        <?php
                        the_post_thumbnail('thumbnail', [
                            'title' => esc_attr(get_the_title()),
                            'class' => 'img-speaker',
                            'alt' => esc_attr(get_the_title())
                        ]);
                        ?>
                    </div>
                    <div class="speaker-details">
                        <h2><?php echo esc_html(get_the_title()); ?></h2>
                        <?php if (!empty($speaker_title)) : ?>
                            <p class="position_title"><?php echo esc_html($speaker_title); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($company_name)) : ?>
                            <p class="speaker_company"><?php echo esc_html($company_name); ?></p>
                        <?php endif; ?>
                        <span class="hidden speaker_title">
                            <?php
                            echo esc_html(get_the_title());
                            if (!empty($speaker_title)) {
                                echo ', ' . esc_html($speaker_title);
                            }
                            if (!empty($company_name)) {
                                echo ', ' . esc_html($company_name);
                            }
                            ?>
                        </span>
                        <span class="hidden desc">
                            <?php the_content(); ?>
                        </span>
                    </div>
                </div><!-- .speaker-row -->
            </a>
        <?php endwhile; ?>
    <?php else : ?>
        <p><?php esc_html_e('No speakers found.', 'tyler-child'); ?></p>
    <?php endif; ?>
</div><!-- .container -->

<?php get_footer(); ?>