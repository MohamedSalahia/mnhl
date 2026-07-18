<div class="row slide-lis" data-url="{{ route('organization.projects.reorder') }}">

    @forelse ($projects as $project)

        <div class="col-md-4 slide-item mb-1" id="{{ $project->id }}">

            <div class="card">

                <div class="card-body text-center">

                    <div class="d-flex justify-content-between align-items-start" style="overflow: hidden;">
                        <div class="flex-grow-1 text-center">
                            <h4 style="margin-bottom: 3px; font-size: 1rem; padding: 0.5rem; border-radius: 0.375rem;">{{ Str::limit($project->name, 30) }}</h4>
                        </div>
                    </div>
                </div>

            </div><!-- end of card -->

        </div><!-- end of col -->

    @empty

        <div class="col-md-12">
            <h4>@lang('site.no_data_found')</h4>
        </div>

    @endforelse

</div><!-- end of row -->

<style>
    .ui-sortable-placeholder,
    .slide-item-placeholder {
        visibility: visible !important;
        display: block !important;
        border: 2px dashed #6c757d !important;
        background-color: #f8f8f8 !important;
        opacity: 0.5 !important;
        border-radius: 0.25rem;
        height: auto !important;
        min-height: 100px;
        margin-bottom: 0.5rem;
    }

    .ui-sortable-placeholder .card,
    .slide-item-placeholder .card {
        border: 2px dashed #6c757d !important;
        background-color: #f8f8f8 !important;
        opacity: 0.5 !important;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100px;
    }

    .ui-sortable-placeholder .card-body,
    .slide-item-placeholder .card-body {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .ui-sortable-placeholder .card-body::after,
    .slide-item-placeholder .card-body::after {
        content: "Drop here";
        color: #6c757d;
        font-weight: 500;
        font-size: 1rem;
    }

    .slide-item .card {
        height: 100%;
    }

    .slide-item .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 1rem;
    }

    .slide-item img {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease-in-out;
    }

    .slide-item img:hover {
        transform: scale(1.02);
    }
</style>

<script>
    $(function () {
        $('.slide-lis').each(function () {
            let $element = $(this);

            // Check if sortable is already initialized
            if ($element.hasClass('ui-sortable')) {
                $element.sortable('destroy');
            }

            $element.sortable({
                items: "> .slide-item",
                cursor: "move",
                tolerance: 'pointer',
                helper: 'clone',
                opacity: 0.5,
                revert: 50,
                forceHelperSize: true,
                placeholder: 'slide-item-placeholder',
                start: function (event, ui) {
                    ui.placeholder.height(ui.item.height());
                    ui.placeholder.addClass('col-md-4 mb-1');
                    ui.placeholder.css({
                        'visibility': 'visible',
                        'display': 'block',
                        'opacity': '0.5'
                    });
                },

                update: function (event, ui) {

                    let url = $(this).data('url');
                    let ids = $(this).sortable('toArray');

                    let data = {
                        'ids': ids
                    }

                    $.ajax({
                        url: url,
                        method: 'post',
                        data: data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {

                            new Noty({
                                layout: 'topRight',
                                text: data.success_message,
                                timeout: 2000,
                                killer: true
                            }).show();

                            if ($('.datatable').length) {
                                $('.datatable').DataTable().ajax.reload();
                            }//end of if

                        },
                    })
                }
            }).disableSelection();
        });
    });
</script>
