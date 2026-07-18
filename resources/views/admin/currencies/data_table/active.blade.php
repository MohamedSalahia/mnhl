<div class="custom-control custom-control-primary custom-switch">
    <input type="checkbox" class="custom-control-input language-toggle-active"
           id="language-{{ $language->id }}" {{ $language->active ? 'checked' : '' }}
    />
    <label class="custom-control-label" for="language-{{ $language->id }}"></label>
    <form method="post" action="{{ route('admin.languages.toggle_active', $language->id) }}">
        @csrf
        @method('put')
    </form><!-- end of form -->
</div>