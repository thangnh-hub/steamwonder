@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
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

        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            @lang($module_name)
                        </h3>
                        <a class="btn btn-sm btn-primary pull-right hide-print"
                            href="{{ route('warehouse_reimburse.index') }}">
                            <i class="fa fa-bars"></i> @lang('Danh sách phiếu')
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <p>@lang('Tên phiếu hoàn trả'): {{ $detail->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Cơ sở hoàn trả'): {{ $detail->area->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Kho hoàn trả'): {{ $detail->warehouse->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Kỳ'): {{ $detail->period ?? date('Y-m', time()) }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Người đề xuất'): {{ $detail->nguoi_de_xuat->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày đề xuất'):
                                    {{ $detail->day_create != '' ? date('d-m-Y', strtotime($detail->day_create)) : '' }}
                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ghi chú'): {{ $detail->json_params->note ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày tạo phiếu'):
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom:10px">@lang('Danh sách tài sản đã hoàn trả')</h4>
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
                </div>
            </div>
        </div>
    </section>
@endsection
