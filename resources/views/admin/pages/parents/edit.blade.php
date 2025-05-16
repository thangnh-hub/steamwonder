@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
<form action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('Edit form')</h3>
                </div>

                <div class="box-body">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    <h5>Thông tin phụ huynh <span class="text-danger">*</span></h5>
                                </a>
                            </li>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="d-flex-wap">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Khu vực')<small class="text-red">*</small></label>
                                            <select name="area_id" class="form-control select2" required>
                                                <option value="">@lang('Chọn khu vực')</option>
                                                @foreach ($list_area as $val)
                                                    <option value="{{ $val->id }}" {{ $detail->area_id == $val->id ? 'selected' : '' }}>
                                                        {{ $val->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Họ')<small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $detail->first_name) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Tên')<small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $detail->last_name) }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Số CMND/CCCD') <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="identity_card" value="{{ old('identity_card', $detail->identity_card) }}" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Số điện thoại')<small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $detail->phone) }}" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Email')<small class="text-red">*</small></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $detail->email) }}" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Địa chỉ')</label>
                                            <input type="text" class="form-control" name="address" value="{{ old('address', $detail->address) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Giới tính')</label>
                                            <select name="sex" class="form-control select2">
                                                @foreach ($list_sex as $key => $value)
                                                    <option value="{{ $key }}" {{ $detail->sex == $key ? 'selected' : '' }}>
                                                        {{ __($value) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Ngày sinh')</label>
                                            <input type="date" class="form-control" name="birthday" value="{{ old('birthday', $detail->birthday) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group box_img_right">
                                            <label>@lang('Ảnh đại diện')</label>
                                            <div id="image-holder">
                                                <img src="{{ $detail->avatar ?? url('themes/admin/img/no_image.jpg') }}">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm" data-type="cms-image">
                                                        <i class="fa fa-picture-o"></i> @lang('Choose')
                                                    </a>
                                                </span>
                                                <input id="image" class="form-control inp_hidden" type="hidden" name="avatar" value="{{ old('avatar', $detail->avatar) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- tab-pane -->
                        </div> <!-- tab-content -->
                    </div>
                </div>

                <div class="box-footer">
                    <a href="{{ route(Request::segment(2) . '.index') }}">
                        <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                    </a>
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
