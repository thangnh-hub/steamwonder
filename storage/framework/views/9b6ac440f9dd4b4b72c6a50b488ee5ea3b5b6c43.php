

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
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
                                                <h5>Thông tin học sinh <span class="text-danger">*</span></h5>
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
                                                        <label><?php echo app('translator')->get('Khu vực'); ?><small class="text-red">*</small></label>
                                                        <select name="area_id" class="form-control select2" required>
                                                            <option value=""><?php echo app('translator')->get('Chọn khu vực'); ?></option>
                                                            <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Họ'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="first_name" value="<?php echo e(old('first_name')); ?>" required>
                                                    </div>
                                                </div> 

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tên'); ?><small class="text-red">*</small></label>
                                                        <input type="text" class="form-control" name="last_name" value="<?php echo e(old('last_name')); ?>" required>
                                                    </div>
                                                </div>
            
                                                

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Tên thường gọi'); ?></label>
                                                        <input type="text" class="form-control" name="nickname" value="<?php echo e(old('nickname')); ?>" >
                                                    </div>
                                                </div>
                                               
            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Giới tính'); ?></label>
                                                        <select name="sex" class="form-control select2" >
                                                            <?php $__currentLoopData = $list_sex; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($key); ?>"><?php echo e(__($value)); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày sinh'); ?></label>
                                                        <input type="date" class="form-control" name="birthday" value="<?php echo e(old('birthday')); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Ngày nhập học'); ?></label>
                                                        <input type="date" class="form-control" name="enrolled_at" value="<?php echo e(old('enrolled_at')); ?>">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
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

                                                <div class="col-md-4">
                                                    <div class="form-group box_img_right">
                                                        <label><?php echo app('translator')->get('Ảnh đại diện'); ?></label>
                                                        <div id="image-holder">
                                                            <img src="<?php echo e(url('themes/admin/img/no_image.jpg')); ?>">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                                                                    data-type="cms-image">
                                                                    <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Choose'); ?>
                                                                </a>
                                                            </span>
                                                            <input id="image" class="form-control inp_hidden" type="hidden" name="avatar"
                                                                placeholder="<?php echo app('translator')->get('Image source'); ?>" value="<?php echo e($detail->image ?? ''); ?>">
                                                        </div>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/students/create.blade.php ENDPATH**/ ?>