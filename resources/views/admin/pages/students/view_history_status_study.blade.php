@if ($type == 'tr')
    <tr class="valign-middle">
        <td></td>
        <td>{{ $rows_end->user->name ?? ($student->name ?? '') }}</td>
        <td>
            <select class="status_study_old  form-control select2" style="width:100%">
                <option value="">@lang('Please select')</option>
                @foreach ($status_student as $val)
                    <option
                        {{ isset($rows_end->status_study_new) && $rows_end->status_study_new == $val->id ? 'selected' : '' }}
                        value="{{ $val->id }}">
                        @lang($val->name)</option>
                @endforeach
            </select>
        </td>
        <td>
            <select class="status_study_new form-control select2" style="width:100%">
                <option value="">@lang('Please select')</option>
                @foreach ($status_student as $val)
                    <option value="{{ $val->id }}">
                        @lang($val->name)</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="date" class="updated_at form-control" value="{{ date('Y-m-d', time()) }}">
        </td>
        <td>
            <textarea class="form-control add_note_status_study"></textarea>
        </td>
        <td>
            <button data-id="{{ $rows_end->user->id ?? ($student->id ?? '') }}" type="button"
                class="btn btn-danger btn-sm btn_add_status_study">
                <i class="fa fa-save"></i> @lang('Lưu lại')
            </button>
        </td>
    </tr>
@else
    @if (isset($rows) && count($rows) > 0)
        @foreach ($rows as $item)
            <tr class="valign-middle">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->user->name }}</td>
                <td>
                    <select class="status_study_old  form-control select2" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($status_student as $val)
                            <option
                                {{ isset($item->status_study_old) && $item->status_study_old == $val->id ? 'selected' : '' }}
                                value="{{ $val->id }}">
                                @lang($val->name)</option>
                        @endforeach
                    </select>

                <td>
                    <select class="status_study_new  form-control select2" style="width:100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($status_student as $val)
                            <option
                                {{ isset($item->status_study_new) && $item->status_study_new == $val->id ? 'selected' : '' }}
                                value="{{ $val->id }}">
                                @lang($val->name)</option>
                        @endforeach
                    </select>
                <td>
                    <input type="date" class="form-control date_updated" value="{{ $item->updated_at_new }}">
                </td>
                <td>
                    <textarea class="form-control note_status_study">{{ $item->json_params->note_status_study ?? '' }}</textarea>
                </td>
                <td>
                    <button data-id="{{ $item->id }}" onclick="updateHistory(this)" type="button"
                        class="btn btn-info btn-sm">
                        <i class="fa fa-save"></i> @lang('Save')
                    </button>

                    <button data-id="{{ $item->id }}"
                        data-url = "{{ route('student.delete_history_statusstudy') }}" onclick="deleteHistory(this)"
                        type="button" class="btn btn-sm btn-danger">
                        <i class="fa fa-trash"></i> @lang('Delete')
                    </button>
                </td>
            </tr>
        @endforeach
    @endif
@endif
