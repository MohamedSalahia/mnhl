@if (auth()->user()->hasPermission('update_examiners', session('selected_branch')['id']))
    @if ($hasTeacherRole ?? false)
        <button type="button" class="btn btn-warning btn-sm" disabled title="{{ __('examiners.has_teacher_role') }}"><i data-feather="edit"></i></button>
    @else
        <a href="{{ route('organization.examiners.edit', $id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i></a>
    @endif
@endif

@if (auth()->user()->hasPermission('delete_examiners', session('selected_branch')['id']))
    <form action="{{ route('organization.examiners.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
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
