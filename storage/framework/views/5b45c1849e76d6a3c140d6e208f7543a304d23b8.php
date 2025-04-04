

<?php $__env->startSection('title'); ?>
    <?php echo e($module_name); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        b {
            margin-left: 10px;
        }

        li {
            list-style-type: circle;
            margin-left: 10px;
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
            <div class="box-body">
                <form action="<?php echo e(route('leave.balance.index')); ?>" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Keyword'); ?> </label>
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('keyword_note'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Filter'); ?></label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10"><?php echo app('translator')->get('Submit'); ?></button>
                                    <a class="btn btn-default btn-sm" href="<?php echo e(route('leave.balance.index')); ?>">
                                        <?php echo app('translator')->get('Reset'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
        

        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo app('translator')->get('List'); ?></h3>
                <div class="box_note">
                    <p><b><?php echo app('translator')->get('Ghi chú'); ?></b></p>
                    <ul>
                        <li> Tổng phép năm sẽ tự động cập nhật theo tháng</li>
                        <li> Phép chuyển giao năm cũ sẽ lấy số phép còn lại của năm trước (Hiện chưa có dữ liệu)</li>
                        <li> Phép đã dùng là tổng số ngày nghỉ của đơn xin nghỉ (Loại nghỉ phép) "Đã được duyệt"</li>
                        <li> Phép khả dụng là số ngày phép có thể sử dựng ( = Tổng phép năm + Phép chuyển giao còn thời hạn
                            sử dụng)</li>
                    </ul>
                </div>
            </div>
            <div class="box-body table-responsive box-alert">
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

                <?php if(!isset($rows) || count($rows) == 0): ?>
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo app('translator')->get('not_found'); ?>
                    </div>
                <?php else: ?>
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Năm'); ?></th>
                                <th><?php echo app('translator')->get('Nhân viên'); ?></th>
                                <th><?php echo app('translator')->get('Tổng phép năm'); ?></th>
                                <th><?php echo app('translator')->get('Phép chuyển giao năm cũ'); ?></th>
                                <th><?php echo app('translator')->get('Phép khả dụng'); ?></th>
                                <th><?php echo app('translator')->get('Đã dùng'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($row->year ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->user->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <div class="box_view view_total_leaves">
                                            <?php echo e($row->total_leaves ?? 0); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="total_leaves form-control" type="number" step="0.1"
                                                min="0" name="total_leaves" value="<?php echo e($row->total_leaves ?? 0); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view view_transfer_old">
                                            <?php echo e($row->transfer_old ?? 0); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="transfer_old form-control" type="number" step="0.5"
                                                min="0" name="transfer_old" value="<?php echo e($row->transfer_old ?? 0); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view view_available">
                                            <?php echo e($row->available ?? 0); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="available form-control" type="number" step="0.5"
                                                min="0" name="available" value="<?php echo e($row->available ?? 0); ?>">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="box_view view_used_leaves">
                                            <?php echo e($row->used_leaves); ?>

                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <input class="used_leaves form-control" type="number" step="0.5"
                                                min="0" name="used_leaves" value="<?php echo e($row->used_leaves ?? 0); ?>">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="box_view">
                                            <button class="btn btn-sm btn-warning btn_edit" data-toggle="tooltip"
                                                style="margin-right: 5px" title="<?php echo app('translator')->get('Edit'); ?>"
                                                data-original-title="<?php echo app('translator')->get('Edit'); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                        </div>
                                        <div class="box_edit" style="display: none">
                                            <div class="d-flex-wap">
                                                <button class="btn btn-sm btn-success btn_save mr-10" data-toggle="tooltip"
                                                    data-id="<?php echo e($row->id); ?>"
                                                    data-original-title="<?php echo app('translator')->get('Lưu'); ?>"><i class="fa fa-check"
                                                        aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-danger btn_exit" data-toggle="tooltip"
                                                    data-original-title="<?php echo app('translator')->get('Hủy'); ?>"><i class="fa fa-times"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </form>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <?php if(isset($rows) && count($rows) > 0): ?>
                <div class="box-footer clearfix">
                    <div class="row">
                        <div class="col-sm-5">
                            Tìm thấy <?php echo e($rows->total()); ?> kết quả
                        </div>
                        <div class="col-sm-7">
                            <?php echo e($rows->withQueryString()->links('admin.pagination.default')); ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.btn_edit').click(function() {
            var h = $(this).parents('tr').find('.box_view');
            var s = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        })
        $('.btn_exit').click(function() {
            var s = $(this).parents('tr').find('.box_view');
            var h = $(this).parents('tr').find('.box_edit');
            show_hide(s, h);
        });
        $('.btn_save').click(function() {
            if (confirm('Bạn chắc chắn muốn lưu thông tin !')) {
                var _id = $(this).data('id');
                var url = "<?php echo e(route('leave.balance.update', ':id')); ?>".replace(':id', _id);
                // Lấy dữ liệu truyền ajax
                var total_leaves = $(this).parents('tr').find('.total_leaves').val();
                var transfer_old = $(this).parents('tr').find('.transfer_old').val();
                var available = $(this).parents('tr').find('.available').val();
                var used_leaves = $(this).parents('tr').find('.used_leaves').val();
                // View đổi nội dung
                var view_total_leave = $(this).parents('tr').find('.view_total_leave');
                var view_transfer_old = $(this).parents('tr').find('.view_transfer_old');
                var view_available = $(this).parents('tr').find('.view_available');
                var view_used_leaves = $(this).parents('tr').find('.view_used_leaves');
                // ẩn hiện
                var btn_exit = $(this).parents('tr').find('.btn_exit');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "_token": "<?php echo e(csrf_token()); ?>",
                        total_leaves: total_leaves,
                        transfer_old: transfer_old,
                        available: available,
                        used_leaves: used_leaves,
                    },
                    success: function(response) {
                        if (response.data != null) {
                            if (response.data == 'warning') {
                                var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            ` + response.message + `
                                        </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert-warning").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);

                            } else {
                                var _html = `<div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    ` + response.message + `
                                </div>`;
                                $('.box-alert').prepend(_html);
                                $('html, body').animate({
                                    scrollTop: $(".alert").offset().top
                                }, 1000);
                                setTimeout(function() {
                                    $(".alert").fadeOut(3000, function() {});
                                }, 800);
                                // Cập nhật lại view
                                view_total_leave.html(response.data.total_leave);
                                view_transfer_old.html(response.data.transfer_old);
                                view_available.html(response.data.available);
                                view_used_leaves.html(response.data.used_leaves);
                                // view_quantity.html(response.data.quantity);
                            }

                        } else {
                            var _html = `<div class="alert alert-warning alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            Bạn không có quyền thao tác chức năng này!
                                        </div>`;
                            $('.box-alert').prepend(_html);
                            $('html, body').animate({
                                scrollTop: $(".alert-warning").offset().top
                            }, 1000);
                            setTimeout(function() {
                                $(".alert").fadeOut(3000, function() {});
                            }, 800);
                        }
                        btn_exit.click();
                    },
                    error: function(response) {
                        let errors = response.responseJSON.message;
                        alert(errors);
                    }
                });
            }

        })

        function show_hide(show, hide) {
            show.show();
            hide.hide();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/leaves/leave_balance_index.blade.php ENDPATH**/ ?>