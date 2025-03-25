

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
            <form action="<?php echo e(route('gift_distribute_entry')); ?>" method="GET">
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
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('gift_distribute_entry')); ?>">
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
                <form action="<?php echo e(route('store_entry')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div  class="box-header with-border">
                        <h3 class="box-title"><?php echo app('translator')->get('Xuất kho quà tặng học viên'); ?></h3>
                    </div>
                    <div class="row" style="margin:0;padding-top:10px">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Cơ sở'); ?><small class="text-red">*</small></label>
                                <select required class="area_id form-control" name="area_id" autocomplete="off">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e($area_selected > 0 && $area_selected == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kho xuất'); ?><small class="text-red">*</small></label>
                                <select required name="warehouse_id_deliver" class="warehouse_avaible form-control"
                                    autocomplete="off">
                                    <?php $__currentLoopData = $list_warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($order_selected) && $order_selected->warehouse_id == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Tên phiếu xuất kho'); ?> <small class="text-red">*</small></label>
                                <input type="text" class="form-control"
                                    name="name" placeholder="<?php echo app('translator')->get('Tên phiếu xuất kho'); ?>" value="Xuất kho quà tặng học viên"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày xuất'); ?> <small class="text-red">*</small></label>
                                <input required type="date" class="form-control" name="day_deliver"
                                    value="<?php echo e(old('day_deliver') ?? date('Y-m-d', time())); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Người tạo phiếu'); ?></label>
                                <input type="text" class="form-control" 
                                    value="<?php echo e($admin_auth->name . ' (' . $admin_auth->admin_code . ')'); ?>" disabled>
                            </div>
                        </div>
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
                                                <?php $__currentLoopData = $val->issued_gifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gift): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <input 
                                                            id="check_<?php echo e($gift->product_id); ?>_<?php echo e($val->id); ?>" 
                                                            type="checkbox" 
                                                            name="gifts[<?php echo e($val->id); ?>][]" 
                                                            value="<?php echo e($gift->id); ?>"
                                                            checked>
                                                        <label for="check_<?php echo e($gift->product_id); ?>_<?php echo e($val->id); ?>">
                                                            <?php echo e($gift->product->name ?? 'Không xác định'); ?>

                                                        </label>
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
                            <i class="fa fa-plus"></i>  Tạo phiếu xuất kho 
                        </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.area_id').change(function() {
            $('#post_related').html('');
            $('.tbody-order-asset').html('');
            var _id = $(this).val();
            let url = "<?php echo e(route('warehouse_by_area')); ?>";
            let _targetHTML = $('.warehouse_avaible');

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    id: _id,
                },
                success: function(response) {
                    if (response.message == 'success') {
                        let list = response.data;
                        let _item = '';
                        if (list.length > 0) {
                            list.forEach(item => {
                                _item += '<option value="' + item.id + '">' + item
                                    .name + '</option>';
                            });
                            _targetHTML.html(_item);
                        }
                    } else {
                        _targetHTML.html('<option value=""><?php echo app('translator')->get('Please select'); ?></option>');
                    }
                },
                error: function(response) {
                    // Get errors
                    // let errors = response.responseJSON.message;
                    // _targetHTML.html('<tr><td colspan="5">' + errors + '</td></tr>');
                }
            });
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\dwn\resources\views/admin/pages/gift_distribution/index_entry.blade.php ENDPATH**/ ?>