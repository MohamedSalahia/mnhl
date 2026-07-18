$(function () {

    handleToggleExaminer();

});//end of document ready

let handleToggleExaminer = () => {

    $(document).on('change', '.toggle-examiner', function () {

        let that = $(this);
        let url = that.data('url');
        let isChecked = that.is(':checked');

        $.ajax({
            url: url,
            method: 'POST',
            cache: false,
            success: function (response) {
                if (response.success) {
                    new Noty({
                        layout: 'topRight',
                        text: response.message,
                        timeout: 2000,
                        killer: true
                    }).show();

                    // Reload the details section to update the badge
                    // Get details URL from data attribute or from the active ajax-data button
                    let detailsUrl = that.data('details-url') || $('.ajax-data.active').data('url');

                    if (detailsUrl) {
                        $.ajax({
                            url: detailsUrl,
                            cache: false,
                            success: function (data) {
                                $('#ajax-data-wrapper').html(data);
                                if (feather) {
                                    feather.replace({
                                        width: 14,
                                        height: 14
                                    });
                                }
                            }
                        });
                    }
                }
            },
            error: function (xhr) {
                // Revert checkbox state on error
                that.prop('checked', !isChecked);

                let errorMessage = xhr.responseJSON?.message || 'An error occurred';
                new Noty({
                    layout: 'topRight',
                    text: errorMessage,
                    type: 'error',
                    timeout: 3000,
                    killer: true
                }).show();
            }
        });

    });

}
