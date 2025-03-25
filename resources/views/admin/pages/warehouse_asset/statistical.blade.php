@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table-bordered>thead>tr>th {
            vertical-align: middle;
        }

        .show_detail .table>thead>tr {
            background-color: #758d9b;
        }

        .block_full_width {
            display: block;
            width: 100%;
            height: 100%;
        }

        .show_detail:hover,
        .td_detail:hover,
        .show_detail.active {
            background-color: #f39c12 !important;
            color: #fff;
        }

        .td_detail:hover a {
            color: #fff;
        }

        @media print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }

            .show-print {
                display: block;
            }
        }
    </style>
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection

@section('content')
    <div id="loading-notification" class="loading-notification">
        <p>@lang('Please wait')...</p>
    </div>
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default hide-print">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form id="form_filter" action="{{ route('warehouse_asset.statistical') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Tên tài sản ...') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên tài sản ...')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Danh mục tài sản')</label>
                                <select name="warehouse_category_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($category_product as $val)
                                        @if ($val->category_parent == null || $val->category_parent == '')
                                            <option value="{{ $val->id }}"
                                                {{ isset($params['warehouse_category_id']) && $params['warehouse_category_id'] == $val->id ? 'selected' : '' }}>
                                                @lang($val->name ?? '')</option>
                                            @foreach ($category_product as $child)
                                                @if ($child->category_parent == $val->id)
                                                    <option value="{{ $child->id }}"
                                                        {{ isset($params['warehouse_category_id']) && $params['warehouse_category_id'] == $child->id ? 'selected' : '' }}>
                                                        - - - @lang($child->name ?? '')</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('kho')</label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($warehouse as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm mr-10"
                                        href="{{ route('warehouse_asset.statistical') }}">
                                        @lang('Reset')
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning " onclick="window.print()"><i
                                            class="fa fa-print"></i>
                                        @lang('In danh sách')</button>
                                    <button type="button" class="btn btn-sm btn-success btn_export"
                                        data-url="{{ route('warehouse_asset.export_statistical') }}"><i class="fa fa-file-excel-o"
                                            aria-hidden="true"></i>
                                        @lang('Export')</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header hide-print">
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

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">@lang('STT')</th>
                            <th rowspan="2">@lang('Mã tài sản')</th>
                            <th rowspan="2">@lang('Tên tài sản')</th>
                            <th rowspan="2">@lang('Loại tài sản')</th>
                            @foreach ($list_area as $area)
                                <th class="text-center" colspan="{{ count($area->warehouse) }}">{{ $area->name }}</th>
                            @endforeach
                            <th colspan="2" class="text-center">@lang('Tổng')</th>
                        </tr>
                        <tr>
                            @foreach ($list_area as $area)
                                @foreach ($area->warehouse as $warehouse)
                                    <th class="text-center">{{ $warehouse->name }}</th>
                                @endforeach
                            @endforeach
                            <th class="text-center">@lang('Trong kho')</th>
                            <th class="text-center">@lang('Đ.Sử dụng')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr class="valign-middle">
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $row->product_code ?? '' }}
                                </td>
                                <td>
                                    {{ $row->name ?? '' }}
                                </td>
                                <td>
                                    @lang($row->product_type ?? '')
                                </td>
                                @foreach ($list_area as $area)
                                    @foreach ($area->warehouse as $warehouse)
                                        <td class="position-relative text-center show_detail cursor"
                                            data-id="{{ $warehouse->id }}" data-product = "{{ $row->product_id }}"
                                            title="@lang('Chi tiết')">
                                            {{ $row->warehouse[$warehouse->id]['total'] ?? 0 }}
                                        </td>
                                    @endforeach
                                @endforeach
                                <td class="text-center">
                                    {{ $row->total_warehouse_new }}
                                </td>
                                <td class="text-center">
                                    {{ $row->total_warehouse_using }}
                                </td>
                            </tr>
                            <tr class="tr_detail tr_department_{{ $row->product_id }}"></tr>
                            <tr class="tr_detail tr_position_{{ $row->product_id }}"></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.show_detail').on('click', function() {
                var _this = $(this);
                var _colspan = '{{ $count_warehouse }}';
                // Lấy val()
                var warehouse = _this.data('id'); // Lấy ID kho
                var product = _this.data('product'); // Lấy ID sản phẩm
                // Box view
                var _view_department = $('.tr_department_' + product);
                var _view_position = $('.tr_position_' + product);
                _view_department.html('');
                _view_position.html('');
                // check active
                if (_this.hasClass('active')) {
                    _this.removeClass('active')
                    return;
                }
                // Thêm active và bỏ active cùng tr
                _this.parents('tr').find('.show_detail').removeClass('active');
                _this.addClass('active');
                // Gọi ajax
                var url = "{{ route('warehouse_asset.view_statistical') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        warehouse_id: warehouse,
                        product_id: product,
                        colspan: _colspan,
                    },
                    success: function(response) {
                        _view_department.html(response.data.view_department);
                        _view_position.html(response.data.view_position);
                    },
                    error: function(response) {
                        var errors = response.responseJSON.errors;
                        alert(errors);
                    }
                });
            });

            $('.btn_export').click(function() {
                var formData = $('#form_filter').serialize();
                var url = $(this).data('url');
                show_loading_notification()
                $.ajax({
                    url: url,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    data: formData,
                    success: function(response) {
                        if (response) {
                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = 'Thong_ke_tai_san.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert').remove();
                            }, 3000);
                        }
                        hide_loading_notification()
                    },
                    error: function(response) {
                        hide_loading_notification()
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });
    </script>
@endsection
