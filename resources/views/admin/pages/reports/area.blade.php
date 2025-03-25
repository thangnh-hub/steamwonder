@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

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
            font: bold 14px/28px "Source Sans Pro";
        }

        .table>thead>tr {
            background-color: #3c8dbc;
            color: #FFFFFF;
        }

        ul.nav-stacked {
            max-height: 500px;
            overflow: auto;
        }

        #pagination {
            display: flex;
            justify-content: center;
        }
    </style>
@endpush
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
            <div class="col-md-4">
                @foreach ($list_area as $item_area)
                    @if (isset($list_student) && count($list_student) > 0)
                        @php
                            $student_by_area = $list_student->filter(function ($item, $key) use ($item_area) {
                                return $item->area_id == $item_area->id;
                            });
                        @endphp
                    @endif
                    <div class="box box-solid collapsed-box">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-building"></i>
                                {{ $item_area->name ?? '' }}
                                ({{ isset($student_by_area) ? count($student_by_area) : '0' }} Học viên)
                            </h3>
                            <div class="box-tools">
                                <button class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>

                                </button>
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                @foreach ($list_status as $status)
                                    @if (isset($list_student) && count($list_student) > 0)
                                        @php
                                            $student_by_status = $list_student->filter(function ($item, $key) use (
                                                $status,
                                                $item_area                                            ) {
                                                return $item->area_id == $item_area->id &&
                                                    $item->status_study == $status->id;
                                            });
                                            $student_status_null = $list_student->filter(function ($item, $key) use (
                                                $status,
                                                $item_area                                            ) {
                                                return $item->area_id == $item_area->id && $item->status_study == null;
                                            });
                                        @endphp
                                    @endif
                                    <li class="student-class-list" data-area-id="{{ $item_area->id }}"
                                        data-class-name="{{ $status->name ?? '' }}" data-class-id="{{ $status->id ?? '' }}">
                                        <a href="javascript:void(0)">
                                            <i class="fa fa-circle-o text-red"></i>
                                            {{ $status->name ?? '' }}
                                            <span class="label label-primary pull-right">
                                                {{ isset($student_by_status) ? count($student_by_status) : '0' }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                                <li class="student-class-list" data-area-id="{{ $item_area->id }}"
                                    data-class-name="Chưa cập nhật" data-class-id="0">
                                    <a href="javascript:void(0)">
                                        <i class="fa fa-circle-o text-red"></i>
                                        Chưa cập nhật
                                        <span class="label label-primary pull-right">
                                            {{ isset($student_status_null) ? count($student_status_null) : '0' }}
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                @endforeach
            </div>
            <div class="col-md-8">
                <div class="box box-primary ">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="title-list">Danh sách học viên theo trạng thái</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mailbox-messages box_view">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">#</th>
                                        <th>@lang('Student code')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('CCCD')</th>
                                        <th>@lang('Phone')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('Lớp')</th>
                                    </tr>
                                </thead>
                                <tbody id="list_student_class">

                                </tbody>
                            </table>
                        </div>
                        <div id="pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(".student-class-list").on('click', function(e) {
            let _class = $(this);
            let status_study = _class.attr('data-class-id');
            let area_id = _class.attr('data-area-id');

            let class_name = _class.attr('data-class-name');
            let _url = "{{ route('reports.student.status') }}";
            // let _content = '';
            let _title_class = $('#title-list').text('Danh sách học viên ở trạng thái ' + class_name);
            $(".student-class-list").removeClass('active');
            _class.toggleClass('active');
            get_view_student(_url,status_study,area_id,'','');
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let pageUrl = $(this).attr('href');
            var status_study = $(".student-class-list.active").attr('data-class-id');
            var area_id = $(".student-class-list.active").attr('data-area-id');;
            var keyword = $('.keyword').val();
            var class_id = $('.class_id').val();
            get_view_student(pageUrl,status_study,area_id,keyword,class_id);

        });
        $(document).on('click', '.btn_filter', function(e) {
            e.preventDefault();
            let pageUrl = "{{ route('reports.student.status') }}";
            var status_study = $(".student-class-list.active").attr('data-class-id');
            var area_id = $(".student-class-list.active").attr('data-area-id');;
            var keyword = $('.keyword').val();
            var class_id = $('.class_id').val();
            get_view_student(pageUrl,status_study,area_id,keyword,class_id);

        });

        function get_view_student(url, status_study, area_id, keyword, class_id) {
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    "status_study": status_study,
                    "area_id": area_id,
                    "keyword": keyword,
                    "class_id": class_id,
                },
                dataType: 'JSON',
                success: function(response) {
                    $('.box_view').html(response.data.html);
                    $('#pagination').html(response.data.pagination);
                    $(".select2").select2();
                },
                error: function(error) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.errors;
                    alert(errors);
                }
            });
        }
    </script>
@endsection
