

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .table-bordered>thead>tr>th {
            vertical-align: middle;
            text-align: center;
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
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route(Request::segment(2) . '.create')); ?>"><i
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
            <form action="<?php echo e(route(Request::segment(2) . '.index')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Tên phiếu đề xuất, mã phiếu..'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kho giao'); ?></label>
                                <select name="warehouse_id_deliver" class=" form-control select2">
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
                                <label><?php echo app('translator')->get('Kho nhận'); ?></label>
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
                                <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e(__($value)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Kỳ'); ?></label>
                                <input type="month" class="form-control" name="period"
                                    value="<?php echo e($params['period'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
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
                                <th rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Mã phiếu'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Tên phiếu đề xuất'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Kỳ'); ?></th>
                                <th colspan="6"><?php echo app('translator')->get('Bên giao'); ?></th>
                                <th colspan="5"><?php echo app('translator')->get('Bên nhận'); ?></th>
                                <th rowspan="2"><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th rowspan="2" class="hide-print"><?php echo app('translator')->get('Chức năng'); ?></th>
                            </tr>
                            <tr>
                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                <th><?php echo app('translator')->get('Tổng sản phẩm'); ?></th>
                                <th><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                <th><?php echo app('translator')->get('Người giao'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                <th><?php echo app('translator')->get('Chức năng'); ?></th>
                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                <th><?php echo app('translator')->get('Tổng sản phẩm'); ?></th>
                                <th><?php echo app('translator')->get('Người nhận'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                <th><?php echo app('translator')->get('Chức năng'); ?></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>
                                        <td>
                                            <a data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết đề xuất'); ?>" target="_blank"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>"><?php echo e($row->code ?? ''); ?>

                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td>
                                            <a data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết đề xuất'); ?>" target="_blank"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>"><?php echo e($row->name ?? ''); ?>

                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td><?php echo e($row->period ?? ''); ?></td>

                                        <td>
                                            <?php echo e($row->warehouse_deliver->name ?? ''); ?>

                                        </td>
                                        <td class="text-center"><?php echo e($row->total_product ?? ''); ?></td>
                                        <td class="text-center">
                                            <?php echo e(isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->nguoi_giao->name ?? ''); ?>

                                        </td>
                                        <td><?php echo e($row->json_params->note_deliver ?? ''); ?></td>
                                        <td>
                                            <?php if($row->status == 'new' && $admin_auth->id == $row->staff_deliver): ?>
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Cập nhật'); ?>" data-original-title="<?php echo app('translator')->get('Cập nhật'); ?>"
                                                    href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                    <i class="fa fa-pencil-square-o"></i> <?php echo app('translator')->get('Cập nhật'); ?>
                                                </a>
                                            <?php endif; ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->warehouse->name ?? ''); ?>

                                        </td>
                                        <td class="text-center"><?php echo e($row->total_product_entry ?? ''); ?></td>
                                        <td>
                                            <?php echo e($row->nguoi_nhan->name ?? ''); ?>

                                        </td>
                                        <td><?php echo e($row->json_params->note ?? ''); ?></td>
                                        <td>
                                            <?php if($row->status == 'new' && $admin_auth->id == $row->staff_entry): ?>
                                                <a class="btn btn-sm btn-success" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Nhận đơn'); ?>" data-original-title="<?php echo app('translator')->get('Nhận đơn'); ?>"
                                                    href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                    <i class="fa fa-pencil-square-o"></i> <?php echo app('translator')->get('Nhận đơn'); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="width: 150px">
                                            <?php echo e(__($row->status)); ?> <?php echo e($row->status == 'approved' ? '- Đã nhận' : ''); ?>

                                        </td>
                                        <td class="hide-print">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </td>
                                    </tr>
                                </form>
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
    <script>
        // $('.approve_order').click(function(e) {
        //     if (confirm('Bạn có chắc chắn muốn duyệt đơn điều chuyển này này ?')) {
        //         let _id = $(this).attr('data-id');
        //         let url = "<?php echo e(route('warehouse_order.approve')); ?>/";
        //         $.ajax({
        //             type: "GET",
        //             url: url,
        //             data: {
        //                 id: _id,
        //             },
        //             success: function(response) {
        //                 location.reload();
        //             },
        //             error: function(response) {
        //                 let errors = response.responseJSON.message;
        //                 alert(errors);
        //             }
        //         });
        //     }
        // });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_transfer/index.blade.php ENDPATH**/ ?>