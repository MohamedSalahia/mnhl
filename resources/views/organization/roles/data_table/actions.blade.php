@if (auth()->user()->hasPermission('update_roles'))
    <a href="{{ route('organization.roles.edit', $id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i> </a>
@endif

@if (auth()->user()->hasPermission('delete_roles'))
    <form action="{{ route('organization.roles.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
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
