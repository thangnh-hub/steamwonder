@extends('admin.layouts.app')
@push('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
@endpush
@section('title')
    @lang($module_name)
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
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route('report.score.student') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Chọn Khóa'):</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_course as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Chọn trình độ'):</label>
                                <select name="list_level" id="list_level" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_level as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['list_level']) && $value->id == $params['list_level'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Chọn lớp'): </label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc CCCD')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Xếp loại'):</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($rank as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.score.student') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <div class="box_alert">
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
        </div>

        {{-- Search form --}}
        @if (isset($rows) && count($rows) >0)
            <div class="box">
                <div class="box-body table-responsive">
                    <table class="table table-hover table-bordered table-sm">
                        <thead>
                            <tr>
                                <th rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Mã HV')</th>
                                <th rowspan="2">@lang('Họ tên')</th>
                                <th rowspan="2">@lang('CCCD')</th>
                                <th rowspan="2">@lang('Lớp')</th>
                                <th colspan="6">@lang('Điểm ')</th>
                                <th rowspan="2">@lang('Đã xếp lớp')</th>
                                <th rowspan="2" style="width:100px">@lang('Ghi chú xếp lớp')</th>
                                <th rowspan="2">@lang('Đã báo phụ huynh')</th>
                                <th rowspan="2" style="width:100px">@lang('Chi chú CSKH')</th>
                                <th rowspan="2">@lang('Hành động')</th>
                            </tr>
                            <tr>
                                <th style="width: 70px">@lang('Nghe')</th>
                                <th style="width: 70px">@lang('Nói')</th>
                                <th style="width: 70px">@lang('Đọc')</th>
                                <th style="width: 70px">@lang('Viết')</th>
                                <th style="width: 70px">@lang('TB')</th>
                                <th style="width: 90px">@lang('Xếp loại')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td class="text-center">
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->student->admin_code ?? '' }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('students.show', $row->student->id) }}">{{ $row->student->name ?? '' }}</a>
                                    </td>
                                    <td class="text-center">
                                        {{ $row->student->json_params->cccd ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        <p>{{ $row->class->name ?? '' }}</p>
                                        <p>Khóa: {{ $row->student->course->name ?? '' }}</p>
                                        <p>Trình độ: {{ $row->class->level->name ?? '' }}</p>
                                    </td>
                                    <td class="text-center">
                                        {{ $row->score_listen ?? '--' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->score_speak ?? '--' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->score_read ?? '--' }}
                                    </td>

                                    <td class="text-center">
                                        {{ $row->score_write ?? '--' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->json_params->score_average ?? '--' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $row->status != '' ? __($row->status) : '' }}
                                    </td>
                                    {{-- Quyền là CSKH chỉ được cập nhật phần ghi chú CSKH --}}
                                    <td class="text-center"><input type="checkbox" class="check_class"
                                            name="check_class" {{ $admin_auth->role == 11 ? 'disabled' : '' }}
                                            {{ isset($row->json_params->check_class) && $row->json_params->check_class == 1 ? 'checked' : '' }}
                                            class="form-check-input" value="1"></td>
                                    <td>
                                        <textarea {{ $admin_auth->role == 11 ? 'disabled' : '' }} rows="5" class="form-control note_class"
                                            name="note_class">{{ $row->json_params->note_class ?? '' }}</textarea>
                                    </td>
                                    {{-- Kết thúc --}}
                                    <td class="text-center"><input type="checkbox"
                                            class="check_cskh" name="check_cskh"
                                            {{ isset($row->json_params->check_cskh) && $row->json_params->check_cskh == 1 ? 'checked' : '' }}
                                            class="form-check-input" value="1"></td>

                                    <td>
                                        <textarea rows="5" class="form-control note_cskh" name="note_cskh">{{ $row->json_params->note_cskh ?? '' }}</textarea>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-sm btn-primary btn_savee_note"
                                            data-id="{{ $row->student->id }}"
                                            data-class="{{ $row->class->id }}">@lang('Lưu lại')</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
        @else
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @lang('not_found')
            </div>
        @endif

    </section>
    </div>
@endsection
@section('script')
    <script>
        $('.btn_savee_note').click(function() {
            var user_id = $(this).data('id');
            var class_id = $(this).data('class');
            var note_class = $(this).parents('tr').find('.note_class').val();
            var note_cskh = $(this).parents('tr').find('.note_cskh').val();
            var check_class = $(this).parents('tr').find('input[name="check_class"]:checked').map(function() {
                return this.value;
            }).get();
            var check_cskh = $(this).parents('tr').find('input[name="check_cskh"]:checked').map(function() {
                return this.value;
            }).get();
            var _url = "{{ route('update.json.score') }}";
            var _json = {
                @if ($admin_auth->role != 11)
                    "note_class": note_class,
                    "check_class": (check_class.length === 0) ? 0 : check_class[0],
                @endif
                "note_cskh": note_cskh,
                "check_cskh": (check_cskh.length === 0) ? 0 : check_cskh[0],
            }
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "user_id": user_id,
                    "class_id": class_id,
                    "json": _json,
                },
                success: function(response) {
                    if (response.data != null) {
                        var _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;

                    }
                    $('.box_alert').prepend(_html);
                    $('html, body').animate({
                        scrollTop: $(".alert").offset().top
                    }, 1000);
                    setTimeout(function() {
                        $('.alert').remove();
                    }, 3000);
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        })
    </script>
@endsection
