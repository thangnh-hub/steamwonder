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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_product">
            @csrf
            @if (Request::get('lang') != '' && Request::get('lang') != $languageDefault->lang_locale)
                <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
            @endif
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
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
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Title') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        placeholder="@lang('Title')" value="{{ old('title') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày khai giảng') </label>
                                                    <input type="date" class="form-control" name="day_opening"
                                                        placeholder="@lang('Ngày khai giảng')" value="{{ old('day_opening') }}">
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Price')</label>
                                                    <input type="number" class="form-control" name="json_params[price]"
                                                        placeholder="@lang('Price')"
                                                        value="{{ old('json_params[price]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Bài học')</label>
                                                    <input type="number" class="form-control" name="json_params[bai_hoc]"
                                                        placeholder="@lang('Price old')"
                                                        value="{{ old('json_params[bai_hoc]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Slot')</label>
                                                    <input type="number" class="form-control" name="json_params[slot]"
                                                        placeholder="@lang('Slot')"
                                                        value="{{ old('json_params[slot]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Thời lượng')</label>
                                                    <input type="text" class="form-control" name="json_params[thoi_luong]"
                                                        placeholder="@lang('Count order')"
                                                        value="{{ old('json_params[thoi_luong]') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Brief')</label>
                                                        <textarea name="json_params[brief][{{ $lang }}]" class="form-control" rows="5" id="brief_vi">{{ old('json_params[brief][' . $lang . ']') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Target')</label>
                                                        <textarea name="json_params[target][{{ $lang }}]" class="form-control" rows="5" id="target_vi">{{ old('json_params[target][' . $lang . ']') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="form-group">
                                                        <label>@lang('Description')</label>
                                                        <textarea name="json_params[des][{{ $lang }}]" class="form-control" rows="5" id="des_vi">{{ old('json_params[des][' . $lang . ']') }}</textarea>
                                                    </div>
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->


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
                                    @foreach ($status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->status) && $detail->status == $key ? 'checked' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Loại')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="type" class=" form-control select2 type_course" required>
                                    @foreach ($course_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->course_type) && $detail->course_type == $key ? 'checked' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border sw_featured d-flex-al-center">
                            <label class="switch ">
                                <input id="sw_featured" name="json_params[is_featured]" value="1" type="checkbox"
                                    {{ isset($detail->json_params->is_featured) && $detail->json_params->is_featured == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <label class="box-title ml-1" for="sw_featured">@lang('Is featured')</label>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Level')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="level_id" class="level_id form-control select2">
                                    <option value="">@lang('Level')</option>
                                    @foreach ($levels as $val)
                                        <option value="{{ $val->id }}">
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Syllabus') <small small class="text-red">*</small></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <select name="syllabus_id" class="syllabus_avaible form-control select2">
                                    <option value="">@lang('Syllabus')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Image')</h3>
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
                                    <input id="avatar" class="form-control inp_hidden" type="hidden"
                                        name="json_params[image]" placeholder="@lang('Image source')" value="">
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        //CKEDITOR.replace('brief_vi', ck_options);
        //CKEDITOR.replace('target_vi', ck_options);
        //CKEDITOR.replace('des_vi', ck_options);
        $(document).ready(function() {
            $('.level_id').change(function() {
                var _id = $(this).val();
                var _type = $('.type_course').val();
                if(_id !='' && _type != ''){
                    getSyllabus(_id,_type)
                }
            })
            $('.type_course').change(function() {
                var _type = $(this).val();
                var _id = $('.level_id').val();
                if(_id !='' && _type != ''){
                    getSyllabus(_id,_type)
                }
            })
            // var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';
            // $('.inp_hidden').on('change', function() {
            //     $(this).parents('.box_img_right').addClass('active');
            // });

            // $('.box_img_right').on('click', '.btn-remove', function() {
            //     let par = $(this).parents('.box_img_right');
            //     par.removeClass('active');
            //     par.find('img').attr('src', no_image_link);
            //     par.find('.inp_hidden').val("");
            // });
        });
        function getSyllabus(id,type){
            let url = "{{ route('syllabus_by_level') }}";
                let _targetHTML = $('.syllabus_avaible');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: id,
                        type: type,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            console.log(list);
                            let _item = '<option value="">@lang('Syllabus')</option>';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<option value="' + item.id + '">' + item
                                        .name + '</option>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<option value="">@lang('Syllabus')</option>');
                        }
                        _targetHTML.trigger('change');
                    },
                    error: function(response) {
                        // Get errors
                        // let errors = response.responseJSON.message;
                        // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
        }
    </script>
@endsection
