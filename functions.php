<?php

include_once get_template_directory() . '/inc/custom_fields.php';
include_once get_template_directory() . '/inc/widgets.php';
include_once get_template_directory() . '/inc/custom_posts.php';
include_once get_template_directory() . '/inc/menu.php';
include_once get_template_directory() . '/inc/global_settings.php';
include_once get_template_directory() . '/inc/custom_sort.php';
include_once get_template_directory() . '/inc/custom_featured_image.php';
include_once get_template_directory() . '/inc/custom_category.php';
include_once get_template_directory() . '/inc/admin.php';
include_once get_template_directory() . '/inc/cors.php';

/**
 * Theme setup.
 */
function tailpress_setup()
{
    add_theme_support('title-tag');

    register_nav_menus(
        array(
            'primary' => __('Primary Menu', 'wp-astro'),
            'footer' => __('Footer Menu', 'wp-astro'),
            'social' => __('Social Menu', 'wp-astro'),
        )
    );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        )
    );

    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');

    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');

    add_theme_support('editor-styles');
    add_editor_style('css/editor-style.css');
}

add_action('after_setup_theme', 'tailpress_setup');

/**
 * Enqueue theme assets.
 */
function tailpress_enqueue_scripts()
{
    $theme = wp_get_theme();

    wp_enqueue_style('wp-astro', tailpress_asset('css/app.css'), array(), $theme->get('Version'));
    wp_enqueue_script('wp-astro', tailpress_asset('js/app.js'), array(), $theme->get('Version'));
    $websiteUrl = get_theme_mod('website_url');
    if ($websiteUrl && filter_var($websiteUrl, FILTER_VALIDATE_URL)) {
        wp_enqueue_style('wp-astro-editor', esc_url($websiteUrl) . '/editor.css', array(), $theme->get('Version'));
    }

}

add_action('wp_enqueue_scripts', 'tailpress_enqueue_scripts');

/**
 * Get asset path.
 *
 * @param string  $path Path to asset.
 *
 * @return string
 */
function tailpress_asset($path)
{
    if (wp_get_environment_type() === 'production') {
        return get_stylesheet_directory_uri() . '/' . $path;
    }

    return add_query_arg('time', time(),  get_stylesheet_directory_uri() . '/' . $path);
}

/**
 * Adds option 'li_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_li_class($classes, $item, $args, $depth)
{
    if (isset($args->li_class)) {
        $classes[] = $args->li_class;
    }

    if (isset($args->{"li_class_$depth"})) {
        $classes[] = $args->{"li_class_$depth"};
    }

    return $classes;
}

add_filter('nav_menu_css_class', 'tailpress_nav_menu_add_li_class', 10, 4);

/**
 * Adds option 'submenu_class' to 'wp_nav_menu'.
 *
 * @param string  $classes String of classes.
 * @param mixed   $item The current item.
 * @param WP_Term $args Holds the nav menu arguments.
 *
 * @return array
 */
function tailpress_nav_menu_add_submenu_class($classes, $args, $depth)
{
    if (isset($args->submenu_class)) {
        $classes[] = $args->submenu_class;
    }

    if (isset($args->{"submenu_class_$depth"})) {
        $classes[] = $args->{"submenu_class_$depth"};
    }

    return $classes;
}

add_filter('nav_menu_submenu_css_class', 'tailpress_nav_menu_add_submenu_class', 10, 3);


function wp_astro_login_logo()
{
    $logo_id = get_theme_mod('custom_logo');
    if (!$logo_id) {
        return;
    }
    $custom_logo_url = wp_get_attachment_image_url($logo_id, 'full');
    if (!$custom_logo_url) {
        return;
    }
?>
    <style type="text/css">
        .login h1 a {
            background-image: url('<?php echo esc_url($custom_logo_url); ?>');
            background-size: contain;
            width: 100%;
            height: 80px;
        }
    </style>
    <?php
}
add_action('login_head', 'wp_astro_login_logo');

function wp_astro_login_logo_link()
{
    return home_url('/');
}
add_filter('login_headerurl', 'wp_astro_login_logo_link');

// css overrides for admin area
function custom_admin_css()
{
    $enabled_features = get_option('enabled_features', array());

    echo '<style>
	#adminmenu div.separator {
		background: #dadada;
	}
    #wpfooter {
        display: none;
    }
    #gallery_images_list li {
        margin-bottom: 8px;
    }
    #gallery_images_list li img {
        border: solid 2px #ccc;
        cursor: move;
    }
    #gallery_images_list li .remove-image {
        display: block;
    }
    #wp-admin-bar-ws-form-node {
        display: none;
    }
    #adminmenu,
    #adminmenu .wp-submenu,
    #adminmenuback,
    #adminmenuwrap,
    #wpadminbar {
        background-color: #535f67;
    }
    #contextual-help-link-wrap {
        display: none;
    }
    #welcome-panel {
        display: none !important;
    }
    #wpadminbar .custom-publish-icon > .ab-item:before {
        content: "\f13b";
        top: 2px;
    }
    #wpadminbar .custom-visit-icon > .ab-item:before {
        content: "\f319";
        top: 2px;
    }
    .wp-admin .components-popover.nux-dot-tip {
        display: none !important;
    }
    #wp-admin-bar-site-name {
        display: none;
    }
    li#toplevel_page_admin-page-wpsmtp_logs {
        display: none;
    }
    li.wp-not-current-submenu.wp-menu-separator:not(.wp-menu-separator-visible) {
        display: none;
    }
    li#menu-comments {
       display: none;
    }
    #toplevel_page_custom_webhook {
        display: none;
    }
    .block-editor-inserter__tablist button:nth-last-child(-n+2) {
        display: none !important;
    }
    #user_switching {
        display: none;
    }
    @media screen and (max-width: 782px) {
        #wp-admin-bar-publish-site {
            display: block !important;
            text-overflow: clip;
        }
        #wp-admin-bar-publish-site>.ab-item {
            font-size: 0;
            white-space: nowrap;
        }
        #wpadminbar .custom-publish-icon > .ab-item:before {
            font-size: 32px;
            top: 6px;
            left: 6px;
        }
    }

    </style>';
}

add_action('admin_head', 'custom_admin_css');

// css overrides for login area
function custom_login_css()
{
    echo '<style>
		.login h1 {
			margin-bottom: 40px;
		}
		#login {
			width: 90%;
			margin: 10px auto;
			max-width: 550px;
			#backtoblog {
				display: none;
			}
		}
	</style>';
}

add_action('login_head', 'custom_login_css');




// Remove menus and access to some pages in admin for the user
function hozt_adjust_permissions()
{
    $user_id = get_current_user_id();
    if ($user_id === 1) {
        return;
    }
    global $pagenow;

    // get the page they are accessing
    $admin_page = get_current_screen();
    $remove_admin_access = [
        'options-writing.php',
        'options-reading.php',
        'options-permalink.php',
        'options-general.php?page=gatsbyjs',
        'themes.php',
        'plugins.php',
        'edit.php?post_type=acf-field-group',
        'options-general.php',
        'users.php',
        'ms-delete-site.php',
        'options-writing.php',
        'options-reading.php',
        'options-discussion.php',
        'options-permalink.php',
        'edit-comments.php',
        'options-discussion.php'
    ];

    if (in_array($pagenow, $remove_admin_access)) {
        wp_redirect(admin_url());
        exit;
    }

    $current_page = isset($_GET['page']) ? $_GET['page'] : '';
    // $remove_current_pages_access = [
    //     'wp-smtp/wp-smtp.php'
    // ];

    // if ( in_array( $current_page, $remove_current_pages_access ) ) {
    //     wp_redirect( admin_url() );
    //     exit;
    // }


    // add menu link for the menus
    // is there way to
    //Exception has occurred.
    //Warning: foreach() argument must be of type array|object, null given

    global $wp_customize;
    if (isset($wp_customize)) {
        $wp_customize->remove_section('custom_css');
        $wp_customize->remove_section('colors');
        $wp_customize->remove_section('static_front_page');
    }
}
add_action('admin_init', 'hozt_adjust_permissions');


function admin_remove_menus()
{
    global $menu;
    $menu[59] = array('', 'read', 'separator', '', 'wp-menu-separator wp-menu-separator-visible');
    $menu[61] = array('', 'read', 'separator', '', 'wp-menu-separator wp-menu-separator-visible');

    if (is_super_admin()) {
        return;
    }
    remove_menu_page('solidwp-mail');
    remove_submenu_page('solidwp-mail', 'solidwp-mail-settings');
    remove_menu_page('graphiql-ide');
    remove_menu_page('plugins.php');
    remove_menu_page('edit-comments.php');
    remove_menu_page('wp-smtp/wp-smtp.php');
    remove_menu_page('themes.php');;
    remove_submenu_page('tools.php', 'ms-delete-site.php');
    remove_submenu_page('options-general.php', 'options-writing.php');
    remove_submenu_page('options-general.php', 'options-reading.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
    remove_submenu_page('options-general.php', 'options-permalink.php');
    remove_submenu_page('options-general.php', 'options-general.php?page=gatsbyjs');

    $enabled_features = get_option('enabled_features', array());

    if (!in_array('scf', $enabled_features)) {
        remove_menu_page('edit.php?post_type=acf-field-group');
    }

    if (!in_array('galleries', $enabled_features)) {
        remove_menu_page('edit.php?post_type=gallery');
    }
    if (!in_array('testimonials', $enabled_features)) {
        remove_menu_page('edit.php?post_type=testimonial');
    }
    if (!in_array('forms', $enabled_features)) {
        remove_menu_page('edit.php?post_type=form');
    }
    if (!in_array('faqs', $enabled_features)) {
        remove_menu_page('edit.php?post_type=faq');
    }
    if (!in_array('events', $enabled_features)) {
        remove_menu_page('edit.php?post_type=event');
    }
    if (!in_array('videos', $enabled_features)) {
        remove_menu_page('edit.php?post_type=video');
    }

    if (!in_array('portfolios', $enabled_features)) {
        remove_menu_page('edit.php?post_type=portfolio');
    }
    if (!in_array('others', $enabled_features)) {
        remove_menu_page('edit.php?post_type=other');
    }
    // posts
    if (!in_array('posts', $enabled_features)) {
        remove_menu_page('edit.php');
    }
    // users
    if (!in_array('users', $enabled_features)) {
        remove_menu_page('users.php');
    }
    // tools
    if (!in_array('tools', $enabled_features)) {
        remove_menu_page('tools.php');
    }
    if (!in_array('redirects', $enabled_features)) {
        remove_menu_page('admin.php?page=custom-redirects');
        remove_menu_page('custom-redirects');
    }
    // settings
    if (!in_array('settings', $enabled_features)) {
        remove_menu_page('options-general.php');
    }
    // backups
    if (!in_array('backups', $enabled_features)) {
        remove_menu_page('site-backup');
    }

    add_menu_page('Menus', 'Menus', 'manage_options', 'nav-menus.php', '', 'dashicons-menu', 60);
    add_menu_page('Customize', 'Customize', 'manage_options', 'customize.php', '', 'dashicons-admin-customizer', 60);
    // add_menu_page( 'Mail Log', 'View Mail Log', 'manage_options', 'admin.php?page=wpsmtp_logs', '', 'dashicons-email-alt', 1 );

}
add_action('admin_menu', 'admin_remove_menus', 999);

function remove_admin_bar_links()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('graphiql-ide');
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('new-content');
    $wp_admin_bar->remove_menu('wp-admin-bar-ws-form-node');
    $wp_admin_bar->remove_menu('wp-admin-bar-site-name');
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_links');


function custom_dashboard_page()
{
    add_menu_page(
        'Publish Site',
        'Publish Site',
        'manage_options',
        'custom_webhook',
        'custom_dashboard_page_content',
        'dashicons-cloud-upload',
        999
    );
}

function custom_admin_bar_menu()
{
    global $wp_admin_bar;

    // Add a parent menu item
    $wp_admin_bar->add_menu(array(
        'id' => 'publish-site',
        'title' => 'Publish Site',
        'href' => '/wp-admin/admin.php?page=custom_webhook',
        'meta' => array('class' => 'custom-publish-icon')
    ));

    $websiteUrl = get_theme_mod('website_url') . '/eDiTor/' . get_theme_mod('editor_key');

    if (!empty($websiteUrl) && filter_var($websiteUrl, FILTER_VALIDATE_URL)) {
        $wp_admin_bar->add_menu(array(
            'id' => 'visit-site',
            'title' => 'Visit Site - Edit Mode',
            'href' => $websiteUrl,
            'meta' => array('class' => 'custom-visit-icon')
        ));
    }
    // change the howdy text
    $my_account = $wp_admin_bar->get_node('my-account');
    if (! $my_account) {
        return;
    }
    $newtext = str_replace('Howdy,', 'Welcome back,', $my_account->title);
    $wp_admin_bar->add_node(array(
        'id' => 'my-account',
        'title' => $newtext,
    ));
}

add_action('wp_before_admin_bar_render', 'custom_admin_bar_menu');

function send_webhook($webhookUrl)
{
    $response = wp_remote_post($webhookUrl, [
        'body' => json_encode([
            'event' => 'publish',
            'data' => [
                'site' => get_bloginfo('name'),
                'url' => get_bloginfo('url'),
            ]
        ]),
        'headers' => [
            'Content-Type' => 'application/json',
        ],
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    return true;
}


function custom_dashboard_page_content()
{
    $webhookUrl = get_theme_mod('publish_webhook_url');
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $webhookUrl) {
        check_admin_referer('wp_astro_publish_site');
        $editorKey = bin2hex(random_bytes(15));
        set_theme_mod('editor_key', $editorKey);
        send_webhook($webhookUrl);
    ?>
        <div class="wrap">
            <h1>Site is publishing now</h1>
            <p>Please allow 2-4 minutes for the site to be fully published. Clear your local browser cache if you do not see the changes.</p>
        </div>
    <?php
        exit;
    }

    ?>
    <div class="wrap">
        <h1>Publish the Site</h1>
        <p>Click the button below to regenerate the site. It will take a few minutes to complete.</p>
        <p>Please click only when you are done editing and ready to publish your changes.</p>
        <form method="post">
            <?php wp_nonce_field('wp_astro_publish_site'); ?>
            <input type="submit" value="Publish Site" class="button button-primary">
        </form>
    </div>

<?php
}

add_action('admin_menu', 'custom_dashboard_page');

add_action('graphql_register_types', function () {
    register_graphql_object_type('CustomSiteSettings', [
        'description' => 'Custom site settings including logo and favicon.',
        'fields' => [
            'siteTitle' => [
                'type' => 'String',
                'description' => 'Title of the website'
            ],
            'tagLine' => [
                'type' => 'String',
                'description' => 'The site tagline.'
            ],
            'logo' => [
                'type' => 'MediaItem',
                'description' => 'The custom logo set in the customizer.'
            ],
            'faviconUrl' => [
                'type' => 'String',
                'description' => 'The favicon URL.'
            ],
            'mobileLogo' => [
                'type' => 'MediaItem',
                'description' => 'The mobile logo set in the customizer.'
            ],
            'defaultHeaderImage' => [
                'type' => 'MediaItem',
                'description' => 'The default header image.'
            ],
            'defaultFeaturedImage' => [
                'type' => 'MediaItem',
                'description' => 'The default featured image.'
            ],
            'faviconLogo' => [
                'type' => 'MediaItem',
                'description' => 'The favicon image.'
            ],
            'authorDisplayName' => [
                'type' => 'String',
                'description' => 'The author of the site.'
            ],
            'editorKey' => [
                'type' => 'String',
                'description' => 'Editor key'
            ],
            'enabledFeatures' => [
                'type' => ['list_of' => 'String'],
                'description' => 'Enabled features for the site.',
                'resolve' => function ($post) {
                    $enabled_features = get_option('enabled_features', array());
                    return is_array($enabled_features) ? $enabled_features : [];
                }
            ],
        ],
    ]);

    register_graphql_field('RootQuery', 'customSiteSettings', [
        'type' => 'CustomSiteSettings',
        'description' => __('The custom site settings including logo and favicon.'),
        'resolve' => function () {
            $favicon_url = get_site_icon_url();
            $tagline = get_bloginfo('description');
            $title = get_bloginfo('name');

            $favicon_id = attachment_url_to_postid($favicon_url);
            $favicon_post = $favicon_id ? get_post($favicon_id) : null;
            $favicon = $favicon_post ? new \WPGraphQL\Model\Post($favicon_post) : null;

            $logo_id = get_theme_mod('custom_logo');
            $logo_post = $logo_id ? get_post($logo_id) : null;
            $logo = $logo_post ? new \WPGraphQL\Model\Post($logo_post) : null;

            $mobile_logo_url = get_theme_mod('mobile_logo');
            $mobile_logo_id = attachment_url_to_postid($mobile_logo_url);
            $mobile_logo_post = $mobile_logo_id ? get_post($mobile_logo_id) : null;
            $mobile_logo = $mobile_logo_post ? new \WPGraphQL\Model\Post($mobile_logo_post) : null;

            // default header image
            $header_image_url = get_theme_mod('header_image');
            $header_image_id = $header_image_url ? attachment_url_to_postid($header_image_url) : null;
            $header_image_post = $header_image_id ? get_post($header_image_id) : null;
            $header_image = $header_image_post ? new \WPGraphQL\Model\Post($header_image_post) : null;

            // default featured image
            $default_featured_image_url = get_theme_mod('default_featured_image');
            $default_featured_image_id = $default_featured_image_url ? attachment_url_to_postid($default_featured_image_url) : null;
            $default_featured_image_post = $default_featured_image_id ? get_post($default_featured_image_id) : null;
            $default_featured_image = $default_featured_image_post ? new \WPGraphQL\Model\Post($default_featured_image_post) : null;

            $authorId = get_current_site_admin_id();
            $author = $authorId ? get_user_by('id', $authorId) : null;
            $authorName = $author ? $author->display_name : null;

            $editorKey = get_theme_mod('editor_key');
            $enabled_features = get_option('enabled_features', array());

            return [
                'siteTitle' => $title,
                'tagLine' => $tagline,
                'logo' => $logo,
                'faviconUrl' => $favicon_url,
                'mobileLogo' => $mobile_logo,
                'defaultHeaderImage' => $header_image,
                'defaultFeaturedImage' => $default_featured_image,
                'faviconLogo' => $favicon,
                'authorDisplayName' => $authorName,
                'editorKey' => $editorKey,
                'enabledFeatures' => implode(', ', $enabled_features),
            ];
        }
    ]);
});

function get_current_site_admin_id()
{
    $admin_users = get_users(array(
        'role' => 'administrator',
        'number' => 1,
    ));

    if (!empty($admin_users)) {
        return $admin_users[0]->ID;
    }

    return null; // Or handle the case where there are no admins
}


// Add custom taxonomy for FAQ
function create_faq_topics_taxonomy()
{
    register_taxonomy(
        'faqs',
        'faq',
        array(
            'labels' => array(
                'name' => 'Faq Sections',
                'add_new_item' => 'Add New Section',
                'new_item_name' => "New Section"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'faqTopic',
            'graphql_plural_name' => 'faqTopics'
        )
    );
    register_taxonomy(
        'portfolio_category',
        'portfolio',
        array(
            'labels' => array(
                'name' => 'Portfolio Categories',
                'add_new_item' => 'Add New Category',
                'new_item_name' => "New Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'portfolioCategory',
            'graphql_plural_name' => 'portfolioCategories'
        )
    );
}
add_action('init', 'create_faq_topics_taxonomy');

// Add custom taxonomy to FAQ list in admin
function add_faq_topics_columns($columns)
{
    $columns['faq_topics'] = 'Topic';
    return $columns;
}
add_filter('manage_faq_posts_columns', 'add_faq_topics_columns'); // Replace 'faq' with the key of your custom post type

// Add content to the new column
function show_faq_topics_column_content($column, $post_id)
{
    switch ($column) {
        case 'faq_topics':
            $terms = get_the_term_list($post_id, 'faqs', '', ', ', ''); // Replace 'faq_topics' with your taxonomy
            if (is_string($terms)) {
                echo $terms;
            } else {
                echo 'No Topics Assigned'; // Display if no terms are assigned
            }
            break;
    }
}
add_action('manage_faq_posts_custom_column', 'show_faq_topics_column_content', 10, 2);

// Add custom taxonomy for post type testimonials
function create_testimonial_tags_taxonomy()
{
    register_taxonomy(
        'testimonial_tag',
        'testimonial',
        array(
            'labels' => array(
                'name' => 'Testimonial Tags',
                'add_new_item' => 'Add New Tag',
                'new_item_name' => "New Tag"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'testimonialTag',
            'graphql_plural_name' => 'testimonialTags'
        )
    );
}
add_action('init', 'create_testimonial_tags_taxonomy');

// add tags for the "other" custom post type
function create_other_tags_taxonomy()
{
    register_taxonomy(
        'other_tag',
        'other',
        array(
            'labels' => array(
                'name' => 'Other Tags',
                'add_new_item' => 'Add New Tag',
                'new_item_name' => "New Tag"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'otherTag',
            'graphql_plural_name' => 'otherTags'
        )
    );
}
add_action('init', 'create_other_tags_taxonomy');


add_action('after_setup_theme', 'custom_image_sizes');
function custom_image_sizes()
{
    add_image_size('medium_large', 800, 800, false);
}


add_filter('image_size_names_choose', 'custom_image_size_names');
function custom_image_size_names($sizes)
{
    return array_merge($sizes, array(
        'medium_large' => __('Medium Large'),
    ));
}


function hozt_customize_register($wp_customize)
{
    // Add setting for additional logo
    $wp_customize->add_setting('mobile_logo', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Add control for additional logo
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'additional_logo_control', array(
        'label' => __('Mobile Logo'),
        'section' => 'title_tagline', // This is the site identity section
        'settings' => 'mobile_logo',
        'priority' => 8, // Adjust the priority to position the control
    )));

    // add default header image
    $wp_customize->add_setting('header_image', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // add control for default header image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'header_image_control', array(
        'label' => __('Default Header Image'),
        'section' => 'title_tagline',
        'settings' => 'header_image',
        'priority' => 8,
    )));


    // add default featured image
    $wp_customize->add_setting('default_featured_image', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));

    // add control for default featured image
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'default_featured_image_control', array(
        'label' => __('Default Featured Image'),
        'section' => 'title_tagline',
        'settings' => 'default_featured_image',
        'priority' => 8,
    )));

    // enabled features
    $wp_customize->add_setting('enabled_features', array(
        'default' => array(),
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // add a new setting for publish webhook text
    $wp_customize->add_setting('publish_webhook_url', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // add a new control for publish webhook text
    $wp_customize->add_control('publish_webhook_url_control', array(
        'label' => 'Publish Webhook',
        'section' => 'title_tagline',
        'settings' => 'publish_webhook_url',
        'type' => 'text',
        'priority' => 9,
    ));

    // add a new setting for editorKey
    $wp_customize->add_setting('editor_key', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // add a new control for editorKey
    $wp_customize->add_control('editor_key_control', array(
        'label' => 'Editor Key',
        'section' => 'title_tagline',
        'settings' => 'editor_key',
        'type' => 'text',
        'priority' => 9,
    ));

    $wp_customize->add_setting('website_url', array(
        'default' => '',
        'transport' => 'refresh',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('website_url_control', array(
        'label' => 'Website Url',
        'section' => 'title_tagline',
        'settings' => 'website_url',
        'type' => 'text',
        'priority' => 9,
    ));
}

add_action('customize_register', 'hozt_customize_register');


function remove_welcome_panel()
{
    remove_action('welcome_panel', 'wp_welcome_panel');
}
add_action('welcome_panel', 'remove_welcome_panel');

function remove_dashboard_widgets()
{
    // Remove existing dashboard widgets
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // Right Now
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress Events and News

    // add a new dashboard widget with rss feed

}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets');


// Register a custom meta box to add custom menu links to add to the menus
function custom_menu_metabox()
{
    add_meta_box(
        'custom-menu-links',         // ID
        'Other Links',              // Title
        'custom_menu_metabox_callback', // Callback
        'nav-menus',                 // Screen
        'side',                      // Context
        'default'                    // Priority
    );
}
add_action('admin_head-nav-menus.php', 'custom_menu_metabox');

// Callback function to display the meta box content
function custom_menu_metabox_callback()
{
?>
    <div id="posttype-custom-links" class="posttypediv">
        <div id="tabs-panel-custom-links" class="tabs-panel tabs-panel-active">
            <ul id="custom-links-checklist" class="categorychecklist form-no-clear">
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Calendar View
                    </label>
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Calendar View">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="/events/">
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                </li>
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-2][menu-item-object-id]" value="-2"> Event List
                    </label>
                    <input type="hidden" class="menu-item-title" name="menu-item[-2][menu-item-title]" value="Event List">
                    <input type="hidden" class="menu-item-url" name="menu-item[-2][menu-item-url]" value="/events-list/">
                    <input type="hidden" class="menu-item-type" name="menu-item[-2][menu-item-type]" value="custom">
                </li>
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Articles
                    </label>
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Articles">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="/articles/">
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                </li>
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Tags
                    </label>
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Tags">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="/tag/">
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                </li>
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[-1][menu-item-object-id]" value="-1"> Testimonials
                    </label>
                    <input type="hidden" class="menu-item-title" name="menu-item[-1][menu-item-title]" value="Testimonials">
                    <input type="hidden" class="menu-item-url" name="menu-item[-1][menu-item-url]" value="/testimonials/">
                    <input type="hidden" class="menu-item-type" name="menu-item[-1][menu-item-type]" value="custom">
                </li>
            </ul>
        </div>
        <p class="button-controls">
            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-custom-menu-item" id="submit-posttype-custom-links">
                <span class="spinner"></span>
            </span>
        </p>
    </div>
<?php
}

// function redirect_to_backend() {
//     if (strpos($_SERVER['REQUEST_URI'], '/wp-json/jwt-auth/v1/token') !== false) {
//         return;
//     }
//     if ( !is_admin() && !is_login() ) {
//         wp_redirect( site_url('wp-login.php') );
//         exit();
//     }
// }
// add_action( 'init', 'redirect_to_backend' );

// function remove_themes_submenu() {
//     remove_submenu_page('themes.php', 'themes.php');
// }
// add_action('admin_menu', 'remove_themes_submenu', 999);

// redirect to admin dashboard after login
function force_admin_redirect($redirect_to, $request, $user)
{
    if (isset($user->roles) && is_array($user->roles)) {
        return admin_url();
    }
    return $redirect_to;
}
add_filter('login_redirect', 'force_admin_redirect', 10, 3);
