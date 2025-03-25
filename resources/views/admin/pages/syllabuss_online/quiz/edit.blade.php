<div class="row lesson-item">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="nav-tabs-custom">
                <div class="tab_offline">
                    <div class="tab-pane active">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Hình thức câu hỏi</label>
                                <select disabled name="type"class="form-control select2 type_change">
                                    @foreach ($type_quiz as $type => $value)
                                        <option {{ isset($quiz->type) && $quiz->type == $type ? 'selected' : '' }}
                                            value="{{ $type }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if ($quiz->type != 'order')
                            <div class="col-md-12 textarea-question">
                                <div class="form-group">
                                    <label>@lang('Câu hỏi')</label>
                                    <textarea class="form-control" id="question" name="question" cols="30" rows="1">{{ $quiz->question ?? '' }}</textarea>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            @switch($quiz->type)
                                @case('choice')
                                    <div class="tab-content choice">
                                        <div class="tab-pane active list_answer ">
                                            <strong>Đáp án:</strong>
                                            @isset($quiz->json_params->answer)
                                                @foreach ($quiz->json_params->answer as $k => $answer)
                                                    <div class="d-flex-wap more_answer">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                    name="json_params[answer][{{ $k }}][value]"
                                                                    placeholder="Đáp án" value="{{ $answer->value ?? '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <input type="checkbox"
                                                                name="json_params[answer][{{ $k }}][boolean]"
                                                                value="{{ isset($answer->boolean) && $answer->boolean == 1 ? 1 : 0 }}"
                                                                {{ isset($answer->boolean) && $answer->boolean == 1 ? 'checked' : '' }}
                                                                onchange="updateCheckboxValue(this)">
                                                        </div>

                                                        <button onclick="_delete_answer(this)"
                                                            type="button"class="btn btn-sm btn-danger">Xóa</button>
                                                    </div>
                                                @endforeach
                                            @endisset
                                        </div>

                                        <button class="form-group btn btn-primary mb-2 add_answer_choice" type="button"><i
                                                class="fa fa-plus"></i>
                                            Thêm câu trả lời</button>
                                    </div>
                                @break

                                @case('fill')
                                    <div class="tab-content fill hidden">
                                        <div class="tab-pane active ">
                                            <strong>Đáp án:</strong>
                                            <div class="d-flex-wap list_answer_fill">
                                                @isset($quiz->json_params->answer)
                                                    @foreach ($quiz->json_params->answer as $k => $answer)
                                                        <div class="col-md-3 more_answer">
                                                            <div class="form-group input-group">
                                                                <input type="text" class="form-control"
                                                                    name="json_params[answer][]" placeholder="Đáp án"
                                                                    value="{{ $answer }}">
                                                                <span onclick="delete_item(this)" class="input-group-btn">
                                                                    <a class="btn btn-danger">
                                                                        <i class="fa fa-trash"></i> Xóa </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        </div>

                                        <button class="form-group btn btn-primary mb-2 add_answer_fill" type="button"><i
                                                class="fa fa-plus"></i>
                                            Thêm câu trả lời</button>
                                    </div>
                                @break

                                @case('order')
                                    <div class="tab-content order hidden">
                                        <div class="tab-pane active ">
                                            <strong>Đáp án:</strong>
                                            <div class="d-flex-wap list_answer_order">
                                                @isset($quiz->json_params->answer)
                                                    @foreach ($quiz->json_params->answer as $k => $answer)
                                                        <div class="col-md-3 more_answer">
                                                            <div class="form-group input-group">
                                                                <input type="text" class="form-control"
                                                                    name="json_params[answer][]" placeholder="Đáp án"
                                                                    value="{{ $answer }}">
                                                                <span onclick="delete_item(this)" class="input-group-btn">
                                                                    <a class="btn btn-danger">
                                                                        <i class="fa fa-trash"></i> Xóa </a>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        </div>

                                        <button class="form-group btn btn-primary mb-2 add_answer_order" type="button"><i
                                                class="fa fa-plus"></i>
                                            Thêm câu trả lời</button>
                                    </div>
                                @break

                                @case('connect')
                                    <div class="tab-content connect hidden">
                                        <div class="tab-pane active ">
                                            <strong>Cặp đáp án:</strong>
                                            <div class="d-flex-wap list_answer_connect">
                                                @isset($quiz->json_params->answer->left)
                                                    @foreach ($quiz->json_params->answer->left as $key => $val)
                                                        <div class="col-md-12 more_answer">
                                                            <div class="form-group d-flex-wap">
                                                                <div class="col-md-3">
                                                                    <input type="text" class="form-control"
                                                                        name="json_params[answer][left][]" placeholder="Đáp án"
                                                                        value="{{ $val ?? '' }}">
                                                                </div>
                                                                <div class="col-md-2 text-center">
                                                                    <img style="width: 100px; max-width: 80%;"
                                                                        src="{{ url('themes/admin/img/connect.png') }}"
                                                                        alt="">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="text" class="form-control"
                                                                        name="json_params[answer][right][]" placeholder="Đáp án"
                                                                        value="{{ $quiz->json_params->answer->right[$key] ?? '' }}">
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
                                                @endisset
                                            </div>
                                        </div>

                                        <button class="form-group btn btn-primary mb-2 add_answer_connect" type="button"><i
                                                class="fa fa-plus"></i>
                                            Thêm cặp đáp án</button>
                                    </div>
                                @break

                                @case('speak')
                                    <div class="tab-content speak hidden">
                                        <div class="tab-pane active ">
                                            <div class="d-flex-wap list_answer_connect">
                                                @isset($quiz->json_params->answer)
                                                    @foreach ($quiz->json_params->answer as $val)
                                                        <div class="col-md-12 more_answer">
                                                            <div class="form-group">
                                                                <label>@lang('File âm thanh')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn">
                                                                        <a data-input="files_audio" class="btn btn-primary file">
                                                                            <i class="fa fa-picture-o"></i> @lang('Select')
                                                                        </a>
                                                                    </span>
                                                                    <input id="files_audio" class="form-control" type="text"
                                                                        name="json_params[files_audio] "
                                                                        placeholder="@lang('Files Audio')" value="{{$quiz->json_params->files_audio}}">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>@lang('Đáp án')</label>
                                                                <input type="text" class="form-control"
                                                                    name="json_params[answer][]" placeholder="Đáp án"
                                                                    value="{{$val}}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                @break

                                @default
                                    <div class="tab-content answer">
                                        <div class="tab-pane active list_answer ">
                                            <strong>Đáp án:</strong>
                                            @isset($quiz->json_params->answer)
                                                @foreach ($quiz->json_params->answer as $answer)
                                                    <div class="d-flex-wap more_answer">
                                                        <div class="col-xs-12 col-md-6">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control"
                                                                    name="json_params[answer][]" placeholder="Đáp án"
                                                                    value="{{ $answer ?? '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endisset
                                        </div>
                                    </div>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
