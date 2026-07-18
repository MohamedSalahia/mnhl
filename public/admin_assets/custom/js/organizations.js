$(function () {

    initializeSuperAdminToggle();

});//end of document ready

let initializeSuperAdminToggle = () => {

    // Handle radio button change
    $(document).on('change', 'input[name="super_admin_type"]', function () {

        const selectedType = $(this).val();

        const existingSelect = $('#existing-super-admin-select');

        if (selectedType === 'new') {
            // Show new user form fields

            $('#new-user-fields').show();
            $('#existing-user-field').hide();

            // Make new user fields required
            $('#new-user-fields input').prop('required', true);

            // Remove required from existing user select
            existingSelect.prop('required', false);
            existingSelect.val('').trigger('change');

        } else if (selectedType === 'existing') {

            // Show existing user select
            $('#existing-user-field').show();
            $('#new-user-fields').hide();

            // Make existing user select required
            existingSelect.prop('required', true);

            // Remove required from new user fields
            $('#new-user-fields input').prop('required', false);
            $('#new-user-fields input').val('');

            // Initialize select2 if not already initialized
            // Use a small delay to ensure the element is visible before initializing
            setTimeout(function () {
                if (!existingSelect.hasClass('select2-hidden-accessible')) {
                    existingSelect.select2({
                        placeholder: existingSelect.data('placeholder') || existingSelect.find('option:first').text(),
                        allowClear: true,
                        width: '100%'
                    });
                } else {
                    // If already initialized, trigger update to ensure it displays correctly
                    existingSelect.trigger('change.select2');
                }
            }, 100);
        }
    });

    // Initialize on page load based on checked radio button
    const checkedRadio = $('input[name="super_admin_type"]:checked');
    if (checkedRadio.length > 0) {
        checkedRadio.trigger('change');
    } else {
        // Default to 'new' if nothing is checked
        $('input[name="super_admin_type"][value="new"]').prop('checked', true).trigger('change');
    }

}//end of initializeSuperAdminToggle

