@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
        <form action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            @csrf
            @method('PUT')
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase">@lang('Chỉnh sửa đề xuất mua sắm') ({{ $detail->code }})</h3>
                    <a class="btn btn-sm btn-success pull-right hide-print"
                        href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                </div>
                <div class="box-body ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Cơ sở') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" value="{{ $detail->area->name ?? '' }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Kho')<small class="text-red">*</small></label>
                                <input type="text" class="form-control" value="{{ $detail->warehouse->name ?? '' }}"
                                    disabled>
                                <input type="hidden" class="form-control warehouse_avaible"
                                    value="{{ $detail->warehouse_id }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Tên phiếu đề xuất') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="name" placeholder="@lang('Tên đề xuất')"
                                    value="{{ $detail->name ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Kỳ') <small class="text-red">*</small></label>
                                <input readonly type="month" class="form-control" name="period"
                                    value="{{ $detail->period ?? date('Y-m', time()) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Người tạo đề xuất')<small class="text-red">*</small></label>
                                <input type="text" class="form-control" value="{{ $detail->staff->name ?? '' }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Phòng ban tạo')<small class="text-red">*</small></label>
                                <input type="text" class="form-control" value="{{ $detail->department->name ?? '' }}"
                                    disabled>
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
                                <input disabled class="form-control" value="{{ __($detail->status) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Ghi chú')</label>
                                <textarea name="json_params[note]" class="form-control" rows="5">{{ $detail->json_params->note ?? '' }}</textarea>
                            </div>
                        </div>

                        @isset($list_relateds)
                            <div class="col-md-12">
                                <h4 style="padding-bottom: 10px">@lang('Danh sách đề xuất order đã gắn với phiếu mua sắm này')</h4>
                                <table class="table table-hover table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">@lang('STT')</th>
                                            <th style="width: 150px">@lang('Kho')</th>
                                            <th style="width: 100px">@lang('Mã phiếu')</th>
                                            <th style="width: 200px">@lang('Tên phiếu đề xuất')</th>
                                            <th style="width: 100px">@lang('Tổng SP')</th>
                                            <th style="width: 100px">@lang('Tổng tiền')</th>
                                            <th style="width: 150px">@lang('Phòng ban')</th>
                                            <th style="width: 100px">@lang('Trạng thái')</th>
                                            <th>@lang('Ghi chú')</th>
                                            <th style="width: 150px">@lang('Người đề xuất')</th>
                                            <th style="width: 100px">@lang('Ngày đề xuất')</th>
                                            <th style="width: 40px">@lang('Chọn')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($list_relateds->count() > 0)
                                            @foreach ($list_relateds as $row)
                                                <tr class="valign-middle">
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>

                                                    <td>
                                                        {{ $row->warehouse->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        <a target="_blank" data-toggle="tooltip" title="@lang('Chi tiết đề xuất')"
                                                            href="{{ route('warehouse_order_product.show', $row->id) }}">{{ $row->code ?? '' }}
                                                            <i class="fa fa-eye"></i></i></a>
                                                    </td>
                                                    <td>
                                                        <a target="_blank" data-toggle="tooltip" title="@lang('Chi tiết đề xuất')"
                                                            href="{{ route('warehouse_order_product.show', $row->id) }}">{{ $row->name ?? '' }}
                                                            <i class="fa fa-eye"></i></a>
                                                    </td>
                                                    <td>{{ $row->total_product ?? '' }}</td>
                                                    <td>{{ isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : '' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->department->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ __($row->status) }}
                                                    </td>
                                                    <td>{{ $row->json_params->note ?? '' }}</td>
                                                    <td>
                                                        {{ $row->staff->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->day_create != '' ? date('d-m-Y', strtotime($row->day_create)) : 'Chưa cập nhật' }}
                                                    </td>

                                                    <td class="hide-print">
                                                        <input name="list_order[]"
                                                            data-products="{{ json_encode(
                                                                $row->orderDetails->map(function ($orderDetail) use ($detail) {
                                                                    return [
                                                                        'id' => $orderDetail->product->id,
                                                                        'name' => $orderDetail->product->name,
                                                                        'unit' => $orderDetail->product->unit,
                                                                        'quantity' => $orderDetail->quantity,
                                                                        'price' => $orderDetail->price, // Giá của sản phẩm
                                                                        'ton_kho' => App\Http\Services\WarehouseService::getTonkho(
                                                                            $orderDetail->product->id,
                                                                            $detail->warehouse_id,
                                                                        ),
                                                                        'subtotal_money' => $orderDetail->subtotal_money, // Giá của sản phẩm
                                                                    ];
                                                                }),
                                                            ) }}"
                                                            type="checkbox" checked value="{{ $row->id }}"
                                                            class="checked_order mr-15 cursor" autocomplete="off">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        @endisset
                        @isset($list_orders)
                            <div class="col-md-12">
                                <h4 style="padding-bottom: 10px">@lang('Danh sách đề xuất order đã duyệt cần xử lý')</h4>
                                <table class="table table-hover table-bordered table-responsive">
                                    <thead>
                                        <tr>
                                            <th style="width: 40px">@lang('STT')</th>
                                            <th style="width: 150px">@lang('Kho')</th>
                                            <th style="width: 100px">@lang('Mã phiếu')</th>
                                            <th style="width: 200px">@lang('Tên phiếu đề xuất')</th>
                                            <th style="width: 100px">@lang('Tổng SP')</th>
                                            <th style="width: 100px">@lang('Tổng tiền')</th>
                                            <th style="width: 150px">@lang('Phòng ban')</th>
                                            <th style="width: 100px">@lang('Trạng thái')</th>
                                            <th>@lang('Ghi chú')</th>
                                            <th style="width: 150px">@lang('Người đề xuất')</th>
                                            <th style="width: 100px">@lang('Ngày đề xuất')</th>
                                            <th style="width: 40px">@lang('Chọn')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-order">
                                        @if ($list_orders->count() > 0)
                                            @foreach ($list_orders as $row)
                                                <tr class="valign-middle">
                                                    <td>
                                                        {{ $loop->index + 1 }}
                                                    </td>

                                                    <td>
                                                        {{ $row->warehouse->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        <a target="_blank" data-toggle="tooltip" title="@lang('Chi tiết đề xuất')"
                                                            href="{{ route('warehouse_order_product.show', $row->id) }}">{{ $row->code ?? '' }}
                                                            <i class="fa fa-eye"></i></i></a>
                                                    </td>
                                                    <td>
                                                        <a target="_blank" data-toggle="tooltip" title="@lang('Chi tiết đề xuất')"
                                                            href="{{ route('warehouse_order_product.show', $row->id) }}">{{ $row->name ?? '' }}
                                                            <i class="fa fa-eye"></i></a>
                                                    </td>
                                                    <td>{{ $row->total_product ?? '' }}</td>
                                                    <td>{{ isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : '' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->department->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ __($row->status) }}
                                                    </td>
                                                    <td>{{ $row->json_params->note ?? '' }}</td>
                                                    <td>
                                                        {{ $row->staff->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $row->day_create != '' ? date('d-m-Y', strtotime($row->day_create)) : 'Chưa cập nhật' }}
                                                    </td>

                                                    <td class="hide-print">
                                                        <input name="list_order[]"
                                                            data-products="{{ json_encode(
                                                                $row->orderDetails->map(function ($orderDetail) use ($detail) {
                                                                    return [
                                                                        'id' => $orderDetail->product->id,
                                                                        'name' => $orderDetail->product->name,
                                                                        'unit' => $orderDetail->product->unit,
                                                                        'quantity' => $orderDetail->quantity,
                                                                        'price' => $orderDetail->price, // Giá của sản phẩm
                                                                        'ton_kho' => App\Http\Services\WarehouseService::getTonkho(
                                                                            $orderDetail->product->id,
                                                                            $detail->warehouse_id,
                                                                        ),
                                                                        'subtotal_money' => $orderDetail->subtotal_money, // Giá của sản phẩm
                                                                    ];
                                                                }),
                                                            ) }}"
                                                            type="checkbox" value="{{ $row->id }}"
                                                            class="checked_order mr-15 cursor" autocomplete="off">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        @endisset
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
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
                                            <input type="text" id="search_title_post" class="form-control pull-right"
                                                placeholder="Tên sản phẩm, mã sản phẩm..." autocomplete="off">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default btn_search">
                                                    <i class="fa fa-search"></i>
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
                                <div class="col-md-8">
                                    <h4 style="padding-bottom:10px;">Tổng hợp sản phẩm mua sắm</h4>
                                    <table id="list_product_by" class="table table-hover table-bordered table-responsive">
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
                                            @if ($detail->orderDetails->count() > 0)
                                                @foreach ($detail->orderDetails as $key => $row)
                                                    <tr class="valign-middle tr_{{ $row->product->id ?? '' }}">
                                                        <td class="order-number">{{ ++$key }}</td>
                                                        <td>{{ $row->product->name ?? '' }}<input type="hidden"
                                                                name="cart[{{ $row->product->id }}][product_id]"
                                                                value="{{ $row->product->id }}"></td>
                                                        <td>{{ $row->product->unit ?? '' }}</td>
                                                        <td><input name="cart[{{ $row->product->id }}][quantity]"
                                                                value="{{ $row->quantity }}"
                                                                class="input-field form-control quantity-input"
                                                                type="number"></td>
                                                        <td><input name="cart[{{ $row->product->id }}][price]"
                                                                value="{{ $row->price }}"
                                                                class="input-field form-control price-input"
                                                                type="number">
                                                        </td>
                                                        <td><input name="cart[{{ $row->product->id }}][subtotal_money]"
                                                                value="{{ $row->subtotal_money }}"
                                                                class="total form-control" type="text" value=""
                                                                readonly></td>
                                                        <td>{{ $row->ton_kho }}</td>
                                                        <td><input onclick="deleteOrder(this)" checked type="checkbox"
                                                                value="{{ $row->product->id }}"
                                                                class="mr-15 related_post_item2 cursor"
                                                                autocomplete="off">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tr>
                                            <td colspan="5">
                                                <strong class="pull-right">TỔNG TIỀN:</strong>
                                            </td>
                                            <td colspan="3">
                                                <strong
                                                    class="total_money">{{ isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . ' đ' : '' }}</strong>
                                                <input type="hidden" name="total_money"
                                                    value="{{ $detail->total_money }}" class="total_money_input">
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    @if ($detail->status == 'not approved')
                        <button type="submit" class="btn btn-info btn-sm pull-right">
                            <i class="fa fa-save"></i> @lang('Lưu thông tin')
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
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
            $('#list_product_by tr').each(function() {
                const quantity = $(this).find('.quantity-input').val();
                const price = $(this).find('.price-input').val();
                const total = Number(quantity * price);

                $(this).find('.total').val(total);
            });
        }
        $('#list_product_by').on('input', '.quantity-input, .price-input', function() {
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
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    keyword: keyword,
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
                    $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    // Get errors
                    $('#loading-notification').css('display', 'none');
                    let errors = response.responseJSON.message;
                    _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        });
        var stt = $('.order-number').length + 1;
        // Checked and unchecked item event
        $(document).on('click', '.related_post_item', function() {
            let ischecked = $(this).is(':checked');
            let _root = $(this).closest('tr');

            if (ischecked) {
                _root.remove();
                let _id = $(this).val();
                let _warehouse_id = $('.warehouse_avaible').val();
                let url = "{{ route('cms_warehouse_product.search') }}/";
                $('#loading-notification').css('display', 'flex');
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
                                        '<input type="hidden"  name="cart[' + item.id +
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
                        $('#loading-notification').css('display', 'none');
                    },
                    error: function(response) {
                        // Get errors
                        $('#loading-notification').css('display', 'none');
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            }
        });

        $(document).on('click', '.checked_order', function() {
            $('#loading-notification').css('display', 'flex');
            const isChecked = $(this).is(':checked'); // Kiểm tra trạng thái checkbox
            const products = $(this).data('products'); //Danh sách tất cả sản phẩm chi tiết trong phiếu đó
            const productList = $('#post_related');
            products.forEach(product => {
                const existingProductRow = $('#post_related').find(".tr_" + product.id);
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
                                // $('#loading-notification').css('display', 'none');
                            }, 1000);

                        } else {
                            existingProductRow.addClass('tr-remove');
                            setTimeout(function() {
                                existingProductRow.remove();
                                calculateTotalMoney()
                                // $('#loading-notification').css('display', 'none');
                            }, 1000);
                        }
                    } else {
                        const newQuantity = Number(quantityCell.val()) + Number(product.quantity);
                        quantityCell.val(newQuantity)
                        existingProductRow.addClass('tr-highlight');
                        setTimeout(() => {
                            existingProductRow.removeClass('tr-highlight');
                            // $('#loading-notification').css('display', 'none');
                        }, 1000);

                    }
                }
                //Nếu sản phẩm không có trong bảng
                else {
                    if (isChecked) {
                        const newRow = $(`
                        <tr class="add-highlight-row valign-middle tr_` + product.id + ` ">
                            <td class="order-number">` + stt + `</td>
                            <td>` + product.name + `<input type="hidden"  name="cart[` + product.id +
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
                            // $('#loading-notification').css('display', 'none');
                        }, 1000);
                    }
                }
            });
            calculateTotal()
            updateOrderNumbers()
            calculateTotalMoney()
            setTimeout(() => {
                $('#loading-notification').css('display', 'none');
            }, 1500); // Đặt thời gian nếu cần đảm bảo các th
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
