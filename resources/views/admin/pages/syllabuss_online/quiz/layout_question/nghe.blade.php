<div class="col-md-12">
    <div class="form-group">
        <label>@lang('File âm thanh')</label>
        <div class="input-group">
            <span class="input-group-btn">
                <a data-input="files_audio" class="btn btn-primary file">
                    <i class="fa fa-picture-o"></i> @lang('Select')
                </a>
            </span>
            <input id="files_audio" class="form-control" type="text" name="json_params[files_audio] "
                placeholder="@lang('Files Audio')" value="{{ $quiz->json_params->files_audio ?? ($quiz_parent->json_params->files_audio??'')}}">
        </div>
    </div>
</div>
