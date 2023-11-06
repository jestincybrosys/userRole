<?php
require_once('capabilities.php');
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
$capability_groups = ediuser_define_capability_groups();

// Code for creating custom user roles
    
    ?>
    <div class="wrap">
        <h2>EdiUser: Create User Role</h2>
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
                    echo '<td class="group-name">';
                    echo '<label for="' . esc_attr($cap) . '">' . esc_html($label) . '</label>';
                    echo '</td>';
                    echo '<td class="group-name">';
                    echo '<input type="checkbox" id="' . esc_attr($cap) . '" name="capabilities[' . esc_attr($cap) . ']" value="' . esc_attr($cap) . '">';
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

            <input type="submit" name="create_ediuser_role" class="button button-primary mt-5" value="Create Role">
        </form>
    </div>
    <?php
}
?>
