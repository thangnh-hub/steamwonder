<input type="hidden" name="class_id" value="{{ $params['class_id'] }}">
<table class="table table-hover table-bordered">
    <thead>
        <tr>
            <th>@lang('STT')</th>
            <th>@lang('Họ và tên')</th>
            <th>@lang('Hình thức thi *')</th>
            <th>@lang('Tổng số kỹ năng')</th>
            <th>@lang('Điểm nghe')</th>
            <th>@lang('Ngày báo điểm')</th>
            <th>@lang('Điểm nói')</th>
            <th>@lang('Ngày báo điểm')</th>
            <th>@lang('Điểm đọc')</th>
            <th>@lang('Ngày báo điểm')</th>
            <th>@lang('Điểm viết')</th>
            <th>@lang('Ngày báo điểm')</th>
            <th>@lang('Giáo viên')</th>
            <th>@lang('Giáo viên phụ')</th>
            <th>@lang('Ghi chú')</th>
            <th>@lang('Action')</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list_student as $item)
            <tr class="valign-middle" id="item_{{ $item->id }}">
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->admin_code }}-{{ $item->name }}</td>
                <td><select class="form-control select2" name="student[{{ $item->id }}][type]">
                        @foreach ($type as $key => $val)
                            <option value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select></td>
                <td><input type="text" class="form-control" name="student[{{ $item->id }}][total_skill]"
                        placeholder="Tổng số kỹ năng" value=""></td>
                <td><input type="text" data-toggle="tooltip" class="form-control"
                        name="student[{{ $item->id }}][score_listen]" data-original-title="@lang('Điểm nghe')"
                        placeholder="@lang('Điểm nghe')" value="{{ old('score_listen') }}">
                </td>
                <td><input type="text" data-toggle="tooltip" data-original-title="@lang('Ngày báo điểm')"
                        class="form-control" name="student[{{ $item->id }}][day_score_listen]"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="@lang('Ngày báo điểm')"
                        value="{{ old('day_score_listen') }}"></td>
                <td><input type="text" data-toggle="tooltip" class="form-control"
                        name="student[{{ $item->id }}][score_speak]" data-original-title="@lang('Điểm nói')"
                        placeholder="@lang('Điểm nói')" value="{{ old('score_speak') }}">
                </td>
                <td><input type="text" data-toggle="tooltip" data-original-title="@lang('Ngày báo điểm')"
                        class="form-control" name="student[{{ $item->id }}][day_score_speak]"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="@lang('Ngày báo điểm')"
                        value="{{ old('day_score_speak') }}"></td>
                <td><input type="text" data-toggle="tooltip" class="form-control"
                        name="student[{{ $item->id }}][score_read]" data-original-title="@lang('Điểm đọc')"
                        placeholder="@lang('Điểm đọc')" value="{{ old('score_read') }}">
                </td>
                <td><input type="text" data-toggle="tooltip" data-original-title="@lang('Ngày báo điểm')"
                        class="form-control" name="student[{{ $item->id }}][day_score_read]"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="@lang('Ngày báo điểm')"
                        value="{{ old('day_score_read') }}"></td>
                <td><input type="text" data-toggle="tooltip" class="form-control"
                        name="student[{{ $item->id }}][score_write]" data-original-title="@lang('Điểm viết')"
                        placeholder="@lang('Điểm viết')" value="{{ old('score_write') }}"></td>
                <td><input type="text" data-toggle="tooltip" data-original-title="@lang('Ngày báo điểm')"
                        class="form-control" name="student[{{ $item->id }}][day_score_write]"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="@lang('Ngày báo điểm')"
                        value="{{ old('day_score_write') }}"></td>
                <td><select class="form-control select2" name="student[{{ $item->id }}][teacher_id]">
                        <option value=""> @lang('Please choose')</option>
                        @foreach ($teacher as $key => $val)
                            <option value="{{ $val->id }}">
                                {{ $val->name }}</option>
                        @endforeach
                    </select></td>
                <td><select class="form-control select2" name="student[{{ $item->id }}][assistant_teacher_id]">
                        <option value=""> @lang('Please choose')</option>
                        @foreach ($teacher as $key => $val)
                            <option value="{{ $val->id }}">
                                {{ $val->name }}</option>
                        @endforeach
                    </select></td>
                <td>
                    <textarea class="form-control" name="student[{{ $item->id }}][json_params][note]" rows="3"></textarea>
                </td>
                <td><button class="btn btn-sm btn-danger" type="button"
                        onclick="delete_items('#item_{{ $item->id }}')" data-toggle="tooltip" title=""
                        data-original-title="Xóa" aria-describedby="tooltip314225">
                        <i class="fa fa-trash"></i>
                    </button></td>
            </tr>
        @endforeach
    </tbody>
</table>
