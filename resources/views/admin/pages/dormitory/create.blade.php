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
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>

                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>

                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row">
                                            @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                                                <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                                            @endif

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Tên phòng') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Đơn nguyên')</label>
                                                    <input type="text" class="form-control" name="don_nguyen"
                                                        placeholder="@lang('Đơn nguyên')" value="{{ old('don_nguyen') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Số chỗ') <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="slot" required
                                                        placeholder="@lang('Số chỗ')" value="{{ old('slot') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Sắp xếp')</label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="@lang('Sắp xếp')"
                                                        value="{{old('iorder') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <input type="test" class="form-control" name="json_params[address]"
                                                        placeholder="@lang('Address')"
                                                        value="{{old('json_paramsp[address]') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Khu vực') <span class="text-danger">*</span></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="area_id" required class=" form-control select2">
                                    <option value="" selected disabled>@lang('Please select')</option>
                                    @foreach ($area as $items)
                                        <option value="{{ $items->id }}"
                                            {{ isset($detail->area_id) && $detail->area_id == $items->id ? 'selected' : '' }}>
                                            {{ __($items->code) }}
                                            - {{ __($items->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->status) && $detail->status == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Ngày bắt đầu thuê phòng') <span class="text-danger">*</span></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="date" required class="form-control" name="time_start" value="">
                            </div>
                        </div>
                    </div>
                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Ngày hết hạn phòng')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="date" class="form-control" name="time_expires" value="">
                            </div>
                        </div>
                    </div> --}}

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Gender')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="gender" class=" form-control select2">
                                    <option value="" selected disabled>@lang('Please select')</option>
                                    @foreach ($gender as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->gender) && $detail->gender == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Publish')</h3>
                        </div>
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

        </form>
    </section>

@endsection

@section('script')
@endsection
