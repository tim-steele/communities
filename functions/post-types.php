<?php 
/**
 * Custom Post Types 
 */

add_action('init', 'register_questions_type');
add_action('init', 'register_buying_guides_type');


/** 
 * @author Eddie Moya
 */
function register_questions_type() {
    $labels = array(
        'name'          => _x('Questions', 'post type general name'),
        'singular_name' => _x('Questions', 'post type singular name'),
        'add_new'       => _x('Add New', 'question'),
        'add_new_item'  => __('Add New Question'),
        'edit_item'     => __('Edit Question'),
        'new_item'      => __('New Question'),
        'all_items'     => __('All Questions'),
        'view_item'     => __('View Question'),
        'search_items'  => __('Search Questions'),
        'not_found'     => __('No questions found'),
        'not_found_in_trash' => __('No questions found in Trash'),
        'parent_item_colon' => '',
        'menu_name'     => 'Questions'
    );
    $rewrite = array(
        'slug'          => 'question',
        'with_front'    => true,
        'feeds'         => true,
        'paged'         => true,
        'ep_mask'       => array()

    );
    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'publicly_queryable' => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'query_var'     => false,
        'rewrite'       => $rewrite,
        'capability_type' => 'post',
        'has_archive'   => false,
        'hierarchical'  => false,
        'menu_position' => 6,
        'supports'      => array('title', 'editor', 'author', 'comments', 'thumbnail'),
        'menu_icon'     => get_template_directory_uri() . '/assets/img/admin/questions_admin_icon.gif',
        'taxonomies'    => array('category', 'post_tag')
    );
    register_post_type('question', $args);
}

/** 
 * @author Jason Corradino
 */
function register_buying_guides_type() {
    $labels = array(
        'name' => _x('Buying Guides', 'post type general name'),
        'singular_name' => _x('Buying Guide', 'post type singular name'),
        'add_new' => _x('Add New', 'Guide'),
        'add_new_item' => __('Add New Guide'),
        'edit_item' => __('Edit Guide'),
        'new_item' => __('New Guide'),
        'all_items' => __('All Guides'),
        'view_item' => __('View Guides'),
        'search_items' => __('Search Guides'),
        'not_found' => __('No buying guides found'),
        'not_found_in_trash' => __('No buying guides found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => 'Buying Guides'
    );
	$rewrite = array(
        'slug'          => 'guides',
        'with_front'    => true,
        'feeds'         => true,
        'paged'         => true,
        'ep_mask'       => array()
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => false,
        'rewrite' => false,
		'rewrite' => $rewrite,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'supports' => array('title', 'editor', 'author', 'comments', 'thumbnail'),
        //'menu_icon' => get_template_directory_uri() . '/assets/img/admin/questions_admin_icon.gif',
        'taxonomies' => array('category', 'post_tag')
    );
    register_post_type('buying-guide', $args);
}

function new_excerpt_more($more) {
    global $post;
	return '... <a class="moretag" href="'. get_permalink($post->ID) . '">Read more</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function custom_excerpt_length( $excerpt ) {
    global $excerptLength;

    if(!isset($excerptLength) || $excerptLength <= 0) {
        return $excerpt;
    }

    if(strlen($excerpt) > $excerptLength) {
        $words = explode(' ', $excerpt);

        $curTotal = 0;
        $i = 0;

        do {
            $excerpt .= $words[$i].' ';

            $curTotal += strlen($words[$i]);
            $i++;
        } while($curTotal <= $excerptLength);

        $excerpt = substr($excerpt, 0, -1);
    }

	return $excerpt;
}
add_filter( 'the_excerpt', 'custom_excerpt_length');