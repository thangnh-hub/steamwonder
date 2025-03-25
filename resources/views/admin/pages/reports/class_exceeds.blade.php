@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
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

        #alert-config {
            width: auto !important;
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
    <div id="alert-config">

    </div>
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('reports.class.exceeds') }}" method="GET">
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
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Từ ngày')</label>
                                <input type="date" name="from_date" class="form-control" value="{{ isset($params['search_from_date']) ?$params['search_from_date'] :""}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Đến ngày')</label>
                                <input type="date" name="to_date" class="form-control" value="{{ isset($params['search_to_date']) ? $params['search_to_date']:"" }}">

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('reports.class.exceeds') }}">
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
                                <th rowspan="2">STT</th>
                                <th rowspan="2">@lang('Title')</th>
                                <th rowspan="2">@lang('Syllabus')</th>
                                <th rowspan="2">@lang('Area')</th>
                                <th rowspan="2">@lang('Room')</th>
                                <th rowspan="2">@lang('Period')</th>
                                <th rowspan="2">@lang('Teacher')</th>
                                <th style="width: 100px" rowspan="2">@lang('Trạng thái')</th>
                                <th colspan="3">@lang('Số buổi học')</th>
                                <th colspan="3">@lang('Thời gian')</th>
                                <th style="width: 280px" rowspan="2">@lang('Ghi chú')</th>
                            </tr>
                            <tr>
                                <th style="width: 120px">@lang('Chương trình')</th>
                                <th style="width: 120px">@lang('Thực tế (Đã điểm danh)')</th>
                                <th style="width: 120px">@lang('Vượt quá')</th>
                                <th style="width: 120px">@lang('Bắt đầu')</th>
                                <th style="width: 120px">@lang('Dự kiến')</th>
                                <th style="width: 120px">@lang('Kết thúc')</th>
                            </tr>


                        </thead>
                        <tbody>
                            @php

                                $filtered = $rows->filter(function ($row) {
                                    if (isset($row->syllabus->lesson)) {
                                        return $row->total_attendance > $row->syllabus->lesson;
                                    }
                                    // else {
                                    //     return $row->total_attendance > $row->lesson_number;
                                    // }
                                });

                                // Sắp xếp theo hiệu ($row->total_attendance - $row->lesson_number) từ thấp đến cao
                                $sorted = $filtered->sort(function ($a, $b) {
                                    if (isset($b->syllabus->lesson) && isset($b->syllabus->lesson)) {
                                        return $b->total_attendance - $b->syllabus->lesson <=>
                                            $a->total_attendance - $a->syllabus->lesson;
                                    }
                                    // else {
                                    // return $b->total_attendance - $b->lesson_number <=>
                                    //     $a->total_attendance - $a->lesson_number;
                                    // }
                                });

                                // Chuyển kết quả thành mảng và đánh lại chỉ mục
                                $result = $sorted->values()->all();

                            @endphp
                            @foreach ($result as $row)
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
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td><a href="{{ route('classs.edit', $row->id) }}"><strong
                                                style="font-size: 14px">{{ $row->json_params->name->{$lang} ?? $row->name }}</strong></a>

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
                                        {{ $row->syllabus->lesson ?? 0 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->total_attendance }}
                                    </td>
                                    <td class="text-danger text-center" style="font-weight: bold; font-size: 16px ">
                                        {{ $row->total_attendance - ($row->syllabus->lesson ?? $row->lesson_number) }}
                                    </td>
                                    {{-- thời gian --}}
                                    <td class="text-center">
                                        {{ isset($row->day_start) ? date('d/m/Y', strtotime($row->day_start)) : '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ isset($row->day_end_expected) ? date('d/m/Y', strtotime($row->day_end_expected)) : '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ isset($row->day_end) ? date('d/m/Y', strtotime($row->day_end)) : '' }}
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <input placeholder="Nhập ghi chú" type="text"
                                                class="form-control note_exceed"
                                                value="{{ $row->json_params->note_exceed ?? '' }}">
                                            <span data-id="{{ $row->id }}" onclick="updateAjaxNoteExceed(this)"
                                                class="input-group-btn">
                                                <a class="btn btn-primary">Lưu </a>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </section>

@endsection

@section('script')
    <script>
        function updateAjaxNoteExceed(th) {
            let _id = $(th).attr('data-id');
            var _note = $(th).parents('tr').find('.note_exceed').val();
            let url = "{{ route('ajax.update.note.exceed') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    note: _note,
                },
                success: function(response) {
                    if (response.message == "success") {
                        $("#alert-config").append(
                            '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>'
                        );
                        setTimeout(function() {
                            $(".alert-success").fadeOut(2000, function() {});
                        }, 800);
                    } else $("#alert-config").append(
                        '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Không cập nhật được ghi chú</div>'
                    );
                    setTimeout(function() {
                        $(".alert-warning").fadeOut(2000, function() {});
                    }, 800);

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
@endsection
