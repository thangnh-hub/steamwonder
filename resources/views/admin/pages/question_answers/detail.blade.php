@extends('admin.layouts.app')
@push('style')
    <style>
        .box-header {
            color: #fff !important;
            background-color: #00A157 !important;
        }
    </style>
@endpush

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
            @isset($detail->classs)
            <div class="row">
                @foreach($detail->classs as $class)
                @php    
                    $evaluations =  \App\Models\Evaluation::where('student_id', $detail->id)->where('class_id', $class->id)->get();
                    $attendances =  \App\Models\Attendance::where('user_id', $detail->id)->where('class_id', $class->id)->get();
                    $count_late = $attendances->where('status', \App\Consts::ATTENDANCE_STATUS['late'])->count();
                    $count_absent = $attendances->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])->count();
                    $count_no_reason = $attendances->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])->where('json_params.value', \App\Consts::OPTION_ABSENT['no reason'])->count();
                    $count_there_reason = $attendances->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])->where('json_params.value', \App\Consts::OPTION_ABSENT['there reason'])->count();
                    $count_not_homework = $attendances->where('is_homework', 1)->count();
                    $count_missing_homework = $attendances->where('is_homework', 2)->count();
                    $scores =  \App\Models\Score::where('user_id', $detail->id)->where('class_id', $class->id)->get();
                @endphp
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Class'): {{ $class->name ?? '' }}</h3>
                            <div class="pull-right">
                                <h3 class="box-title">@lang('Student'): {{ $detail->name ?? '' }} ({{ $detail->admin_code }})</h3>
                            </div>
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
                                            <h5>Nhận xét đánh giá <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1_1">
                                        <div class="d-flex-wap">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Order')</th>
                                                        <th>@lang('Time evaluation')</th>
                                                        <th>@lang('Evaluation')</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($evaluations as $key => $item)
                                                        <tr class="valign-middle">
                                                            <td>
                                                                {{ $loop->index+1 }}
                                                            </td>
                                                            
                                                            <td>
                                                                <span>@lang('From'):</span> {{ optional(\Carbon\Carbon::parse($item->json_params->from_date ?? ''))->format('d/m/Y') }}<br>
                                                                <span>@lang('To'):</span> {{ optional(\Carbon\Carbon::parse($item->json_params->to_date ?? ''))->format('d/m/Y') }}
                                                            </td>
                                                            <td>
                                                                {{ $item->evaluation ??'' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
                                            <h5>Thống kê điểm danh<span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_2_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><strong>@lang('Late'): </strong></label>
                                                    <span>{{ $count_late ?? 0 }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label><strong>@lang('Absent'): </strong></label>
                                                    <span>{{ $count_absent ?? 0 }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><strong>@lang('Absent - there reason'): </strong></label>
                                                    <span>{{ $count_there_reason ?? 0 }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label><strong>@lang('Absent - no reason'): </strong></label>
                                                    <span>{{ $count_no_reason ?? 0 }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><strong>@lang('Not homework'): </strong></label>
                                                    <span>{{ $count_not_homework ?? 0 }}</span>
                                                </div>
                                                <div class="form-group">
                                                    <label><strong>@lang('Missing homework'): </strong></label>
                                                    <span>{{ $count_missing_homework ?? 0 }}</span>
                                                </div>
                                            </div>
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Order')</th>
                                                        <th>@lang('Schedule')</th>
                                                        <th>@lang('Home Work')</th>
                                                        <th>@lang('Status')</th>
                                                        <th>@lang('Note status')</th>
                                                        <th>@lang('Note')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($attendances as $key => $item)
                                                        <tr class="valign-middle">
                                                            <td>
                                                                {{ $loop->index+1 }}
                                                            </td>
                                                            <td>{{ optional(\Carbon\Carbon::parse($item->schedule->date))->format('l d/m/Y') }}</td>
                                                            <td>
                                                                @lang(\App\Consts::IS_HOMEWORK[$item->is_homework ?? 0])
                                                            </td>
                                                            <td>
                                                                @lang($item->status ?? '')
                                                            </td>
                                                            <td>
                                                                @lang($item->json_params->value ?? '')
                                                            </td>
                                                            <td>
                                                                {{ $item->note ?? '' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
                                            <h5>Thống kê bảng điểm<span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_3_1">
                                        <div class="d-flex-wap">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Order')</th>
                                                        <th>@lang('Score listen')</th>
                                                        <th>@lang('Score speak')</th>
                                                        <th>@lang('Score read')</th>
                                                        <th>@lang('Score write')</th>
                                                        <th>@lang('Average')</th>
                                                        <th>@lang('Evaluations')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($scores as $score)
                                                        <tr class="valign-middle">
                                                            <td>
                                                                {{ $loop->index+1 }}
                                                            </td>
                                                            <td>
                                                                {{ $score->score_listen ?? '0' }}đ
                                                            </td>
                                                            <td>
                                                                {{ $score->score_speak ?? '0' }}đ
                                                            </td>
                                                            <td>
                                                                {{ $score->score_read ?? '0' }}đ
                                                            </td>
                                                            <td>
                                                                {{ $score->score_write ?? '0' }}đ
                                                            </td>
                                                            <td>
                                                                {{ $score->json_params->score_verage ?? '0' }}đ
                                                            </td>
                                                            <td style="width: 300px;">
                                                                {{ $score->json_params->note ??'' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->
                            <!-- Custom Tabs -->
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endisset
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
                $('input[name="json_params[related_post][]"]:checked').each(function() {
                    checked_post.push($(this).val());
                });

                let url = "{{ route('cms_product.search') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        taxonomy_id: taxonomy_id,
                        other_list: checked_post,
                        different_id: {{ $detail->id }},
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
                elementAppend += '<img width="150px" height="150px" class="img-width"';
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

            $('.add_space').on('click', function() {
                var _item =
                    "<input type='text' class='form-control form-group ' name='json_product[space][]' placeholder='Nhập không gian' value=''>";
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
        });
    </script>
@endsection
