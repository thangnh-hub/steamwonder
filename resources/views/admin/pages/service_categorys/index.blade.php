@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route($routeDefault . '.create') }}">
                <i class="fa fa-plus"></i> @lang('Add')
            </a>
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route($routeDefault . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword')</label>
                                <input type="text" class="form-control" name="keyword"
                                    placeholder="@lang('keyword_note')"
                                    value="{{ $params['keyword'] ?? '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            @lang($item)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div style="display:flex; gap:5px;">
                                    <button type="submit" class="btn btn-primary btn-sm">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route($routeDefault . '.index') }}">
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
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body table-responsive">
                @if ($rows->count() === 0)
                    <div class="alert alert-warning">@lang('not_found')</div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>{{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}</td>
                                    <td>{{ $row->name ?? "" }}</td>
                                    <td>{{ $row->iorder ??"" }}</td>
                                    <td>@lang($row->status)</td>
                                    <td>
                                        <a class="btn btn-sm btn-warning" href="{{ route($routeDefault . '.edit', $row->id) }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <form action="{{ route($routeDefault . '.destroy', $row->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('@lang('confirm_action')')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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
        </div>
    </section>
@endsection
