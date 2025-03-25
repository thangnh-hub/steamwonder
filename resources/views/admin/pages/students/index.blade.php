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

            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
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
            {{-- <a class="btn btn-sm btn-primary pull-right mr-10" href="{{ route('student.excel.import') }}"><i
                class="fa fa-upload"></i> @lang('Import học viên')</a> --}}
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
            <form action="{{ route(Request::segment(2) . '.index') }}" id="form_filter" method="GET">
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
                                <label>@lang('Loại hợp đồng')</label>
                                <select name="contract_type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_type as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['contract_type']) && $value == $params['contract_type'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
                                    @endforeach
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
                                <label>@lang('Năm nhập học')</label>
                                <select name="year_offical" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($year_offical as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['year_offical']) && $value == $params['year_offical'] ? 'selected' : '' }}>
                                            {{ __($value) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit"
                                        class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route(Request::segment(2) . '.index') }}">
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
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right" href="{{ route(Request::segment(2) . '.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route(Request::segment(2) . '.index') }}?lang={{ $item->lang_locale }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @endif
                    @endforeach
                @endisset
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
                                <th>@lang('Ngày nhập học chính thức')</th>
                                <th>@lang('Loại hợp đồng')</th>
                                <th>@lang('Hợp đồng')</th>
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

                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>{{ $loop->index + 1 }}</td>

                                        <td>
                                            <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                                title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">
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
                                            @php
                                                $list_class = $row->allClassesWithStatus();
                                            @endphp
                                            {{-- @if (isset($row->classs))
                                                <ul>
                                                    @foreach ($row->classs as $i)
                                                        <li>
                                                            {{ $i->name }}
                                                            ({{ __($i->pivot->status ?? '') }})
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif --}}
                                            @if (isset($list_class))
                                                <ul>
                                                    @foreach ($list_class as $i)
                                                        <li>
                                                            {{ $i->name }}
                                                            ({{ __($i->pivot_status  ?? '') }})
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
                                            {{ isset($row->day_official) &&  $row->day_official !="" ?date("d-m-Y", strtotime($row->day_official)): '' }}
                                        </td>

                                        <td>
                                            {{ $row->json_params->contract_type ?? '' }}
                                        </td>
                                        
                                        <td>
                                            {{ $row->json_params->contract_status ?? '' }}
                                        </td>
                                        <td>
                                            @if (isset($row->json_params->field_id))
                                                <ul>
                                                    @foreach ($row->json_params->field_id as $val)
                                                        @php
                                                            $val_field = $field->first(function ($item, $key) use (
                                                                $val ) {
                                                                return $item->id == $val;
                                                            });
                                                        @endphp
                                                        <li>{{ $val_field->name ?? '' }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->json_params->note_cskh ?? '' }}
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
    <div id="import_excel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Import Excel')</h4>
                </div>
                <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST"
                    id="form_product" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row">
                        <input type="hidden" name="import" value="true">
                        <input type="hidden" name="name" value="import">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('File') <a href="{{ url('data/images/Import_excel.png') }}"
                                        target="_blank">(@lang('Sample file structure'))</a></label>
                                <small class="text-red">*</small>
                                <input id="file" class="form-control" type="file" required name="file"
                                    placeholder="@lang('Select File')" value="">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-file-excel-o"
                                aria-hidden="true"></i> @lang('Import')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    </div>
@endsection
@section('script')
    <script>
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
