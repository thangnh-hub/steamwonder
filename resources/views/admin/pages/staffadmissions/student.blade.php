@extends('admin.layouts.app')
@push('style')
    <style>
        @media print {
            #printButton, #pagi-total {
                display: none; /* Ẩn nút khi in */
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
            <form action="{{ route(Request::segment(2) . '.student') }}" method="GET">
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
                                <label>@lang('Admissions')</label>
                                <select name="admission_id" id="admission_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($staffs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['admission_id']) && $value->id == $params['admission_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Course')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['course_id']) && $params['course_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.student') }}">
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
            <div id="box_print">
                <div class="box-header d-flex-wap">
                <h3 class="box-title">@lang('List')</h3>
                <ul class="d-flex-wap" style="gap: 30px">
                    @isset($params['course_id'])
                        <li>@lang('Khóa'):
                            {{ $course->first(function ($item, $key) use ($params) {
                                return $item->id == $params['course_id'];
                            })->name }}
                        </li>
                    @endisset
                    @isset($params['class_id'])
                        <li>@lang('Lớp'):
                            {{ $class->first(function ($item, $key) use ($params) {
                                return $item->id == $params['class_id'];
                            })->name }}
                        </li>
                    @endisset
                    @isset($params['admission_id'])
                        <li>@lang('CB Tuyển sinh'):
                            {{ $staffs->first(function ($item, $key) use ($params) {
                                return $item->id == $params['admission_id'];
                            })->name }}
                        </li>
                    @endisset
                    @isset($params['keyword'])
                        <li>@lang('Từ khóa'): {{ $params['keyword'] }}</li>
                    @endisset
                </ul>
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
                                <th>@lang('Order')</th>
                                <th>@lang('Student code')</th>
                                <th>@lang('Full name')</th>
                                <th>@lang('Gender')</th>

                                <th>@lang('Admissions')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Status Study')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                @endphp
                                <tr class="valign-middle show_detail cursor" data-id="{{ $row->id }}"
                                    data-toggle="tooltip" title="@lang('Xem chi tiết')"
                                    data-original-title="@lang('Xem chi tiết')">
                                    <td>{{ $loop->index + 1 }}</td>

                                    <td>
                                        {{ $row->admin_code }}
                                    </td>
                                    <td>
                                        {{ $row->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->gender)
                                    </td>

                                    <td>
                                        {{ $staff->name ?? '' }}
                                    </td>
                                    <td>
                                        @lang($row->state)
                                    </td>
                                    <td>
                                        {{ $row->updated_at }}
                                    </td>

                                    <td>
                                        @lang($row->status_study_name ?? 'Chưa cập nhật')
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

        </div>
    </section>

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Routes get all
            var routes = @json(App\Consts::ROUTE_NAME ?? []);
            $(document).on('change', '#route_name', function() {
                let _value = $(this).val();
                let _targetHTML = $('#template');
                let _list = filterArray(routes, 'name', _value);
                let _optionList = '<option value="">@lang('Please select')</option>';
                if (_list) {
                    _list.forEach(element => {
                        element.template.forEach(item => {
                            _optionList += '<option value="' + item.name + '"> ' + item
                                .title + ' </option>';
                        });
                    });
                    _targetHTML.html(_optionList);
                }
                $(".select2").select2();
            });

            $('.show_detail').on('click', function() {
                var student = $(this).data('id');
                var _view = $(this);
                _view.toggleClass('active');
                if (_view.hasClass('active') == true) {
                    var url = "{{ route('staffadmissions.student') }}";
                    $.ajax({
                        type: "GET",
                        url: url,
                        data: {
                            student: student,
                        },
                        success: function(response) {
                            _view.after(response);
                            _view.attr('title', 'Đóng').attr('data-original-title', 'Đóng');
                        },
                        error: function(response) {
                            var errors = response.responseJSON.errors;
                            alert(errors);
                        }
                    });
                } else {
                    setTimeout(function() {
                        $('.toggle_' + student).remove();
                    }, 500);

                }
            })

            $('.print').click(function() {
                var printContents = document.getElementById('box_print').innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                window.location.reload();
            })
        });
    </script>
@endsection
