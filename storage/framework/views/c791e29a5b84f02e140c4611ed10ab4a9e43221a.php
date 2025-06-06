<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .item_service {
            margin-bottom: 10px;
            align-items: center;
        }

        .d-flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between
        }

        ul {
            padding-inline-start: 5px;
        }

        .box_cycle {
            overflow-x: auto
        }

        .item_cycle {
            margin-right: 5px;
            padding: 5px;
            border: 1px solid #a9a9a9
        }

        .d-none {
            display: none;
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
        <form role="form" action="<?php echo e(route(Request::segment(2) . '.update', $detail->id)); ?>" method="POST"
            id="form_promotion">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Mã CT Kh.Mãi'); ?> <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_code"
                                    placeholder="<?php echo app('translator')->get('Mã CT Kh.Mãi'); ?>"
                                    value="<?php echo e($detail->promotion_code ?? old('promotion_code')); ?>" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên CT Kh.Mãi'); ?> <small class="text-red">*</small></label>
                                <input type="text" class="form-control" name="promotion_name"
                                    placeholder="<?php echo app('translator')->get('Tên CT Kh.Mãi'); ?>"
                                    value="<?php echo e($detail->promotion_name ?? old('promotion_name')); ?>" required>
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
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Type'); ?> <small class="text-red">*</small></label>
                                <select required name="promotion_type" class="form-control select2 select_type">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e($detail->promotion_type && $detail->promotion_type == $val ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Thời gian bắt đầu'); ?> <small class="text-red">*</small></label>
                                <input required type="date" name="time_start" class="form-control"
                                    value="<?php echo e(\Carbon\Carbon::parse($detail->time_start)->format('Y-m-d') ?? old('time_start')); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Thời gian kết thúc'); ?> <small class="text-red">*</small></label>
                                <input required type="date" name="time_end" class="form-control"
                                    value="<?php echo e(\Carbon\Carbon::parse($detail->time_end)->format('Y-m-d') ?? old('time_end')); ?>">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status'); ?> </label>
                                <select required name="status" class="form-control select2">
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e($detail->status && $detail->status == $val ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Mô tả'); ?> </label>
                                <textarea class="form-control" name="description" rows="5"><?php echo e($detail->description ?? ''); ?></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12">
                            <h4 class="box-title sw_featured">Danh sách dịch vụ (
                                <span for="sw_featured"><?php echo app('translator')->get('Theo chu kỳ thanh toán'); ?></span>
                                <span class="d-flex-al-center">
                                    <label class="switch">
                                        <input id="sw_featured" name="json_params[is_payment_cycle]" value="1"
                                            type="checkbox"
                                            <?php echo e(isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1 ? 'checked' : ''); ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </span>
                                )
                            </h4>
                            <div
                                class="d-flex box_cycle <?php echo e(isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1 ? 'd-flex' : 'd-none'); ?>">
                                <?php $__currentLoopData = $payment_cycle; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_cycle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="item_cycle">
                                        <h4 class="box-title"><?php echo e($item_cycle->name); ?></h4>
                                        <ul class="mt-15">
                                            <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="d-flex item_service">
                                                    <input class="item_check mr-10 checkService" type="checkbox"
                                                        <?php echo e(isset($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}) ? 'checked' : ''); ?>

                                                        name="json_params[payment_cycle][<?php echo e($item_cycle->id); ?>][services][<?php echo e($item_service->id); ?>][service_id]"
                                                        value="<?php echo e($item_service->id); ?>">
                                                    <input placeholder=""
                                                        class="item_value form-control mr-10 check_disable "
                                                        <?php echo e(isset($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}) ? '' : 'disabled'); ?>

                                                        style="width:150px;"
                                                        name="json_params[payment_cycle][<?php echo e($item_cycle->id); ?>][services][<?php echo e($item_service->id); ?>][value]"
                                                        type="number"
                                                        value="<?php echo e($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}->value ?? ''); ?>">
                                                    <input placeholder="Số lần áp dụng theo dịch vụ"
                                                        class="item_apply form-control mr-10 check_disable "
                                                        <?php echo e(isset($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}) ? '' : 'disabled'); ?>

                                                        style="width:150px;"
                                                        name="json_params[payment_cycle][<?php echo e($item_cycle->id); ?>][services][<?php echo e($item_service->id); ?>][apply_count]"
                                                        type="number"
                                                        value="<?php echo e($detail->json_params->payment_cycle->{$item_cycle->id}->services->{$item_service->id}->apply_count ?? ''); ?>">
                                                    <span class="fw-bold ml-10"
                                                        style="min-width:200px;"><?php echo e($item_service->name ?? ''); ?>

                                                    </span>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div
                                class="box_default <?php echo e(isset($detail->json_params->is_payment_cycle) && $detail->json_params->is_payment_cycle == 1 ? 'd-none' : 'd-flex'); ?>">
                                <ul class="mt-15">
                                    <?php $__currentLoopData = $service; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="d-flex item_service">
                                            <input class="item_check mr-10 checkService" type="checkbox"
                                                <?php echo e(isset($detail->json_params->services->{$item_service->id}) ? 'checked' : ''); ?>

                                                name="json_params[services][<?php echo e($item_service->id); ?>][service_id]"
                                                value="<?php echo e($item_service->id); ?>">
                                            <input placeholder="" class="item_value form-control mr-10 check_disable "
                                                <?php echo e(isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled'); ?>

                                                style="width:250px;"
                                                name="json_params[services][<?php echo e($item_service->id); ?>][value]"
                                                type="number"
                                                value="<?php echo e($detail->json_params->services->{$item_service->id}->value ?? ''); ?>">
                                            <input placeholder="Số lần áp dụng theo dịch vụ"
                                                class="item_apply form-control mr-10 check_disable "
                                                <?php echo e(isset($detail->json_params->services->{$item_service->id}) ? '' : 'disabled'); ?>

                                                style="width:250px;"
                                                name="json_params[services][<?php echo e($item_service->id); ?>][apply_count]"
                                                type="number"
                                                value="<?php echo e($detail->json_params->services->{$item_service->id}->apply_count ?? ''); ?>">
                                            <span class="fw-bold ml-10"
                                                style="min-width:200px;"><?php echo e($item_service->name ?? ''); ?>

                                            </span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </ul>
                            </div>
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
            $('.select_type').trigger('change');
            $('.checkService').change(function() {
                var check = $(this).is(':checked');
                $(this).parents('.item_service').find('.check_disable').attr('disabled', !check)
            })
            $('#form_promotion').on('submit', function(e) {
                $(this).find(':input').filter(function() {
                    return $(this).closest('.d-none').length > 0;
                }).prop('disabled', true);

                // Kiểm tra xem có ít nhất một checkbox được chọn hay không
                if ($('.checkService:checked').length === 0) {
                    e.preventDefault(); // Ngăn form submit
                    alert('Vui lòng chọn ít nhất một dịch vụ!');
                }
            });
            $('#sw_featured').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.box_cycle').addClass('d-flex').removeClass('d-none');
                    $('.box_default').addClass('d-none').removeClass('d-flex');
                } else {
                    $('.box_cycle').addClass('d-none').removeClass('d-flex');
                    $('.box_default').addClass('d-flex').removeClass('d-none');
                }
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/promotion/edit.blade.php ENDPATH**/ ?>