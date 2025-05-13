<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Báo Thu Phí</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .table-bordered thead th {
            text-align: center
        }

        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            background-color: #d2e4f5;
            text-transform: uppercase;
        }

        .total {
            font-weight: bold;
            background-color: #7ca7d2;
            text-transform: uppercase;
        }

        .footer {
            font-size: 14px;
            display: flex;
        }

        .bank-info {
            width: 70%;
        }

        .qr-code {
            width: 30%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header"><?php echo app('translator')->get('THÔNG BÁO THU PHÍ'); ?></div>

        <div class="row">
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Họ và tên'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->first_name ?? ''); ?> <?php echo e($detail->student->last_name ?? ''); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Lớp học'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->currentClass->name ?? ''); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Ngày sinh'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->birthday && optional($detail->student)->birthday ? \Carbon\Carbon::parse(optional($detail->student)->birthday)->format('d/m/Y') : ''); ?>

                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Mã học sinh'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <p><?php echo e($detail->student->student_code); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label"><?php echo app('translator')->get('Tên phụ huynh'); ?>:</label>
                    </div>
                    <div class="col-xs-8 col-sm-8 ">
                        <?php if(isset($detail->student->studentParents)): ?>
                            <?php $__currentLoopData = $detail->student->studentParents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p><?php echo e($item->relationship->title ?? ''); ?>: <?php echo e($item->parent->first_name ?? ''); ?>

                                    <?php echo e($item->parent->last_name ?? ''); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr class="total">
                    <th class="text-center">STT</th>
                    <th>Chi tiết các khoản phí</th>
                    <th>Số tiền</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>

            <?php
                $i = 1;
            ?>
            <tbody>
                
                <?php if(count($serviceYearly) > 0): ?>
                    <tr class="section-title">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('Các khoản thu đầu năm'); ?></td>
                        <td><?php echo e(number_format($serviceYearly['total_amount'] ?? 0, 0, ',', '.')); ?></td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceYearly['services']) && count($serviceYearly['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceYearly['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?></td>
                                <td>Từ: <?php echo e(\Carbon\Carbon::parse($item['min_month'])->format('m-Y') ?? ''); ?> -
                                    Đến: <?php echo e(\Carbon\Carbon::parse($item['max_month'])->format('m-Y') ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                
                <?php if(count($serviceMonthly) > 0): ?>
                    <tr class="section-title">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('Các khoản thu theo kỳ'); ?></td>
                        <td class="text-right"><?php echo e(number_format($serviceMonthly['total_amount'] ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceMonthly['services']) && count($serviceMonthly['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceMonthly['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?>

                                </td>
                                <td>Từ: <?php echo e(\Carbon\Carbon::parse($item['min_month'])->format('m-Y') ?? ''); ?> -
                                    Đến: <?php echo e(\Carbon\Carbon::parse($item['max_month'])->format('m-Y') ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                
                <?php if(count($serviceOther) > 0): ?>
                    <tr class="section-title">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('Các khoản thu phí khác'); ?></td>
                        <td class="text-right"><?php echo e(number_format($serviceOther['total_amount'] ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($serviceOther['services']) && count($serviceOther['services']) > 0): ?>
                        <?php $__currentLoopData = $serviceOther['services']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item['total_amount'] ?? 0, 0, ',', '.')); ?>

                                </td>
                                <td>Từ: <?php echo e(\Carbon\Carbon::parse($item['min_month'])->format('m-Y') ?? ''); ?> -
                                    Đến: <?php echo e(\Carbon\Carbon::parse($item['max_month'])->format('m-Y') ?? ''); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>

                <?php if(count($listServiceDiscoun) > 0): ?>
                    <tr class="section-title">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('Các khoản truy thu/ Hoàn trả (+)Có/(-)Nợ'); ?></td>
                        <td class="text-right">
                            <?php echo e(number_format($listServiceDiscoun->sum('total_discount_amount') ?? 0, 0, ',', '.')); ?>

                        </td>
                        <td></td>
                    </tr>
                    <?php if(isset($listServiceDiscoun) && count($listServiceDiscoun) > 0): ?>
                        <?php $__currentLoopData = $listServiceDiscoun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item['service']->name ?? ''); ?></td>
                                <td class="text-right">
                                    <?php echo e(number_format($item['total_discount_amount'] ?? 0, 0, ',', '.')); ?></td>
                                <td><?php echo $item['note']; ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>

                <?php if($detail->prev_balance != 0 || (isset($detail->json_params->explanation) && count((array) $detail->json_params->explanation) > 0)): ?>
                    <tr class="section-title">
                        <td class="text-center"><?php echo e(\App\Helpers::intToRoman($i)); ?></td>
                        <td><?php echo app('translator')->get('Khoản giải trình'); ?></td>
                        <td class="text-right"><?php echo e(number_format($detail->prev_balance ?? 0, 0, ',', '.')); ?></td>
                        <td></td>
                    </tr>
                    <?php if(isset($detail->json_params->explanation) && count((array) $detail->json_params->explanation) > 0): ?>
                        <?php $__currentLoopData = $detail->json_params->explanation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->index + 1); ?></td>
                                <td><?php echo e($item->content ?? ''); ?></td>
                                <td class="text-right"><?php echo e(number_format($item->value ?? 0, 0, ',', '.')); ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <?php $i++; ?>
                <?php endif; ?>
                <tr class="total">
                    <td colspan="2"><?php echo app('translator')->get('TỔNG PHẢI NỘP'); ?> </td>
                    <td class="text-right">
                        <?php echo e(number_format($detail->total_final, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2"><?php echo app('translator')->get('TỔNG SỐ TIỀN ĐÃ NỘP'); ?></td>
                    <td class="text-right"><?php echo e(number_format($detail->total_paid ?? 0, 0, ',', '.')); ?></td>
                    <td></td>
                </tr>
                <tr class="total">
                    <td colspan="2"><?php echo app('translator')->get('TỔNG SỐ TIỀN CÒN PHẢI NỘP'); ?></td>
                    <td class="text-right"><?php echo e(number_format($detail->total_due, 0, ',', '.')); ?>

                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="bank-info">
                <p><strong>Hình thức thanh toán:</strong></p>
                <p>Thanh toán bằng chuyển khoản, Quý Phụ huynh vui lòng chuyển tiền vào tài khoản sau:</p>
                <p><strong>Tên TK:</strong> <?php echo e(optional($detail->area)->json_params->bank_account ?? ''); ?></p>
                <p><strong>Số TK:</strong> <?php echo e(optional($detail->area)->json_params->bank_stk ?? ''); ?> -
                    <?php echo e(optional($detail->area)->json_params->bank_name ?? ''); ?></p>
                <p><strong>Nội dung chuyển khoản:</strong> Mã học sinh_Tên học sinh_Ngày sinh</p>
                <p>* Thanh toán tiền mặt: chi trả bằng tiền mặt tại Phòng Tuyển sinh</p>
            </div>
            <?php if(isset($qrCode)): ?>
                <div class="qr-code">
                    <p style="text-align: center"><img src="<?php echo e($qrCode); ?>" alt="QR Ngân hàng" width="250"></p>
                    <p><?php echo app('translator')->get('Vui lòng quét mã QR để thanh toán'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mở hộp thoại in
            window.print();
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\steamwonders\resources\views/admin/pages/receipt/print.blade.php ENDPATH**/ ?>