

<div class="tab-content connect">
    <div class="tab-pane active ">
        <strong>Cặp đáp án:</strong>
        <div class="d-flex-wap list_answer_connect">
            @isset($quiz->json_params->answer->left)
                @foreach ($quiz->json_params->answer->left as $key => $val)
                    <div class="col-md-12 more_answer">
                        <div class="form-group d-flex-wap">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="json_params[answer][left][]"
                                    placeholder="Đáp án" value="{{ $val ?? '' }}">
                            </div>
                            <div class="col-md-2 text-center">
                                <img style="width: 100px; max-width: 80%;" src="{{ url('themes/admin/img/connect.png') }}"
                                    alt="">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="json_params[answer][right][]"
                                    placeholder="Đáp án" value="{{ $quiz->json_params->answer->right[$key] ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <span onclick="delete_item(this)" class="input-group-btn">
                                    <a class="btn btn-danger">
                                        <i class="fa fa-trash"></i> Xóa </a>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12 more_answer">
                    <div class="form-group d-flex-wap">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="json_params[answer][left][]"
                                placeholder="Đáp án" value="">
                        </div>
                        <div class="col-md-2 text-center">
                            <img style="width: 100px; max-width: 80%;" src="{{ url('themes/admin/img/connect.png') }}"
                                alt="">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="json_params[answer][right][]"
                                placeholder="Đáp án" value="">
                        </div>
                        <div class="col-md-3">
                            <span onclick="delete_item(this)" class="input-group-btn">
                                <a class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Xóa </a>
                            </span>
                        </div>
                    </div>
                </div>

            @endisset
        </div>
    </div>

    <button class="form-group btn btn-primary mb-2 add_answer_connect" type="button"><i class="fa fa-plus"></i>
        Thêm cặp đáp án</button>
</div>
