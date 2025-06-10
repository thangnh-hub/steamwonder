

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

        .loading-notification {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 1.5rem;
            z-index: 9999;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
            <a class="btn btn-sm btn-warning pull-right" href="<?php echo e(route('entry_warehouse.create')); ?>"><i
                    class="fa fa-plus"></i> <?php echo app('translator')->get('Add'); ?></a>
        </h1>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div id="loading-notification" class="loading-notification">
            <p><?php echo app('translator')->get('Please wait'); ?>...</p>
        </div>
        
        <div class="box box-default hide-print">

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo app('translator')->get('Filter'); ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="<?php echo e(route('entry_warehouse')); ?>" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Tên phiếu, mã phiếu..'); ?>"
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
                                <label><?php echo app('translator')->get('Phiếu đề xuất mua sắm'); ?></label>
                                <select name="order_id" class=" form-control select2">
                                    <option value="">Chọn</option>
                                    <?php $__currentLoopData = $list_order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['order_id']) && $params['order_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->code . ' - ' . $val->name); ?>

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
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('entry_warehouse')); ?>">
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
                <h3 class="box-title"><?php echo app('translator')->get('Danh sách phiếu nhập kho'); ?></h3>
                
                <div class="pull-right" style="display: none; margin-left:15px ">
                    <input class="form-control" type="file" name="files" id="fileImport"
                        placeholder="<?php echo app('translator')->get('Select File'); ?>">
                    <button type="button" class="btn btn-sm btn-success"
                        onclick="importFile('fileImport','<?php echo e(route('warehouse_entry.import_entry')); ?>')">
                        <i class="fa fa-file-excel-o"></i>
                        <?php echo app('translator')->get('Import sản phẩm'); ?></button>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?php if(session('errorMessage')): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('errorMessage'); ?>

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
                                <th><?php echo app('translator')->get('Phiếu mua sắm'); ?></th>
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
                                        <?php echo e($row->warehouse->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->period ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php if($row->order_id != ''): ?>
                                            <a target="_blank"
                                                href="<?php echo e(route('warehouse_order_product_buy.show', $row->order_id)); ?>">
                                                <?php echo e($row->order_warehouse->code . '-' . $row->order_warehouse->name ?? ''); ?>

                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($row->total_product ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(isset($row->total_money) && is_numeric($row->total_money) ? number_format($row->total_money, 0, ',', '.') : ''); ?>

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
                                            href="<?php echo e(route('entry_warehouse.show', $row->id)); ?>">
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
    <script>
        function importFile(_file, _url) {
            var formData = new FormData();
            var file = $('#' + _file)[0].files[0];
            console.log(file);

            if (file == null) {
                alert('Cần chọn file để Import!');
                return;
            }
            formData.append('file', file);
            formData.append('_token', '<?php echo e(csrf_token()); ?>');
            $('#loading-notification').css('display', 'flex');
            $.ajax({
                url: _url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.data != null) {
                        location.reload();
                    } else {
                        var _html = `<div class="alert alert-warning alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            Bạn không có quyền thao tác chức năng này!
                            </div>`;
                        $('.table-responsive').prepend(_html);
                        $('html, body').animate({
                            scrollTop: $(".alert-warning").offset().top
                        }, 1000);
                        setTimeout(function() {
                            $('.alert-warning').remove();
                        }, 3000);
                    }
                    $('#loading-notification').css('display', 'none');
                },
                error: function(response) {
                    // Get errors
                    $('#loading-notification').css('display', 'none');
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/warehouse_entry/index.blade.php ENDPATH**/ ?>