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
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Khu vực') </label>
                                <select name="area_id" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ $val == old('area_id', $detail->area_id) ? 'selected' : '' }}>
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Loại phí dịch vụ') <small class="text-red">*</small></label>
                                <select required name="type" class="form-control select2">
                                    @foreach ($type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ $val == old('type', $detail->type) ? 'selected' : '' }}>
                                            {{ __($val) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày bắt đầu áp dụng') </label>
                                <input type="date" class="form-control" name="time_start"
                                    value="{{ old('time_start') ?? optional(\Carbon\Carbon::parse($detail->time_start))->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Ngày kết thúc áp dụng') </label>
                                <input type="date" class="form-control" name="time_end"
                                    value="{{ old('time_end') ?? optional(\Carbon\Carbon::parse($detail->time_end))->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-12 mt-15">
                            <h4 class="box-title">Danh sách phí theo khung giờ</h4>
                            <div class="list_item mt-10">
                                @if (isset($detail->json_params->time_range) && count((array)$detail->json_params->time_range) > 0)
                                    @foreach ((array)$detail->json_params->time_range as $key => $val)
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Thời gian từ') </label>
                                                    <input type="time" class="form-control"
                                                        name="time_range[{{ $key }}][block_start]"
                                                        value="{{ $val->block_start ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Thời gian đến') </label>
                                                    <input type="time" class="form-control"
                                                        name="time_range[{{ $key }}][block_end]"
                                                        value="{{ $val->block_end ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Số tiền') </label>
                                                    <input type="number" class="form-control"
                                                        name="time_range[{{ $key }}][price]"
                                                        placeholder="@lang('Số tiền')" value="{{ $val->price ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                                                    onclick="$(this).closest('.row').remove()" title="@lang('Delete')"
                                                    data-original-title="@lang('Delete')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button class="btn btn-sm btn-primary btn_addtime" type="button">
                                <i class="fa fa-plus"></i> @lang('Thêm khung giờ')
                            </button>
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
        $('.btn_addtime').click(function() {
            var currentDateTime = Math.floor(Date.now() / 100);
            let _html = `
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('Thời gian từ') </label>
                        <input type="time" class="form-control" name="time_range[${currentDateTime}][block_start]"
                            value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('Thời gian đến') </label>
                        <input type="time" class="form-control" name="time_range[${currentDateTime}][block_end]"
                            value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('Số tiền') </label>
                        <input type="number" class="form-control" name="time_range[${currentDateTime}][price]"
                            placeholder="@lang('Số tiền')" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                        onclick="$(this).closest('.row').remove()" title="@lang('Delete')"
                        data-original-title="@lang('Delete')">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            `;
            $('.list_item').append(_html);
        })
    </script>
@endsection
