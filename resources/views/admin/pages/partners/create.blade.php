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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
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
                                                        value="{{ old('name') }}" required>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Mã đối tác') </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('Mã Code')" name="user_code"
                                                        value="{{ old('user_code') ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Tên đường') </label>
                                                    <input type="text" class="form-control" name="json_params[street]"
                                                        placeholder="@lang('Tên đường')"
                                                        value="{{ old('json_params[street]') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Số nhà') </label>
                                                    <input type="text" class="form-control" name="json_params[number]"
                                                        placeholder="@lang('Số nhà')"
                                                        value="{{ old('json_params[number]') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('PLZ') </label>
                                                    <input type="text" class="form-control" name="json_params[plz]"
                                                        placeholder="@lang('PLZ')"
                                                        value="{{ old('json_params[plz]') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Thành phố') </label>
                                                    <input type="text" class="form-control" name="json_params[city]"
                                                        placeholder="@lang('Thành phố')"
                                                        value="{{ old('json_params[city]') ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-8">
                                                <div class="form-group">
                                                    <label>@lang('Đối tượng tìm kiếm') </label>
                                                    <select name="json_params[target_search][]" class=" form-control select2" multiple="multiple">
                                                        @foreach ($target_search as $key => $val)
                                                            <option value="{{ $key }}">
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Nhóm ngành') <small class="text-red">*</small></label>
                                                    <select name="json_params[field_id]" class=" form-control select2" required>
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($fields as $val)
                                                            <option value="{{ $val->id }}">
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
                                                        value="{{ old('json_params[contact_name]') ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Email') <small class="text-red">*</small></label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="{{ old('email') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Điện thoại') </label>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="{{ old('phone') }}">
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-primary">
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
                {{-- <div class="col-lg-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2">
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}">
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
                </div> --}}
            </div>
        </form>
    </section>
@endsection
@section('script')
    <script>
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
