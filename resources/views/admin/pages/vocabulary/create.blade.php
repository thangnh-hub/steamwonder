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
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>

                        </div>
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Từ vựng') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Từ vựng')" value="{{ old('name') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Tiền tố')</label>
                                                    <input type="text" class="form-control" name="prefix"
                                                        placeholder="@lang('Tiền tố')" value="{{ old('prefix') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Phiên âm')</label>
                                                    <input type="text" class="form-control" name="transcription"
                                                        placeholder="@lang('Phiên âm')" value="{{ old('transcription') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Nghĩa tiếng việt')</label>
                                                    <input type="text" class="form-control" name="meaning"
                                                        placeholder="@lang('Nghĩa tiếng việt')" value="{{ old('meaning') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Giải thích bằng tiếng Đức')</label>
                                                    <textarea name="json_params[explanation][de]" class="form-control" rows="5">{{ old('json_params[explanation][de]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Giải thích bằng tiếng Việt')</label>
                                                    <textarea name="json_params[explanation][vi]" class="form-control" rows="5">{{ old('json_params[explanation][vi]') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Mẫu câu')</label>
                                                    <textarea name="json_params[sample]" id="sample" class="form-control" rows="5">{{ old('jjson_params[sample]') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group box_img_right {{ isset($detail->image) ? 'active' : '' }}">
                                <div id="image-holder">
                                    <img src="{{ url('themes/admin/img/no_image.jpg') }}">
                                </div>
                                <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-trash"></i></span>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                            data-type="cms-image">
                                            <i class="fa fa-picture-o"></i> @lang('Choose')
                                        </a>
                                    </span>
                                    <input id="image" class="form-control inp_hidden" type="hidden" name="image"
                                        placeholder="@lang('Image source')" value="{{ $detail->image ?? '' }}">
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

        </form>
    </section>

@endsection

@section('script')
    <script>
        CKEDITOR.replace('sample', ck_options);

        // Change to filter
        $(document).ready(function() {
            var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';
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
    </script>
@endsection
