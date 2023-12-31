<?php


function ediuser_existing_role_capabilities_page() {
    $wp_roles = wp_roles();
    ?>
    <div class="wrap">
        <h2>EdiUser: User Roles and Capabilities Overview</h2>
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
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
     
    </div>
    <?php
}



