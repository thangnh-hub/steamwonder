@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .item_service {
            margin-bottom: 10px;
            align-items: center;
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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_promotion">
            @csrf
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label>@lang('Mã CT Kh.Mãi') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_code"
                                    placeholder="@lang('Mã CT Kh.Mãi')" value="{{ old('promotion_code') }}" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label>@lang('Tên CT Kh.Mãi') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_name"
                                    placeholder="@lang('Tên CT Kh.Mãi')" value="{{ old('promotion_name') }}" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $val)
                                        <option value="{{ $val->id }}"
                                            {{ old('area_id') && old('area_id') == $val->id ? 'selected' : '' }}>
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label>@lang('Type') <small class="text-red">*</small></label>
                                <select required name="promotion_type" class="form-control select2 select_type">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ old('promotion_type') && old('promotion_type') == $val ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>@lang('Thời gian bắt đầu') <small class="text-red">*</small></label>
                                <input required type="date" name="time_start" class="form-control"
                                    value="{{ old('time_start') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>@lang('Thời gian kết thúc') <small class="text-red">*</small></label>
                                <input required type="date" name="time_end" class="form-control"
                                    value="{{ old('time_end') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>@lang('Status') </label>
                                <select required name="status" class="form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ old('status') && old('status') == $val ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Mô tả') </label>
                                <textarea class="form-control" name="description" rows="5"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <h4 class="box-title">Danh sách dịch vụ</h4>
                            <ul class="mt-15">
                                @foreach ($service as $item_service)
                                    <li class="d-flex-wap item_service">
                                        <input class="item_check mr-10 checkService" type="checkbox"
                                            name="json_params[services][{{ $item_service->id }}][service_id]"
                                            value="{{ $item_service->id }}">
                                        <input placeholder="" class="item_value form-control mr-10 check_disable " disabled
                                            style="width:250px;"
                                            name="json_params[services][{{ $item_service->id }}][value]" type="number"
                                            value="">
                                        <input placeholder="Số lần áp dụng theo dịch vụ"
                                            class="item_apply form-control mr-10 check_disable " disabled
                                            style="width:250px;"
                                            name="json_params[services][{{ $item_service->id }}][apply_count]"
                                            type="number" value="">
                                        <span class="fw-bold ml-10"
                                            style="min-width:200px;">{{ $item_service->name ?? '' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                        @lang('Save')</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#form_promotion').on('submit', function(e) {
                // Kiểm tra xem có ít nhất một checkbox được chọn hay không
                if ($('.checkService:checked').length === 0) {
                    e.preventDefault(); // Ngăn form submit
                    alert('Vui lòng chọn ít nhất một dịch vụ!');
                }
            });
            $('.select_type').on('change', function() {
                var _type = $(this).val();
                switch (_type) {
                    case 'percent':
                        $('.item_value').attr('placeholder', 'Nhập % khuyến mãi');
                        $('.item_apply').attr('readonly', false).val('');
                        break;
                    case 'fixed_amout':
                        $('.item_value').attr('placeholder', 'Nhập số tiền khuyến mãi')
                        $('.item_apply').attr('readonly', true).val(1);
                        break;
                    case 'add_month':
                        $('.item_value').attr('placeholder', 'Nhập số tháng khuyến mãi')
                        $('.item_apply').attr('readonly', true).val(1);
                        break;

                    default:
                        break;
                }
            })
            $('.checkService').change(function() {
                var check = $(this).is(':checked');
                $(this).parents('.item_service').find('.check_disable').attr('disabled', !check)
            })

        });
    </script>
@endsection
