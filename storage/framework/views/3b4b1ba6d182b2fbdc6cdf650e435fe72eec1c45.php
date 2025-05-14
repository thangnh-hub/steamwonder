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
        
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Lọc theo mã học viên, họ tên hoặc email'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
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
                                <select required name="class_id" class="form-control select2 w-100">
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
                                <input type="date" name="tracked_at" class="form-control" required
                                    value="<?php echo e(isset($params['tracked_at']) && $params['tracked_at'] != '' ? $params['tracked_at'] : date('Y-m-d', time())); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                <th class="text-center" rowspan="2"><?php echo app('translator')->get('Mã học sinh'); ?></th>
                                <th class="text-center" rowspan="2"><?php echo app('translator')->get('Tên học sinh'); ?></th>
                                <th class="text-center" rowspan="2"><?php echo app('translator')->get('Nickname'); ?></th>
                                <th class="text-center" rowspan="2"><?php echo app('translator')->get('Đi học'); ?></th>
                                <th class="text-center" colspan="2"><?php echo app('translator')->get('Nghỉ học'); ?></th>
                                <th class="text-center" rowspan="2"><?php echo app('translator')->get('Nội dung Đưa/Đón'); ?></th>
                            </tr>
                            <tr>

                                <th class="text-center"><?php echo app('translator')->get('Không phép'); ?></th>
                                <th class="text-center"><?php echo app('translator')->get('Có phép'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                    <td><?php echo e($item->student->student_code ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($item->student->first_name ?? ''); ?>

                                        <?php echo e($item->student->last_name ?? ''); ?></td>
                                    <td><?php echo e($item->student->nickname ?? ''); ?></td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_<?php echo e($item->student_id); ?>_checkin">
                                            <input id="student_<?php echo e($item->student_id); ?>_checkin"
                                                name="student[<?php echo e($item->student_id); ?>][status]" class="radiobox checkin"
                                                data-id="<?php echo e($item->student_id); ?>" type="radio" value="1">
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_<?php echo e($item->student_id); ?>_absent_unexcused">
                                            <input id="student_<?php echo e($item->student_id); ?>_absent_unexcused"
                                                name="student[<?php echo e($item->student_id); ?>][status]"
                                                class="radiobox absent_unexcused" data-id="<?php echo e($item->student_id); ?>"
                                                type="radio" value="1">
                                        </label>
                                    </td>
                                    <td class="text-center">
                                        <label class="box_radio" for="student_<?php echo e($item->student_id); ?>_absent_excused">
                                            <input id="student_<?php echo e($item->student_id); ?>_absent_excused"
                                                name="student[<?php echo e($item->student_id); ?>][status]"
                                                class="radiobox absent_excused" data-id="<?php echo e($item->student_id); ?>"
                                                type="radio" value="1">
                                        </label>
                                    </td>
                                    <td class="content_<?php echo e($item->student_id); ?>">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <img class="photo_<?php echo e($item->student_id); ?>"
                                                style="display:none; width: 100%; max-width: 250px;">
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6 information_<?php echo e($item->student_id); ?>"
                                            style="display:none">
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <select class="form-control select2 w-100"
                                                    name="student_logtime[<?php echo e($item->student_id); ?>][relative_login]">
                                                    <option selected="" value="">-Người đưa-</option>
                                                    ${_option}
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <select class="form-control select2 w-100"
                                                    name="student_logtime[<?php echo e($item->student_id); ?>][member_login]">
                                                    <option value="">-Giáo viên đón-</option>
                                                    <?php $__currentLoopData = $list_teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>">
                                                            <?php echo e($item->name ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-12 col-xs-12">
                                                <input name="student_logtime[<?php echo e($item->student_id); ?>][note]"
                                                    type="text" class="form-control" style="width: 100%"
                                                    id="note_<?php echo e($item->student_id); ?>" placeholder="Nhập ghi chú"
                                                    value="">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </tbody>
                    </table>
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
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <video id="video" autoplay style="width: 100%; max-width: 250px;"></video>
                                <canvas id="canvas" style="display:none;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="capture" data-id="" class="btn btn-success">
                            <i class="fa fa-save"></i> <?php echo app('translator')->get('Chụp ảnh và xác nhận điểm danh'); ?>
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
                    url: '<?php echo e(route('save.image')); ?>',
                    type: 'POST',
                    data: {
                        image: imageData,
                        _token: '<?php echo e(csrf_token()); ?>'
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/attendance/index.blade.php ENDPATH**/ ?>