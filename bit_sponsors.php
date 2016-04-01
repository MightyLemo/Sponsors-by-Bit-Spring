<?php 
/*Plugin Name: Sponsors - By Bit Spring
Description: Provides a custom post type for Sponsors.
Version: 1.0
Author: Bit Spring Web Services
Author URI: http://bit-spring.com/
License: GPLv2
*/
 
define( 'SPONSORS__PLUGIN_DIR', plugin_dir_url( __FILE__ ) );


// THUMBNAIL IMAGES

if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'sponsor-thumb-300x250', 300, 250 ); //(cropped)
}

// CUSTOM POST TYPE

add_action('init', 'sponsor_register');
function sponsor_register() {   
	
	$labels = array( 
		'name' => _x('Sponsors', 'post type general name'), 
		'singular_name' => _x('Sponsor', 'post type singular name'), 
		'add_new' => _x('Add New', 'Sponsor'), 
		'add_new_item' => __('Add New Sponsor'), 
		'edit_item' => __('Edit Sponsor'), 
		'new_item' => __('New Sponsor'), 
		'view_item' => __('View Sponsor'), 
		'search_items' => __('Search Sponsors'), 
		'not_found' => __('Nothing found'), 
		'not_found_in_trash' => __('Nothing found in Trash'), 
		'parent_item_colon' => '' 
	);   
		
	$args = array( 
		'labels' => $labels, 
		'public' => true, 
		'publicly_queryable' => false, 
		'exclude_from_search' => true,
		'show_ui' => true, 
		'query_var' => true, 
		'menu_icon' => SPONSORS__PLUGIN_DIR . 'assets/img/icon.png', 
		'rewrite' => true, 
		'capability_type' => 'post', 
		'hierarchical' => false, 
		'menu_position' => null, 
		'supports' => array('title', 'excerpt', 'editor','page-attributes', 'sort')
	);   
	
	register_post_type( 'sponsor' , $args ); 
}


// SHORT CODES

// [random_sponsor]
function sponsor_random( $atts ){
	
	$output = '<div class="sponsor">';
			
		$args = array( 'post_type' => 'sponsor', 'posts_per_page' => 1, 'orderby' => 'rand' );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post(); 
		
			$url = get_field('sponsor_url');
			$image = get_field('sponsor_image');

			if( !empty($image) ): 

				$output .= '<a href="' . $url . '"><img src="' . $image['url'] . '" alt="' . $image['alt'] . '" /></a>';

			endif; 

		endwhile;

	$output .= '</div>';
	
	return $output;
}

add_shortcode( 'random_sponsor', 'sponsor_random' );



// [sponsors]
function sponsor_func( $atts ){
	
	$output = '<div class="sponsors_list">';
			
	$args = array( 'post_type' => 'sponsor', 'posts_per_page' => 15, 'menu_order', 'order' => 'ASC' );
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post(); 
		$output .= '<div class="sponsor">';
		$output .= '<div class="sponsor_content">';
		$output .= nl2br(get_the_content());
		$output .= '</div>';
		$output .= '<div class="author">';
		$output .= '- ' . get_field('author');
		$output .= '</div></div>';
	endwhile;
		
	$output .= '</div>';
	
	return $output;
}

add_shortcode( 'sponsor', 'sponsor_func' );






?>