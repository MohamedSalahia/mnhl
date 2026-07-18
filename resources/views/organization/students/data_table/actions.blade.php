@php use App\Enums\OrganizationStudentStatusEnum; @endphp
@if (request()->status == OrganizationStudentStatusEnum::PENDING)

    <a href="{{ route('organization.students.show', $hash_id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i></a>

@else

    @if (auth()->user()->hasPermission('read_students', session('selected_branch')['id']))
        <a href="{{ route('organization.students.show', $hash_id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i></a>
    @endif

    @if (auth()->user()->hasPermission('update_students', session('selected_branch')['id']))
        <a href="{{ route('organization.students.edit', $hash_id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i></a>
    @endif

    @if (auth()->user()->hasPermission('delete_students', session('selected_branch')['id']))
        <form action="{{ route('organization.students.destroy', $hash_id) }}" class="ajax-form" method="post" style="display: inline-block;">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger btn-sm delete"><i data-feather="trash-2"></i></button>
        </form>
    @endif

@endif
<script>
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
</script>
