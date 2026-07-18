<form method="post" action="{{ route('organization.evaluation_items.update', $evaluationItem->id) }}" class="ajax-form">
    @csrf
    @method('put')
    
    {{--name--}}
    <div class="form-group">
        <label>@lang('evaluation_items.name')<span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" value="{{ $evaluationItem->name }}" autofocus required>
    </div>


    {{--evaluation_model_id--}}
    <div class="form-group">
        <label>@lang('evaluation_models.evaluation_model')<span class="text-danger">*</span></label>
        <select name="evaluation_model_id" class="form-control select2" required>
            <option value="">@lang('site.choose') @lang('evaluation_models.evaluation_model')</option>
            @foreach ($evaluationModels as $evaluationModel)
                <option value="{{ $evaluationModel->id }}" {{ $evaluationModel->id == $evaluationItem->evaluation_model_id ? 'selected' : '' }}>{{ $evaluationModel->name }}</option>
            @endforeach
        </select>
    </div>

    {{--background_color--}}
    <div class="form-group">
        <label>@lang('evaluation_items.background_color')<span class="text-danger">*</span></label>
        <input type="color" name="background_color" class="form-control" value="{{ $evaluationItem->background_color }}" required>
    </div>


    {{--text_color--}}
    <div class="form-group">
        <label>@lang('evaluation_items.text_color')<span class="text-danger">*</span></label>
        <input type="color" name="text_color" class="form-control" value="{{ $evaluationItem->text_color }}" required>
    </div>


    {{--pass--}}
    <div class="form-group">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="pass" id="pass" value="1" {{ $evaluationItem->pass ? 'checked' : '' }}>
            <label class="custom-control-label" for="pass">@lang('evaluation_items.pass')</label>
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary"><i data-feather="edit"></i> @lang('site.update')</button>
    </div>

</form><!-- end of form -->
