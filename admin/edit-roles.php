<?php
function ediuser_edit_roles_page() {
    $roles = get_editable_roles();
    $selected_role = "administrator"; // Default role
    $current_role = get_role($selected_role);
    $selected_capabilities = array(); // Define it here

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
            // Add more capabilities...
        ),
        'Posts' => array(
            'delete_others_posts',
            'delete_posts',
            'edit_others_posts',
            'edit_posts',
            // Add more capabilities...
        ),
        // Add more groups...
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
                <?php
                // Display capabilities grouped by category
                foreach ($capability_groups as $group_name => $group_capabilities) {
                    echo '<h4>' . esc_html($group_name) . '</h4>';
                    echo '<ul>';

                    foreach ($group_capabilities as $capability) {
                        $checked = in_array($capability, $selected_capabilities) ? 'checked' : '';
                        echo '<li><label><input type="checkbox" name="capabilities[]" value="' . esc_attr($capability) . '" ' . $checked . '> ' . esc_html($capability) . '</label></li>';
                    }

                    echo '</ul>';
                }
                ?>
            </div>
            <br>

            <input type="submit" name="update_role_capabilities" class="button button-primary" value="Update Role Capabilities">
        </form>
    </div>

    <?php
}
?>