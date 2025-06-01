

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
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
                        <a class="btn btn-sm btn-primary pull-right hide-print"
                            href="<?php echo e(route('warehouse_reimburse.index')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('Danh sách phiếu'); ?>
                        </a>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Tên phiếu hoàn trả'); ?>: <?php echo e($detail->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Cơ sở hoàn trả'); ?>: <?php echo e($detail->area->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Kho hoàn trả'); ?>: <?php echo e($detail->warehouse->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Kỳ'); ?>: <?php echo e($detail->period ?? date('Y-m', time())); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Người đề xuất'); ?>: <?php echo e($detail->nguoi_de_xuat->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày đề xuất'); ?>:
                                    <?php echo e($detail->day_create != '' ? date('d-m-Y', strtotime($detail->day_create)) : ''); ?>

                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ghi chú'); ?>: <?php echo e($detail->json_params->note ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày tạo phiếu'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? ''); ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom:10px"><?php echo app('translator')->get('Danh sách tài sản đã hoàn trả'); ?></h4>
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
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_recall/reimburse_show.blade.php ENDPATH**/ ?>