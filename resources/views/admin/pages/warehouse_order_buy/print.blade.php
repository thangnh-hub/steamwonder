<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        @yield('title') | DWN
    </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="icon" href="{{ asset('themes/admin/img/meta-logo-favicon.png') }}">
    {{-- Include style for app --}}
    @include('admin.panels.styles')
    <style>
        .w-100 {
            width: 100%;
        }
    </style>
</head>

<body class="skin-blue layout-top-nav">
    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title text-uppercase text-center w-100">Cộng hòa xã hội chủ nghĩa Việt Nam</h3>
                <h3 class="box-title text-center w-100">Độc lập - Tự do - Hạnh phúc</h3>

                <p class="text-right"><i>Hà Nội, ngày {{ date('d'), time() }} Tháng {{ date('m'), time() }} Năm
                        {{ date('Y'), time() }}</i></p>

                <div class="text-center">
                    <h3 class="text-uppercase mt-15">Phiếu đề nghị thanh toán</h3>
                    <p class="fw-bold">Kính gửi: <span class="text-uppercase">Ban giám đốc</span></p>
                </div>

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-12">
                        <p>@lang('Phiếu mua sắm'): <span class="fw-bold">{{ $order_buy->name ?? '' }}</span></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Người đề xuất'): <span class="fw-bold">{{ $data['name'] ?? '' }}</span></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Bộ phận'): <span class="fw-bold">{{ $data['bo_phan'] ?? '' }}</span></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Nội dung'): <span class="fw-bold">{{ $params['payment_content'] ?? '' }}</span></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Thông tin người nhận'): <span class="fw-bold">{{ $params['payment_stk'] ?? '' }}</span></p>
                    </div>
                    <div class="col-xs-12">
                        <p>@lang('Ngày phát sinh'): <span class="fw-bold">{{ date('d-m-Y', time()) }}</span></p>
                    </div>

                    @if (isset($order_buy))
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">@lang('STT')</th>
                                        <th class="text-center">@lang('Diễn giản nội dung')</th>
                                        <th class="text-center">@lang('Số lượng')</th>
                                        <th class="text-center">@lang('Đơn giá') (VNĐ)</th>
                                        <th class="text-center">@lang('Thành tiền') (VNĐ)</th>

                                    </tr>
                                </thead>
                                <tbody class="tbody-order">
                                    @if (isset($order_buy->orderDetails) && count($order_buy->orderDetails) > 0)
                                        @foreach ($order_buy->orderDetails as $item)
                                            <tr class="valign-middle">
                                                <td class="text-center">
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td>
                                                    {{ $item->product->name ?? '' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->quantity ?? '' }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($item->price ?? 0, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    {{ number_format($item->subtotal_money ?? 0, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td class=" text-bold" colspan="4">Tổng trước thuế</td>
                                        <td class="text-center text-bold">
                                            {{ $order_buy->total_money !== '' ? number_format($order_buy->total_money, 0, ',', '.') : 0 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-bold" colspan="4">VAT 8%</td>
                                        <td class="text-center text-bold">
                                            {{ isset($params['vat8']) && !empty($params['vat8']) ? number_format((int) $params['vat8'], 0, ',', '.') : 0 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-bold" colspan="4">VAT 10%</td>
                                        <td class="text-center text-bold">
                                            {{ isset($params['vat10']) && !empty($params['vat10']) ? number_format((int) $params['vat10'], 0, ',', '.') : 0 }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class=" text-bold" colspan="4">TỔNG TIỀN:</td>
                                        <td class="text-center text-bold">
                                            {{ number_format($order_buy->total_money + ((int) $params['vat10'] ?? 0) + ((int) $params['vat8'] ?? 0), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                    <div class="col-md-12 mt-15">
                        <button type="button" class= "btn btn-danger btn-sm pull-right">
                            {{ $order_buy->admin_approved->name ?? '' }} - @lang('Đã duyệt')
                        </button>
                    </div>

                    <div class="col-md-12 mt-15">
                        <p>Ghi chú: {{ $params['note'] ?? '' }}</p>
                        <p style="display: flex; justify-content: space-between">
                            Số tiền đã tạm ứng:
                            <span class=" text-bold"
                                style="width: 300px">{{ number_format((int) $params['money'], 0, ',', '.') }} VNĐ
                            </span>
                        </p>
                        <p style="display: flex; justify-content: space-between">
                            Số tiền cần phải thanh toán:
                            <span class=" text-bold" style="width: 300px">
                                {{ number_format((int) $data['total_money'], 0, ',', '.') }} VNĐ
                            </span>
                        </p>
                        <p class="text-bold">Số tiền bằng chữ: <span class="ml-10">{{ ucfirst($data['text_money']) }}
                                đồng</span> </p>
                    </div>
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
                </div>
            </div>
        </div>
    </section>
    @include('admin.panels.scripts')

</body>

</html>
