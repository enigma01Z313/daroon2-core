<?php

// Add Bookly Author meta box for posts
add_action('add_meta_boxes', function() {
    add_meta_box(
        'daroon2_bookly_author',
        __('Bookly Author', 'daroon2'),
        'daroon2_render_bookly_author_metabox',
        'post',
        'side',
        'default'
    );
});

function daroon2_render_bookly_author_metabox($post) {
    // Ensure the Bookly staff fetch function is available
    if (!function_exists('daroon2_get_bookly_staff')) {
        require_once __DIR__ . '/booklyIntegration/get_bookly_staff.php';
    }
    $bookly_staff_list = daroon2_get_bookly_staff();
    $selected = get_post_meta($post->ID, 'bookly_author_id', true);
    wp_nonce_field('daroon2_bookly_author_save', 'daroon2_bookly_author_nonce');
    echo '<label for="bookly_author_id">' . __('Select Author (Bookly Staff)', 'daroon2') . '</label>';
    echo '<select name="bookly_author_id" id="bookly_author_id" style="width:100%">';
    echo '<option value="">' . __('None', 'daroon2') . '</option>';
    foreach ($bookly_staff_list as $staff) {
        $is_selected = $selected == $staff['id'] ? 'selected' : '';
        echo '<option value="' . esc_attr($staff['id']) . '" ' . $is_selected . '>' . esc_html($staff['full_name']) . ' (' . esc_html($staff['email']) . ')</option>';
    }
    echo '</select>';
}

add_action('save_post', function($post_id) {
    if (!isset($_POST['daroon2_bookly_author_nonce']) || !wp_verify_nonce($_POST['daroon2_bookly_author_nonce'], 'daroon2_bookly_author_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    $author_id = isset($_POST['bookly_author_id']) ? sanitize_text_field($_POST['bookly_author_id']) : '';
    update_post_meta($post_id, 'bookly_author_id', $author_id);
});

