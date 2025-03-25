@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>

        @media print {
            #printButton, .hide-print{
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
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
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default hide-print">

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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên danh mục, mã danh mục..')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Trạng thái')</label>
                                <select name="status" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key=> $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
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
                    <table class="table table-hover table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã Danh mục')</th>
                                <th>@lang('Tên Danh mục')</th>
                                <th>@lang('Loại')</th>
                                <th>@lang('Trạng thái')</th>
                                <th class="hide-print">@lang('Chức năng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $stt = 1; 
                            @endphp
                            @foreach ($rows as $row)
                                @if($row->category_parent==""||$row->category_parent==NULL)
                                    <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('@lang('confirm_action')')">
                                        <tr class="valign-middle">
                                            <td>
                                                {{ $stt++}}
                                            </td>
                                            <td>
                                                {{$row->code??"" }}
                                            </td>
                                            <td>
                                                {{$row->name??"" }}
                                            </td>
                                            <td>
                                                {{ __($row->type) }}
                                            </td>
                                            <td>
                                                {{ __($row->status) }}
                                            </td>
                                            <td class="hide-print">
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="@lang('Update')" data-original-title="@lang('Update')"
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
                                    @foreach ($rows as $row_child)
                                        @if($row_child->category_parent==$row->id)
                                        <form action="{{ route(Request::segment(2) . '.destroy', $row_child->id) }}" method="POST"
                                            onsubmit="return confirm('@lang('confirm_action')')">
                                            <tr>
                                                <td>
                                                    {{ $stt++ }} 
                                                </td>

                                                <td>
                                                    --- {{$row_child->code??"" }}
                                                </td>

                                                <td>
                                                    --- {{$row_child->name??"" }}
                                                </td>
                                                <td>
                                                    {{ __($row_child->type) }}
                                                </td>
                                                <td>
                                                    {{ __($row_child->status) }}
                                                </td>

                                                <td class="hide-print">
                                                    <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                        title="@lang('Update')" data-original-title="@lang('Update')"
                                                        href="{{ route(Request::segment(2) . '.edit', $row_child->id) }}">
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
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
           
        </div>
    </section>
@endsection
@section('script')
    <script>

    </script>
@endsection
