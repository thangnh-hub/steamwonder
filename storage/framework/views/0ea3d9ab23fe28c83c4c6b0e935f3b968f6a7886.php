

<?php $__env->startSection('title'); ?>
  <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
  
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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

        <div class="box box-default">
            <div class="box-header">
                <h3 class="text-title"><?php echo app('translator')->get($module_name); ?></h3>
            </div>
            <div class="box-body ">
                <div class="form-horizontal">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-primary">
                                <div class="box-body">
                                    <!-- Custom Tabs -->
                                    <div class="nav-tabs-custom">
                                        <ul class="nav nav-tabs">
                                            <li class="active">
                                                <a href="#tab_1" data-toggle="tab">
                                                    <h5>Thông tin chính </h5>
                                                </a>
                                            </li>
                                            <li class="">
                                                <a href="#tab_2" data-toggle="tab">
                                                    <h5>Người thân của bé</h5>
                                                </a>
                                            </li>
                                        </ul>
        
                                        <div class="tab-content">
                                            <!-- TAB 1: Thông tin học sinh -->
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Họ và tên'); ?>:</strong>
                                                            <?php echo e($detail->last_name ?? ''); ?> <?php echo e($detail->first_name ?? ''); ?>

                                                        </p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Tên thường gọi'); ?>:</strong>
                                                            <?php echo e($detail->nickname ?? ''); ?>

                                                        </p>
                                                    </div>
                                        
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Mã học sinh'); ?>:</strong>
                                                            <?php echo e($detail->student_code  ?? ''); ?>

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Ngày sinh'); ?>:</strong>
                                                            <?php echo e($detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : ''); ?>

                                                        </p>
                                                    </div>
                                                                                
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Giới tính'); ?>:</strong>
                                                            <?php echo e(__($detail->sex ?? '')); ?>

                                                        </p>
                                                    </div>
                                        
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Khu vực'); ?>:</strong>
                                                            <?php echo e($detail->area->name ?? ''); ?>

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Lớp đang học'); ?>:</strong>
                                                            <?php echo e($detail->currentClass->name ?? ''); ?>

                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Ngày nhập học'); ?>:</strong>
                                                            <?php echo e(isset($detail->enrolled_at) &&  $detail->enrolled_at !="" ?date("d-m-Y", strtotime($detail->enrolled_at)): ''); ?>

                                                        </p>
                                                    </div>


                                        
                                                    <div class="col-md-6">
                                                        <p><strong><?php echo app('translator')->get('Ảnh đại diện'); ?>:</strong><br>
                                                            <a target="_blank" href="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>">
                                                                <img src="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>" alt="avatar" style="max-height: 120px;">
                                                            </a>   
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                            <!-- TAB 2: Người thân -->
                                            <div class="tab-pane" id="tab_2">
                                                <?php if($detail->studentParents->isNotEmpty()): ?>
                                                    <table class="table table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo app('translator')->get('STT'); ?></th>
                                                                <th><?php echo app('translator')->get('Avatar'); ?></th>
                                                                <th><?php echo app('translator')->get('Họ và tên'); ?></th>
                                                                <th><?php echo app('translator')->get('Mối quan hệ'); ?></th>
                                                                <th><?php echo app('translator')->get('Giới tính'); ?></th>
                                                                <th><?php echo app('translator')->get('Ngày sinh'); ?></th>
                                                                <th><?php echo app('translator')->get('Số điện thoại'); ?></th>
                                                                <th><?php echo app('translator')->get('Email'); ?></th>
                                                                <th><?php echo app('translator')->get('Địa chỉ'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php $__currentLoopData = $detail->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($index + 1); ?></td>
                                                                    <td>
                                                                        <?php if(!empty($relation->parent->avatar)): ?>
                                                                            <img src="<?php echo e(asset($relation->parent->avatar)); ?>" alt="Avatar" width="100" height="100" style="object-fit: cover;">
                                                                        <?php else: ?>
                                                                            <span class="text-muted">No image</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td><?php echo e($relation->parent->last_name ?? ''); ?> <?php echo e($relation->parent->first_name ?? ''); ?></td>
                                                                    <td><?php echo e($relation->relationship->title ?? ''); ?></td>
                                                                    <td><?php echo e(__($relation->parent->sex ?? '')); ?></td>
                                                                    
                                                                    <td>
                                                                        <?php echo e($relation->parent->birthday ? \Carbon\Carbon::parse($relation->parent->birthday)->format('d/m/Y') : ''); ?>

                                                                    </td>
                                                                    
                                                                    <td><?php echo e($relation->parent->phone ?? ''); ?></td>
                                                                    <td><?php echo e($relation->parent->email ?? ''); ?></td>
                                                                    <td><?php echo e($relation->parent->address ?? ''); ?></td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                <?php else: ?>
                                                    <p class="text-muted"><?php echo app('translator')->get('Không có người thân nào được liên kết.'); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                    </div><!-- /.tab-content -->
                                </div><!-- nav-tabs-custom -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer clearfix">
                <a href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <button type="button" class="btn btn-sm btn-success">Danh sách</button>
                </a>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
  
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/students/detail.blade.php ENDPATH**/ ?>