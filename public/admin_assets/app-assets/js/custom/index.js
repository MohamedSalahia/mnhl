$(function () {

    //select 2
    $('.select2').select2({
        'width': '100%',
    });

    // $('.dob').flatpickr({
    //     dateFormat: 'Y-m-d',
    //     disableMobile: "true"

    // defaultDate: this.value,
    // disable: [
    //     function (date) {
    //         let currentDate = new Date();
    //         currentDate.setHours(0, 0, 0, 0)
    //         return (date >= currentDate);
    //     }
    // ]
    // });


    $('.date').flatpickr({
        dateFormat: 'Y-m-d',
        disableMobile: "true"
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //select all functionality
    $(document).on('change', '.record__select', function () {
        getSelectedRecords();
    });

    // used to select all records
    $(document).on('change', '#record__select-all', function () {

        $('.record__select').prop('checked', this.checked);
        getSelectedRecords();
    });

    function getSelectedRecords() {
        var recordIds = [];
        $.each($(".record__select:checked"), function () {
            recordIds.push($(this).val());
        });

        $('#record-ids').val(JSON.stringify(recordIds));

        recordIds.length > 0
            ? $('#bulk-delete').attr('disabled', false)
            : $('#bulk-delete').attr('disabled', true)

    }

    $(document).on('change', '.load-image', function (e) {

        var that = $(this);

        let reader = new FileReader();
        reader.onload = function () {
            that.parent().find('.loaded-image').attr('src', reader.result);
            that.parent().find('.loaded-image').css('display', 'block');
        }
        reader.readAsDataURL(e.target.files[0]);

    });

    // $(document).on('submit', '.modal-body form', function (e) {
    //     e.preventDefault();
    //
    //     $('.errors').hide();
    //     $('.errors').empty();
    //
    //     $('.loader-wrapper').css('display', 'flex');
    //     $('.modal-content-wrapper').css('display', 'none');
    //
    //     let url = $(this).attr('action');
    //     let data = new FormData(this);
    //
    //     let button = $('button[type="submit"]', this);
    //     let loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i>';
    //     let originalText = button.html();
    //
    //     button.html(loadingText);
    //
    //     $.ajax({
    //         url: url,
    //         data: data,
    //         method: "post",
    //         processData: false,
    //         contentType: false,
    //         success: function (response) {
    //
    //             $('.modal').modal('hide');
    //             $('.modal-body form')[0].reset();
    //             $('.datatable').DataTable().ajax.reload();
    //             $(".select2").val('').trigger('change');
    //
    //             new Noty({
    //                 layout: 'topRight',
    //                 type: 'alert',
    //                 text: response,
    //                 killer: true,
    //                 timeout: 2000,
    //             }).show();
    //
    //             button.html(originalText);
    //
    //             $('.loader-wrapper').css('display', 'none');
    //             $('.modal-content-wrapper').css('display', 'block');
    //
    //         },
    //         error: function (xhr, status, error) {
    //
    //             $('.errors').show();
    //             let errors = JSON.parse(xhr.responseText)['errors'];
    //
    //             $.each(errors, function (key, val) {
    //                 let html = '<p class="mb-0">' + val[0] + '</p>';
    //                 $('.errors').append(html);
    //             });
    //
    //             button.html(originalText);
    //         }
    //     });//end of ajax call
    //
    // });

    $(document).on('click', '.remote-data', function (e) {
        e.preventDefault();

        let url = $(this).attr('href');
        let title = $(this).data('title');

        $('.general-modal').modal('show');
        $('.general-modal .modal-title').empty().html(title);
        $('.general-modal .modal-body').empty().append('<div class="d-flex justify-content-center align-items-center"><div class="loader"></div></div>');

        $.ajax({
            url: url,
            success: function (html) {
                $('.general-modal .modal-body').empty().append(html);
            },

        });//end of ajax call

    })

    //modal close
    // $('.modal').on('hidden.bs.modal', function () {
    // $('.errors').empty();
    // $('.errors').hide();
    // $('.modal form')[0].reset();
    // $(".select2").val('').trigger('change');
    // $('.loaded-image').css('display', 'none');
    // $('.tab-content .tab-pane').removeClass('active show');
    // $('select').select2({'width': '100%'});
    // });

    //modal open
    // $('.modal').on('show.bs.modal', function (event) {
    //     $(this).find('.nav a:first').tab('show');
    //     $(this).find('.tab-content .nav a:first').tab('show');
    //
    //     $(this).find('.tab-content .tab-pane:first').addClass('show active');
    //     $(this).find('.tab-content .tab-content .tab-pane:first').addClass('show active');
    // });

    //change language activation
    $(document).on('change', '.language-active', function () {

        let url = $(this).data('url');

        $.ajax({
            url: url,
            method: 'post',
            cache: false,
            processData: false,
            success: function (response) {
                new Noty({
                    layout: 'topRight',
                    type: 'alert',
                    text: response,
                    killer: true,
                    timeout: 2000,
                }).show();

            },
        });//end of ajax call
    })

    select2WithAdd();

    let loading = $('meta[name="loading"]').attr('content');

    $(document).on('keyup', '.select2-search__field', function (e) {
        if (e.keyCode === 13 && $('.select2-add-new-btn').length > 0) {
            $('.select2-add-new-btn').trigger("click");
        }
    })

    $(document).on('click', '.select2-add-new-btn:last', function (e) {
        e.preventDefault();

        let select = $('.select2-container--below.select2-container--open, .select2-container--above.select2-container--open')
            .parent()
            .find('select');

        let value = select
            .data("select2").$container
            .find('.select2-search__field')
            .val();

        if (!value) {
            value = select.data('select2').$dropdown.find('.select2-search__field').val();
        }

        let url = select.data('url');

        let oldText = $(this).html();
        $(this).html(loading);

        $.ajax({
            url: url,
            method: 'post',
            data: {
                value: value,
            },
            cache: false,
            success: function (data) {

                select.append(`<option value="${data.id}" selected>${data.name}</option>`)
                select
                    .data("select2").$container
                    .find('.select2-search__field')
                    .val("");

                select
                    .data("select2").$container
                    .find('.select2-search__field')
                    .focus()

                select.select2('close');

                $(this).html(oldText);

            },
        });//end of ajax call
    });

    $('.gallery-images').each(function () { // the containers for all your galleries

        $(this).magnificPopup({
            delegate: 'a', // child items selector, by clicking on it popup will open
            type: 'image',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
            },
        });

    });

});//end of document ready

function select2WithAdd() {

    let addNew = $('meta[name="add-new"]').attr('content');

    $('.select2-with-add').select2({
        language: {
            noResults: function () {
                return `
                    <button style="width: 100%" type="button" class="btn btn-primary select2-add-new-btn">  + ${addNew}</button>
                `;
            }
        },

        escapeMarkup: function (markup) {
            return markup;
        }
    });

}

//
// function toggleDisabled() {
//
//     $('form div').each(function () {
//         if ($(this).is(':hidden')) {
//             $(this).children().find('input').attr('disabled', true)
//         } else {
//             $(this).children().find('input').attr('disabled', false)
//         }
//
//     });
//
// }//end of checkInput