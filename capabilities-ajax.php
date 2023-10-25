<?php

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
