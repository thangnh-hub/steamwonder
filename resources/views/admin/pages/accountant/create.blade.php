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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
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
                                                            <a href="{{ route(Request::segment(2) . '.create') }}?lang={{ $item->lang_locale }}"
                                                                style="padding-top:10px; padding-bottom:10px;">
                                                                <i class="fa fa-language"></i>
                                                                {{ $item->lang_name }}
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ route(Request::segment(2) . '.create') }}"
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
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row">
                                            @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                                                <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                                            @endif
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Kiểu') <small class="text-red">*</small></label>
                                                    <select name="is_type" class="form-control" required>
                                                        @foreach ($type as $key => $item)
                                                            <option value="{{$key}}">@lang($item)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày thực hiện') <small class="text-red">*</small></label>
                                                    <input type="datetime-local" required
                                                        min="{{ date('Y-m-d', time()) }}T00:00" class="form-control"
                                                        name="time" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Sức chứa') <small class="text-red">*</small></label>
                                                    <input type="number" required min="1" class="form-control"
                                                        name="slot" value="1">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
                <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                    @lang('Save')</button>
            </div>
        </form>
    </section>

@endsection
