

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
                        <div class="box-body">
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">
                                            <h5>Thông tin thực đơn<span class="text-danger">*</span></h5>
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
                                                    <label for="name"><?php echo app('translator')->get('Tên thực đơn'); ?> <span class="text-danger">*</span></label>
                                                    <input placeholder="<?php echo app('translator')->get('Tên thực đơn'); ?>" type="text" name="name" class="form-control" value="<?php echo e(old('name', $detail->name ?? '')); ?>" required>
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Nhóm tuổi áp dụng'); ?> <span class="text-danger">*</span></label>
                                                    <select name="meal_age_id" class="form-control select2" required>
                                                        <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                                        <?php $__currentLoopData = $list_meal_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($item->id); ?>" <?php echo e((old('meal_age_id', $detail->meal_age_id ?? '') == $item->id) ? 'selected' : ''); ?>><?php echo e($item->name ?? ""); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="count_student"><?php echo app('translator')->get('Số lượng học sinh'); ?></label>
                                                    <input required type="number" name="count_student" class="form-control" value="<?php echo e(old('count_student', $detail->count_student ?? '')); ?>" min="0">
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Mùa áp dụng'); ?></label>
                                                    <select name="season" class="form-control select2">
                                                        <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                                        <?php $__currentLoopData = $list_season; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e((old('season', $detail->season ?? '') == $key) ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status"><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                    <select name="status" class="form-control select2">
                                                        <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e(old('status', $detail->status ?? 1) == $key ? 'selected' : ''); ?>><?php echo e(__($value)); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description"><?php echo app('translator')->get('Mô tả'); ?></label>
                                                    <textarea name="description" rows="4" class="form-control" placeholder="<?php echo app('translator')->get('Nhập mô tả'); ?>"><?php echo e(old('description', $detail->description ?? '')); ?></textarea>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/menu_plannings/create.blade.php ENDPATH**/ ?>