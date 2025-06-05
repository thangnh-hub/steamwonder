<?php $__env->startSection('content'); ?>
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h3 class="box-title">
                                <i class="fa fa-graduation-cap"></i> <?php echo app('translator')->get('Thông tin lớp học'); ?>
                            </h3>
                        </div>
                        <div class="box-body">
                            <?php $__currentLoopData = $classs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Mã lớp'); ?>: </strong><?php echo e($class->code ?? ''); ?></p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Tên lớp'); ?>: </strong><?php echo e($class->name ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Hệ đào tạo'); ?>:
                                                </strong><?php echo e($class->education_programs->name ?? 'Chưa cập nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Nhóm tuổi'); ?>:
                                                </strong><?php echo e($class->education_ages->name ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Phòng học'); ?>:</strong>
                                                <?php echo e($class->room->name ?? 'Chưa câp nhật'); ?>

                                            </p>
                                        </div>
                                        <div class="col-sm-12">
                                            <p><strong><?php echo app('translator')->get('Khu vực'); ?>:
                                                </strong><?php echo e($class->area->name ?? 'Chưa cập nhật'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/frontend/pages/user/class.blade.php ENDPATH**/ ?>