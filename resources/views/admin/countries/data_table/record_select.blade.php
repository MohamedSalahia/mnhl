{{--<input type="checkbox" id="record-{{ $id }}" value="{{ $id }}" class="record__select"/>--}}
{{--<label for="record-{{ $id }}"></label>--}}

<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input record__select" id="record-{{ $id }}" value="{{ $id }}">
    <label class="custom-control-label" for="record-{{ $id }}"></label>
</div>