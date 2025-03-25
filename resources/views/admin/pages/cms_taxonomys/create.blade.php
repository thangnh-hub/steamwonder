@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection
@section('style')
    <style>
        .checkbox_list {
            min-height: 300px;
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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-8">
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
                                @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                                    <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                                @endif
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')" value="{{ old('title') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>URL tùy chọn</label>
                                                    <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                                    <small class="form-text">
                                                        (
                                                        <i class="fa fa-info-circle"></i>
                                                        Maximum 100 characters in the group: "A-Z", "a-z", "0-9" and "-_" )
                                                    </small>
                                                    <input name="alias" class="form-control"
                                                        value="{{ old('alias') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Brief')</label>
                                                    <textarea name="json_params[brief][{{ $lang }}]" class="form-control" rows="5">{{ old('json_params[brief][' . $lang . ']') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Description')</label>
                                                    <textarea name="json_params[description][{{ $lang }}]" class="form-control" rows="5">{{ old('json_params[description][' . $lang . ']') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Content')</label>
                                                        <textarea name="json_params[content][{{ $lang }}]" class="form-control" id="content_vi">{{ old('json_params[content][' . $lang . ']') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('seo_title')</label>
                                                    <input name="json_params[seo_title][{{ $lang }}]"
                                                        class="form-control"
                                                        value="{{ $detail->json_params->seo_title->$lang ?? old('json_params[seo_title][' . $lang . ']') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('seo_keyword')</label>
                                                    <input name="json_params[seo_keyword][{{ $lang }}]"
                                                        class="form-control"
                                                        value="{{ $detail->json_params->seo_keyword->$lang ?? old('json_params[seo_keyword][' . $lang . ']') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('seo_description')</label>
                                                    <input name="json_params[seo_description][{{ $lang }}]"
                                                        class="form-control"
                                                        value="{{ $detail->json_params->seo_description->$lang ?? old('json_params[seo_description][' . $lang . ']') }}">
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
                <div class="col-lg-4">
                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Parent element')</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <select name="parent_id" class=" form-control select2">
                                    <option value="">== @lang('ROOT') ==</option>
                                    @foreach ($taxonomys as $val)
                                        @php
                                            if (isset($detail->id) && $detail->id == $val->id) {
                                                continue;
                                            }
                                        @endphp
                                        @if (empty($val->parent_id))
                                            <option value="{{ $val->id }}"
                                                {{ isset($detail->parent_id) && $val->id != $detail->id && $detail->parent_id == $val->id ? 'selected' : '' }}>
                                                @lang($val->name)</option>
                                            @foreach ($taxonomys as $val1)
                                                @php
                                                    if (isset($detail->id) && $detail->id == $val1->id) {
                                                        continue;
                                                    }
                                                @endphp
                                                @if ($val1->parent_id == $val->id)
                                                    <option value="{{ $val1->id }}"
                                                        {{ isset($detail->parent_id) && $val1->id != $detail->id && $detail->parent_id == $val1->id ? 'selected' : '' }}>
                                                        - - @lang($val1->name)</option>
                                                    @foreach ($taxonomys as $val2)
                                                        @php
                                                            if (isset($detail->id) && $detail->id == $val2->id) {
                                                                continue;
                                                            }
                                                        @endphp
                                                        @if ($val2->parent_id == $val1->id)
                                                            <option value="{{ $val2->id }}"
                                                                {{ isset($detail->parent_id) && $val2->id != $detail->id && $detail->parent_id == $val2->id ? 'selected' : '' }}>
                                                                - - - - @lang($val2->name)</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Taxonomy')<small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="taxonomy" class=" form-control select2" required>
                                    <option value="">@lang('Please select')</option>
                                    @foreach (App\Consts::TAXONOMY as $key => $value)
                                        <option value="{{ $key }}"> {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border sw_featured d-flex-al-center">
                            <label class="switch ">
                                <input id="sw_featured" name="json_params[is_featured]" value="1" type="checkbox"
                                    {{ isset($detail->json_params->is_featured) && $detail->json_params->is_featured == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <label class="box-title ml-1" for="sw_featured">@lang('Is featured')</label>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->status) && $detail->status == $val ? 'checked' : '' }}>
                                            @lang($val)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Order')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="number" class="form-control" name="iorder"
                                    placeholder="@lang('Order')" value="{{ $detail->iorder ?? old('iorder') }}">
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image')</h3>
                        </div>
                        <div class="box-body">
                            <div
                                class="form-group box_img_right {{ isset($detail->json_params->image) ? 'active' : '' }}">
                                <div id="image-holder">
                                    @if (isset($detail->json_params->image) && $detail->json_params->image != '')
                                        <img src="{{ $detail->json_params->image }}">
                                    @else
                                        <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                    @endif
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> @lang('Choose')
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden"
                                        name="json_params[image]" placeholder="@lang('Image source')"
                                        value="{{ $detail->json_params->image ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Page config')</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <label>@lang('Route Name')</label>
                                <small class="text-red">*</small>
                                <select name="json_params[route_name]" id="route_name" class="form-control select2"
                                    style="width:100%" required autocomplete="off">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($route_name as $key => $item)
                                        <option value="{{ $item['name'] }}"
                                            {{ isset($detail->json_params->route_name) && $detail->json_params->route_name == $item['name'] ? 'selected' : '' }}>
                                            {{ __($item['title']) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $route = $detail->json_params->route_name ?? '';
                                $templates = collect(App\Consts::ROUTE_NAME);
                                $template = $templates->first(function ($item, $key) use ($route) {
                                    return $item['name'] == $route;
                                });
                            @endphp
                            <div class="form-group">
                                <label>@lang('Template')</label>
                                <small class="text-red">*</small>
                                <select name="json_params[template]" id="template" class="form-control select2"
                                    style="width:100%" required autocomplete="off">
                                    <option value="">@lang('Please select')</option>
                                    @isset($template['template'])
                                        @foreach ($template['template'] as $key => $item)
                                            <option value="{{ $item['name'] }}"
                                                {{ isset($detail->json_params->template) && $detail->json_params->template == $item['name'] ? 'selected' : '' }}>
                                                {{ __($item['title']) }}
                                            </option>
                                        @endforeach
                                    @endisset

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

            </div>

        </form>
    </section>

@endsection

@section('script')
    <script>
        CKEDITOR.replace('content_vi', ck_options);


        $(document).ready(function() {

            var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';
            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('input[type=hidden]').val("");
            });


            // Routes get all
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
