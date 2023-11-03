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
        
        // Create an array to track capabilities to be removed
        $capabilities_to_remove = array();
    print_r($selected_capabilities);
        // Identify capabilities to be removed
        $existing_capabilities = $role->capabilities;
        foreach ($existing_capabilities as $capability => $value) {
            if (!in_array($capability, $selected_capabilities)) {
                $capabilities_to_remove[] = $capability;
            }
        }
        
    
        // Remove capabilities marked for removal
        foreach ($capabilities_to_remove as $capability) {
            $role->remove_cap($capability);
        }
        print_r($capabilities_to_remove);

        // Add newly selected capabilities
        foreach ($selected_capabilities as $capability) {
            $role->add_cap($capability);
        }
        print_r($existing_capabilities);

        echo '<div class="updated"><p>Role capabilities updated successfully.</p></div>';
    }

    

    $wp_roles = wp_roles();

    ?>
    <div class="wrap">
    <h2>EdiUser Capabilities</h2>
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
            <h1></h1>
        <div id="current-capabilities">
            <!-- Capabilities will be loaded here via JavaScript -->
        </div>
        <br>

        <input type="submit" name="update_role_capabilities" class="button button-primary" value="Update Role Capabilities">
    </form>


    </div>
    <script>



</script>

    <?php
}
?>
