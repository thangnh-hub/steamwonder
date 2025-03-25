@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        #alert-config {
            width: auto !important;
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
        </h1>
    </section>
@endsection
@section('content')
    <div id="alert-config">

    </div>
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
            <form action="{{ route('report.class.null') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <input type="text" name="keyword" class="form-control"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}"
                                    placeholder="Nhập tên lớp">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Khu vực')</label>
                                <select name="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $item->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Tháng') </label>
                                <input type="month" class="form-control" name="month"
                                    value="{{ isset($params['month']) ? $params['month'] : date('Y-m', time()) }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Phân loại') *</label>
                                <select name="type" required class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    <option value="evaluations"
                                        {{ isset($params['type']) && $params['type'] == 'evaluations' ? 'selected' : '' }}>
                                        Thiếu nhận xét</option>
                                    <option value="attendance"
                                        {{ isset($params['type']) && $params['type'] == 'attendance' ? 'selected' : '' }}>
                                        Thiếu điểm danh</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trạng thái lớp')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.class.null') }}">
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
                <h3 class="box-title">
                    @lang('Danh sách lớp chưa nhận xét - điểm danh')
                    trong tháng {{ date('m - Y', strtotime($params['month'])) }}</h3>

                <button class="pull-right btn btn-sm btn-success btn_export"
                    data-url="{{ route('report.class.null.export') }}" style="margin-right: 5px"><i
                        class="fa fa-file-excel-o" aria-hidden="true"></i>
                    @lang('Export dữ liệu ')</button>
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
                @if (!isset($rows) || count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên lớp</th>
                                <th>Giáo viên</th>
                                <th>Khu vực</th>
                                <th>Sĩ số</th>
                                <th>Trạng thái</th>
                                <th>Loại</th>
                                <th> {{ $params['type'] == 'evaluations' ? 'Số lần nhận xét' : 'Số buổi chưa điểm danh' }}
                                    trong tháng
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                                @php
                                    $teacher = \App\Models\Teacher::where(
                                        'id',
                                        $item->json_params->teacher ?? 0,
                                    )->first();
                                    if ($params['type'] == 'attendance') {
                                        $unAttendanceDates = $item->schedules
                                            ->pluck('date')
                                            ->map(function ($date) {
                                                return \Carbon\Carbon::parse($date)->format('d/m/Y'); // Định dạng ngày tháng năm
                                            })
                                            ->toArray();
                                    }
                                    // dd($item->students);
                                @endphp
                                <tr class="valign-middle">
                                    <td class="text-center">
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        @if (isset($params['type']) && $params['type'] == 'evaluations')
                                            <a target="_blank"
                                                href="{{ route('evaluationclass.history', ['class_id' => $item->id]) }}">{{ $item->name }}</a>
                                        @else
                                            <a target="_blank"
                                                href="{{ route('schedule_class.index', ['class_id' => $item->id]) }}">{{ $item->name }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $teacher->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $item->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ count($item->students ?? []) }}
                                    </td>
                                    <td>
                                        {{ $class_status[$item->status] }}
                                    </td>
                                    <td>
                                        {{ isset($params['type']) && $params['type'] == 'evaluations' ? 'Thiếu nhận xét' : 'Thiếu điểm danh' }}
                                    </td>
                                    <td>
                                        @if ($params['type'] == 'attendance')
                                            {{ $item->total }} ({{ implode(', ', $unAttendanceDates) }})
                                        @else
                                            {{ $item->total }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ isset($rows) ? count($rows) : 0 }} kết quả
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.btn_export').click(function() {
                var formData = $('#form_filter').serialize();
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: formData,
                    success: function(response) {
                        if (response) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = 'Thong_ke_lop_hoc.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Bạn không có quyền thao tác chức năng này!
                                </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert').remove();
                            }, 3000);
                        }

                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                        eventInProgress = false;
                    }
                });
            })
        });
    </script>
@endsection
