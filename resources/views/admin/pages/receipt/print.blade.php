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
                <tr>
                    <th>TT</th>
                    <th>CHI TIẾT CÁC KHOẢN PHÍ</th>
                    <th>Số tiền</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>I</td>
                    <td>CÁC KHOẢN THU PHÍ ĐẦU NĂM</td>
                    <td>6,484,000</td>
                    <td></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Phí phát triển trường/cơ sở vật chất</td>
                    <td>2,000,000</td>
                    <td>Kỳ 2 năm học 2024-2025 từ 01/01/2025 - 31/05/2025</td>
                </tr>
                <tr>
                    <td>IV</td>
                    <td>@lang('Các khoản giải trình')</td>
                    <td>{{ number_format($detail->prev_balance ?? 0, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                @if (isset($detail->json_params->explanation) && count((array)$detail->json_params->explanation) > 0)
                    @foreach ($detail->json_params->explanation as $item)
                        <tr>
                            <td>---IV.{{ $loop->index + 1 }}</td>
                            <td>{{ $item->content ?? '' }}</td>
                            <td>{{ number_format($item->value ?? '', 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    @endforeach

                @endif


                <tr>
                    <td colspan="2">@lang('TỔNG PHẢI NỘP') (I + II + III - IV)</td>
                    <td>{{ number_format($detail->total_final + $detail->prev_balance, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">@lang('TỔNG SỐ TIỀN ĐÃ NỘP')</td>
                    <td>{{ number_format($detail->total_paid ?? 0, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">@lang('TỔNG SỐ TIỀN CÒN PHẢI NỘP')</td>
                    <td>{{ number_format($detail->total_due, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <p><strong>Hình thức thanh toán:</strong></p>
            <p>Thanh toán bằng chuyển khoản, Quý Phụ huynh vui lòng chuyển tiền vào tài khoản sau:</p>
            <p><strong>Tên TK:</strong> Công ty Cổ phần Mầm Non STEAME GARTEN</p>
            <p><strong>Số TK:</strong> 2662686868 - Techcombank - Chi nhánh Hà Thành - Hà Nội</p>
            <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Ngày sinh</p>
            <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
        </div>
    </div>

</body>

</html>
