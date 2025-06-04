<?php $__env->startSection('content'); ?>
    <section class="student">
        <div class="container">
            <div class="row">
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

<?php echo $__env->make('frontend.layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/frontend/pages/user/parent.blade.php ENDPATH**/ ?>