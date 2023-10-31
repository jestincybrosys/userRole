<?php
function enqueue_ediuser_role_editor_styles() {
    $current_page = isset($_GET['page']) ? $_GET['page'] : '';

    // Check if we are on the main plugin page or any of its submenus
    if (
        $current_page === 'ediuser-role-editor' ||
        $current_page === 'edit-roles' ||
        $current_page === 'existing-role-capabilities' ||
        $current_page === 'user-roles-capabilities' ||
        $current_page === 'ediuser-role-new'
    ) {
        // Get the plugin directory path
        $plugin_dir = plugin_dir_path(__FILE__);

        // Enqueue styles from the "style" folder
        wp_enqueue_style('ediuser-role-editor-styles', plugins_url('style/ediuser-role-editor.css', __FILE__));

        // Enqueue scripts from the "js" folder with dependencies on jQuery
        wp_enqueue_script('ediuser-role-editor', plugins_url('js/ediuser-role-editor.js', __FILE__), array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_ediuser_role_editor_styles');


function generateBulkActions($formId) {
    ?>
<div class="alignleft actions pdtp-5">
        <select name="bulk-action" id="<?php echo $formId; ?>-bulk-action">
            <option value="">Bulk Actions</option>
            <option value="delete">Delete</option>
        </select>
        <input type="submit" class="button action" value="Apply" id="<?php echo $formId; ?>-apply-button">
    </div>
    <?php
}
