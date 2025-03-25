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

        @if($id_user==$detail->staff_entry)
            @include('admin.pages.warehouse_transfer.layout_edit.nguoinhan')
        @elseif($id_user==$detail->staff_deliver)
            @include('admin.pages.warehouse_transfer.layout_edit.nguoigiao')
        @endif
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            calculateTotalMoney()
            show_asset()
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
        //Tìm sản phẩm
        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let warehouse_category_id = $('#search_code_post').val();
            let _targetHTML = $('#post_available');
            var currentDate = new Date();

            let warehouse_id = $('.warehouse_avaible_deliver').val();
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
            $('#loading-notification').css('display', 'flex');
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
                        $('#loading-notification').css('display', 'none');
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
        });
        let stt = $('.order-number').length + 1;
        // thêm sản phẩm
        $(document).on('click', '.related_post_item', function() {
            let ischecked = $(this).is(':checked');
            let _root = $(this).closest('tr');

            if (ischecked) {
                _root.remove();
                let _id = $(this).val();
                let warehouse_id = $('.warehouse_avaible_deliver').val();
                let url = "{{ route('cms_warehouse_product.search') }}/";
                $('#loading-notification').css('display', 'flex');
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
                            $('#loading-notification').css('display', 'none');
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr ' +
                                            (item.warehouse_type == "taisan" || item.warehouse_type == "congcudungcu"
                                                ? 'data-product-id="' + item.id + '" data-product-name="' + item.name + '" '
                                                : '') +
                                            ' class="check_all_product '+
                                                (item.warehouse_type == "taisan" || item.warehouse_type == "congcudungcu"
                                                    ? ' product'
                                                    : '') +
                                            ' valign-middle tr_' + item.id + '_' +item.price + '">';
                                    _item += '<td class="order-number">' + stt + '</td>';
                                    _item += '<td><p class="ten_moi_sp">' + item.name + '</p><input type="hidden" name="cart[' + item.id +
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
                                        _item += '<td><p class="ton_kho_moi_sp">' + item.ton_kho + '</p></td>';

                                    _item +=
                                        '<td><input onclick="deleteOrder(this)" checked type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item2 cursor '+ (item.warehouse_type == "taisan" || item.warehouse_type == "congcudungcu" ? ' asset' : '') +
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
        //thay đổi kho giao và cơ sở giao sẽ thay đổi kho nhậ cơ sở nhận và các sản phẩm đã chọn
        $('.area_id_deliver').change(function() {
            $('.area_id').val('');
            $('.warehouse_avaible').val('');
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();
            var _id = $(this).val();
            let url = "{{ route('warehouse_by_area') }}";
            let _targetHTML = $('.warehouse_avaible_deliver');

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
        $('.warehouse_avaible_deliver').change(function () {
            $('.area_id').val('');
            $('.warehouse_avaible').val('');
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();
        });

        $('.area_id').change(function() {
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();

            let _warehouse_deliver = $('.warehouse_avaible_deliver').val();
            if(!_warehouse_deliver>0){
                alert('Vui lòng chọn kho giao');
                $('.warehouse_avaible').val("");
                $('.area_id').val("");
                return;
            }
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

        $('.warehouse_avaible').change(function(){
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();
        })
    </script>

    @if($id_user==$detail->staff_entry)
        <script>
            //hiển thị tài sản theo sản phẩm
            function show_asset() {
                var _array_asset=@json($list_asset_ids);
                var warehouse_id = $('.warehouse_avaible_deliver').val();
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
                                        var isChecked = _array_asset.includes(item.id.toString()) ? 'checked' : '';
                                        if(isChecked!=""){
                                            _item += `<tr class="valign-middle">
                                                <td>` + index + `</td>
                                                <td>
                                                    <p>` + item.code + `</p>
                                                </td>
                                                <td>
                                                    <p>` + item.name + `</p>
                                                </td>
                                                <td>
                                                    <p>` + item.product.name + `</p>
                                                </td>
                                                <td>
                                                    <p>` + item.quantity + `</p>
                                                </td>
                                                <td>
                                                    <p>` + formatCurrency(item.price) + `</p>
                                                </td>

                                                <td>
                                                    <input ` + isChecked + ` name="asset[` + index +
                                            `][id]" class="mr-15 cursor asset_input_` + item.product.id + `"
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

            $('#myFormTranfer').submit(function(e) {
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
                        alert("Cảnh báo: Vui lòng chọn đúng số lượng sản phẩm " + productName + "")
                        _flag = false;
                        return false;
                    }
                });
                if($('.staff_deliver').val()==$('.staff_entry').val()){
                    alert("Cảnh báo: Người nhận và người giao không được giống nhau")
                    _flag = false;
                    return false;
                };
                if (!_flag) {
                    e.preventDefault();
                }
            });
        </script>
    @elseif($id_user==$detail->staff_deliver)
        <script>
            //hiển thị tài sản theo sản phẩm
            function show_asset() {
                var _array_asset=@json($list_asset_ids);
                var warehouse_id = $('.warehouse_avaible_deliver').val();
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
                                        var isChecked = _array_asset.includes(item.id.toString()) ? 'checked' : '';
                                        _item += `<tr class="valign-middle">
                                                <td>` + index + `</td>
                                                <td>
                                                    <p>` + item.code + `</p>
                                                </td>
                                                <td>
                                                    <p>` + item.name + `</p>
                                                </td>
                                                <td>
                                                    <p>` + item.product.name + `</p>
                                                </td>
                                                <td>
                                                    <p>` + item.quantity + `</p>
                                                </td>
                                                <td>
                                                    <p>` + formatCurrency(item.price) + `</p>
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

            $('#myFormTranfer').submit(function(e) {
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
                    alert("Cảnh báo: Vui lòng chọn đúng số lượng sản phẩm " + productName + "")
                    _flag = false;
                    return false;
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
                    alert("Cảnh báo: Tồn kho của " + productNameEachProduct + " không đủ ")
                    _flag = false;
                    return false;
                }
            });
            if($('.staff_deliver').val()==$('.staff_entry').val()){
                alert("Cảnh báo: Người nhận và người giao không được giống nhau")
                _flag = false;
                return false;
            };
            if (!_flag) {
                e.preventDefault();
            }
        });
        </script>
    @endif
@endsection

