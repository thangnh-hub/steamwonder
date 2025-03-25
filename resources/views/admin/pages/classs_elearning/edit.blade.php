@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .day-repeat-select{
            pointer-events: none;
        }
        .modal-header {
            display: flex;
            align-items: center;
            color: #fff;
            background-color: #00A157;
        }

        .pointer-none {
            pointer-events: none;
            background: #eee;
        }

        .link_doc a {
            text-decoration: underline !important;
        }

        .bg-highlight {
            background: #367fa9;
            color: #fff !important;
        }

        .mr-2 {
            margin-right: 10px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .table_leson .select2-container {
            width: 100% !important;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .overflow-auto {
            width: 100%;
            overflow-x: auto;
        }

        .overflow-auto::-webkit-scrollbar {
            width: 5px !important;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: rgb(107, 144, 218);
            border-radius: 10px;
        }

        .table_leson {
            width: 1600px;
            max-width: unset;
        }

        .table_leson td:first-child {
            width: 190px;
        }

        .table_leson thead {
            background: rgb(107, 144, 218);
            color: #fff
        }
    </style>
@endsection

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
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5>Danh sách học viên</h5>
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
                                                    <input data-id="{{ $detail->id }}" type="text" class="form-control" id="class_name" name="name"
                                                        placeholder="@lang('Title')"
                                                        value="{{ old('name') ?? $detail->name }}" required>
                                                        <p class="check-error text-danger"></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Trạng thái') <small class="text-red">*</small></label>
                                                    <select class="form-control select2" name="status"
                                                        required>
                                                        @foreach ($status_class as  $key=> $val)
                                                            <option {{ isset($detail->status) && $detail->status == $key ? 'selected' : '' }} value="{{ $key }}">
                                                                {{ $val}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                           

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Class Type') <small class="text-red">*</small></label>
                                                    <select disabled="" class="form-control select2">
                                                        @foreach (App\Consts::CLASS_TYPE as $key => $val)
                                                            <option
                                                                {{ isset($detail->type) && $detail->type == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Area')<small class="text-red">*</small></label>
                                                    <select disabled name="area_id" class=" form-control select2 area_id">
                                                        <option value="">@lang('Area')</option>
                                                        @foreach ($area as $val)
                                                            @if (in_array($val->id, $area_user))
                                                            <option
                                                                {{ isset($detail->area_id) && $detail->area_id == $val->id ? 'selected' : '' }}
                                                                value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                                @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Level')<small class="text-red">*</small></label>
                                                    <select disabled="" class=" form-control select2">
                                                        <option value="">@lang('Level')</option>
                                                        @foreach ($levels as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->level_id) && $detail->level_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Syllabus')<small class="text-red">*</small></label>
                                                    <select disabled="" class=" form-control select2">
                                                        <option value="">@lang('Syllabus')</option>
                                                        @foreach ($syllabus as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->syllabus_id) && $detail->syllabus_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Course')<small class="text-red">*</small></label>
                                                    <select disabled="" class=" form-control select2">
                                                        <option value="">@lang('Course')</option>
                                                        @foreach ($course as $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ isset($detail->course_id) && $detail->course_id == $val->id ? 'selected' : '' }}>
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane " id="tab_3">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title">@lang('Danh sách học viên')</h3>
                                                    </div><!-- /.box-header -->
                                                    {{-- phần ds --}}
                                                    <div class="box-body table-responsive no-padding">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Mã Học Viên</th>
                                                                    <th>Tên</th>
                                                                    <th>Ngày sinh</th>
                                                                    <th>CCCD</th>
                                                                    <th>Cơ sở</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Chọn ngày vào lớp</th>
                                                                    <th>Bỏ chọn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="post_related">
                                                                @isset($student)
                                                                    @foreach ($student as $item)
                                                                        <tr>
                                                                            <td>{{ $item->user->admin_code ?? '' }}</td>
                                                                            <td>{{ $item->user->name ?? '' }}</td>
                                                                            <td>{{ $item->user->birthday!="" ? date('d-m-Y',strtotime($item->user->birthday)): '' }}</td>
                                                                            <td>{{ $item->user->json_params->cccd ?? '' }}</td>
                                                                            <td>{{ $item->area_name ?? '' }}</td>
                                                                            <td>
                                                                                <select class="form-control"
                                                                                    name="user_class_status[]" id="">
                                                                                    @foreach (App\Consts::USER_CLASS_STATUS as $k => $us_status)
                                                                                        <option
                                                                                            {{ isset($item->status) && $item->status == $k ? 'selected' : '' }}
                                                                                            value="{{ $k }}">
                                                                                            {{ $us_status }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control" type="date" name="day_in_class[]" value="{{ $item->json_params->day_in_class??date('Y-m-d',strtotime($item->created_at ))}}">
                                                                            </td>
                                                                            <td>
                                                                                <input name="student[]" type="checkbox"
                                                                                    value="{{ $item->user->id ?? '' }}"
                                                                                    class="mr-15 related_post_item cursor"
                                                                                    autocomplete="off" checked>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach
                                                                @endisset
                                                            </tbody>
                                                        </table>
                                                    </div><!-- /.box-body -->
                                                </div><!-- /.box -->
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <br>
                                                    <h4 class="box-title">Thêm học viên vào lớp</h4>
                                                    <br>
                                                    <div class="box-header">
                                                        <h3 class="box-title"></h3>
                                                        <div class="box-tools col-md-12">
                                                            <div class="col-md-4">
                                                                <select style="width:100%" class="form-control select2" name=""
                                                                    id="search_class_student">
                                                                    <option value="">Lớp...</option>
                                                                    @foreach ($list_class as $clas)
                                                                        <option value="{{ $clas->id }}">
                                                                            {{ $clas->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" id="search_title_post"
                                                                    class="form-control pull-right"
                                                                    placeholder="Tên học viên, CCCD..." autocomplete="off">

                                                            </div>

                                                            <div class="input-group col-md-4">
                                                                <input type="text" id="search_code_post"
                                                                    class="form-control pull-right"
                                                                    placeholder="Mã học viên..." autocomplete="off">
                                                                <div class="input-group-btn">
                                                                    <button type="button"
                                                                        class="btn btn-default btn_search">
                                                                        <i class="fa fa-search"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- /.box-header -->
                                                    <div class="box-body table-responsive no-padding">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th >Mã học viên</th>
                                                                    <th>Tên</th>
                                                                    <th>Ngày sinh</th>
                                                                    <th>CCCD</th>
                                                                    <th>Cơ sở</th>
                                                                    <th>Trạng thái</th>
                                                                    <th>Ngày vào lớp</th>
                                                                    <th>Chọn</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="post_available">

                                                            </tbody>
                                                        </table>
                                                    </div><!-- /.box-body -->
                                                </div><!-- /.box -->
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane" id="tab_4">
                                        <a href="{{ route('schedule_class.index', ['class_id' => $detail->id]) }}">@lang('Xem thông tin điểm danh')
                                            - {{ $detail->name }}</a>
                                    </div>
                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12" id="exampleModalLabel">{{ __('Take attendance') }}
                        </h3>

                    </div>
                    <div class="modal-body">
                        <form action="{{ route('attendances.save') }}" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            @csrf
                            <div class="overflow-auto mt-15 mb-15">

                                <table class="table table-hover table-bordered table_leson">
                                    <thead>
                                        <tr>
                                            <th>@lang('Order')</th>
                                            <th>@lang('Class')</th>
                                            <th>@lang('Student')</th>
                                            <th>@lang('Avatar')</th>
                                            <th>@lang('Home Work')</th>
                                            <th>@lang('Updated at')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Note status')</th>
                                            {{-- <th>@lang('Score')</th> --}}
                                            <th>@lang('Note')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="show_attendance">

                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-info">
                                <i class="fa fa-save"></i> @lang('Save')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $('#class_name').on('blur', function () {
            var ten = $(this).val();
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: '{{ route('ajax.nameclass.unique') }}',
                data: {ten: ten, id: id},
                success: function (response) {
                    console.log(response);
                    if (response.data == true) {
                        $('.check-error').text('Tên lớp đã tồn tại. Vui lòng chọn tên khác.');
                    }else $('.check-error').text('');
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        });
     
       
        $(document).ready(function() {

            // Fill Available Blocks by template
            $(document).on('click', '.btn_search', function() {
                let keyword = $('#search_title_post').val();
                let taxonomy_id = $('#search_code_post').val();
                let class_student = $('#search_class_student').val();
                let _targetHTML = $('#post_available');
                var currentDate = new Date();
                var formattedDate = currentDate.toISOString().substr(0, 10);
                _targetHTML.html('');
                let checked_post = [];
                $('input[name="student[]"]:checked').each(function() {
                    checked_post.push($(this).val());
                });

                let url = "{{ route('cms_student.search') }}/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        keyword: keyword,
                        admin_code: taxonomy_id,
                        other_list: checked_post,
                        class_id: class_student,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data || null;
                            console.log(list);
                            let _item = '';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<tr>';
                                    _item += '<td>' + item.admin_code + '</td>';
                                    _item += '<td>' + item.name + '</td>';
                                    _item += '<td>' + item.birthday + '</td>';
                                    _item += '<td>' + item.json_params.cccd + '</td>';
                                    _item += '<td>' + item.area_name + '</td>';
                                    _item +=
                                        '<td><select class="form-control" name="user_class_status[]" id=""><option value="hocmoi">Học mới</option><option value="hoclai">Học Lại</option></select></td>';
                                    _item +=
                                        '<td><input class="form-control" type="date" name="day_in_class[]" value="'+formattedDate+'"></td>';
                                    _item +=
                                        '<td><input name="student[]" type="checkbox" value="' +
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


            $('.box_img_right').on('click', '.btn-remove', function() {
                let par = $(this).parents('.box_img_right');
                par.removeClass('active');
                par.find('img').attr('src', no_image_link);
                par.find('.input[type=hidden]').val("");
            });


           
            

            $('#search_code_post').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('.btn_search').click();
                }
            });
            $('#search_title_post').on('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    $('.btn_search').click();
                }
            });
            $('#search_class_student').on('change', function(event) {
                event.preventDefault();
                $('.btn_search').click();
            });
        });
    </script>
@endsection
