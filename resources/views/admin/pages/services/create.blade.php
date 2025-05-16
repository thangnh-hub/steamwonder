@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>

                        <div class="box-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="d-flex-wap">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Tên dịch vụ') <small class="text-danger">*</small></label>
                                                <input type="text" name="name" class="form-control" placeholder="@lang('Nhập tên dịch vụ')" required>
                                            </div>
                                        </div>

                                        <!-- Khu vực -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Khu vực') <small class="text-danger">*</small></label>
                                                <select name="area_id" class="form-control select2" style="width: 100%;" required>
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_area as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Nhóm dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Nhóm dịch vụ') <small class="text-danger">*</small></label>
                                                <select name="service_category_id" class="form-control select2" style="width: 100%;" required>
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_service_category as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Hệ đào tạo -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Hệ đào tạo') </label>
                                                <select name="education_program_id" class="form-control select2" style="width: 100%;" >
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($list_education_program as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name ?? "" }}</option>
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
                                                        <option value="{{ $item->id }}">{{ $item->name ?? "" }}</option>
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
                                                        <option value="{{ $key }}">{{ $item }}</option>
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
                                                        <option value="{{ $key }}">{{ $item }}</option>
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
                                                        <option value="{{ $key }}">{{ __($item) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Kiểu phí áp dụng')</label>
                                                <select name="service_fee" class="form-control select2" style="width: 100%;">
                                                    <option value="">@lang('Please select')</option>
                                                    @foreach ($service_fees as $key => $item)
                                                        <option value="{{ $key }}">{{ __($item) }}</option>
                                                    @endforeach
                                                </select>
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
                                        <!-- Thứ tự -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Thứ tự')</label>
                                                <input type="number" name="iorder" class="form-control" placeholder="@lang('Nhập thứ tự')">
                                            </div>
                                        </div>
                                    </hr>
                                        <div class="box-header col-md-12">
                                            <h3 class="box-title">@lang('Giá tiền lũy tiến và thời điểm áp dụng:')</h3>
                                        </div>
                                        <div style="padding-left: 0px" class="col-md-12">
                                            <div class="service-detail">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>@lang('Số tiền') <small class="text-danger">*</small></label>
                                                        <input required type="number" name="service_detail[price]" class="form-control" placeholder="@lang('Nhập số tiền')">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>@lang('Số lượng') <small class="text-danger">*</small></label>
                                                        <input required type="number" name="service_detail[quantity]" class="form-control" placeholder="@lang('Nhập số lượng')">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>@lang('Từ ngày') <small class="text-danger">*</small></label>
                                                        <input required type="date" name="service_detail[start_at]" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>@lang('Đến ngày') <small class="text-danger">*</small></label>
                                                        <input required type="date" name="service_detail[end_at]" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
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

    </script>
@endsection
