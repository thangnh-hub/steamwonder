@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@push('style')
    <style>
        table {
            border: 1px solid #dddddd;
        }

        th {
            text-align: center;
            vertical-align: middle !important;
        }

        th,
        td {
            border: 1px solid #dddddd;
        }

        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
@endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                @lang('List')</a>
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

        <form role="form" class="form_inventory" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                        @csrf
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Kỳ kiểm kê') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="period"
                                            placeholder="@lang('Kỳ kiểm kê')" value="{{ old('period') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Người thực hiện')<small class="text-red">*</small></label>
                                        <select required name="person_id" class=" form-control select2">
                                            <option value="">Chọn</option>
                                            @foreach ($persons as $key => $val)
                                                <option value="{{ $val->id }}"
                                                    {{ isset($detail->person_id) && $detail->person_id == $val->id ? 'selected' : '' }}>
                                                    {{ $val->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Ngày kiểm kê') <small class="text-red">*</small></label>
                                        <input required type="date" class="form-control" name="date_received"
                                            value="{{ $detail->day_create ?? date('Y-m-d', time()) }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở')<small class="text-red">*</small></label>
                                        <select name="area_id" class="area_id form-control select2" required>
                                            <option value="">@lang('Please select')</option>
                                            @foreach ($areas as $key => $val)
                                                <option value="{{ $val->id }}">
                                                    @lang($val->name ?? '')</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Kho')<small class="text-red">*</small></label>
                                        <select name="warehouse_id" class="warehouse_id form-control select2" required>
                                            <option value="">@lang('Please select')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Phòng ban')</label>
                                        <select class="form-control select2 department" name="department">
                                            <option value="">@lang('Please select')</option>
                                            @foreach ($department as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Vị trí')</label>
                                        <select class="form-control select2 positions" name="positions_id">
                                            <option value="">@lang('Please select')</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>@lang('Ghi chú')</label>
                                        <textarea name="json_params[note]" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="button"
                                            class="btn btn-success btn_get_view_product">@lang('Lấy danh sách sản phẩm')</button>
                                    </div>
                                </div>

                                <div class="col-md-12" style="border-top: 1px solid #ccc; padding-top:15px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="box-title" style="padding-bottom:10px;">@lang('Danh sách sản phẩm')</h4>
                                            <table id="myTable" class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('STT')</th>
                                                        <th>@lang('Mã tài sản')</th>
                                                        <th>@lang('Tên tài sản')</th>
                                                        <th>@lang('Loại tài sản')</th>
                                                        <th>@lang('Danh mục')</th>
                                                        <th>@lang('Quy cách')</th>
                                                        <th>@lang('Xuất xứ')</th>
                                                        <th>@lang('Hãng SX')</th>
                                                        <th>@lang('Bảo hành')</th>
                                                        <th>@lang('Tình trạng')</th>
                                                        <th style="width: 170px">@lang('Phòng ban')</th>
                                                        <th style="width: 170px">@lang('Vị trí')</th>
                                                        <th style="width: 150px">@lang('Số lượng tồn kho')</th>
                                                        <th style="min-width: 200px">@lang('Ghi chú')</th>
                                                        <th>@lang('Chọn')</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="tbody-order" id="view_list_product">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <div class="pull-right">
                                <input type="hidden" class="synchronize" name="synchronize" value="">
                                <button type="button" style="margin-right: 30px" name="synchronize"
                                    class="synchronize btn btn-warning btn-sm"><i class="fa fa-floppy-o"></i>
                                    @lang('Lưu và đồng bộ')</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i>
                                    @lang('Lưu lại ')</button>

                            </div>

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
        var positions = @json($positions ?? []);
        var departments = @json($department ?? []);
        var state = @json($state ?? []);
        $('.area_id').change(function() {
            var area_id = $(this).val();
            var _html = '<option value="">@lang('Please select')</option>';
            if (area_id != '') {
                warehouses.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });
            }
            $('.warehouse_id').html(_html).trigger('change');
        })
        $('.warehouse_id').on('change', function() {
            var warehouse_id = $(this).val();
            var _html = '<option value="">@lang('Please select')</option>';
            if (warehouse_id != '') {
                positions.forEach(function(item) {
                    if (warehouse_id == item.warehouse_id) {
                        if (item.parent_id == null || item.parent_id == '') {
                            _html += `<option value = "` + item.id + `" > ` + item.name;
                            positions.forEach(function(sub) {
                                if (sub.parent_id == item.id) {
                                    _html += `<option value = "` + sub.id + `" > - - ` + sub.name;
                                    positions.forEach(function(sub_child) {
                                        if (sub_child.parent_id == sub.id) {
                                            _html += `<option value = "` + sub_child.id +
                                                `" > - - - - ` + sub_child.name;
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
            $('.positions').html(_html).trigger('change');
        })

        // Lưu và đồng bộ
        $('.synchronize').click(function() {
            if (!$('.form_inventory')[0].checkValidity()) {
                $('.form_inventory')[0].reportValidity();
                return false;
            }
            // Nếu dữ liệu hợp lệ, hiển thị xác nhận
            if (confirm('Thao tác này không thể hoàn lại.\nBạn chắc chắn muốn lưu và đồng bộ tài sản!')) {
                $('.synchronize').val('synchronize');
                $('.form_inventory').submit();
            }
        });

        $('.warehouse_id, .department, .positions').change(function() {
            $('#view_list_product').html('');
        })

        $('.btn_get_view_product').click(function() {
            var area_id = $('.area_id').val();
            var warehouse_id = $('.warehouse_id').val();
            var department_id = $('.department').val();
            var positions_id = $('.positions').val();
            if (area_id == '' || warehouse_id == '') {
                alert('Cần chọn cơ sở và kho trước khi lấy sản phẩm !')
                return;
            }
            get_view_product(warehouse_id, department_id, positions_id);
        })

        function get_view_product(warehouse_id, department_id, position_id) {
            let url = "{{ route('warehouse_inventory.get_view_list_product') }}";
            let _targetHTML = $('#view_list_product');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    warehouse_id: warehouse_id,
                    department_id: department_id,
                    position_id: position_id,
                },
                success: function(response) {
                    $('#loading-notification').css('display', 'none');
                    var warehouse_asset = response.data.warehouse_asset
                    var position = response.data.positions
                    var _html = renderView(warehouse_asset,position);
                    _targetHTML.html(_html);
                    $('.select2').select2();

                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    $('#loading-notification').css('display', 'none');
                    alert(errors);
                }
            });
        }

        function renderView(warehouse_asset,position) {
            var _html = '';
            var stt = 0;
            warehouse_asset.forEach(items => {
                stt++;
                _html += `
                        <tr class="text-center">
                            <td>
                                ` + stt + `
                            </td>
                            <td>
                                ${items.code ?? ''}
                            </td>
                            <td>
                                ${items.name ?? ''}
                            </td>
                            <td>
                                ${items.product_type ?? ''}
                            </td>
                            <td>
                                 ${items.product.category_product.name ?? ''}
                            </td>
                            <td>
                                ${items.product.json_params.specification ?? ''}
                            </td>
                            <td>
                               ${items.product.json_params.origin ?? ''}
                            </td>
                            <td>
                                ${items.product.json_params.manufacturer ?? ''}
                            </td>
                            <td>
                                ${items.product.json_params.warranty ?? ''}
                            </td>
                            <td>
                                <select class="form-control select2" name="asset[${items.id ?? ''}][state]" style="width: 100%"
                                    ${items.product_type == 'vattutieuhao' ? 'disabled' : ''}>
                                    <option value="">@lang('Trình trạng')</option>`;
                                    Object.entries(state).forEach(([key, val]) => {
                                        _html += `
                                        <option value="${key ?? ''}"
                                        ${items.state == key ? 'selected' : ''}>
                                        ${val ?? ''} </option>
                                        `;
                                    });
                                _html += ` </select>
                            </td>
                            <td>
                                <select class="form-control select2" name="asset[${items.id ?? ''}][department_id]" style="width: 100%">
                                    <option value="">@lang('Phòng ban')</option>`;

                                    Object.entries(departments).forEach(([key, val]) => {
                                        _html += `
                                        <option value="${val.id}"
                                        ${items.department_id == val.id ? 'selected' : ''}>
                                        ${val.name ?? ''} </option>
                                        `;
                                    });
                                    _html += `</select>
                            </td>
                            <td>
                                <select class="form-control select2" name="asset[${items.id ?? ''}][position_id]" style="width: 100%">
                                    <option value="">@lang('Vị trí')</option>`;
                                    Object.entries(position).forEach(([key, val_p])  => {
                                        if(val_p.parent_id =='' || val_p.parent_id == null){
                                            console.log(val_p.parent_id);
                                            _html += `
                                                <option value="${val_p.id}"
                                                ${items.position_id == val_p.id ? 'selected' : ''}>
                                                ${val_p.name ?? ''} </option>
                                                `;
                                            Object.entries(position).forEach(([key1, val_p1])  => {
                                                if(val_p1.parent_id == val_p.id){
                                                    _html += `
                                                        <option value="${val_p1.id}"
                                                        ${items.position_id == val_p1.id ? 'selected' : ''}>
                                                        - - ${val_p1.name ?? ''} </option>
                                                        `;
                                                    Object.entries(position).forEach(([key2, val_p2])  => {
                                                        if(val_p2.parent_id == val_p1.id){
                                                            _html += `
                                                                <option value="${val_p2.id}"
                                                                ${items.position_id == val_p2.id ? 'selected' : ''}>
                                                                - - - - ${val_p2.name ?? ''} </option>
                                                                `;
                                                        }
                                                    });
                                                }
                                            });
                                        }
                                    });
                                _html += `</select>
                            </td>
                            <td>
                                <input ${items.product_type =='vattutieuhao'? '' : 'readonly'} type="number"
                                name="asset[${items.id ?? ''}][quantity]" class="form-control" value="${items.quantity ?? 0}"
                                min="0">
                            </td>
                            <td>
                                <textarea cols="3" name="asset[${items.id ?? ''}][note]" class="form-control"></textarea>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="$(this).parents('tr').remove();" data-toggle="tooltip"
                                    title="@lang('Delete')" data-original-title="@lang('Delete')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        `;
            });
            return _html;
        }
    </script>
@endsection
