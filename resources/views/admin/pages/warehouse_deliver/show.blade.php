@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)

        </h1>
    </section> --}}

    <!-- Main content -->
    <section class="content">
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
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            @lang($module_name)
                        </h3>
                        <a class="btn btn-sm btn-primary pull-right hide-print" href="{{ route('deliver_warehouse') }}">
                            <i class="fa fa-bars"></i> @lang('Danh sách phiếu')
                        </a>
                        <button class="btn btn-sm btn-warning pull-right hide-print mr-10" onclick="window.print()"><i
                                class="fa fa-print"></i>
                            @lang('In phiếu')</button>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    @lang('Cở sở'):
                                    {{ $detail->area->name ?? ($detail->warehouse->area->name ?? ($detail->warehouse_deliver->area->name ?? '')) }}
                                    / {{ $detail->warehouse->name ?? ($detail->warehouse_deliver->name ?? '') }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Kỳ'): {{ $detail->period ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Tên phiếu xuất'): {{ $detail->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Mã phiếu xuất'): {{ $detail->code ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày xuất'):
                                    {{ \Carbon\Carbon::parse($detail->day_deliver)->format('d/m/Y') ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày nhận'):
                                    {{ \Carbon\Carbon::parse($detail->day_entry ?? $detail->day_deliver)->format('d/m/Y') ?? '' }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>
                                    @lang('Xuất theo'):
                                    @if ($detail->order_id != '')
                                        <a target="_blank"
                                            href="{{ route('warehouse_order_product.show', $detail->order_id) }}">
                                            {{ $detail->order_warehouse->code . '-' . $detail->order_warehouse->name ?? '' }}
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @elseif(isset($detail->list_class))
                                        <a href="{{ route('book_distribution.detail_history', $detail->id) }}"
                                            target="_blank">
                                            @lang('Phiếu phát sách') -
                                            @foreach ($detail->list_class as $i)
                                                {{ $i->name }};
                                            @endforeach
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endif
                                </p>
                            </div>
                            @if (isset($detail->department->name) || isset($detail->order_warehouse->department->name))
                                <div class="col-xs-6">
                                    <p>
                                        @lang('Phòng ban order'):
                                        {{ $detail->department->name ?? ($detail->order_warehouse->department->name ?? '') }}
                                    </p>
                                </div>
                            @endif

                            @isset($detail->nguoi_giao->name)
                                <div class="col-xs-6">
                                    <p>@lang('Người giao'): {{ $detail->nguoi_giao->name ?? '' }}</p>
                                </div>
                            @endisset
                            @isset($detail->nguoi_nhan->name)
                                <div class="col-xs-6">
                                    <p>@lang('Người nhận'): {{ $detail->nguoi_nhan->name ?? '' }}</p>
                                </div>
                            @endisset
                            <div class="col-xs-6">
                                <p>@lang('Ngày tạo phiếu'):
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom: 10px">@lang('Danh sách sản phẩm xuất kho')</h4>

                                <table id="myTable" class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px">@lang('STT')</th>
                                            <th class="text-center" style="width:120px">@lang('Mã sản phẩm')</th>
                                            <th class="text-center">@lang('Sản phẩm')</th>
                                            <th class="text-center" style="width:150px">@lang('Loại tài sản')</th>
                                            <th class="text-center" style="width:75px">@lang('ĐVT')</th>
                                            <th class="text-center" style="width:75px">@lang('Số lượng')</th>
                                            <th class="text-center" style="width:100px">@lang('Đơn giá')
                                            </th>
                                            <th class="text-center" style="width:100px">@lang('Tổng tiền')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order">
                                        @if ($entry_details->count() > 0)
                                            @foreach ($entry_details as $entry_detail)
                                                <tr class="valign-middle">
                                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->product->code ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $entry_detail->product->name ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ __($entry_detail->product->warehouse_type ?? '') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->product->unit ?? '' }}
                                                    <td class="text-center">
                                                        {{ $entry_detail->quantity ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ isset($entry_detail->price) && is_numeric($entry_detail->price) ? number_format($entry_detail->price, 0, ',', '.') : '' }}
                                                    </td>
                                                    <td>
                                                        {{ isset($entry_detail->subtotal_money) && is_numeric($entry_detail->subtotal_money) ? number_format($entry_detail->subtotal_money, 0, ',', '.') : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tr>
                                        <td colspan="7">
                                            <strong class="pull-right">TỔNG TIỀN:</strong>
                                        </td>
                                        <td>
                                            <strong
                                                class="total_money">{{ isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . ' đ' : '' }}</strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @if ($list_assets->count() > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="box-title" style="padding-bottom: 10px">@lang('Danh sách tài sản/CCDC đã xuất tương ứng') <span
                                            class="change_text_warehouse"></span></h4>

                                    <table class="table table-hover table-bordered sticky">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:50px">@lang('STT')</th>
                                                <th class="text-center" style="width:150px">@lang('Mã Tài Sản')</th>
                                                <th class="text-center">@lang('Tên tài sản')</th>
                                                <th class="text-center" style="width:150px">@lang('Loại tài sản')</th>
                                                <th class="text-center" style="width:75px">@lang('ĐVT')</th>
                                                <th class="text-center" style="width:75px">@lang('Số lượng')</th>
                                                <th class="text-center" style="width:100px">@lang('Đơn giá')</th>
                                                <th class="text-center" style="width:150px">@lang('Phòng ban')</th>
                                                <th class="text-center" style="width:150px">@lang('Vị trí')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">
                                            @foreach ($list_assets as $list_asset)
                                                <tr class="valign-middle">
                                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                                    <td class="text-center">
                                                        {{ $list_asset->code ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $list_asset->name ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ __($list_asset->product->warehouse_type) ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->product->unit ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $list_asset->quantity ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ isset($list_asset->price) && is_numeric($list_asset->price) ? number_format($list_asset->price, 0, ',', '.') : '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $list_asset->department->name ?? __('Chưa cập nhật') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $list_asset->position->name ?? __('Chưa cập nhật') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12 show-print">
                            <div class="col-xs-4 text-center text-bold text-uppercase">
                                @lang('Kế toán')
                            </div>
                            <div class="col-xs-4 text-center text-bold text-uppercase">
                                @lang('Người giao')
                            </div>
                            <div class="col-xs-4 text-center text-bold text-uppercase">
                                @lang('Người nhận')
                            </div>
                        </div>
                    </div>
                    <div class="box-footer hide-print hidden">
                        <a class="btn btn-success" href="{{ route('deliver_warehouse') }}">Danh sách</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script></script>
@endsection
