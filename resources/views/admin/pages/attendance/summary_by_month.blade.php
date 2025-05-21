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

        .h-25 {
            height: 25px;
        }

        input[type="radio"] {
            transform: scale(1.5);
        }

        .box_radio {
            margin-bottom: 0px
        }

        .radiobox {
            margin-top: 0px !important
        }


        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .item_day {
            font-size: 20px;
            cursor: pointer;
        }

        .item_day:hover.text-secondary {
            opacity: 1;
        }

        .item_day.text-secondary {
            opacity: 0.4;
        }

        .attendance_arrival {
            border-right: 1px solid #6c757d
        }

        .box_image {
            position: relative;
        }

        .box_capture {
            font-size: 60px;
            position: absolute;
            top: calc(50% - 42px);
            left: calc(50% - 32px);
            opacity: 0;
            cursor: pointer;
            z-index: 1;
        }

        .box_capture:hover {
            opacity: 0.5;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-around {
            justify-content: space-around;
        }

        .align-items-center {
            align-items: center
        }

        .box_content {
            width: calc(100% - 210px);
        }

        .select2-container {
            width: 100% !important;
        }

        .photo {
            width: 180px;
            height: 180px;
            padding: 8px;
        }

        #modal_attendance .modal-dialog {
            width: 70%;
        }

        .camera-container {
            position: relative;
            width: 100%;
            height: auto;
            overflow: hidden;
        }

        #video {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .controls {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 10px;
        }

        .div_h {
            height: 25px;
            margin-bottom: 10px
        }

        @media (max-width: 768px) {

            #modal_attendance .modal-dialog {
                width: calc(100% - 20px);
            }
            .box_checked, .box_image, .box_content{
                width: 100%;
            }
            .div_h {
                display: none;
            }

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
            <form action="{{ route(Request::segment(2) . '.summary_by_month') }}" method="GET">
                <div class="box-body">
                    <div class="d-flex-wap">
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Area')</label>
                                <select name="area_id" class="area_id form-control select2 w-100">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
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
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Tháng') <small class="text-red">*</small></label>
                                <input type="month" name="month" class="form-control month" required
                                    value="{{ isset($params['month']) && $params['month'] != '' ? $params['month'] : date('Y-m', time()) }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('Lọc theo mã học viên, họ tên hoặc email')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label>@lang('Lấy điểm danh')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm"
                                        href="{{ route(Request::segment(2) . '.summary_by_month') }}">
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
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center">@lang('STT')</th>
                                    <th rowspan="2" class="text-center">@lang('Thông tin học sinh')</th>
                                    @for ($i = 1; $i <= $daysInMonth; $i++)
                                        <th
                                            class="text-center {{ $carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '') }}">
                                            {{ $day_week[$carbonDate->copy()->day($i)->dayOfWeek] ?? 'CN' }}
                                        </th>
                                    @endfor
                                </tr>
                                <tr>
                                    @for ($i = 1; $i <= $daysInMonth; $i++)
                                        <th
                                            class="text-center {{ $carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '') }}">
                                            {{ $i }}
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td class="text-center">{{ $loop->index + 1 }}</td>
                                        <td class="name_student">{{ $row->student->first_name }}
                                            {{ $row->student->last_name }}
                                            {{ $row->student->nickname != '' ? '(' . $row->student->nickname . ')' : '' }}
                                        </td>
                                        @for ($i = 1; $i <= $daysInMonth; $i++)
                                            <th
                                                class="text-center {{ $carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '') }}">
                                                @if ($carbonDate->copy()->day($i)->dayOfWeek != 0)
                                                    <div class="item_day {{ isset($row->attendances_by_day[$i]) ? 'text-success' : 'text-secondary' }}"
                                                        id="item_{{ $row->class_id }}_{{ $row->student_id }}_{{ $carbonDate->copy()->day($i)->format('Y-m-d') }}"
                                                        data-class="{{ $row->class_id }}"
                                                        data-student="{{ $row->student_id }}"
                                                        data-date="{{ $carbonDate->copy()->day($i)->format('Y-m-d') }}"
                                                        data-toggle="tooltip" data-original-title="@lang('Lấy điểm danh')">
                                                        <i class="{{ isset($row->attendances_by_day[$i]) ? 'fa fa-check-circle-o' : 'fa fa-window-minimize' }} "
                                                            aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                            </th>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>


        <div class="modal fade" id="modal_attendance" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12">@lang('Thông tin điểm danh ngày') <span
                                class="day_attendance"></span>: <span class="student_attendance"></span></h3>
                        </h3>
                    </div>
                    <form action="{{ route('attendance.summary_by_month.update_or_store') }}" method="POST"
                        id="form_attendance">
                        @csrf
                        <div class="modal-body show_detail_attendance">

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> @lang('Lưu lại')
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <i class="fa fa-remove"></i> @lang('Close')
                            </button>
                        </div>
                    </form>
                </div>
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
                                <video id="video" autoplay playsinline style="width: 80%"></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="toggle_camera" class="btn btn-primary" style="display: none;">
                            <i class="fa fa-fa fa-refresh"></i>Đổi
                            Camera</button>
                        <button type="button" id="capture" data-type="" class="btn btn-success">
                            <i class="fa fa-camera"></i> @lang('Chụp ảnh xác nhận')
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
        let videoStream = null; // Biến lưu trữ stream của camera
        let currentFacingMode = "user"; // Chế độ camera mặc định: Camera trước
        var noImage = @json(url('themes/admin/img/no_image.jpg'));
        var areas = @json($areas ?? []);
        var classs = @json($classs ?? []);



        $(document).ready(function() {

            const video = $('#video')[0];
            const canvas = $('#canvas')[0];
            const photo_arrival = $('#photo_arrival')[0];
            const photo_return = $('#photo_return')[0];
            $('.area_id').change(function() {
                var area_id = $(this).val();
                var _html = `<option value="">{{ __('Please select') }}</option>`;
                if (area_id) {
                    _html += classs
                        .filter(item => item.area_id == area_id)
                        .map(item => `<option value="${item.id}">${item.code} - ${item.name}</option>`)
                        .join('');
                }
                $('.class_id').html(_html).trigger('change');
            })

            $('.item_day').click(function() {
                var class_id = $(this).data('class');
                var student_id = $(this).data('student');
                var date = $(this).data('date'); //Y-m-d
                var student_name = $(this).closest('tr').find('.name_student').text();
                $('.day_attendance').text(formatDate(date));
                $('.student_attendance').text(student_name);

                // Gọi ajax lấy thông tin chi tiết
                var url = "{{ route('attendance.summary_by_month.show') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        'class_id': class_id,
                        'student_id': student_id,
                        'date': date,
                    },
                    success: function(response) {
                        if (response) {
                            $('.show_detail_attendance').html(response.data.view);
                            $('#modal_attendance').modal('show');
                            $('.select2').select2({
                                width: '100%',
                            });
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });


            });

            $(document).on('change', '.checkin', function(e) {
                $('.check_disable').prop('disabled', false);
            })
            $(document).on('change', '.absent_unexcused, .absent_excused', function() {
                $('.check_disable').prop('disabled', true);
                $('.photo_arrival').attr('src', noImage);
                $('.img_arrival').val('');
            })

            // Hiển thị modal chụp ảnh
            $(document).on('click', '.box_capture', function() {
                var type = $(this).data('type');
                // Xác định thiết bị di động
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                // Thiết lập facingMode dựa trên thiết bị
                const facingMode = isMobile ? {
                    exact: "environment"
                } : "user"; // Mobile: Camera sau, Desktop: Camera trước
                $('#capture').attr('data-type', type);
                $('#modal_camera').modal('show');
                // Bật camera
                checkCameraAvailability();
                startCamera(facingMode)
            });
            // Chụp ảnh
            $(document).on('click', '#capture', function() {
                var type = $(this).attr('data-type');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                // Hiển thị ảnh đã chụp
                $('#photo_' + type).attr('src', canvas.toDataURL('image/png', 0.8));
                $('#image_' + type).val(canvas.toDataURL('image/png', 0.8));
                // Đóng modal và checked học sinh
                $('#modal_camera').modal('hide');
            });

            // Khi tắt modal thì tắt cam
            $(document).on('hidden.bs.modal', '#modal_camera', function() {
                if (videoStream) {
                    // Dừng tất cả các track video
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null; // Xóa stream để giải phóng bộ nhớ
                }
                $('#toggle_camera').hide();
                // Xóa nội dung video nếu cần
                const video = document.querySelector('#video');
                if (video) {
                    video.srcObject = null;
                }
                // Bỏ checked trạng thái điểm danh nếu chưa chụp ảnh
                var _id = $('#capture').attr('data-id');
                if ($('#photo_arrival').attr('src') == noImage) {
                    $('#student_checkin').prop('checked', false);
                }
            });

            // Submit form
            $(document).on('submit', '#form_attendance', function(e) {
                e.preventDefault(); // Ngăn form submit truyền thống
                let formData = $(this).serialize(); // Lấy dữ liệu form
                let student_id = $(this).find('input[name="student_id"]').val();
                let class_id = $(this).find('input[name="class_id"]').val();
                let date = $(this).find('input[name="date"]').val();
                var item = $('#item_' + class_id + '_' + student_id + '_' + date);

                show_loading_notification();
                $.ajax({
                    url: $(this).attr('action'), // Lấy URL từ action của form
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#modal_attendance').modal('hide');
                        hide_loading_notification();
                        if (response) {
                            item.removeClass('text-secondary');
                            item.addClass('text-success');
                            item.html(
                                '<i class="fa fa-check-circle-o" aria-hidden="true"></i>');
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
                    error: function(xhr) {

                        hide_loading_notification();
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            })
        });


        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = date.getDate(); // Lấy ngày
            var month = date.getMonth() + 1; // Lấy tháng (cần +1 vì tháng bắt đầu từ 0)
            var year = date.getFullYear(); // Lấy năm
            // Đảm bảo ngày và tháng luôn có 2 chữ số
            day = day < 10 ? '0' + day : day;
            month = month < 10 ? '0' + month : month;
            return day + '-' + month + '-' + year; // Trả về định dạng d-m-Y
        }
        // Kiểm tra danh sách camera
        function checkCameraAvailability() {
            return navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    if (videoDevices.length > 1) {
                        // Hiển thị nút "Đổi Camera" nếu có nhiều hơn 1 camera
                        $('#toggle_camera').show();
                    } else {
                        // Ẩn nút "Đổi Camera" nếu chỉ có 1 camera
                        $('#toggle_camera').hide();
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi kiểm tra camera:', error);
                    $('#toggle_camera').hide(); // Ẩn nút nếu không thể kiểm tra
                });
        }
        // Bật camera
        function startCamera(facingMode) {
            // Tắt camera hiện tại nếu có
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
            }

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: facingMode
                    }
                })
                .then(stream => {
                    videoStream = stream; // Lưu stream
                    const video = document.querySelector('#video');
                    video.srcObject = stream;
                    currentFacingMode = facingMode; // Cập nhật chế độ hiện tại
                })
                .catch(error => {
                    alert('Không thể truy cập camera: ' + error.message);
                });
        }
    </script>
@endsection
