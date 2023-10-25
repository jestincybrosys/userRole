<?php
// Enqueue styles and scripts
function enqueue_ediuser_role_editor_styles() {
    $current_page = isset($_GET['page']) ? $_GET['page'] : '';

    // Check if we are on the main plugin page or any of its submenus
    if (
        $current_page === 'ediuser-role-editor' ||
        $current_page === 'edit-roles' ||
        $current_page === 'existing-role-capabilities'
    ) {
        // Get the plugin directory path
        $plugin_dir = plugin_dir_path(__FILE__);

        // Enqueue styles
        wp_enqueue_style('ediuser-role-editor-styles', plugins_url('ediuser-role-editor.css', __FILE__));

        // Enqueue scripts with dependencies on jQuery
        wp_enqueue_script('ediuser-role-editor', plugins_url('ediuser-role-editor.js', __FILE__), array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_ediuser_role_editor_styles');
