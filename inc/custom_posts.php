<?php


function custom_post_types() {
    $labels = array(
        'name'               => _x( 'Galleries', 'post type general name' ),
        'singular_name'      => _x( 'Gallery', 'post type singular name' ),
        'menu_name'          => _x( 'Galleries', 'admin menu' ),
        'name_admin_bar'     => _x( 'Gallery', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'gallery' ),
        'add_new_item'       => __( 'Add New Gallery' ),
        'new_item'           => __( 'New Gallery' ),
        'edit_item'          => __( 'Edit Gallery' ),
        'view_item'          => __( 'View Gallery' ),
        'all_items'          => __( 'All Galleries' ),
        'search_items'       => __( 'Search Galleries' ),
        'parent_item_colon'  => __( 'Parent Galleries:' ),
        'not_found'          => __( 'No galleries found.' ),
        'not_found_in_trash' => __( 'No galleries found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-camera',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array('slug' => 'gallery', 'with_front' => false),
		'show_in_rest'       => true,
		'show_in_graphql'    => true,
		'graphql_single_name' => 'gallery',
		'graphql_plural_name' => 'galleries',
    );

    register_post_type( 'gallery', $args );

    // now add testimonial post type
    $labels = array(
        'name'               => _x( 'Testimonials', 'post type general name' ),
        'singular_name'      => _x( 'Testimonial', 'post type singular name' ),
        'menu_name'          => _x( 'Testimonials', 'admin menu' ),
        'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'testimonial' ),
        'add_new_item'       => __( 'Add New Testimonial' ),
        'new_item'           => __( 'New Testimonial' ),
        'edit_item'          => __( 'Edit Testimonial' ),
        'view_item'          => __( 'View Testimonial' ),
        'all_items'          => __( 'All Testimonials' ),
        'search_items'       => __( 'Search Testimonials' ),
        'parent_item_colon'  => __( 'Parent Testimonials:' ),
        'not_found'          => __( 'No testimonials found.' ),
        'not_found_in_trash' => __( 'No testimonials found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-editor-quote',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array('slug' => 'testimonial', 'with_front' => false),
        'show_in_rest'       => true,
		'show_in_graphql'    => true,
        'graphql_single_name' => 'testimonial',
		'graphql_plural_name' => 'testimonials'
    );

    register_post_type( 'testimonial', $args );

    // add post type for form
    $labels = array(
        'name'               => _x( 'Forms', 'post type general name' ),
        'singular_name'      => _x( 'Form', 'post type singular name' ),
        'menu_name'          => _x( 'Forms', 'admin menu' ),
        'name_admin_bar'     => _x( 'Form', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'form' ),
        'add_new_item'       => __( 'Add New Form' ),
        'new_item'           => __( 'New Form' ),
        'edit_item'          => __( 'Edit Form' ),
        'view_item'          => __( 'View Form' ),
        'all_items'          => __( 'All Forms' ),
        'search_items'       => __( 'Search Forms' ),
        'parent_item_colon'  => __( 'Parent Forms:' ),
        'not_found'          => __( 'No forms found.' ),
        'not_found_in_trash' => __( 'No forms found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-welcome-write-blog',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'            => array('slug' => 'form', 'with_front' => false),
        'show_in_rest'       => true,
		'show_in_graphql'    => true,
        'graphql_single_name' => 'form',
		'graphql_plural_name' => 'forms'
    );

    register_post_type( 'form', $args );

    // Add another content type called FAQ
    $labels = array(
        'name'               => _x( 'FAQs', 'post type general name' ),
        'singular_name'      => _x( 'FAQ', 'post type singular name' ),
        'menu_name'          => _x( 'FAQs', 'admin menu' ),
        'name_admin_bar'     => _x( 'FAQ', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'faq' ),
        'add_new_item'       => __( 'Add New FAQ' ),
        'new_item'           => __( 'New FAQ' ),
        'edit_item'          => __( 'Edit FAQ' ),
        'view_item'          => __( 'View FAQ' ),
        'all_items'          => __( 'All FAQs' ),
        'search_items'       => __( 'Search FAQs' ),
        'parent_item_colon'  => __( 'Parent FAQs:' ),
        'not_found'          => __( 'No FAQs found.' ),
        'not_found_in_trash' => __( 'No FAQs found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-editor-help',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'            => array('slug' => 'faq', 'with_front' => false),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'faq',
		'graphql_plural_name' => 'faqs'
    );

    register_post_type( 'faq', $args );

    $labels = array(
        'name'               => _x( 'Events', 'post type general name' ),
        'singular_name'      => _x( 'Event', 'post type singular name' ),
        'menu_name'          => _x( 'Events', 'admin menu' ),
        'name_admin_bar'     => _x( 'Event', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'event' ),
        'add_new_item'       => __( 'Add New Event' ),
        'new_item'           => __( 'New Event' ),
        'edit_item'          => __( 'Edit Event' ),
        'view_item'          => __( 'View Event' ),
        'all_items'          => __( 'All Events' ),
        'search_items'       => __( 'Search Events' ),
        'parent_item_colon'  => __( 'Parent Events:' ),
        'not_found'          => __( 'No events found.' ),
        'not_found_in_trash' => __( 'No events found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array('slug' => 'event', 'with_front' => false),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'event',
        'graphql_plural_name' => 'events'
    );

    register_post_type( 'event', $args );

    $labels = array(
        'name'               => _x( 'Videos', 'post type general name' ),
        'singular_name'      => _x( 'Video', 'post type singular name' ),
        'menu_name'          => _x( 'Videos', 'admin menu' ),
        'name_admin_bar'     => _x( 'Video', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'video' ),
        'add_new_item'       => __( 'Add New Video' ),
        'new_item'           => __( 'New Video' ),
        'edit_item'          => __( 'Edit Video' ),
        'view_item'          => __( 'View Video' ),
        'all_items'          => __( 'All Videos' ),
        'search_items'       => __( 'Search Videos' ),
        'parent_item_colon'  => __( 'Parent Videos:' ),
        'not_found'          => __( 'No videos found.' ),
        'not_found_in_trash' => __( 'No videos found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'rewrite'            => array('slug' => 'video', 'with_front' => false),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'video',
        'graphql_plural_name' => 'videos'
    );
    register_post_type( 'video', $args );

    // Add another content type called Template
    $labels = array(
        'name'               => _x( 'Templates', 'post type general name' ),
        'singular_name'      => _x( 'Template', 'post type singular name' ),
        'menu_name'          => _x( 'Templates', 'admin menu' ),
        'name_admin_bar'     => _x( 'Template', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'template' ),
        'add_new_item'       => __( 'Add New Template' ),
        'new_item'           => __( 'New Template' ),
        'edit_item'          => __( 'Edit Template' ),
        'view_item'          => __( 'View Template' ),
        'all_items'          => __( 'All Templates' ),
        'search_items'       => __( 'Search Templates' ),
        'parent_item_colon'  => __( 'Parent Templates:' ),
        'not_found'          => __( 'No templates found.' ),
        'not_found_in_trash' => __( 'No templates found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-welcome-write-blog',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'            => array('slug' => 'template', 'with_front' => false),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'template',
        'graphql_plural_name' => 'templates'
    );

    register_post_type( 'template', $args );

    // add post type for portfolio
    $labels = array(
        'name'               => _x( 'Portfolios', 'post type general name' ),
        'singular_name'      => _x( 'Portfolio', 'post type singular name' ),
        'menu_name'          => _x( 'Portfolios', 'admin menu' ),
        'name_admin_bar'     => _x( 'Portfolio', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'portfolio' ),
        'add_new_item'       => __( 'Add New Portfolio' ),
        'new_item'           => __( 'New Portfolio' ),
        'edit_item'          => __( 'Edit Portfolio' ),
        'view_item'          => __( 'View Portfolio' ),
        'all_items'          => __( 'All Portfolios' ),
        'search_items'       => __( 'Search Portfolios' ),
        'parent_item_colon'  => __( 'Parent Portfolios:' ),
        'not_found'          => __( 'No portfolios found.' ),
        'not_found_in_trash' => __( 'No portfolios found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'portfolio',
        'graphql_plural_name' => 'portfolios'
    );

    register_post_type( 'portfolio', $args );

    // add a post type called private for private content
    $labels = array(
        'name'               => _x( 'Private Content', 'post type general name' ),
        'singular_name'      => _x( 'Private Content', 'post type singular name' ),
        'menu_name'          => _x( 'Private Content', 'admin menu' ),
        'name_admin_bar'     => _x( 'Private Content', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'private' ),
        'add_new_item'       => __( 'Add New Private Content' ),
        'new_item'           => __( 'New Private Content' ),
        'edit_item'          => __( 'Edit Private Content' ),
        'view_item'          => __( 'View Private Content' ),
        'all_items'          => __( 'All Private Content' ),
        'search_items'       => __( 'Search Private Content' ),
        'parent_item_colon'  => __( 'Parent Private Content:' ),
        'not_found'          => __( 'No private content found.' ),
        'not_found_in_trash' => __( 'No private content found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-lock',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'private',
        'graphql_plural_name' => 'privates'
    );

    register_post_type( 'private', $args );

    // Add a post type called "Other"
    $labels = array(
        'name'               => _x( 'Others', 'post type general name' ),
        'singular_name'      => _x( 'Other', 'post type singular name' ),
        'menu_name'          => _x( 'Others', 'admin menu' ),
        'name_admin_bar'     => _x( 'Other', 'add new on admin bar' ),
        'add_new'            => _x( 'Add New', 'other' ),
        'add_new_item'       => __( 'Add New Other' ),
        'new_item'           => __( 'New Other' ),
        'edit_item'          => __( 'Edit Other' ),
        'view_item'          => __( 'View Other' ),
        'all_items'          => __( 'All Others' ),
        'search_items'       => __( 'Search Others' ),
        'parent_item_colon'  => __( 'Parent Others:' ),
        'not_found'          => __( 'No others found.' ),
        'not_found_in_trash' => __( 'No others found in Trash.' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-admin-generic',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
        'show_in_graphql'    => true,
        'graphql_single_name' => 'other',
        'graphql_plural_name' => 'others'
    );

    register_post_type( 'other', $args );

}

add_action( 'init', 'custom_post_types' );

// Add custom permalink structure for posts
function custom_post_permalink($permalink, $post) {
    // Check if the post type is 'post'
    if ($post->post_type == 'post') {
        // Use post_name (slug) and ensure it's URL encoded
        $encoded_post_name = urlencode($post->post_name);
        // Construct the correct URL structure
        $permalink = '/articles/' . $encoded_post_name . '/';
    }
    return $permalink;
}
add_filter('pre_post_link', 'custom_post_permalink', 10, 3);

function custom_post_rewrite_rules($rules) {
    // Add custom rewrite rules for the 'article' slug
    $new_rules = array(
        'articles/([^/]+)/?$' => 'index.php?name=$matches[1]&post_type=post',
    );
    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_post_rewrite_rules');
