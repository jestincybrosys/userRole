<?php



// Define the AJAX handler for loading capabilities
add_action('wp_ajax_load_capabilities', 'load_capabilities_callback');
add_action('wp_ajax_nopriv_load_capabilities', 'load_capabilities_callback'); // Allow non-logged-in users to use this action

function load_capabilities_callback() {
    // Get the selected role name from the AJAX request
    $selected_role = sanitize_text_field($_POST['role_name']);

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

    // Create an array to store all capabilities not listed in the 'Custom' array
$custom_capabilities = array();



// Add the missing capabilities to the 'Custom' group
$capability_groups['Custom'] = array_merge($capability_groups['Custom'], array_diff($custom_capabilities, $capability_groups['Custom']));


    // Retrieve the capabilities associated with the selected role
    $role = get_role($selected_role);
    $capabilities = $role->capabilities;

    // Group capabilities based on defined groups
    $grouped_capabilities = array();
    foreach ($capability_groups as $group_name => $group_capabilities) {
        $grouped_capabilities[$group_name] = array();

        foreach ($group_capabilities as $capability) {
            if (array_key_exists($capability, $capabilities)) {
                $grouped_capabilities[$group_name][$capability] = $capabilities[$capability];
            }
        }
    }

    // Display the grouped capabilities as HTML
    $output = '';
    foreach ($grouped_capabilities as $group_name => $group) {
        $output .= '<h4>' . esc_html($group_name) . '</h4>';
        $output .= '<ul>';

        foreach ($group as $capability => $value) {
            $output .= '<li><label><input type="checkbox" name="capabilities[]" value="' . esc_attr($capability) . '"> ' . esc_html($capability) . '</label></li>';
        }

        $output .= '</ul>';
        $output .= '<hr>';
    }

    echo $output;

    // Don't forget to exit to avoid extra output
    wp_die();
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

        // Remove unselected capabilities
        $all_capabilities = $role->capabilities;

        foreach ($all_capabilities as $capability => $value) {
            if (!in_array($capability, $selected_capabilities)) {
                $role->remove_cap($capability);
            }
        }

        // Respond with a success message if needed
        echo 'Role capabilities updated successfully.';
    }

    wp_die(); // Always use wp_die() at the end of AJAX callbacks.
}
