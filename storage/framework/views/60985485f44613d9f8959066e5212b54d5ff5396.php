

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

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
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <?php if(session('errorMessage')): ?>
            <div class="alert alert-warning alert-dismissible hide-print">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('errorMessage')); ?>

            </div>
        <?php endif; ?>
        <?php if(session('successMessage')): ?>
            <div class="alert alert-success alert-dismissible hide-print">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo e(session('successMessage')); ?>

            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible hide-print">
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
                        <h3 class="box-title text-uppercase"><?php echo app('translator')->get($module_name); ?></h3>
                        <a class="btn btn-sm btn-success pull-right hide-print"
                            href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                        </a>
                        <button class="btn btn-sm btn-warning mr-10 pull-right hide-print" onclick="window.print()"><i
                                class="fa fa-print"></i>
                            <?php echo app('translator')->get('In phiếu order'); ?></button>

                        <?php if($detail->status !== 'out warehouse' && $detail->status !== 'not approved'): ?>
                            <a href="<?php echo e(route('deliver_warehouse.create', ['order_id' => $detail->id])); ?>" target="_blank"
                                rel="noopener noreferrer" class="btn btn-sm btn-primary mr-10 pull-right hide-print">
                                Xuất kho
                                <i class="fa fa-sign-in"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-xs-6">
                                <p>
                                    <?php echo app('translator')->get('Cở sở'); ?>:
                                    <?php echo e($detail->area->name ?? ($detail->warehouse->area->name ?? '')); ?>

                                    / <?php echo e($detail->warehouse->name ?? ''); ?>

                                </p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Kỳ'); ?>: <?php echo e($detail->period ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Tên phiếu'); ?>: <?php echo e($detail->name ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Mã phiếu'); ?>: <?php echo e($detail->code ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Phòng đề xuất'); ?>: <?php echo e($detail->department->name ?? ''); ?></p>
                            </div>

                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Người đề xuất'); ?>: <?php echo e($detail->staff->name ?? ''); ?> (<?php echo e($detail->confirmed=='da_nhan'?'Đã nhận':'Chưa nhận'); ?>)</p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Ngày đề xuất'); ?>:
                                    <?php echo e(\Carbon\Carbon::parse($detail->day_create)->format('d/m/Y') ?? ''); ?></p>
                            </div>
                            <div class="col-xs-6">
                                <p><?php echo app('translator')->get('Trạng thái'); ?>: <?php echo e(__($detail->status)); ?></p>
                            </div>
                            <div class="col-xs-12">
                                <p><?php echo e($detail->json_params->note ?? ''); ?></p>
                            </div>
                            <?php if(isset($detail->orderDetails)): ?>
                                <div class="col-md-12">
                                    <table id="myTable" class="table table-hover table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width:50px"><?php echo app('translator')->get('STT'); ?></th>
                                                <th class="text-center"><?php echo app('translator')->get('Sản phẩm'); ?></th>
                                                <th class="text-center" style="width:100px"><?php echo app('translator')->get('ĐVT'); ?></th>
                                                <th class="text-center" style="width:75px"><?php echo app('translator')->get('Số lượng'); ?></th>
                                                <th class="text-center" style="width:100px"><?php echo app('translator')->get('Đơn giá'); ?></th>
                                                <th class="text-center"><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                                <th class="text-center"><?php echo app('translator')->get('Bộ phận '); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-order">
                                            <?php $__currentLoopData = $detail->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="valign-middle">
                                                    <td class="text-center">
                                                        <?php echo e($loop->index + 1); ?>

                                                    </td>
                                                    <td>
                                                        <?php echo e($row->product->name ?? ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <p class="unit"><?php echo e($row->product->unit ?? ''); ?></p>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($row->quantity); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : ''); ?>


                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e(isset($row->subtotal_money) && is_numeric($row->subtotal_money) ? number_format($row->subtotal_money, 0, ',', '.') : ''); ?>

                                                    </td>
                                                    <td class="text-center">
                                                        <?php echo e($row->departmentInfor->name ?? ''); ?>

                                                    </td>

                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tr>
                                            <td colspan="5">
                                                <strong class="pull-right">TỔNG TIỀN:</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong
                                                    class="total_money"><?php echo e(isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') . ' đ' : ''); ?></strong>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12 show-print">
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    <?php echo app('translator')->get('Phòng HCNS'); ?>
                                </div>
                                <div class="col-xs-6 text-center text-bold text-uppercase">
                                    <?php echo app('translator')->get('Người đề nghị'); ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer hide-print">
                        <a class="btn btn-sm btn-success" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                            <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                        </a>
                        <?php if($detail->status == 'not approved'): ?>
                            <button data-id="<?php echo e($detail->id); ?>" type="button"
                                class= "approve_order btn btn-info btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get('Duyệt phiếu'); ?>
                            </button>
                        <?php else: ?>
                            <button type="button" class= "btn btn-danger btn-sm pull-right">
                                <i class="fa fa-save"></i> <?php echo app('translator')->get($detail->status); ?>
                            </button>
                        <?php endif; ?>
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
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_order/show.blade.php ENDPATH**/ ?>