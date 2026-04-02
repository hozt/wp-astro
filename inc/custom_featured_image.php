<?php
function add_additional_featured_meta_boxes() {
    add_meta_box(
        'additional_featured_image',
        __('Additional Image', 'text_domain'),
        'additional_featured_image_callback',
        'portfolio',
        'side'
    );
}
add_action('add_meta_boxes', 'add_additional_featured_meta_boxes');

function additional_featured_image_callback($post) {
    $additional_image = get_post_meta($post->ID, 'additional_image', true);
    ?>
    <p>
        <input type="hidden" id="additional_image" name="additional_image" value="<?php echo esc_attr($additional_image); ?>" />
        <img id="additional_image_preview" src="<?php echo esc_url($additional_image); ?>" style="max-width:100%; <?php echo empty($additional_image) ? 'display:none;' : ''; ?>" />
        <button type="button" class="button" id="additional_image_button"><?php _e('Select Image', 'text_domain'); ?></button>
        <button type="button" class="button" id="remove_additional_image_button" style="<?php echo empty($additional_image) ? 'display:none;' : ''; ?>"><?php _e('Remove Image', 'text_domain'); ?></button>
    </p>
    <?php
}

function save_portfolio_meta_boxes($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['additional_image'])) {
        update_post_meta($post_id, 'additional_image', esc_url_raw($_POST['additional_image']));
    }
}
add_action('save_post', 'save_portfolio_meta_boxes');

function enqueue_custom_media_uploader() {
    wp_enqueue_media();
    wp_enqueue_script('custom-media-uploader.js', get_template_directory_uri() . '/resources/js/custom-media-uploader.js', ['jquery'], null, true);
}

add_action('admin_enqueue_scripts', 'enqueue_custom_media_uploader');

