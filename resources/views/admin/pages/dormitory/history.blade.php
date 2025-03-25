@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>thead>tr>th {
            font: bold 14px/28px "Source Sans Pro";
            text-align: center;
            align-content: center;
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
            <form action="{{ route('dormitory.history') }}" method="GET">
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
                        {{-- <div class="col-md-2">
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
                        </div> --}}

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Từ tháng')</label>
                                <input type="month" class="form-control" name="from_month" placeholder="@lang('Chọn tháng')"
                                    value="{{ $params['from_month'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Đến tháng')</label>
                                <input type="month" class="form-control" name="to_month" placeholder="@lang('Chọn tháng')"
                                    value="{{ $params['to_month'] ?? '' }}">
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
                                    <a class="btn btn-default btn-sm" href="{{ route('dormitory.history') }}">
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
                                {{-- <th>@lang('Lượt vào')</th>
                                <th>@lang('Lượt ra')</th> --}}
                                <th>@lang('Gender')</th>
                                <th>@lang('Địa chỉ')</th>
                                <th>@lang('Ngày thuê')</th>
                                <th>@lang('Ngày hết hạn')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td> <strong style="font-size: 14px;">{{ $row->dormitory->name }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->dormitory->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->dormitory->don_nguyen ?? '' }}
                                    </td>
                                    {{-- <td>
                                        {{ $row->total_users_time_in }}
                                    </td>
                                    <td>
                                        {{ $row->total_users_time_out }}
                                    </td> --}}
                                    <td>
                                        @lang($row->dormitory->gender)
                                    </td>
                                    <td>
                                        {{ $row->dormitory->json_params->address ?? '' }}
                                    </td>

                                    <td>
                                        <input type="date" class="form-control time_in" readonly
                                            value="{{ $row->time_in ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="date" class="form-control time_out" readonly
                                            value="{{ $row->time_out ?? '' }}">
                                    </td>
                                    <td>
                                        <div class="box_btn_action">
                                            <button class="btn btn-sm btn-warning btn_edit" data-toggle="tooltip"
                                                style="margin-right: 5px" title="@lang('Edit')"
                                                data-original-title="@lang('Edit')">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </div>
                                        <div class="box_btn_edit" style="display: none">
                                            <div class="box_input_time">
                                                <button class="btn btn-sm btn-success btn_check" data-toggle="tooltip"
                                                    data-id="{{ $row->id }}"
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
        $('.btn_edit').click(function() {
            var h = $(this).parents('.box_btn_action').hide();
            var s = $(this).parents('tr').find('.box_btn_edit').show();
            $(this).parents('tr').find('.time_in').removeAttr('readonly');
            $(this).parents('tr').find('.time_out').removeAttr('readonly');
            show_hide(s, h);
        })
        $('.btn_exit').click(function() {
            var s = $(this).parents('tr').find('.box_btn_action');
            var h = $(this).parents('.box_btn_edit');
            $(this).parents('tr').find('.time_in').attr('readonly', 'true');
            $(this).parents('tr').find('.time_out').attr('readonly', 'true');
            show_hide(s, h);
        });
        $('.btn_check').on('click', function() {
            var id = $(this).data('id');
            var time_in = $(this).parents('tr').find('.time_in').val();
            var time_out = $(this).parents('tr').find('.time_out').val();
            if (time_in == '') {
                alert('Ngày thuê không được để trống!');
                return;
            }
            var url = "{{ route('dormitory.edithistory') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "time_in": time_in,
                    "time_out": time_out,
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
    </script>
@endsection
