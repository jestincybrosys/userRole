<?php

function edit_roles_page() {
    $roles = get_editable_roles();
    $selected_role = "administrator"; // Default role
    $current_role = get_role($selected_role);

    if (isset($_POST['role_name'])) {
        $selected_role = sanitize_text_field($_POST['role_name']);
        $current_role = get_role($selected_role);
    } elseif (isset($_GET['role_name'])) {
        $selected_role = sanitize_text_field($_GET['role_name']);
        $current_role = get_role($selected_role);
    }

    $all_capabilities = get_role('administrator')->capabilities; // Define $all_capabilities here

    if (isset($_POST['update_role_capabilities'])) {
        $role_name = sanitize_text_field($_POST['role_name']);
        $selected_capabilities = isset($_POST['capabilities']) ? $_POST['capabilities'] : array();

        // Update the role's capabilities
        $role = get_role($role_name);
        $role->capabilities = array();

        foreach ($selected_capabilities as $capability) {
            $role->add_cap($capability);
        }

        echo '<div class="updated"><p>Role capabilities updated successfully.</p></div>';
    }

    $wp_roles = wp_roles();

    ?>
    <div class="flexdiv">
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
                <!-- The capabilities for the selected user role will be loaded here via AJAX -->
            </div>
            <br>

            <input type="submit" name="update_role_capabilities" class="button button-primary" value="Update Role Capabilities">
        </form>
    </div>

    <div class="wrap">
    <h2>Capabilities by Category</h2>
    <table class="widefat">
        <thead>
            <tr>
                <th>Category</th>
                <th>Capabilities</th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wp_roles;
            $capabilities_by_category = array();

            foreach ($wp_roles->role_objects as $role_name => $role) {
                $capabilities = $role->capabilities;
                foreach ($capabilities as $capability => $value) {
                    $category_found = false;
                    // Check if this capability belongs to an existing category
                    foreach ($capabilities_by_category as $category => $capabilityList) {
                        if (in_array($capability, $capabilityList)) {
                            $capabilities_by_category[$category][] = $capability;
                            $category_found = true;
                            break;
                        }
                    }
                    // If not found in any category, create a new category with the capability
                    if (!$category_found) {
                        $capabilities_by_category[$capability] = [$capability];
                    }
                }
            }

            // Output the capabilities by category
            foreach ($capabilities_by_category as $category => $capabilityList) {
                echo '<tr>';
                echo '<td>' . $category . '</td>';
                echo '<td>';
                echo '<div class="capabilities-list">';
                foreach ($capabilityList as $capability) {
                    echo '<span>' . $capability . ' </span>';
                }
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

    </div>

    <?php
    // ...
}