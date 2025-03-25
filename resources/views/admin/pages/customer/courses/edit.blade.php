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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                            @isset($languages)
                                @foreach ($languages as $item)
                                    @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                                        @if (Request::get('lang') != '')
                                            <a class="text-primary pull-right"
                                                href="{{ route(Request::segment(2) . '.edit', $detail->id) }}"
                                                style="padding-left: 15px">
                                                <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                            </a>
                                        @endif
                                    @else
                                        @if (Request::get('lang') != $item->lang_locale)
                                            <a class="text-primary pull-right"
                                                href="{{ route(Request::segment(2) . '.edit', $detail->id) }}?lang={{ $item->lang_locale }}"
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
                        @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                            <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                        @endif
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>@lang('Gallery Image')</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>@lang('Related Products')</h5>
                                        </a>
                                    </li> --}}
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')"
                                                        value="{{ old('name') ?? ($detail->json_params->name->$lang ?? $detail->name) }}"
                                                        required>
                                                </div>
                                            </div>
                                            

                                            {{-- <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>URL tùy chọn</label>
                                                    <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                                                    <small class="form-text">
                                                        (
                                                        <i class="fa fa-info-circle"></i>
                                                        Maximum 100 characters in the group: "A-Z", "a-z", "0-9" and
                                                        "-_" )
                                                    </small>
                                                    <input name="alias" class="form-control"
                                                        value="{{ $detail->alias ?? old('alias') }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Brief')</label>
                                                    <textarea name="json_params[brief][{{ $lang }}]" class="form-control" rows="5">{{ $detail->json_params->brief->$lang ?? old('json_params[brief][' . $lang . ']') }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Content')</label>
                                                        <textarea name="json_params[content][{{ $lang }}]" class="form-control" id="content_vi">{{ $detail->json_params->content->$lang ?? old('json_params[content][' . $lang . ']') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
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
                                            </div> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input class="btn btn-warning btn-sm add-gallery-image"
                                                        data-toggle="tooltip" title="Nhấn để chọn thêm ảnh"
                                                        type="button" value="Thêm ảnh" />
                                                </div>
                                                <div class="row list-gallery-image">
                                                    @isset($detail->json_params->gallery_image)
                                                        @foreach ($detail->json_params->gallery_image as $key => $value)
                                                            @if ($value != null)
                                                                <div class="col-lg-2 col-md-3 col-sm-4 mb-1 gallery-image">
                                                                    <img width="150px" height="150px" class="img-width"
                                                                        src="{{ $value }}">
                                                                    <input type="text"
                                                                        name="json_params[gallery_image][{{ $key }}]"
                                                                        class="hidden" id="gallery_image_{{ $key }}"
                                                                        value="{{ $value }}">
                                                                    <div class="btn-action">
                                                                        <span
                                                                            class="btn btn-sm btn-success btn-upload lfm mr-5"
                                                                            data-input="gallery_image_{{ $key }}">
                                                                            <i class="fa fa-upload"></i>
                                                                        </span>
                                                                        <span class="btn btn-sm btn-danger btn-remove">
                                                                            <i class="fa fa-trash"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane " id="tab_3">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title">Danh sách liên quan</h3>
                                                    </div><!-- /.box-header -->
                                                    <div class="box-body table-responsive no-padding">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-md-1">ID</th>
                                                                    <th class="col-md-5">Tên</th>
                                                                    <th class="col-md-2">Danh mục</th>
                                                                    <th class="col-md-2">Đăng lúc</th>
                                                                    <th class="col-md-2">Bỏ chọn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="post_related">
                                                                @isset($relateds)
                                                                    @foreach ($relateds as $item)
                                                                        <tr>
                                                                            <td>{{ $item->id }}</td>
                                                                            <td>{{ $item->name }}</td>
                                                                            <td>{{ $item->is_type }}</td>
                                                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                                                            </td>
                                                                            <td>
                                                                                <input name="json_params[related_post][]"
                                                                                    type="checkbox"
                                                                                    value="{{ $item->id }}"
                                                                                    class="mr-15 related_post_item cursor"
                                                                                    autocomplete="off" checked>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div><!-- /.box-body -->
                                                </div><!-- /.box -->
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title"></h3>
                                                        <div class="box-tools col-md-12">
                                                            <div class="col-md-6">
                                                                <select class="form-control select2"
                                                                    id="search_taxonomy_id" style="width:100%">
                                                                    <option value="">- Chọn danh mục -</option>
                                                                    @foreach ($parents as $item)
                                                                        @if ($item->parent_id == 0 || $item->parent_id == null)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->json_params->title->$lang ?? $item->title }}
                                                                            </option>

                                                                            @foreach ($parents as $sub)
                                                                                @if ($item->id == $sub->parent_id)
                                                                                    <option value="{{ $sub->id }}">
                                                                                        - -
                                                                                        {{ $sub->title }}
                                                                                    </option>

                                                                                    @foreach ($parents as $sub_child)
                                                                                        @if ($sub->id == $sub_child->parent_id)
                                                                                            <option
                                                                                                value="{{ $sub_child->id }}">
                                                                                                - - - -
                                                                                                {{ $sub_child->title }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="input-group col-md-6">
                                                                <input type="text" id="search_title_post"
                                                                    class="form-control pull-right"
                                                                    placeholder="Tiêu đề..." autocomplete="off">
                                                                <div class="input-group-btn">
                                                                    <button type="button"
                                                                        class="btn btn-default btn_search">
                                                                        <i class="fa fa-search"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.box-header -->
                                                    <div class="box-body table-responsive no-padding">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-md-1">ID</th>
                                                                    <th class="col-md-5">Tên</th>
                                                                    <th class="col-md-2">Danh mục</th>
                                                                    <th class="col-md-2">Đăng lúc</th>
                                                                    <th class="col-md-2">Chọn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="post_available">

                                                            </tbody>
                                                        </table>
                                                    </div><!-- /.box-body -->
                                                </div><!-- /.box -->
                                            </div>
                                        </div>

                                    </div> --}}

                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->

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
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Level')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="level_id" class=" form-control select2">
                                    @foreach ($levels as $val)
                                        <option value="">@lang('Level')</option>
                                        <option value="{{ $val->id }}"
                                            {{ isset($detail->level_id) && $detail->level_id == $val->id ? 'selected' : '' }}>
                                            {{ $val->json_params->name->$lang ?? $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Syllabus')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="syllabus_id" class=" form-control select2">
                                    @foreach ($syllabus as $val)
                                        <option value="">@lang('Syllabus')</option>
                                        <option value="{{ $val->id }}"
                                            {{ isset($detail->syllabus_id) && $detail->syllabus_id == $val->id ? 'selected' : '' }}>
                                            {{ $val->json_params->name->$lang ?? $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{--<div class="box box-primary">
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
                    </div>

                     <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Paramater')</h3>
                        </div>
                        <div class="box-body">
                            @foreach ($parameter as $val)
                                @if ($val->parent_id == 0 || $val->parent_id == null)
                                    <div class="form-group">
                                        <label>{{ $val->name }}</label>
                                        <ul class="list-relation row">
                                            <input type="hidden"
                                                name="json_params[paramater][{{ $val->id }}][name]"
                                                value="{{ Str::slug($val->name) }}">
                                            @foreach ($parameter as $item)
                                                @if ($item->parent_id == $val->id)
                                                    <div class="col-md-6">
                                                        <li>
                                                            <label for="page-{{ $item->id }}">
                                                                @php
                                                                    $val_name = Str::slug($val->name);
                                                                @endphp
                                                                <input id="page-{{ $item->id }}"
                                                                    name="json_params[paramater][{{ $item->parent_id }}][childs][]"
                                                                    type="checkbox"
                                                                    {{ isset($detail->json_params->paramater->{$item->parent_id}->childs) && isset($detail->json_params->paramater->{$item->parent_id}) && in_array($item->id, $detail->json_params->paramater->{$item->parent_id}->childs) ? 'checked' : '' }}
                                                                    value="{{ $item->id }}">
                                                                {{ $item->json_params->name->$lang ?? $item->name }}
                                                            </label>
                                                        </li>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border sw_featured d-flex-al-center">
                            <label class="switch ">
                                <input id="sw_featured" name="is_featured" value="1" type="checkbox"
                                    {{ isset($detail->is_featured) && $detail->is_featured == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <label class="box-title ml-1" for="sw_featured">@lang('Is featured')</label>
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
                            <h3 class="box-title">@lang('Categories') <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <ul class="list-relation">
                                    @foreach ($parents as $item)
                                        @if ($item->parent_id == 0 || $item->parent_id == null)
                                            <li>
                                                <label for="category-{{ $item->id }}">
                                                    <input id="category-{{ $item->id }}" name="relation[]"
                                                        {{ isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item->id) != null ? 'checked' : '' }}
                                                        type="checkbox" value="{{ $item->id }}">
                                                    {{ $item->json_params->name->$lang ?? $item->name }}
                                                </label>
                                                <ul class="list-relation">
                                                    @foreach ($parents as $item1)
                                                        @if ($item1->parent_id == $item->id)
                                                            <li>
                                                                <label for="category-{{ $item1->id }}">
                                                                    <input id="category-{{ $item1->id }}"
                                                                        name="relation[]" type="checkbox"
                                                                        {{ isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item1->id) != null ? 'checked' : '' }}
                                                                        value="{{ $item1->id }}">
                                                                    {{ $item1->json_params->name->$lang ?? $item1->name }}
                                                                </label>
                                                                <ul class="list-relation">
                                                                    @foreach ($parents as $item2)
                                                                        @if ($item2->parent_id == $item1->id)
                                                                            <li>
                                                                                <label for="category-{{ $item2->id }}">
                                                                                    <input
                                                                                        id="category-{{ $item2->id }}"
                                                                                        name="relation[]" type="checkbox"
                                                                                        {{ isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item2->id) != null ? 'checked' : '' }}
                                                                                        value="{{ $item2->id }}">
                                                                                    {{ $item2->json_params->name->$lang ?? $item2->name }}
                                                                                </label>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right {{ isset($detail->image) ? 'active' : '' }}">
                                <div id="image-holder">
                                    @if ($detail->image != '')
                                        <img src="{{ $detail->image }}">
                                    @else
                                        <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                    @endif
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i
                                    class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> @lang('Choose')
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden" name="image"
                                        placeholder="@lang('Image source')" value="{{ $detail->image??'' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image thumb')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="form-group box_img_right {{ isset($detail->image_thumb) ? 'active' : '' }}">
                                    <div id="image_thumb-holder">
                                        @if ($detail->image_thumb != '')
                                            <img src="{{ $detail->image_thumb }}">
                                        @else
                                            <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                        @endif
                                    </div>
                                    <span class="btn btn-sm btn-danger btn-remove"><i
                                        class="fa fa-trash"></i></span>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a data-input="image_thumb" data-preview="image_thumb-holder"
                                                class="btn btn-primary lfm" data-type="cms-image">
                                                <i class="fa fa-picture-o"></i> @lang('Choose')
                                            </a>
                                        </span>
                                        <input id="image_thumb" class="form-control inp_hidden" type="hidden" name="image_thumb"
                                            placeholder="@lang('image_link')..." value="{{ $detail->image_thumb??'' }}">
                                    </div>
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
                            <h3 class="box-title">@lang('Widgets config')</h3>
                        </div>
                        <div class="box-body">
                            @foreach ($widgetConfig as $val)
                                <div class="form-group">
                                    <label>{{ $val->name }}</label>
                                    <select name="widget[]" class=" form-control select2">
                                        <option value="">@lang('Please select')</option>
                                        @foreach ($widgets as $val_wg)
                                            @if ($val_wg->widget_code == $val->widget_code)
                                                <option value="{{ $val_wg->id }}"
                                                    {{ isset($detail->json_params->widget) && in_array($val_wg->id, $detail->json_params->widget) ? 'selected' : '' }}>
                                                    @lang($val_wg->title)
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div> --}}
                </div>

            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
        CKEDITOR.replace('content_vi', ck_options);

        $(document).ready(function() {

            // Fill Available Blocks by template
            $(document).on('click', '.btn_search', function() {
                let keyword = $('#search_title_post').val();
                let taxonomy_id = $('#search_taxonomy_id').val();
                let _targetHTML = $('#post_available');
                _targetHTML.html('');
                let checked_post = [];
                $('input[name="json_params[related_post][]"]:checked').each(function() {
                    checked_post.push($(this).val());
                });

                let url = "{{ route('cms_product.search') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        taxonomy_id: taxonomy_id,
                        other_list: checked_post,
                        different_id: {{ $detail->id }},
                        is_type: "{{ App\Consts::TAXONOMY['product'] }}"
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr>';
                                    _item += '<td>' + item.id + '</td>';
                                    _item += '<td>' + item.name + '</td>';
                                    _item += '<td>' + item.is_type + '</td>';
                                    _item += '<td>' + formatDate(item.created_at) +
                                        '</td> ';
                                    _item +=
                                        '<td><input name="json_params[related_post][]" type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item cursor" autocomplete="off"></td>';
                                    _item += '</tr>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<tr><td colspan="5">' + response.message +
                                '</td></tr>');
                        }
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            });

            // Checked and unchecked item event
            $(document).on('click', '.related_post_item', function() {
                let ischecked = $(this).is(':checked');
                let _root = $(this).closest('tr');
                let _targetHTML;

                if (ischecked) {
                    _targetHTML = $("#post_related");
                } else {
                    _targetHTML = $("#post_available");
                }
                _targetHTML.append(_root);
            });

            var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';

            $('.add-gallery-image').click(function(event) {
                let keyRandom = new Date().getTime();
                let elementParent = $('.list-gallery-image');
                let elementAppend =
                    '<div class="col-lg-3 col-md-3 col-sm-4 mb-1 gallery-image my-15">';
                elementAppend += '<img width="150px" height="150px" class="img-width"';
                elementAppend += 'src="' + no_image_link + '">';
                elementAppend += '<input type="text" name="json_params[gallery_image][' + keyRandom +
                    ']" class="hidden" id="gallery_image_' + keyRandom +
                    '">';
                elementAppend += '<div class="btn-action">';
                elementAppend +=
                    '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
                    keyRandom +
                    '" data-type="cms-image">';
                elementAppend += '<i class="fa fa-upload"></i>';
                elementAppend += '</span>';
                elementAppend += '<span class="btn btn-sm btn-danger btn-remove">';
                elementAppend += '<i class="fa fa-trash"></i>';
                elementAppend += '</span>';
                elementAppend += '</div>';
                elementParent.append(elementAppend);

                $('.lfm').filemanager('image', {
                    prefix: route_prefix
                });
            });
            // Change image for img tag gallery-image
            $('.list-gallery-image').on('change', 'input', function() {
                let _root = $(this).closest('.gallery-image');
                var img_path = $(this).val();
                _root.find('img').attr('src', img_path);
            });

            // Delete image
            $('.list-gallery-image').on('click', '.btn-remove', function() {
                // if (confirm("@lang('confirm_action')")) {
                let _root = $(this).closest('.gallery-image');
                _root.remove();
                // }
            });

            $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
                $(this).find('.btn-action').show();
            });
            $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
                $(this).find('.btn-action').hide();
            });

            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.input[type=hidden]').val("");
            });

            $('.add_space').on('click', function() {
                var _item =
                    "<input type='text' class='form-control form-group ' name='json_product[space][]' placeholder='Nhập không gian' value=''>";
                $('.defautu_space').append(_item);
            });

            $('.add_convenient').on('click', function() {
                var _item = "";
                _item += "<div class='col-md-3 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][icon][]' placeholder='Icon' value=''>";
                _item += "</div>";
                _item += "<div class='col-md-9 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][name][]' placeholder='Nhập tiện nghi' value=''>";
                _item += "</div>";

                $('.defaunt_convenient').append(_item);
            });
            $('.ck_ty').on('change', function() {
                if ($("#form_product input[name='type']:checked").val() == 2) {
                    $('#type_price').attr("disabled", "true");
                } else {
                    $('#type_price').removeAttr('disabled');

                }
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
