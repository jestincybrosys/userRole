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
    // Function to add commas to a list of capabilities
    function addCommasToCapabilities(capabilityList) {
        var capabilitySpans = capabilityList.find('span');
        var capabilities = [];

        capabilitySpans.each(function(index) {
            capabilities.push($(this).text());

            if (index < capabilitySpans.length - 1) {
                capabilities.push(', ');
            }
        });

        capabilityList.text(capabilities.join(''));
    }

    // Hide additional capabilities and show "Load More" link for elements with hidden capabilities
    $('.capabilities-list').each(function() {
        var capabilitiesList = $(this);
        var hiddenCapabilities = capabilitiesList.children('.hidden-capability');
        if (hiddenCapabilities.length > 0) {
            hiddenCapabilities.hide();
            var loadMoreLink = $('<br><a href="#" class="load-more-link">Load More</a>');
            capabilitiesList.append(loadMoreLink);
            loadMoreLink.on('click', function(e) {
                e.preventDefault();
                hiddenCapabilities.show();
                addCommasToCapabilities(capabilitiesList);
                loadMoreLink.remove();
            });
        } else {
            addCommasToCapabilities(capabilitiesList);
        }
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
});


jQuery(document).ready(function($) {
    // Default to showing the "General Group" capabilities when the page loads
    var defaultGroup = "General";
    $(".group-capabilities[data-group='" + defaultGroup + "']").show();
    $(".group-list-ul li a[data-group='" + defaultGroup + "']").parent().addClass("active-group"); // Mark the default group as active

    // Toggle group capabilities when clicking on group name
    $(".toggle-group").on("click", function() {
        var group = $(this).data("group");
        $(".group-capabilities").hide();
        $(".group-capabilities[data-group='" + group + "']").show();
        $(".group-list-ul li").removeClass("active-group");
        $(this).parent().addClass("active-group"); // Mark the clicked group as active
    });
});
