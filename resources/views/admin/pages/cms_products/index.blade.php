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
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
        {{-- <div class="box_excel">
            <a href="{{ route('product.excel.export') }}">
                <button class="btn btn-sm btn-primary "><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                    @lang('Export Excel')</button>
            </a>
            <button class="btn btn-sm btn-danger" data-toggle="modal" data-backdrop="static" data-keyboard="false"
                data-target="#import_excel"><i class="fa fa-file-excel-o" aria-hidden="true"></i> @lang('Import Excel')</button>
        </div> --}}
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Post category')</label>
                                <select name="taxonomy_id" id="taxonomy_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($parents as $item)
                                        @if ($item->parent_id == 0 || $item->parent_id == null)
                                            <option value="{{ $item->id }}"
                                                {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                            @foreach ($parents as $sub)
                                                @if ($item->id == $sub->parent_id)
                                                    <option value="{{ $sub->id }}"
                                                        {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $sub->id ? 'selected' : '' }}>
                                                        - -
                                                        {{ $sub->name }}
                                                    </option>
                                                    @foreach ($parents as $sub_child)
                                                        @if ($sub->id == $sub_child->parent_id)
                                                            <option value="{{ $sub_child->id }}"
                                                                {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $sub_child->id ? 'selected' : '' }}>
                                                                - - - -
                                                                {{ $sub_child->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($postStatus as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Is featured')</label>
                                <select name="is_featured" id="is_featured" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($booleans as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['is_featured']) && $key == $params['is_featured'] ? 'selected' : '' }}>
                                            @lang($value)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
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
            <div class="box-body table-responsive">
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

                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Image')</th>
                                <th style="width: 25%">@lang('Url Mapping')</th>
                                {{-- <th>@lang('Product category')</th> --}}
                                <th>@lang('Is featured')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @if ($row->parent_id == 0 || $row->parent_id == null)
                                    <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('@lang('confirm_action')')">
                                        <tr class="valign-middle">
                                            <td>
                                                <strong
                                                    style="font-size: 14px">{{ $row->json_params->name->{$lang} ?? $row->name }}</strong>
                                            </td>
                                            @php
                                                $url_mapping = route('frontend.page', ['taxonomy' => $row->alias ?? '']);
                                            @endphp
                                            <td>
                                                <img width="100px"
                                                    src="{{ $row->image ?? url('themes/admin/img/no_image.jpg') }}"
                                                    alt="{{ $row->name }}">
                                            </td>
                                            <td>
                                                <a href="{{ $url_mapping }}" target="_blank"
                                                    rel="noopener noreferrer">{{ $url_mapping }}</a>
                                                <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip"
                                                    title="@lang('Link')" data-original-title="@lang('Link')">
                                                    <span class="btn btn-flat btn-xs btn-info">
                                                        <i class="fa fa-external-link"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            {{-- <td>
                                                {{ $row->taxonomy_name }}
                                            </td> --}}
                                            <td>
                                                @lang($booleans[$row->is_featured])
                                            </td>
                                            <td>
                                                {{ $row->iorder }}
                                            </td>
                                            <td>
                                                {{ $row->updated_at }}
                                            </td>
                                            <td>
                                                @lang($row->status)
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="@lang('Update')" data-original-title="@lang('Update')"
                                                    href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit"
                                                    data-toggle="tooltip" title="@lang('Delete')"
                                                    data-original-title="@lang('Delete')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </form>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div id="import_excel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('Import Excel')</h4>
                </div>
                <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST"
                    id="form_product" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row">
                        <input type="hidden" name="import" value="true">
                        <input type="hidden" name="name" value="import">
                        <input type="hidden" name="is_type" value="{{ App\Consts::TAXONOMY['product'] }}">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Language')</label>
                                <select name="lang" class="form-control select2" style="width: 100%;">
                                    @isset($languages)
                                        @foreach ($languages as $item)
                                            <option value="{{ $item->lang_locale }}"
                                                {{ $item->is_default == 1 ? 'selected' : '' }}>
                                                {{ $item->lang_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Route Name')</label>
                                <small class="text-red">*</small>
                                <select name="route_name" id="route_name" required class="form-control select2"
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
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Template')</label>
                                <small class="text-red">*</small>
                                <select name="template" id="template" required class="form-control select2"
                                    style="width:100%" required autocomplete="off">
                                    <option value="">@lang('Please select')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Paramater')</label>
                                <ul class="list-relation">
                                    @foreach ($parents as $item)
                                        @if ($item->parent_id == 0 || $item->parent_id == null)
                                            <li>
                                                <label for="page-{{ $item->id }}">
                                                    <input id="page-{{ $item->id }}" name="relation[]"
                                                        {{ isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item->id) != null ? 'checked' : '' }}
                                                        type="checkbox" value="{{ $item->id }}">
                                                    {{ $item->name }}
                                                </label>
                                                <ul class="list-relation row">
                                                    @foreach ($parents as $item1)
                                                        @if ($item1->parent_id == $item->id)
                                                            <li class="col-md-6">
                                                                <label for="page-{{ $item1->id }}">
                                                                    <input id="page-{{ $item1->id }}"
                                                                        name="relation[]" type="checkbox"
                                                                        {{ isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item1->id) != null ? 'checked' : '' }}
                                                                        value="{{ $item1->id }}">
                                                                    {{ $item1->name }}
                                                                </label>
                                                                <ul class="list-relation">
                                                                    @foreach ($parents as $item2)
                                                                        @if ($item2->parent_id == $item1->id)
                                                                            <li>
                                                                                <label for="page-{{ $item2->id }}">
                                                                                    <input id="page-{{ $item2->id }}"
                                                                                        name="relation[]" type="checkbox"
                                                                                        {{ isset($relationship) && collect($relationship)->firstWhere('taxonomy_id', $item2->id) != null ? 'checked' : '' }}
                                                                                        value="{{ $item2->id }}">
                                                                                    {{ $item2->name }}
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('File') <a href="{{ url('data/images/Import_excel.png') }}"
                                        target="_blank">(@lang('Sample file structure'))</a></label>
                                <small class="text-red">*</small>
                                <input id="file" class="form-control" type="file" required name="file"
                                    placeholder="@lang('Select File')" value="">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-file-excel-o"
                                aria-hidden="true"></i> @lang('Import')</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
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
