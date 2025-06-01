

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .mr-5 {
            margin-right: 5px;
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
                                <input type="text" class="form-control" name="keyword" placeholder="<?php echo app('translator')->get('Từ khóa ...'); ?>"
                                    value="<?php echo e(isset($params['keyword']) ? $params['keyword'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Cơ sở'); ?></label>
                                <select name="area_id" class="form-control select2 area_id" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['area_id']) && $params['area_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e($val->code .' - '.$val->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Phòng ban'); ?></label>
                                <select name="department_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['department_id']) && $params['department_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e(__($val->name )); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Người thực hiện'); ?></label>
                                <select name="person_id" class="form-control select2" style="width: 100%;">
                                    <option value=""><?php echo app('translator')->get('Please select'); ?></option>
                                    <?php $__currentLoopData = $persons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($val->id); ?>"
                                            <?php echo e(isset($params['person_id']) && $params['person_id'] == $val->id ? 'selected' : ''); ?>>
                                            <?php echo e(__($val->name)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Từ ngày'); ?></label>
                                <input type="date" class="form-control from_date" name="from_date"
                                    value="<?php echo e(isset($params['from_date']) ? $params['from_date'] : ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo app('translator')->get('Đến ngày'); ?></label>
                                <input type="date" class="form-control to_date" name="to_date"
                                    value="<?php echo e(isset($params['to_date']) ? $params['to_date'] : ''); ?>">
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
                        <?php echo session('errorMessage'); ?>

                    </div>
                <?php endif; ?>
                <?php if(session('successMessage')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo session('successMessage'); ?>

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
                                <th><?php echo app('translator')->get('Kỳ kiểm kê'); ?></th>
                                <th><?php echo app('translator')->get('Cơ sở'); ?></th>
                                <th><?php echo app('translator')->get('Kho'); ?></th>
                                <th><?php echo app('translator')->get('Phòng ban'); ?></th>
                                <th><?php echo app('translator')->get('Người kiểm kê'); ?></th>
                                <th><?php echo app('translator')->get('Ngày kiểm kê'); ?></th>
                                <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                <th><?php echo app('translator')->get('Action'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="valign-middle">
                                    <td>
                                        <?php echo e($loop->index + 1); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->period ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->area->code ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->warehouse->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->departments->name ?? ''); ?>

                                    </td>

                                    <td>
                                        <?php echo e($row->person->name ?? ''); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row->date_received != '' ? date('d/m/Y', strtotime($row->date_received)) : '---'); ?>

                                    </td>
                                    <td>
                                        <?php echo e(__($row->status)); ?>

                                    </td>
                                    <td class="d-flex-wap">
                                        <?php if($row->status == $status_inventory['Approve']): ?>
                                            <a class="btn btn-sm btn-success" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Chi tiết'); ?>" data-original-title="<?php echo app('translator')->get('Chi tiết'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <a class="btn btn-sm btn-success mr-5" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Chi tiết'); ?>" data-original-title="<?php echo app('translator')->get('Chi tiết'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.show', $row->id)); ?>">
                                                <i class="fa fa-info-circle"></i>
                                            </a>
                                            <a class="btn btn-sm btn-warning mr-5" data-toggle="tooltip"
                                                title="<?php echo app('translator')->get('Sửa'); ?>" data-original-title="<?php echo app('translator')->get('Sửa'); ?>"
                                                href="<?php echo e(route(Request::segment(2) . '.edit', $row->id)); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>
                                            <form action="<?php echo e(route(Request::segment(2) . '.destroy', $row->id)); ?>"
                                                method="POST" onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-danger" type="submit"
                                                    data-toggle="tooltip" title="<?php echo app('translator')->get('Delete'); ?>"
                                                    data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>

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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_inventory/index.blade.php ENDPATH**/ ?>