<?php

function daroon2_register_team_type() {
    $labels = [
        'name' 				=> __('Team','daroon2_core' ),
        'singular_name' 	=> __('Team Member','daroon2_core' ),
        'add_item'			=> __('New Team Member','daroon2_core'),
        'add_new_item' 		=> __('Add New Team Member','daroon2_core'),
        'edit_item' 		=> __('Edit Team Member','daroon2_core')
    ];

    $args = [
        'labels'            => $labels,
        'public'		    => true,
        'has_archive'       => true,
        'capability_type'   => 'post',
        'rewrite'           => array('slug' => 'team'),
        'menu_position'     => 5,
        'show_ui'           => true,
        'supports'          => array('title', 'editor', 'thumbnail', 'page-attributes','excerpt'),
        'menu_icon'         => 'dashicons-groups',
    ];

    register_post_type( 'team', $args );
}
add_action( 'init', 'daroon2_register_team_type' );

function daroon2_register_team_category_taxonomy() {
    $labels = array(
        'name'              => __( 'Team Categories', 'daroon2_core' ),
        'singular_name'     => __( 'Team Category', 'daroon2_core' ),
        'search_items'      => __( 'Search Team Categories','daroon2_core' ),
        'all_items'         => __( 'All Team Categories','daroon2_core' ),
        'parent_item'       => __( 'Parent Team Category','daroon2_core' ),
        'parent_item_colon' => __( 'Parent Team Category:','daroon2_core' ),
        'edit_item'         => __( 'Edit Team Category','daroon2_core' ),
        'update_item'       => __( 'Update Team Category','daroon2_core' ),
        'add_new_item'      => __( 'Add New Team Category','daroon2_core' ),
        'new_item_name'     => __( 'New Team Category Name','daroon2_core' ),
        'menu_name'         => __( 'Team Categories','daroon2_core' ),
    );

    $args = [
        'hierarchical'      => true,            // like categories (true) or tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,            // Gutenberg support
        'show_admin_column' => true,            // show on post list
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'team-category' ],
    ];

    register_taxonomy( 'team-category', [ 'team' ], $args );
}
add_action( 'init', 'daroon2_register_team_category_taxonomy' );

function daroon2_register_team_title_taxonomy() {
    $labels = array(
        'name'              => __( 'Job Title', 'daroon2_core' ),
        'singular_name'     => __( 'Team Category', 'daroon2_core' ),
        'search_items'      => __( 'Search Job Title','daroon2_core' ),
        'all_items'         => __( 'All Job Title','daroon2_core' ),
        'parent_item'       => __( 'Parent Team Category','daroon2_core' ),
        'parent_item_colon' => __( 'Parent Team Category:','daroon2_core' ),
        'edit_item'         => __( 'Edit Team Category','daroon2_core' ),
        'update_item'       => __( 'Update Team Category','daroon2_core' ),
        'add_new_item'      => __( 'Add New Team Category','daroon2_core' ),
        'new_item_name'     => __( 'New Team Category Name','daroon2_core' ),
        'menu_name'         => __( 'Job Title','daroon2_core' ),
    );

    $args = [
        'hierarchical'      => true,            // like categories (true) or tags (false)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,            // Gutenberg support
        'show_admin_column' => true,            // show on post list
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'team-job-title' ],
    ];

    register_taxonomy( 'team-job-title', [ 'team' ], $args );
}
add_action( 'init', 'daroon2_register_team_title_taxonomy' );

// Register a new hard-cropped 64×64 size:
add_image_size( 'thumb-64x64', 64, 64, true );
// (Optional) Make it selectable in the Media Library:
function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, [
        'thumb-64x64' => __( '64×64 Thumbnail' ),
    ] );
}
add_filter( 'image_size_names_choose', 'my_custom_sizes' );
function daroon2_add_thumbnail_support() {
    // Enable featured images for posts and a custom post type 'team'
    add_theme_support( 'post-thumbnails', [ 'post', 'team' ] );

    // If your custom post type was already registered without thumbnail support,
    // you can also do:
    // add_post_type_support( 'team', 'thumbnail' );
}
add_action( 'after_setup_theme', 'daroon2_add_thumbnail_support' );
?>