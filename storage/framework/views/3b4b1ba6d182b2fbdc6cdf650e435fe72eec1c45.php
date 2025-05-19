<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
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

        .d-flex {
            display: flex;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .box_radio {
            margin-bottom: 0px
        }

        .radiobox {
            margin-top: 0px !important
        }

        @media (max-width: 768px) {
            .box_content {
                width: 100%;
            }
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
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
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
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="d-flex-wap">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày '); ?> <small class="text-red">*</small></label>
                                <input type="date" name="tracked_at" class="form-control tracked_at" required
                                    value="<?php echo e(isset($params['tracked_at']) && $params['tracked_at'] != '' ? $params['tracked_at'] : date('Y-m-d', time())); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Lấy điểm'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
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
                                    
                                    <th class="text-center" rowspan="2"><?php echo app('translator')->get('Thông tin học sinh'); ?></th>
                                    <th class="text-center" rowspan="2"><?php echo app('translator')->get('Điểm danh'); ?></th>

                                    <th class="text-center" rowspan="2"><?php echo app('translator')->get('Nội dung Đưa/Đón'); ?></th>
                                    <th class="text-center" rowspan="2"><?php echo app('translator')->get('Hành động'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        
                                        <td>
                                            <p>Mã HS: <?php echo e($row->student->student_code ?? ''); ?></p>
                                            <p>Họ tên: <?php echo e($row->student->first_name ?? ''); ?>

                                                <?php echo e($row->student->last_name ?? ''); ?></p>
                                            <p>Nickname: <?php echo e($row->student->nickname ?? ''); ?></p>
                                        </td>
                                        <td class="">
                                            <div class="d-flex mb-20">
                                                <input id="student_<?php echo e($row->student_id); ?>_checkin"
                                                    name="attendance[<?php echo e($row->student_id); ?>][status]"
                                                    <?php echo e(isset($row->attendance->status) && $row->attendance->status == 'checkin' ? 'checked disabled' : ''); ?>

                                                    class="radiobox mr-10 checkin" data-id="<?php echo e($row->student_id); ?>"
                                                    type="radio" value="checkin">
                                                <label class="box_radio" for="student_<?php echo e($row->student_id); ?>_checkin">
                                                    Đi học
                                                </label>
                                            </div>
                                            <div class="d-flex mb-20">
                                                <input id="student_<?php echo e($row->student_id); ?>_absent_unexcused"
                                                    name="attendance[<?php echo e($row->student_id); ?>][status]"
                                                    <?php echo e(isset($row->attendance->status) && $row->attendance->status == 'absent_unexcused' ? 'checked' : ''); ?>

                                                    class="radiobox mr-10 absent_unexcused"
                                                    data-id="<?php echo e($row->student_id); ?>" type="radio"
                                                    value="absent_unexcused">
                                                <label class="box_radio"
                                                    for="student_<?php echo e($row->student_id); ?>_absent_unexcused">
                                                    Nghỉ không phép
                                                </label>
                                            </div>
                                            <div class="d-flex mb-20">
                                                <input id="student_<?php echo e($row->student_id); ?>_absent_excused"
                                                    name="attendance[<?php echo e($row->student_id); ?>][status]"
                                                    <?php echo e(isset($row->attendance->status) && $row->attendance->status == 'absent_excused' ? 'checked' : ''); ?>

                                                    class="radiobox mr-10 absent_excused"
                                                    data-id="<?php echo e($row->student_id); ?>" type="radio"
                                                    value="absent_excused">
                                                <label class="box_radio"
                                                    for="student_<?php echo e($row->student_id); ?>_absent_excused">
                                                    Nghỉ có phép
                                                </label>
                                            </div>
                                        </td>
                                        <td class="d-flex-wap content_<?php echo e($row->student_id); ?>">
                                            <div class="box_image">
                                                <img class="photo_<?php echo e($row->student_id); ?>"
                                                    src="<?php echo e(isset($row->attendance->json_params->img) ? asset($row->attendance->json_params->img) : url('themes/admin/img/no_image.jpg')); ?>">
                                                <input type="hidden" class="img_<?php echo e($row->student_id); ?>"
                                                    name="attendance[<?php echo e($row->student_id); ?>][json_params][image]"
                                                    value="<?php echo e(isset($row->attendance->json_params->img) ? $row->attendance->json_params->img : ''); ?>">
                                            </div>
                                            <div class="box_content information_<?php echo e($row->student_id); ?>">
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <select class="form-control select2 w-100 check_disable" disabled
                                                        name="attendance[<?php echo e($row->student_id); ?>][checkin_parent_id]">
                                                        <option selected="" value="">-Người đưa-</option>
                                                        <?php if(isset($row->student->studentParents) && count($row->student->studentParents) > 0): ?>
                                                            <?php $__currentLoopData = $row->student->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($item->parent_id); ?>"
                                                                    <?php echo e(isset($row->attendance->checkin_parent_id) && $row->attendance->checkin_parent_id == $item->parent_id ? 'selected' : ''); ?>>
                                                                    <?php echo e($item->relationship->title ?? ''); ?>:
                                                                    <?php echo e($item->parent->first_name ?? ''); ?>

                                                                    <?php echo e($item->parent->last_name ?? ''); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <select class="form-control select2 w-100 check_disable" disabled
                                                        name="attendance[<?php echo e($row->student_id); ?>][checkin_teacher_id]">
                                                        <option value="">-Giáo viên đón-</option>
                                                        <?php $__currentLoopData = $list_teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($item->id); ?>"
                                                                <?php echo e(isset($row->attendance->checkin_teacher_id) && $row->attendance->checkin_teacher_id == $item->id ? 'selected' : ''); ?>>
                                                                <?php echo e($item->name ?? ''); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-sm-12 col-xs-12">
                                                    <input name="attendance[<?php echo e($row->student_id); ?>][json_params][note]"
                                                        type="text" class="form-control check_disable" disabled
                                                        id="note_<?php echo e($row->student_id); ?>" placeholder="Nhập ghi chú"
                                                        value="<?php echo e(isset($row->attendance->json_params->note) ? $row->attendance->json_params->note : ''); ?>">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <?php if(!isset($row->attendance->status) || $row->attendance->status != 'checkin'): ?>
                                                <button class="btn btn-success btn_attendance"
                                                    data-id="<?php echo e($row->student_id); ?>"><?php echo app('translator')->get('Điểm danh'); ?></button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
                        <button type="button" id="capture" data-id="" class="btn btn-success">
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
        var rows = <?php echo json_encode($rows, 15, 512) ?>;
        let videoStream = null; // Biến lưu trữ stream của camera
        let currentFacingMode = "user"; // Chế độ camera mặc định: Camera trước
        $(document).ready(function() {
            const video = $('#video')[0];
            const canvas = $('#canvas')[0];
            const photo = $('#photo')[0];
            var noImage = <?php echo json_encode(url('themes/admin/img/no_image.jpg'), 15, 512) ?>;


            $(document).on('change', '.checkin', function(e) {
                // Lấy id của học sinh từ thuộc tính data-id
                var _student_id = $(this).attr('data-id');
                $('#capture').attr('data-id', _student_id);
                $('#modal_camera').modal('show');
                $('.information_' + _student_id).find('.check_disable').prop('disabled', false);
                // Xác định thiết bị di động
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                // Thiết lập facingMode dựa trên thiết bị
                const facingMode = isMobile ? {
                    exact: "environment"
                } : "user"; // Mobile: Camera sau, Desktop: Camera trước
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
                $('#toggle_camera').hide();
                // Xóa nội dung video nếu cần
                const video = document.querySelector('#video');
                if (video) {
                    video.srcObject = null;
                }
                // Bỏ checked trạng thái điểm danh nếu chưa chụp ảnh
                var _id = $('#capture').attr('data-id');
                if ($('.photo_' + _id).attr('src') == noImage) {
                    $('#student_' + _id + '_checkin').prop('checked', false);
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
                    url: '<?php echo e(route('attendance.store')); ?>',
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
                        _token: '<?php echo e(csrf_token()); ?>',
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/attendance/index.blade.php ENDPATH**/ ?>