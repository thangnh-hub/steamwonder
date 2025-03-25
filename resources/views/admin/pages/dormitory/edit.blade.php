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
            {{-- <button class="btn btn-sm btn-warning pull-right"><i
                    class="fa fa-plus"></i> @lang('Add học viên')</button> --}}
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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                            @isset($languages)
                                <div class="collapse navbar-collapse pull-right">
                                    <ul class="nav navbar-nav">
                                        <li class="dropdown">
                                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-language"></i>
                                                {{ Request::get('lang') && Request::get('lang') != $languageDefault->lang_code
                                                    ? $languages->first(function ($item, $key) use ($lang) {
                                                        return $item->lang_code == $lang;
                                                    })['lang_name']
                                                    : $languageDefault->lang_name }}
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                @foreach ($languages as $item)
                                                    @if ($item->lang_code != $languageDefault->lang_code)
                                                        <li>
                                                            <a href="{{ route(Request::segment(2) . '.edit', $detail->id) }}?lang={{ $item->lang_locale }}"
                                                                style="padding-top:10px; padding-bottom:10px;">
                                                                <i class="fa fa-language"></i>
                                                                {{ $item->lang_name }}
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ route(Request::segment(2) . '.edit', $detail->id) }}"
                                                                style="padding-top:10px; padding-bottom:10px;">
                                                                <i class="fa fa-language"></i>
                                                                {{ $item->lang_name }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <span class="pull-right" style="padding: 15px">@lang('Language'): </span>
                            @endisset

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
                                                        placeholder="@lang('Title')"
                                                        value="{{ $detail->json_params->name->$lang ?? $detail->name }}"
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
                                                        placeholder="@lang('Đơn nguyên')"
                                                        value="{{ $detail->don_nguyen ?? old('don_nguyen') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Số chỗ') <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="slot"
                                                        placeholder="@lang('Số chỗ')" required
                                                        value="{{ $detail->slot ?? old('slot') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Sắp xếp')</label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="@lang('Sắp xếp')"
                                                        value="{{ $detail->iorder ?? old('iorder') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <input type="test" class="form-control" name="json_params[address]"
                                                        placeholder="@lang('Address')"
                                                        value="{{ $detail->json_params->address ?? old('json_paramsp[address]') }}">
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
                                            {{ isset($detail->status) && $detail->status == $val ? 'selected' : '' }}
                                            {{$val!='deactive'?'disabled':''}}
                                            >
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Ngày bắt đầu thuê phòng') <span class="text-danger">*</span></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="date" class="form-control" name="time_start"
                                    value="{{ $detail->time_start != '' ? date('Y-m-d', strtotime($detail->time_start)) : '' }}">
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Ngày hết hạn phòng')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="date" class="form-control" name="time_expires"
                                    value="{{ $detail->time_expires != '' ? date('Y-m-d', strtotime($detail->time_expires)) : '' }}">
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
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
@endsection
