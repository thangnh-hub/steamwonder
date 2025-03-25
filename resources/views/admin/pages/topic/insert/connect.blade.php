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
                <div class="col-md-12">
                    <div class="list_answer ">
                        <label>Cặp đáp án:</label>
                        <div class="more_answer">
                            <div class="form-group row" style="align-items: center">
                                <div class="col-xs-4 col-md-2">
                                    <input type="text" class="form-control" name="json_params[answer][left][]"
                                        placeholder="Đáp án" value="">
                                </div>
                                <div class="col-xs-2 col-md-1 text-center">
                                    <img style="width: 100px; max-width: 80%;"
                                        src="{{ url('themes/admin/img/connect.png') }}" alt="">
                                </div>
                                <div class="col-xs-4 col-md-2">
                                    <input type="text" class="form-control" name="json_params[answer][right][]"
                                        placeholder="Đáp án" value="">
                                </div>
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

    function add_answer_choice(th) {
        var currentTime = $.now();
        var _html = `<div class="more_answer"><div class="form-group row">
                        <div class="col-xs-4 col-md-2">
                            <input type="text" class="form-control" name="json_params[answer][left][]"
                                placeholder="Đáp án" value="">
                        </div>
                        <div class="col-xs-2 col-md-1 text-center">
                            <img style="width: 100px; max-width: 70%;"
                                src="{{ url('themes/admin/img/connect.png') }}" alt="">
                        </div>
                        <div class="col-xs-4 col-md-2">
                            <input type="text" class="form-control" name="json_params[answer][right][]"
                                placeholder="Đáp án"
                                value="">
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <span onclick="_delete_answer(this)" class="input-group-btn">
                                <a class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Xóa </a>
                            </span>
                        </div>
                    </div></div>`;
        $(th).parents('.box-question-item').find('.list_answer').append(_html);
    }
</script>
