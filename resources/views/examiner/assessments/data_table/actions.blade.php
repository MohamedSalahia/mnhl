<a href="{{ route('examiner.assessments.show', $id) }}" wire:navigate class="btn btn-primary btn-sm" title="@lang('site.show')">
    <i data-feather="eye"></i>
</a>

<script>
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
</script>
