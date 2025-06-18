<?php
/**
 * Single Sponsor Template
 */

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
        $categories = get_the_category();
        $booth_number = get_field('booth_number');
        $meeting_space = get_field('meeting_space');
        $post_meta_data = get_post_custom();
        $location_id = $post_meta_data['sponsor_location'][0] ?? null;
        $location = $location_id ? get_term_by('id', $location_id, 'session-location') : null;
?>

<div class="heading">
    <div class="container">
        <h1><?php the_title(); ?></h1>
        <nav class="nav">
            <?php previous_post_link('%link', '<i class="icon-angle-left"></i>'); ?>
            <?php if (!empty($categories)) : ?>
                <a href="<?php echo esc_url(get_category_link($categories[0])); ?>" title="<?php esc_attr_e('All', 'tyler-child'); ?>">
                    <i class="icon-th-large"></i>
                </a>
            <?php endif; ?>
            <?php next_post_link('%link', '<i class="icon-angle-right"></i>'); ?>
        </nav>
    </div>
</div>

<div class="container">
    <?php
    // Display booth and meeting space information
    $location_output = [];
    if ($booth_number) {
        $location_output[] = sprintf(__('Booth %s', 'tyler-child'), esc_html($booth_number));
    }
    if ($meeting_space) {
        $location_output[] = esc_html($meeting_space);
    }
    if (!empty($location_output)) {
        printf('<h3>%s</h3>', implode(' & ', $location_output));
    }
    ?>

    <div class="row">
        <div class="col-xs-12 col-lg-12">
            <?php
            the_post_thumbnail('medium', [
                'title' => esc_attr(get_the_title()),
                'class' => 'img-sponsor',
                'alt' => esc_attr(get_the_title())
            ]);

            if ($location) {
                printf(
                    '<span class="sponsor_location">%s</span>: %s',
                    esc_html__('Location', 'tyler-child'),
                    esc_html($location->name)
                );
            }

            the_content();
            ?>
        </div>
    </div>
</div>

<?php
    endwhile;
else :
    // No posts found
    echo '<p>' . esc_html__('No content found.', 'tyler-child') . '</p>';
endif;

get_footer();
?>