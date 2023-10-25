<?php
function display_user_roles_table() {
    $wp_roles = wp_roles();
    $selected_columns = array('role', 'users', 'capabilities');
    
    if (isset($_POST['columns-toggle'])) {
        $selected_columns = $_POST['columns-toggle'];
    }
    
    ?>
    <div class="wrap">
        <h2>User Roles and Capabilities</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="post">
                    <label for="columns-toggle">Show on screen</label>
                    <select name="columns-toggle[]" id="columns-toggle" multiple>
                        <option value="role" <?php if (in_array('role', $selected_columns)) echo 'selected'; ?>>Role</option>
                        <option value="users" <?php if (in_array('users', $selected_columns)) echo 'selected'; ?>>Users Assigned</option>
                        <option value="capabilities" <?php if (in_array('capabilities', $selected_columns)) echo 'selected'; ?>>Total Capabilities Count</option>
                    </select>
                    <input type="submit" class="button" value="Apply">
                </form>
            </div>
        </div>
        <table class="widefat">
            <thead>
                <tr>
                    <th class="role">Role</th>
                    <th class="users">Users Assigned</th>
                    <th class="capabilities">Total Capabilities Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($wp_roles->role_objects as $role_name => $role) {
                    $capabilities = $role->capabilities;
                    $user_count = count(get_users(['role' => $role_name]));
                    $total_capabilities_count = count($capabilities);

                    echo '<tr>';
                    if (in_array('role', $selected_columns)) {
                        echo '<td class="role">' . $role_name . '</td>';
                    }
                    if (in_array('users', $selected_columns)) {
                        echo '<td class="users">' . $user_count . '</td>';
                    }
                    if (in_array('capabilities', $selected_columns)) {
                        echo '<td class="capabilities">' . $total_capabilities_count . '</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
