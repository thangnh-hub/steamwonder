<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .select2.select2-container {
            width: 100% !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div id="loading-notification" class="loading-notification">
        <p><?php echo app('translator')->get('Please wait'); ?>...</p>
    </div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box_alert">
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
        </div>
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        
                        <div class="box-body">
                            <!-- Custom Tabs -->
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5 class="fw-bold">Thông tin chính <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="#tab_2" data-toggle="tab">
                                            <h5 class="fw-bold">Danh sách học sinh</h5>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab_3" data-toggle="tab">
                                            <h5 class="fw-bold">Danh sách giáo viên</h5>
                                        </a>
                                    </li>

                                    <button type="submit" class="btn btn-primary btn-sm pull-right">
                                        <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Mã lớp'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="code" id="code"
                                                        placeholder="<?php echo app('translator')->get('Mã lớp'); ?>"
                                                        value="<?php echo e(old('code') ?? ($detail->code ?? '')); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Title'); ?> <small class="text-red">*</small></label>
                                                    <input type="text" class="form-control" name="name"
                                                        id="class_name" placeholder="<?php echo app('translator')->get('Title'); ?>"
                                                        value="<?php echo e(old('name') ?? ($detail->name ?? '')); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Area'); ?> <small class="text-red">*</small></label>
                                                    <select required name="area_id" class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($detail->area_id) && $detail->area_id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name ?? ''); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Room'); ?> <small class="text-red">*</small></label>
                                                    <select required name="room_id" class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($detail->room_id) && $detail->room_id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name ?? ''); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Độ tuổi'); ?> <small class="text-red">*</small></label>
                                                    <select required name="education_age_id" class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $ages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($detail->education_age_id) && $detail->education_age_id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name ?? ''); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Chương trình'); ?> <small class="text-red">*</small></label>
                                                    <select required name="education_program_id"
                                                        class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($val->id); ?>"
                                                                <?php echo e(isset($detail->education_program_id) && $detail->education_program_id == $val->id ? 'selected' : ''); ?>>
                                                                <?php echo e($val->name ?? ''); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Slot'); ?> <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="slot"
                                                        placeholder="<?php echo app('translator')->get('Slot'); ?>" min="0"
                                                        value="<?php echo e(old('slot') ?? ($detail->slot ?? '')); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Order'); ?> </label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="<?php echo app('translator')->get('Order'); ?>" min="0"
                                                        value="<?php echo e(old('iorder') ?? ($detail->iorder ?? 0)); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Status'); ?> </label>
                                                    <select required name="status" class="form-control select2">
                                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>"
                                                                <?php echo e(isset($detail->status) && $detail->status == $key ? 'selected' : ''); ?>>
                                                                <?php echo e(__($val)); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sw_featured"><?php echo app('translator')->get('Là năm cuối'); ?></label>
                                                    <div class="sw_featured d-flex-al-center">
                                                        <label class="switch ">
                                                            <input id="sw_featured" name="is_lastyear" value="1"
                                                                type="checkbox"
                                                                <?php echo e(isset($detail->is_lastyear) && $detail->is_lastyear == 1 ? 'checked' : ''); ?>>
                                                            <span class="slider round"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane " id="tab_2">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box" style="border-top: 3px solid #d2d6de;">
                                                    <div class="box-header">
                                                        <h3 class="box-title"><?php echo app('translator')->get('Danh sách học viên'); ?></h3>
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm btn_modal_student pull-right">Thêm
                                                            học viên</button>
                                                    </div>
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky">
                                                            <thead>
                                                                <tr>
                                                                    <th><?php echo app('translator')->get('Mã Học Viên'); ?></th>
                                                                    <th><?php echo app('translator')->get('Họ tên'); ?></th>
                                                                    <th><?php echo app('translator')->get('Nickname'); ?></th>
                                                                    <th><?php echo app('translator')->get('Ngày vào'); ?></th>
                                                                    <th><?php echo app('translator')->get('Ngày ra'); ?></th>
                                                                    <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                                                    <th><?php echo app('translator')->get('Loại'); ?></th>
                                                                    <th><?php echo app('translator')->get('Bỏ chọn'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="box_student">
                                                                <?php if(isset($detail->students)): ?>
                                                                    <?php $__currentLoopData = $detail->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr class="item_student"
                                                                            data-id="<?php echo e($item->id); ?>">
                                                                            <td><?php echo e($item->student_code); ?></td>
                                                                            <td><?php echo e($item->last_name ?? ''); ?>

                                                                                <?php echo e($item->first_name ?? ''); ?></td>
                                                                            <td><?php echo e($item->nickname ?? ''); ?></td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="student[<?php echo e($item->id); ?>][start_at]"
                                                                                    value="<?php echo e(optional($item->pivot)->start_at ? date('Y-m-d', strtotime($item->pivot->start_at)) : ''); ?>">
                                                                            </td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="student[<?php echo e($item->id); ?>][stop_at]"
                                                                                    value="<?php echo e(optional($item->pivot)->stop_at ? date('Y-m-d', strtotime($item->pivot->stop_at)) : ''); ?>">
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control select2 w-100"
                                                                                    name="student[<?php echo e($item->id); ?>][status]">
                                                                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <option value="<?php echo e($val); ?>"
                                                                                            <?php echo e(isset($item->pivot->status) && $item->pivot->status == $val ? 'selected' : ''); ?>>
                                                                                            <?php echo e(__($val)); ?>

                                                                                        </option>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select class="form-control select2 w-100"
                                                                                    name="student[<?php echo e($item->id); ?>][type]">
                                                                                    <?php $__currentLoopData = $type_student; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <option value="<?php echo e($val); ?>"
                                                                                            <?php echo e($item->pivot->type == $val ? 'selected' : ''); ?>>
                                                                                            <?php echo e(__($val)); ?>

                                                                                        </option>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </select>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <input type="checkbox" checked
                                                                                    onclick="this.parentNode.parentNode.remove()">
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane " id="tab_3">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="box">
                                                    <div class="box-header">
                                                        <h3 class="box-title"><?php echo app('translator')->get('Danh sách giáo viên'); ?></h3>
                                                        <button type="button"
                                                            class="btn btn-warning btn-sm btn_modal_teacher pull-right">Thêm
                                                            giáo
                                                            viên</button>
                                                    </div>
                                                    <div class="box-body no-padding">
                                                        <table class="table table-hover sticky ">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th><?php echo app('translator')->get('Giáo viên'); ?></th>
                                                                    <th><?php echo app('translator')->get('Ngày bắt đầu'); ?></th>
                                                                    <th><?php echo app('translator')->get('Ngày kết thúc'); ?></th>
                                                                    <th><?php echo app('translator')->get('GVCN'); ?></th>
                                                                    <th><?php echo app('translator')->get('Status'); ?></th>
                                                                    <th><?php echo app('translator')->get('Bỏ chọn'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="box_teacher">
                                                                <?php if(isset($detail->teacher)): ?>
                                                                    <?php $__currentLoopData = $detail->teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr class="item_teacher"
                                                                            data-id="<?php echo e($item->id); ?>">
                                                                            <td><?php echo e($item->name ?? ''); ?> </td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="teacher[<?php echo e($item->id); ?>][start_at]"
                                                                                    value="<?php echo e(optional($item->pivot)->start_at ? date('Y-m-d', strtotime($item->pivot->start_at)) : ''); ?>">
                                                                            </td>
                                                                            <td><input type="date" class="form-control"
                                                                                    name="teacher[<?php echo e($item->id); ?>][stop_at]"
                                                                                    value="<?php echo e(optional($item->pivot)->stop_at ? date('Y-m-d', strtotime($item->pivot->stop_at)) : ''); ?>">
                                                                            </td>

                                                                            <td>
                                                                                <div class="sw_featured d-flex-al-center">
                                                                                    <label class="switch">
                                                                                        <input
                                                                                            class="teacher_main about-banner"
                                                                                            name="teacher[<?php echo e($item->id); ?>][is_teacher_main]"
                                                                                            type="checkbox" value="1"
                                                                                            <?php echo e(isset($item->pivot->is_teacher_main) && $item->pivot->is_teacher_main == '1' ? 'checked' : ''); ?>>
                                                                                        <span class="slider round"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="w-100">
                                                                                    <select class="form-control select2 w-100"
                                                                                        name="teacher[<?php echo e($item->id); ?>][status]">
                                                                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                            <option
                                                                                                value="<?php echo e($val); ?>"
                                                                                                <?php echo e(isset($item->pivot->status) && $item->pivot->status == $val ? 'selected' : ''); ?>>
                                                                                                <?php echo e(__($val)); ?>

                                                                                            </option>
                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                    </select>
                                                                                </div>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <input type="checkbox" checked
                                                                                    onclick="this.parentNode.parentNode.remove()">
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php endif; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-success btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <button type="submit" class="btn btn-primary pull-right btn-sm"><i
                                    class="fa fa-floppy-o"></i>
                                <?php echo app('translator')->get('Save'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="modal fade" id="modal_teacher" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Thêm giáo viên vào lớp'); ?></h3>
                        </h3>
                    </div>
                    <div class="box_alert_modal">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Giáo viên'); ?> <small class="text-red">*</small></label>
                                    <select required id="select_teacher" name="teacher_id[]" multiple
                                        class="form-control select2  w-100">
                                        <option value="" disabled><?php echo app('translator')->get('Please select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Thời gian bắt đầu'); ?></label>
                                    <input class="form-control start_at" type="date" name="start_at"
                                        value="<?php echo e(date('Y-m-d')); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Thời gian kết thúc'); ?></label>
                                    <input class="form-control stop_at" type="date" name="stop_at" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info mr-10 btn_confirm_teacher">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('Xác nhận'); ?>
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-remove"></i> <?php echo app('translator')->get('Close'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_student" data-backdrop="static" tabindex="-1" role="dialog">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Thêm học viên vào lớp'); ?></h3>
                        </h3>
                    </div>
                    <div class="box_alert_modal">
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Chọn học viên'); ?> <small class="text-red">*</small></label>
                                    <select required id="select_student" name="student_id[]" multiple
                                        class="form-control select2  w-100">
                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Thời gian bắt đầu'); ?></label>
                                    <input class="form-control start_at" type="date" name="start_at"
                                        value="<?php echo e(date('Y-m-d')); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo app('translator')->get('Thời gian kết thúc'); ?></label>
                                    <input class="form-control stop_at" type="date" name="stop_at" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info mr-10 btn_confirm_student">
                            <i class="fa fa-check"></i> <?php echo app('translator')->get('Xác nhận'); ?>
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
        var students = <?php echo json_encode($students, 15, 512) ?>;
        var teachers = <?php echo json_encode($teachers, 15, 512) ?>;


        // Thêm giáo viên vào lớp
        $(document).on('click', '.btn_confirm_teacher', function() {
            var arr_id_teacher = $('#select_teacher').val();
            var start_at = $('#modal_teacher .start_at').val();
            var stop_at = $('#modal_teacher .stop_at').val();
            var selectedTeachers = arr_id_teacher.map(id => {
                return teachers.find(teacher => teacher.id == id);
            });
            selectedTeachers.forEach(teacher => {
                if (teacher) {
                    $('.box_teacher').append(`
                        <tr class="item_teacher" data-id="${teacher.id}">
                            <td> ${teacher.name}</td>
                            <td><input type="date" class="form-control"
                                    name="teacher[${teacher.id}][start_at]"
                                    value="${start_at}">
                            </td>
                            <td><input type="date" class="form-control"
                                    name="teacher[${teacher.id}][stop_at]"
                                    value="${stop_at}">
                            </td>

                            <td>
                                <div class="sw_featured d-flex-al-center">
                                    <label class="switch">
                                        <input
                                            class="teacher_main about-banner"
                                            name="teacher[${teacher.id}][is_teacher_main]"
                                            type="checkbox" value="1">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="w-100">
                                    <div class="w-100">
                                        <select class="form-control select2 w-100"
                                            name="teacher[${teacher.id}][status]">
                                            <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($val); ?>"><?php echo e(__($val)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" checked
                                    onclick="this.parentNode.parentNode.remove()">
                            </td>
                        </tr>
                    `);
                }
            });
            $('.select2').select2();
            $('#select_teacher').val([]).trigger('change');
            $('#modal_teacher').modal('hide');
        })
        $(document).on('click', '.btn_modal_teacher', function() {
            let dataIds = $(".item_teacher").map(function() {
                return $(this).data("id");
            }).get();

            let optionsHtml = teachers.map(teacher => {
                let isDisabled = dataIds.includes(teacher.id); // Kiểm tra ID có trong mảng dataIds
                return `<option value="${teacher.id}" ${isDisabled ? 'disabled' : ''}>${teacher.name}</option>`;
            }).join("");
            $("#select_teacher").html(optionsHtml);
            $('#modal_teacher').modal('show');
        });
        // Thêm học sinh vào lớp
        $(document).on('click', '.btn_modal_student', function() {
            let dataIds = $(".item_student").map(function() {
                return $(this).data("id");
            }).get();
            let optionsHtml = students.map(student => {
                let isDisabled = dataIds.includes(student.id); // Kiểm tra ID có trong mảng dataIds
                return `<option value="${student.id}" ${isDisabled ? 'disabled' : ''}>${student.last_name} ${student.first_name} - ${student.nickname}</option>`;
            }).join("");
            $("#select_student").html(optionsHtml);
            $('#modal_student').modal('show');
        });
        $(document).on('click', '.btn_confirm_student', function() {
            var arr_id_student = $('#select_student').val();
            var start_at = $('#modal_student .start_at').val();
            var stop_at = $('#modal_student .stop_at').val();
            var selectedStudent = arr_id_student.map(id => {
                return students.find(student => student.id == id);
            });
            console.log(selectedStudent);

            selectedStudent.forEach(student => {
                if (student) {
                    $('.box_student').append(`
                        <tr class="item_student" data-id="${student.id}">
                            <td>${student.student_code}</td>
                            <td>${student.last_name} ${student.first_name} </td>
                            <td>${student.nickname}</td>
                            <td><input type="date" class="form-control"
                                    name="student[${student.id}][start_at]"
                                    value="${start_at}">
                            </td>
                            <td><input type="date" class="form-control"
                                    name="student[${student.id}][stop_at]"
                                    value="${stop_at}">
                            </td>
                            <td>
                                <select class="form-control select2 w-100"
                                    name="student[${student.id}][status]">
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>"
                                            <?php echo e(__($val)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control select2 w-100"
                                    name="student[${student.id}][type]">
                                    <?php $__currentLoopData = $type_student; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val); ?>"
                                            <?php echo e(__($val)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" checked
                                    onclick="this.parentNode.parentNode.remove()">
                            </td>
                        </tr>
                    `);
                }
            });
            $('.select2').select2();
            $('#select_student').val([]).trigger('change');
            $('#modal_student').modal('hide');
        })

        // Chọn GVCN là duy nhất
        $(document).on('change', '.teacher_main', function() {
            if ($(this).is(':checked')) {
                $('.teacher_main').not(this).prop('checked', false);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/classs/edit.blade.php ENDPATH**/ ?>