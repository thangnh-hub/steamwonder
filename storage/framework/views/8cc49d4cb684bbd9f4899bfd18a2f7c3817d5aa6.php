

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
            <div class="box-body table-responsive">
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
                                                    <h5>Mối quan hệ với bé</h5>
                                                </a>
                                            </li>
                                        </ul>
        
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab_1">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Họ và tên'); ?>:</strong>
                                                            <?php echo e($detail->first_name ?? ''); ?> <?php echo e($detail->last_name ?? ''); ?> 
                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Số CMND/CCCD'); ?>:</strong>
                                                            <?php echo e($detail->identity_card ?? ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Số điện thoại'); ?>:</strong>
                                                            <?php echo e($detail->phone ?? ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Email'); ?>:</strong>
                                                            <?php echo e($detail->email ?? ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Địa chỉ'); ?>:</strong>
                                                            <?php echo e($detail->address ?? ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Khu vực'); ?>:</strong>
                                                            <?php echo e($detail->area->name ?? ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Giới tính'); ?>:</strong>
                                                            <?php echo e($list_sex[$detail->sex] ?? ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Ngày sinh'); ?>:</strong>
                                                            <?php echo e($detail->birthday ? \Carbon\Carbon::parse($detail->birthday)->format('d/m/Y') : ''); ?>

                                                        </p>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <p class="job"><strong><?php echo app('translator')->get('Ảnh đại diện'); ?>:</strong><br>
                                                            <a target="_blank" href="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>">
                                                                <img src="<?php echo e($detail->avatar ?? url('themes/admin/img/no_image.jpg')); ?>" alt="avatar" style="max-height: 120px;">
                                                            </a>   
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                            </div>
        
                                            <div class="tab-pane " id="tab_2">
                                                <div class="tab-pane" id="tab_2">
                                                    <h4><?php echo app('translator')->get('Danh sách học sinh mà phụ huynh này là người thân'); ?></h4>
                                                    <br>
                                                    <?php if($detail->parentStudents->count()): ?>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th><?php echo app('translator')->get('Avatar'); ?></th>
                                                                    <th><?php echo app('translator')->get('Student code'); ?></th>
                                                                    <th><?php echo app('translator')->get('Full name'); ?></th>
                                                                    <th><?php echo app('translator')->get('Tên thường gọi'); ?></th>
                                                                    <th><?php echo app('translator')->get('Gender'); ?></th>
                                                                    <th><?php echo app('translator')->get('Area'); ?></th>
                                                                    <th><?php echo app('translator')->get('Lớp đang học'); ?></th>
                                                                    <th><?php echo app('translator')->get('Ngày nhập học chính thức'); ?></th>
                                                                    <th><?php echo app('translator')->get('Quan hệ'); ?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $__currentLoopData = $detail->parentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php $student = $relation->student; ?>
                                                                    <?php if($student): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <a target="_blank" href="<?php echo e($student->avatar ?? url('themes/admin/img/no_image.jpg')); ?>">
                                                                                    <img src="<?php echo e($student->avatar ?? url('themes/admin/img/no_image.jpg')); ?>" alt="avatar" style="max-height: 60px;">
                                                                                </a>
                                                                            </td>
                                                                            <td><?php echo e($student->student_code ?? ''); ?></td>
                                                                            <td><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?> </td>
                                                                            <td><?php echo e($student->nickname ?? ''); ?></td>
                                                                            <td><?php echo e(__($student->sex)); ?></td>
                                                                            <td><?php echo e($student->area->name ?? ''); ?></td>
                                                                            <td><?php echo e($student->currentClass->name ?? ''); ?></td>
                                                                            <td>
                                                                                <?php echo e(isset($student->enrolled_at) &&  $student->enrolled_at !="" ?date("d-m-Y", strtotime($student->enrolled_at)): ''); ?>

                                                                            </td>
                                                                            <td><?php echo e($relation->relationship->title ?? ''); ?></td>
                                                                        </tr>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </tbody>
                                                        </table>
                                                    <?php else: ?>
                                                        <p class="text-muted"><?php echo app('translator')->get('Phụ huynh này chưa được liên kết với học sinh nào.'); ?></p>
                                                    <?php endif; ?>
                                                </div>
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/parents/show.blade.php ENDPATH**/ ?>