<?php
// Add a menu item for the ediuser role editor
function ediuser_role_editor_menu() {
    add_menu_page(
        'Ediuser Role Editor',// Parent menu
        'Ediuser: User Roles', // Page title
        'manage_options', // Capability required to access
        'ediuser-role-editor', // Menu slug
        'display_user_roles_table' // Callback function to display the content
    );

    // Add a sub-menu item to display existing roles
    add_submenu_page(
        'ediuser-role-editor',
        'Edit Roles',
        'Edit Roles',
        'manage_options',
        'edit-roles',
        'ediuser_edit_roles_page'
    );

    add_submenu_page(
        'ediuser-role-editor',
        'Existing Role Capabilities',
        'Role Capabilities',
        'manage_options',
        'existing-role-capabilities',
        'existing_role_capabilities_page'
    );

    add_submenu_page(
        'Ediuser Role Editor',
        'Ediuser:new User Roles',
        'Ediuser:new User Roles',
        'manage_options',
        'ediuser-role-new',
        'ediuser_role_editor_new_page'

    );
}
add_action('admin_menu', 'ediuser_role_editor_menu');
