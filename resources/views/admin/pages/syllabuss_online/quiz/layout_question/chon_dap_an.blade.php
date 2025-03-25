
<div class="tab-content">
    <div class="tab-pane active list_answer ">
        <strong>Đáp án:</strong>
        @isset($quiz->json_params->answer)
            @foreach ($quiz->json_params->answer as $k => $answer)
                <div class="d-flex-wap more_answer">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control" name="json_params[answer][{{ $k }}][value]"
                                placeholder="Đáp án" value="{{ $answer->value ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <input type="checkbox" class="check_answer" name="json_params[answer][{{ $k }}][boolean]"
                            value="{{ isset($answer->boolean) && $answer->boolean == 1 ? 1 : 0 }}"
                            {{ isset($answer->boolean) && $answer->boolean == 1 ? 'checked' : '' }}
                            onchange="updateCheckboxValue(this)">
                    </div>

                    <button onclick="_delete_answer(this)" type="button"class="btn btn-sm btn-danger">Xóa</button>
                </div>
            @endforeach
        @else
            <div class="d-flex-wap more_answer">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="text" class="form-control" name="json_params[answer][1][value]" placeholder="Đáp án"
                            value="">
                    </div>
                </div>
                <div class="col-md-1">
                    <input type="checkbox" class="check_answer" name="json_params[answer][1][boolean]" value="0"
                        onchange="updateCheckboxValue(this)">
                </div>
            </div>
        @endisset

    </div>
    <button class="form-group btn btn-primary mb-2 add_answer_choice" type="button"><i class="fa fa-plus"></i>
        @lang('Thêm câu trả lời')</button>
</div>
