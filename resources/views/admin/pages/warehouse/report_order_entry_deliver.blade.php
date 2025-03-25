@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table-bordered>thead>tr>th {
            text-align: center;
            vertical-align: middle;
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
    {{-- <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section> --}}
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
            <form id="form_filter_warehouse" action="{{ route('report_order_entry_deliver') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Từ khóa') </label>
                                <input type="keyword" class="form-control" name="keyword" placeholder="Tên tài sản"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kho')</label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_warehouse as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại')</label>
                                <select name="warehouse_type" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($warehouse_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['warehouse_type']) && $params['warehouse_type'] == $key ? 'selected' : '' }}>
                                            @lang($val ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Danh mục')</label>
                                    <select name="warehouse_category_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($warehouse_category as $category_product)
                                        @if ($category_product->category_parent == '' || $category_product->category_parent == null)
                                            <option {{ isset($params['warehouse_category_id_before']) && $params['warehouse_category_id_before'] == $category_product->id ? 'selected' : '' }} value="{{ $category_product->id }}">
                                                {{ $category_product->name ?? '' }}</option>
                                            @foreach ($warehouse_category as $category_sub)
                                                @if ($category_sub->category_parent == $category_product->id)
                                                    <option {{ isset($params['warehouse_category_id_before']) && $params['warehouse_category_id_before'] == $category_sub->id ? 'selected' : '' }} value="{{ $category_sub->id }}">
                                                        - - - {{ $category_sub->name ?? '' }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kỳ') </label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ isset($params['period']) ? $params['period'] : '' }}">
                            </div>
                        </div> --}}
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Từ ngày') </label>
                                <input required type="date" class="form-control" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Đến ngày') </label>
                                <input required type="date" class="form-control" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report_order_entry_deliver') }}">
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
        @isset($rows)
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">@lang($module_name)</h3>
                    <a href="javascript:void(0)" data-url="{{ route('export_report_warhouse_entry_deliver') }}" class="btn btn-sm btn-success pull-right ml-15 hide-print btn_export_report"><i class="fa fa-file-excel-o"></i>
                         Export Excel</a>
                    <button id="printButton" onclick="window.print()" class="btn btn-primary btn-sm pull-right "><i class="fa fa-print"></i> In thông tin PDF</button>
                </div>
                <div class="box-body">
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
                        <table class="table table-hover table-bordered sticky">
                            <thead>
                                <tr>
                                    <th style="width:50px" rowspan="2">@lang('STT')
                                    </th>
                                    <th rowspan="2">@lang('Tên TS (A)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="@lang('Tên tài sản')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button> 
                                    </th>
                                    <th rowspan="2">@lang('Danh mục (B)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="@lang('Danh mục sản phẩm')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2">@lang('Loại (C)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="@lang('Loại sản phẩm')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2">@lang('ĐVT (D)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" title="@lang('Đơn vị tính')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th colspan="3">@lang('Nhập ') 
                                        
                                    </th>
                                    <th colspan="3">@lang('Xuất')
                                        
                                    </th>
                                    <th colspan="2">@lang('Điều chuyển ')
                                        
                                    </th>
                                    <th >@lang('Thu hồi ')
                                        
                                    </th>
                                    <th colspan="3">@lang('Tồn kho ')
                                       
                                    </th>
                                </tr>    
                                <tr>
                                    <th style="width:75px">@lang('Số lượng (J)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Số lượng đã nhập kho')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px">@lang('Đơn giá (K)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Đơn giá nhập kho')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px">@lang('Thành tiền (L)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Thành tiền nhập kho')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px">@lang('Số lượng (M)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Số lượng đã xuất kho')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button> 
                                    </th>
                                    <th style="width:75px">@lang('Đơn giá (N)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Đơn giá xuất kho')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px">@lang('Thành tiền (O)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Thành tiền xuất kho')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px">@lang('SL giao (P)') 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('SL giao điều chuyển')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px">@lang('SL nhận (Q)') 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('SL nhận điều chuyển')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px">@lang('Số lượng thu hồi (R)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Số lượng đã thu hồi')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>

                                    <th style="width:75px">@lang('Đầu kỳ (S)') 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('S = (J + Q) - (M + P) (kỳ trước)')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px">@lang('Cuối kỳ (T)')
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('T = (S) + (J + Q) - (M + P)')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                    <th style="width:75px">@lang('Hiện tại (U)') 
                                        <button style="border: 0px ;font-size:18px" class="btn btn-primary" data-toggle="tooltip" title="@lang('Tồn kho thực tế hiện tại')">
                                            <i class="fa fa-question-circle-o"></i>
                                        </button>
                                    </th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($params['from_date']) && $params['from_date'] != '' && isset($params['to_date']) && $params['to_date'] != '')
                                    @php
                                        $stt=1;
                                    @endphp
                                    @foreach ($rows as $row)
                                        <tr class="valign-middle">
                                            <td class="text-center">
                                                {{ $stt++ }}
                                            </td>
                                            <td>
                                                {{ $row->product->name ?? '' }} {{ $row->product->id ?? '' }}
                                            </td>
                                            <td>
                                                {{ $row->product->category_product->name ?? '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ __($row->product->warehouse_type ?? '')  }}
                                            </td>
                                            <td class="text-center">
                                                {{ __($row->product->unit ?? '')  }}
                                            </td>
                                            <td class="text-right">
                                                {{ $row->nhap_kho_quantity ?? '' }}
                                            </td>
                                            <td class="text-right">
                                                {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0 }}
                                            </td>
                                            <td class="text-right">
                                                {{ isset($row->nhap_kho_subtotal_money) && is_numeric($row->nhap_kho_subtotal_money) ? number_format($row->nhap_kho_subtotal_money, 0, ',', '.') : 0 }}
                                            </td>
                                            <td class="text-right">
                                                {{ $row->xuat_kho_quantity ?? '' }}
                                            </td>

                                            <td class="text-right">
                                                {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0 }}
                                            </td>
                                            <td class="text-right">
                                                {{ isset($row->xuat_kho_subtotal_money) && is_numeric($row->xuat_kho_subtotal_money) ? number_format($row->xuat_kho_subtotal_money, 0, ',', '.') : 0 }}
                                            </td>
                                        
                                            <td class="text-right">
                                                {{ $row->dieu_chuyen_giao_quantity ?? '' }}
                                            </td>
                                            
                                            <td class="text-right">
                                                {{ $row->dieu_chuyen_nhan_quantity ?? '' }}
                                            </td>

                                            <td class="text-right">
                                                {{ $row->thu_hoi_quantity ?? '' }}
                                            </td>

                                            <td class="text-right">
                                                {{ $row->ton_kho_truoc_ky_quantity }}
                                            </td>

                                            <td  class="text-right">
                                                {{ $row->ton_kho_trong_ky_quantity }}
                                            </td>

                                            <td class="text-right">
                                                {{ $row->ton_kho_quantity }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if(isset($row2) )
                                    @foreach ($rows2 as $row)
                                    @if($row->ton_kho_truoc_ky_quantity != 0)
                                        <tr class="valign-middle">
                                            <td class="text-center">
                                                {{ $stt++ }}
                                            </td>
                                            <td>
                                                {{ $row->product->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $row->product->category_product->name ?? '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ __($row->product->warehouse_type ?? '')  }}
                                            </td>
                                            <td class="text-center">
                                                {{ __($row->product->unit ?? '')  }}
                                            </td>
                                            <td class="text-right">
                                                {{-- {{ $row->nhap_kho_quantity ?? '' }} --}} 0
                                            </td>
                                            <td class="text-right">
                                                {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0 }}
                                            </td>
                                            <td class="text-right">
                                                0 {{-- {{ isset($row->nhap_kho_subtotal_money) && is_numeric($row->nhap_kho_subtotal_money) ? number_format($row->nhap_kho_subtotal_money, 0, ',', '.') : 0 }} --}}
                                            </td>
                                            <td class="text-right">
                                                {{-- {{ $row->xuat_kho_quantity ?? '' }} --}} 0
                                            </td>

                                            <td class="text-right">
                                                {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : 0 }}
                                            </td>
                                            <td class="text-right">
                                              0  {{-- {{ isset($row->xuat_kho_subtotal_money) && is_numeric($row->xuat_kho_subtotal_money) ? number_format($row->xuat_kho_subtotal_money, 0, ',', '.') : 0 }} --}}
                                            </td>
                                        
                                            <td class="text-right">
                                                {{ $row->dieu_chuyen_giao_quantity ?? '' }}
                                            </td>
                                            
                                            <td class="text-right">
                                                {{ $row->dieu_chuyen_nhan_quantity ?? '' }}
                                            </td>

                                            <td class="text-right">
                                                {{ $row->thu_hoi_quantity ?? '' }}
                                            </td>

                                            <td class="text-right">
                                                {{ $row->ton_kho_truoc_ky_quantity }}
                                            </td>

                                            <td  class="text-right">
                                                {{ $row->ton_kho_trong_ky_quantity }}
                                            </td>

                                            <td class="text-right">
                                                {{ $row->ton_kho_quantity }}
                                            </td>
                                        </tr>
                                    @endif    
                                    @endforeach
                                    @endif
                                    <tr class="valign-middle text-bold" style="font-size: 15px;">
                                        <td colspan="5" class="text-right">
                                        </td>
                                        <td colspan="2" class="text-right">
                                            @lang('Tổng tiền nhập hàng:')
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($rows->sum('nhap_kho_subtotal_money'), 0, ',', '.') }}
                                        </td>
                                        <td colspan="2" class="text-right">
                                            @lang('Tổng tiền xuất hàng:')
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($rows->sum('xuat_kho_subtotal_money'), 0, ',', '.') }}
                                        </td>
                                        <td colspan="5" class="text-right">
                                            @lang('Tổng tiền hàng tồn:')
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($rows->map(fn($item) => (int)$item['ton_kho_quantity'] * (int)$item['price'])->sum(), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif    
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endisset
    </section>
@endsection
@section('script')
<script>
    $('.btn_export_report').click(function() {
            var formData = $('#form_filter_warehouse').serialize();
            var url = $(this).data('url');
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
                        a.download = 'reporEntryDeliver.xlsx';
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
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                    eventInProgress = false;
                }
            });
        })
</script>
@endsection
