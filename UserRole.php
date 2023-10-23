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
        'ediuser-role-editor',    
        'Edit Roles',            
        'Edit Roles',            
        'manage_options',        
        'edit-roles',            
        'edit_roles_page'       
    );

    add_submenu_page(
        'ediuser-role-editor',
        'Existing Role Capabilities',
        'Role Capabilities',
        'manage_options',
        'existing-role-capabilities',
        'existing_role_capabilities_page'
    );
        add_submenu_page(
            'ediuser-role-editor',// Parent menu
            'User Roles & Capabilities', // Page title
            'User Roles & Capabilities', // Menu title
            'manage_options', // Capability required to access
            'user-roles-capabilities-submenu', // Menu slug
            'display_user_roles_table' // Callback function to display the content
        );
 
    
}
add_action('admin_menu', 'ediuser_role_editor_menu');

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





// Define the AJAX action
add_action('wp_ajax_load_capabilities', 'load_capabilities_callback');
add_action('wp_ajax_nopriv_load_capabilities', 'load_capabilities_callback');

function load_capabilities_callback() {
    if (isset($_POST['role_name'])) {
        $role_name = sanitize_text_field($_POST['role_name']);
        $role = get_role($role_name);
        $all_capabilities = get_role('administrator')->capabilities; // Use admin capabilities as a reference.

        ob_start();

        foreach ($all_capabilities as $cap => $value) {
            $checked = (isset($role->capabilities[$cap])) ? 'checked' : ''; // Check if the capability is present in the user role
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


add_action('wp_ajax_update_role_capabilities', 'update_role_capabilities_callback');

function update_role_capabilities_callback() {
    if (isset($_POST['role_name']) && isset($_POST['capabilities'])) {
        $role_name = sanitize_text_field($_POST['role_name']);
        $selected_capabilities = $_POST['capabilities'];

        // Update the role's capabilities
        $role = get_role($role_name);
        $role->capabilities = array();

        foreach ($selected_capabilities as $capability) {
            $role->add_cap($capability);
        }

        // Respond with a success message if needed
        echo 'Role capabilities updated successfully.';
    }

    wp_die(); // Always use wp_die() at the end of AJAX callbacks.
}

 


function existing_role_capabilities_page() {

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
                    echo '<td>';
                    $capabilityKeys = array_keys($capabilities);
                    echo '<div class="capabilities-list">';
                    for ($i = 0; $i < count($capabilityKeys); $i++) {
                        if ($i >= 10) {
                            echo '<span class="hidden-capability">' . $capabilityKeys[$i] . '</span> ';
                        } else {
                            echo '<span>' . $capabilityKeys[$i] . '</span> ';
                        }
                    }
                    echo '</div>';
                    // Add "Load More" link if there are more than 10 capabilities
                    if (count($capabilityKeys) > 10) {
                        echo '<a class="load-more-link" href="#">Load More</a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
function display_user_roles_table() {
    $wp_roles = wp_roles();
    $selected_columns = array('role', 'users', 'capabilities');
    
    if (isset($_POST['columns-toggle'])) {
        $selected_columns = $_POST['columns-toggle'];
    }
    
    ?>
    <div class="wrap">
        <h2>User Roles and Capabilities</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="post">
                    <label for="columns-toggle">Show on screen</label>
                    <select name="columns-toggle[]" id="columns-toggle" multiple>
                        <option value="role" <?php if (in_array('role', $selected_columns)) echo 'selected'; ?>>Role</option>
                        <option value="users" <?php if (in_array('users', $selected_columns)) echo 'selected'; ?>>Users Assigned</option>
                        <option value="capabilities" <?php if (in_array('capabilities', $selected_columns)) echo 'selected'; ?>>Total Capabilities Count</option>
                    </select>
                    <input type="submit" class="button" value="Apply">
                </form>
            </div>
        </div>
        <table class="widefat">
            <thead>
                <tr>
                    <th class="role">Role</th>
                    <th class="users">Users Assigned</th>
                    <th class="capabilities">Total Capabilities Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($wp_roles->role_objects as $role_name => $role) {
                    $capabilities = $role->capabilities;
                    $user_count = count(get_users(['role' => $role_name]));
                    $total_capabilities_count = count($capabilities);

                    echo '<tr>';
                    if (in_array('role', $selected_columns)) {
                        echo '<td class="role">' . $role_name . '</td>';
                    }
                    if (in_array('users', $selected_columns)) {
                        echo '<td class="users">' . $user_count . '</td>';
                    }
                    if (in_array('capabilities', $selected_columns)) {
                        echo '<td class="capabilities">' . $total_capabilities_count . '</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
