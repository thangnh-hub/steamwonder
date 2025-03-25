@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
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
            <div class="col-lg-12">
                <div class="box box-primary">
                    <form action="{{ route('transfer_warehouse_approved', $detail->id) }}" method="POST"
                        onsubmit="return confirm('@lang('confirm_action')')">
                        @csrf
                        <div class="box-header with-border text-center">
                            <h3 class="box-title">
                                @lang('Thông tin điều chuyển')
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <p>@lang('Tên phiếu điều chuyển'): {{ $detail->name ?? '' }}</p>
                                    <p>@lang('Kỳ'): {{ $detail->period ?? date('Y-m', time()) }}</p>
                                    <p>@lang('Người đề xuất'): {{ $detail->nguoi_de_xuat->name ?? '' }}</p>
                                    <p>@lang('Ngày đề xuất'):
                                        {{ $detail->day_create != '' ? date('d-m-Y', strtotime($detail->day_create)) : date('Y-m-d', time()) }}
                                    </p>
                                    <p>@lang('Ghi chú giao'): {{ $detail->json_params->note_deliver ?? '' }}</p>
                                    <p>@lang('Ghi chú nhận'): {{ $detail->json_params->note ?? '' }}</p>
                                    <p>@lang('Ngày tạo phiếu'):
                                            {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <h4>Kho giao</h4>
                                    </div>
                                    <p>@lang('Cơ sở'): {{ $detail->area_deliver->name ?? '' }}</p>
                                    <p>@lang('Kho'): {{ $detail->warehouse_deliver->name ?? '' }}</p>
                                    <p>@lang('Người giao'): {{ $detail->nguoi_giao->name ?? '' }}</p>
                                    <p>@lang('Ngày giao'):
                                        {{ $detail->day_deliver != '' ? date('d-m-Y', strtotime($detail->day_deliver)) : '' }}
                                    </p>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <h4>Kho nhận</h4>
                                    </div>
                                    <p>@lang('Cơ sở'): {{ $detail->area->name ?? '' }}</p>
                                    <p>@lang('Kho'): {{ $detail->warehouse->name ?? '' }}</p>
                                    <p>@lang('Người nhận'): {{ $detail->nguoi_nhan->name ?? '' }}</p>
                                    <p>@lang('Ngày nhận'):
                                        {{ $detail->day_entry != '' ? date('d-m-Y', strtotime($detail->day_entry)) : '' }}
                                    </p>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>@lang('Tổng hợp sản phẩm đã nhận')</h4>
                                    </div>
                                    <table id="myTable" class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Sản phẩm')</th>
                                                <th>@lang('ĐVT')</th>
                                                <th>@lang('Số lượng giao')</th>
                                                <th>@lang('Thực nhận')</th>
                                                <th>@lang('Chênh lệch')</th>
                                                <th>@lang('Đơn giá')</th>
                                                <th>@lang('Tổng tiền')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order" id="post_related">
                                            @if ($rows->count() > 0)
                                                @php $total_money=0; @endphp
                                                @foreach ($rows as $key => $row)
                                                    @php $total_money+=$row->subtotal_money; @endphp
                                                    <tr data-product-id="{{ $row->product->id }}"
                                                        data-product-name="{{ $row->product->name }}"
                                                        class="{{ $row->product->warehouse_type == 'taisan' || $row->product->warehouse_type == 'congcudungcu' ? 'product' : '' }} check_all_product valign-middle">
                                                        <td class="order-number">{{ ++$key }}</td>


                                                        <td>{{ $row->product->name ?? '' }}<input type="hidden"
                                                                name="cart[{{ $row->product->id }}][product_id]"
                                                                value="{{ $row->product->id }}"></td>
                                                        <td>{{ $row->product->unit ?? '' }}</td>
                                                        <td>{{ $row->quantity }} <input
                                                                name="cart[{{ $row->product->id }}][quantity]"
                                                                value="{{ $row->quantity }}"
                                                                class="input-field form-control quantity-input"
                                                                type="hidden"></td>
                                                        <td>{{ $row->quantity_entry }} <input
                                                                name="cart[{{ $row->product->id }}][quantity_entry]"
                                                                value="{{ $row->quantity_entry }}"
                                                                class="input-field form-control quantity_entry-input"
                                                                type="hidden"></td>
                                                        <td>{{ $row->quantity_entry > 0 ? (int) $row->quantity - (int) $row->quantity_entry : '' }}
                                                        </td>
                                                        <td>{{ $row->price }} đ
                                                            <input name="cart[{{ $row->product->id }}][price]"
                                                                value="{{ $row->price }}"
                                                                class="input-field form-control price-input" type="hidden">
                                                        </td>
                                                        <td>{{ $row->subtotal_money }} đ</td>
                                                        <td style="display:none"><input checked type="checkbox"
                                                                value="{{ $row->product->id }}"
                                                                class="mr-15 related_post_item2 cursor {{ $row->product->warehouse_type == 'taisan' || $row->product->warehouse_type == 'congcudungcu' ? 'asset' : '' }}"
                                                                autocomplete="off"></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tr>
                                            <td colspan="5">
                                                <strong class="pull-right">TỔNG TIỀN:</strong>
                                            </td>
                                            <td colspan="3">
                                                <strong class="total_money">{{ $total_money }} đ</strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>@lang('Danh sách tài sản tương ứng đã nhận')</h4>
                                    </div>
                                    <table class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Mã Tài Sản')</th>
                                                <th>@lang('Tên tài sản')</th>
                                                <th>@lang('Sản phẩm')</th>
                                                <th>@lang('Số lượng')</th>
                                                <th>@lang('Đơn giá')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <input type="hidden" name="warehouse_id_deliver" value="{{ $detail->warehouse_id_deliver }}">
                            <input type="hidden" name="day_deliver" value="{{ $detail->day_deliver }}">
                            <input type="hidden" name="staff_deliver" value="{{ $detail->staff_deliver }}">
                            <input type="hidden" name="warehouse_id" value="{{ $detail->warehouse_id }}">
                            <input type="hidden" name="staff_entry" value="{{ $detail->staff_entry }}">
                            <input type="hidden" name="day_entry" value="{{ $detail->day_entry }}">

                            @if ($detail->status != 'approved')
                                <button type="submit" class="btn btn-info btn-sm pull-right">
                                    <i class="fa fa-save"></i> @lang('Duyệt phiếu điều chuyển')
                                </button>
                            @else
                                <button type="button" class="btn btn-danger btn-sm pull-right">
                                    <i class="fa fa-save"></i> {{ __($detail->status ?? '') }}
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            show_asset()
        });

        function show_asset() {
            var detail = @json($detail);
            console.log(detail.json_params.list_asset_entry);

            var _array_asset = @json($list_asset_ids);
            var warehouse_id = '';
            if (detail.status == 'approved') {
                warehouse_id = $('.warehouse_avaible').val();
            } else {
                warehouse_id = $('.warehouse_avaible_deliver').val();
            }
            let _selectedValues = [];
            $('.asset:checked').each(function() {
                _selectedValues.push($(this).val());
            });
            let url = "{{ route('warehouse_order_detail_list_id_product_by_order') }}";
            let _targetHTML = $('.tbody-order-asset');
            if (_selectedValues.length > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: _selectedValues,
                        warehouse_id: warehouse_id,
                        status: "{{ App\Consts::WAREHOUSE_ASSET_STATUS['new'] }}",
                        id_asset: _array_asset
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            let _item = '';
                            let index = 1;
                            if (list.length > 0) {
                                list.forEach(item => {
                                    if (_array_asset.includes(item.id.toString())) {
                                        _item += `<tr class="valign-middle

                                        ${detail.status != 'new'? (isIdDuplicate(detail.json_params.list_asset_entry, item.id) ? '' : 'bg-red'):''}
                                        ">
                                            <td>` + index + `</td>
                                            <td>
                                                ` + item.code + `
                                            </td>
                                            <td>
                                                ` + item.name + `
                                            </td>
                                            <td>
                                                ` + item.product.name + `
                                            </td>
                                            <td>
                                                ` + item.quantity + `
                                            </td>
                                            <td>
                                                ` + formatCurrency(item.price) + `
                                            </td>
                                        </tr>`;
                                        index++;
                                    }
                                });
                                _targetHTML.html(_item);
                                $(".select2").select2();
                            }

                        } else {
                            _targetHTML.html(
                                '<tr><td colspan="8"><strong>Không tìm thấy bản ghi</strong></td></tr>');
                        }
                        _targetHTML.trigger('change');
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        }

        function isIdDuplicate(array, id) {
            var check = false;
            const listid = Object.values(array);
            listid.forEach(item => {
                if (Number(item.id) == Number(id)) {
                    check = true;
                }
            });
            return check;
        }
    </script>
@endsection
