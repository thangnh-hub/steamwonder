@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .select2.select2-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Please wait')...</p>
    </div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box_alert">
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
        </div>
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        {{-- <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div> --}}
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5 class="fw-bold">Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5 class="fw-bold">Danh sách học sinh</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5 class="fw-bold">Danh sách giáo viên</h5>
                                        </a>
                                    </li>

                                    <button type="submit" class="btn btn-primary btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã lớp') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="code" id="code"
                                                        placeholder="@lang('Mã lớp')"
                                                        value="{{ old('code') ?? ($detail->code ?? '') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="class_name" placeholder="@lang('Title')"
                                                        value="{{ old('name') ?? ($detail->name ?? '') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Area') <small class="text-red">*</small></label>
                                                    <select required name="area_id" class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($areas as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->area_id) && $detail->area_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Room') <small class="text-red">*</small></label>
                                                    <select required name="room_id" class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($rooms as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->room_id) && $detail->room_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Độ tuổi') <small class="text-red">*</small></label>
                                                    <select required name="education_age_id" class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($ages as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->education_age_id) && $detail->education_age_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Chương trình') <small class="text-red">*</small></label>
                                                    <select required name="education_program_id"
                                                        class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($programs as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->education_program_id) && $detail->education_program_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Slot') <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="slot"
                                                        placeholder="@lang('Slot')" min="0"
                                                        value="{{ old('slot') ?? ($detail->slot ?? '') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Order') </label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="@lang('Order')" min="0"
                                                        value="{{ old('iorder') ?? ($detail->iorder ?? 0) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Status') </label>
                                                    <select required name="status" class="form-control select2">
                                                        @foreach ($status as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->status) && $detail->status == $key ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sw_featured">@lang('Là năm cuối')</label>
                                                    <div class="sw_featured d-flex-al-center">
                                                        <label class="switch ">
                                                            <input id="sw_featured" name="is_lastyear" value="1"
                                                                type="checkbox"
                                                                {{ isset($detail->is_lastyear) && $detail->is_lastyear == 1 ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title">@lang('Danh sách học sinh')</h3>
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm btn_modal_student pull-right">Thêm
                                                            học sinh</button>
                                                    </div>
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Mã Học Viên')</th>
                                                                    <th>@lang('Họ tên')</th>
                                                                    <th>@lang('Nickname')</th>
                                                                    <th>@lang('Ngày vào')</th>
                                                                    <th>@lang('Ngày ra')</th>
                                                                    <th>@lang('Trạng thái')</th>
                                                                    <th>@lang('Loại')</th>
                                                                    <th>@lang('Bỏ chọn')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="box_student">
                                                                @isset($detail->students)
                                                                    @foreach ($detail->students as $item)
                                                                        <tr class="item_student"
                                                                            data-id="{{ $item->id }}">
                                                                            <td>{{ $item->student_code }}</td>
                                                                            <td>{{ $item->last_name ?? '' }}
                                                                                {{ $item->first_name ?? '' }}</td>
                                                                            <td>{{ $item->nickname ?? '' }}</td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="student[{{ $item->id }}][start_at]"
                                                                                    value="{{ optional($item->pivot)->start_at ? date('Y-m-d', strtotime($item->pivot->start_at)) : '' }}">
                                                                            </td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="student[{{ $item->id }}][stop_at]"
                                                                                    value="{{ optional($item->pivot)->stop_at ? date('Y-m-d', strtotime($item->pivot->stop_at)) : '' }}">
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control select2 w-100"
                                                                                    name="student[{{ $item->id }}][status]">
                                                                                    @foreach ($status as $val)
                                                                                        <option value="{{ $val }}"
                                                                                            {{ isset($item->pivot->status) && $item->pivot->status == $val ? 'selected' : '' }}>
                                                                                            {{ __($val) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control select2 w-100"
                                                                                    name="student[{{ $item->id }}][type]">
                                                                                    @foreach ($type_student as $val)
                                                                                        <option value="{{ $val }}"
                                                                                            {{ $item->pivot->type == $val ? 'selected' : '' }}>
                                                                                            {{ __($val) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <input type="checkbox" checked
                                                                                    onclick="this.parentNode.parentNode.remove()">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane " id="tab_3">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box">
                                                    <div class="box-header">
                                                        <h3 class="box-title">@lang('Danh sách giáo viên')</h3>
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm btn_modal_teacher pull-right">Thêm
                                                            giáo
                                                            viên</button>
                                                    </div>
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky ">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th>@lang('Giáo viên')</th>
                                                                    <th>@lang('Ngày bắt đầu')</th>
                                                                    <th>@lang('Ngày kết thúc')</th>
                                                                    <th>@lang('GVCN')</th>
                                                                    <th>@lang('Status')</th>
                                                                    <th>@lang('Bỏ chọn')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="box_teacher">
                                                                @isset($detail->teacher)
                                                                    @foreach ($detail->teacher as $item)
                                                                        <tr class="item_teacher"
                                                                            data-id="{{ $item->id }}">
                                                                            <td>{{ $item->name ?? '' }} </td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="teacher[{{ $item->id }}][start_at]"
                                                                                    value="{{ optional($item->pivot)->start_at ? date('Y-m-d', strtotime($item->pivot->start_at)) : '' }}">
                                                                            </td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="teacher[{{ $item->id }}][stop_at]"
                                                                                    value="{{ optional($item->pivot)->stop_at ? date('Y-m-d', strtotime($item->pivot->stop_at)) : '' }}">
                                                                            </td>

                                                                            <td>
                                                                                <div class="sw_featured d-flex-al-center">
                                                                                    <label class="switch">
                                                                                        <input
                                                                                            class="teacher_main about-banner"
                                                                                            name="teacher[{{ $item->id }}][is_teacher_main]"
                                                                                            type="checkbox" value="1"
                                                                                            {{ isset($item->pivot->is_teacher_main) && $item->pivot->is_teacher_main == '1' ? 'checked' : '' }}>
                                                                                        <span class="slider round"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="w-100">
                                                                                    <select class="form-control select2 w-100"
                                                                                        name="teacher[{{ $item->id }}][status]">
                                                                                        @foreach ($status as $val)
                                                                                            <option
                                                                                                value="{{ $val }}"
                                                                                                {{ isset($item->pivot->status) && $item->pivot->status == $val ? 'selected' : '' }}>
                                                                                                {{ __($val) }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <input type="checkbox" checked
                                                                                    onclick="this.parentNode.parentNode.remove()">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-primary pull-right btn-sm"><i
                                    class="fa fa-floppy-o"></i>
                                @lang('Save')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="modal_teacher" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12">@lang('Thêm giáo viên vào lớp')</h3>
                        </h3>
                    </div>
                    <div class="box_alert_modal">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Giáo viên') <small class="text-red">*</small></label>
                                    <select required id="select_teacher" name="teacher_id[]" multiple
                                        class="form-control select2  w-100">
                                        <option value="" disabled>@lang('Please select')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Thời gian bắt đầu')</label>
                                    <input class="form-control start_at" type="date" name="start_at"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Thời gian kết thúc')</label>
                                    <input class="form-control stop_at" type="date" name="stop_at" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info mr-10 btn_confirm_teacher">
                            <i class="fa fa-check"></i> @lang('Xác nhận')
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-remove"></i> @lang('Close')
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_student" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12">@lang('Thêm học sinh vào lớp')</h3>
                        </h3>
                    </div>
                    <div class="box_alert_modal">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Chọn học sinh') <small class="text-red">*</small></label>
                                    <select required id="select_student" name="student_id[]" multiple
                                        class="form-control select2  w-100">
                                        <option value="">@lang('Please select')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Thời gian bắt đầu')</label>
                                    <input class="form-control start_at" type="date" name="start_at"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Thời gian kết thúc')</label>
                                    <input class="form-control stop_at" type="date" name="stop_at" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info mr-10 btn_confirm_student">
                            <i class="fa fa-check"></i> @lang('Xác nhận')
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-remove"></i> @lang('Close')
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('script')
    <script>
        var students = @json($students);
        var teachers = @json($teachers);


        // Thêm giáo viên vào lớp
        $(document).on('click', '.btn_confirm_teacher', function() {
            var arr_id_teacher = $('#select_teacher').val();
            var start_at = $('#modal_teacher .start_at').val();
            var stop_at = $('#modal_teacher .stop_at').val();
            var selectedTeachers = arr_id_teacher.map(id => {
                return teachers.find(teacher => teacher.id == id);
            });
            selectedTeachers.forEach(teacher => {
                if (teacher) {
                    $('.box_teacher').append(`
                        <tr class="item_teacher" data-id="${teacher.id}">
                            <td> ${teacher.name}</td>
                            <td><input type="date" class="form-control"
                                    name="teacher[${teacher.id}][start_at]"
                                    value="${start_at}">
                            </td>
                            <td><input type="date" class="form-control"
                                    name="teacher[${teacher.id}][stop_at]"
                                    value="${stop_at}">
                            </td>

                            <td>
                                <div class="sw_featured d-flex-al-center">
                                    <label class="switch">
                                        <input
                                            class="teacher_main about-banner"
                                            name="teacher[${teacher.id}][is_teacher_main]"
                                            type="checkbox" value="1">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="w-100">
                                    <div class="w-100">
                                        <select class="form-control select2 w-100"
                                            name="teacher[${teacher.id}][status]">
                                            @foreach ($status as $val)
                                                <option value="{{ $val }}">{{ __($val) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" checked
                                    onclick="this.parentNode.parentNode.remove()">
                            </td>
                        </tr>
                    `);
                }
            });
            $('.select2').select2();
            $('#select_teacher').val([]).trigger('change');
            $('#modal_teacher').modal('hide');
        })
        $(document).on('click', '.btn_modal_teacher', function() {
            let dataIds = $(".item_teacher").map(function() {
                return $(this).data("id");
            }).get();

            let optionsHtml = teachers.map(teacher => {
                let isDisabled = dataIds.includes(teacher.id); // Kiểm tra ID có trong mảng dataIds
                return `<option value="${teacher.id}" ${isDisabled ? 'disabled' : ''}>${teacher.name}</option>`;
            }).join("");
            $("#select_teacher").html(optionsHtml);
            $('#modal_teacher').modal('show');
        });
        // Thêm học sinh vào lớp
        $(document).on('click', '.btn_modal_student', function() {
            let dataIds = $(".item_student").map(function() {
                return $(this).data("id");
            }).get();
            let optionsHtml = students.map(student => {
                let isDisabled = dataIds.includes(student.id); // Kiểm tra ID có trong mảng dataIds
                return `<option value="${student.id}" ${isDisabled ? 'disabled' : ''}>${student.last_name} ${student.first_name} - ${student.nickname}</option>`;
            }).join("");
            $("#select_student").html(optionsHtml);
            $('#modal_student').modal('show');
        });
        $(document).on('click', '.btn_confirm_student', function() {
            var arr_id_student = $('#select_student').val();
            var start_at = $('#modal_student .start_at').val();
            var stop_at = $('#modal_student .stop_at').val();
            var selectedStudent = arr_id_student.map(id => {
                return students.find(student => student.id == id);
            });
            console.log(selectedStudent);

            selectedStudent.forEach(student => {
                if (student) {
                    $('.box_student').append(`
                        <tr class="item_student" data-id="${student.id}">
                            <td>${student.student_code}</td>
                            <td>${student.last_name} ${student.first_name} </td>
                            <td>${student.nickname}</td>
                            <td><input type="date" class="form-control"
                                    name="student[${student.id}][start_at]"
                                    value="${start_at}">
                            </td>
                            <td><input type="date" class="form-control"
                                    name="student[${student.id}][stop_at]"
                                    value="${stop_at}">
                            </td>
                            <td>
                                <select class="form-control select2 w-100"
                                    name="student[${student.id}][status]">
                                    @foreach ($status as $val)
                                        <option value="{{ $val }}"
                                            {{ __($val) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control select2 w-100"
                                    name="student[${student.id}][type]">
                                    @foreach ($type_student as $val)
                                        <option value="{{ $val }}"
                                            {{ __($val) }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" checked
                                    onclick="this.parentNode.parentNode.remove()">
                            </td>
                        </tr>
                    `);
                }
            });
            $('.select2').select2();
            $('#select_student').val([]).trigger('change');
            $('#modal_student').modal('hide');
        })

        // Chọn GVCN là duy nhất
        $(document).on('change', '.teacher_main', function() {
            if ($(this).is(':checked')) {
                $('.teacher_main').not(this).prop('checked', false);
            }
        });
    </script>
@endsection
