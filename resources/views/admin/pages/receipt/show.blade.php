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
        ..select2-container{
            width: 100% !important;
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
                                                    {{ $detail->status == 'pending' ? '' : 'disabled' }}
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
                                                                onclick="$(this).closest('tr').remove()"
                                                                title="@lang('Delete')"
                                                                data-original-title="@lang('Delete')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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
                                            <th colspan="7" class="text-left"><b>2. Phí dự kiến</b></th>
                                            <th class="text-right">
                                                <button data-toggle="modal" data-target="#modal_show_service"
                                                    class="btn btn-warning">@lang('Thay đổi kỳ tính phí cho HS')</button>
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
                                                <td>{!! $item->note ?? '' !!}</td>
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
                                                {{ $detail->status == 'approved' ? '' : 'disabled' }}
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
                                    <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> @lang('Xác nhận thanh toán')
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
                                                    <select class="form-control select2 w-100" name="student_services[{{ $item->id }}][payment_cycle_id]">
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

        $('.btn_approved').click(function() {
            if (confirm('{{ __('confirm_action') }}')) {
                var _url = "{{ route(Request::segment(2) . '.approved', $detail->id) }}";
                var formData = $('#form_update_explanation').serialize();
                $.ajax({
                    type: "POST",
                    url: _url,
                    data: formData,
                    success: function(response) {
                        if (response) {
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
                        }
                    },
                    error: function(data) {
                        var errors = data.responseJSON.message;
                        alert(data);
                    }
                });
            }

        });

        $(document).on('click', '.update_student_service', function() {
            var _id = $(this).data('id');
            var _payment_cycle_id = $(this).closest('tr').find('.payment_cycle').val();
            $.ajax({
                type: "POST",
                url: "{{ route('student.updateService.ajax') }}",
                data: {
                    id: _id,
                    payment_cycle_id: _payment_cycle_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
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
                    alert("Lỗi cập nhật.");
                }
            });
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
