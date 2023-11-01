<?php
function ediuser_role_editor_new_page() {
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
       echo ' <div class="updated"><p>ediuser role created successfully.</p></div>';
    }
// Define capability groups and assign capabilities to groups
$capability_groups = array(
    'General' => array(
        'edit_dashboard' => 'Edit Dashboard',
        'edit_files' => 'Edit Files',
        'export' => 'Export',
        'import' => 'Import',
        'manage_links' => 'Manage Links',
        'manage_options' => 'Manage Options',
        'moderate_comments' => 'Moderate Comments',
        'read' => 'Read',
        'unfiltered_html' => 'Unfiltered HTML',
        'update_core' => 'Update Core',
    ),
    'Posts' => array(
        'delete_others_posts' => "Delete Others' Posts",
        'delete_posts' => 'Delete Posts',
        'delete_private_posts' => 'Delete Private Posts',
        'delete_published_posts' => 'Delete Published Posts',
        'edit_others_posts' => "Edit Others' Posts",
        'edit_posts' => 'Edit Posts',
        'edit_private_posts' => 'Edit Private Posts',
        'edit_published_posts' => 'Edit Published Posts',
        'publish_posts' => 'Publish Posts',
        'read_private_posts' => 'Read Private Posts',
    ),
    'Pages' => array(
        'delete_others_pages' => "Delete Others' Pages",
        'delete_pages' => 'Delete Pages',
        'delete_private_pages' => 'Delete Private Pages',
        'delete_published_pages' => 'Delete Published Pages',
        'edit_others_pages' => "Edit Others' Pages",
        'edit_pages' => 'Edit Pages',
        'edit_private_pages' => 'Edit Private Pages',
        'edit_published_pages' => 'Edit Published Pages',
        'publish_pages' => 'Publish Pages',
        'read_private_pages' => 'Read Private Pages',
    ),
    'Attachments' => array(
        'upload_files' => 'Upload Files',
    ),
    'Taxonomies' => array(
        'manage_categories' => 'Manage Categories',
    ),
    'Themes' => array(
        'delete_themes' => 'Delete Themes',
        'edit_theme_options' => 'Edit Theme Options',
        'edit_themes' => 'Edit Themes',
        'install_themes' => 'Install Themes',
        'switch_themes' => 'Switch Themes',
        'update_themes' => 'Update Themes',
    ),
    'Plugins' => array(
        'activate_plugins' => 'Activate Plugins',
        'delete_plugins' => 'Delete Plugins',
        'edit_plugins' => 'Edit Plugins',
        'install_plugins' => 'Install Plugins',
        'update_plugins' => 'Update Plugins',
    ),
    'Users' => array(
        'create_roles' => 'Create Roles',
        'create_users' => 'Create Users',
        'delete_roles' => 'Delete Roles',
        'delete_users' => 'Delete Users',
        'edit_roles' => 'Edit Roles',
        'edit_users' => 'Edit Users',
        'list_roles' => 'List Roles',
        'list_users' => 'List Users',
        'promote_users' => 'Promote Users',
        'remove_users' => 'Remove Users',
    ),
    'Custom' => array(
        'restrict_content' => 'Restrict Content',
        // Add your custom capabilities here...
    ),
    // Add more groups as needed...
);
// Code for creating custom user roles

    ?>
    <div class="wrap">
        <h2>Create Custom User Role</h2>
        <form method="post" action="">
            <label for="role_name">Role Name:</label>
            <input type="text" id="role_name" placeholder="Enter Role Name" name="role_name" required>



<div class="capability-group-main">

<!-- Display the group list on one side -->
<h3>Capability Groups</h3>
<div class="group-li">
    <div class="group-list">
        <ul class="group-list-ul">
            <?php
            $all_capabilities = get_role('administrator')->capabilities; // Use admin capabilities as a reference.
            foreach ($capability_groups as $group_name => $group_capabilities) {
                echo '<li><a href="javascript:void(0);" class="toggle-group" data-group="' . esc_attr($group_name) . '">' . esc_html($group_name) . '</a></li>';
            }
            ?>
        </ul>
    </div>

<!-- Display the selected group's capabilities on the other side -->
<div class="selected-group-capabilities">
    <table class="widefat striped tbl">
        <thead>
            <tr>
                <th>Capabilities</th>
                <th>Grant</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($capability_groups as $group_name => $group_capabilities) {
             foreach ($group_capabilities as $cap => $label) {
                    echo '<tr class="group-capabilities" data-group="' . esc_attr($group_name) . '" style="display:none">';
                    echo '<td class="group-name">' . esc_attr($label) .  '</td>';
                    echo '<td class="group-name">';
                    echo '<input type="checkbox" name="capabilities[]" value=" '. esc_attr($cap) .'>">';
                    echo '</td>';
                    echo '</tr>';
                
            }}
            ?>
        </tbody>
    </table>
    </div>

</div>
</div>
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

</script>

            <input type="submit" name="create_ediuser_role" class="button button-primary" value="Create Role">
        </form>
    </div>
    <?php
}
?>
