
<div class="col-md-12">
    <div class="box box-primary box_detail">
        <div class="box-header with-border">
            <h3 class="box-title" id="title-list">@lang('Danh sách học viên đủ điều kiện')</h3>
            <div class="pull-right" style="width: 50%">
                <div class="row">
                    <div class="col-md-4"><select onchange="search_js(this,'tr_filter')"
                            class="form-control select2 search_course" style="width: 100%">
                            <option value="">@lang('Chọn khóa học')</option>
                            @foreach ($course as $val)
                                <option value="{{ $val->name }}">
                                    {{ $val->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select onchange="search_js(this,'tr_filter')" class="form-control select2 search_class"
                            style="width: 100%">
                            <option value="">@lang('Chọn lớp học')</option>
                            @foreach ($classs as $class)
                                <option value="{{ $class->name }}">
                                    {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input type="text" class="form-control search_name"
                            onkeyup="search_js(this,'tr_filter')" placeholder="Họ tên hoặc Mã học viên"></div>
                </div>
            </div>
        </div>
        <div class="table-responsive mailbox-messages table_scroll">
            <table class="table table-bordered table_scroll">
                <thead>
                    <tr>
                        <th>@lang('Mã HV')</th>
                        <th>@lang('Họ tên')</th>
                        <th>@lang('Khóa học')</th>
                        <th>@lang('Lớp')</th>
                        <th>@lang('Trình độ')</th>
                        <th>@lang('Tên sách')</th>
                        <th>@lang('Cấp sách')</th>
                    </tr>
                </thead>
                <tbody class="table_detail">
                    @isset($students)
                        @foreach ($students as $val)
                            <tr class="tr_filter">
                                <td>{{ $val->student->admin_code }}</td>
                                <td class="student_name">
                                    {{ $val->student->name ?? '' }}</td>
                                <td class="course_name">
                                    {{ $val->student->course->name ?? '' }}</td>
                                <td class="class_name">{{ $val->class->name ?? '' }}</td>
                                <td>{{ $val->level->name ?? '' }}</td>
                                <td>{{ $val->product->name ?? '' }}</td>
                                <td><input type="checkbox" class="active_book book_class_{{ $val->class_id }}"
                                        data-class="{{ $val->class_id }}" name="book[]"
                                        value="{{ $val->id . '-' . $val->product_id.'-'.$val->class_id }}">
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
</div>
