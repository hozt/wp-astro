<?php

// Add custom class to menu <li> elements
function add_custom_menu_li_class($classes, $item, $args, $depth) {
    if ($args->theme_location == 'primary') {
        $classes[] = 'relative group lg:mx-4';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_custom_menu_li_class', 10, 4);

// Add custom class to menu <a> elements
function add_custom_menu_link_class($atts, $item, $args, $depth) {
    if ($args->theme_location == 'primary') {
        $atts['class'] = 'block px-4 py-2 hover:bg-gray-100 hover:text-gray-900';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_custom_menu_link_class', 10, 4);

// Add custom class to sub-menu <ul> elements
function add_custom_submenu_ul_class($classes, $args, $depth) {
    if ($args->theme_location == 'primary') {
        $classes[] = 'absolute left-0 hidden bg-white shadow-lg group-hover:block group-hover:text-gray-900';
    }
    return $classes;
}
add_filter('nav_menu_submenu_css_class', 'add_custom_submenu_ul_class', 10, 3);



// // Add custom class to menu <li> elements
// function add_footer_menu_li_class($classes, $item, $args, $depth) {
//     if ($args->theme_location == 'footer') {
//         // $classes[] = ''; // Add your custom class
//         // $classes = [];
//     }
//     return $classes;
// }
// add_filter('nav_menu_css_class', 'add_footer_menu_li_class', 10, 4);

// Add custom class to menu <a> elements

// function add_footer_menu_link_class($atts, $item, $args, $depth) {
//     if ($args->theme_location == 'footer') {
//         $atts['class'] = ''; // Add your custom class
//     }
//     return $atts;
// }
// add_filter('nav_menu_link_attributes', 'add_footer_menu_link_class', 10, 4);

// Ensure sub-menu <ul> elements are always visible and prevent indent
function add_footer_submenu_ul_class($classes, $args, $depth) {
    if ($args->theme_location == 'footer') {
        $classes = [];
        $classes[] = "ml-0";
    }
    return $classes;
}
add_filter('nav_menu_submenu_css_class', 'add_footer_submenu_ul_class', 10, 3);

function register_footer_menu() {
    register_nav_menu('footer', __('Footer Menu', 'your-theme-text-domain'));
}
add_action('init', 'register_footer_menu');
