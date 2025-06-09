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

        <form role="form" action="{{ route("meal_warehouse_ingredients_entry_store") }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title text-uppercase">@lang('Thêm mới nhập kho')</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở')<small class="text-red"> *</small></label>
                                        <select required class="area_id form-control select2" name="area_id"
                                            autocomplete="off">
                                            <option value="">Chọn</option>
                                            @foreach ($list_area as $key => $val)
                                                <option value="{{ $val->id }}">
                                                    {{ $val->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu nhập kho') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('Tên phiếu nhập kho')" value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú')</label>
                                        <textarea cols="5" name="json_params[note]" class="form-control"
                                            placeholder="@lang('Ghi chú')">{{ old('json_params.note') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <h4 style="padding-bottom:10px;">Tìm thực phẩm</h4>
                                            <div style="padding-bottom: 5px">
                                                <div style="padding-left: 0px" class="col-md-6">
                                                    <select style="width:100%" class="form-control select2" name=""
                                                        id="search_code_post">
                                                        <option value="">Danh mục thực phẩm...</option>
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
                                                        placeholder="Tên thực phẩm..." autocomplete="off">
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
                                                            <th>Mã TP</th>
                                                            <th>Tên TP</th>
                                                            <th>ĐVT</th>
                                                            <th>Tồn kho</th>
                                                            <th>Chọn</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="post_available">

                                                    </tbody>
                                                </table>
                                            </div><!-- /.box-body -->
                                        </div>
                                        <div  class="col-md-7">
                                            <h4 style="padding-bottom:10px;">@lang('Danh sách thực phẩm nhập kho')</h4>
                                            <table id="myTable"
                                                class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('STT')</th>
                                                        <th>@lang('Sản phẩm')</th>
                                                        <th>@lang('ĐVT')</th>
                                                        <th>@lang('Số lượng')</th>
                                                        <th>@lang('Tồn kho')</th>
                                                        <th>@lang('Chọn')</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-order" id="post_related">
                                                    <tr>
                                                        <td colspan="8" class="text-center show_empty">
                                                            @lang('Vui lòng chọn thực phẩm để nhập kho')
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success">
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

        $(document).on('click', '.btn_search', function() {
            let keyword = $('#search_title_post').val();
            let area_id = $('.area_id').val();
            if (area_id == '') {
                alert('Vui lòng chọn cơ sở');
                return false;
            }
            let warehouse_category_id = $('#search_code_post').val();
            let _targetHTML = $('#post_available');
            var currentDate = new Date();
            _targetHTML.html('');
            let checked_post = [];
            $('input.related_post_item2:checked').each(function() {
                checked_post.push($(this).val());
            });
            let url = "{{ route('mealmenu.searchIngredients.withTonkho') }}/";
            $('#loading-notification').css('display', 'flex');

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    keyword: keyword,
                    ingredient_category_id: warehouse_category_id,
                    other_list: checked_post,
                    area_id: area_id,
                },
                success: function(response) {
                    $('#loading-notification').css('display', 'none');
                    if (response.message == 'success') {
                        let list = response.data || null;
                        console.log(list);
                        let _item = '';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<tr>';
                                _item += '<td>' + item.ingredient_category_name + '</td>';
                                _item += '<td>' + 'TP' + String(item.id).padStart(5, '0') + '</td>';
                                _item += '<td>' + item.name + '</td>';
                                _item += '<td>' + item.unit_default_name+ '</td>';
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

            let area_id = $('.area_id').val();
            if (area_id == '') {
                alert('Vui lòng chọn cơ sở');
                return false;
            }
            if (ischecked) {
                _root.remove();
                let _id = $(this).val();
                let url = "{{ route('mealmenu.searchIngredients.withTonkho') }}/";
                $('#loading-notification').css('display', 'flex');
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                        area_id: area_id,
                    },
                    success: function(response) {
                        $('#loading-notification').css('display', 'none');
                        $('.show_empty').remove();
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '';
                            var stt = $("#post_related tr").length ;
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr class="valign-middle">';
                                    _item += '<input type="hidden" name="cart[' + item.id +
                                        '][product_id]" value="' + item.id + '">';
                                    _item += '<td class="order-number">' + stt + '</td>';
                                    _item += '<td>' + item.name + '</td>';
                                    _item += '<td>' + item.unit_default_name + '</td>';
                                    _item += '<td><input name="cart[' + item.id +
                                        '][quantity]" value="1" class="input-field form-control quantity-input" type="number"></td>';
                                   
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
    </script>
@endsection
