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
