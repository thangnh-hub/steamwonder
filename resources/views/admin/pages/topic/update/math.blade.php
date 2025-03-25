<div class="box box-primary box-question-item">
    <div class="box-body">
        <div class="tab_offline">
            <div class="tab-pane active">
                <div class="col-md-12 textarea-question">
                    <div class="form-group">
                        <label>@lang('Câu hỏi')</label>
                        <textarea id="question_textarea_update" required class="form-control input-question" name="question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question->question }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('Số điểm')</label>
                        <input type="number" name="point" class="form-control" value="{{ $question->point }}">
                    </div>
                </div>
                <div class="col-md-12">
                    @isset($question->json_params->answer)
                    <div class="tab-content fill ">
                        <div class="tab-pane active ">
                            <label>Đáp án:</label>
                            <div class="d-flex-wap list_answer_fill">
                                <div class="col-md-3 more_answer pl-0">
                                    <div class="form-group">
                                        <input type="text" class="form-control"
                                            name="json_params[answer]"
                                            value="{{ $question->json_params->answer }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
<script>
 CKEDITOR.replace('question_textarea_update', ck_options);
</script>
