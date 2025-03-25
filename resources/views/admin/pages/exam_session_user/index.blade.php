@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)

            <form class="pull-right" action="{{ route('export_exam_session_user') }}" method="get"
                enctype="multipart/form-data">
                <input type="hidden" name="keyword" value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                <input type="hidden" name="status" value="{{ isset($params['status']) ? $params['status'] : '' }}">
                <input type="hidden" name="course_id"
                    value="{{ isset($params['course_id']) ? $params['course_id'] : '' }}">
                <input type="hidden" name="class_id" value="{{ isset($params['class_id']) ? $params['class_id'] : '' }}">
                <input type="hidden" name="day_exam" value="{{ isset($params['day_exam']) ? $params['day_exam'] : '' }}">
                <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                    @lang('Export kết quả')</button>
            </form>

            <a class="pull-right" style="margin-right: 10px" href="{{ route('exam_session_user.examResult') }}"><button
                    class="btn btn-sm btn-primary ">Xem kết quả theo học
                    viên</button></a>
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Tên học viên, mã học viên, CCCD')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Khóa')</label>
                                <select name="course_id" class=" form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['course_id']) && $params['course_id'] == $val->id ? 'selected' : '' }}>
                                            @lang($val->name)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($class as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Ngày thi')</label>
                                <input type="date" name="day_exam" class="form-control"
                                    value="{{ isset($params['day_exam']) ? $params['day_exam'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $value }}"
                                            {{ isset($params['status']) && $value == $params['status'] ? 'selected' : '' }}>
                                            @lang($value)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body table-responsive">
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
                @if (count($rows) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Mã học viên')</th>
                                <th>@lang('Học viên')</th>
                                <th>@lang('CCCD')</th>
                                <th>@lang('Khóa')</th>
                                <th>@lang('Mã CBTS')</th>
                                {{-- <th>@lang('CB tuyển sinh')</th> --}}
                                <th>@lang('Buổi thi')</th>
                                <th>@lang('Ngày thi')</th>
                                <th>@lang('Giờ thi')</th>
                                <th>@lang('Trạng thái thi')</th>
                                <th>@lang('Kết quả')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @if ($row->student)
                                    @php
                                        $student = $row->student;
                                        $detail_admission = $admission->first(function ($item, $key) use ($student) {
                                            return $item->id == $student->admission_id;
                                        });
                                    @endphp
                                    <tr class="valign-middle">
                                        <td>
                                            <strong style="font-size: 14px">{{ $row->student->admin_code ?? '' }}</strong>
                                        </td>
                                        <td>
                                            {{ $row->student->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->student->json_params->cccd ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->exam->course->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $detail_admission->admin_code ?? '' }}
                                        </td>
                                        {{-- <td>
                                            {{ $detail_admission->name ?? '' }}
                                        </td> --}}
                                        <td>
                                            @if (route('exam_session.edit', $row->exam_id))
                                                <a target="_blank" data-toggle="tooltip" title="@lang('Xem chi tiết')"
                                                    href="{{ route('exam_session.edit', $row->exam_id) }}">
                                                    {{ $row->exam->title ?? '' }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->exam->day_exam ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->exam->time_exam_start ?? '' }}
                                        </td>
                                        <td>
                                            @lang($row->status)
                                        </td>
                                        <td>
                                            <span class="txt_score">{{ $row->score ?? 'Chưa cập nhật' }}</span>
                                            <input type="number" class="ip_score form-control " min="0"
                                                style="display: none" name="score" value="{{ $row->score ?? '' }}">
                                        </td>

                                        <td class="d-flex-wap" style="gap: 5px">
                                            <button class="btn btn-sm btn-warning btn_reser"
                                                data-id="{{ $row->user_id }}"
                                                data-exam="{{ $row->exam_id }}">@lang('Reset phiên thi')</button>
                                            <button class="btn btn-sm btn-primary btn_point"
                                                data-id="{{ $row->user_id }}"
                                                data-exam="{{ $row->exam_id }}">@lang('Nhập điểm')</button>
                                            {{-- <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                            method="POST" onsubmit="return confirm('@lang('Duyên Đỗ lưu ý:Bạn có chắc muốn xóa bản ghi này?')')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form> --}}

                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
@section('script')
    <script>
        if ($('.alert').length) {
            $('html, body').animate({
                scrollTop: $(".alert").offset().top
            }, 1000);
        }
        $('.btn_reser').click(function() {
            var user_id = $(this).data('id');
            var exam_id = $(this).data('exam');
            var _cf = confirm(
                'Thao tác này sẽ reset lại trạng thái và xóa dữ liệu kết quả thi nếu có. \nBạn chắc chắn muốn reset !'
            );
            if (_cf) {
                var _url = "{{ route('exam_session_user.resetStatus') }}";
                $.ajax({
                    type: "GET",
                    url: _url,
                    data: {
                        "user_id": user_id,
                        "exam_id": exam_id,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            location.reload();
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.table-responsive').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert-warning').remove();
                            }, 3000);
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            }
        })
        $('.btn_point').click(function() {
            var user_id = $(this).data('id');
            var exam_id = $(this).data('exam');
            $(this).parents('tr').find('.txt_score').hide();
            $(this).parents('tr').find('.ip_score').show().focus();
            $(this).html('Lưu lại').attr('onclick', 'changePoint(' + user_id + ',' + exam_id + ',this)');

        })

        function changePoint(user_id, exam_id, t) {
            var score = $(t).parents('tr').find('.ip_score').val();
            var _cf = confirm(
                'Bạn chắc chắn muốn lưu lại kêt quả thi !');
            if (_cf) {
                var _url = "{{ route('exam_session_user.resetPoint') }}";
                $.ajax({
                    type: "GET",
                    url: _url,
                    data: {
                        "user_id": user_id,
                        "exam_id": exam_id,
                        "score": score,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            location.reload();

                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.table-responsive').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert-warning').remove();
                            }, 3000);
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            }
        }
    </script>
@endsection
