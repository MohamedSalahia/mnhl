$(function () {

    fetchGovernorates();

    fetchAreas();

});//end of document ready

let fetchGovernorates = () => {

    $(document).on('change', '#country-id', function () {

        if (this.value && this.value != 0) {

            let baseUrl = $('#country-id').data('governorates-base-url');
            let url     = baseUrl.replace('/0/', '/' + this.value + '/');

            let emptyValueText = $('#governorate-id').find('option:first').text();

            $.ajax({
                url: url,
                data: {
                    'empty_value_text': emptyValueText
                },
                cache: false,
                success: function (html) {

                    let $gov = $('#governorate-id');
                    $gov.find('option').not(':first').remove();
                    $gov.append(html);
                    $gov.prop('disabled', false).val('');
                    // Destroy & re-init Select2 so it picks up the new options
                    if ($gov.hasClass('select2-hidden-accessible')) {
                        $gov.select2('destroy');
                    }
                    window.initSelect2 && window.initSelect2();

                    let $area = $('#area-id');
                    $area.find('option').not(':first').remove();
                    $area.prop('disabled', true).val('');
                    if ($area.hasClass('select2-hidden-accessible')) {
                        $area.select2('destroy');
                    }
                    window.initSelect2 && window.initSelect2();

                },
            });//end of ajax call

        } else {

            $('#governorate-id').prop('disabled', true).val('').trigger('change');

            $('#area-id').prop('disabled', true).val('').trigger('change');

        } //end of else

    });

}

let fetchAreas = () => {

    $(document).on('change', '#governorate-id', function () {

        if (this.value && this.value != 0) {

            let baseUrl = $('#governorate-id').data('areas-base-url');
            let url     = baseUrl.replace('/0/', '/' + this.value + '/');

            $.ajax({
                url: url,
                cache: false,
                success: function (html) {

                    let $area = $('#area-id');
                    $area.find('option').not(':first').remove();
                    $area.append(html);
                    $area.prop('disabled', false).val('');
                    // Destroy & re-init Select2 so it picks up the new options
                    if ($area.hasClass('select2-hidden-accessible')) {
                        $area.select2('destroy');
                    }
                    window.initSelect2 && window.initSelect2();

                },
            });//end of ajax call

        } else {

            $('#area-id').prop('disabled', true).val('').trigger('change');

        } //end of else
    });

}
