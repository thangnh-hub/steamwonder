


<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .item_service {
            margin-bottom: 10px;
            align-items: center;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="box_alert">
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
        </div>
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên chu kỳ'); ?> <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="name" id="class_name"
                                    placeholder="<?php echo app('translator')->get('Tên chu kỳ'); ?>" value="<?php echo e($detail->name); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Số tháng'); ?> <small class="text-red">*</small></label>
                                <input type="number" class="form-control" name="months" placeholder="<?php echo app('translator')->get('Số tháng'); ?>"
                                    value="<?php echo e($detail->months ?? old('months')); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e($detail->area_id && $detail->area_id == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sw_featured"><?php echo app('translator')->get('Mặc định'); ?></label>
                                <div class="sw_featured d-flex-al-center">
                                    <label class="switch ">
                                        <input id="sw_featured" name="is_default" value="1" type="checkbox"
                                            <?php echo e($detail->is_default && $detail->is_default == '1' ? 'checked' : ''); ?>>
                                        <span class="slider round"></span>
                                    </label>

                                </div>
                            </div>
                        </div>



                        <hr>
                        <div class="col-md-12">
                            <h4 class="box-title">Danh sách dịch vụ</h4>
                            <ul class="mt-15">
                                <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="d-flex-wap item_service">
                                        <input class="item_check mr-10 checkService" type="checkbox"
                                            <?php echo e(isset($detail->json_params->services->{$item_service->id}) ? 'checked' : ''); ?>

                                            name="json_params[services][<?php echo e($item_service->id); ?>][service_id]"
                                            value="<?php echo e($item_service->id); ?>">
                                        <input placeholder="Nhập số tiền (hoặc %) giảm trừ"
                                            class="item_number_hssv form-control mr-10 check_disable "
                                            <?php echo e(isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled'); ?>

                                            style="width:250px;"
                                            name="json_params[services][<?php echo e($item_service->id); ?>][value]" type="number"
                                            value="<?php echo e($detail->json_params->services->{$item_service->id}->value ?? ''); ?>">
                                        <select name="json_params[services][<?php echo e($item_service->id); ?>][type]"
                                            class="form-control select2 mr-10 check_disable"
                                            <?php echo e(isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled'); ?>

                                            style="width: 250px">
                                            <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item); ?>"
                                                    <?php echo e(isset($detail->json_params->services->{$item_service->id}->type) && $detail->json_params->services->{$item_service->id}->type == $item ? 'selected' : ''); ?>>
                                                    <?php echo e(__($item)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <span class="fw-bold ml-10"
                                            style="min-width:200px;"><?php echo e($item_service->name ?? ''); ?>

                                        </span>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </ul>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                        <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                        <?php echo app('translator')->get('Save'); ?></button>
                </div>
            </div>
        </form>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $('.checkService').change(function() {
            var check = $(this).is(':checked');
            $(this).parents('.item_service').find('.check_disable').attr('disabled', !check)
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/payment_cycle/edit.blade.php ENDPATH**/ ?>