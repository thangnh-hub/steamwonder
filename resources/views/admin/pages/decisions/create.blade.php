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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST" id="form_product">
            @csrf
            <div class="row">
                <div class="col-lg-12">
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

                                    <a class="btn btn-success pull-right btn-sm"
                                        href="{{ route(Request::segment(2) . '.index') }}">
                                        <i class="fa fa-bars"></i> @lang('List')
                                    </a>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Học viên') <span class="text-danger">*</span></label>
                                                    <select name="json_params[student][id]" id="student_id" class="form-control select2" required>
                                                        <option value="">@lang('Please choose')</option>
                                                        @foreach ($students as $key => $student)
                                                            <option value="{{ $student->id }}">
                                                                {{ $student->admin_code . ' - ' . $student->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Nội dung') <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="code" name="code"
                                                        placeholder="@lang('Nội dung biến động')" value="" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Ngày biến động') <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" name="active_date" required
                                                        placeholder="@lang('Ngày biến động')" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Type') <span class="text-danger">*</span></label>
                                                    <select name="is_type" class=" form-control select2" required>
                                                        <option value="">@lang('Please choose')</option>
                                                        @foreach ($type as $key => $val)
                                                            <option value="{{ $key }}"
                                                                {{ isset($detail->is_type) && $detail->is_type == $val ? 'checked' : '' }}>
                                                                @lang($val)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Đơn biến động')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="decision" data-preview="decision-holder"
                                                                class="btn btn-primary file">
                                                                <i class="fa fa-file-pdf-o"></i> @lang('File ảnh hoặc PDF')
                                                            </a>
                                                        </span>
                                                        <input id="decision" class="form-control" type="text"
                                                            name="file_name" value="{{ $detail->file_name ?? '' }}"
                                                            placeholder="@lang('Link file')...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Note')</label>
                                                    <textarea name="note" class="form-control" rows="5">{{ old('note') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>

                        <div class="box-footer">
                            <div class="btn-set">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-xs-6">
                    <div class="box" style="border-top: 3px solid #d2d6de;">
                        <div class="box-header">
                            <h3 class="box-title">Danh sách học viên trong quyết định</h3>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">ID</th>
                                        <th class="col-md-5">Tên</th>
                                        <th class="col-md-2">Mã học viên</th>
                                        <th class="col-md-2">Bỏ chọn</th>
                                    </tr>
                                </thead>
                                <tbody id="student_decision">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="box" style="border-top: 3px solid #d2d6de;">
                        <div class="box-header">
                            <h3 class="box-title"></h3>
                            <div class="box-tools col-md-12">
                                <div class="col-md-6">
                                    <input type="text" id="search_student_code" class="form-control pull-right"
                                        placeholder="Mã học viên..." autocomplete="off">
                                </div>
                                <div class="input-group col-md-6">
                                    <input type="text" id="search_student_name" class="form-control pull-right"
                                        placeholder="Tên học viên..." autocomplete="off">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default btn_search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">ID</th>
                                        <th class="col-md-5">Tên</th>
                                        <th class="col-md-2">Mã học viên</th>
                                        <th class="col-md-2">Chọn</th>
                                    </tr>
                                </thead>
                                <tbody id="student_available">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
        </form>
    </section>

@endsection

@section('script')
    <script>
        $('#admin_code').on('blur', function() {
            var admin_code = $(this).val();
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: "{{ route('cms_student.search') }}/",
                data: {
                    admin_code: admin_code,
                },
                success: function(response) {
                    // console.log(response);
                    if (response.message === 'success') {
                        let list = response.data || null;
                        if (list.length > 0) {
                            $('#student_name').val(list[0].name);
                            $('#student_id').val(list[0].id);

                        }
                    } else {
                        $('#student_name').val(response.message);
                        $('#student_id').val('');
                    }
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    $('#student_name').val(errors);
                    $('#student_id').vale('');
                }
            });
        });

        $(document).ready(function() {
            // Fill Available Blocks by template
            $(document).on('click', '.btn_search', function() {
                let keyword = $('#search_student_name').val();
                let admin_code = $('#search_student_code').val();
                // let taxonomy_id = $('#search_taxonomy_id').val();
                let _targetHTML = $('#student_available');
                _targetHTML.html('');
                let checked_student = [];
                $('input[name="json_params[student][]"]:checked').each(function() {
                    checked_student.push($(this).val());
                });

                let url = "{{ route('student.search') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        admin_code: admin_code,
                        other_list: checked_student,

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
                                    _item += '<td>' + item.admin_code + '</td>';
                                    _item +=
                                        '<td><input name="json_params[student][]" type="checkbox" value="' +
                                        item.id +
                                        '" class="mr-15 student_item cursor" autocomplete="off"></td>';
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
            $(document).on('click', '.student_item', function() {
                let ischecked = $(this).is(':checked');
                let _root = $(this).closest('tr');
                let _targetHTML;

                if (ischecked) {
                    _targetHTML = $("#student_decision");
                } else {
                    _targetHTML = $("#student_available");
                }
                _targetHTML.append(_root);
            });

        });
    </script>
@endsection
