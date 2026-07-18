@php
    $branchId = session('selected_branch')['id'] ?? null;
@endphp

@if ($branchId !== null && auth()->user()->hasPermission('update_installments', $branchId))
    <button type="button" class="btn btn-warning btn-sm ajax-modal mr-1"
            data-url="{{ route('organization.installments.edit', $installment) }}"
            data-modal-title="@lang('installments.edit_installment')"
            data-modal-size-class="modal-md">
        <i data-feather="edit"></i>
    </button>
@endif

@if ($branchId !== null && auth()->user()->hasPermission('delete_installments', $branchId))
    <form action="{{ route('organization.installments.destroy', $installment) }}" class="ajax-form d-inline-block" method="post">
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
