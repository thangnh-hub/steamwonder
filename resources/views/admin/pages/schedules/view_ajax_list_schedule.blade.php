@if ($type == 'list')
    @if (isset($schedule))
        @foreach ($schedule as $key => $lesson)
            <tr>
                <td>
                    <button type="button" data-lesson-id="{{ $lesson->id }}"
                        class="btn btn-sm btn-danger del_lesson">Xóa
                        buổi</button>
                </td>
                <td class="{{ App\Consts::SCHEDULE_STATUS_COLOR[$lesson->status] }}">
                    {{ App\Consts::SCHEDULE_STATUS[$lesson->status] }}
                    <input type="hidden" name="lesson[{{ $key }}][id]" value="{{ $lesson->id }}">
                </td>
                <td>
                    <div class="form-group d-flex align-items-center">
                        <input name="lesson[{{ $key }}][date]" type="date" value="{{ $lesson->date }}"
                            class="form-control mr-2 {{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }}">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[{{ $key }}][period_id]" style="width: 100%"
                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2">
                            @foreach ($period as $val)
                                <option
                                    {{ isset($lesson->period_id) && $lesson->period_id == $val->id ? 'selected' : '' }}
                                    value="{{ $val->id }}">
                                    {{ $val->iorder }}
                                    ({{ $val->start_time }} -
                                    {{ $val->end_time }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[{{ $key }}][room_id]" style="width: 100%"
                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_room_change lesson_period form-control select2">
                            @foreach ($room as $val)
                                <option {{ isset($lesson->room_id) && $lesson->room_id == $val->id ? 'selected' : '' }}
                                    value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[{{ $key }}][teacher_id]" style="width: 100%"
                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }}
                            teacher_id_select lesson_period form-control select2" disabled>
                            @foreach ($teacher as $val)
                                @if (isset($class->json_params->teacher) && $class->json_params->teacher == $val->id)
                                    <option value="{{ $val->id }}">
                                        {{ $val->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                </td>
                <td>
                    @php
                        if ($lesson->assistant_teacher !== null && $lesson->assistant_teacher !== ' ') {
                            $assistantTeacherArray = json_decode($lesson->assistant_teacher, true);
                        }
                    @endphp
                    <div class="form-group">
                        <select name="lesson[{{ $key }}][assistant_teacher][]" style="width: 100%"
                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2">
                            <option value="0">
                                @lang('Please select')
                            </option>
                            @foreach ($teacher as $val)
                                <option
                                    {{ isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray) ? 'selected' : '' }}
                                    value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control" name="lesson[{{ $key }}][note]"
                        value="{{ $lesson->json_params->note ?? '' }}">
                </td>
            </tr>
        @endforeach
    @endif
@else
    <tr>
        <td>
            <button onclick="_delete_lesson(this)" type="button" class="btn btn-sm btn-danger">Xóa buổi</button>
        </td>
        <td class="{{ App\Consts::SCHEDULE_STATUS_COLOR['chuahoc'] }}">
            {{ App\Consts::SCHEDULE_STATUS['chuahoc'] }}
            <input required type="hidden" name="lesson[{{ $count + 1 }}][id]" value="">
        </td>
        <td>
            <div class="form-group d-flex align-items-center">
                <input required name="lesson[{{ $count + 1 }}][date]" type="date"
                    value="{{ date('Y-m-d', time()) }}" class="form-control mr-2">
            </div>
        </td>
        <td>
            <div class="form-group">
                <select name="lesson[{{ $count + 1 }}][period_id]" class="lesson_period form-control select2"
                    style="width: 100%">
                    @foreach ($period as $val)
                        <option value="{{ $val->id }}">
                            {{ $val->iorder }} ({{ $val->start_time }} - {{ $val->end_time }})</option>
                    @endforeach
                </select>
            </div>
        </td>

        <td>
            <div class="form-group">
                <select name="lesson[{{ $count + 1 }}][room_id]" style="width: 100%"
                    class="lesson_period form-control select2  lesson_room_change">
                    @foreach ($room as $val)
                        <option value="{{ $val->id }}">
                            {{ $val->name }}
                        </option>
                    @endforeach

                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <select style="width: 100%" class="lesson_period form-control select2 teacher_id_select" disabled>
                    @foreach ($teacher as $val)
                        @if (isset($class->json_params->teacher) && $class->json_params->teacher == $val->id)
                            <option value="{{ $val->id }}">
                                {{ $val->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <select name="lesson[{{ $count + 1 }}][assistant_teacher][]"
                    class="lesson_period form-control select2" style="width: 100%">
                    <option value="0">@lang('Please select') </option>
                    @foreach ($teacher as $val)
                        <option value="{{ $val->id }}">
                            {{ $val->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </td>
        <td>
            <input type="text" class="form-control" name="lesson[{{ $count + 1 }}][note]">
        </td>
    </tr>
@endif
