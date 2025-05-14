@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .modal-dialog.modal-custom {
            max-width: 80%;
            width: auto;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-success pull-right " href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
            </a>
            <a class="btn btn-warning pull-right mr-10" target="_blank"
                href="{{ route(Request::segment(2) . '.print', $detail->id) }}" onclick="return openCenteredPopup(this.href)">
                <i class="fa fa-print"></i> @lang('In TBP')
            </a>
        </h1>

    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                {{-- <h3 class="box-title">@lang('List')</h3> --}}
            </div>
            <div class="box-body box_alert">
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
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="custom-scroll table-responsive">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th>Mã học sinh</th>
                                        <th>Họ tên</th>
                                        <th>Nickname</th>
                                        <th>Ngày sinh</th>
                                        <th>Địa chỉ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>{{ $detail->student->student_code ?? '' }}</b></td>
                                        <td><b>{{ $detail->student->first_name ?? '' }}
                                                {{ $detail->student->last_name ?? '' }}</b></td>
                                        <td>{{ $detail->student->nickname ?? '' }}</td>
                                        <td>{{ isset($detail->student->birthday) && $detail->student->birthday != '' ? date('d-m-Y', strtotime($detail->student->birthday)) : '' }}
                                        </td>
                                        <td>{{ $detail->student->address ?? '' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-scroll table-responsive">
                            <form method="post"
                                action="{{ route(Request::segment(2) . '.update_json_explanation', $detail->id) }}"
                                id="form_update_explanation">
                                @csrf
                                <table class="table table-bordered table-hover no-footer no-padding">
                                    <thead>
                                        <tr>
                                            <th colspan="7" class="text-left"><b>1. Số dư kỳ trước - <small>(+) Có /
                                                        (-) Nợ</small> </b>
                                            </th>
                                            <th class="text-right">
                                                <input type="number" name="prev_balance"
                                                    class="form-control pull-right prev_balance" style="max-width: 200px;"
                                                    placeholder="Nhập số dư kỳ trước"
                                                    value="{{ (int) $detail->prev_balance }}">
                                            </th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @if (isset($detail->prev_receipt_detail) && count($detail->prev_receipt_detail) > 0)
                                            <tr>
                                                <th>Tháng</th>
                                                <th>Dịch vụ</th>
                                                <th>Đơn giá</th>
                                                <th>Số lượng </th>
                                                <th>Tạm tính </th>
                                                <th>Tiền giảm</th>
                                                <th>Truy thu (+) / Hoàn trả (-)</th>
                                                <th>Tổng tiền</th>
                                            </tr>
                                            @foreach ($detail->prev_receipt_detail as $item_prev)
                                                <tr>
                                                    <td>{{ date('d-m-Y', strtotime($item_prev->month)) }}</td>
                                                    <td>{{ $item_prev->service->name ?? '' }}</td>
                                                    <td>{{ number_format($item_prev->unit_price, 0, ',', '.') ?? '' }}</td>
                                                    <td>{{ number_format($item_prev->spent_number, 0, ',', '.') ?? '' }}
                                                    </td>
                                                    <td>{{ number_format($item_prev->amount, 0, ',', '.') ?? '' }}</td>
                                                    <td>{{ number_format($item_prev->discount_amount, 0, ',', '.') ?? '' }}
                                                    </td>
                                                    <td>{{ number_format($item_prev->adjustment_amount, 0, ',', '.') ?? '' }}
                                                    </td>
                                                    <td>{{ number_format($item_prev->final_amount, 0, ',', '.') ?? '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tbody class="box_explanation">
                                        @if (isset($detail->json_params->explanation))
                                            @foreach ($detail->json_params->explanation as $key => $item)
                                                <tr class="item_explanation">
                                                    <td colspan="6">
                                                        <input type="text"
                                                            name="explanation[{{ $key }}][content]"
                                                            class="form-control action_change" value="{{ $item->content }}"
                                                            placeholder="Nội dung Truy thu/Hoàn trả">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="explanation[{{ $key }}][value]"
                                                            class="form-control action_change" value="{{ $item->value }}"
                                                            placeholder="Giá trị tương ứng">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button"
                                                            data-toggle="tooltip" onclick="$(this).closest('tr').remove()"
                                                            title="@lang('Delete')"
                                                            data-original-title="@lang('Delete')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </form>
                            <button class="btn btn-warning btn_explanation mt-10">@lang('Thêm giải trình')</button>
                        </div>
                        <div class="custom-scroll table-responsive mt-15">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-left"><b>2. Phí dự kiến</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Du kien thu thang nay -->

                                    @if (isset($detail->receiptDetail) && count($detail->receiptDetail) > 0)
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</span></th>
                                            <th>Tạm tính</th>
                                            <th>Giảm trừ</th>
                                            {{-- <th>Hoàn trả / phát sinh</th> --}}
                                            <th>Tổng tiền</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                        @foreach ($detail->receiptDetail as $item)
                                            <tr>
                                                <td>{{ date('m-Y', strtotime($item->month)) }}</td>
                                                <td>{{ $item->services_receipt->name ?? '' }}</td>
                                                <td>{{ number_format($item->unit_price, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->by_number, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->discount_amount, 0, ',', '.') ?? '' }}</td>
                                                {{-- <td>{{ number_format($item->adjustment_amount, 0, ',', '.') ?? '' }}</td> --}}
                                                <td>{{ number_format($item->final_amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ $item->note ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <form method="post" action="{{ route(Request::segment(2) . '.payment', $detail->id) }}"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $detail->id }}">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <tbody>
                                    <tr>
                                        <td>@lang('Mã TBP')</td>
                                        <td class="text-right"> {{ $detail->receipt_code }} </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Tên TBP')</td>
                                        <td class="text-right"> {{ $detail->receipt_name }} </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Trạng thái thanh toán')</td>
                                        <td class="text-right"><span
                                                class="label {{ $detail->status == 'pending' ? 'label-warning' : 'label-success' }}">{{ __($detail->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @lang('Tổng tiền')
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($detail->total_amount, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Tổng giảm trừ')</td>
                                        <td class="text-right">
                                            {{ number_format($detail->total_discount, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Số dư kỳ trước')</td>
                                        <td class="text-right total_prev_balance"
                                            data-balance="{{ $detail->prev_balance }}">
                                            {{ number_format($detail->prev_balance, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td>Tổng cộng các truy thu (+) / hoàn trả (-)</td>
                                        <td class="text-right">
                                            {{ number_format($detail->total_adjustment, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td>@lang('Tổng tiền thực tế sau đối soát tất cả dịch vụ')</td>
                                        <td class="text-right total_final" data-final="{{ $detail->total_final }}">
                                            {{ number_format($detail->total_final, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Đã thu')</td>
                                        <td class="text-right">
                                            <input type="number" name="total_paid" class="form-control text-right"
                                                value="{{ (int) $detail->total_paid ?? 0 }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Số tiền còn phải thu (+) hoặc thừa (-)')</td>
                                        <td class="text-right total_due" data-due="{{ $detail->total_due }}">
                                            {{ number_format($detail->total_due, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Hạn thanh toán')</td>
                                        <td class="text-right">
                                            <input type="date" name="payment_deadline" class="form-control"
                                                value="{{ $detail->json_params->payment_deadline ?? '' }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            {{-- <button type="submit" class="btn btn-success">
                                @lang('Duyệt TBP')
                            </button> --}}
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> @lang('Xác nhận thanh toán')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $('.prev_balance').keyup(function() {
            var _balance = parseInt($(this).val(), 10);
            if (isNaN(_balance)) {
                _balance = 0;
            }
            var _total_prev_balance = parseInt($('.total_prev_balance').data('balance'));
            var _total_final = parseInt($('.total_final').data('final'), 10);
            var _total_due = parseInt($('.total_due').data('due'), 10);

            $('.total_prev_balance').html(new Intl.NumberFormat('vi-VN').format(_balance));
            $('.total_final').html(new Intl.NumberFormat('vi-VN').format(_total_final + _total_prev_balance -
                _balance));
            $('.total_due').html(new Intl.NumberFormat('vi-VN').format(_total_due + _total_prev_balance -
                _balance));

        })

        $('.prev_balance').on('change', function() {
            updateJsonExplanation();
        });

        $('.btn_explanation').click(function() {
            var currentDateTime = Math.floor(Date.now() / 1000);

            var _html = `
            <tr class="item_explanation">
                <td colspan="6">
                    <input type="text" name="explanation[${currentDateTime}][content]" class="form-control action_change"
                        placeholder="Nội dung Truy thu/Hoàn trả">
                </td>
                <td>
                    <input type="number" name="explanation[${currentDateTime}][value]" class="form-control action_change"
                        placeholder="Giá trị tương ứng">
                </td>
                <td>
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                    onclick="$(this).closest('tr').remove()"
                        title="@lang('Delete')" data-original-title="@lang('Delete')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
            $('.box_explanation').append(_html);
        })

        $(document).on('change', '.action_change', function() {
            updateJsonExplanation();
        })

        $('#form_update_explanation').on('submit', function(event) {
            event.preventDefault();
            updateJsonExplanation();
        });

        function updateJsonExplanation() {
            var _url = $('#form_update_explanation').prop('action')
            var formData = $('#form_update_explanation').serialize();
            $.ajax({
                type: "POST",
                url: _url,
                data: formData,
                success: function(response) {
                    console.log(response.data);
                },
                error: function(data) {
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        }
    </script>
@endsection
