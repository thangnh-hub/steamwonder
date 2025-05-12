

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .modal-dialog.modal-custom {
            max-width: 80%;
            width: auto;
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

    <!-- Main content -->
    <section class="content">
        
        <div class="box box-default">

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
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Mã hoặc tên TBP'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Học sinh'); ?></label>
                                <select name="student_id" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['student_id']) && $params['student_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->student_code ?? ''); ?> - <?php echo e($item->first_name ?? ''); ?>

                                            <?php echo e($item->last_name ?? ''); ?>

                                            (<?php echo e($item->nickname ?? ''); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Area'); ?></label>
                                <select name="area_id" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $item->id ? 'selected' : ''); ?>>
                                            <?php echo e($item->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Status'); ?></label>
                                <select name="status" class="form-control select2 w-100">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                            <?php echo e(__($val)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Ngày tạo'); ?></label>
                                <input type="date" name="created_at" class="form-control"
                                    value="<?php echo e($params['created_at'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
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
            <div class="box-body box_alert">
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('Mã TBP'); ?></th>
                                <th><?php echo app('translator')->get('Tên TBP'); ?></th>
                                <th><?php echo app('translator')->get('Học sinh'); ?></th>
                                <th><?php echo app('translator')->get('Khu vực'); ?></th>
                                
                                <th><?php echo app('translator')->get('Thành tiền'); ?></th>
                                <th><?php echo app('translator')->get('Tổng giảm trừ'); ?></th>
                                <th><?php echo app('translator')->get('Số dư kỳ trước'); ?></th>
                                <th><?php echo app('translator')->get('Tổng tiền thực tế'); ?></th>
                                <th><?php echo app('translator')->get('Đã thu'); ?></th>
                                <th><?php echo app('translator')->get('Số tiền còn phải thu (+) hoặc thừa (-)'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                <th><?php echo app('translator')->get('Người tạo'); ?></th>
                                <th><?php echo app('translator')->get('Ngày tạo'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <strong style="font-size: 14px"><?php echo e($row->receipt_code ?? ''); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo e($row->receipt_name); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->student->student_code ?? ''); ?> - <?php echo e($row->student->first_name ?? ''); ?>

                                        <?php echo e($row->student->last_name ?? ''); ?>(<?php echo e($row->student->nickname ?? ''); ?>)
                                    </td>
                                    <td>
                                        <?php echo e($row->area->name ?? ''); ?>

                                    </td>
                                    
                                    <td>
                                        <?php echo e(number_format($row->total_amount, 0, ',', '.') ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($row->total_discount, 0, ',', '.') ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($row->prev_balance, 0, ',', '.') ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($row->total_final, 0, ',', '.') ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($row->total_paid, 0, ',', '.') ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(number_format($row->total_due, 0, ',', '.') ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(__($row->status ?? '')); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->note ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->adminCreated->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e(\Carbon\Carbon::parse($row->created_at)->format('d/m/Y') ?? ''); ?>

                                    </td>
                                    <td class="">

                                        <button class="btn btn-sm btn-success btn_show_detail" data-toggle="tooltip"
                                            data-id="<?php echo e($row->id); ?>"
                                            data-url="<?php echo e(route(Request::segment(2) . '.view', $row->id)); ?>"
                                            title="<?php echo app('translator')->get('Xem nhanh'); ?>" data-original-title="<?php echo app('translator')->get('Xem nhanh'); ?>">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?php echo app('translator')->get('Chỉnh sửa'); ?>"
                                            data-original-title="<?php echo app('translator')->get('Chỉnh sửa'); ?>" style="min-width: 34px"
                                            href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

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

        </div>
    </section>
    <div class="modal fade" id="modal_show_deduction" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-custom" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h3 class="modal-title text-center col-md-12"><?php echo app('translator')->get('Thông tin TBP'); ?></h3>
                    </h3>
                </div>
                <div class="modal-body show_detail_deduction">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-remove"></i> <?php echo app('translator')->get('Close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.btn_show_detail').click(function() {
            var url = $(this).data('url');
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response) {
                    if (response) {
                        $('.show_detail_deduction').html(response.data.view);
                        $('#modal_show_deduction').modal('show');
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
                    var errors = response.responseJSON.message;
                    console.log(errors);
                }
            });
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/receipt/index.blade.php ENDPATH**/ ?>