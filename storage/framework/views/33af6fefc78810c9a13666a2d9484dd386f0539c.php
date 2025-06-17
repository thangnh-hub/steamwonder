<?php $__env->startPush('style'); ?>
    <style>
        .modal-dialog {
            max-width: 80%;
        }

        @media  only screen and (max-width: 576px) {
            .table_receipt {
                min-width: 2000px;
            }

            .modal-dialog {
                max-width: 95%;
            }
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <section class="student">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 information box-day">
                    <div class="box box-warning mb-3">
                        <div class="box-header with-border mb-3">
                            <h4 class="box-title d-flex justify-content-between flex-wrap">
                                <span class="mb-3"><i class="fa fa-calendar-check-o"></i> <?php echo app('translator')->get('Thông tin TBP'); ?>
                                </span>
                                </h3>
                        </div>
                        <div class="box-body ">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered table_receipt">
                                    <thead>
                                        <tr>
                                            <th><?php echo app('translator')->get('STT'); ?></th>
                                            <th><?php echo app('translator')->get('Mã TBP'); ?></th>
                                            <th><?php echo app('translator')->get('Tên TBP'); ?></th>
                                            <th><?php echo app('translator')->get('Mã học sinh'); ?></th>
                                            <th><?php echo app('translator')->get('Tên học sinh'); ?></th>
                                            <th><?php echo app('translator')->get('Lớp'); ?></th>
                                            <th><?php echo app('translator')->get('Thành tiền'); ?></th>
                                            <th><?php echo app('translator')->get('Tổng giảm trừ'); ?></th>
                                            <th><?php echo app('translator')->get('Số dư kỳ trước'); ?></th>
                                            <th><?php echo app('translator')->get('Tổng tiền thực tế'); ?></th>
                                            <th><?php echo app('translator')->get('Trạng thái'); ?></th>
                                            <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="valign-middle">
                                                <td>
                                                    <?php echo e($loop->index + 1); ?>

                                                </td>
                                                <td>
                                                    <strong style="font-size: 14px"><?php echo e($row->receipt_code ?? ''); ?></strong>
                                                </td>
                                                <td>
                                                    <?php echo e($row->receipt_name); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->student->student_code ?? ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e($row->student->first_name ?? ''); ?>

                                                    <?php echo e($row->student->last_name ?? ''); ?>

                                                    (<?php echo e($row->student->nickname ?? ''); ?>)
                                                </td>
                                                <td>
                                                    <?php echo e(optional($row->student->currentClass)->name); ?>

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
                                                    <?php echo e(__($row->status ?? '')); ?>

                                                    <?php if($row->status == 'paid'): ?>
                                                        <br>
                                                        <button class="btn btn_sm btn-success btn_show_tbp"
                                                            data-id = "<?php echo e($row->id); ?>"><?php echo app('translator')->get('Chi tiết'); ?></button>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo e($row->note ?? ''); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="receipt_transaction" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="tbpDetailModalLabel"><?php echo app('translator')->get('Chi tiết thnh toán'); ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo app('translator')->get('STT'); ?></th>
                                        <th><?php echo app('translator')->get('Mã TBP'); ?></th>
                                        <th><?php echo app('translator')->get('Ngày thanh toán'); ?></th>
                                        <th><?php echo app('translator')->get('Số tiền'); ?></th>
                                        <th><?php echo app('translator')->get('Ghi chú'); ?></th>
                                        <th><?php echo app('translator')->get('Người thu'); ?></th>
                                    </tr>
                                </thead>
                                <tbody id="tbpDetailBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script'); ?>
    <script>
        var receipt = <?php echo json_encode($rows, 15, 512) ?>;
        $('.btn_show_tbp').click(function() {
            var id = $(this).data('id');
            var receipt_detail = receipt.find(function(item) {
                return item.id == id;
            });
            var tbpDetailBody = $('#tbpDetailBody');
            tbpDetailBody.empty();
            if (receipt_detail && receipt_detail.receipt_transaction && receipt_detail.receipt_transaction.length >
                0) {
                receipt_detail.receipt_transaction.forEach(function(transaction, index) {
                    tbpDetailBody.append(`
                        <tr class="valign-middle">
                            <td>${index + 1}</td>
                            <td>${receipt_detail.receipt_code ?? ''}</td>
                            <td>${transaction.payment_date ? new Date(transaction.payment_date).toLocaleDateString('vi-VN') : ''}</td>
                            <td>${new Intl.NumberFormat('vi-VN', { style: 'decimal' }).format(transaction.paid_amount) ?? ''}</td>
                            <td>${transaction.json_params.note ?? ''}</td>
                            <td>${transaction.user_cashier.name ?? ''} </td>
                        </tr>
                    `);
                });
            } else {
                tbpDetailBody.append(`
                    <tr class="valign-middle">
                        <td colspan="6" class="text-center"><?php echo app('translator')->get('Không có giao dịch nào'); ?></td>
                    </tr>
                `);
            }
            $('#receipt_transaction').modal('show');
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/frontend/pages/user/receipt.blade.php ENDPATH**/ ?>