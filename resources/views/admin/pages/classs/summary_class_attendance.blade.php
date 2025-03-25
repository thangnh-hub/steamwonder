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
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>
        {{-- <div class="box_excel">
            <a href="{{ route('product.excel.export') }}">
                <button class="btn btn-sm btn-primary "><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    @lang('Export Excel')</button>
            </a>
            <button class="btn btn-sm btn-danger" data-toggle="modal" data-backdrop="static" data-keyboard="false"
                data-target="#import_excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> @lang('Import Excel')</button>
        </div> --}}
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

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div> --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="area_id form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Teacher')</label>
                                <select name="teacher_id" id="teacher_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($teachers as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['teacher_id']) && $value->id == $params['teacher_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('School day') </label>
                                <input type="date" class="form-control" name="school_day"
                                    value="{{ isset($params['school_day']) ? $params['school_day'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
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
                                <th>@lang('Area')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Teacher')</th>
                                <th>@lang('Level')</th>
                                <th>@lang('attendant')</th>
                                <th>@lang('absent')</th>
                                <th>@lang('Secondary status')</th>
                                <th>@lang('Note')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $teacher = \App\Models\Teacher::where('id', $row->json_params->teacher ?? 0)->first();
                                    $room = \App\Models\Room::where('id', $row->schedules->first()->room_id)->first();
                                    $quantity_student = \App\Models\UserClass::where('class_id',$row->id)->get()->count();
                                    $schedule = \App\Models\Schedule::where('class_id', $row->id)->where('status', \App\Consts::SCHEDULE_STATUS['dadiemdanh'])->where('date', $params['school_day'])->first();
                                    $schedules = \App\Models\Schedule::where('class_id', $row->id)->get();
                                    
                                    if(isset($schedule)){
                                        $attendant = $schedule->attendances()->where('status', \App\Consts::ATTENDANCE_STATUS['attendant'])->count();
                                        $absent = $schedule->attendances()->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])->count();
                                        $licensed = $schedule->attendances()
                                                                            ->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])
                                                                            ->where('json_params->value', \App\Consts::OPTION_ABSENT['there reason'])->count();
                                        $unauthorized = $schedule->attendances()
                                                                            ->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])
                                                                            ->where('json_params->value', \App\Consts::OPTION_ABSENT['no reason'])->count();
                                    }else{
                                        $attendant = \App\Consts::SCHEDULE_STATUS['chuahoc'];
                                        $absent = \App\Consts::SCHEDULE_STATUS['chuahoc'];
                                    }
                                    if (isset($schedules) && $schedules->count() > 0) {
                                        $sortedSchedules = $schedules->sortBy('date');
                                        $ordinal = $sortedSchedules->search(function ($schedule) use ($params) {
                                            return $schedule->date == $params['school_day'];
                                        });
                                        $syllabus = $row->syllabus;
                                        if(isset($syllabus->lessons)){
                                            $lessons = $syllabus->lessons;
                                            if ($ordinal < $lessons->count()) {
                                                $lesson = $lessons[$ordinal];
                                            }
                                        }
                                    }
                                @endphp
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $row->area->name ?? '' }}
                                        </td>
                                        <td>
                                            <strong
                                                style="font-size: 14px">{{ $row->json_params->name->{$lang} ?? $row->name }} (@lang('Sĩ số'): {{ $quantity_student }})</strong>
                                        </td>
                                        <td>
                                            {{ $teacher->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->level->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $attendant }}
                                        </td>
                                        <td>
                                            {{ $absent }}
                                        </td>
                                        
                                        <td>
                                            @if($absent == \App\Consts::SCHEDULE_STATUS['chuahoc'])
                                                
                                            @else
                                            <ul>
                                                <li>@lang('Licensed'): {{ $licensed }}</li>
                                                <li>@lang('Unauthorized'): {{ $unauthorized }}</li>
                                            </ul>
                                            @endif
                                        </td>
                                        <td>{{ $lesson->title ?? '' }}</td>
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
