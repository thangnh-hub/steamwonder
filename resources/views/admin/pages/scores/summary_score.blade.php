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

<style>
    .input-with-suffix {
        position: relative;
    }

    .input-suffix {
        position: absolute;
        right: 30px;
        top: 8px;
    }
    .table_scroll{
        width: 1900px;
    }
</style>

@section('content-header')
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
                                <label>@lang('Class')</label>
                                <select name="class_id" id="class_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($classs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Student')</label>
                                <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($all_student as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['user_id']) && $value->id == $params['user_id'] ? 'selected' : '' }}>
                                            {{ __($value->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Get information')</button>
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
       
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
            </div>
            <div class="box-body table-responsive table-reponscroll">
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
                    <form class="table_scroll" action="{{ route('attendances.save') }}" method="POST"
                        onsubmit="return confirm('@lang('confirm_action')')">
                        @csrf
                        <table class="table table-hover table-bordered ">
                            <thead>
                                <tr>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Student Code')</th>
                                    <th>@lang('Student')</th>
                                    <th>@lang('Avatar')</th>
                                    <th>@lang('Class')</th>
                                    <th>@lang('Teacher')</th>
                                    <th>@lang('Course')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('Syllabus')</th>
                                    <th>@lang('Listen')</th>
                                    <th>@lang('Speak')</th>
                                    <th>@lang('Read')</th>
                                    <th>@lang('Write')</th>
                                    <th>@lang('Average')</th>
                                    <th>@lang('Equal')</th>
                                    <th>@lang('Evaluations')</th>
                                    <th>@lang('Có phép')</th>
                                    <th>@lang('Không phép')</th>
                                    <th>@lang('Đi muộn')</th>
                                    <th>@lang('Thời điểm điểm danh')</th>
                                    <th>@lang('Số lần không làm bài')</th>
                                    <th>@lang('Số lần học lại')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $key => $row)
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index+1 }}
                                        </td>
                                       
                                       <td>{{ $row->student->admin_code??"" }}</td>
                                       <td>{{ $row->student->name??"" }}</td>
                                       <td><img src="{{ $row->avatar ?? url('themes/admin/img/no_image.jpg') }}" style="width: 100px;height:100px;"></td>
                                       <td>{{ $row->class->name??"" }}</td>
                                       @php
                                        $teacher = App\Models\Student::where('id',$row->class->json_params->teacher)->first();
                                       @endphp
                                       <td>{{ $teacher->name??"" }}</td>
                                       <td>{{ $row->class->course->name??"" }}</td>
                                       <td>{{ $row->class->level->name??"" }}</td>
                                       <td>{{ $row->class->syllabus->name??"" }}</td>
                                       <td>{{ $row->score_listen??"" }}</td>
                                       <td>{{ $row->score_speak??"" }}</td>
                                       <td>{{ $row->score_read??"" }}</td>
                                       <td>{{ $row->score_write??"" }}</td>
                                       <td>{{ $row->json_params->score_average ?? '0' }}</td>
                                       <td >
                                        {{ $row->status ??'' }}
                                        </td>
                                        <td >
                                        {{ $row->json_params->note ??'' }}
                                        </td>
                                        <td>
                                            @php
                                                $is_absent_has_reson = App\Models\Attendance::where('class_id',$row->class->id)->where('user_id',$row->student->id)->where('is_homework',App\Consts::ATTENDANCE_STATUS['absent'])->whereJsonContains('json_params->value',App\Consts::OPTION_ABSENT['there reason'])->count();
                                            @endphp
                                            {{ isset($is_absent_has_reson)?$is_absent_has_reson: 0}}
                                        </td>
                                        <td>
                                            @php
                                                $is_absent_no_reson = App\Models\Attendance::where('class_id',$row->class->id)->where('user_id',$row->student->id)->where('is_homework',App\Consts::ATTENDANCE_STATUS['absent'])->whereJsonContains('json_params->value',App\Consts::OPTION_ABSENT['no reason'])->count();
                                            @endphp
                                            {{ isset($is_absent_no_reson)?$is_absent_no_reson: "0"}}
                                        </td>
                                        <td>
                                            @php
                                                $is_late = App\Models\Attendance::where('class_id',$row->class->id)->where('user_id',$row->student->id)->where('is_homework',App\Consts::ATTENDANCE_STATUS['late'])->count();
                                            @endphp
                                            {{ isset($is_late)?$is_late: 0}}
                                        </td>
                                        <td>
                                            @php
                                                $uppdated = App\Models\Attendance::where('class_id',$row->class->id)->where('user_id',$row->student->id)->first();
                                            @endphp
                                            {{ date('m-Y',strtotime($uppdated->updated_at)) }}
                                        </td>
                                        <td>
                                            @php
                                                $is_homework = App\Models\Attendance::where('class_id',$row->class->id)->where('user_id',$row->student->id)->where('is_homework',1)->count();
                                            @endphp
                                            {{ isset($is_homework)?$is_homework: 0}}
                                        </td>
                                        <td>-</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
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

    </div>
@endsection
