@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>tbody>tr>td {
            white-space: normal;
            word-break: break-word;
        }
        .mt-2{margin-top: 2rem;}
    </style>
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
        <div class="box-alert">
            @if (session('errorMessage'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session('errorMessage') !!}
                </div>
            @endif
            @if (session('successMessage'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {!! session('successMessage') !!}
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
        <form id="myFormTranfer" role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Kho giao')</h3>
                            <p class="text-red">Thay đổi kho hoặc cơ sở sẽ reset phiếu và sản phẩm bên dưới</p>
                        </div>
                        @csrf
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở giao')<small class="text-red">*</small></label>
                                        <select required name="area_id_deliver" class="area_id_deliver form-control select2"
                                            autocomplete="off">
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
                                        <label>@lang('Kho giao')<small class="text-red">*</small></label>
                                        <select required name="warehouse_id_deliver"
                                            class="warehouse_avaible_deliver form-control select2">
                                            <option value="">@lang('Please select')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người giao')<small class="text-red">*</small></label>
                                        <input type="text" class="form-control staff_deliver" readonly
                                            value="{{ $admin_auth->name }}" data-id = "{{ $admin_auth->id }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày giao') <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="day_deliver"
                                            value="{{ date('Y-m', time()) }}">
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
                            <p class="text-red">(Chọn kho giao mới được phép chọn kho nhận)</p>
                        </div>
                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở nhận')<small class="text-red">*</small></label>
                                        <select name="area_id" required class="area_id form-control select2"
                                            autocomplete="off">
                                            <option value="">Chọn</option>
                                            @foreach ($all_area as $key => $val)
                                                <option value="{{ $val->id }}">
                                                    @lang($val->name ?? '')</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kho nhận')<small class="text-red">*</small></label>
                                        <select required name="warehouse_id" class="warehouse_avaible form-control select2">
                                            <option value="">@lang('Please select')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người nhận')<small class="text-red">*</small></label>
                                        <select required name="staff_entry" class="staff_entry form-control select2">
                                            <option value="">Chọn</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày nhận dự kiến') <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="day_entry"
                                            value="{{ date('Y-m', time()) }}">
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
                        @csrf
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu điều chuyển') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('Tên điều chuyển')" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                        <input required type="month" class="form-control" name="period"
                                            value="{{ date('Y-m', time()) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Người đề xuất')<small class="text-red">*</small></label>
                                        <input type="text" class=" form-control staff_request" readonly
                                            value="{{ $admin_auth->name }}" data-id = "{{ $admin_auth->id }}">
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
                                        <textarea name="json_params[note]" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex-wap">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <h4 class="box-title">Thêm sản phẩm</h4>
                                    </div>
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
                                    <div class="table-config-overflow table-responsive no-padding">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Danh mục</th>
                                                    <th>Mã Sp</th>
                                                    <th style="width: 50%">Tên Sp</th>
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

                            <div class="col-md-12 mt-2">
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
                        <div class="box-footer">
                            <a class="btn  btn-success" href="{{ route(Request::segment(2) . '.index') }}">
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
        var warehouses = @json($warehouses ?? []);
        var staff_request = @json($staff_request ?? []);
        var admin_auth = @json($admin_auth ?? []);

        $(document).ready(function() {
            $('.area_id_deliver').trigger('change');
            calculateTotalMoney()
        });
        //thay đổi kho giao và cơ sở giao sẽ thay đổi kho nhận cơ sở nhận và các sản phẩm đã chọn
        $('.area_id_deliver').change(function() {
            $('.area_id').val('');
            $('.warehouse_avaible').val('');
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();
            let _targetHTML = $('.warehouse_avaible_deliver');
            var area_id = $(this).val();
            var _html = '<option value="">@lang('Please select')</option>';
            if (area_id != '') {
                warehouses.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });
            }
            _targetHTML.html(_html).trigger('change');
        })
        $('.area_id').change(function() {
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();

            let _warehouse_deliver = $('.warehouse_avaible_deliver').val();
            if (!_warehouse_deliver > 0) {
                alert('Vui lòng chọn kho giao');
                $('.warehouse_avaible').val("");
                $('.area_id').val("");
                return;
            }
            let _targetHTML = $('.warehouse_avaible');
            var area_id = $(this).val();
            var _html = _html_staff = '<option value="">@lang('Please select')</option>';
            if (area_id != '') {
                warehouses.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });
                staff_request.forEach(function(item) {
                    if (area_id == item.area_id && admin_auth.id != item.id) {
                        _html_staff += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });

            }

            _targetHTML.html(_html).trigger('change');
            $('.staff_entry').html(_html_staff).trigger('change');
        })
        // Cập nhật lại tổng tiền khi thay đổi nội dung
        $('#myTable').on('input', '.quantity-input, .price-input', function() {
            calculateTotal();
            calculateTotalMoney();
        });
        // Làm mới nội dung khi chọn lại kho
        $('.warehouse_avaible_deliver').change(function() {
            $('.area_id').val('');
            $('.warehouse_avaible').val('');
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();
        });
        $('.warehouse_avaible').change(function() {
            $('#post_related').html('');
            $('#post_available').html('');
            calculateTotalMoney();
        })

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
                            if (_item == '') {
                                _item =
                                    '<tr><td colspan="5">Không tìm thấy sản phẩm tồn trong kho!</td></tr>'
                            }
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<tr><td colspan="5">' + response.message +
                            '</td></tr>');
                    }
                    hide_loading_notification()
                },
                error: function(response) {
                    hide_loading_notification()
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
                show_loading_notification();
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
                                        '][price]" readonly value="' + item.price +
                                        '" class="input-field form-control price-input" type="number" ></td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][subtotal_money]" value="' + (item.price * 1) +
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
                                show_asset();
                            }
                        } else {
                            _targetHTML.html('<tr><td colspan="5">' + response.message +
                                '</td></tr>');
                        }
                        hide_loading_notification()
                    },
                    error: function(response) {
                        // Get errors
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            }
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
                    _html += `<div class="alert alert-warning alert-dismissible">
                        Cảnh báo: Tồn kho của ` + productNameEachProduct + ` không đủ
                        </div>`;

                    _flag = false;
                    return false;
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

        //hiển thị tài sản theo sản phẩm
        function show_asset() {
            var warehouse_id = $('.warehouse_avaible_deliver').val();
            let _selectedValues = [];
            $('.asset:checked').each(function() {
                _selectedValues.push($(this).val());
            });
            console.log(_selectedValues);

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

        function calculateTotalMoney() {
            var total_money = 0;
            $('.total').each(function() {
                var total = $(this).val();
                total_money += Number(total)
            });
            $('.total_money').text(formatCurrency(total_money));
            $('.total_money_input').val(total_money);
        }

        function calculateTotal() {
            $('#myTable tr').each(function() {
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();
                const total = Number(quantity * price);
                $(this).find('.total').val(total);
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

        function updateOrderNumbers() {
            $('.tbody-order tr').each(function(index) {
                $(this).find('.order-number').text(index + 1);
            });
        }
    </script>
@endsection
