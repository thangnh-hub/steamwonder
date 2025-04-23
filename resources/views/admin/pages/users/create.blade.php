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
        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        @csrf
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Name')" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Email')<small class="text-red">*</small></label>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="@lang('Email')" value="{{ old('email') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Phone')</label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="@lang('Phone')" value="{{ old('phone') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <input type="text" class="form-control" name="address"
                                                        placeholder="@lang('Address')" value="{{ old('address') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Password') <small class="text-red">*</small></label>
                                                    <input type="password" class="form-control" required name="password"
                                                        placeholder="@lang('Password must be at least 8 characters')" value="{{ old('password') }}"
                                                        autocomplete="new-password">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->

                    </div>
                </div>

                <div class="col-lg-4">
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
                                <a class="btn btn-success " href="{{ route(Request::segment(2) . '.index') }}">
                                    <i class="fa fa-bars"></i> @lang('List')
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Status')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="status" class=" form-control select2">
                                    @foreach (App\Consts::USER_STATUS as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ old('status') && old('status') == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right">
                                <div id="image-holder">
                                    <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> @lang('choose')
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden" name="image"
                                        placeholder="@lang('Image source')" value="{{ old('avatar') ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

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
    <script></script>
@endsection
