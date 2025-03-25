@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .btn_status {
            background-color: #dd4b39;
            border-color: #d73925;
            color: #fff;
        }

        .btn_status.active {
            color: #fff;
            background-color: #00a65a;
            border-color: #008d4c;
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
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách điểm danh KTX ') - {{ $dormitory->name }} - khu vực: {{ $dormitory->area->name }} -
                    ngày
                    {{ isset($params['time']) ? date('d-m-Y', strtotime($params['time'])) : '' }}</h3>
            </div>
            <div class=" table-responsive box_alert">
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
                    <form action="{{ route('dormitory.updatemuster') }}" method="POST">
                        @csrf
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Mã HV')</th>
                                    <th>@lang('Họ tên')</th>
                                    <th>@lang('Giới tính')</th>
                                    <th>@lang('Trạng thái')</th>
                                    <th>@lang('Lý do')</th>
                                    <th>@lang('Ghi chú')</th>
                                    {{-- <th>@lang('Action')</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($rows as $row)
                                    <tr class="valign-middle">
                                        <td> <strong style="font-size: 14px;">{{ $row->student->admin_code }}</strong>
                                        </td>
                                        <td> <strong style="font-size: 14px;">{{ $row->student->name }}</strong>
                                        </td>
                                        <td>
                                            @lang($row->student->gender)
                                        </td>
                                        <td>
                                            <select class="form-control select2 status_muster" style="width: 100%"
                                                name="data[{{ $row->id }}][status]">
                                                @foreach ($status as $val)
                                                    <option value="{{ $val }}"
                                                        {{ $row->status == $val ? 'selected' : '' }}>@lang($val)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control select2" style="width: 100%"
                                                name="data[{{ $row->id }}][json_params][reason]">
                                                <option value="" selected>@lang('Please select')</option>
                                                @foreach ($reason as $val)
                                                    <option value="{{ $val }}"
                                                        {{ isset($row->json_params->reason) && $row->json_params->reason == $val ? 'selected' : '' }}>
                                                        @lang($val)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <textarea rows="4" placeholder="Nhập ghi chú" name="data[{{ $row->id }}][json_params][note]"
                                                class="form-control">{{ isset($row->json_params->note) && $row->json_params->note !='' ? $row->json_params->note : '' }}</textarea>
                                        </td>
                                        {{-- <td>
                                        <button class="btn btn-warning btn_reason"
                                            data-id ="{{ $row->id }}"><i class="fa fa-save"></i> @lang('Lưu lại')</button>
                                    </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-info mt-5">
                            <i class="fa fa-save"></i> Lưu lại </button>
                    </form>
                @endif
            </div>
            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->count() }} kết quả
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        $('.btn_status').click(function() {
            if ($(this).hasClass('active') == true) {
                $(this).removeClass('active').html('Vắng mặt');
            } else {
                $(this).addClass('active').html('Có mặt');
            }
        })
    </script>
@endsection
