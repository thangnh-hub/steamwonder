@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>thead>tr>th {
            text-align: center;
            align-content: center;
        }

        .table>tbody>tr>td {
            text-align: center;
            align-content: center;
            min-width: 120px;
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

        <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Create form')</h3>
                        </div>

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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Lớp')</label>
                                                    <select class="form-control select2 class_id" name="class_id">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($list_class as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Học viên')</label>
                                                    <select class="form-control select2 student_id"
                                                        name="student_id">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($list_student as $val)
                                                            <option value="{{ $val->id }}">
                                                                {{ $val->admin_code ?? '' }}-{{ $val->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 row table-responsive box_list_student">
                                                <table class="table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>@lang('STT')</th>
                                                            <th>@lang('Họ và tên')</th>
                                                            <th>@lang('Hình thức thi *')</th>
                                                            <th>@lang('Tổng số kỹ năng')</th>
                                                            <th>@lang('Điểm nghe')</th>
                                                            <th>@lang('Ngày báo điểm')</th>
                                                            <th>@lang('Điểm nói')</th>
                                                            <th>@lang('Ngày báo điểm')</th>
                                                            <th>@lang('Điểm đọc')</th>
                                                            <th>@lang('Ngày báo điểm')</th>
                                                            <th>@lang('Điểm viết')</th>
                                                            <th>@lang('Ngày báo điểm')</th>
                                                            <th>@lang('Ghi chú')</th>
                                                            <th>@lang('Action')</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info btn-sm pull-right">
                                    <i class="fa fa-save"></i> @lang('Save')
                                </button>
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
        $(document).ready(function() {
            $('.class_id').change(function() {
                var _class_id = $(this).val();
                var _class_element = $('.box_list_student');
                var _student_id = $('.student_id').val();
                get_student(_class_id, _student_id, _class_element);
            })
            $('.student_id').change(function() {
                var _student_id = $(this).val();
                var _class_element = $('.box_list_student');
                var _class_id = $('.class_id').val();
                get_student(_class_id, _student_id, _class_element);
            })
        });

        function get_student(class_id, student_id, class_element) {
            let url = "{{ route('student.byclass') }}/";
            $.ajax({
                type: "get",
                url: url,
                data: {
                    class_id: class_id,
                    id: student_id,
                },
                success: function(response) {
                    class_element.html(response.data.html);
                    $('.select2').select2();
                },
                error: function(response) {
                    var errors = response.responseJSON.message;
                    class_element.html(errors);
                }
            });
        }

        function delete_items(id) {
            $(id).remove();
        }
    </script>
@endsection
