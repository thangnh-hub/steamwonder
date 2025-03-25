@extends('admin.layouts.app')


@section('title')
    @lang($module_name)
@endsection

@section('style')
    <style>
        .day-repeat-select {
            pointer-events: none;
        }

        .modal-header {
            display: flex;
            align-items: center;
            color: #fff;
            background-color: #00A157;
        }

        .pointer-none {
            pointer-events: none;
            background: #eee;
        }

        .link_doc a {
            text-decoration: underline !important;
        }

        .bg-highlight {
            background: #367fa9;
            color: #fff !important;
        }

        .mr-2 {
            margin-right: 10px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .table_leson .select2-container {
            width: 100% !important;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .overflow-auto {
            width: 100%;
            overflow-x: auto;
        }

        .overflow-auto::-webkit-scrollbar {
            width: 5px !important;
        }

        .overflow-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-auto::-webkit-scrollbar-thumb {
            background: rgb(107, 144, 218);
            border-radius: 10px;
        }

        .table_leson {
            width: 1600px;
            max-width: unset;
        }

        .table_leson td:first-child {
            width: 190px;
        }

        .table_leson thead {
            background: rgb(107, 144, 218);
            color: #fff
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
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
        <form role="form" action="{{ route('class.updateByTeacher', ['class_id' => $detail->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('Update form')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">

                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5>Lịch học</h5>
                                        </a>
                                    </li>

                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> @lang('Save')
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_2">
                                        <div class="overflow-auto mt-15">
                                            <table class="table  table-bordered table_leson">
                                                <thead>
                                                    <tr>
                                                        <th>@lang('Lesson')</th>
                                                        <th>@lang('Status')</th>
                                                        <th>@lang('Trạng thái chuyển giao')</th>
                                                        <th>@lang('Date-time')</th>
                                                        <th>@lang('Period')</th>
                                                        <th>@lang('Room')</th>
                                                        <th>@lang('Teacher')</th>
                                                        <th>@lang('Giáo viên phụ')</th>
                                                        <th>@lang('Ghi chú')</th>
                                                    </tr>
                                                </thead>
                                                @if (isset($list_lesson))
                                                    <tbody class="lesson_body">
                                                        @foreach ($list_lesson as $key => $lesson)
                                                            <tr
                                                                class="  {{ $lesson->is_add_more == 1 ? 'bg-highlight' : '' }}">
                                                                <td>
                                                                    {{ ++$key }}
                                                                    {{ $lesson->is_add_more == 1 ? '(Bổ sung)' : '' }}
                                                                </td>
                                                                <td
                                                                    class="{{ App\Consts::SCHEDULE_STATUS_COLOR[$lesson->status] }}">
                                                                    {{ App\Consts::SCHEDULE_STATUS[$lesson->status] }}
                                                                    <input type="hidden"
                                                                        name="lesson[{{ $key }}][id]"
                                                                        value="{{ $lesson->id }}">
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][transfer_status]"
                                                                            class="form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($transfer_status as $keys => $val)
                                                                                <option
                                                                                    {{ isset($lesson->transfer_status) && $lesson->transfer_status == $keys ? 'selected' : '' }}
                                                                                    value="{{ $keys }}">
                                                                                    {{ __($val) }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div class="form-group d-flex align-items-center">
                                                                        <input name="lesson[{{ $key }}][date]"
                                                                            type="date" value="{{ $lesson->date }}"
                                                                            class="form-control mr-2 {{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }}">
                                                                        <label>{{ date('l', strtotime($lesson->date)) }}</label>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][period_id]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
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
                                                                        <select name="lesson[{{ $key }}][room_id]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_room_change lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($room as $val)
                                                                                @if ($val->area_id == $lesson->area_id)
                                                                                    <option
                                                                                        {{ isset($lesson->room_id) && $lesson->room_id == $val->id ? 'selected' : '' }}
                                                                                        value="{{ $val->id }}">
                                                                                        {{ $val->name }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][teacher_id]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} teacher_id_select lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
                                                                            @foreach ($teacher as $val)
                                                                                <option
                                                                                    {{ isset($lesson->teacher_id) && $lesson->teacher_id == $val->id ? 'selected' : '' }}
                                                                                    value="{{ $val->id }}">
                                                                                    {{ $val->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                </td>
                                                                <td>
                                                                    @php
                                                                        if (
                                                                            $lesson->assistant_teacher !== null &&
                                                                            $lesson->assistant_teacher !== ' '
                                                                        ) {
                                                                            $assistantTeacherArray = json_decode(
                                                                                $lesson->assistant_teacher,
                                                                                true,
                                                                            );
                                                                        }
                                                                    @endphp
                                                                    <div class="form-group">
                                                                        <select
                                                                            name="lesson[{{ $key }}][assistant_teacher][]"
                                                                            class="{{ $lesson->status == 'dadiemdanh' ? 'pointer-none' : '' }} lesson_period form-control select2"
                                                                            {{ $lesson->status == 'dadiemdanh' ? 'disabled' : '' }}>
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
                                                                    <input type="text" class="form-control"
                                                                        name="lesson[{{ $key }}][note]"
                                                                        value="{{ $lesson->json_params->note ?? '' }}">
                                                                </td>

                                                            </tr>
                                                            {{-- @endif --}}
                                                        @endforeach
                                                    </tbody>
                                                @endif
                                            </table>
                                            {{-- <button data-lesson="{{ $key }}"
                                                class="form-group btn btn-primary mb-2 add_lesson" type="button"><i
                                                    class="fa fa-plus"></i> @lang(' Thêm buổi học')</button> --}}
                                        </div>

                                    </div>
                                </div>
                            </div><!-- /.tab-content -->
                        </div><!-- nav-tabs-custom -->

                    </div>
                </div>
            </div>
        </form>
    </section>

@endsection

@section('script')
    <script>
        function _delete_lesson(th) {
            $(th).parents('.more_lesson').remove();
        }
        $('.del_lesson').click(function(e) {
            e.preventDefault();
            var _this = $(this);
            var schedule_id = _this.attr("data-lesson-id");
            let url = "{{ route('ajax.lessonDestroy') }}/";

            // Thêm hộp thoại xác nhận
            if (confirm("Bạn có chắc chắn muốn xóa buổi học này không?")) {
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        schedule_id: schedule_id,
                    },
                    success: function(response) {
                        if (response.message == 'success') {
                            alert('Xóa buổi học thành công!');
                            _this.parents('tr').fadeOut(800, function() {
                                _this.parents('tr').remove();
                            });
                        } else {
                            alert('Không thể xóa buổi học!');
                        }
                    },
                    error: function(response) {
                        // Lấy lỗi
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });

        $('.add_lesson').click(function() {
            var _count = Number($(this).attr('data-lesson'));
            var _html = `<tr class="more_lesson">
                <td>
                     <button onclick="_delete_lesson(this)" type="button" class="btn btn-sm btn-danger">Xóa</button>
                </td>
                <td class="{{ App\Consts::SCHEDULE_STATUS_COLOR['chuahoc'] }}">
                    {{ App\Consts::SCHEDULE_STATUS['chuahoc'] }}
                    <input required type="hidden" name="lesson[` + (_count + 1) + `][id]" value="">
                </td>
                <td>
                    <div class="form-group d-flex align-items-center">
                        <input required name="lesson[` + (_count + 1) + `][date]" type="date" value="" class="form-control mr-2">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][period_id]" class="lesson_period form-control select2">
                            @foreach ($period as $val)
                                <option {{ isset($detail->period_id) && $detail->period_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                    {{ $val->iorder }} ({{ $val->start_time }} - {{ $val->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                </td>

                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][room_id]" class="lesson_period form-control select2  lesson_room_change">
                            @foreach ($room as $val)
                                @if (isset($lesson->area_id) && $val->area_id == $lesson->area_id)
                                <option {{ isset($lesson->room_id) && $lesson->room_id == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <select name="lesson[` + (_count + 1) + `][teacher_id]" class="lesson_period form-control select2 teacher_id_select">
                            @foreach ($teacher as $val)
                                <option {{ isset($detail->json_params->teacher) && $detail->json_params->teacher == $val->id ? 'selected' : '' }} value="{{ $val->id }}">
                                    {{ $val->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </td>


                <td>
                    <div class="form-group">
                        <select
                            name="lesson[{{ $key }}][assistant_teacher][]"
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
                    <input type="text" class="form-control" name="lesson[` + (_count + 1) + `][note]">
                </td>
            </tr>`;
            $('.lesson_body').append(_html);
            $(".select2").select2();
            $('.lfm').filemanager('other', {
                // prefix: route_prefix
                prefix: '{{ route('ckfinder_browser') }}'
            });
            $('.add_lesson').attr('data-lesson', _count + 1);
        })
    </script>
@endsection
