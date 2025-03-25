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

        .table>tbody>tr>td {
            text-align: center;
            /* vertical-align: inherit; */
        }

        @media print {
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
            <form action="{{ route('report.ranking.level.class') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Tên lớp') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Nhập tên lớp')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Level')</label>
                                <select name="level_id" id="" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_level as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['level_id']) && $params['level_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Teacher')</label>
                                <select name="teacher_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($teachers as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['teacher_id']) && $params['teacher_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Ngày thi từ')</label>
                                <input type="date" name="from_day_exam" class="form-control"
                                    value="{{ $params['from_day_exam'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Đến')</label>
                                <input type="date" name="to_day_exam" class="form-control"
                                    value="{{ $params['to_day_exam'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.ranking.level.class') }}">
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
            <div class="box-header hide-print">
                <h3 class="box-title">@lang('Danh sách theo trình độ A1, A2')</h3>
                <button onclick="window.print()" class="btn btn-primary mb-2 pull-right">@lang('In thông tin')</button>
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
                @if (count($list_level) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Trình độ')</th>
                                <th rowspan="2">@lang('Lớp')</th>
                                <th rowspan="2">@lang('Khu vực')</th>
                                <th rowspan="2">@lang('Giáo viên')</th>
                                <th rowspan="2">@lang('Sĩ số')</th>
                                <th rowspan="2">@lang('Ngày thi')</th>
                                <th colspan="3">@lang('Tỉ lệ (%)')</th>
                                <th colspan="3">@lang('Tổng (%)')</th>
                            </tr>

                            <tr>
                                <th style="width:120px">@lang('Đạt - Lên trình')</th>
                                <th style="width:120px">@lang('Không đạt - đơn lên trình')</th>
                                <th style="width:120px">@lang('Không đạt - Học lại')</th>

                                <th style="width:120px">@lang('Đạt - Lên trình')</th>
                                <th style="width:120px">@lang('Không đạt - đơn lên trình')</th>
                                <th style="width:120px">@lang('Không đạt - Học lại')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($list_level)
                                @foreach ($list_level as $level)
                                    @php
                                        $stt = $loop->index + 1;
                                        $i = 1;
                                    @endphp
                                    @foreach ($level->class as $items)
                                        @php
                                            $teacher = \App\Models\Teacher::where(
                                                'id',
                                                $items->json_params->teacher ?? 0,
                                            )->first();
                                        @endphp
                                        @if ($i == 1)
                                            <tr>
                                                <td rowspan="{{ count($level->class) }}">{{ $stt }}</td>
                                                <td rowspan="{{ count($level->class) }}">{{ $level->name ?? '' }}</td>
                                                <td>
                                                    {{ $items->json_params->name->{$lang} ?? $items->name }}
                                                </td>
                                                <td>
                                                    {{ $items->area->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $teacher->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_student ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_level_up ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_fail ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_pass ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_level_up ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_fail ?? '' }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    {{ $items->json_params->name->{$lang} ?? $items->name }}
                                                </td>
                                                <td>
                                                    {{ $items->area->name ?? '' }}
                                                </td>

                                                <td>
                                                    {{ $teacher->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_student ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_level_up ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_fail ?? '' }}
                                                </td>
                                            </tr>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div class="box">
            <div class="box-header hide-print">
                <h3 class="box-title">@lang('Danh sách theo trình độ B1')</h3>
            </div>
            <div class="box-body box-alert">
                @if (count($list_level_B) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Trình độ')</th>
                                <th rowspan="2">@lang('Lớp')</th>
                                <th rowspan="2">@lang('Khu vực')</th>
                                <th rowspan="2">@lang('Giáo viên')</th>
                                <th rowspan="2">@lang('Sĩ số')</th>
                                <th rowspan="2">@lang('Ngày thi')</th>
                                <th colspan="5">@lang('Tỉ lệ (%)')</th>
                                <th colspan="5">@lang('Tổng (%)')</th>
                            </tr>

                            <tr>
                                <th style="width:120px">@lang('Đạt')</th>
                                {{-- <th style="width:120px">@lang('Đơn lên trình')</th>
                                <th style="width:120px">@lang('Trượt/Học lại')</th> --}}
                                <th style="width:120px">@lang('Đỗ Modul Viết')</th>
                                <th style="width:120px">@lang('Đỗ Modul Nói')</th>
                                <th style="width:120px">@lang('Đỗ Full')</th>
                                <th style="width:120px">@lang('Cần cố gắng')</th>
                                <th style="width:120px">@lang('Đạt')</th>
                                {{-- <th style="width:120px">@lang('Đơn lên trình')</th>
                                <th style="width:120px">@lang('Trượt/Học lại')</th> --}}
                                <th style="width:120px">@lang('Đỗ Modul Viết')</th>
                                <th style="width:120px">@lang('Đỗ Modul Nói')</th>
                                <th style="width:120px">@lang('Đỗ Full')</th>
                                <th style="width:120px">@lang('Cần cố gắng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($list_level_B)
                                @foreach ($list_level_B as $level)
                                    @php
                                        $stt = $loop->index + 1;
                                        $i = 1;
                                    @endphp
                                    @foreach ($level->class as $items)
                                        @php
                                            $teacher = \App\Models\Teacher::where(
                                                'id',
                                                $items->json_params->teacher ?? 0,
                                            )->first();
                                        @endphp
                                        @if ($i == 1)
                                            <tr>
                                                <td rowspan="{{ count($level->class) }}">{{ $stt }}</td>
                                                <td rowspan="{{ count($level->class) }}">{{ $level->name ?? '' }}</td>
                                                <td>
                                                    {{ $items->json_params->name->{$lang} ?? $items->name }}
                                                </td>
                                                <td>
                                                    {{ $items->area->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $teacher->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_student ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass ?? '' }}
                                                </td>
                                                {{-- <td>
                                                    {{ $items->person_level_up ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_fail ?? '' }}
                                                </td> --}}
                                                <td>
                                                    {{ $items->person_pass_write ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass_speak ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass_full ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_need_try ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_pass ?? '' }}
                                                </td>
                                                {{-- <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_level_up ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_fail ?? '' }}
                                                </td> --}}
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_pass_write ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_pass_speak ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_pass_full ?? '' }}
                                                </td>
                                                <td rowspan="{{ count($level->class) }}">
                                                    {{ $level->total_person_need_try ?? '' }}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    {{ $items->json_params->name->{$lang} ?? $items->name }}
                                                </td>
                                                <td>
                                                    {{ $items->area->name ?? '' }}
                                                </td>

                                                <td>
                                                    {{ $teacher->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->total_student ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->day_exam != '' ? date('d-m-Y', strtotime($items->day_exam)) : '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass ?? '' }}
                                                </td>
                                                {{-- <td>
                                                    {{ $items->person_level_up ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_fail ?? '' }}
                                                </td> --}}
                                                <td>
                                                    {{ $items->person_pass_write ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass_speak ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_pass_full ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $items->person_need_try ?? '' }}
                                                </td>
                                            </tr>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                @endforeach
                            @endisset
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
