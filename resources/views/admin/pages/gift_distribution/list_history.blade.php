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

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
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
            <form action="{{ route('gift_distribution.list_history') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
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
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kỳ')</label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ $params['period'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route('gift_distribution.list_history') }}">
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
                                <th>@lang('Kho xuất')</th>
                                <th>@lang('Ngày xuất')</th>
                                <th>@lang('Kỳ')</th>
                                <th>@lang('Người tạo')</th>
                                <th class=" hide-print">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($rows)
                                @foreach ($rows as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td class="text-center">{{ $row->code ?? '' }}</td>
                                        
                                        <td class="text-center">{{ $row->warehouse_deliver->name ?? '' }}</td>
                                        <td class="text-center">{{ date('d-m-Y', strtotime($row->day_deliver)) }}</td>
                                        <td class="text-center">{{ date('m-Y', strtotime($row->period)) }}</td>
                                        <td>{{ $row->admin_created->name??"" }}</td>
                                        <td class="text-center hide-print">
                                            <a href="{{ route('gift_distribution.detail_history', $row->id) }}"
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
