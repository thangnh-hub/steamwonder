@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection


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
        </h1>
    </section>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Lớp'):{{ $this_class->name }}</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Schedule')</label>
                            <select disabled name="schedule_id" id="schedule_id" class="form-control select2"
                                style="width: 100%;">
                                <option value="">@lang('Please select')</option>
                                @foreach ($schedules as $key => $value)
                                    <option value="{{ $value->id }}"
                                        {{ isset($params['schedule_id']) && $value->id == $params['schedule_id'] ? 'selected' : '' }}>
                                        {{ optional(\Carbon\Carbon::parse($value->date))->format('l d/m/Y') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Ca hoc')</label>
                            <input readonly class=" form-control" type="text"
                                value="Ca {{ $schedule->period_id }} ({{ $schedule->period->start_time }} - {{ $schedule->period->end_time }})">
                        </div>
                    </div>
                    @if ($schedule->teacher->teacher_type == 'parttime')
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Loại buổi học')</label>
                                <select name="type_schedule" class="type_schedule form-control select2"
                                    style="width: 100%;">
                                    <option value="parttime">@lang('parttime')</option>
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Loại buổi học')</label>
                                <select name="type_schedule" class="type_schedule form-control select2"
                                    style="width: 100%;">
                                    @foreach ($teacher_type as $key => $val)
                                        <option value="{{ $key }}"
                                            {{ $key == 'parttime' && $schedule->period_id == 4 ? 'selected' : ($schedule->type_schedule == $key ? 'selected' : '') }}>
                                            @lang($val) </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Trạng thái chuyển giao')</label>
                            <select name="transfer_status" id="transfer_status" class="form-control select2"
                                style="width: 100%;">
                                {{-- <option value="">@lang('Please select')</option> --}}
                                @foreach ($transfer_status as $keys => $val)
                                    <option value="{{ $keys }}"
                                        {{ isset($schedule->transfer_status) && $keys == $schedule->transfer_status ? 'selected' : '' }}>
                                        {{ __($val) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Ghi chú')</label>
                            <textarea name="json_params[note]" class="form-control schedule_note" cols="1" rows="1">{{ $schedule->json_params->note ?? '' }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
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
                    <form action="{{ route('attendances.update') }}" method="POST"
                        onsubmit="return confirm('@lang('Sau khi lưu sẽ không chỉnh sửa được. Xác nhận thao tác?')')">
                        @csrf
                        <input type="hidden" name="schedule" value="{{ $schedule->id ?? 0 }}">
                        <input type="hidden" name="type_schedule" class="input_type_schedule"
                            value="{{ $schedule->type_schedule }}">
                        <input type="hidden" name="transfer_status" class="input_transfer_status"
                            value="{{ $schedule->transfer_status }}">
                        <input type="hidden" name="schedule_note" class="input_schedule_note"
                            value="{{ $schedule->json_params->note ?? '' }}">


                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Class')</th>
                                    <th>@lang('Student')</th>
                                    <th>@lang('Home Work')</th>
                                    <th>@lang('Updated at')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Note status')</th>
                                    <th>@lang('Ghi chú nhận xét (Giáo viên nhập)')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $key => $row)
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('classs.edit', $row->class->id) }}">{{ $row->class->name ?? '' }}</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('students.edit', $row->student->id) }}">{{ $row->student->name ?? '' }}
                                                ({{ $row->student->admin_code ?? '' }})
                                            </a>
                                        </td>
                                        <td>
                                            <select name="list[{{ $row->id }}][is_homework]" id="is_homework"
                                                class="form-control select2" style="width: 100%;">
                                                <option value="">@lang('Please select')</option>
                                                @foreach ($is_homework as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ isset($row->is_homework) && $key == $row->is_homework ? 'selected' : '' }}>
                                                        {{ __($value) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="list[{{ $row->id }}][id]"
                                                value="{{ $row->id }}">
                                        </td>
                                        <td>
                                            {{ $row->updated_at }}
                                        </td>
                                        <td>
                                            <select name="list[{{ $row->id }}][status]"
                                                id="status_{{ $key }}" data-id="{{ $row->id }}"
                                                class="form-control status-select" style="width: 100%;">
                                                <option value="">@lang('Please select')</option>
                                                @foreach ($status as $statusKey => $statusValue)
                                                    <option value="{{ $statusKey }}"
                                                        {{ isset($row->status) && $statusKey == $row->status ? 'selected' : '' }}>
                                                        {{ __($statusValue) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="note-status">
                                                @if ($row->status == \App\Consts::ATTENDANCE_STATUS['attendant'])
                                                    <input type="text" class="form-control note-input"
                                                        name="list[{{ $row->id }}][json_params][value]"
                                                        value="{{ $row->json_params->value ?? '' }}">
                                                @elseif($row->status == \App\Consts::ATTENDANCE_STATUS['absent'])
                                                    <select name="list[{{ $row->id }}][json_params][value]"
                                                        id="note_{{ $key }}"
                                                        class="form-control select2 note-select" style="width: 100%;">
                                                        <option value="">@lang('Please select')</option>
                                                        @foreach ($option_absent as $optionKey => $optionValue)
                                                            <option value="{{ $optionKey }}"
                                                                {{ isset($row->json_params->value) && $optionKey == $row->json_params->value ? 'selected' : '' }}>
                                                                {{ __($optionValue) }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <div class="input-with-suffix">
                                                        <input type="number" class="form-control time-input"
                                                            name="list[{{ $row->id }}][json_params][value]"
                                                            value="{{ $row->json_params->value ?? '' }}"
                                                            id="json_params[value]" min="5" max="180"
                                                            step="5">
                                                        <span class="input-suffix">(@lang('minute'))</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <p>{{ $row->note_teacher ?? '' }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-info">
                            <i class="fa fa-save"></i> @lang('Save')
                        </button>
                    </form>
                @endif
            </div>

            {{-- <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $rows->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $rows->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div> --}}

        </div>
    </section>

    </div>
@endsection
@section('script')
    <script>
        document.querySelectorAll('.status-select').forEach(function(select, index) {
            select.addEventListener("change", function() {
                var selectedValue = this.value;
                var dataId = this.dataset.id; // Lấy giá trị data-id

                var parentRow = this.closest('tr'); // Lấy phần tử cha là dòng chứa select
                var noteStatusDiv = parentRow.querySelector('.note-status');

                if (selectedValue === "attendant") {
                    // If "Attendant" is selected, display a text input
                    noteStatusDiv.innerHTML = '<input type="text" class="form-control" name="list[' +
                        dataId + '][json_params][value]">';
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
                    noteStatusDiv.innerHTML =
                        '<input type="number" class="form-control time-input" name="list[' + dataId +
                        '][json_params][value]" id="json_params[value]" min="5" max="180" step="5" value="5">';
                }
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

            $('.type_schedule').change(function(e) {
                var _val = $(this).val();
                $('.input_type_schedule').val(_val);
            });
            $('#transfer_status').change(function(e) {
                var _val = $(this).val();
                $('.input_transfer_status').val(_val);
            });
            $('.schedule_note').change(function(e) {
                var _val = $(this).val();
                $('.input_schedule_note').val(_val);
            });

            $('.type_schedule').trigger('change');
            $('#transfer_status').trigger('change');


        });
    </script>
@endsection
