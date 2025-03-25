@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        ul {
            padding-inline-start: 16px;
        }

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
        <p>@lang('Please wait')...</p>
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
            <form action="{{ route('dormitory.liststudent') }}" method="GET" id="form_filter">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Học viên')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Gender')</label>
                                <select name="gender_user" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($gender as $key => $val)
                                        <option value="{{ $val }}"
                                            {{ isset($params['gender_user']) && $val == $params['gender_user'] ? 'selected' : '' }}>
                                            {{ __($val) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Phòng')</label>
                                <select name="dormitory" id="dormitory" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($dormitory as $key => $value)
                                        @if (isset($params['area_id']) && $params['area_id'] != '')
                                            @if ($value->area_id == $params['area_id'])
                                                <option value="{{ $value->id }}"
                                                    {{ isset($params['dormitory']) && $value->id == $params['dormitory'] ? 'selected' : '' }}>
                                                    {{ __($value->name) }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $value->id }}"
                                                {{ isset($params['dormitory']) && $value->id == $params['dormitory'] ? 'selected' : '' }}>
                                                {{ __($value->name) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái phòng')</label>
                                <select name="status" id="status" class="form-control " style="width: 100%;">
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
                                <label>@lang('Tiêu chí thời gian')</label>
                                <select name="type" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    <option value="come"
                                        {{ isset($params['type']) && 'come' == $params['type'] ? 'selected' : '' }}>
                                        @lang('Ngày vào')</option>
                                    <option value="leave"
                                        {{ isset($params['type']) && 'leave' == $params['type'] ? 'selected' : '' }}>
                                        @lang('Ngày ra')</option>
                                    <option value="expire"
                                        {{ isset($params['type']) && 'expire' == $params['type'] ? 'selected' : '' }}>
                                        @lang('Ngày hết hạn')</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Từ tháng')</label>
                                <input type="month" class="form-control month-picker" name="from_month"
                                    placeholder="@lang('Chọn tháng')" value="{{ $params['from_month'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Đến tháng')</label>
                                <input type="month" class="form-control month-picker" name="to_month"
                                    placeholder="@lang('Chọn tháng')" value="{{ $params['to_month'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái HV')</label>
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

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('dormitory.liststudent') }}">
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
                <h3 class="box-title">@lang('Danh sách học viên - phòng ')</h3>
                <div class="pull-right">
                    <a class="pull-right" href="{{ url('data/dormitory_student.xlsx') }}">@lang('File mẫu')</a>
                    <div class="pull-right" style="display: flex; margin-left:15px ">
                        <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                            <i class="fa fa-file-excel-o"></i>
                            @lang('Import dữ liệu học viên')</button>
                        <input class="form-control" type="file" name="files" id="fileImport"
                            placeholder="@lang('Select File')">
                    </div>

                    @if (count($rows) > 0)
                        <button class="btn btn-sm btn-danger pull-right delete_student_all" title="@lang('Delete')">
                            @lang('Xóa học viên đã chọn')
                        </button>
                    @endif
                    <button class="btn btn-sm btn-warning pull-right" style="margin-right: 5px" data-toggle="modal"
                        data-backdrop="static" data-keyboard="false" data-target="#add_hv"><i class="fa fa-plus"></i>
                        @lang('Add học viên')</button>
                    <button class="btn btn-sm btn-success btn_export" data-url="{{ route('dormitory.export_student') }}"
                        style="margin-right: 5px"><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        @lang('Export học viên')</button>
                </div>

            </div>
            <div class="box-body table-responsive box_alert">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="check_all"> @lang('Chọn tất')</th>
                                <th>@lang('Mã HV')</th>
                                <th>@lang('Họ tên')</th>
                                <th>@lang('CBTS')</th>
                                <th>@lang('Giới tính')</th>
                                <th>@lang('Khóa')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Trạng thái HV')</th>
                                <th>@lang('Phòng')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Trạng thái phòng')</th>
                                <th>@lang('Ngày vào KTX')</th>
                                <th>@lang('Ngày ra KTX')</th>
                                <th>@lang('Ngày hết hạn KTX')</th>
                                <th>@lang('Đơn vào KTX')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                    $course = \App\Models\Course::find($row->course_id ?? 0);

                                @endphp
                                <tr class="valign-middle">
                                    <td class="text-center">
                                        <input class="ckeck_delete" type="checkbox" value="{{ $row->id }}">
                                    </td>
                                    <td> <strong style="font-size: 14px;">{{ $row->admin_code }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->user_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $staff->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->user_gender)
                                    </td>
                                    <td>
                                        {{ $course->name ?? '' }}
                                    </td>
                                    <td>
                                        @if (isset($row->student->classs))
                                            <ul>
                                                @foreach ($row->student->classs as $i)
                                                    <li>{{ $i->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $row->student->StatusStudent->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->dormitory->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->dormitory->area->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->status)
                                    </td>
                                    <td>
                                        {{ $row->time_in != '' ? date('d/m/Y', strtotime($row->time_in)) : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $row->time_out != '' ? date('d/m/Y', strtotime($row->time_out)) : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $row->time_expires != '' ? date('d/m/Y', strtotime($row->time_expires)) : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->don_vao ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->ghi_chu ?? '' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn_edit_student"
                                            data-id="{{ $row->id }}" data-toggle="tooltip"
                                            style="margin-right: 5px" title="@lang('Edit')"
                                            data-original-title="@lang('Edit')">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete_student"
                                            data-id="{{ $row->id }}" data-toggle="tooltip"
                                            title="@lang('Delete')" data-original-title="@lang('Delete')">
                                            <i class="fa fa-trash"></i>
                                        </button>
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
    <div id="add_hv" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Thêm học viên vào KTX')</h4>
                    <p class="text-red text-left">Vui lòng kiểm tra lại thông tin trước khi tạo !</p>

                </div>
                <form role="form" action="{{ route('dormitory.addstudent') }}" method="POST" id="form_add_student">
                    @csrf
                    <div class="modal-body row">
                        <div class="box_alert_modal">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mã học viên') <small class="text-red">*</small></label>
                                <input type="text" class="form-control admin_code" name="admin_code" id= "admin_code"
                                    required value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Giới tính') <small class="text-red">*</small></label>
                                <select class="form-control select2" id="gender" name="gender" style="width: 100%">
                                    <option value="" selected disabled>@lang('Vui lòng chọn')</option>
                                    @foreach ($gender as $val)
                                        <option value="{{ $val }}"> @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Phòng') <small class="text-red">*</small></label>
                                <select name="id_dormitory" required class=" form-control select2" style="width: 100%">
                                    <option value="" selected disabled>@lang('Please select')</option>
                                    @foreach ($dormitory as $items)
                                        <option value="{{ $items->id }}">
                                            {{ __($items->name) }}
                                            - {{ $items->area->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày vào KTX') <small class="text-red">*</small></label>
                                <input type="date" name="time_in" class="form-control" required value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày hết hạn')</label>
                                <input type="date" name="time_expires" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Đơn vào KTX')</label>
                                <input type="text" name="json_params[don_vao]" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Ghi chú')</label>
                                <input type="text" name="json_params[ghi chú]" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary btn_submit_add"><i class="fa fa-floppy-o"
                                aria-hidden="true"></i>
                            @lang('Add')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div id="edit_hv" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Sửa thông tin học viên')</h4>
                </div>
                <form role="form" action="{{ route('dormitory.editstudent') }}" method="POST"
                    id="form_edit_student">
                    @csrf
                    <div class="modal-body row">
                        <input type="hidden" name="id" value="">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Học viên') <small class="text-red">*</small></label>
                                <input type="text" class="form-control user_name" readonly value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Giới tính') <small class="text-red">*</small></label>
                                <select class="form-control select2" required id="gender_edit" name="gender"
                                    style="width: 100%">
                                    <option value="" selected disabled>@lang('Vui lòng chọn')</option>
                                    <option value="male"> @lang('male')</option>
                                    <option value="female"> @lang('female')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày vào KTX') <small class="text-red">*</small></label>
                                <input type="date" name="time_in" max="{{ date('Y-m-d') }}" class="form-control"
                                    required value="">
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Ngày ra KTX')</label>
                                <input type="date" name="time_out" class="form-control" value="">
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày hết hạn')</label>
                                <input type="date" name="time_expires" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-12"></div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Đơn vào KTX')</label>
                                <input type="text" name="json_params[don_vao]" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ghi chú')</label>
                                <input type="text" name="json_params[ghi_chu]" class="form-control" value="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i>
                            @lang('Save')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // if ($('#area_id').val() != '') {
            //     var areaId = $('#area_id').val();
            //     loadDormitory(areaId);
            // }
            $('#area_id').on('change', function() {
                var areaId = $(this).val();
                loadDormitory(areaId);
            })

            $('.btn_edit_student').click(function() {
                var form = $('#form_edit_student');
                var url = "{{ route('dormitory.getstudent') }}/";
                var id = $(this).data('id');
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        "id": id,
                    },
                    success: function(response) {
                        let list = response.data || null;
                        if (list == 'error') {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert').remove();
                            }, 3000);
                        } else {
                            form.find('.user_name').val(list.user_name);
                            form.find('input[name="id"]').val(id);
                            form.find('input[name="time_in"]').val(list.time_in);
                            form.find('input[name="time_out"]').val(list.time_out);
                            form.find('input[name="time_expires"]').val(list.time_expires);
                            form.find('#gender_edit').val(list.gender).trigger('change');
                            form.find('input[name="json_params[don_vao]"]').val(list.don_vao);
                            form.find('input[name="json_params[ghi_chu]"]').val(list.ghi_chu);
                            $('#edit_hv').modal('show');
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            })
            $('.check_all').click(function() {
                const isChecked = $(this).prop('checked');
                $('.ckeck_delete').prop('checked', isChecked);
            });
            $('.ckeck_delete').change(function() {
                if ($('.ckeck_delete:checked').length == $('.ckeck_delete').length) {
                    $('.check_all').prop('checked', true);
                } else {
                    $('.check_all').prop('checked', false);
                }
            });

            $('.delete_student_all').click(function() {
                var id = [];
                $('.ckeck_delete:checked').each(function() {
                    id.push($(this).val());
                });
                if (id.length <= 0) {
                    alert('Vui lòng chọn học viên cần xóa!')
                } else {
                    var result = confirm("Bạn có chắc chắn muốn tiếp tục?");
                    if (result) {
                        deleteStudentDormitory(id)

                    }
                }
            });
            $('.delete_student').click(function() {
                var result = confirm("Bạn có chắc chắn muốn tiếp tục?");
                var id = [];
                if (result) {
                    id.push($(this).data('id'));
                    deleteStudentDormitory(id)
                }
            })

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
                            a.download = 'Danh_sach_hoc_vien_ktx.xlsx';
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
                        let errors = response.responseJSON.message;
                        alert(errors);
                        eventInProgress = false;
                    }
                });
            })
            $("#admin_code").on('change', function() {
                var admin_code = $(this).val();
                var _html = '';
                $.ajax({
                    type: "GET",
                    url: '{{ route('dormitory.gender.student') }}',
                    data: {
                        "admin_code": admin_code
                    },
                    success: function(response) {
                        if (response.data != null) {
                            var student = response.data;
                            $('#gender').val(student.gender).trigger('change');
                            _html += `<div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Học viên: ` + student.name + ` - ` + student.admin_code + `
                            </div>`;
                            $('.box_alert_modal').html(_html)
                        } else {
                            $('.box_alert_modal').html('')
                        }
                    },
                    error: function(response) {
                        // Get errors
                        var errors = response.responseJSON.message;
                        alert(errors);
                    }
                });

            })
            $('.btn_submit_add').on('click', function(event) {
                event.preventDefault();
                if ($("#form_add_student")[0].checkValidity()) {
                    var admin_code = $('.admin_code').val();
                    if (admin_code != '') {
                        $.ajax({
                            type: "GET",
                            url: '{{ route('dormitory.getstudent') }}',
                            data: {
                                "admin_code": admin_code,
                                "type": 'admin_code',
                            },
                            success: function(response) {
                                if (response.data == 'success') {
                                    $('#form_add_student').submit();
                                } else {
                                    var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + response.message + `
                            </div>`;
                                    $('.box_alert_modal').prepend(_html);
                                    setTimeout(function() {
                                        $('.alert').remove();
                                    }, 5000);
                                }
                            },
                            error: function(response) {
                                var errors = response.responseJSON.message;
                                var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ` + errors + `
                            </div>`;
                                $('.box_alert_modal').prepend(_html);
                                setTimeout(function() {
                                    $('.alert').remove();
                                }, 5000);
                            }
                        });


                    }
                } else {
                    $("#form_add_student")[0].reportValidity();
                }


            });
        });

        function loadDormitory(areaId) {
            var dormitory = @json($dormitory ?? []);
            var _html = `<option value="">@lang('Please select')</option>`;
            dormitory.forEach(function(val) {
                if (val.area_id == areaId) {
                    _html += `<option value="` + val.id + `">` + val.name + `</option>`;
                }
            })
            $('#dormitory').html(_html).select2({
                width: '100%'
            });
        }

        function deleteStudentDormitory(id) {
            $.ajax({
                type: "POST",
                url: '{{ route('dormitory.deletestudent') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
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
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.message;
                    alert(errors);
                    // location.reload();
                }
            });
        }

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
                url: '{{ route('dormitory.import_student') }}',
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
