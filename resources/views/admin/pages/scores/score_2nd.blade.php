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
@push('style')
    <style>
        table {
            max-width: unset !important;
            min-width: 1200px;
        }

        table .btn {
            width: 100%;
        }

        .input-with-suffix {
            position: relative;
        }

        .input-suffix {
            position: absolute;
            right: 30px;
            top: 8px;
        }
    </style>
@endpush
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
            <div style="display: none" class="col-md-2">
                <div class="form-group">
                    <label>@lang('Class')</label>
                    <input name="class_id" type="text" value="{{ isset($this_class) ? $this_class->id : '' }}">
                </div>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">{{ isset($this_class) ? 'Thông tin lớp ' . $this_class->name : 'Thông tin lớp' }}</h3>
            </div>
            @if (isset($this_class) && $this_class != null)
                @php
                    $quantity_student = \App\Models\UserClass::where('class_id', $this_class->id)->get()->count();
                    $teacher = \App\Models\Teacher::where('id', $this_class->json_params->teacher ?? 0)->first();
                    if ($this_class->assistant_teacher !== null && $this_class->assistant_teacher !== ' ') {
                        $assistantTeacherArray = json_decode($this_class->assistant_teacher, true);
                    }
                    $list = '';
                @endphp
                <div class="d-flex-wap box-header">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Lớp học: </strong></label>
                            <span>{{ $this_class->name }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên: </strong></label>
                            <span>{{ $teacher->name ?? '' }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Sĩ số: </strong></label>
                            <span> {{ $quantity_student }} </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Trình độ: </strong></label>
                            <span>{{ $this_class->level->name ?? '' }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Giảng viên phụ: </strong></label>
                            @foreach ($list_teacher as $val)
                                @if (isset($assistantTeacherArray) && in_array($val->id, $assistantTeacherArray))
                                    @php
                                        $list .= $val->name . ',';
                                    @endphp
                                @endif
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
                            <span>{{ $this_class->syllabus->name ?? '' }}</span>
                        </div>

                        <div class="form-group">
                            <label><strong>Phòng học: </strong></label>
                            <span>{{ $this_class->room->name ?? '' }} (Khu vực:
                                {{ $this_class->area->name ?? '' }})</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Bắt đầu | Kết thúc: </strong></label>
                            <span> {{ date('d-m-Y', strtotime($this_class->day_start)) }} |
                                {{ date('d-m-Y', strtotime($this_class->day_end)) }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Khóa học: </strong></label>
                            <span>{{ $this_class->course->name ?? '' }}</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ca học: </strong></label>
                            <span>{{ $this_class->period->iorder }} ({{ $this_class->period->start_time ?? '' }} -
                                {{ $this_class->period->end_time ?? '' }})</span>
                        </div>
                        <div class="form-group">
                            <label><strong>Ngày thi: </strong></label>
                            <span>{{ $this_class->day_exam != '' ? date('d-m-Y', strtotime($this_class->day_exam)) : '' }}
                            </span>
                        </div>
                        @if (count($rows) > 0)
                            <div class="form-group">
                                <form style="margin-right: 10px" class="" action="{{ route('export_score') }}"
                                    method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="class_id"
                                        value="{{ isset($params['class_id']) ? $params['class_id'] : '' }}">
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i>
                                        @lang('Export bảng điểm')</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">@lang('Danh sách học viên') (Điểm thi lần 2)
                </h3>
                @isset($languages)
                    @foreach ($languages as $item)
                        @if ($item->is_default == 1 && $item->lang_locale != Request::get('lang'))
                            @if (Request::get('lang') != '')
                                <a class="text-primary pull-right" href="{{ route('evaluation_class.index') }}"
                                    style="padding-left: 15px">
                                    <i class="fa fa-language"></i> {{ __($item->lang_name) }}
                                </a>
                            @endif
                        @else
                            @if (Request::get('lang') != $item->lang_locale)
                                <a class="text-primary pull-right"
                                    href="{{ route('evaluation_class.index') }}?lang={{ $item->lang_locale }}"
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
                    <form action="{{ route('scores.save_2nd') }}" method="POST"
                        onsubmit="return confirm('@lang('Duyên Đỗ lưu ý đến bạn: Bạn có chắc chắn thực hiện thao tác này?')')">
                        @csrf
                        <input type="hidden" name="class_id"
                            value="{{ isset($params['class_id']) ? $params['class_id'] : '' }}">
                        <div style="padding-left: 0px" class="form-group col-md-4">
                            <label> Ngày thi lần 2<span class="text-danger">*</span></label>
                            <input required type="date" name="day_exam2" class="form-control"
                                value="{{ $this_class->day_exam2 ?? '' }}">
                        </div>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Student')</th>
                                    <th>@lang('Main class')</th>
                                    <th style="width: 150px;">@lang('Score listen') (Trọng số:
                                        {{ $this_class->syllabus->json_params->score->listen->weight ?? '' }})</th>
                                    <th style="width: 150px;">@lang('Score speak') (Trọng số:
                                        {{ $this_class->syllabus->json_params->score->speak->weight ?? '' }})</th>
                                    <th style="width: 150px;">@lang('Score read') (Trọng số:
                                        {{ $this_class->syllabus->json_params->score->read->weight ?? '' }})</th>
                                    <th style="width: 150px;">@lang('Score write') (Trọng số:
                                        {{ $this_class->syllabus->json_params->score->write->weight ?? '' }})</th>
                                    <th style="width: 210px;">@lang('Average')</th>
                                    {{-- <th>@lang('Evaluations')</th> --}}
                                    <th>@lang('Xếp loại')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    @php
                                        $syllabus_id = $row->class->syllabus_id;
                                        $syllabus = \App\Models\Syllabus::find($syllabus_id);
                                        if (isset($syllabus->json_params)) {
                                            $listen_weight = $syllabus->json_params->score->listen->weight ?? 25;
                                            $speak_weight = $syllabus->json_params->score->speak->weight ?? 25;
                                            $read_weight = $syllabus->json_params->score->read->weight ?? 25;
                                            $write_weight = $syllabus->json_params->score->write->weight ?? 25;
                                            $listen_min = $syllabus->json_params->score->listen->min ?? 60;
                                            $speak_min = $syllabus->json_params->score->speak->min ?? 60;
                                            $read_min = $syllabus->json_params->score->read->min ?? 60;
                                            $write_min = $syllabus->json_params->score->write->min ?? 60;
                                        }
                                    @endphp
                                    <tr class="valign-middle">
                                        <td>
                                            {{ $loop->index + 1 }}
                                            <input type="hidden" name="list[{{ $row->id }}][id]"
                                                value="{{ $row->id }}">
                                            <input type="hidden" name="list[{{ $row->id }}][level]"
                                                value="{{ $this_class->level->id ?? '' }}">
                                            <input type="hidden" name="list[{{ $row->id }}][json_params][note]"
                                                value="{{ $row->json_params->note ?? '' }}">
                                            <input type="hidden"
                                                name="list[{{ $row->id }}][json_params][exam_1st][score_listen]"
                                                value="{{ $row->json_params->exam_1st->score_listen ?? $row->score_listen }}">
                                            <input type="hidden"
                                                name="list[{{ $row->id }}][json_params][exam_1st][score_speak]"
                                                value="{{ $row->json_params->exam_1st->score_speak ?? $row->score_speak }}">
                                            <input type="hidden"
                                                name="list[{{ $row->id }}][json_params][exam_1st][score_read]"
                                                value="{{ $row->json_params->exam_1st->score_read ?? $row->score_read }}">
                                            <input type="hidden"
                                                name="list[{{ $row->id }}][json_params][exam_1st][score_write]"
                                                value="{{ $row->json_params->exam_1st->score_write ?? $row->score_write }}">
                                        </td>
                                        <td>
                                            {{ $row->student->admin_code ?? '' }}
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('students.show', $row->student->id) }}">{{ $row->student->name ?? '' }}</a>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('classs.edit', $row->class->id) }}">{{ $row->class->name ?? '' }}</a>
                                        </td>
                                        <td>
                                            <div class="input-with-suffix">
                                                <input data-class-id="{{ $row->class->id }}"
                                                    data-id="{{ $row->id }}" type="number"
                                                    class="form-control score-input listen"
                                                    name="list[{{ $row->id }}][score_listen]"
                                                    value="{{ $row->json_params->exam_2nd->score_listen ?? '' }}"
                                                    min="0" max="1000"
                                                    data-weight="{{ $listen_weight ?? 25 }}">
                                                <input type="hidden"
                                                    name="list[{{ $row->id }}][score_listen_weight]"
                                                    value="{{ $listen_weight ?? 25 }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-with-suffix">
                                                <input data-class-id="{{ $row->class->id }}"
                                                    data-id="{{ $row->id }}" type="number"
                                                    class="form-control score-input speak"
                                                    name="list[{{ $row->id }}][score_speak]"
                                                    value="{{ $row->json_params->exam_2nd->score_speak ?? '' }}"
                                                    min="0" max="1000"
                                                    data-weight="{{ $speak_weight ?? 25 }}">
                                                <input type="hidden"
                                                    name="list[{{ $row->id }}][score_speak_weight]"
                                                    value="{{ $speak_weight ?? 25 }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-with-suffix">
                                                <input data-class-id="{{ $row->class->id }}"
                                                    data-id="{{ $row->id }}" type="number"
                                                    class="form-control score-input read"
                                                    name="list[{{ $row->id }}][score_read]"
                                                    value="{{ $row->json_params->exam_2nd->score_read ?? '' }}"
                                                    min="0" max="1000"
                                                    data-weight="{{ $read_weight ?? 25 }}">
                                                <input type="hidden" name="list[{{ $row->id }}][score_read_weight]"
                                                    value="{{ $read_weight ?? 25 }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-with-suffix">
                                                <input data-class-id="{{ $row->class->id }}"
                                                    data-id="{{ $row->id }}" type="number"
                                                    class="form-control score-input write"
                                                    name="list[{{ $row->id }}][score_write]"
                                                    value="{{ $row->json_params->exam_2nd->score_write ?? '' }}"
                                                    min="0" max="1000"
                                                    data-weight="{{ $write_weight ?? 25 }}">
                                                <input type="hidden"
                                                    name="list[{{ $row->id }}][score_write_weight]"
                                                    value="{{ $write_weight ?? 25 }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-with-suffix">
                                                <input type="text" class="form-control"
                                                    name="list[{{ $row->id }}][json_params][score_average]"
                                                    value="{{ $row->json_params->score_average ?? '' }}" min="0"
                                                    max="1000" id="average_{{ $row->id }}" readonly>
                                            </div>
                                        </td>

                                        <td>
                                            <label
                                                class="btn {{ $row->status != '' ? App\Consts::ranked_academic_color[$row->status] ?? '' : 'Chưa xác định' }}">
                                                {{ $row->status != '' ? App\Consts::ranked_academic_total[$row->status] ?? $row->status : 'Chưa xác định' }}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-info">
                            <i class="fa fa-save"></i>
                            @lang('Lưu và xếp loại')
                        </button>

                    </form>
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
        var scoreInputs = document.querySelectorAll('.score-input');
        scoreInputs.forEach(function(input) {
            input.addEventListener("change", function() {
                var selectedScore = parseFloat(this.value); // Lấy giá trị số từ phần tử đang thay đổi

                var minScore = 0;
                var maxScore = 1000;

                if (selectedScore < minScore) {
                    this.value = minScore;
                } else if (selectedScore > maxScore) {
                    this.value = maxScore;
                }
            });
        });
    </script>
@endsection
