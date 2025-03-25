@extends('admin.layouts.app')

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
            <form action="" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Khóa học')</label>
                                <select name="course_id" id="course_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($course as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['course_id']) && $value->id == $params['course_id'] ? 'selected' : '' }}>
                                            {{ __($value->name ?? '') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route('student.cbtsdelete') }}">
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
                <button class="btn btn-sm btn-danger pull-right delete_student_all" title="@lang('Delete')">
                    @lang('Xóa học viên đã chọn')
                </button>
            </div>
            <div class="box-body table-responsive">
                @if (session('errorMessage'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('errorMessage') !!}
                    </div>
                @endif
                @if (session('successMessage'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!! session('successMessage') !!}
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
                                <th><input type="checkbox" class="check_all"> @lang('Chọn tất cả')</th>
                                <th>@lang('Student code')</th>
                                <th>@lang('Full name')</th>
                                <th>@lang('CCCD')</th>
                                <th>@lang('Gender')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Area')</th>
                                <th>@lang('Khóa học')</th>
                                <th>@lang('Admissions')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff = \App\Models\Staff::find($row->admission_id ?? 0);
                                @endphp
                                    <tr class="valign-middle">
                                        <td class="text-center">
                                            <input class="ckeck_delete" type="checkbox" value="{{ $row->id }}">
                                        </td>

                                        <td>
                                            <a target="_blank" class="btn btn-sm" data-toggle="tooltip"
                                                title="@lang('Xem chi tiết')" data-original-title="@lang('Xem chi tiết')"
                                                href="{{ route('students.show',$row->id) }}">
                                                {{ $row->admin_code }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $row->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $row->json_params->cccd ?? '' }}
                                        </td>
                                        <td>
                                            @lang($row->gender)
                                        </td>
                                        <td>
                                            @if (isset($row->classs))
                                                <ul>
                                                    @foreach ($row->classs as $i)
                                                        <li>{{ $i->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->area->name ??""}}
                                        </td>
                                        <td>
                                            {{ $row->course->name ??""}}
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
                                            <button data-id="{{ $row->id }}" class="btn btn-sm btn-danger delete_student" type="button" data-toggle="tooltip"
                                                title="@lang('Delete')" data-original-title="@lang('Delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
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
        $('.delete_student_all').click(function() {
                var id = [];
                $('.ckeck_delete:checked').each(function() {
                    id.push($(this).val());
                });
                if (id.length <= 0) {
                    alert('Vui lòng chọn học viên cần xóa!')
                } else {
                    var result = confirm("Bạn có chắc chắn muốn tiếp tục?");
                    if (result) {
                        deleteStudentCBTS(id)

                    }
                }
            });
            $('.delete_student').click(function() {
                var result = confirm("Bạn có chắc chắn muốn tiếp tục?");
                var id = [];
                if (result) {
                    id.push($(this).data('id'));
                    deleteStudentCBTS(id)
                }
            })
        $('.check_all').click(function() {
            const isChecked = $(this).prop('checked');
            $('.ckeck_delete').prop('checked', isChecked);
        });
        $('.ckeck_delete').change(function() {
            if ($('.ckeck_delete:checked').length == $('.ckeck_delete').length) {
                $('.check_all').prop('checked', true);
            } else {
                $('.check_all').prop('checked', false);
            }
        });
        function deleteStudentCBTS(id) {
            $.ajax({
                type: "POST",
                url: '{{ route('student.cbtsdelete.post') }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id,
                    "admin_id": "{{ $admin_id }}"
                },
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.box_alert').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert').remove();
                        }, 3000);
                    }
                },
                error: function(response) {
                    // Get errors
                    console.log(response);
                    var errors = response.responseJSON.message;
                    alert(errors);
                    // location.reload();
                }
            });
        }
    </script>
@endsection
