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

        .mr-2 {
            margin-left: 1em !important;
        }

        ul.nav-stacked {
            padding-left: 1.5em;
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
                    @php
                        $class_area = $list_class->filter(function ($item, $key) use ($item_area) {
                            return $item->area_id == $item_area->id;
                        });

                    @endphp
                    <div class="box box-solid {{ count($class_area) > 0 ? 'collapsed-box' : '' }}">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-building"></i>
                                {{ $item_area->name ?? '' }}
                                ({{ count($class_area) }} lớp - {{ $item_area->total_student }} học
                                viên)
                            </h3>
                            @if (count($class_area) > 0)
                                <div class="box-tools">
                                    <button class="btn btn-box-tool" data-widget="collapse">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        @if (count($class_area) > 0)
                            <div class="box-body no-padding">
                                @foreach ($status_class as $keys => $val)
                                    @php
                                        $class_status = $class_area->filter(function ($item, $key) use ($keys) {
                                            return $item->status == $keys;
                                        });
                                    @endphp
                                    <div class="box box-solid collapsed-box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title mr-2">
                                                {{ $val }}
                                                ({{ count($class_status) }} lớp)
                                            </h3>
                                            @if (count($class_status) > 0)
                                                <div class="box-tools">
                                                    <button class="btn btn-box-tool" data-widget="collapse">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="box-body no-padding">
                                            <ul class="nav nav-pills nav-stacked">
                                                @foreach ($class_status as $item_class)
                                                    <li class="student-class-list"
                                                        data-class-name="{{ $item_class->name ?? '' }}"
                                                        data-class-id="{{ $item_class->id ?? '' }}">
                                                        <a href="javascript:void(0)">
                                                            <i class="fa fa-circle-o text-red"></i>
                                                            @lang('Lớp'): {{ $item_class->name ?? '' }} -
                                                            {{ $item_class->status }}
                                                            <span class="label label-primary pull-right">
                                                                {{ $item_class->total_student ?? '' }}
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="title-list">Danh sách học viên theo lớp</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">#</th>
                                        <th>@lang('Student code')</th>
                                        <th>@lang('Name')</th>
                                        {{-- <th>@lang('Phone')</th>
                                        <th>@lang('Email')</th> --}}
                                        <th>@lang('Status')</th>
                                    </tr>
                                </thead>
                                <tbody id="list_student_class">
                                </tbody>
                            </table>
                        </div>
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
            let class_id = _class.attr('data-class-id');
            let class_name = _class.attr('data-class-name');
            let _url = "{{ route('staffadmissions.area') }}";
            let _html = $('#list_student_class');
            let _content = '';
            let _title_class = $('#title-list').text('Danh sách học viên Lớp ' + class_name);

            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "class_id": class_id
                },
                dataType: 'JSON',
                success: function(response) {
                    _list = response.data;
                    if (_list) {
                        let i = 1;
                        _list.forEach(item => {
                            _content += '<tr>';
                            _content += '<td>' + (i++) + '.</td>';
                            _content += '<td>';
                            _content +=
                                '<a target="_blank" title="Xem chi tiết" href="/admin/students/' +
                                item.id + '">';
                            _content += item.admin_code;
                            _content += '</a></td>';

                            _content += '<td>' + item.name + '</td>';
                            // _content += '<td>' + (item.phone !== null ? item
                            //     .phone : 'Chưa cập nhật') + '</td>';
                            // _content += '<td>' + item.email + '</td>';
                            _content += '<td>' + (item.status_study_name !== null ? item
                                .status_study_name : 'Chưa cập nhật') + '</td>';
                            _content += '</tr>';
                        });
                        _html.html(_content);
                    }
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.errors;
                    // Foreach and show errors to html
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = "@lang('CSRF token mismatch.')";
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                }
            });
        });
    </script>
@endsection
