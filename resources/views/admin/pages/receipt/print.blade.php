<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Thu Phí</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            margin: 20px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .wrapper {
            max-width: 297mm;
            margin: 0 auto;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .info {
            width: 100%;
            margin-bottom: 10px;
        }

        .fee-table {
            width: 100%;
            border-collapse: collapse;
        }

        .fee-table th,
        .fee-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .fee-table th {
            background-color: #e9f0f8;
            text-align: center;
        }

        .section {
            background-color: #dce6f1;
            font-weight: bold;
        }

        .total {
            background-color: #c5d9f1;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            display: flex;
        }

        .bank-info {
            width: 70%;
        }

        .qr-code {
            width: 30%;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .fee-table th,
            .section,
            .total {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }

            .qr-code img {
                width: 100%;
                height: auto;
            }
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <h2 class="title">THÔNG BÁO THU PHÍ</h2>

        <table class="info">
            <tr>
                <td>
                    <strong>Họ và tên:</strong>
                    {{ optional($detail->student)->first_name ?? '' }}
                    {{ optional($detail->student)->last_name ?? '' }}
                </td>
                <td>
                    <strong>Lớp học:</strong>
                    {{ optional(optional($detail->student)->currentClass)->name ?? '' }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Ngày sinh:</strong>
                    {{ $detail->student->birthday && optional($detail->student)->birthday ? \Carbon\Carbon::parse(optional($detail->student)->birthday)->format('d/m/Y') : '' }}
                </td>
                <td>
                    <strong>Phụ huynh:</strong>
                    @isset(optional($detail->student)->studentParents)
                        @foreach (optional($detail->student)->studentParents as $item)
                            {{ optional($item->relationship)->title ?? '' }} {{ optional($item->parent)->first_name ?? '' }}
                            {{ optional($item->parent)->last_name . '. ' ?? '' }}
                        @endforeach
                    @endisset
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Mã học sinh:</strong>
                    {{ optional($detail->student)->student_code }}
                </td>
                <td>
                    <strong>Mã TBP:</strong>
                    {{ $detail->receipt_code ?? '' }}
                </td>
            </tr>
        </table>

        <table class="fee-table" border="1" cellspacing="0" cellpadding="5">
            <thead>
                <tr>
                    <th>TT</th>
                    <th>CHI TIẾT CÁC KHOẢN PHÍ</th>
                    <th>SỐ TIỀN</th>
                    <th>GHI CHÚ</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                {{-- Lấy theo năm --}}
                @if (count($serviceYearly) > 0)
                    <tr class="section">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td class="text-uppercase">@lang('Các khoản thu đầu năm')</td>
                        <td>{{ number_format($serviceYearly['total_amount'] ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    @if (isset($serviceYearly['services']) && count($serviceYearly['services']) > 0)
                        @foreach ($serviceYearly['services'] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item['service']->name ?? '' }}</td>
                                <td>{{ number_format($item['total_amount'] ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    Từ:
                                    {{ \Carbon\Carbon::parse($item['min_month'])->copy()->startOfMonth()->format('d/m/Y') ?? '' }}
                                    -
                                    {{ \Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif
                {{-- Lấy theo tháng --}}
                @if (count($serviceMonthly) > 0)
                    <tr class="section">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td class="text-uppercase">@lang('Các khoản thu theo kỳ')</td>
                        <td class="text-right">{{ number_format($serviceMonthly['total_amount'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    @if (isset($serviceMonthly['services']) && count($serviceMonthly['services']) > 0)
                        @foreach ($serviceMonthly['services'] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item['service']->name ?? '' }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    Từ:
                                    {{ \Carbon\Carbon::parse($item['min_month'])->copy()->startOfMonth()->format('d/m/Y') ?? '' }}
                                    -
                                    {{ \Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif
                {{-- Lấy theo other --}}
                @if (count($serviceOther) > 0)
                    <tr class="section">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td class="text-uppercase">@lang('Các khoản thu phí khác')</td>
                        <td class="text-right">{{ number_format($serviceOther['total_amount'] ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    @if (isset($serviceOther['services']) && count($serviceOther['services']) > 0)
                        @foreach ($serviceOther['services'] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item['service']->name ?? '' }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'] ?? 0, 0, ',', '.') }}
                                </td>
                                <td>
                                    Từ:
                                    {{ \Carbon\Carbon::parse($item['min_month'])->copy()->startOfMonth()->format('d/m/Y') ?? '' }}
                                    -
                                    {{ \Carbon\Carbon::parse($item['max_month'])->copy()->endOfMonth()->format('d/m/Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif
                {{-- Giảm trừ / ưu đãi --}}
                @if (count($listServiceDiscount) > 0)
                    <tr class="section">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td class="text-uppercase">@lang('Các khoản Ưu đãi/Giảm trừ')</td>
                        <td class="text-right">
                            {{ number_format($listServiceDiscount->sum('total_discount_amount') ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    @if (isset($listServiceDiscount) && count($listServiceDiscount) > 0)
                        @foreach ($listServiceDiscount as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item['service']->name ?? '' }}</td>
                                <td class="text-right">
                                    {{ number_format($item['total_discount_amount'] ?? 0, 0, ',', '.') }}</td>
                                <td>{!! $item['note'] !!}</td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif

                @if (
                    $detail->prev_balance != 0 ||
                        (isset($detail->json_params->explanation) && count((array) $detail->json_params->explanation) > 0))
                    <tr class="section">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td>@lang('CÁC KHOẢN TRUY THU/HOÀN TRẢ') [+ Có , - Nợ]</td>
                        <td class="text-right">{{ number_format($detail->prev_balance ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    @if (isset($detail->json_params->explanation) && count((array) $detail->json_params->explanation) > 0)
                        @foreach ($detail->json_params->explanation as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item->content ?? '' }}</td>
                                <td class="text-right">{{ number_format($item->value ?? 0, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif
                <tr class="total">
                    <td colspan="2">@lang('TỔNG PHẢI NỘP') </td>
                    <td class="text-right">
                        {{ number_format($detail->total_final, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2">@lang('TỔNG SỐ TIỀN ĐÃ NỘP')</td>
                    <td class="text-right">{{ number_format($detail->total_paid ?? 0, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2">@lang('TỔNG SỐ TIỀN CÒN PHẢI NỘP')</td>
                    <td class="text-right">{{ number_format($detail->total_due, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="bank-info">
                <p><strong>Hình thức thanh toán:</strong></p>
                <p>Thanh toán bằng chuyển khoản, Quý Phụ huynh vui lòng chuyển tiền vào tài khoản sau:</p>
                <p><strong>Tên TK:</strong> {{ optional($detail->area)->json_params->bank_account ?? '' }}</p>
                <p><strong>Số TK:</strong> {{ optional($detail->area)->json_params->bank_stk ?? '' }} -
                    {{ optional($detail->area)->json_params->bank_name ?? '' }}</p>
                <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Mã TBP</p>
                <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
            </div>
            @isset($qrCode)
                <div class="qr-code">
                    <p class="text-center"><img src="{{ $qrCode }}" alt="QR Ngân hàng" width="250"></p>
                    <p class="text-center">@lang('Vui lòng quét mã QR để thanh toán')</p>
                </div>
            @endisset
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mở hộp thoại in
            // window.print();
        });
    </script>
</body>

</html>
