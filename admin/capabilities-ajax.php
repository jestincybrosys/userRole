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
            'edit_dashboard' => 'Edit Dashboard',
            'edit_files' => 'Edit Files',
            'export' => 'Export',
            'import' => 'Import',
            'manage_links' => 'Manage Links',
            'manage_options' => 'Manage Options',
            'moderate_comments' => 'Moderate Comments',
            'read' => 'Read',
            'unfiltered_html' => 'Unfiltered HTML',
            'update_core' => 'Update Core',
        ),
        'Posts' => array(
            'delete_others_posts' => "Delete Others' Posts",
            'delete_posts' => 'Delete Posts',
            'delete_private_posts' => 'Delete Private Posts',
            'delete_published_posts' => 'Delete Published Posts',
            'edit_others_posts' => "Edit Others' Posts",
            'edit_posts' => 'Edit Posts',
            'edit_private_posts' => 'Edit Private Posts',
            'edit_published_posts' => 'Edit Published Posts',
            'publish_posts' => 'Publish Posts',
            'read_private_posts' => 'Read Private Posts',
        ),
        'Pages' => array(
            'delete_others_pages' => "Delete Others' Pages",
            'delete_pages' => 'Delete Pages',
            'delete_private_pages' => 'Delete Private Pages',
            'delete_published_pages' => 'Delete Published Pages',
            'edit_others_pages' => "Edit Others' Pages",
            'edit_pages' => 'Edit Pages',
            'edit_private_pages' => 'Edit Private Pages',
            'edit_published_pages' => 'Edit Published Pages',
            'publish_pages' => 'Publish Pages',
            'read_private_pages' => 'Read Private Pages',
        ),
        'Attachments' => array(
            'upload_files' => 'Upload Files',
        ),
        'Taxonomies' => array(
            'manage_categories' => 'Manage Categories',
        ),
        'Themes' => array(
            'delete_themes' => 'Delete Themes',
            'edit_theme_options' => 'Edit Theme Options',
            'edit_themes' => 'Edit Themes',
            'install_themes' => 'Install Themes',
            'switch_themes' => 'Switch Themes',
            'update_themes' => 'Update Themes',
        ),
        'Plugins' => array(
            'activate_plugins' => 'Activate Plugins',
            'delete_plugins' => 'Delete Plugins',
            'edit_plugins' => 'Edit Plugins',
            'install_plugins' => 'Install Plugins',
            'update_plugins' => 'Update Plugins',
        ),
        'Users' => array(
            'create_roles' => 'Create Roles',
            'create_users' => 'Create Users',
            'delete_roles' => 'Delete Roles',
            'delete_users' => 'Delete Users',
            'edit_roles' => 'Edit Roles',
            'edit_users' => 'Edit Users',
            'list_roles' => 'List Roles',
            'list_users' => 'List Users',
            'promote_users' => 'Promote Users',
            'remove_users' => 'Remove Users',
        ),
        'Custom' => array(
            'restrict_content' => 'Restrict Content',
            // Add your custom capabilities here...
        ),
        // Add more groups as needed...
    );
    

    
    $role = get_role($selected_role);
    $capabilities = $role->capabilities;
    $all_capabilities = get_role('administrator')->capabilities;

    // Group capabilities based on defined groups
    $grouped_capabilities = array();
    foreach ($capability_groups as $group_name => $group_capabilities) {
        $grouped_capabilities[$group_name] = array();

        foreach ($group_capabilities as $capability => $label) {
            $grouped_capabilities[$group_name][$capability] = array(
                'label' => $label,
                'isPresent' => in_array($capability, array_keys($capabilities)),
            );
        }
    }

    // Display the grouped capabilities with a single main div
    $output = '<div class="capability-group-main">';

    // Display the group list on one side
    $output .= '<h3>Capability Groups</h3>';
    $output .='<div class="group-li">';
    $output .= '<div class="group-list">';

    $output .= '<ul class="group-list-ul">';
    foreach (array_keys($grouped_capabilities) as $group_name) {
        $output .= '<li><a href="javascript:void(0);" class="toggle-group" data-group="' . esc_attr($group_name) . '">' . esc_html($group_name) . '</a></li>';
    }
    $output .= '</ul>';
    $output .= '</div>';

    // Display the selected group's capabilities on the other side
    $output .= '<div class="selected-group-capabilities">';
    $output .= '<table class="widefat striped tbl">';
    $output .= '<thead><tr><th>Capabilities</th><th>Grant</th></tr></thead>';
    $output .= '<tbody>';

    foreach ($grouped_capabilities as $group_name => $group) {
        foreach ($group as $capability => $data) {
            $output .= '<tr class="group-capabilities" data-group="' . esc_attr($group_name) . '" style="display:none;">';

            // $output .= '<td class="group-name">';
            // $output .= esc_html($capability);
            // $output .= '</td>';
            
            $output .= '<td class="group-name">';
            $output .= esc_html($data['label']);
            $output .= '</td>';

            $output .= '<td class="group-name">';
            $checked = $data['isPresent'] ? 'checked' : '';
            $output .= '<input type="checkbox" name="capabilities[]" value="' . esc_attr($capability) . '" ' . $checked . '>';
            $output .= '</td>';
        }
        $output .= '</tr>';
    }

    $output .= '</tbody>';
    $output .= '</table>';
    $output .='</div>';

    $output .= '</div>';
    $output .= '</div>';

    echo $output;
?>

<script>
jQuery(document).ready(function($) {
    // Default to showing the "General Group" capabilities when the page loads
    var defaultGroup = "General";
    $(".group-capabilities[data-group='" + defaultGroup + "']").show();
    $(".group-list-ul li a[data-group='" + defaultGroup + "']").parent().addClass("active-group"); // Mark the default group as active

    // Toggle group capabilities when clicking on group name
    $(".toggle-group").on("click", function() {
        var group = $(this).data("group");
        $(".group-capabilities").hide();
        $(".group-capabilities[data-group='" + group + "']").show();
        $(".group-list-ul li").removeClass("active-group");
        $(this).parent().addClass("active-group"); // Mark the clicked group as active
    });
});

</script>

<?php


    wp_die();
}


// add_action('wp_ajax_update_role_capabilities', 'update_role_capabilities_callback');

// function update_role_capabilities_callback() {
//     if (isset($_POST['role_name']) && isset($_POST['capabilities'])) {
//         $role_name = sanitize_text_field($_POST['role_name']);
//         $selected_capabilities = $_POST['capabilities'];

//         // Update the role's capabilities
//         $role = get_role($role_name);
//         $role->capabilities = array();

//         foreach ($selected_capabilities as $capability) {
//             $role->add_cap($capability);
//         }
 
//         // Remove unselected capabilities
//         $all_capabilities = $role->capabilities;

//         foreach ($all_capabilities as $capability => $value) {
//             if (!in_array($capability, $selected_capabilities)) {
//                 $role->remove_cap($capability);
//             }
//         }

//         // Respond with a success message if needed
//         echo 'Role capabilities updated successfully.';
//     }

//     wp_die(); // Always use wp_die() at the end of AJAX callbacks.
// }



// Define the AJAX action for individual role deletion
add_action('wp_ajax_delete_role', 'delete_role_callback');
add_action('wp_ajax_nopriv_delete_role', 'delete_role_callback'); // Allow non-logged-in users to use this action

function delete_role_callback() {
    $role_name = sanitize_text_field($_POST['role_name']);

    if ($role_name === 'administrator') {
        echo 'error';
        exit;
    }

    if (remove_role($role_name)) {
        echo 'success';
        exit;
    } else {
        echo 'error';
        exit;
    }
}

// Define the AJAX action for bulk role deletion
add_action('wp_ajax_bulk_delete_roles', 'bulk_delete_roles_callback');
add_action('wp_ajax_nopriv_bulk_delete_roles', 'bulk_delete_roles_callback'); // Allow non-logged-in users to use this action

function bulk_delete_roles_callback() {
    $roles = $_POST['selected-roles'];
    $success_count = 0;
    $error_count = 0;

    foreach ($roles as $role_name) {
        if ($role_name !== 'administrator' && remove_role($role_name)) {
            $success_count++;
            echo '<div class="updated"><p>'. $role_name .' deleted successfully.</p></div>';

        } else {
            $error_count++;
        }
    }

   
}



