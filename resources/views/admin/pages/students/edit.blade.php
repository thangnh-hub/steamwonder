@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .flex-inline-group {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

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
        td ul{
            margin-block-start: 0px !important;
            padding-inline-start: 10px !important;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Thêm mới học viên')</a>
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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin học sinh <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Người thân của bé</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>Dịch vụ đã đăng ký</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_4" data-toggle="tab">
                                            <h5>Quản lý TBP</h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_5" data-toggle="tab">
                                            <h5>CT Kh.Mãi được áp dụng</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="box-body">
                                            <div class="d-flex-wap">
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Khu vực')<small class="text-red">*</small></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value="">@lang('Chọn khu vực')</option>
                                                            @foreach ($list_area as $val)
                                                                <option value="{{ $val->id }}"
                                                                    {{ old('area_id', $detail->area_id) == $val->id ? 'selected' : '' }}>
                                                                    {{ $val->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Họ')<small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="first_name"
                                                            value="{{ old('first_name', $detail->first_name) }}" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Tên')<small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="last_name"
                                                            value="{{ old('last_name', $detail->last_name) }}" required>
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Tên thường gọi')</label>
                                                        <input type="text" class="form-control" name="nickname"
                                                            value="{{ old('nickname', $detail->nickname) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Giới tính')</label>
                                                        <select name="sex" class="form-control select2">
                                                            @foreach ($list_sex as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ old('sex', $detail->sex) == $key ? 'selected' : '' }}>
                                                                    {{ __($value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày sinh')</label>
                                                        <input type="date" class="form-control" name="birthday"
                                                            value="{{ old('birthday', $detail->birthday) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày nhập học')</label>
                                                        <input type="date" class="form-control" name="enrolled_at"
                                                            value="{{ old('enrolled_at', $detail->enrolled_at) }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Trạng thái')</label>
                                                        <select name="status" class="form-control select2">
                                                            @foreach ($list_status as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ old('status', $detail->status) == $key ? 'selected' : '' }}>
                                                                    {{ __($value) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Chính sách được hưởng')</label>
                                                        <select name="policies[]" class="form-control select2" multiple>
                                                            @php
                                                                $selectedPolicies = $detail->studentPolicies
                                                                    ->pluck('policy_id')
                                                                    ->toArray();
                                                            @endphp
                                                            @foreach ($list_policies as $policy)
                                                                <option value="{{ $policy->id }}"
                                                                    {{ in_array($policy->id, $detail->studentPolicies->pluck('policy_id')->toArray()) ? 'selected' : '' }}>
                                                                    {{ $policy->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group">
                                                        <label>@lang('Chu kỳ thu dịch vụ')</label>
                                                        <select style="width:100%" name="payment_cycle_id"
                                                            class="form-control select2">
                                                            <option value="">Chọn</option>
                                                            @foreach ($list_payment_cycle as $payment_cycle)
                                                                <option
                                                                    {{ old('payment_cycle_id', $detail->payment_cycle_id) == $payment_cycle->id ? 'selected' : '' }}
                                                                    value="{{ $payment_cycle->id }}">
                                                                    {{ $payment_cycle->name ?? '' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-xs-12">
                                                    <div class="form-group box_img_right">
                                                        <label>@lang('Ảnh đại diện')</label>
                                                        <div id="image-holder">
                                                            <img src="{{ !empty($detail->avatar) ? asset($detail->avatar) : url('themes/admin/img/no_image.jpg') }}"
                                                                style="max-height: 120px;">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder"
                                                                    class="btn btn-primary lfm" data-type="cms-image">
                                                                    <i class="fa fa-picture-o"></i> @lang('Choose')
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control inp_hidden"
                                                                type="hidden" name="avatar"
                                                                placeholder="@lang('Image source')"
                                                                value="{{ old('avatar', $detail->avatar) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- người thân --}}
                                    <div class="tab-pane " id="tab_2">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#addParentModal">
                                                    <i class="fa fa-plus"></i> @lang('Cập nhật người thân')
                                                </button>
                                            </div>

                                            <br>
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('STT')</th>
                                                            <th>@lang('Avatar')</th>
                                                            <th>@lang('Họ và tên')</th>
                                                            <th>@lang('Giới tính')</th>
                                                            <th>@lang('Ngày sinh')</th>
                                                            <th>@lang('Số CMND/CCCD')</th>
                                                            <th>@lang('Số điện thoại')</th>
                                                            <th>@lang('Email')</th>  
                                                            <th>@lang('Địa chỉ')</th>
                                                            <th>@lang('Khu vực')</th>
                                                            <th>@lang('Trạng thái')</th>
                                                            <th>@lang('Quan hệ')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($detail->studentParents->count())
                                                        @foreach ($detail->studentParents as $row)
                                                            <tr class="valign-middle">
                                                                <td>
                                                                    {{ $loop->iteration }}
                                                                </td>
                                                                <td>
                                                                    @if (!empty($row->parent->avatar))
                                                                        <img src="{{ asset($row->parent->avatar) }}" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                    @else
                                                                        <span class="text-muted">No image</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <a target="_blank" href="{{ route('parents.show', $row->parent->id) }}">
                                                                        {{ $row->parent->first_name ?? '' }} {{ $row->parent->last_name ?? '' }}  
                                                                    </a>
                                                                </td>
                                                                <td>@lang($row->parent->sex ?? '')</td>
                                                                <td>{{ $row->parent->birthday ? \Carbon\Carbon::parse($row->parent->birthday)->format('d/m/Y') : '' }}</td>
                                                                <td>{{ $row->parent->identity_card ?? '' }}</td>
                                                                <td>{{ $row->parent->phone ?? '' }}</td>
                                                                <td>{{ $row->parent->email ?? '' }}</td>
                                                                <td>{{ $row->parent->address ?? '' }}</td>
                                                                <td>{{ $row->parent->area->name ?? '' }}</td>
                                                                <td>@lang($row->parent->status ?? '')</td>
                                                                <td>{{ $row->relationship->title ?? '' }}</td>
                                                            </tr>
                                                        @endforeach
                                                        @else
                                                            <tr>
                                                                <td colspan="14" class="text-center">Không có dữ liệu</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>    
                                            </div>
                                        </div>                      
                                    </div>
                                    {{-- Dịch vụ đã đăng ký --}}
                                    <div class="tab-pane " id="tab_3">
                                        <div class="box-body ">
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                    data-target="#addServiceModal">
                                                    <i class="fa fa-plus"></i> @lang('Đăng ký dịch vụ')
                                                </button>     
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#reincarnationModal">
                                                    <i class="fa fa-recycle"></i> @lang('Xử lý tái tục dịch vụ')
                                                </button>     
                                            </div>
                                            <br>
                                            <div class="table-responsive">
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
                                                            <th>@lang('Ngày bắt đầu')</th>
                                                            <th>@lang('Ngày kết thúc')</th>
                                                            <th>@lang('Ghi chú')</th>
                                                            <th>@lang('Chức năng')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $activeServices = $detail->studentServices->where('status', 'active');
                                                        @endphp
                                                        @if($activeServices->count())
                                                        @foreach ($activeServices as $row)
                                                        <tr>
                                                            <td>{{ $loop->index + 1 }}</td>
                                                            <td>{{ $row->services->name ?? "" }}</td>
                                                            <td>{{ $row->services->service_category->name ?? "" }}</td>
                                                            <td>{{ $row->services->education_program->name ?? "" }}</td>
                                                            <td>{{ $row->services->education_age->name ?? "" }}</td>
                                                            <td>{{ $row->services->is_attendance== 0 ? "Không theo điểm danh" : "Tính theo điểm danh"}}</td>
                                                            <td>{{ __($row->services->service_type??"") }}</td>
                                                            
                                                            <td>
                                                                @if(isset($row->services->serviceDetail) && $row->services->serviceDetail->count() > 0)
                                                                @foreach ($row->services->serviceDetail as $detail_service)
                                                                <ul>
                                                                    <li>Số tiền: {{ isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : '' }}</li>
                                                                    <li>Số lượng: {{ $detail_service->quantity ?? '' }}</li>
                                                                    <li>Từ: {{ (isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '') }}</li>
                                                                    <li>Đến: {{ (isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '') }}</li>
                                                                </ul>
                                                                @endforeach
                        
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $row->paymentcycle->name ?? "" }}
                                                            </td>
    
                                                            <td>
                                                                {{ ($row->created_at)
                                                                    ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') 
                                                                    : '' 
                                                                }}
                                                            </td>
                                                            <td>
                                                                {{ ($row->cancelled_at) 
                                                                    ? \Carbon\Carbon::parse($row->cancelled_at)->format('d-m-Y') 
                                                                    : '' 
                                                                }}
                                                            </td>
    
                                                            <td>
                                                                {{ $row->json_params->note ?? "" }}
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-danger delete_student_service" data-id="{{ $row->id }}">
                                                                    <i class="fa fa-close"></i> Hủy
                                                                </button>
                                                                <button data-id="{{ $row->id }}" type="button" class="btn btn-primary btn-sm update_student_service" data-toggle="modal" data-target="#editServiceModal">
                                                                    <i class="fa fa-pencil"></i> @lang('Cập nhật')
                                                                </button> 
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
                                            </div>
                                            <br>
                                            @php
                                                $cancelledServices = $detail->studentServices->where(
                                                    'status',
                                                    'cancelled',
                                                );
                                            @endphp
                                            @if($cancelledServices->count())
                                            <h4 class="mt-4 ">Danh sách dịch vụ bị huỷ</h4>
                                            <br>
                                            <div class="table-responsive">
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
                                                                    {{ ($row->created_at)
                                                                        ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') 
                                                                        : '' 
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{ ($row->cancelled_at) 
                                                                        ? \Carbon\Carbon::parse($row->cancelled_at)->format('d-m-Y') 
                                                                        : '' 
                                                                    }}
                                                                </td>
                                                             
                                                                <td>
                                                                    {{ $row->adminUpdated->name ?? "" }} ({{ $row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('H:i:s d-m-Y') : '' }})   
                                                                </td>
                                                                <td><span class="badge badge-danger">Đã huỷ</span></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- TAB 4: Biên lai thu phí -->
                                    <div class="tab-pane" id="tab_4">
                                        <div class="box-body ">
                                            {{-- <form id="calculate-receipt-form">
                                                @csrf
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Ngày bắt đầu chu kỳ thanh toán') <small
                                                                class="text-danger">*</small></label>
                                                        <input class="form-control" type="date" id="enrolled_at"
                                                            value="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>@lang('Chu kỳ thu dịch vụ') <small
                                                                class="text-danger">*</small></label>
                                                        <select style="width:100%" id="selectpayment_cycle_id"
                                                            class="form-control select2">
                                                            <option value="">Chọn</option>
                                                            @foreach ($list_payment_cycle as $payment_cycle)
                                                                <option
                                                                    {{ old('payment_cycle_id', $detail->payment_cycle_id) == $payment_cycle->id ? 'selected' : '' }}
                                                                    value="{{ $payment_cycle->id }}">
                                                                    {{ $payment_cycle->name ?? '' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="d-block">@lang('Tính tháng hiện tại ở chu kỳ thu?')</label>
                                                        <div id="receipt-options" class="flex-inline-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="includeCurrentMonth" id="includeCurrentMonthYes"
                                                                    value="1">
                                                                <label class="form-check-label mb-0"
                                                                    for="includeCurrentMonthYes">Có</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="includeCurrentMonth" id="includeCurrentMonthNo"
                                                                    value="0" checked>
                                                                <label class="form-check-label mb-0"
                                                                    for="includeCurrentMonthNo">Không</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-success btn-sm mb-15"
                                                        id="btnCalculateReceipt" data-id="{{ $detail->id }}">
                                                        <i class="fa fa-money"></i> @lang('Tính toán thu phí')
                                                    </button>
                                                </div>

                                            </form> --}}
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('STT')</th>
                                                        <th>@lang('Mã biểu phí')</th>
                                                        <th>@lang('Tên biểu phí')</th>
                                                        {{-- <th>@lang('Chu kỳ')</th>
                                                        <th>@lang('Biểu phí trước')</th> --}}
                                                        <th>@lang('Số dư kỳ trước ')</th>
                                                        <th>@lang('Thành tiền')</th>
                                                        <th>@lang('Tổng giảm trừ')</th>
                                                        <th>@lang('Tổng tiền truy thu/hoàn trả')</th>
                                                        <th>@lang('Tổng tiền')</th>
                                                        <th>@lang('Đã thanh toán')</th>
                                                        <th>@lang('Còn lại')</th>
                                                        <th>@lang('Trạng thái')</th>
                                                        <th>@lang('Ghi chú')</th>
                                                        {{-- <th>@lang('Người lập biên lai')</th> --}}
                                                        <th>@lang('Ngày tạo phí')</th>
                                                        <th>@lang('Chức năng')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        function format_currency($price)
                                                        {
                                                            return isset($price) && is_numeric($price)
                                                                ? number_format($price, 0, ',', '.') 
                                                                : '';
                                                        }
                                                    @endphp
                                                    @if ($detail->studentReceipt->count())
                                                        @foreach ($detail->studentReceipt as $row)
                                                            <tr>
                                                                <td>{{ $loop->index + 1 }} </td>
                                                                <td>{{ $row->receipt_code ?? '' }}</td>
                                                                <td>{{ $row->receipt_name ?? '' }}</td>
                                                                {{-- <td>{{ $row->payment_cycle->name ?? '' }}</td>
                                                                <td>{{ $row->prev_receipt->receipt_name ?? '' }}</td> --}}
                                                                <td>{{ format_currency($row->prev_balance) }}</td>
                                                                <td>{{ format_currency($row->total_amount) }}</td>
                                                                <td>{{ format_currency($row->total_discount) }}</td>
                                                                <td>{{ format_currency($row->total_adjustment) }}</td>
                                                                <td>{{ format_currency($row->total_final) }}</td>
                                                                <td>{{ format_currency($row->total_paid) }}</td>
                                                                <td>{{ format_currency($row->total_due) }}</td>
                                                                <td>{{ __($row->status) }}</td>
                                                                <td>{{ $row->note ?? '' }}</td>
                                                                {{-- <td>{{ $row->cashier->name ?? '' }}</td> --}}
                                                                <td>{{ isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : '' }}
                                                                </td>
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary btn_show_detail mr-10"
                                                                        data-toggle="tooltip"
                                                                        data-id="{{ $row->id }}"
                                                                        data-url="{{ route('receipt.view', $row->id) }}"
                                                                        title="@lang('Show')"
                                                                        data-original-title="@lang('Show')">
                                                                        <i class="fa fa-eye"></i> Chi tiết
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
                                            <p>Danh sách các CT Kh.Mãi</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Chọn')</th>
                                                        <th>@lang('Mã CT Kh.Mãi')</th>
                                                        <th>@lang('Tên CT Kh.Mãi')</th>
                                                        <th>@lang('Mô tả')</th>
                                                        <th>@lang('Loại')</th>
                                                        <th>@lang('Thời gian bắt đầu')</th>
                                                        <th>@lang('Thời gian kết thúc')</th>
                                                        <th>@lang('Chi tiết Kh.Mãi')</th>
                                                        <th>@lang('Trạng thái')</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($list_promotion as $row)
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="radio" class="radio_promotion" {{in_array($row->id,$promotion_active->pluck('promotion_id')->toArray())?'disabled':''}}
                                                                    name="radio_promotion" value="{{ $row->id}}">
                                                            </td>
                                                            <td class="code">{{ $row->promotion_code ?? '' }}</td>
                                                            <td class="name">{{ $row->promotion_name ?? '' }}</td>
                                                            <td class="des">{{ $row->description ?? '' }}</td>
                                                            <td class="type">{{ __($row->promotion_type) }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($row->time_start)->format('d/m/Y') ?? '' }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($row->time_end)->format('d/m/Y') ?? '' }}
                                                            </td>
                                                            <td class="service">
                                                                @foreach ($row->json_params->services as $val)
                                                                    @php
                                                                        $service_detail = $row
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
                                                            <td class="status">
                                                                {{ __($row->status) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <br>
                                            <p>CT Kh.Mãi được áp dụng</p>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>

                                                        <th>@lang('Chọn')</th>
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
                                                    @foreach ($promotion_active as $row)
                                                        <tr>
                                                            <td>

                                                            </td>
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
                                                                {{__($row->status)}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="promotion_new"></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-sm btn-success">@lang('Danh sách')</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <!-- Modal Người thân-->
    <div class="modal fade" id="addParentModal" tabindex="-1" role="dialog" aria-labelledby="addParentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
        <form action="{{ route('student.addParent', $detail->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addParentModalLabel">@lang('Chọn người thân')</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search-parent" placeholder="@lang('Tìm theo tên phụ huynh...')">
                    </div>
                    <div class="table-wrapper table-responsive">
                        <table class="table table-hover table-bordered" id="parent-table">
                            <thead>
                                <tr>
                                    <th>Chọn</th>
                                    <th>@lang('Họ và tên')</th>
                                    <th>@lang('Giới tính')</th>
                                    <th>@lang('Số điện thoại')</th>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Chọn mối quan hệ')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allParents as $parent)
                                @php
                                    $isChecked = in_array($parent->id, $studentParentIds);
                                    $existingRelation = $detail->studentParents->firstWhere('parent_id', $parent->id);
                                @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="parents[{{ $parent->id }}][id]" value="{{ $parent->id }}" {{ $isChecked ? 'checked' : '' }}>
                                        </td>
                                        <td class="parent-name">{{ $parent->first_name }} {{ $parent->last_name }}</td>
                                        <td>@lang($parent->sex)</td>
                                        <td>{{ $parent->phone }}</td>
                                        <td>{{ $parent->email }}</td>
                                        <td>
                                            <select style="width:100%" name="parents[{{ $parent->id }}][relationship_id]" class="form-control select2">
                                                @foreach($list_relationship as $relation)
                                                    <option {{ $existingRelation && $existingRelation->relationship_id == $relation->id ? 'selected' : '' }} value="{{ $relation->id }}">{{ $relation->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>    
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('Lưu người thân đã chọn')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Đóng')</button>
                </div>
            </div>
        </form>
        </div>
    </div>

    <!-- Modal dịch vụ-->
    <div data-backdrop="static" class="modal fade" id="addServiceModal" tabindex="-1" role="dialog"
        aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full" role="document">
        <form id="submitstudentaddService" action="{{ route('student.addService', $detail->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">@lang('Chọn dịch vụ')</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search-service" placeholder="@lang('Tìm theo tên dịch vụ...')">
                    </div>
                    <div class="table-wrapper table-responsive" >
                        <table class="table table-hover table-bordered" id="service-table">
                            <thead>
                                <tr>
                                    <th>@lang('Tên dịch vụ')</th>
                                    <th>@lang('Nhóm dịch vụ')</th>
                                    <th>@lang('Tính chất dịch vụ')</th>
                                    <th>@lang('Loại dịch vụ')</th>
                                    <th>@lang('Biểu phí')</th>
                                    <th>@lang('Chu kỳ thu')</th>
                                    <th>@lang('Ghi chú')</th>
                                    <th>Chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unregisteredServices as $service)
                                    <tr>
                                        <td class="service-name">{{ $service->name ?? "" }}</td>
                                        <td>{{ $service->service_category->name ?? "" }}</td>
                                        <td>{{ $service->is_attendance== 0 ? "Không theo điểm danh" : "Tính theo điểm danh"}}</td>
                                        <td>{{ __($service->service_type??"") }}</td>
                                        
                                        <td>
                                            @if(isset($service->serviceDetail) && $service->serviceDetail->count() > 0)
                                                @foreach ($service->serviceDetail as $detail_service)
                                                <ul>
                                                    <li>Số tiền: {{ isset($detail_service->price) && is_numeric($detail_service->price) ? number_format($detail_service->price, 0, ',', '.') . ' đ' : '' }}</li>
                                                    <li>Số lượng: {{ $detail_service->quantity ?? '' }}</li>
                                                    <li>Từ: {{ (isset($detail_service->start_at) ? \Illuminate\Support\Carbon::parse($detail_service->start_at)->format('d-m-Y') : '') }}</li>
                                                    <li>Đến: {{ (isset($detail_service->end_at) ? \Illuminate\Support\Carbon::parse($detail_service->end_at)->format('d-m-Y') : '') }}</li>
                                                </ul>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <select style="width:100%" name="services[{{ $service->id }}][payment_cycle_id]" class="form-control select2">
                                                @foreach ($list_payment_cycle as $payment_cycle)
                                                    <option  value="{{ $payment_cycle->id }}">{{ $payment_cycle->name ?? "" }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="services[{{ $service->id }}][note]" value="" placeholder="@lang('Ghi chú')">
                                        </td>
                                        <td>
                                            <input type="checkbox" name="services[{{ $service->id }}][id]" value="{{ $service->id }}" >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('Lưu dịch vụ đã chọn')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Đóng')</button>
                </div>
            </div>
        </form>
        </div>
    </div>
    <!-- Modal tái tục-->
    <div data-backdrop="static" class="modal fade" id="reincarnationModal" tabindex="-1" role="dialog" aria-labelledby="reincarnationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
        <form id="formRenew" action="{{ route('receipt.calculStudent.renew') }}"  method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reincarnationModalLabel">@lang('Tái tục dịch vụ cho học sinh')</h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>@lang('Ngày bắt đầu chu kỳ thanh toán') <small class="text-danger">*</small></label>
                            <input class="form-control" type="date" id="enrolled_at" value="" required>
                            <input type="hidden" name="student_id" value="{{ $detail->id }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">@lang('Tính toán tái tục dịch vụ')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Đóng')</button>
                </div>
            </div>
        </form>
        </div>
    </div>

    {{-- modal chỉnh sửa dịch vụ/ --}}
    <div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="updateStudentServiceForm" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editServiceModalLabel">@lang('Cập nhật dịch vụ')</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ghi chú</label>
                                    <select name="payment_cycle_service" style="width:100%" class="form-control select2">
                                        @foreach ($list_payment_cycle as $payment_cycle)
                                            <option  value="{{ $payment_cycle->id }}">{{ $payment_cycle->name ?? "" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Ghi chú</label>
                                    <input type="text" class="form-control" name="note_service" value=""
                                        placeholder="@lang('Ghi chú')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnUpdateService" type="button" class="btn btn-primary">@lang('Cập nhật')</button>
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('Đóng')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- modal chi tiết biên lai --}}
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
        $(document).on('change', '.radio_promotion', function() {
            var id = $(this).val();
            var code = $(this).parents('tr').find('.code').html();
            var name = $(this).parents('tr').find('.name').html();
            var des = $(this).parents('tr').find('.des').html();
            var type = $(this).parents('tr').find('.type').html();
            var service = $(this).parents('tr').find('.service').html();
            $('.promotion_new').html(`
            <td>
                <button type="button"
                    class="btn btn-sm btn-danger delete_student_promotion"
                    onclick="$(this).closest('tr').html(''); $('.radio_promotion').prop('checked', false);">
                    <i class="fa fa-close"></i> Hủy
                </button>
                <input type="hidden" name="promotion_student[promotion_id]" value="${id}">
            </td>
            <td>${code}</td>
            <td>${name}</td>
            <td>${des}</td>
            <td>${type}</td>
            <td>
                <input required type="date" name="promotion_student[time_start]"
                    class="form-control"
                    value="">

            </td>
            <td>
                <input required type="date" name="promotion_student[time_end]"
                    class="form-control"
                    value="">
            </td>
            <td>${service}</td>
            <td>
                <select name="promotion_student[status]" class="form-control select2 w-100">
                    @foreach ($status as $key => $val)
                        <option value="{{ $key }}">
                            {{ __($val) }}</option>
                    @endforeach
                </select>
            </td>
            `)
            $('.select2').select2();
        })


        $('#search-parent').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#parent-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.parent-name').text().toLowerCase().indexOf(value) > -1);
            });
        });
        $('#search-service').on('keyup', function() {
            let value = $(this).val().toLowerCase();
            $('#service-table tbody tr').filter(function() {
                $(this).toggle($(this).find('.service-name').text().toLowerCase().indexOf(value) > -1);
            });
        });


        $('.delete_student_service').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn xóa dịch vụ này khỏi học sinh ?')) {
                let _id = $(this).attr('data-id');
                let url = "{{ route('delete_student_service') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        if (response.message === 'success') {
                            localStorage.setItem('activeTab', '#tab_3');
                            location.reload();
                        } else {
                            alert("Bạn không có quyền thao tác dữ liệu");
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
        $('.update_student_service').click(function(e) {
            e.preventDefault();
            let _id = $(this).data('id');
            let url = "{{ route('get_student_service_info') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                },
                success: function(response) {
                    if (response.success) {
                        $('#editServiceModal input[name="note"]').val(response.data.note);
                        $('#editServiceModal select').val(response.data.payment_cycle_id).trigger('change');
                        // Mở modal
                        $('#editServiceModal').modal('show');
                        $('#btnUpdateService').attr('data-id', _id);
                    } else {
                        alert("Không tìm thấy dữ liệu dịch vụ.");
                    }
                },
                error: function(response) {
                    alert("Đã có lỗi xảy ra khi tải dữ liệu.");
                }
            });
        });

        $('#btnUpdateService').click(function() {
            let cycleValue = $('select[name="payment_cycle_service"]').val();
            let noteValue  = $('input[name="note_service"]').val();
            let currentStudentServiceId = $(this).data('id'); // Lấy ID dịch vụ hiện tại từ nút cập nhật
            $.ajax({
                type: "POST",
                url: "{{ route('student.updateService.ajax') }}",
                data: {
                    id: currentStudentServiceId,
                    note: noteValue,
                    payment_cycle_id: cycleValue,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.message === 'success') {
                        $('#editServiceModal').modal('hide');
                        localStorage.setItem('activeTab', '#tab_3');
                        location.reload();
                    } else {
                        alert("Không có quyền thao tác.");
                    }
                },
                error: function() {
                    alert("Lỗi cập nhật.");
                }
            });
        });

        $(document).ready(function() {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                // Bỏ class active hiện tại
                $('.nav-tabs li, .tab-content .tab-pane').removeClass('active');

                // Thêm active cho tab tương ứng
                $('.nav-tabs li a[href="' + activeTab + '"]').parent().addClass('active');
                $(activeTab).addClass('active');

                // Xoá dữ liệu đã lưu để tránh kích hoạt lại lần sau
                localStorage.removeItem('activeTab');
            }
        });

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


        $('#btnCalculateReceipt').click(function() {
            let studentId = $(this).data('id');
            let includeCurrentMonth = $('#receipt-options input[type="radio"]:checked').val();
            let enrolledAt = $('#enrolled_at').val();
            let paymentCycleId = $('#selectpayment_cycle_id').val();
            if (paymentCycleId == "") {
                alert("Vui lòng chọn chu kỳ thu dịch vụ!");
                return;
            }
            if (enrolledAt == "") {
                alert("Vui lòng chọn ngày bắt đầu chu kỳ thanh toán!");
                return;
            }
            $.ajax({
                type: "POST",
                url: "{{ route('receipt.calculStudent') }}",
                data: {
                    student_id: studentId,
                    include_current_month: includeCurrentMonth,
                    enrolled_at: enrolledAt,
                    payment_cycle_id: paymentCycleId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.message === 'success') {
                        alert("Tạo hóa đơn thành công!");
                        localStorage.setItem('activeTab', '#tab_4');
                        location.reload();
                    } else {
                        alert("Không thể tạo hóa đơn.");
                    }
                },
                error: function() {
                    alert("Đã xảy ra lỗi khi tạo hóa đơn.");
                }
            });
        });

        $('#submitstudentaddService').on('submit', function () {
            localStorage.setItem('activeTab', '#tab_3');
        });
        
        $('#formRenew').on('submit', function () {
            localStorage.setItem('activeTab', '#tab_4');
        });
    </script>
@endsection
