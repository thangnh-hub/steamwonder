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
            /* margin-top: 25px;
            border-top: 1px dashed #000;
            padding-top: 10px; */
            display: flex;
            /* position: relative; */
        }

        .bank-info {
            width: 80%;
            text-align: justify;
        }

        .content-payment {
            text-align: justify;
        }

        .qr-code {
            width: 20%;
            /* position: absolute;
            top: 0;
            right: 0; */
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
                zoom: 75%;
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
                /* position: absolute;
                top: 0; */
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            width: 20%;
            float: left;
        }

        .header .logo img {
            max-width: 100%;
            height: auto;
        }

        .header .company-info {
            width: 80%;
            float: left;
            margin-left: 20px;
            margin-right: 20px;
        }

        .qr-code img {
            width: 100%;
            height: auto;
        }

        p {
            margin-top: 0px;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="header">
            <div class="logo">
                <img src="https://steamwonders.vn/data/logo/SWS-logo.png" alt="Logo">
            </div>
            <div class="company-info">
                <p><strong>{{ optional($detail->area)->json_params->company ?? '' }}</strong></p>
                <p><strong>{{ optional($detail->area)->json_params->address ?? '' }}</strong></p>
                <p>
                    <strong>Điện thoại:</strong> {{ optional($detail->area)->json_params->phone ?? '' }}
                    <strong>Email:</strong> {{ optional($detail->area)->json_params->email ?? '' }}
                </p>
            </div>
        </div>

        <div class="content">
            <p>Kính gửi: Quý Phụ huynh,</p>
            <p>
                {{ optional($detail->area)->json_params->school ?? '' }} trân trọng cảm ơn Quý Phụ huynh đã quan tâm
                trong suốt thời
                gian qua.
            </p>
            <p>
                <strong><i>Dưới đây, Nhà trường xin gửi đến Quý Phụ huynh Thông báo thu phí (tạm tính). Sau 02 ngày kể
                        từ ngày nhận được Thông báo tạm tính này, nếu Quý Phụ huynh không có thắc mức gì thì bản tạm
                        tính này được coi là bản chính thức.</i></strong>
            </p>

            <p>
                <strong><i>Nếu Quý Phụ huynh có yêu cầu hoặc thắc mắc gì, vui lòng liên hệ với bộ phận CSKH để được giải
                        đáp chi tiết qua email cskh.thuhocphi.steame@gmail.com hoặc hotline: 0473.366.6666</i></strong>
            </p>
        </div>

        <h2 class="title">THÔNG BÁO THU PHÍ THÁNG {{ \Carbon\Carbon::parse($detail->period_start)->format('m/Y') }}
        </h2>

        <table class="info">
            <tr>
                <td>
                    <strong>Họ và tên học sinh:</strong>
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
                            {{ optional($item->relationship)->title ?? '' }}
                            {{ optional($item->parent)->first_name ?? '' }}
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

        <h3 class="text-center">PHẦN I - CÁC KHOẢN PHÍ PHẢI NỘP</h3>
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
        <h3 class="text-center">PHẦN II - HƯỚNG DẪN THANH TOÁN</h3>
        <div class="footer">
            <div class="bank-info">
                <p><strong>Hình thức thanh toán:</strong></p>
                <p>Thanh toán bằng chuyển khoản, Quý Phụ huynh vui lòng chuyển tiền vào tài khoản sau:</p>
                <p><strong>Tên TK:</strong> {{ optional($detail->area)->json_params->bank_account ?? '' }}</p>
                <p><strong>Số TK:</strong> {{ optional($detail->area)->json_params->bank_stk ?? '' }} -
                    {{ optional($detail->area)->json_params->bank_name ?? '' }}</p>
                <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Mã TBP</p>
                <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
                <p><strong>Thời hạn nộp phí:</strong></p>
                <p>Quý phụ huynh vui lòng thanh toán các khoản phí trên trong vòng 10 ngày kể từ ngày nhận được thông
                    báo từ
                    Nhà trường.</p>

            </div>
            @isset($qrCode)
                <div class="qr-code">
                    <img src="{{ $qrCode }}" alt="QR Ngân hàng">
                    <p class="text-center">@lang('Quét mã QR để thanh toán')</p>
                </div>
            @endisset
        </div>
        <div class="content-payment">
            <p><strong>Thanh toán phí:</strong></p>
            <p>Phụ huynh cần hiểu rõ trách nhiệm hoàn tất phí và thời hạn chi trả của mình. Việc thông báo nhắc nhở
                của
                Nhà trường vì một lý do nào đó khống đến được với Phụ huynh không có ý nghĩa trì hoãn trách nhiệm
                chi
                trả các khoản phí cho học sinh theo quy định.</p>
            <p>Nếu Nhà trường chưa nhận được học phí và các khoản phí liên quan sau ngày quy định nộp phí, các khoản
                phí
                đến hạn phải nộp sẽ tự động tăng thêm 0.05%/ngày chậm trên số tiền chưa thanh toán và Phụ huynh có
                trách
                nhiệm nộp cả phần tăng thêm này. Phụ huynh đóng phí sau thời hạn quy định sẽ không được hưởng ưu
                đãi.
            </p>
            <p>Trường hợp Phụ huynh không đóng phí đúng hạn hoặc không đóng phí theo Quy định tài chính, Nhà trường
                có
                quyền không xếp lớp và không cung cấp dịch vụ cho học sinh vào đầu năm học hoặc ngừng cung cấp dịch
                vụ
                nếu Phụ huynh không đóng phí cho học sinh theo kỳ học.</p>
            <p><i>Quý Phụ huynh vui lòng bỏ qua thông báo này nếu đã thanh toán. Mọi yêu cầu hoặc thắc mắc gì, xin
                    vui
                    lòng liên hệ với bộ phận CSKH để được giải đáp chi tiết.</i></p>
            <p class="footer">Thông báo này được in tự động từ hệ thống nên không có dấu và chữ ký.</p>
            <p>Trân trọng cảm ơn!</p>
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
