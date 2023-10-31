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
                    bulk_delete_roles_callback($role_name);
                }
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
            <?php
                generateBulkActions('my-form-2');
                ?>
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
                            <th class="manage-column column-users " style="text-align: center;">Users</th>
                        <?php } ?>
                        <?php if (in_array('capabilities', $selected_columns)) { ?>
                            <th class="manage-column column-capabilities" style="text-align: center;">Capabilities</th>
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

                    echo '<td class="check-column"><input type="checkbox" name="selected-roles[]" class="delete-role" data-role="' . $role_name . '" value="' . $role_name . '"></td>';
                    
                    if (in_array('role', $selected_columns)) {
                        echo '<td class="role column-role">';
                        echo '<a href="?page=edit-roles&role_name=' . $role_name .'"><strong>' . ucfirst($role_name) . '</strong></a>';
                        echo '<br>';
                        echo '<a href="?page=edit-roles&role_name=' . $role_name . '&capabilities=' . urlencode(serialize($capabilities)) . '" class="edit-button">Edit</a>';
                        
                        // Add a delete button with JavaScript confirmation
                        echo '<span class="edit-button"> | </span>';
                        echo '<a href="#" class="delete-role delete-button" data-role="' . $role_name . '">Delete </a><br>';
                        echo '</td>';
                    }
                    
                    if (in_array('users', $selected_columns)) {
                        echo '<td class="users column-users aligncenter">' . $user_count . '</td>';
                    }
                    
                    if (in_array('capabilities', $selected_columns)) {
                        echo '<td class="capabilities column-capabilities aligncenter">' . $total_capabilities_count . '</td>';
                    }
                    
                    echo '</tr>';
                }
                
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="select-all-rows"></th>
                        <?php if (in_array('role', $selected_columns)) { ?>
                            <th class="manage-column column-role">Role</th>
                        <?php } ?>
                        <?php if (in_array('users', $selected_columns)) { ?>
                            <th class="manage-column column-users " style="text-align: center;">Users</th>
                        <?php } ?>
                        <?php if (in_array('capabilities', $selected_columns)) { ?>
                            <th class="manage-column column-capabilities " style="text-align: center;">Capabilities</th>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
           
        </form>
    </div>

    <?php
}
