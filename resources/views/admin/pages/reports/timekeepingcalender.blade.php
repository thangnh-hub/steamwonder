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

        .table>tbody>tr>td {
            text-align: center;
            vertical-align: inherit;
        }

        .text_period {
            white-space: nowrap
        }

        .text_period.active {
            font-weight: bold;
            color: #00a65a
        }

        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }

        a::after {
            content: none !important;
        }

        a[href]::before {
            content: none !important;
        }

        @media screen and (max-width: 767px) {
            .table_responsive {
                min-height: .01%;
                overflow-x: auto;
            }
        }
    </style>
@endsection
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
            <form action="{{ route('report.timekeeping.calender') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tháng') </label>
                                <input type="month" class="form-control" name="month"
                                    value="{{ isset($params['month']) ? $params['month'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Giáo viên')</label>
                                <input type="text" name="keyword" class="form-control"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}"
                                    placeholder="Nhập tên giáo viên, mã giáo viên">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.timekeeping.calender') }}">
                                        @lang('Reset')
                                    </a>
                                    <a target="_blank" style="margin-left: 10px" class="btn btn-success btn-sm"
                                        href="{{ route('timekeeping_teacher.index') }}">
                                        @lang('Danh sách chấm công bổ sung')
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
                <h3 class="box-title">@lang($module_name)</h3>
                <button type="button" class="btn btn-warning pull-right" style="margin-left: 10px" data-toggle="modal"
                    data-target="#modal_periods">
                    @lang('Thông tin ca học')
                </button>
                <button id="printButton" onclick="window.print()"
                    class="btn btn-primary mb-2 pull-right">@lang('In thông tin')</button>
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
                @if (count($teachers) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Mã giáo viên')</th>
                                <th rowspan="2">@lang('Họ và tên Giáo viên')</th>
                                <th rowspan="2">@lang('Tên lớp')</th>
                                @foreach ($day_in_month as $val)
                                    <th>{{ $val->day }}</th>
                                @endforeach
                                {{-- <th colspan="3">@lang('Thời gian')</th> --}}
                                <th colspan="3">@lang('Số buổi')</th>
                                <th colspan="6">@lang('Trong tháng')</th>
                            </tr>

                            <tr>
                                @foreach ($day_in_month as $val)
                                    <th>{{ $day_week_mini[$val->day_of_Week] ?? 'CN' }}</th>
                                @endforeach
                                {{-- <th>@lang('Bắt đầu ')</th>
                                <th>@lang('Dự kiến ')</th>
                                <th>@lang('Kết thúc')</th> --}}
                                <th>@lang('Đã học/Tiêu chuẩn')</th>
                                <th>@lang('Quá buổi')</th>
                                {{-- <th style="width: 150px">@lang('Ghi chú')</th> --}}
                                {{-- <th>@lang('Số lần nhận xét')</th> --}}
                                {{-- <th>@lang('Điểm danh muộn')</th> --}}
                                <th>@lang('Đã điểm danh')</th>
                                <th>@lang('Tổng')</th>
                                <th>@lang('Bổ sung')</th>
                                <th>@lang('Tổng cộng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $item)
                                @php
                                    $stt = $loop->index + 1;
                                    $i = 1;
                                @endphp
                                @foreach ($item->classs as $item_class)
                                    <tr>
                                        @if ($i == 1)
                                            <td rowspan="{{ count($item->classs) }}">{{ $stt }}</td>
                                            <td rowspan="{{ count($item->classs) }}"><a target="_blank"
                                                    href="{{ route('detail.report.timekeeping.teacher', ['teacher_id' => $item->id, 'month' => $params['month']]) }}">{{ $item->admin_code }}</a>
                                            </td>
                                            <td rowspan="{{ count($item->classs) }}"><a target="_blank"
                                                    href="{{ route('detail.report.timekeeping.teacher', ['teacher_id' => $item->id, 'month' => $params['month']]) }}">{{ $item->name }}
                                                    ({{ $item['teacher_type'] }})
                                                </a>
                                            </td>
                                        @endif
                                        <td><a target="_blank"
                                                href="{{ route('schedule_class.index', ['class_id' => $item_class->class->id]) }}">{{ $item_class->class->name }}
                                            </a></td>
                                        @foreach ($item_class->calender as $days)
                                            <td style="width: 30px">
                                                @foreach ($days->periods as $period)
                                                    @if ($period->status == 'dadiemdanh')
                                                        <p class="text_period">Ca: {{ $period->id }}</p>
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endforeach


                                        {{-- <td>{{ $item_class->thongke_buoihoc->day_start ?? '' }}</td>
                                        <td>{{ $item_class->thongke_buoihoc->day_end_expected ?? '' }}</td>
                                        <td>{{ $item_class->thongke_buoihoc->day_end ?? '' }}</td> --}}
                                        <td>
                                            {{ $item_class->thongke_buoihoc->total_attendance ?? 0 }}/{{ $item_class->lesson_number ?? 0 }}
                                        </td>

                                        <td
                                            style="{{ $item_class->thongke_buoihoc->total_attendance - $item_class->lesson_number > 0 ? 'color:red;font-weight:bold; background-color:yellow' : '' }}">
                                            {{ $item_class->thongke_buoihoc->total_attendance - $item_class->lesson_number > 0 ? $item_class->thongke_buoihoc->total_attendance - $item_class->lesson_number : 0 }}
                                        </td>
                                        {{-- <td>
                                            {{ ($item_class->class->json_params->note_exceed ?? '') . $item_class->thongke_trangthai->text_transfer_status ?? '' }}
                                        </td> --}}

                                        {{-- <td>{{ $item_class->total_evaluations ?? 0 }}</td>
                                        <td>{{ $item_class->attendance_late ?? 0 }}</td> --}}
                                        <td>{{ $item_class->thongke_buoihoc->attendant_in_month }}
                                        </td>
                                        @if ($i == 1)
                                            <td rowspan="{{ count($item->classs) }}" style="border-top: 0px;">
                                                <strong>{{ $item->total_attendance_in_month }}</strong>
                                            </td>
                                            <td rowspan="{{ count($item->classs) }}"
                                                style="{{ $item->bosung > 0 ? 'color:red;font-weight:700;' : '' }};border-top: 0px;">
                                                {{ $item->bosung }}
                                                @if ($item->bosung > 0)
                                                    <a target="_blank" class="btn btn-sm btn-warning hide-print"
                                                        data-toggle="tooltip" title=""
                                                        data-original-title="Xem chi tiết"
                                                        href="{{ route('timekeeping_teacher.index', ['teacher_id' => $item->id, 'months' => $params['month']]) }}">
                                                        @lang('Chi tiết')
                                                    </a>
                                                @endif
                                            </td>

                                            <td rowspan="{{ count($item->classs) }}">
                                                <strong>{{ $item->total_attendance_in_month + $item->bosung }}</strong>
                                            </td>
                                        @endif
                                    </tr>

                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="modal_periods" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="display: inline-block">Ghi chú ca học</h4>
                    <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Order')</th>
                                <th>@lang('Start')</th>
                                <th>@lang('End')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($periods as $val)
                                <tr class="valign-middle">
                                    <td>
                                        Ca: {{ $val->iorder }}
                                    </td>
                                    <td>
                                        {{ $val->start_time }}
                                    </td>
                                    <td>
                                        {{ $val->end_time }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
