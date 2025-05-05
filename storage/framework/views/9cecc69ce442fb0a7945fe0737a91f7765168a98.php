

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
                                <label><?php echo app('translator')->get('Kỳ'); ?></label>
                                <input type="month" class="form-control" name="period"
                                    value="<?php echo e($params['period'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                                <label><?php echo app('translator')->get('Phòng'); ?></label>
                                <select name="department_request" class=" form-control select2">
                                    <option value=""><?php echo app('translator')->get('Chọn'); ?></option>
                                    <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['department_request']) && $params['department_request'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->name ?? ''); ?></option>
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
            <div class="box-body box_alert table-responsive">
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
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                <th><?php echo app('translator')->get('Mã phiếu'); ?></th>
                                <th><?php echo app('translator')->get('Tên phiếu đề xuất'); ?></th>
                                <th><?php echo app('translator')->get('Kỳ'); ?></th>
                                <th><?php echo app('translator')->get('Tổng sản phẩm'); ?></th>
                                <th><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                <th><?php echo app('translator')->get('Phòng ban'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Người đề xuất'); ?></th>
                                <th><?php echo app('translator')->get('Tình trạng'); ?></th>
                                <th><?php echo app('translator')->get('Ngày đề xuất'); ?></th>
                                <th><?php echo app('translator')->get('Người tạo'); ?></th>
                                <th class="hide-print"><?php echo app('translator')->get('Chức năng'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tongtien = 0;
                            ?>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $tongtien += $row->total_money ?? 0;
                                ?>
                                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->index + 1); ?>

                                        </td>

                                        <td>
                                            <?php echo e($row->warehouse->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <a target="_blank" data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết đề xuất'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>"><?php echo e($row->code ?? ''); ?>

                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td>
                                            <a target="_blank" data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết đề xuất'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>"><?php echo e($row->name ?? ''); ?>

                                                <i class="fa fa-eye"></i></a>
                                        </td>
                                        <td><?php echo e($row->period ?? ''); ?></td>
                                        <td><?php echo e($row->total_product ?? ''); ?></td>
                                        <td><?php echo e(isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') . ' đ' : ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e($row->department->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php echo e(__($row->status)); ?>

                                            <?php if($row->status == 'not approved'): ?>
                                                <button data-id="<?php echo e($row->id); ?>" type="button"
                                                    class="btn btn-sm btn-success approve_order"><?php echo app('translator')->get('Duyệt'); ?></button>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($row->staff->name ?? ''); ?>

                                        </td>
                                        <td>
                                            <?php if($row->confirmed == 'da_nhan'): ?>
                                                <?php echo e($row->confirmed == 'da_nhan' ? 'Đã nhận' : ''); ?>

                                            <?php elseif($row->status == 'out warehouse' && ($admin_auth->id == $row->staff->id || $admin_auth->id == 1)): ?>
                                                <button class="btn btn-sm btn-success btn_confirm" type="button"
                                                    data-id="<?php echo e($row->id); ?>">Nhận đơn</button>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($row->day_create != '' ? date('d-m-Y', strtotime($row->day_create)) : 'Chưa cập nhật'); ?>

                                        </td>
                                        <td><?php echo e($row->admin_created->name ?? ''); ?></td>
                                        <td class="hide-print">
                                            <?php if($row->status !== 'out warehouse' && $row->status !== 'not approved'): ?>
                                                <a href="<?php echo e(route('deliver_warehouse.create', ['order_id' => $row->id])); ?>"
                                                    target="_blank" rel="noopener noreferrer"
                                                    class="btn btn-sm btn-primary">
                                                    Xuất kho
                                                    <i class="fa fa-sign-in"></i>
                                                </a>
                                            <?php elseif($row->status == 'not approved'): ?>
                                                <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Chỉnh sửa'); ?>" data-original-title="<?php echo app('translator')->get('Update'); ?>"
                                                    href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-danger" type="submit"
                                                    data-toggle="tooltip" title="<?php echo app('translator')->get('Delete'); ?>"
                                                    data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            <?php elseif($row->status == 'out warehouse' && isset($row->entry)): ?>
                                                <a href="<?php echo e(route('deliver_warehouse.show', $row->entry->id)); ?>"
                                                    target="_blank" rel="noopener noreferrer"
                                                    class="btn btn-sm btn-info">
                                                    Xem phiếu xuất
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td colspan="13"><strong>Tổng tiền:
                                        <?php echo e(isset($tongtien) && is_numeric($tongtien) ? number_format($tongtien, 0, ',', '.') . ' đ' : ''); ?></strong>
                                </td>
                            </tr>
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
        $('.approve_order').click(function(e) {
            if (confirm('Bạn có chắc chắn muốn duyệt đề xuất này ?')) {
                let _id = $(this).attr('data-id');
                let url = "<?php echo e(route('warehouse_order.approve')); ?>/";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        id: _id,
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        });
        $('.btn_confirm').on('click', function() {
            if (confirm('Bạn chắc chắn xác nhận đã nhận đơn!')) {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?php echo e(route('warehouse_order_product.confirm')); ?>',
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        id: id,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            location.reload();
                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                            $('.box_alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $('.alert').remove();
                            }, 3000);
                        }
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_order/index.blade.php ENDPATH**/ ?>