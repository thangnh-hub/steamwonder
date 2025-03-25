<div class="box box-primary box-question-item">
    <div class="box-body">
        <div class="tab_offline">
            <div class="tab-pane active">
                <div class="col-md-12 textarea-question">
                    <div class="form-group">
                        <label>@lang('Câu hỏi')</label>
                        <textarea id="question_textarea_insert" class="form-control input-question" name="question" cols="30" rows="1"
                            placeholder="Nhập câu hỏi..."></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('Số điểm')</label>
                        <input type="number" name="point" class="form-control" value="">
                    </div>
                </div>
                <div class="col-md-3 pl-0">
                    <div class="form-group">
                        <label>Đáp án:</label>
                        <input type="text" name="json_params[answer][]" class="form-control" placeholder="Đáp án"
                            value="">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace('question_textarea_insert', ck_options);
</script>
