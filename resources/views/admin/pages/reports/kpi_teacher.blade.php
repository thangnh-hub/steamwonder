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
        @isset($params['teacher_id'])
            <div class="box box-default">
                <div class="box-header with-border">
                    <h2 class="box-title text-uppercase text-bold">
                        <i class="fa fa-user"></i> KPI Giáo viên {{ $name_teacher }}
                    </h2>
                </div>
                <div style="padding-top: 0px" class="box-body">
                    <div style="margin-top: 10px" class="row">
                        <div class="col-md-3">
                            <p style="font-size: 16px"><strong style="color: #d73925">@lang('TỔNG KPI NĂM ĐẠT ĐƯỢC'): <span
                                        class="total_kpi_year"></span>%</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>@lang('Năm'): </strong>{{ $params['year'] ?? 2024 }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>@lang('Thời gian báo cáo'): </strong><span
                                    class="time_report">{{ date('d-m-Y', time()) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <p>Tổng KPI kết quả đào tạo: <strong style="color: #f39c12"><span
                                        class="show-kpi-score">0</span>%</strong> (Tổng KPI : 80%)</p>
                        </div>
                        <div class="col-md-3">
                            <p>Tổng KPI tiến độ đào tạo: <strong class="text-success"><span
                                        class="show-kpi-process">0</span>%</strong> (Tổng KPI : 10%)</p>
                        </div>
                        <div class="col-md-3">
                            <p>KPI quản lý lớp học: <strong class="text-primary"><span
                                        class="show-kpi-manage-class">{{ isset($kpi_detail->kpi_class) && $kpi_detail->kpi_class != '' ? $kpi_detail->kpi_class : 6 }}</span>%</strong>
                                (Tổng KPI : 6%)</p>
                            <div class="box_btn_edit hide-print">
                                <div class="box_input_time">
                                    <input type="number" max="6" min="0"
                                        class="form-control input_time kpi_manage_class" style="margin-right: 3px"
                                        placeholder="Nhập %"
                                        value="{{ isset($kpi_detail->kpi_class) && $kpi_detail->kpi_class != '' ? $kpi_detail->kpi_class : 6 }}" />
                                    <button class="btn btn-sm btn-success btn_check" data-toggle="tooltip"
                                        data-original-title="@lang('Lưu')"><i class="fa fa-check"
                                            aria-hidden="true"></i></button>
                                    <button class="btn btn-sm btn-danger btn_exit" style="margin-left: 3px"
                                        data-toggle="tooltip" data-original-title="@lang('Hủy')"><i class="fa fa-times"
                                            aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-primary btn_deactive hide-print" data-toggle="tooltip"
                                style="margin-right: 5px" title="@lang('Chỉnh sửa')" data-original-title="@lang('Chỉnh sửa')">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </button>
                        </div>
                        <div class="col-md-3">
                            <p>KPI tác phong, kỷ luật: <strong class="text-primary"><span
                                        class="show-kpi-behavor ">{{ isset($kpi_detail->kpi_behavior) && $kpi_detail->kpi_behavior != '' ? $kpi_detail->kpi_behavior : 4 }}</span>%</strong>
                                (Tổng KPI : 4%)</p>
                            <div class="box_btn_edit hide-print">
                                <div class="box_input_time">
                                    <input type="number" max="4" min="0"
                                        class="form-control input_time kpi_behavor" style="margin-right: 3px"
                                        placeholder="Nhập %"
                                        value="{{ isset($kpi_detail->kpi_behavior) && $kpi_detail->kpi_behavior != '' ? $kpi_detail->kpi_behavior : 4 }}" />
                                    <button class="btn btn-sm btn-success btn_check" data-toggle="tooltip"
                                        data-original-title="@lang('Lưu')"><i class="fa fa-check"
                                            aria-hidden="true"></i></button>
                                    <button style="margin-left: 3px" class="btn btn-sm btn-danger btn_exit"
                                        data-toggle="tooltip" data-original-title="@lang('Hủy')"><i class="fa fa-times"
                                            aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-primary btn_deactive hide-print" data-toggle="tooltip"
                                style="margin-right: 5px" title="@lang('Chỉnh sửa')"
                                data-original-title="@lang('Chỉnh sửa')">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endisset
        @if (isset($getClassHasScoreInYear) && count($getClassHasScoreInYear) > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-graduation-cap"></i> @lang('Chi tiết KPI kết quả đào tạo')
                    </h3>
                    <h3 class="box-title text-uppercase text-bold pull-right">
                        @lang('Tổng KPI tối đa: 80%')
                    </h3>
                </div>
                <div style="padding-top:0px" class="box-body">
                    <div class="d-flex-wap table-responsive">
                        <table style="border: 1px solid #dddddd;" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th rowspan="2">@lang('STT')</th>
                                    <th rowspan="2">@lang('Trình độ')</th>
                                    <th rowspan="2">@lang('Tỷ trọng')</th>
                                    <th rowspan="2">@lang('Lớp học')</th>
                                    <th rowspan="2">@lang('Loại lớp')</th>
                                    <th rowspan="2">@lang('Sỹ số')</th>
                                    <th colspan="3">@lang('Trình độ A1-A2')</th>
                                    <th colspan="5">@lang('Trình độ B1')</th>
                                    <th rowspan="2">@lang('KPI đạt được')</th>
                                </tr>
                                <tr>
                                    <th>@lang('>= 80 điểm')</th>
                                    <th>@lang('>= 75 điểm')</th>
                                    <th>@lang('>= 60 điểm')</th>
                                    <th>@lang('Đỗ nghe')</th>
                                    <th>@lang('Đỗ đọc')</th>
                                    <th>@lang('Đỗ viết')</th>
                                    <th>@lang('Đỗ nói')</th>
                                    <th>@lang('Tỉ lệ % đỗ ')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getClassHasScoreInYear as $row)
                                    <tr class="valign-middle">
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td class="text-center">
                                            {{ $row->level->name ?? '' }} ({{ $row->syllabus->score_type ?? '' }})
                                        </td>
                                        <td class="text-center">
                                            {{ $row->density ?? '' }}%
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('scores.index', ['class_id' => $row->id]) }}">{{ $row->name ?? '' }}</a>
                                        </td>

                                        <td class="text-center">
                                            {{ __($row->type_normal_special ?? '') }}
                                        </td>

                                        <td class="text-center">
                                            {{ $row->students_count ?? '' }}
                                        </td>

                                        <td class="text-center">
                                            @isset($row->kpi[80]['studentAchieved'])
                                                {{ $row->kpi[80]['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi[80]['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($row->kpi[75]['studentAchieved'])
                                                {{ $row->kpi[75]['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi[75]['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($row->kpi[60]['studentAchieved'])
                                                {{ $row->kpi[60]['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi[60]['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($row->kpi['listen']['studentAchieved'])
                                                {{ $row->kpi['listen']['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi['listen']['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>

                                        <td class="text-center">
                                            @isset($row->kpi['read']['studentAchieved'])
                                                {{ $row->kpi['read']['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi['read']['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($row->kpi['write']['studentAchieved'])
                                                {{ $row->kpi['write']['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi['write']['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($row->kpi['speak']['studentAchieved'])
                                                {{ $row->kpi['speak']['studentAchieved'] ?? '' }}
                                                ({{ round($row->kpi['speak']['percentAchieved'], 3) * 100 }}%)
                                            @endisset
                                        </td>
                                        <td class="text-center">
                                            @isset($row->kpi['totalAchieved'])
                                                {{ round($row->kpi['totalAchieved'] / $row->kpi['totalskill'], 3) * 100 }}%
                                            @endisset
                                        </td>

                                        <td class="text-center text-yellow text-bold">
                                            @isset($row->kpi['percent_receive'])
                                                {{ 100 * $row->kpi['percent_receive'] }}%
                                            @endisset
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>Trung bình KPI A1: {{ round($avager_kpi_a1, 2) }}%</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Trung bình KPI A2: {{ round($avager_kpi_a2, 2) }}%</strong></p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Trung bình KPI B1: {{ round($avager_kpi_b1, 2) }}%</strong></p>
                        </div>
                        <div class="col-md-3 ">
                            <p style="text-align:right"><strong>Tổng KPI: <span
                                        class="text-yellow">{{ round($total_kpi, 2) }}%</span></strong></p>
                        </div>
                        {{-- Tính KPI cuối cùng nhận được --}}

                        <input type="hidden" value="{{ round($total_kpi, 2) }}" class="kpi_score">
                    </div>
                </div>
            </div>
        @endif

        @if (isset($getClassInYear) && count($getClassInYear) > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-graduation-cap"></i> @lang('Chi tiết KPI tiến độ đào tạo')
                    </h3>
                    <h3 class="box-title text-uppercase text-bold pull-right">
                        @lang('Tổng KPI tối đa: 10%')
                    </h3>
                </div>
                <div style="padding-top:0px" class="box-body">
                    <div style="overflow-x: unset" class="table table-responsive">
                        <table style="border: 1px solid #dddddd;" class="table table-hover table-striped ">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Lớp học')</th>
                                    <th>@lang('Trình độ')</th>
                                    <th>@lang('Chương trình')</th>
                                    <th>@lang('Ngày bắt đầu ')</th>
                                    <th>@lang('Ngày kết thúc (thực tế)')</th>
                                    <th>@lang('Tổng số buổi theo lộ trình')</th>
                                    <th>@lang('Tổng số buổi thực tế')</th>
                                    <th>@lang('Tiến độ')</th>
                                </tr>

                            </thead>
                            <tbody>
                                @php
                                    $av;
                                @endphp
                                @foreach ($getClassInYear as $row)
                                    <tr class="valign-middle">
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('classs.edit', $row->id) }}">{{ $row->name ?? '' }}</a>
                                        </td>
                                        <td class="text-center">
                                            <a
                                                href="{{ route('levels.edit', $row->level_id ?? 0) }}">{{ $row->level->name ?? '' }}</a>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('syllabuss.edit', $row->syllabus ?? 0) }}">{{ $row->syllabus->name ?? '' }}</a>
                                        </td>

                                        <td class="text-center">
                                            {{ $row->day_start != '' ? date('d/m/Y', strtotime($row->day_start)) : '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $row->day_end != '' ? date('d/m/Y', strtotime($row->day_end)) : '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $row->lesson_number }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->total_schedules }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                if ($row->delay > 0) {
                                                    $text = 'Chậm ' . abs(100 * $row->delay) . '%';
                                                    $text_color = 'text-red';
                                                } elseif ($row->delay < 0) {
                                                    $text = 'Nhanh ' . abs(100 * $row->delay) . '%';
                                                    $text_color = 'text-green';
                                                } elseif ($row->lesson_number > 0) {
                                                    $text = 'Đúng tiến độ';
                                                    $text_color = 'text-green';
                                                } else {
                                                    $text = 'Không xác định';
                                                    $text_color = 'text-dark';
                                                }
                                            @endphp
                                            <p class="{{ $text_color }}">{{ $text }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-3">
                                <p><strong>Tổng số buổi theo lộ trình: {{ $lesson_number }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Tổng số buổi thực tế: {{ $total_schedules }}</strong></p>
                            </div>
                            @php
                                if ($delay_ratio > 0) {
                                    $text = 'Chậm ' . $delay_ratio . '%';
                                    $text_color = 'text-red';
                                }
                                if ($delay_ratio < 0) {
                                    $text = 'Nhanh ' . abs($delay_ratio) . '%';
                                    $text_color = 'text-green';
                                }
                                if ($delay_ratio == 0) {
                                    $text = 'Đúng tiến độ';
                                    $text_color = 'text-green';
                                }
                            @endphp
                            <div class="col-md-3">
                                <p><strong>Trung bình tiến độ: <span
                                            class="{{ $text_color }}">{{ $text }}</span></strong></p>
                            </div>
                            <div class="col-md-3">
                                <p class="pull-right"><strong>Tổng KPI tiến độ đào tạo: <span
                                            style="color:#00a65a">{{ $kpi_process }}%</span></strong></p>
                            </div>
                        </div>
                        <input type="hidden" value="{{ $kpi_process }}" class="kpi_process">
                    </div>
                </div>
            </div>
        @endif

        @if (isset($certificates) && count($certificates) > 0)
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase text-bold">
                        <i class="fa fa-graduation-cap"></i> @lang('Kết quả thi chứng chỉ B1')
                    </h3>
                    <button 
                        data-toggle="modal"data-target=".bd-example-modal-lg-detail"
                        style="margin-left: 20px" type="button"
                        class="btn btn-primary btn-sm pull-right"><i class="fa fa-eye"></i> Chi tiết 
                    </button>
                    <h3 class="box-title pull-right">Tổng số HV đào tạo trong năm (B1 và sau B1): {{ $totalUniqueStudentsInYear->count() }}</h3>

                </div>
                <div style="padding-top:0px" class="box-body">
                    <div style="overflow-x: unset" class="table table-responsive">
                        <table style="border: 1px solid #dddddd;" class="table table-hover table-striped ">
                            <thead>
                                <tr>
                                    <th>@lang('STT')</th>
                                    <th>@lang('Mã HV')</th>
                                    <th>@lang('Họ và tên')</th>
                                    <th>@lang('Lớp')</th>
                                    <th>@lang('Hình thức thi')</th>
                                    <th>@lang('Tổng KN')</th>
                                    <th>@lang('Nghe')</th>
                                    <th>@lang('Ngày báo điểm nghe')</th>
                                    <th>@lang('Nói')</th>
                                    <th>@lang('Ngày báo điểm nói')</th>
                                    <th>@lang('Đọc')</th>
                                    <th>@lang('Ngày báo điểm đọc')</th>
                                    <th>@lang('Viết')</th>
                                    <th>@lang('Ngày báo điểm viết')</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($certificates as $row)
                                    <tr class="valign-middle">
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td class="text-center">
                                            {{ $row->students->admin_code ?? ($row->json_params->admin_code ?? '') }}
                                        </td>
                                        <td>
                                            {{ $row->students->name ?? ($row->json_params->student_name ?? '') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->class->name ?? ($row->json_params->class_name ?? '') }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->type }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->total_skill ?? '' }}
                                        </td class="text-center">
                                        <td class="text-center">
                                            {{ $row->score_listen ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->day_score_listen != '' ? date('d/m/Y', strtotime($row->day_score_listen)) : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->score_speak ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->day_score_speak != '' ? date('d/m/Y', strtotime($row->day_score_speak)) : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->score_read ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->day_score_read != '' ? date('d/m/Y', strtotime($row->day_score_read)) : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->score_write ?? '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->day_score_write != '' ? date('d/m/Y', strtotime($row->day_score_write)) : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="modal fade bd-example-modal-lg-detail" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-full">
                <div id="alert-config">
                </div>
                <div class="modal-content">

                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                Danh sách học viên
                            </h4>
                        </div>

                        <div class="modal-body">
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr>
                                            <th>@lang('STT')</th>
                                            <th>@lang('Mã HV')</th>
                                            <th>@lang('Họ và tên')</th>
                                            <th>@lang('CCCD')</th>
                                            <th>@lang('Cơ sở')</th>
                                            <th>@lang('Danh sách lớp đã học')</th>
                                            <th>@lang('CBTS')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="show-user">
                                        @if(isset($totalUniqueStudentsInYear) && $totalUniqueStudentsInYear->count()>0)
                                            @foreach($totalUniqueStudentsInYear as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                                            title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                                            href="{{ route('students.show', $item->id) }}">
                                                            {{ $item->admin_code ?? "" }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->name ?? "" }}</td>
                                                    <td>{{ $item->json_params->cccd ?? '' }}</td>
                                                    <td>{{ $item->area->code ?? '' }} - {{ $item->area->name ?? '' }}</td>
                                                    <td>
                                                        @if (isset($item->list_class))
                                                            <ul>
                                                                @foreach ($item->list_class as $i)
                                                                    <li>{{ $i->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->admission->name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
    </section>

@endsection

@section('script')
    <script>
        $('.btn_deactive').click(function() {
            $(this).parents('.col-md-3').find('.box_btn_edit').show();
            $(this).hide();
        })
        $('.btn_exit').click(function() {
            $(this).parents('.col-md-3').find('.box_btn_edit').hide();
            $(this).parents('.col-md-3').find('.btn_deactive').show();
        });
        $('.btn_check').click(function() {
            $(this).parents('.col-md-3').find('.box_btn_edit').hide();
            $(this).parents('.col-md-3').find('.btn_deactive').show();
            updateKpi();
            updateAjaxKPI();
        })
        $('.input_time').on('change', function() {
            var max = $(this).attr('max'); // Lấy giá trị max từ thuộc tính max
            var value = $(this).val(); // Lấy giá trị hiện tại của input
            if (value > max) {
                $(this).val(max); // Nếu giá trị nhập vào lớn hơn max, set lại bằng max
            }
        });

        function updateKpi() {
            var kpi_score = Number(0);
            var kpi_process = Number(0);
            if ($('.kpi_score').val()) kpi_score = Number($('.kpi_score').val());
            if ($('.kpi_process').val()) kpi_process = Number($('.kpi_process').val());
            var kpi_manage_class = Number($('.kpi_manage_class').val());
            var kpi_behavor = Number($('.kpi_behavor').val());
            var total_kpi_year = Number(kpi_score + kpi_process + kpi_manage_class + kpi_behavor);

            $('.show-kpi-score').text(kpi_score);
            $('.show-kpi-process').text(kpi_process);
            $('.show-kpi-manage-class').text(kpi_manage_class);
            $('.show-kpi-behavor').text(kpi_behavor);
            $('.total_kpi_year').text(total_kpi_year);
        }
        $(document).ready(function() {
            updateKpi();
        });

        function updateAjaxKPI(th) {
            let kpi_year = $('.kpi_year').val();
            var teacher_id = $('.teacher_id').val();
            var kpi_class = $('.kpi_manage_class').val();
            var kpi_behavior = $('.kpi_behavor').val();
            var kpi_total = $('.total_kpi_year').text();
            var time_report = $('.time_report').text();
            let url = "{{ route('ajax_kpi_teacher_index') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    kpi_year: kpi_year,
                    teacher_id: teacher_id,
                    kpi_class: kpi_class,
                    kpi_behavior: kpi_behavior,
                    kpi_total: kpi_total,
                    time_report: time_report,
                },
                success: function(response) {
                    console.log(response);
                    if (response.message == 'success') {
                        $("#alert-config").append(
                            '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>'
                        );
                        setTimeout(function() {
                            $(".alert-success").fadeOut(2000, function() {});
                        }, 800);
                    }else{
                        alert('Bạn không có quyền chỉnh sửa');
                        location.reload();
                    }
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
@endsection
