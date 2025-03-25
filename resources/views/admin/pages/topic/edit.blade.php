@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .mb-10 {
            margin-bottom: 10px
        }

        .mt-0 {
            margin-top: 0px !important;
        }

        .pl-0 {
            padding-left: 0px !important
        }

        textarea {
            resize: none;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->

    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Thêm mới đề')</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div id="alert-config"></div>

        @if (session('errorMessage'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('errorMessage') }}
            </div>
        @endif
        @if (session('successMessage'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('successMessage') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Đề bài')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Lưu đề')
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Title') <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="name"
                                                    placeholder="@lang('Title')"
                                                    value="{{ $detail->name ?? old('name') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Số câu hỏi sẽ thi') <small class="text-red">*</small></label>
                                                <input type="number" class="form-control" name="question_exam"
                                                    placeholder="@lang('Số câu hỏi sẽ thi')"
                                                    value="{{ $detail->question_exam ?? old('question_exam') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Loại đề') <small class="text-red">*</small></label>
                                                <select disabled name="type" class=" form-control select2 topic_type">
                                                    @foreach ($type as $key => $val)
                                                        <option value="{{ $key }}"
                                                            {{ isset($detail->type) && $detail->type == $key ? 'selected' : '' }}>
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Status')</label>
                                                <select name="status" class=" form-control select2">
                                                    @foreach ($status as $key => $val)
                                                        <option value="{{ $key }}"
                                                            {{ isset($detail->status) && $detail->status == $val ? 'selected' : '' }}>
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>@lang('Content')</label>
                                                    <textarea name="content" class="form-control" id="content_vi">{{ $detail->content ?? old('content') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <button data-toggle="modal" data-target=".bd-modal-lg" class="btn btn-primary mb-2 add_question_topic"
            style="margin-bottom: 10px;" type="button"><i class="fa fa-plus"></i>
            Thêm câu hỏi và đáp án cho đề này</button>

        @if ($list_question->count() > 0)
            @foreach ($list_question as $question_item)
                <form action="{{ route('student_test.update', $question_item->id) }}" onsubmit="return false"
                    method="POST" class="form-update-question">
                    @csrf
                    <input type="hidden" name="ajax_update" value="true">
                    @switch($detail->type)
                        @case('text')
                            <div class="box box-primary box-question-item" data-type ="{{ $detail->type }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button data-toggle="modal" data-target=".bd-modal-lg-update" type="button"
                                        data-question-id="{{ $question_item->id }}"
                                        data-url-update="{{ route('student_test.update', $question_item->id) }}"
                                        class="btn btn-sm btn-primary show_question_topic">Chỉnh sửa câu hỏi</button> --}}
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa câu
                                            hỏi</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" name="question" required
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                @isset($question_item->json_params->answer)
                                                    <div class="list_answer">
                                                        <label>Đáp án:</label>
                                                        @foreach ($question_item->json_params->answer as $k => $answer)
                                                            <div class="d-flex-wap more_answer">
                                                                <div class="col-md-6 pl-0">
                                                                    <div
                                                                        class="form-group {{ $loop->index > 0 ? 'input-group' : '' }}">
                                                                        <input type="text"
                                                                            name="json_params[answer][{{ $k }}][value]"
                                                                            class="form-control" placeholder="Đáp án"
                                                                            value="{{ $answer->value ?? '' }}">
                                                                        @if ($loop->index > 0)
                                                                            <span onclick="_delete_answer(this)"
                                                                                class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <input type="checkbox"
                                                                        name="json_params[answer][{{ $k }}][boolean]"
                                                                        value="{{ isset($answer->boolean) && $answer->boolean == 1 ? 1 : 0 }}"{{ isset($answer->boolean) && $answer->boolean == 1 ? 'checked' : '' }}
                                                                        onchange="updateCheckboxValue(this)">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endisset
                                                <button onclick="add_answer_choice(this)" class="form-group btn btn-primary mb-2"
                                                    type="button"><i class="fa fa-plus"></i>
                                                    Thêm câu trả lời</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('math')
                            <div class="box box-primary box-question-item">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button data-toggle="modal" data-target=".bd-modal-lg-update" type="button"
                                        data-question-id="{{ $question_item->id }}"
                                        data-url-update="{{ route('student_test.update', $question_item->id) }}"
                                        class="btn btn-sm btn-primary show_question_topic">Chỉnh sửa đáp án</button> --}}
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" name="question" required
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            @isset($question_item->json_params->answer)
                                                <div class="col-md-12">
                                                    <div class="tab-content fill ">
                                                        <div class="tab-pane active ">
                                                            <label>Đáp án:</label>
                                                            <div class="d-flex-wap list_answer_fill">
                                                                <div class="col-md-3 more_answer pl-0">
                                                                    <div class="form-group ">
                                                                        <input type="text" class="form-control"
                                                                            name="json_params[answer]"
                                                                            value="{{ $question_item->json_params->answer }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('eye_training')
                            <div class="box box-primary box-question-item">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button data-toggle="modal" data-target=".bd-modal-lg-update" type="button"
                                        data-question-id="{{ $question_item->id }}"
                                        data-url-update="{{ route('student_test.update', $question_item->id) }}"
                                        class="btn btn-sm btn-primary show_question_topic">Chỉnh sửa đáp án</button> --}}
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" required name="question"
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            @isset($question_item->json_params->answer)
                                                <div class="col-md-12">
                                                    <div class="tab-content fill ">
                                                        <div class="tab-pane active ">
                                                            <label>Đáp án:</label>
                                                            <div class="d-flex-wap list_answer_fill">
                                                                <div class="col-md-3 more_answer pl-0">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"
                                                                            name="json_params[answer]"
                                                                            value="{{ $question_item->json_params->answer }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('logic')
                            <div class="box box-primary box-question-item">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button data-toggle="modal" data-target=".bd-modal-lg-update" type="button"
                                        data-question-id="{{ $question_item->id }}"
                                        data-url-update="{{ route('student_test.update', $question_item->id) }}"
                                        class="btn btn-sm btn-primary show_question_topic">Chỉnh sửa đáp án</button> --}}
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" required name="question"
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            @isset($question_item->json_params->answer)
                                                <div class="col-md-12">
                                                    <div class="tab-content fill ">
                                                        <div class="tab-pane active ">
                                                            <label>Đáp án (chuỗi gồm 10 ký tự):</label>
                                                            <div class="d-flex-wap list_answer_fill">
                                                                <div class="col-md-3 more_answer pl-0">
                                                                    <div class="form-group">
                                                                        <input type="text" class="form-control"
                                                                            name="json_params[answer]"
                                                                            value="{{ $question_item->json_params->answer }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('order_table')
                            <div class="box box-primary box-question-item" data-type ="{{ $detail->type }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        {{-- <button data-toggle="modal" data-target=".bd-modal-lg-update" type="button"
                                    data-question-id="{{ $question_item->id }}"
                                    data-url-update="{{ route('student_test.update', $question_item->id) }}"
                                    class="btn btn-sm btn-primary show_question_topic">Chỉnh sửa câu hỏi</button> --}}
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa câu
                                            hỏi</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" name="question" required
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                @isset($question_item->json_params->answer)
                                                    <div class="list_answer">
                                                        <label>Đáp án:</label>
                                                        @foreach ($question_item->json_params->answer as $k => $answer)
                                                            <div class="d-flex-wap more_answer">
                                                                <div class="col-md-6 pl-0">
                                                                    <div
                                                                        class="form-group {{ $loop->index > 0 ? 'input-group' : '' }}">
                                                                        <input type="text" name="json_params[answer][]"
                                                                            class="form-control" placeholder="Đáp án"
                                                                            value="{{ $answer ?? '' }}">
                                                                        @if ($loop->index > 0)
                                                                            <span onclick="_delete_answer(this)"
                                                                                class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endisset
                                                <button onclick="add_answer_order_table(this)"
                                                    class="form-group btn btn-primary mb-2" type="button"><i
                                                        class="fa fa-plus"></i>
                                                    Thêm câu trả lời</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('connect')
                            <div class="box box-primary box-question-item" data-type ="{{ $detail->type }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa câu
                                            hỏi</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" name="question" required
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                @isset($question_item->json_params->answer)
                                                    <div class="list_answer">
                                                        <label>Đáp án:</label>
                                                        @isset($question_item->json_params->answer->left)
                                                            @foreach ($question_item->json_params->answer->left as $key => $val)
                                                                <div class="more_answer">
                                                                    <div class="form-group row">
                                                                        <div class="col-xs-4 col-md-2">
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][left][]" placeholder="Đáp án"
                                                                                value="{{ $val ?? '' }}">
                                                                        </div>
                                                                        <div class="col-xs-2 col-md-1 text-center">
                                                                            <img style="width: 100px; max-width: 80%;"
                                                                                src="{{ url('themes/admin/img/connect.png') }}"
                                                                                alt="">
                                                                        </div>
                                                                        <div class="col-xs-4 col-md-2">
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][right][]" placeholder="Đáp án"
                                                                                value="{{ $question_item->json_params->answer->right[$key] ?? '' }}">
                                                                        </div>
                                                                        @if ($loop->index > 0)
                                                                            <div class="col-xs-2 col-md-2">
                                                                                <span onclick="_delete_answer(this)"
                                                                                    class="input-group-btn">
                                                                                    <a class="btn btn-danger">
                                                                                        <i class="fa fa-trash"></i> Xóa </a>
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endisset
                                                    </div>
                                                @endisset
                                                <button onclick="add_answer_connect(this)" class="form-group btn btn-primary mb-2"
                                                    type="button"><i class="fa fa-plus"></i>
                                                    Thêm câu trả lời</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('listen')
                            <div class="box box-primary box-question-item" data-type ="{{ $detail->type }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa câu
                                            hỏi</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" name="question" required
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('File âm thanh')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="files_audio_{{$question_item->id}}" class="btn btn-primary file">
                                                                <i class="fa fa-picture-o"></i> @lang('Select')
                                                            </a>
                                                        </span>
                                                        <input id="files_audio_{{$question_item->id}}" class="form-control" type="text"
                                                            name="json_params[files_audio] " placeholder="@lang('Files Audio')"
                                                            value="{{ $question_item->json_params->files_audio }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                @isset($question_item->json_params->answer)
                                                    <div class="list_answer">
                                                        <label>Đáp án:</label>
                                                        @foreach ($question_item->json_params->answer as $k => $answer)
                                                            <div class="d-flex-wap more_answer">
                                                                <div class="col-md-6 pl-0">
                                                                    <div
                                                                        class="form-group {{ $loop->index > 0 ? 'input-group' : '' }}">
                                                                        <input type="text" name="json_params[answer][]"
                                                                            class="form-control" placeholder="Đáp án"
                                                                            value="{{ $answer ?? '' }}">
                                                                        @if ($loop->index > 0)
                                                                            <span onclick="_delete_answer(this)"
                                                                                class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endisset
                                                <button onclick="add_answer_order_table(this)"
                                                    class="form-group btn btn-primary mb-2" type="button"><i
                                                        class="fa fa-plus"></i>
                                                    Thêm câu trả lời</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @case('fill_words')
                            <div class="box box-primary box-question-item" data-type ="{{ $detail->type }}">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" data-question-id="{{ $question_item->id }}"
                                            onclick="del_question_topic(this)" class="btn btn-danger btn-sm ">Xóa câu
                                            hỏi</button>
                                        <button class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea_{{ $question_item->id }}" name="question" required
                                                        class="form-control input-question" cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" class="form-control" name="point"
                                                        value="{{ $question_item->point ?? 0 }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                @isset($question_item->json_params->answer)
                                                    <div class="list_answer">
                                                        <label>Đáp án:</label>
                                                        @isset($question_item->json_params->answer)
                                                            @foreach ($question_item->json_params->answer as $key => $val)
                                                                <div class="more_answer">
                                                                    <div class="form-group row">
                                                                        <input type="text" class="form-control"
                                                                            name="json_params[answer][]" placeholder="Đáp án"
                                                                            value="{{ $val ?? '' }}">
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endisset
                                                    </div>
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right btn-sm btn_update_question"
                                        data-id ="{{ $question_item->id }}">
                                        <i class="fa fa-floppy-o"></i>
                                        @lang('Save')</button>
                                </div>
                            </div>
                        @break

                        @default
                    @endswitch
                </form>
            @endforeach

            <button data-toggle="modal" data-target=".bd-modal-lg"
                class="form-group btn btn-primary mb-2 add_question_topic" type="button"><i class="fa fa-plus"></i>
                Thêm câu hỏi và đáp án cho đề này</button>
        @endif
    </section>

    <div class="modal fade bd-modal-lg " data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Thêm mới câu hỏi
                        </h4>
                    </div>
                    <form action="{{ route('student_test.store') }}" method="POST"
                        class="form-ajax-add-question-topic">
                        @csrf
                        <input type="hidden" name="id_topic" value="{{ $detail->id }}" class="lesson_id">
                        <div class="modal-body">
                            <div class="body-add-question">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                            <button class="btn btn-primary">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade bd-modal-lg-update" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Chỉnh sửa câu hỏi
                        </h4>
                    </div>
                    <form action="" method="POST" class="form-update-question">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div id="alert-config"></div>
                            <div class="body-edit-question">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                            <button class="btn btn-primary">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('script')
    <script>
        var ck_options = {
            language: 'en',
            uiColor: '#E0F2F4',
            height: 200,
            entities: false,
            fullPage: false,
            allowedContent: true,
            autoParagraph: false,

            filebrowserBrowseUrl: route_prefix,
            filebrowserImageBrowseUrl: route_prefix + "?type=Images",
            filebrowserFlashBrowseUrl: route_prefix + "?type=Flash",
            filebrowserUploadUrl: route_prefix_connector +
                "?command=QuickUpload&type=Files",
            filebrowserImageUploadUrl: route_prefix_connector +
                "?command=QuickUpload&type=Images",
            filebrowserFlashUploadUrl: route_prefix_connector +
                "?command=QuickUpload&type=Flash",
        };
        CKEDITOR.replace('content_vi', ck_options);

        @foreach ($list_question as $question_item)
            CKEDITOR.replace('question_textarea_{{ $question_item->id }}', ck_options);
        @endforeach

        $('.btn_update_question').click(function(e) {
            e.preventDefault();
            var _id = $(this).data('id');
            var _form = $(this).parents('.form-update-question');
            var formData = _form.serialize();
            // Lấy dữ liệu từ CKEditor
            var editorInstance = CKEDITOR.instances['question_textarea_' + _id];
            if (editorInstance) {
                var editorData = editorInstance.getData();
                // Append dữ liệu từ CKEditor vào formData
                formData += '&question=' + encodeURIComponent(editorData);
            } else {
                console.error('CKEditor instance not found');
            }
            var _url = _form.attr('action');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: _url,
                method: 'PUT',
                data: formData,
                success: function(response) {
                    var _html = `<div class="alert alert-` + response
                        .data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                    _form.prepend(_html);
                    $('html, body').animate({
                        scrollTop: _form.offset().top
                    }, 1000);
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })
        //thêm câu hỏi
        $(document).on('click', '.add_question_topic', function() {
            let _type = $('.topic_type').val();
            let url = "{{ route('ajax.topic.addquestion') }}/";
            var _targetHTML = $('.body-add-question');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: _type,
                },
                success: function(response) {
                    _targetHTML.html(response)
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    _targetHTML.html(errors);
                }
            });
        });
        //xử lý show câu hỏi
        $(document).on('click', '.show_question_topic', function() {
            let _type = $('.topic_type').val();
            let _id = $(this).attr("data-question-id");
            let _url_update = $(this).attr("data-url-update");
            let url = "{{ route('ajax.topic.editquestion') }}/";
            var _targetHTML = $('.body-edit-question');
            $('.form-update-question').attr('action', _url_update);
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: _type,
                    id: _id,
                },
                success: function(response) {
                    _targetHTML.html(response)
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    _targetHTML.html(errors);
                }
            });
        });

        //Xóa câu hỏi
        function del_question_topic(th) {
            if (confirm("Duyên Đỗ lưu ý: Bạn có chắc chắn muốn xóa câu hỏi này không?")) {
                var _id = $(th).attr('data-question-id');
                var _url = "{{ route('ajax.topic.destroyquestion') }}";
                $.ajax({
                    type: "GET",
                    url: _url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            $("#alert-config").append(
                                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Xóa thành công câu hỏi</div>'
                            );
                            setTimeout(function() {
                                $(".alert-warning").fadeOut(1000, function() {});
                            }, 800);
                            $(th).parents('.box-question-item').fadeOut(800, function() {
                                $(th).parents('.box-question-item').remove();
                            });
                        } else {
                            alert('Không có quyền xóa câu hỏi!');
                        }
                    },
                    error: function(response) {
                        // Lấy lỗi
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        }

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

        function add_answer_choice(th) {
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

        function add_answer_order_table(th) {
            var currentTime = $.now();
            var _html = `<div class="d-flex-wap more_answer">
                        <div class="col-md-6 pl-0">
                            <div class="form-group input-group">
                                <input name="json_params[answer][]" type="text" class="form-control" placeholder="Đáp án" value="">
                                <span onclick="_delete_answer(this)" class="input-group-btn">
                                    <a class="btn btn-danger">
                                        <i class="fa fa-trash"></i> Xóa </a>
                                </span>
                            </div>
                        </div>
                    </div>`;
            $(th).parents('.box-question-item').find('.list_answer').append(_html);
        }

        function add_answer_connect(th) {
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
@endsection
