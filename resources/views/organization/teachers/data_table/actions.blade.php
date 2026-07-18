@php use App\Enums\OrganizationTeacherStatusEnum; @endphp

@if (request()->status == OrganizationTeacherStatusEnum::PENDING)

    <a href="{{ route('organization.teachers.show', $hash_id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i></a>

@else

    <a href="{{ route('organization.teachers.show', $hash_id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i></a>

    @if (auth()->user()->hasPermission('update_teachers', session('selected_branch')['id']))
        <a href="{{ route('organization.teachers.edit', $hash_id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i></a>
    @endif

    @if (auth()->user()->hasPermission('delete_teachers', session('selected_branch')['id']))
        <form action="{{ route('organization.teachers.destroy', $hash_id) }}" class="ajax-form" method="post" style="display: inline-block;">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-danger btn-sm delete"><i data-feather="trash-2"></i></button>
        </form>
    @endif

    @if (auth()->user()->hasRole(\App\Enums\UserTypeEnum::ORGANIZATION_SUPER_ADMIN) || auth()->user()->hasRole(\App\Enums\UserTypeEnum::SUPER_ADMIN))
        <form action="{{ route('organization.teachers.impersonate', $hash_id) }}" method="post" style="display: inline-block;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm" title="@lang('teachers.impersonate')"><i data-feather="log-in"></i></button>
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

