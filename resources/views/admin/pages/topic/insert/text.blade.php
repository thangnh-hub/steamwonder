<div class="box box-primary box-question-item">
    <div class="box-body">
        <div class="tab_offline">
            <div class="tab-pane active">
                <div class="col-md-12 textarea-question">
                    <div class="form-group">
                        <label>@lang('Câu hỏi')</label>
                        <textarea id="question_textarea_insert" required class="form-control input-question" name="question" cols="30" rows="1" placeholder="Nhập câu hỏi..."></textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('Số điểm')</label>
                        <input type="number" name="point" class="form-control" value="">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="list_answer ">
                        <label>Đáp án:</label>
                        <div class="d-flex-wap more_answer">
                            <div class="col-md-6 pl-0">
                                <div class="form-group">
                                    <input type="text" name="json_params[answer][1][value]" class="form-control" placeholder="Đáp án" value="">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input name="json_params[answer][1][boolean]" type="checkbox" value="0" onchange="updateCheckboxValue(this)" >
                            </div>
                        </div>
                    </div>

                    <button onclick="add_answer_choice(this)" class="form-group btn btn-primary mb-2" type="button"><i
                            class="fa fa-plus"></i>
                        Thêm câu trả lời</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace('question_textarea_insert', ck_options);
    function _delete_answer(th) {
        $(th).parents('.more_answer').fadeOut(500, function() {
            $(th).parents('.more_answer').remove();
        });
    }

    function updateCheckboxValue(checkbox) {
        if (checkbox.checked) {
            checkbox.value = 1;
        } else {
            checkbox.value = 0;
        }
    }
    function add_answer_choice(th){
        var currentTime = $.now();
        var _html = `<div class="d-flex-wap more_answer">
                        <div class="col-md-6 pl-0">
                            <div class="form-group input-group">
                                <input name="json_params[answer][` + currentTime + `][value]" type="text" class="form-control" placeholder="Đáp án" value="">
                                <span onclick="_delete_answer(this)" class="input-group-btn">
                                    <a class="btn btn-danger">
                                        <i class="fa fa-trash"></i> Xóa </a>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox"  name="json_params[answer][` + currentTime + `][boolean]" value="0"  onchange="updateCheckboxValue(this) ">
                        </div>
                    </div>`;
        $(th).parents('.box-question-item').find('.list_answer').append(_html);
    }
</script>
