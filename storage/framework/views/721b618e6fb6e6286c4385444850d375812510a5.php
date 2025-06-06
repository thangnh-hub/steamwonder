<?php $__env->startSection('content'); ?>
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-address-card"></i> <?php echo app('translator')->get('Thông tin học sinh'); ?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p>
                                                <strong><?php echo app('translator')->get('Họ và tên'); ?>: </strong>
                                                <?php echo e($student->first_name ?? ''); ?>

                                                <?php echo e($student->last_name ?? ''); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Nickname'); ?>:
                                                </strong><?php echo e($student->nickname ?? 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Mã HS'); ?>:
                                                </strong><?php echo e($student->student_code ?? 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Giới tính'); ?>:
                                                </strong><?php echo e(__($student->sex) ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Ngày sinh'); ?>:</strong>
                                                <?php echo e($student->birthday != '' ? date('d/m/Y', strtotime($student->birthday)) : 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Địa chỉ'); ?>:
                                                </strong><?php echo e($student->address ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Mã lớp'); ?>: </strong><?php echo e($student->currentClass->code ?? ''); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Tên lớp'); ?>:
                                                </strong><?php echo e($student->currentClass->name ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Hệ đào tạo'); ?>:
                                                </strong><?php echo e($student->currentClass->education_programs->name ?? 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Ngày nhập học'); ?>:
                                                </strong><?php echo e($student->enrolled_at != '' ? date('d/m/Y', strtotime($student->enrolled_at)) : 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-user-circle-o"></i> <?php echo app('translator')->get('Thông tin phụ huynh'); ?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <?php $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-6 row mb-3">
                                        <div class="col-sm-12">
                                            <p>
                                                <strong><?php echo e($parent['relationship']->title); ?>: </strong>
                                                <?php echo e($parent['parent']->first_name ?? ''); ?>

                                                <?php echo e($parent['parent']->last_name ?? ''); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Phone'); ?>:
                                                </strong><?php echo e($parent['parent']->phone ?? 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Email'); ?>:
                                                </strong><?php echo e($parent['parent']->email ?? 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Address'); ?>:
                                                </strong><?php echo e(__($parent['parent']->address) ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/frontend/pages/user/student.blade.php ENDPATH**/ ?>