@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>thead>tr>th {
            font: bold 14px/28px "Source Sans Pro";
            text-align: center;
            align-content: center;
        }

        .table>tbody>tr>td {
            text-align: center
        }

        @media screen and (max-width: 767px) {
            .table_responsive {
                min-height: .01%;
                overflow-x: auto;
            }
        }
        @media print {
            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
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
        {{-- Search form --}}
        <div class="box box-default hide-print">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('dormitory.report_month') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Từ tháng') <small class="text-red">*</small></label>
                                <input type="month" class="form-control" name="from_month" required
                                    placeholder="@lang('Chọn tháng')" value="{{ $params['from_month'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Đến tháng') <small class="text-red">*</small></label>
                                <input type="month" class="form-control" name="to_month" required
                                    placeholder="@lang('Chọn tháng')" value="{{ $params['to_month'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('dormitory.report_month') }}">
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
                <button id="printButton" onclick="window.print()"
                    class="btn btn-primary mb-2 pull-right">@lang('In thông tin')</button>
            </div>
            <div class="box-body table_responsive">
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


                <table class="table table-hover table-bordered sticky">
                    <thead>
                        <tr>
                            <th rowspan="2">@lang('Khu vực')</th>
                            <th rowspan="2">@lang('Tháng')</th>
                            <th rowspan="2">@lang('Tổng số phòng cho thuê')</th>
                            <th colspan="3">@lang('Đang ở')</th>
                            <th colspan="4">@lang('Vào')</th>
                            <th colspan="4">@lang('Rời đi')</th>
                            <th colspan="4">@lang('Trống')</th>
                        </tr>
                        <tr>
                            <th>@lang('Nam')</th>
                            <th>@lang('Nữ')</th>
                            <th>@lang('Tổng')</th>
                            <th>@lang('Nam')</th>
                            <th>@lang('Nữ')</th>
                            <th>@lang('Khác')</th>
                            <th>@lang('Tổng')</th>
                            <th>@lang('Nam')</th>
                            <th>@lang('Nữ')</th>
                            <th>@lang('Khác')</th>
                            <th>@lang('Tổng')</th>
                            <th>@lang('Nam')</th>
                            <th>@lang('Nữ')</th>
                            <th>@lang('Khác')</th>
                            <th>@lang('Tổng')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($list_area)
                            @foreach ($list_area as $area)
                                @foreach ($area->month as $month => $val)
                                    <tr class="valign-middle">
                                        @if ($loop->index == 0)
                                            <td rowspan="{{ count((array) $area->month) }}"><strong
                                                    style="font-size: 14px;">{{ $area->area->name }}</strong>
                                        @endif
                                        <td> <strong style="font-size: 14px;">{{ $month }}</strong> </td>
                                        <td>{{ $val->total_dormitory ?? 0 }}({{ $val->total_slot ?? 0 }} chỗ)</td>
                                        <td>{{ $val->already->already_male }}</td>
                                        <td>{{ $val->already->already_female }}</td>
                                        <td><strong>{{ $val->already->already_total }}</strong></td>
                                        <td>{{ $val->come->come_male }}</td>
                                        <td>{{ $val->come->come_female }}</td>
                                        <td>{{ $val->come->come_other }}</td>
                                        <td><strong>{{ $val->come->come_total }}</strong></td>
                                        <td>{{ $val->leave->leave_male }}</td>
                                        <td>{{ $val->leave->leave_female }}</td>
                                        <td>{{ $val->leave->leave_other }}</td>
                                        <td><strong>{{ $val->leave->leave_total }}</strong></td>
                                        <td>{{ $val->empty->empty_male }}</td>
                                        <td>{{ $val->empty->empty_female }}</td>
                                        <td>{{ $val->empty->empty_other }}</td>
                                        <td><strong>{{ $val->empty->empty_total }}</strong></td>
                                    </tr>
                                @endforeach
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
