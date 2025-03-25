@extends('admin.layouts.app')
@section('title')
    {{ $module_name }}
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }

@endphp
@section('style')
    <style>
        .checkbox_list {
            min-height: 300px;
        }
    </style>
@endsection
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
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

            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.store') }}"
                method="POST">
                @csrf
                @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                    <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                @endif
                <div class="box-body">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    <h5>
                                        @lang('General information')
                                        <span class="text-danger">*</span>
                                    </h5>
                                </a>
                            </li>
                            <button type="submit" class="btn btn-primary btn-sm pull-right">
                                <i class="fa fa-floppy-o"></i>
                                @lang('Save')
                            </button>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Title')</label>
                                            <small class="text-red">*</small>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="@lang('Title')" value="{{ old('title') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Url customize')</label>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <small class="form-text">
                                                (
                                                <i class="fa fa-info-circle"></i>
                                                @lang('Maximum 100 characters in the group: "A-Z", "a-z", "0-9" and "-_"')
                                                )
                                            </small>
                                            <input type="text" class="form-control" name="alias"
                                                placeholder="@lang('Url customize')" value="{{ old('alias') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Keyword')</label>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <input type="text" class="form-control" name="keyword"
                                                placeholder="@lang('Keyword')" value="{{ old('keyword') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Description')</label>
                                            <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                            <textarea type="text" class="form-control" name="description" placeholder="@lang('Description')">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Content Page')</label>
                                            <textarea type="text" class="form-control" name="content" id="content">{{ old('content') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Status')</label>
                                            <div class="form-control">
                                                @foreach (App\Consts::STATUS as $key => $value)
                                                    <label>
                                                        <input type="radio" name="status" value="{{ $value }}"
                                                            {{ $loop->index == 0 ? 'checked' : '' }}>
                                                        <small class="mr-15">{{ __($value) }}</small>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Order')</label>
                                            <input type="number" class="form-control" name="iorder"
                                                placeholder="@lang('Order')" value="{{ old('iorder') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Route Name')</label>
                                            <small class="text-red">*</small>
                                            <select name="route_name" id="route_name" class="form-control select2"
                                                style="width:100%" required autocomplete="off">
                                                <option value="">@lang('Please select')</option>
                                                @foreach (App\Consts::ROUTE_NAME as $key => $item)
                                                    @if (isset($item['is_config']) && $item['is_config'])
                                                        <option value="{{ $item['name'] }}">
                                                            {{ __($item['title']) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Template')</label>
                                            <small class="text-red">*</small>
                                            <select name="json_params[template]" id="template"
                                                class="form-control select2" style="width:100%" required>
                                                <option value="">@lang('Please select')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i>
                        @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm pull-right">
                        <i class="fa fa-floppy-o"></i>
                        @lang('Save')
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script>
        CKEDITOR.replace('content', ck_options);
        $(document).ready(function() {

            var routes = @json(App\Consts::ROUTE_NAME ?? []);
            $(document).on('change', '#route_name', function() {
                let _value = $(this).val();
                let _targetHTML = $('#template');
                let _list = filterArray(routes, 'name', _value);
                let _optionList = '<option value="">@lang('Please select')</option>';
                if (_list) {
                    _list.forEach(element => {
                        element.template.forEach(item => {
                            _optionList += '<option value="' + item.name + '"> ' + item
                                .title + ' </option>';
                        });
                    });
                    _targetHTML.html(_optionList);
                }
                $(".select2").select2();
            });

        });
    </script>
@endsection
