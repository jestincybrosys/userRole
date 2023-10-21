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
    var selectedCapabilities = $('#current-capabilities').find('input[type="checkbox"]');
    var allCapabilities = $('#all-capabilities').find('input[type="checkbox"]');

    // Uncheck all checkboxes
    allCapabilities.prop('checked', false);

    // Check only the selected user role's capabilities
    selectedCapabilities.each(function() {
        var capabilityName = $(this).val();
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

        // Add commas to all visible capabilities (excluding the last one)
        var visibleCapabilities = $(this).find('span:visible');
        visibleCapabilities.each(function(index) {
            if (index < visibleCapabilities.length - 1) {
                $(this).after(', ');
            }
        });

        // Remove the last comma
        visibleCapabilities.last().next().remove();
    });

    // Show additional capabilities when "Load More" is clicked
    $('.load-more-link').on('click', function(e) {
        e.preventDefault();
        var capabilitiesList = $(this).prev('.capabilities-list');
        var hiddenCapabilities = capabilitiesList.children('.hidden-capability');
        hiddenCapabilities.show();

        // Update commas for all visible capabilities
        var visibleCapabilities = capabilitiesList.find('span:visible');
        visibleCapabilities.each(function(index) {
            if (index < visibleCapabilities.length - 1) {
                // Add a comma
                $(this).after(', ');
            }
        });

        // Remove the last comma
        visibleCapabilities.last().next().remove();

        $(this).hide();
    });
});

