@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@push('style')
    <style>

    </style>
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
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

        <form role="form" action="{{ route('warehouse_reimburse.store') }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Thông tin hoàn trả tài sản')</h3>
                        </div>
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Tên phiếu hoàn trả') <small class="text-red">*</small></label>
                                        <input type="text" class="form-control" name="name"
                                            placeholder="@lang('Tên phiếu hoàn trả')" value="{{ old('name') }}" required>
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
                                        <input type="text" class="form-control"
                                            value="{{ $admin_auth->name . ' (' . $admin_auth->admin_code . ')' }}" disabled>
                                        <input type="hidden" class="form-control" name="staff_request"
                                            value="{{ $admin_auth->id }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Ngày đề xuất') <small class="text-red">*</small></label>
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
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Chọn tài sản hoàn trả')</h3>
                        </div>
                        <div class="box-body">
                            <div class="d-flex-wap">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Cơ sở hoàn trả')<small class="text-red">*</small></label>
                                        <select name="area_id" class="area_id form-control select2">
                                            <option value="">Chọn</option>
                                            @foreach ($list_area as $val)
                                                <option value="{{ $val->id }}">{{ $val->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Kho hoàn trả')<small class="text-red">*</small></label>
                                        <select required name="warehouse_id" class="warehouse_id form-control select2">
                                            <option value="">Chọn</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Vị trí')</label>
                                        <select name="json_params[position_id]" class="form-control select2 position_id">
                                            <option value="">@lang('Please select')</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Phòng ban')</label>
                                        <select name="json_params[department_id]" class="department_id form-control select2"
                                            style="width: 100%;">
                                            <option value="">@lang('Please select')</option>
                                            @foreach ($department as $val)
                                                <option value="{{ $val->id }}">{{ $val->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Người sử dụng')</label>
                                        <select class="form-control select2 staff_request staff_entry" style="width: 100%;">
                                            <option value="">@lang('Please select')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Tên, mã tài sản')</label>
                                        <input type="text" class="form-control keyword"
                                            placeholder="@lang('Tên, mã tài sản')">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('Lọc tài sản')</label>
                                        <div>
                                            <button onclick="filter_asset()" type="button"
                                                class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h4>@lang('Danh sách tài sản hoàn trả')</h4>
                                    </div>
                                    <table class="table table-hover table-bordered sticky">
                                        <thead>
                                            <tr>
                                                <th>@lang('STT')</th>
                                                <th>@lang('Mã Tài Sản')</th>
                                                <th>@lang('Tên tài sản')</th>
                                                <th>@lang('Kho')</th>
                                                <th>@lang('Phòng ban')</th>
                                                <th>@lang('Người sử dụng')</th>
                                                <th style="width: 180px">@lang('Vị trí')</th>
                                                <th>
                                                    <input id="allCheckbox" class="all_checkbox cursor mr-15"
                                                        type="checkbox" autocomplete="off">
                                                    <span class="">@lang('Chọn tất cả') </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="{{ route('warehouse_reimburse.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
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
        var positions = @json($position ?? []);
        var staff_request = @json($staff_request ?? []);
        $('.area_id').change(function() {
            var area_id = $(this).val();
            var _html = _html_staff = '<option value="">@lang('Please select')</option>';
            if (area_id != '') {
                warehouses.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });
                staff_request.forEach(function(item) {
                    if (area_id == item.area_id) {
                        _html_staff += `<option value = "` + item.id + `" > ` + item.name;
                    }
                });

            }
            $('.warehouse_id').html(_html).trigger('change');
            $('.staff_request').html(_html_staff).trigger('change');
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
            $('.position_id').html(_html).trigger('change');
        })

        function checked_all() {
            $('#allCheckbox').change(function() {
                $('.each_checkbox').prop('checked', $(this).prop('checked'));
            });

            $('.each_checkbox').change(function() {
                $('#allCheckbox').prop('checked', $('.each_checkbox:checked').length === $('.each_checkbox')
                    .length);
            });
        }
        //hiển thị tài sản theo sản phẩm
        function filter_asset() {
            var area_id = $('.area_id').val();
            var keyword = $('.keyword').val();
            var warehouse_id = $('.warehouse_id').val();
            var position_id = $('.position_id').val();
            var department_id = $('.department_id').val();
            var staff_entry = $('.staff_entry').val();
            let url = "{{ route('warehouse_filter_asset_recall') }}"; //lấy danh sách tài sản
            let _targetHTML = $('.tbody-order-asset');
            if (!warehouse_id > 0) {
                alert('Vui lòng chọn kho hoàn trả');
                return false;
            }
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    area_id: area_id,
                    keyword: keyword,
                    warehouse_id: warehouse_id,
                    position_id: position_id,
                    department_id: department_id,
                    staff_entry: staff_entry,
                },
                success: function(response) {
                    $('#loading-notification').css('display', 'none');
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '';
                        let index = 1;
                        if (list.length > 0) {
                            list.forEach(items => {
                                var position = items.position;
                                _item += `<tr class="valign-middle">
                                        <td>${index }</td>
                                        <td>
                                            ${items.code ?? ''}
                                        </td>
                                        <td>
                                            ${items.name ?? ''}
                                        </td>
                                        <td>
                                            ${items.warehouse?.name ?? ''}
                                        </td>

                                        <td>
                                            ${items.department?.name ?? ''}
                                        </td>
                                        <td>
                                            ${items.staff_entry_use?.name ?? ''}
                                        </td>
                                        <td>
                                            ${items.position?.name ?? ''}
                                        </td>
                                        <td>
                                            <input name="asset[${items.product_id}][${index }][id]" class="each_checkbox mr-15 cursor"
                                                type="checkbox" value="` + items.id + `" autocomplete="off">
                                        </td>
                                    </tr>`;
                                index++;

                            });
                            _targetHTML.html(_item);
                            $(".select2").select2();
                            checked_all()
                        }
                    } else {
                        _targetHTML.html(
                            '<tr><td colspan="8"><strong>Không tìm thấy bản ghi</strong></td></tr>');
                    }
                    _targetHTML.trigger('change');
                },
                error: function(response) {
                    // Get errors
                    $('#loading-notification').css('display', 'none');
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }
    </script>
@endsection
