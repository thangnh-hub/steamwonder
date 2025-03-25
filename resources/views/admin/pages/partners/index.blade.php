@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i>
                @lang('Thêm mới đối tác')</a>
        </h1>

    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo: ID, Tên công ty, email, điện thoại...')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Nhóm ngành')</label>
                                <select name="field_id" id="field_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($fields as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['field_id']) && $val->id == $params['field_id'] ? 'selected' : '' }}>
                                            {{ __($val->name) }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Đối tượng tìm kiếm')</label>
                                <select name="target_search" id="target_search" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($target_search as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['target_search']) && $key == $params['target_search'] ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
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
        <div class="box">
            <div class="box-body table-responsive">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ __(session('errorMessage')) }}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ __(session('successMessage')) }}
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

                @if (!$rows->total())
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('No record found on the system!')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>@lang('Tên công ty')</th>
                                <th>@lang('Tên đường')</th>
                                <th>@lang('Số nhà')</th>
                                <th>@lang('PLZ')</th>
                                <th>@lang('Thành phố')</th>
                                <th>@lang('Nhóm ngành')</th>
                                <th>@lang('Nhóm ngành')</th>
                                <th>@lang('Người liên lạc')</th>
                                <th>@lang('Email / Điện thoại')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $user)
                                <form action="{{ route(Request::segment(2) . '.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $user->user_code }}
                                        </td>
                                        <td>
                                            {{ $user->name }}
                                        </td>
                                        <td>
                                            {{ $user->json_params->street ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->json_params->number ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->json_params->plz ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->json_params->city ?? '' }}
                                        </td>
                                        <td>
                                            {{ isset($user->json_params->target_search) && is_array($user->json_params->target_search) ? implode(', ', $user->json_params->target_search) : '' }}
                                        </td>
                                        <td>
                                            {{ $user->field_name }}
                                        </td>
                                        <td>
                                            {{ $user->json_params->contact_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $user->email }} /
                                            {{ $user->phone }}

                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="@lang('Edit')" data-original-title="@lang('Edit')"
                                                href="{{ route(Request::segment(2) . '.edit', $user->id) }}">
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

            @if ($rows->hasPages())
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
            @endif
        </div>
    </section>
@endsection
