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

        .box_image {
            width: 150px;
            height: 150px;
            overflow: hidden;
        }

        .box_image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 5px;
        }

        .box_content {
            width: calc(100% - 200px);
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
        <div id="loading-notification" class="loading-notification">
            <p>@lang('Please wait')...</p>
        </div>
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
                                <select required name="class_id" class="class_id form-control select2 w-100">
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
                                <input type="date" name="tracked_at" class="form-control tracked_at" required
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
                                <th class="text-center" rowspan="2">@lang('Hành động')</th>
                            </tr>
                            <tr>

                                <th class="text-center">@lang('Không phép')</th>
                                <th class="text-center">@lang('Có phép')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td class="text-center">{{ $loop->index + 1 }}</td>
                                    <td>{{ $row->student->student_code ?? '' }}</td>
                                    <td class="text-center">{{ $row->student->first_name ?? '' }}
                                        {{ $row->student->last_name ?? '' }}</td>
                                    <td>{{ $row->student->nickname ?? '' }}</td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_{{ $row->student_id }}_checkin">
                                            <input id="student_{{ $row->student_id }}_checkin"
                                                name="attendance[{{ $row->student_id }}][status]"
                                                {{ isset($row->attendance->status) && $row->attendance->status == 'checkin' ? 'checked disabled' : '' }}
                                                class="radiobox checkin" data-id="{{ $row->student_id }}" type="radio"
                                                value="checkin">
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_{{ $row->student_id }}_absent_unexcused">
                                            <input id="student_{{ $row->student_id }}_absent_unexcused"
                                                name="attendance[{{ $row->student_id }}][status]"
                                                {{ isset($row->attendance->status) && $row->attendance->status == 'absent_unexcused' ? 'checked' : '' }}
                                                class="radiobox absent_unexcused" data-id="{{ $row->student_id }}"
                                                type="radio" value="absent_unexcused">
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_{{ $row->student_id }}_absent_excused">
                                            <input id="student_{{ $row->student_id }}_absent_excused"
                                                name="attendance[{{ $row->student_id }}][status]"
                                                {{ isset($row->attendance->status) && $row->attendance->status == 'absent_excused' ? 'checked' : '' }}
                                                class="radiobox absent_excused" data-id="{{ $row->student_id }}"
                                                type="radio" value="absent_excused">
                                        </label>
                                    </td>
                                    <td class="d-flex-wap content_{{ $row->student_id }}">
                                        <div class="box_image">
                                            <img class="photo_{{ $row->student_id }}"
                                                src="{{ isset($row->attendance->json_params->img) ? asset($row->attendance->json_params->img) : url('themes/admin/img/no_image.jpg') }}">
                                            <input type="hidden" class="img_{{ $row->student_id }}"
                                                name="attendance[{{ $row->student_id }}][json_params][image]"
                                                value="{{ isset($row->attendance->json_params->img) ? $row->attendance->json_params->img : '' }}">
                                        </div>
                                        <div class="box_content information_{{ $row->student_id }}">
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <select class="form-control select2 w-100 check_disable" disabled
                                                    name="attendance[{{ $row->student_id }}][checkin_parent_id]">
                                                    <option selected="" value="">-Người đưa-</option>
                                                    @if (isset($row->student->studentParents) && count($row->student->studentParents) > 0)
                                                        @foreach ($row->student->studentParents as $item)
                                                            <option value="{{ $item->parent_id }}"
                                                                {{ isset($row->attendance->checkin_parent_id) && $row->attendance->checkin_parent_id == $item->parent_id ? 'selected' : '' }}>
                                                                {{ $item->relationship->title ?? '' }}:
                                                                {{ $item->parent->first_name ?? '' }}
                                                                {{ $item->parent->last_name ?? '' }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <select class="form-control select2 w-100 check_disable" disabled
                                                    name="attendance[{{ $row->student_id }}][checkin_teacher_id]">
                                                    <option value="">-Giáo viên đón-</option>
                                                    @foreach ($list_teacher as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ isset($row->attendance->checkin_teacher_id) && $row->attendance->checkin_teacher_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12 col-xs-12">
                                                <input name="attendance[{{ $row->student_id }}][json_params][note]"
                                                    type="text" class="form-control check_disable" disabled
                                                    id="note_{{ $row->student_id }}" placeholder="Nhập ghi chú"
                                                    value="{{ isset($row->attendance->json_params->note) ? $row->attendance->json_params->note : '' }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if (!isset($row->attendance->status) || $row->attendance->status != 'checkin')
                                            <button class="btn btn-success btn_attendance"
                                                data-id="{{ $row->student_id }}">@lang('Điểm danh')</button>
                                    </td>
                            @endif

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
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <video id="video" autoplay style="width: 80%"></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="capture" data-id="" class="btn btn-success">
                            <i class="fa fa-save"></i> @lang('Chụp ảnh xác nhận')
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
            var noImage = @json(url('themes/admin/img/no_image.jpg'));

            $(document).on('change', '.checkin', function(e) {
                // Lấy id của học sinh từ thuộc tính data-id
                var _student_id = $(this).attr('data-id');
                $('#capture').attr('data-id', _student_id);
                $('#modal_camera').modal('show');
                $('.information_' + _student_id).find('.check_disable').prop('disabled', false);
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
                        alert.error('Không thể truy cập camera:', error);
                    });
            });
            // Chụp ảnh
            $(document).on('click', '#capture', function() {
                var _id = $(this).attr('data-id');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                // Hiển thị ảnh đã chụp
                $('.photo_' + _id).attr('src', canvas.toDataURL('image/png', 0.8));
                $('.img_' + _id).val(canvas.toDataURL('image/png', 0.8));
                // Đóng modal và checked học sinh
                $('#modal_camera').modal('hide');
            });
            $('.absent_unexcused, .absent_excused').on('change', function() {
                var _id = $(this).attr('data-id');
                $('.information_' + _id).find('.check_disable').prop('disabled', true);
                $('.photo_' + _id).attr('src', noImage);
                $('.img_' + _id).val('');
            })
            // Khi tắt modal thì tắt cam
            $(document).on('hidden.bs.modal', '#modal_camera', function() {
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


            // Lưu thông tin điểm danh
            $('.btn_attendance').click(function() {
                var studentId = $(this).data('id');
                var class_id = $('.class_id').val();
                var tracked_at = $('.tracked_at').val();

                var _status = $('input[name="attendance[' + studentId + '][status]"]:checked').val();
                var _checkin_parent_id = $('select[name="attendance[' + studentId +
                    '][checkin_parent_id]"]').val();
                var _checkin_teacher_id = $('select[name="attendance[' + studentId +
                    '][checkin_teacher_id]"]').val();
                var _note = $('input[name="attendance[' + studentId + '][json_params][note]"]').val();
                var _img = $('input[name="attendance[' + studentId + '][json_params][image]"]').val();

                if (_status == undefined) {
                    alert('Vui lòng chọn trạng thái điểm danh');
                    return;
                }
                if (_status == 'checkin' && _checkin_parent_id == '') {
                    alert('Vui lòng chọn người đưa');
                    return;
                }
                if (_status == 'checkin' && _checkin_teacher_id == '') {
                    alert('Vui lòng chọn giáo viên đón');
                    return;
                }
                if (_status == 'checkin' && _img == '') {
                    alert('Vui lòng chụp ảnh');
                    return;
                }
                show_loading_notification();
                $.ajax({
                    url: '{{ route('attendance.store') }}',
                    type: 'POST',
                    data: {
                        "student_id": studentId,
                        "class_id": class_id,
                        "tracked_at": tracked_at,
                        "status": _status,
                        "checkin_parent_id": _checkin_parent_id,
                        "checkin_teacher_id": _checkin_teacher_id,
                        "json_params[note]": _note,
                        "json_params[img]": _img,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        hide_loading_notification();
                        if (response) {
                            var _html = `<div class="alert alert-${response.data} alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        ${response.message}
                        </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(2000, function() {});
                            }, 800);
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(2000, function() {});
                            }, 800);
                        }
                    },
                    error: function(response) {
                        hide_loading_notification();
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            });

        });
    </script>
@endsection
