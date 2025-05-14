@extends('admin.layouts.app')

@section('title')
    @lang($module_name)
@endsection
@section('style')
    <style>
        .table>thead>tr>th,
        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .box_radio {
            width: 100%;
            height: 100%;
        }

        input[type="radio"] {
            transform: scale(1.5);
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection
@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang($module_name)
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection

@section('content')

    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Lớp') <small class="text-red">*</small></label>
                                <select required name="class_id" class="form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($classs as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->code ?? '' }} - {{ $item->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Ngày ') <small class="text-red">*</small></label>
                                <input type="date" name="tracked_at" class="form-control" required
                                    value="{{ isset($params['tracked_at']) && $params['tracked_at'] != '' ? $params['tracked_at'] : date('Y-m-d', time()) }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">
                {{-- <h3 class="box-title">@lang('List')</h3> --}}
            </div>
            <div class="box-body box_alert">
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2">@lang('STT')</th>
                                <th class="text-center" rowspan="2">@lang('Mã học sinh')</th>
                                <th class="text-center" rowspan="2">@lang('Tên học sinh')</th>
                                <th class="text-center" rowspan="2">@lang('Nickname')</th>
                                <th class="text-center" rowspan="2">@lang('Đi học')</th>
                                <th class="text-center" colspan="2">@lang('Nghỉ học')</th>
                                <th class="text-center" rowspan="2">@lang('Nội dung Đưa/Đón')</th>
                            </tr>
                            <tr>

                                <th class="text-center">@lang('Không phép')</th>
                                <th class="text-center">@lang('Có phép')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->student->student_code ?? '' }}</td>
                                    <td class="text-center">{{ $item->student->first_name ?? '' }}
                                        {{ $item->student->last_name ?? '' }}</td>
                                    <td>{{ $item->student->nickname ?? '' }}</td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_{{ $item->student_id }}_checkin">
                                            <input id="student_{{ $item->student_id }}_checkin"
                                                name="student[{{ $item->student_id }}][status]" class="radiobox checkin"
                                                data-id="{{ $item->student_id }}" type="radio" value="1">
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_{{ $item->student_id }}_absent_unexcused">
                                            <input id="student_{{ $item->student_id }}_absent_unexcused"
                                                name="student[{{ $item->student_id }}][status]"
                                                class="radiobox absent_unexcused" data-id="{{ $item->student_id }}"
                                                type="radio" value="1">
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_{{ $item->student_id }}_absent_excused">
                                            <input id="student_{{ $item->student_id }}_absent_excused"
                                                name="student[{{ $item->student_id }}][status]"
                                                class="radiobox absent_excused" data-id="{{ $item->student_id }}"
                                                type="radio" value="1">
                                        </label>
                                    </td>
                                    <td class="content_{{ $item->student_id }}">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <img class="photo_{{ $item->student_id }}"
                                                style="display:none; width: 100%; max-width: 250px;">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 information_{{ $item->student_id }}"
                                            style="display:none">
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <select class="form-control select2 w-100"
                                                    name="student_logtime[{{ $item->student_id }}][relative_login]">
                                                    <option selected="" value="">-Người đưa-</option>
                                                    ${_option}
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <select class="form-control select2 w-100"
                                                    name="student_logtime[{{ $item->student_id }}][member_login]">
                                                    <option value="">-Giáo viên đón-</option>
                                                    @foreach ($list_teacher as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ $item->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12 col-xs-12">
                                                <input name="student_logtime[{{ $item->student_id }}][note]"
                                                    type="text" class="form-control" style="width: 100%"
                                                    id="note_{{ $item->student_id }}" placeholder="Nhập ghi chú"
                                                    value="">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                @endif
            </div>


        </div>

        <div class="modal fade" id="modal_camera" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12">@lang('Chụp ảnh xác nhận')</h3>
                        </h3>
                    </div>
                    <div class="modal-body show_detail_eduction">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <video id="video" autoplay style="width: 100%; max-width: 250px;"></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="capture" data-id="" class="btn btn-success">
                            <i class="fa fa-save"></i> @lang('Chụp ảnh và xác nhận điểm danh')
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-remove"></i> @lang('Close')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        var rows = @json($rows);
        let videoStream = null; // Biến lưu trữ stream của camera
        $(document).ready(function() {
            const video = $('#video')[0];
            const canvas = $('#canvas')[0];
            const photo = $('#photo')[0];

            $(document).on('change', '.checkin', function() {
                var _student_id = $(this).data('id');
                var _student = rows.find(row => row.student_id === _student_id);
                var _option = ``;
                _student.student.student_parents.forEach(function(row) {
                    _option += `<option value="${row.parent_id}">
                                ${row.relationship.title ?? '' }:
                                ${row.parent.first_name ?? '' }
                                ${row.parent.last_name ?? '' }</option>`;
                });
                var _html = `

                           `;

                $('.select2').select2();
                $('.box_eduction').html(_html);
                $('#capture').attr('data-id', _student_id);
                $('#modal_camera').modal('show');

                // Bật camera
                navigator.mediaDevices.getUserMedia({
                        video: true
                    })
                    .then(stream => {
                        videoStream = stream; // Lưu stream để sử dụng sau
                        const video = document.querySelector('#video');
                        video.srcObject = stream;
                    })
                    .catch(error => {
                        console.error('Không thể truy cập camera:', error);
                    });
            });
            // Khi tắt modal thì tắt cam
            $('#modal_camera').on('hidden.bs.modal', function() {
                if (videoStream) {
                    // Dừng tất cả các track video
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null; // Xóa stream để giải phóng bộ nhớ
                }
                // Xóa nội dung video nếu cần
                const video = document.querySelector('#video');
                if (video) {
                    video.srcObject = null;
                }
            });


            // Chụp ảnh
            $(document).on('click', '#capture', function() {

                var _id = $(this).data('id');
                console.log(_id);

                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                // Hiển thị ảnh đã chụp
                $('.photo_' + _id).attr('src', canvas.toDataURL('image/png')).show();
                $('information_' + _id).show();
                // Đóng modal
                $('#modal_camera').modal('hide');
            });

            // Lưu ảnh
            $('#save').click(function() {
                const imageData = canvas.toDataURL('image/png');
                $.ajax({
                    url: '{{ route('save.image') }}',
                    type: 'POST',
                    data: {
                        image: imageData,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert(response.message);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
