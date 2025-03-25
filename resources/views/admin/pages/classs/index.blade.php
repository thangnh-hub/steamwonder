@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
@endsection

@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>

    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tên lớp') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Nhập tên lớp')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Level')</label>
                                <select name="level_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($levels as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Syllabus')</label>
                                <select name="syllabus_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($syllabuss as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['syllabus_id']) && $params['syllabus_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Course')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['course_id']) && $params['course_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Room')</label>
                                <select name="room_id" id="room_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($rooms as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['room_id']) && $params['room_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái')</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status_class as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Năm học')</label>
                                <input type="text" class="form-control" name="year" placeholder="@lang('Nhập năm học')"
                                    value="{{ isset($params['year']) ? $params['year'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right" href="{{ route(Request::segment(2) . '.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route(Request::segment(2) . '.index') }}?lang={{ $item->lang_locale }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @endif
                    @endforeach
                @endisset

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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('Title')</th>
                                <th rowspan="2">@lang('Syllabus')</th>
                                <th rowspan="2">@lang('Area')</th>
                                <th rowspan="2">@lang('Room')</th>
                                <th rowspan="2">@lang('Period')</th>
                                <th rowspan="2">@lang('Teacher')</th>
                                <th rowspan="2">@lang('Trạng thái')</th>
                                <th rowspan="2">@lang('Sĩ số')</th>
                                <th rowspan="2">@lang('Số buổi')</th>
                                <th colspan="3">@lang('Thời gian')</th>
                                <th rowspan="2">@lang('Action')</th>
                            </tr>
                            <tr>
                                <th style="width:120px">@lang('Bắt đầu')</th>
                                <th style="width:120px">@lang('Dự kiến')</th>
                                <th style="width:120px">@lang('Thực tế')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $teacher = \App\Models\Teacher::where(
                                        'id',
                                        $row->json_params->teacher ?? 0,
                                    )->first();
                                    if (isset($row->schedules->first()->room_id)) {
                                        $room = \App\Models\Room::where(
                                            'id',
                                            $row->schedules->first()->room_id,
                                        )->first();
                                    }
                                    $quantity_student = \App\Models\UserClass::where('class_id', $row->id)
                                        ->get()
                                        ->count();
                                @endphp

                                <tr class="valign-middle">
                                    <td>
                                        <strong
                                            style="font-size: 14px">{{ $row->json_params->name->{$lang} ?? $row->name }}</strong>
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('syllabuss.show', $row->syllabus_id) }}">{{ $row->syllabus->name ?? '' }}</a>
                                    </td>

                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $room->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->period->iorder ?? '' }} ({{ $row->period->start_time ?? '' }} -
                                        {{ $row->period->end_time ?? '' }})
                                    </td>
                                    <td>
                                        {{ $teacher->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ App\Consts::CLASS_STATUS[$row->status] ?? '' }}
                                        {{ $row->status == 'huy' && $row->end_date != '' ? ' ( ' . date('d-m-Y', strtotime($row->end_date)) . ' )' : '' }}
                                    </td>
                                    <td>
                                        {{ $quantity_student }}
                                    </td>

                                    <td>
                                        {{ $row->total_attendance }}/{{ $row->total_schedules }}
                                    </td>
                                    <td class="text-center">
                                        <p class="text-success" style="font-weight: 700">
                                            {{ date('d-m-Y', strtotime($row->day_start)) }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-success" style="font-weight: 700">
                                            {{ date('d-m-Y', strtotime($row->day_end_expected)) }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-success" style="font-weight: 700">
                                            {{ date('d-m-Y', strtotime($row->day_end)) }}</p>
                                    </td>

                                    <td style="width:150px">
                                        @if (!isset($row->syllabus->type) || (isset($row->syllabus->type) && $row->syllabus->type != 'online'))
                                            <p>
                                                <a target="_blank"
                                                    href="{{ route('schedule_class.index', ['class_id' => $row->id]) }}">
                                                    - @lang('Lịch học')
                                                </a>
                                            </p>
                                            <p>
                                                <a target="_blank"
                                                    href="{{ route('scores.index', ['class_id' => $row->id]) }}">
                                                    - @lang('Nhập điểm thi lần 1')
                                                </a>
                                            </p>
                                            @if (isset($row->syllabus->score_type) && $row->syllabus->score_type != 'telc')
                                                <p>
                                                    <a target="_blank"
                                                        href="{{ route('input_score_second.index', ['class_id' => $row->id]) }}">
                                                        - @lang('Nhập điểm thi lần 2')
                                                    </a>
                                                </p>
                                            @endif

                                            <p>
                                                <a target="_blank"
                                                    href="{{ route('evaluationclass.history', ['class_id' => $row->id]) }}">
                                                    - @lang('Nhận xét - Đánh giá')
                                                </a>
                                            </p>
                                            <p>
                                                <a target="_blank"
                                                    href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                    - @lang('Chi tiết lớp')
                                                </a>
                                            </p>
                                            <p>
                                                <a target="_blank"
                                                    href="{{ route('class.editByTeacher', ['id' => $row->id]) }}">
                                                    - @lang('Chỉnh sửa buổi học (Giáo viên)')
                                                </a>
                                            </p>
                                            <p><a target="_blank"
                                                    href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                    - @lang('Danh sách học viên')
                                                </a>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>
    </div>
@endsection
