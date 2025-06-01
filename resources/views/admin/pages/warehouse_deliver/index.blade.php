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
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('deliver_warehouse.create') }}"><i
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
            <form action="{{ route('deliver_warehouse') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Mã phiếu...')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Kỳ')</label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ $params['period'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Kho')</label>
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
                                <label>@lang('Phiếu đề xuất order')</label>
                                <select name="order_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_order as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['order_id']) && $params['order_id'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->code . '-' . $val->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('deliver_warehouse') }}">
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
                <h3 class="box-title">@lang('Danh sách phiếu xuất kho')</h3>
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
                                <th style="width:50px">@lang('STT')</th>
                                <th>@lang('Kho')</th>
                                <th style="width:100px">@lang('Mã phiếu')</th>
                                <th>@lang('Tên phiếu')</th>
                                <th style="width:100px">@lang('Kỳ')</th>
                                <th>@lang('Phiếu Order/phát sách')</th>
                                <th style="width:100px">@lang('Tổng sp')</th>
                                <th style="width:100px">@lang('Tổng tiền')</th>
                                <th>@lang('Người tạo')</th>
                                <th style="width:100px">@lang('Ngày tạo')</th>
                                <th class="hide-print">@lang('Chức năng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $row->warehouse_deliver->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->code ?? '' }}
                                    </td>
                                    <td>
                                        @if (isset($row->list_class))
                                            <ul>
                                                @foreach ($row->list_class as $i)
                                                    <li>{{ $i->name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ $row->name ?? '' }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $row->period ?? '' }}
                                    </td>
                                    <td>
                                        @if ($row->order_id != '')
                                            <a target="_blank"
                                                href="{{ route('warehouse_order_product.show', $row->order_id) }}">
                                                {{ $row->order_warehouse->code . '-' . $row->order_warehouse->name ?? '' }}
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $row->total_product ?? '' }}
                                    </td>
                                    <td>
                                        {{ isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : '' }}
                                    </td>
                                    <td>
                                        {{ $row->admin_created->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->created_at->format('d/m/Y') ?? '' }}
                                    </td>

                                    <td class="hide-print">
                                        <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="@lang('Xem chi tiết')"
                                            data-original-title="@lang('Xem chi tiết đơn')"
                                            href="{{ route('deliver_warehouse.show', $row->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
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
    <script></script>
@endsection
