@foreach ($levels as $level)
    <option value="{{ $level->id }}" data-from-page="{{ $level->from_page }}" data-to-page="{{ $level->to_page }}">{{ $level->name }}</option>
@endforeach
