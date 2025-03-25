@extends('admin.layouts.app')
@push('style')
    <style>
        @media print {

            #printButton,
            #pagi-total {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
@endpush
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
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <button class="btn btn-sm btn-warning pull-right mr-10 print"><i class="fa fa-print"></i>
                @lang('In danh sách học viên')</button>
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
            <form action="{{ route('report.student.learnAgain') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('report.student.learnAgain') }}">
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
                                <th>@lang('Order')</th>
                                <th>@lang('Student code')</th>
                                <th>@lang('Full name')</th>
                                <th>@lang('Gender')</th>
                                <th>@lang('Số lần học lại')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                @endphp
                                <tr class="valign-middle  cursor">
                                    <td>{{ $loop->index + 1 }}</td>

                                    <td>
                                        <a
                                            href="{{ route('students.edit', $row->user_id) }}">{{ $row->user->admin_code }}</a>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('students.edit', $row->user_id) }}">{{ $row->user->name ?? '' }}</a>
                                    </td>
                                    <td>
                                        @lang($row->user->gender)
                                    </td>
                                    <td>
                                        {{ $row->repeat_count ?? '' }} Lần
                                        <button data-id="{{ $row->user_id }}" data-toggle="modal"
                                            data-target=".bd-example-modal-lg" style="margin-left: 20px" type="button"
                                            class="btn btn-primary view-user"><i class="fa fa-eye"></i> Xem chi tiết
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>


        <div id="pagi-total" class="box-footer clearfix">
            <div class="row">
                <div class="col-sm-5">
                    Tìm thấy {{ $rows->total() }} kết quả
                </div>
                <div class="col-sm-7">
                    {{ $rows->withQueryString()->links('admin.pagination.default') }}
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-content">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                Danh sách học viên
                            </h4>
                        </div>

                        <form action="" method="POST" class="form-ajax-lesson">
                            <div class="modal-body modal-body-add-leson">
                                <div class="box-body table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên</th>
                                                <th>Lớp</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody class="show-user">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    Đóng
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@section('script')
    <script>
        $('.view-user').click(function(e) {
            e.preventDefault();
            var _user_id = $(this).attr('data-id');
            let _url = "{{ route('ajax.report.student.learnAgain') }}";
            var _html = $('.show-user');
            var _content = "";
            $.ajax({
                type: "GET",
                url: _url,
                data: {
                    "user_id": _user_id,
                },
                dataType: 'JSON',
                success: function(response) {
                    _list = response.data;
                    if (_list.length > 0) {
                        var i = 1;
                        _list.forEach(it => {
                            _content += `<tr class="valign-middle">
                                            <td>${i}</td>
                                            <td><a target="_blank" href="${it.link_student}"><strong style="font-size: 14px;">${it.user.name}</strong></a></td>
                                            <td><a target="_blank" href="${it.link_class}">${it.class.name}</a></td>
                                            <td>${it.status}</td>
                                        </tr>`;
                            i++;
                        });
                        _html.html(_content);
                    } else {
                        _content = `<tr>
                                        <td colspan='7'>Không có bản ghi phù hợp</td>
                                    </tr>`;
                        _html.html(_content);
                    }
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.errors;
                    // Foreach and show errors to htmluu
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = translations.csrf_mismatch;
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    $('.error-container').html(
                        elementErrors); // Assuming you have a container to display errors
                }
            });
        });
    </script>
@endsection
