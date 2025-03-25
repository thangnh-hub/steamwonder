@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)

        </h1>
    </section> --}}

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

        <form id="myFormDeliver" onsubmit="return confirm('@lang('confirm_action')')" role="form"
            action="{{ route('deliver_warehouse.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title text-uppercase">@lang($module_name)</h3>
                        </div>
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở')<small class="text-red">*</small></label>
                                        <select required class="area_id form-control" name="area_id" autocomplete="off">
                                            <option value="">Chọn</option>
                                            @foreach ($list_area as $key => $val)
                                                <option value="{{ $val->id }}"
                                                    {{ $area_selected > 0 && $area_selected == $val->id ? 'selected' : '' }}>
                                                    {{ $val->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kho xuất')<small class="text-red">*</small></label>
                                        <select required name="warehouse_id_deliver" class="warehouse_avaible form-control"
                                            autocomplete="off">
                                            <option value="">Chọn</option>
                                            @foreach ($list_warehouse as $key => $val)
                                                <option value="{{ $val->id }}"
                                                    {{ isset($order_selected) && $order_selected->warehouse_id == $val->id ? 'selected' : '' }}>
                                                    {{ $val->name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Phiếu đề xuất order')<small class="text-red">*</small></label>
                                        <select required name="order_id" class="order_id form-control" autocomplete="off">
                                            <option value="">Chọn</option>
                                            @isset($list_order)
                                                @foreach ($list_order as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name . ' (' . $item->code . ')' }}
                                                    </option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu xuất kho') <small class="text-red">*</small></label>
                                        <input type="text" value="{{ old('name') ?? '' }}" class="form-control"
                                            name="name" placeholder="@lang('Tên phiếu xuất kho')" value="{{ old('name') }}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                        <input required type="month" class="form-control" name="period"
                                            value="{{ old('period') ?? date('Y-m', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người tạo phiếu') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control"
                                            value="{{ $admin_auth->name . ' (' . $admin_auth->admin_code . ')' }}"
                                            disabled>
                                        <input type="hidden" class="form-control" name="staff_request"
                                            value="{{ $admin_auth->id }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày xuất') <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="day_deliver"
                                            value="{{ old('day_deliver') ?? date('Y-m-d', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày nhận') <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="day_entry"
                                            value="{{ old('day_entry') ?? date('Y-m-d', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4 style="padding-bottom: 10px;">Danh sách sản phẩm xuất kho</h4>
                                    <table id="myTable" class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Sản phẩm')</th>
                                                <th>@lang('ĐVT')</th>
                                                <th>@lang('Loại')</th>
                                                <th>@lang('Số lượng')</th>
                                                <th>@lang('Đơn giá')</th>
                                                <th>@lang('Tổng tiền')</th>
                                                <th>@lang('Tồn kho')</th>
                                                <th>@lang('Chọn')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order" id="post_related">

                                        </tbody>
                                        <tr>
                                            <td colspan="6">
                                                <strong class="pull-right">TỔNG TIỀN:</strong>
                                            </td>
                                            <td colspan="3">
                                                <strong class="total_money"></strong>
                                                <input type="hidden" name="total_money" value=""
                                                    class="total_money_input">
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-12">
                                    <h4 style="padding-bottom: 10px">
                                        @lang('Danh sách tài sản của kho'): <span class="change_text_warehouse"></span>
                                    </h4>

                                    <table class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Mã Tài Sản')</th>
                                                <th>@lang('Tên tài sản')</th>
                                                <th>@lang('Sản phẩm')</th>
                                                <th>@lang('Số lượng')</th>
                                                <th>@lang('Đơn giá')</th>
                                                <th>@lang('Phòng ban')</th>
                                                <th>@lang('Vị trí')</th>
                                                <th>@lang('Chọn')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">

                                        </tbody>

                                    </table>
                                </div>
                                <div class="col-md-12 error_html_result">

                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route('deliver_warehouse') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button class="btn btn-sm btn-info save-order pull-right">
                                <i class="fa fa-save"></i>
                                @lang('Lưu thông tin')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    @isset($order_selected)
        <script>
            // Phần này bổ sung để check gọi sự kiện onchange cho tạo phiếu nếu có order_id đc gửi sang

            $(document).ready(function() {
                const $select = $('.order_id');
                // Thay đổi giá trị của select
                const order_id = {{ $order_selected->id }}; // Giá trị muốn chọn
                $select.val(order_id);
                // Gửi sự kiện change
                $select.trigger('change');
            });
        </script>
    @endisset
    <script>
        function updateOrderNumbers() {
            $('.tbody-order tr').each(function(index) {
                $(this).find('.order-number').text(index + 1);
            });
        }

        function deleteOrder(th) {
            let ischecked = $(th).is(':checked');
            let id_product = $(th).val();
            if (!ischecked) {
                $(th).parents('tr').remove();
                updateOrderNumbers();
                calculateTotalMoney();
                show_asset();
            }
        }

        function show_asset() {
            var warehouse_id = $('.warehouse_avaible').val();
            let _selectedValues = [];
            $('.asset:checked').each(function() {
                _selectedValues.push($(this).val());
            });
            let url = "{{ route('warehouse_order_detail_list_id_product_by_order') }}"; //láy danh sách tài sản
            let _targetHTML = $('.tbody-order-asset');
            $('.change_text_warehouse').text($('.warehouse_avaible option:selected').text());
            if (_selectedValues.length > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: _selectedValues,
                        warehouse_id: warehouse_id,
                        status: "{{ App\Consts::WAREHOUSE_ASSET_STATUS['new'] }}",
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            let _item = '';
                            let index = 1;
                            if (list.length > 0) {
                                function formatCurrency(amount) {
                                    if (!amount || isNaN(amount)) return "";
                                    return new Intl.NumberFormat('vi-VN', {
                                        style: 'currency',
                                        currency: 'VND'
                                    }).format(amount).replace('₫', 'đ');
                                }
                                list.forEach(item => {
                                    let department_name = '';
                                    if (typeof item.department !== 'undefined' && item.department !==
                                        null) {
                                        department_name = item.department.name;
                                    }
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
                                                ` + formatCurrency(item.price) + `
                                            </td>
                                            <td>
                                                ` + department_name + `
                                            </td>
                                            <td>`;
                                    // Kiểm tra điều kiện nếu item.options có phần tử
                                    if (item.position_by_warehouse && item.position_by_warehouse
                                        .length > 0) {
                                        _item += `<select style="width: 100%;" name="asset[` + index + `][position]" class="form-control select2">
                                                                <option value="">Chọn</option>`;
                                        item.position_by_warehouse.forEach(option => {

                                            if (option.id == item.position_id) {
                                                _item +=
                                                    `<option selected value="${option.id}">${option.name ?? ""}</option>`;
                                            } else {
                                                _item +=
                                                    `<option value="${option.id}">${option.name ?? ""}</option>`;
                                            }
                                        });
                                        _item += `</select>`;
                                    } else {
                                        _item += `Chưa có lựa chọn`;
                                    }
                                    _item += `</td>
                                            <td>
                                                <input name="asset[` + index +
                                        `][id]" class="mr-15 cursor asset_input_` + item.product.id + `" 
                                                    type="checkbox" value="` + item.id + `" autocomplete="off">
                                            </td>
                                        </tr>`;
                                    index++;

                                });
                                _targetHTML.html(_item);
                                $(".select2").select2();
                            }

                        } else {
                            _targetHTML.html(
                                '<tr><td colspan="9"><strong>Không tìm thấy bản ghi</strong></td></tr>');
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

            function formatCurrency(amount) {
                if (!amount || isNaN(amount)) return "";
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount).replace('₫', 'đ');
            }
            $('.total_money').text(formatCurrency(total_money));
            $('.total_money_input').val(total_money);
        }

        $('#myTable').on('input', '.quantity-input, .price-input', function() {
            calculateTotal();
            calculateTotalMoney();
        });

        $('.order_id').change(function() {
            $('.tbody-order-asset').html('');
            var _id = $(this).val();
            var _warehouse_id_deliver = $('.warehouse_id_deliver').val();
            let url = "{{ route('warehouse_order_detail_by_order') }}";
            let _targetHTML = $('.tbody-order');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: _id,
                    warehouse_id: _warehouse_id_deliver,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '';
                        let index = 1;
                        if (list.length > 0) {
                            let index = 1;
                            list.forEach(item => {
                                _item += `<tr class="check_all_product valign-middle ${item.product.warehouse_type == "taisan" || item.product.warehouse_type == "congcudungcu" ? `product` : ""}" 
                    ${item.product.warehouse_type == "taisan" || item.product.warehouse_type == "congcudungcu" ? `data-product-id="${item.product_id}" data-product-name="${item.product.name}"` : ""} >
                                    <td class="order-number">${index++}</td>
                                    <td>
                                        <p class="ten_moi_sp">` + item.product.name + `</p>
                                        <input value="` + item.product_id + `" type="hidden"  name="cart[` + item
                                    .product_id + `][product_id]">
                                    </td>
                                    <td>
                                        ` + item.product.unit + `
                                    </td>
                                    <td>
                                        ` + item.warehouse_type_text + `
                                    </td>
                                    <td>
                                        <input name="cart[` + item.product_id + `][quantity]" value="` + item
                                    .quantity + `" class="input-field form-control quantity-input" type="number" readonly>
                                    </td>
                                    <td>
                                        <input name="cart[` + item.product_id + `][price]" value="` + item.price + `" class="input-field form-control price-input" type="number" >
                                    </td>
                                    <td>
                                        <input name="cart[` + item.product_id + `][subtotal_money]" value="` + item
                                    .subtotal_money + `" class="total form-control" type="text" readonly >
                                    </td>
                                    <td>
                                        <p class="ton_kho_moi_sp">` + item.ton_kho + `</p>
                                    </td>
                                    <td><input disabled checked type="checkbox" value="` + item
                                    .product_id + `" class="mr-15 related_post_item2 cursor ${item.product.warehouse_type == "taisan" || item.product.warehouse_type == "congcudungcu" ? `asset` : ""}" autocomplete="off"></td>
                                </tr>`;
                            });
                            _targetHTML.html(_item);
                        }
                        $(".select2").select2();
                        calculateTotalMoney()
                        show_asset()
                    } else {
                        _targetHTML.html(
                            '<tr><td colspan="9"><strong>Không tìm thấy bản ghi</strong></td></tr>');
                    }
                    // _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })

        $('.area_id').change(function() {
            $('#post_related').html('');
            $('.tbody-order-asset').html('');
            var _id = $(this).val();
            let url = "{{ route('warehouse_by_area') }}";
            let _targetHTML = $('.warehouse_avaible');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: _id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '<option value="">@lang('Please select')</option>';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<option value="' + item.id + '">' + item
                                    .name + '</option>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<option value="">@lang('Please select')</option>');
                    }
                },
                error: function(response) {
                    // Get errors
                    // let errors = response.responseJSON.message;
                    // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        })
        $('.warehouse_avaible').change(function() {
            $('#post_related').html('');
            $('.tbody-order-asset').html('');
            calculateTotalMoney();


            let _warehouse_id = $('.warehouse_avaible').val();
            let url = "{{ route('order_by_warehouse') }}";
            let _targetHTML = $('.order_id');
            if (_warehouse_id > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        warehouse_id: _warehouse_id,
                        status: [
                            "{{ App\Consts::APPROVE_WAREHOUSE_ORDER['approved'] }}",
                            "{{ App\Consts::APPROVE_WAREHOUSE_ORDER['in order_buy'] }}"
                        ],
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            let _item = '<option value="">@lang('Please select')</option>';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<option value="' + item.id + '">' + item
                                        .name + '</option>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<option value="">@lang('Please select')</option>');
                        }
                    },
                    error: function(response) {
                        // Get errors
                        // let errors = response.responseJSON.message;
                        // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            }
        })
        $('#myFormDeliver').submit(function(e) {
            var _flag = true;
            var _error_text = 'Cảnh báo:';
            var _error_html = $('.error_html_result');
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
                    _error_text += '<br/>- Vui lòng chọn đúng số lượng sản phẩm ' + productName;
                    _flag = false;
                }
            });
            $('.check_all_product').each(function() {
                // Lấy số lượng sản phẩm
                let quantity_each_product = parseInt($(this).find('.quantity-input').val());

                // Đếm số checkbox được chọn trong nhóm tài sản
                let check_ton_kho = parseInt($(this).find('.ton_kho_moi_sp').text());
                let productNameEachProduct = $(this).find('.ten_moi_sp').text()
                // Kiểm tra điều kiện
                if (quantity_each_product > check_ton_kho) {
                    _error_text += '<br/>- Tồn kho của ' + productNameEachProduct + ' không đủ';
                    _flag = false;
                }
            });
            if (!_flag) {
                e.preventDefault();

                _error_text =
                    '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                    _error_text + '</div>';
                _error_html.html(_error_text);
            }
        });
    </script>
@endsection