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
            <form action="{{ route('dormitory.liststudentoverseer') }}" method="GET" id="form_filter">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Học viên')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
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

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('dormitory.liststudentoverseer') }}">
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
                                <th>@lang('Giới tính')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Phòng đang ở')</th>
                                {{-- <th>@lang('Phòng muốn dổi')</th> --}}
                                <th>@lang('Ngày vào KTX')</th>
                                {{-- <th>@lang('Ngày ra KTX')</th> --}}
                                <th>@lang('Ngày hết hạn KTX')</th>
                                <th>@lang('Đơn vào KTX')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td> <strong style="font-size: 14px;">{{ $row->admin_code }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->user_name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->user_gender)
                                    </td>

                                    <td>
                                        {{ $row->dormitory->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->dormitory->name ?? '' }}
                                    </td>
                                    {{-- <td>
                                        <select name="id_dormitory" class="select2" style="width: 250px">
                                            <option value="">@lang('Chọn phòng')</option>
                                            @foreach ($dormitory as $items)
                                                @if ($items->area_id == $row->dormitory->area->id && $items->id != $row->id_dormitory)
                                                    <option value="{{ $items->id }}">{{ $items->name }} -
                                                        {{ $items->area->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td> --}}
                                    <td>
                                        {{ $row->time_in != '' ? date('d/m/Y', strtotime($row->time_in)) : '--/--/----' }}
                                    </td>
                                    {{-- <td>
                                        <input type="date" class="form-control" name="time_out"
                                            value="{{ $row->time_out ?? '' }}">
                                    </td> --}}
                                    <td>
                                        {{ $row->time_expires != '' ? date('d/m/Y', strtotime($row->time_expires)) : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->don_vao ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->ghi_chu ?? '' }}
                                    </td>
                                    <td style="width: 280px">

                                        <div class="box-hide box-change-room " style="display: none">
                                            <form role="form" class="d-flex-wap" action="{{ route('dormitory.leave_or_change_room') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $row->id ?? '' }}">

                                                <select name="id_dormitory" required class="select2" style="width: 150px">
                                                    <option value="">@lang('Chọn phòng')</option>
                                                    @foreach ($dormitory as $items)
                                                        @if ($items->area_id == $row->dormitory->area->id && $items->id != $row->id_dormitory && $items->status)
                                                            <option value="{{ $items->id }}">{{ $items->name }} -
                                                                {{ $items->area->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-success"
                                                    data-id="{{ $row->id }}">
                                                    @lang('Lưu')
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn_exit">
                                                    @lang('Hủy')
                                                </button>
                                            </form>
                                        </div>
                                        <div class="box-hide box-leave-room " style="display: none">
                                            <form role="form" class="d-flex-wap" action="{{ route('dormitory.leave_or_change_room') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $row->id ?? '' }}">
                                                <input type="text" name="time_out" required style="width: 150px"
                                                    class="form-control input_time" data-toggle="tooltip"
                                                    style="margin-right: 3px"
                                                    data-original-title="{{ 'Chọn ngày trả phòng' }}"
                                                    onfocus="(this.type='date')" onblur="(this.type='text')"
                                                    placeholder="Chọn ngày trả phòng " />

                                                <button type="submit" onclick="return confirm('Xác nhận cho học viên trả phòng !')" class="btn btn-sm btn-success"
                                                    data-id="{{ $row->id }}">
                                                    @lang('Lưu')
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn_exit">
                                                    @lang('Hủy')
                                                </button>
                                            </form>
                                        </div>
                                        <div class="box-btn">
                                            <button type="button" class="btn btn-sm btn-warning btn_change_room"
                                                data-toggle="tooltip" title="@lang('Đổi phòng')"
                                                data-original-title="@lang('Đổi phòng')">
                                                @lang('Đổi phòng')
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger btn_leave_room"
                                                data-toggle="tooltip" title="@lang('Trả phòng')"
                                                data-original-title="@lang('Trả phòng')">
                                                @lang('Trả phòng')
                                            </button>
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
        $(document).ready(function() {
            $('#area_id').on('change', function() {
                var areaId = $(this).val();
                loadDormitory(areaId);
            })
            $('.btn_change_room').click(function() {
                $(this).parents('tr').find('.box-btn').hide();
                $(this).parents('tr').find('.box-hide').hide();
                $(this).parents('tr').find('.box-change-room').show();
            })
            $('.btn_leave_room').click(function() {
                $(this).parents('tr').find('.box-btn').hide();
                $(this).parents('tr').find('.box-hide').hide();
                $(this).parents('tr').find('.box-leave-room').show();
            })
            $('.btn_exit').click(function() {
                $(this).parents('tr').find('.box-hide').hide();
                $(this).parents('tr').find('.box-btn').show();
            })
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
    </script>
@endsection
