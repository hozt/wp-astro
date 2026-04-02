<?php

// Add custom field call content_footer to the template post type
// make sure the field can be edited by anyone that can edit the post type
// Add custom field called content_footer to the template post type
function add_content_footer_meta_box()
{
    add_meta_box(
        'content_footer_meta_box',
        'Content Footer',
        'show_content_footer_meta_box',
        'template',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_content_footer_meta_box');

function show_content_footer_meta_box($post)
{
    wp_nonce_field(basename(__FILE__), 'content_footer_meta_box_nonce');
    $content = get_post_meta($post->ID, 'content_footer', true);

    wp_editor($content, 'content_footer', array(
        'textarea_name' => 'content_footer',
        'media_buttons' => true,
        'tinymce'       => true,
        'textarea_rows' => 10,
        'editor_height' => 150,
        'editor_class'  => 'content_footer_editor'
    ));
}

function save_content_footer_meta($post_id)
{
    if (!isset($_POST['content_footer_meta_box_nonce']) || !wp_verify_nonce($_POST['content_footer_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if ('template' === $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    if (isset($_POST['content_footer'])) {
        $new_content = wp_kses_post($_POST['content_footer']);
        update_post_meta($post_id, 'content_footer', $new_content);
    }
}

add_action('save_post', 'save_content_footer_meta');

// Add custom field to the tag edit form
function add_tag_hide_field($term) {
    $term_id = $term->term_id;
    $hide_value = get_term_meta($term_id, 'hide_from_display', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="hide_from_display"><?php _e('Hide from Display', 'your-text-domain'); ?></label></th>
        <td>
            <input type="checkbox" name="hide_from_display" id="hide_from_display" value="1" <?php checked($hide_value, 1); ?> />
            <p class="description"><?php _e('Check this box to hide this tag from the list of tags.', 'your-text-domain'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('post_tag_edit_form_fields', 'add_tag_hide_field', 10, 2);

// Save the custom field value
function save_tag_hide_field($term_id) {
    if (isset($_POST['hide_from_display'])) {
        update_term_meta($term_id, 'hide_from_display', 1);
    } else {
        delete_term_meta($term_id, 'hide_from_display');
    }
}
add_action('edited_post_tag', 'save_tag_hide_field');
add_action('create_post_tag', 'save_tag_hide_field');



// add custom field url to the portfolio post type
function add_link_url_meta_box()
{
    add_meta_box(
        'link_url_meta_box',
        'Link URL',
        'show_link_url_meta_box',
        'portfolio',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_link_url_meta_box');

function show_link_url_meta_box()
{
    global $post;
    $meta = get_post_meta($post->ID, 'link_url', true);
    echo '<input type="hidden" name="link_url_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="link_url">URL:</label><br>';
    echo '<input type="text" name="link_url" id="link_url" class="meta-image regular-text" value="' . $meta . '">';
    echo '</p>';
}

function save_link_url_meta($post_id)
{
    if (!isset($_POST['link_url_meta_box_nonce']) || !wp_verify_nonce($_POST['link_url_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if ('portfolio' === $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $old = get_post_meta($post_id, 'link_url', true);
    $new = isset($_POST['link_url']) ? $_POST['link_url'] : '';
    $new = sanitize_text_field($new);

    // Update the meta field if the new value is different from the old one
    if (!empty($new) && $new !== $old) {
        update_post_meta($post_id, 'link_url', $new);
    } elseif (empty($new) && !empty($old)) {
        // Delete the meta field if the new value is empty and the old value is not
        delete_post_meta($post_id, 'link_url', $old);
    }
}

add_action('save_post', 'save_link_url_meta');

/* New gallery image field */
function register_gallery_meta_box()
{
    $post_types = ['gallery','other', 'portfolio'];
    foreach ($post_types as $post_type) {
        add_meta_box(
            'gallery_images_meta_box',
            'Gallery Images',
            'gallery_images_meta_box_html',
            $post_type
        );
    }
}
add_action('add_meta_boxes', 'register_gallery_meta_box');

// add sticky checkbox to the custom posts testimonials, events, portfolio, videos, and gallery
function add_sticky_checkbox()
{
    $post_types = ['testimonial', 'event', 'portfolio', 'video', 'gallery'];
    foreach ($post_types as $post_type) {
        add_meta_box(
            'sticky_meta_box',
            'Sticky',
            'show_sticky_meta_box',
            $post_type,
            'side',
            'high'
        );
    }
}

add_action('add_meta_boxes', 'add_sticky_checkbox');

function show_sticky_meta_box($post)
{
    wp_nonce_field('sticky_meta_box', 'sticky_meta_box_nonce');
    $is_sticky = get_post_meta($post->ID, '_sticky_post', true);
    $is_sticky = $is_sticky === 'yes' ? true : false;
    echo '<input type="checkbox" id="sticky_post" name="sticky_post" ' . checked($is_sticky, true, false) . ' /> ';
    echo '<label for="sticky">Featured Content</label> ';
}

function save_sticky_post($post_id)
{
    if (!isset($_POST['sticky_meta_box_nonce']) || !wp_verify_nonce($_POST['sticky_meta_box_nonce'], 'sticky_meta_box')) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if (isset($_POST['post_type']) && in_array($_POST['post_type'], ['testimonial', 'event', 'portfolio', 'video', 'gallery'])) {
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
    }

    $old = get_post_meta($post_id, '_sticky_post', true);
    $new = isset($_POST['sticky_post']) ? 'yes' : 'no';

    if ($new !== $old) {
        update_post_meta($post_id, '_sticky_post', $new);
    } elseif ($new === 'no' && $old) {
        delete_post_meta($post_id, '_sticky_post', $old);
    }
}

add_action('save_post', 'save_sticky_post');


function gallery_images_meta_box_html($post)
{
    // Use nonce for verification
    wp_nonce_field('gallery_images_meta_box', 'gallery_images_meta_box_nonce');

    // Get existing images if any
    $image_ids = get_post_meta($post->ID, '_gallery_image_ids', true);
    $image_ids = explode(',', $image_ids);

    // Display the sortable list of images
    echo '<ul id="gallery_images_list">';
    foreach ($image_ids as $image_id) {
        $image_url = wp_get_attachment_url($image_id);
        if (!empty($image_url)) {  // Check if image URL exists
            echo '<li class="ui-state-default" data-id="' . esc_attr($image_id) . '">';
            echo '<img src="' . esc_url($image_url) . '" style="width:100px;">';
            echo '<span class="remove-image">Remove</span>';
            echo '</li>';
        }
    }
    echo '</ul>';

    // Hidden input field to store image IDs
    echo '<input type="hidden" id="gallery_image_ids" name="gallery_image_ids" value="' . implode(',', $image_ids) . '" />';

    // Button to add images
    echo '<button type="button" class="button" id="add_gallery_image">Add Images</button>';
}

function enqueue_gallery_scripts($hook)
{
    global $post;
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('gallery' === get_post_type($post)) {
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('gallery-admin-js', get_template_directory_uri() . '/resources/js/gallery-image-upload.js', array('jquery', 'jquery-ui-sortable'), null, true);
            wp_enqueue_media();
        }
    }
}
add_action('admin_enqueue_scripts', 'enqueue_gallery_scripts');

function save_gallery_images($post_id)
{
    // Check if nonce is set and verify it to ensure the request is valid
    if (
        !isset($_POST['gallery_images_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['gallery_images_meta_box_nonce'], 'gallery_images_meta_box')
    ) {
        return;
    }

    // Check for autosave to prevent the metadata from being saved during autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user capabilities to ensure they have permission to edit the post
    if (isset($_POST['post_type']) && 'gallery' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Check if gallery_image_ids is set and is not empty, then update the post meta
    if (isset($_POST['gallery_image_ids']) && !empty($_POST['gallery_image_ids'])) {
        // Sanitize the input to ensure it's a safe string for saving
        $image_ids = sanitize_text_field($_POST['gallery_image_ids']);
        // Explode the string into an array to check each ID individually
        $image_ids = explode(',', $image_ids);
        // Filter out any empty values to prevent storing unnecessary commas
        $image_ids = array_filter($image_ids, 'is_numeric');  // Ensuring each is a numeric value
        // Implode back into a string to store it in the database
        $image_ids = implode(',', $image_ids);
        update_post_meta($post_id, '_gallery_image_ids', $image_ids);
    } else {
        // If there are no IDs or input is empty, delete the metadata to clean up
        delete_post_meta($post_id, '_gallery_image_ids');
    }
}
add_action('save_post', 'save_gallery_images');

/* Banner page field */
function add_custom_meta_box()
{
    add_meta_box(
        'custom_meta_box',
        'Custom Fields',
        'show_custom_meta_box',
        'page',
        'normal',
        'high'
    );

    $custom_post_types = ['post', 'form', 'template', 'portfolio'];
    foreach ($custom_post_types as $post_type) {
        add_meta_box(
            'custom_meta_box',
            'Custom Fields',
            'show_custom_meta_box',
            $post_type,
            'normal',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'add_custom_meta_box');

function show_custom_meta_box()
{
    global $post;
    $meta = get_post_meta($post->ID, 'banner_image', true);
    echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="banner_image">Header banner image:</label><br>';
    echo '<input type="text" name="banner_image" id="banner_image" class="meta-image regular-text" value="' . $meta . '">';
    echo '<input type="button" class="button image-upload" value="Browse">';
    if (!empty($meta)) {
        echo '<input type="button" class="button image-remove" value="Remove">';
    }
    echo '</p>';
    echo '<p><img id="image-preview" style="max-width: 250px; max-height: 250px; display: ' . ($meta ? 'block' : 'none') . ';" src="' . esc_url($meta) . '" alt="Image preview" /></p>';
    $meta = get_post_meta($post->ID, 'subtitle', true);
    echo '<input type="hidden" name="subtitle_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="subtitle">Subtitle:</label><br>';
    echo '<input type="text" name="subtitle" id="subtitle" class="regular-text" value="' . $meta . '">';
    echo '</p>';
    // add meta title
    $meta = get_post_meta($post->ID, 'meta_title', true);
    echo '<input type="hidden" name="meta_title_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="meta_title">Meta Title:</label><br>';
    echo '<input type="text" name="meta_title" id="meta_title" class="regular-text" value="' . $meta . '">';
    // show good if the length is between 50 and 60 characters
    echo '<p><span id="meta_title_count"></span></p>';
    echo '</p>';
    // add meta description
    $meta = get_post_meta($post->ID, 'meta_description', true);
    echo '<input type="hidden" name="meta_description_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="meta_description">Meta Description:</label><br>';
    echo '<textarea name="meta_description" id="meta_description" class="regular-textarea" style="width:100%">' . $meta . '</textarea>';
    // add banner html
    $meta = get_post_meta($post->ID, 'banner_html', true);
    echo '<input type="hidden" name="banner_html_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="banner_html">Banner HTML:</label><br>';
    echo '<textarea name="banner_html" id="banner_html" class="regular-textarea" style="width:100%">' . esc_textarea($meta) . '</textarea>';

    // add a display that counts the number of characters.  It should be between 120 and 160 to display good
    echo '<p><span id="meta_description_count"></span></p>';
    echo '</p>';
    // add custom js
    $meta = get_post_meta($post->ID, 'custom_js', true);
    echo '<input type="hidden" name="custom_js_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="custom_js">Custom JavaScript:</label><br>';
    echo '<textarea name="custom_js" id="custom_js" class="regular-textarea" style="width:100%">' . esc_textarea($meta) . '</textarea>';
    echo '</p>';
    // add a custom checkbox field "Exclude from sitemap"

    $meta = get_post_meta($post->ID, 'exclude_from_sitemap', true);
    echo '<input type="hidden" name="exclude_from_sitemap_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="exclude_from_sitemap">Exclude from sitemap:</label> ';
    echo '<input type="checkbox" name="exclude_from_sitemap" id="exclude_from_sitemap" value="yes" ' . checked($meta, 'yes', false) . '>';
    echo '</p>';
}

function save_custom_meta($post_id)
{
    // Check if nonce is set and valid to ensure the request comes from the correct context
    if (!isset($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Prevent the function from executing during autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check user permissions to ensure they have the ability to edit the post
    if ('page' === $_POST['post_type'] && !current_user_can('edit_page', $post_id)) {
        return $post_id;
    }

    // Retrieve the old and new values
    $old = get_post_meta($post_id, 'banner_image', true);
    $new = isset($_POST['banner_image']) ? $_POST['banner_image'] : '';

    // Sanitize the new value to ensure it's safe for storage
    $new = sanitize_text_field($new);

    // Update the meta field if the new value is different from the old one
    if (!empty($new) && $new !== $old) {
        update_post_meta($post_id, 'banner_image', $new);
    } elseif (empty($new) && !empty($old)) {
        // Delete the meta field if the new value is empty and the old value is not
        delete_post_meta($post_id, 'banner_image', $old);
    }

    // save the subtitle
    $old_subtitle = get_post_meta($post_id, 'subtitle', true);
    $new_subtitle = isset($_POST['subtitle']) ? $_POST['subtitle'] : '';
    $new_subtitle = sanitize_text_field($new_subtitle);
    if (!empty($new_subtitle) && $new_subtitle !== $old_subtitle) {
        update_post_meta($post_id, 'subtitle', $new_subtitle);
    } elseif (empty($new_subtitle) && !empty($old_subtitle)) {
        delete_post_meta($post_id, 'subtitle', $old_subtitle);
    }

    // save the meta title
    $old_meta_title = get_post_meta($post_id, 'meta_title', true);
    $new_meta_title = isset($_POST['meta_title']) ? $_POST['meta_title'] : '';
    $new_meta_title = sanitize_text_field($new_meta_title);
    if (!empty($new_meta_title) && $new_meta_title !== $old_meta_title) {
        update_post_meta($post_id, 'meta_title', $new_meta_title);
    } elseif (empty($new_meta_title) && !empty($old_meta_title)) {
        delete_post_meta($post_id, 'meta_title', $old_meta_title);
    }

    // save the meta description
    $old_meta_description = get_post_meta($post_id, 'meta_description', true);
    $new_meta_description = isset($_POST['meta_description']) ? $_POST['meta_description'] : '';
    $new_meta_description = sanitize_text_field($new_meta_description);
    if (!empty($new_meta_description) && $new_meta_description !== $old_meta_description) {
        update_post_meta($post_id, 'meta_description', $new_meta_description);
    } elseif (empty($new_meta_description) && !empty($old_meta_description)) {
        delete_post_meta($post_id, 'meta_description', $old_meta_description);
    }

    // save the banner html
    $old_banner_html = get_post_meta($post_id, 'banner_html', true);
    $new_banner_html = isset($_POST['banner_html']) ? $_POST['banner_html'] : '';
    $new_banner_html = wp_kses_post($new_banner_html);
    if (!empty($new_banner_html) && $new_banner_html !== $old_banner_html) {
        update_post_meta($post_id, 'banner_html', $new_banner_html);
    } elseif (empty($new_banner_html) && !empty($old_banner_html)) {
        delete_post_meta($post_id, 'banner_html', $old_banner_html);
    }

    // save the custom js
    $old_custom_js = get_post_meta($post_id, 'custom_js', true);
    $new_custom_js = isset($_POST['custom_js']) ? $_POST['custom_js'] : '';
    $new_custom_js = wp_kses_post($new_custom_js);
    if (!empty($new_custom_js) && $new_custom_js !== $old_custom_js) {
        update_post_meta($post_id, 'custom_js', $new_custom_js);
    } elseif (empty($new_custom_js) && !empty($old_custom_js)) {
        delete_post_meta($post_id, 'custom_js', $old_custom_js);
    }

    // save the exclude from sitemap checkbox
    $old_exclude_from_sitemap = get_post_meta($post_id, 'exclude_from_sitemap', true);
    $new_exclude_from_sitemap = isset($_POST['exclude_from_sitemap']) ? 'yes' : 'no';
    if ($new_exclude_from_sitemap !== $old_exclude_from_sitemap) {
        update_post_meta($post_id, 'exclude_from_sitemap', $new_exclude_from_sitemap);
    } elseif ($new_exclude_from_sitemap === 'no' && $old_exclude_from_sitemap) {
        delete_post_meta($post_id, 'exclude_from_sitemap', $old_exclude_from_sitemap);
    }
}

add_action('save_post', 'save_custom_meta');

// Enqueue the WordPress media scripts
function enqueue_custom_admin_scripts()
{
    wp_enqueue_media();
    wp_enqueue_script('gallery-admin-js', get_template_directory_uri() . '/resources/js/gallery-image-upload.js', array('jquery', 'jquery-ui-sortable'), null, true);
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_scripts');

// add new field called related_link that is a text field one to many
function add_related_link_meta_box()
{
    $custom_post_types = ['post', 'event'];
    foreach ($custom_post_types as $post_type) {
        add_meta_box(
            'related_link_meta_box',
            'Related Link',
            'show_related_link_meta_box',
            $post_type,
            'normal',
            'high'
        );
    }
}

add_action('add_meta_boxes', 'add_related_link_meta_box');

function show_related_link_meta_box($post)
{
    $meta = get_post_meta($post->ID, 'related_link', false);
    wp_nonce_field(basename(__FILE__), 'related_link_meta_box_nonce');
    echo '<div id="related_links_container">';
    if (!empty($meta)) {
        foreach ($meta as $link) {
            echo '<p><input type="text" name="related_link[]" class="meta-image regular-text" value="' . esc_attr($link) . '">';
            echo '<button type="button" class="remove-link">Remove</button></p>';
        }
    } else {
        echo '<p><input type="text" name="related_link[]" class="meta-image regular-text">';
        echo '<button type="button" class="remove-link">Remove</button></p>';
    }
    echo '</div>';
    echo '<button type="button" id="add_related_link">Add Another Link</button>';
}

function save_related_link_meta($post_id)
{
    if (!isset($_POST['related_link_meta_box_nonce']) || !wp_verify_nonce($_POST['related_link_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    $post_type = get_post_type($post_id);
    if (!in_array($post_type, ['post', 'event']) || !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $new = isset($_POST['related_link']) ? $_POST['related_link'] : array();

    $new = array_map('sanitize_text_field', $new);
    $new = array_filter($new);

    delete_post_meta($post_id, 'related_link');

    foreach ($new as $link) {
        add_post_meta($post_id, 'related_link', $link);
    }
}

add_action('save_post', 'save_related_link_meta');

function enqueue_related_link_scripts() {
    global $post_type;
    if (in_array($post_type, ['post', 'event'])) {
        wp_enqueue_script('related-link-script', get_template_directory_uri() . '/resources/js/related-link.js', array('jquery'), '1.0', true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_related_link_scripts');


// Add a new custom field testimonial_source to post type testimonial
function add_testimonial_source_meta_box()
{
    add_meta_box(
        'testimonial_source_meta_box',
        'Testimonial Source',
        'show_testimonial_source_meta_box',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_testimonial_source_meta_box');

function show_testimonial_source_meta_box()
{
    global $post;
    $meta = get_post_meta($post->ID, 'testimonial_source', true);
    echo '<input type="hidden" name="testimonial_source_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="testimonial_source">Source:</label><br>';
    echo '<input type="text" name="testimonial_source" id="testimonial_source" class="meta-image regular-text" value="' . $meta . '">';
    echo '</p>';
}

function save_testimonial_source_meta($post_id)
{
    // Check if nonce is set and valid to ensure the request comes from the correct context
    if (!isset($_POST['testimonial_source_meta_box_nonce']) || !wp_verify_nonce($_POST['testimonial_source_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // Prevent the function from executing during autosaves
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check user permissions to ensure they have the ability to edit the post
    if ('testimonial' === $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Retrieve the old and new values
    $old = get_post_meta($post_id, 'testimonial_source', true);
    $new = isset($_POST['testimonial_source']) ? $_POST['testimonial_source'] : '';

    // Sanitize the new value to ensure it's safe for storage
    $new = sanitize_text_field($new);

    // Update the meta field if the new value is different from the old one
    if (!empty($new) && $new !== $old) {
        update_post_meta($post_id, 'testimonial_source', $new);
    } elseif (empty($new) && !empty($old)) {
        // Delete the meta field if the new value is empty and the old value is not
        delete_post_meta($post_id, 'testimonial_source', $old);
    }
}

add_action('save_post', 'save_testimonial_source_meta');

// Add above fields to the GraphQL schema
add_action('graphql_register_types', function () {

    $gallery_post_types = ['other', 'gallery', 'portfolio'];
    foreach ($gallery_post_types as $post_type) {
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object) {
            register_graphql_field($post_type_object->graphql_single_name, 'galleryImages', [
                'type' => ['list_of' => 'MediaItem'],
                'description' => 'List of images associated with the post',
                'resolve' => function ($post, $args, $context, $info) {
                    $image_ids = get_post_meta($post->ID, '_gallery_image_ids', true);
                    if (!$image_ids) {
                        return [];
                    }
                    $image_ids = explode(',', $image_ids);
                    $image_ids = array_filter($image_ids); // Remove any empty values

                    $images = array_map(function ($id) use ($context) {
                        return \WPGraphQL\Data\DataSource::resolve_post_object($id, $context);
                    }, $image_ids);

                    return $images;
                }
            ]);
        }
    }

    register_graphql_field('Page', 'bannerImage', [
        'type' => 'MediaItem',
        'description' => 'Banner image for the page',
        'resolve' => function ($post, $args, $context, $info) {
            $bannerImageUrl = get_post_meta($post->ID, 'banner_image', true);
            if (!$bannerImageUrl) {
                return null;
            }
            $banner_image_id = attachment_url_to_postid($bannerImageUrl);
            if (!$banner_image_id) {
                return null;
            }
            return \WPGraphQL\Data\DataSource::resolve_post_object($banner_image_id, $context);
        }
    ]);

    register_graphql_field('Page', 'subtitle', [
        'type' => 'String',
        'description' => 'Subtitle for the page',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'subtitle', true);
        }
    ]);

    // add meta title field to the Page type
    register_graphql_field('Page', 'metaTitle', [
        'type' => 'String',
        'description' => 'Meta title for the page',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'meta_title', true);
        }
    ]);

    // add meta description field to the Page type
    register_graphql_field('Page', 'metaDescription', [
        'type' => 'String',
        'description' => 'Meta description for the page',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'meta_description', true);
        }
    ]);

    // also add to custom post types
    $custom_post_types = ['post', 'form', 'template', 'portfolio'];
    foreach ($custom_post_types as $post_type) {
        register_graphql_field($post_type, 'bannerImage', [
            'type' => 'MediaItem',
            'description' => 'Banner image for the post',
            'resolve' => function ($post, $args, $context, $info) {
                $bannerImageUrl = get_post_meta($post->ID, 'banner_image', true);
                if (!$bannerImageUrl) {
                    return null;
                }
                $banner_image_id = attachment_url_to_postid($bannerImageUrl);
                if (!$banner_image_id) {
                    return null;
                }
                return \WPGraphQL\Data\DataSource::resolve_post_object($banner_image_id, $context);
            }
        ]);

        register_graphql_field($post_type, 'subtitle', [
            'type' => 'String',
            'description' => 'Subtitle for the post',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'subtitle', true);
            }
        ]);

        // add meta title field to the custom post types
        register_graphql_field($post_type, 'metaTitle', [
            'type' => 'String',
            'description' => 'Meta title for the post',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'meta_title', true);
            }
        ]);

        // add meta description field to the custom post types
        register_graphql_field($post_type, 'metaDescription', [
            'type' => 'String',
            'description' => 'Meta description for the post',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'meta_description', true);
            }
        ]);

        // add banner_html field to the Page type
        register_graphql_field('Page', 'bannerHtml', [
            'type' => 'String',
            'description' => 'Banner HTML for the page',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'banner_html', true);
            }
        ]);

        // add exclude from sitemap field to the custom post types
        register_graphql_field($post_type, 'excludeFromSitemap', [
            'type' => 'Boolean',
            'description' => 'Whether the post should be excluded from the sitemap',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'exclude_from_sitemap', true) === 'yes';
            }
        ]);

    }

    // add related_link field to the Post type
    $custom_post_types = ['post', 'event'];
    foreach ($custom_post_types as $post_type) {
        register_graphql_field($post_type, 'relatedLinks', [
            'type' => ['list_of' => 'String'],
            'description' => 'Related links for the post',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'related_link', false);
            }
        ]);
    };

    // add testimonial source field to the Testimonial type
    register_graphql_field('Testimonial', 'source', [
        'type' => 'String',
        'description' => 'Source of the testimonial',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'testimonial_source', true);
        }
    ]);

    // add event start and end dates to the Event type
    register_graphql_field('Event', 'startDatetime', [
        'type' => 'String',
        'description' => 'Start date and time of the event',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, '_event_start_datetime', true);
        }
    ]);

    // add Javascript field
    register_graphql_field('Page', 'customJs', [
        'type' => 'String',
        'description' => 'Custom JavaScript for the page',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'custom_js', true);
        }
    ]);

    // add exclude from sitemap field
    register_graphql_field('Page', 'excludeFromSitemap', [
        'type' => 'Boolean',
        'description' => 'Whether the page should be excluded from the sitemap',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'exclude_from_sitemap', true) === 'yes';
        }
    ]);

    register_graphql_field('Event', 'endDatetime', [
        'type' => 'String',
        'description' => 'End date and time of the event',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, '_event_end_datetime', true);
        }
    ]);

    register_graphql_field('Event', 'location', [
        'type' => 'String',
        'description' => 'Location of the event',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, '_event_location', true);
        }
    ]);

    $video_post_types = ['video', 'post'];
    foreach ($video_post_types as $post_type) {
        register_graphql_field($post_type, 'videoUrl', [
            'type' => 'String',
            'description' => 'URL of the video',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, 'video_url', true);
            }
        ]);
    }

    // add portfolio url field to the Portfolio type
    register_graphql_field('Portfolio', 'link_url', [
        'type' => 'String',
        'description' => 'URL of the portfolio post type',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'link_url', true);
        }
    ]);

    // add hide_from_display field to the Tag type
    register_graphql_field('Tag', 'hideFromDisplay', [
        'type' => 'Boolean',
        'description' => 'Whether the tag should be hidden from display',
        'resolve' => function ($tag, $args, $context, $info) {
            return get_term_meta($tag->term_id, 'hide_from_display', true) === '1';
        }
    ]);

    // add additional_image media image field for the portfolio post type
    register_graphql_field('Portfolio', 'additionalImage', [
        'type' => 'MediaItem',
        'description' => 'Additional image for the portfolio post type',
        'resolve' => function ($post, $args, $context, $info) {
            $additionalImageUrl = get_post_meta($post->ID, 'additional_image', true);
            if (!$additionalImageUrl) {
                return null;
            }
            $additional_image_id = attachment_url_to_postid($additionalImageUrl);
            if (!$additional_image_id) {
                return null;
            }
            return \WPGraphQL\Data\DataSource::resolve_post_object($additional_image_id, $context);
        }
    ]);

    register_graphql_field('Template', 'contentFooter', [
        'type' => 'String',
        'description' => 'Content footer for the template post type',
        'resolve' => function ($post, $args, $context, $info) {
            return get_post_meta($post->ID, 'content_footer', true);
        }
    ]);

    // add sticky field to the custom post types
    $custom_post_types = ['testimonial', 'event', 'portfolio', 'video', 'gallery'];
    foreach ($custom_post_types as $post_type) {
        register_graphql_field($post_type, 'isSticky', [
            'type' => 'Boolean',
            'description' => 'Whether the post is sticky',
            'resolve' => function ($post, $args, $context, $info) {
                return get_post_meta($post->ID, '_sticky_post', true) === 'yes';
            }
        ]);
    }

    register_graphql_field('RootQueryToPostConnectionWhereArgs', 'isStickyPost', [
        'type' => 'Boolean',
        'description' => 'Filter for sticky posts',
    ]);
});

add_filter('graphql_post_object_connection_query_args', function ($query_args, $source, $args, $context, $info) {
    if (isset($args['where']['isStickyPost']) && $args['where']['isStickyPost'] === true) {
        $sticky_posts = get_option('sticky_posts');
        if (!empty($sticky_posts)) {
            $query_args['post__in'] = $sticky_posts;
        }
    }
    return $query_args;
}, 10, 5);

// event start and end dates

// Register Meta Boxes
function register_event_meta_boxes()
{
    add_meta_box(
        'event_location_meta_box',
        'Event Location',
        'event_location_meta_box_html',
        'event',
        'normal',
        'high'
    );
    add_meta_box(
        'event_start_datetime_meta_box',
        'Event Start',
        'event_start_datetime_meta_box_html',
        'event',
        'normal',
        'high'
    );
    add_meta_box(
        'event_end_datetime_meta_box',
        'Event End',
        'event_end_datetime_meta_box_html',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'register_event_meta_boxes');

// Meta Box HTML
function event_location_meta_box_html($post)
{
    wp_nonce_field('event_location_meta_box', 'event_location_meta_box_nonce');
    $location = get_post_meta($post->ID, '_event_location', true);
    echo '<label for="event_location">Location: </label>';
    echo '<input type="text" id="event_location" name="event_location" value="' . esc_attr($location) . '" class="regular-text" />';
}

function event_start_datetime_meta_box_html($post)
{
    wp_nonce_field('event_start_datetime_meta_box', 'event_start_datetime_meta_box_nonce');
    $start_datetime = get_post_meta($post->ID, '_event_start_datetime', true);
    echo '<label for="event_start_datetime">Date and Time: </label>';
    echo '<input type="text" id="event_start_datetime" style="width:170px;" name="event_start_datetime" value="' . esc_attr($start_datetime) . '" class="regular-text datetime-picker" />';
}

function event_end_datetime_meta_box_html($post)
{
    wp_nonce_field('event_end_datetime_meta_box', 'event_end_datetime_meta_box_nonce');
    $end_datetime = get_post_meta($post->ID, '_event_end_datetime', true);
    echo '<label for="event_end_datetime">Date and Time: </label>';
    echo '<input type="text" id="event_end_datetime" style="width:170px;" name="event_end_datetime" value="' . esc_attr($end_datetime) . '" class="regular-text datetime-picker" />';
}

// Enqueue Datetime Picker Scripts
function enqueue_event_datetime_picker_scripts($hook)
{
    global $post;
    if (($hook == 'post-new.php' || $hook == 'post.php') && 'event' === get_post_type($post)) {
        wp_enqueue_script('jquery-datetimepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js', array('jquery'), null, true);
        wp_enqueue_style('jquery-datetimepicker-style', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css');
        wp_enqueue_script('event-admin-js', get_template_directory_uri() . '/resources/js/event-datetime-picker.js', array('jquery', 'jquery-datetimepicker'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_event_datetime_picker_scripts');

// Save Meta Box Data
function save_event_datetimes($post_id)
{
    if (!isset($_POST['event_location_meta_box_nonce']) || !wp_verify_nonce($_POST['event_location_meta_box_nonce'], 'event_location_meta_box')) {
        return;
    }
    if (!isset($_POST['event_start_datetime_meta_box_nonce']) || !wp_verify_nonce($_POST['event_start_datetime_meta_box_nonce'], 'event_start_datetime_meta_box')) {
        return;
    }
    if (!isset($_POST['event_end_datetime_meta_box_nonce']) || !wp_verify_nonce($_POST['event_end_datetime_meta_box_nonce'], 'event_end_datetime_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['post_type']) && 'event' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    if (isset($_POST['event_location'])) {
        $location = sanitize_text_field($_POST['event_location']);
        update_post_meta($post_id, '_event_location', $location);
    }

    if (isset($_POST['event_start_datetime'])) {
        $start_datetime = sanitize_text_field($_POST['event_start_datetime']);
        update_post_meta($post_id, '_event_start_datetime', $start_datetime);
    }

    if (isset($_POST['event_end_datetime'])) {
        $end_datetime = sanitize_text_field($_POST['event_end_datetime']);
        update_post_meta($post_id, '_event_end_datetime', $end_datetime);
    }
}
add_action('save_post', 'save_event_datetimes');

// Add videoUrl field to the Post type video
function add_video_url_meta_box()
{
    add_meta_box(
        'video_url_meta_box',
        'Video URL',
        'show_video_url_meta_box',
        'post',
        'normal',
        'high'
    );
    add_meta_box(
        'video_url_meta_box',
        'Video URL',
        'show_video_url_meta_box',
        'video',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'add_video_url_meta_box');

function show_video_url_meta_box()
{
    global $post;
    $meta = get_post_meta($post->ID, 'video_url', true);
    echo '<input type="hidden" name="video_url_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '">';
    echo '<p><label for="video_url">Video URL:</label><br>';
    echo '<input type="text" name="video_url" id="video_url" class="meta-image regular-text" value="' . $meta . '">';
    echo '</p>';
}

function save_video_url_meta($post_id)
{
    if (!isset($_POST['video_url_meta_box_nonce']) || !wp_verify_nonce($_POST['video_url_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    if ('video' === $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $old = get_post_meta($post_id, 'video_url', true);
    $new = isset($_POST['video_url']) ? $_POST['video_url'] : '';
    $new = sanitize_text_field($new);

    // Update the meta field if the new value is different from the old one
    if (!empty($new) && $new !== $old) {
        update_post_meta($post_id, 'video_url', $new);
    } elseif (empty($new) && !empty($old)) {
        // Delete the meta field if the new value is empty and the old value is not
        delete_post_meta($post_id, 'video_url', $old);
    }
}

add_action('save_post', 'save_video_url_meta');


add_filter('register_post_type_args', 'add_menu_order_to_faq_rest_api', 10, 2);

function add_menu_order_to_faq_rest_api($args, $post_type)
{
    if ($post_type === 'faq') {
        $args['show_in_rest'] = true;
        $args['rest_base'] = 'faq';
        $args['rest_controller_class'] = 'WP_REST_Posts_Controller';
    }
    return $args;
}

add_filter('rest_faq_schema', 'add_menu_order_to_faq_schema');

function add_menu_order_to_faq_schema($schema)
{
    $schema['properties']['menu_order'] = array(
        'description' => 'The order of the FAQ post',
        'type'        => 'integer',
        'context'     => array('view', 'edit'),
    );
    return $schema;
}

add_filter('rest_prepare_faq', 'add_menu_order_to_faq_response', 10, 3);

function add_menu_order_to_faq_response($response, $post, $request)
{
    $response->data['menu_order'] = $post->menu_order;
    return $response;
}

add_filter('rest_faq_collection_params', 'add_faq_rest_orderby_params', 10, 1);

function add_faq_rest_orderby_params($params)
{
    $params['orderby']['enum'][] = 'menu_order';
    return $params;
}

// Add "Is Stylesheet" checkbox to media sidebar
add_filter('attachment_fields_to_edit', function ($form_fields, $post) {
    $is_stylesheet = get_post_meta($post->ID, 'is_stylesheet', true);
    $form_fields['is_stylesheet'] = [
        'label' => 'Is Stylesheet',
        'input' => 'html',
        'html' => '<input type="checkbox" name="attachments['.$post->ID.'][is_stylesheet]" value="1" '.checked($is_stylesheet, '1', false).' />',
        'value' => $is_stylesheet,
        'helps' => 'Check if this media item is a stylesheet.'
    ];
    return $form_fields;
}, 10, 2);

// Save "Is Stylesheet" field
add_filter('attachment_fields_to_save', function ($post, $attachment) {
    if (isset($attachment['is_stylesheet']) && $attachment['is_stylesheet'] === '1') {
        update_post_meta($post['ID'], 'is_stylesheet', '1');
    } else {
        delete_post_meta($post['ID'], 'is_stylesheet');
    }
    return $post;
}, 10, 2);

// Register isStylesheet field for MediaItem
add_action('graphql_register_types', function () {
    register_graphql_field('MediaItem', 'isStylesheet', [
        'type' => 'Boolean',
        'description' => 'Whether the media item is a stylesheet',
        'resolve' => function ($media_item) {
            return get_post_meta($media_item->ID, 'is_stylesheet', true) === '1';
        }
    ]);
});

// Register isStylesheet where argument
add_action('graphql_register_types', function () {
    register_graphql_field('RootQueryToMediaItemConnectionWhereArgs', 'isStylesheet', [
        'type' => 'Boolean',
        'description' => 'Filter media items by whether they are stylesheets'
    ]);
});

// Filter media items by isStylesheet
add_filter('graphql_connection_query_args', function ($query_args, $resolver) {
    $field_name = $resolver->get_info()->fieldName;

    // Only apply to the 'mediaItems' connection
    if ('mediaItems' !== $field_name) {
        return $query_args;
    }

    // Get the GraphQL input args
    $args = $resolver->get_args();

    // Initialize meta_query
    if (!isset($query_args['meta_query'])) {
        $query_args['meta_query'] = [];
    }

    // Ensure post_type and post_status are set
    $query_args['post_type'] = 'attachment';
    $query_args['post_status'] = ['inherit', 'publish'];

    // Apply meta_query based on isStylesheet
    if (isset($args['where']['isStylesheet'])) {
        if (true === $args['where']['isStylesheet']) {
            $query_args['meta_query'][] = [
                'key' => 'is_stylesheet',
                'value' => '1',
                'compare' => '=',
                'type' => 'CHAR' // Explicitly set type to avoid type mismatches
            ];
        } elseif (false === $args['where']['isStylesheet']) {
            $query_args['meta_query'][] = [
                'key' => 'is_stylesheet',
                'compare' => 'NOT EXISTS',
                'type' => 'CHAR'
            ];
        }
    }

    // Set relation if multiple meta_queries
    if (count($query_args['meta_query']) > 1) {
        $query_args['meta_query']['relation'] = 'AND';
    }

    return $query_args;
}, 999, 2);
