@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@push('style')
    <style>
        .student-list.active{
            background: #3c8dbc;
        }
        .student-list.active .box-header{
            color: #FFFFFF !important;
        }
        .pointer{
            cursor: pointer;
        }
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
                @foreach ($list_level as $item_level)
                    @php
                        $student_by_level = $list_student->filter(function ($item, $key) use ($item_level) {
                            return $item->level_id == $item_level->id && ($item->status_study !=10 && $item->status_study !=11) ;
                        });
                    @endphp
                    <div data-level-id="{{ $item_level->id }}" data-level-name="{{ $item_level->name }}" class="box box-solid collapsed-box pointer student-list">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-mortar-board"></i>
                                {{ $item_level->name ?? '' }}
                                ({{ isset($student_by_level)? count($student_by_level): '0' }} Học viên) 
                            </h3>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="title-list">Danh sách học viên theo trình độ</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 40px">#</th>
                                        <th>@lang('Student code')</th>
                                        <th>@lang('Name')</th>
                                        <th>@lang('Phone')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('Trạng thái học')</th>
                                        <th>@lang('Khu vực')</th>
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
        $(".student-list").on('click', function(e) {
            let _class = $(this);
            let level_id = _class.attr('data-level-id');
            let level_name = _class.attr('data-level-name');
            let _url = "{{ route('reports.student.levels') }}";
            let _html = $('#list_student_class');
            let _content = '';
            let _title_class = $('#title-list').text('Danh sách học viên ở trình độ ' + level_name);
            $(".student-list").removeClass('active');
            _class.toggleClass('active');
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "level_id": level_id
                },
                dataType: 'JSON',
                success: function(response) {
                    _list = response.data;
                    if (_list.length>0) {
                        let i = 1;
                        _list.forEach(it => {
                            if(it.status_study!=10 && it.status_study!=11 ){
                                _content += '<tr>';
                                _content += '<td>' + (i++) + '.</td>';
                                _content += '<td>';
                                _content +=
                                    '<a target="_blank" title="Xem chi tiết" href="/admin/students/' +
                                    it.id + '">';
                                _content += it.admin_code;
                                _content += '</a></td>';

                                _content += '<td>' + it.name + '</td>';
                                _content += '<td>' + (it.phone !== null ? it
                                    .phone : 'Chưa cập nhật') + '</td>';
                                _content += '<td>' + it.email + '</td>';
                                _content += '<td>' + (it.status_study_name !== null ? it
                                    .status_study_name : 'Chưa cập nhật') + '</td>';
                                _content += '<td>' + (it.area_name !== null ? it
                                    .area_name : 'Chưa cập nhật') + '</td>';    
                                _content += '</tr>';
                            }
                            
                        });
                        _html.html(_content);
                    }else{
                        _content=`<tr>
                                    <td colspan='7'>Không có bản ghi</td>
                                </tr>`;
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
