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
                                                        placeholder="@lang('Phone')" value="{{ old('phone') }}"
                                                        >
                                                </div>
                                            </div>
                                           <div class="col-md-6">
                                               <div class="form-group">
                                                <label>@lang('Country')</label>
                                                    <select name="country_id" class="country-select form-control select2">
                                                         @foreach($country as $key => $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ (old('country_id') && old('country_id')== $val->id )? 'selected' : '' }}>
                                                                {{ $val->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                           </div>
                                           <div class="col-md-6">
                                               <div class="form-group">
                                                <label>@lang('City')</label>
                                                    <select name="city_id" class="city-select form-control select2">
                                                         
                                                    </select>
                                                </div>
                                           </div>   
                                           <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Address')</label>
                                                    <input type="text" class="form-control" name="street_address"
                                                        placeholder="@lang('Address')" value="{{ old('street_address') }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                  <label>@lang('Password') <small class="text-red">*</small></label>
                                                  <input type="password" class="form-control" required name="password" placeholder="@lang('Password must be at least 8 characters')"
                                                    value="{{ old('password') }}" autocomplete="new-password">
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
                                            {{ ((old('status')) && old('status') == $val )? 'selected' : '' }}>
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
                                <div id="image-holder" class="box_image {{ old('avatar') ? 'active' : '' }}">
                                    <img class="img-width"
                                        src="{{ old('avatar') ?? url('themes/admin/img/no_image.jpg') }}">
                                    <input id="image" class="form-control hidden list_image" type="text"
                                        name="avatar" value="{{ old('avatar') ?? '' }}">
                                    <span class="btn btn-sm btn-danger btn-remove" style="display: none"><i
                                            class="fa fa-trash"></i></span>
                                </div>
                                <span class="input-group-btn">
                                    <a data-input="image" class="btn btn-primary lfm" data-type="cms-image">
                                        <i class="fa fa-picture-o"></i> @lang('choose')
                                    </a>
                                </span>
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
            $('.country-select').trigger('change');

        })
        $('.country-select').change(function() {
            var _id = $(this).val();
            var city_id = {{ $detail->city_id ?? '0' }};
            $.ajax({
                type: "POST",
                url: '{{ route('frontend.order.getcity') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": _id
                },
                success: function(response) {
                    var data = response.data;
                    var html = "";
                    data.forEach(elm => {
                        var select = "";
                        if (elm.id == city_id) select = "selected";
                        html += `<option ` + select + ` value="` + elm.id + `">` + elm
                            .name + `</option>`;
                    })
                    $('.city-select').html(html);
                    $('.city-select').trigger('change');
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.message;
                    alert(errors);
                    location.reload();
                }
            });
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
