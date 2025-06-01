<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">
                <h5 class="fw-bold">Thông tin chính</h5>
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
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Khu vực'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->area->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Mã lớp'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->code ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Tên lớp'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Nhóm trẻ'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->education_ages->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Hệ đào tạo'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->education_programs->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Phòng học'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->room->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Sức chứa'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <p><?php echo e($detail->slot ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <label class="control-label"><strong><?php echo app('translator')->get('Năm cuối'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <div class="sw_featured d-flex-al-center">
                                    <label class="switch">
                                        <input class="teacher_main about-banner" type="checkbox" value="1" disabled
                                            <?php echo e(isset($detail->is_lastyear) && $detail->is_lastyear == '1' ? 'checked' : ''); ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Ngày tạo'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e(date('H:i - d/m/Y', strtotime($detail->created_at))); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Người tạo'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e($detail->admin_created->name ?? ''); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Ngày cập nhật'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e(date('H:i - d/m/Y', strtotime($detail->updated_at))); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label class="control-label"><strong><?php echo app('translator')->get('Người cập nhật'); ?></strong></label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <p><?php echo e($detail->admin_updated->name ?? ''); ?></p>
                            </div>
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
                                    </tr>
                                </thead>
                                <tbody class="box_student">
                                    <?php if(isset($detail->students)): ?>
                                        <?php $__currentLoopData = $detail->students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="item_student" data-id="<?php echo e($item->id); ?>">
                                                <td><?php echo e($item->student_code); ?></td>
                                                <td><?php echo e($item->last_name ?? ''); ?>

                                                    <?php echo e($item->first_name ?? ''); ?></td>
                                                <td><?php echo e($item->nickname ?? ''); ?></td>
                                                <td><?php echo e(optional($item->pivot)->start_at ? date('d-m-Y', strtotime($item->pivot->start_at)) : ''); ?>

                                                </td>
                                                <td><?php echo e(optional($item->pivot)->stop_at ? date('d-m-Y', strtotime($item->pivot->stop_at)) : ''); ?>

                                                </td>
                                                <td><?php echo e(__($item->pivot->status)); ?> </td>
                                                <td><?php echo e(__($item->pivot->type)); ?> </td>
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
                                    </tr>
                                </thead>
                                <tbody class="box_teacher">
                                    <?php if(isset($detail->teacher)): ?>
                                        <?php $__currentLoopData = $detail->teacher; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="item_teacher" data-id="<?php echo e($item->id); ?>">
                                                <td><?php echo e($item->name ?? ''); ?> </td>
                                                <td><?php echo e(optional($item->pivot)->start_at ? date('d-m-Y', strtotime($item->pivot->start_at)) : ''); ?>

                                                </td>
                                                <td><?php echo e(optional($item->pivot)->stop_at ? date('d-m-Y', strtotime($item->pivot->stop_at)) : ''); ?>

                                                </td>
                                                <td>
                                                    <div class="sw_featured d-flex-al-center">
                                                        <label class="switch">
                                                            <input class="teacher_main about-banner"
                                                                name="teacher[<?php echo e($item->id); ?>][is_teacher_main]"
                                                                type="checkbox" value="1" disabled
                                                                <?php echo e(isset($item->pivot->is_teacher_main) && $item->pivot->is_teacher_main == '1' ? 'checked' : ''); ?>>

                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td><?php echo e(__($item->pivot->status)); ?></td>
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
<?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/classs/show.blade.php ENDPATH**/ ?>