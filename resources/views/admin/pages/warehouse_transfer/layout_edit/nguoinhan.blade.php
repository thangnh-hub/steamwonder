@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box-alert">
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
        </div>

        <form id="myFormTranfer" role="form" action="{{ route('transfer_warehouse_received_update', $detail->id) }}"
            method="POST" onsubmit="return confirm('@lang('confirm_action')')">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Kho giao')</h3>
                        </div>
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở giao')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $detail->area_deliver->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kho giao')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control warehouse_avaible_deliver"
                                            value="{{ $detail->warehouse_deliver->name }}"
                                            data-id = "{{ $detail->warehouse_deliver->id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người giao')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $detail->nguoi_giao->name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày giao') <small class="text-red">*</small></label>
                                        <input readonly type="date" class="form-control"
                                            value="{{ $detail->day_deliver }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Kho nhận') </h3>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở nhận')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $detail->area->name }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kho nhận')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $detail->warehouse->name }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người nhận')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $detail->nguoi_nhan->name }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày nhận') <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="day_entry"
                                            value="{{ $detail->day_entry }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Thông tin điều chuyển')</h3>
                        </div>
                        <div class="box-body">
                            <div class="d-flex-wap">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu điều chuyển') <small class="text-red">*</small></label>
                                        <input readonly required type="text" class="form-control"
                                            placeholder="@lang('Tên điều chuyển')" value="{{ $detail->name ?? old('name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                        <input readonly required type="month" class="form-control"
                                            value="{{ $detail->period ?? date('Y-m', time()) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người đề xuất')<small class="text-red">*</small></label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $detail->nguoi_de_xuat->name }}">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày đề xuất') </label>
                                        <input readonly required type="date" class="form-control"
                                            value="{{ $detail->day_create ?? date('Y-m-d', time()) }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú giao')</label>
                                        <textarea readonly class="form-control" rows="3">{{ $detail->json_params->note_deliver ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú nhận')</label>
                                        <textarea name="note" class="form-control" rows="3">{{ $detail->json_params->note ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4 class="box-title">Tổng hợp sản phẩm đã nhận</h4>
                                    </div>
                                    <table id="myTable" class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Sản phẩm')</th>
                                                <th>@lang('ĐVT')</th>
                                                <th>@lang('Số lượng')</th>
                                                <th>@lang('Đơn giá')</th>
                                                <th>@lang('Tổng tiền')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order" id="post_related">
                                            @if ($rows->count() > 0)
                                                @foreach ($rows as $key => $row)
                                                    <tr data-product-id="{{ $row->product->id }}"
                                                        data-product-name="{{ $row->product->name }}"
                                                        class="{{ $row->product->warehouse_type == 'taisan' || $row->product->warehouse_type == 'congcudungcu' ? 'product' : '' }} check_all_product valign-middle">
                                                        <td class="order-number">{{ ++$key }}</td>
                                                        <td>{{ $row->product->name ?? '' }}<input type="hidden"
                                                                name="cart[{{ $row->product->id }}][product_id]"
                                                                value="{{ $row->product->id }}"></td>
                                                        <td>{{ $row->product->unit ?? '' }}</td>
                                                        <td><input name="cart[{{ $row->product->id }}][quantity]"
                                                                value="{{ $row->quantity }}"
                                                                class="input-field form-control quantity-input"
                                                                type="number"></td>
                                                        <td><input readonly name="cart[{{ $row->product->id }}][price]"
                                                                value="{{ $row->price }}"
                                                                class="input-field form-control price-input"
                                                                type="number">
                                                        </td>
                                                        <td><input name="cart[{{ $row->product->id }}][subtotal_money]"
                                                                value="{{ $row->subtotal_money }}"
                                                                class="total form-control" type="text" value=""
                                                                readonly></td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tr>
                                            <td colspan="5">
                                                <strong class="pull-right">TỔNG TIỀN:</strong>
                                            </td>
                                            <td colspan="3">
                                                <strong class="total_money">0</strong>
                                                <input type="hidden" name="total_money" value=""
                                                    class="total_money_input">
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
                                                <th>@lang('ĐƠN GIÁ')</th>
                                                <th>@lang('Chọn')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">

                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="false">&times;</button>
                                <p>Việc xác nhận đồng nghĩa với việc đã nhận tài sản và không thể hoàn
                                    tác hay chỉnh sửa</p>
                            </div>

                            <div>
                                <p class="text-rest"></p>
                            </div>
                            <a class="btn btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Xác nhận nhận đơn')
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            calculateTotalMoney()
            show_asset()
            $('#myTable').on('input', '.quantity-input, .price-input', function() {
                calculateTotal();
                calculateTotalMoney();
            });
            $('#myFormTranfer').submit(function(e) {
                var _flag = true;
                var _html = '';
                $('.product').each(function() {
                    // Lấy ID sản phẩm
                    let productId = $(this).data('product-id');
                    let productName = $(this).data('product-name');

                    // Lấy số lượng sản phẩm
                    let quantity = parseInt($(this).find('.quantity-input').val());

                    // Đếm số checkbox được chọn trong nhóm tài sản
                    let checkedAssets = $('.asset_input_' + productId + ':checked').length;

                    // Kiểm tra điều kiện
                    if (quantity != checkedAssets) {
                        _html += `<div class="alert alert-warning alert-dismissible">
                        Cảnh báo: Vui lòng chọn đúng số lượng sản phẩm ` + productName + `
                        </div>`;
                        _flag = false;
                    }
                });
                $('.box-alert').prepend(_html);
                setTimeout(function() {
                    if ($(".alert-warning").length) {
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                    }
                }, 50);
                setTimeout(function() {
                    $(".alert-warning").fadeOut(2000, function() {});
                }, 5000);
                if (!_flag) {
                    e.preventDefault();
                }
            });
        });

        function calculateTotal() {
            $('#myTable tr').each(function() {
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();
                const total = Number(quantity * price);

                $(this).find('.total').val(total);
            });
        }

        function calculateTotalMoney() {
            var total_money = 0;
            $('.total').each(function() {
                var total = $(this).val();
                total_money += Number(total)
            });
            $('.total_money').text(formatCurrency(total_money));
            $('.total_money_input').val(total_money);
        }


        function updateOrderNumbers() {
            $('.tbody-order tr').each(function(index) {
                $(this).find('.order-number').text(index + 1);
            });
        }
        //hiển thị tài sản theo sản phẩm
        function show_asset() {
            var _array_asset = @json($list_asset_ids);
            var warehouse_id = $('.warehouse_avaible_deliver').data('id');
            let _selectedValues = [];

            $('.check_all_product ').each(function() {
                _selectedValues.push($(this).data('product-id'));
            });
            let url = "{{ route('warehouse_order_detail_list_id_product_by_order') }}"; //láy danh sách tài sản
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
                                        _item += `<tr class="valign-middle">
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
                                                    ` + formatCurrency(item.price) + `</p>
                                                </td>

                                                <td>
                                                    <input checked name="asset[` + index +
                                            `][id]" class="mr-15 cursor asset_input_` + item.product
                                            .id + `"
                                                        type="checkbox" value="` + item.id + `" autocomplete="off">
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
    </script>
@endsection
