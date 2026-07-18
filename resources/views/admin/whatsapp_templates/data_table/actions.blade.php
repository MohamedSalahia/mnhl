<a href="{{ route('admin.whatsapp_templates.edit', $id) }}" wire:navigate class="btn btn-warning btn-sm"><i data-feather="edit"></i></a>

<form action="{{ route('admin.whatsapp_templates.destroy', $id) }}" class="ajax-form" method="post" style="display: inline-block;">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-danger btn-sm delete"><i data-feather="trash-2"></i></button>
</form>

<script>
    if (feather) { feather.replace({ width: 14, height: 14 }); }
</script>
