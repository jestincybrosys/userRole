<?php
// Add a menu item for the ediuser role editor
function ediuser_role_editor_menu() {
    $menu_icon_svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
<path d="M458.7,388.5c0,11.1-9.6,20.1-21.3,20.1h-64v60.3c0,11.1-9.6,20.1-21.3,20.1s-21.3-9-21.3-20.1v-60.3h-64
   c-11.8,0-21.3-9-21.3-20.1s9.6-20.1,21.3-20.1h64v-60.3c0-11.1,9.6-20.1,21.3-20.1s21.3,9,21.3,20.1v60.3h64
   C449.1,368.4,458.7,377.4,458.7,388.5z M277.3,157c41.2,0,74.7-33.6,74.7-75S318.5,7,277.3,7s-74.7,33.6-74.7,75
   S236.2,157,277.3,157z M96,241.6c0,41.1,33.5,74.6,74.7,74.6s74.7-33.4,74.7-74.6S211.8,167,170.7,167S96,200.4,96,241.6z
    M202.7,395.7c0-19.4,9.9-36.5,25-47.5c-17-7.8-36.5-12.1-57-12.1C105.3,336,53,377.4,53.3,436.4c0.1,10.9,9.9,19.6,21.5,19.6h191.7
   c0,0,0,0,0,0C231.3,456,202.7,428.9,202.7,395.7L202.7,395.7z M262.4,174.9c16.6,19.4,26.5,43.9,26.5,70.5c0,18.6-4.9,36-13.4,51.5
   H293c9.1-23.9,33.4-41.2,62.2-41.2c16.9,0,32.2,6.1,43.9,15.9C396.1,214,342.1,174,277.9,174C272.6,174,267.5,174.4,262.4,174.9
   L262.4,174.9z"/>
</svg>';

    global $submenu;

    add_menu_page(
        'EdiUser Role Editor',// Parent menu
        'EdiUser', // Page title
        'manage_options', // Capability required to access
        'ediuser-role-editor', // Menu slug
        'ediuser_display_user_roles_table' ,// Callback function to display the content
        'data:image/svg+xml;base64,' . base64_encode($menu_icon_svg) // Use inline SVG markup

    );
    // Add a sub-menu item to display existing roles
    add_submenu_page(
        'ediuser-role-editor',
        'Add New Roles',
        'Add New Roles',
        'manage_options',
        'ediuser-role-new',
        'ediuser_role_editor_new_page'
    );

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
        'Role Overview',
        'manage_options',
        'existing-role-capabilities',
        'ediuser_existing_role_capabilities_page'
    );


    $submenu['ediuser-role-editor'][0][0] = 'Roles';

}
add_action('admin_menu', 'ediuser_role_editor_menu');
