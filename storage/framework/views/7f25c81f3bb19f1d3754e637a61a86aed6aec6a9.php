

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Content Header (Page header) -->
    

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
                        <a class="btn btn-sm btn-primary pull-right hide-print" href="<?php echo e(route('deliver_warehouse')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('Danh sách phiếu'); ?>
                        </a>
                        <button class="btn btn-sm btn-warning pull-right hide-print mr-10" onclick="window.print()"><i
                                class="fa fa-print"></i>
                            <?php echo app('translator')->get('In phiếu'); ?></button>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    <?php echo app('translator')->get('Cở sở'); ?>:
                                    <?php echo e($detail->area->name ?? ($detail->warehouse->area->name ?? ($detail->warehouse_deliver->area->name ?? ''))); ?>

                                    / <?php echo e($detail->warehouse->name ?? ($detail->warehouse_deliver->name ?? '')); ?>

                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Kỳ'); ?>: <?php echo e($detail->period ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Tên phiếu xuất'); ?>: <?php echo e($detail->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Mã phiếu xuất'); ?>: <?php echo e($detail->code ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày xuất'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->day_deliver)->format('d/m/Y') ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày nhận'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->day_entry ?? $detail->day_deliver)->format('d/m/Y') ?? ''); ?>

                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p>
                                    <?php echo app('translator')->get('Xuất theo'); ?>:
                                    <?php if($detail->order_id != ''): ?>
                                        <a target="_blank"
                                            href="<?php echo e(route('warehouse_order_product.show', $detail->order_id)); ?>">
                                            <?php echo e($detail->order_warehouse->code . '-' . $detail->order_warehouse->name ?? ''); ?>

                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php elseif(isset($detail->list_class)): ?>
                                        <a href="<?php echo e(route('book_distribution.detail_history', $detail->id)); ?>"
                                            target="_blank">
                                            <?php echo app('translator')->get('Phiếu phát sách'); ?> -
                                            <?php $__currentLoopData = $detail->list_class; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($i->name); ?>;
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <?php if(isset($detail->department->name) || isset($detail->order_warehouse->department->name)): ?>
                                <div class="col-xs-6">
                                    <p>
                                        <?php echo app('translator')->get('Phòng ban order'); ?>:
                                        <?php echo e($detail->department->name ?? ($detail->order_warehouse->department->name ?? '')); ?>

                                    </p>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($detail->nguoi_giao->name)): ?>
                                <div class="col-xs-6">
                                    <p><?php echo app('translator')->get('Người giao'); ?>: <?php echo e($detail->nguoi_giao->name ?? ''); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if(isset($detail->nguoi_nhan->name)): ?>
                                <div class="col-xs-6">
                                    <p><?php echo app('translator')->get('Người nhận'); ?>: <?php echo e($detail->nguoi_nhan->name ?? ''); ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày tạo phiếu'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y') ?? ''); ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="box-title" style="padding-bottom: 10px"><?php echo app('translator')->get('Danh sách sản phẩm xuất kho'); ?></h4>

                                <table id="myTable" class="table table-hover table-bordered sticky">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                            <th class="text-center" style="width:120px"><?php echo app('translator')->get('Mã sản phẩm'); ?></th>
                                            <th class="text-center"><?php echo app('translator')->get('Sản phẩm'); ?></th>
                                            <th class="text-center" style="width:150px"><?php echo app('translator')->get('Loại tài sản'); ?></th>
                                            <th class="text-center" style="width:75px"><?php echo app('translator')->get('ĐVT'); ?></th>
                                            <th class="text-center" style="width:75px"><?php echo app('translator')->get('Số lượng'); ?></th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('Đơn giá'); ?>
                                            </th>
                                            <th class="text-center" style="width:100px"><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-order">
                                        <?php if($entry_details->count() > 0): ?>
                                            <?php $__currentLoopData = $entry_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->product->code ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($entry_detail->product->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(__($entry_detail->product->warehouse_type ?? '')); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->product->unit ?? ''); ?>

                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->quantity ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(isset($entry_detail->price) && is_numeric($entry_detail->price) ? number_format($entry_detail->price, 0, ',', '.') : ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(isset($entry_detail->subtotal_money) && is_numeric($entry_detail->subtotal_money) ? number_format($entry_detail->subtotal_money, 0, ',', '.') : ''); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tr>
                                        <td colspan="7">
                                            <strong class="pull-right">TỔNG TIỀN:</strong>
                                        </td>
                                        <td>
                                            <strong
                                                class="total_money"><?php echo e(isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . ' đ' : ''); ?></strong>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <?php if($list_assets->count() > 0): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="box-title" style="padding-bottom: 10px"><?php echo app('translator')->get('Danh sách tài sản/CCDC đã xuất tương ứng'); ?> <span
                                            class="change_text_warehouse"></span></h4>

                                    <table class="table table-hover table-bordered sticky">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Mã Tài Sản'); ?></th>
                                                <th class="text-center"><?php echo app('translator')->get('Tên tài sản'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Loại tài sản'); ?></th>
                                                <th class="text-center" style="width:75px"><?php echo app('translator')->get('ĐVT'); ?></th>
                                                <th class="text-center" style="width:75px"><?php echo app('translator')->get('Số lượng'); ?></th>
                                                <th class="text-center" style="width:100px"><?php echo app('translator')->get('Đơn giá'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Phòng ban'); ?></th>
                                                <th class="text-center" style="width:150px"><?php echo app('translator')->get('Vị trí'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order-asset">
                                            <?php $__currentLoopData = $list_assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list_asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->code ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($list_asset->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(__($list_asset->product->warehouse_type) ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($entry_detail->product->unit ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->quantity ?? ''); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e(isset($list_asset->price) && is_numeric($list_asset->price) ? number_format($list_asset->price, 0, ',', '.') : ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->department->name ?? __('Chưa cập nhật')); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($list_asset->position->name ?? __('Chưa cập nhật')); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-12 show-print">
                            <div class="col-xs-4 text-center text-bold text-uppercase">
                                <?php echo app('translator')->get('Kế toán'); ?>
                            </div>
                            <div class="col-xs-4 text-center text-bold text-uppercase">
                                <?php echo app('translator')->get('Người giao'); ?>
                            </div>
                            <div class="col-xs-4 text-center text-bold text-uppercase">
                                <?php echo app('translator')->get('Người nhận'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer hide-print hidden">
                        <a class="btn btn-success" href="<?php echo e(route('deliver_warehouse')); ?>">Danh sách</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_deliver/show.blade.php ENDPATH**/ ?>