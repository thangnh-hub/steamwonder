@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection
@php
    if (Request::get('lang') == $languageDefault->lang_locale || Request::get('lang') == '') {
        $lang = $languageDefault->lang_locale;
    } else {
        $lang = Request::get('lang');
    }
@endphp
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
                <div class="col-lg-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                            @isset($languages)
                                @foreach ($languages as $item)
                                    @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                                        @if (Request::get('lang') != '')
                                            <a class="text-primary pull-right"
                                                href="{{ route(Request::segment(2) . '.create') }}" style="padding-left: 15px">
                                                <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                            </a>
                                        @endif
                                    @else
                                        @if (Request::get('lang') != $item->lang_locale)
                                            <a class="text-primary pull-right"
                                                href="{{ route(Request::segment(2) . '.create') }}?lang={{ $item->lang_locale }}"
                                                style="padding-left: 15px">
                                                <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                            </a>
                                        @endif
                                    @endif
                                @endforeach
                            @endisset
                            <span class="pull-right">@lang('Student code'): <strong>{{ $detail->admin_code }}</strong></span>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">
                                            <h5>Thông tin đăng nhập</h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1_1">
                                        <div class="d-flex-wap">
                                            @if ($lang != '')
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                            @endif
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="email"
                                                        placeholder="@lang('Email')" value="{{ $detail->email }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Student code') </label>
                                                    <input type="text" class="form-control" name="admin_code"
                                                        placeholder="@lang('Admin code')"
                                                        value="{{ old('admin_code') ?? $detail->admin_code }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Password') <small
                                                            class="text-muted"><i>(@lang("Skip if you don't want to change your password"))</i></small></label>
                                                    <input type="password" class="form-control" name="password_new"
                                                        placeholder="@lang('Password must be at least 8 characters')" value=""
                                                        autocomplete="new-password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_2_1" data-toggle="tab">
                                            <h5>Thông tin học viên<span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    {{-- <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button> --}}
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_2_1">
                                        <div class="d-flex-wap">
                                            @if ($lang != '')
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                            @endif
                                            <div class="col-xs-12 col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Full name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Full name')"
                                                        value="{{ old('name') ?? $detail->name }}" required>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Middle name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[middle_name]"
                                                        placeholder="@lang('Middle name')" value="{{ old('json_params[middle_name]') ?? $detail->json_params->middle_name }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('First name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[first_name]"
                                                        placeholder="@lang('First name')" value="{{ old('json_params[first_name]') ?? $detail->json_params->first_name }}"
                                                        required>
                                                </div>
                                            </div> --}}

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Phone')</label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="@lang('Phone')"
                                                        value="{{ old('phone') ?? $detail->phone }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Birthday')</label>
                                                    <input type="datetime-local" class="form-control" name="birthday"
                                                        placeholder="@lang('Birthday')"
                                                        value="{{ old('birthday') ?? $detail->birthday }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Gender') <small class="text-red">*</small></label>
                                                    <select name="gender" class=" form-control select2" required>
                                                        <option value="" selected disabled>@lang('Please select')
                                                        </option>
                                                        @foreach ($gender as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->gender) && $detail->gender == $val ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <textarea name="json_params[address]" class="form-control" rows="5">{{ $detail->json_params->address ?? old('json_params[address]') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_3_1" data-toggle="tab">
                                            <h5>Thông tin tuyển sinh</h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_3_1">
                                        <div class="d-flex-wap">
                                            @if ($lang != '')
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                            @endif
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Entry Level')</label>
                                                    <select name="json_params[entry_level_id]"
                                                        class=" form-control select2">
                                                        @foreach ($entry_level as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->entry_level_id) && $detail->json_params->entry_level_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Major')</label>
                                                    <select name="json_params[major_id]" class=" form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($major as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->major_id) && $detail->json_params->major_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Class')</label>
                                                    <select name="json_params[class_id]" class=" form-control select2">
                                                        <option value="">Chọn lớp chính</option>
                                                        @foreach ($class as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->class_id) && $detail->json_params->class_id == $val->id ? 'checked' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> --}}

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Forms of training')</label>
                                                    <select name="json_params[forms_training]"
                                                        class=" form-control select2">
                                                        @foreach ($forms_training as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->json_params->forms_training) && $detail->json_params->forms_training == $val ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Admissions')</label>
                                                    <select name="admission_id" class=" form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($admission as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->admission_id) && $detail->admission_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Field')</label>
                                                    <select name="field_id[]" multiple="multiple"
                                                        class="form-control select2">
                                                        @foreach ($field as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->field_id) && in_array($val->id, $detail->json_params->field_id) ? 'selected' : '' }}>
                                                                {{ $val->json_params->name->$lang ?? $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                            <!-- Custom Tabs -->
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_4_1" data-toggle="tab">
                                            <h5>Thông tin gia đình</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_5_1">
                                        <div class="d-flex-wap">
                                            @if ($lang != '')
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                            @endif
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Father full name')</label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[dad_name]" placeholder="@lang('Father full name')"
                                                        value="{{ old('json_params[dad_name]') ?? ($detail->json_params->dad_name ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Father phone')</label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[dad_phone]" placeholder="@lang('Father phone')"
                                                        value="{{ old('json_params[dad_phone]') ?? ($detail->json_params->dad_phone ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mother full name')</label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[mami_name]" placeholder="@lang('Mother full name')"
                                                        value="{{ old('json_params[mami_name]') ?? ($detail->json_params->mami_name ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mother phone')</label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[mami_phone]" placeholder="@lang('Mother phone')"
                                                        value="{{ old('json_params[mami_phone]') ?? ($detail->json_params->mami_phone ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_5_1" data-toggle="tab">
                                            <h5>Thông tin giấy tờ tùy thân</h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_5_1">
                                        <div class="d-flex-wap">
                                            @if ($lang != '')
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                            @endif
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('CCCD / CMT') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[cccd]"
                                                        required placeholder="@lang('CCCD / CMT')"
                                                        value="{{ old('json_params[cccd]') ?? ($detail->json_params->cccd ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Date range')</label>
                                                    <input type="date" class="form-control"
                                                        name="json_params[date_range]" placeholder="@lang('Date range')"
                                                        value="{{ old('json_params[date_range]') ?? ($detail->json_params->date_range ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Issued by')</label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[issued_by]" placeholder="@lang('Issued by')"
                                                        value="{{ old('json_params[issued_by]') ?? ($detail->json_params->issued_by ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Publish')</h3>
                        </div>
                        <div class="box-body">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                                &nbsp;&nbsp;
                                <a class="btn btn-success "
                                    href="{{ $admin_auth->role == 11 ? route('student.cskh') : route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Role')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>@lang('Role')</label>
                                <select name="role" id="role" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($roles as $item)
                                      <option value="{{ $item->id }}" {{ $detail->role == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                      </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div> --}}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group input-group">
                                <select name="status_study" class=" form-control select2">
                                    @foreach ($status as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($detail->status_study) && $detail->status_study == $val->id ? 'selected' : '' }}>
                                            @lang($val->name)</option>
                                    @endforeach
                                </select>
                                <span data-toggle="modal" data-target=".bd-example-modal-lg"
                                    data-id="{{ $detail->id ?? '' }}" class="input-group-btn"
                                    onclick="showStatusStudent({{ $detail->id }})">
                                    <a class="btn btn-primary"><i class="fa fa-eye"></i> Xem lịch sử đổi trạng thái</a>
                                </span>
                                {{-- <select name="status" class=" form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->status) && $detail->status == $val ? 'checked' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select> --}}
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Ngày nhập học chính thức') </h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="date" class="form-control" name="day_official"
                                    placeholder="Ngày nhập học chính thức" value="{{ $detail->day_official ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Khu vực')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>@lang('Thuộc khu vực')</label>
                                <select name="area_id" class=" form-control select2" required>
                                    <option value="" selected disabled>@lang('Please select')</option>
                                    @foreach ($area as $items)
                                        <option value="{{ $items->id }}"
                                            {{ isset($detail->area_id) && $detail->area_id == $items->id ? 'selected' : '' }}>
                                            {{ __($items->code) }}
                                            - {{ __($items->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Ngày vào khu vực')</label>
                                <input type="date" class="form-control" name="json_params[day_in_area]"
                                    placeholder="Ngày vào khu vực"
                                    value="{{ isset($detail->json_params->day_in_area) && $detail->json_params->day_in_area != '' ? $detail->json_params->day_in_area : '' }}">
                            </div>
                            <div class="form-group">
                                <label>@lang('Ghi chú vào khu vực')</label>
                                <textarea class="form-control" name="json_params[note_in_area]" rows="3">{{ $detail->json_params->note_in_area ?? '' }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Khóa học') <span class="text-danger">*</span></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="course_id" class=" form-control select2" required>
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($courses as $items)
                                        <option value="{{ $items->id }}"
                                            {{ isset($detail->course_id) && $detail->course_id == $items->id ? 'selected' : '' }}>
                                            {{ __($items->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Chỗ ở')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="json_params[dormitory]" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($dormitory as $key => $items)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->dormitory) && $detail->json_params->dormitory == $key ? 'selected' : '' }}>
                                            {{ __($items) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Contract')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>@lang('Contract type')</label>
                                <select name="json_params[contract_type]" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->contract_type) && $detail->json_params->contract_type == $key ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Version')</label>
                                <select name="version" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($version as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->version) && $detail->version == $key ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Contract status')</label>
                                <select name="json_params[contract_status]" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->contract_status) && $detail->json_params->contract_status == $key ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Contract performance status')</label>
                                <select name="json_params[contract_performance_status]" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($contract_performance_status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->contract_performance_status) && $detail->json_params->contract_performance_status == $key ? 'selected' : '' }}>
                                            @lang($val)</option>
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
                            <div class="form-group box_img_right {{ isset($detail->avatar) ? 'active' : '' }}">
                                <div id="avatar-holder">
                                    @if (isset($detail->avatar) && $detail->avatar != '')
                                        <img src="{{ $detail->avatar }}">
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
                                        placeholder="@lang('Image source')" value="{{ $detail->avatar ?? '' }}">
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
                                <a class="btn btn-success "
                                    href="{{ $admin_auth->role == 11 ? route('student.cskh') : route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </section>
    <div class="modal fade bd-example-modal-lg "data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div id="alert-config"></div>
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            @lang('Danh sách lịch sử học viên')
                        </h4>
                    </div>
                    <form action="" method="POST" class="form-ajax-lesson">
                        <div class="modal-body modal-body-add-leson">
                            <div class="box-body table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên</th>
                                            <th>Trạng thái cũ</th>
                                            <th>Trạng thái mới</th>
                                            <th>Ngày cập nhật</th>
                                            <th>Ghi chú</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody class="show-user">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning add_td_history" data-id="{{$detail->id}}">Thêm mới</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // CKEDITOR.replace('content_vi', ck_options);

        $(document).ready(function() {
            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.input[type=hidden]').val("");
            });
        });
        $(document).on('click', '.add_td_history', function() {
            var student_id = $(this).data("id");
            showStatusStudent(student_id,'tr')
            $(this).hide();
        })

        $(document).on('click', '.btn_add_status_study', function(e) {
            e.preventDefault();
            var _url = "{{ route('student.add_history_statusstudy') }}";
            var student_id = $(this).data('id');
            var status_study_old = $(this).parents('tr').find('.status_study_old').val();
            var status_study_new = $(this).parents('tr').find('.status_study_new').val();
            var updated_at = $(this).parents('tr').find('.updated_at ').val();
            var note_status_study = $(this).parents('tr').find('.add_note_status_study').val();
            $.ajax({
                type: 'GET',
                url: _url,
                data: {
                    student_id: student_id,
                    status_study_old: status_study_old,
                    status_study_new: status_study_new,
                    updated_at: updated_at,
                    note_status_study: note_status_study,
                },
                success: function(response) {
                    $("#alert-config").append(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+response.message+'</div>'
                    );
                    setTimeout(function() {
                        $(".alert-success").fadeOut(2000, function() {});
                    }, 800);
                    showStatusStudent(student_id);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        })

        function showStatusStudent(student_id,type='') {
            let _url = "{{ route('ajax.get_history.status_student') }}";
            var _html = $('.show-user');
            var _content = "";
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "student_id": student_id,
                    "type": type,
                },
                dataType: 'JSON',
                success: function(response) {
                    _view = response.data.html;
                    if(type=='tr'){
                        _html.append(_view);
                    }else{
                        _html.html(_view);
                        $('.add_td_history').show();
                    }
                    $('.select2').select2();
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.errors;
                    // Foreach and show errors to htmluu
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = translations.csrf_mismatch;
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    $('.error-container').html(
                        elementErrors); // Assuming you have a container to display errors
                }
            });
        }

        function updateHistory(th) {
            let _id = $(th).attr('data-id');
            let _date = $(th).parents('tr').find('.date_updated').val();
            let _note = $(th).parents('tr').find('.note_status_study').val();
            let _status_old = $(th).parents('tr').find('.status_study_old').val();
            let _status_new = $(th).parents('tr').find('.status_study_new').val();
            let url = "{{ route('ajax.update.day_change_status') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    status_old: _status_old,
                    status_new: _status_new,
                    date: _date,
                    note: _note,
                },
                success: function(response) {
                    $("#alert-config").append(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>'
                    );
                    setTimeout(function() {
                        $(".alert-success").fadeOut(2000, function() {});
                    }, 800);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        function deleteHistory(th) {
            var _confirm = confirm('@lang('confirm_action')');
            if (_confirm) {
                var _this = $(th);
                var _id = $(th).data('id');
                var _url = $(th).data('url');
                $.ajax({
                    type: "POST",
                    url: _url,
                    data: {
                        "_token": '{{ csrf_token() }}',
                        id: _id,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            _this.parents('tr').remove();
                            $("#alert-config").append(
                                '<div class="alert alert-'+response.data+' alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+response.message+'</div>'
                            );
                            setTimeout(function() {
                                $(".alert-"+response.data).fadeOut(2000, function() {});
                            }, 800);
                        }
                        else {
                            $("#alert-config").append(
                                '<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Bạn không có quyền thao tác chức năng này!</div>'
                            );
                            setTimeout(function() {
                                $(".alert-warning").fadeOut(2000, function() {});
                            }, 800);
                        }

                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        }
    </script>
@endsection
