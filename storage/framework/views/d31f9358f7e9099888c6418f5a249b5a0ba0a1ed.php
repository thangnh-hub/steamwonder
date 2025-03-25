<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
    <style>

    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-dm btn-success pull-right" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
            </a>
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
                            <h3 class="box-title"><?php echo app('translator')->get('Tạo phiên thi'); ?></h3>
                            <button type="submit" class="btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Save'); ?>
                            </button>
                        </div>
                        <div class="box-body">
                            <div class="tab_offline">
                                <div class="tab-pane active">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Trình độ'); ?> <small class="text-red">*</small></label>
                                                <select required name="id_level" class="id_level form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val->id ?? ''); ?>">
                                                            <?php echo e($val->name ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Tổ chức'); ?></label>
                                                <select name="organization" class=" form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $organization; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key ?? ''); ?>">
                                                            <?php echo e(__($val) ?? ''); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Phần thi'); ?> <small class="text-red">*</small></label>
                                                <select required name="is_type" class="form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>">
                                                            <?php echo e($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Chọn hình thức'); ?> <small class="text-red">*</small></label>
                                                <select required name="skill_test" class="form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $skill; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>">
                                                            <?php echo app('translator')->get($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Kiểu câu hỏi'); ?> <small class="text-red">*</small></label>
                                                <select required name="type_question" class="form-control select2">
                                                    <option value=""><?php echo app('translator')->get('Please choose'); ?></option>
                                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($val); ?>">
                                                            <?php echo app('translator')->get($val); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('File Audio nếu có'); ?></label>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a data-input="files_audio" class="btn btn-primary file">
                                                                <i class="fa fa-picture-o"></i> <?php echo app('translator')->get('Select'); ?>
                                                            </a>
                                                        </span>
                                                        <input id="files_audio" class="form-control" type="text"
                                                            name="audio"
                                                            placeholder="<?php echo app('translator')->get('Files Audio'); ?>" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label><?php echo app('translator')->get('Content'); ?></label>
                                                    <textarea name="content" class="form-control" id="content_vi"><?php echo e(old('content')); ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                            </a>
                            <button type="submit" class="btn btn-info pull-right">
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
        CKEDITOR.replace('content_vi', ck_options);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\dwn\resources\views/admin/pages/hv_exam_topic/create.blade.php ENDPATH**/ ?>