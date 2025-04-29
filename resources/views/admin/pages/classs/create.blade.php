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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_class">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mã lớp') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="code" id="code"
                                                        placeholder="@lang('Mã lớp')" value="{{ old('code') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="class_name" placeholder="@lang('Title')"
                                                        value="{{ old('name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Area') <small class="text-red">*</small></label>
                                                    <select required name="area_id" class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($areas as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ old('area_id') && old('area_id') == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Room') <small class="text-red">*</small></label>
                                                    <select required name="room_id" class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($rooms as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ old('room_id') && old('room_id') == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Độ tuổi') <small class="text-red">*</small></label>
                                                    <select required name="education_age_id" class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($ages as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ old('education_age_id') && old('education_age_id') == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Chương trình') <small class="text-red">*</small></label>
                                                    <select required name="education_program_id"
                                                        class="form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($programs as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ old('education_program_id') && old('education_program_id') == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Slot') <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="slot"
                                                        placeholder="@lang('Slot')" min="0"
                                                        value="{{ old('slot') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Order') </label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="@lang('Order')" min="0"
                                                        value="{{ old('iorder') ?? 0 }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Status') </label>
                                                    <select required name="status " class="form-control select2">
                                                        @foreach ($status as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ old('status') && old('status') == $val ? 'selected' : '' }}>
                                                                {{ __($val) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sw_featured">@lang('Là năm cuối')</label>
                                                    <div class="sw_featured d-flex-al-center">
                                                        <label class="switch ">
                                                            <input id="sw_featured" name="is_lastyear" value="1"
                                                                type="checkbox"
                                                                {{ old('is_lastyear') && old('is_lastyear') == '1' ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                <i class="fa fa-bars"></i> @lang('List')
                            </a>
                            <button type="submit" class="btn btn-primary pull-right btn-sm"><i
                                    class="fa fa-floppy-o"></i>
                                @lang('Save')</button>
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
