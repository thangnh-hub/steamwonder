@extends('admin.layouts.app')
@push('style')
    <style>
        .invoice {
            margin: 10px 15px;
        }

        table {
            border: 1px solid #dddddd;
        }

        .text-success {
            color: #00a65a !important;
        }

        .box_input_time {
            display: flex;
            margin-bottom: 5px
        }

        .box_btn_edit {
            display: none
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        th,
        td {
            border: 1px solid #dddddd;
        }

        .modal-header {
            background: #3c8dbc;
            color: #fff;
            text-align: center;
        }

        .mb-2 {
            margin-bottom: 2rem;
        }

        .min-height {
            min-height: unset !important
        }

        #alert-config {
            width: auto !important;
        }

        a::after {
            content: none !important;
        }

        a[href]::before {
            content: none !important;
        }

        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endpush
@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div id="alert-config">
        </div>
        <button id="printButton" onclick="window.print()" class="btn btn-primary mb-2">@lang('In thông tin')</button>
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

        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h2 class="box-title text-uppercase text-bold">
                    <i class="fa fa-calculator"></i> @lang('Tính toán KPI giáo viên')
                </h2>
            </div>
            <form action="{{ route('kpi_teacher_index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Năm') </label>
                                <input type="number" class="form-control kpi_year" name="year"
                                    placeholder="@lang('Nhập năm')"
                                    value="{{ isset($params['year']) ? $params['year'] : date('Y', time()) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Giáo viên')</label>
                                <select class="form-control select2 teacher_id" name="teacher_id" id="">
                                    <option value="">Chọn</option>
                                    @foreach ($teacher as $item)
                                        <option
                                            {{ isset($params['teacher_id']) && $params['teacher_id'] == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->admin_code }} - {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('kpi_teacher_index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        @isset($teachers)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-star-half-o"></i> 
                        @lang('Tổng hợp KPI giáo viên')
                        {{ isset($params['year']) ? $params['year'] : date('Y', time()) }}
                    </h3>
                </div>
                <div style="padding-top:0px" class="box-body">
                    <div class="d-flex-wap">
                        <table style="border: 1px solid #dddddd;" class="table table-hover table-striped sticky">
                            <thead>
                                <tr>
                                    <th style="width:75px" rowspan="2">@lang('STT')</th>
                                    <th style="width:100px" rowspan="2">@lang('Mã GV')</th>
                                    <th rowspan="2">@lang('Giáo viên')</th>
                                    <th colspan="4">@lang('KPI kết quả đào tạo (80%)')</th>
                                    <th colspan="4">@lang('KPI tiến độ đào tạo (10%)')</th>
                                    <th colspan="2">@lang('KPI tác phong/kỷ luật (10%)')</th>
                                    <th style="width:120px" rowspan="2">@lang('KPI đạt được (100%)')</th>
                                </tr>
                                <tr>
                                    <th style="width:75px">@lang('A1')</th>
                                    <th style="width:75px">@lang('A2')</th>
                                    <th style="width:75px">@lang('B1')</th>
                                    <th style="width:100px">@lang('KPI đạt')</th>
                                    <th style="width:100px">@lang('Lộ trình')</th>
                                    <th style="width:100px">@lang('Thực tế')</th>
                                    <th style="width:100px">@lang('Kết quả')</th>
                                    <th style="width:100px">@lang('KPI đạt')</th>
                                    <th style="width:120px">@lang('Quản lý lớp (6%)')</th>
                                    <th style="width:120px">@lang('Tác phong, kỷ luật (4%)')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teachers as $item)
                                    @php
                                        if ($item->delay_ratio > 0) {
                                            $text = 'Chậm ' . $item->delay_ratio . '%';
                                            $text_color = 'text-red';
                                        }
                                        if ($item->delay_ratio < 0) {
                                            $text = 'Nhanh ' . abs($item->delay_ratio) . '%';
                                            $text_color = 'text-green';
                                        }
                                        if ($item->delay_ratio == 0) {
                                            $text = 'Đúng tiến độ';
                                            $text_color = 'text-green';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->index + 1 }}</td>

                                        <td class="text-center">
                                            <a href="{{ route('kpi_teacher_index', ['year' => $params['year'], 'teacher_id' => $item->id]) }}"
                                                target="_blank">
                                                {{ $item->admin_code }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('kpi_teacher_index', ['year' => $params['year'], 'teacher_id' => $item->id]) }}"
                                                target="_blank">
                                                {{ $item->name }}
                                            </a>
                                        </td>
                                        <td class="text-center">{{ round($item->avager_kpi_a1, 2) }}%</td>
                                        <td class="text-center">{{ round($item->avager_kpi_a2, 2) }}%</td>
                                        <td class="text-center">{{ round($item->avager_kpi_b1, 2) }}%</td>
                                        <td class="text-center text-bold">{{ round($item->total_kpi, 2) }}%</td>
                                        <td class="text-center">{{ $item->lesson_number }}</td>
                                        <td class="text-center">{{ $item->total_schedules }}</td>
                                        <td class="text-center {{ $text_color }}">
                                            @if ($item->total_schedules > 0)
                                                {{ $text }}
                                            @endif
                                        </td>
                                        <td class="text-center text-bold">{{ $item->kpi_process ?? 0 }}%</td>
                                        <td class="text-center text-bold">{{ $item->kpi_class }}%</td>
                                        <td class="text-center text-bold">{{ $item->kpi_behavior }}%</td>
                                        <td class="text-center text-bold text-green">
                                            {{ round($item->total_kpi + $item->kpi_process + $item->kpi_class + $item->kpi_behavior, 2) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>


                </div>
            </div>
        @endisset

    </section>

@endsection
