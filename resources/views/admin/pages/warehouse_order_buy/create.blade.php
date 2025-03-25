@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section> --}}

    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            @csrf

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title text-uppercase">@lang('Tạo mới đề xuất mua sắm')</h3>
                        </div>
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở')<small class="text-red">
                                                (@lang('Chọn chính xác CS vì thay đổi cơ sở sẽ reset toàn bộ phiếu và sản phẩm đã chọn bên dưới'))*</small></label>
                                        <select name="area_id" class="area_id form-control " autocomplete="off">
                                            <option value="">Chọn</option>
                                            @foreach ($list_area as $key => $val)
                                                <option value="{{ $val->id }}"
                                                    {{ $admin_auth->area_id == $val->id ? 'selected' : '' }}>
                                                    @lang($val->name ?? '')</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kho')<small class="text-red">
                                                (@lang('Chọn chính xác kho vì thay đổi kho sẽ reset toàn bộ phiếu và sản phẩm đã chọn bên dưới'))*</small></label>
                                        <select required name="warehouse_id" class="warehouse_avaible form-control ">
                                            <option value="">Chọn</option>
                                            @foreach ($list_warehouse as $key => $val)
                                                <option value="{{ $val->id }}">
                                                    @lang($val->name ?? '')
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu đề xuất') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('Tên đề xuất')" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                        <input readonly type="month" class="form-control" name="period"
                                            value="{{ date('Y-m', time()) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người tạo đề xuất') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control"
                                            value="{{ $admin_auth->name . ' (' . $admin_auth->admin_code . ')' }}" disabled>
                                        <input type="hidden" class="form-control" name="staff_request"
                                            value="{{ $admin_auth->id }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Phòng ban tạo') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control"
                                            value="{{ $admin_auth->department->name ?? '' }}" disabled>
                                        <input type="hidden" class="form-control" name="department_request"
                                            value="{{ $admin_auth->department_id }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày đề xuất') <small class="text-red">*</small></label>
                                        <input readonly type="date" class="form-control" name="day_create"
                                            value="{{ $detail->day_create ?? date('Y-m-d', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Trạng thái')</label>
                                        <select name="status" class=" form-control select2">
                                            <option value="not approved">
                                                @lang('Chưa duyệt')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú')</label>
                                        <textarea name="json_params[note]" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4 style="padding-bottom: 10px">@lang('Danh sách đề xuất order đã duyệt cần xử lý')</h4>
                                    <table class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Kho')</th>
                                                <th>@lang('Mã phiếu')</th>
                                                <th>@lang('Tên phiếu đề xuất')</th>
                                                <th>@lang('Tổng sản phẩm')</th>
                                                <th>@lang('Tổng tiền')</th>
                                                <th>@lang('Phòng')</th>
                                                <th>@lang('Trạng thái')</th>
                                                <th>@lang('Người đề xuất')</th>
                                                <th>@lang('Ngày đề xuất')</th>
                                                <th>@lang('Chọn')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-order">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h4 style="padding-bottom:10px;">Tìm sản phẩm</h4>
                                            <div style="padding-bottom: 5px">
                                                <div style="padding-left: 0px" class="col-md-6">
                                                    <select style="width:100%" class="form-control select2"
                                                        name="" id="search_code_post">
                                                        <option value="">Danh mục sản phẩm...</option>
                                                        @foreach ($category_products as $category_product)
                                                            @if ($category_product->category_parent == null || $category_product->category_parent == 0)
                                                                <option value="{{ $category_product->id }}">
                                                                    {{ $category_product->name ?? '' }}</option>
                                                                @isset($category_product->children)
                                                                    @foreach ($category_product->children as $child_1)
                                                                        <option value="{{ $child_1->id }}">
                                                                            -- {{ $child_1->name ?? '' }}</option>
                                                                        @isset($child_1->children)
                                                                            @foreach ($child_1->children as $child_2)
                                                                                <option value="{{ $child_2->id }}">
                                                                                    ---- {{ $child_2->name ?? '' }}</option>
                                                                            @endforeach
                                                                        @endisset
                                                                    @endforeach
                                                                @endisset
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="input-group col-md-6">
                                                    <input type="text" id="search_title_post"
                                                        class="form-control pull-right"
                                                        placeholder="Tên sản phẩm, mã sản phẩm..." autocomplete="off">
                                                    <div class="input-group-btn">
                                                        <button type="button" class="btn btn-default btn_search">
                                                            <i class="fa fa-search"></i> Tìm SP
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-config-overflow box-body table-responsive no-padding">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Danh mục</th>
                                                            <th>Mã Sp</th>
                                                            <th>Tên Sp</th>
                                                            <th>Tồn kho hiện tại</th>
                                                            <th>Chọn</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="post_available">

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h4 style="padding-bottom:10px;">Tổng hợp sản phẩm mua sắm</h4>
                                            <table id="myTable"
                                                class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('STT')</th>
                                                        <th>@lang('Sản phẩm')</th>
                                                        <th>@lang('ĐVT')</th>
                                                        <th>@lang('Số lượng')</th>
                                                        <th>@lang('Đơn giá')</th>
                                                        <th>@lang('Tổng tiền')</th>
                                                        <th>@lang('Tồn kho hiện tại')</th>
                                                        <th>@lang('Chọn')</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-order" id="post_related">

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
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">
                                <i class="fa fa-save"></i> @lang('Lưu thông tin')
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
        });

        function calculateTotalMoney() {
            var total_money = 0;
            $('.total').each(function() {
                var total = $(this).val();
                total_money += Number(total)
            });
            $('.total_money').text(formatCurrency(total_money));
            $('.total_money_input').val(total_money);
        }

        function deleteOrder(th) {
            let ischecked = $(th).is(':checked');
            if (!ischecked) {
                $(th).parents('tr').remove()
                updateOrderNumbers()
                calculateTotalMoney();
            }
        }

        function updateOrderNumbers() {
            $('.tbody-order tr').each(function(index) {
                $(this).find('.order-number').text(index + 1);
            });
        }

        function calculateTotal() {
            $('#myTable tr').each(function() {
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();
                const total = Number(quantity * price);

                $(this).find('.total').val(total);
            });
        }
        $('#myTable').on('input', '.quantity-input, .price-input', function() {
            calculateTotal();
            calculateTotalMoney();
        });
        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let warehouse_category_id = $('#search_code_post').val();
            let warehouse_id = $('.warehouse_avaible').val();
            if (warehouse_id == "" || warehouse_id == null) {
                alert('Vui lòng chọn kho');
                return;
            }
            let _targetHTML = $('#post_available');
            var currentDate = new Date();
            _targetHTML.html('');
            let checked_post = [];
            $('input.related_post_item2:checked').each(function() {
                checked_post.push($(this).val());
            });

            let url = "{{ route('cms_warehouse_product.search') }}/";
            show_loading_notification();
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    keyword: keyword,
                    warehouse_id: warehouse_id,
                    warehouse_category_id: warehouse_category_id,
                    other_list: checked_post,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data || null;
                        let _item = '';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<tr>';
                                _item += '<td>' + item.category_product.name + '</td>';
                                _item += '<td>' + item.code + '</td>';
                                _item += '<td>' + item.name + '</td>';
                                _item += '<td>' + item.ton_kho + '</td>';
                                _item +=
                                    '<td><input type="checkbox" value="' +
                                    item.id +
                                    '" class="mr-15 related_post_item cursor" autocomplete="off"></td>';

                                _item += '</tr>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<tr><td colspan="5">' + response.message +
                            '</td></tr>');
                    }
                },
                error: function(response) {
                    // Get errors
                    let errors = response.responseJSON.message;
                    _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
            hide_loading_notification();
        });
        let stt = $('.order-number').length + 1;
        // Checked and unchecked item event
        $(document).on('click', '.related_post_item', function() {
            let ischecked = $(this).is(':checked');
            let _root = $(this).closest('tr');

            if (ischecked) {
                _root.remove();
                let _id = $(this).val();
                let _warehouse_id = $('.warehouse_avaible').val();
                let url = "{{ route('cms_warehouse_product.search') }}/";
                show_loading_notification();
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                        warehouse_id: _warehouse_id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr class="valign-middle tr_' + item.id + '_' +
                                        item.price + '">';
                                    _item += '<td class="order-number">' + stt + '</td>';
                                    _item += '<td>' + item.name +
                                        '<input type="hidden" name="cart[' + item.id +
                                        '][product_id]" value="' + item.id + '"></td>';
                                    _item += '<td>' + item.unit + '</td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][quantity]" value="1" class="input-field form-control quantity-input" type="number"></td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][price]" value="' + item.price +
                                        '" class="input-field form-control price-input" type="number" ></td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][subtotal_money]" value="' + (item.price * 1) +
                                        '" class="total form-control" type="text" value="" readonly></td>';
                                    _item += '<td>' + item.ton_kho + '</td>';
                                    _item +=
                                        '<td><input onclick="deleteOrder(this)" checked type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item2 cursor" autocomplete="off"></td>';
                                    _item += '</tr>';
                                    stt++;
                                });
                                $("#post_related").append(_item);
                                calculateTotalMoney();
                            }
                        } else {
                            _targetHTML.html('<tr><td colspan="5">' + response.message +
                                '</td></tr>');
                        }
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
                hide_loading_notification();
            }
        });
        $('.warehouse_avaible').change(function() {
            $('#post_related').html('');
            $('#post_available').html('');
            $('.table-order').html('');
            calculateTotalMoney();

            let _warehouse_id = $('.warehouse_avaible').val();
            let url = "{{ route('order_by_warehouse') }}";
            let _targetHTML = $('.table-order');

            if (_warehouse_id > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        warehouse_id: _warehouse_id,
                        status: "{{ App\Consts::APPROVE_WAREHOUSE_ORDER['approved'] }}",
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '';
                            var stt_order = 1;

                            if (list.length > 0) {
                                list.forEach(item => {
                                    function formatCurrency(amount) {
                                        if (!amount || isNaN(amount)) return "";
                                        return new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(amount).replace('₫', 'đ');
                                    }
                                    let productsData = JSON.stringify(item.data_products);
                                    _item += `<tr class="valign-middle">
                                            <td>
                                                ` + (stt_order) + `
                                            </td>

                                            <td>
                                                ` + item.warehouse.name + `
                                            </td>
                                            <td>
                                                <a target="_blank" data-toggle="tooltip"
                                                    title="@lang('Chi tiết đề xuất')"
                                                    href="` + item.link_order + `">` + item.code + `
                                                    <i class="fa fa-eye"></i></i></a>
                                            </td>
                                            <td>
                                                <a target="_blank" data-toggle="tooltip"
                                                    title="@lang('Chi tiết đề xuất')"
                                                    href="` + item.link_order + `">` + item.name + `
                                                    <i class="fa fa-eye"></i></a>
                                            </td>
                                            <td>` + item.total_product + `</td>
                                            <td>` + formatCurrency(item.total_money) + `
                                            </td>
                                            <td>
                                                ` + item.department.name + `
                                            </td>
                                            <td>
                                                ` + item.status + `
                                            </td>

                                            <td>
                                                ` + item.staff.name + `
                                            </td>
                                            <td>
                                                ` + item.day_create + `
                                            </td>

                                            <td class="hide-print">
                                                <input name="list_order[]"
                                                    data-products='${productsData}'
                                                    type="checkbox"
                                                    value="${item.id}"
                                                    class="checked_order mr-15 cursor"
                                                    autocomplete="off">
                                            </td>
                                        </tr>`;
                                    stt_order++;
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            // _targetHTML.html('<tr><td>Không tìm thấy đơn đề xuất</td></tr>');
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
        $('.area_id').change(function() {
            var _id = $(this).val();
            let url = "{{ route('warehouse_by_area') }}";
            let _targetHTML = $('.warehouse_avaible');
            $('#post_related').html('');
            $('#post_available').html('');
            $('.table-order').html('');
            calculateTotalMoney();
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
                    // _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    // let errors = response.responseJSON.message;
                    // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        })

        $(document).on('click', '.checked_order', function() {
            show_loading_notification();
            const isChecked = $(this).is(':checked'); // Kiểm tra trạng thái checkbox
            const products = $(this).data('products'); //Danh sách tất cả sản phẩm chi tiết trong phiếu đó
            const productList = $('#post_related');
            products.forEach(product => {
                const existingProductRow = $('#post_related').find(".tr_" + product.id + "_" + product
                    .price + "");
                // Nếu sản phẩm đã có trong bảng, cập nhật số lượng
                if (existingProductRow.length) {
                    const quantityCell = existingProductRow.find('.quantity-input');
                    const totalCell = existingProductRow.find('.total');

                    if (isChecked == false) {
                        const newQuantity = quantityCell.val() - product.quantity;
                        if (newQuantity > 0) {
                            quantityCell.val(newQuantity)
                            existingProductRow.addClass('tr-highlight');
                            setTimeout(() => {
                                existingProductRow.removeClass('tr-highlight');
                            }, 1000);


                        } else {
                            existingProductRow.addClass('tr-remove');
                            setTimeout(function() {
                                existingProductRow.remove();
                                calculateTotalMoney()
                            }, 1000);

                        }
                    } else {
                        const newQuantity = Number(quantityCell.val()) + Number(product.quantity);
                        quantityCell.val(newQuantity)
                        existingProductRow.addClass('tr-highlight');
                        setTimeout(() => {
                            existingProductRow.removeClass('tr-highlight');
                        }, 1000);
                    }
                }
                //Nếu sản phẩm không có trong bảng
                else {
                    if (isChecked) {
                        const newRow = $(`
                        <tr class="add-highlight-row valign-middle tr_` + product.id + `_` + product.price + ` ">
                            <td class="order-number">` + stt + `</td>
                            <td>` + product.name + `<input type="hidden" name="cart[` + product.id +
                            `][product_id]" value="` + product.id + `"></td>
                            <td>` + product.unit + `</td>
                            <td><input name="cart[` + product.id + `][quantity]" value="` + product.quantity + `" class="input-field form-control quantity-input" type="number"></td>
                            <td><input name="cart[` + product.id + `][price]" value="` + product.price + `" class="input-field form-control price-input" type="number" ></td>
                            <td><input name="cart[` + product.id + `][subtotal_money]" value="` + product
                            .subtotal_money + `" class="total form-control" type="text" value="" readonly></td>
                            <td>` + product.ton_kho + `</td>
                            <td><input onclick="deleteOrder(this)" checked type="checkbox" value="` + product.id + `" class="mr-15 related_post_item2 cursor" autocomplete="off"></td>
                        </tr>
                    `);
                        productList.append(newRow);
                        setTimeout(() => {
                            newRow.addClass('show');
                        }, 10);
                        setTimeout(() => {
                            newRow.removeClass('add-highlight-row');
                            calculateTotalMoney()
                        }, 1000);
                    }
                }
            });
            calculateTotal()
            updateOrderNumbers()
            calculateTotalMoney()
            hide_loading_notification()
        });
        $('#search_title_post').on('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                $('.btn_search').click();
            }
        });
        $('#search_code_post').on('change', function(event) {
            event.preventDefault();
            $('.btn_search').click();
        });
    </script>
@endsection
