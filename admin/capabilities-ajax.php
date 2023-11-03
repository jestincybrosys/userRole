<?php
require_once('capabilities.php');



// Define the AJAX handler for loading capabilities
add_action('wp_ajax_load_capabilities', 'load_capabilities_callback');
add_action('wp_ajax_nopriv_load_capabilities', 'load_capabilities_callback'); // Allow non-logged-in users to use this action

function load_capabilities_callback() {
    // Get the selected role name from the AJAX request
    $selected_role = sanitize_text_field($_POST['role_name']);

    // Define capability groups and assign capabilities to groups

    $capability_groups = define_capability_groups();

    
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

            $output .= '<td class="group-name">';
            $output .= '<label for="' . esc_attr($capability) . '">' . esc_html($data['label']) . '</label>';
            $output .= '</td>';
            
            $output .= '<td class="group-name">';
            $checked = $data['isPresent'] ? 'checked' : '';
            $output .= '<input type="checkbox" id="' . esc_attr($capability) . '" name="capabilities[]" class="' . esc_attr($capability) . '" value="' . esc_attr($capability) . '" ' . $checked . '>';
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

// Add an event listener to all checkboxes
var checkboxes = document.querySelectorAll('input[type="checkbox"]');
checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        // Get the capability name from the clicked checkbox
        var capabilityName = this.value;

        // Find all checkboxes with the same capability name
        var relatedCheckboxes = document.querySelectorAll('input[type="checkbox"][value="' + capabilityName + '"]');

        // Update the state of related checkboxes
        relatedCheckboxes.forEach(function(relatedCheckbox) {
            relatedCheckbox.checked = this.checked;
        }.bind(this));
    });
});
</script>




<?php


    wp_die();
}


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

