@extends('admin.layouts.app')

@section('style')
    <style>
        .more_answer {
            padding: 10px;
        }

        .hidden {
            display: none !important;
            visibility: hidden;
        }

        .valign-middle img {
            width: 200px !important;
            height: 200px !important;
        }

        .edit-quiz {
            margin-right: 10px
        }
    </style>
@endsection

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right add-quiz" href data-toggle="modal" data-target=".modal_add_quiz"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách câu hỏi') {{ $lesson->title ?? '' }} của chương trình
                    {{ $lesson->syllabus->name ?? '' }}</h3>
            </div>
            <div class="box-body table-responsive">
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
                @if (count($quizs) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Đề bài/Câu hỏi')</th>
                                <th>@lang('Loại')</th>
                                <th>@lang('Dạng')</th>
                                <th>@lang('Kiểu')</th>
                                <th style="width:240px">@lang('Cập nhật')</th>
                                <th style="width:270px">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($quizs)
                                @foreach ($quizs as $row)
                                    @if ($row->parent_id == 0 || $row->parent_id == null)
                                        <tr class="valign-middle">
                                            <td>
                                                {!! $row->question ?? '' !!}
                                            </td>
                                            <td>
                                                @lang($row->type)
                                            </td>
                                            <td>
                                                @lang($row->form)
                                            </td>
                                            <td>
                                                @lang($row->style)
                                            </td>
                                            <td>
                                                {{ $row->updated_at }}
                                            </td>
                                            <td class="d-flex-wap">
                                                <a data-quiz="{{ $row->id }}" class="btn btn-sm btn-warning edit-quiz"
                                                    data-toggle="modal" data-target=".modal_edit_quiz"
                                                    title="@lang('Update')" href>
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                @if ($row->sub_quiz_id == 0)
                                                    <form action="{{ route('quiz.delete', ['id' => $row->id]) }}"
                                                        method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger" type="submit"
                                                            data-toggle="tooltip" title="@lang('Delete')"
                                                            data-original-title="@lang('Delete')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                            </td>
                                        </tr>
                                        @foreach ($quizs as $sub)
                                            @if ($sub->parent_id == $row->id)
                                                <tr class="valign-middle bg-gray-light">
                                                    <td>
                                                        - - - - {{ strip_tags($sub->question) ?? '' }}
                                                    </td>
                                                    <td>
                                                        @lang($sub->type)
                                                    </td>
                                                    <td>
                                                        @lang($sub->form)
                                                    </td>
                                                    <td>
                                                        @lang($sub->style)
                                                    </td>

                                                    <td>
                                                        {{ $sub->updated_at }}
                                                    </td>

                                                    <td class="d-flex-wap">
                                                        <a data-quiz="{{ $sub->id }}"
                                                            class="btn btn-sm btn-warning edit-quiz" data-toggle="modal"
                                                            data-target=".modal_edit_quiz" title="@lang('Update')" href>
                                                            <i class="fa fa-pencil-square-o"></i>
                                                        </a>

                                                        <form action="{{ route('quiz.delete', ['id' => $sub->id]) }}"
                                                            method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger" type="submit"
                                                                data-toggle="tooltip" title="@lang('Delete')"
                                                                data-original-title="@lang('Delete')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $quizs->count() }} kết quả
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade modal_add_quiz" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Thêm mới Đề/câu hỏi
                        </h4>
                    </div>
                    <form action="{{ route('quiz.store') }}" method="post" class="form-ajax-lesson">
                        @csrf
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id ?? '' }}">
                        <div class="modal-body modal-body-add-leson">
                            <div class="row lesson-item">
                                <div class="col-lg-12">
                                    <div class="box box-primary">
                                        <div class="tab-pane active">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Đề bài')</label>
                                                    <select name="parent_id" class="form-control select2 parent"
                                                        style="width: 100%">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($quiz_parent as $item)
                                                            <option value="{{ $item->id }}">@lang($item->question)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Loại câu hỏi') <span class="text-red">*</span></label>
                                                    <select name="type" required
                                                        class="form-control type_quiz select2 select_parent "
                                                        style="width: 100%">
                                                        @foreach ($type_quiz as $type => $value)
                                                            <option value="{{ $type }}">@lang($value)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Dạng câu hỏi') <span class="text-red">*</span></label>
                                                    <select name="form" required
                                                        class="form-control form_quiz select2 select_parent "
                                                        style="width: 100%">
                                                        @foreach ($form_quiz as $type => $value)
                                                            <option value="{{ $type }}">@lang($value)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Kiểu câu hỏi') <span class="text-red">*</span>
                                                    </label>
                                                    <select name="style" required
                                                        class="form-control select2 style_quiz select_parent "
                                                        style="width: 100%">
                                                        @foreach ($style_quiz as $type => $value)
                                                            <option value="{{ $type }}">@lang($value)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea class="form-control" id="question" name="question" cols="30" rows="1"
                                                        placeholder="Nhập câu hỏi..."></textarea>
                                                </div>
                                            </div>
                                            <div class="view_audio"></div>
                                            <div class="col-md-12 view_question">

                                                {{-- <div class="tab-content chon_dap_an hidden">
                                                            <div class="tab-pane active list_answer ">
                                                                <strong>Đáp án:</strong>
                                                                <div class="d-flex-wap more_answer">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][1][value]"
                                                                                placeholder="Đáp án" value="">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        <input type="checkbox"
                                                                            name="json_params[answer][1][boolean]"
                                                                            value="0"
                                                                            onchange="updateCheckboxValue(this)">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button
                                                                class="form-group btn btn-primary mb-2 add_answer_choice"
                                                                type="button"><i class="fa fa-plus"></i>
                                                                Thêm câu trả lời</button>
                                                        </div>

                                                        <div class="tab-content dien_tu_theo_tung_cau hidden">
                                                            <div class="tab-pane active ">
                                                                <strong>Đáp án:</strong>
                                                                <div class="d-flex-wap list_answer_fill">
                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group ">
                                                                            <input type="text" class="form-control" name="json_params[answer][]"
                                                                                placeholder="Đáp án" value=""disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group input-group">
                                                                            <input type="text" class="form-control" name="json_params[answer][]"
                                                                                placeholder="Đáp án" value=""disabled>
                                                                            <span onclick="delete_item(this)" class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group input-group">
                                                                            <input type="text" class="form-control" name="json_params[answer][]"
                                                                                placeholder="Đáp án" value=""disabled>
                                                                            <span onclick="delete_item(this)" class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group input-group">
                                                                            <input type="text" class="form-control" name="json_params[answer][]"
                                                                                placeholder="Đáp án" value=""disabled>
                                                                            <span onclick="delete_item(this)" class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button class="form-group btn btn-primary mb-2 add_answer_fill" type="button"><i
                                                                    class="fa fa-plus"></i>
                                                                Thêm câu trả lời</button>
                                                        </div>

                                                        <div class="tab-content sap_xep_cau_hoan_chinh hidden">
                                                            <div class="tab-pane active ">
                                                                <strong>Đáp án:</strong>
                                                                <div class="d-flex-wap list_answer_order">
                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" name="json_params[answer][]"
                                                                                placeholder="Đáp án" value="" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group input-group">
                                                                            <input type="text" class="form-control" name="json_params[answer][]"
                                                                                placeholder="Đáp án" value="" disabled>
                                                                            <span onclick="delete_item(this)" class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group input-group">
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][]" placeholder="Đáp án" value=""
                                                                                disabled>
                                                                            <span onclick="delete_item(this)" class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 more_answer">
                                                                        <div class="form-group input-group">
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][]" placeholder="Đáp án"
                                                                                value=""disabled>
                                                                            <span onclick="delete_item(this)" class="input-group-btn">
                                                                                <a class="btn btn-danger">
                                                                                    <i class="fa fa-trash"></i> Xóa </a>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button class="form-group btn btn-primary mb-2 add_answer_order" type="button"><i
                                                                    class="fa fa-plus"></i>
                                                                Thêm câu trả lời</button>
                                                        </div>

                                                        <div class="tab-content nhap_dap_an hidden">
                                                            <div class="tab-pane active ">
                                                                <strong>Đáp án:</strong>
                                                                <div class="d-flex-wap more_answer">
                                                                    <div class="col-xs-12 col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][]" placeholder="Đáp án" value=""
                                                                                disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="tab-content noi_dap_an hidden">
                                                            <div class="tab-pane active ">
                                                                <strong>Cặp đáp án:</strong>
                                                                <div class="d-flex-wap list_answer_connect">
                                                                    <div class="col-md-12 more_answer">
                                                                        <div class="form-group d-flex-wap">
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control"
                                                                                    name="json_params[answer][left][]" placeholder="Đáp án"
                                                                                    value="" disabled>
                                                                            </div>
                                                                            <div class="col-md-2 text-center">
                                                                                <img style="width: 100px; max-width: 80%;" src="{{url('themes/admin/img/connect.png')}}" alt="">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control"
                                                                                    name="json_params[answer][right][]" placeholder="Đáp án"
                                                                                    value="" disabled>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12 more_answer">
                                                                        <div class="form-group d-flex-wap">
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control"
                                                                                    name="json_params[answer][left][]" placeholder="Đáp án"
                                                                                    value="" disabled>
                                                                            </div>
                                                                            <div class="col-md-2 text-center">
                                                                                <img style="width: 100px; max-width: 80%;" src="{{url('themes/admin/img/connect.png')}}" alt="">
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control"
                                                                                    name="json_params[answer][right][]" placeholder="Đáp án"
                                                                                    value="" disabled>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <span onclick="delete_item(this)" class="input-group-btn">
                                                                                    <a class="btn btn-danger">
                                                                                        <i class="fa fa-trash"></i> Xóa </a>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <button class="form-group btn btn-primary mb-2 add_answer_connect" type="button"><i
                                                                    class="fa fa-plus"></i>
                                                                Thêm cặp đáp án</button>
                                                        </div> --}}

                                                {{-- <div class="tab-content noi_dap_an hidden">
                                                            <div class="tab-pane active ">
                                                                <div class="d-flex-wap list_answer_speak">
                                                                    <div class="col-md-12 more_answer">
                                                                        <div class="form-group">
                                                                            <label>@lang('File âm thanh')</label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-btn">
                                                                                    <a data-input="files_audio"
                                                                                        class="btn btn-primary file">
                                                                                        <i class="fa fa-picture-o"></i> @lang('Select')
                                                                                    </a>
                                                                                </span>
                                                                                <input id="files_audio" class="form-control" type="text" name="json_params[files_audio] "
                                                                                    placeholder="@lang('Files Audio')" value="">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>@lang('Đáp án')</label>
                                                                            <input type="text" class="form-control"
                                                                                name="json_params[answer][]" placeholder="Đáp án" value=""
                                                                                disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <div class="modal fade modal_edit_quiz " data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Chỉnh sửa Đề bài/Câu hỏi
                        </h4>
                    </div>
                    <form action="{{ route('quiz.update') }}" method="post" class="form-ajax-lesson">
                        @csrf
                        <input type="hidden" name="quiz_id" value="" class="quiz_id">
                        <div class="modal-body modal-body-edit-leson">
                            <div class="row lesson-item">
                                <div class="col-lg-12">
                                    <div class="box box-primary">
                                        <div class="tab-pane active">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Đề bài')</label>
                                                    <select name="parent_id" class="form-control select2 parent"
                                                        style="width: 100%">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($quiz_parent as $item)
                                                            <option value="{{ $item->id }}">@lang($item->question)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Loại câu hỏi') <span class="text-red">*</span></label>
                                                    <select name="type"
                                                        class="form-control type_quiz select2 select_parent "
                                                        style="width: 100%">
                                                        @foreach ($type_quiz as $type => $value)
                                                            <option value="{{ $type }}">@lang($value)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Dạng câu hỏi') <span class="text-red">*</span></label>
                                                    <select name="form"
                                                        class="form-control form_quiz select2 select_parent "
                                                        style="width: 100%">
                                                        @foreach ($form_quiz as $type => $value)
                                                            <option value="{{ $type }}">@lang($value)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Kiểu câu hỏi') <span class="text-red">*</span>
                                                    </label>
                                                    <select name="style"
                                                        class="form-control select2 style_quiz select_parent "
                                                        style="width: 100%">
                                                        <option value="">Chọn</option>
                                                        @foreach ($style_quiz as $type => $value)
                                                            <option value="{{ $type }}">@lang($value)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea class="form-control" id="question_edit" name="question" cols="30" rows="1"
                                                        placeholder="Nhập câu hỏi..."></textarea>
                                                </div>
                                            </div>
                                            <div class="view_audio"></div>
                                            <div class="col-md-12 view_question"></div>
                                        </div>
                                    </div>
                                </div>
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
@endsection
@section('script')
    <script>
        CKEDITOR.replace('question', ck_options);
        CKEDITOR.replace('question_edit', ck_options);
        let Event = false;
        $('.form_quiz').change(function() {
            var _modal = $(this).parents('.modal')
            var form = $(this).val();
            if (form == 'nghe') {
                var _html = `
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
                            placeholder="@lang('Files Audio')" value="">
                    </div>
                </div>
                </div>
                `;
                _modal.find('.modal_add_quiz .view_audio').html(_html);
                $('.file').filemanager('Files', {
                    prefix: '{{ route('ckfinder_browser') }}'
                });
            }
            else{
                _modal.find('.modal_add_quiz .view_audio').html('');
            }
        })

        $('.parent').change(function() {
            if (Event) return;
            var _id = $(this).val();
            $('.view_audio').html('')
            if (_id != '') {
                $('.select_parent').prop('disabled', true);
                var url = "{{ route('quiz.get_layout_question') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        var view = response.data.view;
                        var quiz = response.data.quiz;
                        Event = true;
                        $('.modal_add_quiz .type_quiz').val(quiz.type).trigger('change');
                        $('.modal_add_quiz .form_quiz').val(quiz.form).trigger('change');
                        $('.modal_add_quiz .style_quiz').val(quiz.style).trigger('change');
                        Event = false;
                        $('.view_question').html(view);
                        $('.view_audio').html(response.data.view_audio);
                        $('.file').filemanager('Files', {
                            prefix: '{{ route('ckfinder_browser') }}'
                        });
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            } else {
                $('.select_parent').prop('disabled', false);
                $('.view_question').html('')
            }
        })

        $('.add-quiz').click(function() {
            $('.parent').val('').trigger('change');
            $('.view_audio').html('')
            $('.view_question').html('')
        })

        $('.edit-quiz').on('click', function(e) {
            e.preventDefault();
            var _id = $(this).data('quiz');
            let url = "{{ route('quiz.get_layout_question') }}/";
            $('.view_audio').html('')
            $('.view_question').html('')
            var _targetHTML = $('.view_question');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                },
                success: function(response) {
                    var _view = response.data.view;
                    var _quiz = response.data.quiz;
                    console.log(response.dat);

                    var _quiz_parent = response.data.quiz_parent ?? '';
                    $('.modal_edit_quiz .quiz_id').val(_id);
                    Event = true;
                    if (_quiz.parent_id != null) {
                        $('.modal_edit_quiz .parent').val(_quiz.parent_id ?? '').prop('disabled', true)
                            .trigger('change');
                        $('.modal_edit_quiz .type_quiz').val(_quiz_parent.type ?? '').prop('disabled',
                            true).trigger('change');
                        $('.modal_edit_quiz .form_quiz').val(_quiz_parent.form ?? '').prop('disabled',
                            true).trigger('change');
                        $('.modal_edit_quiz .style_quiz').val(_quiz_parent.style ?? '').prop('disabled',
                            true).trigger('change');
                        _targetHTML.html(_view)
                    } else {
                        $('.modal_edit_quiz .parent').prop('disabled', true).val('').trigger('change');
                        $('.modal_edit_quiz .type_quiz').val(_quiz.type ?? '').prop('disabled', true)
                            .trigger('change');
                        $('.modal_edit_quiz .form_quiz').val(_quiz.form ?? '').prop('disabled', true)
                            .trigger('change');
                        $('.modal_edit_quiz .style_quiz').val(_quiz.style ?? '').prop('disabled', true)
                            .trigger('change');
                        _targetHTML.html('');
                    }
                    $('.modal_edit_quiz .view_audio').html(response.data.view_audio);
                    $('.file').filemanager('Files', {
                        prefix: '{{ route('ckfinder_browser') }}'
                    });
                    Event = false;
                    CKEDITOR.instances['question_edit'].setData(_quiz.question ?? '');

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        });

        $(document).on('click', '.add_answer_choice', function() {
            var currentTime = $.now();
            var _html = `<div class="d-flex-wap more_answer">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control"
                                    name="json_params[answer][` + currentTime + `][value]" placeholder="Đáp án"
                                    value="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" class="check_answer" name="json_params[answer][` + currentTime + `][boolean]"
                                value="0"  onchange="updateCheckboxValue(this) ">
                        </div>
                        <button onclick="_delete_answer(this)" type="button"
                            class="btn btn-sm btn-danger" >Xóa</button>
                    </div>`;
            $('.list_answer').append(_html)
        })
        $(document).on('click', '.add_answer_connect', function() {
            var _html = `<div class="col-md-12 more_answer">
                            <div class="form-group d-flex-wap">
                                <div class="col-md-3">
                                    <input type="text" class="form-control"
                                        name="json_params[answer][left][]" placeholder="Đáp án"
                                        value="">
                                </div>
                                <div class="col-md-2 text-center">
                                    <img style="width: 100px; max-width: 80%;" src="{{ url('themes/admin/img/connect.png') }}" alt="">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control"
                                        name="json_params[answer][right][]" placeholder="Đáp án"
                                        value="">
                                </div>
                                <div class="col-md-3">
                                    <span onclick="delete_item(this)" class="input-group-btn">
                                        <a class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Xóa </a>
                                    </span>
                                </div>
                            </div>
                        </div>`;
            $('.list_answer_connect').append(_html)
        })
        $(document).on('click', '.add_answer', function() {
            var _html = `<div class="col-md-3 more_answer">
                        <div class="form-group input-group">
                            <input type="text" class="form-control"
                                name="json_params[answer][]" placeholder="Đáp án"
                                value="" >
                                <span onclick="delete_item(this)" class="input-group-btn">
                                    <a class="btn btn-danger">
                                        <i class="fa fa-trash"></i> Xóa </a>
                                </span>
                        </div>
                    </div>`;
            $('.list_answer').append(_html)
        })

        function delete_item(th) {
            $(th).parents('.more_answer').remove();
        }

        function _delete_answer(th) {
            $(th).parents('.more_answer').remove();
        }

        function updateCheckboxValue(_this) {
            $('.check_answer').prop('checked', false).val('0');
            $(_this).prop('checked', true).val('1');
        }
    </script>
@endsection
