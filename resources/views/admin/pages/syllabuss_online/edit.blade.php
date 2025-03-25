@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .gra_voca_quiz-item .box.box-primary {
            margin-bottom: 0px !important;
        }

        .gra_voca_quiz-item .box-body {
            margin-bottom: 0px !important;
        }

        .gra_voca_quiz-item {
            margin: 15px;
            border: 1px solid #d2d6de
        }

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

        .deactive, .deactive:focus {
            border: 1px solid red;
            background: #fff6f6;
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
                <div class="col-lg-8">
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Loại chương trình') <small class="text-red">*</small></label>
                                                    <select required name="type"
                                                        class="form-control select2 type_sylabus">
                                                        <option value="elearning">Elearning</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')" value="{{ $detail->name ?? '' }}"
                                                        required>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Giá')</label>
                                                    <input type="number" class="form-control" name="json_params[price]"
                                                        placeholder="@lang('Giá')"
                                                        value="{{ old('json_params[price]') ?? ($detail->json_params->price ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Thời lượng')</label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[thoi_luong]" placeholder="@lang('Thời lượng')"
                                                        value="{{ old('json_params[thoi_luong]') ?? ($detail->json_params->thoi_luong ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Brief')</label>
                                                        <textarea name="json_params[brief]" class="form-control" rows="5" id="brief_vi">{{ old('json_params[brief]') ?? ($detail->json_params->brief ?? '') }}</textarea>
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
                <div class="col-lg-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Publish')</h3>
                        </div>
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border sw_featured d-flex-al-center">
                            <label class="switch ">
                                <input id="sw_featured" name="json_params[is_featured]" value="1" type="checkbox"
                                    {{ isset($detail->json_params->is_featured) && $detail->json_params->is_featured == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <label class="box-title ml-1" for="sw_featured">@lang('Is featured')</label>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image')</h3>
                        </div>
                        <div class="box-body">
                            <div
                                class="form-group box_img_right {{ isset($detail->json_params->image) ? 'active' : '' }}">
                                <div id="image-holder">
                                    @if (isset($detail->json_params->image) && $detail->json_params->image != '')
                                        <img src="{{ $detail->json_params->image }}">
                                    @else
                                        <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                    @endif
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> @lang('Choose')
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden"
                                        name="json_params[image]" placeholder="@lang('Image source')"
                                        value="{{ $detail->json_params->image ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <section class="mb-15">
            </section>
            <div class="box-avaible">
                <div class="row lesson-item">
                    <div class="col-lg-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Danh sách buổi học</h3>
                            </div>

                            <div class="box-body table-responsive">
                                <table class="table table-hover table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th style="width:70px" rowspan="2">@lang('#')</th>
                                            <th rowspan="2">@lang('Buổi học')</th>
                                            <th colspan="2">@lang('Nội dung')</th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @lang('Thông tin buổi học')
                                            </th>
                                            <th>
                                                @lang('Câu hỏi (Quiz)')
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($lessonSylabus) && count($lessonSylabus) > 0)
                                            @foreach ($lessonSylabus as $key => $lesson)
                                                <tr class="valign-middle">
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>
                                                    <td>
                                                        <strong style="font-size: 14px">Buổi học thứ
                                                            {{ $loop->index + 1 }}</strong>
                                                    </td>


                                                    <td style="text-align: center">
                                                        <a href class="show-lesson" data-id_lesson="{{ $lesson->id }}"
                                                            data-toggle="modal" data-target=".bd-example-modal-lg-update"
                                                            title="@lang('Chi tiết')">@lang('Chi tiết')
                                                        </a>

                                                    </td>
                                                    <td style="text-align: center">
                                                        <a href="{{ route('quiz.index', ['lesson_id' => $lesson->id]) }}"
                                                            target="_blank">@lang('Chi tiết') <i
                                                                class="fa fa-arrow-right"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <section class="mb-15 pl-0">
                <button type="button" data-toggle="modal" data-target=".bd-example-modal-lg"
                    class="btn btn-primary add-lesson"><i class="fa fa-plus"></i>
                    @lang('Thêm buổi học')
                </button>
            </section>
        </form>
    </section>
    <div class="modal fade bd-example-modal-lg " data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Thêm mới buổi học
                        </h4>
                    </div>
                    <form action="{{ route('ajax.syllabus_online.savelesson') }}" method="post"
                        class="form-ajax-lesson">
                        @csrf
                        <input type="hidden" name="syllabus_id" value="{{ $detail->id ?? '' }}">
                        <div class="modal-body modal-body-add-leson">

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

    <div class="modal fade bd-example-modal-lg-update " data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            Chỉnh sửa thông tin buổi học
                        </h4>
                    </div>
                    <form action="{{ route('syllabus_online.update_lesson') }}" method="post" class="form-ajax-lesson">
                        @csrf
                        <input type="hidden" name="lesson_id" value="" class="lesson_id">
                        <div class="modal-body modal-body-update-leson">

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
        function delete_file(th) {
            $(th).parents('.col-md-12').remove();
        }

        function add_file(th) {
            var currentTime = $.now();
            var target = $(th).data('num');
            var _html = `<div class="col-md-12">
                        <div class="form-group">
                            <div class="col-md-5 pl-0">
                                <input name="json_params[file][` + currentTime + `][title]" class="form-control mb-15" type="text" placeholder="Tiêu đề..." >
                            </div>
                            <div class="col-md-5">
                                <input name="json_params[file][` + currentTime + `][link]" class="form-control" type="text" placeholder="Link...">
                            </div>
                            <div class="col-md-2">
                                <button type="button" onclick="delete_file(this)" class="btn btn-sm btn-danger">Xóa</button>
                            </div>
                        </div>
                    </div>`;
            $(th).parents('.lesson-item').find('.box-file').append(_html);
        }

        $(document).on('click', '.add-lesson', function() {
            let keyword = $('#search_title_post').val();
            let url = "{{ route('ajax.syllabus_online.addlesson') }}/";
            var _targetHTML = $('.modal-body-add-leson');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    keyword: keyword,
                },
                success: function(response) {
                    _targetHTML.html(response)
                    // console.log(response);

                    $('.lfm').filemanager('image', {
                        prefix: route_prefix
                    });
                    // CKEDITOR.replace('content_vi', ck_options);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    _targetHTML.html(errors);
                }
            });
        });
        $(document).on('click', '.show-lesson', function() {
            let id_lesson = $(this).data('id_lesson');
            $('.lesson_id').val(id_lesson);
            let url = "{{ route('ajax.syllabus_online.showlesson') }}/";
            var _targetHTML = $('.modal-body-update-leson');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id_lesson: id_lesson,
                },
                success: function(response) {
                    _targetHTML.html(response)
                    // console.log(response);

                    $('.lfm').filemanager('image', {
                        prefix: route_prefix
                    });
                    // CKEDITOR.replace('content_vi', ck_options);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    _targetHTML.html(errors);
                }
            });
        });
    </script>
@endsection
