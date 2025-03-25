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

        <form role="form" onsubmit="return confirm('@lang('confirm_action')')" action="{{ route('entry_warehouse.store') }}"
            method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title text-uppercase">@lang($module_name)</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <p class="col-md-12 text-danger">Vui lòng điền đầy đủ thông tin trước khi thêm sản phẩm vào
                                    phiếu!</p>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở')<small class="text-red"> *</small></label>
                                        <select required class="area_id form-control select2" name="area_id"
                                            autocomplete="off">
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
                                        <label>@lang('Kho')<small class="text-red"> *</small></label>
                                        <select required name="warehouse_id" class="warehouse_avaible form-control select2"
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
                                        <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                        <input required type="month" class="form-control" name="period"
                                            value="{{ $detail->period ?? date('Y-m', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu nhập kho') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('Tên phiếu nhập kho')" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Phiếu đề xuất mua sắm')</label>
                                        <select name="order_id" class="order_id form-control select2" autocomplete="off">
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
                                        <label>@lang('Ghi chú')</label>
                                        <input type="text" name="json_params[note]" class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="padding-bottom:10px;">Tìm sản phẩm</h4>
                                            <div style="padding-bottom: 5px">
                                                <div style="padding-left: 0px" class="col-md-6">
                                                    <select style="width:100%" class="form-control select2" name=""
                                                        id="search_code_post">
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
                                                            <i class="fa fa-search"></i> Lọc
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
                                            <h4 style="padding-bottom:10px;">Danh sách sản phẩm nhập kho</h4>
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
                                                        <th>@lang('Tồn kho')</th>
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
                                                        <strong class="total_money"></strong>
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
                            <a class="btn btn-sm btn-success" href="{{ route('entry_warehouse') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button class="btn btn-info save-order pull-right">
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

        function deleteOrder(th) {
            let ischecked = $(th).is(':checked');
            if (!ischecked) {
                $(th).parents('tr').remove()
                updateOrderNumbers()
                calculateTotalMoney();
            }
        }
        $('#myTable').on('input', '.quantity-input, .price-input', function() {
            calculateTotal();
            calculateTotalMoney();
        });


        $('.order_id').change(function() {
            var _id = $(this).val();
            var _warehouse_id = $('.warehouse_avaible').val();
            let url = "{{ route('warehouse_order_detail_by_order') }}";
            let _targetHTML = $('.tbody-order');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: _id,
                    warehouse_id: _warehouse_id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '';
                        let index = 1;
                        if (list.length > 0) {
                            let index = 1;
                            list.forEach(item => {
                                _item += `<tr class="valign-middle">
                                    <td class="order-number">${index++}</td>
                                    <td>
                                        <p>` + item.product.name + `</p>
                                        <input value="` + item.product_id + `" type="hidden" name="cart[` + item
                                    .product_id + `][product_id]">
                                    </td>
                                    <td>
                                        <p>` + item.product.unit + `</p>
                                    </td>
                                    <td>
                                        <input name="cart[` + item.product_id + `][quantity]" value="` + item
                                    .quantity + `" class="input-field form-control quantity-input" type="number" >
                                    </td>
                                    <td>
                                        <input name="cart[` + item.product_id + `][price]" value="` + item.price + `" class="input-field form-control price-input" type="number" >
                                    </td>
                                    <td>
                                        <input name="cart[` + item.product_id + `][subtotal_money]" value="` + item
                                    .subtotal_money + `" class="total form-control" type="text" readonly >
                                    </td>
                                    <td>
                                        <p>` + item.ton_kho + `</p>
                                    </td>
                                    <td><input onclick="deleteOrder(this)" checked type="checkbox" value="` + item
                                    .product_id + `" class="mr-15 related_post_item2 cursor" autocomplete="off"></td>
                                </tr>`;
                            });
                            _targetHTML.html(_item);
                        }
                        $(".select2").select2();
                        calculateTotalMoney()
                    } else {

                        // _targetHTML.html('<tr><td colspan="8"><strong>Không tìm thấy bản ghi</strong></td></tr>');
                        calculateTotalMoney()
                    }
                    _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });

        })

        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let warehouse_id = $('.warehouse_avaible').val();
            if (warehouse_id == "" || warehouse_id == null) {
                alert('Vui lòng chọn kho');
                return;
            }
            let warehouse_category_id = $('#search_code_post').val();
            let _targetHTML = $('#post_available');
            var currentDate = new Date();
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
                        $('#loading-notification').css('display', 'none');
                        let list = response.data || null;
                        console.log(list);
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
        // Checked and unchecked item event
        $(document).on('click', '.related_post_item', function() {
            let ischecked = $(this).is(':checked');
            let _root = $(this).closest('tr');

            if (ischecked) {
                _root.remove();
                let _id = $(this).val();
                let warehouse_id = $('.warehouse_avaible').val();
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
                            var stt = $("#post_related tr").length + 1;
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr class="valign-middle">';
                                    _item += '<input type="hidden" name="cart[' + item.id +
                                        '][product_id]" value="' + item.id + '">';
                                    _item += '<td class="order-number">' + stt + '</td>';
                                    _item += '<td>' + item.name + '</td>';
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
            }
        });
        $('.warehouse_avaible').change(function() {
            calculateTotalMoney();
            $('#post_available').html('');
            $('#post_related').html('');
            $('.order_id').html('');
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
                        type: 'buy',
                        status: "{{ App\Consts::APPROVE_WAREHOUSE_ORDER['approved'] }}",
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '<option value="">@lang('Please select')</option>';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<option value="' + item.id + '">' + item.code +
                                        ' - ' + item.name + '</option>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<option value="">@lang('Please select')</option>');
                        }
                        _targetHTML.trigger('change');
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
            $('#post_available').html('')
            $('#post_related').html('')
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
                    _targetHTML.trigger('change');
                    $('.order_id').trigger('change');
                },
                error: function(response) {
                    // Get errors
                    // let errors = response.responseJSON.message;
                    // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        })
    </script>
@endsection
