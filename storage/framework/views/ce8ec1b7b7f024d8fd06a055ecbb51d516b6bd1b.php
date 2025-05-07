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
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.store')); ?>" method="POST" id="form_promotion">
            <?php echo csrf_field(); ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Mã CT Kh.Mãi'); ?> <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_code"
                                    placeholder="<?php echo app('translator')->get('Mã CT Kh.Mãi'); ?>" value="<?php echo e(old('promotion_code')); ?>" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên CT Kh.Mãi'); ?> <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_name"
                                    placeholder="<?php echo app('translator')->get('Tên CT Kh.Mãi'); ?>" value="<?php echo e(old('promotion_name')); ?>" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(old('area_id') && old('area_id') == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Type'); ?> <small class="text-red">*</small></label>
                                <select required name="promotion_type" class="form-control select2 select_type">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(old('promotion_type') && old('promotion_type') == $val ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Thời gian bắt đầu'); ?> <small class="text-red">*</small></label>
                                <input required type="date" name="time_start" class="form-control"
                                    value="<?php echo e(old('time_start')); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Thời gian kết thúc'); ?> <small class="text-red">*</small></label>
                                <input required type="date" name="time_end" class="form-control"
                                    value="<?php echo e(old('time_end')); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status'); ?> </label>
                                <select required name="status" class="form-control select2">
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(old('status') && old('status') == $val ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Mô tả'); ?> </label>
                                <textarea class="form-control" name="description" rows="5"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <h4 class="box-title">Danh sách dịch vụ</h4>
                            <ul class="mt-15">
                                <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="d-flex-wap item_service">
                                        <input class="item_check mr-10 checkService" type="checkbox"
                                            name="json_params[services][<?php echo e($item_service->id); ?>][service_id]"
                                            value="<?php echo e($item_service->id); ?>">
                                        <input placeholder="" class="item_value form-control mr-10 check_disable " disabled
                                            style="width:250px;"
                                            name="json_params[services][<?php echo e($item_service->id); ?>][value]" type="number"
                                            value="">
                                        <input placeholder="Số lần áp dụng theo dịch vụ"
                                            class="item_apply form-control mr-10 check_disable " disabled
                                            style="width:250px;"
                                            name="json_params[services][<?php echo e($item_service->id); ?>][apply_count]"
                                            type="number" value="">
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
        $(document).ready(function() {
            $('#form_promotion').on('submit', function(e) {
                // Kiểm tra xem có ít nhất một checkbox được chọn hay không
                if ($('.checkService:checked').length === 0) {
                    e.preventDefault(); // Ngăn form submit
                    alert('Vui lòng chọn ít nhất một dịch vụ!');
                }
            });
            $('.select_type').on('change', function() {
                var _type = $(this).val();
                switch (_type) {
                    case 'percent':
                        $('.item_value').attr('placeholder', 'Nhập % khuyến mãi');
                        $('.item_apply').attr('readonly', false).val('');
                        break;
                    case 'fixed_amout':
                        $('.item_value').attr('placeholder', 'Nhập số tiền khuyến mãi')
                        $('.item_apply').attr('readonly', true).val(1);
                        break;
                    case 'add_month':
                        $('.item_value').attr('placeholder', 'Nhập số tháng khuyến mãi')
                        $('.item_apply').attr('readonly', true).val(1);
                        break;

                    default:
                        break;
                }
            })
            $('.checkService').change(function() {
                var check = $(this).is(':checked');
                $(this).parents('.item_service').find('.check_disable').attr('disabled', !check)
            })

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/promotion/create.blade.php ENDPATH**/ ?>