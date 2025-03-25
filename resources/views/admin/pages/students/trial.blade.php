@extends('admin.layouts.app')
@push('style')
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
@endpush
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
        </h1>
    </section>
@endsection


@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Importing, please wait')...</p>
    </div>
    <section class="content" style="margin-bottom: 0px; padding-bottom: 0px;min-height:0px;">
        <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            @lang('Import mã học viên mới')
                        </h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <form role="form" action="{{ route('trial_student.import_trial_student') }}" method="POST"
                            id="form_student" enctype="multipart/form-data">
                            @csrf
                            <p class="text-danger">@lang('Lưu ý: Việc Import tại đây chỉ thực hiện cập nhật lại mã học viên  ')</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" type="file" required name="file" id="file"
                                            placeholder="@lang('Select File')">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary" onclick="submitForm()"><i
                                                class="fa fa-file-excel-o" aria-hidden="true"></i>
                                            @lang('Import')</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
            <form action="{{ route('trial_student.index') }}" method="GET" id="form_filter">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" id="keyword" name="keyword"
                                    placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Khóa học')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : '' }}>
                                            {{ __($value->name ?? '') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('State')</label>
                                <select name="state" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['state']) && $key == $params['state'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

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
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <button type="button" class="btn btn-sm btn-success export_trial_student" data-url="{{ route('trial_student.export_trial_student') }}"><i
                                            class="fa fa-file-excel-o"></i> @lang('Export')</button>
                                    {{-- <a class="btn btn-default btn-sm" href="{{ route('trial_student.index') }}">
                                        @lang('Reset')
                                    </a> --}}
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
                                <th>@lang('Mã HV hiện tại')</th>
                                <th>@lang('Full name')</th>
                                <th>@lang('CCCD')</th>
                                <th>@lang('Gender')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Admissions')</th>
                                <th>@lang('Status Study')</th>
                                <th>@lang('Mã HV mới')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                @endphp
                                <tr class="valign-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                            title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                            href="{{ route('students.show', $row->id) }}">
                                            {{ isset($row->json_params->trial_code) && $row->json_params->trial_code != '' ? $row->json_params->trial_code : $row->admin_code }}
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
                                        {{ $row->area_name }}
                                    </td>
                                    <td>
                                        @if (isset($row->classs))
                                            <ul>
                                                @foreach ($row->classs as $i)
                                                    <li>{{ $i->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $staff->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->status_study_name ?? 'Chưa cập nhật')
                                    </td>
                                    <td>
                                        <div class="box_change_code" data-id="{{ $row->id }}"
                                            style="display: flex">
                                            <input type="text" name="code[{{ $row->id }}]"
                                                class="form-control change_admin_code"
                                                value="{{ isset($row->json_params->trial_code) && $row->json_params->trial_code != '' ? $row->admin_code : '' }}">
                                            <div class="btn-set pull-right">
                                                <button type="submit" class="btn btn-info btn_change_admin_code">
                                                    <i class="fa fa-save"></i> @lang('Save')
                                                </button>
                                            </div>
                                        </div>

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
        let eventInProgress = false;
        $(document).ready(function() {
            $('.export_trial_student').click(function() {
                var formData = $('#form_filter').serialize();
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: formData,
                    success: function(data) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = 'Trial_Student.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                        eventInProgress = false;
                    }
                });
            })
            $('.change_admin_code').on('change', function() {
                if (eventInProgress) {
                    return;
                }
                eventInProgress = true;
                var id = $(this).parents('.box_change_code').data('id');
                var code = $(this).val();
                if (code == null) {
                    alert('Cần nhập mã học viên');
                    eventInProgress = false;
                    return
                }
                $.ajax({
                    url: "{{ route('trial_student.change_admin_code') }}",
                    type: 'GET',
                    data: {
                        id: id,
                        code: code
                    },
                    success: function(response) {
                        if (response.data != null) {
                            var _html = `<div class="alert-` + id + ` alert alert-` + response
                                .data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                        } else {
                            var _html = `<div class="alert-` + id + ` alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        }
                        $('.table-responsive').prepend(_html);
                        setTimeout(function() {
                            $('.alert-' + id).remove();
                        }, 3000);
                        eventInProgress = false;
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                        eventInProgress = false;
                    }
                });


            })
            $('.btn_change_admin_code').on('click', function() {
                if (eventInProgress) {
                    return;
                }
                eventInProgress = true;
                var id = $(this).parents('.box_change_code').data('id');
                var code = $(this).parents('.box_change_code').find('.change_admin_code').val();
                if (code == null) {
                    alert('Cần nhập mã học viên');
                    eventInProgress = false;
                    return
                }
                $.ajax({
                    url: "{{ route('trial_student.change_admin_code') }}",
                    type: 'GET',
                    data: {
                        id: id,
                        code: code
                    },
                    success: function(response) {
                        if (response.data != null) {
                            var _html = `<div class="alert-` + id + ` alert alert-` + response
                                .data + ` alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                        } else {
                            var _html = `<div class="alert-` + id + ` alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        }
                        $('.table-responsive').prepend(_html);
                        setTimeout(function() {
                            $('.alert-' + id).remove();
                        }, 3000);
                        eventInProgress = false;
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                        eventInProgress = false;
                    }
                });
            })
        });

        function submitForm() {
            if (document.getElementById('file').files.length == 0) {
                alert("Vui lòng chọn file trước khi thực hiện!");
                return;
            }
            document.getElementById("loading-notification").style.display = "flex";
            document.getElementById("form_student").submit();
        }
    </script>
@endsection
