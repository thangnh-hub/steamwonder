@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table-bordered>thead>tr>th {
            vertical-align: middle;
            text-align: center;
        }

        @media print {

            #printButton,
            .hide-print {
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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên phiếu đề xuất, mã phiếu..')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kho giao')</label>
                                <select name="warehouse_id_deliver" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_warehouse as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kho nhận')</label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_warehouse as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kỳ')</label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ $params['period'] ?? '' }}">
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
                                <th rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Mã phiếu')</th>
                                <th rowspan="2">@lang('Tên phiếu đề xuất')</th>
                                <th rowspan="2">@lang('Kỳ')</th>
                                <th colspan="6">@lang('Bên giao')</th>
                                <th colspan="5">@lang('Bên nhận')</th>
                                <th rowspan="2">@lang('Trạng thái')</th>
                                <th rowspan="2" class="hide-print">@lang('Chức năng')</th>
                            </tr>
                            <tr>
                                <th>@lang('Kho')</th>
                                <th>@lang('Tổng sản phẩm')</th>
                                <th>@lang('Tổng tiền')</th>
                                <th>@lang('Người giao')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Chức năng')</th>
                                <th>@lang('Kho')</th>
                                <th>@lang('Tổng sản phẩm')</th>
                                <th>@lang('Người nhận')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Chức năng')</th>

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
                                            <a data-toggle="tooltip" title="@lang('Chi tiết đề xuất')" target="_blank"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">{{ $row->code ?? '' }}
                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td>
                                            <a data-toggle="tooltip" title="@lang('Chi tiết đề xuất')" target="_blank"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">{{ $row->name ?? '' }}
                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td>{{ $row->period ?? '' }}</td>

                                        <td>
                                            {{ $row->warehouse_deliver->name ?? '' }}
                                        </td>
                                        <td class="text-center">{{ $row->total_product ?? '' }}</td>
                                        <td class="text-center">
                                            {{ isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : '' }}
                                        </td>
                                        <td>
                                            {{ $row->nguoi_giao->name ?? '' }}
                                        </td>
                                        <td>{{ $row->json_params->note_deliver ?? '' }}</td>
                                        <td>
                                            @if ($row->status == 'new' && $admin_auth->id == $row->staff_deliver)
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="@lang('Cập nhật')" data-original-title="@lang('Cập nhật')"
                                                    href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                    <i class="fa fa-pencil-square-o"></i> @lang('Cập nhật')
                                                </a>
                                            @endif

                                        </td>
                                        <td>
                                            {{ $row->warehouse->name ?? '' }}
                                        </td>
                                        <td class="text-center">{{ $row->total_product_entry ?? '' }}</td>
                                        <td>
                                            {{ $row->nguoi_nhan->name ?? '' }}
                                        </td>
                                        <td>{{ $row->json_params->note ?? '' }}</td>
                                        <td>
                                            @if ($row->status == 'new' && $admin_auth->id == $row->staff_entry)
                                                <a class="btn btn-sm btn-success" data-toggle="tooltip"
                                                    title="@lang('Nhận đơn')" data-original-title="@lang('Nhận đơn')"
                                                    href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                    <i class="fa fa-pencil-square-o"></i> @lang('Nhận đơn')
                                            @endif
                                        </td>
                                        <td class="text-center" style="width: 150px">
                                            {{ __($row->status) }} {{ $row->status == 'approved' ? '- Đã nhận' : '' }}
                                        </td>
                                        <td class="hide-print">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i> Xóa
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
        // $('.approve_order').click(function(e) {
        //     if (confirm('Bạn có chắc chắn muốn duyệt đơn điều chuyển này này ?')) {
        //         let _id = $(this).attr('data-id');
        //         let url = "{{ route('warehouse_order.approve') }}/";
        //         $.ajax({
        //             type: "GET",
        //             url: url,
        //             data: {
        //                 id: _id,
        //             },
        //             success: function(response) {
        //                 location.reload();
        //             },
        //             error: function(response) {
        //                 let errors = response.responseJSON.message;
        //                 alert(errors);
        //             }
        //         });
        //     }
        // });
    </script>
@endsection
