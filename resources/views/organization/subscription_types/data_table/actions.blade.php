@if (auth()->user()->hasPermission('update_subscription_types', session('selected_branch')['id']))
    <a href="{{ route('organization.subscription_types.edit', $id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i> </a>
@endif

@if (auth()->user()->hasPermission('delete_subscription_types', session('selected_branch')['id']))
    <form action="{{ route('organization.subscription_types.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
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
