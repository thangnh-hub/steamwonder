@extends('admin.layouts.app')
@section('style')
    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}">
                <i class="fa fa-plus"></i> @lang('Add')
            </a>
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
                <h3 class="box-title">@lang('Update form')</h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right"
                                    href="{{ route(Request::segment(2) . '.edit', $detail->id) }}" style="padding-left: 15px">
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
                        <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                            <i class="fa fa-bars"></i> @lang('List')
                        </a>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                                    <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                                @endif
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                @lang('Title')
                                                <small class="text-red">*</small>
                                            </label>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="@lang('Title')"
                                                value="{{ old('title') ?? ($detail->json_params->title->$lang??$detail->title) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('Status')</label>
                                            <div class="form-control">
                                                <label>
                                                    <input type="radio" name="status" value="active"
                                                        {{ $detail->status == 'active' ? 'checked' : '' }}>
                                                    <small class="mr-15">@lang('active')</small>
                                                </label>
                                                <label>
                                                    <input type="radio" name="status" value="delete"
                                                        {{ $detail->status == 'delete' ? 'checked' : '' }}>
                                                    <small class="mr-15">@lang('delete')</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('Order')</label>
                                            <input type="number" class="form-control" name="iorder"
                                                placeholder="@lang('Order')"
                                                value="{{ old('iorder') ?? $detail->iorder }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Brief')</label>
                                            <textarea name="brief" id="brief" class="form-control" rows="5">{{ old('brief') ?? ($detail->json_params->brief->$lang??$detail->brief) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-floppy-o"></i>
                                            @lang('Save')
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-md-12">
                                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                </div>
                                <div class="col-lg-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">@lang('Add new item to component')</h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="the-box ">
                                                <form action="{{ route(Request::segment(2) . '.store') }}" method="POST"
                                                    id="form-main" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('POST')
                                                    @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                                                        <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                                                    @endif
                                                    <div class="d-flex-wap">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>
                                                                    @lang('Title')
                                                                    <small class="text-red">*</small>
                                                                </label>
                                                                <input type="text" class="form-control" name="title"
                                                                    placeholder="@lang('Title')"
                                                                    value="{{ old('title') ?? '' }}" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Status')</label>
                                                                <div class="form-control">
                                                                    <label>
                                                                        <input type="radio" name="status"
                                                                            value="active" checked>
                                                                        <small class="mr-15">@lang('active')</small>
                                                                    </label>
                                                                    <label>
                                                                        <input type="radio" name="status"
                                                                            value="delete">
                                                                        <small class="mr-15">@lang('delete')</small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>
                                                                    @lang('Sub title')
                                                                </label>
                                                                <textarea row="3" class="form-control" id="item-brief" placeholder="@lang('Sub title')" name="brief">{{ old('brief') }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>@lang('Image')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-btn">
                                                                        <a data-input="image" data-preview="image-holder"
                                                                            class="btn btn-primary lfm"
                                                                            data-type="banner">
                                                                            <i class="fa fa-picture-o"></i>
                                                                            @lang('Select')
                                                                        </a>
                                                                    </span>
                                                                    <input id="image" class="form-control"
                                                                        type="text" name="image"
                                                                        placeholder="@lang('Image source')"
                                                                        value="{{ old('image') }}">
                                                                </div>
                                                                <div id="image-holder"
                                                                    style="margin-top:15px;max-height:100px;">
                                                                    @if (old('image') != '')
                                                                        <img style="height: 5rem;"
                                                                            src="{{ old('image') }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Url redirect')</label>
                                                                <input type="text" class="form-control"
                                                                    name="json_params[url_link]"
                                                                    placeholder="@lang('Url redirect')"
                                                                    value="{{ old('json_params[url_link]') ?? '' }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Url redirect title')</label>
                                                                <input type="text" class="form-control"
                                                                    name="json_params[url_link_title]"
                                                                    placeholder="@lang('Url redirect title')"
                                                                    value="{{ old('json_params[url_link_title]') ?? '' }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="item-target">@lang('Select URL target')</label>
                                                                <select name="json_params[target]" id="item-target"
                                                                    class="form-control select2">
                                                                    <option value="_self" selected>
                                                                        @lang('_self')</option>
                                                                    <option value="_blank">@lang('_blank')
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>@lang('Order')</label>
                                                                <input type="number" class="form-control"
                                                                    id="item-iorder" placeholder="@lang('Order')"
                                                                    value="{{ old('iorder') }}" name="iorder">
                                                            </div>
                                                        </div>
                                                        <div class="text-end col-md-6">
                                                            <div class="btn-group btn-group-devided">
                                                                <input type="hidden" name="parent_id"
                                                                    value="{{ $detail->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-success btn-sm submit_form">
                                                                    <i class="fa fa-floppy-o"></i>
                                                                    @lang('Add new')
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-6">
                                    @if (isset($items) && count($items) > 0)
                                        <div class="row">
                                            <div class="col-md-12 mt-md-10">
                                                <div class="box box-primary">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title">
                                                            @lang('Component items')
                                                        </h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="table-responsive">
                                                            <div class="dd" id="menu-sort">
                                                                <ol class="dd-list">
                                                                    @foreach ($items as $item)
                                                                        <li class="dd-item dd3-item "
                                                                            data-id="{{ $item->id }}">
                                                                            <div class="dd-handle dd3-handle"></div>
                                                                            <div class="dd3-content">
                                                                                <span class="text float-start"
                                                                                    data-update="title">{{ $item->title }}</span>
                                                                                <span class="text float-end"></span>
                                                                                <a data-toggle="collapse"
                                                                                    href="#item-details-{{ $item->id }}"
                                                                                    role="button" aria-expanded="false"
                                                                                    aria-controls="item-details-{{ $item->id }}"
                                                                                    class="show-item-details">
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <div class="clearfix"></div>
                                                                            </div>

                                                                            <div class="item-details collapse multi-collapse form-block"
                                                                                id="item-details-{{ $item->id }}">

                                                                                <form role="form"
                                                                                    action="{{ route(Request::segment(2) . '.update', $item->id) }}"
                                                                                    method="POST">
                                                                                    @csrf
                                                                                    @method('PUT')
                                                                                    <input type="hidden" name="parent_id"
                                                                                        value="{{ $detail->id }}">
                                                                                    <div class="box-body">

                                                                                        <div class="d-flex-wap">
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label>
                                                                                                        @lang('Title')
                                                                                                        <small
                                                                                                            class="text-red">*</small>
                                                                                                    </label>
                                                                                                    <input type="text"
                                                                                                        class="form-control"
                                                                                                        name="title"
                                                                                                        placeholder="@lang('Title')"
                                                                                                        value="{{ old('title') ?? $item->title }}"
                                                                                                        required>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label>@lang('Status')</label>
                                                                                                    <div
                                                                                                        class="form-control">
                                                                                                        <label>
                                                                                                            <input
                                                                                                                type="radio"
                                                                                                                name="status"
                                                                                                                value="active"
                                                                                                                {{ $item->status == 'active' ? 'checked' : '' }}>
                                                                                                            <small
                                                                                                                class="mr-15">@lang('active')</small>
                                                                                                        </label>
                                                                                                        <label>
                                                                                                            <input
                                                                                                                type="radio"
                                                                                                                name="status"
                                                                                                                value="delete"
                                                                                                                {{ $item->status == 'delete' ? 'checked' : '' }}>
                                                                                                            <small
                                                                                                                class="mr-15">@lang('delete')</small>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                <div class="form-group">
                                                                                                    <label>
                                                                                                        @lang('Sub title')
                                                                                                    </label>
                                                                                                    <blade
                                                                                                        ___html_tags_2___ />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-12">
                                                                                                <div class="form-group">
                                                                                                    <label>@lang('Image')</label>
                                                                                                    <div
                                                                                                        class="input-group">
                                                                                                        <span
                                                                                                            class="input-group-btn">
                                                                                                            <a data-input="image{{ $item->id }}"
                                                                                                                data-preview="image-holder{{ $item->id }}"
                                                                                                                class="btn btn-primary lfm"
                                                                                                                data-type="cms-image">
                                                                                                                <i
                                                                                                                    class="fa fa-picture-o"></i>
                                                                                                                @lang('Select')
                                                                                                            </a>
                                                                                                        </span>
                                                                                                        <input
                                                                                                            id="image{{ $item->id }}"
                                                                                                            class="form-control"
                                                                                                            type="text"
                                                                                                            name="image"
                                                                                                            value="{{ $item->image }}"
                                                                                                            placeholder="@lang('Image source')">
                                                                                                    </div>
                                                                                                    <div id="image-holder{{ $item->id }}"
                                                                                                        style="margin-top:15px;max-height:100px;">
                                                                                                        @if ($item->image != '')
                                                                                                            <img style="height: 5rem;"
                                                                                                                src="{{ $item->image }}">
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>


                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label>@lang('Url redirect')</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control"
                                                                                                        name="json_params[url_link]"
                                                                                                        placeholder="@lang('Url redirect')"
                                                                                                        value="{{ $item->json_params->url_link ? $item->json_params->url_link : old('json_params[url_link]') }}">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label>@lang('Url redirect title')</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control"
                                                                                                        name="json_params[url_link_title]"
                                                                                                        placeholder="@lang('Url redirect title')"
                                                                                                        value="{{ $item->json_params->url_link_title ? $item->json_params->url_link_title : old('json_params[url_link_title]') }}">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label>@lang('Icon')</label>
                                                                                                    <input type="text"
                                                                                                        class="form-control"
                                                                                                        name="json_params[icon]"
                                                                                                        placeholder="Ex: fa fa-folder"
                                                                                                        value="{{ $item->json_params->icon ?? '' }}">
                                                                                                </div>
                                                                                            </div>


                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="clearfix"></div>
                                                                                    <div class="text-end mt-2">
                                                                                        <button
                                                                                            class="btn btn-primary btn-sm">@lang('Save')</button>
                                                                                        <p class="btn btn-danger remove_menu btn-sm"
                                                                                            data-id="{{ $item->id }}">
                                                                                            Remove </p>
                                                                                    </div>
                                                                                </form>

                                                                            </div>
                                                                            <div class="clearfix"></div>
                                                                        </li>
                                                                    @endforeach
                                                                </ol>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="box-footer">
                                                        <a class="btn btn-warning btn-flat menu-sort-save btn-sm"
                                                            title="@lang('Save')">
                                                            <i class="fa fa-floppy-o"></i>
                                                            @lang('Save sort')
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">

            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#menu-sort').nestable({
                group: 0,
                maxDepth: 1,
            });
        });
        $('.menu-sort-save').click(function() {
            $('#loading').show();
            let serialize = $('#menu-sort').nestable('serialize');
            let menu = JSON.stringify(serialize);
            $.ajax({
                    url: '{{ route('component.update_sort') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        menu: menu,
                        root_id: {{ $detail->id }}
                    },
                })
                .done(function(data) {
                    $('#loading').hide();
                    if (data.error == 0) {
                        location.reload();
                    } else {
                        alert(data.msg);
                        location.reload();
                    }
                });
        });
        $('.remove_menu').click(function() {
            if (confirm("@lang('confirm_action')")) {
                let _root = $(this).closest('.dd-item');
                let id = $(this).data('id');
                $.ajax({
                    method: 'post',
                    url: '{{ route('component.delete') }}',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        if (data.error == 1) {
                            alert(data.msg);
                        } else {
                            _root.remove();
                        }
                    }
                });
            }
        });
    </script>
@endsection
