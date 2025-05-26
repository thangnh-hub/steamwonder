

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

        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST" id="form_product">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Create form'); ?></h3>
                        </div>
                        <form action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#tab_1" data-toggle="tab">
                                                <h5>Thông tin nhà cung cấp <span class="text-danger">*</span></h5>
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
                                                        <label for="area_id">Khu vực <span class="text-danger">*</span></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value="">-- Chọn khu vực --</option>
                                                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($area->id); ?>" <?php echo e(old('area_id', $detail->area_id ?? '') == $area->id ? 'selected' : ''); ?>><?php echo e($area->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="name">Tên nhà cung cấp <span class="text-danger">*</span></label>
                                                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $detail->name ?? '')); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="code">Mã nhà cung cấp <span class="text-danger">*</span></label>
                                                        <input type="text" name="code" class="form-control" value="<?php echo e(old('code', $detail->name ?? '')); ?>" required>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="phone">Số điện thoại</label>
                                                    <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $detail->phone ?? '')); ?>">
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="address">Địa chỉ</label>
                                                        <input type="text" name="address" class="form-control" value="<?php echo e(old('address', $detail->address ?? '')); ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="status">Trạng thái</label>
                                                        <select name="status" class="form-control">
                                                            <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>" <?php echo e(old('status', $detail->status ?? 1) == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/suppliers/create.blade.php ENDPATH**/ ?>