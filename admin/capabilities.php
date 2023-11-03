<?php


function define_capability_groups() {
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
    );

    // Check if WooCommerce is active and include its capabilities
    if (is_plugin_active('woocommerce/woocommerce.php')) {
        $capability_groups['Products'] = array(
            
                'edit_product' => 'Edit Product',
                'read_product' => 'Read Product',
                'delete_product' => 'Delete Product',
                'edit_products' => 'Edit Products',
                'edit_others_products' => 'Edit Others\' Products',
                'publish_products' => 'Publish Products',
                'read_private_products' => 'Read Private Products',
                'delete_products' => 'Delete Products',
                'delete_private_products' => 'Delete Private Products',
                'delete_published_products' => 'Delete Published Products',
                'delete_others_products' => 'Delete Others\' Products',
                'edit_private_products' => 'Edit Private Products',
                'edit_published_products' => 'Edit Published Products',
            );
            $capability_groups['Orders'] = array(
                'edit_shop_order' => 'Edit Shop Order',
                'read_shop_order' => 'Read Shop Order',
                'delete_shop_order' => 'Delete Shop Order',
                'edit_shop_orders' => 'Edit Shop Orders',
                'edit_others_shop_orders' => 'Edit Others\' Shop Orders',
                'publish_shop_orders' => 'Publish Shop Orders',
                'read_private_shop_orders' => 'Read Private Shop Orders',
                'delete_shop_orders' => 'Delete Shop Orders',
                'delete_private_shop_orders' => 'Delete Private Shop Orders',
                'delete_published_shop_orders' => 'Delete Published Shop Orders',
                'delete_others_shop_orders' => 'Delete Others\' Shop Orders',
                'edit_private_shop_orders' => 'Edit Private Shop Orders',
                'edit_published_shop_orders' => 'Edit Published Shop Orders',
            );
            $capability_groups['Coupons'] = array(
                'edit_shop_coupon' => 'Edit Shop Coupon',
                'read_shop_coupon' => 'Read Shop Coupon',
                'delete_shop_coupon' => 'Delete Shop Coupon',
                'edit_shop_coupons' => 'Edit Shop Coupons',
                'edit_others_shop_coupons' => 'Edit Others\' Shop Coupons',
                'publish_shop_coupons' => 'Publish Shop Coupons',
                'read_private_shop_coupons' => 'Read Private Shop Coupons',
                'delete_shop_coupons' => 'Delete Shop Coupons',
                'delete_private_shop_coupons' => 'Delete Private Shop Coupons',
                'delete_published_shop_coupons' => 'Delete Published Shop Coupons',
                'delete_others_shop_coupons' => 'Delete Others\' Shop Coupons',
                'edit_private_shop_coupons' => 'Edit Private Shop Coupons',
                'edit_published_shop_coupons' => 'Edit Published Shop Coupons',
            );
            $capability_groups['Refunds'] = array(
                'process_shop_refund' => 'Process Shop Refund',
                'edit_shop_refunds' => 'Edit Shop Refunds',
                'read_shop_refunds' => 'Read Shop Refunds',
                'delete_shop_refunds' => 'Delete Shop Refunds',
            );
        
    }

    $administrator_capabilities = get_role('administrator')->capabilities;

    // Initialize an empty "Others" group.
    $others_capabilities = array();

    // Iterate through the defined capability groups.
    foreach ($capability_groups as $group_name => $group_capabilities) {
        // Compare the administrator capabilities with the group capabilities.
        $missing_capabilities = array_diff(array_keys($administrator_capabilities), array_keys($group_capabilities));

        // Add missing capabilities to the "Others" group.
        $others_capabilities = array_merge($others_capabilities, $missing_capabilities);
    }

    // Remove capabilities that were added to the "Others" group from the administrator capabilities.
    $administrator_capabilities = array_diff_key($administrator_capabilities, array_flip($others_capabilities));

    // Add the "Others" group with the remaining capabilities.
    if (!empty($others_capabilities)) {
        $capability_groups['All'] = array_combine($others_capabilities, $others_capabilities);
    }
        print_r($capability_groups);
        return $capability_groups;
}
