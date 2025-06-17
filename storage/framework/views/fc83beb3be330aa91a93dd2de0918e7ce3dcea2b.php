

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
            <form action="<?php echo e(route(Request::segment(2) . '.studentMeal')); ?>" method="GET">
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
                                        href="<?php echo e(route(Request::segment(2) . '.studentMeal')); ?>">
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
                        <div class="mb-15">
                            <b>Lưu ý:</b> - Báo lịch ăn cho học sinh ngày hôm sau phải trước 15h ngày hôm nay
                            <br>
                            - <span class="text-success"><i class='fa fa-check-circle-o' aria-hidden='true'></i></span> là
                            có ăn, <span class="text-danger"><i class='fa fa-ban' aria-hidden='true'></i></span> là không ăn
                            <br>
                            - Trường hợp không ăn sẽ được tính là nghỉ có phép
                        </div>
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
                                            <td class="text-center box-item">
                                                <?php if($carbonDate->copy()->day($i)->dayOfWeek != 0): ?>
                                                    <div
                                                        class="item_day <?php echo e(isset($row->student_meal[$i]) ? ($row->student_meal[$i]->status == 'active' ? 'text-success' : 'text-danger') : 'text-secondary'); ?>">
                                                        <i class="<?php echo e(isset($row->student_meal[$i]) ? ($row->student_meal[$i]->status == 'active' ? 'fa fa-check-circle-o' : 'fa fa-ban') : 'fa fa-window-minimize'); ?> "
                                                            aria-hidden="true"></i>
                                                    </div>
                                                    <?php if($carbonDate->copy()->day($i) == $tomorrow && $carbonDate->isSameMonth(now())): ?>
                                                        <div class="box-actions" data-class="<?php echo e($row->class_id); ?>"
                                                            data-student="<?php echo e($row->student_id); ?>"
                                                            data-date="<?php echo e($carbonDate->copy()->day($i)->format('Y-m-d')); ?>">
                                                            <button class="btn btn-success btn-sm btn_change_meal"
                                                                data-status = "active" data-toggle="tooltip"
                                                                data-original-title="<?php echo app('translator')->get('Có ăn'); ?>">
                                                                <i class="fa fa-check-circle-o"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm btn_change_meal"
                                                                data-status = "deactive" data-toggle="tooltip"
                                                                data-original-title="<?php echo app('translator')->get('Không ăn'); ?>">
                                                                <i class="fa fa-ban"></i></button>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endfor; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        var areas = <?php echo json_encode($areas ?? [], 15, 512) ?>;
        var classs = <?php echo json_encode($classs ?? [], 15, 512) ?>;
        $(document).ready(function() {
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

            $('.btn_change_meal').click(function() {
                var status = $(this).data('status');
                var parents = $(this).closest('.box-actions');
                var student_id = parents.data('student');
                var class_id = parents.data('class');
                var meal_day = parents.data('date');
                var item_day = parents.closest('.box-item').find('.item_day');
                var statusClasses = {
                    active: {
                        add: 'text-success',
                        remove: 'text-danger text-secondary',
                        icon: "<i class='fa fa-check-circle-o' aria-hidden='true'></i>"
                    },
                    deactive: {
                        add: 'text-danger',
                        remove: 'text-success text-secondary',
                        icon: "<i class='fa fa-ban' aria-hidden='true'></i>"
                    }
                };
                $.ajax({
                    type: "POST",
                    url: "<?php echo e(route('attendance.save_studentMeal')); ?>",
                    data: {
                        'status': status,
                        'student_id': student_id,
                        'class_id': class_id,
                        'meal_day': meal_day,
                        "_token": "<?php echo e(csrf_token()); ?>",
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'success') {
                                if (statusClasses[status]) {
                                    var update = statusClasses[status];
                                    item_day.removeClass(update.remove).addClass(update.add)
                                        .html(
                                            update.icon);
                                }
                            }

                            var _html = `<div class="alert alert-${response.data} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            ${response.message}
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000,
                                    function() {});
                            }, 800);

                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Bạn không có quyền thao tác chức năng này!
                        </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert-warning").fadeOut(3000,
                                    function() {});
                            }, 800);
                        }
                    },
                    error: function(response) {
                        var errors = response.responseJSON.message;
                        console.log(errors);
                    }
                });
            })
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/attendance/student_meal.blade.php ENDPATH**/ ?>