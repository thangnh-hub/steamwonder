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
            <form action="{{ route('dormitory.liststudentregister') }}" method="GET" id="form_filter">
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
                                <label>@lang('Chỗ ở')</label>
                                <select name="dormitory" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($dormitory as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['dormitory']) && $key == $params['dormitory'] ? 'selected' : '' }}>
                                            {{ __($value) }}
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
                                    <a class="btn btn-default btn-sm" href="{{ route('dormitory.liststudentregister') }}">
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
                {{-- <h3 class="box-title">@lang('Danh sách học viên')</h3> --}}
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
                                <th>@lang('CCCD')</th>
                                <th>@lang('CBTS')</th>
                                <th>@lang('Giới tính')</th>
                                <th>@lang('Chỗ ở')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                @endphp
                                <tr class="valign-middle">
                                    <td> <strong style="font-size: 14px;">{{ $row->admin_code }}</strong>
                                    </td>
                                    <td>
                                        {{ $row->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->cccd ?? '' }}
                                    </td>
                                    <td>
                                        {{ $staff->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->gender)
                                    </td>
                                    <td>
                                        {{ isset($row->json_params->dormitory) && $row->json_params->dormitory != '' ? $dormitory[$row->json_params->dormitory] : '' }}
                                    </td>

                                    <td>
                                        {{ $row->area->name ?? '' }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning btn_payment_dormitory"
                                            data-code="{{ $row->admin_code }}" data-toggle="tooltip"
                                            style="margin-right: 5px" title="@lang('Xác nhận vào KTX')"
                                            data-original-title="@lang('Xác nhận vào KTX')">
                                            <i class="fa fa-money"></i> @lang('Xác nhận vào KTX')
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


    <div id="show_hv" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog  modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Thông tin học viên')</h4>
                </div>
                <form role="form" action="{{ route('dormitory.addstudent') }}" method="POST"
                    id="form_payment_student">
                    @csrf
                    <div class="modal-body row">
                        <input type="hidden" name="admin_code" value="">
                        <input type="hidden" name="status_dormitory" value="{{ $status_payment_dormitory['paid'] }}">
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
                                <input type="date" name="time_in" min="{{ date('Y-m-d') }}" class="form-control"
                                    required value="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày hết hạn')</label>
                                <input type="date" name="time_expires" min="{{ date('Y-m-d') }}"
                                    class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-12"></div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Ghi chú')</label>
                                <textarea name="json_params[ghi_chu]" rows="4"class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-warning"><i class="fa fa-money"></i>
                            @lang('Xác nhận vào KTX')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.btn_payment_dormitory').click(function() {
                var form = $('#form_payment_student');
                var url = "{{ route('dormitory.gender.student') }}/";
                var admin_code = $(this).data('code');
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        "admin_code": admin_code,
                    },
                    success: function(response) {
                        let user = response.data;
                        form.find('.user_name').val(user.name);
                        form.find('input[name="admin_code"]').val(admin_code);
                        form.find('#gender_edit').val(user.gender).trigger('change');
                        $('#show_hv').modal('show');
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            })
        });
    </script>
@endsection
