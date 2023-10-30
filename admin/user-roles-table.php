<?php

function display_user_roles_table() {
    $wp_roles = wp_roles();
    $selected_columns = array('role', 'users', 'capabilities');
    
    if (isset($_POST['columns-toggle'])) {
        $selected_columns = $_POST['columns-toggle'];
    }
    
    if (isset($_POST['bulk-action'])) {
        $bulk_action = $_POST['bulk-action'];
        
        if ($bulk_action === 'delete') {
            // Handle delete action or show a message
            if (isset($_POST['selected-roles'])) {
                foreach ($_POST['selected-roles'] as $role_name) {
                    delete_selected_role($role_name);
                }
                echo 'Selected Roles Deleted';
            }
        }
    }

    
    ?>
    <div class="wrap">
        <h2>User Roles and Capabilities</h2>

        <!-- Button to Create a New Role -->
        <a href="?page=ediuser-role-new" class="button-prymary">New Role</a>

        <form method="post">
            <div class="tablenav top">
                    <div class="alignright actions">
                    <input type="search" id="table-search" name="s" value="<?php echo isset($_POST['s']) ? esc_attr($_POST['s']) : ''; ?>">
                    <input type="submit" class="button" value="Search">
                </div>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="select-all-rows"></th>
                        <?php if (in_array('role', $selected_columns)) { ?>
                            <th class="manage-column column-role">Role</th>
                        <?php } ?>
                        <?php if (in_array('users', $selected_columns)) { ?>
                            <th class="manage-column column-users">Users Assigned</th>
                        <?php } ?>
                        <?php if (in_array('capabilities', $selected_columns)) { ?>
                            <th class="manage-column column-capabilities">Total Capabilities Count</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="the-list">
                <?php
                    foreach ($wp_roles->role_objects as $role_name => $role) {
                        $capabilities = $role->capabilities;
                        $user_count = count(get_users(['role' => $role_name]));
                        $total_capabilities_count = count($capabilities);

                        echo '<tr>';
                        echo '<th class="check-column"><input type="checkbox" name="selected-roles[]" value="' . $role_name . '"></th>';
                        if (in_array('role', $selected_columns)) {
                            echo '<td class="role column-role">';
                            echo '<a href="?page=edit-roles&role_name=' . $role_name .'"><strong>' . ucfirst($role_name) . '</strong></a>';
                            echo '<br>';
                            echo '<a href="?page=edit-roles&role_name=' . $role_name . '&capabilities=' . urlencode(serialize($capabilities)) . '" class="edit-button">Edit</a>';
                            echo '<span class="edit-button" > | </span>';
                            echo '<a href="javascript:void(0);" class="delete-button" onclick="confirmDelete(\'' . $role_name . '\')">Delete</a>';
                            echo '</td>';
                            
                        }
                        if (in_array('users', $selected_columns)) {
                            echo '<td class="users column-users">' . $user_count . '</td>';
                        }
                        if (in_array('capabilities', $selected_columns)) {
                            echo '<td class="capabilities column-capabilities">' . $total_capabilities_count . '</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <?php
                generateBulkActions('my-form-2');
                ?>
        </form>
    </div>

    <?php
}
function delete_selected_role($role_name) {
    if (empty($role_name)) {
        // Role name is empty, do nothing
        return;
    }

    // Check if the role exists
    if (!get_role($role_name)) {
        echo 'Role does not exist: ' . $role_name;
        return;
    }

    // Do not delete the default roles (Administrator, Editor, etc.)
    if (in_array($role_name, ['administrator', 'editor', 'author', 'contributor', 'subscriber'])) {
        echo '<div class="error"><p>Cannot delete a default WordPress role: ' . $role_name . '</p></div>';
        return;
    }

    // Display a JavaScript confirmation message
    echo '<script>
        function confirmDelete(roleName) {
            var confirmed = confirm("Are you sure you want to delete the role: " + roleName + " ?");
            if (confirmed) {
                // The user confirmed, proceed with the deletion
                deleteRole(roleName);
            }
        }

        function deleteRole(roleName) {
            // Use an AJAX request to call the server-side function to remove the role
            jQuery.ajax({
                url: "?page=delete_selected_role&role_name=" + roleName,
                method: "GET",
                success: function (data) {
                    // Display a success message or handle any other actions
                    alert("Role deleted successfully: " + roleName);
                    // Optionally, refresh the page or update the role list
                    location.reload();
                },
                error: function (data) {
                    // Handle errors or display an error message
                    alert("Failed to delete role: " + roleName);
                }
            });
        }
    </script>';
}



