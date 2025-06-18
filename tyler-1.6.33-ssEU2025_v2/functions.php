<?php
function tc_add_to_head()
{
	echo "<script type='text/javascript'>var speakerBioInLightBox=0;</script>";
}
add_action('wp_head', 'tc_add_to_head');

function tc_enqueue_scripts()
{

	wp_enqueue_style('tc-jquery-ui', get_stylesheet_directory_uri() . '/jquery-ui/jquery-ui.min.css');
	wp_enqueue_style('tc-jquery-theme-ui', get_stylesheet_directory_uri() . '/jquery-ui/jquery-ui.theme.min.css');
	wp_enqueue_style('tyler-style', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('simon-layout-mobile', get_stylesheet_directory_uri() . '/css/layout-mobile.css');

	wp_enqueue_script('tc-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery'), '1', true);
	wp_enqueue_script('tc-jquery-ui', get_stylesheet_directory_uri() . '/jquery-ui/jquery-ui.min.js', array('jquery'), '1', true);
	/**
	 * Changed by Sandeep @codeable 
	 **/
	$session_templates = ["schedule.php", "schedule-session-two.php", "schedule-session-three.php", "schedule-session-four.php"];
	if (in_array(get_page_template_slug(), $session_templates)) {
		// replace the parent's schedule.js file with the child's file
		wp_dequeue_script('tyler-schedule');
		wp_enqueue_script('tc-schedule', get_stylesheet_directory_uri() . '/js/schedule.js', array('jquery'));
	}
	// replace the parent's main.js file with the child's file
	wp_dequeue_script('tyler-script');
	wp_enqueue_script('tc-script', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'tc_enqueue_scripts', 15);

function tc_child_meta_boxes($callback)
{
	// replace tyler's speaker meta box with a custom one, adding first and last name to the keynote checkbox
	remove_meta_box('metabox-speaker', 'speaker', 'normal');
	add_meta_box('metabox-speaker', __('Speaker Details', 'tyler-child'), 'tc_metabox_speaker', 'speaker', 'normal', 'high');

	// add locations to the sponsor edit page
	add_meta_box('metabox-sponsor-locations', __('Locations', 'tyler-child'), 'tc_metabox_sponsor', 'sponsor', 'normal', 'default');

	// add settings for breaks to the session edit page
	/**
	 * Changed by Sandeep @codeable 
	 * add_meta_box(
	 *	'metabox-session-sponsors',
	 *	__('Breaks', 'tyler-child'),
	 *	'tc_metabox_session',
	 *	'session',
	 *	'normal',
	 *	'default'
	 *	);
	 */	add_meta_box(
		'metabox-session-sponsors',
		__('Breaks', 'tyler-child'),
		'tc_metabox_session',
		['session', "sessiontwo", "sessionthree", "sessionfour"],
		'normal',
		'default'
	);
}
add_action('add_meta_boxes', 'tc_child_meta_boxes', 999);

function tc_save_meta_box($post_id, $post, $update)
{
	// save extra fields
	if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
		return $post_id;

	if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
		return $post_id;

	if ($post->post_type == "speaker") {
		// keynote is saved in parent theme
		tc_save_post_meta($post_id, "speaker_moderator");
		tc_save_post_meta($post_id, "first_name");
		tc_save_post_meta($post_id, "last_name");
		tc_save_post_meta($post_id, "speaker_title");
		tc_save_post_meta($post_id, "company_name");
	}
	if ($post->post_type == "sponsor")
		tc_save_post_meta($post_id, "sponsor_location");

	if (in_array($post->post_type, ["session", "sessiontwo", "sessionthree", "sessionfour"])) {
		tc_save_post_meta($post_id, "session_sponsor");
		tc_save_post_meta($post_id, "no_links");
	}
}
add_action('save_post', 'tc_save_meta_box', 10, 3);

function tc_save_post_meta($post_id, $field_name)
{
	$value = "";
	if (isset($_POST[$field_name]))
		$value = $_POST[$field_name];
	update_post_meta($post_id, $field_name, $value);
}

function tc_metabox_sponsor($post)
{
	// allowing selection of session locations for exhibitors (type of sponsors)
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_meta_data = get_post_custom($post->ID);
	$sponsor_location = $post_meta_data['sponsor_location'][0];
?>
	<table class='form-table'>
		<tbody>
			<tr>
				<th scope='row'>
					<label for='sponsor_location'><?php _e('Exhibitor Location', 'tyler-child'); ?></label>
				</th>
				<td>
					<select name='sponsor_location' id='sponsor_location'>
						<option value=''></option>
						<?php
						$locations = get_terms(array(
							'taxonomy' => 'session-location',
							'hide_empty' => false,
						));
						if ($locations) {
							foreach ($locations as $location) {
								echo "<option value='" . $location->term_taxonomy_id . "'";
								if ($sponsor_location == $location->term_taxonomy_id)
									echo " selected";
								echo ">" . $location->name . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function tc_metabox_session($post)
{
	// allowing selection of sponsors for sessions. also set no links to sessions' single pages
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_meta_data = get_post_custom($post->ID);
	$session_sponsor = $post_meta_data['session_sponsor'][0];
	$no_links = $post_meta_data['no_links'][0];
?>
	<table class='form-table'>
		<tbody>
			<tr>
				<th scope='row'>
					<?php _e('Session Page', 'tyler-child'); ?>
				</th>
				<td>
					<label for='no_links'>
						<input type='checkbox' id='no_links' name='no_links' value='1' <?php if ($no_links == 1) echo "checked='checked'"; ?> />
						<?php _e("Remove links to the session page", 'tyler-child'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='session_sponsor'><?php _e('Break sponsored by', 'tyler-child'); ?></label>
				</th>
				<td>
					<select name='session_sponsor' id='session_sponsor'>
						<option value=''></option>
						<?php
						$sponsors = get_posts(array(
							'post_type' => 'sponsor',
							'nopaging' => true,
							'orderby' => 'title',
							'order' => 'ASC'
						));
						if ($sponsors) {
							foreach ($sponsors as $sponsor) {
								echo "<option value='" . $sponsor->ID . "'";
								if ($session_sponsor == $sponsor->ID)
									echo " selected";
								echo ">" . $sponsor->post_title . "</option>";
							}
						}
						?>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function tc_metabox_speaker($post)
{
	// meta box for speakers, extended to the one used in parent theme
	wp_nonce_field(basename(__FILE__), "meta-box-nonce");
	$post_meta_data = get_post_custom($post->ID);
	$speaker_keynote = $post_meta_data['speaker_keynote'][0];
	$speaker_moderator = 0;
	if (array_key_exists('speaker_moderator', $post_meta_data))
		$speaker_moderator = $post_meta_data['speaker_moderator'][0];
?>
	<table class='form-table'>
		<tbody>
			<tr>
				<th scope='row'>
					<?php _e('Keynote', 'tyler-child'); ?>
				</th>
				<td>
					<label for='speaker_keynote'>
						<input type='checkbox' id='speaker_keynote' name='speaker_keynote' value='1' <?php if ($speaker_keynote == 1) echo "checked='checked'"; ?> />
						<?php _e('Keynote Speaker', 'tyler-child'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<?php _e('Moderator', 'tyler-child'); ?>
				</th>
				<td>
					<label for='speaker_moderator'>
						<input type='checkbox' id='speaker_moderator' name='speaker_moderator' value='1' <?php if ($speaker_moderator == 1) echo "checked='checked'"; ?> />
						<?php _e('Moderator', 'tyler-child'); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='first_name'><?php _e('First Name', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='first_name' id='first_name' value='<?php echo esc_attr($post_meta_data['first_name'][0]); ?>'>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='last_name'><?php _e('Last Name', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='last_name' id='last_name' value='<?php echo esc_attr($post_meta_data['last_name'][0]); ?>' required>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='speaker_title'><?php _e('Title', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='speaker_title' id='speaker_title' value='<?php echo esc_attr($post_meta_data['speaker_title'][0]); ?>'>
				</td>
			</tr>
			<tr>
				<th scope='row'>
					<label for='company_name'><?php _e('Company', 'tyler-child'); ?></label>
				</th>
				<td>
					<input type='text' name='company_name' id='company_name' value='<?php echo esc_attr($post_meta_data['company_name'][0]); ?>'>
				</td>
			</tr>
		</tbody>
	</table>
<?php
}

function tc_register_taxonomies()
{
	// add a taxonomy to sponsors, called type. this will be used to set sponsors as exhibitors and supporting organizations.
	register_taxonomy('sponsor-type', 'sponsor', array(
		'hierarchical' => true,
		'labels' => array(
			'name' => __('Types', 'tyler-child'),
			'singular_name' => __('Type', 'tyler-child'),
			'search_items' => __('Search Types', 'tyler-child'),
			'all_items' => __('All Types', 'tyler-child'),
			'parent_item' => __('Parent Type', 'tyler-child'),
			'parent_item_colon' => __('Parent Type:', 'tyler-child'),
			'edit_item' => __('Edit Type', 'tyler-child'),
			'update_item' => __('Update Type', 'tyler-child'),
			'add_new_item' => __('Add New Type', 'tyler-child'),
			'new_item_name' => __('New Type', 'tyler-child'),
			'menu_name' => __('Types', 'tyler-child')
		),
		'query_var' => true,
		'rewrite' => true,
		'show_admin_column' => true
	));
}
add_action('init', 'tc_register_taxonomies');

function tc_modify_sponsor_custom_post_type($args, $post_type)
{
	// change the sponsor and speaker custom post type, have them support archives so that we will have list pages for them
	if ($post_type == "sponsor") {
		$new_args = array('has_archive' => true);
		return array_merge($args, $new_args);
	}
	if ($post_type == "speaker") {
		$new_args = array('has_archive' => true);
		return array_merge($args, $new_args);
	}
	return $args;
}
add_filter('register_post_type_args', 'tc_modify_sponsor_custom_post_type', 10, 2);

function tc_custom_post_order_sort($query)
{
	if ((!is_admin()) && (is_archive()) && ((is_tax("sponsor-type")) || (is_post_type_archive('sponsor')))) {	// change the order of sponsors to be by title, alphabetically
		$query->set('nopaging', true);
		$query->set('orderby', 'post_title');
		$query->set('order', 'ASC');
	}

	if ((!is_admin()) && (is_archive()) && (is_post_type_archive('speaker'))) {	// change the order of speakers to be by last name
		$query->set('nopaging', true);
		$query->set('meta_query', array(
			array(
				'key' => 'last_name',
				'compare' => 'EXISTS'
			)
		));
		$query->set('orderby', 'meta_value title');
		$query->set('order', 'ASC');
	}
	if ($query->get('post_type') === 'nav_menu_item') { 	// menu won't appear on speakers' page without this
		$query->set('meta_query', '');
		$query->set('meta_key', '');
		$query->set('orderby', '');
	}
}
add_action('pre_get_posts', 'tc_custom_post_order_sort');

remove_action('wp_ajax_get_schedule', array('EF_Session_Helper', 'fudge_ajax_get_schedule'));
remove_action('wp_ajax_nopriv_get_schedule', array('EF_Session_Helper', 'fudge_ajax_get_schedule'));

function tc_ajax_get_schedule()
{
	// this is the result of an ajax call to create the schedule. it replaces the original function in the parent theme as it
	// adds company name and job title to the speaker's info
	$ret = array(
		'sessions' => array(),
		'strings'  => array(
			'more_info' => __('More info', 'dxef')
		)
	);
	/**
	 * Added by Sandeep @codeable 
	 */
	$session_type = sanitize_text_field($_POST["session_type"]);
	$session_types = ["session", "sessiontwo", "sessionthree", "sessionfour"];
	if (!in_array($session_type, $session_types)) {
		echo json_encode($ret);
		die();
	}


	$timestamp      = !empty($_POST['data-timestamp']) ? $_POST['data-timestamp'] : 0;
	$location       = !empty($_POST['data-location']) && ctype_digit($_POST['data-location']) ? intval($_POST['data-location']) : '0';
	$track          = !empty($_POST['data-track']) && ctype_digit($_POST['data-track']) ? intval($_POST['data-track']) : '0';
	$wp_time_format = get_option("time_format");

	add_filter('posts_fields', array('EF_Session_Helper', 'ef_sessions_posts_fields'));
	add_filter('posts_orderby', array('EF_Session_Helper', 'ef_sessions_posts_orderby'));

	$session_loop_args = array(
		/*** Changed by Sandeep @codeable 
		 * 
		 * 'post_type'   => "session",
		 */
		'post_type'   => $session_type,
		'post_status' => 'publish',
		'nopaging'    => true,
		'meta_query'  => array(
			array(
				'key'     => 'session_time',
				'compare' => 'EXISTS',
			),
			array(
				'key'     => 'session_date',
				'compare' => 'EXISTS',
			)
		),
		'tax_query'   => array(),
		//'meta_key' => 'session_date',
		'orderby'     => 'meta_value',
		'order'       => 'ASC'
	);

	if ($timestamp > 0)
		$session_loop_args['meta_query'][] = array(
			'key'   => 'session_date',
			'value' => $timestamp
		);
	if ($location > 0)
		$session_loop_args['tax_query'][]  = array(
			'taxonomy' => 'session-location',
			'field'    => 'id',
			'terms'    => $location
		);
	if ($track > 0)
		$session_loop_args['tax_query'][]  = array(
			'taxonomy' => 'session-track',
			'field'    => 'id',
			'terms'    => $track
		);
	$sessions_loop                     = new WP_Query($session_loop_args);

	remove_filter('posts_fields', array('EF_Session_Helper', 'ef_sessions_posts_fields'));
	remove_filter('posts_orderby', array('EF_Session_Helper', 'ef_sessions_posts_orderby'));

	while ($sessions_loop->have_posts()) {
		$sessions_loop->the_post();
		global $post;

		$time = $post->time;
		if (!empty($time)) {
			$time_parts = explode(':', $time);
			if (count($time_parts) == 2)
				$time       = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
		}

		$end_time = $post->session_end_time;
		if (!empty($end_time)) {
			$time_parts = explode(':', $end_time);
			if (count($time_parts) == 2)
				$end_time   = date($wp_time_format, mktime($time_parts[0], $time_parts[1], 0));
		}

		$locations     = wp_get_post_terms(get_the_ID(), 'session-location');
		if ($locations && count($locations) > 0)
			$location      = $locations[0];
		else
			$location      = '';
		$tracks        = wp_get_post_terms(get_the_ID(), 'session-track', array('fields' => 'ids', 'count' => 1));
		if ($tracks && count($tracks) > 0)
			$track         = $tracks[0];
		else
			$track         = '';
		$speakers_list = get_post_meta(get_the_ID(), 'session_speakers_list', true);
		$speakers      = array();
		if ($speakers_list && count($speakers_list) > 0) {
			foreach ($speakers_list as $speaker_id)
				$speakers[] = array(
					'post_title' => get_the_title($speaker_id),
					'featured'   => get_post_meta($speaker_id, 'speaker_keynote', true),
					'url'        => get_permalink($speaker_id),
					'post_image' => get_the_post_thumbnail($speaker_id, array(54, 54), array('alt' => get_the_title($speaker_id))),
					'company'	 => get_post_meta($speaker_id, 'company_name', true),
					'speaker_title'	 => get_post_meta($speaker_id, 'speaker_title', true),
					'speaker_moderator' => get_post_meta($speaker_id, 'speaker_moderator', true),
					'desc'	 => apply_filters('the_content', get_post_field('post_content', $speaker_id))
				);
		}

		$session_date = get_post_meta(get_the_ID(), 'session_date', true);

		if (empty($session_date)) {
			// If session date is empty, get the Post publish time
			$session_date = get_the_date(get_option(' date_format'), $post->ID);
		} else {
			// else get the session_date
			$session_date = date_i18n(get_option('date_format'), $session_date);
		}

		$session_sponsor_id = get_post_meta(get_the_ID(), 'session_sponsor', true);
		$sponsor_logo = "";
		if (!empty($session_sponsor_id))
			$sponsor_logo = get_the_post_thumbnail_url($session_sponsor_id);

		array_push($ret['sessions'], array(
			'post_title' => get_the_title(),
			'url'        => get_permalink(get_the_ID()),
			'time'       => $time,
			'end_time'   => $end_time,
			'workshop'   => get_post_meta(get_the_ID(), 'session_workshop', true),
			'date'       => $session_date,
			'location'   => $location ? $location->name : '',
			'color'      => $track ? EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $track, 'session_track_color') : '',
			'speakers'   => $speakers,
			'no_links'	 => get_post_meta(get_the_ID(), 'no_links', true),
			'sponsor_logo' => $sponsor_logo
		));
	}

	echo json_encode($ret);
	die;
}
add_action('wp_ajax_get_schedule', 'tc_ajax_get_schedule');
add_action('wp_ajax_nopriv_get_schedule', 'tc_ajax_get_schedule');

//	function tc_excerpt_more($more) {
//		$post = get_post();
//		if ($post->post_type == "sponsor")
//			return ' <a href="'. get_permalink($post->ID) . '">' . __('Read More', 'tyler-child') . '</a>';
//		return $more;
//	}
//	add_filter('excerpt_more', 'tc_excerpt_more', 21);




add_action('add_meta_boxes', 'wpt_add_event_metaboxes');
function wpt_add_event_metaboxes()
{
	/*** Changed by Sandeep @codeable 
	 * 
	 * add_meta_box(
		'workshop_post_show_id',
		'Show Post on Workshop page',
		'workshop_post_show',
		'session',
		'side',
		'high'
	);
	 */
	add_meta_box(
		'workshop_post_show_id',
		'Show Post on Workshop page',
		'workshop_post_show',


		['session', "sessiontwo", "sessionthree", "sessionfour"],
		'side',
		'high'
	);
}
function workshop_post_show($post)
{
	global $post;
	wp_nonce_field(basename(__FILE__), 'session_fieldss');
	$session_workshop = get_post_meta($post->ID, 'session_workshop', true);
?>

	<p>
		<label for="session_workshop"><?php _e('Show in workshop page', 'dxef'); ?></label>
		<input type="checkbox" onclick="myFunction()" id="session_workshop" name="session_workshop" value="<?php echo $session_workshop; ?>" <?php if ($session_workshop == 1) {
																																					echo 'checked="checked"';
																																				} ?> />
		<script>
			function myFunction() {
				// Get the checkbox
				var checkBox = document.getElementById("session_workshop");
				// Get the output text



				// If the checkbox is checked, display the output text
				if (checkBox.checked == true) {
					checkBox.setAttribute("value", "1");
				} else {
					checkBox.setAttribute("value", "0");
					checkBox.removeAttribute("checked");
				}
			}
		</script>
	</p>


<?php
}

function wpt_save_events_meta($post_id, $post)
{
	// $to = 'danko.zloporubovic@gmail.com';
	// $subject = 'The subject';
	// $body = 'The email body content';
	// $headers = array('Content-Type: text/html; charset=UTF-8','From: My Site Name &lt;support@example.com');

	// wp_mail( $to, $subject, $body, $headers );
	/**
	 * Changed by Sandeep @codeable 
	 * if($post->post_type!="session"){
	 * return ;
	 * }
	 */
	if (!in_array($post->post_type, ["session", "sessiontwo", "sessionthree", "sessionfour"])) {
		return;
	}
	// Return if the user doesn't have edit permissions.
	if (! current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	// if ( ! isset( $_POST['session_workshop'] ) || ! wp_verify_nonce( $_POST['session_fieldss'], basename(__FILE__) ) ) {
	// 	return $post_id;
	// }

	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $events_meta.
	$events_meta['session_workshop'] = esc_textarea($_POST['session_workshop']);

	// Cycle through the $events_meta array.
	// Note, in this example we just have one item, but this is helpful if you have multiple.
	foreach ($events_meta as $key => $value) :

		// Don't store custom data twice
		if ('revision' === $post->post_type) {
			return;
		}

		if (get_post_meta($post_id, $key, false)) {
			// If the custom field already has a value, update it.
			update_post_meta($post_id, $key, $value);
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta($post_id, $key, $value);
		}

		if (! $value) {
			// Delete the meta key if there's no value
			delete_post_meta($post_id, $key);
		}

	endforeach;
}
add_action('save_post_session', 'wpt_save_events_meta', 1, 2);




function get_post_meta_cb($object, $field_name, $request)
{
	return get_post_meta($object->ID, $field_name, true);
}
function update_post_meta_cb($value, $object, $field_name)
{
	$post_meta_update = update_post_meta($object->ID, $field_name, $value);
	return $post_meta_update;
}
add_action('rest_api_init', function () {
	register_rest_field(
		'speaker',
		'first_name',
		array(
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
	register_rest_field(
		'speaker',
		'last_name',
		array(
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
	register_rest_field(
		'speaker',
		'speaker_title',
		array(
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
	register_rest_field(
		'speaker',
		'company_name',
		array(
			'get_callback' => 'get_post_meta_cb',
			'update_callback' => 'update_post_meta_cb',
			'schema' => null
		)
	);
});

/** Added by Sandeep @codeable */
add_action('init', function () {
	$post_types = [2 => "two", 3 => "three", 4 => "four"];
	foreach ($post_types as $num => $type) {
		register_post_type('session' . $type, array(
			'labels' => array(
				'name' => __('Session ' . $num, 'dxef'),
				'singular_name' => __('Sessions ' . $num, 'dxef'),
				'add_new' => __('Add New', 'dxef'),
				'add_new_item' => __('Add New Session ' . $num, 'dxef'),
				'edit_item' => __('Edit Session ' . $num, 'dxef'),
				'new_item' => __('New Session ' . $num, 'dxef'),
				'view_item' => __('View Session ' . $num, 'dxef'),
				'search_items' => __('Search Session ' . $num, 'dxef'),
				'not_found' => __('No Sessions found', 'dxef'),
				'not_found_in_trash' => __('No Sessions found in trash', 'dxef'),
				'menu_name' => __('Sessions ' . $num, 'dxef')
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'sessions-' . $type
			),
			'taxonomies' => ['session-location', 'session-track'], // Associate taxonomies
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array(
				'title',
				'editor',
				'page-attributes',
				'thumbnail',
			)
		));
	}
}, 60);
add_action('add_meta_boxes', function () {
	foreach (["sessiontwo", 'sessionthree', "sessionfour"] as  $type) {
		add_meta_box(
			'metabox-session',
			__('Session Details', 'dxef'),
			'ef_metabox_session',
			$type,
			'normal',
			'high'
		);
		add_meta_box(
			'metabox-session-speakers',
			__('Speakers', 'dxef'),
			'ef_metabox_session_speakers',
			$type,
			'normal',
			'high'
		);
	}
});
add_action('save_post', function ($id) {
	if (isset($_POST['post_type']) && in_array($_POST['post_type'], ["sessiontwo", "sessionthree", 'sessionfour'])) {
		if (isset($_POST['session_home']))
			update_post_meta($id, 'session_home', $_POST['session_home']);
		else
			delete_post_meta($id, 'session_home');

		if (isset($_POST['session_date']))
			update_post_meta($id, 'session_date', strtotime($_POST['session_date']));

		if (isset($_POST['session_time']))
			update_post_meta($id, 'session_time', $_POST['session_time']);

		if (isset($_POST['session_end_time']))
			update_post_meta($id, 'session_end_time', $_POST['session_end_time']);

		if (isset($_POST['session_registration_code']))
			update_post_meta($id, 'session_registration_code', $_POST['session_registration_code']);
		else
			delete_post_meta($id, 'session_registration_code');

		if (isset($_POST['session_registration_title']))
			update_post_meta($id, 'session_registration_title', $_POST['session_registration_title']);
		else
			delete_post_meta($id, 'session_registration_title');

		if (isset($_POST['session_registration_text']))
			update_post_meta($id, 'session_registration_text', $_POST['session_registration_text']);
		else
			delete_post_meta($id, 'session_registration_text');

		// AJAX Speakers Order
		if (isset($_POST['session_speakers_list'])) {
			$session_speakers = $_POST['session_speakers_list'];
			if (! empty($session_speakers[0])) {
				$session_speakers = explode(',', $session_speakers[0]);
				update_post_meta($id, 'session_speakers_list', $session_speakers);
			} else {
				delete_post_meta($id, 'session_speakers_list');
			}
		}
	}
});
add_action('admin_enqueue_scripts', function ($hook) {
	global $post_type;
	if (in_array($hook, array('post.php', 'post-new.php'))) {
		if (in_array($post_type, ["sessiontwo", "sessionthree", "sessionfour"])) {
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('jquery-ui-datepicker', get_template_directory_uri() . '/css/admin/smoothness/jquery-ui-1.10.3.custom.min.css');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_style('tyler-sortable', get_template_directory_uri() . '/css/sortable.css');
		}
	}
});
function sandeep_get_session_dates($post_type="session") {
	global $wpdb;

	$metas = $wpdb->get_results(
			"SELECT DISTINCT meta_value
			FROM $wpdb->postmeta
			INNER JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID
			WHERE
			$wpdb->posts.post_type = '".$post_type."' AND
			$wpdb->posts.post_status = 'publish' AND
			$wpdb->postmeta.meta_key = 'session_date' AND
			$wpdb->postmeta.meta_value != ''
			ORDER BY meta_value ASC");

	return $metas;
}
function sandeep_get_terms_for_post_type($taxonomy, $post_type) {
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => -1, 
        'fields'         => 'ids',
    );
    $posts = get_posts($args);

    if (empty($posts)) {
        return array();
    }

    $term_ids = array();
    foreach ($posts as $post_id) {
        $terms = wp_get_post_terms($post_id, $taxonomy, array('fields' => 'ids'));
        if (!is_wp_error($terms) && !empty($terms)) {
            $term_ids = array_merge($term_ids, $terms);
        }
    }

    $term_ids = array_unique($term_ids);

    if (empty($term_ids)) {
        return array();
    }

    $terms = get_terms(array(
        'taxonomy'   => $taxonomy,
        'include'    => $term_ids,
        'hide_empty' => true, 
    ));

    if (is_wp_error($terms)) {
        return array(); // Return an empty array if there's an error
    }

    return $terms; // Return the list of terms
}





//Simon - Custom list of sponsors 

function list_sponsors_by_type_or_alphabetically($atts) {
    // Get shortcode attributes (allow filtering by sponsor-type)
    $atts = shortcode_atts(array(
        'sponsor_type'  => '', // Default: Show all sponsor types
        'posts_per_page' => -1 // Show all sponsors
    ), $atts, 'sponsor_list');

    // Start output buffering
    ob_start();

    // Query Arguments
    $query_args = array(
        'post_type'      => 'sponsor',
        'posts_per_page' => $atts['posts_per_page'],
        'orderby'        => 'title',
        'order'          => 'ASC'
    );

    // If a specific sponsor type is provided, filter by that taxonomy
    if (!empty($atts['sponsor_type'])) {
        $query_args['tax_query'] = array(
            array(
                'taxonomy' => 'sponsor-type',
                'field'    => 'slug',
                'terms'    => $atts['sponsor_type']
            )
        );
    }

    // Get sponsors based on the query
    $sponsors = new WP_Query($query_args);

    if ($sponsors->have_posts()) {
        echo '<div class="sponsor-list-container">';
        echo '<ul class="sponsor-list">';

        $processed_sponsors = array(); // Prevent duplicate sponsors

        while ($sponsors->have_posts()) {
            $sponsors->the_post();

            // Get sponsor details
            $title = get_the_title();
            $post_id = get_the_ID();
            
            // Prevent duplicates when listing all sponsors
            if (empty($atts['sponsor_type']) && in_array($post_id, $processed_sponsors)) {
                continue;
            }

            $processed_sponsors[] = $post_id;

            $logo = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            $logo_url = $logo ? esc_url($logo[0]) : '';
            $link = get_permalink();

            echo '<li class="sponsor-item">';
            if ($logo_url) {
                echo '<a href="' . esc_url($link) . '" class="sponsor-logo">
                        <img src="' . $logo_url . '" alt="' . esc_attr($title) . '">
                      </a>';
            }
            //echo '<a href="' . esc_url($link) . '" class="sponsor-name">' . esc_html($title) . '</a>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';

        wp_reset_postdata();
    } else {
        echo '<p>No sponsors found.</p>';
    }

    return ob_get_clean();
}

// Register the shortcode
add_shortcode('sponsor_list', 'list_sponsors_by_type_or_alphabetically');





// Simon Edit: Hook to handle AJAX request for fetching categories
// function get_custom_post_type_categories() {
//     // Check if the request is valid (security check)
//     if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'get_categories_nonce' ) ) {
//         wp_send_json_error( array( 'message' => 'Invalid nonce' ) );
//     }
// 
//     // Define the custom post type and taxonomy (for categories)
//     $custom_post_type = 'session'; // Replace with your actual custom post type
//     $taxonomy = 'session-track'; // Replace with your actual taxonomy if different
// 
//     // Get all terms for the custom post type's category
//     $categories = get_terms( array(
//         'taxonomy' => $taxonomy,
//         'hide_empty' => false,
//     ));
// 
//     // Return the categories
//     if ( ! empty($categories) && ! is_wp_error($categories) ) {
//         wp_send_json_success( $categories );
//     } else {
//         wp_send_json_error( array( 'message' => 'No categories found' ) );
//     }
// }
// 
// // Register the AJAX action for both logged-in and logged-out users
// add_action( 'wp_ajax_get_custom_post_type_categories', 'get_custom_post_type_categories' );
// add_action( 'wp_ajax_nopriv_get_custom_post_type_categories', 'get_custom_post_type_categories' );
// 
// function enqueue_custom_ajax_script() {
//     // Enqueue your JavaScript file
//     wp_enqueue_script( 'custom-ajax-request', get_template_directory_uri() . '/js/ajax-request.js', array('jquery'), null, true );
// 
//     // Pass AJAX URL and nonce to the script
//     wp_localize_script( 'custom-ajax-request', 'ajax_obj', array(
//         'ajax_url' => admin_url( 'admin-ajax.php' ),
//         'nonce' => wp_create_nonce( 'get_categories_nonce' ), // Create a nonce
//     ));
// }
// add_action( 'wp_enqueue_scripts', 'enqueue_custom_ajax_script' );

// END Simon Edit
