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

        td {
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
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>

    </section> --}}
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('book_distribution.list_history') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($classs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Kho xuất')</label>
                                <select name="warehouse_id_deliver" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($warehouses as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['warehouse_id_deliver']) && $val->id == $params['warehouse_id_deliver'] ? 'selected' : '' }}>
                                            {{ __($val->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trạng thái nhận sách')</label>
                                <select name="confirmed" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    <option value="da_nhan"
                                        {{ isset($params['confirmed']) && $val->confirmed == $params['confirmed'] ? 'selected' : '' }}>
                                        @lang('Đã nhận sách')</option>
                                    <option value="null"
                                        {{ isset($params['confirmed']) && 'null' == $params['confirmed'] ? 'selected' : '' }}>
                                        @lang('Chưa nhận sách')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Kỳ')</label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ $params['period'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route('book_distribution.list_history') }}">
                                        @lang('Reset')
                                    </a>
                                    <button class="btn btn-sm btn-warning mr-10" onclick="window.print()"><i
                                        class="fa fa-print"></i>
                                    @lang('In danh sách')</button>
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
                <h3 class="box-title">@lang($module_name)</h3>
            </div>
            <div class="box-body box-alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('successMessage') !!}
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
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã')</th>
                                <th>@lang('Lớp học')</th>
                                <th>@lang('Kho xuất')</th>
                                <th>@lang('Người nhận')</th>
                                <th>@lang('Ngày nhận sách')</th>
                                <th>@lang('Kỳ')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Người xác nhận')</th>
                                <th>@lang('Ghi chú')</th>
                                <th class=" hide-print">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($rows)
                                @foreach ($rows as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td class="text-center">{{ $row->code ?? '' }}</td>
                                        <td>
                                            @isset($row->list_class)
                                                <ul>
                                                    @foreach ($row->list_class as $i)
                                                        @php
                                                            $teacher = \App\Models\Teacher::where(
                                                                'id',
                                                                $i->json_params->teacher ?? 0,
                                                            )->first();
                                                        @endphp
                                                        <li>{{ $i->name }}(GV:{{ $teacher->name ?? '' }})</li>
                                                    @endforeach
                                                </ul>
                                            @endisset
                                        </td>
                                        <td class="text-center">{{ $row->warehouse_deliver->name ?? '' }}</td>
                                        <td class="text-center">{{ $row->json_params->staff_entry ?? '' }}</td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($row->day_deliver)) }}</td>
                                        <td class="text-center">{{ date('m-Y', strtotime($row->period)) }}</td>
                                        <td class="text-center">
                                            {!! $row->confirmed == 'da_nhan' ? '<button class="btn btn-success">Đã nhận sách</button>' : '' !!}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->json_params->confirmed_code ?? '' }} -
                                            {{ $row->json_params->confirmed_name ?? '' }}
                                        </td>
                                        <td class="text-center">{{ $row->json_params->note ?? '' }}</td>
                                        <td class="text-center hide-print">
                                            <a href="{{ route('book_distribution.detail_history', $row->id) }}"
                                                class="btn btn-sm btn-warning">@lang('Xem chi tiết')</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix hide-print">
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
    <script></script>
@endsection
