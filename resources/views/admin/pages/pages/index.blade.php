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
                <i class="fa fa-plus"></i>
                @lang('Add')
            </a>
        </h1>
    </section>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Page list')</h3>
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
                        @lang('No record found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Route Name')</th>
                                <th>
                                    @lang('Url customize')
                                    <small><i class="fa fa-coffee text-red" aria-hidden="true"></i></small>
                                </th>
                                <th>@lang('Order')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $routes = [];
                                foreach (App\Consts::ROUTE_NAME as $item) {
                                    $routes[$item['name']] = $item['title'];
                                    $routes['show_route'][$item['name']] = isset($item['show_route']) && $item['show_route'] ? $item['show_route'] : false;
                                }
                            @endphp

                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $row->json_params->title->$lang ?? $row->title }}
                                        </td>
                                        <td>
                                            {{ $routes[$row->route_name] ?? '' }}
                                        </td>
                                        @php
                                            $url_mapping = route('frontend.page', ['taxonomy' => $row->alias ?? '']);
                                        @endphp
                                        <td>
                                            @if (isset($routes['show_route'][$row->route_name]) && $routes['show_route'][$row->route_name])
                                                <a href="{{ $url_mapping }}" target="_blank"
                                                    rel="noopener noreferrer">{{ $url_mapping }}</a>
                                                <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip"
                                                    title="@lang('Link')" data-original-title="@lang('Link')">
                                                    <span class="btn btn-flat btn-xs btn-info">
                                                        <i class="fa fa-external-link"></i>
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->iorder }}
                                        </td>
                                        <td>
                                            @lang($row->status)
                                        </td>
                                        <td>
                                            {{ $row->updated_at }}
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Edit')" data-original-title="@lang('Edit')"
                                                href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
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
    </section>
@endsection
