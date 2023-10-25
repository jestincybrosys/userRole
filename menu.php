<?php
// Add a menu item for the ediuser role editor
function ediuser_role_editor_menu() {
    add_menu_page(
        'Ediuser Role Editor',
        'Ediuser: User Roles',
        'manage_options',
        'ediuser-role-editor',
        'ediuser_role_editor_page'
    );

    // Add a sub-menu item to display existing roles
    add_submenu_page(
        'ediuser-role-editor',
        'Edit Roles',
        'Edit Roles',
        'manage_options',
        'edit-roles',
        'edit_roles_page'
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
        'ediuser-role-editor',// Parent menu
        'User Roles & Capabilities', // Page title
        'User Roles & Capabilities', // Menu title
        'manage_options', // Capability required to access
        'user-roles-capabilities-submenu', // Menu slug
        'display_user_roles_table' // Callback function to display the content
    );
}
add_action('admin_menu', 'ediuser_role_editor_menu');
