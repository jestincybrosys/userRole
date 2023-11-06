<?php
/*
Plugin Name: EdiUser: Edit User Role
Description:  The EdiUser plugin for WordPress is a powerful tool that allows you to manage user roles and permissions on your WordPress website with ease. You can customize and modify user roles, grant or restrict access to various features and functionalities, and ensure the right users have the appropriate privileges.
Version: 1.0.0
Author: Jestin Joseph
Author URI : https://jestinjoseph.netlify.app/
*/

if (!defined('ABSPATH')) {
    exit;
}

// Define a constant for the plugin directory path
define('EDIUSER_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include the required files using require_once
require_once(EDIUSER_PLUGIN_DIR . 'admin/menu.php');
require_once(EDIUSER_PLUGIN_DIR . 'styles-and-scripts.php');
require_once(EDIUSER_PLUGIN_DIR . 'admin/create-role.php');
require_once(EDIUSER_PLUGIN_DIR . 'admin/edit-roles.php'); 
require_once(EDIUSER_PLUGIN_DIR . 'admin/capabilities-ajax.php');
require_once(EDIUSER_PLUGIN_DIR . 'admin/existing-roles-capabilities.php');
require_once(EDIUSER_PLUGIN_DIR . 'admin/user-roles-table.php');


