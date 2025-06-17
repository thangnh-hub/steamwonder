@extends('frontend.layouts.default')

@push('style')
    <style>
        .modal-dialog {
            max-width: 80%;
        }

        @media only screen and (max-width: 576px) {
            .table_receipt {
                min-width: 2000px;
            }

            .modal-dialog {
                max-width: 95%;
            }
        }
    </style>
@endpush
@section('content')
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information box-day">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h4 class="box-title d-flex justify-content-between flex-wrap">
                                <span class="mb-3"><i class="fa fa-calendar-check-o"></i> @lang('Thông tin TBP')
                                </span>
                                </h3>
                        </div>
                        <div class="box-body ">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table_receipt">
                                    <thead>
                                        <tr>
                                            <th>@lang('STT')</th>
                                            <th>@lang('Mã TBP')</th>
                                            <th>@lang('Tên TBP')</th>
                                            <th>@lang('Mã học sinh')</th>
                                            <th>@lang('Tên học sinh')</th>
                                            <th>@lang('Lớp')</th>
                                            <th>@lang('Thành tiền')</th>
                                            <th>@lang('Tổng giảm trừ')</th>
                                            <th>@lang('Số dư kỳ trước')</th>
                                            <th>@lang('Tổng tiền thực tế')</th>
                                            <th>@lang('Trạng thái')</th>
                                            <th>@lang('Ghi chú')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows as $row)
                                            <tr class="valign-middle">
                                                <td>
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td>
                                                    <strong style="font-size: 14px">{{ $row->receipt_code ?? '' }}</strong>
                                                </td>
                                                <td>
                                                    {{ $row->receipt_name }}
                                                </td>
                                                <td>
                                                    {{ $row->student->student_code ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $row->student->first_name ?? '' }}
                                                    {{ $row->student->last_name ?? '' }}
                                                    ({{ $row->student->nickname ?? '' }})
                                                </td>
                                                <td>
                                                    {{ optional($row->student->currentClass)->name }}
                                                </td>
                                                <td>
                                                    {{ number_format($row->total_amount, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>
                                                    {{ number_format($row->total_discount, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>
                                                    {{ number_format($row->prev_balance, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>
                                                    {{ number_format($row->total_final, 0, ',', '.') ?? '' }}
                                                </td>
                                                <td>
                                                    {{ __($row->status ?? '') }}
                                                    @if ($row->status == 'paid')
                                                        <br>
                                                        <button class="btn btn_sm btn-success btn_show_tbp"
                                                            data-id = "{{ $row->id }}">@lang('Chi tiết')</button>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $row->note ?? '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="receipt_transaction" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="tbpDetailModalLabel">@lang('Chi tiết thnh toán')</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>@lang('Mã TBP')</th>
                                        <th>@lang('Ngày thanh toán')</th>
                                        <th>@lang('Số tiền')</th>
                                        <th>@lang('Ghi chú')</th>
                                        <th>@lang('Người thu')</th>
                                    </tr>
                                </thead>
                                <tbody id="tbpDetailBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        var receipt = @json($rows);
        $('.btn_show_tbp').click(function() {
            var id = $(this).data('id');
            var receipt_detail = receipt.find(function(item) {
                return item.id == id;
            });
            var tbpDetailBody = $('#tbpDetailBody');
            tbpDetailBody.empty();
            if (receipt_detail && receipt_detail.receipt_transaction && receipt_detail.receipt_transaction.length >
                0) {
                receipt_detail.receipt_transaction.forEach(function(transaction, index) {
                    tbpDetailBody.append(`
                        <tr class="valign-middle">
                            <td>${index + 1}</td>
                            <td>${receipt_detail.receipt_code ?? ''}</td>
                            <td>${transaction.payment_date ? new Date(transaction.payment_date).toLocaleDateString('vi-VN') : ''}</td>
                            <td>${new Intl.NumberFormat('vi-VN', { style: 'decimal' }).format(transaction.paid_amount) ?? ''}</td>
                            <td>${transaction.json_params.note ?? ''}</td>
                            <td>${transaction.user_cashier.name ?? ''} </td>
                        </tr>
                    `);
                });
            } else {
                tbpDetailBody.append(`
                    <tr class="valign-middle">
                        <td colspan="6" class="text-center">@lang('Không có giao dịch nào')</td>
                    </tr>
                `);
            }
            $('#receipt_transaction').modal('show');
        })
    </script>
@endpush
