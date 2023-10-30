<?php


function existing_role_capabilities_page() {
    $wp_roles = wp_roles();
    ?>
    <div class="wrap">
        <h2>Existing User Roles and Capabilities</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Capabilities</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($wp_roles->role_objects as $role_name => $role) {
                    $capabilities = $role->capabilities;
                    echo '<tr>';
                    echo '<td>' . $role_name . '</td>';
                    echo '<td>';
                    $capabilityKeys = array_keys($capabilities);
                    echo '<div class="capabilities-list">';
                    for ($i = 0; $i < count($capabilityKeys); $i++) {
                        if ($i >= 10) {
                            echo '<span class="hidden-capability">' . $capabilityKeys[$i] . '</span> ';
                        } else {
                            echo '<span>' . $capabilityKeys[$i] . '</span> ';
                        }
                    }
                    echo '</div>';
                    // Add "Load More" link if there are more than 10 capabilities
                    if (count($capabilityKeys) > 10) {
                        echo '<a class="load-more-link" href="#">Load More</a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
