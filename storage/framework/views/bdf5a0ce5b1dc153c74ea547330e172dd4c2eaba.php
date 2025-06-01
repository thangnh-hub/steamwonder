

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .table-bordered>thead:first-child>tr:first-child>th {
            text-align: center;
            vertical-align: middle;
        }

        @media  print {

            #printButton,
            .hide-print {
                display: none;
                /* Ẩn nút khi in */
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        
        <div class="box box-default hide-print">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('report_order')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kỳ'); ?> </label>
                                <input type="month" class="form-control" name="period"
                                    value="<?php echo e(isset($params['period']) ? $params['period'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Cơ sở'); ?> </label>
                                <select name="area_id" class="area_id form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kho'); ?></label>
                                <select name="warehouse_id" class="warehouse_avaible form-control select2">
                                    <option value="">Chọn</option>
                                    <?php if(isset($params['warehouse_id']) && $params['warehouse_id'] != ''): ?>
                                        <?php $__currentLoopData = $list_warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($params['area_id']) && $params['area_id'] != ''): ?>
                                                <?php if($val->area_id == $params['area_id']): ?>
                                                    <option value="<?php echo e($val->id); ?>"
                                                        <?php echo e(isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : ''); ?>>
                                                        <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trạng thái phiếu'); ?></label>
                                <select name="status" class="form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val ?? ''); ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                <select multiple name="department_request[]" class="form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['department_request']) &&  in_array($val->id,$params['department_request']) ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val->name ?? ''); ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('report_order')); ?>">
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
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>
            </div>
            <div class="box-body">
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
                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered sticky">
                        <thead>
                            <tr>
                                <th style="width:50px" rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Sản phẩm'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Loại SP'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('ĐVT'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Đơn giá(Dự kiến)'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Đơn giá'); ?></th>
                                <th colspan="<?php echo e($list_dep->count() + 1 ?? 1); ?>"><?php echo app('translator')->get('Số lượng'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Tổng tiền'); ?></th>
                            <tr>
                                <?php $__currentLoopData = $list_dep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th style="width:70px"><?php echo e(__($dep->code)); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th style="width:70px">Tổng</th>
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($rows->count() > 0): ?>
                                <?php
                                    $total_money = 0;
                                ?>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>

                                        <td>
                                            <?php echo e($row->product->name ?? ''); ?>

                                        </td>

                                        <td>
                                            <?php echo e(__($row->product->warehouse_type ?? '')); ?>

                                        </td>
                                        <td>
                                            <?php echo e(__($row->product->unit ?? '')); ?>

                                        </td>
                                        <td>
                                            <?php echo e(isset($row->product->price) && is_numeric($row->product->price) ? number_format($row->product->price, 0, ',', '.') . ' đ' : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') . ' đ' : ''); ?>


                                        </td>
                                        <?php $__currentLoopData = $list_dep; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td>
                                                <?php $__currentLoopData = $row->list_departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($key == $dep->id): ?>
                                                        <?php echo e($val); ?>

                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td><?php echo e($row->total_quantity); ?></td>
                                        <td>
                                            <?php
                                                $total_money += $row->total_quantity*$row->price;
                                            ?>
                                            <?php echo e(number_format(($row->total_quantity*$row->price), 0, ',', '.') . ' đ'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <br>
                    <p class="pull-right"><strong>Tổng tiền : <?php echo e(number_format(($total_money), 0, ',', '.') . ' đ'); ?></strong></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $('.area_id').change(function() {
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
                        let _item = '<option value=""><?php echo app('translator')->get('Please select'); ?></option>';
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
                    _targetHTML.trigger('change');
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

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_order/report_order.blade.php ENDPATH**/ ?>