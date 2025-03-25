@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }

        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
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
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên tài sản, mã tài sản..')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trạng thái')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Danh mục')</label>
                                <select name="warehouse_category_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_category as $key => $val)
                                        @if ($val->category_parent == '' || $val->category_parent == null)
                                            <option value="{{ $val->id }}"
                                                {{ isset($params['warehouse_category_id']) && $params['warehouse_category_id'] == $val->id ? 'selected' : '' }}>
                                                @lang($val->name ?? '')</option>
                                            @foreach ($list_category as $row_child)
                                                @if ($row_child->category_parent == $val->id)
                                                    <option value="{{ $row_child->id }}" {{ isset($params['warehouse_category_id']) && $params['warehouse_category_id'] == $row_child->id ? 'selected' : '' }}>--- {{ $row_child->code ?? '' }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Loại tài sản')</label>
                                <select name="warehouse_type" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['warehouse_type']) && $params['warehouse_type'] == $key ? 'selected' : '' }}>
                                            @lang($val ?? '')</option>
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
                {{-- Dùng khi nhập tồn kho đầu tiên của khu vực (Sản phẩm chưa có sẽ tạo mới) - Dùng chung file với phần nhập kho --}}
                <div class="pull-right" style="display: none; margin-left:15px ">
                    <input class="form-control" type="file" name="files" id="fileImportTS"
                        placeholder="@lang('Select File')">
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="importFile('fileImportTS','{{ route('warehouse_product.import_asset') }}')">
                        <i class="fa fa-file-excel-o"></i>
                        @lang('Import Tài sản')</button>
                </div>
                {{-- thêm mới tài sản bằng excel (Dùng 1 lần) --}}
                {{-- <div class="pull-right" style="display: none; margin-left:15px ">
                    <input class="form-control" type="file" name="files" id="fileImport"
                        placeholder="@lang('Select File')">
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="importFile('fileImport','{{ route('warehouse_product.import_product') }}')">
                        <i class="fa fa-file-excel-o"></i>
                        @lang('Import sản phẩm')</button>
                </div> --}}
            </div>
            <div class="box-body table-responsive">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('successMessage') !!}
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
                                <th>@lang('Mã sản phẩm')</th>
                                <th>@lang('Tên sản phẩm')</th>
                                <th>@lang('Loại sản phẩm')</th>
                                <th>@lang('Danh mục')</th>
                                <th>@lang('Giá')</th>
                                <th>@lang('Quy cách')</th>
                                <th>@lang('Xuất xứ')</th>
                                <th>@lang('Hãng SX')</th>
                                <th>@lang('Bảo hành')</th>
                                <th>@lang('Trạng thái')</th>
                                <th class="hide-print">@lang('Chức năng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                    onsubmit="return confirm('@lang('confirm_action')')">
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            {{ $row->code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ __($row->warehouse_type ?? '') }}
                                        </td>
                                        <td>
                                            {{ $row->category_product->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') . ' đ' : '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->specification ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->origin ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->manufacturer ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->warranty ?? '' }}
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
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix hide-print">
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
        function importFile(_file, _url) {
            var formData = new FormData();
            var file = $('#' + _file)[0].files[0];
            console.log(file);

            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                url: _url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
@endsection
