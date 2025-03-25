@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-dm btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
            </a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @if (session('successMessage'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('successMessage') }}
            </div>
        @endif

        @if (session('errorMessage'))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ session('errorMessage') }}
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
                <div class="col-lg-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>@lang('Thông tin chính') <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>@lang('Khu vực dữ liệu được xem') (@lang('Nếu có'))</h5>
                                        </a>
                                    </li>
                                    {{-- <button type="submit" class="btn btn-info btn-sm pull-right">
                                            <i class="fa fa-save"></i> @lang('Save')
                                        </button> --}}
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-red">*</small></label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Password') <small
                                                            class="text-muted"><i>(@lang("Skip if you don't want to change your password"))</i></small></label>
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="@lang('Password must be at least 8 characters')" value=""
                                                        autocomplete="password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã nhân viên') </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('Mã Code')" name="admin_code"
                                                        value="{{ old('admin_code') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Full name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Phone') </label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="@lang('Phone')" value="{{ old('phone') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Birthday') </label>
                                                    <input type="date" class="form-control" name="birthday"
                                                        placeholder="@lang('Birthday')"
                                                        value="{{ old('birthday') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Position') </label>
                                                    <input type="text" class="form-control" name="json_params[position]"
                                                        placeholder="@lang('Position')"
                                                        value="{{ old('json_params[position]') ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <textarea name="json_params[address]" class="form-control" rows="5">{{ old('json_params[address]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Brief')</label>
                                                    <textarea name="json_params[brief]" class="form-control" rows="5">{{ old('json_params[brief]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Content')</label>
                                                        <textarea name="json_params[content]" class="form-control" id="content_vi">{{ old('json_params[content]') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            @foreach ($area as $items)
                                                <div class="col-md-4">
                                                    <ul class="checkbox_list">
                                                        @php
                                                            $checked = '';
                                                            if (
                                                                isset($admin->json_params->area_id) &&
                                                                in_array($items->id, $admin->json_params->area_id)
                                                            ) {
                                                                $checked = 'checked';
                                                            }
                                                        @endphp
                                                        <li>
                                                            <input name="json_params[area_id][]" type="checkbox"
                                                                value="{{ $items->id }}"
                                                                id="json_access_menu_id_{{ $items->id }}"
                                                                class="mr-15" {{ $checked }}>
                                                            <label for="json_access_menu_id_{{ $items->id }}"><strong>{{ __($items->code) }}
                                                                    - {{ __($items->name) }}</strong></label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($admin->status) && $admin->status == $key ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Gender')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="gender" class=" form-control select2">
                                    @foreach ($gender as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($admin->gender) && $admin->gender == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Role') <small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="role" id="role" class="form-control select2" required>
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($admin->role) && $admin->role == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Quyền mở rộng')</label>
                                @php
                                    $arr_role_extend = $admin->json_params->role_extend ?? [];
                                @endphp
                                <select name="json_params[role_extend][]" id="role_extend" class="form-control select2"
                                    multiple="multiple" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}"
                                            {{ in_array($item->id, $arr_role_extend) ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Admin type')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="admin_type" class="admin_type form-control select2">
                                    @foreach ($admin_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($admin->admin_type) && $admin->admin_type == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Thuộc khu vực (nếu có)')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="area_id" class=" form-control select2">
                                    <option value="" selected disabled>@lang('Please select')</option>
                                    @foreach ($area as $items)
                                        <option value="{{ $items->id }}">
                                            {{ __($items->code) }}
                                            - {{ __($items->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Phòng ban')</label>
                                <select name="department_id" class="form-control select2">
                                    <option value="">Chọn</option>
                                    @foreach ($departments as $key => $val)
                                        <option value="{{ $val->id }}">
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Direct manager')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="parent_id" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($direct_manager as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($my_info) && $my_info == $val->id ? 'selected' : '' }}>
                                            {{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Avatar')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right">
                                <div id="avatar-holder">
                                    <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="avatar" data-preview="avatar-holder" class="btn btn-primary lfm"
                                            data-type="cms-avatar">
                                            <i class="fa fa-picture-o"></i> @lang('Choose')
                                        </a>
                                    </span>
                                    <input id="avatar" class="form-control inp_hidden" type="hidden" name="avatar"
                                        placeholder="@lang('Image source')" value="">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script>
        CKEDITOR.replace('content_vi', ck_options);
        $(document).ready(function() {
            var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';
            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.inp_hidden').val("");
            });

        })
    </script>
@endsection
