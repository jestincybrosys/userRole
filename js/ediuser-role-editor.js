// jQuery(document).ready(function($) {
//     $('#role_name').change(function() {
//         var selectedRole = $(this).val();

//         $.post(ajaxurl, {
//             action: 'load_capabilities',
//             role_name: selectedRole
//         }, function(response) {
//             $('#current-capabilities').html(response);
//         });
//     });

//     // Trigger the change event when the page loads to initially load the capabilities
//     $('#role_name').trigger('change');
// });



// function checkCapabilities() {
//     var selectedCapabilities = $('#current-capabilities').find('input[type="checkbox"]');
//     var allCapabilities = $('#all-capabilities').find('input[type="checkbox"]');

//     // Uncheck all checkboxes
//     allCapabilities.prop('checked', false);

//     // Check only the selected user role's capabilities
//     selectedCapabilities.each(function() {
//         var capabilityName = $(this).val();
//         allCapabilities.filter('[value="' + capabilityName + '"]').prop('checked', true);
//     });
// }
jQuery(document).ready(function($) {
    $('#role_name').change(function() {
        var selectedRole = $(this).val();

        $.post(ajaxurl, {
            action: 'load_capabilities',
            role_name: selectedRole
        }, function(response) {
            $('#current-capabilities').html(response);

            // After loading capabilities, mark the checkboxes for the selected user role
            checkCapabilities(selectedRole);
        });
    });

    // Trigger the change event when the page loads to initially load the capabilities
    $('#role_name').trigger('change');
});
 
function checkCapabilities(_selectedRole) {
    var selectedCapabilities = jQuery('#current-capabilities').find('input[type="checkbox"]');
    var allCapabilities = jQuery('#all-capabilities').find('input[type="checkbox"]');

    // Uncheck all checkboxes  
    allCapabilities.prop('checked', false);

    // Check only the selected user role's capabilities
    selectedCapabilities.each(function() {
        var capabilityName = jQuery(this).val();
        allCapabilities.filter('[value="' + capabilityName + '"]').prop('checked', true);
    });
}





jQuery(document).ready(function($) {
    // Hide additional capabilities and show "Load More" link
    $('.capabilities-list').each(function() {
        var capabilities = $(this).children('.hidden-capability');
        capabilities.hide();
        if (capabilities.length > 0) {
            $(this).next('.load-more-link').show();
        }

        // Add commas only to the visible, listed capabilities
        var capabilitySpans = $(this).find('span');
        capabilitySpans.each(function(index) {
            if (index < capabilitySpans.length - 1 && !$(this).hasClass('hidden-capability')) {
                $(this).after(', ');
            }
        });
    });

    // Show additional capabilities when "Load More" is clicked
    $('.load-more-link').on('click', function(e) {
        e.preventDefault();
        var capabilitiesList = $(this).prev('.capabilities-list');
        var hiddenCapabilities = capabilitiesList.children('.hidden-capability');
        hiddenCapabilities.show();

        // Remove the last comma from the displayed capabilities
        capabilitiesList.find('span:visible:last').next().remove();

        $(this).hide();
    });
});

jQuery(document).ready(function($) {
    // Handle column toggling
    $('#columns-toggle').change(function() {
        var selectedColumns = $(this).val();
        
        // Hide all columns
        $('table.widefat thead th').hide();
        $('table.widefat tbody td').hide();
        
        // Show the selected columns
        for (var i = 0; i < selectedColumns.length; i++) {
            $('table.widefat thead th.' + selectedColumns[i]).show();
            $('table.widefat tbody td.' + selectedColumns[i]).show();
        }
    });
});


function showEditButton(cell) {
     var editButton = cell.querySelector('.edit-button');
     editButton.style.display = 'inline';
 }

 function hideEditButton(cell) {
     var editButton = cell.querySelector('.edit-button');
     editButton.style.display = 'none';
 }

 function showDeleteButton(cell) {
    var deleteButton = cell.querySelector('.delete-button');
    deleteButton.style.display = 'inline';
}

function hideDeleteButton(cell) {
    var deleteButton = cell.querySelector('.delete-button');
    deleteButton.style.display = 'none';
}




    jQuery(document).ready(function ($) {
        // Individual role deletion
        $('.delete-role').on('click', function (e) {
            e.preventDefault();
            var role_name_to_delete = $(this).data('role');
            if (confirm('Are you sure you want to delete ' + role_name_to_delete + '?')) {
                // Perform the deletion using AJAX
                $.post(ajaxurl, {
                    action: 'delete_role',
                    role_name: role_name_to_delete
                }, function (response) {
                    if (response === 'success') {
                        alert('Role deleted successfully.');
                        // Optionally, you can reload the page or update the role list here
                        location.reload();
                    } else {
                        alert('Role deleted successfully.');

                        location.reload();
                    }
                });
            }
        });

        // Bulk role deletion
        // $('#<?php echo $formId; ?>-apply-button').on('click', function (e) {
        //     e.preventDefault();
        //     var selectedRoles = [];
        //     $('.delete-role:checked').each(function () {
        //         selectedRoles.push($(this).data('role'));
        //     });

        //     if (selectedRoles.length > 0 && confirm('Are you sure you want to delete the selected roles?')) {
        //         // Perform the bulk deletion using AJAX
        //         $.post(ajaxurl, {
        //             action: 'bulk_delete_roles',
        //             roles: selectedRoles
        //         }, function (response) {
        //             if (response === 'success') {
        //                 alert('Selected roles deleted successfully.');
        //                 // Optionally, you can reload the page or update the role list here
        //                 location.reload();
        //             } else {
        //                 alert('Error deleting the selected roles.');
        //             }
        //         });
        //     }
        // });
    });
