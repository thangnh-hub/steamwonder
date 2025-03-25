@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .box {
            border-top: 3px solid;
            border-bottom: 1px solid #CDCDCD;
            border-right: 1px solid #CDCDCD;
            border-left: 1px solid #CDCDCD;
            box-shadow: none;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>thead>tr>td {
            border-bottom-width: 1px;
        }

        .table>thead>tr>th {
            font: bold 14px/28px "Source Sans Pro";
            text-align: center;
            align-content: center;
        }

        .table>tbody>tr>td {
            text-align: center
        }

        .table>thead>tr {
            background-color: #3c8dbc;
            color: #FFFFFF;

        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
        </h1>
    </section>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-body">
                        @if (session('errorMessage'))
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                {!! session('errorMessage') !!}
                            </div>
                        @endif
                        @if (session('successMessage'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {!! session('successMessage') !!}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach

                            </div>
                        @endif
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <form role="form" action="{{ route('book_distribution.active') }}" method="POST"
                                onsubmit="return confirm('@lang('confirm_action')')">
                                @csrf
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Đề xuất cấp phát sách <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="row" style="margin-top: 20px">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Cơ sở xuất')<small class="text-red">*</small></label>
                                                    <select class="area_id form-control select2" required>
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($areas as $key => $val)
                                                            <option value="{{ $val->id }}">
                                                                @lang($val->name ?? '')</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Kho xuất')<small class="text-red">*</small></label>
                                                    <select required name="warehouse_id_deliver"
                                                        class="warehouse_avaible form-control select2">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Người nhận')<small class="text-red">*</small></label>
                                                    {{-- <select name="staff_entry" class="form-control select2" required>
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($staff_request as $key => $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select> --}}
                                                    <input type="text" class="form-control"
                                                        name="json_params[staff_entry]" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Ngày nhận sách') </label>
                                                    <input required type="date" class="form-control" name="day_deliver"
                                                        value="{{ $detail->day_create ?? date('Y-m-d', time()) }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Ghi chú')</label>
                                                    <textarea name="json_params[note]" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row view_book_distribution">
                                            {{-- <div class="col-md-4" style="position: sticky; top: 0px">
                                                <div class="box box-primary box_detail">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title" id="title-list">@lang('Danh sách lớp học đủ điều kiện')</h3>
                                                    </div>
                                                    <div class="table-responsive mailbox-messages table_scroll">
                                                        <table class="table table-bordered table_scroll">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('Tên lớp')</th>
                                                                    <th>@lang('Khu vực')</th>
                                                                    <th>@lang('Giáo viên')</th>
                                                                    <th>@lang('Chọn')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @isset($classs)
                                                                    @foreach ($classs as $class)
                                                                        @php
                                                                            $teacher = \App\Models\Teacher::where(
                                                                                'id',
                                                                                $class->json_params->teacher ?? 0,
                                                                            )->first();
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $class->name ?? '' }}</td>
                                                                            <td>{{ $class->area->name ?? '' }} </td>
                                                                            <td>{{ $teacher->name ?? '' }} </td>
                                                                            <td> <input type="checkbox"
                                                                                    class="active_class class_{{ $class->id }}"
                                                                                    name="class[]" value="{{ $class->id }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="col-md-12">
                                                <div class="box box-primary box_detail">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title" id="title-list">@lang('Danh sách học viên đủ điều kiện')</h3>
                                                        <div class="pull-right" style="width: 50%">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <select onchange="search_js(this,'tr_filter')"
                                                                        class="form-control select2 search_course"
                                                                        style="width: 100%">
                                                                        <option value="">@lang('Chọn khóa học')</option>
                                                                        @foreach ($course as $val)
                                                                            <option value="{{ $val->name }}">
                                                                                {{ $val->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <select onchange="search_js(this,'tr_filter')"
                                                                        class="form-control select2 search_class"
                                                                        style="width: 100%">
                                                                        <option value="">@lang('Chọn lớp học')</option>
                                                                        @foreach ($classs as $class)
                                                                            <option value="{{ $class->name }}">
                                                                                {{ $class->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4"><input type="text"
                                                                        class="form-control search_name"
                                                                        onkeyup="search_js(this,'tr_filter')"
                                                                        placeholder="Họ tên hoặc Mã học viên"></div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive mailbox-messages table_scroll">
                                                        <table class="table table-bordered table_scroll">
                                                            <thead>
                                                                <tr>

                                                                    <th>@lang('STT')</th>
                                                                    <th>@lang('Mã HV')</th>
                                                                    <th>@lang('Họ tên')</th>
                                                                    <th>@lang('Khóa học')</th>
                                                                    <th>@lang('Lớp')</th>
                                                                    <th>@lang('Trình độ')</th>
                                                                    <th>@lang('Tên sách')</th>
                                                                    <th>@lang('Cấp sách')</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table_detail">
                                                                @isset($students)
                                                                    @foreach ($students as $val)
                                                                        <tr class="tr_filter">
                                                                            <td>{{ $loop->index + 1 }}</td>
                                                                            <td class="student_code">
                                                                                {{ $val->student->admin_code }}</td>
                                                                            <td class="student_name">
                                                                                {{ $val->student->name ?? '' }}</td>
                                                                            <td class="course_name">
                                                                                {{ $val->student->course->name ?? '' }}</td>
                                                                            <td class="class_name">
                                                                                {{ $val->class->name ?? '' }}</td>
                                                                            <td>{{ $val->level->name ?? '' }}</td>
                                                                            <td>{{ $val->product->name ?? '' }}</td>
                                                                            <td><input type="checkbox"
                                                                                    class="active_book book_class_{{ $val->class_id }}"
                                                                                    data-class="{{ $val->class_id }}"
                                                                                    name="book[]"
                                                                                    value="{{ $val->id . '-' . $val->product_id . '-' . $val->class_id }}">
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info btn-sm pull-right">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('script')
    <script>
        $('.area_id').change(function() {
            var _id = $(this).val();
            let url = "{{ route('warehouse_by_area') }}";
            let _targetHTML = $('.warehouse_avaible');
            getViewClassAndStudent(_id);
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    id: _id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '<option value="">@lang('Please select')</option>';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<option value="' + item.id + '">' + item
                                    .name + '</option>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<option value="">@lang('Please select')</option>');
                    }
                    _targetHTML.trigger('change');
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });

        })
        // $(document).on('click', '.active_class', function() {
        //     var _class = $(this).val();
        //     $('.book_class_' + _class).prop('checked', $(this).is(':checked'));
        // })
        // $(document).on('click', '.active_book', function() {
        //     var _class = $(this).data('class');
        //     if ($('.book_class_' + _class + ':checked').length == $('.book_class_' + _class).length) {
        //         $('.class_' + _class).prop('checked', true);
        //     } else {
        //         $('.class_' + _class).prop('checked', false);
        //     }
        // });





        function getViewClassAndStudent(_id) {
            let url = "{{ route('book_distribution.get_view_book_distribution') }}";
            let _targetHTML = $('.view_book_distribution');
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    class_id: _id,
                },
                success: function(response) {
                    _targetHTML.html(response.data.view);
                    $('.select2').select2();
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        function search_js(t, cl_filter) {
            var value_course = remove_accent($(".search_course").val().toLowerCase());
            var value_class = remove_accent($(".search_class").val().toLowerCase());
            var value_name = remove_accent($(".search_name").val().toLowerCase());
            $('.active_book').attr('checked', false);

            $("." + cl_filter).each(function() {
                var matches_course = false;
                var matches_class = false;
                var matches_name = false;
                // Kiểm tra cột mã khóa học
                var course_column = $(this).find(".course_name");
                if (course_column.length) {
                    var text_course = remove_accent(course_column.text().toLowerCase());
                    matches_course = text_course.indexOf(value_course) > -1;
                }
                // Kiểm tra cột lớp học
                var class_column = $(this).find(".class_name");
                if (class_column.length) {
                    var text_class = remove_accent(class_column.text().toLowerCase());
                    matches_class = text_class.indexOf(value_class) > -1;
                }
                // Kiểm tra cột tên học viên, hoặc mã học viên
                var name_column = $(this).find(".student_name");
                var code_column = $(this).find(".student_code");
                if (name_column.length || code_column.length) {
                    var text_name = name_column.length ? remove_accent(name_column.text().toLowerCase()) : '';
                    var text_code = code_column.length ? remove_accent(code_column.text().toLowerCase()) : '';
                    // Kiểm tra giá trị nhập có khớp tên hoặc mã
                    matches_name = text_name.indexOf(value_name) > -1 || text_code.indexOf(value_name) > -1;
                }
                // Hiển thị và gán checked cho checkbox
                var matches_all = matches_name && matches_course && matches_class;
                $(this).toggle(matches_all);
                // Nếu dòng hiển thị, đánh dấu checkbox là checked, ngược lại bỏ checked
                var checkbox = $(this).find('.active_book');
                if (checkbox.length) {
                    checkbox.prop('checked', matches_all);
                }
                // Hiển thị nếu thỏa mãn điều kiện khớp
                // $(this).toggle(matches_name && matches_course && matches_class).find('.active_book').attr('checked',true);
            });
        }

        function remove_accent(str) {
            return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        }
    </script>
@endsection
