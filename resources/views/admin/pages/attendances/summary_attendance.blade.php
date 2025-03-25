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
</style>

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            {{-- <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
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
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        {{-- <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($status as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" id="area_id" class="area_id form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['area_id']) && $value->id == $params['area_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Class')<span class="text-danger">*</span></label>
                                <select name="class_id" id="class_id" class="class_avaible form-control select2" required style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @if(isset($params['class_id']) && $params['class_id'] != 0)
                                    @foreach ($classs as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['class_id']) && $value->id == $params['class_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Teacher')</label>
                                <select name="teacher_id" id="teacher_id" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($teachers as $key => $value)
                                        <option value="{{ $value->id }}"
                                            {{ isset($params['teacher_id']) && $value->id == $params['teacher_id'] ? 'selected' : '' }}>
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('From date') </label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('To date') </label>
                                <input type="date" class="form-control" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
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
        {{-- End search form --}}
        @if(count($rows) != 0)
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('General information')</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            @csrf
            <div class="box-body">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="d-flex-wap">
                                @if ($lang != '')
                                    <input type="hidden" name="lang" value="{{ $lang }}">
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>@lang('Class'): </strong></label>
                                        <span>{{ $class_name ?? '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>@lang('Student'): </strong></label>
                                        <span>{{ $count_student ?? '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>@lang('Lesson total'): </strong></label>
                                        <span>{{ $lesson_total ?? '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>@lang('Lesson min'): </strong></label>
                                        <span>{{ $lesson_min ?? '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>@lang('Count of sessions attended'): </strong></label>
                                        <span>{{ $count_attendanced ?? '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>@lang('Count of sessions not yet attended'): </strong></label>
                                        <span>{{ $count_not_attendanced ?? '' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><strong>@lang('Count failed student'): </strong></label>
                                        <span>{{ $count_failed_student ?? '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>@lang('Count success student'): </strong></label>
                                        <span>{{ $count_success_student ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.tab-content -->
                </div><!-- nav-tabs-custom -->
                
            </div>
            <!-- /.box-body -->
        </div>
        @endif
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('List')</h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right" href="{{ route(Request::segment(2) . '.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route(Request::segment(2) . '.index') }}?lang={{ $item->lang_locale }}"
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
                    <form action="{{ route('attendances.save') }}" method="POST"
                        onsubmit="return confirm('@lang('confirm_action')')">
                        @csrf
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Class')</th>
                                    <th>@lang('Period')</th>
                                    <th>@lang('Room')</th>
                                    <th>@lang('Teacher')</th>
                                    <th>@lang('Status')</th>
                                    {{-- <th>@lang('Action')</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $key => $row)
                                    @php
                                        $attendances = new \App\Models\Attendance();
                                        // $attendance = $attendances->where('class_id', $params['class_id'])->where('user_id', $row->id);
                                        $count_dont_homework = $attendances->where('class_id', $params['class_id'])->where('user_id', $row->id)->where('is_homework', 0)->count();
                                        $count_late = $attendances->where('class_id', $params['class_id'])->where('user_id', $row->id)->where('status', \App\Consts::ATTENDANCE_STATUS['late'])->count();
                                        $count_absent = $attendances->where('class_id', $params['class_id'])->where('user_id', $row->id)->where('status', \App\Consts::ATTENDANCE_STATUS['absent'])->count();
                                    @endphp
                                    <tr class="valign-middle">
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>
                                            {{ optional(\Carbon\Carbon::parse($row->date))->format('d/m/Y (l)') }}
                                        </td>
                                        <td>
                                            <a target="_blank" href="{{ route('classs.edit',$row->class->id) }}">{{ $row->class->name }}</a>
                                        </td>
                                        <td>
                                            {{ $row->period->id ?? ''}} ({{ $row->period->start_time ?? ''}} - {{ $row->period->end_time ?? ''}})
                                        </td>
                                        <td>
                                            {{ $row->room->name }}
                                        </td>
                                        <td>
                                            {{ $row->teacher->name }}
                                        </td>
                                        
                                        @if($row->status != App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                                        <td class="text-green">
                                            {{ App\Consts::SCHEDULE_STATUS[$row->status] }}
                                        </td>
                                        @else
                                        <td class="text-red">
                                            {{ App\Consts::SCHEDULE_STATUS[$row->status] }}
                                        </td>
                                        @endif
                                        {{-- <td></td> --}}
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
                        Tìm thấy {{ $rows->count() }} kết quả
                    </div>
                </div>
            </div>

        </div>
    </section>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.area_id').change(function(){
                var _id=$(this).val();
                let url = "{{ route('class_by_area') }}";
                let _targetHTML = $('.class_avaible');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: _id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            let list = response.data;
                            console.log(list);
                            let _item = '<option value="">@lang('Class')</option>';
                            if (list.length > 0) {
                                list.forEach(item => {
                                    _item += '<option value="'+item.id+'">'+item.name+'</option>';
                                });
                                _targetHTML.html(_item);
                            }
                        } else {
                            _targetHTML.html('<option value="">@lang('Class')</option>');
                        }
                        _targetHTML.trigger('change');
                    },
                    error: function(response) {
                        // Get errors
                        // let errors = response.responseJSON.message;
                        // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                    }
                });
            })
        });
        document.querySelectorAll('.status-select').forEach(function(select, index) {
            select.addEventListener("change", function () {
                var selectedValue = this.value;
                var dataId = this.dataset.id; // Lấy giá trị data-id

                var parentRow = this.closest('tr'); // Lấy phần tử cha là dòng chứa select
                var noteStatusDiv = parentRow.querySelector('.note-status');

                if (selectedValue === "attendant") {
                    // If "Attendant" is selected, display a text input
                    noteStatusDiv.innerHTML = '<input type="text" class="form-control" name="list[' + dataId + '][json_params][value]">';
                } else if (selectedValue === "absent") {
                    // If "Absent" is selected, display a dropdown
                    noteStatusDiv.innerHTML = `
                        <select name="list[` + dataId + `][json_params][value]" id="note" class="form-control select2" style="width: 100%;">
                            <option value="">@lang('Please select')</option>
                            @foreach ($option_absent as $key => $value)
                                <option value="{{ $key }}">
                                    {{ __($value) }}</option>
                            @endforeach
                        </select>
                    `;
                } else {
                    // For other options, you can define different HTML here
                    noteStatusDiv.innerHTML = '<input type="number" class="form-control time-input" name="list[' + dataId + '][json_params][value]" id="json_params[value]" min="5" max="180" step="5" value="5">';
                }
            });
            var timeInput = document.querySelector('.time-input');
            timeInput.addEventListener("change", function() {
                var selectedTime = timeInput.value;
                minTime = 5; // 00:05
                maxTime = 180; // 03:00

                if (selectedTime < minTime) {
                    timeInput.value = minTime;
                } else if (selectedTime > maxTime) {
                    timeInput.value = maxTime;
                }
            });
            var scoreInput = document.querySelector('.score-input');
            scoreInput.addEventListener("change", function() {
                var selectedScore = scoreInput.value;
                minScore = 0; 
                maxScore = 10; 

                if (selectedScore < minScore) {
                    scoreInput.value = minScore;
                } else if (selectedScore > maxScore) {
                    scoreInput.value = maxScore;
                }
            });
        });
        
        
    </script>
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
