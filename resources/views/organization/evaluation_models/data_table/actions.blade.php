@if (auth()->user()->hasPermission('read_evaluation_models', session('selected_branch')['id']))
    <a href="{{ route('organization.evaluation_models.show', $id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i> </a>
@endif

@if (auth()->user()->hasPermission('update_evaluation_models', session('selected_branch')['id']))
    <a href="{{ route('organization.evaluation_models.basic_information.edit', $id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i> </a>
@endif

@if (auth()->user()->hasPermission('delete_evaluation_models', session('selected_branch')['id']))
    <form action="{{ route('organization.evaluation_models.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
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

