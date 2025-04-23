

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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Create form'); ?></h3>
                        </div>
                        <form action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab">
                                                <h5>Thông tin mối quan hệ <span class="text-danger">*</span></h5>
                                            </a>
                                        </li>
                                        <button type="submit" class="btn btn-info btn-sm pull-right">
                                            <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                                        </button>
                                    </ul>
            
                                    <div class="tab-content">
                                        <div class="tab-pane active" >
                                            <div class="d-flex-wap">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tiêu đề'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="title" value="<?php echo e(old('title')); ?>" required>
                                                    </div>
                                                </div>
            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Mô tả'); ?></label>
                                                        <input type="text" class="form-control" name="description" value="<?php echo e(old('description')); ?>">
                                                    </div>
                                                </div>
            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Thứ tự'); ?></label>
                                                        <input type="number" class="form-control" name="iorder" value="<?php echo e(old('iorder', 0)); ?>">
                                                    </div>
                                                </div>
            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                        <select name="status" class="form-control select2">
                                                            <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>" <?php echo e(old('status') == $key ? 'selected' : ''); ?>>
                                                                    <?php echo e(__($value)); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
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
                        </form>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/relationships/create.blade.php ENDPATH**/ ?>