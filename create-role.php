<?php
// Code for creating custom user roles
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
            <div class="capability-list">
                <?php
                $all_capabilities = get_role('administrator')->capabilities; // Use admin capabilities as a reference.
                foreach ($all_capabilities as $cap => $value) {
                    ?>
                    <label>
                        <input type="checkbox" name="capabilities[]" value="<?php echo $cap; ?>">
                        <?php echo $cap; ?>
                    </label><br>
                    <?php
                }
                ?>
            </div>

            <input type="submit" name="create_ediuser_role" class="button button-primary" value="Create Role">
        </form>
    </div>
    <?php
}
?>
