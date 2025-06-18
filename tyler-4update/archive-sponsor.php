<?php
/**
 * Sponsors Taxonomy Template
 */

get_header();

$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$locations = get_terms([
    'taxonomy' => 'session-location',
    'hide_empty' => true,
]);
$locations_arr = [];
if ($locations) {
    foreach ($locations as $location) {
        $locations_arr[$location->term_taxonomy_id] = $location->name;
    }
}
?>

<div class="heading">
    <div class="container">
        <h1>
            <?php
            if ($term) {
                echo esc_html($term->name);
            } else {
                esc_html_e('Sponsors', 'tyler-child');
            }
            ?>
        </h1>
    </div>
</div>

<?php if (in_array(get_query_var('term'), ['exhibitors', 'meeting-space'])) : ?>
    <!--
    <div class="container">
        <ul class="nav nav-tabs pull-right">
            <li class="active">
                <a href="javascript:void(0)"><?php esc_html_e('Filter by location', 'tyler-child'); ?></a>
                <ul>
                    <li><a href="#" class="sponsor-location" data-location="0"><?php esc_html_e('All', 'tyler-child'); ?></a></li>
                    <?php foreach ($locations as $location) : ?>
                        <li><a href="#" class="sponsor-location" data-location="<?php echo esc_attr($location->term_taxonomy_id); ?>">
                            <?php echo esc_html($location->name); ?>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
        <div class="after-nav-tabs"></div>
    </div>
    -->
<?php endif; ?>

<?php if (get_query_var('term') === 'meeting-space') : ?>
    <div class="container">
        <div>
            <p>
                <?php esc_html_e('A limited number of private meeting spaces are available for reservation.', 'tyler-child'); ?><br>
                <?php printf(
                    esc_html__('Please %s for availability and pricing', 'tyler-child'),
                    '<a href="https://2024.smallsatshow.com/exhibition-and-sponsorship/">' . esc_html__('contact our exhibitor & sponsor sales team', 'tyler-child') . '</a>'
                ); ?>
            </p>
        </div>
    </div>
<?php endif; ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $exhibitor_location = get_post_meta(get_the_ID(), 'sponsor_location', true);
        $booth_number = get_field('booth_number');
        $meeting_space = get_field('meeting_space');
        ?>
        <div class="sponsor-row" data-location="<?php echo esc_attr($exhibitor_location ?: ''); ?>">
            <div class="container">
                <h2><?php echo esc_html(get_the_title()); ?></h2>
                <br clear="all">
                <?php if (get_query_var('term') === 'exhibitors' && $booth_number) : ?>
                    <h3><?php printf(esc_html__('Booth %s', 'tyler-child'), esc_html($booth_number)); ?></h3>
                <?php elseif (get_query_var('term') === 'meeting-space' && $meeting_space) : ?>
                    <h3><?php echo esc_html($meeting_space); ?></h3>
                <?php endif; ?>
            </div>
            <div class="container clearfix">
                <?php if ($exhibitor_location && isset($locations_arr[$exhibitor_location])) : ?>
                    <span class="sponsor_location"><?php esc_html_e('Location', 'tyler-child'); ?>:</span>
                    <?php echo esc_html($locations_arr[$exhibitor_location]); ?>
                <?php endif; ?>
                <?php
                the_post_thumbnail('medium', [
                    'title' => esc_attr(get_the_title()),
                    'class' => 'img-sponsor',
                    'alt' => esc_attr(get_the_title())
                ]);
                ?>
                <p><?php the_excerpt(); ?></p>
                <p><a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'tyler-child'); ?></a></p>
            </div><!-- .container -->
        </div><!-- .sponsor-row -->
    <?php endwhile; ?>
<?php else : ?>
    <div class="container">
        <p><?php esc_html_e('No sponsors found.', 'tyler-child'); ?></p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>