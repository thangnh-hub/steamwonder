@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        th {
            text-align: center;
            vertical-align: middle !important;
        }

        .modal-header {
            background-color: #3c8dbc;
            color: white;
        }

        .table-wrapper {
            max-height: 450px;
            overflow-y: auto;
            display: block;
        }

        .table-wrapper thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 2;
        }

        .table-wrapper table {
            border-collapse: separate;
            width: 100%;
        }
    </style>
@endsection


@section('content')

    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
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


        <div class="box box-default">
            <div class="box-body ">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="">
                                            <a href="#tab_1" data-toggle="tab">
                                                <h5>Thông tin chính </h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_2" data-toggle="tab">
                                                <h5>Người thân của bé</h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_3" data-toggle="tab">
                                                <h5>Dịch vụ đăng ký</h5>
                                            </a>
                                        </li>
                                        <li class="active">
                                            <a href="#tab_4" data-toggle="tab">
                                                <h5>Biên lai thu phí</h5>
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="#tab_5" data-toggle="tab">
                                                <h5>Chương trình KH.Mãi</h5>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <!-- TAB 1: Thông tin học sinh -->
                                        <div class="tab-pane" id="tab_1">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p><strong>@lang('Mã học sinh'):</strong>
                                                        {{ $detail->student_code ?? '' }}
                                                    </p>
                                                    <p><strong>@lang('Họ và tên'):</strong>
                                                        {{ $detail->first_name ?? '' }} {{ $detail->last_name ?? '' }}
                                                    </p>
                                                    <p><strong>@lang('Ngày sinh'):</strong>
                                                        {{ $detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : '' }}
                                                    </p>
                                                    <p><strong>@lang('Tên thường gọi'):</strong>
                                                        {{ $detail->nickname ?? '' }}
                                                    </p>
                                                </div>

                                                <div class="col-md-4">
                                                    <p><strong>@lang('Khu vực'):</strong>
                                                        {{ $detail->area->name ?? '' }}
                                                    </p>
                                                    <p><strong>@lang('Giới tính'):</strong>
                                                        {{ __($detail->sex ?? '') }}
                                                    </p>
                                                    <p><strong>@lang('Lớp đang học'):</strong>
                                                        {{ $detail->currentClass->name ?? '' }}
                                                    </p>
                                                    <p><strong>@lang('Ngày nhập học'):</strong>
                                                        {{ isset($detail->enrolled_at) && $detail->enrolled_at != '' ? date('d-m-Y', strtotime($detail->enrolled_at)) : '' }}
                                                    </p>
                                                </div>

                                                <div class="col-md-4">
                                                    <p><strong>@lang('Ảnh đại diện'):</strong>
                                                    </p>
                                                    <a target="_blank"
                                                        href="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}">
                                                        <img src="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}"
                                                            alt="avatar" style="max-height:180px;">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TAB 2: Người thân -->
                                        <div class="tab-pane" id="tab_2">
                                            @if ($detail->studentParents->isNotEmpty())
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('STT')</th>
                                                            <th>@lang('Avatar')</th>
                                                            <th>@lang('Họ và tên')</th>
                                                            <th>@lang('Mối quan hệ')</th>
                                                            <th>@lang('Giới tính')</th>
                                                            <th>@lang('Ngày sinh')</th>
                                                            <th>@lang('Số điện thoại')</th>
                                                            <th>@lang('Email')</th>
                                                            <th>@lang('Địa chỉ')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($detail->studentParents as $index => $relation)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>
                                                                    @if (!empty($relation->parent->avatar))
                                                                        <img src="{{ asset($relation->parent->avatar) }}"
                                                                            alt="Avatar" width="100" height="100"
                                                                            style="object-fit: cover;">
                                                                    @else
                                                                        <span class="text-muted">No image</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ $relation->parent->first_name ?? '' }}
                                                                    {{ $relation->parent->last_name ?? '' }} </td>
                                                                <td>{{ $relation->relationship->title ?? '' }}</td>
                                                                <td>{{ __($relation->parent->sex ?? '') }}</td>

                                                                <td>
                                                                    {{ $relation->parent->birthday ? \Carbon\Carbon::parse($relation->parent->birthday)->format('d/m/Y') : '' }}
                                                                </td>

                                                                <td>{{ $relation->parent->phone ?? '' }}</td>
                                                                <td>{{ $relation->parent->email ?? '' }}</td>
                                                                <td>{{ $relation->parent->address ?? '' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <p class="text-muted">@lang('Không có người thân nào được liên kết.')</p>
                                            @endif
                                        </div>

                                        <!-- TAB 3: Dịch vụ đăng ký -->
                                        <div class="tab-pane" id="tab_3">
                                            <div class="box-body ">
                                                <h4 class="mt-4 ">Danh sách dịch vụ đăng ký</h4>
                                                <br>
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('STT')</th>
                                                            <th>@lang('Tên dịch vụ')</th>
                                                            <th>@lang('Nhóm dịch vụ')</th>
                                                            <th>@lang('Hệ đào tạo')</th>
                                                            <th>@lang('Độ tuổi')</th>
                                                            <th>@lang('Tính chất dịch vụ')</th>
                                                            <th>@lang('Loại dịch vụ')</th>
                                                            <th>@lang('Biểu phí')</th>
                                                            <th>@lang('Chu kỳ thu')</th>
                                                            <th>@lang('Ghi chú')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $activeServices = $detail->studentServices->where(
                                                                'status',
                                                                'active',
                                                            );
                                                        @endphp
                                                        @if ($activeServices->count())
                                                            @foreach ($activeServices as $row)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1 }}</td>
                                                                    <td>{{ $row->services->name ?? '' }}</td>
                                                                    <td>{{ $row->services->service_category->name ?? '' }}
                                                                    </td>
                                                                    <td>{{ $row->services->education_program->name ?? '' }}
                                                                    </td>
                                                                    <td>{{ $row->services->education_age->name ?? '' }}
                                                                    </td>
                                                                    <td>{{ $row->services->is_attendance == 0 ? 'Không theo điểm danh' : 'Tính theo điểm danh' }}
                                                                    </td>
                                                                    <td>{{ __($row->services->service_type ?? '') }}</td>

                                                                    <td>
                                                                        @if (isset($row->services->serviceDetail) && $row->services->serviceDetail->count() > 0)
                                                                            @foreach ($row->services->serviceDetail as $detail_service)
                                                                                <ul>
                                                                                    <li>Số tiền:
                                                                                        {{ isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : '' }}
                                                                                    </li>
                                                                                    <li>Số lượng:
                                                                                        {{ $detail_service->quantity ?? '' }}
                                                                                    </li>
                                                                                    <li>Từ:
                                                                                        {{ isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '' }}
                                                                                    </li>
                                                                                    <li>Đến:
                                                                                        {{ isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '' }}
                                                                                    </li>
                                                                                </ul>
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{ $row->paymentcycle->name ?? '' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $row->json_params->note ?? '' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>

                                                </table>
                                                <br>
                                                @php
                                                    $cancelledServices = $detail->studentServices->where(
                                                        'status',
                                                        'cancelled',
                                                    );
                                                @endphp
                                                @if ($cancelledServices->count())
                                                    <h4 class="mt-4 ">Danh sách dịch vụ bị huỷ</h4>
                                                    <br>
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>@lang('STT')</th>
                                                                <th>@lang('Tên dịch vụ')</th>
                                                                <th>@lang('Ngày bắt đầu')</th>
                                                                <th>@lang('Ngày kết thúc')</th>
                                                                <th>@lang('Người cập nhật')</th>
                                                                <th>@lang('Trạng thái')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cancelledServices as $index => $row)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1 }}</td>
                                                                    <td>{{ $row->services->name ?? '' }}</td>
                                                                    <td>
                                                                        {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : '' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $row->cancelled_at ? \Carbon\Carbon::parse($row->cancelled_at)->format('d-m-Y') : '' }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $row->adminUpdated->name ?? '' }}
                                                                        ({{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('H:i:s d-m-Y') : '' }})
                                                                    </td>
                                                                    <td><span class="badge badge-danger">Đã huỷ</span></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- TAB 4: Biên lai thu phí -->
                                        <div class="tab-pane active" id="tab_4">
                                            <div class="box-body ">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('STT')</th>
                                                            <th>@lang('Mã biểu phí')</th>
                                                            <th>@lang('Tên biểu phí')</th>
                                                            <th>@lang('Số dư kỳ trước ')</th>
                                                            <th>@lang('Thành tiền')</th>
                                                            <th>@lang('Tổng giảm trừ')</th>
                                                            <th>@lang('Tổng tiền truy thu/hoàn trả')</th>
                                                            <th>@lang('Tổng tiền')</th>
                                                            <th>@lang('Đã thanh toán')</th>
                                                            <th>@lang('Còn lại')</th>
                                                            <th>@lang('Trạng thái')</th>
                                                            <th>@lang('Ghi chú')</th>
                                                            <th>@lang('Ngày tạo phí')</th>
                                                            <th>@lang('Chức năng')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            function format_currency($price)
                                                            {
                                                                return isset($price) && is_numeric($price)
                                                                    ? number_format($price, 0, ',', '.') . ' đ'
                                                                    : '';
                                                            }
                                                        @endphp
                                                        @if ($detail->studentReceipt->count())
                                                            @foreach ($detail->studentReceipt as $row)
                                                                <tr>
                                                                    <td>{{ $loop->index + 1 }}</td>
                                                                    <td>{{ $row->receipt_code ?? '' }}</td>
                                                                    <td>{{ $row->receipt_name ?? '' }}</td>
                                                                    
                                                                    <td>{{ format_currency($row->prev_balance) }}</td>
                                                                    <td>{{ format_currency($row->total_amount) }}</td>
                                                                    <td>{{ format_currency($row->total_discount) }}</td>
                                                                    <td>{{ format_currency($row->total_adjustment) }}</td>
                                                                    <td>{{ format_currency($row->total_final) }}</td>
                                                                    <td>{{ format_currency($row->total_paid) }}</td>
                                                                    <td>{{ format_currency($row->total_due) }}</td>
                                                                    <td>{{ __($row->status) }}</td>
                                                                    <td>{{ $row->note ?? '' }}</td>
                                                                    <td>{{ isset($row->receipt_date) ? \Illuminate\Support\Carbon::parse($row->receipt_date)->format('d-m-Y') : '' }}
                                                                    </td>
                                                                    <td>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-primary btn_show_detail"
                                                                            data-toggle="tooltip"
                                                                            data-id="{{ $row->id }}"
                                                                            data-url="{{ route('receipt.view', $row->id) }}"
                                                                            title="@lang('Show')"
                                                                            data-original-title="@lang('Show')">
                                                                            <i class="fa fa-eye"></i> Xem
                                                                        </button>
                                                                        
                                                                        <a href="{{ route('receipt.show', $row->id) }}">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-warning  mr-10"
                                                                                title="@lang('Cập nhật')"
                                                                                data-original-title="@lang('Cập nhật')">
                                                                                <i class="fa fa-money"></i> Cập nhật
                                                                            </button>
                                                                        </a>
                                                                    
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        {{-- CT khuyến mãi được áp dụng --}}
                                        <div class="tab-pane " id="tab_5">
                                            <div class="box-body ">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('Mã CT Kh.Mãi')</th>
                                                            <th>@lang('Tên CT Kh.Mãi')</th>
                                                            <th>@lang('Mô tả')</th>
                                                            <th>@lang('Loại')</th>
                                                            <th>@lang('Ngày bắt đầu được hưởng Kh.Mãi')</th>
                                                            <th>@lang('Ngày kết thúc được hưởng Kh.Mãi')</th>
                                                            <th>@lang('Chi tiết Kh.Mãi')</th>
                                                            <th>@lang('Trạng thái')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($detail->studentPromotions as $row)
                                                            <tr>
                                                                <td>{{ $row->promotion->promotion_code ?? '' }}</td>
                                                                <td>{{ $row->promotion->promotion_name ?? '' }}</td>
                                                                <td>{{ $row->promotion->description ?? '' }}</td>
                                                                <td>{{ __($row->promotion->promotion_type) }}</td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($row->time_start)->format('Y-m-d') ?? '' }}

                                                                </td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($row->time_end)->format('Y-m-d') ?? '' }}
                                                                </td>
                                                                <td>
                                                                    @foreach ($row->promotion->json_params->services as $val)
                                                                        @php

                                                                            $service_detail = $row->promotion
                                                                                ->getServices()
                                                                                ->find($val->service_id);
                                                                        @endphp
                                                                        <ul>
                                                                            <li>Dịch vụ:
                                                                                {{ $service_detail->name ?? '' }}
                                                                            </li>
                                                                            <li>Giá trị áp dụng:
                                                                                {{ number_format($val->value, 0, ',', '.') }}
                                                                            </li>
                                                                            <li>Số lần áp dụng:
                                                                                {{ $val->apply_count ?? '' }}
                                                                            </li>
                                                                        </ul>
                                                                    @endforeach
                                                                </td>
                                                                <td>
                                                                    {{ __($row->status) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer ">
                <a href="{{ route(Request::segment(2) . '.index') }}">
                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                </a>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_show_deduction" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-full" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12">@lang('Thông tin hóa đơn')</h3>
                    </h3>
                </div>
                <div class="modal-body show_detail_deduction">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-remove"></i> @lang('Close')
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('.btn_show_detail').click(function(e) {
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    if (response) {
                        $('.show_detail_deduction').html(response.data.view);
                        $('#modal_show_deduction').modal('show');
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
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        });
    </script>
@endsection
