$(function () {

    handlePreviousEducationChange();

    handleSubscriptionTypeFees();

    handleExemptedFromFeesToggle();

});//end of document ready

let handlePreviousEducationChange = function () {

    $(document).on('change', '#has-previous-education', function () {

        if ($(this).val() === '1') {

            $('#previous-education-details').prop('required', true);
            $('#previous-education-details-wrapper').show();

        } else {

            $('#previous-education-details').prop('required', false);
            $('#previous-education-details-wrapper').hide();
        }
    });

};

let handleSubscriptionTypeFees = function () {

    $(document).on('change', '#subscription-type-id', function () {

        let $selected = $(this).find('option:selected');
        let fees       = $selected.data('fees');
        let currencyId = $selected.data('currency-id');

        if (fees === undefined || fees === '') {
            $('#student-fees').val('');
        } else {
            $('#student-fees').val(fees);
        }

        // Auto-fill currency if the subscription type has one
        if (currencyId) {
            let $currencySelect = $('#currency-id');
            $currencySelect.val(currencyId).trigger('change');
        }

    });

};

let handleExemptedFromFeesToggle = function () {

    $(document).on('change', '#exempted-from-fees', function () {

        let $wrapper = $('#student-subscription-fee-fields');

        if (!$wrapper.length) {

            return;

        }

        if ($(this).is(':checked')) {

            $wrapper.css('display', 'none');

        } else {

            $wrapper.css('display', '');

        }

    });

};
