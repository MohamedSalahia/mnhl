<a href="{{ route('teacher.lessons.show', $id) }}" wire:navigate class="btn btn-primary btn-sm"><i data-feather="eye"></i></a>

<script>
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
</script>
