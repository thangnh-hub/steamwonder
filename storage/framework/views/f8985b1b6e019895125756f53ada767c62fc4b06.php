

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
                                            <h5>Thông tin thực phẩm <span class="text-danger">*</span></h5>
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
                                                    <label><?php echo app('translator')->get('Danh mục thực phẩm'); ?><span class="text-danger">*</span></label>
                                                    <select required name="ingredient_category_id" class="form-control select2"style="width: 100%;">
                                                        <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                        <?php $__currentLoopData = $list_ingredient_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($item->id); ?>"
                                                                <?php echo e(isset($detail->ingredient_category_id) && $detail->ingredient_category_id == $item->id ? 'selected' : ''); ?>><?php echo e(__($item->name??"")); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="name"><?php echo app('translator')->get('Tên thực phẩm'); ?> <span class="text-danger">*</span></label>
                                                    <input placeholder="<?php echo app('translator')->get('Tên thực phẩm'); ?>" type="text" name="name" class="form-control" value="<?php echo e(old('name', $detail->name ?? '')); ?>" required>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status"><?php echo app('translator')->get('Trạng thái'); ?></label>
                                                    <select name="status" class="form-control select2">
                                                        <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e(old('status', $detail->status ?? 1) == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="status"><?php echo app('translator')->get('Đơn vị chính'); ?></label>
                                                    <select name="default_unit_id" class="form-control select2">">
                                                        <?php $__currentLoopData = $list_unit_id; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($value->id); ?>" <?php echo e(old('default_unit_id', $detail->default_unit_id ?? 1) == $value->id ? 'selected' : ''); ?>><?php echo e($value->name ?? ""); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="convert_to_gram"><?php echo app('translator')->get('Chuyển sang g (hoặc ml)'); ?> </label>
                                                    <input placeholder="<?php echo app('translator')->get('Chuyển sang g (hoặc ml)'); ?>" type="text" name="convert_to_gram" class="form-control" value="<?php echo e(old('convert_to_gram', $detail->convert_to_gram ?? '')); ?>" >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Loại'); ?></label>
                                                    <select name="type" class="form-control select2">
                                                        <?php $__currentLoopData = $list_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($key); ?>" <?php echo e(old('type', $detail->type ?? 1) == $key ? 'selected' : ''); ?>><?php echo e(__($value)); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for=""><?php echo app('translator')->get('Mô tả'); ?></label>
                                                    <textarea name="description" rows="5" class="form-control" placeholder="Mô tả"><?php echo e($detail->description ?? ""); ?></textarea>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/meal/ingredients/edit.blade.php ENDPATH**/ ?>