<?php
function display_user_roles_table() {
    $wp_roles = wp_roles();
    $selected_columns = array('role', 'users', 'capabilities');

    // Define the maximum number of roles to display
    $max_roles_to_display = 10;

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

    $items_per_page = 10; // Number of items per page
    $current_page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

    $total_items = count($wp_roles->role_objects);
    $total_pages = ceil($total_items / $items_per_page);

    $start_index = ($current_page - 1) * $items_per_page;

    ?>
    <div class="wrap">
        <h2>EdiUser: Roles and Capabilities

        <!-- Button to Create a New Role -->
        <a href="?page=ediuser-role-new" class="page-title-action">New Role</a></h2>

        <form method="post">
            <input type="hidden" name="paged" value="<?php echo esc_attr($current_page); ?>">
            <div class="tablenav top">
                <?php generateBulkActions('my-form-2'); ?>
                <div class="alignright actions">
                    <input type="search" id="table-search" name="s" value="<?php echo isset($_POST['s']) ? esc_attr($_POST['s']) : ''; ?>">
                    <input type="submit" class="button" value="Search">
                </div>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="select-all-rows-1"></th>
                        <?php if (in_array('role', $selected_columns)) { ?>
                            <th class="manage-column column-role">Role</th>
                        <?php } ?>
                        <?php if (in_array('users', $selected_columns)) { ?>
                            <th class="manage-column column-users" style="text-align: center;">Users</th>
                        <?php } ?>
                        <?php if (in_array('capabilities', $selected_columns)) { ?>
                            <th class="manage-column column-capabilities" style="text-align: center;">Capabilities</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="the-list">
                <?php
                // Loop through the first 5 roles only
                $counter = 0;
                foreach (array_slice(array_keys($wp_roles->role_objects), $start_index, $items_per_page) as $role_name) {
                    $capabilities = $wp_roles->role_objects[$role_name]->capabilities;
                    $user_count = count(get_users(['role' => $role_name]));
                    $total_capabilities_count = count($capabilities);

                    // Apply the search filter
                    $search_query = isset($_POST['s']) ? sanitize_text_field($_POST['s']) : '';

                    // Check if the role name or capabilities contain the search query
                    if (empty($search_query) || stripos($role_name, $search_query) !== false || stripos(serialize($capabilities), $search_query) !== false) {
                        echo '<tr>';
                        echo '<td class="check-column"><input type="checkbox" name="selected-roles[]" class="delete-role" data-role="' . $role_name . '" value="' . $role_name . '"></td>';

                        if (in_array('role', $selected_columns)) {
                            echo '<td class="role column-role">';
                            echo '<a href="?page=edit-roles&role_name=' . $role_name . '"><strong>' . ucfirst($role_name) . '</strong></a>';
                            echo '<br>';
                            echo '<a href="?page=edit-roles&role_name=' . $role_name . '&capabilities=' . urlencode(serialize($capabilities)) . '" class="edit-button">Edit</a>';

                            // Add a delete button with JavaScript confirmation
                            echo '<span class="edit-button"> | </span>';
                            echo '<a href="#" class="delete-role delete-button" data-role="' . $role_name . '">Delete</a><br>';
                            echo '</td>';
                        }

                        if (in_array('users', $selected_columns)) {
                            echo '<td class="users column-users aligncenter">' . $user_count . '</td>';
                        }

                        if (in_array('capabilities', $selected_columns)) {
                            echo '<td class="capabilities column-capabilities aligncenter">' . $total_capabilities_count . '</td>';
                        }

                        echo '</tr>';

                        $counter++;
                        if ($counter >= $max_roles_to_display) {
                            break;
                        }
                    }
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="check-column"><input type="checkbox" id="select-all-rows-2"></th>
                        <?php if (in_array('role', $selected_columns)) { ?>
                            <th class="manage-column column-role">Role</th>
                        <?php } ?>
                        <?php if (in_array('users', $selected_columns)) { ?>
                            <th class="manage-column column-users" style="text-align: center;">Users</th>
                        <?php } ?>
                        <?php if (in_array('capabilities', $selected_columns)) { ?>
                            <th class="manage-column column-capabilities" style="text-align: center;">Capabilities</th>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
        </form>

        <?php
        if ($total_pages > 1) {
            echo '<div class="tablenav bottom">
                <div class="tablenav-pages">';
            echo paginate_links(array(
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
                'total' => $total_pages,
                'current' => $current_page,
            ));
            echo '</div>
            </div>';
        }
        ?>

    </div>
<?php
}
