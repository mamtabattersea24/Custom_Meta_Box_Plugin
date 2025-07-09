<?php

    /**
    *Plugin Name: Custom Meta Box
    *Description: Adds a custom meta box to posts.
    *Version: 1.0.0
    *Author: Mamta Paswan
    */


function my_add_custom_meta_box() {
    add_meta_box(
        'my_custom_metabox',         // Unique ID
        'Mamta Custom Meta Box',        // Box title
        'my_custom_metabox_html',    // Content callback
        'post',                      // Post type (e.g., 'post', 'page', or custom type)
        'advanced',                    // Context ('normal', 'side', 'advanced')
        'high'                       // Priority
    );
}

add_action('add_meta_boxes', 'my_add_custom_meta_box');


function my_custom_metabox_html($post) {
    // Add a nonce field for security
    wp_nonce_field('my_custom_metabox_nonce_action', 'my_custom_metabox_nonce');

    // Retrieve existing value from the database
    $value = get_post_meta($post->ID, '_my_custom_field', true);

    // Display the form field
    echo '<label for="my_custom_field">Custom Field:</label> ';
    echo '<input type="text" id="my_custom_field" name="my_custom_field" value="' . esc_attr($value) . '" />';
}


function my_save_custom_metabox($post_id) {
    // Check for nonce and autosave
    if (!isset($_POST['my_custom_metabox_nonce'])) return;
    if (!wp_verify_nonce($_POST['my_custom_metabox_nonce'], 'my_custom_metabox_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // Save the field value
    if (isset($_POST['my_custom_field'])) {
        update_post_meta($post_id, '_my_custom_field', sanitize_text_field($_POST['my_custom_field']));
    }
}
add_action('save_post', 'my_save_custom_metabox');



?>