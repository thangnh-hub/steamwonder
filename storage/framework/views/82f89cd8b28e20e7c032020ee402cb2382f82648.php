

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo app('translator')->get($module_name); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo app('translator')->get('Thông tin thu hồi tài sản'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="d-flex-wap">
                            <div class="col-md-12">
                                <p><?php echo app('translator')->get('Tên phiếu thu hồi'); ?>: <?php echo e($detail->name ?? ''); ?></p>
                                <p><?php echo app('translator')->get('Kỳ'); ?>: <?php echo e($detail->period ?? date('Y-m', time())); ?></p>
                                <p><?php echo app('translator')->get('Người đề xuất'); ?>: <?php echo e($detail->nguoi_de_xuat->name ?? ''); ?></p>
                                <p><?php echo app('translator')->get('Ngày đề xuất'); ?>:
                                    <?php echo e($detail->day_create != '' ? date('d-m-Y', strtotime($detail->day_create)) : ''); ?>

                                </p>
                                <p><?php echo app('translator')->get('Ghi chú'); ?>: <?php echo e($detail->json_params->note ?? ''); ?></p>
                                <p><?php echo app('translator')->get('Ngày tạo phiếu'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo app('translator')->get('Thông tin tài sản đã thu hồi'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="d-flex-wap">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p><?php echo app('translator')->get('Cơ sở thu hồi'); ?>: <?php echo e($detail->area->name ?? ''); ?></p>
                                </div>
                                <div class="form-group">
                                    <p><?php echo app('translator')->get('Kho thu hồi'); ?>: <?php echo e($detail->warehouse->name ?? ''); ?></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr>
                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                            <th><?php echo app('translator')->get('Mã Tài Sản'); ?></th>
                                            <th><?php echo app('translator')->get('Tên tài sản'); ?></th>
                                            <th><?php echo app('translator')->get('Kho'); ?></th>
                                            <th><?php echo app('translator')->get('Vị trí'); ?></th>
                                            <th><?php echo app('translator')->get('Phòng ban'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order-asset">
                                        <?php if(isset($list_asset)): ?>
                                            <?php $__currentLoopData = $list_asset; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td><?php echo e($loop->index + 1); ?></td>
                                                    <td>
                                                        <p><?php echo e($asset->code ?? ''); ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?php echo e($asset->name ?? ''); ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?php echo e($asset->warehouse->name ?? ''); ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?php echo e($asset->position->name ?? ''); ?></p>
                                                    </td>
                                                    <td>
                                                        <p><?php echo e($asset->department->name ?? ''); ?></p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_recall/show.blade.php ENDPATH**/ ?>