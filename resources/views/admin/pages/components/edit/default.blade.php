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
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-component" role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
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
                                    @isset($parent)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Parent element dd')</label>
                                                <select name="parent_id" id="parent_id" class="form-control select2"
                                                    style="width: 100%">
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($parents as $item)
                                                        @if ($item->parent_id == 0 || $item->parent_id == null)
                                                            <option value="{{ $item->id }}"
                                                                {{ $detail->parent_id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->title }}</option>

                                                            @foreach ($parents as $sub)
                                                                @if ($item->id == $sub->parent_id)
                                                                    <option value="{{ $sub->id }}"
                                                                        {{ $detail->parent_id == $sub->id ? 'selected' : '' }}>
                                                                        - -
                                                                        {{ $sub->title }}</option>

                                                                    @foreach ($parents as $sub_child)
                                                                        @if ($sub->id == $sub_child->parent_id)
                                                                            <option value="{{ $sub_child->id }}"
                                                                                {{ $detail->parent_id == $sub_child->id ? 'selected' : '' }}>
                                                                                - - - - {{ $sub_child->title }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endisset
                                    <div class="col-md-6">
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
                                    {{-- @isset($component_configs)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    @lang('Component type')
                                                    <small class="text-red">*</small>
                                                </label>
                                                <select name="component_code" id="component_code" class="form-control select2"
                                                    style="width: 100%" required>
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($component_configs as $item)
                                                        <option value="{{ $item->component_code }}"
                                                            {{ $item->component_code == $detail->component_code ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endisset --}}
                                    @isset($component_configs)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Layout')</label>
                                                <select name="json_params[layout]" id="component_layout"
                                                    class="form-control select2" style="width: 100%">
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($component_configs as $item)
                                                        @if ($item->component_code == $detail->component_code)
                                                            @php
                                                                $json_params = json_decode($item->json_params);
                                                            @endphp
                                                            @isset($json_params->layout)
                                                                @foreach ($json_params->layout as $name => $value)
                                                                    <option value="{{ $value }}"
                                                                        {{ isset($detail->json_params->layout) && $value == $detail->json_params->layout ? 'selected' : '' }}>
                                                                        {{ __($value) }}
                                                                    </option>
                                                                @endforeach
                                                            @endisset
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endisset
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Order')</label>
                                            <input type="number" class="form-control" name="iorder"
                                                placeholder="@lang('Order')"
                                                value="{{ old('iorder') ?? $detail->iorder }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Brief')</label>
                                            <textarea name="brief" id="brief" class="form-control" rows="5">{{ old('brief') ?? ($detail->json_params->brief->$lang?? $detail->brief) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Content')</label>
                                            <textarea name="content" id="content" class="form-control" rows="5">{{ old('content') ?? ($detail->json_params->content->$lang??$detail->content) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Image')</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="image-holder"
                                                        class="btn btn-primary lfm" data-type="cms-image">
                                                        <i class="fa fa-picture-o"></i> @lang('Select')
                                                    </a>
                                                </span>
                                                <input id="image" class="form-control" type="text" name="image"
                                                    placeholder="@lang('Image source')" value="{{ $detail->image }}">
                                            </div>
                                            <div id="image-holder" style="margin-top:15px;max-height:100px;">
                                                @if ($detail->image != '')
                                                    <img style="height: 5rem;" src="{{ $detail->image }}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Background image')</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image_background"
                                                        data-preview="image_background-holder" data-type="cms-image"
                                                        class="btn btn-primary lfm">
                                                        <i class="fa fa-picture-o"></i> @lang('Select')
                                                    </a>
                                                </span>
                                                <input id="image_background" class="form-control" type="text"
                                                    name="image_background" placeholder="@lang('Image source')"
                                                    value="{{ $detail->image }}">
                                            </div>
                                            <div id="image_background-holder" style="margin-top:15px;max-height:100px;">
                                                @if ($detail->image_background != '')
                                                    <img style="height: 5rem;" src="{{ $detail->image_background }}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
        </div>
    </section>

@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('#component_code').on('change', function() {
                let component_code = $(this).val();

                var _targetLayout = $(this).parents('.form-component').find('#component_layout');
                _targetLayout.html('');
                var url = "{{ route('component.config') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        "component_code": component_code,
                    },
                    success: function(response) {
                        var _optionListLayout = '<option value="">@lang('Please select')</option>';
                        if (response.data != null) {
                            let json_params = JSON.parse(response.data);
                            if (json_params.hasOwnProperty('layout')) {
                                Object.entries(json_params.layout).forEach(([key, value]) => {
                                    _optionListLayout += '<option value="' + value +
                                        '"> ' + value + ' </option>';
                                });
                            }
                        }
                        _targetLayout.html(_optionListLayout);
                        $(".select2").select2();
                    },
                    error: function(response) {
                        // Get errors
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            });

            $('#menu-sort').nestable({
                group: 0,
                maxDepth: 1,
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
                            root_id: '{{ $detail->id ?? '0' }}',
                        },
                    })
                    .done(function(data) {
                        $('#loading').hide();
                        if (data.error == 0) {
                            // alert('Cập nhật thành công');
                            location.reload();
                        } else {
                            // alert("Cập nhật thất bại");
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
                        url: '{{ route('block.delete') }}',
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
        });
    </script>
@endsection
