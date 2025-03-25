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
            <form action="{{ route('dormitory.liststudentpaid') }}" method="GET" id="form_filter">
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
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('dormitory.liststudentpaid') }}">
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
                <h3 class="box-title">@lang('Danh sách học viên')</h3>
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
                                <th>@lang('Khu vực')</th>
                                <th>@lang('Ngày vào KTX')</th>
                                <th>@lang('Ngày hết hạn KTX')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Phòng') *</th>
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
                                        {{ $row->user_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $staff->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->user_gender)
                                    </td>

                                    <td>
                                        {{ $row->student->area->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->time_in != '' ? date('d/m/Y', strtotime($row->time_in)) : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $row->time_expires != '' ? date('d/m/Y', strtotime($row->time_expires)) : '--/--/----' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->ghi_chu ?? '' }}
                                    </td>
                                    <td>
                                        <form role="form" action="{{ route('dormitory.updatestudentpaid') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$row->id??''}}" >
                                            <select name="id_dormitory" class="select2" required style="width: 150px">
                                                <option value="">@lang('Chọn phòng')</option>
                                                @foreach ($dormitory as $items)
                                                    <option value="{{ $items->id }}">{{ $items->name }} - {{$items->area->name}}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-success">Lưu</button>
                                        </form>
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

@endsection
