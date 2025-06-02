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

        .d-flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between
        }

        ul {
            padding-inline-start: 5px;
        }

        .box_cycle {
            overflow-x: auto
        }

        .item_cycle {
            margin-right: 5px;
            padding: 5px;
            border: 1px solid #a9a9a9
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
        <div class="box_alert">
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
        </div>
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST"
            id="form_promotion">
            @csrf
            @method('PUT')
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label>@lang('Mã CT Kh.Mãi') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_code"
                                    placeholder="@lang('Mã CT Kh.Mãi')"
                                    value="{{ $detail->promotion_code ?? old('promotion_code') }}" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label>@lang('Tên CT Kh.Mãi') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_name"
                                    placeholder="@lang('Tên CT Kh.Mãi')"
                                    value="{{ $detail->promotion_name ?? old('promotion_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $val)
                                        <option value="{{ $val->id }}"
                                            {{ $detail->area_id && $detail->area_id == $val->id ? 'selected' : '' }}>
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
                                            {{ $detail->promotion_type && $detail->promotion_type == $val ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>@lang('Thời gian bắt đầu') <small class="text-red">*</small></label>
                                <input required type="date" name="time_start" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($detail->time_start)->format('Y-m-d') ?? old('time_start') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>@lang('Thời gian kết thúc') <small class="text-red">*</small></label>
                                <input required type="date" name="time_end" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($detail->time_end)->format('Y-m-d') ?? old('time_end') }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label>@lang('Status') </label>
                                <select required name="status" class="form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ $detail->status && $detail->status == $val ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Mô tả') </label>
                                <textarea class="form-control" name="description" rows="5">{{ $detail->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <h4 class="box-title sw_featured">Danh sách dịch vụ (
                                <span for="sw_featured">@lang('Theo chu kỳ thanh toán')</span>
                                <span class="d-flex-al-center">
                                    <label class="switch">
                                        <input id="sw_featured" name="json_params[is_payment_cycle]" value="1"
                                            type="checkbox"
                                            {{ isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1 ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </span>
                                )
                            </h4>
                            <div class="d-flex box_cycle"
                                style="display: {{ isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1 ? 'flex' : 'none' }}">
                                @foreach ($payment_cycle as $item_cycle)
                                    <div class="item_cycle">
                                        <h4 class="box-title">{{ $item_cycle->name }}</h4>
                                        <ul class="mt-15">
                                            @foreach ($service as $item_service)
                                                <li class="d-flex item_service">
                                                    <input class="item_check mr-10 checkService" type="checkbox"
                                                        {{ isset($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}) ? 'checked' : '' }}
                                                        name="json_params[payment_cycle][{{ $item_cycle->id }}][services][{{ $item_service->id }}][service_id]"
                                                        value="{{ $item_service->id }}">
                                                    <input placeholder=""
                                                        class="item_value form-control mr-10 check_disable "
                                                        {{ isset($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}) ? '' : 'disabled' }}
                                                        style="width:150px;"
                                                        name="json_params[payment_cycle][{{ $item_cycle->id }}][services][{{ $item_service->id }}][value]"
                                                        type="number"
                                                        value="{{ $detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}->value ?? '' }}">
                                                    <input placeholder="Số lần áp dụng theo dịch vụ"
                                                        class="item_apply form-control mr-10 check_disable "
                                                        {{ isset($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}) ? '' : 'disabled' }}
                                                        style="width:150px;"
                                                        name="json_params[payment_cycle][{{ $item_cycle->id }}][services][{{ $item_service->id }}][apply_count]"
                                                        type="number" value="{{ $detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}->apply_count ?? '' }}">
                                                    <span class="fw-bold ml-10"
                                                        style="min-width:200px;">{{ $item_service->name ?? '' }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                            <div class="box_default"
                                style="display: {{ isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1 ? 'none' : 'flex' }}">
                                <ul class="mt-15">
                                    @foreach ($service as $item_service)
                                        <li class="d-flex item_service">
                                            <input class="item_check mr-10 checkService" type="checkbox"
                                                {{ isset($detail->json_params->services->{$item_service->id}) ? 'checked' : '' }}
                                                name="json_params[services][{{ $item_service->id }}][service_id]"
                                                value="{{ $item_service->id }}">
                                            <input placeholder="" class="item_value form-control mr-10 check_disable "
                                                {{ isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled' }}
                                                style="width:250px;"
                                                name="json_params[services][{{ $item_service->id }}][value]"
                                                type="number"
                                                value="{{ $detail->json_params->services->{$item_service->id}->value ?? '' }}">
                                            <input placeholder="Số lần áp dụng theo dịch vụ"
                                                class="item_apply form-control mr-10 check_disable "
                                                {{ isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled' }}
                                                style="width:250px;"
                                                name="json_params[services][{{ $item_service->id }}][apply_count]"
                                                type="number"
                                                value="{{ $detail->json_params->services->{$item_service->id}->apply_count ?? '' }}">
                                            <span class="fw-bold ml-10"
                                                style="min-width:200px;">{{ $item_service->name ?? '' }}
                                            </span>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
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
            $('.select_type').trigger('change');
            $('.checkService').change(function() {
                var check = $(this).is(':checked');
                $(this).parents('.item_service').find('.check_disable').attr('disabled', !check)
            })
            $('#form_promotion').on('submit', function(e) {
                // Kiểm tra xem có ít nhất một checkbox được chọn hay không
                if ($('.checkService:checked').length === 0) {
                    e.preventDefault(); // Ngăn form submit
                    alert('Vui lòng chọn ít nhất một dịch vụ!');
                }
            });

        });
    </script>
@endsection
