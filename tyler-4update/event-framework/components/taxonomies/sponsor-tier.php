<?php
register_taxonomy ( 'sponsor-tier', 'sponsor', array (
		'hierarchical' => true,
		'labels' => array (
				'name' => __ ( 'Tiers', 'dxef' ),
				'singular_name' => __ ( 'Tier', 'dxef' ),
				'search_items' => __ ( 'Search Tiers', 'dxef' ),
				'all_items' => __ ( 'All Tiers', 'dxef' ),
				'parent_item' => __ ( 'Parent Tier', 'dxef' ),
				'parent_item_colon' => __ ( 'Parent Tier:', 'dxef' ),
				'edit_item' => __ ( 'Edit Tier', 'dxef' ),
				'update_item' => __ ( 'Update Tier', 'dxef' ),
				'add_new_item' => __ ( 'Add New Tier', 'dxef' ),
				'new_item_name' => __ ( 'New Tier', 'dxef' ),
				'menu_name' => __ ( 'Tiers', 'dxef' ) 
		),
		'query_var' => true,
		'rewrite' => true 
) );