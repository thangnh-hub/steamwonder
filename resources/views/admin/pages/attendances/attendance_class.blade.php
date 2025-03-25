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
            {{-- <a class="btn btn-sm btn-warning pull-right" href="#"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
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
            <form action="{{ route('attendance_class.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="id" id="class_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"
                                        {{ isset($params['id']) && $params['id'] == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $key => $area)
                                        <option value="{{ $area->id }}"
                                            {{ isset($params['area_id']) && $area->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($area->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Level')</label>
                                <select name="level_id" id="level_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($levels as $key => $level)
                                        <option value="{{ $level->id }}"
                                            {{ isset($params['level_id']) && $level->id == $params['level_id'] ? 'selected' : '' }}>
                                            {{ __($level->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Get information')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('evaluation_class.index') }}">
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
                                <a class="text-primary pull-right" href="{{ route('evaluation_class.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route('evaluation_class.index') }}?lang={{ $item->lang_locale }}"
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
                                <th>@lang('Class')</th>
                                <th>@lang('Level')</th>
                                <th>@lang('Syllabus')</th>
                                <th>@lang('Course')</th>
                                <th>@lang('Period')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $now = date('Y-m-d');
                                    $schedule_now = $row->schedules()->where('date', $now)->first();
                                @endphp
                                <tr class="valign-middle">
                                    <td>
                                        <a href="{{ route('schedule_class.index', ['class_id' => $row->id]) }}">
                                        <strong
                                            style="font-size: 14px">{{ $row->json_params->name->{$lang} ?? $row->name }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{ $row->level->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->syllabus->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->course->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->period->iorder }} ({{$row->period->start_time}} - {{$row->period->end_time}})
                                    </td>
                                    <td>
                                        {{ $row->updated_at }}
                                    </td>
                                    
                                    <td>
                                        <a href="{{ route('schedule_class.index', ['class_id' => $row->id]) }}" class="btn btn-info">
                                            <i class="fa fa-external-link"></i> @lang('Schedule')
                                        </a>
                                    </td>
                                </tr>
                                @if ($schedule_now)
                                    @if($schedule_now->status == \App\Consts::SCHEDULE_STATUS['chuahoc'])
                                    <th class="text-red">@lang("Today's class has not taken attendance yet.")</th>
                                    @else
                                    <th class="text-green">@lang("Attendanced")</th>
                                    @endif
                                <th>@lang('Date')</th>
                                <th>@lang('Period')</th>
                                <th>@lang('Room')</th>
                                <th>@lang('Teacher')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>

                                <tr class="valign-middle">
                                    
                                    <td></td>
                                    <td>
                                        {{ $schedule_now->date }}
                                    </td>
                                    <td>
                                        {{ $schedule_now->period->id ?? ''}} ({{ $schedule_now->period->start_time ?? ''}} - {{ $schedule_now->period->end_time ?? ''}})
                                    </td>
                                    <td>
                                        {{ $schedule_now->room->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $schedule_now->teacher->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($schedule_now->status)
                                    </td>
                                    <td>
                                        <a href="{{ route('attendances.index', ['schedule_id' => $schedule_now->id]) }}" class="btn btn-info">
                                            <i class="fa fa-external-link"></i> @lang('Take attendance')
                                        </a>
                                    </td>
                                </tr>
                                @endif
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
        $(document).ready(function() {
            // Routes get all
            var routes = @json(App\Consts::ROUTE_NAME ?? []);
            $(document).on('change', '#route_name', function() {
                let _value = $(this).val();
                let _targetHTML = $('#template');
                let _list = filterArray(routes, 'name', _value);
                let _optionList = '<option value="">@lang('Please select')</option>';
                if (_list) {
                    _list.forEach(element => {
                        element.template.forEach(item => {
                            _optionList += '<option value="' + item.name + '"> ' + item
                                .title + ' </option>';
                        });
                    });
                    _targetHTML.html(_optionList);
                }
                $(".select2").select2();
            });

        });
    </script>
@endsection
