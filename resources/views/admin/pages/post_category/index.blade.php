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
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}">
                <i class="fa fa-plus"></i> @lang('Add')
            </a>
        </h1>
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

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="{{ __('Title') . '...' }}" value="{{ $params['keyword'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Taxonomy')</label>
                                <select name="taxonomy" id="taxonomy" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach (App\Consts::TAXONOMY as $key => $value)
                                        @if ($key == $params['taxonomy'])
                                            <option value="{{ $key }}"
                                                {{ isset($params['taxonomy']) && $key == $params['taxonomy'] ? 'selected' : '' }}>
                                                {{ __($value) }}</option>
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
                                    @foreach (App\Consts::TAXONOMY_STATUS as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
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
                                <a class="text-primary pull-right"
                                    href="{{ route(Request::segment(2) . '.index') }}" style="padding-left: 15px">
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
                                <th>@lang('Taxonomy')</th>
                                <th>@lang('Link')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rows)
                                @foreach ($rows as $row)
                                    @if ($row->parent_id == 0 || $row->parent_id == null)
                                        <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                            method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                            <tr class="valign-middle">
                                                <td>
                                                    <strong style="font-size: 14px;">{{ $row->json_params->name->$lang??$row->name }}</strong>
                                                </td>
                                                <td>
                                                    {{ __(App\Consts::TAXONOMY[$row->taxonomy] ?? '') }}
                                                </td>
                                                @php
                                                    $url_mapping = route('frontend.page', ['taxonomy' => App\Consts::ROUTE_TAXONOMY['post'], 'alias' => $row->alias ?? '']);
                                                @endphp
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

                                        @foreach ($rows as $sub)
                                            @if ($sub->parent_id == $row->id)
                                                <form action="{{ route(Request::segment(2) . '.destroy', $sub->id) }}"
                                                    method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                                    <tr class="valign-middle bg-gray-light">

                                                        <td>
                                                            - - - - {{ $sub->json_params->name->$lang??$sub->name }}
                                                        </td>
                                                        <td>
                                                            {{ __(App\Consts::TAXONOMY[$sub->taxonomy] ?? '') }}
                                                        </td>
                                                        @php
                                                            $url_mapping = route('frontend.page', ['taxonomy' => App\Consts::ROUTE_TAXONOMY['post'], 'alias' => $sub->alias ?? '']);
                                                        @endphp
                                                        <td>
                                                            <a href="{{ $url_mapping }}" target="_blank"
                                                                rel="noopener noreferrer">{{ $url_mapping }}</a>
                                                            <a target="_new" href="{{ $url_mapping }}"
                                                                data-toggle="tooltip" title="@lang('Link')"
                                                                data-original-title="@lang('Link')">
                                                                <span class="btn btn-flat btn-xs btn-info">
                                                                    <i class="fa fa-external-link"></i>
                                                                </span>
                                                            </a>
                                                        </td>

                                                        <td>
                                                            {{ $sub->iorder != '' ? '- - - - ' . $sub->iorder : '' }}
                                                        </td>
                                                        <td>
                                                            {{ $sub->updated_at }}
                                                        </td>
                                                        <td>
                                                            @lang($sub->status)
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                                title="@lang('Update')"
                                                                data-original-title="@lang('Update')"
                                                                href="{{ route(Request::segment(2) . '.edit', $sub->id) }}">
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

                                                @foreach ($rows as $sub_child)
                                                    @if ($sub_child->parent_id == $sub->id)
                                                        <form
                                                            action="{{ route(Request::segment(2) . '.destroy', $sub_child->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('@lang('confirm_action')')">
                                                            <tr class="valign-middle bg-gray-light">
                                                                <td>
                                                                    - - - - - - {{ $sub_child->json_params->name->$lang??$sub_child->name }}
                                                                </td>
                                                                <td>
                                                                    {{ __(App\Consts::TAXONOMY[$sub_child->taxonomy] ?? '') }}
                                                                </td>
                                                                @php
                                                                    $url_mapping = route('frontend.page', ['taxonomy' => App\Consts::ROUTE_TAXONOMY['post'], 'alias' => $sub_child->alias ?? '']);
                                                                @endphp
                                                                <td>
                                                                    <a href="{{ $url_mapping }}" target="_blank"
                                                                        rel="noopener noreferrer">{{ $url_mapping }}</a>
                                                                    <a target="_new" href="{{ $url_mapping }}"
                                                                        data-toggle="tooltip" title="@lang('Link')"
                                                                        data-original-title="@lang('Link')">
                                                                        <span class="btn btn-flat btn-xs btn-info">
                                                                            <i class="fa fa-external-link"></i>
                                                                        </span>
                                                                    </a>
                                                                </td>

                                                                <td>
                                                                    {{ $sub_child->iorder != '' ? '- - - - - - ' . $sub_child->iorder : '' }}
                                                                </td>
                                                                <td>
                                                                    {{ $sub_child->updated_at }}
                                                                </td>
                                                                <td>
                                                                    @lang($sub_child->status)
                                                                </td>
                                                                <td>
                                                                    <a class="btn btn-sm btn-warning"
                                                                        data-toggle="tooltip" title="@lang('Update')"
                                                                        data-original-title="@lang('Update')"
                                                                        href="{{ route(Request::segment(2) . '.edit', $sub_child->id) }}">
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
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </section>
@endsection
