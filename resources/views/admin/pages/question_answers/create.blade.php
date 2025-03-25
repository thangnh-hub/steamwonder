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
            @if (Request::get('lang') != '' && Request::get('lang') != $item->lang_locale)
                <input type="hidden" name="lang" value="{{ Request::get('lang') }}">
            @endif
            <div class="row">
                <div class="col-lg-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
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
                                            <h5>Thông tin đăng nhập <span class="text-danger">*</span></h5>
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
                                                        placeholder="@lang('Email')" value="{{ old('email') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Password') <small class="text-red">*</small></label>
                                                    <input type="password" class="form-control" name="password"
                                                        placeholder="@lang('Password')" value="{{ old('password') }}"
                                                        required>
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Last name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[last_name]"
                                                        placeholder="@lang('Last name')" value="{{ old('json_params[last_name]') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Middle name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[middle_name]"
                                                        placeholder="@lang('Middle name')" value="{{ old('json_params[middle_name]') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('First name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[first_name]"
                                                        placeholder="@lang('First name')" value="{{ old('json_params[first_name]') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Phone') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="phone"
                                                        placeholder="@lang('Phone')" value="{{ old('phone') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Birthday') <small class="text-red">*</small></label>
                                                    <input type="date" class="form-control" name="birthday"
                                                        placeholder="@lang('Birthday')" value="{{ old('birthday') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Gender')</label>
                                                    <select name="gender" class=" form-control select2">
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
                                                    <label>@lang('Address') <small class="text-red">*</small></label>
                                                    <textarea name="json_params[address]" class="form-control"
                                                        rows="5">{{ old('json_params[address]') }}</textarea>
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
                                            <h5>Thông tin tuyển sinh<span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_3_1">
                                        <div class="d-flex-wap">
                                            @if ($lang != '')
                                                <input type="hidden" name="lang" value="{{ $lang }}">
                                            @endif
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Entry Level')</label>
                                                    <select name="json_params[entry_level_id]" class=" form-control select2">
                                                        @foreach ($entry_level as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->entry_level_id) && $detail->json_params->entry_level_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Major')</label>
                                                    <select name="json_params[major_id]" class=" form-control select2">
                                                        @foreach ($major as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->major_id) && $detail->json_params->major_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-4">
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
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Field')</label>
                                                    <select name="field_id[]" multiple="multiple" class="form-control select2">
                                                        @foreach ($field as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->json_params->name->$lang ?? $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Forms of training')</label>
                                                    <select name="json_params[forms_training]" class=" form-control select2">
                                                        @foreach ($forms_training as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->json_params->forms_training) && $detail->json_params->forms_training == $val ? 'selected' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Admissions')</label>
                                                    <select name="json_params[admission_id]" class=" form-control select2">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($admission as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->json_params->admission_id) && $detail->json_params->admission_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
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
                                            <h5>Thông tin gia đình <span class="text-danger">*</span></h5>
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
                                                    <label>@lang('Father full name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[dad_name]"
                                                        placeholder="@lang('Father full name')" value="{{ old('json_params[dad_name]') }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Father phone') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[dad_phone]"
                                                        placeholder="@lang('Father phone')" value="{{ old('json_params[dad_phone]') }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mother full name') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[mami_name]"
                                                        placeholder="@lang('Mother full name')" value="{{ old('json_params[mami_name]') }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Mother phone') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[mami_phone]"
                                                        placeholder="@lang('Mother phone')" value="{{ old('json_params[mami_phone]') }}"
                                                        >
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
                                            <h5>Thông tin giấy tờ tùy thân <span class="text-danger">*</span></h5>
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
                                                        placeholder="@lang('CCCD / CMT')" value="{{ old('json_params[cccd]') }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Date range') <small class="text-red">*</small></label>
                                                    <input type="date" class="form-control" name="json_params[date_range]"
                                                        placeholder="@lang('Date range')" value="{{ old('json_params[date_range]') }}"
                                                        >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Issued by') <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="json_params[issued_by]"
                                                        placeholder="@lang('Issued by')" value="{{ old('json_params[issued_by]') }}"
                                                        >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                        </div>
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
                            <h3 class="box-title">@lang('Role')</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label>@lang('Role') <small class="text-red">*</small></label>
                                <select name="role" id="role" class="form-control select2" required>
                                <option value="">@lang('Please select')</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                                </select>
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
                                            {{ isset($detail->status) && $detail->status == $val ? 'checked' : '' }}>
                                            @lang($val)</option>
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
                                    @foreach ($contract_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->contract_type) && $detail->json_params->contract_type == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Contract status')</label>
                                <select name="json_params[contract_status]" class=" form-control select2">
                                    @foreach ($contract_status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->contract_status) && $detail->json_params->contract_status == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>@lang('Contract performance status')</label>
                                <select name="json_params[contract_performance_status]" class=" form-control select2">
                                    @foreach ($contract_performance_status as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ isset($detail->json_params->contract_performance_status) && $detail->json_params->contract_performance_status == $val ? 'selected' : '' }}>
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
                                        placeholder="@lang('Image source')" value="{{ $detail->avatar ?? '' }}">
                                </div>
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
        CKEDITOR.replace('content_vi', ck_options);

        $(document).ready(function() {
            // Fill Available Blocks by template
            $(document).on('click', '.btn_search', function() {
                let keyword = $('#search_title_post').val();
                let taxonomy_id = $('#search_taxonomy_id').val();
                let _targetHTML = $('#post_available');
                _targetHTML.html('');
                let checked_post = [];
                $("input:checkbox:checked").each(function() {
                    checked_post.push($(this).val());
                });

                let url = "{{ route('cms_product.search') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        taxonomy_id: taxonomy_id,
                        other_list: checked_post,
                        is_type: "{{ App\Consts::TAXONOMY['product'] }}"
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr>';
                                    _item += '<td>' + item.id + '</td>';
                                    _item += '<td>' + item.name + '</td>';
                                    _item += '<td>' + item.is_type + '</td>';
                                    _item += '<td>' + formatDate(item.created_at) +
                                        '</td> ';
                                    _item +=
                                        '<td><input name="json_params[related_post][]" type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 related_post_item cursor" autocomplete="off"></td>';
                                    _item += '</tr>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<tr><td colspan="5">' + response.message +
                                '</td></tr>');
                        }
                    },
                    error: function(response) {
                        // Get errors
                        let errors = response.responseJSON.message;
                        _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            });

            // Checked and unchecked item event
            $(document).on('click', '.related_post_item', function() {
                let ischecked = $(this).is(':checked');
                let _root = $(this).closest('tr');
                let _targetHTML;

                if (ischecked) {
                    _targetHTML = $("#post_related");
                } else {
                    _targetHTML = $("#post_available");
                }
                _targetHTML.append(_root);
            });



            var no_image_link = '{{ url('themes/admin/img/no_image.jpg') }}';

            $('.add-gallery-image').click(function(event) {
                let keyRandom = new Date().getTime();
                let elementParent = $('.list-gallery-image');
                let elementAppend =
                    '<div class="col-lg-3 col-md-3 col-sm-4 mb-1 gallery-image my-15">';
                elementAppend += '<img class="img-width"';
                elementAppend += 'src="' + no_image_link + '">';
                elementAppend += '<input type="text" name="json_params[gallery_image][' + keyRandom +
                    ']" class="hidden" id="gallery_image_' + keyRandom +
                    '">';
                elementAppend += '<div class="btn-action">';
                elementAppend +=
                    '<span class="btn btn-sm btn-success btn-upload lfm mr-5" data-input="gallery_image_' +
                    keyRandom +
                    '" data-type="cms-image">';
                elementAppend += '<i class="fa fa-upload"></i>';
                elementAppend += '</span>';
                elementAppend += '<span class="btn btn-sm btn-danger btn-remove">';
                elementAppend += '<i class="fa fa-trash"></i>';
                elementAppend += '</span>';
                elementAppend += '</div>';
                elementParent.append(elementAppend);

                $('.lfm').filemanager('image', {
                    prefix: route_prefix
                });
            });


            // Change image for img tag gallery-image
            $('.list-gallery-image').on('change', 'input', function() {
                let _root = $(this).closest('.gallery-image');
                var img_path = $(this).val();
                _root.find('img').attr('src', img_path);
            });

            // Delete image
            $('.list-gallery-image').on('click', '.btn-remove', function() {
                // if (confirm("@lang('confirm_action')")) {
                let _root = $(this).closest('.gallery-image');
                _root.remove();
                // }
            });

            $('.list-gallery-image').on('mouseover', '.gallery-image', function(e) {
                $(this).find('.btn-action').show();
            });
            $('.list-gallery-image').on('mouseout', '.gallery-image', function(e) {
                $(this).find('.btn-action').hide();
            });


            $('.inp_hidden').on('change', function() {
                $(this).parents('.box_img_right').addClass('active');
            });

            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.input[type=hidden]').val("");
            });


            // Routes get all
            var routes = @json(App\Consts::ROUTE_NAME ?? []);
            $(document).on('change', '#route_name', function() {
                let _value = $(this).val();
                let _targetHTML = $('#template');
                let _list = filterArray(routes, 'name', _value);
                let _optionList = '<option value="">@lang('Please select')</option>';
                if (_list) {
                    _list.forEach(element => {
                        element.template.forEach(item => {
                            _optionList += '<option value="' + item.name + '"> ' + item
                                .title + ' </option>';
                        });
                    });
                    _targetHTML.html(_optionList);
                }
                $(".select2").select2();
            });

            //add space
            $('.add_space').on('click', function() {
                var _item =
                    "<input type='text' class='form-control form-group' name='json_product[space][]' placeholder='Nhập không gian' value=''>";
                $('.defautu_space').append(_item);
            });

            $('.add_convenient').on('click', function() {
                var _item = "";
                _item += "<div class='col-md-3 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][icon][]' placeholder='Icon' value=''>";
                _item += "</div>";
                _item += "<div class='col-md-9 form-group'>";
                _item +=
                    "<input type='text' class='form-control' name='json_product[convenient][name][]' placeholder='Nhập tiện nghi' value=''>";
                _item += "</div>";

                $('.defaunt_convenient').append(_item);
            });
            $('.ck_ty').on('change', function() {
                if ($("#form_product input[name='type']:checked").val() == 2) {
                    $('#type_price').attr("disabled", "true");
                } else {
                    $('#type_price').removeAttr('disabled');

                }

            });
        });
    </script>
@endsection
