@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section> --}}
@endsection

@section('content')
    <section class="content">
        @if (session('errorMessage'))
            <div class="alert alert-warning alert-dismissible hide-print">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('errorMessage') }}
            </div>
        @endif
        @if (session('successMessage'))
            <div class="alert alert-success alert-dismissible hide-print">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('successMessage') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible hide-print">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title text-uppercase">@lang($module_name)</h3>
                        <a class="btn btn-sm btn-success pull-right hide-print"
                            href="{{ route(Request::segment(2) . '.index') }}">
                            <i class="fa fa-bars"></i> @lang('List')
                        </a>
                        <button class="btn btn-sm btn-warning mr-10 pull-right hide-print" onclick="window.print()"><i
                                class="fa fa-print"></i>
                            @lang('In phiếu order')</button>

                        @if ($detail->status !== 'out warehouse' && $detail->status !== 'not approved')
                            <a href="{{ route('deliver_warehouse.create', ['order_id' => $detail->id]) }}" target="_blank"
                                rel="noopener noreferrer" class="btn btn-sm btn-primary mr-10 pull-right hide-print">
                                Xuất kho
                                <i class="fa fa-sign-in"></i>
                            </a>
                        @endif
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    @lang('Cở sở'):
                                    {{ $detail->area->name ?? ($detail->warehouse->area->name ?? '') }}
                                    / {{ $detail->warehouse->name ?? '' }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Kỳ'): {{ $detail->period ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Tên phiếu'): {{ $detail->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Mã phiếu'): {{ $detail->code ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Phòng đề xuất'): {{ $detail->department->name ?? '' }}</p>
                            </div>

                            <div class="col-xs-6">
                                <p>@lang('Người đề xuất'): {{ $detail->staff->name ?? '' }} ({{$detail->confirmed=='da_nhan'?'Đã nhận':'Chưa nhận'}})</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày đề xuất'):
                                    {{ \Carbon\Carbon::parse($detail->day_create)->format('d/m/Y') ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Trạng thái'): {{ __($detail->status) }}</p>
                            </div>
                            <div class="col-xs-12">
                                <p>{{ $detail->json_params->note ?? '' }}</p>
                            </div>
                            @isset($detail->orderDetails)
                                <div class="col-md-12">
                                    <table id="myTable" class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:50px">@lang('STT')</th>
                                                <th class="text-center">@lang('Sản phẩm')</th>
                                                <th class="text-center" style="width:100px">@lang('ĐVT')</th>
                                                <th class="text-center" style="width:75px">@lang('Số lượng')</th>
                                                <th class="text-center" style="width:100px">@lang('Đơn giá')</th>
                                                <th class="text-center">@lang('Tổng tiền')</th>
                                                <th class="text-center">@lang('Bộ phận ')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order">
                                            @foreach ($detail->orderDetails as $key => $row)
                                                <tr class="valign-middle">
                                                    <td class="text-center">
                                                        {{ $loop->index + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $row->product->name ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="unit">{{ $row->product->unit ?? '' }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $row->quantity }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : '' }}

                                                    </td>
                                                    <td class="text-center">
                                                        {{ isset($row->subtotal_money) && is_numeric($row->subtotal_money) ? number_format($row->subtotal_money, 0, ',', '.') : '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $row->departmentInfor->name ?? '' }}
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tr>
                                            <td colspan="5">
                                                <strong class="pull-right">TỔNG TIỀN:</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong
                                                    class="total_money">{{ isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . ' đ' : '' }}</strong>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endisset
                            <div class="col-md-12 show-print">
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    @lang('Phòng HCNS')
                                </div>
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    @lang('Người đề nghị')
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer hide-print">
                        <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                            <i class="fa fa-bars"></i> @lang('List')
                        </a>
                        @if ($detail->status == 'not approved')
                            <button data-id="{{ $detail->id }}" type="button"
                                class= "approve_order btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Duyệt phiếu')
                            </button>
                        @else
                            <button type="button" class= "btn btn-danger btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang($detail->status)
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $('.approve_order').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn duyệt đề xuất này ?')) {
                let _id = $(this).attr('data-id');
                let url = "{{ route('warehouse_order.approve') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
    </script>
@endsection
