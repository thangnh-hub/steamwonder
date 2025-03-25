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
    $show_teacher = $teacher->first(function ($item, $key) use ($this_class) {
        return $item->id == $this_class->json_params->teacher;
    });
    $assistantTeacherArray = json_decode($this_class->assistant_teacher, true);
    $list_teacher = '';
    if (isset($assistantTeacherArray) ) {
        foreach ($assistantTeacherArray as $val) {
            $teacher_name = $teacher->first(function ($item, $key) use ($val) {
                return $item->id == $val;
            });
            $teacher_name=$teacher_name->name??"";
            $list_teacher .= $teacher_name . ', ';
        }
    }
@endphp
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="#"><i
                    class="fa fa-plus"></i> @lang('Add')</a> --}}
        </h1>

    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">{{ isset($this_class) ? 'Thông tin lớp ' . $this_class->name : 'Thông tin lớp' }}</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>

            <div class="d-flex-wap box-header">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Sĩ số: </strong></label>
                        <span>{{ count($rows) }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Trình độ: </strong></label>
                        <span>{{ $this_class->level->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Chương trình: </strong></label>
                        <span>{{ $this_class->syllabus->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Khóa học: </strong></label>
                        <span>{{ $this_class->course->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Ca học: </strong></label>
                        <span>{{ $this_class->period->iorder??'' }} ({{ $this_class->period->start_time ?? '' }} -
                            {{ $this_class->period->end_time ?? '' }})</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Phòng học: </strong></label>
                        <span>{{ $this_class->room->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Giảng viên: </strong></label>
                        <span>{{ $show_teacher->name ?? '' }}</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Giảng viên phụ: </strong></label>
                        <span>{{ rtrim($list_teacher,", ")}}</span>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Ngày kết thúc dự kiến: </strong></label>
                        <span>{{ date('d-m-Y', strtotime($day_end_expected)) }}</span>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><strong>Ngày kết thúc thực tế: </strong></label>
                        <span>{{ date('d-m-Y', strtotime($day_end)) }}</span>

                    </div>
                </div>
            </div>

        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right" href="{{ route('evaluation_class.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route('evaluation_class.index') }}?lang={{ $item->lang_locale }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @endif
                    @endforeach
                @endisset
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
                                {{-- <th>@lang('Middle name')</th>
                                <th>@lang('First name')</th> --}}
                                <th>@lang('Gender')</th>
                                <th>@lang('State')</th>
                                <th>@lang('Updated at')</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $staff_row = $staffs->first(function ($item, $key) use ($row) {
                                        return $item->id == $row->admission_id;
                                    });
                                @endphp

                                <tr class="valign-middle">
                                    <td>{{ $loop->index + 1 }}</td>
                                    {{-- <td>
                                        {{ $row->email }}
                                    </td> --}}
                                    <td>
                                        <a target="_blank" class="btn btn-sm" data-toggle="tooltip" title="@lang('Detail')"
                                            data-original-title="@lang('Detail')"
                                            href="{{ route('students.show', $row->id) }}">
                                            {{ $row->admin_code }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $row->name ?? '' }}
                                    </td>
                                    {{-- <td>
                                        {{ $row->json_params->middle_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->json_params->first_name ?? '' }}
                                    </td> --}}
                                    <td>
                                        @lang($row->gender)
                                    </td>

                                    {{-- <td>
                                        @if ($staff_row)
                                            <a
                                                href="{{ route('staffs.edit', $staff_row->id ?? 0) }}">{{ $staff_row->name ?? '' }}</a>
                                        @endif
                                    </td> --}}
                                    <td>
                                        {{ $row->StatusStudent->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->updated_at }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </section>




@endsection
@section('script')
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
