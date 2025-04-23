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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $admin->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
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
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>@lang('Chức năng mở rộng') (@lang('Chỉ IT cấu hình'))</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_4" data-toggle="tab">
                                            <h5>@lang('Menu mở rộng') (@lang('Chỉ IT cấu hình'))</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-red">*</small></label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ $admin->email }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Password') <small
                                                            class="text-muted"><i>(@lang("Skip if you don't want to change your password"))</i></small></label>
                                                    <input type="password" class="form-control" name="password_new"
                                                        placeholder="@lang('Password must be at least 8 characters')" value=""
                                                        autocomplete="new-password">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã nhân viên') </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('Mã Code')" name="admin_code"
                                                        value="{{ old('admin_code') ?? $admin->admin_code }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Full name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $admin->name }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Phone') </label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="@lang('Phone')"
                                                        value="{{ old('phone') ?? $admin->phone }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Birthday') </label>
                                                    <input type="date" class="form-control" name="birthday"
                                                        placeholder="@lang('Birthday')"
                                                        value="{{ old('birthday') ?? (\Carbon\Carbon::parse($admin->birthday)->format('Y-m-d') ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Position') </label>
                                                    <input type="text" class="form-control" name="json_params[position]"
                                                        placeholder="@lang('Position')"
                                                        value="{{ old('json_params[position]') ?? ($admin->json_params->position ?? '') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <textarea name="json_params[address]" class="form-control" rows="5">{{ $admin->json_params->address ?? old('json_params[address]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Brief')</label>
                                                    <textarea name="json_params[brief]" class="form-control" rows="5">{{ $admin->json_params->brief ?? old('json_params[brief]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Content')</label>
                                                        <textarea name="json_params[content]" class="form-control" id="content_vi">{{ $admin->json_params->content ?? old('json_params[content]') }}</textarea>
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
                                                                !empty($admin->json_params->area_id) &&
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

                                    <div class="tab-pane" id="tab_3">

                                        {{-- <div class="masonry-container">
                      @if (count($activeModules) == 0)
                        <p>
                          @lang('No record found on the system!')
                        </p>
                      @else
                        @foreach ($activeModules as $module)
                          <div class="masonry-box-item">
                            <ul class="checkbox_list">
                              <li>
                                <label
                                  for="json_access_module_code_{{ $module->id }}"><strong>{{ __($module->name) }}</strong></label>
                              </li>
                              @foreach ($module->moduleFunctions as $func)
                                @if (isset($admin->permission_access_by_role->function_code) && in_array($func->function_code, $admin->permission_access_by_role->function_code))
                                  <li>
                                    <input type="checkbox" class="mr-15" checked disabled>
                                    <label style="font-style: italic;" class="text-danger"
                                      for="json_access_function_code_{{ $func->id }}">{{ __($func->name) }}
                                      ({{ $func->function_code ?? '' }})
                                    </label>
                                  </li>
                                @else
                                  @php
                                    $checked = '';
                                    if (
                                        isset($admin->json_params->function_code) &&
                                        in_array($func->function_code, $admin->json_params->function_code)
                                    ) {
                                        $checked = 'checked';
                                    }
                                  @endphp
                                  <li>
                                    <input name="json_params[function_code][]" type="checkbox"
                                      value="{{ $func->function_code }}"
                                      id="json_access_function_code_{{ $func->id }}" class="mr-15"
                                      {{ $checked }}>
                                    <label for="json_access_function_code_{{ $func->id }}">{{ __($func->name) }}
                                      ({{ $func->function_code ?? '' }})
                                    </label>
                                  </li>
                                @endif
                              @endforeach

                            </ul>
                          </div>
                        @endforeach
                      @endif

                    </div> --}}

                                    </div>

                                    <div class="tab-pane" id="tab_4">
                                        <div class="masonry-container">
                                            @if (count($activeMenus) == 0)
                                                <div class="col-12">
                                                    @lang('No record found on the system!')
                                                </div>
                                            @else
                                                {{-- @foreach ($activeMenus as $menu)
                                                    <div class="masonry-box-item">
                                                        <ul class="checkbox_list">
                                                            @include('admin.pages.admins.role-menu-item', [
                                                                'menu' => $menu,
                                                                'selectedMenus' => array_merge(
                                                                    $admin->permission_access_by_role->menu_id ??
                                                                        [],
                                                                    $admin->json_params->menu_id ?? []),
                                                            ])
                                                        </ul>
                                                    </div>
                                                @endforeach --}}
                                            @endif

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2 {{ $admin->status }}">
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
                                    $arr_role_extend = (array) $admin->json_params->role_extend ?? [];
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
                            <div class="form-group">
                                <select name="teacher_type" class="teacher_type form-control select2">
                                    @foreach ($teacher_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($admin->teacher_type) && $admin->teacher_type == $key ? 'selected' : '' }}>
                                            {{ __($val) }}
                                        </option>
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
                                        <option value="{{ $items->id }}"
                                            {{ isset($admin->area_id) && $admin->area_id == $items->id ? 'selected' : '' }}>
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
                                        <option {{ $admin->department_id == $val->id ? 'selected' : '' }}
                                            value="{{ $val->id }}">
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
                                            {{ isset($admin->parent_id) && $admin->parent_id == $val->id ? 'selected' : '' }}>
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
                            <div class="form-group box_img_right {{ isset($admin->avatar) ? 'active' : '' }}">
                                <div id="avatar-holder">
                                    @if (isset($admin->avatar) && $admin->avatar != '')
                                        <img src="{{ $admin->avatar }}">
                                    @else
                                        <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                    @endif
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
                                        placeholder="@lang('Image source')" value="{{ $admin->avatar ?? '' }}">
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
        </form>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.admin_type').trigger('change');
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

        CKEDITOR.replace('content_vi', ck_options);
    </script>
@endsection
