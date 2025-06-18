<?php

register_taxonomy ( 'media-type', 'event-media', array (
		'hierarchical' => true,
		'labels' => array (
				'name' => __ ( 'Media Types', 'dxef' ),
				'singular_name' => __ ( 'Media Type', 'dxef' ),
				'search_items' => __ ( 'Search Media Types', 'dxef' ),
				'all_items' => __ ( 'All Media Types', 'dxef' ),
				'parent_item' => __ ( 'Parent Media Type', 'dxef' ),
				'parent_item_colon' => __ ( 'Parent Media Type:', 'dxef' ),
				'edit_item' => __ ( 'Edit Media Type', 'dxef' ),
				'update_item' => __ ( 'Update Media Type', 'dxef' ),
				'add_new_item' => __ ( 'Add New Media Type', 'dxef' ),
				'new_item_name' => __ ( 'New Media Type', 'dxef' ),
				'menu_name' => __ ( 'Media Types', 'dxef' ) 
		),
		'query_var' => true,
		'rewrite' => true 
) );