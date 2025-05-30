

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
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
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route('deliver_warehouse.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
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
            <form action="<?php echo e(route('deliver_warehouse')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Mã phiếu...'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kỳ'); ?></label>
                                <input type="month" class="form-control" name="period"
                                    value="<?php echo e($params['period'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kho'); ?></label>
                                <select name="warehouse_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_warehouse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['warehouse_id']) && $params['warehouse_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo app('translator')->get($val->name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phiếu đề xuất order'); ?></label>
                                <select name="order_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['order_id']) && $params['order_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->code . '-' . $val->name); ?>

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
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('deliver_warehouse')); ?>">
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
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách phiếu xuất kho'); ?></h3>
            </div>
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
                <?php if(count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                <th style="width:100px"><?php echo app('translator')->get('Mã phiếu'); ?></th>
                                <th><?php echo app('translator')->get('Tên phiếu'); ?></th>
                                <th style="width:100px"><?php echo app('translator')->get('Kỳ'); ?></th>
                                <th><?php echo app('translator')->get('Phiếu Order/phát sách'); ?></th>
                                <th style="width:100px"><?php echo app('translator')->get('Tổng sp'); ?></th>
                                <th style="width:100px"><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                <th><?php echo app('translator')->get('Người tạo'); ?></th>
                                <th style="width:100px"><?php echo app('translator')->get('Ngày tạo'); ?></th>
                                <th class="hide-print"><?php echo app('translator')->get('Chức năng'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($loop->index + 1); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->warehouse_deliver->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php if(isset($row->list_class)): ?>
                                            <ul>
                                                <?php $__currentLoopData = $row->list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($i->name); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            <?php echo e($row->name ?? ''); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($row->period ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php if($row->order_id != ''): ?>
                                            <a target="_blank"
                                                href="<?php echo e(route('warehouse_order_product.show', $row->order_id)); ?>">
                                                <?php echo e($row->order_warehouse->code . '-' . $row->order_warehouse->name ?? ''); ?>

                                                <i class="fa fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($row->total_product ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->admin_created->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->created_at->format('d/m/Y') ?? ''); ?>

                                    </td>

                                    <td class="hide-print">
                                        <a class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo app('translator')->get('Xem chi tiết'); ?>"
                                            data-original-title="<?php echo app('translator')->get('Xem chi tiết đơn'); ?>"
                                            href="<?php echo e(route('deliver_warehouse.show', $row->id)); ?>">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <div class="box-footer clearfix hide-print">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy <?php echo e($rows->total()); ?> kết quả
                    </div>
                    <div class="col-sm-7">
                        <?php echo e($rows->withQueryString()->links('admin.pagination.default')); ?>

                    </div>
                </div>
            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_deliver/index.blade.php ENDPATH**/ ?>