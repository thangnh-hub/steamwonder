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
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section> --}}
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
                                <label>@lang('Keyword') </label>
                                <input type="date" class="form-control" name="date" placeholder="@lang('Date')"
                                    value="{{ isset($params['date']) ? $params['date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $item)
                                        <option value="{{ $item->id }}"
                                        {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"
                                        {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Room')</label>
                                <select name="room_id" id="" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($room as $item)
                                        <option value="{{ $item->id }}"
                                        {{ isset($params['room_id']) && $params['room_id'] == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Period')</label>
                                <select name="period_id" id="" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($period as $item)
                                        <option value="{{ $item->id }}"
                                        {{ isset($params['period_id']) && $params['period_id'] == $item->id ? 'selected' : '' }}>
                                        {{ $item->start_time }} - {{ $item->end_time }}</option>
                                    @endforeach
                                </select>
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
                                <th>@lang('Date')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Period')</th>
                                <th>@lang('Room')</th>
                                <th>@lang('Teacher')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ optional(\Carbon\Carbon::parse($row->date))->format('l d/m/Y') }}
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{ route('classs.edit',$row->class->id) }}">{{ $row->class->name }}</a>
                                        </td>
                                        <td>
                                            {{ $row->period->id ?? ''}} ({{ $row->period->start_time ?? ''}} - {{ $row->period->end_time ?? ''}})
                                        </td>
                                        <td>
                                            {{ $row->room->name }}
                                        </td>
                                        <td>
                                            {{ $row->teacher->name }}
                                        </td>
                                        <td>
                                            {{ App\Consts::SCHEDULE_STATUS[$row->status] }}
                                        </td>

                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit"
                                                data-toggle="tooltip" title="@lang('Delete')"
                                                data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </form>
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
    <div id="calendar"></div>
    </section>
    </div>
@endsection

