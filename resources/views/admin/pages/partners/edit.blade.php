@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i>
                @lang('Thêm mới đối tác')</a>
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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
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
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Tên công ty') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $user->name ?? old('name') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Mã đối tác') </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('Mã Code')" name="user_code"
                                                        value="{{ $user->user_code ?? (old('user_code') ?? '') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Tên đường') </label>
                                                    <input type="text" class="form-control" name="json_params[street]"
                                                        placeholder="@lang('Tên đường')"
                                                        value="{{ $user->json_params->street ?? (old('json_params[street]') ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Số nhà') </label>
                                                    <input type="text" class="form-control" name="json_params[number]"
                                                        placeholder="@lang('Số nhà')"
                                                        value="{{ $user->json_params->number ?? (old('json_params[number]') ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('PLZ') </label>
                                                    <input type="text" class="form-control" name="json_params[plz]"
                                                        placeholder="@lang('PLZ')"
                                                        value="{{ $user->json_params->plz ?? (old('json_params[plz]') ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Thành phố') </label>
                                                    <input type="text" class="form-control" name="json_params[city]"
                                                        placeholder="@lang('Thành phố')"
                                                        value="{{ $user->json_params->city ?? (old('json_params[city]') ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-8">
                                                <div class="form-group">
                                                    <label>@lang('Đối tượng tìm kiếm') </label>
                                                    <select name="json_params[target_search][]"
                                                        class=" form-control select2" multiple="multiple">
                                                        @php
                                                            $user_target_search_arr = !is_array(
                                                                $user->json_params->target_search,
                                                            )
                                                                ? json_decode($user->json_params->target_search)
                                                                : $user->json_params->target_search;
                                                        @endphp
                                                        @foreach ($target_search as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($user->json_params->target_search) && in_array($key, $user_target_search_arr) ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Nhóm ngành') <small class="text-red">*</small></label>
                                                    <select name="json_params[field_id]" class=" form-control select2"
                                                        required>
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($fields as $val)
                                                            <option
                                                                value="{{ $val->id }}"{{ isset($user->json_params->field_id) && $user->json_params->field_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Người liên lạc') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control"
                                                        name="json_params[contact_name]" placeholder="@lang('Người liên lạc')"
                                                        value="{{ $user->json_params->contact_name ?? (old('json_params[contact_name]') ?? '') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-red">*</small></label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ $user->email ?? old('email') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Điện thoại') </label>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ $user->phone ?? old('phone') }}">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o"></i>
                                @lang('Save')</button>
                            <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script></script>
@endsection
