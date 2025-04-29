@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .align-items-end{
            display: flex;
            justify-items: end
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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

        <form action="{{ route(Request::segment(2) . '.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Edit form')</h3>
                        </div>
        
                        <div class="box-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="d-flex-wap">
                                        <!-- Tên dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Tên dịch vụ') <small class="text-danger">*</small></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                                            </div>
                                        </div>
        
                                        <!-- Khu vực -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Khu vực') <small class="text-danger">*</small></label>
                                                <select name="area_id" class="form-control select2" required>
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_area as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == old('area_id', $service->area_id) ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
        
                                        <!-- Nhóm dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Nhóm dịch vụ') <small class="text-danger">*</small></label>
                                                <select name="service_category_id" class="form-control select2" required>
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_service_category as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == old('service_category_id', $service->service_category_id) ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Hệ đào tạo') </label>
                                                <select name="education_program_id" class="form-control select2" style="width: 100%;" >
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_education_program as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id == old('education_program_id', $service->education_program_id) ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Độ tuổi -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Độ tuổi') </label>
                                                <select name="education_age_id" class="form-control select2" style="width: 100%;" >
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_education_age as $item)
                                                        <option value="{{ $item->id }}" {{ $item->id == old('education_age_id', $service->education_age_id) ? 'selected' : '' }}>{{ $item->name ?? "" }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Tính chất dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Tính chất dịch vụ')</label>
                                                <select name="is_attendance" class="form-control select2" style="width: 100%;">
                                                    @foreach ($list_is_attendance as $key => $item)
                                                        <option value="{{ $key }}" {{ $key == old('is_attendance', $service->is_attendance) ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Mặc định -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Dịch vụ mặc định cho lớp')</label>
                                                <select name="is_default" class="form-control select2" style="width: 100%;">
                                                    @foreach ($list_is_default as $key => $item)
                                                        <option value="{{ $key }}" {{ $key == old('is_default', $service->is_default) ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Loại dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Loại dịch vụ')</label>
                                                <select name="service_type" class="form-control select2" style="width: 100%;">
                                                    @foreach ($list_service_type as $key => $item)
                                                        <option value="{{ $key }}" {{ $key == old('service_type', $service->service_type) ? 'selected' : '' }}>{{ __($item) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Thứ tự -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Thứ tự')</label>
                                                <input type="number" name="iorder" class="form-control" placeholder="@lang('Nhập thứ tự')" value="{{ $service->iorder ?? "" }}">
                                            </div>
                                        </div>
                    
                                        <!-- Trạng thái -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Status')</label>
                                                <select name="status" class="form-control select2" style="width: 100%;">
                                                    @foreach ($list_status as $key => $item)
                                                        <option value="{{ $key }}">@lang($item)</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
        
                                        <!-- Chi tiết dịch vụ (service_detail) -->
                                        <hr>
                                        <div class="box-header col-md-12">
                                            <h3 class="box-title">@lang('Giá tiền lũy tiến và thời điểm áp dụng:')</h3>
                                        </div>
                                        <div class="col-md-12 service-detail-wrapper" style="padding-left: 0px">
                                            @php
                                                $details = old('service_detail', $service->serviceDetail ?? []);
                                            @endphp
                                        
                                            @foreach ($details as $key => $detail)
                                            <div class="col-md-12" style="padding-left: 0px">
                                                <div class="service-detail" data-key="{{ $key }}">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>@lang('Số tiền') <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[{{ $key }}][price]" class="form-control" placeholder="@lang('Nhập số tiền')"
                                                                    value="{{ old('service_detail.' . $key . '.price', $detail['price'] ?? $detail->price ?? '') }}">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>@lang('Số lượng') <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[{{ $key }}][quantity]" class="form-control" placeholder="@lang('Nhập số lượng')"
                                                                    value="{{ old('service_detail.' . $key . '.quantity', $detail['quantity'] ?? $detail->quantity ?? '') }}">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>@lang('Từ ngày') <small class="text-danger">*</small></label>
                                                            <input required type="date" name="service_detail[{{ $key }}][start_at]" class="form-control"
                                                                    value="{{ old('service_detail.' . $key . '.start_at', isset($detail['start_at']) ? \Illuminate\Support\Carbon::parse($detail['start_at'])->format('Y-m-d') : (isset($detail->start_at) ? \Illuminate\Support\Carbon::parse($detail->start_at)->format('Y-m-d') : '')) }}">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>@lang('Đến ngày')</label>
                                                            <input required type="date" name="service_detail[{{ $key }}][end_at]" class="form-control"
                                                                    value="{{ old('service_detail.' . $key . '.end_at', isset($detail['end_at']) ? \Illuminate\Support\Carbon::parse($detail['end_at'])->format('Y-m-d') : (isset($detail->end_at) ? \Illuminate\Support\Carbon::parse($detail->end_at)->format('Y-m-d') : '')) }}">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <div class="form-group">
                                                            <label>@lang('Chức năng')</label>
                                                            <button type="button" class="btn btn-danger btn-remove-detail btn-sm"><i class="fa fa-trash"></i></button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Nút Thêm dòng -->
                                        <div style="padding-left:15px " class="mt-3">
                                            <button type="button" class="btn btn-primary" id="btn-add-detail">
                                                <i class="fa fa-plus"></i> @lang('Thêm')
                                            </button>
                                        </div>
                                        
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
        
                        <div class="box-footer">
                            <a href="{{ route(Request::segment(2) . '.index') }}">
                                <button type="button" class="btn btn-sm btn-success">
                                    <i class="fa fa-list"></i> @lang('Danh sách')
                                </button>
                            </a>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
    </section>

@endsection

@section('script')

<script>
    $(document).ready(function() {
    
        $('#btn-add-detail').click(function() {
            let indexDetail = Date.now(); // Lấy time hiện tại làm key
    
            let html = `
            <div class="col-md-12" style="padding-left: 0px">
                                                <div class="service-detail" data-key="${indexDetail}">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Số tiền <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[${indexDetail}][price]" class="form-control" placeholder="Nhập số tiền"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Số lượng <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[${indexDetail}][quantity]" class="form-control" placeholder="Nhập số lượng"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Từ ngày <small class="text-danger">*</small></label>
                                                            <input required type="date" name="service_detail[${indexDetail}][start_at]" class="form-control"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Đến ngày</label>
                                                            <input required type="date" name="service_detail[${indexDetail}][end_at]" class="form-control"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <div class="form-group">
                                                            <label>Chức năng</label>
                                                            <button type="button" class="btn btn-danger btn-remove-detail btn-sm"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
    
            $('.service-detail-wrapper').append(html);
        });
    
        // Sự kiện xóa dòng
        $(document).on('click', '.btn-remove-detail', function() {
            $(this).closest('.service-detail').remove();
        });
    
    });
    </script>
    
    
@endsection
