<?php
/*
 * Template Name: Speakers
 *
 * @package WordPress
 * @subpackage Tyler
 */
?>
<?php get_header() ?>

<?php while (have_posts()) : the_post(); ?>
    <div class="heading">
        <div class="container">
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
    <div class="container">
        <p>
            <?php the_content(); ?>
        </p>
        <?php for ($i = 0; $i < 3; $i++) { ?>
			 <?php
            $speakers_full_order = get_post_meta(get_the_ID(), 'speakers_full_order_' . ($i + 1), true);
            if (!empty($speakers_full_order)) {
                $speakers_full_order = explode(',', $speakers_full_order);
                ?>
            <hr/>
            <h2><?php echo get_post_meta(get_the_ID(), 'speakers_full_title_' . ($i + 1), true); ?></h2>
           
                <div class="speakers">
                    <?php foreach ($speakers_full_order as $speaker_id) { ?>
                    	<?php 
							$speaker = get_post($speaker_id); 
							$post_meta_data = get_post_custom($speaker_id);
							$speaker_keynote = $post_meta_data['speaker_keynote'][0];
							$desc = $post_meta_data['speaker_title'][0];
							if ((!empty($post_meta_data['speaker_title'][0])) && (!empty($post_meta_data['company_name'][0])))
								$desc .= ", " . $post_meta_data['company_name'][0];
						?>
                        <div class="speaker <?php if ($speaker_keynote == 1) echo ' featured'; ?>">
                            <a href="<?php echo get_permalink($speaker_id); ?>" class="speaker-inner">
                                <span class="photo">
                                    <?php echo get_the_post_thumbnail($speaker_id, 'tyler-speaker', array('title' => get_the_title($speaker_id))); ?>
                                </span>
                                <span class="name"><span class="text-fit"><?php echo get_the_title($speaker_id); ?></span></span>
                                <span class="description"><?php echo $desc; ?></span>
                                <span class="view">
                                    <?php _e('View profile', 'tyler'); ?> <i class="icon-angle-right"></i>
                                </span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
<?php endwhile; // end of the loop. ?>

<?php get_footer() ?>