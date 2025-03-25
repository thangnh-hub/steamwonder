@extends('admin.layouts.app')
@section('style')
    <style>
        .select2-container {
            width: 100% !important;
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
@section('title')
    @lang($module_name)
@endsection

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
                                                @foreach (App\Consts::STATUS as $key => $value)
                                                    <label>
                                                        <input type="radio" name="status" value="{{ $value }}"
                                                            {{ $detail->status == $value ? 'checked' : '' }}>
                                                        <small class="mr-15">{{ __($value) }}</small>
                                                    </label>
                                                @endforeach
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
                                <div class="col-md-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                @lang('Component items')
                                            </h3>
                                        </div>
                                        <div class="box-body table-responsive">
                                            @if (count($items) > 0)
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('Title')</th>
                                                            <th>@lang('Image')</th>
                                                            <th>@lang('Order')</th>
                                                            <th>@lang('Created at')</th>
                                                            <th>@lang('Status')</th>
                                                            <th>@lang('Action')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $item)
                                                            <form
                                                                action="{{ route(Request::segment(2) . '.destroy', $item->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('@lang('confirm_action')')">
                                                                <tr class="valign-middle">
                                                                    <td>
                                                                        {{ $item->title }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($item->image != '')
                                                                            <img style="max-width: 150px;"
                                                                                src="{{ $item->image }}"
                                                                                class="img-responsive">
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->iorder }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $item->created_at }}
                                                                    </td>
                                                                    <td>
                                                                        @lang($item->status)
                                                                    </td>
                                                                    <td>
                                                                        <a class="btn btn-sm btn-warning"
                                                                            data-toggle="tooltip"
                                                                            title="@lang('Edit')"
                                                                            data-original-title="@lang('Edit')"
                                                                            href="{{ route(Request::segment(2) . '.edit', $item->id) }}">
                                                                            <i class="fa fa-pencil-square-o"></i>
                                                                        </a>
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button class="btn btn-sm btn-danger"
                                                                            type="submit" data-toggle="tooltip"
                                                                            title="@lang('Delete')"
                                                                            data-original-title="@lang('Delete')">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            </form>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary">
                                        <form action="{{ route(Request::segment(2) . '.store') }}" method="POST"
                                            class="form-horizontal" id="form-main" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                                                        <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
                                                    @endif
                                            <div class="box-header with-border">
                                                <h3 class="box-title" id="item-title">
                                                    @lang('Add new item to component')
                                                </h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="item-title" class="col-sm-3 control-label">
                                                        @lang('Title')
                                                        <small class="text-red">*</small>
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="item-title"
                                                            placeholder="@lang('Title')" value="{{ old('title') }}"
                                                            name="title" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-brief" class="col-sm-3 control-label">
                                                        @lang('Sub title')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <textarea row="3" class="form-control" id="item-brief" placeholder="@lang('Sub title')" name="brief">{{ old('brief') }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-image" class="col-sm-3 control-label">
                                                        @lang('Image')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder"
                                                                    class="btn btn-primary lfm" data-type="banner">
                                                                    <i class="fa fa-picture-o"></i> @lang('Select')
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control" type="text"
                                                                name="image" placeholder="@lang('Image source')"
                                                                value="{{ old('image') }}">
                                                        </div>
                                                        <div id="image-holder" style="margin-top:15px;max-height:100px;">
                                                            @if (old('image') != '')
                                                                <img style="height: 5rem;" src="{{ old('image') }}">
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-url_link" class="col-sm-3 control-label">
                                                        @lang('Url redirect')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="item-url_link"
                                                            placeholder="@lang('Url redirect')" name="json_params[url_link]"
                                                            value="{{ old('json_params[url_link]') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-url_link_title" class="col-sm-3 control-label">
                                                        @lang('Url redirect title')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control"
                                                            id="item-url_link_title" placeholder="@lang('Url redirect title')"
                                                            name="json_params[url_link_title]"
                                                            value="{{ old('json_params[url_link_title]') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-target" class="col-sm-3 control-label">
                                                        @lang('Select URL target')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <select name="json_params[target]" id="item-target"
                                                            class="form-control select2">
                                                            <option value="_self" selected>@lang('_self')</option>
                                                            <option value="_blank">@lang('_blank')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-order" class="col-sm-3 control-label">
                                                        @lang('Order')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <input type="number" class="form-control" id="item-iorder"
                                                            placeholder="@lang('Order')" value="{{ old('iorder') }}"
                                                            name="iorder">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="item-status" class="col-sm-3 control-label">
                                                        @lang('Status')
                                                    </label>
                                                    <div class="col-sm-9">
                                                        <div class="form-control">
                                                            @foreach (App\Consts::STATUS as $key => $value)
                                                                <label>
                                                                    <input type="radio" name="status"
                                                                        value="{{ $value }}"
                                                                        {{ $loop->index == 0 ? 'checked' : '' }}>
                                                                    <small class="mr-15">{{ __($value) }}</small>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <input type="hidden" name="parent_id" value="{{ $detail->id }}">
                                                <button type="submit" class="btn btn-success btn-sm submit_form">
                                                    @lang('Add new')
                                                </button>
                                                <button type="button"
                                                    class="btn btn-default btn-sm pull-right reset_form">
                                                    @lang('Reset form')
                                                </button>
                                            </div>
                                        </form>
                                    </div>
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

        });
    </script>
@endsection
