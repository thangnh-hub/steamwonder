<input type="hidden" name="id" value="{{ $history->id }}">

@if ($history->type == 'change_status_student')
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Trạng thái cũ</th>
                <th>Trạng thái mới</th>
                <th>Ngày cập nhật</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody class="box_history">
            <tr class="valign-middle">
                <td>
                    <select class="status_study_old  form-control select2" name="status_study_old" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($status_student as $val)
                            <option
                                {{ isset($history->status_study_old) && $history->status_study_old == $val->id ? 'selected' : '' }}
                                value="{{ $val->id }}">
                                @lang($val->name)</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="status_study_new form-control select2" name="status_study_new" style="width:100%">
                        @foreach ($status_student as $val)
                            <option
                                {{ isset($history->status_study_new) && $history->status_study_new == $val->id ? 'selected' : '' }}
                                value="{{ $val->id }}">
                                @lang($val->name)</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="date" class="updated_at form-control" name="updated_at"
                        value="{{ date('Y-m-d', strtotime($history->updated_at)) ?? date('Y-m-d', time()) }}">
                </td>
                <td>
                    <textarea class="form-control add_note_status_study" name="json_params[note_status_study]">{{ isset($history->json_params->note_status_study) && $history->json_params->note_status_study != '' ? $history->json_params->note_status_study : '' }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
@else
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Lớp cũ</th>
                <th>Lớp mới</th>
                <th>Ngày vào lớp(Nếu có)</th>
                <th>Trạng thái vào lớp(Nếu có)</th>
                <th>Ngày cập nhật</th>
                <th>Ghi chú</th>
            </tr>
        </thead>
        <tbody class="box_history">
            <tr class="valign-middle">
                <td>
                    <select class="form-control select2" name="class_id_old" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($class as $val)
                            <option
                                {{ isset($history->class_id_old) && $history->class_id_old == $val->id ? 'selected' : '' }}
                                value="{{ $val->id }}">
                                @lang($val->name)</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control select2" name="class_id_new" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($class as $val)
                            <option
                                {{ isset($history->class_id_new) && $history->class_id_new == $val->id ? 'selected' : '' }}
                                value="{{ $val->id }}">
                                @lang($val->name)</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="date" class="form-control" name="json_params['day_in_class']"
                        value="{{ isset($history->json_params->day_in_class) && $history->json_params->day_in_class != '' ? date('Y-m-d', strtotime($history->json_params->day_in_class)) : '' }}">
                </td>
                <td>
                    <select class="form-control select2" name="status_change_class" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($user_class_status as $key => $val)
                            <option
                                {{ isset($history->status_change_class) && $history->status_change_class == $key ? 'selected' : '' }}
                                value="{{ $key }}">
                                @lang($val)</option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input type="date" class="form-control" name="updated_at"
                        value="{{ date('Y-m-d', strtotime($history->updated_at)) ?? date('Y-m-d', time()) }}">
                </td>
                <td>
                    <textarea class="form-control add_note_status_study" name="json_params[note_status_study]">{{ isset($history->json_params->note_status_study) && $history->json_params->note_status_study != '' ? $history->json_params->note_status_study : '' }}</textarea>
                </td>
            </tr>
        </tbody>
    </table>
@endif
