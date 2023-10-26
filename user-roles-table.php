<<?php
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
            echo 'Bulk Delete Action Performed';
        }
    }
    
    ?>
    <div class="wrap">
        <h2>User Roles and Capabilities</h2>
        <form method="post">
            <div class="tablenav top">
                <div class="alignleft actions">
                    <label for="bulk-action">Bulk Actions</label>
                    <select name="bulk-action" id="bulk-action">
                        <option value="">Select an action</option>
                        <option value="delete">Delete</option>
                        <!-- Add more bulk actions as needed -->
                    </select>
                    <input type="submit" class="button action" value="Apply">
                </div>
                <div class="alignright actions">
                    <label for="table-search">Search:</label>
                    <input type="search" id="table-search" name="s" value="<?php echo isset($_POST['s']) ? esc_attr($_POST['s']) : ''; ?>">
                    <input type="submit" class="button" value="Search">
                </div>
            </div>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column check-column"><input type="checkbox" id="select-all-rows"></th>
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
        echo '<td class="role column-role" onmouseover="showEditButton(this)" onmouseout="hideEditButton(this)">';
        echo '<strong>' . ucfirst($role_name) . '</strong>';
        echo '<br>';
        echo ' <a href="?page=edit-roles&role_name=' . $role_name . '&capabilities=' . urlencode(serialize($capabilities)) . '" class="edit-button">Edit</a>';
        echo '<br><a href="?page=edit-roles&role_name=' . $role_name . '&capabilities=' . urlencode(serialize($capabilities)) . '" class="edit-button">Edit</a>';
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
            <input type="submit" class="button action" value="Apply">
        </form>
    </div>
    <style>
        .edit-button {
            display: none;
        }
    </style>
    <script>
        function showEditButton(cell) {
            var editButton = cell.querySelector('.edit-button');
            editButton.style.display = 'inline';
        }

        function hideEditButton(cell) {
            var editButton = cell.querySelector('.edit-button');
            editButton.style.display = 'none';
        }
    </script>
    <?php
}

function delete_selected_role($role_name) {
    // Implement your logic to delete the role from the database
    // Example: wp_delete_role($role_name);
    // You may want to add error handling and success messages here.
}
