@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        ul {
            padding-inline-start: 16px;
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
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['dormitory']) && $value->id == $params['dormitory'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ 'dormitory-list-student' }}">
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
                <h3 class="box-title">@lang('Danh sách học viên sắp đến hạn trong 15 ngày')</h3>
                <div class="pull-right">

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
                                <th>@lang('Mã HV')</th>
                                <th>@lang('Họ tên')</th>
                                <th>@lang('CBTS')</th>
                                <th>@lang('Giới tính')</th>
                                <th>@lang('Khóa')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Phòng')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Ngày vào KTX')</th>
                                <th>@lang('Ngày ra KTX')</th>
                                <th>@lang('Ngày hết hạn KTX')</th>
                                <th>@lang('Thời gian còn lại')</th>
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
                                        {{ $row->dormitory->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->dormitory->area->name ?? '' }}
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
                                    <td class="text-center">
                                        <strong class="text-red font-weight-bold">{{ floor((strtotime($row->time_expires) - time()) /86400) }} ngày</strong>
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Học viên') <small class="text-red">*</small></label>
                                <input type="text" class="form-control user_name" readonly value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Ngày vào KTX') <small class="text-red">*</small></label>
                                <input type="date" name="time_in" max="{{ date('Y-m-d') }}" class="form-control"
                                    required value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Ngày ra KTX')</label>
                                <input type="date" name="time_out" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
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

            $('.delete_student').click(function() {
                var result = confirm("Bạn có chắc chắn muốn tiếp tục?");
                var id = [];
                if (result) {
                    id.push($(this).data('id'));
                    deleteStudentDormitory(id)
                }
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
                        }else{
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


        });

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
    </script>
@endsection
