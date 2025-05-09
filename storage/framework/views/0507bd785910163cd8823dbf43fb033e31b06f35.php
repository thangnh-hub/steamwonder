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
            <a class="btn btn-success pull-right " href="<?php echo e(route(Request::segment(2) . '.index')); ?>">
                <i class="fa fa-bars"></i> <?php echo app('translator')->get('List'); ?>
            </a>
            <a class="btn btn-warning pull-right mr-10" target="_blank" href="<?php echo e(route(Request::segment(2) . '.print', $detail->id)); ?>">
                <i class="fa fa-print"></i> <?php echo app('translator')->get('In TBP'); ?>
            </a>
        </h1>

    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                
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

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                        <div class="custom-scroll table-responsive">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th>Mã học sinh</th>
                                        <th>Họ tên</th>
                                        <th>Nickname</th>
                                        <th>Ngày sinh</th>
                                        <th>Địa chỉ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b><?php echo e($detail->student->student_code ?? ''); ?></b></td>
                                        <td><b><?php echo e($detail->student->first_name ?? ''); ?>

                                                <?php echo e($detail->student->last_name ?? ''); ?></b></td>
                                        <td><?php echo e($detail->student->nickname ?? ''); ?></td>
                                        <td><?php echo e(isset($detail->student->birthday) && $detail->student->birthday != '' ? date('d-m-Y', strtotime($detail->student->birthday)) : ''); ?>

                                        </td>
                                        <td><?php echo e($detail->student->address ?? ''); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="custom-scroll table-responsive">
                            <form method="post"
                                action="<?php echo e(route(Request::segment(2) . '.update_json_explanation', $detail->id)); ?>"
                                id="form_update_explanation">
                                <?php echo csrf_field(); ?>
                                <table class="table table-bordered table-hover no-footer no-padding">
                                    <thead>
                                        <tr>
                                            <th colspan="7" class="text-left"><b>1. Số dư kỳ trước - <small>(+) Có /
                                                        (-) Nợ</small> </b>
                                            </th>
                                            <th class="text-right">
                                                <input type="number" name="prev_balance"
                                                    class="form-control pull-right prev_balance" style="max-width: 200px;"
                                                    placeholder="Nhập số dư kỳ trước"
                                                    value="<?php echo e((int) $detail->prev_balance); ?>">
                                            </th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <?php if(isset($detail->prev_receipt_detail) && count($detail->prev_receipt_detail) > 0): ?>
                                            <tr>
                                                <th>Tháng</th>
                                                <th>Dịch vụ</th>
                                                <th>Đơn giá</th>
                                                <th>Số lượng </th>
                                                <th>Tạm tính </th>
                                                <th>Tiền giảm</th>
                                                <th>Truy thu (+) / Hoàn trả (-)</th>
                                                <th>Tổng tiền</th>
                                            </tr>
                                            <?php $__currentLoopData = $detail->prev_receipt_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_prev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e(date('d-m-Y', strtotime($item_prev->month))); ?></td>
                                                    <td><?php echo e($item_prev->service->name ?? ''); ?></td>
                                                    <td><?php echo e(number_format($item_prev->unit_price, 0, ',', '.') ?? ''); ?></td>
                                                    <td><?php echo e(number_format($item_prev->spent_number, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                    <td><?php echo e(number_format($item_prev->amount, 0, ',', '.') ?? ''); ?></td>
                                                    <td><?php echo e(number_format($item_prev->discount_amount, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                    <td><?php echo e(number_format($item_prev->adjustment_amount, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                    <td><?php echo e(number_format($item_prev->final_amount, 0, ',', '.') ?? ''); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tbody class="box_explanation">
                                        <?php if(isset($detail->json_params->explanation)): ?>
                                            <?php $__currentLoopData = $detail->json_params->explanation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="item_explanation">
                                                    <td colspan="6">
                                                        <input type="text"
                                                            name="explanation[<?php echo e($key); ?>][content]"
                                                            class="form-control action_change" value="<?php echo e($item->content); ?>"
                                                            placeholder="Nội dung Truy thu/Hoàn trả">
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                            name="explanation[<?php echo e($key); ?>][value]"
                                                            class="form-control action_change" value="<?php echo e($item->value); ?>"
                                                            placeholder="Giá trị tương ứng">
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-danger" type="button"
                                                            data-toggle="tooltip" onclick="$(this).closest('tr').remove()"
                                                            title="<?php echo app('translator')->get('Delete'); ?>"
                                                            data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </form>
                            <button class="btn btn-warning btn_explanation mt-10"><?php echo app('translator')->get('Thêm giải trình'); ?></button>
                        </div>
                        <div class="custom-scroll table-responsive mt-15">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-left"><b>2. Phí dự kiến</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Du kien thu thang nay -->

                                    <?php if(isset($detail->receiptDetail) && count($detail->receiptDetail) > 0): ?>
                                        <tr>
                                            <th>Tháng</th>
                                            <th>Dịch vụ</th>
                                            <th>Đơn giá</th>
                                            <th>Số lượng</span></th>
                                            <th>Tạm tính</th>
                                            <th>Giảm trừ</th>
                                            
                                            <th>Tổng tiền</th>
                                        </tr>
                                        <?php $__currentLoopData = $detail->receiptDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(date('m-Y', strtotime($item->month))); ?></td>
                                                <td><?php echo e($item->services_receipt->name ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->unit_price, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->by_number, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->amount, 0, ',', '.') ?? ''); ?></td>
                                                <td><?php echo e(number_format($item->discount_amount, 0, ',', '.') ?? ''); ?></td>
                                                
                                                <td><?php echo e(number_format($item->final_amount, 0, ',', '.') ?? ''); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <form method="post" action="<?php echo e(route(Request::segment(2) . '.payment', $detail->id)); ?>"
                            onsubmit="return confirm('<?php echo app('translator')->get('confirm_action'); ?>')">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id" value="<?php echo e($detail->id); ?>">
                            <table class="table table-bordered table-hover no-footer no-padding">
                                <tbody>
                                    <tr>
                                        <td><?php echo app('translator')->get('Mã TBP'); ?></td>
                                        <td class="text-right"> <?php echo e($detail->receipt_code); ?> </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Tên TBP'); ?></td>
                                        <td class="text-right"> <?php echo e($detail->receipt_name); ?> </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Trạng thái thanh toán'); ?></td>
                                        <td class="text-right"><span
                                                class="label <?php echo e($detail->status == 'pending' ? 'label-warning' : 'label-success'); ?>"><?php echo e(__($detail->status)); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php echo app('translator')->get('Tổng tiền'); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo e(number_format($detail->total_amount, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Tổng giảm trừ'); ?></td>
                                        <td class="text-right">
                                            <?php echo e(number_format($detail->total_discount, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Số dư kỳ trước'); ?></td>
                                        <td class="text-right total_prev_balance" data-total="<?php echo e($detail->prev_balance); ?>">
                                            <?php echo e(number_format($detail->prev_balance, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td><?php echo app('translator')->get('Tổng tiền thực tế sau đối soát tất cả dịch vụ'); ?></td>
                                        <td class="text-right total_final" data-final="<?php echo e($detail->total_final); ?>">
                                            <?php echo e(number_format($detail->total_final + $detail->prev_balance, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Đã thu'); ?></td>
                                        <td class="text-right">
                                            <input type="number" name="total_paid" class="form-control text-right"
                                                value="<?php echo e((int) $detail->total_paid ?? 0); ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Số tiền còn phải thu (+) hoặc thừa (-)'); ?></td>
                                        <td class="text-right">
                                            <?php echo e(number_format($detail->total_due + $detail->prev_balance, 0, ',', '.') ?? ''); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo app('translator')->get('Hạn thanh toán'); ?></td>
                                        <td class="text-right">
                                            <input type="date" name="payment_deadline" class="form-control"
                                                value="<?php echo e($detail->json_params->payment_deadline ?? ''); ?>">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-usd" aria-hidden="true" title="Thanh toán"></i> <?php echo app('translator')->get('Xác nhận thanh toán'); ?>
                                </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        $('.prev_balance').keyup(function() {
            var _balance = parseInt($(this).val(), 10);
            if (isNaN(_balance)) {
                _balance = 0;
            }
            // var _total_prev_balance = parseInt($('.total_prev_balance').data('total'));
            var _total_final = parseInt($('.total_final').data('final'), 10);

            var _total = _total_final + _balance;
            $('.total_prev_balance').html(new Intl.NumberFormat('vi-VN').format(_balance));
            $('.total_final').html(new Intl.NumberFormat('vi-VN').format(_total));
        })
        $('.btn_explanation').click(function() {
            var currentDateTime = Math.floor(Date.now() / 1000);

            var _html = `
            <tr class="item_explanation">
                <td colspan="6">
                    <input type="text" name="explanation[${currentDateTime}][content]" class="form-control action_change"
                        placeholder="Nội dung Truy thu/Hoàn trả">
                </td>
                <td>
                    <input type="number" name="explanation[${currentDateTime}][value]" class="form-control action_change"
                        placeholder="Giá trị tương ứng">
                </td>
                <td>
                    <button class="btn btn-sm btn-danger" type="button" data-toggle="tooltip"
                    onclick="$(this).closest('tr').remove()"
                        title="<?php echo app('translator')->get('Delete'); ?>" data-original-title="<?php echo app('translator')->get('Delete'); ?>">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            `;
            $('.box_explanation').append(_html);
        })

        $(document).on('change', '.action_change', function() {
            updateJsonExplanation();
        })

        $('#form_update_explanation').on('submit', function(event) {
            event.preventDefault();
            updateJsonExplanation();
        });

        function updateJsonExplanation() {
            var _url = $('#form_update_explanation').prop('action')
            var formData = $('#form_update_explanation').serialize();
            $.ajax({
                type: "POST",
                url: _url,
                data: formData,
                success: function(response) {
                    console.log(response.data);
                },
                error: function(data) {
                    var errors = data.responseJSON.message;
                    alert(data);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\steamwonder\resources\views/admin/pages/receipt/show.blade.php ENDPATH**/ ?>