

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($module_name); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .select2.select2-container.select2-container--default {
            width: 100% !important;
        }

        .table-bordered>thead:first-child>tr:first-child>th {
            text-align: center;
            vertical-align: middle;
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
        <div class="box">
            <div class="box-header with-border text-center">
                <h3 class="box-title text-uppercase"><?php echo e($module_name); ?></h3>
                <a class="btn btn-sm btn-success pull-right hide-print" href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                    <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
                </a>
                <button class="btn btn-sm btn-warning pull-right hide-print mr-10" onclick="window.print()"><i
                        class="fa fa-print"></i>
                    <?php echo app('translator')->get('In phiếu mua sắm'); ?></button>
                
                <?php if($detail->status == 'approved'): ?>
                    <a href="<?php echo e(route('entry_warehouse.create', ['order_id' => $detail->id])); ?>" target="_blank"
                        rel="noopener noreferrer" class="btn btn-sm btn-primary mr-10 pull-right hide-print">
                        Nhập kho
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
                        <p><?php echo app('translator')->get('Người đề xuất'); ?>: <?php echo e($detail->staff->name ?? ''); ?></p>
                    </div>
                    <div class="col-xs-6">
                        <p><?php echo app('translator')->get('Ngày đề xuất'); ?>: <?php echo e(\Carbon\Carbon::parse($detail->day_create)->format('d/m/Y') ?? ''); ?></p>
                    </div>
                    <div class="col-xs-6">
                        <p><?php echo app('translator')->get('Trạng thái'); ?>: <?php echo e(__($detail->status)); ?></p>
                    </div>
                    <div class="col-xs-12">
                        <p><?php echo e($detail->json_params->note ?? ''); ?></p>
                    </div>

                    <?php if(isset($list_relateds)): ?>
                        <div class="col-md-12 hide-print">
                            <h4 style="padding-bottom: 10px"><?php echo app('translator')->get('Danh sách đề xuất order đã gắn với phiếu mua sắm này'); ?></h4>

                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                        <th><?php echo app('translator')->get('Kho'); ?></th>
                                        <th><?php echo app('translator')->get('Mã phiếu'); ?></th>
                                        <th><?php echo app('translator')->get('Tên phiếu đề xuất'); ?></th>
                                        <th><?php echo app('translator')->get('Tổng sản phẩm'); ?></th>
                                        <th><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                        <th><?php echo app('translator')->get('Phòng'); ?></th>
                                        <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                        <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                        <th><?php echo app('translator')->get('Người đề xuất'); ?></th>
                                        <th><?php echo app('translator')->get('Tình trạng'); ?></th>
                                        <th><?php echo app('translator')->get('Ngày đề xuất'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($list_relateds->count() > 0): ?>
                                        <?php $__currentLoopData = $list_relateds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="valign-middle">
                                                <td>
                                                    <?php echo e($loop->index + 1); ?>

                                                </td>

                                                <td>
                                                    <?php echo e($row->warehouse->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <a target="_blank" data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết đề xuất'); ?>"
                                                        href="<?php echo e(route('warehouse_order_product.show', $row->id)); ?>"><?php echo e($row->code ?? ''); ?>

                                                        <i class="fa fa-eye hide-print"></i></i></a>
                                                </td>
                                                <td>
                                                    <a target="_blank" data-toggle="tooltip" title="<?php echo app('translator')->get('Chi tiết đề xuất'); ?>"
                                                        href="<?php echo e(route('warehouse_order_product.show', $row->id)); ?>"><?php echo e($row->name ?? ''); ?>

                                                        <i class="fa fa-eye hide-print"></i></a>
                                                </td>
                                                <td><?php echo e($row->orderDetails->sum('quantity') ?? ''); ?></td>
                                                <td><?php echo e(number_format($row->orderDetails->sum(fn($item) => $item['price'] * $item['quantity']), 0, ',', '.') . ' đ'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->department->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(__($row->status)); ?>

                                                </td>
                                                <td><?php echo e($row->json_params->note ?? ''); ?></td>
                                                <td>
                                                    <?php echo e($row->staff->name ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->confirmred == 'da_nhan' ? 'Đã nhận' : 'Chưa nhận'); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->day_create != '' ? date('d-m-Y', strtotime($row->day_create)) : 'Chưa cập nhật'); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    <?php if(isset($detail->orderDetails) && count($detail->orderDetails) > 0): ?>
                        <div class="col-md-12">
                            <h4 style="padding-bottom: 10px" class="hide-print"><?php echo app('translator')->get('Tổng hợp sản phẩm trong phiếu'); ?></h4>

                            <table class="table table-bordered sticky">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('STT'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Sản phẩm'); ?></th>
                                        <th class="text-center" rowspan="2"><?php echo app('translator')->get('Loại sản phẩm'); ?></th>
                                        <th class="text-center" style="width:75px" rowspan="2"><?php echo app('translator')->get('ĐVT'); ?></th>
                                        <th class="text-center" style="width:135px" rowspan="2">
                                            <?php echo app('translator')->get('Đơn giá'); ?><br />(Dự kiến)</th>
                                        <th class="text-center" style="width:135px" rowspan="2"><?php echo app('translator')->get('Đơn giá'); ?></th>
                                        <?php if(isset($department)): ?>
                                            <th class="text-center" colspan="<?php echo e($department->count() + 1 ?? 1); ?>">
                                                <?php echo app('translator')->get('Số lượng order'); ?></th>
                                        <?php endif; ?>
                                        <th class="text-center" style="width:100px" rowspan="2"><?php echo app('translator')->get('Tồn kho (Trước kỳ)'); ?></th>
                                        <th class="text-center" style="width:100px" rowspan="2"><?php echo app('translator')->get('SL mua'); ?></th>
                                        <th class="text-center" style="width:100px" rowspan="2"><?php echo app('translator')->get('Tổng tiền'); ?></th>
                                        <th class="text-center" style="width:100px" rowspan="2"><?php echo app('translator')->get('SL nhập kho'); ?></th>
                                        <th class="text-center" style="width:100px" rowspan="2"><?php echo app('translator')->get('SL xuất kho'); ?></th>
                                        

                                    </tr>
                                    <tr>
                                        <?php if(isset($department)): ?>
                                            <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th class="text-center" style="width:75px"><?php echo e(__($dep->code)); ?></th>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <th class="text-center" style="width:75px">Tổng</th>
                                        <?php endif; ?>
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
                                                <?php echo e(__($row->product->warehouse_type ?? '')); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e($row->product->unit ?? ''); ?>

                                            </td>

                                            <td class="text-center">
                                                <?php echo e(isset($row->product->price) && is_numeric($row->product->price) ? number_format($row->product->price, 0, ',', '.') : ''); ?>

                                            </td>
                                            <td class="text-center">
                                                <?php echo e(isset($row->price) && is_numeric($row->price) ? number_format($row->price, 0, ',', '.') : ''); ?>

                                            </td>
                                            <?php if(isset($department)): ?>
                                                <?php
                                                    $total_all_dep = 0;
                                                ?>
                                                <?php $__currentLoopData = $department; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $quatity_now =
                                                            $row->quantity_by_department[$dep->id]['quantity'] ?? 0;
                                                        $quatity_prev =
                                                            $row->quantity_by_department[$dep->id]['prev'] ?? 0;
                                                        $total_all_dep += $quatity_now;
                                                        $icon = '';
                                                        if ($quatity_now > $quatity_prev) {
                                                            $icon =
                                                                '<i class="fa fa-arrow-up text-success pull-right"></i>';
                                                        } elseif ($quatity_now < $quatity_prev) {
                                                            $icon =
                                                                '<i class="fa fa-arrow-down text-danger pull-right"></i>';
                                                        } elseif ($quatity_now > 0 && $quatity_now == $quatity_prev) {
                                                            $icon =
                                                                '<i class="fa fa-exchange text-warning pull-right"></i>';
                                                        }

                                                    ?>
                                                    <td>
                                                        <?php echo e($quatity_now); ?>

                                                        <?php echo $icon; ?>

                                                    </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <?php echo e($total_all_dep); ?>

                                                </td>
                                            <?php endif; ?>

                                            <td class="text-center"><?php echo e($row->ton_kho_truoc_ky); ?></td>
                                            <td class="text-center"><?php echo e($row->quantity); ?></td>
                                            <td>
                                                <?php echo e(isset($row->subtotal_money) && is_numeric($row->subtotal_money) ? number_format($row->subtotal_money, 0, ',', '.') : ''); ?>

                                            </td>
                                            <td class="text-center"><?php echo e($row->total_entry); ?></td>
                                            <td class="text-center"><?php echo e($row->total_deliver); ?></td>
                                            
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-right text-bold"
                                            colspan="<?php echo e(isset($department) ? $department->count() + 9 : 8); ?>">TỔNG
                                            TIỀN:</td>
                                        <td class="text-bold" colspan="4">
                                            <?php echo e(isset($detail->total_money) && is_numeric($detail->total_money) ? number_format($detail->total_money, 0, ',', '.') : ''); ?>

                                        </td>
                                    </tr>
                                </tbody>

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
                        <i class="fa fa-save"></i> <?php echo e($detail->admin_approved->name??''); ?> - <?php echo app('translator')->get('Đã duyệt'); ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="modal fade modal_payment_request" data-backdrop="static" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Thông tin phiếu in
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="box-body">
                                <form role="form" id="printForm" action="" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($detail->id); ?>">
                                    <div class="d-flex-wap">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Nội dung'); ?> <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="payment_content"
                                                    placeholder="<?php echo app('translator')->get('Nội dung'); ?>" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Người nhận'); ?> <small class="text-red">*</small></label>
                                                <input type="text" class="form-control" name="payment_stk"
                                                    value="" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="money"><?php echo app('translator')->get('Số tiền tạm ứng'); ?></label>
                                                <input type="text" class="form-control" name="money"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vat8"><?php echo app('translator')->get('VAT 8%'); ?></label>
                                                <input type="text" class="form-control" name="vat8"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vat10"><?php echo app('translator')->get('VAT 10%'); ?></label>
                                                <input type="text" class="form-control" name="vat10"
                                                    value="">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label><?php echo app('translator')->get('Ghi chú'); ?></label>
                                                <textarea name="note" class="form-control" cols="5" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-primary print_payment">
                                In thông tin
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                Đóng
                            </button>
                        </div>
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
                let url = "<?php echo e(route('warehouse_order_buy.approve')); ?>/";
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

        $('.print_payment').click(function(e) {
            e.preventDefault();
            if (!this.checkValidity()) {
                this.reportValidity(); // Hiển thị thông báo lỗi nếu không hợp lệ
                return;
            }
            var formData = $('#printForm').serialize();
            $.ajax({
                url: "<?php echo e(route('warehouse_order_product_buy.print_payment_request')); ?>",
                type: "POST",
                data: formData,
                success: function(response) {
                    const printWindow = window.open('', '_blank', 'width=800,height=600');
                    printWindow.document.open();
                    printWindow.document.write(response);
                    printWindow.document.close();

                    // In nội dung trong popup
                    printWindow.onload = function() {
                        printWindow.print();
                        printWindow.onafterprint = function() {
                            printWindow.close(); // Đóng popup sau khi in
                        };
                    };
                },
                error: function(response) {
                    let errors = response.responseJSON.message;
                    alert(errors);
                }
            });
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/warehouse_order_buy/show.blade.php ENDPATH**/ ?>