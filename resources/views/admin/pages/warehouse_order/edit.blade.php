@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title text-uppercase">@lang('Chỉnh sửa đề xuất order') ({{ $detail->code }})</h3>
                    <a class="btn btn-sm btn-success pull-right hide-print"
                        href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Cơ sở') <small class="text-red">*</small></label>
                                <select required name="area_id" class="area_id form-control">
                                    <option value="">Chọn</option>
                                    @foreach ($list_area as $key => $val)
                                        <option
                                            {{ isset($detail->area_id) && $detail->area_id == $val->id ? 'selected' : '' }}
                                            value="{{ $val->id }}">
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Kho')<small class="text-red">*</small></label>
                                <select required name="warehouse_id" class="warehouse_avaible form-control">
                                    @foreach ($list_warehouse as $key => $val)
                                        <option
                                            {{ isset($detail->warehouse_id) && $detail->warehouse_id == $val->id ? 'selected' : '' }}
                                            value="{{ $val->id }}">
                                            @lang($val->name ?? '')
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Phòng đề xuất')<small class="text-red">*</small></label>
                                <select required name="department_request" class="dep_avaible form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($department as $key => $val)
                                        <option {{ $detail->department_request == $val->id ? 'selected' : '' }}
                                            value="{{ $val->id }}">
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
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
                                <label>@lang('Người đề xuất') <small class="text-red">*</small></label>
                                <input type="text" class="form-control"
                                    value="{{ isset($detail->staff) ? $detail->staff->name . ' (' . $detail->staff->admin_code . ')' : $admin_auth->name . ' (' . $admin_auth->admin_code . ')' }}"
                                    disabled>
                                <input type="hidden" class="form-control" name="staff_request"
                                    value="{{ $detail->staff_request ?? $admin_auth->id }}" required>
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
                                                    <th>Tồn kho hiện tại</th>
                                                    <th>Chọn</th>
                                                </tr>
                                            </thead>
                                            <tbody id="post_available">

                                            </tbody>
                                        </table>
                                    </div><!-- /.box-body -->
                                </div>
                                <div class="col-md-7">
                                    <h4 style="padding-bottom:10px;">Danh sách sản phẩm order</h4>
                                    <table id="myTable" class="table table-hover table-bordered table-responsive">
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
                                            @if (isset($detail->orderDetails) && $detail->orderDetails->count() > 0)
                                                @foreach ($detail->orderDetails as $key => $row)
                                                    <tr class="valign-middle">
                                                        <td class="order-number">{{ ++$key }}</td>
                                                        <td>{{ $row->product->name ?? '' }}<input type="hidden"
                                                                name="cart[{{ $key }}][product_id]"
                                                                value="{{ $row->product->id }}"></td>
                                                        <td>{{ $row->product->unit ?? '' }}</td>
                                                        <td><input name="cart[{{ $key }}][quantity]"
                                                                value="{{ $row->quantity }}"
                                                                class="input-field form-control quantity-input"
                                                                type="number"></td>
                                                        <td><input readonly name="cart[{{ $key }}][price]"
                                                                value="{{ $row->price }}"
                                                                class="input-field form-control price-input"
                                                                type="number">
                                                        </td>
                                                        <td><input name="cart[{{ $key }}][subtotal_money]"
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
                    @if ($detail->status == 'not approved')
                        <button type="submit" class="btn btn-info pull-right">
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
        let previousValue;
        // Khi bắt đầu focus vào select, lưu giá trị hiện tại
        $('.warehouse_avaible').on('focus', function() {
            previousValue = $(this).val();
        });
        $('.warehouse_avaible').change(function(e) {
            e.preventDefault();
            var check_isset_product = $('#post_related tr').length;
            if (check_isset_product > 0) {
                if (confirm('Thay đổi kho sẽ thay đổi toàn bộ sản phẩm đã chọn. Xác nhận thay đổi?')) {
                    $('#post_related').html('');
                    $('#post_available').html('');
                    calculateTotalMoney();
                } else {
                    $('.warehouse_avaible').val(previousValue);
                }
            } else {
                $('#post_related').html('');
                $('#post_available').html('');
                calculateTotalMoney();
            }
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
                updateOrderNumbers();
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
                            $('#loading-notification').css('display', 'none');
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr class="valign-middle">';
                                    _item += '<td class="order-number">' + stt + '</td>';
                                    _item += '<td>' + item.name +
                                        '<input type="hidden" name="cart[' + stt +
                                        '][product_id]" value="' + item.id + '"></td>';
                                    _item += '<td>' + item.unit + '</td>';
                                    _item += '<td><input name="cart[' + stt +
                                        '][quantity]" value="1" class="input-field form-control quantity-input" type="number"></td>';
                                    _item += '<td><input readonly name="cart[' + stt +
                                        '][price]" value="' + item.price +
                                        '" class="input-field form-control price-input" type="number" ></td>';
                                    _item += '<td><input name="cart[' + stt +
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

        $('.area_id').change(function() {
            var _id = $(this).val();
            let url = "{{ route('warehouse_by_area') }}";
            let _targetHTML = $('.warehouse_avaible');
            $('#post_related').html('');
            $('#post_available').html('');
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
                    _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    // let errors = response.responseJSON.message;
                    // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        })

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
