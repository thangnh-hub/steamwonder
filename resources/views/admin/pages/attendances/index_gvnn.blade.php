@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection


<style>
    .d-flex {
        display: flex
    }

    #alert-config {
        width: auto !important;
    }

    .input-with-suffix {
        position: relative;
    }

    .pointer-none {
        pointer-events: none;
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
    <div id="alert-config">

    </div>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>@lang('Ca hoc')</label>
                            <input readonly class=" form-control" type="text"
                                value="Ca {{ $schedule->period_id }} ({{ $schedule->period->start_time }} - {{ $schedule->period->end_time }})">
                        </div>
                    </div>
                    @if ($schedule->teacher->teacher_type == 'parttime')
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại buổi học')</label>
                                <select class="type_schedule form-control select2" style="width: 100%;">
                                    <option value="parttime">@lang('parttime')</option>
                                </select>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Loại buổi học')</label>
                                <select class="type_schedule form-control select2" style="width: 100%;">
                                    <option value="fulltime"
                                        {{ $schedule->period_id != '' && ($schedule->period_id == 1 || $schedule->period_id == 2 || $schedule->period_id == 3) ? 'selected' : '' }}>
                                        @lang('fulltime')</option>
                                    <option value="parttime"
                                        {{ $schedule->period_id != '' && ($schedule->period_id == 4 || $schedule->period_id == 5 || $schedule->period_id == 6) ? 'selected' : '' }}>
                                        @lang('parttime')</option>
                                </select>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <div class="box_title">
                    <h3 class="box-title">@lang('List')</h3>
                    @if ($schedule->date != date('Y-m-d', time()))
                        <span class="text-red">({{ $mess }})</span>
                    @endif
                </div>
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
                    @if ($schedule->status != App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                        <form action="{{ route('attendances.save_gvnn') }}" method="POST"
                            onsubmit="return confirm('@lang('Sau khi lưu sẽ không chỉnh sửa được. Xác nhận thao tác?')')">
                            @csrf
                            <input type="hidden" name="schedule" value="{{ $schedule->id ?? 0 }}">
                            <input type="hidden" name="type_schedule" class="input_type_schedule" value="fulltime">
                    @endif

                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('Order')</th>
                                <th>@lang('Class')</th>
                                <th>@lang('Student')</th>
                                <th>@lang('Home Work')</th>
                                <th>@lang('Updated at')</th>
                                <th>@lang('Status')</th>
                                <th style="width:200px">@lang('Note status')</th>
                                <th style="width:300px">@lang('Ghi chú nhận xét (GV nhập)')</th>
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
                                            class="form-control select2 " style="width: 100%;"
                                            {{ $schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'] ? 'disabled' : '' }}>
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
                                        <select
                                            {{ $schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'] ? 'disabled' : '' }}
                                            name="list[{{ $row->id }}][status]" id="status_{{ $key }}"
                                            data-id="{{ $row->id }}" class="form-control status-select"
                                            style="width: 100%;">
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
                                                @if ($schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                                                    <p>{{ $row->json_params->value ?? '' }}</p>
                                                @else
                                                    <input
                                                        {{ $schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'] ? 'disabled' : '' }}
                                                        type="text" class="form-control note-input"
                                                        name="list[{{ $row->id }}][json_params][value]"
                                                        value="{{ $row->json_params->value ?? '' }}">
                                                @endif
                                            @elseif($row->status == \App\Consts::ATTENDANCE_STATUS['absent'])
                                                <select
                                                    {{ $schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'] ? 'disabled' : '' }}
                                                    name="list[{{ $row->id }}][json_params][value]"
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
                                                    <input
                                                        {{ $schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'] ? 'disabled' : '' }}
                                                        type="number" class="form-control time-input"
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
                                        <div class="d-flex">
                                            <div class="col-md-10">
                                                <textarea rows="4" placeholder="Nhập ghi chú" name="list[{{ $row->id }}][note_teacher]"
                                                    class="form-control note-teacher">{{ $row->note_teacher ?? '' }}</textarea>
                                            </div>
                                            @if ($schedule->status == App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                                                <span data-id="{{ $row->id }}" onclick="updateAjax(this)"
                                                    class="input-group-btn">
                                                    <a class="btn btn-primary">Lưu </a>
                                                </span>
                                            @endif
                                        </div>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if ($schedule->status != App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                        <button type="submit" class="btn btn-info">
                            <i class="fa fa-save"></i> @lang('Lưu lại và chấm công')
                        </button>
                        @if ($schedule->date != date('Y-m-d', time()))
                            <span class="text-red">({{ $mess }})</span>
                        @endif
                        </form>
                    @else
                        <button type="button" class="btn btn-danger">
                            @lang(App\Consts::SCHEDULE_STATUS['dadiemdanh'])
                        </button>
                    @endif

                @endif
            </div>
        </div>
    </section>

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
                // var timeInput = document.querySelector('.time-input');
                // timeInput.addEventListener("change", function() {
                //     var selectedTime = timeInput.value;
                //     minTime = 5; // 00:05
                //     maxTime = 180; // 03:00

                //     if (selectedTime < minTime) {
                //         timeInput.value = minTime;
                //     } else if (selectedTime > maxTime) {
                //         timeInput.value = maxTime;
                //     }
                // });
                // var scoreInput = document.querySelector('.score-input');
                // scoreInput.addEventListener("change", function() {
                //     var selectedScore = scoreInput.value;
                //     minScore = 0;
                //     maxScore = 10;

                //     if (selectedScore < minScore) {
                //         scoreInput.value = minScore;
                //     } else if (selectedScore > maxScore) {
                //         scoreInput.value = maxScore;
                //     }
                // });
            });

        });

        function updateAjax(th) {
            let _id = $(th).attr('data-id');
            var _note = $(th).parents('tr').find('.note-teacher').val();
            let url = "{{ route('ajax.attendance.update.note.teacher') }}/";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    id: _id,
                    note: _note,
                },
                success: function(response) {
                    $(".table-responsive").prepend(
                        '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Đã lưu cập nhật</div>'
                    );
                    setTimeout(function() {
                        $(".alert").fadeOut(2000, function() {});
                    }, 800);
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        }

        $('.type_schedule').change(function(e) {
            var _val = $(this).val();
            $('.input_type_schedule').val(_val);
        });
        $(document).ready(function() {
            $('.type_schedule').trigger('change')
        });
    </script>
@endsection
