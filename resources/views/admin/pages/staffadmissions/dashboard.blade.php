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
@push('style')
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
            font: normal 14px/28px "RobotoCondensed-Bold";
        }

        .table>thead>tr {
            background-color: #3c8dbc;
            color: #FFFFFF;
        }
    </style>

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/admin/plugins/datatables/dataTables.bootstrap.css') }}">
@endpush
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('staffadmissions.create') }}"><i
                    class="fa fa-plus"></i> @lang('Thêm mới CBTS')</a>
            {{-- <a class="btn btn-sm btn-primary pull-right mr-10" href="{{ route('student.excel.import') }}"><i
                    class="fa fa-upload"></i> @lang('Import học viên')</a> --}}
            <a class="btn btn-sm btn-info pull-right mr-10" href="{{ route('students.create') }}"><i class="fa fa-plus"></i>
                @lang('Thêm mới học viên')</a>
        </h1>
    </section>
@endsection

@section('content')
    <!-- Style content -->
    <style>
        :root {
            --width: 36;
            --rounding: 4px;
            --accent: #696;
            --dark-grey: #ddd;
            --grey: #eee;
            --light-grey: #f8f8f8;
        }

        html .tree {
            font-weight: 300;
            font-size: clamp(18px, 100vw / var(--width), 15px);
            font-feature-settings: 'onum', 'pnum';
            line-height: 1.5;
            -webkit-text-size-adjust: none;
        }

        .tree {
            --spacing: 3rem;
            --radius: 10px;
            margin: 0px;
            padding: 0px;
        }

        .tree li {
            display: block;
            position: relative;
            padding-left: calc(2 * var(--spacing) - var(--radius) - 2px);
        }

        .tree ul {
            margin-left: calc(var(--radius) - var(--spacing));
            padding-left: 0;
        }

        .tree ul li {
            border-left: 2px solid #ddd;
        }

        .tree ul li:last-child {
            border-color: transparent;
        }

        .tree ul li::before {
            content: '';
            display: block;
            position: absolute;
            top: calc(var(--spacing) / -2);
            left: -2px;
            width: calc(var(--spacing) + 2px);
            height: calc(var(--spacing) + 1px);
            border: solid #ddd;
            border-width: 0 0 2px 2px;
        }

        .tree summary {
            display: block;
            cursor: pointer;
            padding-bottom: 10px;
        }

        .tree summary::marker,
        .tree summary::-webkit-details-marker {
            display: none;
        }

        .tree summary:focus {
            outline: none;
        }

        .tree summary:focus-visible {
            outline: 1px dotted #000;
        }

        .tree li::after,
        .tree summary::before {
            content: '';
            display: block;
            position: absolute;
            top: calc(var(--spacing) / 2 - var(--radius));
            left: calc(var(--spacing) - var(--radius) - 1px);
            width: calc(2 * var(--radius));
            height: calc(2 * var(--radius));
            border-radius: 50%;
            background: #ddd;
        }

        .tree summary::before {
            z-index: 1;
            background: #696 url({{ asset('themes/admin/expand-collapse.svg') }}) 0 0;
        }

        .tree details[open]>summary::before {
            background-position: calc(-2 * var(--radius)) 0;
        }

        .tree .table {
            margin-bottom: 10px;
        }
        .list_study.active{
            color: #dd4b39 ;
        }
    </style>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-body table-responsive row">
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

                <div class="col-md-6">
                    <ul class="tree">
                        <li>
                            <details open>
                                <summary>{{ $detail->admin_code}}-{{ $detail->name ?? '' }} (Tổng: {{ count($rows_permission) }} Cán bộ cấp dưới -
                                    {{ count($rows_student) }} Học viên)</summary>
                                <ul>
                                    @if (isset($student_childs) && count($student_childs) > 0)
                                        <li>
                                            <details>
                                                <summary class="list_study" data-id = "{{ $detail->id }}">
                                                    @lang('Danh sách học viên tuyển sinh trực tiếp') ({{ $student_childs->count() }} Học viên)
                                                </summary>
                                            </details>
                                        </li>
                                    @endif
                                    {!! $view !!}
                                </ul>
                            </details>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="title-list">@lang('Danh sách học viên')</h3>
                            <div class="row ">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Keyword') </label>
                                        <input type="text" class="form-control" id="keyword"
                                            placeholder="@lang('Họ tên hoặc mã học viên')" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Class')</label>
                                        <select id="class_id" class="form-control select2" style="width: 100%;">
                                            <option value="">@lang('Please select')</option>
                                            @foreach ($class as $key => $value)
                                                <option value="{{ $value->id }}">
                                                    {{ __($value->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>@lang('Filter')</label>
                                        <div>
                                            <button type="button" class="btn btn-primary btn-sm mr-10 filter_student"
                                                data-id = "{{ $admin->id ?? '' }}">@lang('Submit')</button>
                                            <button class="btn btn-default btn-sm reset_student"
                                                data-id = "{{ $admin->id ?? '' }}">
                                                @lang('Reset') </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="box_list_student">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/admin/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(function() {
            $("#main_list").DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": false,
                "autoWidth": false,
                "language": {
                    "search": "Tìm kiếm:",
                    "sSearchPlaceholder": "Thông tin học viên..."
                },

            });
            $('.list_study').click(function() {
                var id = $(this).data('id');
                $('.list_study').removeClass('active');
                $(this).addClass('active');
                $('#keyword').val("");
                $('#class_id').val("");
                get_students(id);
            });
        });

        function get_students(id, page = 1, keyword = "", class_id = "") {
            var url = "{{ route('student.admissions') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: id,
                    page: page,
                    keyword: keyword,
                    class_id: class_id,
                },
                success: function(response) {
                    $('.filter_student').attr('data-id', id);
                    $('.reset_student').attr('data-id', id);
                    $('#box_list_student').html(response);
                    attachFilterStudentClickEvent();
                    attachResetStudentClickEvent();
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    alert(errors);
                }
            });
        }

        function attachFilterStudentClickEvent() {
            $(document).off('click', '.filter_student'); // Ensure to remove previous handlers
            $(document).on('click', '.filter_student', function(e) {
                var id = $(this).data('id');
                if (id != null && id != "") {
                    var keyword = $('#keyword').val();
                    var class_id = $('#class_id').val();
                    get_students(id, 1, keyword, class_id);
                }
            });
        }

        function attachResetStudentClickEvent() {
            $(document).off('click', '.reset_student'); // Ensure to remove previous handlers
            $(document).on('click', '.reset_student', function(e) {
                var id = $(this).data('id');
                if (id != null && id != "") {
                    $('#keyword').val("");
                    $('#class_id').val("");
                    get_students(id, 1);
                    $('.select2').select2()
                }
            });
        }
    </script>
@endsection
