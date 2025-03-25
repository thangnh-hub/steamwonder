@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .box_input_time {
            display: flex
        }

        .input_time {
            width: 100px;
            font-size: 12px;
            padding: 5px;
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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên phòng')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Gender')</label>
                                <select name="gender" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($gender as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['gender']) && $key == $params['gender'] ? 'selected' : '' }}>
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
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
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
                <h3 class="box-title">@lang('List')</h3>
                <div class="pull-right">
                    <div class="pull-right" style="display: flex; margin-left:15px ">
                        <button type="button" class="btn btn-sm btn-success" onclick="importFile()">
                            <i class="fa fa-file-excel-o"></i>
                            @lang('Import dữ liệu phòng')</button>
                        <input class="form-control" type="file" name="files" id="fileImport"
                            placeholder="@lang('Select File')">
                    </div>
                    <a class="btn  btn-warning  pull-right" target="_blank"
                        href="{{ route('dormitory.history') }}">@lang('Lịch sử DWN thuê phòng')</a>
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

                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Phòng')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Đơn nguyên')</th>
                                <th>@lang('Học viên / Sức chứa')</th>
                                <th>@lang('Gender')</th>
                                <th>@lang('Địa chỉ')</th>
                                <th>@lang('Status')</th>
                                {{-- <th>@lang('Ngày thuê')</th> --}}
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td> <strong
                                            style="font-size: 14px;">{{ $row->json_params->name->{$lang} ?? $row->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->don_nguyen ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->dormitoryUsers()->where('status', 'already')->count() }}/{{ $row->slot ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->gender ?? '')
                                    </td>
                                    <td>
                                        {{ $row->json_params->address ?? '' }}
                                    </td>
                                    <td>
                                        {{$row->slot <= $row->quantity ?__('full'):__($row->status)}}
                                        {{-- @lang($row->status) --}}
                                    </td>

                                    {{-- <td>
                                        {{ $row->time_start != '' ? date('d/m/Y', strtotime($row->time_start)) : '--/--/----' }}
                                    </td> --}}
                                    <td style="width: 180px">
                                        <div class="box_btn_action">
                                            <a class="btn btn-sm btn-success" data-toggle="tooltip"
                                                style="margin-right: 5px" data-original-title="@lang('Danh sách sinh viên')"
                                                href="{{ route('dormitory.liststudent', ['area_id' => $row->area->id, 'dormitory' => $row->id]) }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i></a>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                style="margin-right: 5px" title="@lang('Edit')"
                                                data-original-title="@lang('Edit')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @if ($row->status != 'deactive')
                                                <button class="btn btn-sm btn-danger btn_deactive" data-toggle="tooltip"
                                                    style="margin-right: 5px" title="@lang('Trả phòng')"
                                                    data-original-title="@lang('Trả phòng')">
                                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-primary btn_deactive" data-toggle="tooltip"
                                                    style="margin-right: 5px" title="@lang('Thuê lại')"
                                                    data-original-title="@lang('Thuê lại')">
                                                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <div class="box_btn_edit" style="display: none">
                                            <div class="box_input_time">
                                                <input type="text" class="form-control input_time"
                                                    data-toggle="tooltip" style="margin-right: 3px"
                                                    data-original-title="{{$row->status == 'deactive'?'Chọn ngày thuê phòng':'Chọn ngày trả phòng'}}" onfocus="(this.type='date')"
                                                    onblur="(this.type='text')" placeholder="Chọn ngày" />
                                                <button class="btn btn-sm btn-success btn_check"
                                                    data-type="{{ $row->status == 'deactive' ? 'checkin' : 'checkout' }}"
                                                    data-toggle="tooltip" data-id="{{ $row->id }}"
                                                    data-original-title="@lang('Lưu')"><i class="fa fa-check"
                                                        aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-danger btn_exit" data-toggle="tooltip"
                                                    data-original-title="@lang('Hủy')"><i class="fa fa-times"
                                                        aria-hidden="true"></i></button>
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
        $('.btn_deactive').click(function() {
            var h = $(this).parents('.box_btn_action').hide();
            var s = $(this).parents('tr').find('.box_btn_edit').show();
            show_hide(s, h);
        })
        $('.btn_exit').click(function() {
            var s = $(this).parents('tr').find('.box_btn_action');
            var h = $(this).parents('.box_btn_edit');
            show_hide(s, h);
        });
        $('.btn_check').click(function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var time = $(this).parents('.box_input_time').find('.input_time').val();
            if (time == '') {
                alert('Vui lòng chọn ngày!');
                return;
            }
            if (type == 'checkout') {
                var url = "{{ route('dormitory.setcheckout') }}";
            } else {
                var url = "{{ route('dormitory.setcheckin') }}";
            }

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    "id": id,
                    "time": time,
                },
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
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });

        })

        function show_hide(show, hide) {
            show.show();
            hide.hide();
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
            $.ajax({
                url: '{{ route('dormitory.import_dormitory') }}',
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
