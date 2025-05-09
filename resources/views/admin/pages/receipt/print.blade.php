<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Thu Phí</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .table-bordered thead th {
            text-align: center
        }

        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            background-color: #d2e4f5;
        }

        .total {
            font-weight: bold;
            background-color: #7ca7d2;
        }

        .footer {
            font-size: 14px;
            display: flex;
        }

        .bank-info {
            width: 70%;
        }

        .qr-code {
            width: 30%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">@lang('THÔNG BÁO THU PHÍ')</div>

        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">@lang('Họ và tên'):</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p>{{ $detail->student->first_name ?? '' }} {{ $detail->student->last_name ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">@lang('Lớp học'):</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p>@lang('Chưa cập nhật')</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">@lang('Ngày sinh'):</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p>{{ $detail->student->birthday && optional($detail->student)->birthday ? \Carbon\Carbon::parse(optional($detail->student)->birthday)->format('d/m/Y') : '' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">@lang('Mã học sinh'):</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p>{{ $detail->student->student_code }}</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">@lang('Tên phụ huynh'):</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        @isset($detail->student->studentParents)
                            @foreach ($detail->student->studentParents as $item)
                                <p>{{ $item->relationship->title ?? '' }}: {{ $item->parent->first_name ?? '' }}
                                    {{ $item->parent->last_name ?? '' }}</p>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr class="total">
                    <th class="text-center">STT</th>
                    <th>Chi tiết các khoản phí</th>
                    <th>Số tiền</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>

            @php
                $i = 1;
            @endphp
            <tbody>
                {{-- Lấy theo năm --}}
                @if (count($serviceYearly) > 0)
                    <tr class="section-title">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td>@lang('Các khoản thu đầu năm')</td>
                        <td>{{ number_format($serviceYearly['total_amount'] ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    @if (isset($serviceYearly['services']) && count($serviceYearly['services']) > 0)
                        @foreach ($serviceYearly['services'] as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item['service']->name ?? '' }}</td>
                                <td>{{ number_format($item['total_amount'] ?? 0, 0, ',', '.') }}</td>
                                <td>Từ: {{ \Carbon\Carbon::parse($item['min_month'])->format('m-Y') ?? '' }} -
                                    Đến: {{ \Carbon\Carbon::parse($item['max_month'])->format('m-Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif
                {{-- Lấy theo tháng --}}
                @if (count($serviceMonthly) > 0)
                    <tr class="section-title">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td>@lang('Các khoản thu theo kỳ')</td>
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
                                <td>Từ: {{ \Carbon\Carbon::parse($item['min_month'])->format('m-Y') ?? '' }} -
                                    Đến: {{ \Carbon\Carbon::parse($item['max_month'])->format('m-Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif
                {{-- Lấy theo tháng --}}
                @if (count($serviceOther) > 0)
                    <tr class="section-title">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td>@lang('Các khoản thu phí khác')</td>
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
                                <td>Từ: {{ \Carbon\Carbon::parse($item['min_month'])->format('m-Y') ?? '' }} -
                                    Đến: {{ \Carbon\Carbon::parse($item['max_month'])->format('m-Y') ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif

                @if (count($listtServiceDiscoun) > 0)
                    <tr class="section-title">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td>@lang('Các khoản giảm trừ')</td>
                        <td class="text-right">
                            {{ number_format($listtServiceDiscoun->sum('total_discount_amount') ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    @if (isset($listtServiceDiscoun) && count($listtServiceDiscoun) > 0)
                        @foreach ($listtServiceDiscoun as $item)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $item['service']->name ?? '' }}</td>
                                <td class="text-right">
                                    {{ number_format($item['total_discount_amount'] ?? 0, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    @php $i++; @endphp
                @endif

                @if ($detail->prev_balance != 0)
                    <tr class="section-title">
                        <td class="text-center">{{ \App\Helpers::intToRoman($i) }}</td>
                        <td>@lang('khoản giải trình')</td>
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
                        {{ number_format($detail->total_final , 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2">@lang('TỔNG SỐ TIỀN ĐÃ NỘP')</td>
                    <td class="text-right">{{ number_format($detail->total_paid ?? 0, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2">@lang('TỔNG SỐ TIỀN CÒN PHẢI NỘP')</td>
                    <td class="text-right">{{ number_format($detail->total_due , 0, ',', '.') }}
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
                <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Ngày sinh</p>
                <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
            </div>
            @isset($qrCode)
                <div class="qr-code">
                    <p style="text-align: center"><img src="{{ $qrCode }}" alt="QR Ngân hàng" width="250"></p>
                    <p>@lang('Vui lòng quét mã QR để thanh toán')</p>
                </div>
            @endisset
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mở hộp thoại in
            window.print();
        });
    </script>
</body>

</html>
