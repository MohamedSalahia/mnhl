$(function () {

    $(document).on('change', '.all-permissions', function () {

        $(this).parents('tr').find('input[type="checkbox"]').prop('checked', this.checked);

    });

    $(document).on('change', '.role', function () {

        if (!this.checked) {
            $(this).parents('tr').find('.all-permissions').prop('checked', false);
        }

    });

});//end of document ready
