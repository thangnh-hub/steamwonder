@extends('admin.layouts.app')
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
    </style>
@endpush
@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Main content -->
    <section class="content box_alert">
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
        <div class="box box-default">
            <div class="box-header with-border">
                <h2 class="box-title text-uppercase text-bold">
                    <i class="fa fa-history"></i> @lang('Thông tin lịch kiểm kê')
                </h2>
                <a class="btn btn-sm btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                    @lang('List')</a>
            </div>
            <div class="box-body">
                <div class="row invoice-info">
                    <div class="col-sm-4">
                        <address>
                            <p><strong>@lang('Kỳ kiểm kê'):</strong> {{ $detail->period ?? 'Chưa cập nhật' }}</p>
                            <p><strong>@lang('Người thực hiện'):</strong> {{ $detail->person->name ?? 'Chưa cập nhật' }}</p>
                            <p><strong>@lang('Ngày kiểm kê'):</strong>
                                {{ $detail->date_received != '' ? date('d-m-Y', strtotime($detail->date_received)) : 'Chưa cập nhật' }}
                            </p>
                            <p><strong>@lang('Trạng thái'):</strong> @lang($detail->status)</p>
                        </address>
                    </div>
                    <div class="col-sm-4">
                        <address>
                            <p><strong>@lang('Cơ sở'):</strong> {{ $detail->area->name ?? 'Chưa cập nhật' }}</p>
                            <p><strong>@lang('Kho'):</strong> {{ $detail->warehouse->name ?? 'Chưa cập nhật' }}</p>
                            <p><strong>@lang('Vị trí'):</strong> {{ $detail->positions->name ?? 'Chưa cập nhật' }}</p>
                            <p><strong>@lang('Phòng ban'):</strong> {{ $detail->departments->name ?? 'Chưa cập nhật' }}</p>

                        </address>
                    </div>
                    <div class="col-sm-12">
                        <address>
                            <p><strong>@lang('Ghi chú'):</strong> {{ $detail->json_params->note ?? 'Chưa cập nhật' }}</p>
                        </address>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($asset_history) && count($asset_history) > 0)
            <form class="form_inventory" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title text-uppercase text-bold">
                            <i class="fa fa-history"></i> @lang('Tài sản kiểm kê')
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asset_history as $items)
                                        <tr class="text-center">
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $items->asset_his->code ?? '' }}
                                            </td>
                                            <td>
                                                {{ $items->asset_his->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ __($items->product->warehouse_type ?? '') }}
                                            </td>
                                            <td>
                                                {{ $items->product->category_product->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $items->product->json_params->specification ?? '' }}
                                            </td>
                                            <td>
                                                {{ $items->product->json_params->origin ?? '' }}
                                            </td>
                                            <td>
                                                {{ $items->product->json_params->manufacturer ?? '' }}
                                            </td>
                                            <td>
                                                {{ $items->product->json_params->warranty ?? '' }}
                                            </td>
                                            <td>
                                                <select class="form-control select2" style="width: 100%"
                                                    name="asset[{{ $items->id }}][state]"
                                                    {{ $items->product->warehouse_type == 'vattutieuhao' ? 'disabled' : '' }}>
                                                    <option value="">@lang('Trình trạng')</option>
                                                    @foreach ($state as $key => $val)
                                                        <option value="{{ $key }}"
                                                            {{ isset($items->state) && $items->state == $key ? 'selected' : '' }}>
                                                            @lang($val) </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <select class="form-control select2" style="width: 100%"
                                                    name="asset[{{ $items->id }}][department_id]">
                                                    <option value="">@lang('Vị trí')</option>
                                                    @foreach ($department as $val)
                                                        <option value="{{ $val->id }}"
                                                            {{ isset($items->department_id) && $items->department_id == $val->id ? 'selected' : '' }}>
                                                            {{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control select2" style="width: 100%"
                                                    name="asset[{{ $items->id }}][position_id]">
                                                    <option value="">@lang('Vị trí')</option>
                                                    @foreach ($positions as $val)
                                                        @if (empty($val->parent_id) && $val->warehouse_id == $items->warehouse_id)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($items->position_id) && $items->position_id == $val->id ? 'selected' : '' }}>
                                                                @lang($val->name)</option>
                                                            @foreach ($positions as $val1)
                                                                @if ($val1->parent_id == $val->id)
                                                                    <option value="{{ $val1->id }}"
                                                                        {{ isset($items->position_id) && $items->position_id == $val1->id ? 'selected' : '' }}>
                                                                        - - @lang($val1->name)</option>
                                                                    @foreach ($positions as $val2)
                                                                        @if ($val2->parent_id == $val1->id)
                                                                            <option value="{{ $val2->id }}"
                                                                                {{ isset($items->position_id) && $items->position_id == $val2->id ? 'selected' : '' }}>
                                                                                - - - - @lang($val2->name)</option>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>

                                                <input type="number"
                                                    {{ $items->product->warehouse_type == 'vattutieuhao' ? '' : 'readonly' }}
                                                    class="form-control" name="asset[{{ $items->id }}][quantity]"
                                                    value="{{ $items->quantity ?? 0 }}" min="0">
                                            </td>
                                            <td>
                                                <textarea cols="3" name="asset[{{ $items->id }}][note]" class="form-control">{{ $items->json_params->note ?? '' }}</textarea>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                            <i class="fa fa-bars"></i> @lang('List')
                        </a>
                        <div class="pull-right">
                            <input type="hidden" class="synchronize" name="synchronize" value="">
                            <button type="button" style="margin-right: 30px" class="synchronize btn btn-warning btn-sm"><i
                                    class="fa fa-floppy-o"></i>
                                @lang('Lưu và đồng bộ')</button>

                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i>
                                @lang('Save')</button>
                        </div>

                    </div>
                </div>
            </form>
        @endif


    </section>
@endsection
@section('script')
    <script>
        $('.synchronize').click(function() {
            if (confirm('Thao tác này không thể hoàn lại.\nBạn chắc chắn muốn lưu và đồng bộ tài sản!')) {
                $('.synchronize').val('synchronize');
                $('.form_inventory').submit();
            }
        })
    </script>
@endsection
