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

// Retrieve the capabilities associated with the selected role
$role = get_role($selected_role);
$capabilities = $role->capabilities;
$all_capabilities = get_role('administrator')->capabilities;

// Group capabilities based on defined groups
$grouped_capabilities = array();
foreach ($capability_groups as $group_name => $group_capabilities) {
    $grouped_capabilities[$group_name] = array();

    foreach ($group_capabilities as $capability) {
        $grouped_capabilities[$group_name][$capability] = in_array($capability, array_keys($capabilities));
    }
}

// Retrieve the capabilities associated with the selected role
$role = get_role($selected_role);
$capabilities = $role->capabilities;
$all_capabilities = get_role('administrator')->capabilities;

// Group capabilities based on defined groups
$grouped_capabilities = array();
foreach ($capability_groups as $group_name => $group_capabilities) {
    $grouped_capabilities[$group_name] = array();

    foreach ($group_capabilities as $capability) {
        // Remove underscores from capability names
        $capability = str_replace('_', ' ', $capability);
        $grouped_capabilities[$group_name][$capability] = in_array($capability, array_keys($capabilities));
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
$output .= '<thead><tr><th>Capabilities</th><th>Grand</th></tr></thead>';
$output .= '<tbody>';

foreach ($grouped_capabilities as $group_name => $group) {
    foreach ($group as $capability => $isPresent) {
    $output .= '<tr class="group-capabilities" data-group="' . esc_attr($group_name) . '" style="display:none;">';

    $output .= '<td class="group-name">';
    
    $output .='' . esc_html($capability) .'' ;
    $output .= '</td>';
    $output .= '<td class="group-name">';
   
        $checked = $isPresent ? 'checked' : '';
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

    // Toggle group capabilities when clicking on group name
    $(".toggle-group").on("click", function() {
        var group = $(this).data("group");
        $(".group-capabilities").hide();
        $(".group-capabilities[data-group='" + group + "']").show();
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

