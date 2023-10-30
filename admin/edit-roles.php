<?php
function ediuser_edit_roles_page() {
    $roles = get_editable_roles();
    $selected_role = "administrator"; // Default role
    $current_role = get_role($selected_role);
    $selected_capabilities = array(); // Define it here

    if (isset($_POST['role_name'])) {
        $selected_role = sanitize_text_field($_POST['role_name']);
        $current_role = get_role($selected_role);
    } elseif (isset($_GET['role_name'])) {
        $selected_role = sanitize_text_field($_GET['role_name']);
        $current_role = get_role($selected_role);
    }

    if (isset($_POST['update_role_capabilities'])) {
        $role_name = sanitize_text_field($_POST['role_name']);
        $selected_capabilities = isset($_POST['capabilities']) ? $_POST['capabilities'] : array();

        // Update the role's capabilities
        $role = get_role($role_name);
        $role->capabilities = array();

        foreach ($selected_capabilities as $capability) {
            $role->add_cap($capability);
        }

        // Remove unselected capabilities
        $all_capabilities = $role->capabilities;

        foreach ($all_capabilities as $capability => $value) {
            if (!in_array($capability, $selected_capabilities)) {
                $role->remove_cap($capability);
            }
        }

        echo '<div class="updated"><p>Role capabilities updated successfully.</p></div>';
    }

    $wp_roles = wp_roles();

    // Define capability groups and assign capabilities to groups
    $capability_groups = array(
        'General' => array(
            'edit_dashboard',
            'edit_files',
            'export',
            'import',
            'manage_links',
            'manage_options',
            'moderate_comments',
            'read',
            'unfiltered_html',
            'update_core',
        ),
        'Posts' => array(
            'delete_others_posts',
            'delete_posts',
            'delete_private_posts',
            'delete_published_posts',
            'edit_others_posts',
            'edit_posts',
            'edit_private_posts',
            'edit_published_posts',
            'publish_posts',
            'read_private_posts',
        ),
        'Pages' => array(
            'delete_others_pages',
            'delete_pages',
            'delete_private_pages',
            'delete_published_pages',
            'edit_others_pages',
            'edit_pages',
            'edit_private_pages',
            'edit_published_pages',
            'publish_pages',
            'read_private_pages',
        ),
        'Attachments' => array(
            'upload_files',
        ),
        'Taxonomies' => array(
            'manage_categories',
        ),
        'Themes' => array(
            'delete_themes',
            'edit_theme_options',
            'edit_themes',
            'install_themes',
            'switch_themes',
            'update_themes',
        ),
        'Plugins' => array(
            'activate_plugins',
            'delete_plugins',
            'edit_plugins',
            'install_plugins',
            'update_plugins',
        ),
        'Users' => array(
            'create_roles',
            'create_users',
            'delete_roles',
            'delete_users',
            'edit_roles',
            'edit_users',
            'list_roles',
            'list_users',
            'promote_users',
            'remove_users',
        ),
        'Custom' => array(
            'restrict_content',
            // Add your custom capabilities here...
        ),
        // Add more groups as needed...
    );

    ?>
    <div class="wrap">
    <h2>Edit User Role Capabilities</h2>
<form method="post" action="">
    <label for="role_name">Select Role to Edit:</label>
    <select id="role_name" name="role_name">
        <?php
        foreach ($roles as $role_name => $role_info) {
            $selected = ($role_name === $selected_role) ? 'selected' : '';
            echo '<option value="' . esc_attr($role_name) . '" ' . $selected . '>' . esc_html($role_info['name']) . '</option>';
        }
        ?>
    </select>

    <h3>Current Role Capabilities</h3>
    <div id="current-capabilities">
        <!-- Capabilities will be loaded here via JavaScript -->
    </div>
    <br>

    <input type="submit" name="update_role_capabilities" class="button button-primary" value="Update Role Capabilities">
</form>


    </div>
    <?php
}
?>
