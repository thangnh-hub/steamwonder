@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table-bordered>thead:first-child>tr:first-child>th {
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
    <section class="content-header">
        <h1>
            @lang($module_name)
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
            <form action="{{ route('report_order') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kỳ') </label>
                                <input type="month" class="form-control" name="period"
                                    value="{{ isset($params['period']) ? $params['period'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Cơ sở') </label>
                                <select name="area_id" class="area_id form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($list_area as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Kho')</label>
                                <select name="warehouse_id" class="warehouse_avaible form-control select2">
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
                                <label>@lang('Trạng thái phiếu')</label>
                                <select name="status" class="form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $params['status'] == $key ? 'selected' : '' }}>
                                            @lang($val ?? '')
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Phòng ban')</label>
                                <select multiple name="department_request[]" class="form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($department as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['department_request']) &&  in_array($val->id,$params['department_request']) ? 'selected' : '' }}>
                                            @lang($val->name ?? '')
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report_order') }}">
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
                                <th style="width:50px" rowspan="2">@lang('STT')</th>
                                <th rowspan="2">@lang('Sản phẩm')</th>
                                <th rowspan="2">@lang('Loại SP')</th>
                                <th rowspan="2">@lang('ĐVT')</th>
                                <th rowspan="2">@lang('Đơn giá(Dự kiến)')</th>
                                <th rowspan="2">@lang('Đơn giá')</th>
                                <th colspan="{{ $list_dep->count() + 1 ?? 1 }}">@lang('Số lượng')</th>
                                <th rowspan="2">@lang('Tổng tiền')</th>
                            <tr>
                                @foreach ($list_dep as $dep)
                                    <th style="width:70px">{{ __($dep->code) }}</th>
                                @endforeach
                                <th style="width:70px">Tổng</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @if($rows->count() > 0)
                                @php
                                    $total_money = 0;
                                @endphp
                                @foreach ($rows as $row)
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>

                                        <td>
                                            {{ $row->product->name ?? '' }}
                                        </td>

                                        <td>
                                            {{ __($row->product->warehouse_type ?? '') }}
                                        </td>
                                        <td>
                                            {{ __($row->product->unit ?? '') }}
                                        </td>
                                        <td>
                                            {{ isset($row->product->price) && is_numeric($row->product->price) ? number_format($row->product->price, 0, ',', '.') . ' đ' : '' }}
                                        </td>
                                        <td>
                                            {{ isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') . ' đ' : '' }}

                                        </td>
                                        @foreach($list_dep as $dep)
                                            <td>
                                                @foreach($row->list_departments as $key => $val)
                                                    @if ($key == $dep->id)
                                                        {{ $val }}
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endforeach
                                        <td>{{ $row->total_quantity }}</td>
                                        <td>
                                            @php
                                                $total_money += $row->total_quantity*$row->price;
                                            @endphp
                                            {{ number_format(($row->total_quantity*$row->price), 0, ',', '.') . ' đ' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <br>
                    <p class="pull-right"><strong>Tổng tiền : {{ number_format(($total_money), 0, ',', '.') . ' đ' }}</strong></p>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')
<script>
    $('.area_id').change(function() {
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
