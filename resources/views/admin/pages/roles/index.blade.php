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
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
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
                        @lang('No record found on the system!')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="col-md-2">@lang('Name')</th>
                                <th class="col-md-2">@lang('Description')</th>
                                <th>@lang('Người dùng áp dụng')</th>
                                <th style="width:75px">@lang('Order')</th>
                                <th style="width:100px">@lang('Updated at')</th>
                                <th style="width:100px">@lang('Status')</th>
                                <th class="col-md-1">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $row->name }}
                                        </td>
                                        <td>
                                            {{ $row->description }}
                                        </td>
                                        <td>
                                            @isset($row->admins)
                                                @if (count($row->admins) > 0)
                                                    @foreach ($row->admins as $admin)
                                                        <a class="btn btn-sm btn-primary" style="margin: 1px 0;"
                                                            href="{{ route('admins.edit', $admin->id) }}" data-toggle="tooltip"
                                                            title="@lang('Chi tiết')" data-original-title="@lang('Chi tiết')"
                                                            onclick="return openCenteredPopup(this.href)">
                                                            {{ $admin->name }}
                                                        </a>
                                                    @endforeach
                                                @else
                                                    <span class="label label-default">@lang('No user applied')</span>
                                                @endif
                                            @endisset
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
