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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="box box-primary">
                <div class="box-body">
                    <div class="d-flex-wap">
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Trạng thái') <small class="text-red">*</small></label>
                                <select name="status" required class="form-control select2 w-100">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ $detail->status && $detail->status == $key ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Mã giảm trừ') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="code" placeholder="@lang('Mã giảm trừ')"
                                    value="{{ $detail->code ?? '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Tên giảm trừ') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="name" placeholder="@lang('Tên giảm trừ')"
                                    value="{{ $detail->name ?? '' }}" required>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Loại giảm trừ')<small class="text-red">*</small></label>
                                <select required name="type" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ $detail->type && $detail->type == $key ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Kiểu điều kiện')<small class="text-red">*</small></label>
                                <select required name="condition_type" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($condition_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ $detail->condition_type && $detail->condition_type == $key ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sw_featured">@lang('Cho phép giảm lũy kế')</label>
                                <div class="sw_featured d-flex-al-center">
                                    <label class="switch ">
                                        <input id="sw_featured" name="is_cumulative" value="1" type="checkbox"
                                            {{ $detail->is_cumulative && $detail->is_cumulative == '1' ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Từ')</label>
                                <input type="number" class="form-control" name="json_params[condition][start]"
                                    value="{{ $detail->json_params->condition->start ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Đến')</label>
                                <input type="number" class="form-control" name="json_params[condition][end]"
                                    value="{{ $detail->json_params->condition->end ?? '' }}">
                            </div>
                        </div>


                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Mô tả')</label>
                                <textarea class="form-control" name="description" rows="5">{{ $detail->description ?? '' }}</textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <h4 class="box-title">Danh sách dịch vụ</h4>
                            <ul class="mt-15">
                                @foreach ($service as $item_service)
                                    <li class="d-flex-wap item_service">
                                        <input class="item_check mr-10 checkService" type="checkbox"
                                            {{ isset($detail->json_params->services->{$item_service->id}) ? 'checked' : '' }}
                                            name="json_params[services][{{ $item_service->id }}][service_id]"
                                            value="{{ $item_service->id }}">
                                        <input placeholder="Nhập số tiền (hoặc %) giảm trừ"
                                            class="item_number_hssv form-control mr-10 check_disable "
                                            {{ isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled' }}
                                            style="width:250px;"
                                            name="json_params[services][{{ $item_service->id }}][value]" type="number"
                                            value="{{ $detail->json_params->services->{$item_service->id}->value ?? '' }}">
                                        <select name="json_params[services][{{ $item_service->id }}][type]"
                                            class="form-control select2 mr-10 check_disable"
                                            {{ isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled' }}
                                            style="width: 250px">
                                            @foreach ($type as $item)
                                                <option value="{{ $item }}"
                                                    {{ isset($detail->json_params->services->{$item_service->id}->type) && $detail->json_params->services->{$item_service->id}->type == $item ? 'selected' : '' }}>
                                                    {{ __($item) }}</option>
                                            @endforeach
                                        </select>
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
        $('.checkService').change(function() {
            var check = $(this).is(':checked');
            $(this).parents('.item_service').find('.check_disable').attr('disabled', !check)
        })
    </script>
@endsection
