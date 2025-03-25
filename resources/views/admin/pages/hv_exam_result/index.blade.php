@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Lớp')</label>
                                <select name="id_class" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($class as $val)
                                        <option value="{{ $val->id }}"
                                            {{ isset($params['id_class']) && $params['id_class'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Trình độ') </label>
                                <select name="id_level" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($levels as $val)
                                        <option value="{{ $val->id ?? '' }}"
                                            {{ isset($params['id_level']) && $params['id_level'] == $val->id ? 'selected' : '' }}>
                                            {{ $val->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Ngày thi')</label>
                                <input type="date" name="day_exam" class="form-control"
                                    value="{{ isset($params['day_exam']) ? $params['day_exam'] : '' }}">
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Tổ chức')</label>
                                <select name="organization" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($type as $val)
                                        <option value="{{ $val }}"
                                        {{isset($params['organization']) && $params['organization'] == $val ?'selected':''}}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Kỹ năng')</small></label>
                                <select name="skill_test" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($skill as $val)
                                        <option value="{{ $val }}"
                                            {{ isset($params['skill_test']) && $params['skill_test'] == $val ? 'selected' : '' }}>
                                            @lang($val)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Giám thị')</label>
                                <select name="id_invigilator" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($list_admins as $val)
                                        <option value="{{ $val->id }}"
                                            {{isset($params['id_invigilator']) && $params['id_invigilator'] == $val->id ?'selected':''}}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Người chấm')</label>
                                <select name="id_grader_exam" class="form-control select2 w-100">
                                    <option value="">@lang('Please choose')</option>
                                    @foreach ($list_admins as $val)
                                        <option value="{{ $val->id }}"
                                            {{isset($params['id_grader_exam']) && $params['id_grader_exam'] == $val->id ?'selected':''}}>
                                            @lang($val->name ?? '')</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-2">
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
            <div class="box-body table-responsive box-alert">
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
                                <th>@lang('STT')</th>
                                <th>@lang('Mã Hv')</th>
                                <th>@lang('Họ tên')</th>
                                <th>@lang('Lớp')</th>
                                <th>@lang('Trình độ')</th>
                                <th>@lang('Ngày thi')</th>
                                <th>@lang('Kỹ năng thi')</th>
                                <th>@lang('trạng thái')</th>
                                <th>@lang('Người chấm')</th>
                                <th>@lang('Điểm')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td>
                                        {{ $row->student->admin_code ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->student->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->classs->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->level->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strTotime($row->exam_session->day_exam)) }}
                                    </td>
                                    <td>
                                        {{ __($row->skill_test) ?? '' }}
                                    </td>
                                    <td>
                                        {{ __($row->status) ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->exam_session->grader_exam->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->score ?? '' }}
                                    </td>
                                    <td>
                                        <div class="d-flex-wap ">
                                            <a class="btn btn-sm btn-success mr-10 btn_show" data-toggle="tooltip"
                                                title="@lang('Chi tiết')" data-original-title="@lang('Chi tiết')"
                                                href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-warning mr-10 btn_reset" data-toggle="tooltip"
                                                title="@lang('Reset')" data-original-title="@lang('Reset')"
                                                data-url="{{ route(Request::segment(2) . '.reset') }}"
                                                data-id = "{{ $row->id }}">
                                                <i class="fa fa-refresh"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
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
        $('.btn_reset').click(function() {
            var _this = $(this);
            var url = _this.data('url');
            var id = _this.data('id');
            if (confirm('Bạn có chắc chắn muốn reset kết quả này không?')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'warning') {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    ` + response.message + `
                                    </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);
                                console.log(2);
                            } else {
                                console.log(1);
                                location.reload();
                            }
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                Bạn không có quyền thao tác chức năng này!
                                </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });

            }

        });
    </script>
@endsection
