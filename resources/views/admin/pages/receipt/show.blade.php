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
            <a class="btn btn-warning pull-right mr-10" href="{{ route(Request::segment(2) . '.print', $detail->id) }}">
                <i class="fa fa-print"></i> @lang('In hóa đơn')
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
                            <h3>Thông tin học sinh</h3>
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
                            <table id="dt_basic" class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="text-left"><b>1. Dư nợ kỳ trước:</b>
                                        </th>
                                        <th>&nbsp;</th>

                                        <th class="text-right">
                                            {{ number_format($detail->prev_balance, 0, ',', '.') ?? '' }}
                                        </th>
                                    </tr>

                                </thead>
                                <tbody>

                                    @if (isset($detail->prev_receipt_detail) && count($detail->prev_receipt_detail) > 0)
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Số lượng dự kiến (thu trước theo dịch vụ)</th>
                                            <th>Số lượng sử dụng thực tế (đối soát theo dịch vụ)</th>
                                            <th>Đơn giá dịch vụ</th>
                                            <th>Số tiền dịch vụ trong tháng </th>
                                            <th>Tiền giảm trừ trong tháng </th>
                                            <th>Truy thu (+) / Hoàn trả (-) thực tế sau đối soát</th>
                                            <th>Số tiền cuối cùng phải thu sau giảm trừ & điều chỉnh</th>
                                        </tr>
                                        @foreach ($detail->prev_receipt_detail as $item_prev)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($item_prev->month)) }}</td>
                                                <td>{{ $item_prev->service->name ?? '' }}</td>
                                                <td>{{ number_format($item_prev->by_number, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item_prev->spent_number, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item_prev->unit_price, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item_prev->amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item_prev->discount_amount, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>{{ number_format($item_prev->adjustment_amount, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>{{ number_format($item_prev->final_amount, 0, ',', '.') ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-scroll table-responsive">
                            <table id="dt_basic" class="table table-bordered table-hover no-footer no-padding">
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
                                            <th>SL thực tế</span></th>
                                            <th>Giảm trừ</th>
                                            <th>Tạm tính</th>
                                            <th>Hoàn trả / phát sinh</th>
                                            <th>Tổng tiền</th>
                                        </tr>
                                        @foreach ($detail->receiptDetail as $item)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($item->month)) }}</td>
                                                <td>{{ $item->services_receipt->name ?? '' }}</td>
                                                <td>{{ number_format($item->unit_price, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->spent_number, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->discount_amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->adjustment_amount, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>{{ number_format($item->final_amount, 0, ',', '.') ?? '' }}</td>
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
                                        <td><label>@lang('Mã hóa đơn')</label></td>
                                        <td class="text-right"> {{ $detail->receipt_code }} </td>
                                    </tr>
                                    <tr>
                                        <td><label>@lang('Tên hóa đơn')</label></td>
                                        <td class="text-right"> {{ $detail->receipt_name }} </td>
                                    </tr>
                                    <tr>
                                        <td><label>@lang('T/T thanh toán')</label></td>
                                        <td class="text-right"><span
                                                class="label {{ $detail->status == 'pending' ? 'label-warning' : 'label-success' }}">{{ __($detail->status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label> <strong> Tổng số tiền cần nộp </strong> </label>
                                        </td>
                                        <td class="text-right">
                                            <b>{{ number_format($detail->total_amount, 0, ',', '.') ?? '' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Tổng giảm trừ</label></td>
                                        <td class="text-right">
                                            <b>{{ number_format($detail->total_discount, 0, ',', '.') ?? '' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Tổng cộng các truy thu (+) / hoàn trả (-)</label></td>
                                        <td class="text-right">
                                            <b>{{ number_format($detail->total_adjustment, 0, ',', '.') ?? '' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Tổng tiền thực tế sau đối soát tất cả dịch vụ</label></td>
                                        <td class="text-right">
                                            <b>{{ number_format($detail->total_final, 0, ',', '.') ?? '' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Đã thu</label></td>
                                        <td class="text-right">
                                            <b>{{ number_format($detail->total_paid, 0, ',', '.') ?? '' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Số tiền còn phải thu (+) hoặc thừa (-)</label></td>
                                        <td class="text-right">
                                            <b>{{ number_format($detail->total_due, 0, ',', '.') ?? '' }}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Ngày bắt đầu kỳ thu</label></td>
                                        <td class="text-right">
                                            {{ date('d-m-Y', strtotime($detail->period_start)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Ngày kết thúc kỳ thu</label></td>
                                        <td class="text-right">
                                            {{ date('d-m-Y', strtotime($detail->period_end)) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            @if ($detail->status == 'pendding')
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> Xác nhận thanh
                                    toán</button>
                            @else
                                <button type="button" class="btn btn-warning"> Đã thanh toán</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
