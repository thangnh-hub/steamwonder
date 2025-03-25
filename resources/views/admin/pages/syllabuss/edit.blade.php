@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .select2-container {
            width: 100% !important;
        }

        .hidden {
            display: none;
        }

        .mb-10 {
            margin-bottom: 10px
        }

        .pl-0 {
            padding-left: 0px !important
        }

        textarea {
            resize: none;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>

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

        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST"
            id="form_product">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <div class="tab_offline">
                                    <div class="tab-pane active">
                                        <div class="">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="sw_featured">
                                                        <label class="switch ">
                                                            <input id="sw_featured" name="is_flag" value="1"
                                                                type="checkbox"
                                                                {{ isset($detail->is_flag) && $detail->is_flag == '1' ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                        <label class="box-title ml-1"
                                                            for="sw_featured">@lang('New')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 hidden">
                                                <div class="form-group">
                                                    <label>@lang('Loại chương trình') <small class="text-red">*</small></label>
                                                    <select required name="type"
                                                        class="form-control select2 type_sylabus">
                                                        <option value="offline">Offline</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 ">
                                                <div class="form-group">
                                                    <label>@lang('Cách tính điểm') <small class="text-red">*</small></label>
                                                    <select required name="score_type" class="form-control select2">
                                                        @foreach ($score_type as $key => $type)
                                                            <option
                                                                {{ isset($detail->score_type) && $detail->score_type == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                @lang($type)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')" value="{{ $detail->name ?? '' }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Lesson')<small class="text-red">*</small></label>
                                                    <input required readonly id="lesson" type="number"
                                                        class="form-control lesson" name="lesson"
                                                        placeholder="@lang('Lesson')"
                                                        value="{{ count($lessonSylabus) ?? $detail->lesson }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Lesson min')<small class="text-red">*</small></label>
                                                    <input required type="number" class="form-control" name="lesson_min"
                                                        placeholder="@lang('Lesson min')"
                                                        value="{{ $detail->lesson_min ?? old('lesson_min') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Approve')</label>
                                                    <select name="is_approve" class=" form-control select2">
                                                        @foreach ($approve as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->is_approve) && $detail->is_approve == $val ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Level')<small class="text-red">*</small></label>
                                                    <select name="level_id" required class=" form-control select2">
                                                        <option value="">@lang('Level')</option>
                                                        @foreach ($parents as $val)
                                                            <option
                                                                {{ isset($detail->level_id) && $detail->level_id == $val->id ? 'selected' : '' }}
                                                                value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tab-pane-3">
                                            <div class="col-md-12 mb-10">
                                                <h3>Nhóm kỹ năng:</h3>
                                            </div>
                                            <div class="col-md-6 mb-15 pl-0">
                                                <div class="flex align-items-center">
                                                    <div class="col-md-2">
                                                        <label>@lang('Listen Skill'):</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][listen][min]"
                                                            placeholder="@lang('Min score')"value="{{ $detail->json_params->score->listen->min ?? old('json_params->score->listen->min') }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][listen][weight]"
                                                            placeholder="@lang('Weight')"value="{{ $detail->json_params->score->listen->weight ?? old('json_params->score->listen->weight') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-15 col-md-6 ">
                                                <div class="flex align-items-center">
                                                    <div class="col-md-2">
                                                        <label>@lang('Speak Skill'):</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][speak][min]"
                                                            placeholder="@lang('Min score')"value="{{ $detail->json_params->score->speak->min ?? old('json_params->score->speak->min') }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][speak][weight]"
                                                            placeholder="@lang('Weight')"value="{{ $detail->json_params->score->speak->weight ?? old('json_params->score->speak->weight') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-15 col-md-6 pl-0">
                                                <div class="flex align-items-center">
                                                    <div class="col-md-2">
                                                        <label>@lang('Read Skill'):</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][read][min]"
                                                            placeholder="@lang('Min score')"value="{{ $detail->json_params->score->read->min ?? old('json_params->score->read->min') }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][read][weight]"
                                                            placeholder="@lang('Weight')"value="{{ $detail->json_params->score->read->weight ?? old('json_params->score->read->weight') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-15 col-md-6">
                                                <div class="flex align-items-center">
                                                    <div class="col-md-2">
                                                        <label>@lang('Write Skill'):</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][write][min]"
                                                            placeholder="@lang('Min score')"value="{{ $detail->json_params->score->write->min ?? old('json_params->score->write->min') }}">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input required type="number" class="form-control"
                                                            name="json_params[score][write][weight]"
                                                            placeholder="@lang('Weight')"value="{{ $detail->json_params->score->write->weight ?? old('json_params->score->write->weight') }}">
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
            </div>

            <section class="mb-15 box_alert">
                <h3>
                    @lang('Danh sách buổi học')
                    <div class="box-tools pull-right">
                        <div style="margin-right: 10px" class="pull-right">
                            <div class="form-group" style="display: flex;">
                                <button type="button" class="btn btn-sm btn-success"
                                    onclick="importFile({{ $detail->id }})"><i class="fa fa-file-excel-o"></i>
                                    @lang('Import buổi học')</button>
                                <input class="form-control" type="file" name="files" id="fileImport"
                                    placeholder="@lang('Select File')">
                            </div>
                        </div>
                    </div>
                </h3>
            </section>

            <div class="box-avaible">
                @if (isset($lessonSylabus) && count($lessonSylabus) > 0)
                    @foreach ($lessonSylabus as $key => $lesson)
                        <div class="row lesson-item">
                            <div class="col-lg-12">
                                <div class="box box-primary collapsed-box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">@lang('Buổi học thứ '){{ $loop->index + 1 }}</h3>
                                        <input type="hidden" value="Buổi học thứ {{ $loop->index + 1 }}"
                                            name="lesson_syllabus[{{ $key }}][ordinal]">
                                        <div class="box-tools pull-right">
                                            {{-- <button type="button" onclick="delete_lesson(this)" class="btn btn-sm btn-danger" ><i class="fa fa-recycle "></i> Xóa buổi</button> --}}
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="nav-tabs-custom">
                                            <div class="tab_offline">
                                                <div class="tab-pane active">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>@lang('Tên buổi học') </label>
                                                            <input class="form-control" type="text"
                                                                name="lesson_syllabus[{{ $key }}][title]"
                                                                placeholder="Tên buổi học"
                                                                value="{{ $lesson->title ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Nội dung buổi học') </label>
                                                            <textarea class="form-control" name="lesson_syllabus[{{ $key }}][content]" cols="30" rows="4"
                                                                placeholder="Nội dung buổi học">{{ $lesson->content ?? '' }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Mục tiêu buổi học')</label>
                                                            <textarea class="form-control" name="lesson_syllabus[{{ $key }}][target]" cols="30" rows="4"
                                                                placeholder="Mục tiêu buổi học">{{ $lesson->target ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Nhiệm vụ giảng viên')</label>
                                                            <textarea class="form-control" name="lesson_syllabus[{{ $key }}][teacher_mission]" cols="30"
                                                                rows="4" placeholder="Nhiệm vụ giảng viên">{{ $lesson->teacher_mission ?? '' }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>@lang('Nhiệm vụ học viên')</label>
                                                            <textarea class="form-control" name="lesson_syllabus[{{ $key }}][student_mission]" cols="30"
                                                                rows="4" placeholder="Nhiệm vụ học viên">{{ $lesson->student_mission ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="lesson_syllabus[{{ $key }}][id]"
                                                        value="{{ $lesson->id }}">
                                                    <div class="col-md-12">
                                                        <label>@lang('Tài liệu')</label>
                                                    </div>
                                                    @if(isset($lesson->json_params->file) && $lesson->json_params->file != '')
                                                        @foreach ($lesson->json_params->file as $key_file => $file_less)
                                                            <div class="col-md-12 file-item">
                                                                <div class="form-group">
                                                                    <div class="col-md-5 pl-0">
                                                                        <input value="{{ $file_less->title ?? '' }}"
                                                                            name="lesson_syllabus[{{ $key }}][file][{{ $key_file }}][title]"
                                                                            class="form-control mb-15" type="text"
                                                                            placeholder="Tiêu đề...">
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <input value="{{ $file_less->link ?? '' }}"
                                                                            name="lesson_syllabus[{{ $key }}][file][{{ $key_file }}][link]"
                                                                            class="form-control" type="text"
                                                                            placeholder="Link...">
                                                                    </div>
                                                                    @if ($key_file)
                                                                        <div class="col-md-2">
                                                                            <button type="button" onclick="delete_file(this)"
                                                                                class="btn btn-sm btn-danger">Xóa</button>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <div class="box-file">

                                                    </div>

                                                    <div class="col-md-12">
                                                        <button type="button" data-num="{{ $key }}"
                                                            onclick="add_file(this)" class="btn btn-primary">
                                                            Thêm tài liệu
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <section class="mb-15 pl-0">
                <button type="button" class="btn btn-primary add-lesson"><i class="fa fa-plus"></i>
                    @lang('Thêm buổi học')
                </button>
                <button type="submit" class="btn btn-info btn-sm pull-right">
                    <i class="fa fa-save"></i> @lang('Save')
                </button>
            </section>
        </form>
    </section>

@endsection

@section('script')
    <script>
        $('.add-lesson').click(function() {
            var currentTime = $.now();
            var countLesson = $("div.lesson-item").length + 1;
            var _targetHTML = `<div class="row lesson-item">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Buổi học thứ `+countLesson+`')</h3>
                            <input type="hidden" value="@lang('Buổi học thứ 1')" name="lesson_syllabus_add[` + currentTime + `][ordinal]">
                            <div class="box-tools pull-right">
                                <button type="button" onclick="delete_lesson(this)" class="btn btn-sm btn-danger" ><i class="fa fa-recycle "></i> Xóa buổi</button>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <div class="tab_offline">
                                    <div class="tab-pane active">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Tên buổi học') </label>
                                                <input class="form-control" type="text" name="lesson_syllabus_add[` +
                currentTime + `][title]" placeholder="Tên buổi học">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Nội dung buổi học') </label>
                                                <textarea class="form-control" name="lesson_syllabus_add[` + currentTime + `][content]" cols="30" rows="4" placeholder="Nội dung buổi học"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Mục tiêu buổi học')</label>
                                                <textarea class="form-control" name="lesson_syllabus_add[` + currentTime + `][target]" cols="30" rows="4" placeholder="Mục tiêu buổi học"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Nhiệm vụ giảng viên')</label>
                                                <textarea class="form-control" name="lesson_syllabus_add[` + currentTime + `][teacher_mission]" cols="30" rows="4" placeholder="Nhiệm vụ giảng viên"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Nhiệm vụ học viên')</label>
                                                <textarea class="form-control" name="lesson_syllabus_add[` + currentTime + `][student_mission]" cols="30" rows="4" placeholder="Nhiệm vụ học viên"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-12 file-item">
                                            <label>@lang('Tài liệu')</label>
                                            <div class="form-group">
                                                <div class="col-md-5 pl-0">
                                                    <input name="lesson_syllabus_add[` + currentTime + `][file][0][title]" class="form-control mb-15" type="text" placeholder="Tiêu đề..." >
                                                </div>
                                                <div class="col-md-5">
                                                    <input name="lesson_syllabus_add[` + currentTime + `][file][0][link]" class="form-control" type="text" placeholder="Link...">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-file">

                                        </div>

                                        <div class="col-md-12">
                                            <button type="button" data-num="` + currentTime + `" onclick="add_file(this)" class="btn btn-primary">
                                                Thêm tài liệu
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            $('.box-avaible').append(_targetHTML);
            $('#lesson').val(countLesson);
        });

        function delete_lesson(th) {
            $(th).parents('.lesson-item').remove();
        }

        function delete_file(th) {
            var countFile = $(th).parents('.lesson-item').find('.file-item').length;
            if (countFile > 1) $(th).parents('.col-md-12').remove();

        }

        function add_file(th) {
            var currentTime = $.now();
            var target = $(th).data('num');
            var _html = `<div class="col-md-12 file-item">
                        <div class="form-group">
                            <div class="col-md-5 pl-0">
                                <input name="lesson_syllabus_add[` + target + `][file][` + currentTime + `][title]" class="form-control mb-15" type="text" placeholder="Tiêu đề..." >
                            </div>
                            <div class="col-md-5">
                                <input name="lesson_syllabus_add[` + target + `][file][` + currentTime + `][link]" class="form-control" type="text" placeholder="Link...">
                            </div>
                            <div class="col-md-2">
                                <button type="button" onclick="delete_file(this)" class="btn btn-sm btn-danger">Xóa</button>
                            </div>
                        </div>
                    </div>`;
            $(th).parents('.lesson-item').find('.box-file').append(_html);
        }

        function importFile(id) {
            var formData = new FormData();
            var file = $('#fileImport')[0].files[0];
            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('syllabuss_id', id);
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url: '{{ route('syllabuss.import_lesson_syllabuss') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload();
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
@endsection
