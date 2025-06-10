@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection

@section('content')
    {{-- <form action="{{ route('data_dishes.import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>@lang('Chọn tệp') </label>
                    <small class="text-red">*</small>
                    <div style="display: flex" class="d-flex">
                        <input id="file" class="form-control" type="file" required name="file"
                            placeholder="@lang('Select File')" value="">
                        <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"
                                aria-hidden="true"></i> @lang('Import')</button>   
                    </div>
                </div>
            </div>
        </div>
    </form> --}}
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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại món ăn')</label>
                                <select name="dishes_type" class="form-control select2"style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_type as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['dishes_type']) && $params['dishes_type'] == $key ? 'selected' : '' }}>{{ __($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Bữa áp dụng')</label>
                                <select name="dishes_time" class="form-control select2"style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_time as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['dishes_time']) && $params['dishes_time'] == $key ? 'selected' : '' }}>{{ __($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_status as $key => $item)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>{{ __($item) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div style="display:flex;jsutify-content:space-between;">
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm  mr-10" href="{{ route(Request::segment(2) . '.index') }}">
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
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('STT')</th>
                            <th>@lang('Tên món ăn')</th>
                            <th>@lang('Mã món ăn')</th>
                            <th>@lang('Loại món ăn')</th>
                            <th>@lang('Bữa áp dụng')</th>
                            <th>@lang('Mô tả')</th>
                            <th>@lang('Trạng thái')</th>
                            <th>@lang('Thao tác')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="valign-middle">
                                <td>
                                    {{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}
                                </td>
                                <td>{{ $row->name ?? '' }}</td>
                                <td>
                                    {{ 'MA' . str_pad($row->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    {{ __($row->dishes_type ?? '') }}
                                </td>
                                <td>
                                    {{ __($row->dishes_time ?? '') }}
                                </td>
                                <td>
                                    {{ $row->description ?? "" }}
                                </td>
                                <td>@lang($row->status)</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                                       href="{{ route('dishes.edit', $row->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                
                                    <form action="{{ route('dishes.destroy', $row->id) }}" method="POST"
                                          style="display:inline-block"
                                          onsubmit="return confirm('@lang('confirm_action')')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
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

@endsection
@section('script')
    <script>
    </script>
@endsection
