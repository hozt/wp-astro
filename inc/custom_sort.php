<?php

function enqueue_sorting_scripts() {
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('admin-sorting', get_template_directory_uri() . '/resources/js/admin-sorting.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_sorting_scripts');

function save_sorting_order() {
    if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
        wp_send_json_error('Invalid data');
    }

    foreach ($_POST['ids'] as $index => $id) {
        wp_update_post(array(
            'ID' => (int)$id,
            'menu_order' => $index
        ));
    }

    wp_send_json_success('Order saved');
}
add_action('wp_ajax_save_sorting_order', 'save_sorting_order');


global $sortable_post_types;
$sortable_post_types = [
    'video' => __('Videos', 'tailpresshozt'),
    'faq' => __('FAQs', 'tailpresshozt'),
    'testimonial' => __('Testimonials', 'tailpresshozt'),
    'portfolio' => __('Portfolios', 'tailpresshozt'),
];

add_action('graphql_register_types', function() {
    global $sortable_post_types;

    foreach ($sortable_post_types as $post_type => $label) {
        $post_type_object = get_post_type_object($post_type);

        if ($post_type_object && $post_type_object->show_in_graphql) {
            register_graphql_field($post_type_object->graphql_single_name, 'menuOrder', [
                'type' => 'Int',
                'description' => sprintf(__("The menu order of the %s", 'tailpresshozt'), $label),
                'resolve' => function($post) {
                    return (int) get_post_field('menu_order', $post->ID);
                },
            ]);
        }
    }
});

function add_sorting_pages() {
    global $sortable_post_types;

    foreach ($sortable_post_types as $post_type => $label) {
        add_submenu_page(
            "edit.php?post_type={$post_type}", // Parent slug
            sprintf(__('Sort %s', 'tailpresshozt'), $label),
            sprintf(__('Sort %s', 'tailpresshozt'), $label),
            'manage_options',
            "sort_{$post_type}s",
            'render_sort_page'
        );
    }
}

add_action('admin_menu', 'add_sorting_pages');

function render_sort_page() {
    global $sortable_post_types;
    $screen = get_current_screen();
    $post_type = substr($screen->id, strrpos($screen->id, '_') + 1, -1);

    if (array_key_exists($post_type, $sortable_post_types)) {
        // Get taxonomy categories
        $taxonomies = get_object_taxonomies($post_type, 'objects');
        $taxonomies = array_filter($taxonomies, function($taxonomy) {
            return $taxonomy->hierarchical;
        });

        echo '<div class="container p-4 mx-auto">';
        echo '<h1 class="mb-4 text-2xl font-bold">' . sprintf(__('Sort %s', 'tailpresshozt'), $sortable_post_types[$post_type]) . '</h1>';

        if (empty($taxonomies)) {
            // If there are no taxonomies, display all posts of this type
            display_sortable_list_no_taxonomy($post_type);
        } else {
            // Loop through each taxonomy
            foreach ($taxonomies as $taxonomy) {
                $terms = get_terms(array(
                    'taxonomy' => $taxonomy->name,
                    'hide_empty' => false,
                    'parent' => 0 // Get only top-level terms
                ));

                if (!empty($terms)) {
                    echo '<h2 class="mt-6 text-xl font-semibold">' . $taxonomy->label . '</h2>';
                    echo '<ul class="space-y-2">';

                    // Loop through each top-level term
                    foreach ($terms as $term) {
                        $children = get_terms(array(
                            'taxonomy' => $taxonomy->name,
                            'hide_empty' => false,
                            'parent' => $term->term_id
                        ));

                        if (empty($children)) {
                            // If the term has no children, display its posts
                            echo '<li class="p-2 bg-gray-100 rounded">';
                            echo '<h3 class="text-lg font-medium">' . $term->name . '</h3>';
                            display_sortable_list($post_type, $taxonomy->name, $term->term_id);
                            echo '</li>';
                        } else {
                            // If the term has children, display them instead
                            echo '<li class="p-2 bg-gray-100 rounded">';
                            echo '<h3 class="text-lg font-medium">' . $term->name . '</h3>';
                            echo '<ul class="ml-4 space-y-2">';
                            foreach ($children as $child) {
                                echo '<li class="p-2 bg-gray-200 rounded">';
                                echo '<h4 class="font-medium text-md">' . $child->name . '</h4>';
                                display_sortable_list($post_type, $taxonomy->name, $child->term_id);
                                echo '</li>';
                            }
                            echo '</ul>';
                            echo '</li>';
                        }
                    }

                    echo '</ul>';
                }
            }
        }

        echo '</div>';
    } else {
        echo '<div class="container p-4 mx-auto">';
        echo '<h1 class="mb-4 text-2xl font-bold">' . __('Invalid Post Type', 'tailpresshozt') . '</h1>';
        echo '</div>';
    }
}

// add a sortabble list of posts without a taxonomy
function display_sortable_list_no_taxonomy($post_type) {
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );

    $query = new WP_Query($args);

    $list_id = "sortable-{$post_type}";
    echo '<ul id="' . $list_id . '" class="mt-2 space">';
    while ($query->have_posts()) : $query->the_post();
        echo '<li style="width:80%; padding: 8px; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" data-id="' . get_the_ID() . '">' . get_the_title() . '</li>';
    endwhile;
    echo '</ul>';
}

function display_sortable_list($post_type, $taxonomy = null, $term_id = null) {
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    );

    if ($taxonomy && $term_id) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        );
    }

    $query = new WP_Query($args);

    $list_id = $taxonomy && $term_id ? "sortable-{$term_id}" : "sortable-{$post_type}";
    echo '<ul id="' . $list_id . '" class="mt-2 space-y-2">';
    while ($query->have_posts()) : $query->the_post();
        // echo '<li style="" data-id="' . get_the_ID() . '">' . get_the_title() . '</li>';
        echo '<li style="width:80%; padding: 8px; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);" data-id="' . get_the_ID() . '">' . get_the_title() . '</li>';
    endwhile;
    echo '</ul>';
}
