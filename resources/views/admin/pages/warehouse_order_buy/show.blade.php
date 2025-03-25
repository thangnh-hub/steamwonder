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
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section> --}}
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
            <div class="box-header with-border text-center">
                <h3 class="box-title text-uppercase">{{ $module_name }}</h3>
                <a class="btn btn-sm btn-success pull-right hide-print" href="{{ route(Request::segment(2) . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
                <button class="btn btn-sm btn-warning pull-right hide-print mr-10" onclick="window.print()"><i
                        class="fa fa-print"></i>
                    @lang('In phiếu mua sắm')</button>
                {{-- @if ($detail->status != 'not approved')
                    <button class="btn btn-sm btn-warning pull-right hide-print mr-10"
                        data-toggle="modal"data-target=".modal_payment_request"><i class="fa fa-print"></i>
                        @lang('In phiếu đề nghị thanh toán')</button>
                @endif --}}
                @if ($detail->status == 'approved')
                    <a href="{{ route('entry_warehouse.create', ['order_id' => $detail->id]) }}" target="_blank"
                        rel="noopener noreferrer" class="btn btn-sm btn-primary mr-10 pull-right hide-print">
                        Nhập kho
                        <i class="fa fa-sign-in"></i>
                    </a>
                @endif
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
                        <p>@lang('Phòng đề xuất'): {{ $detail->department->name ?? '' }}</p>
                    </div>
                    <div class="col-xs-6">
                        <p>@lang('Người đề xuất'): {{ $detail->staff->name ?? '' }}</p>
                    </div>
                    <div class="col-xs-6">
                        <p>@lang('Ngày đề xuất'): {{ \Carbon\Carbon::parse($detail->day_create)->format('d/m/Y') ?? '' }}</p>
                    </div>
                    <div class="col-xs-6">
                        <p>@lang('Trạng thái'): {{ __($detail->status) }}</p>
                    </div>
                    <div class="col-xs-12">
                        <p>{{ $detail->json_params->note ?? '' }}</p>
                    </div>

                    @if (isset($list_relateds))
                        <div class="col-md-12 hide-print">
                            <h4 style="padding-bottom: 10px">@lang('Danh sách đề xuất order đã gắn với phiếu mua sắm này')</h4>

                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Kho')</th>
                                        <th>@lang('Mã phiếu')</th>
                                        <th>@lang('Tên phiếu đề xuất')</th>
                                        <th>@lang('Tổng sản phẩm')</th>
                                        <th>@lang('Tổng tiền')</th>
                                        <th>@lang('Phòng')</th>
                                        <th>@lang('Trạng thái')</th>
                                        <th>@lang('Ghi chú')</th>
                                        <th>@lang('Người đề xuất')</th>
                                        <th>@lang('Tình trạng')</th>
                                        <th>@lang('Ngày đề xuất')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($list_relateds->count() > 0)
                                        @foreach ($list_relateds as $row)
                                            <tr class="valign-middle">
                                                <td>
                                                    {{ $loop->index + 1 }}
                                                </td>

                                                <td>
                                                    {{ $row->warehouse->name ?? '' }}
                                                </td>
                                                <td>
                                                    <a target="_blank" data-toggle="tooltip" title="@lang('Chi tiết đề xuất')"
                                                        href="{{ route('warehouse_order_product.show', $row->id) }}">{{ $row->code ?? '' }}
                                                        <i class="fa fa-eye hide-print"></i></i></a>
                                                </td>
                                                <td>
                                                    <a target="_blank" data-toggle="tooltip" title="@lang('Chi tiết đề xuất')"
                                                        href="{{ route('warehouse_order_product.show', $row->id) }}">{{ $row->name ?? '' }}
                                                        <i class="fa fa-eye hide-print"></i></a>
                                                </td>
                                                <td>{{ $row->orderDetails->sum('quantity') ?? '' }}</td>
                                                <td>{{ number_format($row->orderDetails->sum(fn($item) => $item['price'] * $item['quantity']), 0, ',', '.') . ' đ' }}
                                                </td>
                                                <td>
                                                    {{ $row->department->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ __($row->status) }}
                                                </td>
                                                <td>{{ $row->json_params->note ?? '' }}</td>
                                                <td>
                                                    {{ $row->staff->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $row->confirmred == 'da_nhan' ? 'Đã nhận' : 'Chưa nhận' }}
                                                </td>
                                                <td>
                                                    {{ $row->day_create != '' ? date('d-m-Y', strtotime($row->day_create)) : 'Chưa cập nhật' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif
                    @if (isset($detail->orderDetails) && count($detail->orderDetails) > 0)
                        <div class="col-md-12">
                            <h4 style="padding-bottom: 10px" class="hide-print">@lang('Tổng hợp sản phẩm trong phiếu')</h4>

                            <table class="table table-bordered sticky">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2">@lang('STT')</th>
                                        <th class="text-center" rowspan="2">@lang('Sản phẩm')</th>
                                        <th class="text-center" rowspan="2">@lang('Loại sản phẩm')</th>
                                        <th class="text-center" style="width:75px" rowspan="2">@lang('ĐVT')</th>
                                        <th class="text-center" style="width:135px" rowspan="2">
                                            @lang('Đơn giá')<br />(Dự kiến)</th>
                                        <th class="text-center" style="width:135px" rowspan="2">@lang('Đơn giá')</th>
                                        @if (isset($department))
                                            <th class="text-center" colspan="{{ $department->count() + 1 ?? 1 }}">
                                                @lang('Số lượng order')</th>
                                        @endif
                                        <th class="text-center" style="width:100px" rowspan="2">@lang('Tồn kho (Trước kỳ)')</th>
                                        <th class="text-center" style="width:100px" rowspan="2">@lang('SL mua')</th>
                                        <th class="text-center" style="width:100px" rowspan="2">@lang('Tổng tiền')</th>
                                        <th class="text-center" style="width:100px" rowspan="2">@lang('SL nhập kho')</th>
                                        <th class="text-center" style="width:100px" rowspan="2">@lang('SL xuất kho')</th>
                                        {{-- <th class="text-center" style="width:100px" rowspan="2">@lang('Tồn kho (Hiện tại)')</th> --}}

                                    </tr>
                                    <tr>
                                        @if (isset($department))
                                            @foreach ($department as $dep)
                                                <th class="text-center" style="width:75px">{{ __($dep->code) }}</th>
                                            @endforeach

                                            <th class="text-center" style="width:75px">Tổng</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="tbody-order">

                                    @foreach ($detail->orderDetails as $key => $row)
                                        <tr class="valign-middle">
                                            <td class="text-center">
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $row->product->name ?? '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ __($row->product->warehouse_type ?? '') }}
                                            </td>
                                            <td class="text-center">
                                                {{ $row->product->unit ?? '' }}
                                            </td>

                                            <td class="text-center">
                                                {{ isset($row->product->price) && is_numeric($row->product->price) ? number_format($row->product->price, 0, ',', '.') : '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : '' }}
                                            </td>
                                            @if (isset($department))
                                                @php
                                                    $total_all_dep = 0;
                                                @endphp
                                                @foreach ($department as $dep)
                                                    @php
                                                        $quatity_now =
                                                            $row->quantity_by_department[$dep->id]['quantity'] ?? 0;
                                                        $quatity_prev =
                                                            $row->quantity_by_department[$dep->id]['prev'] ?? 0;
                                                        $total_all_dep += $quatity_now;
                                                        $icon = '';
                                                        if ($quatity_now > $quatity_prev) {
                                                            $icon =
                                                                '<i class="fa fa-arrow-up text-success pull-right"></i>';
                                                        } elseif ($quatity_now < $quatity_prev) {
                                                            $icon =
                                                                '<i class="fa fa-arrow-down text-danger pull-right"></i>';
                                                        } elseif ($quatity_now > 0 && $quatity_now == $quatity_prev) {
                                                            $icon =
                                                                '<i class="fa fa-exchange text-warning pull-right"></i>';
                                                        }

                                                    @endphp
                                                    <td>
                                                        {{ $quatity_now }}
                                                        {!! $icon !!}
                                                    </td>
                                                @endforeach
                                                <td>
                                                    {{ $total_all_dep }}
                                                </td>
                                            @endif

                                            <td class="text-center">{{ $row->ton_kho_truoc_ky }}</td>
                                            <td class="text-center">{{ $row->quantity }}</td>
                                            <td>
                                                {{ isset($row->subtotal_money) && is_numeric($row->subtotal_money) ? number_format($row->subtotal_money, 0, ',', '.') : '' }}
                                            </td>
                                            <td class="text-center">{{ $row->total_entry }}</td>
                                            <td class="text-center">{{ $row->total_deliver }}</td>
                                            {{-- <td class="text-center">{{ $row->ton_kho }}</td> --}}
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right text-bold"
                                            colspan="{{ isset($department) ? $department->count() + 9 : 8 }}">TỔNG
                                            TIỀN:</td>
                                        <td class="text-bold" colspan="4">
                                            {{ isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') : '' }}
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    @endif
                    <div class="col-md-12 show-print">
                        <div class="col-xs-6 text-center text-bold text-uppercase">
                            @lang('Phòng HCNS')
                        </div>
                        <div class="col-xs-6 text-center text-bold text-uppercase">
                            @lang('Người đề nghị')
                        </div>
                    </div>
                </div>

            </div>
            <div class="box-footer hide-print">
                <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
                @if ($detail->status == 'not approved')
                    <button data-id="{{ $detail->id }}" type="button"
                        class= "approve_order btn btn-info btn-sm pull-right">
                        <i class="fa fa-save"></i> @lang('Duyệt phiếu')
                    </button>
                @else
                    <button type="button" class= "btn btn-danger btn-sm pull-right">
                        <i class="fa fa-save"></i> {{$detail->admin_approved->name??''}} - @lang('Đã duyệt')
                    </button>
                @endif
            </div>
        </div>

        <div class="modal fade modal_payment_request" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Thông tin phiếu in
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="box-body">
                                <form role="form" id="printForm" action="" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $detail->id }}">
                                    <div class="d-flex-wap">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Nội dung') <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="payment_content"
                                                    placeholder="@lang('Nội dung')" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Người nhận') <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="payment_stk"
                                                    value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="money">@lang('Số tiền tạm ứng')</label>
                                                <input type="text" class="form-control" name="money"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vat8">@lang('VAT 8%')</label>
                                                <input type="text" class="form-control" name="vat8"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vat10">@lang('VAT 10%')</label>
                                                <input type="text" class="form-control" name="vat10"
                                                    value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>@lang('Ghi chú')</label>
                                                <textarea name="note" class="form-control" cols="5" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary print_payment">
                                In thông tin
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $('.approve_order').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn duyệt đề xuất này ?')) {
                let _id = $(this).attr('data-id');
                let url = "{{ route('warehouse_order_buy.approve') }}/";
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

        $('.print_payment').click(function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.reportValidity(); // Hiển thị thông báo lỗi nếu không hợp lệ
                return;
            }
            var formData = $('#printForm').serialize();
            $.ajax({
                url: "{{ route('warehouse_order_product_buy.print_payment_request') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    const printWindow = window.open('', '_blank', 'width=800,height=600');
                    printWindow.document.open();
                    printWindow.document.write(response);
                    printWindow.document.close();

                    // In nội dung trong popup
                    printWindow.onload = function() {
                        printWindow.print();
                        printWindow.onafterprint = function() {
                            printWindow.close(); // Đóng popup sau khi in
                        };
                    };
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })
    </script>
@endsection
