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
                                    <th>@lang('Vị trí')</th>
                                    <th>@lang('Số lượng')</th>
                                    <th>@lang('Ghi chú')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asset_history as $items)
                                    <tr class="text-center">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            {{ $items->product->code ?? '' }}
                                        </td>
                                        <td>
                                            {{ $items->product->name ?? '' }}
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
                                            @lang($items->state ?? '')
                                        </td>
                                        <td>
                                            {{ $items->positions->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $items->quantity ?? 0 }}
                                        </td>
                                        <td>
                                            {{ $items->json_paaams->note ?? '' }}
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
                </div>
            </div>
        @endif


    </section>
@endsection

@section('script')
    <script></script>
@endsection
