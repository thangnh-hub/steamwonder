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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        @csrf
                        @method('PUT')
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
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('username') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('Name')"
                                                        value="{{ $detail->username ?? '' }}" disabled readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Password') <small
                                                            class="text-muted"><i>(@lang("Skip if you don't want to change your password"))</i></small></label>
                                                    <input type="password" class="form-control" name="password_new"
                                                        placeholder="@lang('Password must be at least 6 characters')" value=""
                                                        autocomplete="new-password">
                                                </div>
                                            </div>
                                            <hr class="col-md-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('First name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="first_name"
                                                        placeholder="@lang('First name')"
                                                        value="{{ $detail->first_name ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Last name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="last_name"
                                                        placeholder="@lang('Last name')"
                                                        value="{{ $detail->last_name ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Email')</label>
                                                    <input type="text" class="form-control" name="email"
                                                        placeholder="@lang('Email')" value="{{ $detail->email }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Phone') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="phone" required
                                                        placeholder="@lang('Phone')" value="{{ $detail->phone }}"
                                                        required>
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <input type="text" class="form-control" name="street_address"
                                                        placeholder="@lang('Address')"
                                                        value="{{ $detail->street_address }}">
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
                                            {{ isset($detail->status) && $detail->status == $val ? 'selected' : '' }}>
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
                            <div class="form-group box_img_right {{ isset($detail->avatar) ? 'active' : '' }}">
                                <div id="image-holder" class="img-width">
                                    @if ($detail->avatar != '')
                                        <img src="{{ $detail->avatar }}">
                                    @else
                                        <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                    @endif
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> @lang('Choose')
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden" name="avatar"
                                        placeholder="@lang('Image source')" value="{{ $detail->avatar ?? '' }}">
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
    <script>
        $(document).ready(function() {


        })

        $('.img-width, .btn-remove').on('mouseover', function(e) {
            $(this).parents('.active').find('.btn-remove').show();
        });
        $('.img-width, .btn-remove').on('mouseout', function(e) {
            $(this).parents('.active').find('.btn-remove').hide();
        });
        var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';
        $('.btn-remove').click(function() {
            $(this).hide();
            let par = $(this).parents('.box_image');
            par.removeClass('active');
            par.find('img').attr('src', no_image_link);
            par.find('.list_image').val("");
        });
        $('.list_image').on('change', function() {
            var img_path = $(this).val();
            $(this).parents('.box_image').addClass('active');
            $(this).parents('.box_image').find('img').attr('src', img_path);
        });
    </script>
@endsection
