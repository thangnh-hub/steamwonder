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
    <style>
        .nav-tabs {
            padding: 0px;
        }

        .nav-tabs li {
            width: 100%;
            background-color: #ECF0F5;
        }

        .nav-tabs li a {
            border: solid 1px #ECF0F5;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .tab-content {
            border: solid 1px #ECF0F5;
        }

        .nav-tabs-custom>.nav-tabs>li:first-of-type.active>a,
        .nav-tabs-custom>.nav-tabs>li.active>a {
            border-left-color: #ECF0F5;
            border-bottom-color: #ECF0F5;
        }

        .nav-tabs li a i {
            width: 20px;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Default Website settings')</h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default)
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right" href="{{ route(Request::segment(2) . '.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route(Request::segment(2) . '.index') }}?lang={{ $item->lang_locale }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @endif
                    @endforeach
                @endisset

            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                <div class="box-body">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs col-md-3">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    <h5>
                                        <i class="fa fa-home"></i>
                                        @lang('General information')
                                    </h5>
                                </a>
                            </li>
                            <li>
                                <a href="#tab_2" data-toggle="tab">
                                    <h5>
                                        <i class="fa fa-image"></i>
                                        @lang('System image')
                                    </h5>
                                </a>
                            </li>
                            <li>
                                <a href="#tab_3" data-toggle="tab">
                                    <h5>
                                        <i class="fa fa-link"></i>
                                        @lang('Social links')
                                    </h5>
                                </a>
                            </li>

                        </ul>
                        <div class="tab-content col-md-9">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Site title')
                                            </label>
                                            <input type="text" class="form-control" name="site_title"
                                                placeholder="@lang('Site title')"
                                                value="{{ old('site_title') ?? ($setting->{$lang . '-site_title'} ?? ($setting->site_title ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('SEO title')
                                            </label>
                                            <input type="text" class="form-control" name="seo_title"
                                                placeholder="@lang('SEO title')"
                                                value="{{ old('seo_title') ?? ($setting->{$lang . '-seo_title'} ?? ($setting->seo_title ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('SEO keyword	')
                                            </label>
                                            <input type="text" class="form-control" name="seo_keyword"
                                                placeholder="@lang('SEO keyword	')"
                                                value="{{ old('seo_keyword') ?? ($setting->{$lang . '-seo_keyword'} ?? ($setting->seo_keyword ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('SEO description')
                                            </label>
                                            <textarea name="seo_description" id="seo_description" class="form-control" rows="5" placeholder="SEO description">{{ old('seo_description') ?? ($setting->{$lang . '-seo_description'} ?? ($setting->seo_description ?? '')) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Hotline')
                                            </label>
                                            <input type="text" class="form-control" name="hotline"
                                                placeholder="@lang('Hotline')"
                                                value="{{ old('hotline') ?? ($setting->{$lang . '-hotline'} ?? ($setting->hotline ?? '')) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Phone')
                                            </label>
                                            <input type="text" class="form-control" name="phone"
                                                placeholder="@lang('Phone')"
                                                value="{{ old('phone') ?? ($setting->{$lang . '-phone'} ?? ($setting->phone ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Fax')
                                            </label>
                                            <input type="text" class="form-control" name="fax"
                                                placeholder="@lang('Fax')"
                                                value="{{ old('fax') ?? ($setting->{$lang . '-fax'} ?? ($setting->fax ?? '')) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Email')
                                            </label>
                                            <input type="text" class="form-control" name="email"
                                                placeholder="@lang('Email')"
                                                value="{{ old('email') ?? ($setting->{$lang . '-email'} ?? ($setting->email ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Address')
                                            </label>
                                            <input type="text" class="form-control" name="address"
                                                placeholder="@lang('Address')"
                                                value="{{ old('address') ?? ($setting->{$lang . '-address'} ?? ($setting->address ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Send Email')
                                            </label>
                                            <input type="text" class="form-control" name="send_email"
                                                placeholder="@lang('Send Email')"
                                                value="{{ old('send_email') ?? ($setting->{$lang . '-send_email'} ?? ($setting->send_email ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Copyright')
                                            </label>
                                            <input type="text" class="form-control" name="copyright"
                                                placeholder="@lang('Copyright')"
                                                value="{{ old('copyright') ?? ($setting->{$lang . '-copyright'} ?? ($setting->copyright ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Slogan')
                                            </label>
                                            <input type="text" class="form-control" name="slogan"
                                                placeholder="@lang('Slogan')"
                                                value="{{ old('slogan') ?? ($setting->{$lang . '-slogan'} ?? ($setting->slogan ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Text Footer')
                                            </label>
                                            <input type="text" class="form-control" name="footer_text"
                                                placeholder="@lang('Text Footer')"
                                                value="{{ old('footer_text') ?? ($setting->{$lang . '-footer_text'} ?? ($setting->footer_text ?? '')) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="row">
                                    @foreach ($all_setting as $value)
                                        @if ($value->description == 'image')
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ $value->option_name }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="{{ $value->option_name }}"
                                                                data-preview="{{ $value->option_name }}-holder"
                                                                data-type="cms-image" class="btn btn-primary lfm">
                                                                <i class="fa fa-picture-o"></i> @lang('choose')
                                                            </a>
                                                        </span>
                                                        <input id="{{ $value->option_name }}" class="form-control"
                                                            type="text" name="{{ $value->option_name }}"
                                                            placeholder="@lang('image_link')..."
                                                            value="{{ old($value->option_name) ?? $value->option_value }}">
                                                    </div>
                                                    <div id="{{ $value->option_name }}-holder"
                                                        style="margin-top:15px;max-height:100px;">
                                                        @if (isset($value) && $value->option_value != '')
                                                            <img style="height: 5rem;"
                                                                src="{{ old($value->option_name) ?? $value->option_value }}">
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_3">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Facebook url')
                                            </label>
                                            <input type="text" class="form-control" name="facebook_url"
                                                placeholder="@lang('Facebook url')"
                                                value="{{ old('facebook_url') ?? ($setting->{$lang . '-facebook_url'} ?? ($setting->facebook_url ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Youtube url')
                                            </label>
                                            <input type="text" class="form-control" name="youtube_url"
                                                placeholder="@lang('Twitter url')"
                                                value="{{ old('youtube_url') ?? ($setting->{$lang . '-youtube_url'} ?? ($setting->youtube_url ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Instagram url')
                                            </label>
                                            <input type="text" class="form-control" name="instagram_url"
                                                placeholder="@lang('Instagram url')"
                                                value="{{ old('instagram_url') ?? ($setting->{$lang . '-instagram_url'} ?? ($setting->instagram_url ?? '')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>
                                                @lang('Linkedin url')
                                            </label>
                                            <input type="text" class="form-control" name="linkedin_url"
                                                placeholder="@lang('Linkedin url')"
                                                value="{{ old('linkedin_url') ?? ($setting->{$lang . '-linkedin_url'} ?? ($setting->linkedin_url ?? '')) }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right btn-sm">
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
        $(document).ready(function() {

        });
    </script>
@endsection
