@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Thông tin thu hồi tài sản')</h3>
                    </div>
                    <div class="box-body">
                        <div class="d-flex-wap">
                            <div class="col-md-12">
                                <p>@lang('Tên phiếu thu hồi'): {{ $detail->name ?? '' }}</p>
                                <p>@lang('Kỳ'): {{ $detail->period ?? date('Y-m', time()) }}</p>
                                <p>@lang('Người đề xuất'): {{ $detail->nguoi_de_xuat->name ?? '' }}</p>
                                <p>@lang('Ngày đề xuất'):
                                    {{ $detail->day_create != '' ? date('d-m-Y', strtotime($detail->day_create)) : '' }}
                                </p>
                                <p>@lang('Ghi chú'): {{ $detail->json_params->note ?? '' }}</p>
                                <p>@lang('Ngày tạo phiếu'):
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Thông tin tài sản đã thu hồi')</h3>
                    </div>
                    <div class="box-body">
                        <div class="d-flex-wap">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p>@lang('Cơ sở thu hồi'): {{ $detail->area->name ?? '' }}</p>
                                </div>
                                <div class="form-group">
                                    <p>@lang('Kho thu hồi'): {{ $detail->warehouse->name ?? '' }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr>
                                            <th>@lang('STT')</th>
                                            <th>@lang('Mã Tài Sản')</th>
                                            <th>@lang('Tên tài sản')</th>
                                            <th>@lang('Kho')</th>
                                            <th>@lang('Vị trí')</th>
                                            <th>@lang('Phòng ban')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order-asset">
                                        @if (isset($list_asset))
                                            @foreach ($list_asset as $asset)
                                                <tr class="valign-middle">
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        <p>{{ $asset->code ?? '' }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ $asset->name ?? '' }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ $asset->warehouse->name ?? '' }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ $asset->position->name ?? '' }}</p>
                                                    </td>
                                                    <td>
                                                        <p>{{ $asset->department->name ?? '' }}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                            <i class="fa fa-bars"></i> @lang('List')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
