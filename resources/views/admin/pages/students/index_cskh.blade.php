@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('students.create') }}"><i
                    class="fa fa-plus"></i> @lang('Thêm mới học viên')</a>
            {{-- <form style="margin-right: 10px" class=" pull-right" action="{{ route('export_student') }}" method="get"
                enctype="multipart/form-data">
                <input type="hidden" name="keyword" value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                <input type="hidden" name="status" value="{{ isset($params['status']) ? $params['status'] : '' }}">
                <input type="hidden" name="status_study"
                    value="{{ isset($params['status_study']) ? $params['status_study'] : '' }}">
                <input type="hidden" name="admission_id"
                    value="{{ isset($params['admission_id']) ? $params['admission_id'] : '' }}">
                <input type="hidden" name="course_id"
                    value="{{ isset($params['course_id']) ? $params['course_id'] : '' }}">
                <input type="hidden" name="class_id" value="{{ isset($params['class_id']) ? $params['class_id'] : '' }}">
                <input type="hidden" name="area_id" value="{{ isset($params['area_id']) ? $params['area_id'] : '' }}">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                    @lang('Export học viên')</button>
            </form> --}}
        </h1>
    </section>
@endsection

@section('content')
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
            <form action="{{ route('student.cskh') }}" id="form_filter" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Khóa học')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : '' }}>
                                            {{ __($value->name ?? '') }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['course_id']) && $params['course_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('State')</label>
                                <select name="state" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['state']) && $key == $params['state'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['state']) && $params['state'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Status Study')</label>
                                <select name="status_study" id="status_study" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status_study as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['status_study']) && $value->id == $params['status_study'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['status_study']) && $params['status_study'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Admissions')</label>
                                <select name="admission_id" id="admission_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($staffs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}
                                            (Mã: {{ $value->admin_code }})
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['admission_id']) && $params['admission_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['class_id']) && $params['class_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($area as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                            (Mã: {{ $value->code }})
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['area_id']) && $params['area_id'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Loại hợp đồng')</label>
                                <select name="contract_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_type as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['contract_type']) && $value == $params['contract_type'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['contract_type']) && $params['contract_type'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trạng thái hợp đồng')</label>
                                <select name="contract_status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_status as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['contract_status']) && $value == $params['contract_status'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
                                    @endforeach
                                    <option value="null"
                                        {{ isset($params['contract_status']) && $params['contract_status'] == 'null' ? 'selected' : '' }}>
                                        @lang('Chưa cập nhật')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Ngành nghề')</label>
                                <select name="field_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($field as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['field_id']) && $value->id == $params['field_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10" href="{{ route('student.cskh') }}">
                                        @lang('Reset')
                                    </a>
                                    <a href="javascript:void(0)" data-url="{{ route('export_student') }}"
                                        class="btn btn-sm btn-success btn_export"><i class="fa fa-file-excel-o"></i>
                                        @lang('Export học viên')</a>
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Order')</th>
                                <th>@lang('Student code')</th>
                                <th>@lang('Full name')</th>
                                <th>@lang('CCCD')</th>
                                <th>@lang('Gender')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Khóa học')</th>
                                <th>@lang('Admissions')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Status Study')</th>
                                <th>@lang('Loại hợp đồng')</th>
                                <th>@lang('Trạng thái hợp đồng')</th>
                                <th>@lang('Ngành nghề')</th>
                                <th>@lang('Ghi chú (CSKH)')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                @endphp

                                <form id="form_{{ $row->id }}" action="{{ route('student.cskh_update') }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <tr class="valign-middle">
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                                title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route('students.show', $row->id) }}">
                                                {{ $row->admin_code }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $row->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->cccd ?? '' }}
                                        </td>
                                        <td>
                                            @lang($row->gender)
                                        </td>
                                        <td>
                                            {{ $row->area->code ?? '' }}
                                        </td>
                                        <td>
                                            @if (isset($row->classs))
                                                <ul>
                                                    @foreach ($row->classs as $i)
                                                        <li>
                                                            {{ $i->name }}
                                                            ({{ __($i->pivot->status ?? '') }})
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>{{ $row->course->name ?? '' }}</td>
                                        <td>
                                            {{-- {{ $staff->name ?? '' }} --}}
                                            {{ $staff->admin_code ?? '' }}
                                        </td>
                                        <td>
                                            @lang($row->state)
                                        </td>
                                        <td>
                                            @lang($row->status_study_name ?? 'Chưa cập nhật')
                                        </td>
                                        <td>
                                            <select name="json_params[contract_type]" class=" form-control select2" style="width: 100%">
                                                <option value="">Chọn</option>
                                                @foreach ($contract_type as $val)
                                                    <option value="{{ $val }}"
                                                        {{ isset($row->json_params->contract_type) && $row->json_params->contract_type == $val ? 'selected' : '' }}>
                                                        {{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="json_params[contract_status]" class=" form-control select2" style="width: 100%">
                                                <option value="">Chọn</option>
                                                @foreach ($contract_status as $val)
                                                    <option value="{{ $val }}"
                                                        {{ isset($row->json_params->contract_status) && $row->json_params->contract_status == $val ? 'selected' : '' }}>
                                                        {{ $val }}</option>
                                                @endforeach
                                            </select>
                                            {{-- {{ $row->json_params->contract_status }} --}}
                                        </td>
                                        <td>
                                            <select name="json_params[field_id][]" multiple class=" form-control select2" style="width: 100%">
                                                @foreach ($field as $val)
                                                    <option value="{{ $val->id }}"
                                                        {{ isset($row->json_params->field_id) && in_array($val->id, $row->json_params->field_id) ? 'selected' : '' }}>
                                                        {{ $val->name ?? '' }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <textarea name="json_params[note_cskh]" style="width: 200px; height: 100px;" class="form-control"
                                                onfocus="this.placeholder = 'Nhập ghi chú của bạn...'" onblur="this.placeholder = 'Nhấp vào để nhập ghi chú...'"
                                                placeholder="Nhấp vào để nhập ghi chú...">{{ $row->json_params->note_cskh ?? '' }}</textarea>
                                        <td>
                                            <div class="d-flex-wap" style="width: 110px">
                                                <button type="button" data-id = "{{ $row->id }}"
                                                    style="margin-right: 10px" class="btn btn-success btn_update">
                                                    @lang('Lưu')</button>
                                                <a href="{{ route('students.edit', $row->id) }}"
                                                    class="btn btn-warning">@lang('Sửa')</a>
                                            </div>
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
        $('.btn_update').click(function() {
            var id = $(this).data('id');
            var formData = $('#form_' + id).serialize();
            var url = "{{ route('student.cskh_update') }}";
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.data != null) {
                        var _html = `<div class="alert alert-` + response.data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 5000);
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
                        }, 5000);
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + errors + `
                            </div>`;
                    $('.table-responsive').prepend(_html);
                    $('html, body').animate({
                        scrollTop: $(".alert-warning").offset().top
                    }, 1000);
                    setTimeout(function() {
                        $('.alert-warning').remove();
                    }, 5000);
                }
            });
        })

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
                        a.download = 'Student.xlsx';
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
    </script>
@endsection
