

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Create form'); ?></h3>
                        </div>
        
                        <div class="box-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="d-flex-wap">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Tên dịch vụ'); ?> <small class="text-danger">*</small></label>
                                                <input type="text" name="name" class="form-control" placeholder="<?php echo app('translator')->get('Nhập tên dịch vụ'); ?>" required>
                                            </div>
                                        </div>
                    
                                        <!-- Khu vực -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Khu vực'); ?> <small class="text-danger">*</small></label>
                                                <select name="area_id" class="form-control select2" style="width: 100%;" required>
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Nhóm dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Nhóm dịch vụ'); ?> <small class="text-danger">*</small></label>
                                                <select name="service_category_id" class="form-control select2" style="width: 100%;" required>
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_service_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Hệ đào tạo -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Hệ đào tạo'); ?> </label>
                                                <select name="education_program_id" class="form-control select2" style="width: 100%;" >
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_education_program; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->name ?? ""); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Độ tuổi -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Độ tuổi'); ?> </label>
                                                <select name="education_age_id" class="form-control select2" style="width: 100%;" >
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_education_age; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->name ?? ""); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Tính chất dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Tính chất dịch vụ'); ?></label>
                                                <select name="is_attendance" class="form-control select2" style="width: 100%;">
                                                    <?php $__currentLoopData = $list_is_attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($item); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Mặc định -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Dịch vụ mặc định cho lớp'); ?></label>
                                                <select name="is_default" class="form-control select2" style="width: 100%;">
                                                    <?php $__currentLoopData = $list_is_default; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($item); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Loại dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Loại dịch vụ'); ?></label>
                                                <select name="service_type" class="form-control select2" style="width: 100%;">
                                                    <?php $__currentLoopData = $list_service_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e(__($item)); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Thứ tự -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Thứ tự'); ?></label>
                                                <input type="number" name="iorder" class="form-control" placeholder="<?php echo app('translator')->get('Nhập thứ tự'); ?>">
                                            </div>
                                        </div>
                    
                                        <!-- Trạng thái -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Status'); ?></label>
                                                <select name="status" class="form-control select2" style="width: 100%;">
                                                    <?php $__currentLoopData = $list_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo app('translator')->get($item); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="box-header ">
                                            <h3 class="box-title"><?php echo app('translator')->get('Giá tiền lũy tiến và thời điểm áp dụng:'); ?></h3>
                                        </div>
                                        <div style="padding-left: 0px" class="col-md-12">
                                            <div class="service-detail">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Số tiền'); ?> <small class="text-danger">*</small></label>
                                                        <input required type="number" name="service_detail[price]" class="form-control" placeholder="<?php echo app('translator')->get('Nhập số tiền'); ?>">
                                                    </div>        
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Số lượng'); ?> <small class="text-danger">*</small></label>
                                                        <input required type="number" name="service_detail[quantity]" class="form-control" placeholder="<?php echo app('translator')->get('Nhập số lượng'); ?>">
                                                    </div>        
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Từ ngày'); ?> <small class="text-danger">*</small></label>
                                                        <input required type="date" name="service_detail[start_at]" class="form-control">
                                                    </div>        
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo app('translator')->get('Đến ngày'); ?> <small class="text-danger">*</small></label>
                                                        <input required type="date" name="service_detail[end_at]" class="form-control">
                                                    </div>        
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
        
                        <div class="box-footer">
                            <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <button type="button" class="btn btn-sm btn-success">
                                    <i class="fa fa-list"></i> <?php echo app('translator')->get('Danh sách'); ?>
                                </button>
                            </a>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/services/create.blade.php ENDPATH**/ ?>