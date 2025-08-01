

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
       .d-flex {
            display: flex;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
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

        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            <?php echo app('translator')->get($module_name); ?>
                        </h3>
                        <a class="btn btn-sm btn-primary pull-right hide-print" href="<?php echo e(route('entry_warehouse')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('Danh sách phiếu'); ?>
                        </a>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    <?php echo app('translator')->get('Cở sở'); ?>:
                                    <?php echo e($detail->area->name); ?>

                                </p>
                            </div>
                           
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Tên phiếu'); ?>: <?php echo e($detail->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Mã phiếu'); ?>: <?php echo e($detail->code ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Người tạo phiếu'); ?>: <?php echo e($detail->admin_created->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày tạo phiếu'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? ''); ?></p>
                            </div>

                            <?php if(isset($detail->json_params->note) && $detail->json_params->note != ''): ?>
                                <div class="col-xs-6">
                                    <p><?php echo app('translator')->get('Ghi chú'); ?>: <?php echo e($detail->json_params->note ?? ''); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom:10px"><?php echo app('translator')->get('Danh sách sản phẩm nhập kho'); ?></h4>
                                <table id="myTable" class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr class="valign-middle">
                                            <th class="text-center" style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                            <th class="text-center"><?php echo app('translator')->get('Sản phẩm'); ?></th>
                                            <th class="text-center" style="width:75px"><?php echo app('translator')->get('ĐVT'); ?></th>
                                            <th class="text-center" style="width:75px"><?php echo app('translator')->get('Số lượng'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order">
                                        <?php if($entry_details->count() > 0): ?>
                                            <?php $__currentLoopData = $entry_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                                    <td>
                                                        <?php echo e($entry_detail->ingredient->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->ingredient->unitDefault->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->quantity ?? ''); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer hide-print">
                        <a class="btn btn-sm btn-success pull-right" target="_blank"
                            href="<?php echo e(route('warehouse_ingredients_entry.index')); ?>">
                            <i class="fa fa-bank"></i> <?php echo app('translator')->get('Danh sách'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\steamwonder\resources\views/admin/pages/meal/warehouse_ingredients_entry/show.blade.php ENDPATH**/ ?>