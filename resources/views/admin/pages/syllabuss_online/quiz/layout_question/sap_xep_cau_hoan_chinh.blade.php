
<div class="tab-content order">
    <div class="tab-pane active ">
        <strong>Đáp án:</strong>
        <div class="d-flex-wap list_answer">
            @isset($quiz->json_params->answer)
                @foreach ($quiz->json_params->answer as $k => $answer)
                    <div class="col-md-3 more_answer">
                        <div class="form-group input-group">
                            <input type="text" class="form-control" name="json_params[answer][]" placeholder="Đáp án"
                                value="{{ $answer ?? '' }}">
                            <span onclick="delete_item(this)" class="input-group-btn">
                                <a class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Xóa </a>
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-3 more_answer">
                    <div class="form-group input-group">
                        <input type="text" class="form-control" name="json_params[answer][]" placeholder="Đáp án"
                            value="">
                        <span onclick="delete_item(this)" class="input-group-btn">
                            <a class="btn btn-danger">
                                <i class="fa fa-trash"></i> Xóa </a>
                        </span>
                    </div>
                </div>
            @endisset
        </div>
    </div>

    <button class="form-group btn btn-primary mb-2 add_answer" type="button"><i class="fa fa-plus"></i>
        Thêm câu trả lời</button>
</div>
