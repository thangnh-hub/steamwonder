@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .box_input_time{
            display: flex;
            gap: 5px;
        }
        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>
    </section>
@endsection

@section('content')
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default hide-print">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Mã tài sản, tên tài sản ...') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Mã tài sản, tên tài sản ...')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Phòng ban')</label>
                                <select name="department_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_department as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['department_id']) && $params['department_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tình trạng')</label>
                                <select name="state" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($state as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['state']) && $params['state'] == $key ? 'selected' : '' }}>
                                            @lang($val ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại tài sản')</label>
                                <select name="product_type" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['product_type']) && $params['product_type'] == $key ? 'selected' : '' }}>
                                            @lang($val ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Cơ sở')</label>
                                <select name="area_id" class="area_id form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($areas as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->code . '-' . $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kho')</label>
                                <select name="warehouse_id" class="warehouse_id form-control select2">
                                    <option value="">Chọn</option>
                                    @if (isset($params['warehouse_id']) && $params['warehouse_id'] != '')
                                        @foreach ($list_warehouse as $key => $val)
                                            @if (isset($params['area_id']) && $params['area_id'] != '')
                                                @if ($val->area_id == $params['area_id'])
                                                    <option value="{{ $val->id }}"
                                                        {{ isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : '' }}>
                                                        @lang($val->name ?? '')</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Vị trí')</label>
                                <select name="position_id" class="position_id form-control select2">
                                    <option value="">Chọn</option>
                                    @if (isset($params['position_id']) && $params['position_id'] != '')
                                        @if (isset($params['warehouse_id']) && $params['warehouse_id'] != '')
                                            @foreach ($list_position as $key => $val)
                                                @if ($val->warehouse_id == $params['warehouse_id'])
                                                    @if (empty($val->parent_id))
                                                    <option value="{{ $val->id }}"
                                                        {{ isset($params['position_id']) && $params['position_id'] == $val->id ? 'selected' : '' }}>
                                                        @lang($val->name)</option>
                                                    {{-- Cấp 2 --}}
                                                    @foreach ($list_position as $val1)
                                                        @if ($val1->parent_id == $val->id)
                                                            <option value="{{ $val1->id }}"
                                                                {{ isset($params['position_id']) && $params['position_id'] == $val1->id ? 'selected' : '' }}>
                                                                - - @lang($val1->name)</option>
                                                        @endif
                                                    @endforeach
                                                    @endif
                                                @endif

                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Phiếu nhập')</label>
                                <select name="entry_id" class="entry_id form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($warehouse_entry as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['entry_id']) && $params['entry_id'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->code }} - {{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Phiếu xuất')</label>
                                <select name="deliver_id" class="deliver_id form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($warehouse_deliver as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['deliver_id']) && $params['deliver_id'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->code }} - {{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body table-responsive box-alert">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Mã tài sản')</th>
                                <th>@lang('Tên tài sản')</th>
                                <th>@lang('Loại tài sản')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Phiếu nhập')</th>
                                <th>@lang('Phiếu xuất')</th>
                                <th style="width: 200px">@lang('Phòng ban')</th>
                                <th>@lang('Người sử dụng')</th>
                                <th>@lang('Kho')</th>
                                <th>@lang('Số lượng')</th>
                                <th style="width: 200px">@lang('Vị trí')</th>
                                <th style="width: 200px">@lang('Tình trạng')</th>
                                <th>@lang('Ghi chú')</th>
                                <th class="hide-print">@lang('Chức năng')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $row->code ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->product->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->product_type ?? '')
                                    </td>

                                    <td>
                                        {{ __($row->status ?? '') }}
                                    </td>
                                    <td>
                                        {{ ($row->warehouse_entry->code ?? '') . ' - ' . ($row->warehouse_entry->name ?? '') }}
                                    </td>
                                    <td>
                                        {{ ($row->warehouse_deliver->code ?? '') . ' - ' . ($row->warehouse_deliver->name ?? '') }}
                                    </td>
                                    <td>
                                        <div class="box_view view_department">
                                            {{ __($row->department->name ?? '') }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <select class="form-control select2 department_id" style="width: 100%">
                                                <option value="">@lang('Phòng ban')</option>
                                                @foreach ($list_department as $val)
                                                    <option value="{{ $val->id }}"
                                                        {{ isset($row->department_id) && $row->department_id == $val->id ? 'selected' : '' }}>
                                                        {{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        {{ __($row->staff_entry_use->name ?? '') }}
                                    </td>
                                    <td>
                                        {{ __($row->warehouse->name ?? '') }}
                                    </td>
                                    <td>
                                        {{ __($row->quantity ?? '') }}
                                    </td>
                                    <td>
                                        <div class="box_view view_position">
                                            {{ __($row->position->name ?? '') }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <select class="form-control select2 position_id" style="width: 100%">
                                                <option value="">@lang('Vị trí')</option>
                                                @foreach ($list_position as $val)
                                                    @if (empty($val->parent_id) && $val->warehouse_id == $row->warehouse_id)
                                                        <option value="{{ $val->id }}"
                                                            {{ isset($row->position_id) && $row->position_id == $val->id ? 'selected' : '' }}>
                                                            @lang($val->name)</option>
                                                        @foreach ($list_position as $val1)
                                                            @if ($val1->parent_id == $val->id)
                                                                <option value="{{ $val1->id }}"
                                                                    {{ isset($row->position_id) && $row->position_id == $val1->id ? 'selected' : '' }}>
                                                                    - - @lang($val1->name)</option>
                                                                @foreach ($list_position as $val2)
                                                                    @if ($val2->parent_id == $val1->id)
                                                                        <option value="{{ $val2->id }}"
                                                                            {{ isset($row->position_id) && $row->position_id == $val2->id ? 'selected' : '' }}>
                                                                            - - - - @lang($val2->name)</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view view_state">
                                            {{ __($row->state ?? '') }}
                                        </div>
                                        @if ($row->product_type != 'vattutieuhao')
                                            <div class="box_edit" style="display: none">
                                                <select class="form-control select2 state" style="width: 100%">
                                                    <option value="">@lang('Trình trạng')</option>
                                                    @foreach ($state as $key => $val)
                                                        <option value="{{ $key }}"
                                                            {{ isset($row->state) && $row->state == $key ? 'selected' : '' }}>
                                                            @lang($val) </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="box_view view_note">
                                            {{ $row->json_params->note ?? '' }}
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <textarea class="form-control note" rows="3">{{ $row->json_params->note ?? '' }}</textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view">
                                            <button class="btn btn-sm btn-warning btn_edit" data-toggle="tooltip"
                                                style="margin-right: 5px" title="@lang('Edit')"
                                                data-original-title="@lang('Edit')">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <div class="box_input_time">
                                                <button class="btn btn-sm btn-success btn_save" data-toggle="tooltip"
                                                    data-id="{{ $row->id }}"
                                                    data-original-title="@lang('Lưu')"><i class="fa fa-check"
                                                        aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-danger btn_exit" data-toggle="tooltip"
                                                    data-original-title="@lang('Hủy')"><i class="fa fa-times"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="box-footer clearfix hide-print">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        // var list_area = {!! json_encode($areas) !!};
        var warehouses = {!! json_encode($list_warehouse) !!};
        var positions = {!! json_encode($list_position) !!};

        $('.area_id').on('change', function() {
            var area_id = $(this).val();
            var _html = '<option value="">Chọn</option>';
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
            var _html = '<option value="">Chọn</option>';
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


        $('.btn_edit').click(function() {
            var h = $(this).parents('tr').find('.box_view');
            var s = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        })
        $('.btn_exit').click(function() {
            var s = $(this).parents('tr').find('.box_view');
            var h = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        });
        $('.btn_save').click(function() {
            if (confirm('Bạn chắc chắn muốn lưu tài sản !')) {
                var _id = $(this).data('id');
                var url = "{{ route('warehouse_asset.update', ':id') }}".replace(':id', _id);
                // Lấy dữ liệu truyền ajax
                var state = $(this).parents('tr').find('.state').val();
                var department_id = $(this).parents('tr').find('.department_id').val();
                var position_id = $(this).parents('tr').find('.position_id').val();
                var note = $(this).parents('tr').find('.note').val();
                // var quantity = $(this).parents('tr').find('.quantity').val();
                // View đổi nội dung
                var view_state = $(this).parents('tr').find('.view_state');
                var view_department = $(this).parents('tr').find('.view_department');
                var view_position = $(this).parents('tr').find('.view_position');
                var view_note = $(this).parents('tr').find('.view_note');
                // var view_quantity = $(this).parents('tr').find('.view_quantity');
                // ẩn hiện
                var btn_exit = $(this).parents('tr').find('.btn_exit');
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        state: state,
                        department_id: department_id,
                        position_id: position_id,
                        note: note,
                        // quantity: quantity,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'warning') {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            ` + response.message + `
                                        </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);

                            } else {
                                var _html = `<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    ` + response.message + `
                                </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);
                                // Cập nhật lại view
                                view_state.html(response.data.state);
                                view_department.html(response.data.department);
                                view_position.html(response.data.position);
                                view_note.html(response.data.note);
                                // view_quantity.html(response.data.quantity);
                            }

                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            Bạn không có quyền thao tác chức năng này!
                                        </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                        btn_exit.click();
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }

        })
        function show_hide(show, hide) {
            show.show();
            hide.hide();
        }
    </script>
@endsection
