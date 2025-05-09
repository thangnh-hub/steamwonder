<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_thongtin" data-toggle="tab">
                <h5 class="fw-bold">Thông tin TBP</h5>
            </a>
        </li>
        <li class="">
            <a href="#tab_dichvu" data-toggle="tab">
                <h5 class="fw-bold">Dịch vụ kèm theo</h5>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_thongtin">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Mã TBP')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->receipt_code ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Tên TBP')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->receipt_name ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Khu vực')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->area->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Hoc sinh')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->student->student_code ?? ('' . ' - ' . $detail->student->first_name ?? ('' . ' ' . $detail->student->last_name ?? '')) }}({{ $detail->student->nickname }})
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Chu kỳ thanh toán')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->payment_cycle->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Số tiên cần thu')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ number_format($detail->total_amount, 0, ',', '.') ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Tổng giảm trừ')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ number_format($detail->total_discount, 0, ',', '.') ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Số dư kỳ trước')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ number_format($detail->prev_balance, 0, ',', '.') ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Tổng tiền thực tế')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ number_format($detail->total_final + $detail->prev_balance, 0, ',', '.') ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Đã thu')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ number_format($detail->total_paid, 0, ',', '.') ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Số tiền còn phải thu (+) hoặc thừa (-)')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ number_format($detail->total_due + $detail->prev_balance, 0, ',', '.') ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Trạng thái')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->status }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <label class="control-label"><strong>@lang('Ghi chú')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                <p>{{ $detail->note }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Ngày tạo')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ date('H:i - d/m/Y', strtotime($detail->created_at)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Người tạo')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ $detail->admin_created->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Ngày cập nhật')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ date('H:i - d/m/Y', strtotime($detail->updated_at)) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong>@lang('Người cập nhật')</strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p>{{ $detail->admin_updated->name ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane " id="tab_dichvu">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box" style="border-top: 3px solid #d2d6de;">
                        <div class="box-body no-padding">
                            <div class="table-responsive table-wrapper">
                                <table class="table table-hover sticky ">
                                    <thead>
                                        <tr>
                                            <th>@lang('Tên dịch vụ')</th>
                                            <th>@lang('Loại dịch vụ')</th>
                                            <th>@lang('Tháng áp dụng')</th>
                                            <th>@lang('Số lượng ')</th>
                                            {{-- <th>@lang('Số lượng thực tế')</th> --}}
                                            <th>@lang('Đơn giá')</th>
                                            <th>@lang('Thành tiền')</th>
                                            <th>@lang('Giảm trừ')</th>
                                            {{-- <th>@lang('Truy thu (+) / Hoàn trả (-)')</th> --}}
                                            <th>@lang('Tổng tiền cuối cùng')</th>
                                            {{-- <th>@lang('Trạng thái')</th> --}}
                                            <th style="width:250px">@lang('Ghi chú')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="box_policies">
                                        @isset($detail->receiptDetail)
                                            @foreach ($detail->receiptDetail as $item)
                                                <tr class="item_policies">
                                                    <td>{{ $item->services_receipt->name ?? '' }}</td>
                                                    <td>{{ __($item->services_receipt->service_type) ?? '' }}</td>
                                                    <td>{{ date('m-Y', strtotime($item->month)) }}</td>
                                                    <td>{{ $item->by_number ?? 0 }}</td>
                                                    {{-- <td>{{ $item->spent_number ?? 0 }}</td> --}}
                                                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->amount, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($item->discount_amount, 0, ',', '.') }}</td>
                                                    {{-- <td>{{ number_format($item->adjustment_amount, 0, ',', '.') }}</td> --}}
                                                    <td>{{ number_format($item->final_amount, 0, ',', '.') }}</td>
                                                    {{-- <td>{{ __($item->status) }}</td> --}}
                                                    <td>{!! $item->note !!}</td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
