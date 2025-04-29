

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        .align-items-end{
            display: flex;
            justify-items: end
        }
    </style>
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

        <form action="<?php echo e(route(Request::segment(2) . '.update', $service->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo app('translator')->get('Edit form'); ?></h3>
                        </div>
        
                        <div class="box-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="d-flex-wap">
                                        <!-- Tên dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Tên dịch vụ'); ?> <small class="text-danger">*</small></label>
                                                <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $service->name)); ?>" required>
                                            </div>
                                        </div>
        
                                        <!-- Khu vực -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Khu vực'); ?> <small class="text-danger">*</small></label>
                                                <select name="area_id" class="form-control select2" required>
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == old('area_id', $service->area_id) ? 'selected' : ''); ?>>
                                                            <?php echo e($item->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
        
                                        <!-- Nhóm dịch vụ -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Nhóm dịch vụ'); ?> <small class="text-danger">*</small></label>
                                                <select name="service_category_id" class="form-control select2" required>
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_service_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == old('service_category_id', $service->service_category_id) ? 'selected' : ''); ?>>
                                                            <?php echo e($item->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Hệ đào tạo'); ?> </label>
                                                <select name="education_program_id" class="form-control select2" style="width: 100%;" >
                                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                                    <?php $__currentLoopData = $list_education_program; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == old('education_program_id', $service->education_program_id) ? 'selected' : ''); ?>>
                                                        <?php echo e($item->name); ?>

                                                    </option>
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
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == old('education_age_id', $service->education_age_id) ? 'selected' : ''); ?>><?php echo e($item->name ?? ""); ?></option>
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
                                                        <option value="<?php echo e($key); ?>" <?php echo e($key == old('is_attendance', $service->is_attendance) ? 'selected' : ''); ?>><?php echo e($item); ?></option>
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
                                                        <option value="<?php echo e($key); ?>" <?php echo e($key == old('is_default', $service->is_default) ? 'selected' : ''); ?>><?php echo e($item); ?></option>
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
                                                        <option value="<?php echo e($key); ?>" <?php echo e($key == old('service_type', $service->service_type) ? 'selected' : ''); ?>><?php echo e(__($item)); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                    
                                        <!-- Thứ tự -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Thứ tự'); ?></label>
                                                <input type="number" name="iorder" class="form-control" placeholder="<?php echo app('translator')->get('Nhập thứ tự'); ?>" value="<?php echo e($service->iorder ?? ""); ?>">
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
        
                                        <!-- Chi tiết dịch vụ (service_detail) -->
                                        <hr>
                                        <div class="box-header col-md-12">
                                            <h3 class="box-title"><?php echo app('translator')->get('Giá tiền lũy tiến và thời điểm áp dụng:'); ?></h3>
                                        </div>
                                        <div class="col-md-12 service-detail-wrapper" style="padding-left: 0px">
                                            <?php
                                                $details = old('service_detail', $service->serviceDetail ?? []);
                                            ?>
                                        
                                            <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-12" style="padding-left: 0px">
                                                <div class="service-detail" data-key="<?php echo e($key); ?>">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số tiền'); ?> <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[<?php echo e($key); ?>][price]" class="form-control" placeholder="<?php echo app('translator')->get('Nhập số tiền'); ?>"
                                                                    value="<?php echo e(old('service_detail.' . $key . '.price', $detail['price'] ?? $detail->price ?? '')); ?>">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Số lượng'); ?> <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[<?php echo e($key); ?>][quantity]" class="form-control" placeholder="<?php echo app('translator')->get('Nhập số lượng'); ?>"
                                                                    value="<?php echo e(old('service_detail.' . $key . '.quantity', $detail['quantity'] ?? $detail->quantity ?? '')); ?>">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Từ ngày'); ?> <small class="text-danger">*</small></label>
                                                            <input required type="date" name="service_detail[<?php echo e($key); ?>][start_at]" class="form-control"
                                                                    value="<?php echo e(old('service_detail.' . $key . '.start_at', isset($detail['start_at']) ? \Illuminate\Support\Carbon::parse($detail['start_at'])->format('Y-m-d') : (isset($detail->start_at) ? \Illuminate\Support\Carbon::parse($detail->start_at)->format('Y-m-d') : ''))); ?>">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Đến ngày'); ?></label>
                                                            <input required type="date" name="service_detail[<?php echo e($key); ?>][end_at]" class="form-control"
                                                                    value="<?php echo e(old('service_detail.' . $key . '.end_at', isset($detail['end_at']) ? \Illuminate\Support\Carbon::parse($detail['end_at'])->format('Y-m-d') : (isset($detail->end_at) ? \Illuminate\Support\Carbon::parse($detail->end_at)->format('Y-m-d') : ''))); ?>">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <div class="form-group">
                                                            <label><?php echo app('translator')->get('Chức năng'); ?></label>
                                                            <button type="button" class="btn btn-danger btn-remove-detail btn-sm"><i class="fa fa-trash"></i></button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        
                                        <!-- Nút Thêm dòng -->
                                        <div style="padding-left:15px " class="mt-3">
                                            <button type="button" class="btn btn-primary" id="btn-add-detail">
                                                <i class="fa fa-plus"></i> <?php echo app('translator')->get('Thêm'); ?>
                                            </button>
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
    $(document).ready(function() {
    
        $('#btn-add-detail').click(function() {
            let indexDetail = Date.now(); // Lấy time hiện tại làm key
    
            let html = `
            <div class="col-md-12" style="padding-left: 0px">
                                                <div class="service-detail" data-key="${indexDetail}">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Số tiền <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[${indexDetail}][price]" class="form-control" placeholder="Nhập số tiền"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <label>Số lượng <small class="text-danger">*</small></label>
                                                            <input required type="number" name="service_detail[${indexDetail}][quantity]" class="form-control" placeholder="Nhập số lượng"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Từ ngày <small class="text-danger">*</small></label>
                                                            <input required type="date" name="service_detail[${indexDetail}][start_at]" class="form-control"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Đến ngày</label>
                                                            <input required type="date" name="service_detail[${indexDetail}][end_at]" class="form-control"
                                                                    value="">
                                                        </div>
                                                    </div>
                                        
                                                    <div class="col-md-1 d-flex align-items-end">
                                                        <div class="form-group">
                                                            <label>Chức năng</label>
                                                            <button type="button" class="btn btn-danger btn-remove-detail btn-sm"><i class="fa fa-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`;
    
            $('.service-detail-wrapper').append(html);
        });
    
        // Sự kiện xóa dòng
        $(document).on('click', '.btn-remove-detail', function() {
            $(this).closest('.service-detail').remove();
        });
    
    });
    </script>
    
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/services/edit.blade.php ENDPATH**/ ?>