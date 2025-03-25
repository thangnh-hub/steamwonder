@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .day-repeat-select {
            pointer-events: none;
        }

        .modal-header {
            display: flex;
            align-items: center;
            color: #fff;
            background-color: #00A157;
        }

        .pointer-none {
            pointer-events: none;
            background: #eee;
        }

        .link_doc a {
            text-decoration: underline !important;
        }

        .bg-highlight {
            background: #367fa9;
            color: #fff !important;
        }

        .mr-2 {
            margin-right: 10px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .table_leson .select2-container {
            width: 100% !important;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .overflow-auto {
            width: 100%;
            overflow-x: auto;
        }

        .overflow-auto::-webkit-scrollbar {
            width: 5px !important;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: rgb(107, 144, 218);
            border-radius: 10px;
        }

        .table_leson {
            width: 1600px;
            max-width: unset;
        }

        .table_leson td:first-child {
            width: 190px;
        }

        .table_leson thead {
            background: rgb(107, 144, 218);
            color: #fff
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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Lịch học</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>Danh sách học viên</h5>
                                        </a>
                                    </li>
                                    @if (isset($student) && count($student) > 0)
                                        <li class="">
                                            <a href="#tab_4" data-toggle="tab">
                                                <h5>Điểm danh</h5>
                                            </a>
                                        </li>
                                    @endif
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input data-id="{{ $detail->id }}" type="text" class="form-control"
                                                        id="class_name" name="name" placeholder="@lang('Title')"
                                                        value="{{ old('name') ?? $detail->name }}" required>
                                                    <p class="check-error text-danger"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Trạng thái') <small class="text-red">*</small></label>
                                                    <select class="form-control select2" name="status" required>
                                                        @foreach ($status_class as $key => $val)
                                                            <option
                                                                {{ isset($detail->status) && $detail->status == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Start date') <small class="text-red">*</small></label>
                                                    <input disabled type="date" class="form-control"
                                                        placeholder="@lang('Start date')"
                                                        value="{{ old('start_date') ?? $detail->start_date }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('End date') (Hủy)</label>
                                                    <input type="date" class="form-control"
                                                        placeholder="@lang('Start date')" name="end_date"
                                                        value="{{ $detail->end_date != '' ? date('Y-m-d', strtotime($detail->end_date)) : old('end_date') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Buổi học dự kiến')</label>
                                                    <input type="number" class="form-control"
                                                        placeholder="@lang('Buổi học dự kiến')" name="lesson_number"
                                                        value="{{ $detail->lesson_number != '' ? $detail->lesson_number : count($list_lesson) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Lớp thường/Lớp đặc biệt')</label>
                                                    <select name="type_normal_special" class="form-control select2">
                                                        @foreach (App\Consts::type_normal_special as $key => $val)
                                                            <option
                                                                {{ isset($detail->type_normal_special) && $detail->type_normal_special == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Class Type') <small class="text-red">*</small></label>
                                                    <select disabled="" class="form-control select2">
                                                        @foreach (App\Consts::CLASS_TYPE as $key => $val)
                                                            <option
                                                                {{ isset($detail->type) && $detail->type == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Area')<small class="text-red">*</small></label>
                                                    <select disabled name="area_id" class=" form-control select2 area_id">
                                                        <option value="">@lang('Area')</option>
                                                        @foreach ($area as $val)
                                                            @if (in_array($val->id, $area_user))
                                                                <option
                                                                    {{ isset($detail->area_id) && $detail->area_id == $val->id ? 'selected' : '' }}
                                                                    value="{{ $val->id }}">
                                                                    {{ $val->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Teacher') <small class="text-red">*</small></label>
                                                    <select name="json_params[teacher]"
                                                        class="form-control select2 tab_1_select_day_repeat">
                                                        <option value="">@lang('Teacher')</option>
                                                        @foreach ($teacher as $val)
                                                            <option
                                                                {{ isset($detail->json_params->teacher) && $detail->json_params->teacher == $val->id ? 'selected' : '' }}
                                                                value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @php
                                                if (
                                                    $detail->assistant_teacher !== null &&
                                                    $detail->assistant_teacher !== ' '
                                                ) {
                                                    $assistantTeacherArray = json_decode(
                                                        $detail->assistant_teacher,
                                                        true,
                                                    );
                                                }
                                            @endphp
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Assistant teacher'):</label>
                                                    <select name="assistant_teacher[]" class="form-control select2"
                                                        multiple>
                                                        @foreach ($teacher as $val)
                                                            <option
                                                                {{ isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray) ? 'selected' : '' }}
                                                                value="{{ $val->id }}">
                                                                {{ $val->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Period')<small class="text-red">*</small></label>
                                                    <select disabled class=" form-control select2">
                                                        <option value="">@lang('Period')</option>
                                                        @foreach ($period as $val)
                                                            <option
                                                                {{ isset($detail->period_id) && $detail->period_id == $val->id ? 'selected' : '' }}
                                                                value="{{ $val->id }}">
                                                                {{ $val->iorder }} ({{ $val->start_time }} -
                                                                {{ $val->end_time }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Room')<small class="text-red">*</small></label>
                                                    <select name="room_id" class="room_change form-control select2">
                                                        <option value="">@lang('Room')</option>
                                                        @foreach ($room as $val)
                                                            @if ($val->area_id == $detail->area_id)
                                                                <option
                                                                    {{ isset($detail->room_id) && $detail->room_id == $val->id ? 'selected' : '' }}
                                                                    value="{{ $val->id }}">
                                                                    {{ $val->name }} </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày thi')</label>
                                                    <input type="date" name="day_exam" class="form-control"
                                                        placeholder="@lang('Ngày thi')"
                                                        value="{{ $detail->day_exam ?? old('day_exam') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-12 day-repeat-select">
                                                <div class="form-group">
                                                    <label>@lang('Day repeat') <small class="text-red">*</small></label>
                                                    <select class="form-control select2 select2-multy " multiple
                                                        name="json_params[day_repeat][]">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach (App\Consts::DAY_REPEAT as $key => $val)
                                                            <option
                                                                {{ isset($detail->json_params->day_repeat) && in_array($key, $detail->json_params->day_repeat) ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Level')<small class="text-red">*</small></label>
                                                    <select disabled="" class=" form-control select2">
                                                        <option value="">@lang('Level')</option>
                                                        @foreach ($levels as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->level_id) && $detail->level_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Syllabus')<small class="text-red">*</small></label>
                                                    <select name="syllabus_id" class=" form-control select2">
                                                        <option value="">@lang('Syllabus')</option>
                                                        @foreach ($syllabus as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->syllabus_id) && $detail->syllabus_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Course')<small class="text-red">*</small></label>
                                                    <select disabled="" class=" form-control select2">
                                                        <option value="">@lang('Course')</option>
                                                        @foreach ($course as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->course_id) && $detail->course_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_2">
                                        <div class="overflow-auto mt-15">
                                            <table class="table  table-bordered table_leson">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Lesson')</th>
                                                        <th>@lang('Status')</th>
                                                        <th>@lang('Trạng thái chuyển giao')</th>
                                                        <th>@lang('Date-time')</th>
                                                        <th>@lang('Period')</th>
                                                        <th>@lang('Room')</th>
                                                        <th>@lang('Teacher')</th>
                                                        <th>@lang('Giáo viên phụ')</th>
                                                        <th>@lang('Ghi chú')</th>
                                                    </tr>
                                                </thead>
                                                @if (isset($list_lesson))
                                                    <tbody class="lesson_body">
                                                        @foreach ($list_lesson as $key => $lesson)
                                                            <tr
                                                                class="{{ $lesson->is_add_more == 1 ? 'bg-highlight' : '' }}">
                                                                <td>
                                                                    {{ ++$key }}
                                                                    {{ $lesson->is_add_more == 1 ? '(Bổ sung)' : '' }}
                                                                    {{-- @if ($list_lesson->count() > 0 && $lesson->status != 'dadiemdanh') --}}
                                                                    <button type="button"
                                                                        data-lesson-id="{{ $lesson->id }}"
                                                                        class="btn btn-sm btn-danger pull-right del_lesson">Xóa
                                                                        buổi</button>
                                                                    {{-- @endif --}}
                                                                </td>
                                                                <td
                                                                    class="{{ App\Consts::SCHEDULE_STATUS_COLOR[$lesson->status] }}">
                                                                    {{ App\Consts::SCHEDULE_STATUS[$lesson->status] }}
                                                                    <input type="hidden"
                                                                        name="lesson[{{ $key }}][id]"
                                                                        value="{{ $lesson->id }}">
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][transfer_status]"
                                                                            class="form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($transfer_status as $keys => $val)
                                                                                <option
                                                                                    {{ isset($lesson->transfer_status) && $lesson->transfer_status == $keys ? 'selected' : '' }}
                                                                                    value="{{ $keys }}">
                                                                                    {{ __($val) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group d-flex align-items-center">
                                                                        <input name="lesson[{{ $key }}][date]"
                                                                            type="date" value="{{ $lesson->date }}"
                                                                            class="form-control mr-2 {{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }}">
                                                                        <label>{{ date('l', strtotime($lesson->date)) }}</label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][period_id]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($period as $val)
                                                                                <option
                                                                                    {{ isset($lesson->period_id) && $lesson->period_id == $val->id ? 'selected' : '' }}
                                                                                    value="{{ $val->id }}">
                                                                                    {{ $val->iorder }}
                                                                                    ({{ $val->start_time }} -
                                                                                    {{ $val->end_time }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][room_id]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_room_change lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($room as $val)
                                                                                @if ($val->area_id == $lesson->area_id)
                                                                                    <option
                                                                                        {{ isset($lesson->room_id) && $lesson->room_id == $val->id ? 'selected' : '' }}
                                                                                        value="{{ $val->id }}">
                                                                                        {{ $val->name }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][teacher_id]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} teacher_id_select lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($teacher as $val)
                                                                                <option
                                                                                    {{ isset($lesson->teacher_id) && $lesson->teacher_id == $val->id ? 'selected' : '' }}
                                                                                    value="{{ $val->id }}">
                                                                                    {{ $val->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                </td>
                                                                <td>
                                                                    @php
                                                                        if (
                                                                            $lesson->assistant_teacher !== null &&
                                                                            $lesson->assistant_teacher !== ' '
                                                                        ) {
                                                                            $assistantTeacherArray = json_decode(
                                                                                $lesson->assistant_teacher,
                                                                                true,
                                                                            );
                                                                        }
                                                                    @endphp
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][assistant_teacher][]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            <option value="0">
                                                                                @lang('Please select')
                                                                            </option>
                                                                            @foreach ($teacher as $val)
                                                                                <option
                                                                                    {{ isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray) ? 'selected' : '' }}
                                                                                    value="{{ $val->id }}">
                                                                                    {{ $val->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control"
                                                                        name="lesson[{{ $key }}][note]"
                                                                        value="{{ $lesson->json_params->note ?? '' }}">
                                                                </td>

                                                            </tr>
                                                            {{-- @endif --}}
                                                        @endforeach
                                                    </tbody>
                                                @endif
                                            </table>
                                            <button data-lesson="{{ $key }}"
                                                class="form-group btn btn-primary mb-2 add_lesson" type="button"><i
                                                    class="fa fa-plus"></i> @lang(' Thêm buổi học')</button>
                                        </div>

                                    </div>

                                    <div class="tab-pane " id="tab_3">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title">@lang('Danh sách học viên')</h3>
                                                    </div><!-- /.box-header -->
                                                    {{-- phần ds --}}
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky">
                                                            <thead>
                                                                <tr>
                                                                    <th>Mã Học Viên</th>
                                                                    <th>Tên</th>
                                                                    <th>Ngày sinh</th>
                                                                    <th>CCCD</th>
                                                                    <th>Cơ sở</th>
                                                                    <th>Lớp đã học</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Chọn ngày vào lớp</th>
                                                                    <th>Bỏ chọn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="post_related">
                                                                @isset($student)
                                                                    @foreach ($student as $item)
                                                                        <tr>
                                                                            <td>
                                                                                <a target="_blank" data-toggle="tooltip"
                                                                                    title="@lang('Xem chi tiết')"
                                                                                    data-original-title="@lang('Xem chi tiết')"
                                                                                    href="{{ route('students.show', $item->user->id ?? '') }}">
                                                                                    {{ $item->user->admin_code ?? '' }}
                                                                                    <i class="fa fa-eye"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td>
                                                                                <a target="_blank" data-toggle="tooltip"
                                                                                    title="@lang('Update')"
                                                                                    data-original-title="@lang('Update')"
                                                                                    href="{{ route('students.edit', $item->user->id ?? '') }}">
                                                                                    {{ $item->user->name ?? '' }}
                                                                                    <i class="fa fa-pencil"></i>
                                                                                </a>

                                                                            </td>
                                                                            <td>{{ $item->user->birthday != '' ? date('d-m-Y', strtotime($item->user->birthday)) : '' }}
                                                                            </td>
                                                                            <td>{{ $item->user->json_params->cccd ?? '' }}
                                                                            </td>
                                                                            <td>{{ $item->area_name ?? '' }}</td>
                                                                            <td>
                                                                                {{-- @if (isset($item->user->classs))
                                                                                    <ul>
                                                                                        @foreach ($item->user->classs as $i)
                                                                                            <li>
                                                                                                {{ $i->name }}
                                                                                                ({{ __($i->pivot->status ?? '') }})
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif --}}
                                                                                @php
                                                                                    $list_class = $item->user->allClassesWithStatus();
                                                                                @endphp
                                                                                
                                                                                @if (isset($list_class))
                                                                                    <ul>
                                                                                        @foreach ($list_class as $i)
                                                                                            <li>
                                                                                                {{ $i->name }}
                                                                                                ({{ __($i->pivot_status  ?? '') }})
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control"
                                                                                    name="user_class_status[]" id="">
                                                                                    @foreach (App\Consts::USER_CLASS_STATUS as $k => $us_status)
                                                                                        <option
                                                                                            {{ isset($item->status) && $item->status == $k ? 'selected' : '' }}
                                                                                            value="{{ $k }}">
                                                                                            {{ $us_status }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control" type="date"
                                                                                    name="day_in_class[]"
                                                                                    value="{{ $item->json_params->day_in_class ?? date('Y-m-d', strtotime($item->created_at)) }}">
                                                                            </td>
                                                                            <td>
                                                                                <input name="student[]" type="checkbox"
                                                                                    value="{{ $item->user->id ?? '' }}"
                                                                                    class="mr-15 related_post_item cursor"
                                                                                    autocomplete="off" checked>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div><!-- /.box-body -->
                                                </div><!-- /.box -->
                                            </div>
                                            <div class="col-xs-12">
                                                <h4 style="padding-bottom:10px;">Thêm học viên vào lớp</h4>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <select style="width:100%" class="form-control select2"
                                                            name="" id="search_class_student">
                                                            <option value="">Lớp...</option>
                                                            @foreach ($list_class as $clas)
                                                                <option value="{{ $clas->id }}">
                                                                    {{ $clas->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" id="search_title_post"
                                                            class="form-control pull-right"
                                                            placeholder="Tên học viên, CCCD..." autocomplete="off">

                                                    </div>
                                                    <div class=" col-md-4">
                                                        <div class="input-group">
                                                            <input type="text" id="search_code_post"
                                                                class="form-control pull-right"
                                                                placeholder="Mã học viên..." autocomplete="off">
                                                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-default btn_search">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12" style="padding-top: 5px">
                                                        <table class="table table-hover sticky">
                                                            <thead>
                                                                <tr>
                                                                    <th>Mã học viên</th>
                                                                    <th>Tên</th>
                                                                    <th>Ngày sinh</th>
                                                                    <th>CCCD</th>
                                                                    <th>Cơ sở</th>
                                                                    <th>Lớp đã học</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Ngày vào lớp</th>
                                                                    <th>Chọn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="post_available">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <a href="{{ route('schedule_class.index', ['class_id' => $detail->id]) }}">@lang('Xem thông tin điểm danh')
                                            - {{ $detail->name }}</a>
                                    </div>
                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12" id="exampleModalLabel">{{ __('Take attendance') }}
                        </h3>

                    </div>
                    <div class="modal-body">
                        <form action="{{ route('attendances.save') }}" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            @csrf
                            <div class="overflow-auto mt-15 mb-15">

                                <table class="table table-hover table-bordered table_leson">
                                    <thead>
                                        <tr>
                                            <th>@lang('Order')</th>
                                            <th>@lang('Class')</th>
                                            <th>@lang('Student')</th>
                                            <th>@lang('Avatar')</th>
                                            <th>@lang('Home Work')</th>
                                            <th>@lang('Updated at')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Note status')</th>
                                            {{-- <th>@lang('Score')</th> --}}
                                            <th>@lang('Note')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="show_attendance">

                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $('#class_name').on('blur', function() {
            var ten = $(this).val();
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{ route('ajax.nameclass.unique') }}',
                data: {
                    ten: ten,
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    if (response.data == true) {
                        $('.check-error').text('Tên lớp đã tồn tại. Vui lòng chọn tên khác.');
                    } else $('.check-error').text('');
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        });
        $('.room_change').change(function() {
            $('.lesson_room_change').val($(this).val()).trigger('change');
        })
        $('.tab_1_select_day_repeat').change(function() {
            var _val = $(this).val();
            $('.teacher_id_select').val(_val).trigger('change');
        })

        function _delete_lesson(th) {
            $(th).parents('.more_lesson').remove();
        }
        $('.del_lesson').click(function(e) {
            e.preventDefault();
            var _this = $(this);
            var schedule_id = _this.attr("data-lesson-id");
            let url = "{{ route('ajax.lessonDestroy') }}/";

            // Thêm hộp thoại xác nhận
            if (confirm("Bạn có chắc chắn muốn xóa buổi học này không?")) {
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        schedule_id: schedule_id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            alert('Xóa thành công buổi học');
                            _this.parents('tr').fadeOut(800, function() {
                                _this.parents('tr').remove();
                            });
                        } else {
                            alert('Không thể xóa buổi học!');
                        }
                    },
                    error: function(response) {
                        // Lấy lỗi
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });

        $('.add_lesson').click(function() {
            var _count = Number($(this).attr('data-lesson'));
            var _html = `<tr class="more_lesson">
                <td>
                     <button onclick="_delete_lesson(this)" type="button" class="btn btn-sm btn-danger">Xóa</button>
                </td>
                <td class="{{ App\Consts::SCHEDULE_STATUS_COLOR['chuahoc'] }}">
                    {{ App\Consts::SCHEDULE_STATUS['chuahoc'] }}
                    <input required type="hidden" name="lesson[` + (_count + 1) + `][id]" value="">
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][transfer_status]" class="form-control select2">
                            @foreach ($transfer_status as $keys => $val)
                                <option value="{{ $keys }}"> {{ __($val) }} </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group d-flex align-items-center">
                        <input required name="lesson[` + (_count + 1) + `][date]" type="date" value="" class="form-control mr-2">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][period_id]" class="lesson_period form-control select2">
                            @foreach ($period as $val)
                                <option {{ isset($detail->period_id) && $detail->period_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                    {{ $val->iorder }} ({{ $val->start_time }} - {{ $val->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                </td>

                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][room_id]" class="lesson_period form-control select2  lesson_room_change">
                            @foreach ($room as $val)
                                @if (isset($lesson->area_id) && $val->area_id == $lesson->area_id)
                                <option {{ isset($lesson->room_id) && $lesson->room_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][teacher_id]" class="lesson_period form-control select2 teacher_id_select">
                            @foreach ($teacher as $val)
                                <option {{ isset($detail->json_params->teacher) && $detail->json_params->teacher == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>


                <td>
                    <div class="form-group">
                        <select
                            name="lesson[{{ $key }}][assistant_teacher][]"
                            class="{{ isset($lesson->status) && $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2">
                            <option value="0">
                                @lang('Please select')
                            </option>
                            @foreach ($teacher as $val)
                                <option
                                    {{ isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray) ? 'selected' : '' }}
                                    value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control" name="lesson[` + (_count + 1) + `][note]">
                </td>
            </tr>`;
            $('.lesson_body').append(_html);
            $(".select2").select2();
            $('.lfm').filemanager('other', {
                // prefix: route_prefix
                prefix: '{{ route('ckfinder_browser') }}'
            });
            $('.add_lesson').attr('data-lesson', _count + 1);
        })
        $(document).ready(function() {

            // Fill Available Blocks by template
            $(document).on('click', '.btn_search', function() {
                let keyword = $('#search_title_post').val();
                let taxonomy_id = $('#search_code_post').val();
                let class_student = $('#search_class_student').val();
                let _targetHTML = $('#post_available');
                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().substr(0, 10);
                _targetHTML.html('');
                let checked_post = [];
                $('input[name="student[]"]:checked').each(function() {
                    checked_post.push($(this).val());
                });

                let url = "{{ route('cms_student.search') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        admin_code: taxonomy_id,
                        other_list: checked_post,
                        class_id: class_student,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            // console.log(list);
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _url_show = "{{ route('students.show', ':id') }}"
                                        .replace(':id', item.id);
                                    _url_edit = "{{ route('students.edit', ':id') }}"
                                        .replace(':id', item.id);

                                    _item += '<tr>';
                                    _item += '<td><a target="_blank" href="' +
                                        _url_show + '">' + item.admin_code +
                                        ' <i class="fa fa-eye"></i></a></td>';
                                    _item += '<td><a target="_blank" href="' +
                                        _url_edit + '">' + item.name +
                                        ' <i class="fa fa-pencil"></i></a></td>';
                                    _item += '<td>' + item.birthday + '</td>';
                                    _item += '<td>' + item.json_params.cccd + '</td>';
                                    _item += '<td>' + item.area_name + '</td>';
                                    _item += '<td>' + item.class_to_str + '</td>';
                                    _item +=
                                        '<td><select class="form-control" name="user_class_status[]" id="">@foreach (App\Consts::USER_CLASS_STATUS as $k => $us_status)<option value="{{ $k }}">{{ $us_status }}</option>@endforeach</select></td>';
                                    _item +=
                                        '<td><input class="form-control" type="date" name="day_in_class[]" value="' +
                                        formattedDate + '"></td>';
                                    _item +=
                                        '<td><input name="student[]" type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item cursor" autocomplete="off"></td>';

                                    _item += '</tr>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<tr><td colspan="5">' + response.message +
                                '</td></tr>');
                        }
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            });

            // Checked and unchecked item event
            $(document).on('click', '.related_post_item', function() {
                let ischecked = $(this).is(':checked');
                let _root = $(this).closest('tr');
                let _targetHTML;

                if (ischecked) {
                    _targetHTML = $("#post_related");
                } else {
                    _targetHTML = $("#post_available");
                }
                _targetHTML.append(_root);
            });

            var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';

            $('.add-gallery-image').click(function(event) {
                let keyRandom = new Date().getTime();
                let elementParent = $('.list-gallery-image');
                let elementAppend =
                    '<div class="col-lg-3 col-md-3 col-sm-4 mb-1 gallery-image my-15">';
                elementAppend += '<img width="150px" height="150px" class="img-width"';
                elementAppend += 'src="' + no_image_link + '">';
                elementAppend += '<input type="text" name="json_params[gallery_image][' + keyRandom +
                    ']" class="hidden" id="gallery_image_' + keyRandom +
                    '">';
                elementAppend += '<div class="btn-action">';
                elementAppend +=
                    '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
                    keyRandom +
                    '" data-type="cms-image">';
                elementAppend += '<i class="fa fa-upload"></i>';
                elementAppend += '</span>';
                elementAppend += '<span class="btn btn-sm btn-danger btn-remove">';
                elementAppend += '<i class="fa fa-trash"></i>';
                elementAppend += '</span>';
                elementAppend += '</div>';
                elementParent.append(elementAppend);

                $('.lfm').filemanager('image', {
                    prefix: route_prefix
                });
            });
            // Change image for img tag gallery-image
            $('.list-gallery-image').on('change', 'input', function() {
                let _root = $(this).closest('.gallery-image');
                var img_path = $(this).val();
                _root.find('img').attr('src', img_path);
            });

            // Delete image
            $('.list-gallery-image').on('click', '.btn-remove', function() {
                // if (confirm("@lang('confirm_action')")) {
                let _root = $(this).closest('.gallery-image');
                _root.remove();
                // }
            });

            $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
                $(this).find('.btn-action').show();
            });
            $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
                $(this).find('.btn-action').hide();
            });

            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.input[type=hidden]').val("");
            });

            $('.add_space').on('click', function() {
                var _item =
                    "<input type='text' class='form-control form-group ' name='json_product[space][]' placeholder='Nhập không gian' value=''>";
                $('.defautu_space').append(_item);
            });

            $('.add_convenient').on('click', function() {
                var _item = "";
                _item += "<div class='col-md-3 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][icon][]' placeholder='Icon' value=''>";
                _item += "</div>";
                _item += "<div class='col-md-9 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][name][]' placeholder='Nhập tiện nghi' value=''>";
                _item += "</div>";

                $('.defaunt_convenient').append(_item);
            });
            $('.ck_ty').on('change', function() {
                if ($("#form_product input[name='type']:checked").val() == 2) {
                    $('#type_price').attr("disabled", "true");
                } else {
                    $('#type_price').removeAttr('disabled');

                }
            });

            $('#search_code_post').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('.btn_search').click();
                }
            });
            $('#search_title_post').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('.btn_search').click();
                }
            });
            $('#search_class_student').on('change', function(event) {
                event.preventDefault();
                $('.btn_search').click();
            });
        });
    </script>
@endsection
