<?php

// Add the dashboard widget
function enabled_features_dashboard_widget() {
    wp_add_dashboard_widget(
        'enabled_features_widget',
        'Enabled Features',
        'enabled_features_widget_display'
    );
}
add_action('wp_dashboard_setup', 'enabled_features_dashboard_widget');

// Display the widget content
function enabled_features_widget_display() {
    $features = array(
        'posts' => 'Posts',
        'galleries' => 'Galleries',
        'testimonials' => 'Testimonials',
        'forms' => 'Forms',
        'faqs' => 'FAQs',
        'events' => 'Events',
        'videos' => 'Videos',
        'portfolios' => 'Portfolios',
        'podcasts' => 'Podcasts (plugin needs to be enabled)',
        'private' => 'Private Content',
        'others' => 'Other Post Types',
        'backups' => 'Backups',
        'import' => 'Import',
        'redirects' => 'Redirects',
        'tools' => 'Advanced Tools',
        'settings' => 'Advanced Settings',
        'users' => 'User Management',
        'scf' => 'Custom Fields'
    );

    $enabled_features = get_option('enabled_features', array());

    // Save settings if form is submitted
    if (isset($_POST['save_features'])) {
        $new_features = isset($_POST['enabled_features']) ? $_POST['enabled_features'] : array();
        update_option('enabled_features', $new_features);
        echo '<div class="updated"><p>Settings saved.</p></div>';
        $enabled_features = $new_features; // Update the local variable with new values
    }

    echo '<form method="post" action="">';
    foreach ($features as $key => $label) {
        $checked = in_array($key, $enabled_features) ? 'checked' : '';
        echo "<div class='mb-1'><label><input type='checkbox' name='enabled_features[]' value='$key' $checked> $label</label></div>";
    }
    echo '<br><input type="submit" name="save_features" class="button button-primary" value="Save Changes">';
    echo '</form>';
}


