<?php
/*
Plugin Name: EdiUser: edit user role
Description: A plugin for editing user roles.
Version: 1.0
Author: jestin joseph
*/

// Add a menu item for the ediuser role editor
function ediuser_role_editor_menu() {
    add_menu_page(
        'Ediuser Role Editor',
        'Ediuser: User Roles',
        'manage_options',
        'ediuser-role-editor',
        'ediuser_role_editor_page'
    );

    // Add a sub-menu item to display existing roles
    add_submenu_page(
        'ediuser-role-editor',    // Parent menu slug
        'Edit Roles',            // Page title
        'Edit Roles',            // Menu title
        'manage_options',        // Capability
        'edit-roles',            // Menu slug
        'edit_roles_page'        // Callback function to edit roles
    );

    add_submenu_page(
        'ediuser-role-editor',
        'Existing Role Capabilities',
        'Role Capabilities',
        'manage_options',
        'existing-role-capabilities',
        'existing_role_capabilities_page'
    );
}
add_action('admin_menu', 'ediuser_role_editor_menu');
function enqueue_ediuser_role_editor_styles() {
    // Check if we are on the plugin page
    $current_screen = get_current_screen();

    if ($current_screen && $current_screen->id === 'toplevel_page_ediuser-role-editor') {
        // We are on the plugin page, enqueue the styles
        wp_enqueue_style('ediuser-role-editor-styles', plugins_url('ediuser-role-editor.css', __FILE__));
        wp_enqueue_script('ediuser-role-editor', plugins_url('ediuser-role-editor.js', __FILE__), array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_ediuser_role_editor_styles');


function ediuser_role_editor_page() {
    if (isset($_POST['create_ediuser_role'])) {
        $role_name = sanitize_text_field($_POST['role_name']);
        $selected_capabilities = isset($_POST['capabilities']) ? $_POST['capabilities'] : array();
        
        // Get the full list of capabilities
        $all_capabilities = get_role('administrator')->capabilities; // Use admin capabilities as a reference.
        
        // Initialize an empty array for the role's capabilities
        $role_capabilities = array();
        
        // Filter selected capabilities to ensure they are valid and add them to the role's capabilities array
        foreach ($selected_capabilities as $capability) {
            if (array_key_exists($capability, $all_capabilities)) {
                $role_capabilities[$capability] = true;
            }
        }

        // Create the ediuser role.
        add_role($role_name, $role_name, $role_capabilities);
        echo '<div class="updated"><p>ediuser role created successfully.</p></div>';
    }
    ?>
    <div class="wrap">
        <h2>Create Custom User Role</h2>
        <form method="post" action="">
            <label for="role_name">Role Name:</label>
            <input type="text" id="role_name" name="role_name" required>
            
            <label style="margin-bottom: 20px;">Capabilities:</label>
            <?php
            $all_capabilities = get_role('administrator')->capabilities; // Use admin capabilities as a reference.
            foreach ($all_capabilities as $cap => $value) {
                ?>
                <label style="width: 200px;">
                    <input type="checkbox" name="capabilities[]" value="<?php echo $cap; ?>">
                    <?php echo $cap; ?>
                </label><br>
                <?php
            }
            ?>
            
            <input type="submit" name="create_ediuser_role" class="button button-primary" value="Create Role">
        </form>
    </div>
    <?php
}

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
        }x
        ?>
    </select>

    <h3>Current Role Capabilities</h3>
<div id="current-capabilities">
    <!-- The capabilities for the selected user role will be loaded here via AJAX -->
</div>
 

        <h3>All Capabilities</h3>
        <div id="all-capabilities">
            <!-- Display all capabilities here -->
        </div>

        <input type="submit" name="update_role_capabilities" class="button button-primary" value="Update Role Capabilities">
    </form>
</div>

    <?php
}



// Define the AJAX action
add_action('wp_ajax_load_capabilities', 'load_capabilities_callback');
add_action('wp_ajax_nopriv_load_capabilities', 'load_capabilities_callback');

function load_capabilities_callback() {
    if (isset($_POST['role_name'])) {
        $role_name = sanitize_text_field($_POST['role_name']);
        $role = get_role($role_name);

        ob_start();

        foreach ($all_capabilities as $cap => $value) {
            $checked = (isset($role->capabilities[$cap])) ? 'checked' : '';
            ?>
            <label style="width: 200px;">
                <input type="checkbox" name="capabilities[]" value="<?php echo $cap; ?>" <?php echo $checked; ?>>
                <?php echo $cap; ?>
            </label><br>
            <?php
        }

        $response = ob_get_clean();
        echo $response;
    }

    wp_die(); // Always use wp_die() at the end of AJAX callbacks.
}

// Callback function to display existing roles
function existing_roles_page() {
    $wp_roles = wp_roles();
    ?> 
    <div class="wrap">
        <h2>Existing User Roles and Capabilities</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Capabilities</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($wp_roles->role_objects as $role_name => $role) {
                    $capabilities = $role->capabilities;
                    echo '<tr>';
                    echo '<td>' . $role_name . '</td>';
                    echo '<td>' . implode(', ', array_keys($capabilities)) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

function existing_role_capabilities_page() {
    global $wp_roles;
    ?>
    <div class="wrap">
        <h2>Existing User Role Capabilities</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th style=" width: 200px;">Role Name</th>
                    <th>Capabilities</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($wp_roles->role_objects as $role_name => $role) {
                    $capabilities = $role->capabilities;
                    echo '<tr>';
                    echo '<td>' . $role_name . '</td>';
                    echo '<td>' . implode(', ', array_keys($capabilities)) . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
