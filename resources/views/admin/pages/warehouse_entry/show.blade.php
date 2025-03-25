@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
       .d-flex {
            display: flex;
        }
    </style>
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
                        <a class="btn btn-sm btn-primary pull-right hide-print" href="{{ route('entry_warehouse') }}">
                            <i class="fa fa-bars"></i> @lang('Danh sách phiếu')
                        </a>
                        <button class="btn btn-sm btn-warning pull-right hide-print mr-10" data-toggle="modal" data-target=".modal_payment_request_entry"><i
                                class="fa fa-plus"></i>
                            @lang('Tạo phiếu thanh toán')
                        </button>
                        <a class="btn btn-sm btn-success pull-right hide-print mr-10" href="">
                            <i class="fa fa-refresh"></i> @lang('Làm mới dữ liệu')
                        </a>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    @lang('Cở sở'):
                                    {{ $detail->area->name ?? ($detail->warehouse->area->name ?? '') }}
                                    / {{ $detail->warehouse->name ?? '' }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Kỳ'): {{ $detail->period ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Tên phiếu'): {{ $detail->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Mã phiếu'): {{ $detail->code ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Người tạo phiếu'): {{ $detail->admin_created->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày tạo phiếu'):
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                            </div>

                            @if (isset($detail->order_warehouse))
                                <div class="col-xs-6">
                                    <p>
                                        @lang('Nhập theo phiếu'):
                                        <a target="_blank"
                                            href="{{ route('warehouse_order_product_buy.show', $detail->order_id) }}">
                                            {{ $detail->order_warehouse->code . '-' . $detail->order_warehouse->name ?? '' }}
                                            <i class="fa fa-eye hide-print"></i>
                                        </a>
                                    </p>
                                </div>
                            @endif

                            @if (isset($detail->json_params->note) && $detail->json_params->note != '')
                                <div class="col-xs-6">
                                    <p>@lang('Ghi chú'): {{ $detail->json_params->note ?? '' }}</p>
                                </div>
                            @endif

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom:10px">@lang('Danh sách sản phẩm nhập kho')</h4>
                                <table id="myTable" class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr class="valign-middle">
                                            <th class="text-center" style="width:50px">@lang('STT')</th>
                                            <th class="text-center" style="width:120px">@lang('Mã sản phẩm')</th>
                                            <th class="text-center">@lang('Sản phẩm')</th>
                                            <th class="text-center" style="width:120px">@lang('Loại tài sản')</th>
                                            <th class="text-center" style="width:75px">@lang('ĐVT')</th>
                                            <th class="text-center" style="width:75px">@lang('Số lượng')</th>
                                            <th class="text-center" style="width:100px">@lang('Đơn giá') <br /> (Dự kiến)
                                            </th>
                                            <th class="text-center" style="width:100px">@lang('Đơn giá') <br /> (Thực tế)
                                            </th>
                                            <th class="text-center" style="width:100px">@lang('Thành tiền')</th>
                                            <th class="text-center" style="width:100px">@lang('VAT (%)')</th>
                                            <th class="text-center" style="width:100px">@lang('Tiền thuế GTGT')</th>
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
                                                        {{ __($entry_detail->product->warehouse_type) ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->product->unit ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->quantity ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ isset($entry_detail->product->price) && is_numeric($entry_detail->product->price) ? number_format($entry_detail->product->price, 0, ',', '.') : '' }}
                                                    </td>
                                                    <td>
                                                        {{ isset($entry_detail->price) && is_numeric($entry_detail->price) ? number_format($entry_detail->price, 0, ',', '.') : '' }}
                                                    </td>
                                                    
                                                    <td>
                                                        <input type="hidden" class="subtotal-input" value="{{ $entry_detail->subtotal_money ?? 0 }}">
                                                        <span class="subtotal-text">{{ isset($entry_detail->subtotal_money) && is_numeric($entry_detail->subtotal_money) ? number_format($entry_detail->subtotal_money, 0, ',', '.') : '' }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <input data-detail-id="{{ $entry_detail->id }}" value="{{ $entry_detail->json_params->vat_money ?? 0 }}"  
                                                                type="number" class="vat_value form-control" placeholder="VAT (%)">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="vat_money"></p>
                                                    </td>
                                                    
                                                </tr>
                                            @endforeach
                                        @endif
                                        <tr>
                                            <td colspan="8"><strong class="pull-right">Tổng : </strong></td>
                                            <td colspan="2">
                                                <input type="hidden" class="total-input" value="{{ $detail->total_money ?? 0 }}">
                                                <strong>{{ isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . '' : '' }}</strong>
                                            </td>
                                            <td ><strong class="total_vat">0</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"><strong class="pull-right">Tổng tiền: </strong></td>
                                            <td colspan="3">
                                                <strong class="total_money">{{ isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . '' : '' }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            @if ($list_assets->count() > 0)
                                <div class="col-md-12">
                                    <h4 class="box-title" style="padding-bottom:10px">@lang('Danh sách tài sản tự động sinh mã theo phiếu nhập kho này')</h4>

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
                                        <tbody class="tbody-order">

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
                            @endif

                            <div class="col-md-12 show-print">
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    @lang('Phòng HCNS')
                                </div>
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    @lang('Thủ kho')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer hide-print">
                        <a class="btn btn-sm btn-success pull-right" target="_blank"
                            href="{{ route('warehouse_asset.index', ['entry_id' => $detail->id]) }}">
                            <i class="fa fa-bank"></i> @lang('Cập nhật thông tin lưu kho tài sản')
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modal_payment_request_entry" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Thông tin phiếu thanh toán
                            </h4>
                        </div>
                        <form  action="{{ route('payment_request_by_entry_store' )}}" method="POST" >
                            @csrf
                            <input type="text" name="entry_id" value="{{ $detail->id }}" hidden>
                            <input type="hidden" name="total_money_vnd" class="total_money_vnd" value="{{ $detail->total_money }}" >
                            <input type="text" name="json_params[total_money_vnd_without_vat]" value="{{ $detail->total_money ?? 0 }}" hidden>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="box box-primary">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Người đề nghị thanh toán') </label>
                                                    <input type="text" class="form-control"
                                                    placeholder="@lang('Name')" disabled value="{{ $admin->name ??"" }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Phòng ban') </label>
                                                    <select style="width:100%" class="form-control select2" name="dep_id">
                                                        @foreach ($department as $dep)
                                                            <option 
                                                            {{ isset($admin->department_id) && $admin->department_id == $dep->id ? "selected" : "" }} 
                                                            value="{{ $dep->id }}">{{ $dep->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số tài khoản') </label>
                                                    <input name="qr_number" type="text" class="form-control"
                                                    placeholder="@lang('Số tài khoản..')" value="{{ old('qr_number') }}">
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Số tiền VNĐ đã tạm ứng')</label>
                                                    <div class="d-flex">
                                                        <input value="{{ old('total_money_vnd_advance') ?? 0 }}" name="total_money_vnd_advance" type="number" class="form-control" placeholder="@lang('Số tiền vnđ đã tạm ứng..')">
                                                        <input type="text" class="form-control form-control-sm" style="max-width: 70px;" value="VNĐ" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Nội dung') <small class="text-red">*</small></label>
                                                    <textarea class="form-control" name="content"
                                                    placeholder="@lang('Nội dung đề nghị')" required>Đề nghị thanh toán cho phiếu nhập kho {{ $detail->name??"" }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary print_payment">
                                    @lang('Tạo đề nghị thanh toán')
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    @lang('Đóng')
                                </button>
                            </div>
                        </form>    
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
<script>
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '';
    }

    function calculateTotal() {
        let totalSubtotal = $('.total-input').val() || 0;
        let totalVat = 0;

        $('#myTable tr').each(function() {
            const subtotal = Number($(this).find('.subtotal-input').val())||0;
            const vatPercent = Number($(this).find('.vat_value').val())||0;
            const vatMoney = Number((subtotal * vatPercent) / 100);

            totalVat +=vatMoney;

            $(this).find('.vat_money').text(formatCurrency(vatMoney));
        });
        let totalAfterTax = Number(totalSubtotal)  + Number(totalVat) ;

        $('.total_vat').text(formatCurrency(totalVat));
        $('.total_money').text(formatCurrency(totalAfterTax));
        $('.total_money_vnd').val(totalAfterTax);
    }

    $('#myTable').on('input', function() {
        calculateTotal();
    });

    $(document).ready(function() {
        calculateTotal();
    });

    $('.vat_value').change(function (e) { 
        var _id=$(this).attr('data-detail-id');
        var _value=$(this).val();
        let _url = "{{ route('ajax_update_vat_entry_detail') }}";
        $.ajax({
            type: "GET",
            url: _url,
            data: {
                id: _id,
                vat_money: _value,
            },
            success: function(response) {
                
            },
            error: function(response) {
                let errors = response.responseJSON.message;
                alert(errors);
            }
        });
    });
    
</script>

@endsection
