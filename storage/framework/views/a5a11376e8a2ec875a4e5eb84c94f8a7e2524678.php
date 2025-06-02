

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
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
        <form action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Edit form'); ?></h3>
                        </div>

                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Chi tiết chuyển đổi <span class="text-danger">*</span></h5>
                                        </a>
                                    </li>
                                    <button type="submit" class="btn btn-info btn-sm pull-right">
                                        <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                                    </button>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="d-flex-wap">
                                            <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Đơn vị gốc <span class="text-danger">*</span></label>
                                                        <select name="from_unit_id" class="form-control select2" required>
                                                            <option value="">-- Chọn đơn vị --</option>
                                                            <?php $__currentLoopData = $list_units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($unit->id); ?>" <?php echo e(old('from_unit_id', $detail->from_unit_id ?? '') == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->name ?? ""); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Đơn vị chuyển đổi sang <span class="text-danger">*</span></label>
                                                        <select name="to_unit_id" class="form-control select2" required>
                                                            <option value="">-- Chọn đơn vị --</option>
                                                            <?php $__currentLoopData = $list_units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($unit->id); ?>" <?php echo e(old('to_unit_id', $detail->to_unit_id ?? '') == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->name ?? ""); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Hệ số chuyển đổi <span class="text-danger">*</span></label>
                                                        <input type="number" step="0.0001" name="ratio" class="form-control" value="<?php echo e(old('ratio', $detail->ratio ?? '')); ?>" required>
                                                    </div>
                                                </div>
                                        </div>
                                    </div> <!-- tab-pane -->
                                </div> <!-- tab-content -->
                            </div>
                        </div>

                        <div class="box-footer">
                            <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/unit_conversions/edit.blade.php ENDPATH**/ ?>