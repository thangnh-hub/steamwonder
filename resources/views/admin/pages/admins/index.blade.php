@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <div class="pull-right" style="display: flex; margin-left:15px ">
                <input class="form-control" type="file" name="files" id="fileImport" placeholder="@lang('Select File')">
                <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                    <i class="fa fa-file-excel-o"></i>
                    @lang('Import dữ liệu')</button>
            </div> --}}
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i>
                @lang('Thêm mới người dùng')</a>

        </h1>

    </section>
@endsection
@push('styles')
    <style>
        ul {
            padding-left: 15px;
        }
    </style>
    @section('content')
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('Filter')</h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <form action="{{ route(Request::segment(2) . '.index') }}" method="GET" id="form_filter">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Keyword') </label>
                                    <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                        value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Role')</label>
                                    <select name="role" id="roles" class="form-control select2" style="width: 100%;">
                                        <option value="">@lang('Please select')</option>
                                        @foreach ($roles as $item)
                                            <option value="{{ $item->id }}"
                                                {{ isset($params['role']) && $item->id == $params['role'] ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Admin type')</label>
                                    <select name="admin_type" id="admin_type" class="form-control select2" style="width: 100%;">
                                        <option value="">@lang('Please select')</option>
                                        @foreach ($admin_type as $key => $val)
                                            <option value="{{ $key }}"
                                                {{ isset($params['admin_type']) && $val == $params['admin_type'] ? 'selected' : '' }}>
                                                @lang($val)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Area')</label>
                                    <select name="area_id" id="area" class="form-control select2" style="width: 100%;">
                                        <option value="">@lang('Please select')</option>
                                        @foreach ($area as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset($params['area_id']) && $val->id == $params['area_id'] ? 'selected' : '' }}>
                                                {{ __($val->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Phòng ban')</label>
                                    <select name="department_id" id="department_id" class="form-control select2"
                                        style="width: 100%;">
                                        <option value="">@lang('Please select')</option>
                                        @foreach ($departments as $val)
                                            <option value="{{ $val->id }}"
                                                {{ isset($params['department_id']) && $val->id == $params['department_id'] ? 'selected' : '' }}>
                                                {{ __($val->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                        <option value="">@lang('Please select')</option>
                                        @foreach ($status as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                                {{ __($value) }}</option>
                                        @endforeach
                                    </select>
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
                                        <a href="javascript:void(0)" data-url="{{ route('admin.export_admin') }}"
                                            class="btn btn-sm btn-success btn_export"><i class="fa fa-file-excel-o"></i>
                                            @lang('Export dữ liệu')</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="box">
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

                    @if (!$admins->total())
                        <div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            @lang('No record found on the system!')
                        </div>
                    @else
                        <table class="table table-hover table-bordered sticky">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã</th>
                                    <th>@lang('Full name')</th>
                                    <th>@lang('Email/SĐT')</th>
                                    <th>@lang('Thuộc khu vực')</th>
                                    <th>@lang('Khu vực được quản lý')</th>
                                    <th>@lang('Phòng ban')</th>
                                    <th>@lang('Admin type')</th>
                                    <th>@lang('Role')</th>
                                    <th>@lang('Chức năng mở rộng')</th>
                                    <th>@lang('Direct manager')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($admins as $admin)
                                    <form action="{{ route(Request::segment(2) . '.destroy', $admin->id) }}" method="POST"
                                        onsubmit="return confirm('@lang('confirm_action')')">
                                        <tr class="valign-middle">
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $admin->admin_code ?? '' }}
                                            </td>
                                            <td>
                                                {{ $admin->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $admin->email ?? '' }}
                                                {{ $admin->phone != '' ? ' / ' . $admin->phone : '' }}
                                            </td>
                                            <td>
                                                {{ $admin->area->name ?? '' }}
                                            </td>
                                            <td>

                                                @if (isset($admin->area_extends))
                                                    <ul>
                                                        @foreach ($admin->area_extends as $i)
                                                            <li>{{ $i->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $admin->department->name ?? '' }}
                                            </td>
                                            <td>
                                                @lang($admin->admin_type)
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>{{ $admin->role_name }}</li>
                                                    @isset($admin->role_extends)
                                                        @foreach ($admin->role_extends as $i)
                                                            <li>{{ $i->name }}</li>
                                                        @endforeach
                                                    @endisset
                                                </ul>
                                            </td>
                                            <td>
                                                @isset($admin->function_extends)
                                                    <ul>
                                                        @foreach ($admin->function_extends as $i)
                                                            <li>{{ $i->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endisset
                                            </td>
                                            <td>
                                                {{ $admin->direct_manager->name ?? '' }}
                                            </td>
                                            <td>
                                                @lang($admin->status)
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="@lang('Edit')" data-original-title="@lang('Edit')"
                                                    href="{{ route(Request::segment(2) . '.edit', $admin->id) }}">
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

                @if ($admins->hasPages())
                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-sm-5">
                                Tìm thấy {{ $admins->total() }} kết quả
                            </div>
                            <div class="col-sm-7">
                                {{ $admins->withQueryString()->links('admin.pagination.default') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endsection

    @section('script')
        <script>
            $('.btn_export').click(function() {
            show_loading_notification()
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
                        a.download = 'Admin.xlsx';
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        hide_loading_notification()
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
                        hide_loading_notification()
                    }
                },
                error: function(response) {
                    hide_loading_notification()
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })
            function importFile() {
                show_loading_notification();
                var formData = new FormData();
                var file = $('#fileImport')[0].files[0];
                if (file == null) {
                    alert('Cần chọn file để Import!');
                    return;
                }
                formData.append('file', file);
                formData.append('_token', '{{ csrf_token() }}');
                $.ajax({
                    url: '{{ route('admin.import_user') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        hide_loading_notification();
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
                    },
                    error: function(response) {
                        // Get errors
                        hide_loading_notification();
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            }
        </script>
    @endsection
