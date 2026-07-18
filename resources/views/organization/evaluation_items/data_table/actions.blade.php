@if (auth()->user()->hasPermission('update_evaluation_items', session('selected_branch')['id']))
    <a href="#" class="btn btn-warning btn-sm ajax-modal"
       data-url="{{ route('organization.evaluation_items.edit', $id) }}"
       data-modal-title="@lang('site.edit') @lang('evaluation_items.evaluation_item')"
    >
        <i data-feather="edit"></i>
    </a>
@endif

@if (auth()->user()->hasPermission('delete_evaluation_items', session('selected_branch')['id']))
    <form action="{{ route('organization.evaluation_items.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm delete"><i data-feather="trash-2"></i></button>
    </form>
@endif

<script>
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
</script>

