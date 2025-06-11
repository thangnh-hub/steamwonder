@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .item_adjustment:last-child {
            position: absolute;
            top: -100vh;
        }

        .modal-dialog.modal-custom {
            max-width: 80%;
            width: auto;
        }

        .select2-container {
            width: 100% !important;
        }

        .tooltip-inner {
            white-space: nowrap;
            max-width: none;
            text-align: left
        }

        .box-flex-between {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-bordered>tbody>tr>td {
            vertical-align: middle
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
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>

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
                                            <th colspan="6" class="text-left"><b>1. Số dư kỳ trước <span data-html="true"
                                                        data-toggle="tooltip"
                                                        title="
                                                        Hoàn trả sẽ nhập số nguyên dương (+)
                                                        <br>
                                                        Truy thu sẽ nhập số nguyên âm (-)">
                                                        <i class="fa fa-question-circle-o" aria-hidden="true"></i></span>
                                                </b>
                                            </th>
                                            <th class="text-right" colspan="2">
                                                <input type="number" name="prev_balance"
                                                    {{ $detail->status == 'pending' ? '' : 'disabled' }} readonly
                                                    class="form-control pull-right prev_balance" style="max-width: 200px;"
                                                    placeholder="Nhập số dư kỳ trước" data-toggle="tooltip"
                                                    title="Tổng số dư kỳ trước của học sinh này, nếu có"
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
                                        @if (isset($detail->student->receiptAdjustment))
                                            @foreach ($detail->student->receiptAdjustment as $item)
                                                @if ($item->receipt_id == null || $item->receipt_id == $detail->id)
                                                    <tr
                                                        class="item_adjustment {{ in_array($item->type, ['dunokytruoc', 'doisoat']) ? 'bg-gray' : '' }}">
                                                        <td class="text-center">
                                                            @if ($detail->status == 'pending')
                                                                <input type="checkbox" class="check_doisoat"
                                                                    onclick="updateBalance()" name="receipt_adjustment[]"
                                                                    value="{{ $item->id }}"
                                                                    {{ $item->receipt_id == $detail->id ? 'checked' : '' }}>
                                                            @endif
                                                        </td>
                                                        <td colspan="3">
                                                            @if (in_array($item->type, ['dunokytruoc', 'doisoat']))
                                                                {{ $item->note }}
                                                            @else
                                                                <input type="text"
                                                                    {{ $detail->status == 'pending' ? '' : 'disabled' }}
                                                                    name="adjustment[{{ $item->id }}][note]"
                                                                    class="form-control action_change"
                                                                    value="{{ $item->note }}"
                                                                    placeholder="Nội dung Truy thu/Hoàn trả">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (in_array($item->type, ['dunokytruoc', 'doisoat']))
                                                                <input type="hidden" name="list_doisoat[]"
                                                                    value="{{ $item->id }}">
                                                                <span
                                                                    class="final_amount">{{ (int) $item->final_amount }}</span>
                                                            @else
                                                                <input type="number"
                                                                    {{ $detail->status == 'pending' ? '' : 'disabled' }}
                                                                    name="adjustment[{{ $item->id }}][final_amount]"
                                                                    class="form-control action_change"
                                                                    value="{{ (int) $item->final_amount }}"
                                                                    placeholder="Giá trị tương ứng">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (in_array($item->type, ['dunokytruoc', 'doisoat']))
                                                                {{ __($item->type) }}
                                                            @else
                                                                <select name="adjustment[{{ $item->id }}][type]"
                                                                    class="form-control">
                                                                    @foreach ($type as $key => $val)
                                                                        @if (!in_array($key, ['dunokytruoc', 'doisoat']))
                                                                            <option value="{{ $key }}" {{$item->type == $key ?'selected':''}}>
                                                                                {{ __($val) }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (in_array($item->type, ['dunokytruoc', 'doisoat']))
                                                                {{ date('m-Y', strtotime($item->month)) }}
                                                            @else
                                                                <input type="date"
                                                                    {{ $detail->status == 'pending' ? '' : 'disabled' }}
                                                                    name="adjustment[{{ $item->id }}][month]"
                                                                    class="form-control action_change"
                                                                    value="{{ $item->month != '' ? date('Y-m-d', strtotime($item->month)) : '' }}">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($detail->status == 'pending')
                                                                @if (!in_array($item->type, ['dunokytruoc', 'doisoat']))
                                                                    <button
                                                                        class="btn btn-sm btn-success btn_save_adjustment"
                                                                        type="button" data-toggle="tooltip" onclick=""
                                                                        title="@lang('Save')">
                                                                        <i class="fa fa-save"></i>
                                                                    </button>
                                                                    {{-- <button class="btn btn-sm btn-danger" type="button"
                                                                        data-toggle="tooltip"
                                                                        onclick="$(this).closest('tr').remove();updateBalance()"
                                                                        title="@lang('Delete')">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button> --}}
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        {{-- @if (isset($detail->json_params->explanation))
                                            @foreach ($detail->json_params->explanation as $key => $item)
                                                <tr class="item_explanation">
                                                    <td class="text-center">
                                                    </td>
                                                    <td colspan="5">
                                                        <input type="text"
                                                            {{ $detail->status == 'pending' ? '' : 'disabled' }}
                                                            name="explanation[{{ $key }}][content]"
                                                            class="form-control action_change" value="{{ $item->content }}"
                                                            placeholder="Nội dung Truy thu/Hoàn trả">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            {{ $detail->status == 'pending' ? '' : 'disabled' }}
                                                            name="explanation[{{ $key }}][value]"
                                                            class="form-control action_change" value="{{ $item->value }}"
                                                            placeholder="Giá trị tương ứng">
                                                    </td>

                                                    <td>
                                                        @if ($detail->status == 'pending')
                                                            <button class="btn btn-sm btn-danger" type="button"
                                                                data-toggle="tooltip"
                                                                onclick="$(this).closest('tr').remove();updateBalance()"
                                                                title="@lang('Xóa giải trình')"
                                                                data-original-title="@lang('Xóa giải trình')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif --}}
                                        <tr class="item_adjustment">
                                            <td class="text-center"></td>
                                            <td colspan="3">
                                                <input type="text" name="adjustment[0][note]"
                                                    class="form-control action_change"
                                                    placeholder="Nội dung Truy thu/Hoàn trả">
                                            </td>
                                            <td>
                                                <input type="number" name="adjustment[0][final_amount]"
                                                    class="form-control action_change" placeholder="Giá trị tương ứng">
                                            </td>
                                            <td>
                                                <select name="adjustment[0][type]" class="form-control">
                                                    @foreach ($type as $key => $val)
                                                        @if (!in_array($key, ['dunokytruoc', 'doisoat']))
                                                            <option value="{{ $key }}">
                                                                {{ __($val) }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="date" name="adjustment[0][month]"
                                                    class="form-control action_change" value="">
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success btn_save_adjustment" type="button"
                                                    data-toggle="tooltip" onclick="" title="@lang('Save')">
                                                    <i class="fa fa-save"></i>
                                                </button>
                                                {{-- <button class="btn btn-sm btn-danger" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="$(this).closest('tr').remove();updateBalance()"
                                                    title="@lang('Delete')">
                                                    <i class="fa fa-trash"></i>
                                                </button> --}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                            @if ($detail->status == 'pending')
                                <button class="btn btn-warning btn_explanation mt-10">@lang('Thêm giải trình')</button>
                            @endif
                        </div>
                        <div class="custom-scroll table-responsive mt-15">
                            @if (isset($detail->receiptDetail) && count($detail->receiptDetail) > 0)
                                <table class="table table-bordered table-hover no-footer no-padding">
                                    <thead>
                                        <tr>
                                            <th colspan="6" class="text-left"><b>2. Phí dự kiến</b></th>
                                            <th colspan="3"class="text-right">
                                                @if ($detail->status == 'pending')
                                                    <button data-toggle="modal" data-target="#modal_show_service"
                                                        class="btn btn-warning">@lang('Thay đổi kỳ tính phí cho HS')</button>
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Du kien thu thang nay -->
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</span></th>
                                            <th>Tạm tính</th>
                                            <th>Giảm trừ</th>
                                            <th>Tổng tiền</th>
                                            <th>Ghi chú</th>
                                            <th></th>

                                        </tr>
                                        @foreach ($detail->receiptDetail as $item)
                                            <tr>
                                                <td>{{ date('m-Y', strtotime($item->month)) }}</td>
                                                <td>{{ $item->services_receipt->name ?? '' }}</td>
                                                <td>{{ number_format($item->unit_price, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->by_number, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->discount_amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ number_format($item->final_amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{!! $item->note ?? '' !!}</td>
                                                <td>
                                                    @if ($detail->status == 'pending')
                                                        <button
                                                            class="btn btn-sm btn-danger delete_receipt_detail_and_recalculate"
                                                            data-receipt="{{ $detail->id }}"
                                                            data-id = "{{ $item->id }}" type="button"
                                                            data-toggle="tooltip" title="@lang('Xóa')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <form method="post" action="{{ route(Request::segment(2) . '.payment', $detail->id) }}"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            @csrf
                            <input type="hidden" name="id" value="{{ $detail->id }}">
                            <table class="table table-bordered table-hover no-footer no-padding table_paid">
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
                                        <td>@lang('Tổng tiền thực tế sau đối soát tất cả dịch vụ') <span data-html="true" data-toggle="tooltip"
                                                title="= [Tổng tiền] - [Giảm trừ] - [Số dư kỳ trước]">
                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i></span>
                                        </td>
                                        <td class="text-right total_final" data-final="{{ $detail->total_final }}">
                                            {{ number_format($detail->total_final, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="box-flex-between">
                                                <span>@lang('Đã thu')</span>
                                                @if ($detail->status != 'pending')
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        data-toggle="modal" data-target="#modal_receipt_transaction">Chi
                                                        tiết</button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($detail->total_paid, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> @lang('Số tiền còn phải thu (+) hoặc thừa (-)')
                                            <span data-toggle="tooltip"
                                                title="Số tiền thừa sẽ được chuyển qua kỳ thanh toán tiếp theo"><i
                                                    class="fa fa-question-circle-o" aria-hidden="true"></i></span>
                                        </td>
                                        <td class="text-right total_due" data-due="{{ $detail->total_due }}">
                                            {{ number_format($detail->total_due, 0, ',', '.') ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>@lang('Hạn thanh toán')</td>
                                        <td class="text-right">
                                            <input type="date" name="due_date" class="form-control"
                                                {{ $detail->status == 'approved' ? '' : 'disabled' }}
                                                value="{{ $detail->json_params->due_date ?? $due_date }}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            @if ($detail->status == 'pending')
                                <button type="button" class="btn btn-success btn_approved">
                                    @lang('Duyệt TBP')
                                </button>
                            @endif
                            @if ($detail->status == 'approved')
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> @lang('Xác nhận đã thanh toán')
                                </button>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="modal fade" id="modal_show_service" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12">@lang('Thay đổi kỳ tính phí cho học sinh')</h3>
                        </h3>
                    </div>
                    <form action="{{ route('receipt.update_student_service_and_fee') }}" method="POST"
                        class="form_detail_service">
                        @csrf
                        <input type="hidden" name="receipt_id" value="{{ $detail->id }}">
                        <input type="hidden" name="student_id" value="{{ $detail->student->id }}">
                        <div class="modal-body show_detail_service">
                            <div class="modal-alert"></div>
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th>@lang('Tên dịch vụ')</th>
                                        <th>@lang('Nhóm dịch vụ')</th>
                                        <th>@lang('Hệ đào tạo')</th>
                                        <th>@lang('Loại dịch vụ')</th>
                                        <th>@lang('Biểu phí')</th>
                                        <th>@lang('Chu kỳ thu')</th>
                                    </tr>
                                </thead>
                                <tbody class="box_service">
                                    @if (isset($detail->student->studentServices) && count($detail->student->studentServices) > 0)
                                        @foreach ($detail->student->studentServices as $item)
                                            <tr>
                                                <td>{{ $item->services->name ?? '' }}</td>
                                                <td>{{ $item->services->service_category->name ?? '' }}</td>
                                                <td>{{ $item->services->education_program->name ?? '' }}</td>
                                                <td>{{ __($item->services->service_type ?? '') }}</td>
                                                <td>
                                                    @if (isset($item->services->serviceDetail) && $item->services->serviceDetail->count() > 0)
                                                        @foreach ($item->services->serviceDetail as $detail_service)
                                                            <ul>
                                                                <li>@lang('Số tiền'):
                                                                    {{ isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : '' }}
                                                                </li>
                                                                <li>@lang('Số lượng'):
                                                                    {{ $detail_service->quantity ?? '' }}
                                                                </li>
                                                                <li>@lang('Từ'):
                                                                    {{ isset($detail_service->start_at) ? \Carbon\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '' }}
                                                                </li>
                                                                <li>@lang('Đến'):
                                                                    {{ isset($detail_service->end_at) ? \Carbon\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '' }}
                                                                </li>
                                                            </ul>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    <select class="form-control select2 w-100"
                                                        name="student_services[{{ $item->id }}][payment_cycle_id]">
                                                        @if (isset($payment_cycle) && count($payment_cycle) > 0)
                                                            @foreach ($payment_cycle as $val)
                                                                <option value="{{ $val->id }}"
                                                                    {{ $item->payment_cycle_id == $val->id ? 'selected' : '' }}>
                                                                    {{ $val->name ?? '' }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> @lang('Lưu và chạy lại phí')
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <i class="fa fa-remove"></i> @lang('Close')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal_receipt_transaction" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12">@lang('Danh sách khoản thu của TBP')</h3>
                        </h3>
                    </div>
                    <form action="{{ route('receipt.crud_receipt_transaction') }}" method="POST"
                        id="form_receipt_transaction">
                        @csrf
                        <input type="hidden" name="receipt_id" value="{{ $detail->id }}">
                        <input type="hidden" name="type" value="create">
                        <div class="modal-body show_receipt_transaction">
                            <div class="modal-alert"></div>
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Số tiền thanh toán')</th>
                                        <th>@lang('Ngày thanh toán')</th>
                                        <th>@lang('Ghi chú')</th>
                                        <th>@lang('Thu ngân')</th>
                                    </tr>
                                </thead>
                                <tbody class="box_service">
                                    @if (isset($detail->receiptTransaction) && count($detail->receiptTransaction) > 0)
                                        @foreach ($detail->receiptTransaction as $key => $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ number_format($item->paid_amount, 0, ',', '.') ?? '' }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item->payment_date)) }}</td>
                                                <td>{{ $item->json_params->note ?? '' }}</td>
                                                <td>{{ $item->user_cashier->name ?? '' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">@lang('Chưa có giao dịch nào')</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if ($detail->status == 'approved')
                                <div class="row">
                                    <h4 class="text-center form-group col-md-12">@lang('Thông tin thanh toán cho kỳ này')</h4>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Nhập số tiền thanh toán') <small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="paid_amount"
                                                placeholder="@lang('Nhập số tiền thanh toán')" value="{{ old('paid_amount') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Ngày thanh toán') <small class="text-red">*</small></label>
                                            <input type="date" class="form-control" name="payment_date"
                                                value="" required>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Ghi chú')</label>
                                            <textarea name="json_params[note]" class="form-control" cols="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="modal-footer">
                            @if ($detail->status == 'approved')
                                <button type="submit" class="btn btn-success btn_save_transaction">
                                    <i class="fa fa-save"></i> @lang('Lưu lại')
                                </button>
                            @endif
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <i class="fa fa-remove"></i> @lang('Đóng')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </section>
@endsection
@section('script')
    <script>
        // Thêm giải trình html
        var adjustmentTypes = @json($type);
        $('.btn_explanation').click(function() {
            var lastElement = $('.item_adjustment').last();
            var clonedElement = lastElement.clone();
            // Lấy giá trị `key` cuối cùng từ dòng cuối hoặc gán giá trị thời gian thực
            var currentDateTime = Math.floor(Date.now() / 1000);
            // Điều chỉnh tất cả các input và select trong dòng clone
            clonedElement.find('input, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    // Thay thế key cũ bằng key mới
                    var updatedName = name.replace(/\[\d+\]/, `[${currentDateTime}]`);
                    $(this).attr('name', updatedName); // Cập nhật name
                }
                if ($(this).is('input')) {
                    $(this).val(''); // Reset giá trị input
                } else if ($(this).is('select')) {
                    $(this).prop('selectedIndex', 0); // Chọn option đầu tiên
                }
            });
            // Thêm dòng clone vào bảng
            lastElement.before(clonedElement);
            return;
        })

        // Thay đổi giá trị prev_balance khi các cập nhật giải trình
        $(document).on('click', '.btn_save_adjustment', function() {
            updateBalance();
        })

        function updateBalance() {
            var total = 0;
            $('input.action_change[type="number"]').each(function() {
                if ($(this).parents('tr').find('.check_doisoat').is(':checked') == true) {
                    var value = parseFloat($(this).val()) ||
                        0; // Chuyển giá trị thành số, mặc định 0 nếu không hợp lệ
                    total += value;
                }
            });
            $('.final_amount').each(function() {
                var value = parseFloat($(this).html()) || 0; // Chuyển giá trị thành số, mặc định 0 nếu không hợp lệ
                if ($(this).parents('tr').find('.check_doisoat').is(':checked') == true) {
                    total += value;
                }
            });
            $('.prev_balance').val(total).change();
        }

        //Thay đổi số tiền tổng
        $(document).on('change', '.prev_balance', function() {
            var _balance = parseInt($(this).val(), 10);
            if (isNaN(_balance)) {
                _balance = 0;
            }
            $('.total_prev_balance').html(new Intl.NumberFormat('vi-VN').format(_balance));
            updateJsonExplanation();
        })

        // Hàm cập nhật giải trình lưu lại trong JSON và tính lại số tiền
        function updateJsonExplanation() {
            var _url = $('#form_update_explanation').prop('action')
            var formData = $('#form_update_explanation').serialize();
            show_loading_notification();
            $.ajax({
                type: "POST",
                url: _url,
                data: formData,
                success: function(response) {
                    hide_loading_notification();
                    if (response.data == 'warning') {
                        location.reload();
                    }
                    $('.total_final').html(new Intl.NumberFormat('vi-VN').format(response.data.total_final));
                    $('.total_due').html(new Intl.NumberFormat('vi-VN').format(response.data.total_due));
                },
                error: function(data) {
                    hide_loading_notification();
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        }



        // Cập nhật giải trình khi form được submit
        // $('#form_update_explanation').on('submit', function(event) {
        //     event.preventDefault();
        //     updateJsonExplanation();
        // });


        // Xử lý sự kiện click nút duyệt TBP
        $('.btn_approved').click(function() {
            if (confirm('{{ __('confirm_action') }}')) {
                var _url = "{{ route(Request::segment(2) . '.approved', $detail->id) }}";
                var formData = $('#form_update_explanation').serialize();
                show_loading_notification();
                $.ajax({
                    type: "POST",
                    url: _url,
                    data: formData,
                    success: function(response) {
                        if (response) {
                            hide_loading_notification();
                            window.location.reload();
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert-danger").fadeOut(3000, function() {});
                            }, 800);
                            hide_loading_notification();
                        }
                    },
                    error: function(data) {
                        hide_loading_notification();
                        var errors = data.responseJSON.message;
                        alert(data);
                    }
                });
            }

        });

        $(document).on('click', '.update_student_service', function() {
            var _id = $(this).data('id');
            var _payment_cycle_id = $(this).closest('tr').find('.payment_cycle').val();
            show_loading_notification();
            $.ajax({
                type: "POST",
                url: "{{ route('student.updateService.ajax') }}",
                data: {
                    id: _id,
                    payment_cycle_id: _payment_cycle_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    hide_loading_notification();
                    if (response.message === 'success') {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Cập nhật thành công!
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                    }
                },
                error: function() {
                    hide_loading_notification();
                    alert("Lỗi cập nhật.");
                }
            });
        });

        // Thay đổi kỳ thanh toán
        $('#form_receipt_transaction').on('submit', function(event) {
            event.preventDefault();
            var _url = $(this).prop('action')
            var formData = $(this).serialize();
            show_loading_notification();
            $.ajax({
                type: "POST",
                url: _url,
                data: formData,
                success: function(response) {
                    if (response) {
                        hide_loading_notification();
                        var _html = `<div class="alert alert-${response.data} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ${response.message}
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                        if (response.data == 'success') {
                            location.reload();
                        }

                    } else {
                        hide_loading_notification();
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.modal-alert').prepend(_html);
                        setTimeout(function() {
                            $(".alert").fadeOut(3000, function() {});
                        }, 800);
                    }
                },
                error: function(data) {
                    hide_loading_notification();
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        });

        $('.delete_receipt_detail_and_recalculate').click(function() {
            if (confirm('{{ __('confirm_action') }}')) {
                var receipt_id = $(this).data('receipt');
                var detail_id = $(this).data('id');
                show_loading_notification();
                $.ajax({
                    type: "POST",
                    url: "{{ route('receipt.deletePaymentDetailsAndRecalculate') }}",
                    data: {
                        receipt_id: receipt_id,
                        detail_id: detail_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        hide_loading_notification();
                        if (response) {
                            hide_loading_notification();
                            var _html = `<div class="alert alert-${response.data} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ${response.message}
                            </div>`;
                            $('.box_alert').prepend(_html);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                            if (response.data == 'success') {
                                location.reload();
                            }

                        } else {
                            hide_loading_notification();
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                    },
                    error: function(data) {
                        hide_loading_notification();
                        var errors = data.responseJSON.message;
                        alert(data);
                    }
                });
            }
        })
    </script>
@endsection
