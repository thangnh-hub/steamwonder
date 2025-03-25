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
                <h3 class="box-title">{{ isset($this_class) ? 'Thông tin lớp ' . $this_class->name : 'Thông tin lớp' }}</h3>
            </div>
            @if (isset($this_class) && $this_class != null)
                @php
                $quantity_student = \App\Models\UserClass::where('class_id', $this_class->id)->get()->count();
                $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
                if (
                    $this_class->assistant_teacher !== null &&
                    $this_class->assistant_teacher !== ' '
                ) {
                    $assistantTeacherArray = json_decode(
                        $this_class->assistant_teacher,
                        true,
                    );
                }
                $list="";
                @endphp
                <div class="d-flex-wap box-header">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Lớp học: </strong></label>
                            <span>{{ $this_class->name }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên: </strong></label>
                            <span>{{ $teacher->name??"" }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Sĩ số: </strong></label>
                            <span> {{ $quantity_student }} </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Trình độ: </strong></label>
                            <span>{{ $this_class->level->name ?? "" }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên phụ: </strong></label>
                            @foreach ($list_teacher as $val)
                            @php (isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray)) ? $list.= $val->name .',':""@endphp
                            @endforeach
                            <span>{{ $list }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Số buổi: </strong></label>
                            <span> {{ $this_class->total_attendance }}/{{ $this_class->total_schedules }} </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Chương trình: </strong></label>
                            <span>{{ $this_class->syllabus->name ?? "" }}</span>
                        </div>

                        <div class="form-group">
                            <label><strong>Phòng học: </strong></label>
                            <span>{{ $this_class->room->name ?? "" }} (Khu vực: {{ $this_class->area->name ?? "" }})</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Bắt đầu | Kết thúc: </strong></label>
                            <span> {{ date('d-m-Y', strtotime($this_class->day_start)) }} | {{ date('d-m-Y', strtotime($this_class->day_end )) }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Khóa học: </strong></label>
                            <span>{{ $this_class->course->name ?? "" }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ca học: </strong></label>
                            <span>{{ $this_class->period->iorder }} ({{ $this_class->period->start_time ?? '' }} -
                                {{ $this_class->period->end_time ?? '' }})</span>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('schedule_class.index') }}" method="GET">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('Class')</label>
                                    <select name="class_id" id="class_id" class="form-control select2"
                                        style="width: 100%;">
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
                                    <label>@lang('Filter')</label>
                                    <div>
                                        <button type="submit"
                                            class="btn btn-primary btn-sm mr-10">@lang('Get information')</button>
                                        <a class="btn btn-default btn-sm" href="{{ route('evaluation_class.index') }}">
                                            @lang('Reset')
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            @endif
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
                                <th>@lang('Date')</th>
                                <th>@lang('Period')</th>
                                <th>@lang('Room')</th>
                                <th>@lang('Teacher')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Thời gian điểm danh')</th>
                                <th>@lang('Có mặt/ Vắng mặt/ Đi muộn')</th>
                                <th>@lang('Ghi chú')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="valign-middle">
                                    <td>
                                        {{ optional(\Carbon\Carbon::parse($row->date))->format('l d/m/Y') }}
                                    </td>
                                    <td>
                                        {{ $row->period->id ?? '' }} ({{ $row->period->start_time ?? '' }} -
                                        {{ $row->period->end_time ?? '' }})
                                    </td>
                                    <td>
                                        {{ $row->room->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $row->teacher->name ?? '' }}
                                    </td>
                                    <td class="{{ App\Consts::SCHEDULE_STATUS_COLOR[$row->status] }}">
                                        @lang($row->status)
                                    </td>
                                    <td>
                                        @if($row->attendance_time!="")
                                       {{ date('d-m-Y H:i:s', strtotime($row->attendance_time))  }}
                                       @endif
                                    </td>
                                    <td>
                                        @if($row->status=="dadiemdanh")
                                        {{ $row->total_attendant }} / {{ $row->total_absent }} / {{ $row->total_late }}
                                        @else
                                        <p class="text-red" >@lang('Chưa điểm danh')</p>
                                        @endif
                                    </td>
                                    <td>
                                        {{$row->json_params->note??''}}
                                    </td>
                                    <td>
                                        <a href="{{ route('attendances.index_gvnn', ['schedule_id' => $row->id]) }}"
                                            class="btn btn-info">
                                            <i class="fa fa-external-link"></i> @lang('Take attendance')
                                        </a>
                                        @if($row->status=="dadiemdanh")
                                        <a href="{{ route('attendances.edit_gvnn',['schedule_id' => $row->id]) }}"
                                            class="btn btn-warning">
                                            <i class="fa fa-external-link"></i> @lang('Sửa điểm danh')
                                        </a>
                                        @endif
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

        });
    </script>
@endsection
