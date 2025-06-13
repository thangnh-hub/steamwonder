

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
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
                max-height: calc(100vh - 20px);
                overflow-y: auto;
            }

            .box_checked {
                width: 100%;
                margin-bottom: 15px
            }

            .box_image,
            .box_content {
                width: 100%;
            }

            .attendance_arrival {
                border-right: none;
            }

            .div_h {
                display: none;
            }

        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
        
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route(Request::segment(2) . '.summary_by_month')); ?>" method="GET">
                <div class="box-body">
                    <div class="d-flex-wap">
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" class="area_id form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lớp'); ?> <small class="text-red">*</small></label>
                                <select required name="class_id" class="class_id form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['class_id']) && $params['class_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->code ?? ''); ?> - <?php echo e($item->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tháng'); ?> <small class="text-red">*</small></label>
                                <input type="month" name="month" class="form-control month" required
                                    value="<?php echo e(isset($params['month']) && $params['month'] != '' ? $params['month'] : date('Y-m', time())); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lấy điểm danh'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm"
                                        href="<?php echo e(route(Request::segment(2) . '.summary_by_month')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-header">
                
            </div>
            <div class="box-body box_alert">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('errorMessage')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo e(session('successMessage')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                <?php endif; ?>
                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center"><?php echo app('translator')->get('STT'); ?></th>
                                    <th rowspan="2" class="text-center"><?php echo app('translator')->get('Thông tin học sinh'); ?></th>
                                    <?php for($i = 1; $i <= $daysInMonth; $i++): ?>
                                        <th
                                            class="text-center <?php echo e($carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '')); ?>">
                                            <?php echo e($day_week[$carbonDate->copy()->day($i)->dayOfWeek] ?? 'CN'); ?>

                                        </th>
                                    <?php endfor; ?>
                                </tr>
                                <tr>
                                    <?php for($i = 1; $i <= $daysInMonth; $i++): ?>
                                        <th
                                            class="text-center <?php echo e($carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '')); ?>">
                                            <?php echo e($i); ?>

                                        </th>
                                    <?php endfor; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                        <td class="name_student"><?php echo e($row->student->first_name); ?>

                                            <?php echo e($row->student->last_name); ?>

                                            <?php echo e($row->student->nickname != '' ? '(' . $row->student->nickname . ')' : ''); ?>

                                        </td>
                                        <?php for($i = 1; $i <= $daysInMonth; $i++): ?>
                                            <th class="text-center <?php echo e($carbonDate->copy()->day($i)->dayOfWeek == 0 ? 'bg-danger' : ($carbonDate->copy()->day($i)->dayOfWeek == 6 ? 'bg-warning' : '')); ?>">
                                                <?php if($carbonDate->copy()->day($i)->dayOfWeek != 0): ?>
                                                    <div class="item_day <?php echo e(isset($row->attendances_by_day[$i]) ? 'text-success' : 'text-secondary'); ?>"
                                                        id="item_<?php echo e($row->class_id); ?>_<?php echo e($row->student_id); ?>_<?php echo e($carbonDate->copy()->day($i)->format('Y-m-d')); ?>"
                                                        data-class="<?php echo e($row->class_id); ?>"
                                                        data-student="<?php echo e($row->student_id); ?>"
                                                        data-date="<?php echo e($carbonDate->copy()->day($i)->format('Y-m-d')); ?>"
                                                        data-toggle="tooltip" data-original-title="<?php echo app('translator')->get('Lấy điểm danh'); ?>">
                                                        <i class="<?php echo e(isset($row->attendances_by_day[$i]) ? 'fa fa-check-circle-o' : 'fa fa-window-minimize'); ?> "
                                                            aria-hidden="true"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </th>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <div class="modal fade" id="modal_attendance" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Thông tin điểm danh ngày'); ?> <span
                                class="day_attendance"></span>: <span class="student_attendance"></span></h3>
                        </h3>
                    </div>
                    <form action="<?php echo e(route('attendance.summary_by_month.update_or_store')); ?>" method="POST"
                        id="form_attendance">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body show_detail_attendance">

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Lưu lại'); ?>
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                <i class="fa fa-remove"></i> <?php echo app('translator')->get('Close'); ?>
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
                        <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Chụp ảnh xác nhận'); ?></h3>
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
                            <i class="fa fa-camera"></i> <?php echo app('translator')->get('Chụp ảnh xác nhận'); ?>
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-remove"></i> <?php echo app('translator')->get('Close'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        let videoStream = null; // Biến lưu trữ stream của camera
        let currentFacingMode = "user"; // Chế độ camera mặc định: Camera trước
        var noImage = <?php echo json_encode(url('themes/admin/img/no_image.jpg'), 15, 512) ?>;
        var areas = <?php echo json_encode($areas ?? [], 15, 512) ?>;
        var classs = <?php echo json_encode($classs ?? [], 15, 512) ?>;



        $(document).ready(function() {

            const video = $('#video')[0];
            const canvas = $('#canvas')[0];
            const photo_arrival = $('#photo_arrival')[0];
            const photo_return = $('#photo_return')[0];
            $('.area_id').change(function() {
                var area_id = $(this).val();
                var _html = `<option value=""><?php echo e(__('Please select')); ?></option>`;
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
                var url = "<?php echo e(route('attendance.summary_by_month.show')); ?>";
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
            // Nút đổi camera
            $('#toggle_camera').on('click', function() {
                const newFacingMode = currentFacingMode === "user" ? {
                    exact: "environment"
                } : "user";
                startCamera(newFacingMode);
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/attendance/summary_by_month.blade.php ENDPATH**/ ?>