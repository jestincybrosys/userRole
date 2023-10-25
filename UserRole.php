<?php
/*
Plugin Name: EdiUser: edit user role
Description: A plugin for editing user roles.
Version: 1.0
Author: jestin joseph
*/

// Add a menu item for the ediuser role editor

// Include the required files using require_once
require_once(plugin_dir_path(__FILE__) . 'menu.php');
require_once(plugin_dir_path(__FILE__) . 'styles-and-scripts.php');
require_once(plugin_dir_path(__FILE__) . 'create-role.php');
require_once(plugin_dir_path(__FILE__) . 'edit-roles.php');
require_once(plugin_dir_path(__FILE__) . 'capabilities-ajax.php');
require_once(plugin_dir_path(__FILE__) . 'existing-roles-capabilities.php');
require_once(plugin_dir_path(__FILE__) . 'user-roles-table.php');
