@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        .table-bordered>thead:first-child>tr:first-child>th {
            text-align: center;
            vertical-align: middle;
        }
        .text-center{
            text-align: center !important;
        }
        @media print {
            #printButton,
            .hide-print {
                display: none !important;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endsection


@section('content')
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
        <div class="box">
            <div class="show-print">
                <h3 class="box-title text-uppercase text-center w-100">Cộng hòa xã hội chủ nghĩa Việt Nam</h3>
                <h3 class="box-title text-center w-100">Độc lập - Tự do - Hạnh phúc</h3>

                <p class="text-right"><i>Hà Nội, ngày {{ date('d'), time() }} Tháng {{ date('m'), time() }} Năm
                    {{ date('Y'), time() }}</i></p>

                <div class="text-center">
                    <h3 class="text-uppercase mt-15">Đề nghị thanh toán</h3>
                    <p class="fw-bold">Kính gửi: <span class="text-uppercase">Ban giám đốc</span></p>
                </div>
            </div>
            <div class="box-header  text-center">
                <h4 class="box-title text-uppercase text-bold hide-print">{{ $module_name }}</h4>
                <button onclick="window.print()" class="btn btn-sm btn-warning pull-right hide-print mr-10"><i class="fa fa-print"></i>
                    @lang('In phiếu đề nghị thanh toán')</button>
            </div>
            
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <p>@lang('Họ tên người thanh toán'): <strong>{{ $detail->user->name ?? '' }}</strong></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Phòng ban/Bộ phận'): <strong>{{ $detail->department->name ?? '' }}</strong></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Nội dung'): <strong>{{ $detail->content ?? '' }}</strong></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Số tài khoản'): <strong>{{ $detail->qr_number ?? '' }}</strong></p>
                    </div>
                    
                    <div class="col-md-12">
                        <h4 style="padding-bottom: 10px;margin-top:20px" class="hide-print">@lang('Thông tin phiếu nhập kho'): <a target="_blank" href="{{ route('entry_warehouse.show', $detail->entry_id) }}">{{ $detail->entry->name??"" }}</a> </h4>
                        <table id="myTable" class="table  table-bordered ">
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
                                                <span class="subtotal-text">{{ isset($entry_detail->subtotal_money) && is_numeric($entry_detail->subtotal_money) ? number_format($entry_detail->subtotal_money, 0, ',', '.') : '' }} đ</span>
                                            </td>
                                            <td class="valign-middle">
                                                <input type="hidden" value="{{ $entry_detail->json_params->vat_money ?? 0 }}"  
                                                    type="number" class="vat_value form-control" placeholder="VAT (%)">
                                                {{ $entry_detail->json_params->vat_money ?? 0 }} %
                                            </td>
                                            <td>
                                                <p class="vat_money"></p>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                
                                <tr>
                                    <td style="vertical-align: middle;" class="text-bold " colspan="8">TỔNG CỘNG TRƯỚC THUẾ:</td>
                                    <td colspan="3" class="text-bold text-center">
                                        <p>{{ isset($detail->entry->total_money) && is_numeric($detail->entry->total_money) ? number_format($detail->entry->total_money, 0, ',', '.') . '' : '' }} VNĐ</p>   
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: middle;" class="text-bold " colspan="8">VAT:</td>
                                    <td colspan="3" class="text-bold text-center">
                                        <p class="total_vat"></p> 
                                    </td>
                                </tr>
                             
                                <tr>
                                    <td style="vertical-align: middle;" class="text-bold " colspan="8">TỔNG TIỀN:</td>
                                    <td colspan="3" class="text-bold text-center" >
                                        {{ isset($detail->total_money_vnd) && is_numeric($detail->total_money_vnd) ? number_format($detail->total_money_vnd, 0, ',', '.') : '' }} VNĐ 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div  class="col-md-12 mt-15 mb-15 show-print">
                        <button type="button" class= "btn btn-danger btn-sm pull-right">
                            {{ $detail->entry->order_warehouse->admin_approved->name ?? '' }} - @lang('Đã duyệt phiếu mua sắm') : {{ $detail->entry->order_warehouse->name ?? '' }}
                        </button>
                    </div>

                    <div class="col-md-6"><p>Số tiền đã tạm ứng (VNĐ):</p></div>
                    <div class="col-md-6"><p><strong>{{ isset($detail->total_money_vnd_advance) && is_numeric($detail->total_money_vnd_advance) ? number_format($detail->total_money_vnd_advance, 0, ',', '.') : '0' }} VNĐ</strong></p></div>

                    <div class="col-md-6"><p>Số tiền cần thanh toán (VNĐ):</p></div>
                    <div class="col-md-6"><p><strong style="color: red">{{ isset($total_money_vnd_finally) && is_numeric($total_money_vnd_finally) ? number_format($total_money_vnd_finally, 0, ',', '.') : '0' }} VNĐ</strong></p></div>

                    <div class="col-md-6"><p>Số tiền cần thanh toán (VNĐ) bằng chữ:</p> </div>
                    <div class="col-md-6"><p class="text-capitalize"><strong>{{ $total_money_vnd_finally_word ?? 0 }} đồng</strong></p> </div>

                    <div class="col-md-12 show-print" style="margin-top: 30px">
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            @lang('Ban Kiểm Soát')
                        </div>
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            @lang('Kế toán')
                        </div>
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            @lang('Hành chính')
                        </div>
                        <div class="col-xs-3 text-center text-bold text-uppercase">
                            @lang('Giám đốc CN')
                        </div>
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            @lang('Người đề nghị')
                        </div>
                    </div>
                    @if ($detail->status == 'paid')
                    <div class="col-md-12 show-print" style="margin-top: 100px">
                        <div class="col-xs-2 text-center text-bold text-uppercase">
                            {{ $detail->approved_admin->name??"" }}  @lang('Đã Duyệt')
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="box-footer hide-print">
                <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
                    
                @if ($detail->status == 'new')
                    <button data-id="{{ $detail->id }}" type="button"
                        class="approve_payment btn btn-info btn-sm pull-right">
                        <i class="fa fa-money"></i> @lang('Duyệt')
                    </button>
                @else
                    <button type="button" class= "btn btn-danger btn-sm pull-right">
                        <i class="fa fa-money"></i> {{ $detail->approved_admin->name??"" }}  @lang('Đã Duyệt')
                    </button>
                @endif    
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
    }

    $('#myTable').on('input', function() {
        calculateTotal();
    });

    $(document).ready(function() {
        calculateTotal();
    });
        $('.approve_payment').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn duyệt đề nghị thanh toán này ?')) {
                let _id = $(this).attr('data-id');
                let url = "{{ route('payment.approve') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
    </script>
@endsection
