@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .item_answer {
            align-items: center;
        }

        .w-150 {
            width: 150px;
        }

        .h-60 {
            height: 60px;
        }

        .bd-b {
            border-bottom: 1px solid #000;
        }

        .bd-l {
            border-left: 1px solid #000;
        }

        .box_center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-dm btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
            </a>

        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
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
                            <h3 class="box-title">@lang('Phần thi thi')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Lưu phần thi')
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Trình độ') {{ $detail->id_level }}<small
                                                        class="text-red">*</small></label>
                                                <select required name="id_level" class="id_level form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($levels as $val)
                                                        <option value="{{ $val->id ?? '' }}"
                                                            {{ $detail->id_level == $val->id ? 'selected' : '' }}>
                                                            {{ $val->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Tổ chức')</label>
                                                <select name="organization" class=" form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($organization as $key => $val)
                                                        <option value="{{ $key ?? '' }}"
                                                            {{ $detail->organization == $key ? 'selected' : '' }}>
                                                            {{ __($val) ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Phần thi') <small class="text-red">*</small></label>
                                                <select required name="is_type" class="form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($group as $val)
                                                        <option value="{{ $val }}"
                                                            {{ $detail->is_type == $val ? 'selected' : '' }}>
                                                            {{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Chọn hình thức') <small class="text-red">*</small></label>
                                                <select required name="skill_test" class="form-control select2">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($skill as $val)
                                                        <option value="{{ $val }}"
                                                            {{ $detail->skill_test == $val ? 'selected' : '' }}>
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('Kiểu câu hỏi') <small class="text-red">*</small></label>
                                                <select disabled class="form-control select2 type_question">
                                                    <option value="">@lang('Please choose')</option>
                                                    @foreach ($type as $val)
                                                        <option value="{{ $val }}"
                                                            {{ $detail->type_question == $val ? 'selected' : '' }}>
                                                            @lang($val)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>@lang('File Audio nếu có')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="files_audio" class="btn btn-primary file">
                                                                <i class="fa fa-picture-o"></i> @lang('Select')
                                                            </a>
                                                        </span>
                                                        <input id="files_audio" class="form-control" type="text"
                                                            name="audio" placeholder="@lang('Files Audio')"
                                                            value="{{ $detail->audio ?? '' }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>@lang('Content')</label>
                                                    <textarea name="content" class="form-control" id="content_vi">{!! $detail->content ?? old('content') !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($detail->type_question == 'nhap_dap_an_dang_bang')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Demo')</label>
                                                    <div class="d-flex-wap">
                                                        <div class="w-150">
                                                            <p class="box_center font-weight-bold h-60 bd-b">Person</p>
                                                            <p class="box_center font-weight-bold h-60">
                                                                Lösung</p>
                                                        </div>
                                                        <div class="w-150 bd-l">
                                                            <p class="box_center flex-column h-60 bd-b">
                                                                <input type="text" class="form-control text-center w-75"
                                                                    name="json_params[demo_question]"
                                                                    value="{{ $detail->json_params->demo_question ?? '' }}"
                                                                    placeholder="Mẫu câu">
                                                            </p>
                                                            <p class="box_center h-60">
                                                                <input type="text"
                                                                    class="form-control text-center w-75"
                                                                    name="json_params[demo_answer]"
                                                                    value="{{ $detail->json_params->demo_answer ?? '' }}"
                                                                    placeholder="Mẫu đáp án">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <button class="btn btn-primary mb-2 add_question_topic" style="margin-bottom: 10px;" type="button"><i
                class="fa fa-plus"></i>
            Thêm câu hỏi và đáp án cho phần này</button>
        <div class="row">
            <div class="col-md-12">
                <h4 style="padding-bottom:10px;">Danh sách câu hỏi</h4>

                @if (isset($detail->exam_questions) && count($detail->exam_questions) > 0)
                    @foreach ($detail->exam_questions as $question_item)
                        @switch($question_item->is_type)
                            @case('chon_dap_an')
                                <div class="box box-primary box-question-item">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                        <div class="box-tools pull-right">
                                            <form action="{{ route('hv_exam_questions.destroy', $question_item->id) }}"
                                                onsubmit="return confirm('@lang('confirm_action')')" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm ">Xóa câu hỏi</button>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="{{ route('hv_exam_questions.update', $question_item->id) }}"
                                        onsubmit="return confirm('@lang('confirm_action')')" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="box-body">
                                            <div class="tab_offline">
                                                <div class="tab-pane active">
                                                    <div class="col-md-12 textarea-question">
                                                        <div class="form-group">
                                                            <label>@lang('Câu hỏi')</label>
                                                            <textarea id="question_{{ $question_item->id }}" name="question" required class="form-control input-question"
                                                                cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>@lang('Số điểm')</label>
                                                            <input type="number" name="point" class="form-control"
                                                                min="0" value="{{ $question_item->point ?? 0 }}"
                                                                placeholder="Số điểm cho câu hỏi này">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 box_answers">
                                                        <div class="tab-content">
                                                            <div class="form-group ">
                                                                <label>Đáp án:</label>
                                                                <div class="more_answer">
                                                                    @if (isset($question_item->exam_answers) && count($question_item->exam_answers) > 0)
                                                                        @foreach ($question_item->exam_answers as $key_answer => $item_answer)
                                                                            <div class="d-flex-wap item_answer">
                                                                                <div class="col-md-4">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control"
                                                                                            name="answer[{{ $key_answer }}][value]"
                                                                                            placeholder="Đáp án"
                                                                                            value="{{ $item_answer->answer ?? '' }}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <input type="checkbox" class="check_answer"
                                                                                        {{ $item_answer->correct_answer == 1 ? 'checked' : '' }}
                                                                                        name="answer[{{ $key_answer }}][boolean]"
                                                                                        value="{{ $item_answer->correct_answer }}"
                                                                                        onchange="updateCheckboxValue(this)">
                                                                                </div>
                                                                                <div class="col-md-1">
                                                                                    <span onclick="delete_answer(this)"
                                                                                        class="input-group-btn">
                                                                                        <a class="btn btn-danger">
                                                                                            <i class="fa fa-trash"></i> Xóa </a>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <button class="form-group btn btn-primary mb-2" type="button"
                                                                onclick="add_answer_choice(this)"><i class="fa fa-plus"></i>
                                                                @lang('Thêm câu trả lời')</button>
                                                        </div>
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
                                    </form>
                                </div>
                            @break

                            @default
                                {{-- Loại điền đáp án, điền đáp án dạng bảng  --}}
                                <div class="box box-primary box-question-item">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Câu hỏi {{ $loop->index + 1 }}</h3>
                                        <div class="box-tools pull-right">
                                            <form action="{{ route('hv_exam_questions.destroy', $question_item->id) }}"
                                                onsubmit="return confirm('@lang('confirm_action')')" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm ">Xóa câu hỏi</button>
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                        class="fa fa-minus"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <form action="{{ route('hv_exam_questions.update', $question_item->id) }}"
                                        onsubmit="return confirm('@lang('confirm_action')')" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="box-body">
                                            <div class="tab_offline">
                                                <div class="tab-pane active">
                                                    <div class="col-md-12 textarea-question">
                                                        <div class="form-group">
                                                            <label>@lang('Câu hỏi')</label>
                                                            <textarea id="question_{{ $question_item->id }}" required name="question" class="form-control input-question"
                                                                cols="30" rows="3" placeholder="Nhập câu hỏi...">{{ $question_item->question ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>@lang('Số điểm')</label>
                                                            <input type="number" name="point" class="form-control"
                                                                min="0" value="{{ $question_item->point ?? 0 }}"
                                                                placeholder="Số điểm cho câu hỏi này">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="tab-content fill ">
                                                            <div class="tab-pane active ">
                                                                <label>Đáp án:</label>
                                                                <div class="d-flex-wap list_answer_fill">
                                                                    <div class="col-md-3 more_answer pl-0">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" required
                                                                                name="answer"
                                                                                value="{{ $question_item->exam_answers->first()->answer ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                    </form>
                                </div>
                            @break
                        @endswitch
                    @endforeach
                @endif
            </div>
        </div>
        <button class="btn btn-primary mb-2 add_question_topic mt-15" style="margin-bottom: 10px;" type="button"><i
                class="fa fa-plus"></i>
            Thêm câu hỏi và đáp án cho phần này</button>
    </section>
    <div class="modal fade bd-modal-lg " data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Thêm mới câu hỏi
                        </h4>
                    </div>
                    <form action="{{ route('hv_exam_questions.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_topic" value="{{ $detail->id }}">
                        <input type="hidden" name="is_type" value="{{ $detail->type_question }}">
                        <div class="modal-body">
                            <div class="box box-primary box-question-item">
                                <div class="box-body">
                                    <div class="tab_offline">
                                        <div class="tab-pane active">
                                            {{-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Kiểu câu hỏi') <small class="text-red">*</small></label>
                                                    <select required name="is_type"
                                                        class="form-control select2 change_type" style="width: 100%">
                                                        <option value="">@lang('Please choose')</option>
                                                        @foreach ($type as $val)
                                                            <option value="{{ $val }}" {{ $detail->type_question == $val ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-12 textarea-question">
                                                <div class="form-group">
                                                    <label>@lang('Câu hỏi')</label>
                                                    <textarea id="question_textarea" class="form-control input-question" name="question" cols="30" rows="1"
                                                        placeholder="Nhập câu hỏi..."></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số điểm')</label>
                                                    <input type="number" name="point" class="form-control"
                                                        min="0" value="{{ $question_item->point ?? 0 }}"
                                                        placeholder="Số điểm cho câu hỏi này">
                                                </div>
                                            </div>
                                            <div class="col-md-12 box_answers">
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
@endsection
@section('script')
    <script>
        CKEDITOR.replace('content_vi', ck_options);
        CKEDITOR.replace('question_textarea', ck_options);
        var list_question = @json($detail->exam_questions ?? []);
        list_question.forEach(function(question_item) {
            CKEDITOR.replace(`question_${question_item.id}`, ck_options);
        });


        $(function() {
            $('.add_question_topic').click(function() {
                var _type = $('.type_question').val();
                var _html_answers = '';
                switch (_type) {
                    case "chon_dap_an":
                        _html_answers += `
                        <div class="tab-content">
                            <div class="form-group ">
                                <label>Đáp án:</label>
                                    <div class="more_answer">
                                        <div class="d-flex-wap item_answer">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="answer[1][value]" placeholder="Đáp án"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <input type="checkbox" class="check_answer" name="answer[1][boolean]" value="0"
                                                    onchange="updateCheckboxValue(this)">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <button class="form-group btn btn-primary mb-2" type="button" onclick="add_answer_choice(this)"><i class="fa fa-plus"></i>
                                @lang('Thêm câu trả lời')</button>
                        </div>
                        `;
                        break;
                    default:
                        // Nhập 1 đáp án đúng
                        _html_answers += `
                        <div class="col-md-3 pl-0">
                            <div class="form-group">
                                <label>Đáp án:</label>
                                <input type="text" name="answer"
                                    class="form-control" required placeholder="Đáp án" value="">
                            </div>
                        </div>
                        `;
                        break;
                }
                $('.modal-body .box_answers').html(_html_answers);
                $('.bd-modal-lg').modal('show');
            });
            $('.change_type').change(function() {
                var _type = $(this).val();
                var _html_answers = '';
                switch (_type) {
                    case "chon_dap_an":
                        _html_answers += `
                        <div class="tab-content">
                            <div class="form-group ">
                                <label>Đáp án:</label>
                                    <div class="more_answer">
                                        <div class="d-flex-wap item_answer">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" name="answer[1][value]" placeholder="Đáp án"
                                                        value="">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <input type="checkbox" class="check_answer" name="answer[1][boolean]" value="0"
                                                    onchange="updateCheckboxValue(this)">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <button class="form-group btn btn-primary mb-2" type="button" onclick="add_answer_choice(this)"><i class="fa fa-plus"></i>
                                @lang('Thêm câu trả lời')</button>
                        </div>
                        `;
                        break;
                    default:
                        // Nhập 1 đáp án đúng
                        _html_answers += `
                        <div class="col-md-3 pl-0">
                            <div class="form-group">
                                <label>Đáp án:</label>
                                <input type="text" name="answer"
                                    class="form-control" required placeholder="Đáp án" value="">
                            </div>
                        </div>
                        `;
                        break;
                }

            })
        });

        function add_answer_choice(th) {
            var currentTime = $.now();
            var _html = `
                        <div class="d-flex-wap item_answer">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input name="answer[` + currentTime + `][value]" type="text" class="form-control" placeholder="Đáp án"
                                     value="">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" class="check_answer" name="answer[` + currentTime + `][boolean]" value="0"  onchange="updateCheckboxValue(this) ">
                            </div>
                            <div class="col-md-1">
                                <span onclick="delete_answer(this)" class="input-group-btn">
                                        <a class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Xóa </a>
                                    </span>
                            </div>
                        </div>`;
            $(th).parents('.box_answers').find('.more_answer').append(_html);
        }

        function delete_answer(th) {
            $(th).parents('.item_answer').remove();
        }

        function updateCheckboxValue(th) {
            $('.check_answer').prop('checked', false).val(0);
            $(th).prop('checked', true).val(1);
        }
    </script>
@endsection
