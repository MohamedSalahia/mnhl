@foreach ($governorates as $governorate)
    <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
@endforeach


