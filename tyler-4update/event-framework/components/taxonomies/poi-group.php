<?php

register_taxonomy ( 'poi-group', 'poi', array (
		'hierarchical' => true,
		'labels' => array (
				'name' => __ ( 'POI Groups', 'dxef' ),
				'singular_name' => __ ( 'POI Group', 'dxef' ),
				'search_items' => __ ( 'Search POI Groups', 'dxef' ),
				'all_items' => __ ( 'All POI Groups', 'dxef' ),
				'parent_item' => __ ( 'Parent POI Group', 'dxef' ),
				'parent_item_colon' => __ ( 'Parent POI Group:', 'dxef' ),
				'edit_item' => __ ( 'Edit POI Group', 'dxef' ),
				'update_item' => __ ( 'Update POI Group', 'dxef' ),
				'add_new_item' => __ ( 'Add New POI Group', 'dxef' ),
				'new_item_name' => __ ( 'New POI Group', 'dxef' ),
				'menu_name' => __ ( 'POI Groups', 'dxef' ) 
		),
		'query_var' => true,
		'rewrite' => true 
) );