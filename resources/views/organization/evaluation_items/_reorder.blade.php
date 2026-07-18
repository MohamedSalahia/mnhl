<div class="row slide-lis" data-url="{{ route('organization.evaluation_items.reorder') }}">

    @forelse ($evaluationItems as $evaluationItem)

        <div class="col-md-3 slide-item mb-2" id="{{ $evaluationItem->id }}">

            <div class="card">

                <div class="card-body text-center">

                    <div class="d-flex justify-content-between align-items-start" style="overflow: hidden;">
                        <div class="flex-grow-1 text-center">
                            <h4 style="margin-bottom: 3px; font-size: 1rem; @if($evaluationItem->background_color) background-color: {{ $evaluationItem->background_color }}; @endif @if($evaluationItem->text_color) color: {{ $evaluationItem->text_color }}; @endif padding: 0.5rem; border-radius: 0.375rem;">{{ Str::limit($evaluationItem->name, 30) }}</h4>
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

@push('styles')
    <style>
        .slide-item-placeholder {
            border: 2px dashed #6c757d;
            background-color: #f8f8f8;
            opacity: 0.5;
            height: 220px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.25rem;
        }

        .slide-item-placeholder::after {
            content: "Drop here";
            color: #6c757d;
            font-weight: 500;
        }

        .slide-item .card {
            height: 100%;
        }

        .slide-item .card-body {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
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
@endpush

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
                placeholder: 'slide-item slide-item-placeholder col-md-3',

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
