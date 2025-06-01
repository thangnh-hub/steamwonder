

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
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
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('keyword_note'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Trạng thái'); ?></label>
                                <select name="status" class="form-control select2"
                                    style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                        <?php echo e(isset($params['status']) && $params['status'] == $key ? 'selected' : ''); ?>>
                                        <?php echo e(__($item)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                <select name="dep_id" class="form-control select2"
                                    style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->id); ?>"
                                        <?php echo e(isset($params['dep_id']) && $params['dep_id'] == $item->id ? 'selected' : ''); ?>>
                                        <?php echo e(__($item->name)); ?></option>
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('STT'); ?></th>
                                <th style="width:20%"><?php echo app('translator')->get('Title'); ?></th>
                                <th><?php echo app('translator')->get('Bộ phận'); ?></th>
                                <th><?php echo app('translator')->get('Khoản thanh toán'); ?></th>
                                <th><?php echo app('translator')->get('Tổng tiền (VNĐ)'); ?></th>
                                <th><?php echo app('translator')->get('Tổng tiền (EURO)'); ?></th>
                                <th><?php echo app('translator')->get('Số tài khoản'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Người tạo'); ?></th>
                                <th><?php echo app('translator')->get('Ngày đề xuất'); ?></th>
                                <th><?php echo app('translator')->get('Người duyệt'); ?></th>
                                <th style="width:200px"><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>" method="POST"
                                    onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                    <tr class="valign-middle">
                                        <td>
                                            <?php echo e($loop->iteration); ?>

                                        </td>
                                        <td>
                                            <a href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>"><strong><?php echo e($row->content ?? ""); ?> <i class="fa fa-eye"></i> </strong></a>
                                        </td>
                                        
                                        <td>
                                            <?php echo e($row->department->name ?? ""); ?>

                                        </td>
                                        <td>
                                            <?php if($row->is_entry == 0): ?>
                                                <?php echo e($row->paymentDetails()->count()); ?>

                                            <?php else: ?>
                                                <a href="<?php echo e(route('entry_warehouse.show', $row->entry_id)); ?>"><strong><?php echo e($row->entry->name ?? ""); ?> <i class="fa fa-eye"></i> </strong></a> 
                                            <?php endif; ?>
                                        </td>
                                        

                                        <td>
                                            <?php if($row->is_entry == 0): ?>
                                                <?php echo e(isset($row->total_money_vnd_finally) && is_numeric($row->total_money_vnd_finally) ? number_format($row->total_money_vnd_finally, 0, ',', '.') : ''); ?> 
                                            <?php else: ?>
                                                <?php echo e(isset($row->json_params->total_money_vnd_without_vat) && is_numeric($row->json_params->total_money_vnd_without_vat) ? number_format($row->json_params->total_money_vnd_without_vat, 0, ',', '.') : ''); ?>

                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php echo e(isset($row->total_money_euro_finally) && is_numeric($row->total_money_euro_finally) ? number_format($row->total_money_euro_finally, 0, ',', '.') : ''); ?> 

                                        </td>
                                        <td>
                                            <?php echo e($row->qr_number ?? ""); ?>

                                        </td>
                                        
                                        <td >
                                            <p class="<?php echo e($row->status  == 'new' ?"text-danger":"text-success"); ?>">
                                                <?php echo e(__($row->status ?? "")); ?>

                                            </p>
                                            <?php if($row->status == 'new'): ?>
                                                <button data-id="<?php echo e($row->id); ?>" type="button" data-toggle="tooltip"
                                                    title="<?php echo app('translator')->get('Nhấn để duyệt đề xuất'); ?>"
                                                    class="btn btn-sm btn-success approve_payment"><?php echo app('translator')->get('Duyệt'); ?></button>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($row->user->name ?? ""); ?>

                                        </td>
                                        <td>
                                            <?php echo e(date("d-m-Y",strtotime($row->created_at))); ?>

                                        </td>
                                        <td> 
                                            <?php if($row->status == 'paid'): ?>
                                                <?php echo e($row->approved_admin->name??""); ?>

                                            <?php endif; ?> 
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Update'); ?>" data-original-title="<?php echo app('translator')->get('Update'); ?>"
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
                                            <a class="btn btn-sm btn-info" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Update'); ?>" data-original-title="<?php echo app('translator')->get('Update'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>">
                                                <i class="fa fa-eye">Xem chi tiết</i>
                                            </a>
                                        </td>
                                    </tr>
                                </form>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.approve_payment').click(function(e) {
            
            if (confirm('Bạn có chắc chắn muốn duyệt đề nghị thanh toán này ?')) {
                let _id = $(this).attr('data-id');
                let url = "<?php echo e(route('payment.approve')); ?>/";
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/payment_request/index.blade.php ENDPATH**/ ?>