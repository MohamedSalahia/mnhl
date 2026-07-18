@if (auth()->user()->hasPermission('read_classrooms', session('selected_branch')['id']))
    <a href="{{ route('organization.classrooms.show', $id) }}" wire:navigate class="btn btn-primary btn-sm" title="@lang('site.show')"><i data-feather="eye"></i></a>
    <a href="{{ route('organization.classrooms.show', $id) }}?tab=students" wire:navigate class="btn btn-info btn-sm" title="@lang('classrooms.students_list')"><i data-feather="users"></i></a>
@endif

@if (auth()->user()->hasPermission('update_classrooms', session('selected_branch')['id']))
    <a href="{{ route('organization.classrooms.edit', $id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i></a>
@endif

@if (auth()->user()->hasPermission('delete_classrooms', session('selected_branch')['id']))
    <form action="{{ route('organization.classrooms.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
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
