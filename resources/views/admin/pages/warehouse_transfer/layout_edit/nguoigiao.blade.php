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
        <form id="myFormTranfer" role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}"
            method="POST" onsubmit="return confirm('@lang('confirm_action')')">
            @method('PUT')
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
                                        <input required type="date" class="form-control" name="day_deliver"
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
                            <h3 class="box-title">@lang('Kho nhận')</h3>
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
                                        <select required name="staff_entry" class="staff_entry form-control select2">
                                            <option value="">Chọn</option>
                                            @foreach ($staff_request as $key => $val)
                                                @if ($val->area_id == $detail->area->id)
                                                    <option
                                                        {{ isset($detail->staff_entry) && $detail->staff_entry == $val->id ? 'selected' : '' }}
                                                        value="{{ $val->id }}">
                                                        {{ $val->name ?? '' }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày nhận dự kiến') <small class="text-red">*</small></label>
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
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('Tên điều chuyển')" value="{{ $detail->name ?? old('name') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                        <input required type="month" class="form-control" name="period"
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
                                        <input required type="date" class="form-control" name="day_create"
                                            value="{{ $detail->day_create ?? date('Y-m-d', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú')</label>
                                        <textarea name="json_params[note_deliver]" class="form-control" rows="5">{{ $detail->json_params->note_deliver ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex-wap">
                                <div class="col-md-5">
                                    <h4 class="box-title">Thêm sản phẩm</h4>
                                    <h3 class="box-title"></h3>
                                    <div class="">
                                        <div style="padding-left: 0px" class="col-md-6">
                                            <select style="width:100%" class="form-control select2" name=""
                                                id="search_code_post">
                                                <option value="">Danh mục sản phẩm...</option>
                                                @foreach ($category_products as $category_product)
                                                    @if ($category_product->category_parent == '' || $category_product->category_parent == null)
                                                        <option value="{{ $category_product->id }}">
                                                            {{ $category_product->name ?? '' }}</option>
                                                        @foreach ($category_products as $category_sub)
                                                            @if ($category_sub->category_parent == $category_product->id)
                                                                <option value="{{ $category_sub->id }}">
                                                                    - - - {{ $category_sub->name ?? '' }}</option>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="input-group col-md-6">
                                            <input type="text" id="search_title_post" class="form-control pull-right"
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
                                                    <th>Tồn kho</th>
                                                    <th>Chọn</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_available">

                                            </tbody>
                                        </table>
                                    </div><!-- /.box-body -->
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <h4 class="box-title">Tổng hợp sản phẩm</h4>
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
                                                <th>@lang('Tồn kho (kho giao)')</th>
                                                <th>@lang('Chọn')</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order" id="post_related">
                                            @if ($rows->count() > 0)
                                                @foreach ($rows as $key => $row)
                                                    <tr data-product-id="{{ $row->product->id }}"
                                                        data-product-name="{{ $row->product->name }}"
                                                        class="{{ $row->product->warehouse_type == 'taisan' || $row->product->warehouse_type == 'congcudungcu' ? 'product' : '' }} check_all_product valign-middle">
                                                        <td class="order-number">{{ ++$key }}</td>
                                                        <td class="ten_moi_sp">{{ $row->product->name ?? '' }}<input
                                                                type="hidden"
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
                                                        <td class="ton_kho_moi_sp">{{ $row->ton_kho }}</td>
                                                        <td><input onclick="deleteOrder(this)" checked type="checkbox"
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
                                                <strong class="total_money">0</strong>
                                                <input type="hidden" name="total_money" value=""
                                                    class="total_money_input">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4>@lang('Danh sách tài sản tương ứng của kho giao')</h4>
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
                        <a class="btn btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                            <i class="fa fa-bars"></i> @lang('List')
                        </a>
                        <button type="submit" class="btn btn-info pull-right">
                            <i class="fa fa-save"></i> @lang('Save')
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

        });
        $(document).on('submit', '#myFormTranfer', function(e) {
            var _flag = true;
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
                    var _html = `<div class="alert alert-warning alert-dismissible">
                        Cảnh báo: Vui lòng chọn đúng số lượng sản phẩm ` + productName + `
                        </div>`;
                    $('.box-alert').prepend(_html);
                    $('html, body').animate({
                        scrollTop: $(".alert-warning").offset().top
                    }, 1000);
                    setTimeout(function() {
                        $(".alert-warning").fadeOut(2000, function() {});
                    }, 3000);
                    _flag = false;
                    return false;
                }
            });
            $('.check_all_product').each(function() {
                // Lấy số lượng sản phẩm
                let quantity_each_product = parseInt($(this).find('.quantity-input').val());
                // Đếm số checkbox được chọn trong nhóm tài sản
                let check_ton_kho = parseInt($(this).find('.ton_kho_moi_sp').text());
                let productNameEachProduct = $(this).find('.ten_moi_sp').text();
                // Kiểm tra điều kiện
                if (quantity_each_product > check_ton_kho) {
                    var _html = `<div class="alert alert-warning alert-dismissible">
                        Cảnh báo: Tồn kho của ` + productNameEachProduct + ` không đủ
                        </div>`;
                    $('.box-alert').prepend(_html);
                    $('html, body').animate({
                        scrollTop: $(".alert-warning").offset().top
                    }, 1000);
                    setTimeout(function() {
                        $(".alert-warning").fadeOut(2000, function() {});
                    }, 3000);
                    _flag = false;
                    return false;
                }
            });
            if (!_flag) {
                e.preventDefault();
            }
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

        function deleteOrder(th) {
            let ischecked = $(th).is(':checked');
            if (!ischecked) {
                $(th).parents('tr').remove()
                updateOrderNumbers();
                calculateTotalMoney();
            }
            show_asset();
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
            $('.asset:checked').each(function() {
                _selectedValues.push($(this).val());
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
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            let _item = '';
                            let index = 1;
                            if (list.length > 0) {
                                list.forEach(item => {
                                    var position = item.position;
                                    var isChecked = _array_asset.includes(item.id.toString()) ?
                                        'checked' : '';
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
                                                    <input ` + isChecked + ` name="asset[` + index +
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

        //Tìm sản phẩm
        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let warehouse_category_id = $('#search_code_post').val();
            let _targetHTML = $('#post_available');
            var currentDate = new Date();

            let warehouse_id = $('.warehouse_avaible_deliver').data('id');
            if (warehouse_id == "" || warehouse_id == null) {
                alert('Vui lòng chọn kho');
                return;
            }
            _targetHTML.html('');
            let checked_post = [];
            $('input.related_post_item2:checked').each(function() {
                checked_post.push($(this).val());
            });

            let url = "{{ route('cms_warehouse_product.search') }}/";
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
                        console.log(list);
                        let _item = '';
                        if (list.length > 0) {
                            list.forEach(item => {
                                if (item.ton_kho > 0) {
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
                                }
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
        });
        let stt = $('.order-number').length + 1;
        // thêm sản phẩm
        $(document).on('click', '.related_post_item', function() {
            let ischecked = $(this).is(':checked');
            let _root = $(this).closest('tr');

            if (ischecked) {
                _root.remove();
                let _id = $(this).val();
                let warehouse_id = $('.warehouse_avaible_deliver').data('id');
                let url = "{{ route('cms_warehouse_product.search') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                        warehouse_id: warehouse_id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            console.log(list);
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr ' +
                                        (item.warehouse_type == "taisan" || item
                                            .warehouse_type == "congcudungcu" ?
                                            'data-product-id="' + item.id +
                                            '" data-product-name="' + item.name + '" ' :
                                            '') +
                                        ' class="check_all_product ' +
                                        (item.warehouse_type == "taisan" || item
                                            .warehouse_type == "congcudungcu" ?
                                            ' product' :
                                            '') +
                                        ' valign-middle tr_' + item.id + '_' + item.price +
                                        '">';
                                    _item += '<td class="order-number">' + stt + '</td>';
                                    _item += '<td><p class="ten_moi_sp">' + item.name +
                                        '</p><input type="hidden" name="cart[' + item.id +
                                        '][product_id]" value="' + item.id + '"></td>';
                                    _item += '<td>' + item.unit + '</td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][quantity]" value="1" class="input-field form-control quantity-input" type="number"></td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][price]" value="' + item.price +
                                        '" class="input-field form-control price-input" type="number" readonly></td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][subtotal_money]"  value="' + (item.price * 1) +
                                        '" class="total form-control" type="text" value="" readonly></td>';
                                    _item += '<td><p class="ton_kho_moi_sp">' + item.ton_kho +
                                        '</p></td>';

                                    _item +=
                                        '<td><input onclick="deleteOrder(this)" checked type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item2 cursor ' + (item
                                            .warehouse_type == "taisan" || item
                                            .warehouse_type == "congcudungcu" ? ' asset' : '') +
                                        '" autocomplete="off"></td>';

                                    _item += '</tr>';
                                    stt++;
                                });
                                $("#post_related").append(_item);
                                calculateTotalMoney();
                                show_asset()
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
            }
        });
    </script>
@endsection
