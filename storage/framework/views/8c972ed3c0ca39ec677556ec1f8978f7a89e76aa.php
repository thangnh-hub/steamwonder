

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .item_service {
            margin-bottom: 10px;
            align-items: center;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
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
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khu vực'); ?> </label>
                                <select name="area_id" class="form-control select2">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"> <?php echo e($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Loại phí dịch vụ'); ?> <small class="text-red">*</small></label>
                                <select required name="type" class="form-control select2">
                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(old('type') && old('type') == $val ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày bắt đầu áp dụng'); ?> </label>
                                <input type="date" class="form-control" name="time_start"
                                    value="<?php echo e(old('time_start')); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày kết thúc áp dụng'); ?> </label>
                                <input type="date" class="form-control" name="time_end" value="<?php echo e(old('time_end')); ?>">
                            </div>
                        </div>
                        <div class="col-md-12 mt-15">
                            <h4 class="box-title">Danh sách phí theo khung giờ</h4>
                            <div class="list_item mt-10">
                            </div>

                            <button class="btn btn-sm btn-primary btn_addtime" type="button">
                                <i class="fa fa-plus"></i> <?php echo app('translator')->get('Thêm khung giờ'); ?>
                            </button>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-success btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                        </a>
                        <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                            <?php echo app('translator')->get('Save'); ?></button>
                    </div>
                </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $('.btn_addtime').click(function() {
            var currentDateTime = Math.floor(Date.now() / 100);
            let _html = `
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo app('translator')->get('Thời gian từ'); ?> </label>
                        <input type="time" class="form-control" name="time_range[${currentDateTime}][block_start]"
                            value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo app('translator')->get('Thời gian đến'); ?> </label>
                        <input type="time" class="form-control" name="time_range[${currentDateTime}][block_end]"
                            value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo app('translator')->get('Số tiền'); ?> </label>
                        <input type="number" class="form-control" name="time_range[${currentDateTime}][price]"
                            placeholder="<?php echo app('translator')->get('Số tiền'); ?>" value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                        onclick="$(this).closest('.row').remove()" title="<?php echo app('translator')->get('Delete'); ?>"
                        data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
            `;
            $('.list_item').append(_html);
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/service_config/create.blade.php ENDPATH**/ ?>