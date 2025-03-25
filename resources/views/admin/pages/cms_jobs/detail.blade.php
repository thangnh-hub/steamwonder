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

        @if ($detail->time_expired != '' && date('Y-m-d', strtotime($detail->time_expired)) < date('Y-m-d', time()))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                @lang('Tin này đã hết hạn')
            </div>
        @endif

        <div class="box box-default">
            <div class="box-header">
                <h3 class="">{{ $detail->job_title ?? '' }}</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="form-horizontal">
                    <p class="job"><strong>@lang('Lịch phỏng vấn'):</strong>
                        {{ $detail->time_interview != '' ? date('Y-m-d', strtotime($detail->time_interview)) : 'Đang cập nhật' }}
                    </p>
                    <p class="job"><strong>@lang('Day_expried'):</strong>
                        {{ $detail->time_expired != '' ? date('Y-m-d', strtotime($detail->time_expired)) : 'Đang cập nhật' }}
                    </p>
                    <div class="content">
                        {!! $detail->json_params->content !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                </div>

                <h3 class="box-title">@lang('Danh sách ứng tuyển')</h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Link CV học viên')</th>
                            <th>@lang('Lịch Test')</th>
                            <th>@lang('Lịch ôn luyện')</th>
                            <th>@lang('Người tạo')</th>
                            <th>@lang('Kết quả phỏng vấn')</th>
                            <th>@lang('Ghi chú')</th>
                            @if ($admin_auth->admin_type == 'diplomatic')
                                <th>@lang('Action')</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="box_user_action">
                        @if (isset($user_actions) && count($user_actions) > 0)
                            @foreach ($user_actions as $row)
                                @php
                                    // lịch test mới nhất
                                    $params['schedule_test'] = explode(' ,', trim($row->id_schedule_test));
                                    $params['is_type'] = 'test';
                                    $test = App\Models\ScheduleTest::getSqlScheduleTime($params)->first();
                                    // lịch training mới nhất
                                    $params['is_type'] = 'training';
                                    $training = App\Models\ScheduleTest::getSqlScheduleTime($params)->first();
                                @endphp

                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px;">{{ $row->json_params->name ?? '' }}</strong>
                                    </td>
                                    <td>
                                        <a href="{{ $row->json_params->link_cv ?? '#' }}"
                                            target="_blank">{{ $row->json_params->link_cv ?? 'Chưa cập nhật' }}</a>
                                    </td>
                                    <td>
                                        {{ isset($test->time) && $test->time != '' ? date('d-m-Y', strtotime($test->time)) : 'Chưa cập nhật' }}
                                    </td>
                                    <td>
                                        {{ isset($training->time) && $training->time != '' ? date('d-m-Y', strtotime($training->time)) : 'Chưa cập nhật' }}
                                    </td>
                                    <td>
                                        {{ $row->admin_name ?? '' }}
                                    </td>

                                    <td>
                                        <select name="result" class="form-select select2 select_result"
                                            style="width: 100%;" required>
                                            <option value="" selected disabled>@lang('Chọn kết quả ')</option>
                                            @foreach ($type_result as $key => $val)
                                                <option value="{{ $key }}"
                                                    {{ $row->result_interview == $key ? 'selected' : '' }}>
                                                    @lang($val)</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <textarea name="note" class="form-control note_result" style="min-width: 200px" rows="5"
                                            placeholder="Nhận xét">{{ $row->comment_user_action ?? '' }}</textarea>
                                    </td>
                                    @if ($admin_auth->admin_type == 'diplomatic')
                                        <td>
                                            <button type="button" class="btn btn-success btn_submit btn-result"
                                                data-id="{{ $row->id }}">@lang('Lưu kết quả ')</button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <h3 class="box-title">@lang('Thêm mới hồ sơ ứng tuyển')</h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Link CV học viên')</th>
                            <th>@lang('Lịch Test')</th>
                            <th>@lang('Lịch ôn luyện')</th>
                            <th>@lang('Người tạo')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{ route('apply.job') }}" method="post" id="form_apply">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ Request::segment(3) }}">
                            <tr class="valign-middle">
                                <td>
                                    <input type="text" class="form-control" name="json_params[name]" value=""
                                        style="min-width: 200px" placeholder="Tên học viên *" required>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="json_params[link_cv]" value=""
                                        placeholder="Link CV học viên *" required>
                                </td>
                                <td>
                                    <select name="json_schedule[tets]" class="form-select select2 select_tets"
                                        style="width: 100%;">
                                        {{-- <option value="" selected>@lang('Chọn lịch test ')</option> --}}
                                        @foreach ($schedule_test as $items_test)
                                            @if ($items_test->is_type == 'test')
                                                <option value="{{ $items_test->id }}">
                                                    Ngày {{ date('d-m-Y', strTotime($items_test->time)) }}. Còn
                                                    {{ $items_test->slot - $items_test->total }} chỗ</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="json_schedule[training]" class="form-select select2 select_training"
                                        style="width: 100%;">
                                        {{-- <option value="" selected>@lang('Chọn lịch ôn luyện ')</option> --}}
                                        @foreach ($schedule_test as $items_test)
                                            @if ($items_test->is_type == 'training')
                                                <option value="{{ $items_test->id }}">
                                                    Ngày {{ date('d-m-Y', strTotime($items_test->time)) }}. Còn
                                                    {{ $items_test->slot - $items_test->total }} chỗ</option>
                                            @endif
                                        @endforeach
                                    </select>

                                </td>
                                <td>
                                    <button type="submit" class="btn btn-warning btn_submit">@lang('Thêm hồ sơ')</button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>


            </div>
            <div class="box-footer clearfix">
                <a class="btn btn-success btn-sm" href="{{ route('jobs' . '.index') }}">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
            </div>
        </div>

    </section>

@endsection
@section('script')
    <script>
        $("#form_apply").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            form.find('.btn_submit').html("@lang('Đang gửi')...");
            var html_job = '';
            var html_time_test = '';
            var html_time_training = '';
            var view_job = $('.box_user_action')
            var time_test = $('.select_tets')
            var time_training = $('.select_training')
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    form[0].reset();
                    form.find('.btn_submit').html("@lang('Thêm hồ sơ')");
                    let jobs = response.data.job
                    // append đơn ứng tuyển
                    html_job = '<tr class="valign-middle">';
                    html_job += '<td><strong style="font-size: 14px;">' + jobs.name + '</strong></td>';
                    html_job += '<td>' + jobs.link_cv + '</td>';
                    html_job += '<td>' + jobs.time_test + '</td>';
                    html_job += '<td>' + jobs.time_training + '</td>';
                    html_job += '<td>' + jobs.admin_name + '   </td>';
                    html_job +=
                        '<td> <select name="result" class="form-select select2 select_result" style="width: 100%;" required>';
                    html_job += '<option value="" selected disabled>@lang('Chọn kết quả')</option>';
                    html_job += '@foreach (App\Consts::RESULT_INTERVIEW as $key => $item)';
                    html_job += '<option value="{{ $key }}">@lang($item)</option>';
                    html_job += '@endforeach';
                    html_job += '</select> </td>';
                    html_job +=
                        '<td> <textarea name="note" class="form-control note_result" style="min-width: 200px" rows="5" placeholder="Nhận xét"></textarea></td>';
                    @if ($admin_auth->admin_type == 'diplomatic')
                        html_job +=
                            '<td><button type="button"class="btn btn-success btn_submit btn-result" data-id="' +
                            jobs.id + '">@lang('Lưu kết quả ')</button></td>';
                    @endif
                    html_job += '</tr>';

                    // cập nhật lại thời gian test-luyện
                    $.each(response.data.schedule_test, function(index, val) {
                        if (val.is_type == 'test') {
                            html_time_test += '<option value="' + val.id + '">Ngày ' +
                                formatDate(val.time) + '. Còn ' + (val.slot - val.total) +
                                ' chỗ</option>';
                        } else {
                            html_time_training += '<option value="' + val.id + '">Ngày ' +
                                formatDate(val.time) + '. Còn ' + (val.slot - val.total) +
                                ' chỗ</option>';
                        }
                    });

                    view_job.append(html_job);
                    time_test.html(html_time_test);
                    time_training.html(html_time_training);
                    $('.select2').select2();
                },
                error: function(response) {
                    // Get errors
                    var errors = response.responseJSON.errors;
                    // Foreach and show errors to html
                    var elementErrors = '';
                    $.each(errors, function(index, item) {
                        if (item === 'CSRF token mismatch.') {
                            item = "@lang('CSRF token mismatch.')";
                        }
                        elementErrors += '<p>' + item + '</p>';
                    });
                    alert(elementErrors);
                }
            });
        });

        $(document).on('click', '.btn-result', function() {
            var _id = $(this).attr('data-id');
            var _type_result = $(this).parents('tr').find('.select_result');
            var _note = $(this).parents('tr').find('.note_result');
            var url = "{{ route('jobs.resultt') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": _id,
                    "type": _type_result.val(),
                    "note": _note.val(),
                },
                success: function(response) {
                    if (response.data == 'success' && typeof response.data !== 'undefined') {
                        alert(response.message);
                    } else {
                        alert('Bạn không có quyền thực hiện chức năng này !');
                        _type_result.val('');
                        _note.val('');
                        $('.select2').select2();
                    }

                },
                error: function(data) {
                    console.log(data);
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        });

        function formatDate(date) {
            let d = new Date(date);
            let day = ('0' + d.getDate()).slice(-2);
            let month = ('0' + (d.getMonth() + 1)).slice(-2); // Tháng tính từ 0
            let year = d.getFullYear();

            return `${day}-${month}-${year}`; // Định dạng: DD-MM-YYYY
        }
    </script>
@endsection
