<?php

add_action( 'add_meta_boxes', 'ef_speaker_metabox' );

function ef_speaker_metabox() {
	add_meta_box('metabox-speaker', __('Speaker Details', 'dxef'), 
		'ef_metabox_speaker', 'speaker', 'normal', 'high');
	
	if (get_page_template_slug() == 'speakers.php') {
		add_meta_box('metabox-speakers-full-screen', __('Speakers Sections', 'dxef'), 'ef_metabox_speakers_full_screen', 'page', 'normal', 'high');
	}
}


function ef_metabox_speaker($post) {
	$speaker_keynote = get_post_meta($post->ID, 'speaker_keynote', true);
	?>
    <p>
        <label for="speaker_keynote"><?php _e('Keynote', 'dxef'); ?></label>
        <input type="checkbox" id="speaker_keynote" name="speaker_keynote" value="1" <?php if ($speaker_keynote == 1) echo 'checked="checked"'; ?> />
    </p>
    <?php
}

add_action( 'save_post', 'ef_speaker_save_post' );

function ef_speaker_save_post( $id ) {
	if (isset($_POST['speaker_keynote']))
		update_post_meta($id, 'speaker_keynote', $_POST['speaker_keynote']);
	else
		delete_post_meta($id, 'speaker_keynote');
	
	if (get_page_template_slug() == 'speakers.php') {
		if (isset($_POST['speakers_full_title_1']))
			update_post_meta($id, 'speakers_full_title_1', $_POST['speakers_full_title_1']);
	
		if (isset($_POST['speakers_full_order_1']))
			update_post_meta($id, 'speakers_full_order_1', $_POST['speakers_full_order_1']);
	
		if (isset($_POST['speakers_full_title_2']))
			update_post_meta($id, 'speakers_full_title_2', $_POST['speakers_full_title_2']);
	
		if (isset($_POST['speakers_full_order_2']))
			update_post_meta($id, 'speakers_full_order_2', $_POST['speakers_full_order_2']);
	
		if (isset($_POST['speakers_full_title_3']))
			update_post_meta($id, 'speakers_full_title_3', $_POST['speakers_full_title_3']);
	
		if (isset($_POST['speakers_full_order_3']))
			update_post_meta($id, 'speakers_full_order_3', $_POST['speakers_full_order_3']);
	}
}

function ef_metabox_speakers_full_screen($post) {
	$speakers_full_title_1 = get_post_meta($post->ID, 'speakers_full_title_1', true);
	$speakers_full_order_1 = explode(',', get_post_meta($post->ID, 'speakers_full_order_1', true));
	$speakers_full_title_2 = get_post_meta($post->ID, 'speakers_full_title_2', true);
	$speakers_full_order_2 = explode(',', get_post_meta($post->ID, 'speakers_full_order_2', true));
	$speakers_full_title_3 = get_post_meta($post->ID, 'speakers_full_title_3', true);
	$speakers_full_order_3 = explode(',', get_post_meta($post->ID, 'speakers_full_order_3', true));
	$selected_speakers_query_1 = new WP_Query(array('post_type' => 'speaker', 'post__in' => $speakers_full_order_1, 'orderby' => 'post__in', 'posts_per_page' => -1));
	$ignored_speakers_query_1 = new WP_Query(array('post_type' => 'speaker', 'post__not_in' => $speakers_full_order_1, 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1));
	$selected_speakers_query_2 = new WP_Query(array('post_type' => 'speaker', 'post__in' => $speakers_full_order_2, 'orderby' => 'post__in', 'posts_per_page' => -1));
	$ignored_speakers_query_2 = new WP_Query(array('post_type' => 'speaker', 'post__not_in' => $speakers_full_order_2, 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1));
	$selected_speakers_query_3 = new WP_Query(array('post_type' => 'speaker', 'post__in' => $speakers_full_order_3, 'orderby' => 'post__in', 'posts_per_page' => -1));
	$ignored_speakers_query_3 = new WP_Query(array('post_type' => 'speaker', 'post__not_in' => $speakers_full_order_3, 'orderby' => 'title', 'order' => 'ASC', 'posts_per_page' => -1));
	?>
    <p>
        <label for="speakers_full_title_1"><?php _e('Title Section 1', 'dxef'); ?></label>
        <input type="text" class="widefat" id="speakers_full_title_1" name="speakers_full_title_1" value="<?php echo $speakers_full_title_1; ?>" />
    </p>
    <div class="sortable-container">
        <p><?php _e('Select and order speakers to show in this section', 'dxef'); ?></p>
        <ul id="sortable1_1" class="sortable destination sortable1">
            <?php
            while ($selected_speakers_query_1->have_posts()) :
                $selected_speakers_query_1->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <ul id="sortable1_2" class="sortable source sortable1">
            <?php
            while ($ignored_speakers_query_1->have_posts()) :
                $ignored_speakers_query_1->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <input type="hidden" id="speakers_full_order_1" name="speakers_full_order_1" value="<?php echo implode(',', $speakers_full_order_1); ?>" />
    </div>
    </p>
    <p>
        <label for="speakers_full_title_2"><?php _e('Title Section 2', 'dxef'); ?></label>
        <input type="text" class="widefat" id="speakers_full_title_2" name="speakers_full_title_2" value="<?php echo $speakers_full_title_2; ?>" />
    </p>
    <div class="sortable-container">
        <p><?php _e('Select and order speakers to show in this section', 'dxef'); ?></p>
        <ul id="sortable2_1" class="sortable destination sortable2">
            <?php
            while ($selected_speakers_query_2->have_posts()) :
                $selected_speakers_query_2->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <ul id="sortable2_2" class="sortable source sortable2">
            <?php
            while ($ignored_speakers_query_2->have_posts()) :
                $ignored_speakers_query_2->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <input type="hidden" id="speakers_full_order_2" name="speakers_full_order_2" value="<?php echo implode(',', $speakers_full_order_2); ?>" />
    </div>
    <p>
        <label for="speakers_full_title_3"><?php _e('Title Section 3', 'dxef'); ?></label>
        <input type="text" class="widefat" id="speakers_full_title_3" name="speakers_full_title_3" value="<?php echo $speakers_full_title_3; ?>" />
    </p>
    <div class="sortable-container">
        <p><?php _e('Select and order speakers to show in this section', 'dxef'); ?></p>
        <ul id="sortable3_1" class="sortable destination sortable3">
            <?php
            while ($selected_speakers_query_3->have_posts()) :
                $selected_speakers_query_3->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <ul id="sortable3_2" class="sortable source sortable3">
            <?php
            while ($ignored_speakers_query_3->have_posts()) :
                $ignored_speakers_query_3->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <input type="hidden" id="speakers_full_order_3" name="speakers_full_order_3" value="<?php echo implode(',', $speakers_full_order_3); ?>" />
    </div>
    <?php
}