@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        #alert-config{

        }
        .background-warning-yellow {
            background: #f9e7a2;
        }

        .font-weight-bold {
            font-weight: bold;
            font-size: 16px
        }

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
        </h1>
    </section>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div id="alert-config"></div>
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('report.class.is.ending') }}" method="GET">
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
                                <label>@lang('Giáo viên')</label>
                                <select name="teacher_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_teacher as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['teacher_id']) && $params['teacher_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái')</label>
                                <select name="ketoan_xacnhan" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    <option {{ isset($params['ketoan_xacnhan']) && $params['ketoan_xacnhan'] == "daxacnhan" ? 'selected' : '' }} value="daxacnhan">Đã xác nhận</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.class.is.ending') }}">
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
                    <table class="table  table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('Title')</th>
                                <th rowspan="2">@lang('Level')</th>
                                <th rowspan="2">@lang('Danh sách học viên')</th>
                                <th rowspan="2">@lang('Syllabus')</th>
                                <th rowspan="2">@lang('Area')</th>
                                <th rowspan="2">@lang('Room')</th>
                                <th rowspan="2">@lang('Period')</th>
                                <th rowspan="2">@lang('Teacher')</th>
                                <th rowspan="2">@lang('Trạng thái')</th>
                                <th colspan="3">@lang('Số buổi học')</th>
                                <th rowspan="2">@lang('Xác nhận')</th>

                            </tr>
                            <tr>
                                <th style="width: 120px">@lang('Đã học')</th>
                                <th style="width: 120px">@lang('Thực tế')</th>
                                <th style="width: 120px">@lang('Còn lại')</th>
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
                                        {{ $row->level->name ?? '' }}
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('classs.show', $row->id) }}">Link</a>
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
                                    </td>
                                    <td class="text-center">
                                        {{ $row->total_attendance }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->total_schedules }}
                                    </td>
                                    <td class="text-danger text-center" style="font-weight: bold; font-size: 16px ">
                                        {{ $row->total_schedules - $row->total_attendance }}
                                    </td>
                                    <td>
                                        @php
                                        $confirm=(!isset($row->json_params->ketoan_xacnhan) || $row->json_params->ketoan_xacnhan=="chuaxacnhan") ? "daxacnhan":"chuaxacnhan";
                                        @endphp
                                        @if($confirm=="daxacnhan")
                                            <button data-confirm="{{ $confirm }}" data-id="{{ $row->id }}" type="button" class="btn btn-sm btn-warning confirmClass">Xác nhận</button>
                                        @else
                                            <button data-confirm="{{ $confirm }}" data-id="{{ $row->id }}" type="button" class="btn btn-sm btn-success confirmClass">Đã xác nhận</button>
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
@endsection

@section('script')
<script>
    $('.confirmClass').click(function (e) { 
        if (confirm('Bạn có chắc chắn xác nhận lớp này không?')){
            let _id = $(this).attr('data-id');
            let _confirm = $(this).attr('data-confirm');
            let url = "{{ route('ajax.confirm.class.ending') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    confirm: _confirm,
                },
                success: function(response) {
                    alert('Cập nhật thành công');
                    location.reload();
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    });
    
</script>

@endsection
