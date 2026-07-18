<a href="{{ route('admin.organizations.show', $hash_id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i> </a>

@if (auth()->user()->hasPermission('update_organizations'))
    <a href="{{ route('admin.organizations.edit', $hash_id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i> </a>
@endif

@if (auth()->user()->hasPermission('delete_organizations'))
    <form action="{{ route('admin.organizations.destroy', $hash_id) }}" class="ajax-form" method="post" style="display: inline-block;">
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

