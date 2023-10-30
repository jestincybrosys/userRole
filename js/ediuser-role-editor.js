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
        });
    });

    // Trigger the change event when the page loads to initially load the capabilities
    $('#role_name').trigger('change');
});

function checkCapabilities() {
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

function confirmDelete(roleName) {
    var confirmMessage = "Are you sure you want to delete the role: " + roleName + "?";
    if (confirm(confirmMessage)) {
        // If the user confirms, proceed with the deletion
        window.location.href = "?page=delete_selected_role&role_name=" + roleName;
    }
}