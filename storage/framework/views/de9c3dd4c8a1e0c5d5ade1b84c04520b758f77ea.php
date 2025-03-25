

<?php $__env->startSection('title'); ?>
    <?php echo e($module_name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>
        ul li {
            list-style: none;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo e($module_name); ?>

        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('gift_distribute')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Khóa học'); ?></label>
                                <select class="form-control select2" name="course_id" id="">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>" <?php echo e(isset($params['course_id']) && $params['course_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name??""); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('gift_distribute')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        

        <div class="box">
            <div class="box-body table-responsive">
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
                <form action="<?php echo e(route('store_history')); ?>" method="POST">
                    <input type="text" name="course_id" value="<?php echo e($params['course_id'] ?? ''); ?>" hidden>
                    <?php echo csrf_field(); ?>
                    <div  class="box-header with-border">
                        <h3 class="box-title"><?php echo app('translator')->get('Danh sách học viên'); ?></h3>
                        <?php if($students->count() > 0): ?>
                            <button type="submit" class="btn btn-success pull-right">
                            <i class="fa fa-save"></i> Lưu cấp phát quà
                            </button>
                        <?php endif; ?>
                    </div>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Mã HV'); ?></th>
                                <th><?php echo app('translator')->get('Họ tên'); ?></th>
                                <th><?php echo app('translator')->get('Khóa học'); ?></th>
                                <th style="width:40%"><?php echo app('translator')->get('DS Quà tặng'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->index + 1); ?></td>
                                    <td><?php echo e($val->admin_code ?? ""); ?></td>
                                    <td><?php echo e($val->name ?? ''); ?></td>
                                    <td class="course_name"><?php echo e($val->course->name ?? ''); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 10px;">
                                            <ul>
                                                <?php $__currentLoopData = $gifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        

                                                        <?php if(!in_array($gift->id, $val->issued_gifts)): ?>
                                                            <input 
                                                                id="check_<?php echo e($gift->id); ?>_<?php echo e($val->id); ?>" 
                                                                type="checkbox" 
                                                                name="gifts[<?php echo e($val->id); ?>][]" 
                                                                value="<?php echo e($gift->id); ?>">
                                                            <label for="check_<?php echo e($gift->id); ?>_<?php echo e($val->id); ?>"><?php echo e($gift->name); ?></label>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <?php if($students->count() > 0): ?>
                    <button type="submit" class="btn btn-success pull-right">
                       <i class="fa fa-save"></i> Lưu cấp phát quà
                    </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dwn\resources\views/admin/pages/gift_distribution/index.blade.php ENDPATH**/ ?>