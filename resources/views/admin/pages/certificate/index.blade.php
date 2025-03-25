@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
@endsection
@section('content-header')
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
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form id="form_filter" action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên hoặc mã học viên')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <select name="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Giáo viên')</label>
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
                                <label>@lang('Số kỹ năng')</label>
                                <select name="total_skill" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @for ($i = 1; $i < 5; $i++)
                                        <option value="{{ $i }}"
                                            {{ isset($params['total_skill']) && $params['total_skill'] == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Năm') <span class="text-red">*</span></label>
                                <input type="text" class="form-control" required name="year"
                                    placeholder="@lang('Năm')"
                                    value="{{ isset($params['year']) ? $params['year'] : date('Y') }}">
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

                                    <button class="btn btn-sm btn-success btn_export mr-10" type="button"
                                        data-url="{{ route('certificate.export') }}" style="margin-right: 5px"><i
                                            class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        @lang('Export DS')</button>
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
                <h3 class="box-title">@lang('Tổng hợp kết quả thi chứng chỉ B1 toàn hệ thống')</h3>

                <div class="pull-right" style="display: none; margin-left:15px ">
                    <a href="{{ url('data/certificate_student.xlsx') }}" class="btn btn-sm btn-default" download><i
                            class="fa fa-file-excel-o"></i>
                        @lang('File Mẫu')</a>
                    <input class="form-control" type="file" name="files" id="fileImport"
                        placeholder="@lang('Select File')">
                    <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                        <i class="fa fa-file-excel-o"></i>
                        @lang('Import học viên')</button>
                </div>
            </div>
            <div class="box-body table-responsive">
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

                <div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Hình thức thi')</th>
                                <th>@lang('Số HV đỗ 1 KN')</th>
                                <th>@lang('Số HV đỗ 2 KN')</th>
                                <th>@lang('Số HV đỗ 3 KN')</th>
                                <th>@lang('Số HV đỗ 4 KN')</th>
                                <th>@lang('Tổng: '){{ count($rows_all) }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($statistics as $key => $items)
                                @php
                                    $total = 0;
                                @endphp
                                <tr>
                                    <td>{{ $key }}</td>
                                    @foreach ($items as $val)
                                        @php
                                            $total += $val;
                                        @endphp
                                        <td>{{ $val }}</td>
                                    @endforeach
                                    <td>{{ $total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Mã HV')</th>
                                <th>@lang('Họ và tên')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Cơ sở')</th>
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
                                <th>@lang('GVCN - GV phụ')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Thao tác')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $row->students->admin_code ?? ($row->json_params->admin_code ?? '') }}
                                        </td>
                                        <td>
                                            {{ $row->students->name ?? ($row->json_params->student_name ?? '') }}
                                        </td>
                                        <td>
                                            {{ $row->class->name ?? ($row->json_params->class_name ?? '') }}
                                        </td>
                                        <td>
                                            {{ $row->class->area->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->type }}
                                        </td>
                                        <td>
                                            {{ $row->total_skill ?? '' }}
                                        </td>
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
                                        <td>
                                            {{ $row->teacher->name ?? ($row->json_params->teacher_name ?? '') }}{{ isset($row->assistant_teacher) && !empty($row->assistant_teacher->name) ? ' - ' . $row->assistant_teacher->name : '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->note ?? '' }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Update')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
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
    </section>
@endsection
@section('script')
    <script>
        $('.btn_export').click(function() {
            var formData = $('#form_filter').serialize();
            var url = $(this).data('url');
            $('#loading-notification').css('display', 'flex');
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
                        a.download = 'Chung_chi_b1.xlsx';
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
                    $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    $('#loading-notification').css('display', 'none');
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });
        })

        function importFile() {
            var formData = new FormData();
            var file = $('#fileImport')[0].files[0];
            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                url: '{{ route('certificate.import_student') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                    // $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
@endsection
