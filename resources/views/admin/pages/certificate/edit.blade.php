@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
            <input type="hidden" name="student_id" value="{{ $detail->student_id }}">
            <input type="hidden" name="class_id" value="{{ $detail->class_id }}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Thông tin học viên {{ $detail->students->name ?? '' }} -
                                {{ $detail->students->admin_code ?? '' }}</h3>
                        </div>
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin chính</h5>
                                        </a>
                                    </li>
                                    <a class="btn btn-success btn-sm pull-right"
                                        href="{{ route(Request::segment(2) . '.index') }}" style="margin-left: 10px">
                                        <i class="fa fa-bars"></i> @lang('List')
                                    </a>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>

                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Hình thức thi') <small class="text-red">*</small></label>
                                                    <select required class="form-control select2" name="type">
                                                        @foreach ($type as $key => $val)
                                                            <option {{ $detail->type == $key ? 'selected' : '' }}
                                                                value="{{ $key }}">
                                                                {{ $val }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('Tổng số kỹ năng')</label>
                                                    <input type="text" class="form-control" name="total_skill"
                                                        placeholder="@lang('Tổng số kỹ năng')"
                                                        value="{{ $detail->total_skill ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Điểm nghe')</label>
                                                    <input type="text" class="form-control" name="score_listen"
                                                        placeholder="@lang('Điểm nghe')"
                                                        value="{{ $detail->score_listen ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Ngày báo điểm nghe')</label>
                                                    <input type="date" class="form-control" name="day_score_listen"
                                                        placeholder="@lang('Ngày báo điểm nghe')"
                                                        value="{{ $detail->day_score_listen ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Điểm nói')</label>
                                                    <input type="text" class="form-control" name="score_speak"
                                                        placeholder="@lang('Điểm nói')"
                                                        value="{{ $detail->score_speak ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Ngày báo điểm nói')</label>
                                                    <input type="date" class="form-control" name="day_score_speak"
                                                        placeholder="@lang('Ngày báo điểm nói')"
                                                        value="{{ $detail->day_score_speak ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Điểm đọc')</label>
                                                    <input type="text" class="form-control" name="score_read"
                                                        placeholder="@lang('Điểm đọc')"
                                                        value="{{ $detail->score_read ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Ngày báo điểm đọc')</label>
                                                    <input type="date" class="form-control" name="day_score_read"
                                                        placeholder="@lang('Ngày báo điểm đọc')"
                                                        value="{{ $detail->day_score_read ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Điểm viết')</label>
                                                    <input type="text" class="form-control" name="score_write"
                                                        placeholder="@lang('Điểm viết')"
                                                        value="{{ $detail->score_write ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('Ngày báo điểm viết')</label>
                                                    <input type="date" class="form-control" name="day_score_write"
                                                        placeholder="@lang('Ngày báo điểm viết')"
                                                        value="{{ $detail->day_score_write ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Lớp')</label>
                                                    <select class="form-control select2" name="class_id">
                                                        <option value=""> @lang('Please choose')</option>
                                                        @foreach ($list_class as $key => $val)
                                                            <option {{ $detail->class_id == $val->id ? 'selected' : '' }}
                                                                value="{{ $val->id }}">
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Giáo viên')</label>
                                                    <select class="form-control select2" name="teacher_id">
                                                        <option value=""> @lang('Please choose')</option>
                                                        @foreach ($teacher as $key => $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ $val->id == $detail->teacher_id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>@lang('Giáo viên phụ')</label>
                                                    <select class="form-control select2" name="assistant_teacher_id">
                                                        <option value=""> @lang('Please choose')</option>
                                                        @foreach ($teacher as $key => $val)
                                                            <option value="{{ $val->id }}"
                                                                {{ $val->id == $detail->assistant_teacher_id ? 'selected' : '' }}>
                                                                {{ $val->name }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('Ghi chú')</label>
                                                    <textarea class="form-control" name="json_params[note]" rows="5">{{ $detail->json_params->note ?? '' }}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- nav-tabs-custom -->

                        </div>
                        <!-- /.box-body -->


                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        $('.class_id').change(function() {
            var _id = $(this).val();
            let url = "{{ route('student.byclass') }}/";
            let student_id = "{{ $detail->student_id ?? '' }}";
            let _targetHTML = $('.student_avaible');
            $.ajax({
                type: "get",
                url: url,
                data: {
                    class_id: _id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        console.log(list);
                        let _item = '<option value="">@lang('Chọn học viên')</option>';
                        if (list.length > 0) {
                            list.forEach(item => {
                                var selected = "";
                                if (student_id == item.id) selected = "selected";
                                _item += '<option ' + selected + ' value="' + item.id + '">' +
                                    item
                                    .name + '</option>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<option value="">@lang('Syllabus')</option>');
                    }
                    _targetHTML.trigger('change');
                },
                error: function(response) {

                }
            });
        })
        $(document).ready(function() {
            $('.class_id').trigger('change');
        });
    </script>
@endsection
