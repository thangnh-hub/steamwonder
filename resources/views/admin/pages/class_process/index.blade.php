@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Từ khóa') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Nhập tên lớp, khóa...')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
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
                <h3 class="box-title">@lang('Báo cáo tổng hợp lộ trình')</h3>
                <button id="printButton" onclick="window.print()"
                    class="btn btn-sm btn-primary mb-2 pull-right">@lang('In thông tin')</button>
            </div>
            <div class="box-body">
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
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('Lớp')</th>
                                <th rowspan="2">@lang('Tên chương trình')</th>
                                <th rowspan="2">@lang('Khu vực')</th>
                                <th rowspan="2">@lang('Phòng')</th>
                                <th rowspan="2">@lang('Ca học')</th>
                                <th rowspan="2">@lang('Giáo viên')</th>
                                <th rowspan="2">@lang('Trạng thái')</th>
                                <th colspan="3">@lang('Buổi học')</th>
                                <th colspan="3">@lang('Thời gian')</th>
                            </tr>
                            <tr>
                                <th>@lang('Dự kiến')</th>
                                <th>@lang('Thực tế')</th>
                                <th>@lang('Tiến độ')</th>
                                <th>@lang('Bắt đầu')</th>
                                <th>@lang('Dự kiến')</th>
                                <th>@lang('Thực tế')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="bg-gray">
                                    <td colspan="7" class="text-bold" style="vertical-align: middle">
                                        {{ $row->name ?? '' }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        {{ $row->total_lesson }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        {{ $row->total_schedule }}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle">
                                        {{ $row->text_total }}
                                    </td>
                                    <td colspan="3">
                                        <a class="btn btn-sm btn-warning pull-right hide-print" data-toggle="tooltip"
                                            title="@lang('Cập nhật lộ trình')" data-original-title="@lang('Cập nhật lộ trình')"
                                            href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                    </td>
                                </tr>
                                @if ($row->a11)
                                    <tr>
                                        <td>
                                            {{ $row->a11_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->a11_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->a11_class->area->name ?? '' }}</td>
                                        <td>{{ $row->a11_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->a11_class->period->id ?? '' }}
                                            ({{ $row->a11_class->period->start_time ?? '' }}
                                            - {{ $row->a11_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->a11_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->a11_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->a11_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->a11_class->schedules)
                                                ? $row->a11_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->a11_syllabus->lesson) ||
                                                    !isset($row->a11_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->a11_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->a11_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a11->start_date != '' ? \Carbon\Carbon::parse($row->a11->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a11->end_date != '' ? \Carbon\Carbon::parse($row->a11->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a11->end_date_real != '' ? \Carbon\Carbon::parse($row->a11->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($row->a12)
                                    <tr>
                                        <td>
                                            {{ $row->a12_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->a12_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->a12_class->area->name ?? '' }}</td>
                                        <td>{{ $row->a12_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->a12_class->period->id ?? '' }}
                                            ({{ $row->a12_class->period->start_time ?? '' }}
                                            - {{ $row->a12_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->a12_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->a12_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->a12_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->a12_class->schedules)
                                                ? $row->a12_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->a12_syllabus->lesson) ||
                                                    !isset($row->a12_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->a12_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->a12_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a12->start_date != '' ? \Carbon\Carbon::parse($row->a12->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a12->end_date != '' ? \Carbon\Carbon::parse($row->a12->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a12->end_date_real != '' ? \Carbon\Carbon::parse($row->a12->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($row->a21)
                                    <tr>
                                        <td>
                                            {{ $row->a21_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->a21_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->a21_class->area->name ?? '' }}</td>
                                        <td>{{ $row->a21_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->a21_class->period->id ?? '' }}
                                            ({{ $row->a21_class->period->start_time ?? '' }}
                                            - {{ $row->a21_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->a21_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->a21_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->a21_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->a21_class->schedules)
                                                ? $row->a21_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->a21_syllabus->lesson) ||
                                                    !isset($row->a21_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->a21_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->a21_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a21->start_date != '' ? \Carbon\Carbon::parse($row->a21->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a21->end_date != '' ? \Carbon\Carbon::parse($row->a21->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a21->end_date_real != '' ? \Carbon\Carbon::parse($row->a21->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif

                                @if ($row->a22)
                                    <tr>
                                        <td>
                                            {{ $row->a22_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->a22_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->a22_class->area->name ?? '' }}</td>
                                        <td>{{ $row->a22_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->a22_class->period->id ?? '' }}
                                            ({{ $row->a22_class->period->start_time ?? '' }}
                                            - {{ $row->a22_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->a22_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->a22_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->a22_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->a22_class->schedules)
                                                ? $row->a22_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->a22_syllabus->lesson) ||
                                                    !isset($row->a22_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->a22_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->a22_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a22->start_date != '' ? \Carbon\Carbon::parse($row->a22->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a22->end_date != '' ? \Carbon\Carbon::parse($row->a22->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->a22->end_date_real != '' ? \Carbon\Carbon::parse($row->a22->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($row->b11)
                                    <tr>
                                        <td>
                                            {{ $row->b11_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->b11_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->b11_class->area->name ?? '' }}</td>
                                        <td>{{ $row->b11_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->b11_class->period->id ?? '' }}
                                            ({{ $row->b11_class->period->start_time ?? '' }}
                                            - {{ $row->b11_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->b11_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->b11_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->b11_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->b11_class->schedules)
                                                ? $row->b11_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->b11_syllabus->lesson) ||
                                                    !isset($row->b11_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->b11_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->b11_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->b11->start_date != '' ? \Carbon\Carbon::parse($row->b11->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->b11->end_date != '' ? \Carbon\Carbon::parse($row->b11->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->b11->end_date_real != '' ? \Carbon\Carbon::parse($row->b11->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($row->b12)
                                    <tr>
                                        <td>
                                            {{ $row->b12_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->b12_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->b12_class->area->name ?? '' }}</td>
                                        <td>{{ $row->b12_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->b12_class->period->id ?? '' }}
                                            ({{ $row->b12_class->period->start_time ?? '' }}
                                            - {{ $row->b12_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->b12_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->b12_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->b12_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->b12_class->schedules)
                                                ? $row->b12_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->b12_syllabus->lesson) ||
                                                    !isset($row->b12_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->b12_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->b12_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->b12->start_date != '' ? \Carbon\Carbon::parse($row->b12->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->b12->end_date != '' ? \Carbon\Carbon::parse($row->b12->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->b12->end_date_real != '' ? \Carbon\Carbon::parse($row->b12->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif
                                @if ($row->otcs)
                                    <tr>
                                        <td>
                                            {{ $row->otcs_class->name ?? '' }}
                                        </td>
                                        <td>{{ $row->otcs_syllabus->name ?? '' }}</td>
                                        <td>{{ $row->otcs_class->area->name ?? '' }}</td>
                                        <td>{{ $row->otcs_class->room->name ?? '' }}</td>
                                        <td>
                                            {{ $row->otcs_class->period->id ?? '' }}
                                            ({{ $row->otcs_class->period->start_time ?? '' }}
                                            - {{ $row->otcs_class->period->end_time ?? '' }})
                                        </td>
                                        <td>{{ $row->otcs_class->teacher->name ?? '' }}</td>
                                        <td>{{ __($row->otcs_class->status ?? '') }}</td>
                                        <td class="text-center">{{ $row->otcs_syllabus->lesson ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->otcs_class->schedules)
                                                ? $row->otcs_class->schedules->filter(fn($schedule) => $schedule->type === 'gv')->count()
                                                : '' }}
                                        </td>
                                        <td>
                                            @php
                                                $text = '';
                                                if (
                                                    !isset($row->otcs_syllabus->lesson) ||
                                                    !isset($row->otcs_class->schedules)
                                                ) {
                                                    $text = 'N/A';
                                                } else {
                                                    $day =
                                                        $row->otcs_class->schedules
                                                            ->filter(fn($schedule) => $schedule->type === 'gv')
                                                            ->count() - $row->otcs_syllabus->lesson;
                                                    if ($day < 0) {
                                                        $text = 'Nhanh: ' . abs($day) . ' ngày ';
                                                    } elseif ($day == 0) {
                                                        $text = 'Đúng tiến độ';
                                                    } else {
                                                        $text = 'Chậm: ' . $day . ' ngày ';
                                                    }
                                                }
                                            @endphp
                                            {{ $text }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->otcs->start_date != '' ? \Carbon\Carbon::parse($row->otcs->start_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->otcs->end_date != '' ? \Carbon\Carbon::parse($row->otcs->end_date)->format('d/m/Y') : '' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $row->otcs->end_date_real != '' ? \Carbon\Carbon::parse($row->otcs->end_date_real)->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
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
    </div>
@endsection
