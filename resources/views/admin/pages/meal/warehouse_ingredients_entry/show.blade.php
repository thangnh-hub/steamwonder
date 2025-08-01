@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
       .d-flex {
            display: flex;
        }
    </style>
@endsection

@section('content')
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
                        <a class="btn btn-sm btn-primary pull-right hide-print" href="{{ route('entry_warehouse') }}">
                            <i class="fa fa-bars"></i> @lang('Danh sách phiếu')
                        </a>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    @lang('Cở sở'):
                                    {{ $detail->area->name  }}
                                </p>
                            </div>
                           
                            <div class="col-xs-6">
                                <p>@lang('Tên phiếu'): {{ $detail->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Mã phiếu'): {{ $detail->code ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Người tạo phiếu'): {{ $detail->admin_created->name ?? '' }}</p>
                            </div>
                            <div class="col-xs-6">
                                <p>@lang('Ngày tạo phiếu'):
                                    {{ \Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? '' }}</p>
                            </div>

                            @if (isset($detail->json_params->note) && $detail->json_params->note != '')
                                <div class="col-xs-6">
                                    <p>@lang('Ghi chú'): {{ $detail->json_params->note ?? '' }}</p>
                                </div>
                            @endif

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom:10px">@lang('Danh sách sản phẩm nhập kho')</h4>
                                <table id="myTable" class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr class="valign-middle">
                                            <th class="text-center" style="width:50px">@lang('STT')</th>
                                            <th class="text-center">@lang('Sản phẩm')</th>
                                            <th class="text-center" style="width:75px">@lang('ĐVT')</th>
                                            <th class="text-center" style="width:75px">@lang('Số lượng')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order">
                                        @if ($entry_details->count() > 0)
                                            @foreach ($entry_details as $entry_detail)
                                                <tr class="valign-middle">
                                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        {{ $entry_detail->ingredient->name ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->ingredient->unitDefault->name ?? '' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $entry_detail->quantity ?? '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer hide-print">
                        <a class="btn btn-sm btn-success pull-right" target="_blank"
                            href="{{ route('warehouse_ingredients_entry.index') }}">
                            <i class="fa fa-bank"></i> @lang('Danh sách')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')

@endsection
