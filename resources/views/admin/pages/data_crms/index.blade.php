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
                                <label>@lang('Khu vực')</label>
                                <select name="area_id" id="area_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($list_area as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2"style="width: 100%;">
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
                                    <button type="button" data-toggle="modal" data-target="#create_crmdata"
                                    class="btn btn-success btn-sm">@lang('Import Excel')</button>
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
                                <th>@lang('First Name')</th>
                                <th>@lang('Last Name')</th>
                                <th>@lang('Số điện thoại')</th>
                                <th>@lang('Email')</th>  
                                <th>@lang('Địa chỉ')</th>
                                <th>@lang('Khu vực')</th>
                                <th>@lang('CBTS')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->iteration + ($rows->currentPage() - 1) * $rows->perPage() }}
                                        </td>
                                        <td>
                                            <strong
                                                style="font-size: 14px">{{  $row->first_name ?? "" }}</strong>
                                        </td>
                                        <td>
                                            <strong
                                                style="font-size: 14px">{{  $row->last_name ?? "" }}</strong>
                                        </td>
                                        <td>
                                            {{ $row->phone ?? "" }}
                                        </td>
                                        <td>
                                            {{ $row->email ?? "" }}
                                        </td>

                                        <td>
                                            {{ $row->address ?? "" }}
                                        </td>

                                        <td>
                                            {{ $row->area->name ?? "" }}
                                        </td>

                                        <td>
                                            {{ $row->admission->name ?? "" }}
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
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <a class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                title="@lang('Update')" data-original-title="@lang('Chi tiết')"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                <i class="fa fa-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                </form>
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

    <div id="create_crmdata" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Import biến động</h4>
                </div>
                <form action="{{ route('data_crm.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Chọn tệp') <a href="{{ url('themes\admin\img\data.xlsx') }}" target="_blank">(@lang('Minh họa file excel'))</a></label>
                                <small class="text-red">*</small>
                                <label class="text-danger">Lưu ý nếu không điền mã CBTS trong file excel thì hệ thống sẽ mặc định CBTS là bạn.</label>
                                <div style="display: flex" class="d-flex">
                                    <input id="file" class="form-control" type="file" required name="file"
                                        placeholder="@lang('Select File')" value="">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-file-excel-o"
                                            aria-hidden="true"></i> @lang('Import')</button>   
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
           
        });
    </script>
@endsection
