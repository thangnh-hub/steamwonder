<div class="box-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>@lang('Keyword') </label>
                <input type="text" class="form-control keyword" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>@lang('Class')</label>
                <select name="class_id" class="class_id form-control select2" style="width: 100%;">
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
                    <button class="btn btn-primary btn-sm mr-10 btn_filter">@lang('Submit')</button>
                </div>
            </div>
        </div>
    </div>
</div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th style="width: 40px">#</th>
            <th>@lang('Student code')</th>
            <th>@lang('Name')</th>
            <th>@lang('CCCD')</th>
            <th>@lang('Phone')</th>
            <th>@lang('Email')</th>
            <th>@lang('Lớp')</th>
        </tr>
    </thead>
    <tbody id="list_student_class">
        @foreach ($list_student as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td><a target="_blank" title="Xem chi tiết"
                        href="{{ route('students.show', $item->id) }}">{{ $item->admin_code }}
                    </a></td>
                <td>{{ $item->name ?? '' }}</td>
                <td>{{ $item->json_params->cccd ?? 'Chưa cập nhật' }}</td>
                <td>{{ $item->phone ?? 'Chưa cập nhật' }}</td>
                <td>{{ $item->email }}</td>
                <td>
                    @if ($item->classs)
                        <ul>
                            @foreach ($item->classs as $val_class)
                                <li>{{ $val_class->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
